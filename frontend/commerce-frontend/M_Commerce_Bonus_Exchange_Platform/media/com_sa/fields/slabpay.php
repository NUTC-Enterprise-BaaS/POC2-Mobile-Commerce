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
 */
class JFormFieldSlabpay extends JFormField
{
	protected $type = 'Slabpay';

	protected $name = 'Slabpay';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 *
	 * @since	1.6
	 */
	public function getInput()
	{
		$js_key = "function checkforalpha(el,allowed_ascii)
		{
			allowed_ascii= (typeof allowed_ascii === 'undefined') ? '' : allowed_ascii;
			var i =0 ;
			for(i=0;i<el.value.length;i++){
				if(el.value=='0'){
					alert('" . JText::_('COM_SOCIALADS_ZERO_VALUE_VALI_MSG') . "');
					el.value = el.value.substring(0,i); break;
				}
			 if((el.value.charCodeAt(i) <= 47 || el.value.charCodeAt(i) >= 58) || (el.value.charCodeAt(i) == 45 )){
					if(allowed_ascii !=el.value.charCodeAt(i) ){
						alert('" . JText::_('COM_SOCIALADS_NUMONLY_VALUE_VALI_MSG') . "'); el . value = el . value . substring(0,i); break;
					}
				}
			}
		}";
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($js_key);
		$html = '';
		$fieldName = "configure_slab";
		$params = JComponentHelper::getParams('com_socialads');
		$group_info = $params->get('configure_slab');
		$fieldName = $this->name;

		$html .= '</div>
					<div class = "control-group slab_tr_hide" id = "slab_tr_row" >
						<div class="controls">
							<table class="table table-striped sa-elements-custom" style="width:60px;">
								<tbody><tr>
									<td width="2%" align="center" class="title">
										' . JHtml::tooltip(JText::_('COM_SOCIALADS_OPTIONS_SLABS_TITLE_TOOLTIP'), JText::_('COM_SOCIALADS_OPTIONS_SLABS_TITLE'), '', JText::_('COM_SOCIALADS_OPTIONS_SLABS_TITLE')) . '
									</td>
									<td width="2%" align="center" class="title">
										' . JHtml::tooltip(JText::_('COM_SOCIALADS_OPTIONS_SLABS_DURATION_TOOLTIP'), JText::_('COM_SOCIALADS_OPTIONS_SLABS_DURATION'), '', JText::_('COM_SOCIALADS_OPTIONS_SLABS_DURATION')) . '
									</td>
									<td width="2%" align="center" class="title">
										' . JHtml::tooltip(JText::_('COM_SOCIALADS_OPTIONS_SLABS_PRICE_TOOLTIP'), JText::_('COM_SOCIALADS_OPTIONS_SLABS_PRICE'), '', JText::_('COM_SOCIALADS_OPTIONS_SLABS_PRICE')) . '
									</td>
								</tr>';

		if (isset($group_info))

		// For edit - recreate giveback blocks
		{
			$count = count($group_info);
			$j = 0;
			$i = 0;
			$html .= '<tr>
						<td  width = "">
						<input type = "text" class = "" name = "' . $fieldName . '[]' . '"  value = "' . $group_info[$i] . '" placeholder = "Week" "/></td>
						<td class = "setting-td" align = "center" width = "10px">
							<input type = "number" min="0" class = "" name = "' . $fieldName . '[]' . '"  value = "' . $group_info[$i + 1] . '" placeholder = "7" "/>
						</td>
						<td class = "setting-td">
							<input type = "number" min="0" class = "" name = "' . $fieldName . '[]' . '"  value = "' . $group_info[$i + 2] . '" placeholder = "20" "/>
						</td>
					</tr>
					<tr>
						<td  width = "">
						<input type = "text" class = "" name = "' . $fieldName . '[]' . '"  value = "' . $group_info[$i + 3] . '" placeholder = "Month" "/>
						</td>
						<td class = "setting-td" align = "center" width = "10px">
							<input type = "number" min="0" class = "" name="' . $fieldName . '[]' . '"  value = "' . $group_info[$i + 4] . '" placeholder = "30" "/>
						</td>
						<td class = "setting-td">
							<input type = "number" min="0" class = "" name = "' . $fieldName . '[]' . '"  value = "' . $group_info[$i + 5] . '" placeholder = "5" "/>
						</td>
					</tr> ';
		}
		else
		{
			$html .= '<tr>
						<td  width = ""><input type = "text" class = "" name = "' . $fieldName . '[]' . '"  value = "" placeholder="Week" "/></td>
						<td class = "setting-td" align = "center" width = "10px">
							<input type="number" min="0" class="" name="' . $fieldName . '[]' . '"  value="" placeholder="7" "/>
						</td>
						<td class = "setting-td">
							<input type = "number" min="0" class = "" name="' . $fieldName . '[]' . '"  value = "" placeholder="20" "/>
						</td>
					</tr>
					<tr>
						<td class="setting-td">
								<input type = "text" class = "" name = "' . $fieldName . '[]' . '"  value = "" placeholder = "month" "/>
						</td>
						<td class="setting-td">
								<input type = "number" min="0" class = "" name = "' . $fieldName . '[]' . '"  value = "" placeholder = "30" "/>
						</td>
						<td class = "setting-td">
								<input type = "number" min="0" class = "" name = "' . $fieldName . '[]' . '"  value = "" placeholder = "15" "/>
						</td>
					</tr>';
		}

		$html .= '</tbody>
				</table>
				</div>';
		echo $html;
	}
}
