<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::register('AcmanagerFrontendHelper', JPATH_COMPONENT . '/helpers/acmanager.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Acmanager');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
