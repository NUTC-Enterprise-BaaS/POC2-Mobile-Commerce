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

defined("_JEXEC") or die('Restricted Access');

defined("VBO_ROUTER_DEBUG") or define("VBO_ROUTER_DEBUG", false);
defined("VBO_ROUTER_BUILD_DEBUG") or define("VBO_ROUTER_BUILD_DEBUG", false);

class VikBookingRouter {

	private $debug = false;
	private $build_debug = false;

	public function __construct($debug=false, $build_debug=false) {
		$this->debug = $debug;
		$this->build_debug = $build_debug;
	}

	public function build(&$query) {

		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$active = $menu->getActive();

		$dbo = JFactory::getDBO();

		$segments = array();

		if($this->build_debug) {
			echo '<div style="border: 1px solid #f00;padding: 5px;margin: 5px;">';
			echo '<pre>'.print_r($_REQUEST, true).'</pre><br/>';echo '<pre>'.print_r($query, true).'</pre><br/>';echo '<pre>'.print_r($active, true).'</pre><br/>';
			echo '</div>';
		}

		if( isset($query['view']) ) {

			if( $query['view'] == 'roomdetails' && isset($query['roomid']) ) {
				
				if( empty($active->query['view']) ) {
					$segments[] = $query['view'];
				}

				$q = "SELECT `id`,`alias` FROM `#__vikbooking_rooms` WHERE `id`=".intval($query['roomid'])." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() > 0 ) {
					if( $active->query['view'] != 'roomdetails' ) {
						$room_data = $dbo->loadAssoc();
						$segments[] = $this->renderTag( $room_data['alias'] );
					}
					unset($query['roomid']);
				}

				unset($query['view']);

			}elseif( $query['view'] == 'searchdetails' && isset($query['roomid']) ) {

				if( empty($active->query['view']) ) {
					$segments[] = $query['view'];
				}

				$q = "SELECT `id`,`alias` FROM `#__vikbooking_rooms` WHERE `id`=".intval($query['roomid'])." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() > 0 ) {
					if( $active->query['view'] != 'searchdetails' ) {
						$room_data = $dbo->loadAssoc();
						$segments[] = $this->renderTag( $room_data['alias'] );
						$segments[] = 'searchdetails';
					}
					unset($query['roomid']);
				}

				unset($query['view']);

			}elseif( $query['view'] == 'packagedetails' && isset($query['pkgid']) ) {

				if( empty($active->query['view']) ) {
					$segments[] = $query['view'];
				}

				$q = "SELECT `id`,`alias` FROM `#__vikbooking_packages` WHERE `id`=".intval($query['pkgid'])." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() > 0 ) {
					$pkg_data = $dbo->loadAssoc();
					$segments[] = $this->renderTag( $pkg_data['alias'] );
					if( $active->query['view'] != 'packageslist' ) {
						$segments[] = 'packagedetails';
					}
					unset($query['pkgid']);
				}

				unset($query['view']);

			}

		}

		return $segments;
	}

	public function parse($segments) {
		$total = count($segments);
		
		$dbo = JFactory::getDBO();

		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$active = $menu->getActive();
		
		$query_view = ( empty($active->query['view']) ? '' : $active->query['view'] );
	
		$vars = array();
		
		if($this->debug) {
			echo '$query_view: '.$query_view.'<br/><pre>'.print_r($_REQUEST, true).'</pre><br/>';echo '<pre>'.print_r($active, true).'</pre><br/>';echo '<pre>'.print_r($segments, true).'</pre><br/>';
		}

		if( $total > 0 ) {
			if( ($query_view == 'roomslist' || $query_view == 'promotions' || $query_view == 'availability')  && !in_array('searchdetails', $segments) ) {
				$vars['view'] = 'roomdetails';
				$itemid = $this->getProperItemID($menu, $vars['view']);
				if( !empty($itemid) ) {
					$vars['Itemid'] = $itemid;
				}
				$q = "SELECT `id` FROM `#__vikbooking_rooms` WHERE `alias`=".$dbo->quote($this->aliasNoSlug($segments[0]))." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() > 0 ) {
					$vars['roomid'] = $dbo->loadResult();
				}   
			}elseif( ($query_view == 'vikbooking' || $query_view == 'roomslist' || $query_view == 'promotions' || $query_view == 'roomdetails' || $query_view == 'availability') && in_array('searchdetails', $segments) ) {
				$vars['view'] = 'searchdetails';
				$itemid = $this->getProperItemID($menu, $vars['view']);
				if( !empty($itemid) ) {
					$vars['Itemid'] = $itemid;
				}
				$q = "SELECT `id` FROM `#__vikbooking_rooms` WHERE `alias`=".$dbo->quote($this->aliasNoSlug($segments[0]))." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() > 0 ) {
					$vars['roomid'] = $dbo->loadResult();
				}
			}elseif($query_view == 'packageslist' || in_array('packagedetails', $segments)) {
				$vars['view'] = 'packagedetails';
				$itemid = $this->getProperItemID($menu, $vars['view']);
				if( !empty($itemid) ) {
					$vars['Itemid'] = $itemid;
				}
				$q = "SELECT `id` FROM `#__vikbooking_packages` WHERE `alias`=".$dbo->quote($this->aliasNoSlug($segments[0]))." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if( $dbo->getNumRows() > 0 ) {
					$vars['pkgid'] = $dbo->loadResult();
				}
			}elseif( empty($query_view) && in_array('searchdetails', $segments) ) {
				//Search results page with no Itemid, maybe a module in the Home Page. Set view = searchdetails
				$room_alias = $this->getAliasFromSegments($segments);
				if(!empty($room_alias)) {
					$vars['view'] = 'searchdetails';
					$q = "SELECT `id` FROM `#__vikbooking_rooms` WHERE `alias`=".$dbo->quote($this->aliasNoSlug($room_alias))." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if( $dbo->getNumRows() > 0 ) {
						$vars['roomid'] = $dbo->loadResult();
					}
				}
			}
		}

		return $vars;
	}

	private function renderTag($str) {
		$str = JFilterOutput::stringURLSafe($str);
		return $str;
	}

	private function aliasNoSlug($alias) {
		$name = str_replace(':', '-', $alias);
		return trim($name);
	}

	private function getAliasFromSegments($segments) {
		foreach ($segments as $value) {
			if(strpos($value, ':') !== false) {
				return $value;
			}
		}
		return '';
	}
	
	private function getProperItemID($menu, $itemtype) {
		foreach( $menu->getMenu() as $itemid => $item ) {
			if( $item->query['option'] == 'com_vikbooking' && $item->query['view'] == $itemtype ) {
				return $itemid;
			}
		}
		return 0;
	}

}

/**
*
*
*/

function vikbookingBuildRoute(&$query) {
	$router = new VikBookingRouter(VBO_ROUTER_DEBUG, VBO_ROUTER_BUILD_DEBUG);
	return $router->build($query);
}

function vikbookingParseRoute($segments) {
	$router = new VikBookingRouter(VBO_ROUTER_DEBUG, VBO_ROUTER_BUILD_DEBUG);
	return $router->parse($segments);
}

?>