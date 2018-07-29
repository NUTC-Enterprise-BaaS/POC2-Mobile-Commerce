<?php

/*------------------------------------------------------------------------
# field.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

/**
 * Employee table
 *
 * @package		Joomla.Administrator
 * @subpackage	com_osservicesbooking
 * @since		1.5
 */
class OsAppTableField extends JTable
{
	var $id = 0;
	var $field_area = null;
	var $field_type = null;
	var $field_label = null;
	var $field_options = null;
	var $ordering = null;
	var $required = null;
	var $published = null;
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $_db
	 */	
	function __construct(&$_db)
	{
		parent::__construct('#__app_sch_fields', 'id', $_db);
	}
}