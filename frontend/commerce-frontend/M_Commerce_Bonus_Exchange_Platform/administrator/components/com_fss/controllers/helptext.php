<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerHelptext extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'apply', 'save' );
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'helptext' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('helptext');

        $post = JRequest::get('post');
		$post['message'] = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post['translation'] = JRequest::getVar('translation', '', 'post', 'string', JREQUEST_ALLOWRAW);

		$db = JFactory::getDBO();
		$query = "UPDATE #__fss_help_text SET message = '" . $db->escape($post['message']) . "', translation = \"" . $db->escape($post['translation']) . "\" WHERE identifier = '" . $db->escape($post['identifier']) . "'";
		$db->setQuery($query);
		$db->Query();        

		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=helptext&task=edit&identifier=" . $post['identifier'];
		} else {
			$link = 'index.php?option=com_fss&view=helptexts';
		}
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=helptexts', $msg );
	}

	function unpublish()
	{
		$model = $this->getModel('helptext');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING");

		$this->setRedirect( 'index.php?option=com_fss&view=helptexts', $msg );
	}

	function publish()
	{
		$model = $this->getModel('helptext');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING");

		$this->setRedirect( 'index.php?option=com_fss&view=helptexts', $msg );
	}
}



