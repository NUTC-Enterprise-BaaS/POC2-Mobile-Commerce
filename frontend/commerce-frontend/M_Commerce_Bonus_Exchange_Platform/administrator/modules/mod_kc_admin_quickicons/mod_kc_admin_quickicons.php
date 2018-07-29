<?php
/**
 * @version		$Id: mod_kc_admin_quickicons.php 14276 2010-01-18 14:20:28Z laurelle $
 * @package		Joomla.Administrator
 * @subpackage	mod_kc_admin_quickicons
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 - 2014 Keashly.ca Consulting
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

require_once dirname(__FILE__).'/helper.php';

//$lang_param = $params->get('language'); // Get the language parameter
//$lang = JFactory::getLanguage()->getTag(); // Get the site language

//if ($lang == $lang_param || $lang_param =="*") {
	
	// Only display something if the language is correct or should be shown for all languages
	require JModuleHelper::getLayoutPath('mod_kc_admin_quickicons', $params->get('layout', 'default'));
	
//}