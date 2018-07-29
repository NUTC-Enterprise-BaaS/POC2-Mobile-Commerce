<?php
/**
 * @version    SVN: <svn_id>
 * @package    Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.controlleradmin');

/**
 * Lengths list controller class.
 *
 * @package     Socialads
 * @subpackage  com_socialads
 * @since       1.7
 */
class SocialadsControllerSalist extends JControllerAdmin
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('unfeatured', 'featured');
	}

	/**
	 * Method to publish records.
	 *
	 * @return void
	 *
	 * @since 1.7
	 */
	public function publish()
	{
		$id = JFactory::getApplication()->input->get('cid', array(), 'array');

		$data = array(
			'publish' => 1,
			'unpublish' => 0,
			'archive' => 2,
			'trash' => -2,
			'report' => -3
		);

		$task = $this->getTask();
		$value = JArrayHelper::getValue($data, $task, 0, 'int');

		// Get called controller name
		$controllerName = get_called_class();
		$controllerName = str_split($controllerName, strlen('SocialadsController'));
		$currentController = $controllerName[1];
		$currentListView = strtolower($currentController);

		// Get called controller's - singular and plural names
		$singular_name = JText::_('COM_SOCIALADS_SINGULAR_' . strtoupper($currentController));
		$plural_name   = JText::_('COM_SOCIALADS_PLURAL_' . strtoupper($currentController));

		// Get some variables from the request
		if (empty($id))
		{
			JLog::add(JText::sprintf('COM_SOCIALADS_NO_ITEMS_SELECTED', $plural_name), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($id);

			// Publish the items.
			try
			{
				$model->publish($id, $value);
				$count = count($id);

				// Multiple records.
				if ($count > 1)
				{
					if ($value === 1)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_PUBLISHED_1', $count, $plural_name);
					}
					elseif ($value === 0)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_UNPUBLISHED_1', $count, $plural_name);
					}
					elseif ($value == 2)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_ARCHIVED', $count, $plural_name);
					}
					else
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_TRASHED', $count, $plural_name);
					}
				}
				// Single record.
				else
				{
					if ($value === 1)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_PUBLISHED', $count, $singular_name);
					}
					elseif ($value === 0)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_UNPUBLISHED', $count, $singular_name);
					}
					elseif ($value == 2)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_ARCHIVED', $count, $singular_name);
					}
					else
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_TRASHED', $count, $singular_name);
					}
				}

				$this->setMessage($ntext);
			}

			catch (Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
			}
		}

		$this->setRedirect('index.php?option=com_socialads&view=' . $currentListView);
	}

	/**
	 * Removes an item.
	 *
	 * @return  void
	 *
	 * @since   1.7
	 */
	public function delete()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to remove from the request.
		$id = JFactory::getApplication()->input->get('cid', array(), 'array');

		// Get called controller name
		$controllerName = get_called_class();
		$controllerName = str_split($controllerName, strlen('SocialadsController'));
		$currentController = $controllerName[1];
		$currentListView = strtolower($currentController);

		// Get called controller's - singular and plural names
		$singular_name = JText::_('COM_SOCIALADS_SINGULAR_' . strtoupper($currentController));
		$plural_name   = JText::_('COM_SOCIALADS_PLURAL_' . strtoupper($currentController));

		// Get some variables from the request

		if (!is_array($id) || count($id) < 1)
		{
			JLog::add(JText::sprintf('COM_SOCIALADS_NO_ITEMS_SELECTED', $plural_name), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($id);

			// Remove the items.
			try
			{
				$status = $model->delete($id);
				$count = count($id);

				if ($status)
				{
					// Multiple records.
					if ($count > 1)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_DELETED', $count, $plural_name);
					}
					// Single record.
					else
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_DELETED', $count, $singular_name);
					}

					$this->setMessage($ntext);
				}
			}
			catch (Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
			}
		}

		// Invoke the postDelete method to allow for the child class to access the model.
		// $this->postDeleteHook($model, $id);

		$this->setRedirect('index.php?option=com_socialads&view=' . $currentListView);
	}

	/**
	 * Method to feature records.
	 *
	 * @return void
	 *
	 * @since 1.7
	 */
	public function featured()
	{
		$id = JFactory::getApplication()->input->get('cid', array(), 'array');
		$data = array(
			'featured' => 1,
			'unfeatured' => 0
		);

		$task = $this->getTask();
		$value = JArrayHelper::getValue($data, $task, 0, 'int');

		// Get called controller name
		$controllerName = get_called_class();
		$controllerName = str_split($controllerName, strlen('JgiveController'));
		$currentController = $controllerName[1];
		$currentListView = strtolower($currentController);

		// Get called controller's - singular and plural names
		$singular_name = JText::_('COM_SOCIALADS_SINGULAR_' . strtoupper($currentController));
		$plural_name   = JText::_('COM_SOCIALADS_PLURAL_' . strtoupper($currentController));

		// Get some variables from the request
		if (empty($id))
		{
			JLog::add(JText::sprintf('COM_SOCIALADS_N_ITEMS_SELECTED', $plural_name), JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($id);

			// Feature the items.
			try
			{
				$model->featured($id, $value);
				$count = count($id);

				// Multiple records.
				if ($count > 1)
				{
					if ($value === 1)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_FEATURED', $count, $plural_name);
					}
					elseif ($value === 0)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_UNFEATURED', $count, $plural_name);
					}
				}
				// Single record.
				else
				{
					if ($value === 1)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_FEATURED', $count, $singular_name);
					}
					elseif ($value === 0)
					{
						$ntext = JText::sprintf('COM_SOCIALADS_N_ITEMS_UNFEATURED', $count, $singular_name);
					}
				}

				$this->setMessage($ntext);
			}
			catch (Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
			}
		}

		$this->setRedirect('index.php?option=com_socialads&view=' . $currentListView);
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   1.7
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
}
