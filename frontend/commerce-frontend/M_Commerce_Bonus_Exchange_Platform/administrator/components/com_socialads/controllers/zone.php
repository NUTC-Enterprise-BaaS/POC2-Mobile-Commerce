<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Zone controller class.
 *
 * @since  1.0
 */
class SocialadsControllerZone extends JControllerForm
{
	/**
	 *Function to construct a zones view
	 *
	 * @since  3.0
	 */
	public function __construct()
	{
		$this->view_list = 'zones';
		parent::__construct();
	}

	/**
	 * Overrides parent save method.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   3.2
	 */
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$task = $this->getTask();

		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel('Zone', 'SocialadsModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

		// Attempt to save the data.
		$return = $model->save($data);
		$id = $return;

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_socialads.edit.zone.data', $data);

			// Tweak *important.
			$app->setUserState('com_socialads.edit.zone.id', $data['id']);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_socialads.edit.zone.id');
			$this->setMessage(JText::sprintf('COM_SOCIALADS_SAVE_MSG_ERROR', $model->getError()), 'warning');
			$this->setRedirect('index.php?option=com_socialads&&view=zone&layout=edit&id=' . $id);

			return false;
		}

		if (!$id)
		{
			$id = (int) $app->getUserState('com_socialads.edit.zone.id');
		}

		if ($task === 'apply')
		{
			$redirect = 'index.php?option=com_socialads&task=zone.edit&id=' . $id;
		}
		else
		{
			// Clean the session data and redirect.
			$model->checkin($id);

			// Clear the profile id from the session.
			$app->setUserState('com_socialads.edit.zone.id', null);

			// Flush the data from the session.
			$app->setUserState('com_socialads.edit.zone.data', null);

			// Redirect to the list screen.
			$redirect = 'index.php?option=com_socialads&view=zones';
		}

		$msg = JText::_('COM_SOCIALADS_SAVE_SUCCESS');
		$this->setRedirect($redirect, $msg);
	}

	/**
	 * Function to call the plugin from view
	 *
	 * @return  void
	 *
	 * @since  3.0
	 */
	public function getSelectedLayouts()
	{
		$input = JFactory::getApplication()->input;
		$addtype = $input->get('addtype');
		$zonlay = $input->get('zonelayout', '', 'array');
		$zoneTypes = $zonlay[0];
		$selected_layout1 = array();

		if ($zoneTypes)
		{
			JRequest::setVar('layout', $zoneTypes);
			$selected_layout_arr = explode('|', $zoneTypes);
			$i = 0;

			foreach ($selected_layout_arr as $selected_layout)
			{
				$selected_layout1[$i] = $selected_layout;
				$i++;
			}
		}

		if ($addtype == 'text')
		{
			$layout_type = "Text";
		}

		elseif ($addtype == 'media')
		{
			$layout_type = "Media";
		}

		elseif ($addtype == 'text_media')
		{
			$layout_type = "Text And Media";
		}

		else
		{
			$layout_type = "";
			$add_type[] = JHtml::_('select.option', '0', 'select');
			JHtml::_('select.genericlist', $add_type, 'layout_select', 'class = "inputbox" size=1', 'value', '');
			exit;
		}

		$add_type = '';
		$newvar = JPluginHelper::getPlugin('socialadslayout');
		$sel_layout1 = array_values($selected_layout1);

		foreach ($newvar as $k => $v)
		{
			$params = explode("\n", $v->params);

			foreach ($params as $pa => $p)
			{
				if (JVERSION >= '1.6')
				{
					$lay = json_decode($p);

					if (isset($lay->layout_type))
					{
						if ($layout_type == $lay->layout_type)
						{
							$chk = '';
							$nam = substr($v->name, 5);

							if (in_array($nam, $sel_layout1))
							{
								$chk = 'checked = "checked"';
							}
							elseif($layout_type == 'Media')
								$chk = 'checked="yes"';
								$add_type .= '<span style = "vertical-align:text-top;">
											<input type="checkbox" ' . $chk . ' name="layout_select[]" class="inputbox" value="' . $nam . '" />
											<img src="' . JUri::root() . 'plugins/socialadslayout/plug_' . $nam . '/plug_' . $nam . '/layout.png" >
											</span>&nbsp;&nbsp;&nbsp;';
						}
					}
				}
			}
		}

				if ($add_type == '')
				{
					echo JText::_('COM_SOCIALADS_ZONES_NO_LAYOUT');
				}
				else
				{
					echo $add_type;
				}

				exit;
	}
}
