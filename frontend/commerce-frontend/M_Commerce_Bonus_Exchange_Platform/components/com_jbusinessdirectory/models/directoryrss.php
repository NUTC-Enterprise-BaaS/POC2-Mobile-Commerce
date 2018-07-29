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

class JBusinessDirectoryModelDirectoryRSS extends JModelList {

	
	function __construct(){
	
		parent::__construct();
	
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	}
	
	/**
	 * Declare RSS information including the Title, URL and the Meta Description of the site.
	 */
	function getHeaderRSS() {
		$config = JFactory::getConfig();

		header('Cache-Control: no-cache, must-revalidate');
		header('content-type: text/xml');

		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">';
		echo '<channel>';
		echo '<title>'.$config->get('sitename').'</title>';
		echo '<link>'.JURI::root().'</link>';
		echo '<description>'.$config->get('MetaDesc').'</description>';
	}

	/**
	 * Get the RSS Feeds of the companies.
	 */
	function getCompaniesRSS() {
		$companiesTable = JTable::getInstance("Company", "JTable");
		$companies = $companiesTable->getCompaniesRSS();
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateBusinessListingsTranslation($companies);
			JBusinessDirectoryTranslations::updateBusinessListingsSloganTranslation($companies);
		}
		
		$this->getHeaderRSS();

		foreach ($companies as $company) {
			if(empty($company->logoLocation))
				$company->logoLocation = DS.'no_image.jpg'; // If there isn't any image display the default image.

			$campanyLogo = JURI::root().PICTURES_PATH.$company->logoLocation;

			echo '<item>';
			echo '<title>';
			echo '<![CDATA['.$company->name.']]>';
			echo '</title>';
			echo '<link>'.JBusinessUtil::getCompanyLink($company).'</link>';
			echo '<pubDate>';
			echo date(DATE_RSS, strtotime($company->creationDate));
			echo '</pubDate>';
			echo '<description>';
			echo '<![CDATA[<img src="'.$campanyLogo.'" width="150" /><br>'.$company->description.']]>';
			echo '</description>';
			echo '</item>';
		}

		echo '</channel>';
		echo '</rss>';
	}

	/**
	 * Get the RSS Feeds of the offers.
	 */
	function getOffersRSS() {
		$offersTable = JTable::getInstance("Offer", "JTable");
		$offers = $offersTable->getOffersRSS();

		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateOffersTranslation($offers);
		}
		
		$this->getHeaderRSS();

		foreach ($offers as $offer) {

			if(empty($offer->picture_path))
				$offer->picture_path = DS.'no_image.jpg';

			$offerLogo = JURI::root().PICTURES_PATH.$offer->picture_path;

			echo '<item>';
			echo '<title>';
			echo '<![CDATA['.$offer->subject.']]>';
			echo '</title>';
			echo '<link>'.JBusinessUtil::getOfferLink($offer->id, $offer->alias).'</link>';
			echo '<pubDate>';
			echo date(DATE_RSS, strtotime($offer->created));
			echo '</pubDate>';
			echo '<description>';
			echo '<![CDATA[<img src="'.$offerLogo.'" width="150" /><br>'.$offer->description.']]>';
			echo '</description>';
			echo '</item>';
		}

		echo '</channel>';
		echo '</rss>';
	}

	/**
	 * Get the RSS Feeds of the events.
	 */
	function getEventsRSS() {
		$eventsTable = JTable::getInstance("Event", "JTable");
		$events = $eventsTable->getEventsRSS();

		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEventsTranslation($events);
		}
		
		$this->getHeaderRSS();

		foreach ($events as $event) {

			if(empty($event->picture_path))
				$event->picture_path = DS.'no_image.jpg';

			$eventLogo = JURI::root().PICTURES_PATH.$event->picture_path;

			echo '<item>';
			echo '<title>';
			echo '<![CDATA['.$event->name.']]>';
			echo '</title>';
			echo '<link>'.JBusinessUtil::getEventLink($event->id, $event->alias).'</link>';
			echo '<pubDate>';
			echo date(DATE_RSS, strtotime($event->created));
			echo '</pubDate>';
			echo '<description>';
			echo '<![CDATA[<img src="'.$eventLogo.'" width="150" /><br>'.$event->description.']]>';
			echo '</description>';
			echo '</item>';
		}

		echo '</channel>';
		echo '</rss>';
	}
}
?>