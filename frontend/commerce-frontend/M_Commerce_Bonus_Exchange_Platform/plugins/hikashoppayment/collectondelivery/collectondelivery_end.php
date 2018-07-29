<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div class="hikashop_collectondelivery_end" id="hikashop_collectondelivery_end">
	<span class="hikashop_collectondelivery_end_message" id="hikashop_collectondelivery_end_message">
	<?php
			$db = JFactory::getDbo();
 			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('user_id', 'points')));
			$query->from($db->quoteName('#__social_points_history'));
			$query->where($db->quoteName('message') . ' LIKE '. $db->quote('訂單編號 : '.$this->order_number));
			$db->setQuery($query);
			$results = $db->loadObjectList();
			$finalPrice = $this->order->cart->order_subtotal + -($results[0]->points);
		?>
		<?php
		if (-($results[0]->points) < 0){
			echo JText::_('ORDER_IS_COMPLETE').'<br/>'.
			JText::sprintf('AMOUNT_COLLECTED_ON_DELIVERY',(float)$finalPrice.' TWD', $this->order_number).'<br/>'.//$this->order->cart->order_subtotal是沒有扣掉點的價錢
			JText::_('THANK_YOU_FOR_PURCHASE');
		} else {
			echo JText::_('ORDER_IS_COMPLETE').'<br/>'.
			JText::sprintf('AMOUNT_COLLECTED_ON_DELIVERY',$this->amount, $this->order_number).'<br/>'.//$this->order->cart->order_subtotal是沒有扣掉點的價錢
			JText::_('THANK_YOU_FOR_PURCHASE');
		}
		?>
	</span>
</div>
<?php
if(!empty($this->payment_params->return_url)){
	$doc = JFactory::getDocument();
	$doc->addScriptDeclaration("window.hikashop.ready( function() {window.location='".$this->payment_params->return_url."'});");
}
