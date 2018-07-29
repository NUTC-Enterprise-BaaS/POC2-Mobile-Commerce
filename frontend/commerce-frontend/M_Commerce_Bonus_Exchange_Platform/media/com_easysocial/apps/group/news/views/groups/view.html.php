<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class NewsViewGroups extends SocialAppsView
{
	/**
	 * Displays the application output in the canvas.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display($groupId = null, $docType = null)
	{
		// Load up the group
		$group 	= FD::group($groupId);

		// Check if the viewer is really allowed to view news
		if ($group->isInviteOnly() && $group->isClosed() && !$group->isMember() && !$this->my->isSiteAdmin()) {
			return $this->redirect($group->getPermalink(false));
		}

		$params = $this->app->getParams();

		// Set the max length of the item
		$options = array('limit' => (int) $params->get('total', 10));

		$model = FD::model('Groups');
		$items = $model->getNews($group->id, $options);
		$pagination = $model->getPagination();

		// Format the item's content.
		$this->format($items, $params);

		$pagination->setVar('option', 'com_easysocial');
		$pagination->setVar('view', 'groups');
		$pagination->setVar('layout', 'item');
		$pagination->setVar('id', $group->getAlias());
		$pagination->setVar('appId', $this->app->getAlias());

		$this->set('params', $params);
		$this->set('pagination', $pagination);
		$this->set('group', $group);
		$this->set('items', $items);

		echo parent::display('canvas/default');
	}

	private function format(&$items, $params)
	{
		$length	= $params->get('content_length');

		if($length == 0)
		{
			return;
		}

		foreach($items as &$item)
		{
			$item->content 	= JString::substr(strip_tags($item->content), 0, $length) . ' ' . JText::_('COM_EASYSOCIAL_ELLIPSES');
		}
	}
}
