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

jimport( 'joomla.application.component.view' );
JHtml::_('script',  'components/com_jbusinessdirectory/assets/js/jquery-ui.js');

JHtml::_('script',  'components/com_jbusinessdirectory/assets/js/raphael-min.js');
JHtml::_('script',  'components/com_jbusinessdirectory/assets/js/morris.min.js');
JHtml::_('script',  'components/com_jbusinessdirectory/assets/js/prettify.min.js');
JHtml::_('stylesheet',  'components/com_jbusinessdirectory/assets/css/prettify.min.css');
JHtml::_('stylesheet',  'components/com_jbusinessdirectory/assets/css/morris.css');
?>

<?php

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewJBusinessDirectory extends JBusinessDirectoryAdminView
{
	function display($tpl = null)
	{
		$this->addToolbar();
		
		$this->statistics = $this->get("Statistics");
		$this->income = $this->get("Income");
		$this->news = $this->get("LocalNews");
		
		parent::display($tpl);
	}	
	
	protected function addToolbar()
	{
		//require_once JPATH_COMPONENT.'/helpers/menus.php';
	
		JToolBarHelper::title(JText::_('LNG_COM_JBUSINESSDIRECTORY'), 'menumgr.png');
		$canDo = JBusinessDirectoryHelper::getActions();
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
	}
}