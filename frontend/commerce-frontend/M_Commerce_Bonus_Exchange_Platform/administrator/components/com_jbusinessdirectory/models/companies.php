<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';
JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');

/**
 * List Model.
 *
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 */
class JBusinessDirectoryModelCompanies extends JModelList{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'bc.id',
				'name', 'bc.name',
				'username', 'u.username',
				'websiteCount', 'bc.websiteCount',
				'email', 'bc.email',
				'address', 'bc.address',
				'type', 'ct.name',
				'viewCount', 'bc.viewCount',
				'contactCount', 'bc.contactCount',
				'state', 'bc.state','bc.modified','bc.featured',
				'approved', 'bc.approved','p.name','active'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Company', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();

		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		
		$app = JFactory::getApplication();
		$value = $app->input->get('limit', $app->getCfg('list_limit', 0), 'uint');
		$this->setState('list.limit', $value);
		// Create a new query object.
		$db = $this->getDbo();
		
		$query = "	SELECT bc.*,ct.name as typeName,cnt.country_name as country, clm.id as claimId, inv.state = 1 as paid, u.username as username, COUNT(DISTINCT ev.id) as eventCount, COUNT(DISTINCT of.id) as offerCount, COUNT(DISTINCT re.id) as reviewCount, 
   					 (now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY))) as active, (inv.start_date + INTERVAL p.days DAY) as expirationDate, p.name as packageName,
   					  GROUP_CONCAT( DISTINCT cg.id,'|',cg.name,'|',cg.alias ORDER BY cg.name separator '#') as categories
					FROM `#__jbusinessdirectory_companies` AS bc
					left join `#__jbusinessdirectory_company_category` cc on bc.id=cc.companyId 
					left join `#__jbusinessdirectory_categories` cg on cg.id=cc.categoryId and cg.published=1
					LEFT JOIN `#__jbusinessdirectory_company_types` AS ct ON bc.typeId=ct.id
					LEFT JOIN `#__jbusinessdirectory_countries` AS cnt ON bc.countryId=cnt.id
					LEFT JOIN `#__jbusinessdirectory_company_claim` AS clm ON bc.id=clm.companyId
					LEFT JOIN `#__jbusinessdirectory_packages` p on bc.package_id=p.id
					LEFT JOIN `#__jbusinessdirectory_company_events` ev on ev.company_id=bc.id
					LEFT JOIN `#__jbusinessdirectory_company_offers` of on of.companyId=bc.id 
					LEFT JOIN `#__jbusinessdirectory_company_reviews` re on re.companyId=bc.id
					LEFT JOIN `#__users` u on bc.userId=u.id
					left join (
						SELECT t1.* FROM `#__jbusinessdirectory_orders` t1
						JOIN (SELECT company_id, MAX(id) as id FROM `#__jbusinessdirectory_orders` where start_date <= DATE(now())  GROUP BY company_id ) t2
							 ON t1.company_id = t2.company_id AND t1.id = t2.id
						where t1.start_date <= DATE(now())
					)inv on inv.package_id = p.id and inv.company_id = bc.id";

		$where = " where 1 ";
		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'r:') === 0) {
			 $where.=' and bc.registrationCode = '.(int) substr($search, 2);
			}
			else {
				$where.=" and (bc.name LIKE '%".trim($db->escape($search))."%' or
							bc.city LIKE '%".trim($db->escape($search))."%' )";
			}
		}
		
		$typeId = $this->getState('filter.type_id');
		if (is_numeric($typeId)) {
			$where.=' and bc.typeId ='.(int) $typeId;
		}
		
		$statusId = $this->getState('filter.status_id');
		if($statusId == COMPANY_STATUS_CLAIMED_APPROVED){
			$statusId = COMPANY_STATUS_APPROVED;
			$where.=" and clm.id is not null";
		}

		if (is_numeric($statusId)) {
			$where.=" and bc.approved =".(int) $statusId;
		}
			
		
		$stateId = $this->getState('filter.state_id');
		if (is_numeric($stateId)) {
			$where.=' and bc.state ='.(int) $stateId;
		}
		
		$groupBy = " group by bc.id";

		// Add the list ordering clause.
		$orderBy = " order by ". $db->escape($this->getState('list.ordering', 'bc.id')).' '.$db->escape($this->getState('list.direction', 'ASC'));
		
		$query = $query.$where;
		$query = $query.$groupBy;
		$query = $query.$orderBy;
		
		//echo $query;
		
		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$typeId = $app->getUserStateFromRequest($this->context.'.filter.type_id', 'filter_type_id');
		$this->setState('filter.type_id', $typeId);
		
		$statusId = $app->getUserStateFromRequest($this->context.'.filter.status_id', 'filter_status_id');
		$this->setState('filter.status_id', $statusId);

		$stateId = $app->getUserStateFromRequest($this->context.'.filter.state_id', 'filter_state_id');
		$this->setState('filter.state_id', $stateId);	
		
		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);

		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);	
				
		// List state information.
		parent::populateState('bc.id', 'desc');
	}
	
	function uploadFile($fileName, &$data, $dest){
	
		//Retrieve file details from uploaded file, sent from upload form
		$file = JRequest::getVar($fileName, null, 'files', 'array');
	
		if($file['name']=='')
			return true;
	
		//Import filesystem libraries. Perhaps not necessary, but does not hurt
		jimport('joomla.filesystem.file');
			
		//Clean up filename to get rid of strange characters like spaces etc
		$fileNameSrc = JFile::makeSafe($file['name']);
		$data[$fileName] =  $fileNameSrc;
		
		$src = $file['tmp_name'];
		$dest = $dest."/".$fileNameSrc;

		$result =  JFile::upload($src, $dest);
		
		if($result)
			return $dest;
		
		return null;
	}
	
	function getCompanyTypes(){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->getCompanyTypes();
	}
		
	function getStates(){
		$states = array();
		$state = new stdClass();
		$state->value = 0;
		$state->text = JTEXT::_("LNG_INACTIVE");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 1;
		$state->text = JTEXT::_("LNG_ACTIVE");
		$states[] = $state;
	
		return $states;
	}
	
	function getStatuses(){
		$statuses = array();
		$status = new stdClass();
		$status->value = COMPANY_STATUS_CLAIMED;
		$status->text = JTEXT::_("LNG_NEEDS_CLAIM_APROVAL");
		$statuses[] = $status;
		$status = new stdClass();
		$status->value = COMPANY_STATUS_CREATED;
		$status->text = JTEXT::_("LNG_NEEDS_CREATION_APPROVAL");
		$statuses[] = $status;
		$status = new stdClass();
		$status->value = COMPANY_STATUS_DISAPPROVED;
		$status->text = JTEXT::_("LNG_DISAPPROVED");
		$statuses[] = $status;
		$status = new stdClass();
		$status->value = COMPANY_STATUS_APPROVED;
		$status->text = JTEXT::_("LNG_APPROVED");
		$statuses[] = $status;
		$status = new stdClass();
		$status->value = COMPANY_STATUS_CLAIMED_APPROVED;
		$status->text = JTEXT::_("LNG_CLAIM_APPROVED");
		$statuses[] = $status;
	
		return $statuses;
	}
	
	
	public function exportCompaniesCSVtoFile($path){
		$csv_output = $this->getCompaniesCSV();
		$result =  file_put_contents($path,$csv_output);
		return $result;
	}
	
	public function exportCompaniesCSV(){
		
		$csv_output = $this->getCompaniesCSV();
		
		$fileName = "jbusinessdirectory_business_listing";
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header( "Content-disposition: filename=".$fileName.".csv");
		print $csv_output;
	}
	
	public function  getCompaniesCSV(){
		$delimiter = JRequest::getVar("delimiter",",");
		$category = JRequest::getVar("category","");

		$companyTable = $this->getTable();
		
		$categoriesIds="";

		if(!empty($category)){
			$categoriesIds = array();
			$categoryService = new JBusinessDirectorCategoryLib();
			$categoriesIds = $categoryService->getCategoryLeafs($category, CATEGORY_TYPE_BUSINESS);
			
			if(!empty($category)){
				if(isset($categoriesIds) && count($categoriesIds) > 0 ){
					$categoriesIds[] = $category;
				}else{
					$categoriesIds = array($category);
				}
			}
			$categoriesIds = implode(",", $categoriesIds);
		}

		$companies =  $companyTable->getCompaniesForExport($categoriesIds);
		
		
		$attributesTable = JTable::getInstance("Attribute","JTable");
		$companyAttributesTable = JTable::getInstance("CompanyAttributes","JTable");
		
		$attributes = $attributesTable->getAttributes();
		$csv_output = "name".$delimiter."alias".$delimiter."categories".$delimiter."main_subcategory".$delimiter."commercial_name".$delimiter."registration_code".$delimiter."tax_code".$delimiter."type".$delimiter."slogan".$delimiter."description".$delimiter."short_description".$delimiter."street_number".$delimiter."address".$delimiter."city".$delimiter."region".$delimiter."country".$delimiter."website".$delimiter."keywords".$delimiter."phone".$delimiter."mobile".$delimiter."email".$delimiter."fax".$delimiter."latitude".$delimiter."longitude".$delimiter."user".$delimiter."average_rating".$delimiter."views".$delimiter."website_count".$delimiter."contact_count".$delimiter."package".$delimiter."facebook".$delimiter."twitter".$delimiter."googlep".$delimiter."skype".$delimiter."linkedin".$delimiter."youtube".$delimiter."instagram".$delimiter."pinterest".$delimiter."business_hours".$delimiter."custom_tab_name".$delimiter."custom_tab_content".$delimiter."publish_only_city".$delimiter."postal_code".$delimiter."state".$delimiter."approved".$delimiter."contact_name".$delimiter."contact_email".$delimiter."contact_phone".$delimiter."contact_fax".$delimiter."logo_location".$delimiter."business_cover".$delimiter."pictures".$delimiter."meta_title".$delimiter."meta_description";
		if(!empty($attributes)){
			foreach($attributes as $attribute){
				$csv_output = $csv_output.$delimiter.$attribute->name;
			}
		}
		$csv_output = $csv_output."\n";
		$categoryTable = JTable::getInstance("Category","JBusinessTable");
		$categoryService = new JBusinessDirectorCategoryLib();
		$categories = $categoryTable->getAllCategories();
		$categories = $categoryService->processCategories($categories);
		
		foreach($companies as $company){
			$contactTable = $this->getTable('CompanyContact',"Table");
			$company->contact = $contactTable->getCompanyContact($company->id);

			if(!empty($company->mainSubcategory))
				$subcategory = $categoryTable->getCategoryById($company->mainSubcategory);
			$company->subcategory = "";
			if(isset($subcategory))
				$company->subcategory= $subcategory->name;
			$company->short_description = str_replace(array("\r\n", "\r", "\n"), "<br />", $company->short_description);
			$company->description = str_replace(array("\r\n", "\r", "\n"), "<br />", $company->description);
			$company->description = str_replace('"', '""', $company->description);
			$listing_csv = "\"$company->name\"".$delimiter."\"$company->alias\"".$delimiter."\"".$company->categoryNames."\"".$delimiter."\"$company->subcategory\"".$delimiter."\"$company->comercialName\"".$delimiter."\"$company->registrationCode\"".$delimiter."\"$company->taxCode\"".$delimiter."\"$company->type\"".$delimiter."\"$company->slogan\"".$delimiter."\"$company->description\"".$delimiter."\"$company->short_description\"".$delimiter."\"$company->street_number\"".$delimiter."\"$company->address\"".$delimiter."\"$company->city\"".$delimiter."\"$company->county\"".$delimiter."\"$company->countryName\"".$delimiter."\"$company->website\"".$delimiter."\"$company->keywords\"".$delimiter."\"$company->phone\"".$delimiter."\"$company->mobile\"".$delimiter."\"$company->email\"".$delimiter."\"$company->fax\"".$delimiter."\"$company->latitude\"".$delimiter."\"$company->longitude\"".$delimiter."\"$company->userId\"".$delimiter."\"$company->averageRating\"".$delimiter."\"$company->viewCount\"".$delimiter."\"$company->websiteCount\"".$delimiter."\"$company->contactCount\"".$delimiter."\"$company->packageName\"".$delimiter."\"$company->facebook\"".$delimiter."\"$company->twitter\"".$delimiter."\"$company->googlep\"".$delimiter."\"$company->skype\"".$delimiter."\"$company->linkedin\"".$delimiter."\"$company->youtube\"".$delimiter."\"$company->instagram\"".$delimiter."\"$company->pinterest\"".$delimiter."\"$company->business_hours\"".$delimiter."\"$company->custom_tab_name\"".$delimiter."\"$company->custom_tab_content\"".$delimiter."\"$company->publish_only_city\"".$delimiter."\"$company->postalCode\"".$delimiter."\"$company->state\"".$delimiter."\"$company->approved\"".$delimiter."\"".$company->contact->contact_name."\"".$delimiter."\"".$company->contact->contact_email."\"".$delimiter."\"".$company->contact->contact_phone."\"".$delimiter."\"".$company->contact->contact_fax."\"".$delimiter."\"".$company->logoLocation."\"".$delimiter."\"$company->business_cover_image\"".$delimiter."\"".$company->pictures."\"".$delimiter."\"".$company->meta_title."\"".$delimiter."\"".$company->meta_description."\"";
			$listing_csv = str_replace(array("\r\n", "\r", "\n"), "<br/>", $listing_csv);
			$csv_output .=$listing_csv;
			$companyAttributes = $companyAttributesTable->getCompanyAttributes($company->id);
			
			foreach($attributes as $attribute){
				foreach($companyAttributes as $companyAttribute){
					if($attribute->code == $companyAttribute->code){
						$csv_output .= $delimiter."\"".AttributeService::getAttributeValues($companyAttribute)."\"";
					}
				}
			}
			
			$csv_output .= "\n";
		}

		return $csv_output;
	}
	
	public function getCompaniesWithTranslationCSV(){
		$delimiter = JRequest::getVar("delimiter",",");
		$category = JRequest::getVar("category","");
	
		$companyTable = $this->getTable();
	
		$categoriesIds="";
	
		if(!empty($category)){
			$categoriesIds = array();
			$categoryService = new JBusinessDirectorCategoryLib();
			$categoriesIds = $categoryService->getCategoryLeafs($category, CATEGORY_TYPE_BUSINESS);
				
			if(!empty($category)){
				if(isset($categoriesIds) && count($categoriesIds) > 0 ){
					$categoriesIds[] = $category;
				}else{
					$categoriesIds = array($category);
				}
			}
			$categoriesIds = implode(",", $categoriesIds);
		}
	
		$languages = JBusinessUtil::getLanguages();
		
		$companies =  $companyTable->getCompaniesForExport($categoriesIds);
	
		$attributesTable = JTable::getInstance("Attribute","JTable");
		$companyAttributesTable = JTable::getInstance("CompanyAttributes","JTable");
	
		$attributes = $attributesTable->getAttributes();
	
		$csv_output = "id".$delimiter."name".$delimiter."commercial name".$delimiter."website".$delimiter."categories_ids".$delimiter;
		foreach($languages as $language){
			$csv_output.="categories $language".$delimiter;
		}
		
		$csv_output.="registration code".$delimiter."type".$delimiter."slogan".$delimiter;
		foreach($languages as $language){
			$csv_output.="description $language".$delimiter;
		}
		$csv_output.= "short description".$delimiter."full address".$delimiter."street number".$delimiter."address".$delimiter."city".$delimiter."region".$delimiter."country".$delimiter."website".$delimiter."keywords".$delimiter."phone".$delimiter."mobile".$delimiter."email".$delimiter."fax".$delimiter."latitude".$delimiter."longitude".$delimiter."user".$delimiter."averageRating".$delimiter."views".$delimiter."featured".$delimiter."facebook".$delimiter."twitter".$delimiter."googlep".$delimiter."postal code".$delimiter."state".$delimiter."approved".$delimiter."contact_name".$delimiter."contact_email".$delimiter."contact_phone".$delimiter."contact_fax".$delimiter."logo".$delimiter."pictures";
		if(!empty($attributes)){
			foreach($attributes as $attribute){
				$csv_output = $csv_output.$delimiter.$attribute->name;
			}
		}
		$csv_output = $csv_output."\n";
		$categoryTable = JTable::getInstance("Category","JBusinessTable");
		$categoryService = new JBusinessDirectorCategoryLib();
		$categories = $categoryTable->getAllCategories();
		$categories = $categoryService->processCategories($categories);
	
		$translationsc = JBusinessDirectoryTranslations::getCategoriesTranslations();
		
		foreach($companies as $company){
			$contactTable = $this->getTable('CompanyContact',"Table");
			$company->contact = $contactTable->getCompanyContact($company->id);

			$translations = JBusinessDirectoryTranslations::getAllTranslations(BUSSINESS_DESCRIPTION_TRANSLATION,$company->id);
			if(!empty($company->mainSubcategory))
				$subcategory = $categoryTable->getCategoryById($company->mainSubcategory);
			
			$categoryIds = array();
			if(!empty($company->categoryIds)){
				$categoryIds = explode(",",$company->categoryIds);
			}
			
			$company->subcategory = "";
			if(isset($subcategory))
				$company->subcategory= $subcategory->name;
			$company->short_description = str_replace(array("\r\n", "\r", "\n"), "<br />", $company->short_description);
			$company->description = str_replace(array("\r\n", "\r", "\n"), "<br />", $company->description);
			$company->description = str_replace('"', '""', $company->description);
			$csv_output .= $company->id.$delimiter."\"$company->name\"".$delimiter."\"$company->comercialName\"".$delimiter."\"$company->website\"".$delimiter."\"$company->categoryIds\"".$delimiter;
			foreach($languages as $lng){
				$ct = array();
				foreach($categoryIds as $cId){
					$translation = JBusinessDirectoryTranslations::getObjectTranslation(CATEGORY_TRANSLATION, $cId, $lng);
					$ct[] = empty($translation)?"":$translation->name;
				}
				if(empty($ct)){
					$csv_output.= "".$delimiter;
				}else{
					$csv_output.= "\"".implode(",", $ct)."\"".$delimiter;
				}
			}
			
			$csv_output .= "$company->registrationCode".$delimiter."\"$company->type\"".$delimiter."\"$company->slogan\"".$delimiter;
			foreach($languages as $language){
				if(!empty($translations[$language])){
					$translations[$language] = str_replace(array("\r\n", "\r", "\n"), "<br />", $translations[$language]);
					$csv_output.= "\"$translations[$language]\"".$delimiter;
				}else{ 
					$csv_output.= $delimiter;
				}
			}
			$csv_output .="\"$company->short_description\"".$delimiter;
			$csv_output .="\"$company->street_number, $company->address, $company->city, $company->county, $company->countryName\"".$delimiter;
			$csv_output .="$company->street_number".$delimiter."\"$company->address\"".$delimiter."\"$company->city\"".$delimiter."\"$company->county\"".$delimiter."\"$company->countryName\"".$delimiter."\"$company->website\"".$delimiter."\"$company->keywords\"".$delimiter."\"$company->phone\"".$delimiter."\"$company->mobile\"".$delimiter."$company->email".$delimiter."$company->fax".$delimiter."$company->latitude".$delimiter."$company->longitude".$delimiter."$company->userId".$delimiter."$company->averageRating".$delimiter."$company->viewCount".$delimiter."$company->featured".$delimiter."$company->facebook".$delimiter."$company->twitter".$delimiter."$company->googlep".$delimiter."\"$company->postalCode\"".$delimiter."$company->state".$delimiter."$company->approved".$delimiter."\"".$company->contact->contact_name."\"".$delimiter."\"".$company->contact->contact_email."\"".$delimiter."\"".$company->contact->contact_phone."\"".$delimiter."\"".$company->contact->contact_fax."\"".$delimiter;
			
			$csv_output .="\"".(JURI::root().PICTURES_PATH.$company->logoLocation)."\"".$delimiter;
			$pictures = explode(",",$company->pictures);
			foreach($pictures as &$picture){
				$picture = JURI::root().PICTURES_PATH.$picture;
			}
			$pictures = implode(",",$pictures);
			$csv_output .="\"".$pictures."\"";
				
			$companyAttributes = $companyAttributesTable->getCompanyAttributes($company->id);
				
			foreach($attributes as $attribute){
				foreach($companyAttributes as $companyAttribute){
					if($attribute->code == $companyAttribute->code){
						$csv_output .= $delimiter."\"".AttributeService::getAttributeValues($companyAttribute)."\"";
					}
				}
			}
				
			$csv_output .= "\n";
		}

		return $csv_output;
	}
}
