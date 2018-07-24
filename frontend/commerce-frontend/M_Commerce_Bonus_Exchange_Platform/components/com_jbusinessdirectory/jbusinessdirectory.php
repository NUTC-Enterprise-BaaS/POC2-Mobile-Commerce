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
require_once JPATH_COMPONENT_SITE.'/assets/defines.php';
require_once JPATH_COMPONENT_SITE.'/assets/utils.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/translations.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/attachments.php';
require_once JPATH_COMPONENT_SITE.'/assets/logger.php';
JHtml::_('behavior.framework');

JHTML::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/common.css');
JHTML::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/forms.css');
JHTML::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/responsive.css');
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');

JHtml::_('jquery.framework', true, true);
define('J_JQUERY_LOADED', 1);

JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.raty.min.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/jquery.blockUI.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/common.js');
JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/utils.js');

if( !defined('COMPONENT_IMAGE_PATH') )
	define("COMPONENT_IMAGE_PATH", JURI::base()."components/com_jbusinessdirectory/assets/images/");

JBusinessUtil::loadClasses();

$document  =JFactory::getDocument();
$document->addScriptDeclaration('
	var baseUrl="'.(JURI::base().'index.php?option='.JBusinessUtil::getComponentName()).'";
	var imageBaseUrl="'.(JURI::root().PICTURES_PATH).'";
	var siteRoot = "'. JURI::root().'";'
);
JBusinessUtil::setMenuItemId();
JBusinessUtil::loadSiteLanguage();

$log = Logger::getInstance(JPATH_COMPONENT."/logs/site-log-".date("d-m-Y").'.log',1);

// Execute the task.
$controller	= JControllerLegacy::getInstance('JBusinessDirectory');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();