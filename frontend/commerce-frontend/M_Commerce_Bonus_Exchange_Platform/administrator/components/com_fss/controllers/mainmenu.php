<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerMainmenu extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
		$this->registerTask( 'orderup', 'orderup' );
		$this->registerTask( 'orderdown', 'orderdown' );
		$this->registerTask( 'saveorder', 'saveorder' );
		$this->registerTask( 'convert', 'convert' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}


	function edit()
	{
		JRequest::setVar( 'view', 'mainmenu' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('mainmenu');

		$post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::_("MENU_ITEM_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_MENU_ITEM");
		}

		$link = 'index.php?option=com_fss&view=mainmenus';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('mainmenu');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_MENU_ITEMS_COULD_NOT_BE_DELETED") .' - ' . $model->getError();
		} else {
			$msg = JText::_("MENU_ITEM_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('mainmenu');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_AN_MENU_ITEM");

		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}

	function publish()
	{
		$model = $this->getModel('mainmenu');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_AN_MENU_ITEM");

		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}

	function convert()
	{
		if (!$this->convert_items())
		{
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CONVERTING");
		} else {
			$msg = JText::_("CONVERT_OK");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}
	
	private function convert_items()
	{
		$data = array( 
			1 => 'KB',
			2 => 'FAQS',
			3 => 'TEST',
			4 => 'NEW_TICKET',
			5 => 'VIEW_TICKET',
			6 => 'ANNOUNCE',
			8 => 'GLOSSARY',
			9 => 'ADMIN',
			10 => 'GROUP_ADMIN'
		);
		

		//print_p($data);
		
		$db = JFactory::getDBO();

		foreach ($data as $type => $string)
		{
			$qry = "UPDATE #__fss_main_menu SET title = 'MAIN_MENU_{$string}', description = 'MAIN_MENU_{$string}_DESC' WHERE itemtype = $type";
			//echo $qry."<br>";
			
			$db->setQuery($qry);
			$db->Query();
		}
		//
		return true;	
	}	

	function orderup()
	{
		$model = $this->getModel('mainmenu');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('mainmenu');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('mainmenu');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=mainmenus', $msg );
	}
}



