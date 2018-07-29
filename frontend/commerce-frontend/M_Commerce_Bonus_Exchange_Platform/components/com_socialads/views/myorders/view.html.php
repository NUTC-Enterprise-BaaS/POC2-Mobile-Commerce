<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the My orders
 *
 * @since  1.6
 */
class SocialadsViewMyorders extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$this->state      = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->params     = $app->getParams('com_socialads');
		$this->user = JFactory::getUser();
		$this->input = JFactory::getApplication()->input;
		$this->session = JFactory::getSession();
		$this->mainframe = JFactory::getApplication();

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

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->ostatus = array();
		$this->ostatus[] = JHtml::_('select.option', '', JText::_('COM_SOCIALADS_ORDERS_APPROVAL_STATUS'));
		$this->ostatus[] = JHtml::_('select.option', 'P',  JText::_('COM_SOCIALADS_SA_PENDIN'));
		$this->ostatus[] = JHtml::_('select.option', 'C',  JText::_('COM_SOCIALADS_SA_CONFIRM'));
		$this->ostatus[] = JHtml::_('select.option', 'RF',  JText::_('COM_SOCIALADS_SA_REFUND'));
		$this->ostatus[] = JHtml::_('select.option', 'E', JText::_('COM_SOCIALADS_SA_REJECTED'));

		$this->_prepareDocument();
		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_SOCIALADS_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}
}
