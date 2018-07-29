<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerFuser extends FsssController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'save2new', 'save' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'fuser' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		
		$model = $this->getModel('fuser');
		$users = $model->getUsers();
		$user = $model->getData();
		
		if ($user->id < 1 && count($users) == 0)
		{
			$msg = JText::_("CANNOT_ADD_ANOTHER_USER_ALL_JOOMLA_USERS_ALREADY_HAVE_RECORDS_FOR_FREESTYLE_SUPPORT_PORTAL");
			$link = 'index.php?option=com_fss&view=fusers';
			$this->setRedirect($link, $msg);
			return;			
		} else {
			parent::display();
		}
	}

	function save()
	{
		$model = $this->getModel('fuser');

		$post = JRequest::get('post');

		if ($model->store($post)) {
			$msg = JText::_("USER_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_USER");
		}

		// Check the table in so it can be edited.... we are done with it anyway

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=fuser&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			//print_p($this);
			//exit;
			$link = 'index.php?option=com_fss&controller=fuser&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=fusers';
		}
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		$model = $this->getModel('fuser');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_USERS_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("USER_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=fusers', $msg );
	}

	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=fusers', $msg );
	}
}



