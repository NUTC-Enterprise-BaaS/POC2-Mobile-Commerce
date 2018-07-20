<?php
/**
 * @package JBusinessDirectory
 * @author CMSJunkie http://www.cmsjunkie.com
 * @copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

$mod_name = 'mod_jbusinessdirectory_icons';

$document 	= JFactory::getDocument();
$input 		= JFactory::getApplication()->input;

JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');
$document->addStyleSheet(JURI::base(true).'/modules/'.$mod_name.'/tmpl/css/style.css');

require JModuleHelper::getLayoutPath($mod_name, $params->get('layout','default'));