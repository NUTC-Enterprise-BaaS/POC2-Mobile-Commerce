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


JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');

class JBusinessDirectoryModelCompanies extends JModelLegacy
{ 
	function __construct(){
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		parent::__construct();
	}

	function getCompany($cmpId=null){
		$companiesTable = $this->getTable("Company");
		$companyId = JRequest::getVar('companyId');
		
		if(!empty($companyId)){
			$companyId = str_replace(".html","",$companyId);
		}
		
		if(isset($cmpId))
			 $companyId = $cmpId;
		if(empty($companyId))
			return;
		
		$company = $companiesTable->getCompany($companyId);
		if(!empty($company->business_hours)){
			$company->business_hours = explode(",",$company->business_hours);
		}
		
		$categoryTable = $this->getTable("Category","JBusinessTable");
		
		$category = null;
		if(!empty($company->mainSubcategory)){
			$category = $categoryTable->getCategoryById($company->mainSubcategory);
		}else{
			if(!empty($company->categories)){
				$categories = explode('#',$company->categories);
				$category = explode("|", $categories[0]);
				$category = $categoryTable->getCategoryById($category[0]);
			}
		}
		
		$path=array();
		if(!empty($category)){
			$path[]=$category;
			
			if(empty($category)){
				while($category->parent_id != 1){
					if(!$category->parent_id)
						break;
					$category= $categoryTable->getCategoryById($category->parent_id);
					$path[] = $category;
				}
			}			
			$path = array_reverse($path);
			
			$company->path=$path;
		}
		
		$companyLocationsTable = $this->getTable('CompanyLocations');
		$company->locations = $companyLocationsTable->getCompanyLocations($companyId);
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEntityTranslation($company, BUSSINESS_DESCRIPTION_TRANSLATION);
			JBusinessDirectoryTranslations::updateCategoriesTranslation($company->path);
		}
		
		if(!empty($company->description) && $company->description==strip_tags($company->description)){
			$company->description = str_replace("\n", "<br/>", $company->description);
		}
			
		$userId = JFactory::getUser()->id;
		$company->isBookmarked = false;
		if(!empty($userId)){
			$bookmarkTable = $this->getTable('Bookmark');
			$company->bookmark = $bookmarkTable->getBookmark($companyId, $userId);
		}
		
		$company->attachments = JBusinessDirectoryAttachments::getAttachments(BUSSINESS_ATTACHMENTS, $companyId, true);
		
		return $company;
	}
	
	function getPlainCompany($companyId){
		$companiesTable = $this->getTable("Company");
		$company = $companiesTable->getCompany($companyId);
		return $company;
	}

	function getUserRating(){
		$companyId = JRequest::getVar('companyId');
		//dump($_COOKIE['companyRatingIds']);
		$companyRatingIds=array();
		if(isset($_COOKIE['companyRatingIds']))
			$companyRatingIds = explode("#",$_COOKIE['companyRatingIds']);
			
		//dump($companyRatingIds);
		$ratingId =0;
		foreach($companyRatingIds as $companyRatingId){
			$temp = explode(",",$companyRatingId);
			if(strcmp($temp[0],$companyId)==0)
				$ratingId = $temp[1];
		}
		
		$ratingTable = $this->getTable("Rating");
		$rating = $ratingTable->getRating($ratingId);
		//dump($rating);
		
		//exit;
		return $rating;
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Companies', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
	function getReviews(){
		$reviewsTable = $this->getTable("Review");
		$reviews = $reviewsTable->getCompanyReviews(JRequest::getVar('companyId'));

		if(!empty($reviews)){
			foreach($reviews as $review){
				$review->responses =  $reviewsTable->getCompanyReviewResponse($review->id);
				if(isset($review->scores)){
					$review->scores = explode(",",$review->scores);
				}
				if(isset($review->criteria_ids)){
					$review->criteriaIds = explode(",",$review->criteria_ids);
				}
				if(isset($review->answer_ids)){
					$review->answerIds = explode(",", $review->answer_ids);
				}
				if(isset($review->question_ids)){
					$review->questionIds = explode(",",$review->question_ids);

					$temp = array();
					$i = 0;
					foreach($review->questionIds as $val){
						$temp[$val] = $review->answerIds[$i];
						$i++;
					}
					$review->answerIds = $temp;
				}
			}
		}

		return $reviews;
	}
	
	function getReviewCriterias(){
		$reviewsCriteriaTable = $this->getTable("ReviewCriteria");
		$criterias = $reviewsCriteriaTable->getCriterias();

		$result = array();
		foreach($criterias as $criteria){
			$result[$criteria->id]=$criteria;
		}
		$criterias = $result;
		
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateReviewCriteriaTranslation($criterias);
		}
		
		return $criterias;
	}

	function getReviewQuestions(){
		$reviewQuestionsTable = $this->getTable("ReviewQuestion");
		$questions = $reviewQuestionsTable->getQuestions();

		$result = array();
		foreach($questions as $question){
			$result[$question->id]=$question;
		}
		$questions = $result;

		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateReviewQuestionTranslation($questions);
		}

		return $questions;
	}

	function getReviewQuestionAnswers(){
		$companyId = JRequest::getVar('companyId');
		$reviewAnswersTable = $this->getTable("ReviewQuestionAnswer");
		$answers = $reviewAnswersTable->getAnswersByCompany($companyId);

		$result = array();
		foreach($answers as $answer){
			$result[$answer->id]=$answer;
		}
		$answers = $result;

		return $answers;
	}
	
	function getCompanyImages(){
		$query = "SELECT *
				FROM #__jbusinessdirectory_company_pictures
				WHERE picture_enable =1 and companyId =".JRequest::getVar('companyId') ."
				ORDER BY id ";

		$pictures =  $this->_getList( $query );
		$pictures =  $this->_getList( $query );

		return $pictures;
	}
	
	function getCompanyVideos() {
		$table = $this->getTable("companyvideos");
		$videos = $table->getCompanyVideos(JRequest::getVar('companyId'));

		if(!empty($videos)) {
			$data = array();
			foreach($videos as $video) {
				$data = JBusinessUtil::getVideoDetails($video->url);
				$video->url = $data['url'];
				$video->videoType = $data['type'];
				$video->videoThumbnail = $data['thumbnail'];
			}
		}
		
		return $videos;
	}
	
	function getCompanyAttributes(){
		$attributesTable = $this->getTable('CompanyAttributes');
		return  $attributesTable->getCompanyAttributes(JRequest::getVar('companyId'));
	}
	
	function getCompanyOffers(){
		$table = $this->getTable("Offer");
		$offers =  $table->getCompanyOffers(JRequest::getVar('companyId'));
		if(!empty($offers)){
			JBusinessDirectoryTranslations::updateOffersTranslation($offers);
			foreach($offers as $offer){
				switch($offer->view_type){
					case 1:
						$offer->link = JBusinessUtil::getofferLink($offer->id, $offer->alias);
						break;
					case 2:
						$offer->link = JRoute::_('index.php?option=com_content&view=article&id='.$offer->article_id);
						break;
					case 3:
						$offer->link = $offer->url;
						break;
					default:
						$offer->link = JBusinessUtil::getofferLink($offer->id, $offer->alias);
				}
			}
		}
		return $offers;
	}
	
	function getCompanyEvents(){
		$table = $this->getTable("Event");
		$events = $table->getCompanyEvents(JRequest::getVar('companyId'));
		if(!empty($events) && $this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEventsTranslation($events);
		}
		return $events;
	}
	
	/* 
	 * Retrieve the currect active package for a listing
	 */
	function getPackage($companyId=null){
		if(empty($companyId)){
			$companyId = JRequest::getVar('companyId');
		}
		$table = $this->getTable("Package"); 
		$package = $table->getCurrentActivePackage($companyId);

		return $package;
	}
	
	function claimCompany($data){
		$companiesTable = $this->getTable("Company");
		$companyId = JRequest::getVar('companyId');
		
		if($companiesTable->claimCompany($data)){
			return $this->updateCompanyOwner($data['companyId'], $data['userId']);
		}
		return false;
	}
	
	function saveReview($data){
		$criterias = array();
		$questions = array();
		foreach($data as $key=>$value){
			if(strpos($key, "criteria")===0){
				$key = str_replace("criteria-", "", $key);
				$criterias[$key]=$value;
			}
			else if(strpos($key, "question")===0){
				$key = str_replace("question-", "", $key);
				$questions[$key]=$value;
			}
		}
		
		$rating = 0;
		if(isset($data["review"])){
			$rating = $data["review"];
		}
		if(!empty($criterias)){
			$score = 0;
			foreach($criterias as $key=>$value){
				$score += $value;
			}
			$rating = $score/count($criterias);
			$data["rating"] = number_format($rating,2); 
		}
		
		$table = $this->getTable("Review");
		
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
		}
		
		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
		}
		
		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
		}
		$table->updateCompanyReview($data['companyId']);
		
		$reviewId = $table->id;
		foreach($criterias as $key=>$score){
			$table = $this->getTable("ReviewUserCriteria");
			
			$criteriaObj = array();
			$criteriaObj["review_id"]= $reviewId;
			$criteriaObj["criteria_id"]= $key;
			$criteriaObj["score"]= $score;
			// Bind the data.
			//dump($criteriaObj);
			if (!$table->bind($criteriaObj))
			{
				$this->setError($table->getError());
			}
			
			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
			}
			
			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
			}
		}

		foreach($questions as $key=>$value){
			$table = $this->getTable("ReviewQuestionAnswer");

			$questionObj = array();
			$questionObj["review_id"] = $reviewId;
			$questionObj["question_id"] = $key;
			$questionObj["answer"] = $value;
			$questionObj["user_id"] = $data["user_id"];

			// Bind the data.
			if (!$table->bind($questionObj))
			{
				$this->setError($table->getError());
			}

			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
			}

			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
			}
		}
		
		$company=$this->getCompany($data["companyId"]);
		$ret = EmailService::sendReviewEmail($company, $data);
		
		return true;
	}
	
	function saveRating($data){
		$table = $this->getTable("Rating");
		$ratingId = $table->saveRating($data);
		$table->updateCompanyRating($data['companyId']);
		
		return $ratingId;
	}
	
	function getRatingsCount(){
		$companyId = JRequest::getVar('companyId');
		$table = $this->getTable("Rating");
		return $table->getNumberOfRatings($companyId);
	}
	
	function reportAbuse($data){
		
		$data["state"]=1;
		$row = $this->getTable("reviewabuses");
		
		// Bind the form fields to the table
		if (!$row->bind($data)){
				
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$reviewsTable = $this->getTable("Review");
		$review = $reviewsTable->getReview($data["reviewId"]);
		$company=$this->getCompany($data["companyId"]);
		$ret = EmailService::sendReportAbuseEmail($data, $review, $company);
		
		return $ret;
	}
	
	function saveReviewResponse($data){
		//save in banners table
		$row = $this->getTable("reviewresponses");
		$data["state"]=1;
	
		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// Store the web link table to the database
		if (!$row->store()) {
			dump($this->_db->getErrorMsg());
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		$company=$this->getCompany($data["companyId"]);
		$ret = EmailService::sendReviewResponseEmail($company, $data);
		
		return $ret;
	}

	/**
	 * Saves a single Review Question Answer
	 * @param $data
	 * @return bool
	 */
	function saveAnswerAjax($data){
		//save in banners table
		$row = $this->getTable("ReviewQuestionAnswer");

		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			dump($this->_db->getErrorMsg());
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}
	
	function updateCompanyOwner($companyId, $userId){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->updateCompanyOwner($companyId, $userId);
	}
	
	function getUserCompanies(){
		$user = JFactory::getUser();
		if($user->id == 0 ){
			return null;
		}
		$companiesTable = $this->getTable("Company");
		$companies = $companiesTable->getCompaniesByUserId($user->id);
		
		return $companies;
	}
	
	function getCompanyByName($companyName){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->getCompanyByName($companyName);
	}
	
	function contactCompany($data){
		$company = $this->getTable("Company");
		$company->load($data['companyId']);

		$data["description"] = nl2br(htmlspecialchars($data["description"], ENT_QUOTES));
		
		$company->increaseContactsNumber($data['companyId']);
		$ret = EmailService::sendContactCompanyEmail($company, $data);
	
		return $ret;
	}
	
	function addBookmark($data){
		//save in banners table
		$row = $this->getTable("Bookmark");
		
		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Store the web link table to the database
		if (!$row->store()) {
			dump($this->_db->getErrorMsg());
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		return true;
	}
	
	function updateBookmark($data){
		//save in banners table
		$row = $this->getTable("Bookmark");
	
		// Bind the form fields to the table
		if (!$row->bind($data))
		{
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// Make sure the record is valid
		if (!$row->check()) {
			dump($this->_db->getErrorMsg());
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// Store the web link table to the database
		if (!$row->store()) {
			dump($this->_db->getErrorMsg());
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
	
		return true;
	}
	
	function removeBookmark($data){
		$row = $this->getTable("Bookmark");
		return $row->delete($data["id"]);
	}
	
	
	function requestQuoteCompany($data){
		$company = $this->getTable("Company");
		$company->load($data['companyId']);
	
		$company->increaseContactsNumber($data['companyId']);
		$ret = EmailService::sendRequestQuoteEmail($data, $company);
	
		return $ret;
	}

	/**
	 * Get the listings that are about to expire and send an email to business owners
	 */
	function checkBusinessAboutToExpire(){
		$companyTable = $this->getTable("Company");
		$orderTable = $this->getTable("Order");
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$nrDays = $appSettings->expiration_day_notice;
		$companies = $companyTable->getBusinessAboutToExpire($nrDays);
		foreach($companies as $company){
			echo "sending expiration e-mail to: ".$company->name;
			$result = EmailService::sendExpirationEmail($company, $nrDays);
			if($result){
				$orderTable->updateExpirationEmailDate($company->orderId);
			}
		}
		exit;
	}
	
	/**
	 * Increate the website access number when clicked
	 * 
	 * @param int $companyId
	 * @return company
	 */
	function increaseWebsiteCount($companyId){
		
		$company = $this->getCompany();
		
		$companiesTable = $this->getTable("Company");
		$companiesTable->increaseWebsiteCount($company->id);
		
		return $company;
	}
	
	function increaseReviewLikeCount($reviewId){
		$table = $this->getTable("Review");
		return $table->increaseReviewLike($reviewId);
	}
	
	function increaseReviewDislikeCount($reviewId){
		$table = $this->getTable("Review");
		return $table->increaseReviewDislike($reviewId);
	}
	
	function increaseViewCount(){
		$companiesTable = $this->getTable("Company");
		return $companiesTable->increaseViewCount(JRequest::getVar('companyId'));
	}
	
	function getViewCount(){
		return $this->increaseViewCount();
	}

	function saveCompanyMessages(){
		$data = array();
		$data["name"] = JRequest::getVar('firstName');
		$data["surname"] = JRequest::getVar('lastName');
		$data["email"] = JRequest::getVar('email');
		$data["message"] = JRequest::getVar('description');
		$data["company_id"] = JRequest::getVar('companyId');

		$table = $this->getTable("CompanyMessages");

		$data["message"] = htmlspecialchars($data["message"]);
		
		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());
			dump($table->getError());
			return false;
		}
		
		return true;
		
	}
}
?>