<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

class FsssViewTimezone extends JViewLegacy
{
	
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		JToolBarHelper::title( JText::_("FREESTYLE_SUPPORT_PORTAL") .' - '. JText::_("Timezone Helper") , 'fss_settings' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel('cancellist');
		
		parent::display($tpl);
	}

}


