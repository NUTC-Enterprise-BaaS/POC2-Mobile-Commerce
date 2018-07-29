<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerTicketpri extends FsssController
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
		JRequest::setVar( 'view', 'ticketpri' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('ticketpri');

        $post = JRequest::get('post');
        $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if ($model->store($post)) {
			$msg = JText::_("TICKET_PRIORITY_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_TICKET_PRIORITY");
		}

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=ticketpri&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=ticketpri&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=ticketpris';
		}
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('ticketpri');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_TICKET_PRIORITIES_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("TICKET_PRIORITY_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketpris', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=ticketpris', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('ticketpri');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_A_TICKET_PRIORITY");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketpris', $msg );
	}

	function publish()
	{
		$model = $this->getModel('ticketpri');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_A_TICKET_PRIORITY");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketpris', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('ticketpri');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketpris', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('ticketpri');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketpris', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('ticketpri');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketpris', $msg );
	}
}



