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

class JTableEvent extends JTable
{

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function JTableEvent(& $db) {
		parent::__construct('#__jbusinessdirectory_company_events', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}
	
	function getEventsByCategoriesSql($searchDetails){
		$db =JFactory::getDBO();
		
		$keyword = isset($searchDetails['keyword'])?$searchDetails['keyword']:null;
		$categoriesIDs = isset($searchDetails["categoriesIds"])?$searchDetails["categoriesIds"]:null;
		$latitude = isset($searchDetails["latitude"])?$searchDetails["latitude"]:null;
		$longitude = isset($searchDetails["longitude"])?$searchDetails["longitude"]:null;
		$radius = isset($searchDetails["radius"])?$searchDetails["radius"]:null;
		$city = isset($searchDetails["citySearch"])?$searchDetails["citySearch"]:null;
		$region = isset($searchDetails["regionSearch"])?$searchDetails["regionSearch"]:null;
		
		$enablePackage = isset($searchDetails["enablePackages"])?$searchDetails["enablePackages"]:null;
		$showPendingApproval = isset($searchDetails["showPendingApproval"])?$searchDetails["showPendingApproval"]:null;
		$startDate = isset($searchDetails["startDate"])?$searchDetails["startDate"]:null;
		$endDate = isset($searchDetails["endDate"])?$searchDetails["endDate"]:null;
		$type = isset($searchDetails["typeSearch"])?$searchDetails["typeSearch"]:null;
		$companyId = isset($searchDetails["companyId"])?$searchDetails["companyId"]:null;
		$orderBy = isset($searchDetails["orderBy"])?$searchDetails["orderBy"]:null;
		$featured = isset($searchDetails["featured"])?$searchDetails["featured"]:null;
		$asc_desc = isset($searchDetails["asc_desc"])?$searchDetails["asc_desc"]:null;
		$multilingual = false;// isset($searchDetails["multilingual"])?$searchDetails["multilingual"]:null;
		
		$whereCatCond = '';
		//dump($categoriesIDs);
		if(isset($categoriesIDs) && count($categoriesIDs)>0){
			$whereCatCond = ' ( ';
			//dump($categoriesIDs);
			foreach($categoriesIDs as $categoryId){
				$whereCatCond .= $categoryId.',';
			}
			$whereCatCond = substr($whereCatCond, 0, -1);
			$whereCatCond.=')';
			$whereCatCond = ' and cc.categoryId in '.$whereCatCond;
		}
		
		$whereDateCond="";

		if(!empty($startDate) && !empty($endDate)){
			$whereDateCond.=" and (co.start_date<='$endDate' and co.end_date>='$startDate')";
		}else if(!empty($startDate)){
				$whereDateCond.=" and co.end_date>='$startDate'";
		}else if(!empty($endDate)){
				$whereDateCond.=" and co.start_date<='$endDate'";
		}else{
			$whereDateCond.=" and co.end_date>= DATE(NOW())";
		}

		if(empty($keyword)){
			$multilingual = false;
		}
		
		$translationCondition = '';
		if($multilingual ) {
			$translationCondition = " or t.name like '%$keyword%'";
		}

		$whereNameCond='';
		if(!empty($keyword)){
			$keyword = $db->escape($keyword);
				
			$translationCondition = '';
			if($multilingual) {
				$translationCondition = " or t.name like '%$keyword%'";
			}
			
			$keywords = explode(" ",$keyword);
			$fields= array("co.name","cg.name","co.meta_description","co.short_description","co.city","co.county","cp.name");
				
			$sqlFilter="";
			foreach($fields as $field){
				$sqlFilter .= "("."$field LIKE '%".implode("%' and $field LIKE '%", $keywords) . "%') OR ";
			}
				
			$whereNameCond=" and ($sqlFilter (co.name like '%$keyword%') $translationCondition) ";
		}
		
		$whereTypeCond='';
		if(!empty($type)){
			$whereTypeCond=" and co.type = $type";
		}
		
		$whereCompanyIdCond='';
		if(!empty($companyId)){
			$whereCompanyIdCond=" and cp.id = $companyId";
		}
		
		$whereCityCond='';
		if(!empty($city)){
			$city = $db->escape($city);
			$whereCityCond=" and co.city = '".$db->escape($city)."' ";
		}
		
		$whereRegionCond='';
		if(!empty($region)){
			$region = $db->escape($region);
			$whereRegionCond=" and co.county = '".$db->escape($region)."' ";
		}
		
		$distanceQuery = "";
		$having = "";
		if(!empty($latitude) && !empty($longitude)){
			$distanceQuery = ", 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude -abs( co.latitude)) * pi()/180 / 2),2) + COS($latitude * pi()/180 ) * COS( abs( co.latitude) *  pi()/180) * POWER(SIN(($longitude -  co.longitude) *  pi()/180 / 2), 2) )) as distance";
		
		if(empty($orderBy))
				$orderBy = "distance";
		
			if($radius>0)
				$having = "having distance < $radius";
		}
		
		$featuredFilter = "";
		if($featured){
			$featuredFilter = " and co.featured = 1";
		}
		
		$packageFilter = '';
		if($enablePackage){
			$packageFilter = " and (((inv.state= ".PAYMENT_STATUS_PAID." and now() > (inv.start_date) and (now() < (inv.start_date + INTERVAL p.days DAY) or (inv.package_id=p.id and p.days = 0)))) or p.price=0) and pf.feature='company_events' ";
		}
		
		$companyStatusFilter="and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED.") and (co.approved = ".EVENT_APPROVED.")";
		if($showPendingApproval){
			$companyStatusFilter = "and (cp.approved = ".COMPANY_STATUS_APPROVED." or cp.approved= ".COMPANY_STATUS_CLAIMED." or cp.approved= ".COMPANY_STATUS_CREATED.")  and (co.approved = ".EVENT_CREATED." or co.approved = ".EVENT_APPROVED.") ";
		}

		if(empty($asc_desc)){
			$asc_desc = "";
		}

		if($orderBy=="rand()" || empty ($orderBy)){
			$orderBy = "co.id";
			$asc_desc = "desc";
		}

		$query = " select co.id, co.name, co.short_description, co.address, co.city, co.county, co.latitude, co.longitude, co.start_date, co.end_date, co.start_time, co.end_time, co.alias, co.featured, co.created, 
					op.picture_info,op.picture_path, et.id as type, et.name as eventType ,cp.phone, co.featured, co.created,
					GROUP_CONCAT( DISTINCT cg.id,'|',cg.name,'|',cg.alias ORDER BY cg.name separator '#') as categories 
					$distanceQuery
					from
					#__jbusinessdirectory_company_events co
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId and
					(op.id in (
							select  min(op1.id) as min from #__jbusinessdirectory_company_events co1
							left join  #__jbusinessdirectory_company_event_pictures op1 on co1.id=op1.eventId
							where op1.picture_enable=1
							group by co1.id
						)
					)
					left join  #__jbusinessdirectory_company_event_types et on co.type=et.id
					left join #__jbusinessdirectory_company_event_category cc on co.id=cc.eventId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId and cg.published=1
					inner join #__jbusinessdirectory_companies cp on co.company_id = cp.id
					left join #__jbusinessdirectory_orders inv on inv.company_id=cp.id 
					left join #__jbusinessdirectory_packages p on (inv.package_id=p.id and p.status=1) or (p.price=0 and p.status=1)
					left join #__jbusinessdirectory_package_fields pf on p.id=pf.package_id".
					(!empty($multilingual)?"
					left join #__jbusinessdirectory_language_translations t on co.id = t.object_id and t.type = ".EVENT_DESCRIPTION_TRANSLATION:"").
					" where co.state=1
					$whereDateCond $whereCompanyIdCond
					$whereCatCond $packageFilter $companyStatusFilter 
					$whereNameCond $whereTypeCond  $whereCityCond $whereRegionCond $featuredFilter
					and cp.state=1
					group by co.id
					$having
					order by featured desc, $orderBy $asc_desc
					";

		return $query;
	}
	
	
	function getEventsByCategories($searchDetails, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
	
		$query = $this->getEventsByCategoriesSql($searchDetails);
		//echo $query;

		$db->setQuery($query, $limitstart, $limit);
		//dump($this->_db->getErrorMsg());
		$result = $db->loadObjectList();
		
		return $result;
	}
	
	function getTotalEventsByCategories($searchDetails){
		$db =JFactory::getDBO();
		
		$query = $this->getEventsByCategoriesSql($searchDetails);
	
		//dump($query);
		//echo $query;
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function changeAprovalState($eventId, $state){
		$db =JFactory::getDBO();
		$query = " UPDATE #__jbusinessdirectory_company_events SET approved=$state WHERE id = ".$eventId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}


	function increaseViewCount($eventId){
		$db =JFactory::getDBO();
		$query = "update  #__jbusinessdirectory_company_events set view_count = view_count + 1 where id=$eventId";
		$db->setQuery($query);
		return $db->query();
	}

	function getEvent($eventId){
		$db =JFactory::getDBO();
		$query = "select e.*, et.name as eventType, et.id as eventTypeId
					from #__jbusinessdirectory_company_events e
					left join  #__jbusinessdirectory_company_event_types et on e.type=et.id
					where e.id=".$eventId;
		$db->setQuery($query);
		//dump($query);
		return $db->loadObject();
	}

	function getEvents($filter, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$query = "select co.*,cp.name as companyName from #__jbusinessdirectory_company_events co
				  left join  #__jbusinessdirectory_companies cp on cp.id=co.companyId
				  left join #__jbusinessdirectory_company_event_category cc on co.id=cc.eventId
				  left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId and cg.published=1 
		$filter";
		// 		dump($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function getTotalEvents(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_events";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function changeState($eventId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_events SET state = IF(state, 0, 1) WHERE id = ".$eventId ;
		$db->setQuery( $query );

		if (!$db->query()){
			return false;
		}
		return true;
	}
	
	function changeStateFeatured($eventId){
		$db =JFactory::getDBO();
		$query = 	" UPDATE #__jbusinessdirectory_company_events SET featured = IF(featured, 0, 1) WHERE id = ".$eventId ;
		$db->setQuery( $query );
	
		if (!$db->query()){
			return false;
		}
		return true;
	}

	function getCompanyEvents($companyId, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		
		$showPendingApproval = JBusinessUtil::getApplicationSettings()->show_pending_approval;
		$approvalFilter="and (co.approved = ".EVENT_APPROVED.")";
		if($showPendingApproval){
			$approvalFilter = "and (co.approved = ".EVENT_CREATED." or co.approved = ".EVENT_APPROVED.") ";
		}
		
		$query = "select co.*, op.picture_path, et.name as eventType, cg.markerLocation as categoryMarker
					from #__jbusinessdirectory_company_events co
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId
					and (op.id in (
							select  min(op1.id) as min from #__jbusinessdirectory_company_events co1
							left join  #__jbusinessdirectory_company_event_pictures op1 on co1.id=op1.eventId
							where op1.picture_enable=1 and company_id=$companyId
							group by co1.id))
					left join  #__jbusinessdirectory_company_event_types et on co.type=et.id
					left join #__jbusinessdirectory_company_event_category cc on co.id=cc.eventId
					left join #__jbusinessdirectory_categories cg on cg.id=cc.categoryId and cg.published=1
					where co.state=1 and co.end_date>=DATE(now()) and company_id=$companyId $approvalFilter
					group by co.id	
					order by co.start_date";
			
		//echo($query);
		$db->setQuery($query, $limitstart, $limit);
		$result = $db->loadObjectList();
		//dump($result);
		//dump($this->_db->getErrorMsg());
		return $result;
	}

	function getUserEvents($companyIds, $limitstart=0, $limit=0){
		$db =JFactory::getDBO();
		$companyIds = implode(",", $companyIds);
		$query = "select co.*, cp.name as companyName, op.picture_path 
					from 
					#__jbusinessdirectory_company_events co
					left join #__jbusinessdirectory_companies cp on cp.id = co.company_id
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId and
					(op.id in (
							select  min(op1.id) as min from #__jbusinessdirectory_company_events co1
							left join  #__jbusinessdirectory_company_event_pictures op1 on co1.id=op1.eventId
							where op1.picture_enable=1
							group by co1.id
						)
					)
					where company_id in ($companyIds)
					group by co.id	";

		//dump($query);
		$db->setQuery($query, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function getTotalUserEvents($companyIds){
		$db =JFactory::getDBO();
		$companyIds = implode(",", $companyIds);
		$query = "select * from #__jbusinessdirectory_company_events where company_id in ($companyIds)";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function getTotalCompanyEvents($companyId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_events where company_id=$companyId";
		$db->setQuery($query);
		$db->query();
		return $db->getNumRows();
	}

	function getEventPictures($eventId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_company_event_pictures where eventId=$eventId and picture_enable=1 order by id";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getTotalNumberOfEvents($userId=null){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_events ev ".
				(empty($userId)?"":"inner join  #__jbusinessdirectory_companies c on c.id = ev.company_id
									where c.userId =  $userId");
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result->nr;
	}

	function getEventsViews($userId=null){
		$db =JFactory::getDBO();
		$query = "SELECT sum(view_count) as nr FROM #__jbusinessdirectory_company_events ev ".
				(empty($userId)?"":"inner join  #__jbusinessdirectory_companies c on c.id = ev.company_id
									where c.userId =  $userId");
		$db->setQuery($query);
		$result = $db->loadObject();
	
		return $result->nr;
	}
	function getTotalActiveEvents(){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_events where state =1 and end_date>now()";
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result->nr;
	}
	
	function getEventsForExport(){
		$db =JFactory::getDBO();
		$query = "select co.*, GROUP_CONCAT(op.picture_path) as pictures, et.name as eventType, c.name as company
					from #__jbusinessdirectory_company_events co
					left join  #__jbusinessdirectory_company_event_pictures op on co.id=op.eventId
					left join  #__jbusinessdirectory_company_event_types et on co.type=et.id
					left join  #__jbusinessdirectory_companies c on co.company_id=c.id
					group by co.id	
					order by co.id";
			
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}
	
	function checkAlias($id, $alias){
		$db =JFactory::getDBO();
		$query = "SELECT count(*) as nr FROM #__jbusinessdirectory_company_events  WHERE alias='$alias' and id<>$id";
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result->nr;
	}
	
	function getNextEventsIds($id, $reccuring_id){
		$db =JFactory::getDBO();
		$query = "select id from #__jbusinessdirectory_company_events where id>=$id and recurring_id=$reccuring_id";
		$db->setQuery($query);
		$db->setQuery($query);
		$items = $db->loadObjectList();
		$result = array();
		
		foreach($items as $item){
			$result[] = $item->id;
		}
		
		return $result;
	}
	
	function getAllSeriesEventsIds($reccuring_id){
		$db =JFactory::getDBO();
		$query = "select id from #__jbusinessdirectory_company_events where recurring_id=$reccuring_id or id = $reccuring_id";
		$db->setQuery($query);
		$items = $db->loadObjectList();
		$result = array();
		
		foreach($items as $item){
			$result[] = $item->id;
		}
		
		return $result;
	}
	
	function deleteReccuringEvents($reccuring_id){
		$db =JFactory::getDBO();
		$query = "delete from #__jbusinessdirectory_company_events where recurring_id=$reccuring_id";
		$db->setQuery($query);
		return $db->query();
	}
	
	function getReccuringEvents($id, $reccuring_id){
		
	}

	function getNewEvents($start_date, $end_date) {
		$db = JFactory::getDBO();
		$query = "select DATE_FORMAT(created, '%Y-%m-%d') as date, count(*) as value 
					from #__jbusinessdirectory_company_events
					where (CAST(created AS DATE) between '$start_date' and '$end_date')
					group by date
					having date IS NOT NULL
					order by date asc";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}

	function getEventsRSS() {
		$db = JFactory::getDBO();
		$query = "select ce.id, ce.alias, ce.name, ce.description, ce.featured, ce.created,
					op.picture_path, cp.name as companyName
					from #__jbusinessdirectory_company_events ce
					left join #__jbusinessdirectory_company_event_pictures op on ce.id=op.eventId
					and ( op.id in (
						select  min(op1.id) as min from #__jbusinessdirectory_company_events co1
						left join  #__jbusinessdirectory_company_event_pictures op1 on co1.id=op1.eventId
						where op1.picture_enable=1
						group by co1.id ) )
					inner join #__jbusinessdirectory_companies cp on ce.company_id = cp.id
					where ce.state=1 
					and ce.approved !=-1
					and cp.state=1
					and cp.approved !=-1
					group by ce.id
					order by featured desc, name asc";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
}