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
class SocialadsViewadsummary extends JViewLegacy
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
		$user = JFactory::getUser();

		if (!$user->id)
		{
			if (! JFactory::getUser($user->id)->authorise('core.manage_ad', 'com_socialads'))
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('COM_SOCIALADS_AUTH_ERROR'), 'warning');

				return false;
			}
		}

		$input = JFactory::getApplication()->input;
		$this->items = $this->get('Items');
		$this->adid = $input->get('adid');
		$model = $this->getModel('adsummary');
		$this->items = $this->get('Items');
		$this->ad_type = $model->getadtype($this->adid);

		// Get data for donut chart
		$this->statsforpie = $model->getstatsforpiechart();

		// Get data for line chart
		$this->statsforbar = $model->getstatsforlinechart();

		// Get ad preview
		$this->preview = SaAdEngineHelper::getAdHtml($this->adid, 1);

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

		parent::display($tpl);
	}
}
