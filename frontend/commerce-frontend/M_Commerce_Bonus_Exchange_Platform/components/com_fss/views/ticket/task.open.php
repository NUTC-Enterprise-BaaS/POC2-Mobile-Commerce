<?php

/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Open extends Task_Helper
{
	/**
	 * Updates priority for ticket
	 */
	function reset()
	{
		$session = JFactory::getSession();
		$session->clear('ticket_email');
		$session->clear('ticket_reference');
		$session->clear('ticket_name');
		$session->clear('ticket_pass');
		$session->clear('ticket_find');

		return $this->redirect(FSSRoute::_("index.php?option=com_fss&view=ticket&layout=open"));
	}	
	
	function product()
	{
		$mainframe = JFactory::getApplication();
		$this->limit = $mainframe->getUserStateFromRequest('global.list.limit_prod', 'limit', FSS_Settings::Get('ticket_prod_per_page'), 'int');
		$this->limitstart = FSS_Input::getInt('limitstart');
		$this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);

		$this->view->search = FSS_Input::GetString("search");
		$this->view->products = SupportHelper::getProductsUserOpen($this->limitstart, $this->limit, $this->view->search);
		
		$this->view->pagination = new JPaginationAjax(SupportHelper::getProductsUserOpenCount($this->view->search), $this->limitstart, $this->limit );
		$this->view->_display("search");
		exit;
	}
	
	function find()
	{
		$search = FSS_Input::getString("search");

		$this->results = array();
		
		$plugins = FSS_Helper::getPlugins("ticketopensearch");		
		
		foreach ($plugins as $plugin)
		{
			$file = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'ticketopensearch'.DS.$plugin->name.".php";
			if (!file_exists($file)) { echo "No $file<br>"; continue; }
			
			require_once($file);
			
			$class = "FSS_Plugin_OpenSearch_" . $plugin->name;
			if (!class_exists($class)) { echo "No $class<br>"; continue; }
			
			$plo = new $class();
			
			$this->results = array_merge($this->results, $plo->search($search));
		}

		usort($this->results, array($this, "sort_find"));

		$this->results = array_slice($this->results, 0, 30);

		$this->view->results = $this->results;
		$this->view->_display("faqsearch");
		exit;
	}
	
	function sort_find($a, $b)
	{
		return $a->score < $b->score;
	}
}

class FSS_Plugin_OpenSearch extends FSS_Plugin
{
	function search($search)
	{
		return array();
	}	
}

class FSS_OpenSearch_Result {
	var $title;
	var $type;
	var $link;
	var $score;	
}