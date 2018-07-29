<?php
/**
 * @version    SVN: <svn_id>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die();

/**
 * SocialAds component helper.
 *
 * @package     SocialAds
 * @subpackage  com_socialads
 * @since       1.0
 */
class SocialadsHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public static function addSubmenu($vName = '')
	{
		$params       = JComponentHelper::getParams('com_socialads');
		$payment_mode = $params->get('payment_mode');

		if (JVERSION >= '3.0')
		{
			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_DASHBOARD'),
				'index.php?option=com_socialads&view=dashboard',
				$vName == 'dashboard'
			);

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_ADS'),
				'index.php?option=com_socialads&view=forms',
				$vName == 'forms'
			);

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_AD_ORDERS'),
				'index.php?option=com_socialads&view=adorders',
				$vName == 'adorders'
			);

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_COUPONS'),
				'index.php?option=com_socialads&view=coupons',
				$vName == 'coupons'
			);

			if ($payment_mode == 'wallet_mode')
			{
				JHtmlSidebar::addEntry(
					JText::_('COM_SOCIALADS_TITLE_CAMPAIGNS'),
					'index.php?option=com_socialads&view=campaigns',
					$vName == 'campaigns'
				);

				JHtmlSidebar::addEntry(
					JText::_('COM_SOCIALADS_TITLE_ORDERS'),
					'index.php?option=com_socialads&view=orders',
					$vName == 'orders'
				);

				JHtmlSidebar::addEntry(
					JText::_('COM_SOCIALADS_TITLE_WALETS'),
					'index.php?option=com_socialads&view=wallets',
					$vName == 'wallets'
					);
			}

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_ZONES'),
				'index.php?option=com_socialads&view=zones',
				$vName == 'zones'
			);

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_SOCIAL_TARGETING'),
				'index.php?option=com_socialads&view=importfields',
				$vName == 'importfields'
			);

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_COUNTRIES'),
				'index.php?option=com_tjfields&view=countries&client=com_socialads',
				$vName == 'countries'
			);

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_REGIONS'),
				'index.php?option=com_tjfields&view=regions&client=com_socialads',
				$vName == 'regions'
			);

			JHtmlSidebar::addEntry(
				JText::_('COM_SOCIALADS_TITLE_CITIES'),
				'index.php?option=com_tjfields&view=cities&client=com_socialads',
				$vName == 'cities'
			);
		}
		else
		{
			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_DASHBOARD'),
				'index.php?option=com_socialads&view=dashboard',
				$vName == 'dashboard'
			);

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_ADS'),
				'index.php?option=com_socialads&view=forms',
				$vName == 'forms'
			);

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_AD_ORDERS'),
				'index.php?option=com_socialads&view=adorders',
				$vName == 'adorders'
			);

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_COUPONS'),
				'index.php?option=com_socialads&view=coupons',
				$vName == 'coupons'
			);

			if ($payment_mode == 'wallet_mode')
			{
				JSubMenuHelper::addEntry(
					JText::_('COM_SOCIALADS_TITLE_CAMPAIGNS'),
					'index.php?option=com_socialads&view=campaigns',
					$vName == 'campaigns'
				);

				JSubMenuHelper::addEntry(
					JText::_('COM_SOCIALADS_TITLE_ORDERS'),
					'index.php?option=com_socialads&view=orders',
					$vName == 'orders'
				);

				JSubMenuHelper::addEntry(
					JText::_('COM_SOCIALADS_TITLE_WALETS'),
					'index.php?option=com_socialads&view=wallets',
					$vName == 'wallets'
					);
			}

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_ZONES'),
				'index.php?option=com_socialads&view=zones',
				$vName == 'zones'
			);

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_SOCIAL_TARGETING'),
				'index.php?option=com_socialads&view=importfields',
				$vName == 'importfields'
			);

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_COUNTRIES'),
				'index.php?option=com_tjfields&view=countries&client=com_socialads',
				$vName == 'countries'
			);

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_REGIONS'),
				'index.php?option=com_tjfields&view=regions&client=com_socialads',
				$vName == 'regions'
			);

			JSubMenuHelper::addEntry(
				JText::_('COM_SOCIALADS_TITLE_CITIES'),
				'index.php?option=com_tjfields&view=cities&client=com_socialads',
				$vName == 'cities'
			);
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @since  1.6
	 */
	public static function getActions()
	{
		$user = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_socialads';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Get all jtext for javascript
	 *
	 * @return   void
	 *
	 * @since   1.0
	 */
	public static function getLanguageConstant()
	{
		// For number valiation
		JText::script('COM_SOCIALADS_ZERO_VALUE_VALI_MSG');
		JText::script('COM_SOCIALADS_NUMONLY_VALUE_VALI_MSG');

		// For date valiation
		JText::script('COM_SOCIALADS_DATE_START_ERROR_MSG');
		JText::script('COM_SOCIALADS_DATE_END_ERROR_MSG');
		JText::script('COM_SOCIALADS_DATE_ERROR_MSG');
		JText::script('JGLOBAL_VALIDATION_FORM_FAILED');

		// For Zone validation
		JText::script('COM_SOCIALADS_YOU_MUST_PROVIDE_A_MAX_TITLE_CHAR');
		JText::script('COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC');
		JText::script('COM_SOCIALADS_YOU_MUST_PROVIDE_A_MAX_DESC_CHAR');
		JText::script('COM_SOCIALADS_YOU_PROVIDE_A_IMG_HEIGHT');
		JText::script('COM_SOCIALADS_YOU_PROVIDE_A_IMG_WIDTH');
		JText::script('JGLOBAL_VALIDATION_FORM_FAILED');
		JText::script('COM_SOCIALADS_ZONE_DEL_SURE_MSG');
		JText::script('COM_SOCIALADS_ZONE_DEL_NOT_ABLE_TO_DELETE');
		JText::script('COM_SOCIALADS_ZONE_MSG_ON_EDIT_ZONE');

		// For dashboard
		JText::script('COM_SOCIALADS_AMOUNT');
		JText::script('COM_SOCIALADS_PENDING_ORDERS');
		JText::script('COM_SOCIALADS_CONFIRMED_ORDERS');
		JText::script('COM_SOCIALADS_REJECTED_ORDERS');
		JText::script('COM_SOCIALADS_DATE_ERROR_MSG_DASHBOARD');
		JText::script('COM_SOCIALADS_ERROR_LOADING_FEEDS');

		// Days
		JText::script('SUN');
		JText::script('MON');
		JText::script('TUE');
		JText::script('WED');
		JText::script('THU');
		JText::script('FRI');
		JText::script('SAT');

		// Months
		JText::script('JANUARY_SHORT');
		JText::script('FEBRUARY_SHORT');
		JText::script('MARCH_SHORT');
		JText::script('APRIL_SHORT');
		JText::script('MAY_SHORT');
		JText::script('JUNE_SHORT');
		JText::script('JULY_SHORT');
		JText::script('AUGUST_SHORT');
		JText::script('SEPTEMBER_SHORT');
		JText::script('OCTOBER_SHORT');
		JText::script('NOVEMBER_SHORT');
		JText::script('DECEMBER_SHORT');

		// Ads
		JText::script('COM_SOCIALADS_ADS_DELETE_CONFIRM');
		JText::script('COM_SOCIALADS_ADS_STATUS_PROMPT_BOX');

		// Create ad
		JText::script('COM_SOCIALADS_ERR_MSG_FILE_BIG_JS');
		JText::script('COM_SOCIALADS_ERR_MSG_FILE_ALLOW');
		JText::script('COM_SOCIALADS_SELECT_CAMPAIGN');
		JText::script('COM_SOCIALADS_SOCIAL_ESTIMATED_REACH_HEAD');
		JText::script('COM_SOCIALADS_SOCIAL_ESTIMATED_REACH_END');
		JText::script('COM_SOCIALADS_CANCEL_AD');
		JText::script('COM_SOCIALADS_URL_VALID');
		JText::script('COM_SOCIALADS_TITLE_VALID');
		JText::script('COM_SOCIALADS_BODY_VALID');
		JText::script('COM_SOCIALADS_MEDIA_VALID');
		JText::script('COM_SOCIALADS_RATE_PER_CLICK');
		JText::script('COM_SOCIALADS_RATE_PER_IMP');

		JText::script('COM_SOCIALADS_TOTAL_SHOULDBE_VALID_VALUE');
		JText::script('COM_SOCIALADS_ENTER_COP_COD');
		JText::script('COM_SOCIALADS_COP_EXISTS');
		JText::script('SA_RENEW_RECURR');
		JText::script('SA_RENEW_NO_RECURR');
		JText::script('COM_SOCIALADS_AD_CHARGE_TOTAL_DAYS_FOR_RENEWAL');
		JText::script('TOTAL');
		JText::script('POINTS_AVAILABLE');
		JText::script('POINT');
		JText::script('COM_SOCIALADS_AD_NUMBER_OF');
		JText::script('COM_SOCIALADS_AD_SELECT_CAMPAIGN');
		JText::script('COM_SOCIALADS_AD_ENTER_CAMPAIGN');
		JText::script('COM_SOCIALADS_AD_ALLOWED_BUDGET');

		// Importfields
		JText::script('COM_SOCIALADS_SOCIAL_TARGETING_CONFIG_JSMESSAGE');
		JText::script('COM_SOCIALADS_SOCIAL_TARGETING_CONFIG_JSMESSAGE1');

		// Coupons
		JText::script('COM_SOCIALADS_COUPONS_DELETE_CONFORMATION');
		JText::script('COM_SOCIALADS_DUPLICATE_COUPON');
		JText::script('COM_SOCIALADS_AD_PRICING_OPTION');

		// Campaigns
		JText::script('COM_SOCIALADS_CAMPAIGNS_DELETE_CONFIRM');
	}
}
