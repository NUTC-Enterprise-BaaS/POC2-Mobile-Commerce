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

class JTableReviewQuestion extends JTable
{

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) {

        parent::__construct('#__jbusinessdirectory_company_reviews_question', 'id', $db);
    }

    function setKey($k) {
        $this->_tbl_key = $k;
    }

    function getQuestions() {
        $db = JFactory::getDBO();
        $query = "select * from #__jbusinessdirectory_company_reviews_question order by ordering";
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Retrieves all the review questions along with the associated answers
     * @return mixed
     */
    function getQuestionAnswers(){
        $db = JFactory::getDBO();
        $query = "select rq.name as question, rq.type, rq.ordering, qa.answer, qa.user_id, qa.id
                  from
                  #__jbusinessdirectory_company_reviews_question rq
                  left join #__jbusinessdirectory_company_reviews_question_answer qa on qa.question_id = rq.id
                  order by rq.ordering";
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    /**
     * Retrieves the review questions (along with it's answers) that belong to a certain review
     * @param $reviewId
     * @return mixed
     */
    function getQuestionAnswersByReview($reviewId){
        $db = JFactory::getDBO();
        $query = "select rq.name as question, rq.type, rq.ordering, qa.answer, qa.user_id, qa.id
                  from
                  #__jbusinessdirectory_company_reviews_question rq
                  left join #__jbusinessdirectory_company_reviews_question_answer qa on qa.question_id = rq.id
                  where qa.review_id = $reviewId
                  order by rq.ordering";
        $db->setQuery($query);

        return $db->loadObjectList();
    }

}