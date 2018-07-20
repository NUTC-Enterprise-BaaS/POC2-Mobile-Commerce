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
		require_once JPATH_COMPONENT . '/helpers/socialads.php';
		$view = JFactory::getApplication()->input->getCmd('view', 'dashboard');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}

	/**
	 * Method to migrate ad from pay per ad mode to wallet mode and vice versa
	 *
	 * @return  void
	 *
	 * @since  1.5
	 */
	public function migrationOfAds()
	{
		$db = JFactory::getDbo();
		$input = JFactory::getApplication()->input;
		$document = JFactory::getDocument();
		$SocialadsPaymentHelper = new SocialadsPaymentHelper;
		$migrate_check = $input->get('migrate_chk', '0', 'INT');

		if ($input->get('camp_or_old', 'pay_per_ad_mode', 'STRING') == 'wallet_mode')
		{
			// For migrating from pay per ad mode to wallet mode
			$json = $SocialadsPaymentHelper->migrateads_camp($migrate_check);
			$mode = "wallet_mode";
		}
		else
		{
			// For migrating wallet mode to pay per ad mode
			$json = $SocialadsPaymentHelper->migrateads_old($migrate_check);
			$mode = "pay_per_ad_mode";
		}

		if ($migrate_check == "0")
		{
			$data = $this->paymentModeValue();
			$data = json_decode($data);
			$data->payment_mode = $mode;
			$data = json_encode($data);
			$query = $db->getQuery(true);
			$fields = $db->quoteName('params') . "='" . $data . "'";
			$condition = $db->quoteName('name') . ' = "com_socialads"';

			$query->update($db->quoteName('#__extensions'))->set($fields)->where($condition);
			$db->setquery($query);
			$db->execute();
		}

		$content = json_encode($json);
		echo $content;
		jexit();
	}

	/**
	 * Method to get payment mode value
	 *
	 * @return  void
	 *
	 * @since  1.5
	 */
	public function paymentModeValue()
	{
		$db = JFactory::getDBO();
		$query = "SELECT params FROM `#__extensions` WHERE `name` = 'com_socialads' ";
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}
}
