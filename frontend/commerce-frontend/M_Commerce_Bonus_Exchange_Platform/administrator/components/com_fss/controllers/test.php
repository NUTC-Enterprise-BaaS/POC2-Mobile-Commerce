<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerTest extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
	}
	
	function ident()
	{
		JRequest::setVar( 'view', 'test' );
		JRequest::setVar( 'layout', 'form'  );
		parent::display();
		exit;
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}


	function edit()
	{
		JRequest::setVar( 'view', 'test' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('test');

        $post = JRequest::get('post');
        $post['body'] = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if ($model->store($post)) {
			$msg = JText::_("TESTIMONIAL_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_TESTIMONIAL");
		}

		$link = 'index.php?option=com_fss&view=tests';
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('test');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_TESTIMONIAL_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("TESTIMONIAL_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=tests', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=tests', $msg );
	}

}



