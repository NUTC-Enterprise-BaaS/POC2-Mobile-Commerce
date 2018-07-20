<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerTicketstatus extends FsssController
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
		JRequest::setVar( 'view', 'ticketstatus' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('ticketstatus');

        $post = JRequest::get('post');
        $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$msg = "";
		if ($model->store($post)) {
			$msg = JText::_("Saved");
		} else {
			$msg = JText::_("Error Saving");
		}


		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=ticketstatus&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=ticketstatus&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=ticketstatuss';
		}
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('ticketstatus');
		$res = $model->delete();
		$msg = "";

		if (strlen($res) > 3)
		{
			$msg = JText::_($res);
		} else if ($res == false)
		{
			$msg = JText::_("Error Deleting");
		} else {
			$msg = JText::_("Deleted");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}


	function cancel()
	{
		$msg = JText::_("Cancelled");
		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('ticketstatus');
		$res = $model->unpublish();
		$msg = "";
		if (strlen($res) > 3)
		{
			$msg = JText::_($res);
		} else if ($res == false)
		{
			$msg = JText::_("Error Unpublishing");
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function publish()
	{
		$model = $this->getModel('ticketstatus');
		$res = $model->publish();
		$msg = "";
		if ($res != true)
		{
			if (strlen($res) > 3)
			{
				$msg = JText::_($res);
			} else {
				$msg = JText::_("Error Publishing");
			}
		}

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->changeorder(-1))
			$msg = JText::_("Error changing the order");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->changeorder(1))
			$msg = JText::_("Error changing the order");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->saveorder())
			$msg = JText::_("Error changing the order");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	function is_closed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_closed(1))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function not_closed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_closed(0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	function can_autoclose()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_autoclose(1))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function not_autoclosed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_autoclose(0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	function own_tab()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_tab(1))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function not_tab()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_tab(0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	
	function def_open()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_open'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}	
	
	function def_archive()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_archive'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}	
	
	function def_user()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_user'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
	
	function def_user_unset()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_user', 0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
		
	function def_admin()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_admin'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}	
		
	function def_admin_unset()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_admin', 0))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}	
	
	function def_closed()
	{
		$model = $this->getModel('ticketstatus');
		$msg = "";
		if (!$model->set_one_field('def_closed'))
			$msg = JText::_("Error modifying item");

		$this->setRedirect( 'index.php?option=com_fss&view=ticketstatuss', $msg );
	}
}



