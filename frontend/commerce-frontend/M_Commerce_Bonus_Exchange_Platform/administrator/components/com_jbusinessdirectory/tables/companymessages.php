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

class JTableCompanyMessages extends JTable {

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db){

        parent::__construct('#__jbusinessdirectory_company_messages', 'id', $db);
    }

    function setKey($k){
        $this->_tbl_key = $k;
    }

    function getCompanyMessages(){
        $db =JFactory::getDBO();
        $query = "select cm.*, cp.name as companyName
                    from #__jbusinessdirectory_company_messages cm
                    left join #__jbusinessdirectory_companies cp
                    on cm.company_id = cp.id
                    order by cm.id ";
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function getUserCompanyMessages($companyIds, $limitstart=0, $limit=0){
        $db =JFactory::getDBO();
        if(empty($companyIds))
	        return array();
        
        $companyIds = implode(",", $companyIds);
        
        $query = "select cm.*, cp.name as companyName
					from
					#__jbusinessdirectory_company_messages cm
					left join #__jbusinessdirectory_companies cp on cp.id = cm.company_id
					where company_id in ($companyIds)
					group by cm.id	";

        $db->setQuery($query, $limitstart, $limit);
        return $db->loadObjectList();
    }

    function getTotalCompanyMessagesByUser($companyIds){
        $db =JFactory::getDBO();

        if(!empty($companyIds))
            $companyIds = implode(",", $companyIds);
        else
            $companyIds = 0;
        $query = "select cm.*, cp.name as companyName
					from
					#__jbusinessdirectory_company_messages cm
					left join #__jbusinessdirectory_companies cp on cp.id = cm.company_id
					where company_id in ($companyIds)
					group by cm.id	";

        $db->setQuery($query);
        $db->query();
        return $db->getNumRows();

    }
}
