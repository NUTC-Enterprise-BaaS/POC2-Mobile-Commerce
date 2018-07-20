<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Example GUI plugin for Freestyle Support
 * 
 * Please let us know in a support ticket if you would like any new places adding where you can output
 * custom content within the system and we will get support added to the next version 
 * 
 * To enable this example plugin goto Components -> Freestyle Support -> Overview -> Pluings
 **/
class FSS_GUIPlugin_Sample extends FSS_Plugin_GUI
{
	var $title = "GUI Plugin Sample";
	var $description = "Several examples of using the gui plugins";
	
	/**
	 * This is method adminTabs
	 *
	 * End of the admin tab bar (with support, groups, reports etc)
	 */	
	function adminTabs()
	{
		/*$output = "";

		$class = "";
		if (JRequest::getVar('view') == "myview") $class = "active";
		$output[] = '<li class="' . $class . '">';
		$output[] = '<a href="' . JRoute::_( 'index.php?option=com_content' ) . '">';
		$output[] = '	<img src="' .  JURI::root( true ) . '/components/com_fss/assets/images/save_16.png">';
		$output[] = JText::_("New Tab (Plugin)");
		$output[] = '	</a>';
		$output[] = '</li> ';

		return implode("\n", $output);*/
	}

	/**
	 * This is method adminOverviewTop
	 *
	 * output at top of the admin overview
	 */	
	function adminOverviewTop()
	{
		//return "<h4>Admin Overview Top</h4>";
	}

	/**
	 * This is method adminOverviewBottom
	 *
	 * output at bottom of the admin overview
	 */	
	function adminOverviewBottom()
	{
		//return "<h4>Admin Overview Bottom</h4>";
	}

	/**
	 * This is method adminTicketReplyBar
	 *
	 * output after the "Reply" buttons on the admin pages
	 * 
	 * $params['ticket'] - Current ticket
	 * $params['use_buttons'] - Output as buttons instead of links
	 **/
	function adminTicketReplyBar($params)
	{

	}

	/**
	 * This is method adminTicketListTools
	 *
	 * adds items to the bottom of the list ticket tools menu
	 */	
	function adminTicketListTools()
	{
		/*$output = "";

		// divider
		$output[] = '<li class="divider"></li>';

		// new item
		$output[] = '<li>';
		$output[] = '	<a href="' . JRoute::_('index.php?option=com_content') . '">';
		$output[] = JText::_("New item (plugin)");
		$output[] = '	</a>';
		$output[] = '</li>';	

		return implode("\n", $output);*/
	}

	/**
	 * This is method adminSupportTabs_Start
	 *
	 * adds to the start of the ticket tabs (admin interface), before the "open" or "Search" tab
	 */	
	function adminSupportTabs_Start()
	{

	}

	/**
	 * This is method adminSupportTabs_Mid
	 *
	 * adds to the middle of the ticket tabs (admin interface), before "Other"
	 */	
	function adminSupportTabs_Mid()
	{

	}

	/**
	 * This is method adminSupportTabs_End
	 *
	 * adds to the end of the ticket tabs (admin interface), after "Other"
	 */	
	function adminSupportTabs_End()
	{

	}

	/**
	 * This is method adminSupportTabs_Other_Start
	 *
	 * adds to the start of the "Other" dropdown
	 */	
	function adminSupportTabs_Other_Start()
	{

	}

	/**
	 * This is method adminSupportTabs_Other_End
	 *
	 * adds to the end of the "Other" dropdown
	 */	
	function adminSupportTabs_Other_End()
	{

	}

	/**
	 * This is method adminTicketViewTools
	 *
	 * adds item to the view ticket tool bar
	 */	
	function adminTicketViewTools($ticket)
	{
		
	}


	/**
	 * This is method adminTicketViewToolsMenu
	 *
	 * adds item to the of bottom the view ticket tools menu
	 */	
	function adminTicketViewToolsMenu($ticket)
	{
		/*$output = "";

		// divider
		$output[] = '<li class="divider"></li>';

		// new item
		$output[] = '<li>';
		$output[] = '	<a href="' . JRoute::_('index.php?option=com_content') . '">';
		$output[] = $ticket->title;
		$output[] = '	</a>';
		$output[] = '</li>';	

		return implode("\n", $output);*/
	}


	/**
	 * This is method adminTicketViewToolsMenu
	 *
	 * output after the "Reply" buttons on the user pages
	 * 
	 * $params['ticket'] - Current ticket
	 * $params['use_buttons'] - Output as buttons instead of links
	 **/
	function userTicketReplyBar($ticket)
	{

	}
	
	/* User Support Tabs functions: 
	
	userSupportTabs_Start
	userSupportTabs_AfterNew
	userSupportTabs_BefreView
	userSupportTabs_End
	*/
}