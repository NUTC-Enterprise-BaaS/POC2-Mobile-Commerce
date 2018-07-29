<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>	<span class="hikashop_product_price_full">
	<?php
	$database	= JFactory::getDBO();
	$query = 'SELECT * FROM '.hikashop_table('cart_product').' WHERE cart_id = '.$database->Quote($this->row->cart_id);
	$database->setQuery($query);
	$field = $database->loadObjectList();  //抓取cart_product資料表

	foreach ($field as $fields) {
		$arr = 'SELECT * FROM '.hikashop_table('price').' WHERE price_product_id = '.$database->Quote($fields->product_id);
		$database->setQuery($arr);
		$price = $database->loadObjectList();

		foreach ($price as $prices) {
			$totalPoint = 0;
			$originPrice = $prices->price_value;	//原價
			$point[] = ($originPrice - $prices->price_value*(($prices->price_value_with_discount/10)))*$fields->cart_product_quantity; //	點數
			$totalPoint = array_sum($point); //總共需付點數
		}
	}
	$plugin = hikashop_import('hikashop', 'userpoints');
	$app = JFactory::getApplication();
	$use_coupon = 1 - (int)$app->getUserState(HIKASHOP_COMPONENT.'.userpoints_no_virtual_coupon', (int)(@$this->plugin_options['checkout_step'] && @$this->plugin_options['default_no_use']));
	//取得是否點數扣款

	if ($plugin->getUserPoints($this->row->user_id)["esp"] == 0) { //如果點數為0 $use_coupon 為0
		$use_coupon = 0;
	}
	if(empty($this->row->prices)){
		echo JText::_('FREE_PRICE');
	}else{
		$first=true;
		echo JText::_('PRICE_BEGINNING');
		foreach($this->row->prices as $price){
			if ($use_coupon == 1) {
				if($first)$first=false;
				else echo JText::_('PRICE_SEPARATOR');
				$discount = $price->price_value_with_discount/10;		//折扣
				$originPrice = $price->unit_price->price_value/$discount;	//原價
				$points = $originPrice - $price->unit_price->price_value;	//扣除的點數
				if(!empty($this->unit) && isset($price->unit_price)){
					echo '<span class="hikashop_product_price">';
					$price = $price->unit_price;
				}
				if(!isset($price->price_currency_id))
				$price->price_currency_id = hikashop_getCurrency();
				echo '<span class="hikashop_product_price">';
				if($this->params->get('price_with_tax')){
					$priceTotal = @$price->price_value_with_tax;	//原價總和
					$pointsTotal = $priceTotal - @$price->price_value_with_tax*$discount; //點數
					echo '<p>' . '原價：' . @$priceTotal . '<p>'; //原價
					echo '<p>'. $discount * 10 . '折 ' . $this->currencyHelper->format(@$price->price_value_with_tax*$discount, $price->price_currency_id) . '</p>';
					echo '需點數付款：' . $pointsTotal;
				}
			} else {
				if($first)$first=false;
				else echo JText::_('PRICE_SEPARATOR');
				if(!empty($this->unit) && isset($price->unit_price)){
					echo '<span class="hikashop_product_price">';
					$price = $price->unit_price;
				}
				if(!isset($price->price_currency_id))$price->price_currency_id = hikashop_getCurrency();
				echo '<span class="hikashop_product_price">';
				if($this->params->get('price_with_tax')){
					$priceTotal = @$price->price_value_with_tax;	//原價
					echo '<p>' . '原價：' . $this->currencyHelper->format($priceTotal, $price->price_currency_id) . '<p>';
				}
			}

			if($this->params->get('price_with_tax')==2){
				echo JText::_('PRICE_BEFORE_TAX');
			}
			if($this->params->get('price_with_tax')==2||!$this->params->get('price_with_tax')){
				echo $this->currencyHelper->format(@$price->price_value,$price->price_currency_id);
			}
			if($this->params->get('price_with_tax')==2){
				echo JText::_('PRICE_AFTER_TAX');
			}
			if($this->params->get('show_original_price','-1')=='-1'){
				$config =& hikashop_config();
				$defaultParams = $config->get('default_params');
				$this->params->set('show_original_price',$defaultParams['show_original_price']);
			}
			if($this->params->get('show_original_price') && !empty($price->price_orig_value)){
				echo JText::_('PRICE_BEFORE_ORIG');
				if($this->params->get('price_with_tax')){
					echo $this->currencyHelper->format($price->price_orig_value_with_tax,$price->price_orig_currency_id);
				}
				if($this->params->get('price_with_tax')==2){
					echo JText::_('PRICE_BEFORE_TAX');
				}
				if($this->params->get('price_with_tax')==2||!$this->params->get('price_with_tax')){
					echo $this->currencyHelper->format($price->price_orig_value,$price->price_orig_currency_id);
				}
				if($this->params->get('price_with_tax')==2){
					echo JText::_('PRICE_AFTER_TAX');
				}
				echo JText::_('PRICE_AFTER_ORIG');
			}
			echo '</span> ';
			if(!empty($this->row->discount)){
				if($this->params->get('show_discount',3)==3){
					$config =& hikashop_config();
					$defaultParams = $config->get('default_params');
					$this->params->set('show_discount',$defaultParams['show_discount']);
				}
				if($this->params->get('show_discount')==1){
					echo '<span class="hikashop_product_discount">'.JText::_('PRICE_DISCOUNT_START');
					if(bccomp($this->row->discount->discount_flat_amount,0,5)!==0){
						if(!$this->unit)
							$this->row->discount->discount_flat_amount = $this->row->discount->discount_flat_amount * $this->row->cart_product_quantity;
						echo $this->currencyHelper->format(-1*$this->row->discount->discount_flat_amount,$price->price_currency_id);
					}else{
						echo -1*$this->row->discount->discount_percent_amount.'%';
					}
					echo JText::_('PRICE_DISCOUNT_END').'</span>';
				}elseif($this->params->get('show_discount')==2){
					echo '<span class="hikashop_product_price_before_discount">'.JText::_('PRICE_DISCOUNT_START');
					if($this->params->get('price_with_tax')){
						echo $this->currencyHelper->format($price->price_value_without_discount_with_tax,$price->price_currency_id);
					}
					if($this->params->get('price_with_tax')==2){
						echo JText::_('PRICE_BEFORE_TAX');
					}
					if($this->params->get('price_with_tax')==2||!$this->params->get('price_with_tax')){
						echo $this->currencyHelper->format($price->price_value_without_discount,$price->price_currency_id);
					}
					if($this->params->get('price_with_tax')==2){
						echo JText::_('PRICE_AFTER_TAX');
					}
					if($this->params->get('show_original_price') && !empty($price->price_orig_value_without_discount_with_tax)){
						echo JText::_('PRICE_BEFORE_ORIG');
						if($this->params->get('price_with_tax')){
							echo $this->currencyHelper->format($price->price_orig_value_without_discount_with_tax,$price->price_orig_currency_id);
						}
						if($this->params->get('price_with_tax')==2){
							echo JText::_('PRICE_BEFORE_TAX');
						}
						if($this->params->get('price_with_tax')==2||!$this->params->get('price_with_tax')){
							echo $this->currencyHelper->format($price->price_orig_value_without_discount,$price->price_orig_currency_id);
						}
						if($this->params->get('price_with_tax')==2){
							echo JText::_('PRICE_AFTER_TAX');
						}
						echo JText::_('PRICE_AFTER_ORIG');
					}
					echo JText::_('PRICE_DISCOUNT_END').'</span>';
				}
			}
		}
		echo JText::_('PRICE_END');
	}
	?></span>
