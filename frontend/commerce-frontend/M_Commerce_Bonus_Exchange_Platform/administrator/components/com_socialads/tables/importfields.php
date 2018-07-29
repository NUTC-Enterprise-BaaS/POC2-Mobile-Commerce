<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access
defined('_JEXEC') or die(';)');

/**
 * social_targetting Table class
 *
 * @since  1.6
 **/
class TableImportfields extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   object  &$db  Database connector object
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function Tableimportfields (&$db)
	{
		parent::__construct('#__ad_fields_mapping', 'mapping_id', $db);
	}
}
