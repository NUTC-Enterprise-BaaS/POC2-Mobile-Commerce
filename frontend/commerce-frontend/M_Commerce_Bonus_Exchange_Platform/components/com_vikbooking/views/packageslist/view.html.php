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


jimport('joomla.application.component.view');

class VikbookingViewPackageslist extends JViewLegacy {
	function display($tpl = null) {
		vikbooking::prepareViewContent();
		$dbo = JFactory::getDBO();
		$vbo_tn = vikbooking::getTranslator();
		$psortby = JRequest::getString('sortby', '', 'request');
		$psortby = !in_array($psortby, array('cost', 'name', 'id', 'dfrom')) ? 'dfrom' : $psortby;
		$psorttype = JRequest::getString('sorttype', '', 'request');
		$psorttype = $psorttype == 'desc' ? 'DESC' : 'ASC';
		$preslim = JRequest::getInt('reslim', '', 'request');
		$preslim = empty($preslim) || $preslim < 1 ? 20 : $preslim;
		$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
		
		$packages = array();
		$navig = '';

		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_packages` WHERE `dto`>=".time()." ORDER BY `".$psortby."` ".$psorttype;
		$dbo->setQuery($q, $lim0, $preslim);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$packages = $dbo->loadAssocList();
			//pagination
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination($dbo->loadResult(), $lim0, $preslim);
			$navig = $pageNav->getPagesLinks();
			//
			$vbo_tn->translateContents($packages, '#__vikbooking_packages');
			foreach ($packages as $pk => $pv) {
				$q = "SELECT `pr`.`idroom`,`r`.`name`,`r`.`img`,`r`.`fromadult`,`r`.`toadult`,`r`.`fromchild`,`r`.`tochild`,`r`.`totpeople`,`r`.`params` FROM `#__vikbooking_packages_rooms` AS `pr` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`pr`.`idroom` AND `r`.`avail`=1 WHERE `pr`.`idpackage`=".(int)$pv['id'].";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$pkg_rooms = $dbo->loadAssocList();
					$vbo_tn->translateContents($pkg_rooms, '#__vikbooking_rooms', array('id' => 'idroom'));
					$packages[$pk]['rooms'] = $pkg_rooms;
				}
			}
		}
		$this->assignRef('packages', $packages);
		$this->assignRef('navig', $navig);
		$this->assignRef('vbo_tn', $vbo_tn);
		//theme
		$theme = vikbooking::getTheme();
		if($theme != 'default') {
			$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'packageslist';
			if(is_dir($thdir)) {
				$this->_setPath('template', $thdir.DS);
			}
		}
		//
		parent::display($tpl);
	}
}
?>