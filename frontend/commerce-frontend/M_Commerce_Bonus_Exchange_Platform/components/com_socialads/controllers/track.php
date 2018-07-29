<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.controller');

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Campaign controller class.
 *
 * @since  1.6
 */
class SocialadsControllerTrack extends SocialadsController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		return true;

		// parent::display();
	}

	/**
	 * Method to redirect ad
	 *
	 * @return void
	 *
	 * @since 3.0
	 */
	//function adredirector()
	public function redirect()
	{
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$input     = JFactory::getApplication()->input;

		// Get ad id
		// $adid = $input->get('adid', 0, 'INT');
		$adid = $input->get('id', 0, 'INT');

		// require_once JPATH_SITE . '/components/com_socialads/helpers/ads.php';
		// $adRetriever     = new adRetriever();
		// $statue_adcharge = $adRetriever->getAdStatus($adid);
		$statue_adcharge = SaAdEngineHelper::getInstance()->getAdStatus($adid);

		if ($statue_adcharge['status_ads'] == 1)
		{
			$caltype = $input->get('caltype', 0, 'INT');
			$widget  = $input->get('widget', '', 'STRING');
			// $adRetriever->reduceCredits($adid, $caltype, $statue_adcharge['ad_charge'], $widget);
			SaCreditsHelper::reduceCredits($adid, $caltype, $statue_adcharge['ad_charge'], $widget);

			/*START API Trigger*/
			$dispatcher = JDispatcher::getInstance();
			JPluginHelper::importPlugin('system');
			$dispatcher->trigger('onSA_Adclick');
			/*END API Trigger*/

			// Added for added for sa_jbolo integration
			$chatoption = $input->get('chatoption', 0, 'INT');

			if ($chatoption)
			{
				jexit();
			}
			// End added for added for sa_jbolo integration

			// $result = $this->getURL();
			$result = SaAdsHelper::getUrl($adid);
			$mainframe->redirect($result);
		}
	}

		/**
	 * Method to publish records.
	 *
	 * @return void
	 *
	 * @since 3.0
	 */
	//function adredirector()
	public function ignore()
	{
		$input = JFactory::getApplication()->input;

		// $post=$input->post;
		// $input->get

		$database = JFactory::getDBO();
		$my = JFactory::getUser();

		$adid = $input->get('ignore_id', 0, 'INT');
		$fdid = $input->get('feedback', '', 'STRING');

		if ($fdid)
		{
			// Query to find if logged in user has already blocked the same user...
			$qry1 = "UPDATE #__ad_ignore
			 SET ad_feedback ='" . $fdid . "'
			 WHERE userid=" . $my->id . "
			 AND adid=" . $adid;
			$database->setQuery($qry1);
			$database->execute();
		}
		elseif($adid)
		{
			// Query to find if logged in user has already blocked the same user...
			$qry1 = "SELECT userid, adid
			 FROM #__ad_ignore
			 WHERE userid=" . $my->id . " AND adid=" . $adid;
			$database->setQuery($qry1);
			$existing = $database->loadObjectList();

			if (!$existing)
			{
				$data = new stdClass;
				$data->id = NULL;
				$data->userid = $my->id;
				$data->adid = $adid;

				if (!$database->insertObject('#__ad_ignore', $data ))
				{
					echo "0";
				}
				else
				{
					echo "1";
				}
			}
		}
	}

	public function undoIgnore()
	{
		$database = JFactory::getDBO();
		$my = JFactory::getUser();
		$input = JFactory::getApplication()->input;
		$adid = $input->get('ignore_id', 0, 'INT');

		if ($adid)
		{
			// Query to find if logged in user has already blocked the same user...
			$qry1 = "DELETE FROM #__ad_ignore WHERE userid=" . $my->id . " AND adid=" . $adid;
			$database->setQuery($qry1);
			$database->execute();
		}
	}
}
