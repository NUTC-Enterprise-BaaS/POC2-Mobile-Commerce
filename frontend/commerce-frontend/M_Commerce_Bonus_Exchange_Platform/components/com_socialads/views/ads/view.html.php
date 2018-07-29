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

jimport('joomla.application.component.view');

/**
 * View class for a list of Socialads.
 *
 * @since  1.6
 */
class SocialadsViewAds extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   array  $tpl  An optional associative array.
	 *
	 * @return  array
	 *
	 * @since 1.6
	 */
	public function display($tpl = null)
	{
		// *Important- If any Ad id in session, clear it
		// @TODO - needs to be handled while create ad function ends
		JFactory::getSession()->clear('ad_id');
		$this->user = JFactory::getUser();
		$this->session = JFactory::getSession();
		$this->input = JFactory::getApplication()->input;
		$this->mainframe = JFactory::getApplication();
		$this->params     = $this->mainframe->getParams('com_socialads');
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$zone_list = $this->get('Zonelist');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
		$campaigns = JFormHelper::loadFieldType('Campains', false);

		if (!$this->user->id)
		{
			$msg = JText::_('COM_SOCIALADS_LOGIN_MSG');

			if ($this->params->get('registration_form', 1))
			{
				$itemid = $this->input->get('Itemid', 0, 'INT');
				$this->session->set('socialadsbackurl', $_SERVER["REQUEST_URI"]);
				$this->mainframe->redirect(JRoute::_('index.php?option=com_socialads&view=registration&Itemid=' . $itemid, false), $msg);
			}
			else
			{
				$uri = $this->input->server->get('REQUEST_URI', '', 'STRING');
				$url = urlencode(base64_encode($uri));
				$this->mainframe->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url, false), $msg);
			}

			return false;
		}

		// Get campains list
		$this->campaignsoptions = $campaigns->getOptions();
		$zoneslist = JFormHelper::loadFieldType('zoneslist', false);

		// Get zones list
		$this->zonesoptions = $zoneslist->getOptions();

		// Get stats data for line chart
		$model = $this->getModel('ads');
		$this->statsforbar = $model->getstatsforlinechart();

		$this->publish_states = array(
		'' => JText::_('JOPTION_SELECT_PUBLISHED'),
		'1'  => JText::_('JPUBLISHED'),
		'0'  => JText::_('JUNPUBLISHED')
		);

		$this->adstatus = array(
		'' => JText::_('COM_SOCIALADS_ADS_STATUS'),
		'1'  => JText::_('COM_SOCIALADS_ADS_VALID'),
		'0'  => JText::_('COM_SOCIALADS_ADS_EXPIRED')
		);

		// Setup toolbar
		$this->addTJtoolbar();

		parent::display($tpl);
	}

	/**
	 * Setup ACL based tjtoolbar
	 *
	 * @return  void
	 *
	 * @since   2.2
	 */
	protected function addTJtoolbar()
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_socialads/helpers/socialads.php';
		$canDo = SocialadsHelper::getActions();

		// Add toolbar buttons
		jimport('techjoomla.tjtoolbar.toolbar');
		$tjbar = TJToolbar::getInstance('tjtoolbar', 'pull-right');

		if ($canDo->get('core.create'))
		{
			$tjbar->appendButton('ads.addNew', 'TJTOOLBAR_NEW', '', 'class="btn btn-small btn-success"');
		}

		if ($canDo->get('core.edit.own') && isset($this->items[0]))
		{
			$tjbar->appendButton('ads.edit', 'TJTOOLBAR_EDIT', '', 'class="btn btn-small btn-success"');
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]))
			{
				$tjbar->appendButton('ads.publish', 'TJTOOLBAR_PUBLISH', '', 'class="btn btn-small btn-success"');
				$tjbar->appendButton('ads.unpublish', 'TJTOOLBAR_UNPUBLISH', '', 'class="btn btn-small btn-warning"');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]))
			{
				$tjbar->appendButton('ads.delete', 'TJTOOLBAR_DELETE', '', 'class="btn btn-small btn-danger"');
			}
		}

		$this->toolbarHTML = $tjbar->render();
	}
}
