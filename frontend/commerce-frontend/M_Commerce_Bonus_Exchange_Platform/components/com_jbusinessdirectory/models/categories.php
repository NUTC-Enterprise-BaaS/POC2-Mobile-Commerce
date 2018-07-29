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
jimport('joomla.application.component.modellist');

JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');

class JBusinessDirectoryModelCategories extends JModelList
{ 
	function __construct()
	{
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->categoryType = JRequest::getVar('categoryType', CATEGORY_TYPE_BUSINESS);
		parent::__construct();
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Category', $prefix = 'JBusinessTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	 * 
	 * @return object with data
	 */
	function getCategories(){
			
		$categoryService = new JBusinessDirectorCategoryLib();
		$categoryTable = $this->getTable();
			
		$categories = $categoryTable->getAllCategories($this->categoryType);
		$categories = $categoryService->processCategories($categories);
		//$categories = $categories[1]["subCategories"];
		$startingLevel = 0;
		$path=array();
		$level =0;
		$categories["maxLevel"] = $categoryService->setCategoryLevel($categories,$startingLevel,$level,$path);

		
		$details = array();

		$details["enablePackages"] = $this->appSettings->enable_packages;
		$details["showPendingApproval"] =  $this->appSettings->show_pending_approval==1;

		$listingsCount = $categoryTable->getCountPerCategory($details, $this->categoryType);

		foreach($categories as $category){
			if(isset($category[0]->id)){
				$category[0]->nr_listings = isset($listingsCount[$category[0]->id]->nr_listings)?$listingsCount[$category[0]->id]->nr_listings:'0';

				switch($this->categoryType){
					case CATEGORY_TYPE_OFFER:
						$category[0]->link = JBusinessUtil::getOfferCategoryLink($category[0]->id,  $category[0]->alias);
						break;
					case CATEGORY_TYPE_EVENT:
						$category[0]->link = JBusinessUtil::getEventCategoryLink($category[0]->id,  $category[0]->alias);
						break;
					default:
						$category[0]->link = JBusinessUtil::getCategoryLink($category[0]->id,  $category[0]->alias);
				}
			}
		}
		
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateCategoriesTranslation($categories);
		}
		
		return $categories;
	}
	
	function getCategoriesList($keyword, $type=CATEGORY_TYPE_BUSINESS){
		$table = $this->getTable();
		$suggestionList = $table->getCategoriesList($keyword, $type);
		$suggestionList = json_encode($suggestionList);
		return $suggestionList;
	}

	function getCategoryType(){

		return $this->categoryType;
	}
}
?>