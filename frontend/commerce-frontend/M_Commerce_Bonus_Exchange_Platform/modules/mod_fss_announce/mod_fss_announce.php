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
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php' );

	FSS_Helper::StylesAndJS(array('autoscroll'));

	$db = JFactory::getDBO();

	jimport('joomla.utilities.date');

	$query = "SELECT * FROM #__fss_announce";

	$where = array();
	$where[] = "published = 1";
	$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
	$user = JFactory::getUser();
	$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

	if (count($where) > 0)
		$query .= " WHERE " . implode(" AND ",$where);

	$query .= " ORDER BY added DESC ";

	if ($params->get('listall') == 0)
	{
		$query .= " LIMIT " . $params->get('dispcount');
	}

	$maxheight = (int)$params->get('maxheight');

	$db->setQuery($query);
	$rows = $db->loadAssocList();

	$parser = new FSSParser();
	$type = FSS_Settings::Get('announcemod_use_custom') ? 2 : 3;
	$parser->Load("announcemod", $type);
	
	$parser->SetVar('showdate', $params->get('show_date'));
	if ($params->get('viewannounce'))
		$parser->SetVar('readmore', JText::_("READ_MORE"));
	
	require( JModuleHelper::getLayoutPath( 'mod_fss_announce' ) );
}