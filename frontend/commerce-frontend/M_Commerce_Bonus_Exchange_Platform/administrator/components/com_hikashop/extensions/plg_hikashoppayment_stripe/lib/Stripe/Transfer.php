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

class Stripe_Transfer extends Stripe_ApiResource
{
  public static function retrieve($id, $apiKey=null)
  {
	$class = get_class();
	return self::_scopedRetrieve($class, $id, $apiKey);
  }

  public static function all($params=null, $apiKey=null)
  {
	$class = get_class();
	return self::_scopedAll($class, $params, $apiKey);
  }

  public static function create($params=null, $apiKey=null)
  {
	$class = get_class();
	return self::_scopedCreate($class, $params, $apiKey);
  }

  public function cancel()
  {
	$requestor = new Stripe_ApiRequestor($this->_apiKey);
	$url = $this->instanceUrl() . '/cancel';
	list($response, $apiKey) = $requestor->request('post', $url);
	$this->refreshFrom($response, $apiKey);
	return $this;
  }

  public function save()
  {
	$class = get_class();
	return self::_scopedSave($class);
  }

}
