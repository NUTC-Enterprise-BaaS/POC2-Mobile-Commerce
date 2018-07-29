<?php
/**
 * @version    SVN: <svn_id>
 * @package    JBolo
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die();
jimport('joomla.html.parameter.element');
jimport('joomla.form.formfield');
jimport('joomla.html.html.access');

/**
 * Jform field class.
 *
 * @since  3.0
 **/
class JFormFieldPriority extends JFormField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 *
	 * @since	1.6
	 */
	public function getInput()
	{
		return $this->adsPriority($this->name, $this->value, $this->element, $this->options['controls']);
	}

	/**
	 * Function to genarate html of custom element
	 *
	 * @param   STRING  $name          Name of the element
	 * @param   STRING  $value         Default value of the element
	 * @param   STRING  $node          asa
	 * @param   STRING  $control_name  asda
	 *
	 * @return  HTML
	 *
	 * @since  1.0.0
	 */
	public function adsPriority($name, $value, $node, $control_name)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$html = '';

		if ($data = $sa_params->get('priority'))
		{
			$defineArray[0] = JText::_('COM_SOCIALADS_FORM_LBL_SOCIALADS');
			$defineArray[1] = JText::_('COM_SOCIALADS_FORM_LBL_GEOADS');
			$defineArray[2] = JText::_('COM_SOCIALADS_FORM_LBL_CONTEXTUAL_ADS');

			foreach ($data as $value)
			{
				$options[] = JHtml::_('select.option', $value, $defineArray[$value]);
			}
		}
		else
		{
			$options[] = JHtml::_('select.option', '0', JText::_('COM_SOCIALADS_FORM_LBL_SOCIALADS'));
			$options[] = JHtml::_('select.option', '1', JText::_('COM_SOCIALADS_FORM_LBL_GEOADS'));
			$options[] = JHtml::_('select.option', '2', JText::_('COM_SOCIALADS_FORM_LBL_CONTEXTUAL_ADS'));
		}

		$fieldName = $name;
		$i = 0;

		foreach ($node as $key => $value)
		{
			if ($value == 0)
			{
				$valuestr = JText::_('COM_SOCIALADS_FORM_LBL_SOCIALADS');
			}
			elseif ($value == 1)
			{
				$valuestr = JText::_('COM_SOCIALADS_FORM_LBL_GEOADS');
			}
			elseif ($value == 2)
			{
				$valuestr = JText::_('COM_SOCIALADS_FORM_LBL_CONTEXTUAL_ADS');
			}

			$singleselect_prio[] = JHtml::_('select.option', $value, $valuestr);
			$i++;
		}

		$html .= JHtml::_('select.genericlist', $options, $fieldName,
							'class="inputbox chzn-done required" multiple="multiple" size="5" data-chosen="sa"',
							"value", "text", $value, $control_name . $name
							);
		$html .= '<input type="button" class="btn btn-success" value="Move Up" id="mup" onclick="moveUpItem()">
				<input type="button" class="btn btn-warning" value="Move Down" id="mdown" onclick="moveDownItem()">';

		return $html;
	}
}
?>
<script>
 function moveUpItem(){
  jQuery('#jformpriority option:selected').each(function(){
			jQuery(this).insertBefore(jQuery(this).prev());
  });

  jQuery('#jformpriority option').each(function(){
	jQuery(this).attr('selected', 'selected');
  });
 }

 function moveDownItem(){
  jQuery('#jformpriority option:selected').each(function(){
   	jQuery(this).insertAfter(jQuery(this).next());

  });
  jQuery('#jformpriority option').each(function(){
	jQuery(this).attr('selected', 'selected');
  });
 }

function selectAllPriorities(){
	jQuery('#jformpriority option').each(function(){
		jQuery(this).attr('selected', 'selected');
	});
}

jQuery(document).ready(function()
{
	/*Haha - let's trick Joomla toolbar buttons Joomla 3.x*/
	jQuery("button[onclick=\"Joomla.submitbutton('config.save.component.apply')\"]").
	attr("onclick", "selectAllPriorities();Joomla.submitbutton('config.save.component.apply')");
	jQuery("button[onclick=\"Joomla.submitbutton('config.save.component.save')\"]").
	attr("onclick", "selectAllPriorities();Joomla.submitbutton('config.save.component.save')");
});
</script>
