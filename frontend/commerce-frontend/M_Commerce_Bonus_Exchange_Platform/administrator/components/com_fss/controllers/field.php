<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerField extends FsssController
{
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add', 'edit', 'prods', 'depts' );
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
		JRequest::setVar( 'view', 'field' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);
		
		$model = $this->getModel('field');
		parent::display();
	}
	
	function plugin_form()
	{
		JRequest::setVar( 'view', 'field' );
		JRequest::setVar( 'layout', 'plugin_form'  );
		parent::display();
		exit;
	}

	function save()
	{
		$model = $this->getModel('field');

		$post = JRequest::get('post');
		$post['helptext'] = JRequest::getVar('helptext', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['translation'] = JRequest::getVar('translation', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['javascript'] = JRequest::getVar('javascript', '', 'post', 'string', JREQUEST_ALLOWRAW);

		if (!isset($post['alias']) || $post['alias'] == "")
			$post['alias'] = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $post['description']));
		
		if ($post['alias'] == "")
			$post['alias'] = date("Y-m-d-H-i-s");
		
		$a = $post['alias'];
		$c = 1;
		while (true)
		{
			$db = JFactory::getDBO();
			$sql = "SELECT * FROM #__fss_field WHERE alias = '" . $db->escape($a) . "'";
			if ($post['id'] > 0)
				$sql .= " AND id != " . $post['id'];
			
			$db->setQuery($sql);
			if (!$db->loadObject())
				break;
			
			$a = $post['alias']."-" . $c++;
		}
		$post['alias'] = $a;
		
		if ($model->store($post)) {
			$msg = JText::_("FIELD_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_FIELD");
		}

		// Check the table in so it can be edited.... we are done with it anyway

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=field&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=field&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=fields';
		}
		$this->setRedirect($link, $msg);
	}

	function unpublish()
	{
		$model = $this->getModel('field');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_A_FIELD");

		$this->setRedirect( 'index.php?option=com_fss&view=fields', $msg );
	}

	function publish()
	{
		$model = $this->getModel('field');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_A_FIELD");

		$this->setRedirect( 'index.php?option=com_fss&view=fields', $msg );
	}

	function remove()
	{
		$model = $this->getModel('field');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_FIELD_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("FIELD_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=fields', $msg );
	}

	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=fields', $msg );
	}

	function prods()
	{
		JRequest::setVar( 'view', 'field' );
		JRequest::setVar( 'layout', 'prods'  );
		
		parent::display();
	}

	function depts()
	{
		JRequest::setVar( 'view', 'field' );
		JRequest::setVar( 'layout', 'depts'  );
		
		parent::display();
	}

	function orderup()
	{
		$model = $this->getModel('field');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=fields', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('field');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=fields', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('field');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=fields', $msg );
	}

}



