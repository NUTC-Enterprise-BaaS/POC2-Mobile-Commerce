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
class SocialadsViewDashboard extends JViewLegacy
{
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
		// Get params
		$this->params = JComponentHelper::getParams('com_socialads');

		// Get stats data for line chart
		$model = $this->getModel('dashboard');
		$this->statsforbar = $model->getstatsforlinechart();
		$this->activeAds = $model->getActiveAdCount();
		$this->inactiveAds = $model->getInactiveAdCount();
		$this->totalSpent = $model->getAllOrdersIncome();
		$this->topads = $model->getTopAds();
		$this->pendingorders = $model->getPendingOrders($this->params->get('payment_mode'));
		$this->session = JFactory::getSession();
		$this->user = JFactory::getUser();
		$this->mainframe = JFactory::getApplication();
		$this->input = JFactory::getApplication()->input;
		$this->params     = $this->mainframe->getParams('com_socialads');

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

		parent::display($tpl);
	}
}
