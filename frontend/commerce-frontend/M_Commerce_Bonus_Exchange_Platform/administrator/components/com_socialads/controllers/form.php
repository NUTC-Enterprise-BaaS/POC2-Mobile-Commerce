<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Ad controller class.
 *
 * @since  1.0
 */
class SocialadsControllerForm extends JControllerForm
{
	/**
	 *Function to construct a ad view
	 *
	 * @since  3.0
	 */
	public function __construct()
	{
		$this->view_list = 'forms';
		parent::__construct();
	}
}
