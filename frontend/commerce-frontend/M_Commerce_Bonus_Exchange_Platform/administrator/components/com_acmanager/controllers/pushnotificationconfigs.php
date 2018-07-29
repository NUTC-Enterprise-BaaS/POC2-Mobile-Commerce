<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Pushnotificationconfigs list controller class.
 *
 * @since  1.6
 */
class AcmanagerControllerPushnotificationconfigs extends JControllerAdmin
{
	/**
	 * Method to clone existing Pushnotificationconfigs
	 *
	 * @return void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_ACMANAGER_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_ACMANAGER_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_acmanager&view=pushnotificationconfigs');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'pushnotificationconfig', $prefix = 'AcmanagerModel', $config = array())
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
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

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
	 * Method to test notification setup working on site or not via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function checkNotify()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		//$pks   = $input->post->get('cid', array(), 'array');
		$type = $input->get('type', '', 'STRING');

			$easysocial = JPATH_ADMINISTRATOR .'/components/com_easysocial/easysocial.php';
			//es version
			if( JFile::exists( $easysocial ) )
			{
				$user = JFactory::getUser();

				//build test object
				$obj = new stdClass();
				$obj->rule = 'stream';
				$obj->test = 1;
				$obj->participant = array(0=>$user->id);
				$obj->sys_options = array();
				$obj->email_options = array('title'=>'test notification','message'=>'This is admin generated test message','actorAvatar'=>null,'permalink'=>null);
				$args = array(&$obj);

				require_once JPATH_ADMINISTRATOR.'/components/com_easysocial/includes/foundry.php';
				$dispatcher = ES::getInstance('Dispatcher');
				// @trigger onNotificationBeforeCreate from user apps
				$res = $dispatcher->trigger(SOCIAL_APPS_GROUP_USER, 'onNotificationBeforeCreate', $args);
				print_r(json_encode(array_values(array_filter($res))));
			}
			jexit();
	}
	
}
