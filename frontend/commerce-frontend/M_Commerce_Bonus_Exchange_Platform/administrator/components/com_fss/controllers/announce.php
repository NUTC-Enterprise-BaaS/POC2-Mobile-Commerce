<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerAnnounce extends FsssController
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
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}


	function edit()
	{
		JRequest::setVar( 'view', 'announce' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('announce');

        $post = JRequest::get('post');
        $post['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $post['fulltext'] = "";
        
        if (strpos($post['body'],"system-readmore") > 0)
        {
            $pos = strpos($post['body'],"system-readmore");
            $answer = substr($post['body'], 0, $pos);
            $answer = substr($answer,0, strrpos($answer,"<"));
            
            $rest = substr($post['body'], $pos);
            $rest = substr($rest, strpos($rest,">")+1);
            
            $post['body'] = $answer;
            $post['fulltext'] = $rest;                           
        }
   
		if ($model->store($post)) {
			$msg = JText::_("ANNOUNCEMENT_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_ANNOUNCEMENT");
		}


		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=announce&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=announce&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=announces';
		}
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('announce');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_ANNOUNCEMENTS_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("ANNOUNCEMENT_DELETED");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('announce');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_AN_ANNOUNCEMENT");

		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}

	function publish()
	{
		$model = $this->getModel('announce');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_AN_ANNOUNCEMENT");

		$this->setRedirect( 'index.php?option=com_fss&view=announces', $msg );
	}
}



