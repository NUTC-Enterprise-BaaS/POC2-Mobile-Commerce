<?php
/**
 * @version     SVN: <svn_id>
 * @package     JMailAlerts
 * @subpackage  jma_socialads
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/*load language file for plugin frontend*/
$lang = JFactory::getLanguage();
$lang->load('plg_emailalerts_jma_socialads', JPATH_ADMINISTRATOR);

/**
 * Class for JMA Mossets Tree plugin
 *
 * @package     JMailAlerts
 * @subpackage  jma_mosets
 * @since       2.4.4
 */
class PlgEmailalertsjma_Socialads extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param   string  &$subject  subject
	 *
	 * @param   array   $config    plugin config
	 *
	 * @return  null
	 *
	 * @since   2.5.1
	 */
	public function plgEmailalertsSocialads(&$subject, $config)
	{
		parent::__construct($subject, $config);

		if ($this->params === false)
		{
			$jPlugin      = JPluginHelper::getPlugin('emailalerts', 'jma_socialads');
			$this->params = new JParameter($jPlugin->params);
		}
	}

	/**
	 * This retrieves an array of items based on alert preferences passed.
	 * Also it returns HTML and CSS output for fetched items.
	 *
	 * @param   string  $id                 userid or email id for user whom email will be sent
	 * @param   string  $date               timestamp when last email was sent to that user
	 * @param   array   $userparam          array of user's alert preference considering data tags
	 * @param   int     $fetch_only_latest  decide to send only fresh content or not
	 *
	 * @return  array   $areturn  HTML and CSS output for fetched items
	 *
	 * @since  2.5
	 */
	public function onEmail_jma_socialads($id, $date, $userparam, $fetch_only_latest)
	{
		$areturn    = array();
		$areturn[0] = $this->_name;
		$check      = $this->_chkextension();

		if (!($check))
		{
			$areturn[1] = '';
			$areturn[2] = '';

			return $areturn;
		}

		$userparam['alt_ad']      = 1;
		$userparam['ad_rotation'] = 0;

		$input                    = JFactory::getApplication()->input;
		require_once JPATH_ROOT . '/components/com_socialads/helpers/engine.php';
		require_once JPATH_ROOT . '/components/com_socialads/helpers/common.php';
		$html     = '<span>';
		$cssdata  = '';
		$simulate = '';
		$sim_flag = $input->get('flag', 0, 'INT');

		// To check if called from simulate in admin
		if ($sim_flag == 1)
		{
			$simulate = '&amp;simulate=1';
		}

		$adsdata         = array();
		$adsdata         = SaAdEngineHelper::getInstance()->fillslots($userparam);

		if (!empty($adsdata))
		{
			// $random_ads = $adRetriever->getRandomId($adsdata,$userparam);
			$itemid = SaCommonHelper::getSocialadsItemid('adform');

			foreach ($adsdata as $key => $random_ad1)
			{
				foreach ($random_ad1 as $key => $random_ad)
				{
					if ($random_ad->ad_id != -999)
					{
						$addata = SaAdEngineHelper::getInstance()->getAdDetails($random_ad);
					}
					else
					{
						$addata = null;
					}

					if ($addata)
					{
						$html .= '<div>';
						$html .= SaAdEngineHelper::getInstance()->getAdHTML($addata);
						$html .= '<img alt="" src="' . JUri::root() . 'index.php?option=com_socialads&amp;task=getTransparentImage&amp;adid='
						. $random_ad->ad_id . $simulate . '"  border="0"  width="1" height="1"/>';
						$html .= '</div>';

						$cssfile = JPATH_SITE . '/plugins/socialadslayout/plug_' . $addata->layout . '/plug_' . $addata->layout . '/layout.css';

						$cssdata .= file_get_contents($cssfile);
					}
				}
			}

			if ($userparam['create'] == 1)
			{
				$html .= '<div style="clear:both;"></div><a class ="create" target="_blank" href="'
				. JRoute::_(JUri::root() . 'index.php?option=com_socialads&view=adform&Itemid=' . $itemid, false)
				. '">' . $userparam['create_text'] . '</a>';
			}
		}

		$html .= '</span>';

		if (empty($adsdata))
		{
			$areturn[1] = '';
			$areturn[2] = '';
		}
		else
		{
			$areturn[1] = $html;
			$areturn[2] = $cssdata;
		}

		return $areturn;
	}
	// OnEmail_jma_socialads() ends

	/**
	 * check if extension is installed
	 *
	 * @since   1.5
	 *
	 * @return  boolean
	 */
	public function _chkextension()
	{
		jimport('joomla.filesystem.file');
		$extpath = JPATH_ROOT . '/components/com_socialads';

		if (JFolder::exists($extpath))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
}
// Class plgEmailalertsJsntwrk  ends
