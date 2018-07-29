<?php

/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * orders list controller class.
 *
 * @since  3.0
 */
class SocialadsControllerOrders extends JControllerAdmin
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
	public function getModel($name = '', $prefix = 'SocialadsModel', $config = array())
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
		$model = $this->getModel('orders');
		$post = JRequest::get('post');
		$ret = $model->store();

		if ($ret == 1)
		{
			$msg = JText::_('FIELD_SAVING_MSG');
		}
		elseif($ret == 3)
		{
			$msg = JText::_('REFUND_SAVING_MSG');
		}
		elseif($ret == 4)
		{
			$msg = JText::_('SA_CANCEL_SAVING_MSG');
		}
		else
		{
			$msg = JText::_('FIELD_ERROR_SAVING_MSG');
		}

		$link = 'index.php?option=com_socialads&view=orders';
		$this->setRedirect($link, $msg);
	}
}
