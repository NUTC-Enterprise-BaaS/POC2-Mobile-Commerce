<?php
/**
 * @package		com_jmailalerts
 * @version		$versionID$
 * @author		TechJoomla
 * @author mail	extensions@techjoomla.com
 * @website		http://techjoomla.com
 * @copyright	Copyright © 2009-2013 TechJoomla. All rights reserved.
 * @license		GNU General Public License version 2, or later
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldCbfields extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var string
	 * @since 1.6
	 */
	public $type = 'Cbfields';
	/**
	 * Method to get the field options.
	 *
	 * @return array The field option objects.
	 * @since 1.6
	 */
	protected function getOptions()
	{
		// Get the database object and a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		//Build the query.
		$type = "'text','textarea','select','multiselect','checkbox','multicheckbox','radio'";
		$query="SELECT name AS value, title AS text
				FROM #__comprofiler_fields
				WHERE `table` LIKE '#__comprofiler'
				AND published = 1
				AND type IN (".$type.")
				";

		// Set the query and load the options.
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()){
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		if($options)
		{
			foreach ($options as $i=>$option) {
				$options[$i]->text = JText::_($option->text);
			}
			//Merge any additional options in the XML definition.
			$options = array_merge(parent::getOptions(), $options);
			return $options;
		}
		else{
			return JText::_('NO_FIELDS_FOUND');
		}
	}
}
?>
