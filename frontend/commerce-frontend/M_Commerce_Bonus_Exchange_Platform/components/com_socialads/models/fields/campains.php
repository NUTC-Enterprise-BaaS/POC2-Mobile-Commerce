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
class JFormFieldCampains extends JFormFieldList
{
	protected $type = 'Campains';
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

		$query->select('id, campaign');
		$query->from('#__ad_campaign AS ac');

		// Get the options.
		$db->setQuery($query);

		$allcampaigns = $db->loadObjectList();

		$options = array();

		$options[] = JHtml::_('select.option', 0, JText::_('COM_SOCIALADS_SELECT_CAMPAIGN'));

		foreach ($allcampaigns as $c)
		{
			$options[] = JHtml::_('select.option', $c->id, $c->campaign);
		}

		return $options;
	}
}
