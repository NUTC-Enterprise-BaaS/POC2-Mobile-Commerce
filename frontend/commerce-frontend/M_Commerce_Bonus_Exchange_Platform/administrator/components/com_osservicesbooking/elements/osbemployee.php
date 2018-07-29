<?php
/*------------------------------------------------------------------------
# osbemployee.php - Ossolution Services Booking
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
class JFormFieldOsbEmployee extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'osbemployee';
	
	function getInput()
	{    
		if ($this->element['value'] > 0) {
    	    $selectedValue = (int) $this->element['value'] ;
    	} else {
    	    $selectedValue = (int) $this->value ;
    	} 
		$empArr[] = JHTML::_('select.option','',JText::_('OS_EMPLOYEE'));
       	$db = JFactory::getDbo();
       	$db->setQuery("Select id as value, employee_name as text from #__app_sch_employee where published =  '1' order by employee_name");
       	$employeeObjects = $db->loadObjectList();
       	$empArr = array_merge($empArr,$employeeObjects);
		return JHtml::_('select.genericlist',$empArr, $this->name, array(
		    'option.text.toHtml' => false ,
		    'option.value' => 'value', 
		    'option.text' => 'text', 
		    'list.attr' => ' class="inputbox" ',
		    'list.select' => $selectedValue   		        		
		));	
	}
}