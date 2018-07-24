<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/imagesloaded.pkgd.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/jquery.isotope.min.js');
JHTML::_('script',  'components/com_jbusinessdirectory/assets/js/isotope.init.js');
class JBusinessDirectoryViewSearch extends JViewLegacy
{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function display($tpl = null)
	{
		$session = JFactory::getSession();
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$this->companies = $this->get('Items');
		$this->viewType = JRequest::getVar("view-type",LIST_VIEW);
		
		$categoryId= $this->get('CategoryId');
		$this->filterActive = JRequest::getVar("filter_active");
		if(!empty($categoryId)  && $this->appSettings->search_type != 1){
			$this->categoryId=$categoryId;
			$this->category = $this->get('Category');
		}	
		
		$this->selectedCategories =  $this->get("SelectedCategories");
		$this->selectedParams = $this->get('SelectedParams');
		
		
		
		$this->categories = implode(";", $this->get("SelectedCategories"));
		if(!empty($this->categories)){
			$this->categories.=";";
		}	
		
		$this->country = $this->get('Country');
				
		$searchkeyword = JRequest::getVar('searchkeyword');
		if(isset($searchkeyword)){
			$this->searchkeyword=  $searchkeyword;
		}
		
		$this->location = $this->get("Location");
		
		$this->radius= $session->get('radius');
		$this->customAtrributes = $session->get('customAtrributes');
		$this->customAtrributesValues = $this->get("CustomAttributeValues");
		
		if($this->appSettings->enable_search_filter){
			$this->searchFilter = $this->get('SearchFilter');
		}
		$this->pagination = $this->get('Pagination');
		
		$this->orderBy = JRequest::getVar("orderBy", $this->appSettings->order_search_listings);
		
		$this->categorySearch = JRequest::getVar('categorySearch',null);
		$this->citySearch = JRequest::getVar('citySearch',null);
		$this->regionSearch = JRequest::getVar('regionSearch',null);
		$this->zipCode = JRequest::getVar('zipcode');
		$this->typeSearch = JRequest::getVar('typeSearch',null);
		$this->type = $this->get("CompanyType");
		$this->countrySearch = JRequest::getVar('countrySearch',null);
		$this->maincategories = $this->get("MainCategories");
		$this->subcategories = $this->get("SubCategories");
		$this->sortByOptions = $this->get('SortByConfiguration');
		
		$session = JFactory::getSession();
		$this->location = $session->get('location');
		
		parent::display($tpl);
	}
}
?>
