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

class JTableOfferCoupon extends JTable {

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__jbusinessdirectory_company_offer_coupons', 'id', $db);
	}

	function setKey($k) {
		$this->_tbl_key = $k;
	}

	function getCoupon($couponId) {
		$db = JFactory::getDBO();
		$query = "select ofc.*, co.name as company, co.phone as phone, of.subject as offer, of.description as offer_description, of.address as offer_address, of.city as offer_city, of.endDate as expiration_time
					from #__jbusinessdirectory_company_offer_coupons ofc
					left join #__jbusinessdirectory_company_offers of on of.id=ofc.offer_id
					left join #__jbusinessdirectory_companies co on co.id=of.companyId
					where ofc.id='$couponId'";
		$db->setQuery($query);
		return $db->loadObject();
	}

	function getCouponByCode($code) {
		$db = JFactory::getDBO();
		$query = "select ofc.*, co.name as company, co.phone as phone, of.subject as offer, of.description as offer_description, of.address as offer_address, of.city as offer_city, of.endDate as expiration_time
					from #__jbusinessdirectory_company_offer_coupons ofc
					left join #__jbusinessdirectory_company_offers of on of.id=ofc.offer_id
					left join #__jbusinessdirectory_companies co on co.id=of.companyId
					where ofc.code='$code'";
		$db->setQuery($query);
		return $db->loadObject();
	}

	function getCoupons($filter, $limitstart=0, $limit=0) {
		$db = JFactory::getDBO();
		$query = "select ofc.*, co.id as company_id, co.name as company, co.phone as phone, of.subject as offer, of.endDate as expiration_time
					from #__jbusinessdirectory_company_offer_coupons ofc
					left join #__jbusinessdirectory_company_offers of on of.id=ofc.offer_id
					left join #__jbusinessdirectory_companies co on co.id=of.companyId
					$filter";
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function checkCoupon($code) {
		$db = JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_offer_coupons where code='$code'";
		$db->setQuery($query);
		$db->query();
		$num_rows = $db->getNumRows();
		if($num_rows>0)
			return true;
		return false;
	}

	function saveCoupon($userId, $offerId, $code) {
		$db = JFactory::getDBO();
		$code = $db->escape($code);
		
		$query = "insert into #__jbusinessdirectory_company_offer_coupons (user_id, offer_id, code, generated_time) VALUES ('$userId', '$offerId', '$code', NOW())";
		$db->setQuery($query);
		if (!$db->execute())
			return false;
		return true;
	}

	function getCouponsByUserId($userId, $limitstart=0, $limit=0) {
		$db = JFactory::getDBO();
		$query = "select ofc.*, co.id as company_id, co.name as company, co.phone as phone, of.subject as offer, of.endDate as expiration_time
					from #__jbusinessdirectory_company_offer_coupons ofc
					left join #__jbusinessdirectory_company_offers of on of.id=ofc.offer_id
					left join #__jbusinessdirectory_companies co on co.id=of.companyId
					where user_id='$userId'";
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function getTotalOfferCoupons($offerId) {
		$db = JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_offer_coupons where offer_id='$offerId'";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function checkOffer($offerId) {
		$totalCoupons = $this->getTotalOfferCoupons($offerId);
		
		$db = JFactory::getDBO();
		$query = "select total_coupons, endDate from #__jbusinessdirectory_company_offers where id='$offerId'";
		$db->setQuery($query);
		$offer = $db->loadObject();

		$today      = strtotime(date("Y-m-d"));
		$endOffer   = strtotime($offer->endDate);

		// If the total coupons available is not reached and the offer has not expired
		if( ((int)$offer->total_coupons > $totalCoupons) && ($endOffer >= $today) )
			return true;

		return false;
	}
}