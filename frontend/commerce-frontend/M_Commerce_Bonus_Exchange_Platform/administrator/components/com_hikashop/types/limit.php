<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
class hikashopLimitType{
	function load(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', 'quantity',JText::_('PRODUCT_QUANTITY'));
		$this->values[] = JHTML::_('select.option', 'price',JText::_('PRICE'));
		$this->values[] = JHTML::_('select.option', 'weight',JText::_('PRODUCT_WEIGHT'));
	}
	function display($map,$value){
		$this->load();
		return JHTML::_('select.genericlist',   $this->values, $map, 'class="inputbox" size="1"', 'value', 'text', $value );
	}
}
