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

jimport('joomla.application.component.controllerform');

/**
 * Campaign controller class.
 *
 * @since  1.6
 */
class SocialadsControllerCampaign extends JControllerForm
{
	/**
	 * Constructor.
	 *
	 * @see  JController
	 *
	 * @since  1.6
	 */
	public function __construct()
	{
		$this->view_list = 'campaigns';
		parent::__construct();
	}

	/**
	 * Redirect to create new campaign view
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function addNew()
	{
		$input = JFactory::getApplication()->input;
		$redirect = JRoute::_('index.php?option=com_socialads&view=campaign&layout=edit', false);
		$this->setRedirect($redirect, '');
	}
}
