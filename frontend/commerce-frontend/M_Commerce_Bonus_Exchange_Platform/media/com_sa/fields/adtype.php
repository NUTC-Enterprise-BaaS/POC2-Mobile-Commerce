<?php
/**
 * @version    SVN: <svn_id>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Element for activity stream
 *
 * @since  1.0.0
 */
class JFormFieldAdtype extends JFormField
{
	public $type = 'adtype';

	/**
	 * Function to get the input
	 *
	 * @return  Filter
	 *
	 * @since  1.0.0
	 */
	public function getInput()
	{
		return self::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

	/**
	 * Function to get the activity stream filter
	 *
	 * @param   STRING  $name          name of the field
	 * @param   STRING  $value         value of the field
	 * @param   STRING  &$node         name of the field
	 * @param   STRING  $control_name  name of the field
	 *
	 * @return  Filter
	 *
	 * @since  1.0.0
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{
		$options[] = JHTML::_('select.option', 'text_media', JText::_('COM_SOCIALADS_TITLE_ZONE_AD_TYPE_TEXT_AND_MEDIA'));
		$options[] = JHTML::_('select.option', 'text', JText::_('COM_SOCIALADS_TITLE_ZONE_AD_TYPE_TEXT'));
		$options[] = JHTML::_('select.option', 'media', JText::_('COM_SOCIALADS_TITLE_ZONE_AD_TYPE_MEDIA'));
		$fieldName = $name;

		$default = array();
		$default[] = 'text_media';
		$default[] = 'text';
		$default[] = 'media';

		if (empty($value))
		{
			$value = $default;
		}

		$optionalField = 'class="inputbox adtypeFilter"  multiple="multiple" size="10"';

		return JHTML::_('select.genericlist', $options, $fieldName, $optionalField, 'value', 'text', $value, $control_name . $name);
	}
}
