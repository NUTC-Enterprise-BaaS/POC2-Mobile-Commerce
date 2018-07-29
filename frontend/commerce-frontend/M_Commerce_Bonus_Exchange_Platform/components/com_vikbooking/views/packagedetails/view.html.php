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

class VikbookingViewPackagedetails extends JViewLegacy {
	function display($tpl = null) {
		$dbo = JFactory::getDBO();
		$vbo_tn = vikbooking::getTranslator();
		$pkgid = JRequest::getInt('pkgid', '', 'request');
		$pitemid = JRequest::getInt('Itemid', '', 'request');
		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_packages` WHERE `id`='".(int)$pkgid."' AND `dto`>=".time().";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$package = $dbo->loadAssoc();
			$vbo_tn->translateContents($package, '#__vikbooking_packages');
			$q = "SELECT `pr`.`idroom`,`r`.`name`,`r`.`img`,`r`.`units`,`r`.`moreimgs`,`r`.`fromadult`,`r`.`toadult`,`r`.`fromchild`,`r`.`tochild`,`r`.`smalldesc`,`r`.`totpeople`,`r`.`mintotpeople`,`r`.`params`,`r`.`imgcaptions` FROM `#__vikbooking_packages_rooms` AS `pr` LEFT JOIN `#__vikbooking_rooms` `r` ON `r`.`id`=`pr`.`idroom` AND `r`.`avail`=1 WHERE `pr`.`idpackage`=".(int)$package['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$pkg_rooms = $dbo->loadAssocList();
				$vbo_tn->translateContents($pkg_rooms, '#__vikbooking_rooms', array('id' => 'idroom'));
				$package['rooms'] = $pkg_rooms;
			}
			$this->assignRef('package', $package);
			$this->assignRef('vbo_tn', $vbo_tn);
			//theme
			$theme = vikbooking::getTheme();
			if($theme != 'default') {
				$thdir = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.$theme.DS.'packagedetails';
				if(is_dir($thdir)) {
					$this->_setPath('template', $thdir.DS);
				}
			}
			//
			parent::display($tpl);
		}else {
			$mainframe = JFactory::getApplication();
			//no need to set an error as it was probably already raised
			//JError::raiseWarning('', JText::_('VBOPKGNOTFOUND'));
			$mainframe->redirect(JRoute::_("index.php?option=com_vikbooking&view=packageslist".(!empty($pitemid) ? "&Itemid=".$pitemid : ""), false));
			exit;
		}
	}
}
?>