<?php
/**
 * @version    SVN: <svn_id>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Load frontend Payment controller
require_once JPATH_SITE . '/components/com_socialads/controllers/payment.php';

/**
 * Dummy for SocialAds Payment controller class.
 * Needed for payment gatway notify URLs when order placed in backend
 * 
 * @since  3.1
 */
class SocialadsControllerPay extends SocialadsControllerPayment
{
}
