<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );



class FsssViewEmail extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$faq		= $this->get('Data');
		$isNew		= ($faq->id < 1);

		$text = JText::_("EDIT");
		JToolBarHelper::title(   JText::_("EMAIL_TEMPLATE").': <small><small>[ ' . $text.' ]</small></small>', 'fss_emails' );
		JToolBarHelper::custom('translate','translate', 'translate', 'Translate', false);
		JToolBarHelper::custom('reset','reset', 'reset', 'Reset', false);
		JToolBarHelper::spacer();
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel( 'cancel', 'Close' );
		FSSAdminHelper::DoSubToolbar();

		$this->email = $faq;

		parent::display($tpl);
	}
}


