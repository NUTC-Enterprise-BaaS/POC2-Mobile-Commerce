<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die;

jimport('joomla.database.tablenested');
/**
 * Category table
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JBusinessTableCategory extends JTableNested{
	
	/**`
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){
	
		parent::__construct('#__jbusinessdirectory_categories', 'id', $db);
	}
	
	function setKey($k)
	{
		$this->_tbl_key = $k;
	}
	
	/**
	 * Method to delete a node and, optionally, its child nodes from the table.
	 *
	 */
	public function delete($pk = null, $children = false)
	{
		return parent::delete($pk, $children);
	}
	
	public function getCategoryById($categoryId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_categories c
				 where c.id=$categoryId";
		$db->setQuery($query);
		return $db->loadObject();
	}
	
	public function getAllCategories($type = CATEGORY_TYPE_BUSINESS){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_categories where published=1 and type = $type order by parent_id, name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getCategoriesForExport(){
		$db =JFactory::getDBO();
		$query = "select  c.name, GROUP_CONCAT(cc.name ORDER BY cc.name) as subcategories, c.type
				from #__jbusinessdirectory_categories c
				left join #__jbusinessdirectory_categories cc on c.id = cc.parent_id
				group by c.id
				order by c.lft, cc.name";
	
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getMainCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_categories where published=1 and parent_id=1 order by name';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getSubCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_categories where published=1 and parent_id!=1 order by name';
		$db->setQuery($query);
		$result = $db->loadObjectList();
	
		return $result;
	}
	
	function getCategoriesList($keyword, $type){
		$db =JFactory::getDBO();
		$query = "select distinct name as label, name as value from #__jbusinessdirectory_categories where name like '%$keyword%' and published=1 and type=$type";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getTotalCategories(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_categories";
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->nr;
	}
	
	function checkAlias($id, $alias){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_categories  WHERE alias='$alias' and id<>$id";
		$db->setQuery($query);
		$result = $db->loadObject();
		dump($result);
		return $result->nr;
	}
	
	function increaseClickCount($id){
		$db =JFactory::getDBO();
		$query = "UPDATE #__jbusinessdirectory_categories SET clickCount = clickCount+1 WHERE id = ".$id ;
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}
		return true;
	}

	/**
	 * Gets the total number of objects (business, offer, events) for each category they belong to
	 * @param $details (array containing filters)
	 * @param int $type (category type, either business, offer or event category)
	 * @return array|bool
	 */
	function getCountPerCategory($details, $type=CATEGORY_TYPE_BUSINESS){
		$db =JFactory::getDBO();

		$enablePackage = isset($details["enablePackages"])?$details["enablePackages"]:null;
		$showPendingApproval = isset($details["showPendingApproval"])?$details["showPendingApproval"]:null;
		$whereCond = "";
		$select="";
		$feature="";
		$approved="";
		switch($type){
			case CATEGORY_TYPE_OFFER:
				$select = "select count(distinct co.id) as nr_listings, cg1.id";
				$statusFilter="and (cp.approved = ".OFFER_APPROVED;
				$created = OFFER_CREATED;
				$table = "from #__jbusinessdirectory_company_offers co 
						  inner join #__jbusinessdirectory_companies cp on co.companyId = cp.id";
				$innerJoin = "inner join #__jbusinessdirectory_company_offer_category cc on co.id=cc.offerId";
				$whereCond = "and (co.publish_start_date<=DATE(now()) or co.publish_start_date='0000-00-00' or co.publish_start_date is null) and  (co.publish_end_date>=DATE(now()) or co.publish_end_date='0000-00-00' or co.publish_end_date is null)";
				$feature = "and pf.feature='company_offers'";
				$approved = "and (co.approved = ".OFFER_APPROVED.")";
				if($showPendingApproval){
					$approved = "and (co.approved = ".OFFER_CREATED." or co.approved = ".OFFER_APPROVED.")";
				}
				
				break;
			case CATEGORY_TYPE_EVENT:
				$select = "select count(distinct co.id) as nr_listings, cg1.id";
				$statusFilter="and (cp.approved = ".EVENT_APPROVED;
				$created = EVENT_CREATED;
				$table = "from #__jbusinessdirectory_company_events co
						   inner join #__jbusinessdirectory_companies cp on co.company_id = cp.id";
				$innerJoin = "inner join #__jbusinessdirectory_company_event_category cc on co.id=cc.eventId";
				$whereCond.=" and co.end_date>= DATE(NOW())";
				$feature = "and pf.feature='company_events'";
				$approved = "and (co.approved = ".EVENT_APPROVED.")";
				if($showPendingApproval){
					$approved = "and (co.approved = ".EVENT_CREATED." or co.approved = ".EVENT_APPROVED.")";
				}
				break;
			default:
				$select = "select count(distinct cp.id) as nr_listings, cg1.id";
				$statusFilter="and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED;
				$created = COMPANY_STATUS_CREATED;
				$table = "from #__jbusinessdirectory_companies cp";
				$innerJoin = "inner join #__jbusinessdirectory_company_category cc on cp.id=cc.companyId";
				break;
		}

		$packageFilter = '';
		if($enablePackage){
			$packageFilter = " and (((inv.state= ".PAYMENT_STATUS_PAID." and now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0)))) or p.price=0) $feature ";
		}
		
		$companyStatusFilter="and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED.") $approved";
		if($showPendingApproval){
			$companyStatusFilter = "and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED." or cp.approved= ".COMPANY_STATUS_CREATED.") $approved";
		}
		
		$query = "$select
				  $table
				  $innerJoin
				  inner join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId and cg.published=1
				  inner join #__jbusinessdirectory_categories cg1 ON cg1.id = cg.parent_id or cg1.id=cg.id
				  left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id
				  left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1) or (p.price=0 and p.status=1)
				  left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id
				  where cp.state = 1 $packageFilter $companyStatusFilter $whereCond
				  group by cg1.id";
		
		$db->setQuery( $query );
		if (!$db->query()) {
			return false;
		}

		$results = $db->loadObjectList();

		$listingsCount = array();

		foreach($results as $result){
			$listingsCount[$result->id] = $result;
		}

		return $listingsCount;
	}
}


