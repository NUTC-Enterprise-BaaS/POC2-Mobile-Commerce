<?php
/**
 * @version    SVN: <svn_id>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 20012-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access.
defined('_JEXEC') or die();

jimport('joomla.form.formfield');

/**
 * JFormFieldBssetup for setup instruct class.
 *
 * @package     SocialAds
 * @subpackage  com_socialads
 * @since       1.6.7
 */
class JFormFieldBssetup extends JFormField
{
	/**
	 * Get the input
	 *
	 * @return  Input field
	 *
	 * @since 1.8
	 */
	public function getInput()
	{
		return $this->fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

	/**
	 * Build the input field
	 *
	 * @param   String  $name          Name of the field
	 * @param   String  $value         Value of the field
	 * @param   String  $node          Node
	 * @param   String  $control_name  Name of the control class
	 *
	 * @return  Build the input field
	 *
	 * @since 1.8
	 */
	public function fetchElement($name, $value, $node, $control_name)
	{
		$actionLink = JURI::base() . "index.php?option=com_socialads&view=setup&layout=setup";

		// Show link for payment plugins.
		$html = '<a
			href="' . $actionLink . '" target="_blank"
			class="btn btn-small btn-primary ">'
				. JText::_('COM_SOCIALADS_CLICK_BS_SETUP_INSTRUCTION') .
			'</a>';

		return $html;
	}
}
