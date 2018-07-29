<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_GUIPlugin_Extra_Tabs extends FSS_Plugin_GUI
{
	var $title = "Extra Admin Tabs";
	var $description = "Add a new tabs to the admin support tab list. Can select products, departments etc.";

	function outputTabs($location)
	{
		if (!$this->loadSettings()) return;

		$output = "";

		if ($this->settings->products->show && $this->settings->products->location == $location) $output .= $this->getProductOutput($location);
		if ($this->settings->departments->show && $this->settings->departments->location == $location) $output .= $this->getDepartmentOutput($location);

		return $output;
	}

	function adminSupportTabs_Start()
	{
		return $this->outputTabs(0);
	}

	function adminSupportTabs_Mid()
	{
		return $this->outputTabs(1);
	}

	function adminSupportTabs_End()
	{
		return $this->outputTabs(2);
	}

	function adminSupportTabs_Other_Start()
	{
		return $this->outputTabs(3);
	}
	function adminSupportTabs_Other_End()
	{
		return $this->outputTabs(4);
	}

	function getProductOutput($location)
	{
		$output = "";

		if ($location == 4)
		{
			$output[] = "<li class='divider'></li>";
		}

		$products = SupportHelper::getProducts();
		$prod_ids = explode(";", $this->settings->products->list);

		if ($this->settings->products->dropdown && $location < 3)
		{
			$output[] = '	<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#" onclick="return false;">
									' . JText::_('PRODUCTS') . '<b class="caret bottom-up"></b>
								</a>
				
								<ul class="dropdown-menu bottom-up pull-left">  
			';
		}

		foreach ($products as $prod)
		{
			if ($this->settings->products->use === "1") if (!in_array($prod->id, $prod_ids)) continue;
			if ($this->settings->products->use === "0") if (in_array($prod->id, $prod_ids)) continue;

			$status = "";
			if ($this->settings->products->status != "") $status = "&status=" . $this->settings->products->status;

			$class = "";
			if (JRequest::getVar('product') == $prod->id) $class = "active";
			$output[] = '<li class="' . $class . '">';
			$output[] = '<a href="' . JRoute::_( 'index.php?option=com_fss&view=admin_support&what=search&searchtype=advanced&showbasic=1&product=' . $prod->id . $status ) . '">';
			$output[] = $prod->title;
			$output[] = '	</a>';
			$output[] = '</li> ';
		}

		if ($this->settings->products->dropdown && $location < 3)
		{
			$output[] = "</ul></li>";
		}

		if ($location == 3)
		{
			$output[] = "<li class='divider'></li>";
		}

		return implode("\n", $output);
	}

	function getDepartmentOutput($location)
	{
		$output = "";

		if ($location == 4)
		{
			$output[] = "<li class='divider'></li>";
		}


		if ($this->settings->departments->dropdown && $location < 3)
		{
			$output[] = '	<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#" onclick="return false;">
									' . JText::_('DEPARTMENTS') . '<b class="caret bottom-up"></b>
								</a>
				
								<ul class="dropdown-menu bottom-up pull-left">  
			';
		}

		$departments = SupportHelper::getDepartments();
		$dept_ids = explode(";", $this->settings->departments->list);

		foreach ($departments as $dept)
		{
			if ($this->settings->departments->use === "1") if (!in_array($dept->id, $dept_ids)) continue;
			if ($this->settings->departments->use === "0") if (in_array($dept->id, $dept_ids)) continue;

			$status = "";
			if ($this->settings->departments->status != "") $status = "&status=" . $this->settings->departments->status;

			$class = "";
			if (JRequest::getVar('department') == $dept->id) $class = "active";
			$output[] = '<li class="' . $class . '">';
			$output[] = '<a href="' . JRoute::_( 'index.php?option=com_fss&view=admin_support&what=search&searchtype=advanced&showbasic=1&department=' . $dept->id . $status ) . '">';
			$output[] = $dept->title;
			$output[] = '	</a>';
			$output[] = '</li> ';
		}

		if ($this->settings->departments->dropdown && $location < 3)
		{
			$output[] = "</ul></li>";
		}

		if ($location == 3)
		{
			$output[] = "<li class='divider'></li>";
		}
		return implode("\n", $output);
	}
}