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
 * HTML View class for the Aniket Component
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
		$this->params = JComponentHelper::getParams('com_socialads');
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$option = $input->get('option', '', 'STRING');
		$month = $mainframe->getUserStateFromRequest($option . 'month', 'month', '', 'int');
		$year = $mainframe->getUserStateFromRequest($option . 'year', 'year', '', 'int');
		$builadModel = $this->getModel();
		$user_id = $mainframe->getUserStateFromRequest($option, 'userid', '0', 'int');
		$this->wallet = $builadModel->getwallet($user_id);
		$lists['month'] = $month;
		$lists['year'] = $year;
		$this->lists = $lists;
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
