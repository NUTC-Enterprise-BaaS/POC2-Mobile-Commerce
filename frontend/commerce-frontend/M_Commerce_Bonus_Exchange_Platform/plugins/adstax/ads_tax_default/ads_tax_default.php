<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Js_Events
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
$lang = JFactory::getLanguage();
$lang->load('plug_ads_tax_default', JPATH_ADMINISTRATOR);

/**
 * Plugin class to add tax in Socialads.
 *
 * @since  1.6
 */
class PlgAdstaxAds_Tax_Default extends JPlugin
{
	/**
	 * Methode to add tax
	 *
	 * @param   integer  $amt  Tax amount
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function addTax($amt)
	{
		$taxAssign   = $this->params->get('tax_per');
		$taxValue = ($taxAssign * $amt) / 100;
		$return[]  = $taxAssign . "%";
		$return[]  = $taxValue;

		return $return;
	}
}
