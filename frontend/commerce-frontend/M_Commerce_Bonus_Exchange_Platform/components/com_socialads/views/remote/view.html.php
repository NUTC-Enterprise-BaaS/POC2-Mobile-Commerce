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

jimport('joomla.application.component.view');

/**
 * View class for remote ads display of ads.
 *
 * @since  3.1
 */
class SocialadsViewRemote extends JViewLegacy
{
	/**
	 * Display the view
	 *
	 * @param   array  $tpl  An optional associative array.
	 *
	 * @return  array
	 *
	 * @since 1.6
	 */
	public function display($tpl = null)
	{
		$jinput = JFactory::getApplication()->input;
		$adData = $jinput->get('adData', '', "RAW");

		if (!empty($adData))
		{
			$adData = json_decode($adData, true);

			if (!empty($adData['ads_params']['ad_unit']) && !empty($adData['ads_params']['zone']))
			{
				$adData['ads_params']['alt_ad'] = 1;
				$adData['ads_params']['debug']  = 0;
				$this->adData = $adData;

				$session = JFactory::getSession();
				$session->set('userData', $adData);

				require_once JPATH_ROOT . '/components/com_socialads/helpers/remote.php';

				/*
				 * // $adRetriever       = new remoteAdRetriever(1);
				 * // $this->ads         = $adRetriever->getnumberofAds($adData, $adData['ads_params']['ad_unit'], $adRetriever);
				 * // $this->adRetriever = $adRetriever;
				 **/

				$this->ads      = RemoteSaAdEngineHelper::getInstance('remote')->getAdsForZone($adData['ads_params'], $adData['ads_params']['ad_unit']);
				$this->moduleid = $adData['ads_params']['ad_unit'];
				$this->zone     = $adData['ads_params']['zone'];

				// +manoj since 3.1
				$this->ad_rotation       = $adData['ads_params']['ad_rotation'];
				$this->ad_rotation_delay = "";

				if ($this->ad_rotation == "1")
				{
					$this->ad_rotation_delay = $adData['ads_params']['ad_rotation_delay'];
				}
			}
		}

		if (empty($this->ads))
		{
			return;
		}

		parent::display($tpl);
	}
}
