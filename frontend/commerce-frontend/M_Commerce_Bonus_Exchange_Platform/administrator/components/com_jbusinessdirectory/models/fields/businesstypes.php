<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldBusinessTypes extends JFormFieldList {

    protected $type = 'businesstypes';

    // getLabel() left out

    /**
     * Method to get the custom field options.
     * Use the query attribute to supply a query to generate the list.
     *
     * @return  array  The field option objects.
     *
     * @since   11.1
     */
    protected function getOptions()
    {
    	$options = array();
    	$options[] = JHtml::_('select.option', "", JTEXT::_("LNG_ALL_TYPES"));
    
    	// Initialize some field attributes.
    	$key = "id";
    	$value = "name";
    	$translate = $this->element['translate'] ? (string) $this->element['translate'] : false;
    	$query = 'SELECT distinct name as text, id as value FROM #__jbusinessdirectory_company_types where name!="" order by ordering asc';
    
    
        // Get the database object.
    	$db = JFactory::getDBO();
    	$db->setQuery($query);
        
    	$items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items)) {
            foreach ($items as $item) {
                $options[] = JHtml::_('select.option', $item->value, JText::_($item->text));
            }
        }
    
        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);
    
    	return $options;
    }
}