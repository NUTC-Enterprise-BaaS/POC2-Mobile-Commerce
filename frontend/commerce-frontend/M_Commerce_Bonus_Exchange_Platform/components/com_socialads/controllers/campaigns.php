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

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Campaigns list controller class.
 *
 * @since  1.6
 */
class SocialadsControllerCampaigns extends SocialadsController
{
	/**
	 * delete campaign.
	 *
	 * @param   STRING  $name    campaign name
	 *
	 * @param   STRING  $prefix  model prefix
	 *
	 * @param   STRING  $config  config
	 *
	 * @return void
	 *
	 * @since  1.6
	 */
	public function &getModel($name = 'Campaigns', $prefix = 'SocialadsModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * delete campaign.
	 *
	 * @return void
	 *
	 * @since  1.6
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', '', 'array');
		$itemid = SaCommonHelper::getSocialadsItemid('campaigns');
		$model = $this->getModel('campaigns');
		$successCount = $model->delete($cid);

		$app->redirect('index.php?option=com_socialads&view=campaigns&Itemid=' . $itemid, $msg);
	}

	/**
	 * publish campaign.
	 *
	 * @return void
	 *
	 * @since  1.6
	 */
	public function publish()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', '', 'array');
		$itemid = SaCommonHelper::getSocialadsItemid('campaigns');
		$model = $this->getModel('campaigns');
		$successCount = $model->publish($cid);

		$app->redirect('index.php?option=com_socialads&view=campaigns&Itemid=' . $itemid, $msg);
	}

	/**
	 * unpublish campaign.
	 *
	 * @return void
	 *
	 * @since  1.6
	 */
	public function unpublish()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', '', 'array');
		$itemid = SaCommonHelper::getSocialadsItemid('campaigns');
		$model = $this->getModel('campaigns');
		$successCount = $model->unpublish($cid);

		$app->redirect('index.php?option=com_socialads&view=campaigns&Itemid=' . $itemid, $msg);
	}
}
