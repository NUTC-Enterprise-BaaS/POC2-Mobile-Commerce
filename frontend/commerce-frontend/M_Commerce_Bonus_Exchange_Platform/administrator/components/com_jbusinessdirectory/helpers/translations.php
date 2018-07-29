<?php 
/**
 * Translation utility class
 * @author George
 *
 */

defined('_JEXEC') or die( 'Restricted access' );

class JBusinessDirectoryTranslations{
	
	private function __construct(){
	}
	
	public static function getInstance(){
		static $instance;
		if ($instance === null) {
			$instance = new JBusinessDirectoryTranslations();
		}
		return $instance;
	}
	
	public static function getCategoriesTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();
	
		if(!isset($instance->categoriesTranslations)){
			$translations = self::getCategoriesTranslationsObjects();
			$instance->categoriesTranslations = array();
			
			foreach($translations as $translation){
				$instance->categoriesTranslations[$translation->object_id]= $translation;
			}
		}
		return $instance->categoriesTranslations;
	}
	
	static function getCategoriesTranslationsObjects(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
					from  #__jbusinessdirectory_categories c
					inner join  #__jbusinessdirectory_language_translations t on c.id=t.object_id where t.type=".CATEGORY_TRANSLATION." and language_tag='$language'";
	
		$db->setQuery($query);
		if (!$db->query()){
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}
	
	public static function getBusinessTypesTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();
	
		if(!isset($instance->businessTypesTranslations)){
			$translations = self::getBusinessTypesTranslationsObject();
			$instance->businessTypesTranslations = array();
			foreach($translations as $translation){
				$instance->businessTypesTranslations[$translation->object_id]= $translation;
			}
		}
		return $instance->businessTypesTranslations;
	}
	
	static function getBusinessTypesTranslationsObject(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
							from  #__jbusinessdirectory_company_types bt
							inner join  #__jbusinessdirectory_language_translations t on bt.id=t.object_id where type=".TYPE_TRANSLATION." and language_tag='$language'";
		
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}
	
	public static function getAttributesTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();
	
		if(!isset($instance->attributeTranslations)){
			$translations = self::getAttributesTranslationsObject();
			$instance->attributeTranslations = array();
			foreach($translations as $translation){
				$instance->attributeTranslations[$translation->object_id]= $translation;
			}
		}
		return $instance->attributeTranslations;
	}
	
	static function getAttributesTranslationsObject(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
					from  #__jbusinessdirectory_attributes a
					inner join  #__jbusinessdirectory_language_translations t on a.id=t.object_id where t.type=".ATTRIBUTE_TRANSLATION." and language_tag='$language'";
	
		//dump($query);
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}

	public static function getEventTypesTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();

		if(!isset($instance->eventtypeTranslations)){
			$translations = self::getEventTypesTranslationsObject();
			$instance->eventtypeTranslations = array();
			foreach($translations as $translation){
				$instance->eventtypeTranslations[$translation->object_id]= $translation;
			}
		}
		return $instance->eventtypeTranslations;
	}

	static function getEventTypesTranslationsObject(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
					from  #__jbusinessdirectory_company_event_types et
					inner join  #__jbusinessdirectory_language_translations t on et.id=t.object_id where t.type=".EVENT_TYPE_TRANSLATION." and language_tag='$language'";

		//dump($query);
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}
	
	public static function getPackageTranslations(){
		$instance = JBusinessDirectoryTranslations::getInstance();
	
		if(!isset($instance->packageTranslations)){
			$translations = self::getPackagesTranslationsObject();
			$instance->packageTranslations = array();
	
			foreach($translations as $translation){
				$instance->packageTranslations[$translation->object_id]= $translation;
			}
		}
		return $instance->packageTranslations;
	}
	
	
	static function getPackagesTranslationsObject(){
		$db		= JFactory::getDBO();
		$language = JFactory::getLanguage()->getTag();
		$query	= "	SELECT t.*
		from  #__jbusinessdirectory_packages p
		inner join  #__jbusinessdirectory_language_translations t on p.id=t.object_id where t.type=".PACKAGE_TRANSLATION." and language_tag='$language'";
	
		//dump($query);
		$db->setQuery( $query );
		if (!$db->query() )
		{
			JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
			return true;
		}
		return  $db->loadObjectList();
	}
	
	static function getObjectTranslation($translationType,$objectId,$language)
	{
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "select * from  #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId and language_tag='$language'";
			$db->setQuery($query);
			$translation = $db->loadObject();
			return $translation;
		}
		else return null;
	}
	
	static function getAllTranslations($translationType, $objectId){
		$translationArray=array();
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "select * from #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId order by language_tag";
			$db->setQuery($query);
			$translations = $db->loadObjectList();
			
			if(count($translations)>0){
				foreach($translations as $translation){
					$translationArray[$translation->language_tag."_name"]=$translation->name;
					$translationArray[$translation->language_tag]=$translation->content;
					$translationArray[$translation->language_tag."_short"]=$translation->content_short;
				}
			}
		}
		return $translationArray;
	}
	
	static function deleteTranslationsForObject($translationType,$objectId){
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "delete from #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId";
			$db->setQuery($query);
			$db->query();
		}
	}

	static function saveTranslation($translationType, $objectId, $language, $name, $shortContent, $content){
		$db =JFactory::getDBO();
		$name = $db->escape($name);
		$shortContent = $db->escape($shortContent);
		$content = $db->escape($content);
		
		$query = "insert into #__jbusinessdirectory_language_translations(type,object_id,language_tag,name, content_short,content) values($translationType,$objectId,'$language','$name','$shortContent','$content')";
		$db->setQuery($query);
		
		return $db->query();
		
	}

	static function saveTranslations($translationType,$objectId, $identifier){
		self::deleteTranslationsForObject($translationType,$objectId);
		$languages = JBusinessUtil::getLanguages();

		foreach($languages as $lng ){

			$description = 	JRequest::getVar( $identifier.$lng, '', 'post', 'string', JREQUEST_ALLOWHTML);
			$shortDescription = JRequest::getVar( "short_".$identifier.$lng, '', 'post', 'string', JREQUEST_ALLOWHTML);
			$name = JRequest::getVar( "name_".$lng, '', 'post', 'string', JREQUEST_ALLOWHTML);
			if(empty($name)){
				$name = JRequest::getVar( "subject_".$lng, '', 'post', 'string', JREQUEST_ALLOWHTML);
			}

			if(!empty($description) || !empty($shortDescription) || !empty($name)){
				self::saveTranslation($translationType, $objectId, $lng, $name, $shortDescription, $description);
			}
		}
	}
	
	static function getAllTranslationObjects($translationType,$objectId)
	{
		if(!empty($objectId)){
			$db =JFactory::getDBO();
			$query = "select * from #__jbusinessdirectory_language_translations where type=$translationType and object_id=$objectId order by language_tag";
			$db->setQuery($query);
			$translations = $db->loadObjectList();
		}
		return $translations;
	}
	
	static function updateEntityTranslation(&$object, $translationType){
		
		$language = JFactory::getLanguage()->getTag();
		$translation = self::getObjectTranslation($translationType, $object->id, $language);
		if(!empty($translation)){
			if(!empty($translation->content_short))
				$object->short_description = $translation->content_short;
			if(!empty($translation->content))
				$object->description = $translation->content;
			if(!empty($translation->name)){
				switch($translationType){
					case 5:
						$object->subject = $translation->name;
						break;
					default:
						$object->name = $translation->name;
				}
			}
		}
		
		//slogan - for businesses
		$translation = self::getObjectTranslation(BUSSINESS_SLOGAN_TRANSLATION, $object->id, $language);
		if(!empty($translation)){
			$object->slogan = $translation->content;
		}
		
		if(!empty($object->categories)){
			$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
			$categories = explode("#",$object->categories);
			$resCategories = array();
			foreach($categories as &$category){
				$categoryItem =  explode("|", $category);
				if(!empty($categoryTranslations[$categoryItem[0]])){
					$categoryItem[1] = $categoryTranslations[$categoryItem[0]]->name;
				}
				$category = implode("|",$categoryItem);
				$resCategories[] = $category;
			}
		
			$object->categories = implode("#",$resCategories);
		}
		
		if(!empty($object->typeId)){
			$typeTranslations = JBusinessDirectoryTranslations::getBusinessTypesTranslations();
			if(!empty($typeTranslations[$object->typeId])){
				$object->typeName = $typeTranslations[$object->typeId]->name;
			}
		}
		
		if(!empty($object->eventTypeId)){
			$typeTranslations = JBusinessDirectoryTranslations::getEventTypesTranslations();
			if(!empty($typeTranslations[$object->eventTypeId])){
				$object->eventType = $typeTranslations[$object->eventTypeId]->name;
			}
		}
	}
	
	static function updateBusinessListingsTranslation(&$companies){
		$ids = array();
		
		if(empty($companies)){
			return;
		}
		
		foreach($companies as $company)
			$ids[] = $company->id;
		$objectIds = implode(',', $ids);
		
		
		$translationType = BUSSINESS_DESCRIPTION_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
		
		$db =JFactory::getDBO();
		$query = "select object_id, name, content_short from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();

		$companyTranslations = array();
		foreach($translations as $translation){
			$companyTranslations[$translation->object_id]= $translation;
		}

		foreach($companies as &$company){
			if(!empty($companyTranslations[$company->id])){
				if(!empty($companyTranslations[$company->id]->name)){
					$company->name = $companyTranslations[$company->id]->name;
				}
				if(!empty($companyTranslations[$company->id]->content_short)){
					$company->short_description = $companyTranslations[$company->id]->content_short;
				}
			}
			
			$typeTranslations = JBusinessDirectoryTranslations::getBusinessTypesTranslations();
			if(isset($company->typeId) && !empty($typeTranslations[$company->typeId])){
				$company->typeName = $typeTranslations[$company->typeId]->name;
			}
			
			if(!empty($company->categories)){
				$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
				$categories = explode("#",$company->categories);
				$resCategories = array();
				foreach($categories as &$category){
					$categoryItem =  explode("|", $category);
					if(!empty($categoryTranslations[$categoryItem[0]])){
						$categoryItem[1] = $categoryTranslations[$categoryItem[0]]->name;
					}
					$category = implode("|",$categoryItem);
					$resCategories[] = $category;
				}
			
				$company->categories = implode("#",$resCategories);
				
			}
			
			//update main category translation
			if(!empty($company->mainCategoryId)){
				$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
				if(!empty($categoryTranslations[$company->mainCategoryId])){
					$company->mainCategory = $categoryTranslations[$company->mainCategoryId]->name;
				}
			}	

			//update package translation
			if(!empty($company->packageId)){
				$packageTranslations = JBusinessDirectoryTranslations::getPackageTranslations();
				if(!empty($packageTranslations[$company->packageId])){
					$company->packageName = $packageTranslations[$company->packageId]->name;
				}
			}
				
			
		}
	}
	
	static function updateCategoriesTranslation(&$categories){
		if(empty($categories)){
			return;
		}

		$translations = JBusinessDirectoryTranslations::getCategoriesTranslations();
		foreach($categories as &$categoryS){
			$category = $categoryS;
			if(is_array($category)){
				$category[0]->subcategories = $category["subCategories"];
				$category = $category[0];
				
			}
			
			if(!empty($category->id) && isset($translations[$category->id])&& !empty($translations[$category->id]->name)){
				$category->name = $translations[$category->id]->name;
			}
			if(!empty($category->subcategories)){
				
				foreach($category->subcategories as &$subcat){
					if(is_array($subcat)){
						$subcat= $subcat[0];
						
					}
					//dump($translations[$subcat->id]);
					if(!empty($translations[$subcat->id]) && !empty($translations[$subcat->id]->name)){
						$subcat->name = $translations[$subcat->id]->name;
					}
				}
				
			}
		}
	}
	
	static function updateAttributesTranslation(&$attributes){
		if(empty($attributes)){
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getAttributesTranslations();
		foreach($attributes as &$attribute){
			if(!empty($translations[$attribute->id])){
				$attribute->name = $translations[$attribute->id]->name;
			}
		}
	}

	static function updateEventTypesTranslation(&$events){
		if(empty($events)){
			return;
		}

		$translations = JBusinessDirectoryTranslations::getEventTypesTranslations();
		foreach($events as &$event){
			$id = isset($event->type)?$event->type:$event->id;
			if(!empty($translations[$id])){
				// Check if the object passed to the function is an event, or an event type,
				// and apply the translation accordingly
				if(!empty($event->eventType))
					$event->eventType = $translations[$id]->name;
				else
					$event->name = $translations[$id]->name;
			}
		}
	}
	
	static function updateTypesTranslation(&$types){
		if(empty($types)){
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getBusinessTypesTranslations();
		foreach($types as &$type){
			if(isset($type->id) && !empty($translations[$type->id])){
				$type->name = $translations[$type->id]->name;
			}
			if(isset($type->typeId) && !empty($translations[$type->typeId])){
				$type->typeName = $translations[$type->typeId]->name;
			}
		}
	}
	
	static function updateBusinessListingsSloganTranslation(&$companies){
		$ids = array();
		
		if(empty($companies)){
			return;
		}
		
		foreach($companies as $company)
			$ids[] = $company->id;
		$objectIds = implode(',', $ids);
	
	
		$translationType = BUSSINESS_SLOGAN_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, content from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		
		$short_description = array();
		foreach($translations as $translation){
			$short_description[$translation->object_id]= $translation->content;
		}
	
		foreach($companies as &$company){
			if(!empty($short_description[$company->id])){
				$company->slogan = $short_description[$company->id];
			}
		}
	
		//dump($companies);
	}
	
	
	static function updateOffersTranslation(&$offers){
	
		$ids = array();
		
		if(empty($offers)){
			return;
		}
		
		foreach($offers as $offer)
			$ids[] = $offer->id;
		$objectIds = implode(',', $ids);
	
	
		$translationType = OFFER_DESCRIPTION_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, name, content_short from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
	
		$short_description = array();
		$subject = array();
		foreach($translations as $translation){
			$short_description[$translation->object_id]= $translation->content_short;
			$subject[$translation->object_id]= $translation->name;
		}
	
		foreach($offers as &$offer){
			if(!empty($short_description[$offer->id])){
				$offer->short_description = $short_description[$offer->id];
			}
			if(!empty($subject[$offer->id])){
				$offer->subject = $subject[$offer->id];
			}
			
			if(!empty($offer->categories)){
				$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
				$categories = explode("#", $offer->categories);
				$resCategories = array();
				foreach($categories as &$category){
					$categoryItem =  explode("|", $category);
					if(!empty($categoryTranslations[$categoryItem[0]])){
						$categoryItem[1] = $categoryTranslations[$categoryItem[0]]->name;
					}
					$category = implode("|",$categoryItem);
					$resCategories[] = $category;
				}
			
				$offer->categories = implode("#",$resCategories);
			}
		}
	}
	
	static function updateEventsTranslation(&$events){
	
		$ids = array();
		
		if(empty($events)){
			return;
		}
		
		foreach($events as $event)
			$ids[] = $event->id;
		$objectIds = implode(',', $ids);
	
		$translationType = EVENT_DESCRIPTION_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, name, content from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		$description = array();
		$name = array();
		foreach($translations as $translation){
			$description[$translation->object_id]= $translation->content;
			$name[$translation->object_id]= $translation->name;
		}
	
		foreach($events as &$event){
			if(!empty($description[$event->id])){
				$event->description = $description[$event->id];
			}
			if(!empty($name[$event->id])){
				$event->name = $name[$event->id];
			}
		}
	
	}
	
	static function updatePackagesTranslation(&$packages){
		$ids = array();
		
		if(empty($packages)){
			return;
		}
		
		foreach($packages as $package)
			$ids[] = $package->id;
		$objectIds = implode(',', $ids);
	
	
		$translationType = PACKAGE_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, name, content from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		$description = array();
		$name = array();
		foreach($translations as $translation){
			$description[$translation->object_id]= $translation->content;
			$name[$translation->object_id] = $translation->name;
		}
	
		foreach($packages as &$package){
			if(!empty($description[$package->id])){
				$package->description = $description[$package->id];
			}
			if(!empty($name[$package->id])){
				$package->name = $name[$package->id];
			}
		}
	
	}
	
	static function updateConferenceTranslations(&$conferences){
		$ids = array();
	
		if(empty($conferences)){
			return;
		}
	
		foreach($conferences as $conference)
			$ids[] = $conference->id;
		$objectIds = implode(',', $ids);
	
		$translationType = CONFERENCE_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, content_short, name from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		
		$conferenceTranslations = array();
		foreach($translations as $translation){
			$conferenceTranslations[$translation->object_id]= $translation;
		}
		
		foreach($conferences as &$conference){
			if(!empty($conferenceTranslations[$conference->id])){
				if(!empty($conferenceTranslations[$conference->id]->name))
					$conference->name = $conferenceTranslations[$conference->id]->name;
				if(!empty($conferenceTranslations[$conference->id]->content_short))
					$conference->short_description = $conferenceTranslations[$conference->id]->content_short;
			}
		}
	}
	
	static function updateConferenceSessionsTranslation($conferenceSessions){
		$ids = array();
		if(empty($conferenceSessions)){
			return;
		}
		
		if(!is_array($conferenceSessions)){
			$conferenceSessions = array($conferenceSessions);
		}
	
		foreach($conferenceSessions as $cSession){
			if(is_array($cSession)){
				$ids[] =$cSession[0];
			}else{
				$ids[] = $cSession->id;
			}
		}
			
		$objectIds = implode(',', $ids);
	
		$translationType = CONFERENCE_SESSION_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, name, content_short, content from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		
		foreach($translations as $translation){
			$description[$translation->object_id]= $translation;
		}
		
		foreach($conferenceSessions as &$conferenceSession){
			if(is_array($conferenceSession)){
				if(!empty($description[$conferenceSession[0]]->name)){
					$conferenceSession[1] = $description[$conferenceSession[0]]->name;
				}
			}else{
				if(!empty($description[$conferenceSession->id]->content_short)){
					$conferenceSession->short_description = $description[$conferenceSession->id]->content_short;
				}
				if(!empty($description[$conferenceSession->id]->content)){
					$conferenceSession->description = $description[$conferenceSession->id]->content;
				}
				
				if(!empty($description[$conferenceSession->id]->name)){
					$conferenceSession->name = $description[$conferenceSession->id]->name;
				}
				
				if(!empty($conferenceSession->categories)){
					$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
					
					foreach($conferenceSession->categories as &$category){
						if(!empty($categoryTranslations[$category[0]])){
							$category[1] = $categoryTranslations[$category[0]]->name;
						}
					}
				}
				
				if(!empty($conferenceSession->types)){
					$typeTranslations = JBusinessDirectoryTranslations::getBusinessTypesTranslations();
					if(!empty($typeTranslations[$object->typeId])){
						$object->typeName = $typeTranslations[$object->typeId]->name;
					}
				}
				
				if(!empty($conferenceSession->typeId)){
					$typeTranslations = JBusinessDirectoryTranslations::getConferenceSessionTypesTranslationsObject();
					if(!empty($typeTranslations[$conferenceSession->typeId])){
						$conferenceSession->typeName = $typeTranslations[$conferenceSession->typeId]->name;
					}
				}	
			}
		}
	}
	
	static function updateConferenceSpeakersTranslations(&$speakers){
		$ids = array();
		if(empty($speakers)){
			return;
		}

		foreach($speakers as $speaker)
			$ids[] = $speaker->id;
		$objectIds = implode(',', $ids);
	
		$translationType = CONFERENCE_SPEAKER_TRANSLATION;
		$language = JFactory::getLanguage()->getTag();
	
		$db =JFactory::getDBO();
		$query = "select object_id, content_short, content  from  #__jbusinessdirectory_language_translations where type=$translationType and object_id in ($objectIds) and language_tag='$language'";
		$db->setQuery($query);
		$translations = $db->loadObjectList();
		
		$speakerTranslations = array();
		foreach($translations as $translation){
			$speakerTranslations[$translation->object_id]= $translation;
		}

		foreach($speakers as &$speaker){
			if(!empty($speakerTranslations[$speaker->id])){
				if(!empty($speakerTranslations[$speaker->id]->content))
					$speaker->biography = $speakerTranslations[$speaker->id]->content;
				if(!empty($speakerTranslations[$speaker->id]->content_short))
					$speaker->short_biography = $speakerTranslations[$speaker->id]->content_short;
			}
			
			if(!empty($speaker->typeId)){
				$typeTranslations = JBusinessDirectoryTranslations::getConferenceSpeakerTypeTranslationsObject();
				if(!empty($typeTranslations[$speaker->typeId])){
					$speaker->typeName = $typeTranslations[$speaker->typeId]->name;
				}
			}
		}
	}
	
	static function updateConferenceTypesTranslation(&$types){
		if(empty($types)){
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getConferenceSessionTypesTranslationsObject();
	
		foreach($types as &$type){
			if(!empty($translations[$type->id])){
				$type->name = $translations[$type->id]->name;
			}
		}
	}
	
	static function updateConferenceSpeakerTypesTranslation(&$types){
		if(empty($types)){
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getConferenceSpeakerTypeTranslationsObject();
	
		foreach($types as &$type){
			if(!empty($translations[$type->id])){
				$type->name = $translations[$type->id]->name;
			}
		}
	}
	
	static function updateConferenceLevelTranslation(&$levels){
		if(empty($levels)){
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getConferenceSessionLevelTranslationsObject();
	
		foreach($levels as &$level){
			if(!empty($translations[$level->id])){
				$level->name = $translations[$level->id]->name;
			}
		}
	}
	
	static function getConferenceSessionTypesTranslationsObject(){
		$instance = JBusinessDirectoryTranslations::getInstance();
		if(!isset($instance->conferenceTypeTranslations)){
			$db		= JFactory::getDBO();
			$language = JFactory::getLanguage()->getTag();
				$query	= "	SELECT t.*
				from  #__jbusinessdirectory_conference_session_types bt
				inner join  #__jbusinessdirectory_language_translations t on bt.id=t.object_id where type=".CONFERENCE_TYPE_TRANSLATION." and language_tag='$language'";
			
			$db->setQuery( $query );
			if (!$db->query()){
				JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
				return true;
			}
			$translations =  $db->loadObjectList();
			
			$result = array();
			foreach($translations as $translation){
					$result[$translation->object_id]= $translation;
			}
			
			$instance->conferenceTypeTranslations = $result;
		}
		
		return $instance->conferenceTypeTranslations;
		
	}
	
	static function getConferenceSessionLevelTranslationsObject(){
		$instance = JBusinessDirectoryTranslations::getInstance();
		if(!isset($instance->sessionLevelTranslations)){
			$db		= JFactory::getDBO();
			$language = JFactory::getLanguage()->getTag();
			$query	= "	SELECT t.*
						from  #__jbusinessdirectory_conference_session_levels bt
						inner join  #__jbusinessdirectory_language_translations t on bt.id=t.object_id where type=".CONFERENCE_LEVEL_TRANSLATION." and language_tag='$language'";
	
			$db->setQuery( $query );
			if (!$db->query())	{
				JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
				return true;
			}
			$translations =  $db->loadObjectList();
				
			$result = array();
			foreach($translations as $translation){
				$result[$translation->object_id]= $translation;
			}
				
			$instance->sessionLevelTranslations = $result;
		}
	
		return $instance->sessionLevelTranslations;
	
	}
	
	static function getConferenceSpeakerTypeTranslationsObject(){
		$instance = JBusinessDirectoryTranslations::getInstance();
		if(!isset($instance->speakerTypesTranslations)){
			$db		= JFactory::getDBO();
			$language = JFactory::getLanguage()->getTag();
			$query	= "	SELECT t.*
						from  #__jbusinessdirectory_conference_speaker_types bt
						inner join  #__jbusinessdirectory_language_translations t on bt.id=t.object_id where type=".CONFERENCE_SPEAKER_TYPE_TRANSLATION." and language_tag='$language'";
	
			$db->setQuery( $query );
			if (!$db->query())	{
				JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
				return true;
			}
			$translations =  $db->loadObjectList();
	
			$result = array();
			foreach($translations as $translation){
				$result[$translation->object_id]= $translation;
			}

			$instance->speakerTypesTranslations = $result;
		}

		return $instance->speakerTypesTranslations;
	}

	static function updateReviewCriteriaTranslation(&$reviewCriterias){
		if(empty($reviewCriterias)) {
			return;
		}
	
		$translations = JBusinessDirectoryTranslations::getReviewCriteriaTranslationsObject();
		foreach($reviewCriterias as &$reviewCriteria){
			if(!empty($translations[$reviewCriteria->id])){
				$reviewCriteria->name = $translations[$reviewCriteria->id]->name;
			}
		}
	}
	
	static function getReviewCriteriaTranslationsObject() {
		$instance = JBusinessDirectoryTranslations::getInstance();
		if(!isset($instance->reviewCriteriaTranslations)){
			$db		= JFactory::getDBO();
			$language = JFactory::getLanguage()->getTag();
			$query	= "	SELECT t.*
						from  #__jbusinessdirectory_company_reviews_criteria bt
						inner join  #__jbusinessdirectory_language_translations t on bt.id=t.object_id where type=".REVIEW_CRITERIA_TRANSLATION." and language_tag='$language'";
	
			$db->setQuery( $query );
			if (!$db->query())	{
				JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
				return true;
			}
			$translations =  $db->loadObjectList();
	
			$result = array();
			foreach($translations as $translation){
				$result[$translation->object_id]= $translation;
			}
	
			$instance->reviewCriteriaTranslations = $result;
		}
	
		return $instance->reviewCriteriaTranslations;
	}


	static function updateReviewQuestionTranslation(&$reviewQuestions){
		if(empty($reviewQuestions)) {
			return;
		}

		$translations = JBusinessDirectoryTranslations::getReviewQuestionTranslationsObject();
		foreach($reviewQuestions as &$reviewQuestion){
			if(!empty($translations[$reviewQuestion->id])){
				$reviewQuestion->name = $translations[$reviewQuestion->id]->name;
			}
		}
	}

	static function getReviewQuestionTranslationsObject() {
		$instance = JBusinessDirectoryTranslations::getInstance();
		if(!isset($instance->reviewQuestionTranslations)){
			$db		= JFactory::getDBO();
			$language = JFactory::getLanguage()->getTag();
			$query	= "	SELECT t.*
						from  #__jbusinessdirectory_company_reviews_question bt
						inner join  #__jbusinessdirectory_language_translations t on bt.id=t.object_id where t.type=".REVIEW_QUESTION_TRANSLATION." and language_tag='$language'";

			$db->setQuery( $query );
			if (!$db->query())	{
				JError::raiseWarning( 500, JText::_("LNG_UNKNOWN_ERROR") );
				return true;
			}
			$translations =  $db->loadObjectList();

			$result = array();
			foreach($translations as $translation){
				$result[$translation->object_id]= $translation;
			}

			$instance->reviewQuestionTranslations = $result;
		}

		return $instance->reviewQuestionTranslations;
	}

}

?>