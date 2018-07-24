<?php

/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php';
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php';

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/common.css');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/responsive.css');
JHtml::_('stylesheet', 'modules/mod_jbusiness_latest/assets/style.css');
JHtml::_('script', 'modules/mod_jbusiness_latest/assets/js/script.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/utils.js');

JHtml::_('jquery.framework', true, true);

if($params->get('viewtype') == 'slider' || $params->get('viewtype') == 'tier') {
	JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/slick.css');
	JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/slick.js');
}

JHTML::_('script', 	'components/com_jbusinessdirectory/assets/js/jquery.raty.min.js');

require_once JPATH_SITE.'/administrator/components/com_jbusinessdirectory/helpers/translations.php';

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

JBusinessUtil::loadSiteLanguage();

//load items through cache mechanism
$cache = JFactory::getCache();
$items = $cache->call( array( 'modJBusinessLatestHelper', 'getList' ), $params );

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
if($appSettings->enable_multilingual){
	JBusinessDirectoryTranslations::updateBusinessListingsTranslation($items);
	JBusinessDirectoryTranslations::updateBusinessListingsSloganTranslation($items);
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$backgroundCss="";
if($params->get('backgroundColor')) {
	$backgroundCss = "background-color:".$params->get('backgroundColor').";";
}

$borderCss="";
if($params->get('borderColor')) {
	$borderCss="border-color:".$params->get('borderColor').";";
}

require JModuleHelper::getLayoutPath('mod_jbusiness_latest', "default_".$params->get('viewtype'));

?>