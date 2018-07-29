<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="hikashop_userpoints_status">
	<fieldset>
		<legend><?php echo JText::_('POINTS'); ?></legend>
		<span class="hikashop_userpoints_status_value"><?php
			if($points > 0) {
				echo JText::sprintf('USERPOINTS_HAVE_X_POINTS', $points);
			} else {
				echo JText::_('USERPOINTS_NO_POINTS');
			}
		?></span>
<?php
	if(!empty($consume)) {
?>
			<br/><span class="hikashop_userpoints_status_user"><?php
		  if ($use_coupon == 1) {  //判斷選是否的時候要顯示的文字
			  if($points < $consume['points']) { //如果使用者點數小於商品點數
					echo JText::sprintf('USERPOINTS_USER_FOR_DISCOUNT', $points, $discount);
				} else {
			  	echo JText::sprintf('USERPOINTS_USER_FOR_DISCOUNT', $consume['points'], $discount);
				}
			} else {
				echo JText::sprintf('USERPOINTS_USER_FOR_DISCOUNT', 0, $discount);
			}
			?></span>
<?php
			if(!empty($this->plugin_options['ask_no_coupon'])) { ?>
			<br/><span class="hikashop_userpoints_status_question"><span><?php echo JText::_('USERPOINTS_USE_DISCOUNT_QUESTION');?></span> <?php
				echo JHTML::_('hikaselect.booleanlist', 'userpoints_use_coupon', 'onchange="this.form.submit();"', $use_coupon);
			?></span>
<?php }
	}
	$database	= JFactory::getDBO();
	$query = 'SELECT * FROM '.hikashop_table('cart_product').' WHERE cart_id = '.$database->Quote($cart->cart_id);
	$database->setQuery($query);
	$field = $database->loadObjectList();  //抓取cart_product資料表

	$user = JFactory::getUser();
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query
		->select($db->quoteName(array('user_id', 'group_id')))
		->from($db->quoteName('#__user_usergroup_map'))
		->where($db->quoteName('user_id') . '=' . $db->quote($user->id));
	$db->setQuery($query);
	$userGroup = $db->loadObject();
	foreach ($field as $fields) {
		// $arr = 'SELECT * FROM '.hikashop_table('price').' WHERE price_product_id = '.$database->Quote($fields->product_id);
		// $database->setQuery($arr);
		// $price = $database->loadObjectList();
		//業務購買
		if ($userGroup->group_id == '17' || $userGroup->group_id == '18' || $userGroup->group_id == '19' || $userGroup->group_id == '20') {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
				->select('*')
				->from($db->quoteName('#__hikashop_price'))
				->where($db->quoteName('price_product_id') . '=' . $db->quote($fields->product_id))
				->where($db->quoteName('price_access') . ' LIKE '. $db->quote('%17%'));
			$db->setQuery($query);
			$price = $db->loadObjectList();
			} else {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query
					->select('*')
					->from($db->quoteName('#__hikashop_price'))
					->where($db->quoteName('price_product_id') . '=' . $db->quote($fields->product_id))
					->where($db->quoteName('price_access') . ' NOT LIKE '. $db->quote('%17%'));
				$db->setQuery($query);
				$price = $db->loadObjectList();
		}
		foreach ($price as $prices) {
			$totalPoint = 0;
			$originPrice[] = $prices->price_value*$fields->cart_product_quantity;	//原價
			$totalPrice = array_sum($originPrice);
		}
	}
	if($earn_points !== false && !empty($earn_points) || $use_coupon == 0) {
?>		<br/><span class="hikashop_userpoints_earn"><?php echo JText::sprintf('USERPOINTS_EARN_POINTS', $totalPrice); ?></span>
	<?php if ($userGroup->group_id == '17' || $userGroup->group_id == '18' || $userGroup->group_id == '19' || $userGroup->group_id == '20') { ?>
		<br/><span class="hikashop_userpoints_earn"><?php echo JText::sprintf('PV值： ' . $totalPrice * 0.1); ?></span>
	<?php } ?>
<?php
	}
?>
	</fieldset>
</div>
