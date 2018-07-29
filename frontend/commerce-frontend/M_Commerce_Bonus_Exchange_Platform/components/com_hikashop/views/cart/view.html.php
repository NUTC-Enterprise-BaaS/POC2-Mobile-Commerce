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
class CartViewCart extends HikaShopView {
	var $type = 'main';
	var $ctrl= 'cart';
	var $nameListing = 'CARTS';
	var $nameForm = 'CARTS';
	var $icon = 'cart';
	var $module=false;
	function display($tpl = null,$params=array()){

		$this->paramBase = HIKASHOP_COMPONENT.'.'.$this->getName();
		$function = $this->getLayout();
		$this->params =& $params;
		if($function!='cart' && JRequest::getInt('popup') && empty($_COOKIE['popup']) && JRequest::getVar('tmpl')!='component'){
			$cartHelper = hikashop_get('helper.cart');
			$cartHelper->getJS($this->init());
			$doc = JFactory::getDocument();
			$js = '
			window.hikashop.ready( function() {
				SqueezeBox.fromElement(\'hikashop_notice_box_trigger_link\',{parse: \'rel\'});
			});
			';
			$doc->addScriptDeclaration("\n<!--\n".$js."\n//-->\n");
		}
		if(method_exists($this,$function)) $this->$function();
		parent::display($tpl);
	}

	function showcart(){
		$app = JFactory::getApplication();
		$menus	= $app->getMenu();
		$menu	= $menus->getActive();
		global $Itemid;
		if(empty($menu) && !empty($Itemid)) {
			$menus->setActive($Itemid);
			$menu = $menus->getItem($Itemid);
		}

		if(isset($menu->params) && is_object( $menu->params )) {
			$cart_type = $menu->params->get('cart_type');
		}

		if(!isset($cart_type) || $cart_type == null || empty($cart_type)) {
			if(isset($this->params) && is_object($this->params)) {
				$cart_type = $this->params->get('cart_type','cart');
			} else {
				$cart_type = JRequest::getVar('cart_type','cart');
			}
		}

		$image = hikashop_get('helper.image');
		$this->assignRef('image',$image);
		$database	= JFactory::getDBO();
		$config =& hikashop_config();
		$currencyClass = hikashop_get('class.currency');
		$cartClass = hikashop_get('class.cart');
		$productClass = hikashop_get('class.product');
		$main_currency = (int)$config->get('main_currency',1);
		$currency_id = hikashop_getCurrency();
		if($config->get('tax_zone_type','shipping')=='billing'){
			$zone_id = hikashop_getZone('billing');
		}else{
			$zone_id = hikashop_getZone('shipping');
		}
		$discount_before_tax = (int)$config->get('discount_before_tax',0);

		$cart_id = hikashop_getCID();
		if($cart_id == 0)
			$cart_id = JRequest::getInt('cart_id','');
		if(empty($cart_id) || $cart_id == 0){
			$cart_id = $app->getUserState( HIKASHOP_COMPONENT.'.'.$cart_type.'_id', 0, 'int' );
			if($cart_id == 0){
				$session = JFactory::getSession();
				$session_id = '';
				if($session->getId()){
					$session_id = $session->getId();
				}
				if(hikashop_loadUser() == null){
					$filter = 'a.session_id = '.$database->Quote($session_id);
				}else{
					$userInfo = hikashop_loadUser(true);
					$filter = '(a.user_id = '.(int)$userInfo->user_cms_id.' OR a.session_id = '.$database->Quote($session_id).')';
				}
				$query = 'SELECT a.* FROM '.hikashop_table('cart').' AS a WHERE a.cart_type = '.$database->Quote($cart_type).' AND '.$filter;
				$database->setQuery($query);
				$userCarts = $database->loadObjectList();
				$ok = 0;
				$old_modified = 0;
				foreach($userCarts as $userCart){
					if((int)$userCart->cart_current == 1){
						$cart_id = (int)$userCart->cart_id;
						$ok = 1;
					}else if($ok == 0){
						if($old_modified <= $userCart->cart_modified){
							$cart_id = $userCart->cart_id;
							$old_modified = $userCart->cart_modified;
						}
					}
				}
			}
		}

		$popup = hikashop_get('helper.popup');
		$this->assignRef('popup',$popup);
		$query = 'SELECT * FROM '.hikashop_table('cart').' AS a WHERE a.cart_id = '.$cart_id;
		$database->setQuery($query);
		$cartVal = $database->loadObject();

		$rows = new stdClass();
		if($cartVal == null){
			if($config->get('enable_multicart',0)){
				global $Itemid;
				$url = hikashop_contentLink('cart&task=showcarts&cart_type='.$cart_type.'&Itemid='.$Itemid);
				$app->redirect($url);
			}else{
				$cartVal = $cartClass->initCart();
				$cartVal->cart_share = 'no';
				$cartVal->user_id = hikashop_loadUser();
			}
		}

		JRequest::setVar('cart_type', $cartVal->cart_type);
		JRequest::setVar('cart_id',$cart_id);
		$app->setUserState( HIKASHOP_COMPONENT.'.'.$cartVal->cart_type.'_id', $cart_id, 'int' );
		$rows = $cartClass->get($cart_id,false,$cartVal->cart_type);

		$user = hikashop_loadUser();

		$confirmedStatus = $config->get('invoice_order_statuses','confirmed,shipped');
		if(empty($confirmedStatus)) $confirmedStatus = 'confirmed,shipped';
		$confirmedStatus = explode(',', trim($confirmedStatus, ','));
		foreach($confirmedStatus as &$status) {
			$status = $database->Quote($status);
		}
		unset($status);

		if($cartVal->cart_type == 'wishlist'){
			$query='SELECT a.*,b.* FROM '.hikashop_table('order').' AS a LEFT JOIN '.hikashop_table('order_product').' AS b ON a.order_id=b.order_id WHERE a.order_status IN ('.implode(',',$confirmedStatus).') AND b.order_product_wishlist_id ='.(int)$cart_id;
			$database->setQuery($query);
			$buyers = $database->loadObjectList();

			foreach($buyers as $j => $buyer){
				foreach($rows as $k => $row){
					if($row->product_id == $buyer->product_id){
						if($buyer->order_user_id == $user){
							$rows[$k]->bought[$j] = JText::_('ORDER_NUMBER').": ".$buyer->order_id.' - '.$buyer->order_product_quantity.' '.JText::_('HIKASHOP_ITEM');
						}else{
							$userClass = hikashop_get('class.user');
							$user = $userClass->get($buyer->order_user_id);
							if(!empty($user->username)){
								$rows[$k]->bought[$j] = $user->username.' - '.$buyer->order_product_quantity.' '.JText::_('HIKASHOP_ITEM');
							}else if(!empty($user->user_email)){
								$rows[$k]->bought[$j] = $user->user_email.' - '.$buyer->order_product_quantity.' '.JText::_('HIKASHOP_ITEM');
							}else{
								$rows[$k]->bought[$j] = JText::_('HKASHOP_USER_ID').": ".$buyer->order_user_id.' - '.$buyer->order_product_quantity.' '.JText::_('HIKASHOP_ITEM');
							}
						}
						$rows[$k]->cart_product_quantity -= $buyer->order_product_quantity;
						if($rows[$k]->cart_product_quantity < 0)
							$rows[$k]->cart_product_quantity = 0;
					}
				}
			}
		}

		if( $cartVal->cart_share == 'registered'){
			$cartVal->display = 'registered';
		}
		else if($cartVal->cart_share == 'public'){
			$cartVal->display = 'public';
		}
		else if(in_array($user,explode(',',$cartVal->cart_share))){
			$cartVal->display = $cartVal->cart_share;
		}
		else if(JRequest::getString('link','link') == $cartVal->cart_share || strlen($cartVal->cart_share) == 20){
			$cartVal->display = $cartVal->cart_share;
		}
		else{
			$cartVal->display = 'main';
		}
		if($cart_type == 'cart'){
			$cartVal->display = 'main';
		}

		$total = new stdClass();
		if(!empty($rows)){
			$variants = false;
			$ids = array();
			foreach($rows as $k => $row){
				$ids[]=$row->product_id;
				if($row->product_type=='variant'){
					$variants = true;
					foreach($rows as $k2 => $row2){
						if($row->product_parent_id==$row2->product_id){
							$rows[$k2]->variants[]=&$rows[$k];
						}
					}
				}
			}
			if($variants){
				$this->selected_variant_id = 0;
				$query = 'SELECT a.*,b.* FROM '.hikashop_table('variant').' AS a LEFT JOIN '.hikashop_table('characteristic').' AS b ON a.variant_characteristic_id=b.characteristic_id WHERE a.variant_product_id IN ('.implode(',',$ids).') ORDER BY a.ordering ASC,b.characteristic_value ASC';
				$database->setQuery($query);
				$characteristics = $database->loadObjectList();
				if(!empty($characteristics)){
					foreach($rows as $k => $row){
						$element =& $rows[$k];
						$product_id=$row->product_id;
						if($row->product_type=='variant'){
							continue;
						}
						$mainCharacteristics = array();
						foreach($characteristics as $characteristic){
							if($product_id==$characteristic->variant_product_id){
								$mainCharacteristics[$product_id][$characteristic->characteristic_parent_id][$characteristic->characteristic_id]=$characteristic;
							}
							if(!empty($element->options)){
								foreach($element->options as $k => $optionElement){
									if($optionElement->product_id==$characteristic->variant_product_id){
										$mainCharacteristics[$optionElement->product_id][$characteristic->characteristic_parent_id][$characteristic->characteristic_id]=$characteristic;
									}
								}
							}
						}
						if(!empty($element->variants)){
							$this->addCharacteristics($element,$mainCharacteristics,$characteristics);
						}
						if(!empty($element->options)){
							foreach($element->options as $k => $optionElement){
								if(!empty($optionElement->variants)){
									$this->addCharacteristics($element->options[$k],$mainCharacteristics,$characteristics);
								}
							}
						}
					}
				}
			}
			$product_quantities = array();
			foreach($rows as $row){
				if(empty($product_quantities[$row->product_id])){
					$product_quantities[$row->product_id] = (int)@$row->cart_product_quantity;
				}else{
					$product_quantities[$row->product_id]+=(int)@$row->cart_product_quantity;
				}
			}
			foreach($rows as $k => $row){
				$rows[$k]->cart_product_total_quantity = $product_quantities[$row->product_id];
			}
			$currencyClass->getPrices($rows,$ids,$currency_id,$main_currency,$zone_id,$discount_before_tax);
			foreach($rows as $k => $row){
				if(!empty($row->variants)){
					foreach($row->variants as $k2 => $variant){
						$productClass->checkVariant($rows[$k]->variants[$k2],$row);
					}
				}
			}
			$cids = array();
			foreach($rows as $k => $row){
				$currencyClass->calculateProductPriceForQuantity($rows[$k]);

				if($cart_type!='wishlist'){
					if($row->cart_product_quantity == 0){
						$rows[$k]->hide = 1;
					}
				}else if($row->product_type=='variant' && !empty($row->cart_product_parent_id) && isset($rows[$row->cart_product_parent_id])){
					$rows[$row->cart_product_parent_id]->hide = 1;
				}
				$cids[] = (int)$row->product_id;
			}
			$total=new stdClass();
			$currencyClass->calculateTotal($rows,$total,$currency_id);

			$full = $cartClass->loadFullCart(true,true,true);
			foreach($rows as $k => $row){
				if(isset($full->products[$k]->images))
					$rows[$k]->images = $full->products[$k]->images;
			}

			$mainIds = array();
			foreach($rows as $product){
				if($product->product_parent_id == '0')
					$mainIds[]=(int)$product->product_id;
				else
					$mainIds[]=(int)$product->product_parent_id;
			}
			$query = 'SELECT a.*, b.* FROM '.hikashop_table('product_category').' AS a LEFT JOIN '.hikashop_table('category').' AS b ON a.category_id = b.category_id WHERE a.product_id IN('.implode(',',$mainIds).') ORDER BY a.ordering ASC';
			$database->setQuery($query);
			$categories = $database->loadObjectList();
			$quantityDisplayType = hikashop_get('type.quantitydisplay');
			foreach($rows as $k => $row){
				if($row->product_parent_id != 0 && $row->cart_product_parent_id != '0'){
					$row->product_quantity_layout = $rows[$row->cart_product_parent_id]->product_quantity_layout;
					$row->product_min_per_order = $rows[$row->cart_product_parent_id]->product_min_per_order;
					$row->product_max_per_order = $rows[$row->cart_product_parent_id]->product_max_per_order;
				}
				if(empty($row->product_quantity_layout) || $row->product_quantity_layout == 'inherit'){
					$categoryQuantityLayout = '';
					if(!empty($categories) ) {
						foreach($categories as $category) {
							if($category->product_id == $row->product_id && !empty($category->category_quantity_layout) && $quantityDisplayType->check($category->category_quantity_layout, $app->getTemplate())) {
								$categoryQuantityLayout = $category->category_quantity_layout;
								break;
							}
						}
					}
				}
				if(!empty($row->product_quantity_layout) &&  $row->product_quantity_layout != 'inherit'){
					$qLayout = $row->product_quantity_layout;
				}elseif(!empty($categoryQuantityLayout) && $categoryQuantityLayout != 'inherit'){
					$qLayout = $categoryQuantityLayout;
				}else{
					$qLayout = $config->get('product_quantity_display','show_default');
				}
				$rows[$k]->product_quantity_layout = $qLayout;
			}
		}

		$js="function checkAll(){
			var toCheck = document.getElementById('hikashop_cart_product_listing').getElementsByTagName('input');
			for (i = 0 ; i < toCheck.length ; i++) {
				if (toCheck[i].type == 'checkbox') {
					toCheck[i].checked = true;
				}
			}
		}";

		if(!HIKASHOP_PHP5) {
			$doc =& JFactory::getDocument();
		} else {
			$doc = JFactory::getDocument();
		}
		$doc->addScriptDeclaration( "<!--\n".$js."\n//-->\n" );

		$this->assignRef('total',$total);
		$this->assignRef('cartVal',$cartVal);
		$this->assignRef('rows',$rows);
		$this->assignRef('config',$config);
		$cart=hikashop_get('helper.cart');
		$this->assignRef('cart',$cart);
		$this->assignRef('currencyHelper',$currencyClass);
		$cart->cartCount(true);

		$params = new hikaParameter;
		$default_params = $config->get('default_params');
		foreach($default_params as $k => $v){
			$params->set($k,$v);
		}
		$params->set('show_delete',$config->get('checkout_cart_delete',1));
		$this->assignRef('params',$params);

		ob_start();
		$cart->getJS($url,false);
		$notice_html = ob_get_clean();
		$this->assignRef('notice_html',$notice_html);
		if(hikashop_level(2)){
			$fieldsClass=hikashop_get('class.field');
			$this->assignRef('fieldsClass',$fieldsClass);
		}
		JHTML::_('behavior.tooltip');
		if($cart_type == 'cart'){
			$title = JText::_('CARTS');
		}else{
			$title = JText::_('WISHLISTS');
		}
		hikashop_setPageTitle($title);
	}
	function showcarts(){
		$app = JFactory::getApplication();
		$config = hikashop_config();
		$menus	= $app->getMenu();
		$menu	= $menus->getActive();
		global $Itemid;
		if(empty($menu)){
			if(!empty($Itemid)){
				$menus->setActive($Itemid);
				$menu = $menus->getItem($Itemid);
			}
		}

		if (is_object( $menu) && is_object( $menu->params )) {
			$cart_type = $menu->params->get('cart_type');
		}
		if(!empty($cart_type)){
			JRequest::setVar('cart_type',$cart_type);
		}else{
			$cart_type = JRequest::getString('cart_type','cart');
			if(!in_array($cart_type, array('cart','wishlist'))) $cart_type = 'cart';
		}

		$this->assignRef('cart_type', $cart_type);

		$pageInfo = new stdClass();
		$pageInfo->filter = new stdClass();
		$pageInfo->filter->order = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->filter->order->value = $app->getUserStateFromRequest( $this->paramBase.".filter_order", 'filter_order',	'a.cart_id','cmd' );
		$pageInfo->filter->order->dir	= $app->getUserStateFromRequest( $this->paramBase.".filter_order_Dir", 'filter_order_Dir',	'desc',	'word' );
		$pageInfo->search = $app->getUserStateFromRequest( $this->paramBase.".search", 'search', '', 'string' );
		$pageInfo->search = JString::strtolower(trim($pageInfo->search));
		$pageInfo->limit->start = $app->getUserStateFromRequest( $this->paramBase.'.limitstart', 'limitstart', 0, 'int' );
		$oldValue = $app->getUserState($this->paramBase.'.list_limit');
		if(empty($oldValue)){
			$oldValue =$app->getCfg('list_limit');
		}
		$pageInfo->limit->value = $app->getUserStateFromRequest( $this->paramBase.'.list_limit', 'limit', $app->getCfg('list_limit'), 'int' );
		if($oldValue!=$pageInfo->limit->value){
			$pageInfo->limit->start = 0;
			$app->setUserState($this->paramBase.'.limitstart',0);
		}

		$database = JFactory::getDBO();
		$searchMap = array('a.cart_id','a.cart_name','a.cart_type');

		if(hikashop_loadUser() == null){
			global $Itemid;
			$url = '';
			if(!empty($Itemid)){
				$url='&Itemid='.$Itemid;
			}
			if(!HIKASHOP_J16){
				$url = 'index.php?option=com_user&view=login'.$url;
			}else{
				$url = 'index.php?option=com_users&view=login'.$url;
			}
			if($config->get('enable_multicart','0'))
				$app->redirect(JRoute::_($url.'&return='.urlencode(base64_encode(hikashop_currentUrl('',false))),false));
			else
				$app->redirect(JRoute::_($url.'&return='.base64_encode(hikashop_completeLink('cart&task=showcart&cart_type='.$cart_type.'&Itemid='.$Itemid,false,false,true)),false));
			return false;
		}

		$user = hikashop_loadUser(true);
		if(isset($user->user_cms_id))
			$user->id = $user->user_cms_id;
		else {
			if(empty($user)) $user = new stdClass();
			$user->id = 0 ;
		}
		$session = JFactory::getSession();
		if($session->getId()){
			$user->session = $session->getId();
		}else{
			$user->session = '';
		}
		if(hikashop_loadUser() == null){
			$filters = array('a.session_id='.$database->Quote($user->session).' AND a.cart_type ='.$database->quote($cart_type));
		}else{
			$filters = array('(a.user_id='.(int)$user->id.' OR a.session_id='.$database->Quote($user->session).') AND a.cart_type ='.$database->quote($cart_type));
		}
		$groupBy = 'GROUP BY a.cart_id';
		$order = '';
		if(!empty($pageInfo->filter->order->value)){
			$order = 'ORDER BY a.cart_id ASC';
		}
		if(!empty($pageInfo->search)){
			$searchVal = '\'%'.hikashop_getEscaped(JString::strtolower(trim($pageInfo->search)),true).'%\'';
			$filter = implode(" LIKE $searchVal OR ",$searchMap)." LIKE $searchVal";
			$filters[] =  $filter;
		}
		$from = 'FROM '.hikashop_table('cart').' AS a';
		$cartProduct = 'LEFT JOIN '.hikashop_table('cart_product').' AS b ON a.cart_id=b.cart_id';
		$where = 'WHERE ('.implode(') AND (',$filters).') AND a.cart_type ='.$database->quote($cart_type);
		$query = $from.' '.$where.' '.$groupBy.' '.$order; //'.$cartProduct.'
		$database->setQuery('SELECT a.* '.$query);
		$rows = $database->loadObjectList();
		$database->setQuery('SELECT COUNT(*) '.$from.' '.$where);
		$currencyClass = hikashop_get('class.currency');
		$this->assignRef('currencyHelper',$currencyClass);


		$module = hikashop_get('helper.module');
		$module->initialize($this);
		$currencyClass = hikashop_get('class.currency');
		$class = hikashop_get('class.cart');
		$productClass = hikashop_get('class.product');
		$main_currency = (int)$config->get('main_currency',1);
		$currency_id = hikashop_getCurrency();
		if($config->get('tax_zone_type','shipping')=='billing'){
			$zone_id = hikashop_getZone('billing');
		}else{
			$zone_id = hikashop_getZone('shipping');
		}
		$discount_before_tax = (int)$config->get('discount_before_tax',0);

		$cids = array();
		foreach($rows as $row){
			if($row->cart_id != null)
				$cids[] = $row->cart_id;
		}
		$filters = '';
		$filters = array('a.cart_id IN('.implode(",",$cids).')');
		$order = '';
		if(!empty($pageInfo->filter->order->value)){
			$order = ' ORDER BY cart_id ASC';
		}

		$product = ' LEFT JOIN '.hikashop_table('product').' AS b ON a.product_id=b.product_id';
		$query = 'FROM '.hikashop_table('cart_product').' AS a '.$product.' WHERE ('.implode(') AND (',$filters).') '.$order;
		$database->setQuery('SELECT a.*,b.* '.$query);
		if(!empty($cids)){
			$products = $database->loadObjectList();

			$ids = array();
			foreach($products as $row){
				$ids[] = $row->product_id;
			}
			$row_1 = 0;
			foreach($products as $k => $row){
				$currencyClass->getPrices($row,$ids,$currency_id,$main_currency,$zone_id,$discount_before_tax);

				if(!isset($row->prices[0]->price_value)){
					if(isset($row_1->prices[0]))
						$row->prices[0] = $row_1->prices[0];
				}
				$products[$k]->hide = 0;
				if($row->product_type == 'variant'){
					$l = --$k;
					if(isset($products[$l])){
						if(!isset($products[$l]) || !is_object($products[$l])){
							$products[$l] = new stdClass();
						}
						$products[$l]->hide = 1;
					}
				}
				$row_1 = $row;
			}

			$currentId = 0;
			$values = null;
			$price = 0;
			$price_with_tax = 0;
			$quantity = 0;
			$currency = hikashop_getCurrency();
			foreach($products as $product){
				if(isset($product->cart_id) && isset($product->product_id)){
					if($product->cart_id != $currentId){
						$price = 0;
						$price_with_tax = 0;
						$quantity = 0;
						$currentId = $product->cart_id;
						if(isset($product->prices[0]->price_currency_id))
							$currency = $product->prices[0]->price_currency_id;
					}

					if(isset($product->prices[0])){
						$price += $product->cart_product_quantity * $product->prices[0]->price_value;
					}
					if(isset($product->prices[0]->price_value_with_tax)){
						$price_with_tax += $product->cart_product_quantity * $product->prices[0]->price_value_with_tax;
					}
					if(!isset($product->prices[0]->price_value)){
						$variant = new stdClass();
						$variant->product_parent_id = $product->product_parent_id;
						$variant->quantity = $product->cart_product_quantity;
					}
					if(isset($variant) && isset($product->prices[0]) && $product->product_id == $variant->product_parent_id){
						$price += $variant->quantity * $product->prices[0]->price_value;
						$price_with_tax += $variant->quantity * $product->prices[0]->price_value_with_tax;
					}
					$quantity += $product->cart_product_quantity;
					if(!isset($values[$currentId])) $values[$currentId] = new stdClass();
					$values[$currentId]->price = $price;
					$values[$currentId]->price_with_tax = isset($price_with_tax)?$price_with_tax:$price;
					$values[$currentId]->quantity = $quantity;
					$values[$currentId]->currency = $currency;
				}
			}
			$totalCart = 0;
			$limit = 0;
			foreach($rows as $k => $row){
				if($limit >= (int)$pageInfo->limit->start && $limit <(int)$pageInfo->limit->value && isset($values[$row->cart_id]) && $values[$row->cart_id] != null){
					$rows[$k]->price = $values[$row->cart_id]->price;
					$rows[$k]->price_with_tax = $values[$row->cart_id]->price_with_tax;
					$rows[$k]->quantity = $values[$row->cart_id]->quantity;
					$rows[$k]->currency = $values[$row->cart_id]->currency;
					$totalCart++;
				}else{
					unset($rows[$k]);
					$limit--;
				}
				$limit++;
			}
		}

		$pageInfo->elements = new stdClass();
		$pageInfo->elements->total = count($rows);
		if(!empty($pageInfo->search)){
			$rows = hikashop_search($pageInfo->search,$rows,'cart_id');
		}
		$pageInfo->elements->page = count($rows);
		if(!$pageInfo->elements->page){
			if(hikashop_loadUser()!= null){
				$app = JFactory::getApplication();
				if($cart_type == 'cart')
					$app->enqueueMessage(JText::_('HIKA_NO_CARTS_FOUND'));
				else
					$app->enqueueMessage(JText::_('HIKA_NO_WISHLISTS_FOUND'));
			}
		}
		jimport('joomla.html.pagination');
		$pagination = hikashop_get('helper.pagination', $pageInfo->elements->total, $pageInfo->limit->start, $pageInfo->limit->value);
		$pagination->hikaSuffix = '';
		$this->assignRef('pagination',$pagination);
		$this->assignRef('pageInfo',$pageInfo);

		$cart=hikashop_get('helper.cart');
		$this->assignRef('cart',$cart);
		$this->assignRef('config',$config);
		$this->assignRef('carts',$rows);
		if($cart_type == 'cart'){
			$title = JText::_('CARTS');
		}else{
			$title = JText::_('WISHLISTS');
		}
		hikashop_setPageTitle($title);
	}
	function printcart(){
		$this->showcart();
	}
	function _getCheckoutURL(){
		global $Itemid;
		$url_itemid='';
		if(!empty($Itemid)){
			$url_itemid='&Itemid='.$Itemid;
		}
		return hikashop_completeLink('checkout'.$url_itemid,false,true);
	}

	function init($cart=false){
		$config =& hikashop_config();
		$url = $config->get('redirect_url_after_add_cart','stay_if_cart');
		switch($url){
			case 'checkout':
				$url = $this->_getCheckoutURL();
				break;
			case 'stay_if_cart':
				$url='';
				if(!$cart){
					$url = $this->_getCheckoutURL();
					break;
				}
			case 'ask_user':
			case 'stay':
				$url='';
			case '':
			default:
				if(empty($url)){
					$url = hikashop_currentURL('return_url',false);
				}
				break;
		}
		return urlencode($url);
	}
	function addCharacteristics(&$element,&$mainCharacteristics,&$characteristics){
		$element->characteristics = @$mainCharacteristics[$element->product_id][0];
		if(!empty($element->characteristics) && is_array($element->characteristics)){
			foreach($element->characteristics as $k => $characteristic){
				if(!empty($mainCharacteristics[$element->product_id][$k])){
					$element->characteristics[$k]->default=end($mainCharacteristics[$element->product_id][$k]);
				}else{
					$app =& JFactory::getApplication();
					$app->enqueueMessage('The default value of one of the characteristics of that product isn\'t available as a variant. Please check the characteristics and variants of that product');
				}
			}
		}
		if(!empty($element->variants)){
			foreach($characteristics as $characteristic){
				foreach($element->variants as $k => $variant){
					if($variant->product_id==$characteristic->variant_product_id){
						$element->variants[$k]->characteristics[$characteristic->characteristic_parent_id]=$characteristic;
						$element->characteristics[$characteristic->characteristic_parent_id]->values[$characteristic->characteristic_id]=$characteristic;
						if($this->selected_variant_id && $variant->product_id==$this->selected_variant_id){
							$element->characteristics[$characteristic->characteristic_parent_id]->default=$characteristic;
						}
					}
				}
			}
			if(isset($_REQUEST['hikashop_product_characteristic'])){
				if(is_array($_REQUEST['hikashop_product_characteristic'])){
					JArrayHelper::toInteger($_REQUEST['hikashop_product_characteristic']);
					$chars = $_REQUEST['hikashop_product_characteristic'];
				}else{
					$chars = JRequest::getCmd('hikashop_product_characteristic','');
					$chars = explode('_',$chars);
				}
				if(!empty($chars)){
					foreach($element->variants as $k => $variant){
						$chars = array();
						foreach($variant->characteristics as $val){
							$i = 0;
							$ordering = @$element->characteristics[$val->characteristic_parent_id]->ordering;
							while(isset($chars[$ordering])&& $i < 30){
								$i++;
								$ordering++;
							}
							$chars[$ordering] = $val;
						}
						ksort($chars);
						$element->variants[$k]->characteristics=$chars;
						$variant->characteristics=$chars;
						$choosed = true;
						foreach($variant->characteristics as $characteristic){
							$ok = false;
							foreach($chars as $k => $char){
								if(!empty($char)){
									if($characteristic->characteristic_id==$char){
										$ok = true;
										break;
									}
								}
							}
							if(!$ok){
								$choosed=false;
							}else{
								$element->characteristics[$characteristic->characteristic_parent_id]->default=$characteristic;
							}
						}
						if($choosed){
							break;
						}
					}
				}
			}
			foreach($element->variants as $k => $variant){
				$temp=array();
				foreach($element->characteristics as $k2 => $characteristic2){
					if(!empty($variant->characteristics)){
						foreach($variant->characteristics as $k3 => $characteristic3){
							if($k2==$k3){
								$temp[$k3]=$characteristic3;
								break;
							}
						}
					}
				}
				$element->variants[$k]->characteristics=$temp;
			}
		}
	}
}
?>
