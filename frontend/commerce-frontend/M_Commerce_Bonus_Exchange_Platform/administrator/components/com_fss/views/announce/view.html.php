<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport( 'joomla.filesystem.folder' );


class FsssViewAnnounce extends JViewLegacy
{

	function display($tpl = null)
	{
		$announce		= $this->get('Data');
		$isNew		= ($announce->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("ANNOUNCEMENT").': <small><small>[ ' . $text.' ]</small></small>', 'fss_announce' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();

		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		//$this->lists = $lists;
		$this->announce = $announce;

		

		parent::display($tpl);
	}
}


