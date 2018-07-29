<?php
/**------------------------------------------------------------------------
 * mod_vikbooking_rooms - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - Extensionsforjoomla.com
 * copyright Copyright (C) 2014 extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldHeader extends JFormField
{
	protected $type = 'Header';

	function getInput()
	{
		return $this->fetchElement($this->element['name'], $this->value, $this->element, $this->name);
	}
	
	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = array(JText::_($value));
		foreach ($node->children() as $option)
		{
			$options[] = $option->data();
		}
		
		return sprintf('<div style="padding:10px; color:#666; font-weight:bold; border-top-left-radius:4px; border-top-right-radius:4px; -moz-border-top-left-radius:4px; -moz-border-top-right-radius:4px; -webkit-border-top-left-radius:4px; -webkit-border-top-right-radius:4px; background:#f6f6f6; border:1px solid #ccc; border-top:2px solid #99CC00;">%s</div>', call_user_func_array('sprintf', $options));
	}
}
?>
