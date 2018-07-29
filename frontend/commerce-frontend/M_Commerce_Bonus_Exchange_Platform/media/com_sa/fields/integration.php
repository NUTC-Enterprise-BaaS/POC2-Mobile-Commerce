<?php
/**
 * @version    SVN: <svn_id>
 * @package    Com_Tjlms
 * @copyright  Copyright (C) 2005 - 2014. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * Shika is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.html.pane');
jimport('joomla.application.component.helper');
jimport('joomla.filesystem.folder');
jimport('joomla.form.formfield');

/**
 * Class for custom Integration element
 *
 * @since  1.0.0
 */
class JFormFieldIntegration extends JFormField
{
	/**
	 * Function to genarate html of custom element
	 *
	 * @return  HTML
	 *
	 * @since  1.0.0
	 */
	public function getInput()
	{
		return $this->fetchElement($this->name, $this->value, $this->element, $this->options['controls']);
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
	public function fetchElement($name, $value, $node, $control_name)
	{
		$communityfolder = JPATH_SITE . '/components/com_community';
		$esfolder = JPATH_SITE . '/components/com_easysocial';
		$cbfolder = JPATH_SITE . '/components/com_comprofiler';
		$jsString =	"<script>
					function checkIfExtInstalled(selectBoxName, extention)
					{
						var flag = 0;
						if (extention == 'JomSocial')
						{
							";

								if (!JFolder::exists($communityfolder))
								{
									$jsString .= " flag = 1";
								}

							$jsString .= "
						}
						else if (extention == 'EasySocial')
						{
							";

								if (!JFolder::exists($esfolder))
								{
									$jsString .= " flag = 1";
								}

							$jsString .= "
						}
						else if (extention == 'Community Builder')
						{
							";

								if (!JFolder::exists($cbfolder))
								{
									$jsString .= " flag = 1";
								}

							$jsString .= "
						}

						if (flag == 1)
						{
								var extentionName = jQuery('#jformsocial_integration').val();
								alert(extentionName+' not installed');
								jQuery('#jformsocial_integration').val('Joomla');
								jQuery('select').trigger('liszt:updated');
						}
					}

				</script>";
		echo   $jsString;

		$options[] = JHtml::_('select.option', 'Joomla', JText::_('COM_SOCIALADS_FORM_NONE'));
		$options[] = JHtml::_('select.option', 'JomSocial', JText::_('COM_SOCIALADS_FORM_JS'));
		$options[] = JHtml::_('select.option', 'EasySocial', JText::_('COM_SOCIALADS_FORM_ES'));
		$options[] = JHtml::_('select.option', 'Community Builder', JText::_('COM_SOCIALADS_FORM_CB'));

		$fieldName = $name;

		return JHtml::_('select.genericlist',
											$options, $fieldName,
						'class="inputbox btn-group" onchange="checkIfExtInstalled(this.name, this.value)" ',
						'value', 'text', $value, $control_name . $name
						);
	}
}
