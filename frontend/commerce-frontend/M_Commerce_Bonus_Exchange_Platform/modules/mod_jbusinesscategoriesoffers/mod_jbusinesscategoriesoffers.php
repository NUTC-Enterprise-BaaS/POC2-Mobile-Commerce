<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).'/helper.php' );
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php'; 
require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php';
require_once JPATH_SITE.'/administrator/components/com_jbusinessdirectory/helpers/translations.php';

JHtml::_('jquery.framework', true, true);
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/categories.css');
JHtml::_('stylesheet', 'modules/mod_jbusinesscategoriesoffers/assets/style.css');

if($params->get('viewtype') == 'slider') {
    JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/slick.css');
    JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/slick.js');
} else {
    JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/metisMenu.css');
    JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/metisMenu.js');
}

$helper = new modJBusinessCategoriesOffersHelper();
$categories =  $helper->getCategories();
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$categoriesIds = $params->get('categoryIds');


if(isset($categoriesIds) && count($categoriesIds)>0 && $categoriesIds!="") {
    if($params->get('viewtype') == 'slider')
        $categories = $helper->getCategoriesByIdsOnSlider($categoriesIds);
    else
        $categories = $helper->getCategoriesByIdsOnMenu($categoriesIds);
}
else {
    $categories = $helper->getCategories();
}

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

if($appSettings->enable_multilingual) {
    JBusinessDirectoryTranslations::updateCategoriesTranslation($categories);

}

require JModuleHelper::getLayoutPath('mod_jbusinesscategoriesoffers', "default_".$params->get('viewtype'));
?>