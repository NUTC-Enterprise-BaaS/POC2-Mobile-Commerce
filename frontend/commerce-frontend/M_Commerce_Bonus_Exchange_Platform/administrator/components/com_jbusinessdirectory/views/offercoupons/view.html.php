<?php
/**
 * @package     JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die('Restricted access');

/**
 * The HTML Menus Menu Menus View.
 *
 * @package     JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 */

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class JBusinessDirectoryViewOfferCoupons extends JBusinessDirectoryAdminView {

	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {

		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		$this->statuses		= $this->get('Statuses');
		$this->states		= $this->get('States');
		
		JBusinessDirectoryHelper::addSubmenu('offercoupons');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar() {
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();

		JToolBarHelper::title('J-BusinessDirectory : '.JText::_('LNG_OFFERCOUPONS'), 'generic.png' );
		
		if($canDo->get('core.delete')) {
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('', 'offercoupons.delete');
		}

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
		
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'offercoupons.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#offercoupons' );
	}
}