<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Functions relating to general admin stuff
 * 
 * NO TYPE SPECIFIC STUFF - Ie, no content / support / groups etc
 **/
class FSS_Admin_Helper
{
	static function NoPerm()
	{
		/*if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = FSS_Input::getCmd('option','');
			$view = FSS_Input::getCmd('view','');
			$layout = FSS_Input::getCmd('layout','');
			$Itemid = FSS_Input::getInt('Itemid',0);
			$url = FSSRoute::_("index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid); 	
		}
		$url = base64_encode($url);*/

		$return = FSS_Helper::getCurrentURLBase64();

		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'noperm.php');		
		
		return false;
	}
	
	static function id_to_asset($id)
	{
		switch ($id)
		{
		case 'announce':	
			return "com_fss.announce";
		case 'faqs':	
			return "com_fss.faq";
		case 'kb':	
			return "com_fss.kb";
		case 'glossary':	
			return "com_fss.glossary";
		}	
		
		return "com_fss";
	}
}