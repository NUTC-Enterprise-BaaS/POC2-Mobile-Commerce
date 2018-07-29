<?php
/**
 * @version    SVN: <svn_id>
 * @package    Sa
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

// Load Backend Coupon controller
require_once JPATH_ADMINISTRATOR . '/components/com_socialads/controllers/coupon.php';

/**
 * Dummy controller class for SocialAds coupon controller class.
 *
 * @since  3.1
 */
class SaControllerCoupon extends SocialadsControllerCoupon
{
}
