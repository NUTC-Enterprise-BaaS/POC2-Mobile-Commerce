<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

/**
 * Categories view class for the Category package.
 *
 */
class JBusinessDirectoryViewPoints extends JBusinessDirectoryAdminView
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');


		$this->statuses		= JBusinessDirectoryHelper:: getStatuses();
		JBusinessDirectoryHelper::addSubmenu('points');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		$canDo = JBusinessDirectoryHelper::getActions();
		$user  = JFactory::getUser();

		JToolBarHelper::title(   'J-BusinessDirectory : '.JText::_('LNG_POINTS'), 'generic.png' );
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_jbusinessdirectory', 'core.create'))) > 0 ) {
			// JToolbarHelper::addNew('point.add');
		}

		if (($canDo->get('core.edit'))) {
			JToolbarHelper::editList('point.edit');
		}
		if($canDo->get('core.delete')) {
			JToolbarHelper::divider();
			JToolbarHelper::deleteList('', 'points.delete');
		}

		if ($canDo->get('core.admin')) {
			JToolbarHelper::preferences('com_jbusinessdirectory');
		}
		JToolbarHelper::divider();
		JToolBarHelper::custom( 'points.back', 'dashboard', 'dashboard', JText::_("LNG_CONTROL_PANEL"), false, false );
		JToolBarHelper::help('', false, DOCUMENTATION_URL.'businessdiradmin.html#points' );
	}
}
