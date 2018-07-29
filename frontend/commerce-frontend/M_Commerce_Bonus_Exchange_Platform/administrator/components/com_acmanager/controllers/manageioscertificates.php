<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Manageioscertificates controller class.
 *
 * @since  1.6
 */
class AcmanagerControllerManageioscertificates extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'manageioscertificatess';
		parent::__construct();
	}
}
