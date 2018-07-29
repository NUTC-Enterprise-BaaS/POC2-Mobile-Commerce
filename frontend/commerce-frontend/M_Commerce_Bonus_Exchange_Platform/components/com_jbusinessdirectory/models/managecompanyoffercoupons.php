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
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'offercoupons.php');
require_once(JPATH_COMPONENT.DS.'libraries'.DS.'phpqrcode'.DS.'qrlib.php');
require_once(JPATH_COMPONENT.DS.'libraries'.DS.'tfpdf'.DS.'tfpdf.php');

class JBusinessDirectoryModelManageCompanyOfferCoupons extends JBusinessDirectoryModelOfferCoupons {
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'OfferCoupons', $prefix = 'JTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	*
	* @return object with data
	*/
	function getOfferCoupons() {
		// Load the data
		$user = JFactory::getUser();
		$offercouponsTable = $this->getTable("OfferCoupon");
		if (empty($this->_data)) {
			$this->_data = $offercouponsTable->getCouponsByUserId($user->id, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}
}
?>