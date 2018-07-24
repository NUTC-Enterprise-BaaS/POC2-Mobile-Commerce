<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * main controller class
 *
 * @since  1.0
 */
class SocialadsController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since  1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view = JFactory::getApplication()->input->getCmd('view', 'archivestats');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}

	/**
	 * Single cron URL for running all the functions
	 *
	 * @return  void
	 *
	 * @since  3.1
	 */
	public function saStatisticsCron()
	{
		$com_params = JComponentHelper::getParams('com_socialads');
		$input = JFactory::getApplication()->input;
		$pkey = $input->get('pkey');

		if ($pkey != $com_params->get('cron_key'))
		{
			echo JText::_("COM_SOCIALADS_CRON_KEY_MSG");

			return;
		}

		$func = $input->get('func');

		if ($func)
		{
			$this->$func();
		}
		else
		{
			$funcs = array ('archiveStats','sendStatsEmail');	 /*add the function names you need to add here*/

			foreach ($funcs as $func)
			{
				echo '<br>***************************************<br>';
				$this->$func();
				echo '<br>***************************************<br>';
			}
		}
	}

	/**
	 * Task for archiving stats
	 *
	 * @return  void
	 *
	 * @since  3.1
	 */
	public function archiveStats()
	{
		$input = JFactory::getApplication()->input;
		$com_params = JComponentHelper::getParams('com_socialads');

		if ($com_params->get('archivestat'))
		{
			$pkey = $input->get('pkey');

			if ($pkey != $com_params->get('cron_key'))
			{
				echo JText::_("COM_SOCIALADS_CRON_KEY_MSG");

				return;
			}

			$log = array();
			$log[] = JText::_("COM_SOCIALADS_ARCH_STATS_START");

			$db = JFactory::getDBO();
			$backdate = date('Y-m-d  h:m:s', strtotime(date('Y-m-d h:m:s') . ' - ' . $com_params->get('maintain_stats') . ' days'));
			$log[] = JText::sprintf("COM_SOCIALADS_CRON_FROM_TO", $backdate);

			// $query = " SELECT id,ad_id,display_type,time FROM #__ad_stats WHERE time < '".$backdate."' ORDER BY time";
			$query = $db->getQuery(true);

			// Query to get stats
			$query->select('id, ad_id, display_type, time');
			$query->from($db->quoteName('#__ad_stats'));
			$query->where($db->quoteName('time') . "<'" . $backdate . "'");
			$query->order('time');

			$db->setQuery($query);
			$rawstats = $db->loadObjectList();
			$log[] = JText::sprintf("COM_SOCIALADS_CRON_TOTAL_ENTRY", count($rawstats));

			if (count($rawstats))
			{
				$date = date('Y-m-d', strtotime($rawstats[0]->time));
				$final_stats = array();

				foreach ($rawstats as $raw)
				{
					$current_date = date('Y-m-d', strtotime($raw->time));

					if ($date != $current_date)
					{
						$date = date('Y-m-d', strtotime($raw->time));
					}

					// 0=imprs;1= clks;
					if ($raw->display_type == '0')
					{
						if (isset($final_stats[$date][$raw->ad_id]['imprs']))
						{
							$final_stats[$date][$raw->ad_id]['imprs']++;
						}
						else
						{
							$final_stats[$date][$raw->ad_id]['imprs'] = 1;
						}
					}
					elseif ($raw->display_type == '1')
					{
						if (isset($final_stats[$date][$raw->ad_id]['clks']))
						{
							$final_stats[$date][$raw->ad_id]['clks']++;
						}
						else
						{
							$final_stats[$date][$raw->ad_id]['clks'] = 1;
						}
					}
				}

				/* Jugad start
					$query= "TRUNCATE TABLE `#__ad_archive_stats`";
					$db->setQuery($query);
					$db->execute();
					jugad ends
				*/

				$stats_obj = new stdClass;
				$cnt = 0;

				foreach ($final_stats as $date => $stats)
				{
					foreach ($stats as $id => $v)
					{
						$stats_obj = new stdClass;
						$stats_obj->ad_id = $id;
						$stats_obj->date = $date;

						if (isset($v['imprs']))
						{
							$stats_obj->impression = $v['imprs'];
						}

						if (isset($v['clks']))
						{
							$stats_obj->click = $v['clks'];
						}

						$cnt++;

						if (!$db->insertObject('#__ad_archive_stats', $stats_obj, 'id'))
						{
							echo $db->stderr();

							return false;
						}
					}
				}

				$log[] = JText::sprintf("COM_SOCIALADS_CRON_REDUCE_TO", $cnt);

				// $query = "DELETE FROM #__ad_stats WHERE time < '".$backdate."'";
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ad_stats'));
				$query->where($db->quoteName('time') < $backdate);

				$db->setQuery($query);

				if (!$db->execute())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}

			echo implode('<br/>', $log);
			$logfile_path = JPATH_SITE . "/components/com_socialads/log/archive_stats.txt";
			$old_log_content = file_get_contents($logfile_path);

			if ($old_log_content || $old_log_content == '')
			{
				$file_log = implode("\n",  $log);
				$file_log = $old_log_content . "\n\n" . $file_log;
				JFile::write($logfile_path, $file_log);
			}
		}
	}

	/**
	 * Weekly cron mail
	 *
	 * @return  void
	 *
	 * @since  3.1
	 */
	public function sendStatsEmail()
	{
		$com_params = JComponentHelper::getParams('com_socialads');
		$input = JFactory::getApplication()->input;

		if ($com_params->get('weekly_stats'))
		{
			$pkey = $input->get('pkey');

			if ($pkey != $com_params->get('cron_key'))
			{
				echo JText::_("COM_SOCIALADS_CRON_KEY_MSG");

				return;
			}

			echo JText::_('COM_SOCIALADS_WEEK_STATMAIL_START');
			echo '<br>';
			echo '<br>';
			$adcreators = SaCommonHelper::getAdCreators();

			foreach ($adcreators as $userid)
			{
				$statsforpie = SaCommonHelper::statsForPieInMail($userid);

				if ($statsforpie)
				{
					$userinfo['userid'] = $userid;
					$userinform = SaCommonHelper::getUserDetails($userid, 'username,name,email');
					$body = $com_params->get('intro_text_mail');

					// Replace the intro text from component option
					$find 		= array ('{username}', '{name}');
					$replace	= array($userinform[0]->username, $userinform[0]->name);
					$body		= str_replace($find, $replace, $body);
					$find 		= array ('[SEND_TO_USERNAME]', '[SEND_TO_NAME]');
					$replace	= array($userinform[0]->username, $userinform[0]->name);
					$body		= str_replace($find, $replace, $body);
					$userinfo['email'] = $userinform[0]->email;

					foreach ($statsforpie as $statsforpie_ad)
					{
						if (($statsforpie_ad[0][0]->value) or ($statsforpie_ad[1][0]->value))
						{
							$body .= $this->statsEmailBody($statsforpie_ad, $userinfo);
						}
					}

					$body = nl2br($body);
					$this->sendStatsEmailToUser($body, $userinfo);
				}
			}
		}
	}

	/**
	 * Method to create mail body
	 *
	 * @param   array  $statsforpie  stats for pie
	 * @param   array  $userinfo     User information
	 *
	 * @return  void
	 *
	 * @since  3.1
	 */
	public function statsEmailBody($statsforpie, $userinfo)
	{
		global $mainframe;
		$email = $userinfo['email'];
		$adid = $statsforpie[2];
		$clicks_pie = $imprs_pie = $total_no_ads = 0;
		$mainframe = JFactory::getApplication();
		$ad_data = SaCommonHelper::getAdInfo($adid, 'ad_title');

		$ad_title = ($ad_data[0]->ad_title != '') ? JText::_("COM_SOCIALADS_CRON_AD_TITLE") . ' <b>"'
		. $ad_data[0]->ad_title . '"</b>' : JText::_("COM_SOCIALADS_CRON_AD_TITLE") . ' : <b>' . $adid . '</b>';
		$itemid = SaCommonHelper::getSocialadsItemid('ad');
		$edit_ad_link  = JRoute::_(JUri::base() . "index.php?option=com_socialads&view=adform&adid=" . $adid . "&Itemid=" . $itemid);

		if (isset($statsforpie[1][0]->value))
		{
			$clicks_pie = $statsforpie[1][0]->value;
		}

		if (isset($statsforpie[0][0]->value))
		{
			$imprs_pie = $statsforpie[0][0]->value;
		}

		if ($clicks_pie  || $imprs_pie)
		{
			$cl_impr = $imprs_pie . ',' . $clicks_pie;
			$chco = '7777CC|76A4FB';
			$chdl = 'clicks|Impressions';
			$url = "http://0.chart.apis.google.com/chart?chs=300x150&cht=p3&chd=t:" . $cl_impr . "&chdl=Impressions|Clicks";
		}

		$CTR = 0.00;

		if ($clicks_pie and $imprs_pie)
		{
			$CTR = number_format($clicks_pie / $imprs_pie, 2);
		}

		$body = JText::_('COM_SOCIALADS_PERIDIC_STATS_BODY');
		$timestamp = strtotime("-7 days");
		$find = array('[ADTITLE]','[STARTDATE]','[ENDDATE]','[TOTAL_IMPRS]','[TOTAL_CLICKS]','[CLICK_TO_IMPRS]','[STAT_CHART]',
						'[AD_EIDT_LINK]');
		$replace	= array($ad_title,strftime("%d-%m-%Y", $timestamp), date('d-m-Y'), $imprs_pie, $clicks_pie, $CTR, $url, $edit_ad_link);
		$body		= str_replace($find, $replace, $body);

		if (!$ad_title)
		{
			$body = str_replace('Ad Title', '', $body);
		}

		$body = nl2br($body);

		return $body;
	}

	/**
	 * Method to send stats mail to user
	 *
	 * @param   array  $body      Email body
	 * @param   array  $userinfo  User information
	 *
	 * @return  void
	 *
	 * @since  3.1
	 */
	public function sendStatsEmailToUser($body,$userinfo)
	{
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$sitename = '';
		$subject = JText::_('COM_SOCIALADS_PERIDIC_STATS_SUBJECT');
		$sitename = $mainframe->getCfg('sitename');
		$find = array ('[SITENAME]');
		$replace = array($sitename);
		$body = str_replace($find, $replace, $body);
		$subject = str_replace($find, $replace, $subject);
		$email = $userinfo['email'];
		$mainframe = JFactory::getApplication();
		$from = $mainframe->getCfg('mailfrom');
		$fromname = $mainframe->getCfg('fromname');
		$recipient = $email;
		$mode = 1;
		$cc = null;
		$bcc = null;
		$bcc = null;
		$attachment = null;
		$replyto = null;
		$replytoname = null;

		$return = JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);

		if (isset($return->code) && $return->code == 500)
		{
			echo JText::sprintf('COM_SOCIALADS_MAIL_SENT_FAIL', $recipient);
			echo '<br>';
		}
		elseif ($return)
		{
			echo JText::sprintf('COM_SOCIALADS_MAIL_SENT_SUCCESS', $recipient);
			echo '<br>';
		}

		return;
	}

	/**
	 * Method to call remove unused image
	 *
	 * @return  void
	 *
	 * @since  3.1
	 */
	public function removeimagesCall()
	{
		$this->removeImages(0);
	}

	/**
	 * Method to remove unused images
	 *
	 * @param   integer  $called_from  Default variable
	 *
	 * @return  boolean
	 *
	 * @since  3.1
	 */
	public function removeImages($called_from = 0)
	{
		$input = JFactory::getApplication()->input;

		if ($called_from != 0)
		{
			$pkey = $input->get('pkey');

			if ($pkey != $com_params->get('cron_key'))
			{
				echo JText::_("COM_SOCIALADS_CRON_KEY_MSG");

				return;
			}
		}

		$images_del = array();
		$images = array();
		$current_files = array();
		$results = array();
		$database = JFactory::getDBO();
		$match = $database->escape('images/socialads/');
		$query = "SELECT REPLACE(ad_image, '{$match}','') as ad_image  FROM #__ad_data WHERE ad_image<>''";
		$database->setQuery($query);
		$images_del = $database->loadColumn();

		// We can skip the "frames" folder SAFELY here, it is used for gif resizing
		$current_files = JFolder::files(JPATH_SITE . '/images/socialads', '', 0, 0, array('frames','index.html'));
		$no_files_del = 0;

		if (count($current_files) > count($images_del))
		{
			$results = array_diff($current_files, $images_del);

			if ($results)
			{?>
				<div class="alert alert-info">
					<?php echo JText::_("COM_SOCIALADS_UNUSED_IMAGE_LIST");?>
				</div>
				<?php
				foreach ($results as $img_to_del)
				{
					if ($img_to_del)
					{
						if (!JFile::delete(JPATH_SITE . '/images/socialads/' . $img_to_del))
						{
							if ($called_from == 0)
							{
								echo "[" . $img_to_del . "] " . JText::_("COM_SOCIALADS_FILE_DEL_FAIL");
								echo "<br>";
							}
						}
						else
						{
							if ($called_from == 0)
							{
								echo "<br>";
								echo "[" . $img_to_del . "] " . JText::_("COM_SOCIALADS_FILE_DEL_SUCCESSFULLY");
								$no_files_del++;
							}
						}
					}
				}
			}
			else
			{
				if ($called_from == 0)
				{
					?>
					<div class="alert alert-info">
					<?php
						echo "<br>";
						echo JText::_("COM_SOCIALADS_NO_FILE_DEL");  ?>
					</div >
					<?php
				}
			}
		}
		else
		{
			if ($called_from == 0)
			{ ?>
				<div class="alert alert-info">
					<?php
					echo "<br>";
					echo JText::_("COM_SOCIALADS_NO_FILE_DEL"); ?>
				</div>
			<?php
			}
		}

		if ($called_from == 0)
		{
			if ($no_files_del)
			{ ?>
				<div class="alert alert-success">
				<?php
				echo "<br>";
				echo JText::_("COM_SOCIALADS_NUMBER_OF_FILE_DEL") . ":" . $no_files_del; ?>
				</div>
				<?php
			}
		}

		return;
	}
}
