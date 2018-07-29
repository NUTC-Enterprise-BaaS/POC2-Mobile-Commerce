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
jimport('joomla.application.component.modeladmin');

class JBusinessDirectoryModelJBusinessDirectory extends JModelAdmin
{ 
	public function getForm($data = array(), $loadData = true)
	{
	
	}
	
	public function getStatistics(){
		$statistics = new stdClass();
		
		$companyTable = JTable::getInstance('Company','JTable');
		$statistics->totalListings = $companyTable->getTotalListings();
		$statistics->today = $companyTable->getTodayListings();
		$statistics->week = $companyTable->getWeekListings();
		$statistics->month = $companyTable->getMonthListings();
		$statistics->year = $companyTable->getYearListings();
		$statistics->listingsTotalViews = $companyTable->getListingsViews();
		
		$categoryTable = JTable::getInstance('Category','JBusinessTable');
		$statistics->totalCategories = $categoryTable->getTotalCategories();
		
		$offersTable = JTable::getInstance('Offer','JTable');
		$statistics->totalOffers = $offersTable->getTotalNumberOfOffers();
		$statistics->activeOffers = $offersTable->getTotalActiveOffers();
		$statistics->offersTotalViews = $offersTable->getOffersViews();
		
		$eventsTable = JTable::getInstance('Event','JTable');
		$statistics->totalEvents = $eventsTable->getTotalNumberOfEvents();
		$statistics->activeEvents = $eventsTable->getTotalActiveEvents();
		$statistics->eventsTotalViews = $eventsTable->getEventsViews();
		
		$statistics->totalViews = $statistics->listingsTotalViews + $statistics->offersTotalViews + $statistics->eventsTotalViews;
		
		return $statistics;
	}
	
	/**
	 * Get the income for different time periods.
	 */
	public function getIncome(){
		$income = new stdClass();
		
		$orderTable = JTable::getInstance('Order','JTable');
		$income->total = $orderTable->getTotalIncome();
		$income->today = $orderTable->getTodayIncome();
		$income->week = $orderTable->getWeekIncome();
		$income->month = $orderTable->getMonthIncome();
		$income->year = $orderTable->getYearIncome();

		return $income;
	}

	public function getNewCompanies() {
		$start_date = JRequest::getVar('start_date');
		$start_date = date("Y-m-d", strtotime($start_date));
		$end_date = JRequest::getVar('end_date');
		$end_date = date("Y-m-d", strtotime($end_date));

		$companyTable = JTable::getInstance('Company','JTable');
		$result = $companyTable->getNewCompanies($start_date, $end_date);
		
		//add start date element if it does not exists
		if($result[0]->date!=$start_date){
			$item = new stdClass();
			$item->date = $start_date;
			$item->value = 0;
			array_unshift($result, $item);
		}
		
		//add end date element if it does not exists
		if(end($result)->date!=$end_date){
			$item = new stdClass();
			$item->date = $end_date;
			$item->value = 0;
			array_push($result, $item);
		}
		
		return $result;
	}

	public function getNewOffers() {
		$start_date = JRequest::getVar('start_date');
		$start_date = date("Y-m-d", strtotime($start_date));
		$end_date = JRequest::getVar('end_date');
		$end_date = date("Y-m-d", strtotime($end_date));

		$offerTable = JTable::getInstance('Offer','JTable');
		$result = $offerTable->getNewOffers($start_date, $end_date);
		
		//add start date element if it does not exists
		if($result[0]->date!=$start_date){
			$item = new stdClass();
			$item->date = $start_date;
			$item->value = 0;
			array_unshift($result, $item);
		}
		
		//add end date element if it does not exists
		if(end($result)->date!=$end_date){
			$item = new stdClass();
			$item->date = $end_date;
			$item->value = 0;
			array_push($result, $item);
		}
		
		return $result;
	}

	public function getNewEvents() {
		$start_date = JRequest::getVar('start_date');
		$start_date = date("Y-m-d", strtotime($start_date));
		$end_date = JRequest::getVar('end_date');
		$end_date = date("Y-m-d", strtotime($end_date));

		$eventTable = JTable::getInstance('Event','JTable');
		$result = $eventTable->getNewEvents($start_date, $end_date);
		
		//add start date element if it does not exists
		if($result[0]->date!=$start_date){
			$item = new stdClass();
			$item->date = $start_date;
			$item->value = 0;
			array_unshift($result, $item);
		}
		
		//add end date element if it does not exists
		if(end($result)->date!=$end_date){
			$item = new stdClass();
			$item->date = $end_date;
			$item->value = 0;
			array_push($result, $item);
		}
		
		return $result;
	}

	public function getNewIncome() {
		$start_date = JRequest::getVar('start_date');
		$start_date = date("Y-m-d", strtotime($start_date));
		$end_date = JRequest::getVar('end_date');
		$end_date = date("Y-m-d", strtotime($end_date));

		$incomeTable = JTable::getInstance('Order','JTable');
		$income = $incomeTable->getNewIncome($start_date, $end_date);
		
		return $income;
	}

	/**
	 * 
	 */
	public function getServerNews() {
		$rss = new DOMDocument();
		$rss->load('http://www.cmsjunkie.com/blog/rss/');

		$feeds = array();
		foreach ($rss->getElementsByTagName('item') as $node) {
			$item = array ( 
				'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
				'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
				'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
				'publish_date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue
			);
			array_push($feeds, $item);
		}
		return $feeds;
	}
	
	/**
	 * Get the latest news from local database and prepare them
	 * 
	 * @param unknown_type $limit
	 */
	public function getLocalNews($limit=3) {
		// $limit -> the limit of the news to be displayed in the dashboard
		$db = JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_news order by publish_date desc limit $limit";
		$db->setQuery($query);
		$news = $db->loadObjectList();
		
		foreach($news as $item){
			$publish_ago = JBusinessUtil::convertTimestampToAgo($item->publish_date); 
			$item->publish_ago = $publish_ago;
			$item->new = false;
			
			$time = strftime('%Y-%m-%d',(strtotime('7 days ago')));
			$retrieve_date = strftime('%Y-%m-%d',(strtotime($item->retrieve_date)));
			//For 7 days from the moment of the retrieve_date the news will be displayed like NEW
			if($time < $retrieve_date) {
				$item->new = true;
			}
			
			$item->description = mb_strimwidth(strip_tags($item->description), 0, 200, '...');
			$item->publishDateS = date('l, M d, Y', strtotime($item->publish_date));
		}
		
		return $news;
	}

	/**
	 * Retrieve the last news from database
	 * 
	 */
	public function getLocalLastNews() {
		$db = JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_news order by retrieve_date desc limit 1";
		$db->setQuery($query);
		$lastNews = $db->loadObject();
		return $lastNews;
	}
	
	/**
	 * Get the latest news from server and store the new ones
	 * 
	 */
	public function getLatestServerNews() {
		$lastNews = $this->getLocalLastNews();

		if (empty($lastNews)) {
			$serverNews = $this->getServerNews();
			$this->storeNews($serverNews);
		}else{
			$days_ago = NEWS_REFRESH_PERIOD; // refresh records after specified days
			$check_date = date('Y-m-d H:i:s',(strtotime($days_ago.' days ago')));
			$lastNewsRetrieveDate = date('Y-m-d H:i:s', strtotime($lastNews->retrieve_date));
			
			if($check_date > $lastNewsRetrieveDate) {
				$serverNews = $this->getServerNews();
				$localNews = $this->getLocalNews();
	
				$feeds = array();
				foreach($serverNews as $singleServerNews) {
					$title = str_replace(' & ', ' &amp; ', $singleServerNews['title']);
					$link = $singleServerNews['link'];
					$description = $singleServerNews['description'];
					$publish_date = date('Y-m-d H:i:s', strtotime($singleServerNews['publish_date']));
	
					$flag = true;
					foreach ($localNews as $singleLocalNews) {
						$singleLocalNews_publish_date = date('Y-m-d H:i:s', strtotime($singleLocalNews->publish_date));
						if($publish_date == $singleLocalNews_publish_date) {
							$flag = false;
						}
					}
	
					if($flag) {
						$item = array ( 
							'title' => $title,
							'link' => $link,
							'description' => $description,
							'publish_date' => $publish_date
						);
						array_push($feeds, $item);
					}
				}
	
				//if there are new news store them
				if(!empty($feeds)) {
					$this->storeNews($feeds);
					return $this->getLocalNews(3);
				}
			}
		}
	}

	/**
	 * Store the news into database
	 * 
	 * @param unknown_type $feeds
	 */
	public function storeNews($feeds) {
		foreach($feeds as $feed) {
			$title = str_replace(' & ', ' &amp; ', $feed['title']);
			$link = $feed['link'];
			$description = $feed['description'];
			$publish_date = date('Y-m-d H:i:s', strtotime($feed['publish_date']));
			$retrieve_date = date('Y-m-d H:i:s');

			$item = new stdClass();
			$item->title = $title;
			$item->link = $link;
			$item->description = $description;
			$item->publish_date = $publish_date;
			$item->retrieve_date = $retrieve_date;
			
			$result = JFactory::getDbo()->insertObject('#__jbusinessdirectory_news', $item);
		}
	}
}
?>