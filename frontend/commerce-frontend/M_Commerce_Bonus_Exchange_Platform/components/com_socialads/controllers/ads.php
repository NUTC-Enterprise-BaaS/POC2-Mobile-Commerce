<?php
/**
 * @version    SVN: <svn_id>
 * @package    Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access.
defined('_JEXEC') or die();

$lang = JFactory::getLanguage();

jimport('joomla.application.component.controllersite');

/**
 * Proucts list controller class.
 *
 * @package     socialads
 * @subpackage  com_socailads
 * @since       1.6
 */
require_once JPATH_COMPONENT . '/controller.php';

/**
 * Controller for ads view.
 *
 * @since  1.6
 */
class SocialadsControllerAds extends SocialadsController
{
	/**
	 * Method to publish records.
	 *
	 * @return void
	 *
	 * @since 3.0
	 */
	public function publish()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', '', 'array');
		$itemid = SaCommonHelper::getSocialadsItemid('ads');
		$model = $this->getModel('ads');
		$successCount = $model->publish($cid);

		$app->redirect('index.php?option=com_socialads&view=ads&Itemid=' . $itemid, $msg);
	}

	/**
	 * Method to unpublish records.
	 *
	 * @return void
	 *
	 * @since 3.0
	 */
	public function unpublish()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', '', 'array');
		$itemid = SaCommonHelper::getSocialadsItemid('ads');
		$model = $this->getModel('ads');
		$successCount = $model->unpublish($cid);

		$app->redirect('index.php?option=com_socialads&view=ads&Itemid=' . $itemid, $msg);
	}

	/**
	 * Method to delete ad records.
	 *
	 * @return void
	 *
	 * @since 3.0
	 */
	public function delete()
	{
		$mainframe = JFactory::getApplication();
		$post	= JRequest::get('post');
		$adid = $post['cid'];
		$itemid = SaCommonHelper::getSocialadsItemid('ads');
		$model = $this->getModel('ads');
		$successCount = $model->delete($adid);

		$mainframe->redirect('index.php?option=com_socialads&view=ads&Itemid=' . $itemid, $msg);
	}

	/**
	 * Method to add records.
	 *
	 * @return void
	 *
	 * @since 3.0
	 */
	public function addNew()
	{
		$itemId = SaCommonHelper::getSocialadsItemid('adform');

		$link = JRoute::_('index.php?option=com_socialads&view=adform&Itemid=' . $itemId, false);
		$this->setRedirect($link);
	}

	/**
	 * Method to Edit records.
	 *
	 * @return void
	 *
	 * @since 3.0
	 */
	public function edit()
	{
		$input = JFactory::getApplication()->input;
		$cid   = $input->get('cid', '', 'array');
		JArrayHelper::toInteger($cid);

		$itemId = SaCommonHelper::getSocialadsItemid('adform');

		$link = JRoute::_('index.php?option=com_socialads&view=adform&ad_id=' . $cid[0] . '&Itemid=' . $itemId, false);

		$this->setRedirect($link);
	}
}
