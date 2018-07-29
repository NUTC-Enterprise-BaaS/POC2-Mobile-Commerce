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

class JTableOfferAttributes extends JTable
{

    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db){

        parent::__construct('#__jbusinessdirectory_offer_attributes', 'id', $db);
    }

    function setKey($k)
    {
        $this->_tbl_key = $k;
    }

    function getOfferAttributes($id){
        $query = "select a.*,
				  GROUP_CONCAT(DISTINCT ao.name ORDER BY ao.id asc SEPARATOR ',')  options,
				  GROUP_CONCAT(DISTINCT ao.id ORDER BY ao.id asc SEPARATOR ',')  optionsIDS,
				  at.code as attributeTypeCode,
				  ca.value as attributeValue
				  from #__jbusinessdirectory_attributes a
		          left join #__jbusinessdirectory_attribute_types AS at on at.id=a.type
		          left join #__jbusinessdirectory_attribute_options as ao on ao.attribute_id = a.id
		          left join #__jbusinessdirectory_offer_attributes AS ca on ca.attribute_id = a.id and ca.offer_id=$id
		          where a.status = 1 and a.attribute_type = 2
				  group by a.id
		          order by a.ordering
				  ";
        $this->_db->setQuery( $query );
        return $this->_db->loadObjectList();
    }

    function deleteOfferAttributes($offerId){
        $db =JFactory::getDBO();
        $query = 	" delete from #__jbusinessdirectory_offer_attributes WHERE offer_id = ".$offerId ;
        $db->setQuery( $query );
        if (!$db->query()){
            return false;
        }

        return true;
    }
}