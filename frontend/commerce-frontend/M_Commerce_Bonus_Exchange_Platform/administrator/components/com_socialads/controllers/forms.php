<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Load socialads Controller for list views
require_once __DIR__ . '/salist.php';

jimport('joomla.application.component.controlleradmin');

/**
 * ads list controller class.
 *
 * @since  1.6
 */
class SocialadsControllerForms extends SocialadsControllerSalist
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    An ad_id for perticular ad
	 * @param   string  $prefix  To find prefix
	 * @param   array   $config  An optional associative array of configuration settings.
	 *
	 * @return  model
	 *
	 * @since  1.6
	 */
	public function getModel($name = 'form', $prefix = 'SocialadsModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
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

	/**
	 * Method to save status.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function save()
	{
		$model = $this->getModel('forms');
		$post	= JRequest::get('post');
		$store = $model->store();

		if ($store)
		{
			$msg = JText::_('FIELD_SAVING_MSG');
		}
		else
		{
			$msg = JText::_('FIELD_ERROR_SAVING_MSG');
		}

		$link = 'index.php?option=com_socialads&view=forms';
		$this->setRedirect($link, $msg);
	}

	/**
	 * Method to update zone
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function updatezone()
	{
		$model = $this->getModel('forms');
		$post = JRequest::get('post');

		$upDateZone = $model->updatezone();

		if ($upDateZone)
		{
			$msg = JText::_('FIELD_SAVING_MSG');
		}
		else
		{
			$msg = JText::_('FIELD_ERROR_SAVING_MSG');
		}

		$link = 'index.php?option=com_socialads&view=forms';
		$this->setRedirect($link, $msg);
	}

	/**
	 * Method get CSV report
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function adCsvExport()
	{
		$model   = $this->getModel("forms");
		$CSVData = $model->adCsvExport();
	}
}
