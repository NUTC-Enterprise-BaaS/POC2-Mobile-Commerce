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

	global $posdata, $date_format;

	FSS_Helper::StylesAndJS(array('autoscroll'), array("modules/mod_fss_faqs/tmpl/mod_fss_faqs.css"));
	
	$document = JFactory::getDocument();
	$document->addScript(JURI::root(true).'/components/com_fss/assets/js/module.js'); 
	
	$catid = $params->get('catid');
	$dispcount = $params->get('dispcount');
	$maxheight = (int)$params->get('maxheight');
	$mode = $params->get('listtype');
	$per_page = $params->get('per_page');

	if ($mode == "accordion")
		$maxheight = 0;
	
	$db = JFactory::getDBO();

	$qry = "SELECT * FROM #__fss_faq_faq";

	$where = array();
	$where[] = "published = 1";

	// for cats
	if ($catid > 0)
	{
		$where[] = "faq_cat_id = " .  FSSJ3Helper::getEscaped($db, $catid);
	} else if ($catid == -5)
	{
		$where[] = "featured = 1";
	}

	if (count($where) > 0)
	{
		$qry .= " WHERE " . implode(" AND ",$where);	
	}

	$order = "ordering";
	$qry .= " ORDER BY $order ";

	if ($dispcount > 0)
		$qry .= " LIMIT $dispcount";

	$db->setQuery($qry);

	$data = $db->loadObjectList();

	$posdata = array();

	if ($mode == "popup")
	{
		FSS_Helper::IncludeModal();
	}
	
	require( JModuleHelper::getLayoutPath( 'mod_fss_faqs' ) );
}