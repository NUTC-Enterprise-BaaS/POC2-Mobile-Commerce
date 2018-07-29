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
class SocialadsViewAdorders extends JViewLegacy
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

		SocialadsHelper::addSubmenu('adorders');

					$pstatus = array();

		if (JVERSION < 3.0)
		{
			$selectStatus[] = JHtml::_('select.option', '-1',  JText::_('SA_SELONE'));
			$pay[] = JHtml::_('select.option', '1', JText::_('NORMAL_PAY'));
			$selectStatusGateway[] = JHtml::_('select.option', '0', JText::_('FILTER_GATEWAY'));
		}

		$this->assignRef('selectStatus', $selectStatus);
		$this->assignRef('selectStatusGateway', $selectStatusGateway);
		$this->assignRef('pay', $pay);
		$pstatus[] = JHtml::_('select.option', 'P', JText::_('COM_SOCIALADS_AD_PENDING'));
		$pstatus[] = JHtml::_('select.option', 'C', JText::_('COM_SOCIALADS_AD_CONFIRM'));
		$pstatus[] = JHtml::_('select.option', 'RF', JText::_('COM_SOCIALADS_AD_REFUND'));
		$pstatus[] = JHtml::_('select.option', 'E', JText::_('COM_SOCIALADS_AD_CANCEL'));
		$this->assignRef('pstatus', $pstatus);
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
		$input = JFactory::getApplication()->input;
		$layout = $input->get('layout', '', 'STRING');

		if ($layout == "details")
		{
			$this->order_id = $input->get('id', '', 'INT');
			$this->socialadsPaymentHelper = new SocialadsPaymentHelper;
			$this->adDetail = $this->socialadsPaymentHelper->getOrderAndAdDetail($this->order_id);
			$this->userInformation = $this->socialadsPaymentHelper->userInfo($this->order_id);

			// No of clicks or impression
			$this->chargeoption = $this->adDetail['ad_payment_type'];
			$this->ad_totaldisplay = $this->adDetail['ad_credits_qty'];
			$this->ad_gateways = $gateways;
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

		$state = $this->get('State');
		$canDo = SocialadsHelper::getActions($state->get('filter.category_id'));

		if (JVERSION >= '3.0')
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ADORDERS'), 'list');
		}
		else
		{
			JToolBarHelper::title(JText::_('COM_SOCIALADS') . ': ' . JText::_('COM_SOCIALADS_TITLE_ADORDERS'), 'adorders.png');
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_socialads');
		}

		if (JVERSION >= '3.0')
		{
			// Set sidebar action - New in 3.0
			JHtmlSidebar::setAction('index.php?option=com_socialads&view=adorders');
		}

		$this->extra_sidebar = '';
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$layout = $input->get('layout', '', 'STRING');

		if ($layout == 'details')
		{
			JToolBarHelper::back('COM_SOCIALADS_BACK', 'index.php?option=com_socialads&view=adorders');

			JToolBarHelper::custom('printOrder', 'print', 'print', 'COM_SOCIALADS_PRINT', false);
		}
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
		'o.id' => JText::_('JGRID_HEADING_ID'),
		'd.ad_id' => JText::_('COM_SOCIALADS_ADORDERS_AD_ID'),
		'd.ad_title' => JText::_('COM_SOCIALADS_ADORDERS_AD_TITLE'),
		'o.cdate' => JText::_('COM_SOCIALADS_ADORDERS_CDATE'),
		'p.ad_credits_qty' => JText::_('COM_SOCIALADS_ADORDERS_AD_CREDITS_QTY'),
		'o.amount' => JText::_('COM_SOCIALADS_ADORDERS_AD_AMOUNT'),
		'o.status' => JText::_('COM_SOCIALADS_ADORDERS_STATUS'),
		'u.username' => JText::_('COM_SOCIALADS_ADORDERS_USERNAME'),
		'o.processor' => JText::_('COM_SOCIALADS_ADORDERS_PROCESSOR'),
		'd.ad_payment_type' => JText::_('COM_SOCIALADS_ADORDERS_AD_TYPE'),
		);
	}
}
