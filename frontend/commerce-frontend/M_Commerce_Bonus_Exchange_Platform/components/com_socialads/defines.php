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

$limit = 0;
$title_char = 30;

$sa_params = JComponentHelper::getParams('com_socialads');


if (!defined('COM_SA_CONST_MEDIA_ROOTPATH'))
{
	define('COM_SA_CONST_MEDIA_ROOTPATH', JPATH_ROOT . '/images/' . $sa_params->get('image_path', 'sa'));
}

if (!defined('COM_SA_CONST_MEDIA_ROOTURL'))
{
	define('COM_SA_CONST_MEDIA_ROOTURL', JUri::root() . 'images/' . $sa_params->get('image_path', 'sa'));
}

if (!defined('COM_SA_CONST_MEDIA_ROOTPATH_RELATIVE_NO_SLASH'))
{
	define('COM_SA_CONST_MEDIA_ROOTPATH_RELATIVE_NO_SLASH', 'images/' . $sa_params->get('image_path', 'sa'));
}
