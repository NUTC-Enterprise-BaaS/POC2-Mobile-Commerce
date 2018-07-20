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

jimport('joomla.application.component.modelitem');
JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'offercoupon.php');

class JBusinessDirectoryModelOffer extends JModelItem { 
	
	function __construct() {
		parent::__construct();
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->offerId = JRequest::getVar('offerId');
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Offer', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	function getOffer() {
		$offersTable = JTable::getInstance("Offer", "JTable");
		$offer = $offersTable->getOffer($this->offerId);
		$offer->pictures = $offersTable->getOfferPictures($this->offerId);
		$offersTable->increaseViewCount($this->offerId);
		
		$companiesTable = JTable::getInstance("Company", "JTable");
		$company = $companiesTable->getCompany($offer->companyId);
		$offer->company=$company;
		$offer->checkOffer = $this->checkOffer();
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEntityTranslation($offer, OFFER_DESCRIPTION_TRANSLATION);
		}
	
		$offer->attachments = JBusinessDirectoryAttachments::getAttachments(OFFER_ATTACHMENTS, $this->offerId, true);
		
		return $offer;
	}

	public function saveCoupon() {
		//offer ID
		$this->offerId = JFactory::getApplication()->input->get('id');
		//get user
		$user = JFactory::getUser();
		//create a unique code
		$unique = $user->id.$this->offerId;
		//generate the coupon code
		$generatedCode = substr(md5($unique), 0, 15);

		$checkCoupon = false; //coupon dosen't exist

		$offersTable = JTable::getInstance("Offer", "JTable");
		$offer = $offersTable->getOffer($this->offerId);

		$offerCouponsTable = JTable::getInstance("OfferCoupon", "JTable");
		$totalOfferCoupons = $offerCouponsTable->getTotalOfferCoupons($this->offerId);
		
		$this->checkCoupon = $offerCouponsTable->checkCoupon($generatedCode);
		//if coupon already exist
		if($this->checkCoupon)
			$checkCoupon = true;

		//If the total number of available coupons in not reached and the coupon dosen't exist -> save the coupon
		if(($totalOfferCoupons < $offer->total_coupons) && (!$this->checkCoupon)) {
			$checkCoupon = $offerCouponsTable->saveCoupon($user->id, $this->offerId, $generatedCode);
		}

		return $checkCoupon;
	}

	public function getCoupon() {
		//offer ID
		$this->offerId = JFactory::getApplication()->input->get('id');
		//get user
		$user = JFactory::getUser();
		//create a unique code
		$unique = $user->id.$this->offerId;
		//generate the coupon code
		$generatedCode = substr(md5($unique), 0, 15);
		
		$offerCouponsTable = JTable::getInstance("OfferCoupon", "JTable");
		$coupon = $offerCouponsTable->getCouponByCode($generatedCode);
		
		if($coupon) {
			$model = $this->getInstance('OfferCoupon', 'JBusinessDirectoryModel');
			$model->show($coupon->id);
		}
	}

	public function checkOffer() {
		$offerCouponsTable = JTable::getInstance("OfferCoupon", "JTable");
		$checkOffer = $offerCouponsTable->checkOffer($this->offerId);
		
		return $checkOffer;
	}

	function getOfferAttributes(){
		$attributesTable = $this->getTable('OfferAttributes');
		return  $attributesTable->getOfferAttributes($this->offerId);
	}
}
?>

