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

class JTableReviewQuestionAnswer extends JTable
{

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db){

        parent::__construct('#__jbusinessdirectory_company_reviews_question_answer', 'id', $db);
    }

    function setKey($k){
        $this->_tbl_key = $k;
    }

    function getAnswersByCompany($companyId){
        $db = JFactory::getDBO();
        $query = "select rqa.*
                from #__jbusinessdirectory_company_reviews_question_answer rqa
                left join #__jbusinessdirectory_company_reviews cr on cr.id = rqa.review_id
                where cr.companyId =".$companyId."
                ";
        $db->setQuery($query);

        return $db->loadObjectList();
    }
}