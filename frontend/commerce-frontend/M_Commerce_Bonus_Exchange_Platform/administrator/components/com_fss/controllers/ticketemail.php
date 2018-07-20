<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class fsssControllerticketemail extends JControllerLegacy
{

	function __construct()
	{
		parent::__construct();
		
		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'save2new', 'save' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss';
		$this->setRedirect($link, $msg);
	}
	
	function edit()
	{
		JRequest::setVar( 'view', 'ticketemail' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar( 'hidemainmenu' , 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('ticketemail');

        $post = JRequest::get('post');

		if (!array_key_exists('allowunknown',$post))
			$post['allowunknown'] = 0;
	
		if (!array_key_exists('allowrepliesonly',$post))
			$post['allowrepliesonly'] = 0;
		
		if (!array_key_exists('import_html',$post))
			$post['import_html'] = 0;
		
		if (!array_key_exists('usessl',$post))
			$post['usessl'] = 0;
	
		if (!array_key_exists('usetls',$post))
			$post['usetls'] = 0;
	
		if (!array_key_exists('validatecert',$post))
			$post['validatecert'] = 0;
		
		if (!array_key_exists('allow_joomla',$post))
			$post['allow_joomla'] = 0;
		
		if (!array_key_exists('confirmnew',$post))
			$post['confirmnew'] = 0;

		if ($model->store($post)) {
			$msg = JText::_( 'TICKET_EMAIL_ACCOUNT_SAVED' );
		} else {
			$msg = JText::_( 'ERROR_SAVING_TICKET_EMAIL_ACCOUNT' );
		}

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=ticketemail&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=ticketemail&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=ticketemails';
		}

		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('ticketemail');
		if(!$model->delete()) {
			$msg = JText::_( 'ERROR_ONE_OR_MORE_TICKET_EMAIL_ACCOUNTS_COULD_NOT_BE_DELETED' );
		} else {
			$msg = JText::_( 'TICKET_EMAIL_ACCOUNTS_DELETED' );
		}

		$link = 'index.php?option=com_fss&view=ticketemails';
		//print_r($_POST);
		//echo "<a href='$link'>Redirect</a>";
		$this->setRedirect($link, $msg );
	}


	function cancel()
	{
		$msg = JText::_( 'OPERATION_CANCELLED' );
		$this->setRedirect( 'index.php?option=com_fss&view=ticketemails', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('ticketemail');
		if (!$model->unpublish())
			$msg = JText::_( 'ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_A_TICKET_EMAIL_ACCOUNT' );

		$this->setRedirect( 'index.php?option=com_fss&view=ticketemails', $msg );
	}

	function publish()
	{
		$model = $this->getModel('ticketemail');
		if (!$model->publish())
			$msg = JText::_( 'ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_A_TICKET_EMAIL_ACCOUNT' );

		$this->setRedirect( 'index.php?option=com_fss&view=ticketemails', $msg );
	}
}


