<?php
/*------------------------------------------------------------------------
 # JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

if (!function_exists('dump')) {
	function dump()
	{
		$args = func_get_args();

		echo '<pre>';

		foreach ($args as $arg) {
			var_dump($arg);
		}
		echo '</pre>';
		//exit;
	}
}
if (!function_exists('dbg')) {
	function dbg( $text )
	{
		echo "<pre>";
		var_dump($text);
		echo "</pre>";
	}
}

class JBusinessUtil{

	var $applicationSettings ;

	private function __construct()
	{

	}

	public static function getInstance()
	{
		static $instance;
		if ($instance === null) {
			$instance = new JBusinessUtil();
		}
		return $instance;
	}

	public static function getApplicationSettings(){
		$instance = JBusinessUtil::getInstance();

		if(!isset($instance->applicationSettings)){
			$instance->applicationSettings = self::getAppSettings();
		}
		return $instance->applicationSettings;
	}
	
	static function getAppSettings(){
		$db		= JFactory::getDBO();
		$query	= "	SELECT fas.*, df.*, c.currency_name, c.currency_id FROM #__jbusinessdirectory_applicationsettings fas
					inner join  #__jbusinessdirectory_date_formats df on fas.date_format_id=df.id
					inner join  #__jbusinessdirectory_currencies c on fas.currency_id=c.currency_id";
	
		//dump($query);
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObject();
	}

	public static function loadClasses(){
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		//load payment processors
		$classpath = JPATH_COMPONENT_SITE  .DS.'classes'.DS.'payment'.DS.'processors';
		foreach( JFolder::files($classpath) as $file ) {
			JLoader::register(JFile::stripExt($file), $classpath.DS.$file);
		}

		//load payment processors
		$classpath = JPATH_COMPONENT_SITE  .DS.'classes'.DS.'payment';
		foreach( JFolder::files($classpath) as $file ) {
			JLoader::register(JFile::stripExt($file), $classpath.DS.$file);
		}

		//load services
		$classpath = JPATH_COMPONENT_SITE  .DS.'classes'.DS.'services';
		foreach( JFolder::files($classpath) as $file ) {
			JLoader::register(JFile::stripExt($file), $classpath.DS.$file);
		}
	}


	public static function getURLData($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public static function getCoordinates($zipCode) {

		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

		$limitCountries = array();
		$location = null;

		if(!empty($appSettings->country_ids)) {
			$countryIDs = explode(",", $appSettings->country_ids);

			JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
			$countryTable = JTable::getInstance("Country", "JTable");

			foreach ($countryIDs as $countryID) {
				$country = $countryTable->getCountry($countryID);
				array_push($limitCountries, $country->country_code);
			}
		}
		
		$key="";
		if(!empty($appSettings->google_map_key))
			$key="&key=".$appSettings->google_map_key;
		
		$url ="https://maps.googleapis.com/maps/api/geocode/json?sensor=false$key&address=".urlencode($zipCode);
		$data = file_get_contents($url);
		$search_data = json_decode($data);
		if(!empty($search_data) && !empty($search_data->results)){
			$lat =  $search_data->results[0]->geometry->location->lat;
			$lng =  $search_data->results[0]->geometry->location->lng;
			
			if(!empty($limitCountries)){
				foreach($search_data->results as $result){
					$country = "";
					foreach($result->address_components as $addressCmp){
						if(!empty($addressCmp->types) && $addressCmp->types[0]=="country"){
							$country = $addressCmp->short_name;
						}
					}
					if(in_array($country, $limitCountries)){
						$lat =  $result->geometry->location->lat;
						$lng =  $result->geometry->location->lng;
					}
				}
			}
		
			$location =  array();
			$location["latitude"] = $lat;
			$location["longitude"] = $lng;
		}
		
		return $location;
	}


	public static function parseDays($days){
		$date1 = time();
		$date2 = strtotime("+$days day");

		$diff = abs($date2 - $date1);

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
		$result = new stdClass();

		$result->days = $days;
		$result->months = $months;
		$result->years = $years;

		return $result;
	}

	
	static function getComponentName(){
		$componentname = JRequest::getVar('option');
		return $componentname;
	}
	
	static function makePathFile($path){
		$path_tmp = str_replace( '\\', DIRECTORY_SEPARATOR, $path );
		$path_tmp = str_replace( '/', DIRECTORY_SEPARATOR, $path_tmp);
		return $path_tmp;
	}
	
	static function convertTimeToMysqlFormat($time){
		if(empty($time))
			return null;
		$strtotime = strtotime($time);
		$time = date('H:i:s',$strtotime);
		return $time;
	}
	
	static function convertTimeToFormat($time){
		if(empty($time))
			return null;
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$strtotime = strtotime($time);
		$time = date($appSettings->time_format,$strtotime);
		return $time;
	}
	
	static function convertToFormat($date){
		if(isset($date) && strlen($date)>6 && $date!="0000-00-00" && $date!="00-00-0000"){
			try{
				$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
				$date = substr($date,0,10);
				list($yy,$mm,$dd)=explode("-",$date);
				if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)){
					$date = date($appSettings->dateFormat, strtotime($date));
				}else{
					$date="";
				}
			}catch(Exception $e){
				$date="";
			}
		}
		return $date;
	}
	
	static function convertToMysqlFormat($date){
		if(isset($date) && strlen($date)>6){
			$date = date("Y-m-d", strtotime($date));
		}
		return $date;
	}
	
	static function getDateGeneralFormat($data){
		$dateS="";
		if(isset($data) && strlen($data)>6  && $data!="0000-00-00"){
			//$data =strtotime($data);
			//setlocale(LC_ALL, 'de_DE');
			//$dateS = strftime( '%e %B %Y', $data );
			$date = JFactory::getDate($data);
			$dateS = $date->format('j F Y');
			//$dateS = date( 'j F Y', $data );
		}
	
		return $dateS;
	}
	
	static function getDateGeneralShortFormat($data){
		$dateS="";
		if(isset($data) && strlen($data)>6  && $data!="0000-00-00"){
			//$data =strtotime($data);
			//$dateS = strftime( '%e %b %Y', $data );
			//$dateS = date( 'j M Y', $data );
			$date = JFactory::getDate($data);
			$dateS = $date->format('j M Y');
		}
	
		return $dateS;
	}
	
	static function getDateGeneralFormatWithTime($data){
		if(empty($data)){
			return null;
		}
		$data =strtotime($data);
		$dateS = date( 'j M Y  G:i:s', $data );
	
		return $dateS;
	}
	
	static function getShortDate($data){
		if(empty($data)){
			return null;
		}
		
		$date = JFactory::getDate($data);
		$dateS = $date->format('M j');
	
		return $dateS;
	}
	
	static function getTimeText($time){
		$result = date('g:iA', strtotime($time));
		
		return $result;
	}
	
	static function getRemainingTime($date){
		$now = new DateTime();
		$future_date = new DateTime($date);
		$timestamp = strtotime($date);
		$timestamp = strtotime('+1 day', $timestamp);
		if($timestamp  < time()){
			return "";
		}
		
		$interval = $future_date->diff($now);
		$result = JText::_("LNG_ENDS_IN");
		
		if($interval->format("%a")){
			$result .= " ".$interval->format("%a")." ".strtolower(JText::_("LNG_DAYS"));
		}
		
		if($interval->format("%h")){
			$result .= " ".$interval->format("%h")." ".strtolower(JText::_("LNG_HOURS"));
		}
		
		if($interval->format("%m")){
			$result .= " ".$interval->format("%m")." ".strtolower(JText::_("LNG_MINUTES"));
		}
		
		return $result;
	}
	
	static function loadModules($position){
		require_once(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php');
		$document = JFactory::getDocument();
		$renderer = $document->loadRenderer('module');
		$db =JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__modules WHERE position='$position' AND published=1 ORDER BY ordering");
		$modules = $db->loadObjectList();
		if( count( $modules ) > 0 )
		{
			foreach( $modules as $module )
			{
				//just to get rid of that stupid php warning
				$module->user = '';
				$params = array('style'=>'xhtml');
				echo $renderer->render($module, $params);
			}
		}
	}
	
	static function getItemIdS(){
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$menu = $app->getMenu();
		$itemid="";
		
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();
		if(isset($activeMenu)){
			$itemid= JFactory::getApplication()->getMenu()->getActive()->id;
		}
		
		$defaultMenu = $menu->getDefault($lang->getTag());
		if(!empty($defaultMenu) && $itemid == $defaultMenu->id){
			$itemid	= "";
		}
		$itemidS="";
		if(!empty($itemid)){
			$itemidS = '&Itemid='.$itemid;
		}
		
		return $itemidS;
	}
	
	/**
	 * Get the current menu alias
	 */
	static function getCurrentMenuAlias(){
		$menualias =  "";
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$currentMenu = null;
		if(!empty($appSettings->menu_item_id)){
			$currentMenu = JFactory::getApplication()->getMenu()->getItem($appSettings->menu_item_id);
		}
		
		if(empty($currentMenu)){
			$currentMenu = JFactory::getApplication()->getMenu()->getActive();
		}
		
		if(!empty($currentMenu))
			$menualias = $currentMenu->alias;
		
		return $menualias;
	}
	
	/**
	 * Prevent the links to contain administrator keyword
	 * 
	 * @param unknown_type $url
	 */
	static function processURL($url){
		if(strpos($url, "/administrator/")!==false){
			$url = str_replace("administrator/", "", $url);
		}
		
		return $url;
	}
	
	/**
	 * Creates the business listing link
	 * 
	 * @param $company
	 * @param $addIndex
	 */
	static function getCompanyLink($company, $addIndex=null){
		$itemidS = self::getItemIdS();
	
		$companyAlias = trim($company->alias);
		$companyAlias = stripslashes(strtolower($companyAlias));
		$companyAlias = str_replace(" ", "-", $companyAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
	
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		if(!$appSettings->enable_seo){
			$companyLink = $company->id;
			if(JFactory::getConfig()->get("sef")){
				$companyLink = $company->id;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&companyId='.$companyLink.$itemidS,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$companyLink = $company->id."-".htmlentities(urlencode($companyAlias));
			}else{
				$companyLink = htmlentities(urlencode($companyAlias));
			}
				
			if($appSettings->listing_url_type==2){
				$categoryPath = self::getBusinessCategoryPath($company);
				$path="";
					
				foreach($categoryPath as $cp){
					$path = $path. JApplication::stringURLSafe($cp->name)."/";
				}
				$companyLink=strtolower($path).$companyLink;
			}else if($appSettings->listing_url_type==3){
				$companyLink= strtolower($company->county)."/".strtolower($company->city)."/".$companyLink;
			}

			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			
			$url = $base.$companyLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".$companyLink;
			}
		}
		
		$url = self::processURL($url);
	
		return $url;
	}
	
	/**
	 * Create the business listing link only for type one (only name in the link)
	 * 
	 * @param $companyId
	 * @param $companyAlias
	 * @param $addIndex
	 * @return String $url
	 */
	static function getCompanyDefaultLink($companyId, $companyAlias, $addIndex=null){
		$itemidS = self::getItemIdS(); 
		
		$companyAlias = trim($companyAlias);
		$companyAlias = stripslashes(strtolower($companyAlias));
		$companyAlias = str_replace(" ", "-", $companyAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
	
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		if(!$appSettings->enable_seo){
			$companyLink = $companyId;
			if(JFactory::getConfig()->get("sef")){
				$companyLink = $companyId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=companies&companyId='.$companyLink.$itemidS,false,-1);
		}else{
			if($appSettings->add_url_id == 1){ 
				$companyLink = $companyId."-".htmlentities(urlencode($companyAlias));
			}else{
				$companyLink = htmlentities(urlencode($companyAlias));
			}
			
			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			
			$url = $base.$companyLink;
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".$companyLink;
			}
		}
	
		$url = self::processURL($url);
		
		return $url;
	}
	
	/**
	 * Create the link for categories
	 * 
	 * @param unknown_type $categoryId
	 * @param unknown_type $categoryAlias
	 * @param unknown_type $addIndex
	 */
	static function getCategoryLink($categoryId, $categoryAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$categoryAlias = trim($categoryAlias);
		$categoryAlias = stripslashes(strtolower($categoryAlias));
		$categoryAlias = str_replace(" ", "-", $categoryAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$categoryLink = $categoryId;
		
		if(!$appSettings->enable_seo){
			$categoryLink = $categoryId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $categoryId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=search&categoryId='.$categoryLink.$itemidS,false,-1);
		}else{
			
			if($appSettings->add_url_id == 1){ 
				$categoryLink = $categoryId."-".htmlentities(urlencode($categoryAlias));
			}else{
				$categoryLink = htmlentities(urlencode($categoryAlias));
			}

			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->category_url_type==2){
				$url = $base.$categoryLink;
				if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
					$url = $base.$menuAlias."/".$categoryLink;
				}
			}else{
				$url = $base.CATEGORY_URL_NAMING."/".$categoryLink;
				if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
					$url = $base.$menuAlias."/".CATEGORY_URL_NAMING."/".$categoryLink;
				}
			}

		}
		
		$url = self::processURL($url);
		
		return $url;
	}
	
	/**
	 * Create the link for category offers
	 * 
	 * @param $categoryId
	 * @param $categoryAlias
	 * @param $addIndex
	 */
	static function getOfferCategoryLink($categoryId, $categoryAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$categoryAlias = trim($categoryAlias);
		$categoryAlias = stripslashes(strtolower($categoryAlias));
		$categoryAlias = str_replace(" ", "-", $categoryAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$offerCategoryLink = $categoryId;
		
		if(!$appSettings->enable_seo){
			$offerCategoryLink = $categoryId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $categoryId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=offers&offerCategoryId='.$offerCategoryLink.$itemidS,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$offerCategoryLink = $categoryId."-".htmlentities(urlencode($categoryAlias));
			}else{
				$offerCategoryLink =htmlentities(urlencode($categoryAlias));
			}
			
			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			
			$url = $base.OFFER_CATEGORY_URL_NAMING."/".$offerCategoryLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".OFFER_CATEGORY_URL_NAMING."/".$offerCategoryLink;
			}
		}
		
		$url = self::processURL($url);
		
		return $url;
	}
	
	/**
	 * Create the link for event categories
	 * 
	 * @param $categoryId
	 * @param $categoryAlias
	 * @param $addIndex
	 */
	static function getEventCategoryLink($categoryId, $categoryAlias, $addIndex=null){
		$app = JFactory::getApplication();
		$lang = JFactory::getLanguage();
		$menu = $app->getMenu();
		$itemid="";
		$activeMenu = JFactory::getApplication()->getMenu()->getActive();
		if(isset($activeMenu)){
			$itemid= JFactory::getApplication()->getMenu()->getActive()->id;
		}
		
		if($itemid == $menu->getDefault($lang->getTag())->id){
			$itemid	= "";
		}
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$categoryAlias = trim($categoryAlias);
		$categoryAlias = stripslashes(strtolower($categoryAlias));
		$categoryAlias = str_replace(" ", "-", $categoryAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		if(!$appSettings->enable_seo){
			$eventCategoryLink = $categoryId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $categoryId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=events&eventCategoryId='.$eventCategoryLink.'&Itemid='.$itemid,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$eventCategoryLink = $categoryId."-".htmlentities(urlencode($categoryAlias));
			}else{
				$eventCategoryLink = htmlentities(urlencode($categoryAlias));
			}

			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			
			$url = $base.EVENT_CATEGORY_URL_NAMING."/".$eventCategoryLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".EVENT_CATEGORY_URL_NAMING."/".$eventCategoryLink;
			}
		}
	
		$url = self::processURL($url);
		
		return $url;
	}
	
	/**
	 * Create the link for an offer
	 * 
	 * @param $offerId
	 * @param $offerAlias
	 * @param $addIndex
	 */	
	static function getOfferLink($offerId, $offerAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$offerAlias = trim($offerAlias);
		$offerAlias = stripslashes(strtolower($offerAlias));
		$offerAlias = str_replace(" ", "-", $offerAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		$offerLink = $offerId;
		
		if(!$appSettings->enable_seo){
			$offerLink = $offerId;
			if(JFactory::getConfig()->get("sef")){
				$offerLink = $offerId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=offer&offerId='.$offerLink.$itemidS,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$offerLink = $offerId."-".htmlentities(urlencode($offerAlias));
			}else{
				$offerLink = htmlentities(urlencode($offerAlias));
			}
			
			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			$url = $base.OFFER_URL_NAMING."/".$offerLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".OFFER_URL_NAMING."/".$offerLink;
			}
		}
		
		$url = self::processURL($url);
		
		return $url;
	}
	
	/**
	 * Create the link for an event
	 * 
	 * @param $eventId
	 * @param $eventAlias
	 * @param $addIndex
	 */
	static function getEventLink($eventId, $eventAlias, $addIndex=null){
		$itemidS = self::getItemIdS();
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$eventAlias = trim($eventAlias);
		$eventAlias = stripslashes(strtolower($eventAlias));
		$eventAlias = str_replace(" ", "-", $eventAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		if(!$appSettings->enable_seo){
			$eventLink = $eventId;
			if(JFactory::getConfig()->get("sef")){
				$categoryLink = $eventId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=event&eventId='.$eventLink.$itemidS,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$eventLink = $eventId."-".htmlentities(urlencode($eventAlias));
			}else{
				$eventLink = htmlentities(urlencode($eventAlias));
			}
			
			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			
			$url = $base.EVENT_URL_NAMING."/".$eventLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".EVENT_URL_NAMING."/".$eventLink;
			}
		}
		
		$url = self::processURL($url);
	
		return $url;
	}
	
	/**
	 * Create the link for a conference
	 * 
	 * @param $conferenceId
	 * @param $conferenceAlias
	 * @param $addIndex
	 */
	static function getConferenceLink($conferenceId, $conferenceAlias, $addIndex=null){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$itemid = JRequest::getInt('Itemid');
	
		$conferenceAlias = trim($conferenceAlias);
		$conferenceAlias = stripslashes(strtolower($conferenceAlias));
		$conferenceAlias = str_replace(" ", "-", $conferenceAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		if(!$appSettings->enable_seo){
			$conferenceLink = $conferenceId;
			if(JFactory::getConfig()->get("sef")){
				$conferenceLink = $conferenceId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=conference&conferenceId='.$conferenceId.'&Itemid='.$itemid,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$conferenceLink = $conferenceId."-".htmlentities(urlencode($conferenceAlias));
			}else{
				$conferenceLink = htmlentities(urlencode($conferenceAlias));
			}
			
			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			$url = $base.CONFERENCE_URL_NAMING."/".$conferenceLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".CONFERENCE_URL_NAMING."/".$conferenceLink;
			}
		}
	
		return $url;
	}
	
	/**
	 * Create the link for a conference session
	 * 
	 * @param $sessionId
	 * @param $sessionAlias
	 * @param  $addIndex
	 * @return string
	 */
	static function getConferenceSessionLink($sessionId, $sessionAlias, $addIndex=null){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$itemid = JRequest::getInt('Itemid');
	
		$sessionAlias = trim($sessionAlias);
		$sessionAlias = stripslashes(strtolower($sessionAlias));
		$sessionAlias = str_replace(" ", "-", $sessionAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		if(!$appSettings->enable_seo){
			$sessionLink = $sessionId;
			if(JFactory::getConfig()->get("sef")){
				$sessionLink = $sessionId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=conferencesession&cSessionId='.$sessionLink.'&Itemid='.$itemid,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$sessionLink = $sessionId."-".htmlentities(urlencode($sessionAlias));
			}else{
				$sessionLink = htmlentities(urlencode($sessionAlias));
			}
			
			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			
			$url = $base.CONFERENCE_SESSION_URL_NAMING."/".$sessionLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".CONFERENCE_SESSION_URL_NAMING."/".$sessionLink;
			}
		}
	
		return $url;
	}
	
	/**
	 * Create the link for a speaker
	 * 
	 * @param unknown_type $speakerId
	 * @param unknown_type $speakerAlias
	 * @param unknown_type $addIndex
	 */
	static function getSpeakerLink($speakerId, $speakerAlias, $addIndex=null){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$itemid = JRequest::getInt('Itemid');
	
		$speakerAlias = trim($speakerAlias);
		$speakerAlias = stripslashes(strtolower($speakerAlias));
		$speakerAlias = str_replace(" ", "-", $speakerAlias);
	
		$conf = JFactory::getConfig();
		$index ="";
		if(!JFactory::getConfig()->get("sef_rewrite")){
			$index ="index.php/";
		}
	
		if(!$appSettings->enable_seo){
			$speakerLink = $speakerId;
			if(JFactory::getConfig()->get("sef")){
				$speakerLink = $speakerId;
			}
			$url = JRoute::_('index.php?option=com_jbusinessdirectory&view=speaker&speakerId='.$speakerLink.'&Itemid='.$itemid,false,-1);
		}else{
			if($appSettings->add_url_id == 1){
				$speakerLink = $speakerId."-".htmlentities(urlencode($speakerAlias));
			}else{
				$speakerLink = htmlentities(urlencode($speakerAlias));
			}
			
			$base = JURI::base().$index;
			if($appSettings->add_url_language){
				$langTag = self::getCurrentLanguageCode();
				$base.= $langTag."/";
			}
			$url = $base.SPEAKER_URL_NAMING."/".$speakerLink;
			
			$menuAlias = self::getCurrentMenuAlias();
			if($appSettings->enable_menu_alias_url && !empty($menuAlias)){
				$url = $base.$menuAlias."/".SPEAKER_URL_NAMING."/".$speakerLink;
			}
		}
	
		return $url;
	}
	
	static function isJoomla3(){
		$version = new JVersion();
		$versionA =  explode(".", $version->getShortVersion());
		if($versionA[0] =="3"){
			return true;
		}
		return false;
	}
	
	
	static function truncate($text, $length, $suffix = '&hellip;', $isHTML = true){
		$i = 0;
		$tags = array();
		if($isHTML){
			preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
			foreach($m as $o){
				if($o[0][1] - $i >= $length)
					break;
				$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
				if($t[0] != '/')
					$tags[] = $t;
				elseif(end($tags) == substr($t, 1))
				array_pop($tags);
				$i += $o[1][1] - $o[0][1];
			}
		}
	
		$output = substr($text, 0, $length = min(strlen($text),  $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
	
		// Get everything until last space
		$one = substr($output, 0, strrpos($output, " "));
		// Get the rest
		$two = substr($output, strrpos($output, " "), (strlen($output) - strrpos($output, " ")));
		// Extract all tags from the last bit
		preg_match_all('/<(.*?)>/s', $two, $tags);
		// Add suffix if needed
		if (strlen($text) > $length) {
			$one .= $suffix;
		}
		// Re-attach tags
		$output = $one . implode($tags[0]);
	
		return $output;
	}
	
	static function getAlias($title, $alias){
		if (empty($alias) || trim($alias) == ''){
			$alias = $title;
		}
		
		$alias = JApplication::stringURLSafe($alias);
		
		if (trim(str_replace('-', '', $alias)) == ''){
			$alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}
		
		return $alias;
	}
	
	static function getAddressText($company){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$address="";
		if(isset($company->publish_only_city) && $company->publish_only_city){
			$address=$company->city.' '.$company->county;
			return $address;
		}					
		
		if(!empty($company->street_number)){
			if($appSettings->address_format==1 || $appSettings->address_format==3){
				$address = $company->street_number.' '.$company->address;
			}else{
				$address = $company->address.' '.$company->street_number;
			}
		}else {
			$address = $company->address;
		}
		
		if($appSettings->address_format==1 || $appSettings->address_format==3 || $appSettings->address_format==4){
			if(!empty($company->city)) {
				if(!empty($address))
					$address .= ",";
				$address .= " " . $company->city;
			}
		}

		if(isset($company->postalCode)){
			if(!empty($company->postalCode)){
				if($appSettings->address_format== 3 || $appSettings->address_format== 4){
					if(!empty($company->county)){
						if(!empty($address))
							$address .= ",";
						$address .= " " . $company->county;
					}
				}
				if($appSettings->address_format== 2 && !empty($address))
					$address .= ",";
				$address .= " " . $company->postalCode;
			}
			if(!empty($company->city)){
				if($appSettings->address_format == 2){
					$address .= " " . $company->city;
				}
			}
		}

		if($appSettings->address_format==1 || $appSettings->address_format==2){
			if(!empty($company->county)) {
				if(!empty($address))
					$address .= ",";
				$address .= " ". $company->county;
			}
		}
		
		if($appSettings->add_country_address == 1){
			if(!empty($address))
			if(!empty($company->country_name)){
				$address .= ", ".$company->country_name;
			}

			else if(!empty($company->countryName)){
				$address .= ", ".$company->countryName;

			}
			else if(!empty($company->countryId)){
				JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
				$countryTable = JTable::getInstance("Country", "JTable");
				$countryName = $countryTable->getCountry($company->countryId)->country_name;
				$address .= ", ".$countryName;

			}
		}
		
		return $address;
	}
	
	static function getLocationAddressText($street_number,$address, $city, $county, $postalCode ){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$address="";
	
		if(isset($street_number)){
			if($appSettings->address_format==0){
				$address = $street_number.' '.$address;
			}else{
				$address = $address.' '.$street_number;
			}
		}
	
		if($appSettings->address_format==1){
			if(!empty($postalCode) || $city){
				$address .=",";
			}
				
			if(!empty($postalCode)){
				$address .= " ".$postalCode;
			}
				
			if(!empty($city)){
				$address .= " ".$city;
			}
		}
	
		if($appSettings->address_format==0){
			if(!empty($postalCode) || $city){
				$address .=",";
			}
				
			if(!empty($city)){
				$address .= " ".$city;
			}
				
			if(!empty($postalCode)){
				$address .= " ".$postalCode;
			}
				
		}
	
	
		if(!empty($county)){
			$address .= ", ".$county;
		}
	
	
		return $address;
	}
	
	static function getLocationText($item){
		$location="";
	
		if(!empty($item->address)){
			$location .= $item->address;
		}
		
		if(!empty($item->city)){
			if(!empty($location))
				$location .= ", ".$item->city;
			else
				$location = $item->city;
		}
		if(!empty($item->county)){
			if(!empty($location))
				$location .= ", ".$item->county;
			else
				$location = $item->county;
		}
		
		if(empty($item->address) && empty($item->city) && !empty($item->location)){
			$location = $item->location; 
		}
	
		return $location;
	}
	

	static function getBusinessCategoryPath($company){
		
		$categories = self::getCategories();
		
		$category = null;
		$categoryId = 0;
		if(!empty($company->mainSubcategory)){
			$categoryId = $company->mainSubcategory;
		}else{
			if(!empty($company->categories)){
				$listingCategories = explode('#',$company->categories);
				$category = explode("|", $listingCategories[0]);
				$categoryId = $category[0];
			}
		}
		
		if(empty($categoryId)){
			return array();
		}
	
		$category = self::getCategory($categories, $categoryId);
		$path=array();
		if(!empty($category)){
			$path[]=$category;
		
			while($category->parent_id != 1){
				if(!$category->parent_id)
					break;
				$category=self::getCategory($categories, $category->parent_id);
				$path[] = $category;
			}
				
			$path = array_reverse($path);
		}
		
		return $path;
	}
	
	static function getCategories(){
		$instance = JBusinessUtil::getInstance();
		
		if(!isset($instance->categories)){
			JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
			$categoryTable =JTable::getInstance("Category","JBusinessTable");
			$categories = $categoryTable->getAllCategories();
			$instance->categories = $categories;
		}
		return $instance->categories;
	}
	
	static function getCategory($categories, $categoryId){
		if(empty($categories) || empty($categoryId))
			return null;
		
		foreach($categories as $category){
			if($category->id == $categoryId){
				return $category;
			}
		}
		return null;
	}
	
	static function getLanguages(){
		$languages = JLanguage::getKnownLanguages();
		$result = array();
		foreach ($languages as $key=>$language){
			$result[]=$key;
		}
		sort($result);
		return $result;
	} 
	
	static function getCurrentLanguageCode(){
		$lang = JFactory::getLanguage()->getTag();
		$lang = explode("-",$lang);
		return $lang[0];
	}
	
	static function getCategoriesOptions($published, $type = null, $catId = null, $showRoot = false){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)		
		->select('a.id AS value, a.name AS text, a.level, a.published')
		->from('#__jbusinessdirectory_categories AS a')
		->join('LEFT', $db->quoteName('#__jbusinessdirectory_categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		
		if(!empty($catId)){
			$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_categories') . ' AS p ON p.id = ' . (int) $catId)
			->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');
		}
		
		if (($published)) {
			$query->where('a.published = 1');
		}

		if (($type)) {
			$query->where('(a.type IN (0,' . (int) $type.'))');
		}
		
		if(!$showRoot){
			$query->where('a.id >1');
		}
		
		$query->group('a.id, a.name, a.level, a.lft, a.rgt, a.parent_id, a.published')
		->order('a.lft ASC');
		
		$db->setQuery($query);
		$options = $db->loadObjectList();
		$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
		
		// Pad the option text with spaces using depth level as a multiplier.
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			if ($options[$i]->published == 1)
			{
				if(!empty($categoryTranslations[$options[$i]->value]))
					$options[$i]->text = $categoryTranslations[$options[$i]->value]->name;
				if($showRoot){
					$options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
				}else{
					$options[$i]->text = str_repeat('- ', $options[$i]->level-1) . $options[$i]->text;
				}
			}
			else
			{
				$options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
			}
		}
		
		return $options;
	}

	static function getCompaniesOptions() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)		
		->select('id AS value, name AS text')
		->from('#__jbusinessdirectory_companies')
		->group('id')
		->order('name ASC');

		$db->setQuery($query);
		$options = $db->loadObjectList();
		
		return $options;
	}

	static function getSpeakersOptions() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)		
		->select('id AS value, name AS text')
		->from('#__jbusinessdirectory_conference_speakers')
		->group('id')
		->order('title ASC');

		$db->setQuery($query);
		$options = $db->loadObjectList();
		
		return $options;
	}

	static function getSessionsOptions() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)		
		->select('id AS value, name AS text')
		->from('#__jbusinessdirectory_conference_sessions')
		->group('id')
		->order('name ASC');

		$db->setQuery($query);
		$options = $db->loadObjectList();
		
		return $options;
	}
	
	static function getConferenceOptions() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
		->select('id AS value, name AS text')
		->from('#__jbusinessdirectory_conferences')
		->group('id')
		->order('name ASC');
		
		$db->setQuery($query);
		$options = $db->loadObjectList();
		
		return $options;
	}
	
	/**
	 * Get review question types
	 */
	static function getReviewQuestiosnTypes(){
		$types = array();
		$type = new stdClass();
		$type->value = 0;
		$type->text = JTEXT::_("LNG_TEXT");
		$types[] = $type;
		$type = new stdClass();
		$type->value = 1;
		$type->text = JTEXT::_("LNG_YES_NO_QUESTION");
		$types[] = $type;
		$type = new stdClass();
		$type->value = 2;
		$type->text = JTEXT::_("LNG_RATING");
		$types[] = $type;
	
		return $types;
	}
	
	static function getPriceFormat($amount, $currencyId = null) {
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$dec_point=".";
		$thousands_sep = ",";
		
		if($appSettings->amount_separator==2) {
			$dec_point=",";
			$thousands_sep = ".";
		}
		
		$currencyString = $appSettings->currency_name;
		if($appSettings->currency_display==2) {
			$currencyString = $appSettings->currency_symbol;
		}
		
		$amountString = number_format ($amount , 2 , $dec_point,  $thousands_sep);

		if(!empty($currencyId)) {
			JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
			$currencyTable = JTable::getInstance("Currency", "JTable");
			$currency = $currencyTable->getCurrencyById($currencyId);
			$currencyString = $currency->currency_name;
			if($appSettings->currency_display==2)
				$currencyString = $currency->currency_symbol;
		}
		
		if($appSettings->currency_location==1) {
			$result = $currencyString." ".$amountString;
		} else {
			$result = $amountString." ".$currencyString;
		}

		return $result;
	}
	
	static function convertPriceToMysql($number){
		$number = str_replace('.', '', $number); // remove fullstop
		$number = str_replace(' ', '', $number); // remove spaces
		$number = str_replace(',', '.', $number); // change comma to fullstop
		
		return $number;
	}
	
	public static function loadAdminLanguage(){
		$language = JFactory::getLanguage();
		$language_tag 	= $language->getTag();
		//$language_tag = "tr-tr";
		$x = $language->load(
				'com_jbusinessdirectory' , dirname(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jbusinessdirectory'.DS.'language') ,
				$language_tag,true );
	}
	
	public static function loadSiteLanguage(){
		$language = JFactory::getLanguage();
		$language_tag 	= $language->getTag();
		
		$x = $language->load(
		'com_jbusinessdirectory' , dirname(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jbusinessdirectory'.DS.'language') ,
		$language_tag,true );
					
		$language_tag = str_replace("-","_",$language->getTag());
		setlocale(LC_TIME , $language_tag.'.UTF-8');
	}
	
	/**
	 * Set the menu item id based on current menu item id and menu id from general settings
	 * 
	 */
	public static function setMenuItemId(){
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$session = JFactory::getSession();
		//setting menu item Id
		$lang = JFactory::getLanguage();
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$activeMenu = $app->getMenu()->getActive();
		
		$url = $_SERVER['REQUEST_URI'];
		$urlParts = parse_url($url);
		$menuId="";
		if (( !empty($activeMenu) && $menu->getActive() != $menu->getDefault($lang->getTag()))
				|| ($urlParts["path"]=='/' && empty($urlParts["query"]))) {
			$menuId = $activeMenu->id;
			$session->set('menuId', $menuId);
		}
		
		if($appSettings->enable_seo) {
			$menuId = $session->get('menuId');
		}
		
		if(!empty($appSettings->menu_item_id) && ($menuId == $menu->getDefault($lang->getTag())->id || empty($menuId))) {
			$menuId = $appSettings->menu_item_id;
		}
		
		if(!empty($menuId)) {
			JFactory::getApplication()->getMenu()->setActive($menuId);
			JRequest::setVar('Itemid',$menuId);
		}
	}
	
	/**
	 * Remove a directory
	 * 
	 * @param unknown_type $dir
	 */
	public static function removeDirectory($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}
	
	/**
	 * Convert a day to a String
	 * @param unknown_type $day
	 * @param unknown_type $abbr
	 */
	public static function dayToString ($day, $abbr = false)
	{
		$date = new JDate();
		return addslashes($date->dayToString($day, $abbr));
	}
	
	public static function monthToString ($month, $abbr = false)
	{
		$date = new JDate();
		return addslashes($date->monthToString($month, $abbr));
	}
	
	static function getNumberOfDays($startData, $endDate){
	
		$nrDays = floor((strtotime($endDate) - strtotime($startData)) / (60 * 60 * 24));
	
		return $nrDays;
	}
	
	/**
	 * Get the day of month from provided date
	 * @param unknown_type $date
	 */
	public static function getDayOfMonth($date){
		if(empty($date))
			return "";
		
		return date("j", strtotime($date));
	}

	/**
	 * Get month as string from provided date
	 * @param unknown_type $date
	 */
	public static function getMonth($date){
		if(empty($date))
			return "";
		$date = JFactory::getDate($date);
		return $date->format('M');
	}
	
	/**
	 * Get year from provided date
	 * @param unknown_type $date
	 * @return string
	 */
	public static function getYear($date){
		if(empty($date))
			return "";
		
		$date = JFactory::getDate($date);
		return $date->format('Y');
	}
	
	/**
	 * Include validation required files
	 */
	static public function includeValidation(){
		JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/validationEngine.jquery.css');
		$tag = JBusinessUtil::getCurrentLanguageCode();
		
		if(!file_exists(JPATH_COMPONENT_SITE.'/assets/js/validation/jquery.validationEngine-'.$tag.'.js'))
			$tag ="en";
		
		JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/validation/jquery.validationEngine-'.$tag.'.js');
		JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/validation/jquery.validationEngine.js');
	}

	/**
	 * Calculate the elapsed time from a timestamp
	 * 
	 * @param unknown_type $datetime
	 * @param unknown_type $full
	 * @return string
	 */
	public static function convertTimestampToAgo($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	/**
	 * Remove files/images that are not linked anymore
	 * @param array $usedFiles
	 * @param unknown_type $rootFolder
	 * @param unknown_type $filesFolder
	 */
	public static function removeUnusedFiles(array $usedFiles, $rootFolder, $filesFolder) {
		
		$directoryPath = JBusinessUtil::makePathFile($rootFolder.$filesFolder);
		// $usedFiles -> array of the filename of the files
		// $filesFolder -> example: 'items/id/'
		// $rootFolder -> example: 'pictures'
		$usedFiles[]=JBusinessUtil::makePathFile($filesFolder)."index.html";
		
		foreach($usedFiles as &$file){
			$file = JBusinessUtil::makePathFile($file);
		}
		
		$allFiles = array();
		foreach (scandir($directoryPath, 1) as $singleFile) {
			array_push($allFiles, JBusinessUtil::makePathFile($filesFolder.$singleFile));
		}

		$unusedFiles = array_diff($allFiles, $usedFiles);
		
		foreach ($unusedFiles as $unusedFile) {
			if(is_file($rootFolder.$unusedFile)) {
				unlink($rootFolder.$unusedFile);
			}
		}
	}
	
	
	public static function moveFile($picture_path,$companyId, $oldId){
		
		$path_new = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .COMPANY_PICTURES_PATH.($companyId)."/");
		
		//prepare photos
		$path_old = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .COMPANY_PICTURES_PATH.($oldId)."/");
		if(!empty($picture_path)){
			$parts = explode("/",$picture_path);
			$oldId = $parts[2];
			$path_old = JBusinessUtil::makePathFile(JPATH_ROOT."/".PICTURES_PATH .COMPANY_PICTURES_PATH.($oldId)."/");
		}
			
		$file_tmp = JBusinessUtil::makePathFile( $path_old.basename($picture_path) );
		//dump($file_tmp);
		if( !is_file($file_tmp) )
			return;
		//dump("is file");
		if( !is_dir($path_new) )
		{
			if( !@mkdir($path_new) )
			{
				throw( new Exception($this->_db->getErrorMsg()) );
			}
		}
		
		//dbg(($path_old.basename($picture_path).",".$path_new.basename($picture_path)));
		// exit;
		if( $path_old.basename($picture_path) != $path_new.basename($picture_path)){
			if($oldId==0){
				if(@rename($path_old.basename($picture_path),$path_new.basename($picture_path)) )
				{
		
					$picture_path	 = COMPANY_PICTURES_PATH.($companyId).'/'.basename($picture_path);
					//@unlink($path_old.basename($pic->room_picture_path));
				}
				else
				{
					throw( new Exception($this->_db->getErrorMsg()) );
				}
			}else{
				if(@copy($path_old.basename($picture_path),$path_new.basename($picture_path)) )
				{
		
					$picture_path	 = COMPANY_PICTURES_PATH.($companyId).'/'.basename($picture_path);
					//@unlink($path_old.basename($pic->room_picture_path));
				}
				else
				{
					throw( new Exception($this->_db->getErrorMsg()) );
				}
			}
		}
		
		return $picture_path;
	}
	

	/**
	 * Get the type and thumbnail of the video based on the video url (Youtube and Vimeo supported).
	 * 
	 * @param $url
	 * @return array()
	 */
	public static function getVideoDetails($url) {
		$data = array();

		$iframe = strpos($url,'iframe');
		if (!empty($iframe)) { //if the $url is an iframe 
			preg_match('/src="([^"]+)"/', $url, $match);
			if(isset($match[1])){
				$url = $match[1];
			}
		}

		// If it's a youtube video
		if (strpos($url, 'youtu') > 0) {
			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
			
			$id = $matches[1]; // We need the video ID to find the thumbnail
			$thumbnail = 'https://img.youtube.com/vi/'.$id.'/0.jpg';
			
			$data = array(
				'url' => 'https://www.youtube.com/watch?v='.$id,
				'type' => 'youtube',
				'thumbnail' => $thumbnail
			);
		}
		// If it's a vimeo video
		elseif (strpos($url, 'vimeo') > 0) {
			preg_match("/https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/", $url, $matches);
			$id = $matches[3];
			$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$id.".php"));
			$thumbnail = $hash[0]['thumbnail_large'];

			$data = array(
				'url' => 'https://vimeo.com/'.$id,
				'type' => 'vimeo',
				'thumbnail' => $thumbnail
			);
		} 
		// If it's not supported
		else {
			$data = array(
				'url' => 'https://www.youtube.com',
				'type' => 'unsupported',
				'thumbnail' => 'placehold.it/400x300?text=UNSUPPORTED+FORMAT'
			);
		}

		return $data;
	}
	
	/**
	 * Retrieve current version from manifest file
	 * @return  string versino number
	 */
	public static function getCurrentVersion(){
		$module = JComponentHelper::getComponent('com_jbusinessdirectory');
		$extension = JTable::getInstance('extension');
		$extension->load($module->id);
		$data = json_decode($extension->manifest_cache, true);
		return $data['version'];
	}
}
?>