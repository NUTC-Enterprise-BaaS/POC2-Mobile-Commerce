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

// Component Helper
jimport('joomla.application.component.helper');

/**
 * Helper class
 *
 * @since  1.6
 */
abstract class SaDisplayHelper
{
	/**
	 * Sample function code
	 *
	 * @param   string  $view  eg. managead&layout=list
	 *
	 * @return  itemid
	 *
	 * @since 1.6
	 **/
	public static function sampleFunctionCode($view='')
	{
		return $itemid;
	}
}
