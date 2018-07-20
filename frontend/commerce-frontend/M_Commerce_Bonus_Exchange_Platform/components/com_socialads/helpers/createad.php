<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2016 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Component Helper
jimport('joomla.application.component.helper');

/**
 * Create ad class
 *
 * @since  1.6
 */
class CreateAdHelper
{
	/**
	 * Checking if table "ad_fields" exists or not in buildad and manage ad view
	 *
	 * @return  boolean
	 *
	 * @since  1.0
	 **/
	public function chkadfields()
	{
		$db = JFactory::getDBO();
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$dbname    = $mainframe->getCfg('db');
		$dbprefix  = $mainframe->getCfg('dbprefix');
		$tablename = $dbprefix . 'ad_fields';
		$db        = JFactory::getDBO();
		$query     = "SELECT COUNT(*)
					FROM information_schema.tables
					WHERE table_schema = '$dbname'
					AND table_name = '$tablename'";
		$db->setQuery($query);
		$adfields = $db->loadresult();

		if (!$adfields)
		{
			return '';
		}
		else
		{
			return 1;
		}
	}

	/**
	 * Checking if table "ad_fields" exists or not in buildad and manage ad view
	 *
	 * @param   integer  $userid  User ID
	 *
	 * @return  boolean
	 *
	 * @since  1.0
	 **/
	public function getUserCampaign($userid)
	{
		$db    = JFactory::getDBO();
		$query = "SELECT camp_id,campaign,daily_budget FROM #__ad_campaign WHERE user_id=$userid";
		$db->setQuery($query);
		$camp_value = $db->loadobjectList();

		return $camp_value;
	}

	/**
	 * Function to get latest pending order
	 *
	 * @param   integer  $ad_id   Ad ID
	 * @param   integer  $userid  User ID
	 *
	 * @return  boolean
	 *
	 * @since  1.0
	 **/
	public function getLatestPendigOrder($ad_id, $userid)
	{
		$db    = JFactory::getDBO();
		$query = 'SELECT p.`id` FROM `#__ad_payment_info` as p
				WHERE p.ad_id=' . $ad_id . ' AND p.`payee_id`=' . $userid . ' AND p.`status`=\'p\' ORDER BY `id` DESC';
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Function to send ad approval
	 *
	 * @param   object  $designAd_data  provide ad data information
	 *
	 * @return  array
	 *
	 * @since  1.0
	 **/
	public function sendForApproval($designAd_data)
	{
		$return['sa_sentApproveMail'] = '';

		if (empty($designAd_data))
		{
			return $return;
		}

		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('o.id');
		$query->from('`#__ad_orders` AS o');
		$query->join('LEFT', '`#__ad_payment_info` AS p ON p.order_id=o.id');
		$query->where("p.ad_id =" . $designAd_data->ad_id . " AND o.status='C'");
		$query->order($db->quoteName('o.id') . ' DESC');

		$db->setQuery($query);
		$ConfirmOrders = $db->loadResult();

		if (empty($ConfirmOrders))
		{
			// No order is confirm then allow to edit ad
			return $return;
		}

		// Get old ad details
		$query = 'SELECT a.`ad_id`,a.`ad_image`,a.`ad_title`,a.`ad_body`,a.`ad_url2` FROM `#__ad_data` as a
				WHERE a.ad_id=' . $designAd_data->ad_id . '  AND a.ad_approved=1';
		$db->setQuery($query);
		$oldAd = $db->loadObject();

		// ANY ONE IS CHANGED
		if (!empty($oldAd)
			&& ($oldAd->ad_image != $designAd_data->ad_image
			|| $oldAd->ad_title != $designAd_data->ad_title
			|| $oldAd->ad_body != $designAd_data->ad_body
			|| $oldAd->ad_url2 != $designAd_data->ad_url2))
		{
			$createAdHelper = new createAdHelper;
			$createAdHelper->adminAdApprovalEmail($designAd_data->ad_id);
			$return['ad_approved']        = 0;
			$return['sa_sentApproveMail'] = 1;

			return $return;
		}

		return $return;
	}

	/**
	 * Function for admin approval mail
	 *
	 * @param   integer  $ad_id  Ad id
	 *
	 * @return  array
	 *
	 * @since  1.0
	 **/
	public function adminAdApprovalEmail($ad_id)
	{
		$db    = JFactory::getDBO();
		$query = 'SELECT a.`ad_id`,a.`ad_image`,a.`ad_title`,a.`ad_body`,a.`ad_url2` FROM `#__ad_data` as a
				WHERE a.ad_id=' . $ad_id;

		// . '  AND a.ad_approved=1';
		$db->setQuery($query);
		$oldAd = $db->loadObject();

		jimport('joomla.utilities.utility');
		$user = JFactory::getUser();
		global $mainframe;
		$mainframe    = JFactory::getApplication();
		$sitelink     = JUri::root();

		$manageAdLink = "<a href='" . $sitelink . "administrator/"
						. "index.php?option=com_socialads&view=ads' targe='_blank'>" . JText::_("COM_SOCIALADS_EMAIL_THIS_LINK") . "</a>";

		// GET config details
		$frommail = $mainframe->getCfg('mailfrom');
		$fromname = $mainframe->getCfg('fromname');
		$adUserName = $user->username;
		$adTitle    = $oldAd->ad_title;
		$siteName   = $mainframe->getCfg('sitename');
		$today      = date('Y-m-d H:i:s');

		// DEFINE SEARCH
		$find       = array(
			'[SEND_TO_NAME]',
			'[ADVERTISER_NAME]',
			'[SITENAME]',
			'[SITELINK]',
			'[ADTITLE]',
			'[CONTENT]',
			'[TIMESTAMP]'
		);

		// SEND ADMIN MAIL
		$recipient      = $frommail;
		$subject        = JText::_("COM_SOCIALADS_APPRVE_MAIL_TO_ADMIN_SUBJECT");
		$adminEmailBody = JText::sprintf("COM_SOCIALADS_EMAIL_HELLO") .
		JText::sprintf('COM_SOCIALADS_APPRVE_MAIL_TO_ADMIN_CONTENT', $manageAdLink) .
		JText::sprintf("COM_SOCIALADS_EMAIL_SITENAME_TEAM");

		// NOW REPLACE TAG
		// @TODO - Notice: Undefined variable: content in helpers/createad.php
		$replace        = array(
			$fromname,
			$adUserName,
			$siteName,
			$sitelink,
			$adTitle,
			$content,
			$today
		);
		$adminEmailBody = str_replace($find, $replace, $adminEmailBody);

		// $status  = $socialadshelper->sendmail($recipient,$subject,$adminEmailBody,$bcc_string='',$singlemail=0,$attachmentPath="");
		$status = SaCommonHelper::sendmail($recipient, $subject, $adminEmailBody, $bcc_string = '', $singlemail = 0, $attachmentPath = '');

		// SEND TO ADVERTISER MAIL
		$advertiserEmail     = $user->email;
		$subject             = JText::_("COM_SOCIALADS_APPRVE_MAIL_TO_ADVERTISER_SUBJECT");
		$advertiserEmailBody = JText::sprintf("COM_SOCIALADS_EMAIL_HELLO") .
		JText::sprintf('COM_SOCIALADS_APPRVE_MAIL_TO_ADVERTISR_CONTENT') .
		JText::sprintf("COM_SOCIALADS_EMAIL_SITENAME_TEAM");

		// NOW REPLACE TAG
		// @TODO - Notice: Undefined variable: content in helpers/createad.php
		$replace             = array(
			$adUserName,
			$adUserName,
			$siteName,
			$sitelink,
			$adTitle,
			$content,
			$today
		);
		$advertiserEmailBody = str_replace($find, $replace, $advertiserEmailBody);

		// $status  = $socialadshelper->sendmail($advertiserEmail,$subject,$advertiserEmailBody,$bcc_string='',$singlemail=0,$attachmentPath="");
		$status = SaCommonHelper::sendmail($advertiserEmail, $subject, $advertiserEmailBody, $bcc_string = '', $singlemail = 0, $attachmentPath = '');
	}
}
