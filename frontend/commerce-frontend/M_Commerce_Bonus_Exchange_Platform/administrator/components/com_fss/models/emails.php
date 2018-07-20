<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );

class FsssModelEmails extends JModelLegacy
{
	var $_data;

	var $_total = null;

	var $lists = array(0);

	var $_pagination = null;

	function __construct()
	{
		parent::__construct();

		$mainframe = JFactory::getApplication(); global $option;
		$context = "emails_";

		// Get pagination request variables
		$layout = JRequest::getString('layout');
	}

	function _buildQuery()
	{
		$db	= JFactory::getDBO();

		$query = ' SELECT * FROM #__fss_emails';
		return $query;
	}

	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}

		return $this->_data;
	}
}



