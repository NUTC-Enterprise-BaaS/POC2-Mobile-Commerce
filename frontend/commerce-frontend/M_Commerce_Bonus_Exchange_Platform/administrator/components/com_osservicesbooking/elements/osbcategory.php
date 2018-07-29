<?php
/*------------------------------------------------------------------------
# category.php - Ossolution Services Booking
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
class JFormFieldOsbCategory extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'osbcategory';
	
	function getInput()
	{    
		if ($this->element['value'] > 0) {
    	    $selectedValue = (int) $this->element['value'] ;
    	} else {
    	    $selectedValue = (int) $this->value ;
    	} 
		$catArr[] = JHTML::_('select.option','',JText::_('OS_CATEGORY_NAME'));
       	$db = JFactory::getDbo();
       	$db->setQuery("Select id as value, category_name as text from #__app_sch_categories where published =  '1' order by category_name");
       	$catObjects = $db->loadObjectList();
       	$catArr = array_merge($catArr,$catObjects);
		return JHtml::_('select.genericlist',$catArr, $this->name, array(
		    'option.text.toHtml' => false ,
		    'option.value' => 'value', 
		    'option.text' => 'text', 
		    'list.attr' => ' class="inputbox" ',
		    'list.select' => $selectedValue   		        		
		));	
	}
}