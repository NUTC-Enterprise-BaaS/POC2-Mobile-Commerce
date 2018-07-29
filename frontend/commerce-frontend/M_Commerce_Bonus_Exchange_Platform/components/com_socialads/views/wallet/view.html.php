<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Wallet
 *
 * @since  1.6
 */
class SocialadsViewWallet extends JViewLegacy
{
	/**
	 * Overwriting JView display method
	 *
	 * @param   boolean  $cachable   parameter.
	 * @param   boolean  $urlparams  url parameter.
	 * @param   array    $tpl        An optional associative array.
	 *
	 * @return  array
	 *
	 * @since 1.6
	 */
	public function display($cachable = false, $urlparams = false,$tpl = null)
	{
		$this->session1 = JFactory::getSession();
		$this->params = JComponentHelper::getParams('com_socialads');
		$this->user = JFactory::getUser();
		$this->mainframe = JFactory::getApplication();
		$this->input = JFactory::getApplication()->input;

		if (!$this->user->id)
		{
			$registration_form = $this->params->get('registration_form');
			$msg = JText::_('COM_SOCIALADS_PLEASE_LOGIN');

			if ($registration_form)
			{
				$itemid = $this->input->get('Itemid', 0, 'INT');
				$this->session1->set('socialadsbackurl', $_SERVER["REQUEST_URI"]);
				$this->mainframe->redirect(JRoute::_('index.php?option=com_socialads&view=registration&Itemid=' . $itemid, false), $msg);
			}
			else
			{
				$uri = $this->input->server->get('REQUEST_URI', '', 'STRING');
				$url = urlencode(base64_encode($uri));
				$this->mainframe->redirect(JRoute::_('index.php?option=com_users&view=login&return=' . $url, false), $msg);
			}
		}

		$init_balance = SaWalletHelper::getBalance();

		if ($init_balance != 1.00)
		{
			$itemid  = SaCommonHelper::getSocialadsItemid('payment');
			$not_msg = JText::_('COM_SOCIALADS_WALLET_MINIMUM_BALANCE_MESSAGE');
			$not_msg = str_replace(
			'{clk_pay_link}', '<a href="'
			. JRoute::_('index.php?option=com_socialads&view=payment&Itemid=' . $itemid)
			. '">' . JText::_('COM_SOCIALADS_WALLET_CLKHERE') . '</a>', $not_msg
			);

			JError::raiseNotice(100, $not_msg);
		}

		$option = $this->input->get('option', '', 'STRING');
		$month = $this->mainframe->getUserStateFromRequest($option . 'month', 'month', '', 'int');
		$year = $this->mainframe->getUserStateFromRequest($option . 'year', 'year', '', 'int');
		$builadModel = $this->getModel();
		$user_id = $this->user->id;
		$this->wallet = $builadModel->getwallet($user_id);
		$this->months = array(
			0 => JText::_('COM_SOCIALADS_WALLET_MONTH'),
			1 => JText::_('JANUARY_SHORT'),
			2 => JText::_('FEBRUARY_SHORT'),
			3 => JText::_('MARCH_SHORT'),
			4 => JText::_('APRIL_SHORT'),
			5 => JText::_('MAY_SHORT'),
			6 => JText::_('JUNE_SHORT'),
			7 => JText::_('JULY_SHORT'),
			8 => JText::_('AUGUST_SHORT'),
			9 => JText::_('SEPTEMBER_SHORT'),
			10 => JText::_('OCTOBER_SHORT'),
			11 => JText::_('NOVEMBER_SHORT'),
			12 => JText::_('DECEMBER_SHORT'),
		);
		$lists['month'] = $month;
		$lists['year'] = $year;
		$this->lists = $lists;
		$this->month = array();

		foreach ($this->months as $key => $value) :
			$this->month[] = JHtml::_('select.option', $key, $value);
		endforeach;

		// Year filter
		$this->year = array();
		$curYear = date('Y');
		$this->year = range($curYear, 2000, 1);

		foreach ($this->year as $key => $value)
		{
			unset($this->year[$key]);
			$this->year[$value] = $value;
		}

		foreach ($this->year as $key => $value) :
			$year1[] = JHtml::_('select.option', $key, $value);
		endforeach;

		parent::display($tpl);
	}
}
