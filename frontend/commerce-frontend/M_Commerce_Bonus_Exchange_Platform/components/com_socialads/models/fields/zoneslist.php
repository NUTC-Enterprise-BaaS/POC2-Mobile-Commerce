<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Custom Field class for the Joomla Framework.
 *
 * @package  Com_Socialads
 *
 * @since    1.6
 */
class JFormFieldZoneslist extends JFormFieldList
{
	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 *
	 * @since	1.6
	 */
	public function getOptions()
	{
		// Initialize variables.
		$options = array();
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('id,zone_name');
		$query->from('#__ad_zone');
		$query->where('state=1');

		// Get the options.
		$db->setQuery($query);

		$allzones = $db->loadObjectList();

		$options = array();

		$options[] = JHtml::_('select.option', 0, JText::_('COM_SOCIALADS_SELECT_ZONE'));

		foreach ($allzones as $c)
		{
			$options[] = JHtml::_('select.option', $c->id, $c->zone_name);
		}

		return $options;
	}
}
