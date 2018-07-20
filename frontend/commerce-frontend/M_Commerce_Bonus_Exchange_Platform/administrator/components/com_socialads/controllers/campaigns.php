<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

// Load socialads Controller for list views
require_once __DIR__ . '/salist.php';

/**
 * Campaigns list controller class.
 *
 * @since  3.0
 */
class SocialadsControllerCampaigns extends SocialadsControllerSalist
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    An ad_id for perticular campaign
	 * @param   string  $prefix  To find prefix
	 * @param   array   $config  An optional associative array of configuration settings.
	 *
	 * @return  model
	 *
	 * @since  1.6
	 */
	public function getModel($name = 'campaign', $prefix = 'SocialadsModel', $config = array())
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
	 * Method to change status
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function change_status()
	{
		$input = JFactory::getApplication()->input;
		$id = $input->get('campid', 0, 'INT');
		$model = $this->getModel('campaign');
		echo $camp = $model->status($id);
		jexit();
	}
}
