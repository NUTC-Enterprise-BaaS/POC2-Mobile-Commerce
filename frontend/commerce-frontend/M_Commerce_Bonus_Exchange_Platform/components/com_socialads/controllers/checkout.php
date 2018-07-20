<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access.
defined('_JEXEC') or die(';)');
jimport('joomla.application.component.controller');
include_once JPATH_COMPONENT . '/controller.php';

/**
 * Checkout controller class.
 *
 * @package     SocialAds
 * @subpackage  com_socialads
 * @since       1.0
 */
class SocialadsControllerCheckout extends JControllerLegacy
{
	/**
	 * Function to load state
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function loadState()
	{
		$jinput  = JFactory::getApplication()->input;
		$country = $jinput->get('country', '', 'INT');
		require_once JPATH_SITE . '/components/com_tjfields/helpers/geo.php';
		$tjGeoHelper = TjGeoHelper::getInstance('TjGeoHelper');
		$states      = (array) $tjGeoHelper->getRegionList($country, 'com_socialads');

		echo json_encode($states);
		jexit();
	}

	/**
	 * Function to add place holder
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function adsPlaceOrder()
	{
		$jinput = JFactory::getApplication()->input;

		$state = $model->getuserState('India');
		echo json_encode($state);
		jexit();
	}
}
