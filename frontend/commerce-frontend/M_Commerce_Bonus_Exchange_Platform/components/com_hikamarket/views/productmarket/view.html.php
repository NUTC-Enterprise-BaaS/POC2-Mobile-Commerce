<?php
/**
 * @package    HikaMarket for Joomla!
 * @version    1.7.0
 * @author     Obsidev S.A.R.L.
 * @copyright  (C) 2011-2016 OBSIDEV. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
class productmarketViewproductmarket extends hikamarketView {

	protected $ctrl = 'product';
	protected $icon = 'product';
	protected $triggerView = true;

	public function display($tpl = null, $params = array()) {
		$this->params =& $params;
		$fct = $this->getLayout();
		if(method_exists($this, $fct)) {
			if($this->$fct() === false)
				return;
		}
		parent::display($tpl);
	}

	public function listing($tpl = null) {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$ctrl = '';
		$this->paramBase = HIKAMARKET_COMPONENT.'.'.$this->getName().'.listing';

		$vendor = hikamarket::loadVendor(true, false);
		$this->assignRef('vendor', $vendor);

		$config = hikamarket::config();
		$this->assignRef('config', $config);

		$this->loadRef(array(
			'toggleClass' => 'helper.toggle',
			'imageHelper' => 'shop.helper.image',
			'currencyHelper' => 'shop.class.currency',
			'childdisplayType' => 'shop.type.childdisplay',
			'shopCategoryType' => 'type.shop_category',
		));

		$type = JRequest::getString('type', 'product,vendor,manufacturer');
		$getRoot = true;

		$cid = hikamarket::getCID();
		if(empty($cid))
			$cid = 1;
		$this->assignRef('cid', $cid);

		$manage = hikamarket::acl('product/edit');
		$this->assignRef('manage', $manage);

		$product_action_publish = hikamarket::acl('product/edit/published');
		$product_action_delete = hikamarket::acl('product/delete');
		$product_action_copy = ($vendor->vendor_id == 0 || $vendor->vendor_id == 1) && hikamarket::acl('product/copy');
		$product_actions = $product_action_publish || $product_action_delete || $product_action_copy;
		$this->assignRef('product_action_publish', $product_action_publish);
		$this->assignRef('product_action_delete', $product_action_delete);
		$this->assignRef('product_action_copy', $product_action_copy);
		$this->assignRef('product_actions', $product_actions);

		global $Itemid;
		$url_itemid = '';
		if(!empty($Itemid))
			$url_itemid = '&Itemid='.$Itemid;
		$this->assignRef('Itemid', $Itemid);

		$cfg = array(
			'table' => 'shop.product',
			'main_key' => 'product_id',
			'order_sql_value' => 'product.product_id'
		);

		$rootCategory = 0;
		$vendorCategories = 0;
		$vendorClass = hikamarket::get('class.vendor');
		$rootCategory = $vendorClass->getRootCategory($vendor);
		$extra_categories = $vendorClass->getExtraCategories($vendor);
		if(!empty($extra_categories))
			$vendorCategories = array_merge(array($rootCategory), $extra_categories);

		if($vendor->vendor_id == 1) {
			$vendorType = hikamarket::get('type.filter_vendor');
			$this->assignRef('vendorType', $vendorType);
		}
		if(empty($rootCategory))
			$rootCategory = 1;
		if(empty($vendorCategories))
			$vendorCategories = $rootCategory;
		$this->assignRef('rootCategory', $rootCategory);
		$this->assignRef('vendorCategories', $vendorCategories);

		$category_id = $rootCategory;
		$this->assignRef('category_id', $category_id);

		$category_explorer = $config->get('show_category_explorer', 1);
		if(isset($this->category_explorer))
			$category_explorer = $this->category_explorer;

		if($category_explorer && (empty($cid) || $cid == 1)) {
			$cid = (int)$app->getUserState(HIKAMARKET_COMPONENT.'.product.listing_cid');
			if(empty($cid))
				$cid = 1;
		}

		$default_sort_value = trim($config->get('product_listing_default_sort_value', $cfg['order_sql_value']));
		if(empty($default_sort_value))
			$default_sort_value = $cfg['order_sql_value'];
		$default_sort_dir = trim($config->get('product_listing_default_sort_dir', 'asc'));
		if(empty($default_sort_dir) || !in_array($default_sort_dir, array('asc', 'desc')))
			$default_sort_dir = 'asc';

		$pageInfo = $this->getPageInfo($default_sort_value, $default_sort_dir);

		$pageInfo->selectedType = $app->getUserStateFromRequest($this->paramBase.'.filter_type', 'filter_type', (int)$config->get('default_filter_type_product_listing', 0), 'int');
		$pageInfo->filter->vendors = $app->getUserStateFromRequest($this->paramBase.'.filter_vendors', 'filter_vendors', 0, 'int');
		$pageInfo->filter->filter_product_type = false;
		if(!hikamarket::level(2) || $vendor->vendor_id == 1 || !empty($this->producttype_selector)) {
			$this->loadRef(array('productType' => 'shop.type.product'));
			$pageInfo->filter->filter_product_type = $app->getUserStateFromRequest($this->paramBase.'.filter_product_type', 'filter_product_type', 'main', 'word');
		}

		$filters = array(
			'main' => 'product.product_parent_id = 0',
			'product_type' => 'product.product_type IN (\'main\',\'variant\'' . ($config->get('product_approval', 0) ? ',\'waiting_approval\'' : '') . ')'
		);
		$searchMap = array(
			'product.product_name',
			'product.product_description',
			'product.product_id',
			'product.product_code'
		);
		$select = array();
		$join = '';
		if($category_explorer) {
			$query = 'SELECT category_id, category_left, category_right, category_depth, category_parent_id FROM '.hikamarket::table('shop.category').' WHERE category_id IN ('.(int)$cid.','.(int)$rootCategory.')';
			$db->setQuery($query);
			$categories = $db->loadObjectList('category_id');

			if(!isset($categories[$rootCategory]))
				return hikamarket::deny('vendor', JText::sprintf('HIKAM_ACTION_DENY', JText::_('HIKAM_ERR_ROOTCATEGORY_NOT_EXIST')));

			if(!isset($categories[$cid]) || $categories[$cid]->category_left < $categories[$rootCategory]->category_left || $categories[$cid]->category_left > $categories[$rootCategory]->category_right)
				$cid = $rootCategory;

			$app->setUserState(HIKAMARKET_COMPONENT.'.product.listing_cid', $cid);

			$query = 'SELECT cats.category_id, cats.category_depth, cats.category_name, cats.category_parent_id '.
				' FROM '.hikamarket::table('shop.category').' AS cats INNER JOIN '.hikamarket::table('shop.category').' AS basecat ON cats.category_left <= basecat.category_left AND cats.category_right >= basecat.category_right '.
				' WHERE basecat.category_id = '.(int)$cid.' AND cats.category_depth >= '.$categories[$rootCategory]->category_depth.' ORDER BY category_depth';
			$db->setQuery($query);
			$breadcrumb = $db->loadObjectList();
			$this->assignRef('breadcrumb', $breadcrumb);
		}

		if($category_explorer)
			$join = ' INNER JOIN '.hikamarket::table('shop.product_category').' AS product_category ON (product.product_id = product_category.product_id OR product.product_parent_id = product_category.product_id) ';
		if($pageInfo->filter->vendors >= 0 || $vendor->vendor_id > 1) {
			$select['parent_product_name'] = 'parent_product.product_name as parent_product_name';
			$join = ' LEFT JOIN '.hikamarket::table('shop.product').' AS parent_product ON product.product_parent_id = parent_product.product_id AND parent_product.product_vendor_id != product.product_vendor_id AND product.product_vendor_id > 0 ';
			if($category_explorer)
				$join .= ' INNER JOIN '.hikamarket::table('shop.product_category').' AS product_category ON (product.product_id = product_category.product_id OR parent_product.product_id = product_category.product_id) ';
		}

		if((!hikamarket::level(2) || $vendor->vendor_id == 1) && !empty($pageInfo->filter->filter_product_type) && in_array($pageInfo->filter->filter_product_type, array('all', 'variant'))) {
			$select['parent_product_name'] = 'parent_product.product_name as parent_product_name';
			$join = ' LEFT JOIN '.hikamarket::table('shop.product').' AS parent_product ON product.product_parent_id = parent_product.product_id ';
			if($category_explorer)
				$join .= ' INNER JOIN '.hikamarket::table('shop.product_category').' AS product_category ON (product.product_id = product_category.product_id OR parent_product.product_id = product_category.product_id) ';
		}

		if($category_explorer) {
			if($pageInfo->selectedType) {
				$join .= ' INNER JOIN '.hikamarket::table('shop.category').' AS category ON category.category_id = product_category.category_id ';
				$filter = 'category.category_left >= '.$categories[$cid]->category_left.' AND category.category_right <= '.$categories[$cid]->category_right;
			} else
				$filter = 'product_category.category_id = '.$cid;
			if($vendor->vendor_id > 1 && $cid == $rootCategory) {
				if(!$pageInfo->selectedType)
					$join .= ' INNER JOIN '.hikamarket::table('shop.category').' AS category ON category.category_id = product_category.category_id ';
				$filter .= ' OR category.category_left < '.$categories[$cid]->category_left.' OR category.category_right > '.$categories[$cid]->category_right;
			}
			$filters[] = $filter;
		}

		$fieldsClass = hikamarket::get('shop.class.field');
		$categories = array();
		if($category_explorer) {
			$parent_cat_ids = array();
			foreach($breadcrumb as $catElem) {
				$parent_cat_ids[] = $catElem->category_id;
			}
			$categories = array('originals' => array($cid), 'parents' => $parent_cat_ids);
		}
		$fields = $fieldsClass->getData('display:vendor_product_listing=1', 'product', false, $categories);
		$this->assignRef('fields', $fields);
		$this->assignRef('fieldsClass', $fieldsClass);

		foreach($fields as $fieldName => $oneExtraField) {
			$searchMap[] = 'product.' . $fieldName;
		}

		if(!empty($pageInfo->filter->filter_product_type) && in_array($pageInfo->filter->filter_product_type, array('all', 'variant'))) {
			if($pageInfo->filter->filter_product_type == 'all')
				$filters['main'] = 'product.product_parent_id >= 0';
			if($pageInfo->filter->filter_product_type == 'variant')
				$filters['main'] = 'product.product_parent_id > 0';
		}
		if($pageInfo->filter->vendors == 0 || $vendor->vendor_id > 1) {
			$filters['main'] .= ' OR parent_product.product_id != 0 ) AND (product.product_vendor_id = '.(int)$vendor->vendor_id;
			if($vendor->vendor_id == 1)
				$filters['main'] .= ' OR product.product_vendor_id = 0';
		} elseif( $pageInfo->filter->vendors > 1) {
			$filters['main'] .= ' OR parent_product.product_id != 0 ) AND (product.product_vendor_id = '.(int)$pageInfo->filter->vendors;
		}

		$order = '';
		$this->processFilters($filters, $order, $searchMap, array('product.'));

		$query = 'FROM '.hikamarket::table($cfg['table']).' AS product '.$join.$filters.$order;
		$db->setQuery('SELECT DISTINCT product.*' . (empty($select)?'':',') . implode(',', $select) . ' ' . $query, (int)$pageInfo->limit->start, (int)$pageInfo->limit->value);

		$rows = $db->loadObjectList();

		$products = array();
		foreach($rows as &$product) {
			$product->prices = array();
			$product->file_name = $product->product_name;
			if(!isset($products[$product->product_id])) {
				$products[$product->product_id] =& $product;
			} else if(!is_array($products[$product->product_id])) {
				$old =& $products[$product->product_id];
				unset($products[$product->product_id]);
				$products[$product->product_id] = array(&$old, &$product);
			} else {
				$products[$product->product_id][] =& $product;
			}
		}
		unset($product);
		$this->assignRef('products', $rows);

		$db->setQuery('SELECT COUNT(DISTINCT(product.product_id)) '.$query);
		$pageInfo->elements = new stdClass();
		$pageInfo->elements->total = $db->loadResult();
		$pageInfo->elements->page = count($rows);

		$this->loadPricesImages($products);

		$fieldsClass->handleZoneListing($fields, $rows);

		$this->assignRef('vendor_id', $vendor_id);
		$this->assignRef('cancelUrl', $cancelUrl);

		$using_approval = ($config->get('product_approval', 0) && hikamarket::acl('product/approve'));

		$this->toolbar = array(
			'back' => array(
				'icon' => 'back',
				'name' => JText::_('HIKA_BACK'),
				'url' => hikamarket::completeLink('vendor')
			),
			'approve' => array(
				'icon' => 'approve',
				'name' => JText::_('WAITING_APPROVAL_LIST'),
				'url' => hikamarket::completeLink('product&task=waitingapproval'),
				'display' => $using_approval,
				'pos' => 'right'
			),
			'new' => array(
				'icon' => 'new',
				'name' => JText::_('HIKA_NEW'),
				'url' => hikamarket::completeLink('product&task=add'),
				'acl' => hikamarket::acl('product/add'),
				'pos' => 'right'
			)
		);

		$this->getPagination();

		$this->getOrdering('a.ordering', !$pageInfo->selectedType);
		if(!empty($this->ordering->ordering)) {
			$this->toolbar['ordering']['display'] = true;
		}

		return true;
	}

	private function loadPricesImages(&$products) {
		if(empty($products))
			return;

		$db = JFactory::getDBO();
		$db->setQuery('SELECT * FROM '.hikamarket::table('shop.price').' WHERE price_product_id IN ('.implode(',', array_keys($products)).')');
		$prices = $db->loadObjectList();
		if(!empty($prices)) {
			foreach($prices as $price) {
				if(!isset($products[$price->price_product_id]) )
					continue;

				if(!is_array($products[$price->price_product_id])) {
					$products[$price->price_product_id]->prices[] = $price;
				} else {
					foreach($products[$price->price_product_id] as $p) {
						$p->prices[] = $price;
					}
				}
			}
		}
		unset($prices);

		$db->setQuery('SELECT * FROM '.hikamarket::table('shop.file').' WHERE file_ref_id IN ('.implode(',', array_keys($products)).') AND file_type=\'product\' ORDER BY file_ref_id ASC, file_ordering ASC, file_id ASC');
		$images = $db->loadObjectList();
		if(!empty($images)) {
			foreach($images as $image) {
				if(!isset($products[(int)$image->file_ref_id]))
					continue;

				if(!is_array($products[(int)$image->file_ref_id])) {
					if(isset($products[(int)$image->file_ref_id]->file_ref_id))
						continue;

					foreach(get_object_vars($image) as $key => $name) {
						$products[(int)$image->file_ref_id]->$key = $name;
					}
				} else {
					$p = reset($products[(int)$image->file_ref_id]);
					if(isset($p->file_ref_id))
						continue;

					foreach($products[(int)$image->file_ref_id] as $p) {
						foreach(get_object_vars($image) as $key => $name) {
							$p->$key = $name;
						}
					}
				}
			}
		}
	}

	public function selection($tpl = null) {
		$singleSelection = JRequest::getVar('single', 0);
		$confirm = JRequest::getInt('confirm', 1);
		$this->assignRef('singleSelection', $singleSelection);
		$this->assignRef('confirm', $confirm);

		$elemStruct = array(
			'product_name',
			'product_code',
			'product_price',
			'product_quantity'
		);
		$this->assignRef('elemStruct', $elemStruct);

		$ctrl = JRequest::getCmd('ctrl');
		$this->assignRef('ctrl', $ctrl);

		$task = 'useselection';
		$this->assignRef('task', $task);

		$afterParams = array();
		$after = JRequest::getString('after', '');
		if(!empty($after)) {
			list($ctrl, $task) = explode('|', $after, 2);

			$afterParams = JRequest::getString('afterParams', '');
			$afterParams = explode(',', $afterParams);
			foreach($afterParams as &$p) {
				$p = explode('|', $p, 2);
				unset($p);
			}
		}
		$this->assignRef('afterParams', $afterParams);

		$this->producttype_selector = true;
		$this->listing();
		$this->toolbar = array();
	}

	public function useselection() {
		$products = JRequest::getVar('pid', array(), '', 'array');
		$rows = array();
		$data = '';
		$confirm = JRequest::getInt('confirm', 1);
		$singleSelection = JRequest::getVar('single', false);

		$elemStruct = array(
			'product_name',
			'product_code',
			'product_price',
			'product_quantity'
		);

		if(!empty($products)) {
			JArrayHelper::toInteger($products);
		}

		$this->assignRef('rows', $rows);
		$this->assignRef('data', $data);
		$this->assignRef('confirm', $confirm);
		$this->assignRef('singleSelection', $singleSelection);

		if(!empty($confirm)) {
			$js = 'window.hikashop.ready(function(){window.top.hikamarket.submitBox('.$data.');});';
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration($js);
		}
	}

	public function form() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$ctrl = '';
		$this->paramBase = HIKAMARKET_COMPONENT.'.'.$this->getName().'.edit';

		JHTML::_('behavior.tooltip');
		hikamarket::loadJslib('tooltip');

		$vendor = hikamarket::loadVendor(true, false);
		$this->assignRef('vendor', $vendor);

		$config = hikamarket::config();
		$this->assignRef('config', $config);

		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$product_id = hikamarket::getCID('product_id');
		$productClass = hikamarket::get('class.product');
		$vendorClass = hikamarket::get('class.vendor');

		$main_currency = $shopConfig->get('main_currency',1);
		$this->assignRef('main_currency_id', $main_currency);

		$this->loadRef(array(
			'toggleClass' => 'helper.toggle',
			'currencyClass' => 'shop.class.currency',
			'popup' => 'shop.helper.popup',
			'categoryType' => 'type.shop_category',
			'quantityType' => 'type.quantity',
			'productsType' => 'type.products',
			'nameboxType' => 'type.namebox',
			'nameboxVariantType' => 'type.namebox',
			'uploaderType' => 'shop.type.uploader',
			'imageHelper' => 'shop.helper.image',
			'currencyType' => 'shop.type.currency',
			'weight' => 'shop.type.weight',
			'volume' => 'shop.type.volume',
		));

		hikamarket::loadJslib('jquery');

		$product = new stdClass();
		$product->product_description = '';
		$product->product_id = $product_id;
		$template_id = 0;
		$variant_id = 0;

		if(empty($product_id) && !empty($vendor->vendor_template_id) && (int)$vendor->vendor_template_id > 0) {
			$template_id = (int)$vendor->vendor_template_id;
			$product_id = $template_id;
		}
		if(empty($product_id) && (int)$config->get('default_template_id', 0) > 0) {
			$template_id = (int)$config->get('default_template_id', 0);
			$product_id = $template_id;
		}

		if(!empty($template_id)) {
			$query = 'SELECT COUNT(*) FROM '.hikamarket::table('shop.product').' AS p WHERE p.product_id = ' . (int)$template_id . ' AND product_type = ' . $db->Quote('template');
			$db->setQuery($query);
			$isTemplate = (int)$db->loadResult();
			if($isTemplate == 0) {
				$template_id = 0;
				$product_id = 0;
			}
		}

		if(!empty($product_id))
			$product = $productClass->getRaw($product_id, true);

		if(empty($template_id) && isset($product->product_vendor_id) && $vendor->vendor_id > 1 && (int)$product->product_vendor_id != $vendor->vendor_id) {
			$product_duplication = JRequest::getVar('product_duplication', null);
			if(empty($product_duplication) || empty($product_duplication->product_id) || $product_duplication->product_id != $product_id || empty($product_duplication->characteristic_id))
				return hikamarket::deny('product', JText::_('HIKAM_PAGE_DENY'));

			$mainProduct = clone($product);

			$product = new stdClass();
			$product->product_id = 0;
			$product->product_parent_id = $mainProduct->product_id;
			$product->product_name = $mainProduct->product_name;
			$product->product_description = '';
			$product->product_type = 'variant';
			$product_id = 0;
		}

		if(!empty($product_id)) {
			if((int)$product->product_parent_id > 0 && empty($template_id)) {
				$parentProduct = $productClass->getRaw((int)$product->product_parent_id, true);
				if(!empty($parentProduct) && ($vendor->vendor_id == 0 || $vendor->vendor_id == 1 || $parentProduct->product_vendor_id == $vendor->vendor_id)) {
					$variant_id = $product_id;
					$product_id = (int)$product->product_parent_id;
					unset($product);
					$product = $parentProduct;
				} else {
					unset($parentProduct);
				}
			}

			$query = 'SELECT b.* FROM '.hikamarket::table('shop.product_category').' AS a LEFT JOIN '.hikamarket::table('shop.category').' AS b ON a.category_id = b.category_id WHERE a.product_id = '.(int)$product_id.' ORDER BY a.product_category_id';
			$db->setQuery($query);
			$product->categories = $db->loadObjectList('category_id');

			$query = 'SELECT * FROM '.hikamarket::table('shop.file').' WHERE file_ref_id = '.(int)$product_id.' AND file_type=\'product\' ORDER BY file_ordering, file_id';
			$db->setQuery($query);
			$product->images = $db->loadObjectList();

			$query = 'SELECT file.*, SUM(download.download_number) AS download_number FROM '.hikamarket::table('shop.file').' AS file '.
				' LEFT JOIN '.hikamarket::table('shop.download').' AS download ON file.file_id = download.file_id '.
				' WHERE file_ref_id = '.(int)$product_id.' AND file.file_type='.$db->Quote('file').' '.
				' GROUP BY file.file_id '.
				' ORDER BY file.file_ordering, file.file_id';
			$db->setQuery($query);
			$product->files = $db->loadObjectList('file_id');

			$query = 'SELECT a.*,b.* FROM '.hikamarket::table('shop.product_related').' AS a LEFT JOIN '.hikamarket::table('shop.product').' AS b ON a.product_related_id=b.product_id WHERE a.product_related_type=\'related\' AND a.product_id = '.(int)$product_id;
			$db->setQuery($query);
			$product->related = $db->loadObjectList();

			$query = 'SELECT a.*,b.* FROM '.hikamarket::table('shop.product_related').' AS a LEFT JOIN '.hikamarket::table('shop.product').' AS b ON a.product_related_id=b.product_id WHERE a.product_related_type=\'options\' AND a.product_id = '.(int)$product_id;
			$db->setQuery($query);
			$product->options = $db->loadObjectList();

			$query = 'SELECT variant.*, characteristic.* FROM '.hikamarket::table('shop.variant').' as variant LEFT JOIN '.hikamarket::table('shop.characteristic').' as characteristic ON variant.variant_characteristic_id = characteristic.characteristic_id WHERE variant.variant_product_id = '.$product_id . ' ORDER BY ordering ASC';
			$db->setQuery($query);
			$product->characteristics = $db->loadObjectList('characteristic_id');
			$query = 'SELECT p.* FROM '.hikamarket::table('shop.product').' as p WHERE p.product_type = '.$db->Quote('variant').' AND p.product_parent_id = '.(int)$product_id;
			$db->setQuery($query);
			$product->variants = $db->loadObjectList('product_id');

			if(!empty($product->variants)) {
				$variant_ids = array_keys($product->variants);
				$query = 'SELECT * FROM '.hikamarket::table('shop.price').' WHERE price_product_id IN (' . (int)$product_id . ',' . implode(',', $variant_ids).')';
				$db->setQuery($query);
				$prices = $db->loadObjectList();

				$product->prices = array();
				foreach($prices as $price) {
					$ppid = (int)$price->price_product_id;
					if($ppid == $product_id) {
						$product->prices[] = $price;
					} elseif(isset($product->variants[$ppid])) {
						if(empty($product->variants[$ppid]->prices))
							$product->variants[$ppid]->prices = array();
						$product->variants[$ppid]->prices[] = $price;
					}
				}
				unset($prices);

				$query = 'SELECT v.*, c.* FROM '.hikamarket::table('shop.variant').' AS v '.
					' INNER JOIN '.hikamarket::table('shop.characteristic').' AS c ON c.characteristic_id = v.variant_characteristic_id '.
					' WHERE v.variant_product_id IN ('.implode(',',$variant_ids).') '.
					' ORDER BY v.variant_product_id ASC, v.variant_characteristic_id ASC, v.ordering ASC';
				$db->setQuery($query);
				$variant_data = $db->loadObjectList();

				foreach($variant_data as $d) {
					$ppid = (int)$d->variant_product_id;
					if(!isset($product->characteristics[$d->characteristic_parent_id]))
						continue;

					if(!isset($product->variants[$ppid]))
						continue;

					if(empty($product->variants[$ppid]->characteristics))
						$product->variants[$ppid]->characteristics = array();

					$pcid = $product->characteristics[$d->characteristic_parent_id]->characteristic_id;
					$value = new stdClass();
					$value->id = $d->characteristic_id;
					$value->value = $d->characteristic_value;
					$product->variants[$ppid]->characteristics[$pcid] = $value;
				}
			} else {
				$query = 'SELECT * FROM '.hikamarket::table('shop.price').' WHERE price_product_id = ' . (int)$product_id;
				$db->setQuery($query);
				$product->prices = $db->loadObjectList();
			}

			if($vendor->vendor_id > 1) {
				foreach($product->files as &$file) {
					if(strpos($file->file_path, '/') !== false) {
						$file->file_path = substr($file->file_path, strrpos($file->file_path, '/') + 1);
					}
				}
			}
		}

		if(empty($product_id) || !empty($template_id)) {
			$rootCategory = 0;
			$categoryClass = hikamarket::get('shop.class.category');
			$category_explorer = $config->get('show_category_explorer', 1);
			if($category_explorer)
				$rootCategory = (int)$app->getUserState(HIKAMARKET_COMPONENT.'.product.listing_cid');
			if(empty($rootCategory) || $rootCategory == 1){
				$rootCategory = $vendorClass->getRootCategory($vendor->vendor_id);
				if(empty($rootCategory)) {
					$rootCategory = 'product';
					$categoryClass->getMainElement($rootCategory);
				}
			}
			if(!empty($rootCategory)) {
				if(empty($product->categories))
					$product->categories = array( $rootCategory => $categoryClass->get($rootCategory) );
				else
					$product->categories[$rootCategory] = $categoryClass->get($rootCategory);
			}
		}

		if(!empty($template_id)) {
			$product->product_id = 0;
			unset($product->product_type);
			unset($product->product_code);
		}

		if(!empty($product->product_tax_id)) {
			$main_tax_zone = explode(',', $shopConfig->get('main_tax_zone', ''));
			if(count($main_tax_zone)) {
				$main_tax_zone = array_shift($main_tax_zone);
			}
		}
		if(!empty($product->prices)) {
			foreach($product->prices as $key => $price) {
				if(empty($price->price_value)){
					unset($product->prices[$key]);
				}
			}
			if(!empty($product->product_tax_id)) {
				foreach($product->prices as &$price) {
					$price->price_value_with_tax = $this->currencyClass->getTaxedPrice($price->price_value, $main_tax_zone, $product->product_tax_id);
				}
			}else{
				foreach($product->prices as $key => $price) {
					$price->price_value_with_tax = $price->price_value;
				}
			}
		}
		if(empty($product->prices)) {
			$obj = new stdClass();
			$obj->price_value = 0;
			$obj->price_value_with_tax = 0;
			$obj->price_currency_id = $main_currency;
			$product->prices = array($obj);
		}

		$editor = hikamarket::get('shop.helper.editor');
		$editor->setEditor($config->get('editor', ''));
		$editor->name = 'product_description';
		$editor->content = $product->product_description;
		$editor->height = 200;
		if($config->get('editor_disable_buttons', 0))
			$editor->options = false;
		$this->assignRef('editor', $editor);

		if(!isset($product->product_quantity) || $product->product_quantity < 0)
			$product->product_quantity = JText::_('UNLIMITED');
		if(!isset($product->product_max_per_order) || $product->product_max_per_order <= 0)
			$product->product_max_per_order = JText::_('UNLIMITED');

		$this->assignRef('product', $product);

		if(hikashop_level(2)) {
			hikamarket::loadJslib('otree');
			$joomlaAcl = hikamarket::get('type.joomla_acl');
			$this->assignRef('joomlaAcl', $joomlaAcl);
		}

		$translationHelper = hikamarket::get('shop.helper.translation');
		if($translationHelper && $translationHelper->isMulti()) {
			$translationHelper->load('hikashop_product', @$product->product_id, $product);
			$this->assignRef('translationHelper', $translationHelper);
		}

		$manufacturerType = hikamarket::get('shop.type.categorysub');
		$manufacturerType->type = 'manufacturer';
		$manufacturerType->field = 'category_id';
		$this->assignRef('manufacturerType', $manufacturerType);

		$rootCategory = $vendorClass->getRootCategory($vendor);
		$this->assignRef('rootCategory', $rootCategory);

		$vendorCategories = $rootCategory;
		$extra_categories = $vendorClass->getExtraCategories($vendor);
		if(!empty($extra_categories))
			$vendorCategories = array_merge(array($rootCategory), $extra_categories);
		$this->assignRef('vendorCategories', $vendorCategories);

		$main_currency = (int)$shopConfig->get('main_currency');
		$this->currencyType->load($main_currency);
		$currencies = $this->currencyType->currencies;
		$this->assignRef('currencies', $currencies);
		$default_currency = $this->currencyType->currencies[$main_currency];
		$this->assignRef('default_currency', $default_currency);

		$fieldsClass = hikamarket::get('shop.class.field');
		$fields = $fieldsClass->getFields('display:vendor_product_edit=1', $product, 'product', 'field&task=state');
		foreach($fields as $fieldName => $extraField) {
			if(empty($extraField->field_display) || strpos($extraField->field_display, ';vendor_product_edit=1;') === false) {
				unset($fields[$fieldName]);
			}
		}
		$null = array();
		$fieldsClass->addJS($null, $null, $null);
		$fieldsClass->jsToggle($fields, $product, 0);
		$this->assignRef('fieldsClass', $fieldsClass);
		$this->assignRef('fields', $fields);

		$using_approval = ($vendor->vendor_id <= 1 && $config->get('product_approval', 0) && !empty($product->product_type) && $product->product_type == 'waiting_approval' && hikamarket::acl('product/approve'));

		$this->toolbar = array(
			'cancel' => array(
				'url' => hikamarket::completeLink('product'),
				'icon' => 'back',
				'name' => JText::_('HIKA_BACK')
			),
			'back' => array(
				'url' => hikamarket::completeLink('product'),
				'icon' => 'category',
				'name' => JText::_('HIKAM_PRODUCT_LISTING'),
				'display' => false
			),
			'cartlink' => array(
				'url' => hikamarket::completeLink('product&task=cartlink&pid=' . $product->product_id, true),
				'icon' => 'cart',
				'popup' => array('name' => JText::_('HIKAM_CART_LINK'), 'id' => 'cartlink', 'width' => 450, 'height' => 250),
				'name' => JText::_('HIKAM_CART_LINK'), 'pos' => 'right',
				'display' => ($config->get('product_cart_link', 0) && $product->product_id > 0)
			),
			'sep01' => array(
				'sep' => true, 'pos' => 'right',
				'display' => ($config->get('product_cart_link', 0) && $product->product_id > 0)
			),
			'approve' => array(
				'url' => '#approve',
				'linkattribs' => 'onclick="return window.hikamarket.submitform(\'approve\',\'hikamarket_products_form\');"',
				'icon' => 'apply',
				'name' => JText::_('HIKAM_APPROVE'), 'pos' => 'right',
				'display' => $using_approval,
			),
			'sep02' => array(
				'sep' => true, 'pos' => 'right',
				'display' => $using_approval,
			),
			'apply' => array(
				'url' => '#apply',
				'linkattribs' => 'onclick="return window.hikamarket.submitform(\'apply\',\'hikamarket_products_form\');"',
				'icon' => 'apply',
				'name' => JText::_('HIKA_APPLY'), 'pos' => 'right'
			),
			'save' => array(
				'url' => '#save',
				'linkattribs' => 'onclick="return window.hikamarket.submitform(\'save\',\'hikamarket_products_form\');"',
				'icon' => 'save',
				'name' => JText::_('HIKA_SAVE'), 'pos' => 'right'
			)
		);

		$cancel_action = JRequest::getCmd('cancel_action', '');
		$this->assignRef('cancel_action', $cancel_action);
		$cancel_url = urldecode(JRequest::getCmd('cancel_url', ''));
		$this->assignRef('cancel_url', $cancel_url);

		if(!empty($cancel_action)) {
			switch($cancel_action) {
				case 'product':
					if(!empty($product->product_id))
						$this->toolbar['cancel']['url'] = hikamarket::completeLink('shop.product&task=show&cid='.$product->product_id);
						$this->toolbar['back']['display'] = true;
					break;
				case 'url':
					if(!empty($cancel_url)) {
						$cancel_url = base64_decode($cancel_url);
						if($cancel_url !== false && substr($cancel_url, 0, 4) == 'http') {
							$this->toolbar['cancel']['url'] = $cancel_url;
							$this->toolbar['back']['display'] = true;
						}
					}
					break;
			}
		}
	}

	public function form_variants() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$ctrl = '';
		$this->paramBase = HIKAMARKET_COMPONENT.'.'.$this->getName().'.edit';

		$vendor = hikamarket::loadVendor(true, false);
		$this->assignRef('vendor', $vendor);

		$config = hikamarket::config();
		$this->assignRef('config', $config);

		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$product_id = hikamarket::getCID('product_id');
		$productClass = hikamarket::get('class.product');
		$vendorClass = hikamarket::get('class.vendor');

		$main_currency = $shopConfig->get('main_currency',1);
		$this->assignRef('main_currency_id', $main_currency);

		$this->loadRef(array(
			'toggleClass' => 'helper.toggle',
			'currencyClass' => 'shop.class.currency',
		));

		$product = new stdClass();
		$product->product_description = '';
		$product->product_id = $product_id;
		$template_id = 0;
		$variant_id = 0;

		if(empty($product_id) && !empty($vendor->vendor_template_id)) {
			$template_id = $vendor->vendor_template_id;
			$product_id = $template_id;
		}
		if(empty($product_id) && (int)$config->get('default_template_id', 0) > 0) {
			$template_id = (int)$config->get('default_template_id', 0);
			$product_id = $template_id;
		}

		if(!empty($product_id)) {
			$product = $productClass->getRaw($product_id, true);

			if((int)$product->product_parent_id > 0 && empty($template_id)) {
				$parentProduct = $productClass->getRaw((int)$product->product_parent_id, true);
				if(!empty($parentProduct) && ($vendor->vendor_id == 0 || $vendor->vendor_id == 1 || $parentProduct->product_vendor_id == $vendor->vendor_id)) {
					$variant_id = $product_id;
					$product_id = (int)$product->product_parent_id;
					unset($product);
					$product = $parentProduct;
				} else {
					unset($parentProduct);
				}
			}

			$query = 'SELECT variant.*, characteristic.* FROM '.hikamarket::table('shop.variant').' as variant LEFT JOIN '.hikamarket::table('shop.characteristic').' as characteristic ON variant.variant_characteristic_id = characteristic.characteristic_id WHERE variant.variant_product_id = '.$product_id . ' ORDER BY ordering ASC';
			$db->setQuery($query);
			$product->characteristics = $db->loadObjectList('characteristic_id');

			$query = 'SELECT p.* FROM '.hikamarket::table('shop.product').' as p WHERE p.product_type = '.$db->Quote('variant').' AND p.product_parent_id = '.(int)$product_id;
			$db->setQuery($query);
			$product->variants = $db->loadObjectList('product_id');

			if(!empty($product->variants)) {
				$variant_ids = array_keys($product->variants);
				$query = 'SELECT * FROM '.hikamarket::table('shop.price').' WHERE price_product_id IN (' . (int)$product_id . ',' . implode(',', $variant_ids).')';
				$db->setQuery($query);
				$prices = $db->loadObjectList();

				foreach($prices as $price) {
					$ppid = (int)$price->price_product_id;
					if(isset($product->variants[$ppid])) {
						if(empty($product->variants[$ppid]->prices))
							$product->variants[$ppid]->prices = array();
						$product->variants[$ppid]->prices[] = $price;
					}
				}
				unset($prices);

				$query = 'SELECT v.*, c.* FROM '.hikamarket::table('shop.variant').' AS v '.
					' INNER JOIN '.hikamarket::table('shop.characteristic').' AS c ON c.characteristic_id = v.variant_characteristic_id '.
					' WHERE v.variant_product_id IN ('.implode(',',$variant_ids).') '.
					' ORDER BY v.variant_product_id ASC, v.variant_characteristic_id ASC, v.ordering ASC';
				$db->setQuery($query);
				$variant_data = $db->loadObjectList();

				foreach($variant_data as $d) {
					$ppid = (int)$d->variant_product_id;
					if(isset($product->variants[$ppid])) {
						if(empty($product->variants[$ppid]->characteristics))
							$product->variants[$ppid]->characteristics = array();

						$pcid = (int)$product->characteristics[$d->characteristic_parent_id]->characteristic_id;
						$value = new stdClass();
						$value->id = $d->characteristic_id;
						$value->value = $d->characteristic_value;
						$product->variants[$ppid]->characteristics[$pcid] = $value;
					}
				}
			}
		}

		$this->assignRef('product', $product);
	}

	public function variant() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$ctrl = '';
		$this->paramBase = HIKAMARKET_COMPONENT.'.'.$this->getName().'.edit';

		JHTML::_('behavior.tooltip');

		$vendor = hikamarket::loadVendor(true, false);
		$this->assignRef('vendor', $vendor);

		$config = hikamarket::config();
		$this->assignRef('config', $config);

		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$product_id = hikamarket::getCID('variant_id');
		$product_parent_id = JRequest::getInt('product_id');
		$productClass = hikamarket::get('class.product');
		$vendorClass = hikamarket::get('class.vendor');

		$editing_variant = true;
		$this->assignRef('editing_variant', $editing_variant);

		$main_currency = $shopConfig->get('main_currency',1);
		$this->assignRef('main_currency_id', $main_currency);

		$this->loadRef(array(
			'toggleClass' => 'helper.toggle',
			'currencyClass' => 'shop.class.currency',
			'popup' => 'shop.helper.popup',
			'quantityType' => 'type.quantity',
			'uploaderType' => 'shop.type.uploader',
			'imageHelper' => 'shop.helper.image',
			'currencyType' => 'shop.type.currency',
			'weight' => 'shop.type.weight',
			'volume' => 'shop.type.volume',
			'characteristicType' => 'shop.type.characteristic'
		));

		$product = new stdClass();

		if(!empty($product_id)) {
			$product = $productClass->getRaw($product_id, true);

			if((int)$product->product_parent_id != (int)$product_parent_id)
				return false;

			$query = 'SELECT * FROM '.hikamarket::table('shop.file').' WHERE file_ref_id = '.(int)$product_id.' AND file_type=\'product\' ORDER BY file_ordering, file_id';
			$db->setQuery($query);
			$product->images = $db->loadObjectList();

			$query = 'SELECT * FROM '.hikamarket::table('shop.file').' WHERE file_ref_id = '.(int)$product_id.' AND file_type=\'file\' ORDER BY file_ordering, file_id';
			$db->setQuery($query);
			$product->files = $db->loadObjectList('file_id');

			$query = 'SELECT * FROM '.hikamarket::table('shop.price').' WHERE price_product_id = ' . (int)$product_id;
			$db->setQuery($query);
			$product->prices = $db->loadObjectList();

			$query = 'SELECT v.*, c.* FROM '.hikamarket::table('shop.variant').' AS v '.
				' INNER JOIN '.hikamarket::table('shop.characteristic').' as c ON v.variant_characteristic_id = c.characteristic_id '.
				' WHERE characteristic_parent_id > 0 AND variant_product_id = ' . (int)$product_id;
			$db->setQuery($query);
			$characteristic_values = $db->loadObjectList('characteristic_parent_id');

			$query = 'SELECT * FROM '.hikamarket::table('shop.characteristic').
				' WHERE characteristic_id IN ('.implode(',',array_keys($characteristic_values)).') OR characteristic_parent_id IN ('.implode(',',array_keys($characteristic_values)).') '.
				' ORDER BY characteristic_parent_id ASC';
			$db->setQuery($query);
			$characteristics = $db->loadObjectList();

			$product->characteristics = array();
			foreach($characteristics as $c) {
				$charac_pid = ((int)$c->characteristic_parent_id == 0) ? (int)$c->characteristic_id : (int)$c->characteristic_parent_id;
				if(!isset($product->characteristics[$charac_pid])) {
					$product->characteristics[$charac_pid] = new stdClass();
					$product->characteristics[$charac_pid]->values = array();
				}
				if(((int)$c->characteristic_parent_id == 0)) {
					foreach($c as $k => $v)
						$product->characteristics[$charac_pid]->$k = $v;
				} else {
					$product->characteristics[$charac_pid]->values[ (int)$c->characteristic_id ] = $c->characteristic_value;
				}
			}
			foreach($characteristic_values as $k => $v) {
				$product->characteristics[$k]->default_id = (int)$v->characteristic_id;
			}

			if($vendor->vendor_id > 1) {
				foreach($product->files as &$file) {
					if(strpos($file->file_path, '/') !== false) {
						$file->file_path = substr($file->file_path, strrpos($file->file_path, '/')+1);
					}
				}
			}
		}

		$product->parent = $productClass->getRaw((int)$product_parent_id, true);
		if(!empty($product->parent)  && !empty($product->parent->product_tax_id))
			$product->product_tax_id = (int)$product->parent->product_tax_id;

		if(!empty($product->product_tax_id)) {
			$main_tax_zone = explode(',', $shopConfig->get('main_tax_zone', ''));
			if(count($main_tax_zone)) {
				$main_tax_zone = array_shift($main_tax_zone);
			}
		}
		if(!empty($product->prices)) {
			foreach($product->prices as $key => $price) {
				if(empty($price->price_value)){
					unset($product->prices[$key]);
				}
			}
			if(!empty($product->product_tax_id)) {
				foreach($product->prices as &$price) {
					$price->price_value_with_tax = $this->currencyClass->getTaxedPrice($price->price_value, $main_tax_zone, $product->product_tax_id);
				}
			} else {
				foreach($product->prices as $key => $price) {
					$price->price_value_with_tax = $price->price_value;
				}
			}
		}
		if(empty($product->prices)) {
			$obj = new stdClass();
			$obj->price_value = 0;
			$obj->price_value_with_tax = 0;
			$obj->price_currency_id = $main_currency;
			$product->prices = array($obj);
		}

		$editor = hikamarket::get('shop.helper.editor');
		$editor->setEditor($config->get('editor', ''));
		$editor->id = 'product_variant_editors_'.time();
		$editor->name = 'product_variant_description';
		$editor->content = $product->product_description;
		$editor->height = 200;
		if($config->get('editor_disable_buttons', 0))
			$editor->options = false;
		$this->assignRef('editor', $editor);

		if(!isset($product->product_quantity) || $product->product_quantity < 0)
			$product->product_quantity = JText::_('UNLIMITED');
		if(!isset($product->product_max_per_order) || $product->product_max_per_order <= 0)
			$product->product_max_per_order = JText::_('UNLIMITED');

		$this->assignRef('product', $product);

		if(hikashop_level(2)) {
			hikamarket::loadJslib('otree');
			$joomlaAcl = hikamarket::get('type.joomla_acl');
			$this->assignRef('joomlaAcl', $joomlaAcl);
		}

		$translationHelper = hikamarket::get('shop.helper.translation');
		if($translationHelper && $translationHelper->isMulti()) {
			$translationHelper->load('hikashop_product', @$product->product_id, $product);
			$this->assignRef('translationHelper', $translationHelper);
		}

		$main_currency = (int)$shopConfig->get('main_currency');
		$this->currencyType->load($main_currency);
		$currencies = $this->currencyType->currencies;
		$this->assignRef('currencies', $currencies);
		$default_currency = $this->currencyType->currencies[$main_currency];
		$this->assignRef('default_currency', $default_currency);

		$fieldsClass = hikamarket::get('shop.class.field');
		$fields = $fieldsClass->getFields('display:vendor_product_edit=1', $product, 'product', 'field&task=state');
		foreach($fields as $fieldName => $extraField) {
			if(empty($extraField->field_display) || strpos($extraField->field_display, ';vendor_product_edit=1;') === false) {
				unset($fields[$fieldName]);
			}
		}
		$null = array();
		$fieldsClass->addJS($null, $null, $null);
		$fieldsClass->jsToggle($fields, $product, 0);
		$this->assignRef('fieldsClass', $fieldsClass);
		$this->assignRef('fields', $fields);

		return true;
	}

	public function form_variants_add() {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$ctrl = '';
		$this->paramBase = HIKAMARKET_COMPONENT.'.'.$this->getName().'.edit';

		$vendor = hikamarket::loadVendor(true, false);
		$this->assignRef('vendor', $vendor);

		$config = hikamarket::config();
		$this->assignRef('config', $config);

		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$this->loadRef(array(
			'nameboxVariantType' => 'type.namebox',
		));

		$this->nameboxVariantType->setType('characteristic_value', array());

		$product_id = hikamarket::getCID('product_id');
		$this->assignRef('product_id', $product_id);

		$subtask = JRequest::getCmd('subtask', '');
		if($subtask == 'duplicate') {

		}
		$this->assignRef('subtask', $subtask);


		$characteristics = array();
		if(!empty($product_id)) {
			$query = 'SELECT v.*, c.* FROM '.hikamarket::table('shop.variant').' AS v '.
				' INNER JOIN '.hikamarket::table('shop.characteristic').' as c ON v.variant_characteristic_id = c.characteristic_id '.
				' WHERE characteristic_parent_id = 0 AND variant_product_id = ' . (int)$product_id . ' ORDER BY ordering';
			$db->setQuery($query);
			$characteristics = $db->loadObjectList('characteristic_id');
		}
		$this->assignRef('characteristics', $characteristics);
	}

	public function waitingapproval($tpl = null) {
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$ctrl = '';
		$this->paramBase = HIKAMARKET_COMPONENT.'.'.$this->getName().'.waitingapproval';

		$vendor = hikamarket::loadVendor(true, false);
		$this->assignRef('vendor', $vendor);

		$config = hikamarket::config();
		$this->assignRef('config', $config);

		$this->loadRef(array(
			'toggleClass' => 'helper.toggle',
			'imageHelper' => 'shop.helper.image',
			'currencyHelper' => 'shop.class.currency',
			'childdisplayType' => 'shop.type.childdisplay',
			'shopCategoryType' => 'type.shop_category',
		));

		$manage = hikamarket::acl('product/edit');
		$this->assignRef('manage', $manage);

		$product_action_delete = hikamarket::acl('product/delete');
		$this->assignRef('product_action_delete', $product_action_delete);

		global $Itemid;
		$url_itemid = '';
		if(!empty($Itemid))
			$url_itemid = '&Itemid='.$Itemid;
		$this->assignRef('Itemid', $Itemid);

		$cfg = array(
			'table' => 'shop.product',
			'main_key' => 'product_id',
			'order_sql_value' => 'product.product_id'
		);

		if($vendor->vendor_id == 1) {
			$vendorType = hikamarket::get('type.filter_vendor');
			$this->assignRef('vendorType', $vendorType);
		}

		$default_sort_value = trim($config->get('product_listing_default_sort_value', $cfg['order_sql_value']));
		if(empty($default_sort_value))
			$default_sort_value = $cfg['order_sql_value'];
		$default_sort_dir = trim($config->get('product_listing_default_sort_dir', 'asc'));
		if(empty($default_sort_dir) || !in_array($default_sort_dir, array('asc', 'desc')))
			$default_sort_dir = 'asc';

		$pageInfo = $this->getPageInfo($default_sort_value, $default_sort_dir);

		$pageInfo->filter->vendors = $app->getUserStateFromRequest($this->paramBase.'.filter_vendors', 'filter_vendors', -1, 'int');

		$filters = array(
			'main' => 'product.product_parent_id = 0',
			'product_type' => 'product.product_type = \'waiting_approval\''
		);
		$searchMap = array(
			'product.product_name',
			'product.product_description',
			'product.product_id',
			'product.product_code'
		);
		$select = array();
		$join = '';

		if($pageInfo->filter->vendors >= 0 || $vendor->vendor_id > 1) {
			$select['parent_product_name'] = 'parent_product.product_name as parent_product_name';
			$join = ' LEFT JOIN '.hikamarket::table('shop.product').' AS parent_product ON product.product_parent_id = parent_product.product_id AND parent_product.product_vendor_id != product.product_vendor_id AND product.product_vendor_id > 0 ';
		}

		$fieldsClass = hikamarket::get('shop.class.field');
		$categories = array();
		$fields = $fieldsClass->getData('display:vendor_product_listing=1', 'product', false, $categories);
		$this->assignRef('fields', $fields);
		$this->assignRef('fieldsClass', $fieldsClass);

		foreach($fields as $fieldName => $oneExtraField) {
			$searchMap[] = 'product.' . $fieldName;
		}

		if($pageInfo->filter->vendors == 0 || $vendor->vendor_id > 1) {
			$filters['main'] .= ' OR parent_product.product_id != 0 ) AND (product.product_vendor_id = '.(int)$vendor->vendor_id;
			if($vendor->vendor_id == 1)
				$filters['main'] .= ' OR product.product_vendor_id = 0';
		} elseif( $pageInfo->filter->vendors > 1) {
			$filters['main'] .= ' OR parent_product.product_id != 0 ) AND (product.product_vendor_id = '.(int)$pageInfo->filter->vendors;
		}

		$order = '';
		$this->processFilters($filters, $order, $searchMap, array('product.'));

		$query = 'FROM '.hikamarket::table($cfg['table']).' AS product '.$join.$filters.$order;
		$db->setQuery('SELECT DISTINCT product.*' . (empty($select)?'':',') . implode(',', $select) . ' ' . $query, (int)$pageInfo->limit->start, (int)$pageInfo->limit->value);

		$rows = $db->loadObjectList();

		$products = array();
		foreach($rows as &$product) {
			$product->prices = array();
			$product->file_name = $product->product_name;
			if(!isset($products[$product->product_id])) {
				$products[$product->product_id] =& $product;
			} else if(!is_array($products[$product->product_id])) {
				$old =& $products[$product->product_id];
				unset($products[$product->product_id]);
				$products[$product->product_id] = array(&$old, &$product);
			} else {
				$products[$product->product_id][] =& $product;
			}
		}
		unset($product);
		$this->assignRef('products', $rows);

		$this->loadPricesImages($products);

		$db->setQuery('SELECT COUNT(DISTINCT(product.product_id)) '.$query);
		$pageInfo->elements = new stdClass();
		$pageInfo->elements->total = $db->loadResult();
		$pageInfo->elements->page = count($rows);

		$this->toolbar = array(
			'back' => array(
				'icon' => 'back',
				'name' => JText::_('HIKA_BACK'),
				'url' => hikamarket::completeLink('product&task=listing')
			),
		);

		$this->getPagination();

		$this->getOrdering('a.ordering', true);
		if(!empty($this->ordering->ordering)) {
			$this->toolbar['ordering']['display'] = true;
		}

		return true;
	}

	public function edit_translation() {
		$language_id = JRequest::getInt('language_id', 0);
		$this->assignRef('language_id', $language_id);

		$product_id = hikamarket::getCID('product_id');

		$config = hikamarket::config();
		$this->assignRef('config', $config);

		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$productClass = hikamarket::get('class.product');
		$product = $productClass->getRaw($product_id);

		$translationHelper = hikamarket::get('shop.helper.translation');
		if($translationHelper && $translationHelper->isMulti()) {
			$translationHelper->load('hikashop_product', @$product->product_id, $product, $language_id);
			$this->assignRef('translationHelper', $translationHelper);
		}

		$editor = hikamarket::get('shop.helper.editor');
		$editor->setEditor($config->get('editor', ''));
		$editor->content = @$product->product_description;
		$editor->height = 300;
		if($config->get('editor_disable_buttons', 0))
			$editor->options = false;
		$this->assignRef('editor', $editor);

		$toggle = hikamarket::get('helper.toggle');
		$this->assignRef('toggle', $toggle);

		$this->assignRef('product', $product);

		$this->toolbar = array(
			array(
				'url' => '#save',
				'linkattribs' => 'onclick="return window.hikamarket.submitform(\'save_translation\',\'hikamarket_translation_form\');"',
				'icon' => 'save',
				'name' => JText::_('HIKA_SAVE'), 'pos' => 'right'
			)
		);
	}

	public function image() {
		$file_id = (int)hikamarket::getCID();
		$this->assignRef('cid', $file_id);

		$config = hikamarket::config(false);
		$this->assignRef('config', $config);

		$element = null;
		if(!empty($file_id)){
			$fileClass = hikamarket::get('shop.class.file');
			$element = $fileClass->get($file_id);
		}
		$this->assignRef('element', $element);

		$product_id = JRequest::getInt('pid', 0);
		$this->assignRef('product_id', $product_id);

		$imageHelper = hikamarket::get('shop.helper.image');
		$this->assignRef('imageHelper', $imageHelper);

		$editor = hikamarket::get('shop.helper.editor');
		$editor->setEditor($config->get('editor', ''));
		$editor->name = 'file_description';
		$editor->content = @$element->file_description;
		$editor->height = 200;
		if($config->get('editor_disable_buttons', 0))
			$editor->options = false;
		$this->assignRef('editor', $editor);
	}

	public function file() {
		$file_id = (int)hikamarket::getCID();
		$this->assignRef('cid', $file_id);

		$config = hikamarket::config(false);
		$this->assignRef('config', $config);
		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$vendor = hikamarket::loadVendor(true);
		$this->assignRef('vendor', $vendor);

		$element = null;
		if(!empty($file_id)){
			$fileClass = hikamarket::get('shop.class.file');
			$element = $fileClass->get($file_id);
		}

		if(!empty($element)) {
			$firstChar = substr($element->file_path, 0, 1);
			$element->isVirtual = in_array($firstChar, array('#', '@'));
			$element->isLink = (substr($element->file_path, 0, 7) == 'http://' || substr($element->file_path, 0, 8) == 'https://');
		}

		$this->assignRef('element', $element);

		$product_id = JRequest::getInt('pid', 0);
		$this->assignRef('product_id', $product_id);

		$editor = hikamarket::get('shop.helper.editor');
		$editor->setEditor($config->get('editor', ''));
		$editor->name = 'file_description';
		$editor->content = @$element->file_description;
		$editor->height = 200;
		if($config->get('editor_disable_buttons', 0))
			$editor->options = false;
		$this->assignRef('editor', $editor);
	}

	public function addimage() {
		$files_id = JRequest::getVar('cid', array(), '', 'array');
		$product_id = JRequest::getInt('product_id', 0);

		$output = '[]';
		if(!empty($files_id)) {
			JArrayHelper::toInteger($files_id);
			$query = 'SELECT * FROM '.hikamarket::table('shop.file').' WHERE file_id IN ('.implode(',',$files_id).')';
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$files = $db->loadObjectList();

			$helperImage = hikamarket::get('shop.helper.image');
			$ret = array();
			foreach($files as $file) {

				$params = new stdClass();
				$params->product_id = $product_id;
				$params->file_id = $file->file_id;
				$params->file_path = $file->file_path;
				$params->file_name = $file->file_name;

				$ret[] = hikamarket::getLayout('productmarket', 'form_image_entry', $params, $js);
			}
			if(!empty($ret)) {
				$output = json_encode($ret);
			}
		}
		$js = 'window.hikashop.ready(function(){window.top.hikamarket.submitBox({images:'.$output.'});});';
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		return false;
	}

	public function galleryimage() {
		hikamarket::loadJslib('otree');

		$app = JFactory::getApplication();
		$config = hikamarket::config();
		$this->assignRef('config', $config);
		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$this->paramBase = HIKAMARKET_COMPONENT.'.'.$this->getName().'.gallery';

		$vendor = hikamarket::loadVendor(true);
		$uploadFolder = ltrim(JPath::clean(html_entity_decode($shopConfig->get('uploadfolder'))),DS);
		$uploadFolder = rtrim($uploadFolder,DS).DS;
		$basePath = JPATH_ROOT.DS.$uploadFolder.DS;
		if($vendor->vendor_id > 1) {
			$basePath .= 'vendor' . $vendor->vendor_id . DS;
		}

		$pageInfo = new stdClass();
		$pageInfo->limit = new stdClass();
		$pageInfo->limit->value = $app->getUserStateFromRequest( $this->paramBase.'.list_limit', 'limit', 20, 'int' );
		$pageInfo->limit->start = $app->getUserStateFromRequest( $this->paramBase.'.limitstart', 'limitstart', 0, 'int' );
		$pageInfo->search = $app->getUserStateFromRequest( $this->paramBase.'.search', 'search', '', 'string');

		$this->assignRef('pageInfo', $pageInfo);

		jimport('joomla.filesystem.folder');
		if(!JFolder::exists($basePath))
			JFolder::create($basePath);

		$galleryHelper = hikamarket::get('shop.helper.gallery');
		$galleryHelper->setRoot($basePath);
		$this->assignRef('galleryHelper', $galleryHelper);

		$folder = str_replace('|', '/', JRequest::getString('folder', ''));
		$destFolder = rtrim($folder, '/\\');
		if(!$galleryHelper->validatePath($destFolder))
			$destFolder = '';
		if(!empty($destFolder)) $destFolder .= '/';
		$this->assignRef('destFolder', $destFolder);

		$galleryOptions = array(
			'filter' => '.*' . str_replace(array('.','?','*','$','^'), array('\.','\?','\*','$','\^'), $pageInfo->search) . '.*',
			'offset' => $pageInfo->limit->start,
			'length' => $pageInfo->limit->value
		);
		$this->assignRef('galleryOptions', $galleryOptions);

		$treeContent = $galleryHelper->getTreeList(null, $destFolder);
		$this->assignRef('treeContent', $treeContent);

		$dirContent = $galleryHelper->getDirContent($destFolder, $galleryOptions);
		$this->assignRef('dirContent', $dirContent);

		jimport('joomla.html.pagination');
		$pagination = new JPagination( $galleryHelper->filecount, $pageInfo->limit->start, $pageInfo->limit->value );
		$this->assignRef('pagination', $pagination);
	}

	public function addfile() {
		$file_id = (int)hikamarket::getCID();
		$js = 'window.hikashop.ready(function(){window.parent.hikamarket.submitBox({cid:'.$file_id.'});});';
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		return false;
	}

	public function form_image_entry() {
		if(empty($this->popup)) {
			$popup = hikamarket::get('shop.helper.popup');
			$this->assignRef('popup', $popup);
		}
		$config = hikamarket::config(false);
		$this->assignRef('config', $config);
		$imageHelper = hikamarket::get('shop.helper.image');
		$this->assignRef('imageHelper', $imageHelper);
	}

	public function form_file_entry() {
		$file_id = (int)hikamarket::getCID();
		$this->assignRef('cid', $file_id);

		$product_id = JRequest::getInt('pid', 0);
		$this->assignRef('product_id', $product_id);

		$config = hikamarket::config(false);
		$this->assignRef('config', $config);
		$shopConfig = hikamarket::config(false);
		$this->assignRef('shopConfig', $shopConfig);

		$vendor = hikamarket::loadVendor(true);
		$this->assignRef('vendor', $vendor);

		if(empty($this->popup)) {
			$popup = hikamarket::get('shop.helper.popup');
			$this->assignRef('popup', $popup);
		}

		if(empty($this->params) && empty($this->params->file_id)) {
			$element = new stdClass();
			if(!empty($file_id)){
				$fileClass = hikamarket::get('shop.class.file');
				$element = $fileClass->get($file_id);
			}
			$element->product_id = $product_id;

			if(!empty($element->product_id)) {
				$productClass = hikamarket::get('shop.class.product');
				$product = $productClass->get((int)$element->product_id);
				$element->product_type = $product->product_type;
			}

			$this->assignRef('params', $element);
		}

		if($vendor->vendor_id > 1) {
			if(!empty($this->params->file_path) && strpos($this->params->file_path, '/') !== false) {
				$this->params->file_path = substr($this->params->file_path, strrpos($this->params->file_path, '/')+1);
			}
		}
	}


	public function import() {
		$this->loadRef(array(
			'importHelper' => 'helper.import',
			'uploaderType' => 'shop.type.uploader', // TODO use "type.upload" and a new display function
		));

		$this->toolbar = array(
			'back' => array(
				'icon' => 'back',
				'name' => JText::_('HIKA_BACK'),
				'url' => hikamarket::completeLink('product')
			),
		);
	}
}
