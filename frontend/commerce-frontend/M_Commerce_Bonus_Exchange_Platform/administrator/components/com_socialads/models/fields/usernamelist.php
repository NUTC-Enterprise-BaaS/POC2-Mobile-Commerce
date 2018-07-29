<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
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
 * @package     Com_Socialads
 * @subpackage  com_socialads
 * @since       1.6
 */
class JFormFieldUsernamelist extends JFormFieldList
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
		$options = array();
		$user = JFactory::getUser();
		$userid = $user->id;
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select("DISTINCT (c.created_by) AS id");
		$query->select($db->quoteName("u.name", "name"));
		$query->from($db->quoteName('#__ad_campaign', 'c'));
		$query->join('LEFT', $db->quoteName("#__users", "u") . " ON " . $db->quoteName("u.id") . " = " . $db->quoteName("c.created_by"));
		$query->order($db->quoteName("name"));

		// Get the options.
		$db->setQuery($query);

		$allusers = $db->loadObjectList();
		$options[] = JHtml::_('select.option', 0, JText::_('COM_SOCIALADS_SELECT_USERNAME'));

		foreach ($allusers AS $user)
		{
			$options[] = JHtml::_('select.option', $user->id, $user->name);
		}

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $this->_db->getErrorMsg());
		}

		return $options;
	}
}
