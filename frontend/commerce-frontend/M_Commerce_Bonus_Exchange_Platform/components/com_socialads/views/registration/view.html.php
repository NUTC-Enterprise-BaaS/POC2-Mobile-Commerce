<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * View to register
 *
 * @since  1.6
 */
class SocialadsViewRegistration extends JViewLegacy
{
	/**
	 * Display the view
	 *
	 * @param   STRING  $tpl  layout name
	 *
	 * @return  views display
	 */
	public function display($tpl = null)
	{
		$session = JFactory::getSession();
		$this->socialadsbackurl = $session->get('socialadsbackurl');
		$mainframe = JFactory::getApplication();
		$this->input = JFactory::getApplication()->input;
		$this->user = JFactory::getUser();
		$this->params = JComponentHelper::getParams('com_socialads');

		if ($this->user->id > 0)
		{
			$mainframe->redirect(JRoute::_($socialadsbackurl));
		}

		parent::display($tpl);
	}
}
