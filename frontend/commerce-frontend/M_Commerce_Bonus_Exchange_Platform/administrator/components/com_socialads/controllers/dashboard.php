<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access.
defined('_JEXEC') or die();

/**
 * Dashboard controller class.
 *
 * @since  1.6
 */
class SocialadsControllerDashboard extends JControllerAdmin
{
	/**
	 * Get donut chart data for dashboard
	 *
	 * @return json
	 *
	 * @since 3.1
	 */
	public function getDonutChartData()
	{
		$params = JComponentHelper::getParams('com_socialads');
		$model  = $this->getModel('dashboard');
		$data   = $model->getPeriodicOrders($params->get('payment_mode'));

		// Output json response
		header('Content-type: application/json');
		echo json_encode($data);
		jexit();
	}

	/**
	 * Get bar chart data for dashboard
	 *
	 * @return json
	 *
	 * @since 3.1
	 */
	public function getBarChartData()
	{
		$allMonths     = SaCommonHelper::getAllmonths();
		$model         = $this->getModel('dashboard');
		$monthlyIncome = $model->getMonthlyIncome();

		// To assign amount from array monthyincome to array allmonths
		for ($i = 0; $i < count($allMonths); $i++)
		{
			for ($j = 0; $j < count($monthlyIncome); $j++)
			{
				if ($allMonths[$i]['digitmonth'] == $monthlyIncome[$j]['monthsname'])
				{
					$allMonths[$i]['amount'] = $monthlyIncome[$j]['tampunt'];
				}
			}
		}

		// Output json response
		header('Content-type: application/json');
		echo json_encode($allMonths);
		jexit();
	}

	/**
	 * Get News Feeds
	 *
	 * @return  json
	 *
	 * @since 3.1
	 */
	public function getNewsFeeds()
	{
		$model = $this->getModel('dashboard');
		$feeds = $model->getNewsFeeds();

		// Output json response
		header('Content-type: application/json');
		echo json_encode($feeds);
		jexit();
	}
}
