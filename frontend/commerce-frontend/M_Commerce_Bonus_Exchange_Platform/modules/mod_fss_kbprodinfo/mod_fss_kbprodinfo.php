<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

// Include the syndicate functions only once
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);
if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

	FSS_Helper::StylesAndJS();

	$prodid = FSS_Input::getInt('prodid');
	if ($prodid > 0)
	{
		$show_prod = $params->get('show_prod');
		$show_cat = $params->get('show_cat');
		$show_art = $params->get('show_art');
		
		$show = true;
		
		if (FSS_Input::getInt('kbartid'))
		{
			if (!$show_art)
				$show = false;
		} else if (FSS_Input::getInt('catid'))
		{
			if (!$show_cat)
				$show = false;
		} else {
			if (!$show_prod)
				$show = false;
		}
		
		if ($show)
		{
			$db = JFactory::getDBO();
			$query = "SELECT extratext FROM #__fss_prod WHERE id = " . $prodid;

			$db->setQuery($query);
			$rows = $db->loadAssoc();
			
			if ($rows['extratext'])
				require( JModuleHelper::getLayoutPath( 'mod_fss_kbprodinfo' ) );
		}
	} else {
		$module->showtitle = 0;
		$attribs['style'] = "hide_me";
	}
}