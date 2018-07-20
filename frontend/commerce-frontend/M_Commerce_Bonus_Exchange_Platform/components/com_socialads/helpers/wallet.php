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
 * Socialads helper.
 *
 * @since  1.6
 */
class SaWalletHelper
{
	/**
	 * Return 1.00 if there is balance in the user's wallet.
	 *
	 * @param   integer  $userId  Default value
	 *
	 * @return  string
	 *
	 * @since  2.7
	 */
	public static function getBalance($userId = 0)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$db        = JFactory::getDBO();
		$query    = $db->getQuery(true);
		$minimumRequiredBalence = $sa_params->get('camp_currency_pre');

		if (!$userId)
		{
			$userId = JFactory::getUser()->id;
		}

		$query->select('balance');
		$query->from('#__ad_wallet_transc');
		$query->where('time = (select MAX(time) FROM #__ad_wallet_transc WHERE user_id =' . $userId . ')');
		$db->setQuery($query);
		$init_balance = $db->loadResult();

		if ($init_balance == null | $init_balance < $minimumRequiredBalence)
		{
			return '0.00';
		}
		else
		{
			return '1.00';
		}
	}
}
