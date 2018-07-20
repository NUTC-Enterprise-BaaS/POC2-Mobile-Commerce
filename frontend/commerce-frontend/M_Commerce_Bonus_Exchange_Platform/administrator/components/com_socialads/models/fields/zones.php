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
class JFormFieldZones extends JFormFieldList
{
	protected $type = 'Zones';
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
		$user = JFactory::getUser();
		$userid = $user->id;
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('z.id, z.zone_name');
		$query->from('#__ad_data AS d');
		$query->join('LEFT', $db->quoteName('#__ad_zone', 'z') . 'ON' . $db->quoteName('z.id') . '=' . $db->quoteName('d.ad_zone'));
		$query->group($db->quoteName('z.zone_name'));

		// Get the options.
		$db->setQuery($query);

		$allZones = $db->loadObjectList();

		$options = array();

		$options[] = JHtml::_('select.option', 0, JText::_('COM_SOCIALADS_SELECT_ZONE'));

		foreach ($allZones as $c)
		{
			$options[] = JHtml::_('select.option', $c->id, $c->zone_name);
		}

		return $options;
	}
}
