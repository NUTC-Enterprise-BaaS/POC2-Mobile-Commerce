<?php
/**
 * @version    SVN: <svn_id>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

/**
 * Class for custom gateway element
 *
 * @since  1.0.0
 */
class JFormFieldGatewayplg extends JFormField
{
	protected $type = 'Gatewayplg';

	/**
	 * Function to genarate html of custom element
	 *
	 * @return  HTML
	 *
	 * @since  1.0.0
	 */
	public function getInput()
	{
		return $this->fetchElement($this->name, $this->value, $this->element, $this->options['control']);
	}

	/**
	 * Function to fetch a tooltip
	 *
	 * @param   string  $name          name of field
	 * @param   string  $value         value of field
	 * @param   string  &$node         node of field
	 * @param   string  $control_name  control_name of field
	 *
	 * @return  HTML
	 *
	 * @since  1.0.0
	 */
	public function fetchElement($name, $value, &$node, $control_name)
	{
		$db = JFactory::getDBO();
		$condtion = array(0 => '\'payment\'');
		$condtionatype = join(',', $condtion);

		if (JVERSION >= '1.6.0')
		{
			$query = "SELECT extension_id as id,name,element,enabled as published FROM #__extensions WHERE folder in ($condtionatype) AND enabled=1";
		}
		else
		{
			$query = "SELECT id,name,element,published FROM #__plugins WHERE folder in ($condtionatype) AND published=1";
		}

		$db->setQuery($query);
		$gatewayplugin = $db->loadobjectList();

		$options = array();

		foreach ($gatewayplugin as $gateway)
		{
			$gatewayname = ucfirst(str_replace('plugpayment', '', $gateway->element));
			$options[] = JHtml::_('select.option', $gateway->element, $gatewayname);
		}

		if (JVERSION >= 1.6)
		{
			$fieldName = $name;
		}
		else
		{
			$fieldName = $control_name . '[' . $name . ']';
		}

		$default = array();
		$default[] = "bycheck";
		$default[] = "byorder";

		if (empty($value))
		{
			$value = $default;
		}

		$html = JHtml::_('select.genericlist', $options, $fieldName, 'class="inputbox"  multiple="multiple" size="5"', 'value', 'text', $value,
				$control_name . $name
						);

		if (JVERSION < '3.0')
		{
			$class = "sa-elements-gateways-link";
		}
		else
		{
			$class = "";
		}

		// Show link for payment plugins.
		$html .= '<a
			href="index.php?option=com_plugins&view=plugins&filter_folder=payment&filter_enabled="
			target="_blank"
			class="btn btn-small btn-primary ' . $class . '">'
				. JText::_('COM_SOCIALADS_SETTINGS_SETUP_PAYMENT_PLUGINS') .
			'</a>';

		return $html;
	}

	/**
	 * Function to fetch a tooltip
	 *
	 * @param   string  $label         label of field
	 * @param   string  $description   description of field
	 * @param   string  &$node         node of field
	 * @param   string  $control_name  control_name of field
	 * @param   string  $name          name of field
	 *
	 * @return  HTML
	 *
	 * @since  1.0.0
	 */
	public function fetchTooltip($label, $description, &$node, $control_name, $name)
	{
		return null;
	}
}
