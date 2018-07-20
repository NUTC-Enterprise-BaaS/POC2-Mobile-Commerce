<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerMember extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
	}

	function cancellist()
	{
		$link = "index.php?option=com_fss&view=ticketgroups";
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'member' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('member');

        $post = JRequest::get('post');
        $post['answer'] = JRequest::getVar('answer', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if ($model->store($post)) {
			$msg = JText::_("TICKET_GROUP_MEMBER_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_TICKET_GROUP_MEMBER");
		}

		$link = 'index.php?option=com_fss&view=members&groupid=' . JRequest::getVar('groupid');
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('member');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_TICKET_GROUP_MEMBER_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("TICKET_GROUP_MEMBER_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=members&groupid=' . JRequest::getVar('groupid'), $msg );
	}

	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=members&groupid=' . JRequest::getVar('groupid'), $msg );
	}
}



