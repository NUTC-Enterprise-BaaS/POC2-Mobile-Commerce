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
 * @package     Com_Socialads
 * @subpackage  com_socialads
 * @since       1.6
 */
class JFormFieldGatewaylist extends JFormFieldList
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
		$user = JFactory::getUser();
		$userid = $user->id;
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);

		$query->select('DISTINCT(processor)');
		$query->from('#__ad_orders');
		$query->group($db->quoteName('processor'));

		// Get the options.
		$db->setQuery($query);

		$allgateway = $db->loadObjectList();

		$options[] = JHtml::_('select.option', 0, JText::_('COM_SOCIALADS_SELECT_GATEWAY'));

		foreach ($allgateway as $c)
		{
			$options[] = JHtml::_('select.option', $c->processor);
		}
		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
		}

		return $options;
	}
}
