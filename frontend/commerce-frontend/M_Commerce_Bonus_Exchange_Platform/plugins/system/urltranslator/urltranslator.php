<?php
/*------------------------------------------------------------------------
 # JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

if (file_exists(JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php')) {
	require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/defines.php';
}
if (file_exists(JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php')) {
	require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php';
}
/**
 * Joomla! System Remember Me Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	System.remember
 */
class plgSystemUrlTranslator extends JPlugin
{
	function onAfterRoute()
	{
		$app = JFactory::getApplication();

		// No remember me for admin
		if ($app->isAdmin()) {
			return;
		}
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		if(!$appSettings->enable_seo){
			return;
		}
		
		$url = str_replace(JURI::base(),"",JURI::current());
		$url = str_replace("index.php/","",$url);

		$category = null;
		$keyword = null;

		$pieces = explode("/", $url);
		if(count($pieces)>1){
			$keyword= end($pieces);
			$category = prev($pieces);
		}else{
			$keyword = $url;
		}
		
		if(strpos($keyword, "?")){
			$arr = explode("?", $keyword);
			$keyword = $arr[0];
		}
	
		$params = array();
		if($category==CATEGORY_URL_NAMING){
			$params = $this->getCategoryParms( $keyword,"search",CATEGORY_TYPE_BUSINESS);
		}else if($category==OFFER_CATEGORY_URL_NAMING){
			$params = $this->getCategoryParms( $keyword,"offers",CATEGORY_TYPE_OFFER);
		}else if($category==EVENT_CATEGORY_URL_NAMING){
			$params = $this->getCategoryParms($keyword,"events",CATEGORY_TYPE_EVENT);
		}else if($category==OFFER_URL_NAMING){
			$params = $this->getOffersParms($keyword);
		}else if($category==EVENT_URL_NAMING){
			$params = $this->getEventParms($keyword);
		}else if($category==CONFERENCE_URL_NAMING){
			$params = $this->getConferenceParms($keyword);
		}else if($category==CONFERENCE_SESSION_URL_NAMING){
			$params = $this->getConferenceSessionParms($keyword);
		}else if($category==SPEAKER_URL_NAMING){
			$params = $this->getSpeakerParms($keyword);
		}else{
			$params = $this->getCategoryParms($keyword,"search",CATEGORY_TYPE_BUSINESS);
			//dump($params);
			if(empty($params) || (!empty($params) && (isset($params["option"]) && $params["option"] != "com_jbusinessdirectory")))
				$params = $this->getBusinessListingParms($keyword);
		}

		if(!empty($params)){
			JRequest::set($params,'get',true);
		}
		return;
	}

	function getBusinessListingParms($companyLink){
		
		$params = JRequest::get('GET');
		$db = JFactory::getDBO();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

		$company = null;
		
		if($appSettings->add_url_id == 1){
			$companyId = substr($companyLink, 0, strpos($companyLink, "-"));
			$companyAlias = substr($companyLink, strpos($companyLink, "-")+1);
			$companyAlias = urldecode($companyAlias);

			if(!is_numeric($companyId)){
				return;
			}

			$query= "SELECT * FROM `#__jbusinessdirectory_companies` c where c.id = $companyId";
			$db->setQuery($query, 0, 1);
			$company = $db->loadObject();
		}else{
			$companyAlias = urldecode($companyLink);
			$companyAlias = $db->escape($companyAlias);
			$query= "SELECT * FROM `#__jbusinessdirectory_companies` c where c.alias = '$companyAlias'";
			$db->setQuery($query, 0, 1);
			$company = $db->loadObject();
		}

		if(!empty($company) && strcmp($companyAlias, $company->alias)==0 && !empty($company->alias)){
			$params["option"] = "com_jbusinessdirectory";
			$params["controller"] = "companies";
			$params["task"] = "showcompany";
			$params["companyId"] = $company->id;
			$params["view"] = "companies";
			$params["Itemid"] = "";
		}else{
			return null;
		}

		return $params;
	}

	function getCategoryParms($categoryLink, $type, $categoryType){
		$params = array();
		$db = JFactory::getDBO();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

		$category = null;
		if($appSettings->add_url_id == 1){
			$categoryId = substr($categoryLink, 0, strpos($categoryLink, "-"));
			$categoryAlias = substr($categoryLink, strpos($categoryLink, "-")+1);
			$categoryAlias = urldecode($categoryAlias);
				
			if(!is_numeric($categoryId) || empty($categoryId)){
				return;
			}
			$query= "SELECT * FROM #__jbusinessdirectory_categories c where c.id = $categoryId ";
			$db->setQuery($query, 0, 1);
			$category = $db->loadObject();
		}else{
			$categoryAlias = urldecode($categoryLink);
			$categoryAlias = $db->escape($categoryAlias);
			$query= "SELECT * FROM #__jbusinessdirectory_categories c where c.type=$categoryType and c.alias = '$categoryAlias' ";
			$db->setQuery($query, 0, 1);
			$category = $db->loadObject();
		}

		if(!empty($category) && strcmp(strtolower($categoryAlias), (strtolower($category->alias)))==0 && !empty($category->alias)){
			$params["option"] = "com_jbusinessdirectory";
			$params["controller"] = $type;
			$params["categoryId"] = $category->id;
			$params["categorySearch"] = $category->id;
			$params["view"] = $type;
			$params["Itemid"] = "";
		}

		return $params;
	}


	function getOffersParms($keyword){
		$params = JRequest::get('GET');
		$db = JFactory::getDBO();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

		$offer = null;
		if($appSettings->add_url_id == 1){
			$offerId = substr($keyword, 0, strpos($keyword, "-"));
			$offerAlias = substr($keyword, strpos($keyword, "-")+1);
			$offerAlias = urldecode($offerAlias);
			$offerAlias = trim($offerAlias);
				
			if(!is_numeric($offerId) || empty($offerId)){
				return;
			}

			$db = JFactory::getDBO();
			$query= "SELECT * FROM #__jbusinessdirectory_company_offers o where o.id = $offerId ";

			$db->setQuery($query, 0, 1);
			$offer = $db->loadObject();
		}else{
			$offerAlias = urldecode($keyword);
			$offerAlias = $db->escape($offerAlias);
			$query= "SELECT * FROM #__jbusinessdirectory_company_offers o where o.alias = '$offerAlias' ";
			$db->setQuery($query, 0, 1);
			$offer = $db->loadObject();
		}

		if(!empty($offer) && strcmp(strtolower($offerAlias), (strtolower($offer->alias)))==0  && !empty($offer->alias)){
			$params["option"] = "com_jbusinessdirectory";
			$params["controller"] = "offer";
			$params["offerId"] = $offer->id;
			$params["view"] = "offer";
			$params["Itemid"] = "";
		}

		return $params;
	}

	function getEventParms($keyword){
		$params = JRequest::get('GET');
		$db = JFactory::getDBO();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

		$event = null;
		if($appSettings->add_url_id == 1){
			$eventId = substr($keyword, 0, strpos($keyword, "-"));
			$eventAlias = substr($keyword, strpos($keyword, "-")+1);
			$eventAlias = urldecode($eventAlias);
			$eventAlias = trim($eventAlias);

			if(!is_numeric($eventId)){
				return;
			}

			$db = JFactory::getDBO();
			$query= "SELECT * FROM #__jbusinessdirectory_company_events e where e.id = $eventId ";

			$db->setQuery($query, 0, 1);
			$event = $db->loadObject();

		}else{
			$eventAlias = urldecode($keyword);
			$eventAlias = $db->escape($eventAlias);
			$query= "SELECT * FROM #__jbusinessdirectory_company_events e where e.alias = '$eventAlias' ";
			$db->setQuery($query, 0, 1);
			$event = $db->loadObject();
				
		}

		if(!empty($event) && strcmp(strtolower($eventAlias), (strtolower($event->alias)))==0 && !empty($event->alias)){
			$params["option"] = "com_jbusinessdirectory";
			$params["controller"] = "event";
			$params["eventId"] = $event->id;
			$params["view"] = "event";
			$params["Itemid"] = "";
		}

		return $params;
	}
	
	function getConferenceParms($keyword){
		$params = JRequest::get('GET');
		$db = JFactory::getDBO();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$conference = null;
		if($appSettings->add_url_id == 1){
			$conferenceId = substr($keyword, 0, strpos($keyword, "-"));
			$conferenceAlias = substr($keyword, strpos($keyword, "-")+1);
			$conferenceAlias = urldecode($conferenceAlias);
			$conferenceAlias = trim($conferenceAlias);
	
			if(!is_numeric($conferenceId)){
				return;
			}
			$query= "SELECT * FROM #__jbusinessdirectory_conferences c where c.id = $conferenceId ";
	
			$db->setQuery($query, 0, 1);
			$conference = $db->loadObject();
	
		}else{
			$conferenceAlias = urldecode($keyword);
			$conferenceAlias = $db->escape($conferenceAlias);
			$query= "SELECT * FROM #__jbusinessdirectory_conferences c where c.alias = '$conferenceAlias' ";
			$db->setQuery($query, 0, 1);
			$conference = $db->loadObject();
	
		}
	
		if(!empty($conference) && strcmp(strtolower($conferenceAlias), (strtolower($conference->alias)))==0 && !empty($conference->alias)){
			$params["option"] = "com_jbusinessdirectory";
			$params["controller"] = "conference";
			$params["conferenceId"] = $conference->id;
			$params["view"] = "conference";
			$params["Itemid"] = "";
		}
	
		return $params;
	}
	
	function getConferenceSessionParms($keyword){
		$params = JRequest::get('GET');
		$db = JFactory::getDBO();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$conferenceSession = null;
		if($appSettings->add_url_id == 1){
			$cSessionId = substr($keyword, 0, strpos($keyword, "-"));
			$cSessionAlias = substr($keyword, strpos($keyword, "-")+1);
			$cSessionAlias = urldecode($cSessionAlias);
			$cSessionAlias = trim($cSessionAlias);
			
			if(!is_numeric($cSessionId)){
				return;
			}
	
			$db = JFactory::getDBO();
			$query= "SELECT * FROM #__jbusinessdirectory_conference_sessions cs where cs.id = $cSessionId ";
	
			$db->setQuery($query, 0, 1);
			$conferenceSession = $db->loadObject();
	
		}else{
			$cSessionAlias = urldecode($keyword);
			$cSessionAlias = $db->escape($cSessionAlias);
			$query= "SELECT * FROM #__jbusinessdirectory_conference_sessions cs where cs.alias = '$cSessionAlias' ";
			$db->setQuery($query, 0, 1);
			$conferenceSession = $db->loadObject();
		}
	
		
		if(!empty($conferenceSession) && strcmp(strtolower($cSessionAlias), (strtolower($conferenceSession->alias)))==0 && !empty($conferenceSession->alias)){
			$params["option"] = "com_jbusinessdirectory";
			$params["controller"] = "conferencesession";
			$params["cSessionId"] = $conferenceSession->id;
			$params["view"] = "conferencesession";
			$params["Itemid"] = "";
		}

		return $params;
	}

	function getSpeakerParms($keyword){
		$params = JRequest::get('GET');
		$db = JFactory::getDBO();
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$speaker = null;
		if($appSettings->add_url_id == 1){
			$speakerId = substr($keyword, 0, strpos($keyword, "-"));
			$speakerAlias = substr($keyword, strpos($keyword, "-")+1);
			$speakerAlias = urldecode($speakerAlias);
			$speakerAlias = trim($speakerAlias);
	
			if(!is_numeric($speakerId)){
				return;
			}
			$query= "SELECT * FROM #__jbusinessdirectory_conference_speakers s where s.id = $speakerId ";
	
			$db->setQuery($query, 0, 1);
			$speaker = $db->loadObject();
		}else{
			$speakerAlias = urldecode($keyword);
			$speakerAlias = $db->escape($speakerAlias);
			$query= "SELECT * FROM #__jbusinessdirectory_conference_speakers s where s.alias = '$speakerAlias' ";
			$db->setQuery($query, 0, 1);
			$speaker = $db->loadObject();
		}
	
		if(!empty($speaker) && strcmp(strtolower($speakerAlias), (strtolower($speaker->alias)))==0 && !empty($speaker->alias)){
			$params["option"] = "com_jbusinessdirectory";
			$params["controller"] = "speaker";
			$params["speakerId"] = $speaker->id;
			$params["view"] = "speaker";
			$params["Itemid"] = "";
		}
	
		return $params;
	}
}
