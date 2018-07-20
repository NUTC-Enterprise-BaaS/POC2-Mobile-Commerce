<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerGlossary extends FsssController
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

    function pick()
    {
        JRequest::setVar( 'view', 'glossarys' );
        JRequest::setVar( 'layout', 'pick'  );
        
        parent::display();
    }

	function edit()
	{
		JRequest::setVar( 'view', 'glossary' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('glossary');

        $post = JRequest::get('post');
        $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['longdesc'] = JRequest::getVar('longdesc', '', 'post', 'string', JREQUEST_ALLOWRAW);
       
		if ($model->store($post)) {
			$msg = JText::_("GLOSSARY_ITEM_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_GLOSSARY_ITEM");
		}


		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=glossary&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=glossary&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=glossarys';
		}
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('glossary');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_GLOSSARY_ITEM_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("GLOSSARY_ITEM_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=glossarys', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=glossarys', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('glossary');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_AN_GLOSSARY_ITEM");

		$this->setRedirect( 'index.php?option=com_fss&view=glossarys', $msg );
	}

	function publish()
	{
		$model = $this->getModel('glossary');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_AN_GLOSSARY_ITEM");

		$this->setRedirect( 'index.php?option=com_fss&view=glossarys', $msg );
	}
}



