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

jimport('joomla.application.component.view');

// Get SocialAds remote ads view
require_once JPATH_SITE . '/components/com_socialads/views/remote/view.html.php';

/**
 * Dummy View class for SocialAds remote ads view.
 *
 * @since  1.6
 */
class SaViewRemotecontrol extends SocialadsViewRemote
{
}
