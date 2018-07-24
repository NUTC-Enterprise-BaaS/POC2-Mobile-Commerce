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

jimport('joomla.application.component.view');

/**
 * View class for a list of Socialads.
 *
 * @since  1.6
 */
class SocialadsViewDashboard extends JViewLegacy
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
		SocialadsHelper::addSubmenu('dashboard');
		$input = JFactory::getApplication()->input;
		$model = $this->getModel('dashboard');

		// Get params
		$this->params = JComponentHelper::getParams('com_socialads');

		// Set state filter
		$this->publish_states = array(
			'' => JText::_('JOPTION_SELECT_PUBLISHED'),
			'1' => JText::_('JPUBLISHED'),
			'0' => JText::_('JUNPUBLISHED')
		);

		$this->allincome           = $model->getAllOrdersIncome();
		$this->totalads            = $model->getTotalAds();
		$this->pendingorders       = $model->getPendingOrders($this->params->get('payment_mode'));
		$this->averagectr          = $model->getAverageCtr();
		$this->totalorders         = $model->getTotalOrders($this->params->get('payment_mode'));
		$this->topads              = $model->getTopAds();
		$this->periodicorderscount = $model->getPeriodicOrdersCount();

		$this->downloadid = $this->params->get('downloadid');
		$this->currency   = $this->params->get('currency');

		// @TODO - use userstate instead of post here and in model
		if ($input->post->get('from'))
		{
			$this->from_date = $input->post->get('from');
		}
		else
		{
			$this->from_date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 30 days'));
		}

		if ($input->post->get('to'))
		{
			$this->to_date = $input->post->get('to');
		}
		else
		{
			$this->to_date = date('Y-m-d');
		}

		// Get installed version from xml file
		$xml     = JFactory::getXML(JPATH_COMPONENT . '/socialads.xml');
		$version = (string) $xml->version;
		$this->version = $version;

		// Get new version
		$this->latestVersion = $model->getLatestVersion();

		$this->addToolbar();

		if (JVERSION >= '3.0')
		{
			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/socialads.php';

		if (JVERSION >= '3.0')
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS_TITLE_DASHBOARD'), 'list');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS_TITLE_DASHBOARD'), 'dashboard.png');
		}

		JToolBarHelper::preferences('com_socialads');

		// Set sidebar action
		if (JVERSION >= '3.0')
		{
			JHtmlSidebar::setAction('index.php?option=com_socialads&view=dashboard');
			$this->extra_sidebar = '';
		}
	}
}
