<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'form'.DS.'fields'.DS.'rules.php');
	
class JFormFieldFSSRulesU extends JFormFieldRules
{
	protected function getInput()
	{
		//JHtml::_('behavior.tooltip');
		//print_p($this->form);
		
		$user_id = $this->form->getValue("user_id");
		
		$user_obj = JFactory::getUser($user_id);
		
		// Initialise some field attributes.
		$section = $this->element['section'] ? (string) $this->element['section'] : '';
		$component = $this->element['component'] ? (string) $this->element['component'] : '';
		$assetId = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$tab_id = $this->element['tab_id'] ? (string) $this->element['tab_id'] : 'permission';

		// Get the actions for the asset.
		$actions = self::getActions($component, $section);

		// Iterate over the children and add to the actions.
		foreach ($this->element->children() as $el)
		{
			if ($el->getName() == 'action')
			{
				$actions[] = (object) array('name' => (string) $el['name'], 'title' => (string) $el['title'],
					'description' => (string) $el['description'], 'heading' => (string) $el['heading']);
			}
		}

		// Get the available user groups.
		$groups = $this->getUserGroups();

		// Build the form control.
		$curLevel = 0;

		// Prepare output
		$html = array();
		
		// Start a row for each user group.
		foreach ($groups as $group)
		{
			// Initial Active Pane
			$active = "";
			if ($group->value == 1)
			{
				$active = " active";
			}

			$difLevel = $group->level - $curLevel;

			$html[] = '<div class="tab-pane' . $active . ' '.$tab_id.'-' . $group->value . '" id="'.$tab_id.'-' . $group->value . '">';
			$html[] = '<table class="table table-striped">';
			$html[] = '<thead>';
			$html[] = '<tr>';

			$html[] = '<th class="actions" id="actions-th' . $group->value . '">';
			$html[] = '<span class="acl-action">' . JText::_('JLIB_RULES_ACTION') . '</span>';
			$html[] = '</th>';

			$html[] = '<th class="settings" id="settings-th' . $group->value . '">';
			$html[] = '<span class="acl-action">' . JText::_('JLIB_RULES_SELECT_SETTING') . '</span>';
			$html[] = '</th>';
			
			
			$html[] = '<th id="aclactionth' . $group->value . '">';
			$html[] = '<span class="acl-action">' . JText::_('JLIB_RULES_CALCULATED_SETTING') . '</span>';
			$html[] = '</th>';
			
			
			$html[] = '</tr>';
			$html[] = '</thead>';
			$html[] = '<tbody>';

			foreach ($actions as $action)
			{
				if ($action->heading)
				{
					$html[] = "<tr><td colspan='3'><h4>" . JText::_($action->heading) . "</h4></td></tr>";
				}
				
				$html[] = '<tr>';
				$html[] = '<td headers="actions-th' . $group->value . '">';
				$html[] = '<label class="fssTip" for="' . $this->id . '_' . $action->name . '_' . $group->value . '" title="'
					. htmlspecialchars(JText::_($action->title) . ' ' . JText::_($action->description), ENT_COMPAT, 'UTF-8') . '">';
				$html[] = JText::_($action->title);
				$html[] = '</label>';
				$html[] = '</td>';

				$html[] = '<td headers="settings-th' . $group->value . '">';

				$html[] = '<select class="input-small" name="' . $this->name . '[' . $action->name . '][' . $group->value . ']" id="' . $this->id . '_' . $action->name
					. '_' . $group->value . '" title="'
					. JText::sprintf('JLIB_RULES_SELECT_ALLOW_DENY_GROUP', JText::_($action->title), trim($group->text)) . '">';

				$assetRule = null;
				$prop = $action->name;
				if (isset($this->value->$prop))
				{
					$assetRule = $this->value->$prop == 1;	
				}
				
				// Build the dropdowns for the permissions sliders

				// The parent group has "Not Set", all children can rightly "Inherit" from that.
				$html[] = '<option value=""' . ($assetRule === null ? ' selected="selected"' : '') . '>'
					. JText::_(empty($group->parent_id) && empty($component) ? 'JLIB_RULES_NOT_SET' : 'JLIB_RULES_INHERITED') . '</option>';
				$html[] = '<option value="1"' . ($assetRule === true ? ' selected="selected"' : '') . '>' . JText::_('JLIB_RULES_ALLOWED')
					. '</option>';
				$html[] = '<option value="0"' . ($assetRule === false ? ' selected="selected"' : '') . '>' . JText::_('JLIB_RULES_DENIED')
					. '</option>';

				$html[] = '</select>&#160; ';

				$html[] = '</td>';

				// Build the Calculated Settings column.
				// The inherited settings column is not displayed for the root group in global configuration.
				if ($user_id > 0)
				{
					$html[] = '<td headers="aclactionth' . $group->value . '">';

					$inh = $user_obj->authorise($action->name, $assetId);
					//$html[] = "Check : {$action->name} -> $assetId = $inh<br>";
					
					if ($assetRule === null)
					{
						if ($inh)
						{
							$html[] = '<span class="label label-success">' . JText::_('JLIB_RULES_ALLOWED') . '</span>';
						} else {
							$html[] = '<span class="label label-important">' . JText::_('JLIB_RULES_NOT_ALLOWED') . '</span>';
						}
					} else if ($assetRule === true) {
						$html[] = '<span class="label label-success">' . JText::_('JLIB_RULES_ALLOWED') . '</span>';
					} else {
						$html[] = '<span class="label label-important">' . JText::_('JLIB_RULES_NOT_ALLOWED') . '</span>';
					}

					$html[] = '</td>';
				} else {
					$html[] = '<td headers="aclactionth' . $group->value . '">';
					$html[] = '</td>';
				}

				$html[] = '</tr>';
			}

			$html[] = '</tbody>';
			$html[] = '</table></div>';

		}

		$html[] = '<div class="alert">';
		if ($section == 'component' || $section == null)
		{
			$html[] = JText::_('JLIB_RULES_SETTING_NOTES');
		}
		else
		{
			$html[] = JText::_('JLIB_RULES_SETTING_NOTES_ITEM');
		}
		$html[] = '</div>';

		return implode("\n", $html);
	}
	
	public static function getActions($component, $section = 'component')
	{
		JLog::add(__METHOD__ . ' is deprecated. Use JAccess::getActionsFromFile or JAcces::getActionsFromData instead.', JLog::WARNING, 'deprecated');
		$actions = self::getActionsFromFile(
			JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml',
			"/access/section[@name='" . $section . "']/"
		);
		if (empty($actions))
		{
			return array();
		}
		else
		{
			return $actions;
		}
	}

	/**
	 * Method to return a list of actions from a file for which permissions can be set.
	 *
	 * @param   string  $file   The path to the XML file.
	 * @param   string  $xpath  An optional xpath to search for the fields.
	 *
	 * @return  boolean|array   False if case of error or the list of actions available.
	 *
	 * @since   12.1
	 */
	public static function getActionsFromFile($file, $xpath = "/access/section[@name='component']/")
	{
		if (!is_file($file) || !is_readable($file))
		{
			// If unable to find the file return false.
			return false;
		}
		else
		{
			// Else return the actions from the xml.
			$xml = simplexml_load_file($file);
			return self::getActionsFromData($xml, $xpath);
		}
	}

	/**
	 * Method to return a list of actions from a string or from an xml for which permissions can be set.
	 *
	 * @param   string|SimpleXMLElement  $data   The XML string or an XML element.
	 * @param   string                   $xpath  An optional xpath to search for the fields.
	 *
	 * @return  boolean|array   False if case of error or the list of actions available.
	 *
	 * @since   12.1
	 */
	public static function getActionsFromData($data, $xpath = "/access/section[@name='component']/")
	{
		// If the data to load isn't already an XML element or string return false.
		if ((!($data instanceof SimpleXMLElement)) && (!is_string($data)))
		{
			return false;
		}

		// Attempt to load the XML if a string.
		if (is_string($data))
		{
			try
			{
				$data = new SimpleXMLElement($data);
			}
			catch (Exception $e)
			{
				return false;
			}

			// Make sure the XML loaded correctly.
			if (!$data)
			{
				return false;
			}
		}

		// Initialise the actions array
		$actions = array();

		// Get the elements from the xpath
		$elements = $data->xpath($xpath . 'action[@name][@title][@description]');

		// If there some elements, analyse them
		if (!empty($elements))
		{
			foreach ($elements as $action)
			{
				// Add the action to the actions array
				$actions[] = (object) array(
					'name' => (string) $action['name'],
					'heading' => (string) $action['heading'],
					'title' => (string) $action['title'],
					'description' => (string) $action['description']
				);
			}
		}

		// Finally return the actions array
		return $actions;
	}
		
	protected function getUserGroups()
	{
		$option = new stdClass();
		$option->value = 1;
		$option->text = "User";
		$option->level = 0;
		$option->parent_id = 0;
			
		$options[] = $option;
		
		return $options;
	}
}