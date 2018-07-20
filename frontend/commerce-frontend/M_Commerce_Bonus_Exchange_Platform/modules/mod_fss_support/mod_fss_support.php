<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);
if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'models'.DS.'admin.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'models'.DS.'ticket.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'permission.php' );

	$db = JFactory::getDBO();

	$listtype = $params->get('listtype');
	$tickets_user_only = $params->get('tickets_user_only');
	$tickets_closed_status = $params->get('tickets_closed_status');
	$tickets_archived_status = $params->get('tickets_archived_status');
	$tickets_show_my_tickets = $params->get('tickets_show_my_tickets');
	$tickets_open_ticket = $params->get('tickets_open_ticket');

	if ($listtype == "admin" || $listtype == "")
	{
		if (FSS_Permission::CanModerate() || FSS_Permission::auth("fss.handler", "com_fss.support_admin"))
		{
			$model = new FssModelAdmin();

			$comments = new FSS_Comments(null,null);
			$moderatecount = $comments->GetModerateTotal();

			FSS_Helper::StylesAndJS();
		
			require( JModuleHelper::getLayoutPath( 'mod_fss_support' ) );
		} else {
			$module->showtitle = 0;
			$attribs['style'] = "hide_me";	
		}
	} else if ($listtype == "user")
	{
		$user = JFactory::getUser();
		if ($user->id > 0)
		{
			$model = new FssModelTicket();

			FSS_Helper::StylesAndJS();

			require( JModuleHelper::getLayoutPath( 'mod_fss_support', 'user' ) );
		} else {
			$module->showtitle = 0;
			$attribs['style'] = "hide_me";
		}
	}
}