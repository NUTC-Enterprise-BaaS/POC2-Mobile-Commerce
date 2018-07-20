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
 * Archivestat controller class.
 *
 * @since  1.6
 */
class SocialadsControllerArchivestat extends JControllerForm
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
		$this->view_list = 'archivestats';
		parent::__construct();
	}
}
