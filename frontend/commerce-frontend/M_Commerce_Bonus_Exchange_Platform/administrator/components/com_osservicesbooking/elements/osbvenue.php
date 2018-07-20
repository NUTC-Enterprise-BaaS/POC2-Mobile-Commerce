<?php
/*------------------------------------------------------------------------
# osbvenue.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
class JFormFieldOsbVenue extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'osbvenue';
	
	function getInput()
	{   
		$venueArr = array();
		if ($this->element['value'] > 0) {
    	    $selectedValue = (int) $this->element['value'] ;
    	} else {
    	    $selectedValue = (int) $this->value ;
    	} 
		$venueArr[] = JHTML::_('select.option','',JText::_('OS_SELECT_VENUE'));
       	$db = JFactory::getDbo();
       	$db->setQuery("Select id as value, concat(address,' ',city,' ',state) as text from #__app_sch_venues where published =  '1' order by address");
       	$venueObjects = $db->loadObjectList();
       	$venueArr = array_merge($venueArr,$venueObjects);
		return JHtml::_('select.genericlist',$venueArr, $this->name, array(
		    'option.text.toHtml' => false ,
		    'option.value' => 'value', 
		    'option.text' => 'text', 
		    'list.attr' => ' class="input-large" ',
		    'list.select' => $selectedValue   		        		
		));	
	}
}