<?php
/**
 * @version 1.0.0
 * @package JBD Events 1.0.0
 * @copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

class PlgSearchJBDEvents extends JPlugin {

    public function __construct( &$subject, $config ) {
        parent::__construct( $subject, $config );
    }

    /**
     * Determine areas searchable by this plugin.
     *
     * @return  array  An array of search areas.
     *
     * @since   1.6
     */
    public function onContentSearchAreas()
    {
        static $areas = array(
            'content' => 'JBusinessDirectory Events'
        );

        return $areas;
    }

    /**
     * Search content (articles).
     * The SQL must return the following fields that are used in a common display
     * routine: href, title, section, created, text, browsernav.
     *
     * @param   string  $text      Target search string.
     * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
     * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
     * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
     *
     * @return  array  Search results.
     *
     * @since   1.6
     */
    public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null){

        $db 		= JFactory::getDBO();
        $fields 	= (array) $this->params->get('searchfields','');
        $orderby 	= $this->params->get('orderby','name');
        $asc_desc 	= $this->params->get('asc_desc','ASC');
        $itemId 	= $this->params->get('Itemid','');
        $browsernav = $this->params->get('browsernav');
        $itemId 	= (empty($itemId) ? '' : '&Itemid='.$itemId);

        JFactory::getLanguage()->load('plg_search_jbdevents', JPATH_ADMINISTRATOR);

        require_once JPATH_SITE.'/components/com_jbusinessdirectory/assets/utils.php';
        require_once JPATH_ADMINISTRATOR.'/components/com_search/helpers/search.php';

        $db = JFactory::getDbo();
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $tag = JFactory::getLanguage()->getTag();

        $results = array();


        $searchDetails = array();
        $searchDetails["orderby"] = $orderby;
        $searchDetails["asc_desc"] = $asc_desc;
        $searchDetails["keyword"] = $text;

        if(empty($text))
            return null;

        foreach($fields as $field){
            if($field == 'jbdevents_events') {
                JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
                $eventsTable = JTable::getInstance("Event", "JTable");
                $list =  $eventsTable->getEventsByCategories($searchDetails);
                foreach ($list as $item) {
                    if ( !searchHelper::checkNoHTML($item, $text, array('name', 'description')) ) continue;
                    $tmp		= new stdClass();
                    $tmp->title = $item->name;
                    $tmp->href 	= JBusinessUtil::getEventLink($item->id, $item->alias);
                    $tmp->text 	= $item->short_description;
                    $tmp->type	= JText::_('JBD_EVENTS_XML_EVENT');
                    $tmp->browsernav = $browsernav;
                    $tmp->section = JText::_('JBD_EVENTS_XML_EVENT');
                    $tmp->created = $item->created;
                    $results[] 	= $tmp;
                }
            }
            elseif($field == 'jbdevents_categories'){
                JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
                $categoriesTable = JTable::getInstance("CompanyCategory", "JTable");
                $list = $categoriesTable->getCategoriesByType($searchDetails, CATEGORY_TYPE_EVENT);
                foreach ($list as $item) {
                    if ( !searchHelper::checkNoHTML($item, $text, array('name', 'description')) ) continue;
                    $tmp		= new stdClass();
                    $tmp->title = $item->name;
                    $tmp->href 	= JBusinessUtil::getEventCategoryLink($item->id, $item->alias);
                    $tmp->text 	= $item->description;
                    $tmp->type	= JText::_('JBD_EVENTS_XML_CATEGORY');
                    $tmp->browsernav = $browsernav;
                    $tmp->section = JText::_('JBD_EVENTS_XML_CATEGORY');
                    $tmp->created = null;
                    $results[] 	= $tmp;
                }
            }
        }
        return $results;
    }
}
