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

	FSS_Helper::StylesAndJS(array('autoscroll'));
	FSS_Helper::IncludeModal();

	$db = JFactory::getDBO();

	$prodid = $params->get('prodid');
	$dispcount = $params->get('dispcount');
	$listtype = $params->get('listtype');
	$maxlength = $params->get('maxlength');
	$showmore = $params->get('show_more');
	$showadd = $params->get('show_add');
	$maxheight = (int)$params->get('maxheight');
	$speed = (int)$params->get('speed');

	$comments = new FSS_Comments("test",$prodid);
	$comments->template = "comments_testmod";
	if (FSS_Settings::get('comments_testmod_use_custom'))
		$comments->template_type = 2;
	
	if ($listtype == 0)
		$comments->opt_order = 2;

	$comments->opt_no_mod = 1;
	$comments->opt_no_edit = 1;
	$comments->opt_show_add = 0;
	$comments->opt_max_length = $maxlength;
	$comments->opt_disable_pages = 1;
	
	$loop_scroll = $params->get('looped_scroll', 0);

	require( JModuleHelper::getLayoutPath( 'mod_fss_test' ) );
}