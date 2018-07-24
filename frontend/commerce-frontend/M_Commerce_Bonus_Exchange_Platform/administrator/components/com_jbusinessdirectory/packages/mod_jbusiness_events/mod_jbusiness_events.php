<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die;
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php';
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php';

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/common.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/responsive.css');
JHtml::_('stylesheet', 'modules/mod_jbusiness_events/assets/style.css');
// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

JBusinessUtil::loadSiteLanguage();

$items = modJBusinessEventsHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_jbusiness_events', "default_".$params->get('viewtype'));
