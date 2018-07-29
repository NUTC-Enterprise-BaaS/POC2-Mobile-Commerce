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

class JBusinessDirectoryControllerOffer extends JControllerLegacy {
	
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * Generate coupon
	 */
	public function generateCoupon() {
		$model = $this->getModel('Offer');

		$user = JFactory::getUser();
		if (!$user->guest) {
			if ($model->saveCoupon()) {
				JFactory::getApplication()->enqueueMessage(JText::_('LNG_CONGRATULATION_FOR_YOUR_COUPON'));
				$model->getCoupon();
			} else {
				JError::raiseWarning(500, JText::_('LNG_SORRY_COUPONS_TAKEN'));
			}
		} else {
			JError::raiseWarning(500, JText::_('LNG_YOU_HAVE_TO_BE_LOGGED_IN'));
			$this->setRedirect('index.php?option=com_jbusinessdirectory&view=offers');
		}
	}
}