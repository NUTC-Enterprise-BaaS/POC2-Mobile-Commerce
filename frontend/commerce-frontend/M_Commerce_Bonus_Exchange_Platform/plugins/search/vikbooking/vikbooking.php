<?php
/**
 * @copyright	Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class plgSearchVikbooking extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	* @return array An array of search areas
	*/
	function onContentSearchAreas()
	{
		static $areas = array(
			'vikbooking' => 'Rooms'
		);
		return $areas;
	}

	/**
	* Contacts Search method
	*
	* The sql must return the following fields that are used in a common display
	* routine: href, title, section, created, text, browsernav
	* @param string Target search string
	* @param string matching option, exact|any|all
	* @param string ordering option, newest|oldest|popular|alpha|category
	 */
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$section = JText::_('Vikbooking');

		$text	= $db->Quote('%'.$db->escape($text, true).'%', false);

		$rows = array();
		
		$query="SELECT `#__vikbooking_rooms`.`id`,`#__vikbooking_rooms`.`name`,`#__vikbooking_rooms`.`img`,`#__vikbooking_rooms`.`smalldesc`,`#__vikbooking_rooms`.`info` FROM `#__vikbooking_rooms` WHERE `#__vikbooking_rooms`.`avail`='1' AND `#__vikbooking_rooms`.`name` LIKE ".$text." ORDER BY `#__vikbooking_rooms`.`name` ASC";
		
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();

		if ($rows) {
			foreach($rows as $key => $row) {
				$rows[$key]->title = $row->title;
				$rows[$key]->text = (strlen($row->smalldesc) > 0 ? $row->smalldesc : $row->info);
				$rows[$key]->section = $row->name;
				$rows[$key]->browsernav = '1';
				$rows[$key]->href = 'index.php?option=com_vikbooking&view=roomdetails&roomid='.$row->id;
				unset($rows[$key]->id);
				unset($rows[$key]->smalldesc);
				unset($rows[$key]->name);
			}
		}
		
		return $rows;
	}
}
