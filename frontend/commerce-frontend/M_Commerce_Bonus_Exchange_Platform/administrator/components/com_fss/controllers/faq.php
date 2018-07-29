<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerFaq extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
		$this->registerTask( 'unfeature', 'unfeature' );
		$this->registerTask( 'feature', 'feature' );
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
        JRequest::setVar( 'view', 'faqs' );
        JRequest::setVar( 'layout', 'pick'  );
        
        parent::display();
    }

	function edit()
	{
		JRequest::setVar( 'view', 'faq' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('faq');

        $post = JRequest::get('post');
        $post['answer'] = JRequest::getVar('answer', '', 'post', 'string', JREQUEST_ALLOWRAW);
        
        $post['fullanswer'] = "";
        
        if (strpos($post['answer'],"system-readmore") > 0)
        {
            $pos = strpos($post['answer'],"system-readmore");
            $answer = substr($post['answer'], 0, $pos);
            $answer = substr($answer,0, strrpos($answer,"<"));
            
            $rest = substr($post['answer'], $pos);
            $rest = substr($rest, strpos($rest,">")+1);
            
            $post['answer'] = $answer;
            $post['fullanswer'] = $rest;                           
        }
        
		if ($model->store($post)) {
			$msg = JText::_("FAQ_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_FAQ");
		}

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=faq&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=faq&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=faqs';
		}
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('faq');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_FAQ_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("FAQ_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('faq');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function publish()
	{
		$model = $this->getModel('faq');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function unfeature()
	{
		$model = $this->getModel('faq');
		if (!$model->unfeature())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNFEATURING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function feature()
	{
		$model = $this->getModel('faq');
		if (!$model->feature())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_FEATURING_AN_FAQ");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('faq');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('faq');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('faq');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=faqs', $msg );
	}
	
	function autosort()
	{
		//echo "Auto Sort<br>";	
		
		$qry = "SELECT id FROM #__fss_faq_faq ORDER BY question";
		$db = JFactory::getDBO();
		
		//echo "Qry : $qry<br>";
		
		$db->setQuery($qry);
		
		$rows = $db->loadObjectList();	
		
		$order = 1;
		foreach ($rows as $row)
		{
			$qry = "UPDATE #__fss_faq_faq SET ordering = $order WHERE id = {$row->id}";
			$db->setQuery($qry);
			$db->Query(); 	
			$order++;
		}
		$this->setRedirect( 'index.php?option=com_fss&view=faqs', "FAQs ordered alphabetically" );
	}
}



