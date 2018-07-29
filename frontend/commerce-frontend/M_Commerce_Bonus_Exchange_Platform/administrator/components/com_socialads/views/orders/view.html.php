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
class SocialadsVieworders extends JViewLegacy
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
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		SocialadsHelper::addSubmenu('orders');

		if (JVERSION < 3.0)
		{
			$selectStatus[] = JHtml::_('select.option', '-1',  JText::_('SA_SELONE'));
			$pay[] = JHtml::_('select.option', '1', JText::_('NORMAL_PAY'));
			$selectStatusGateway[] = JHtml::_('select.option', '0', JText::_('FILTER_GATEWAY'));
		}

		$this->assignRef('selectStatus', $selectStatus);
		$this->assignRef('selectStatus_gateway', $selectStatusGateway);
		$this->assignRef('pay', $pay);

		$selectStatus = array();
		$selectStatus[] = JHtml::_('select.option', 'P',  JText::_('COM_SOCIALADS_AD_PENDING'));
		$selectStatus[] = JHtml::_('select.option', 'C',  JText::_('COM_SOCIALADS_AD_CONFIRM'));
		$selectStatus[] = JHtml::_('select.option', 'RF',  JText::_('COM_SOCIALADS_AD_REFUND'));
		$selectStatus[] = JHtml::_('select.option', 'E', JText::_('COM_SOCIALADS_AD_CANCEL'));
		$this->addToolbar();

		$this->ostatus = array();
		$this->ostatus[] = JHtml::_('select.option', '', JText::_('SA_ORDER_STATUS'));
		$this->ostatus[] = JHtml::_('select.option', 'P',  JText::_('COM_SOCIALADS_AD_PENDING'));
		$this->ostatus[] = JHtml::_('select.option', 'C',  JText::_('COM_SOCIALADS_AD_CONFIRM'));
		$this->ostatus[] = JHtml::_('select.option', 'RF',  JText::_('COM_SOCIALADS_AD_REFUND'));
		$this->ostatus[] = JHtml::_('select.option', 'E', JText::_('COM_SOCIALADS_AD_CANCEL'));

		if (JVERSION >= '3.0')
		{
			$this->sidebar = JHtmlSidebar::render();
		}

		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');
		$gateways = JFormHelper::loadFieldType('Gatewaylist', false);

		$this->gatewayoptions = $gateways->getOptions();

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
		$state = $this->get('State');
		$canDo = SocialadsHelper::getActions($state->get('filter.category_id'));

		if (JVERSION >= '3.0')
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ORDERS'), 'list');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ORDERS'), 'orders.png');
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_socialads');
		}

		if (JVERSION >= '3.0')
		{
			// Set sidebar action - New in 3.0
			JHtmlSidebar::setAction('index.php?option=com_socialads&view=orders');
		}

		$this->extra_sidebar = '';
	}

	/**
	 * For sorting filter.
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.cdate' => JText::_('COM_SOCIALADS_ADORDERS_CDATE'),
		'a.amount' => JText::_('COM_SOCIALADS_ADORDERS_AD_AMOUNT'),
		'a.status' => JText::_('COM_SOCIALADS_ADORDERS_STATUS'),
		'a.processor' => JText::_('COM_SOCIALADS_ADORDERS_PROCESSOR'),
		'u.username' => JText::_('COM_SOCIALADS_ADORDERS_USERNAME'),
		);
	}
}
