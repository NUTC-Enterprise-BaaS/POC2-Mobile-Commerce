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

jimport('joomla.application.component.model');

// Load frontend model
require_once JPATH_SITE . '/components/com_socialads/models/payment.php';

/**
 * Dummy class for SocialAds payment model class.
 *
 * @since  3.1
 */
class SaModelPayment extends SocialadsModelPayment
{
}
