<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldFSSPrefsReset extends JFormFieldText
{
	protected $type = 'FSSPrefsReset';

	protected function getInput()
	{
		$db = JFactory::getDBO();
		
		$sql = "SELECT count(*) as count FROM #__fss_users WHERE settings <> ''";
		$db->setQuery($sql);
		$data = $db->loadObject();
		
		$output = array();
		$output[] = "<p>There are {$data->count} users with handler prefernces set</p>";

		$output[] = "<a href='" . JRoute::_("index.php?option=com_fss&task=reset&controller=prefs") . "' class='btn btn-default'>Reset Prefs</a>";	
		
		return implode($output);
	}
}
