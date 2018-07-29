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

jimport('joomla.application.component.modeladmin');

// Load frontend adform model
require_once JPATH_SITE . '/components/com_socialads/models/adform.php';

/**
 * Ad form model class for backend
 *
 * @since  1.6
 */
class SocialadsModelForm extends SocialadsModelAdForm
{
}
