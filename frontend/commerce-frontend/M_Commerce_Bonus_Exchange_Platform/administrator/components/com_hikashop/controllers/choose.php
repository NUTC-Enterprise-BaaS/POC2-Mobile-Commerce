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
class ChooseController extends hikashopController{
	function __construct(){
		parent::__construct();
		$this->display[]='searchfields';
		$this->display[]='filters';
	}
	function searchfields(){
		JRequest::setVar( 'layout', 'searchfields'  );
		return parent::display();
	}
	function filters(){
		JRequest::setVar( 'layout', 'filters'  );
		return parent::display();
	}
}
