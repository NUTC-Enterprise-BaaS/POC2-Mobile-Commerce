<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

jimport('joomla.form.formfield');

class JFormFieldVbroomid extends JFormField { 
	protected $type = 'vbroomid';
	
	function getInput() {
		$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		$rooms="";
		$dbo = JFactory::getDBO();
		$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$allvbr=$dbo->loadAssocList();
			foreach($allvbr as $vbr) {
				$rooms.='<option value="'.$vbr['id'].'"'.($this->value == $vbr['id'] ? " selected=\"selected\"" : "").'>'.$vbr['name'].'</option>';
			}
		}
		$html = '<select class="inputbox" name="' . $this->name . '" >';
		$html .= '<option value=""></option>';
		$html .= $rooms;
		$html .='</select>';
		return $html;
    }
}


?>
