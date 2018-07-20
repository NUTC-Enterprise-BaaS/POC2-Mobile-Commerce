<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'models');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.'offercoupon.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'offercoupon.php');

class JBusinessDirectoryControllerManageCompanyOfferCoupon extends JBusinessDirectoryControllerOfferCoupon {

	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {
		parent::__construct();
		$this->log = Logger::getInstance();
	}

	/**
	 * Dummy method to redirect back to standard controller
	 *
	 */
	public function display($cachable = false, $urlparams = false) {
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffercoupons', false));
	}
}