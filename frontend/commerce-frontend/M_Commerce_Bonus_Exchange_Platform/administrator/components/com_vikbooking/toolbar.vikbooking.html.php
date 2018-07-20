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

defined('_JEXEC') or die('Restricted access');


class TOOLBAR_vikbooking{
	public static function DEFAULT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINDASHBOARDTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.admin', 'com_vikbooking')) {
			JToolBarHelper::preferences('com_vikbooking');
		}
	}
	
	public static function ROOMS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINDEAFULTTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newroom', JText::_('VBMAINDEFAULTNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editroom', JText::_('VBMAINDEFAULTEDITC'));
			JToolBarHelper::spacer();
			JToolBarHelper::editList('viewtariffe', JText::_('VBMAINDEFAULTEDITT'));
			JToolBarHelper::spacer();
			JToolBarHelper::custom( 'calendar', 'calendar', 'calendar', JText::_('VBMAINDEFAULTCAL'), true, false);
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removeroom', JText::_('VBMAINDEFAULTDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function CUSTOMF_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCUSTOMFTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newcustomf', JText::_('VBMAINCUSTOMFNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editcustomf', JText::_('VBMAINCUSTOMFEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removecustomf', JText::_('VBMAINCUSTOMFDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function COUPON_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCOUPONTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newcoupon', JText::_('VBMAINCOUPONNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editcoupon', JText::_('VBMAINCOUPONEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removecoupons', JText::_('VBMAINCOUPONDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function SEASONS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINSEASONSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newseason', JText::_('VBMAINSEASONSNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editseason', JText::_('VBMAINSEASONSEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removeseasons', JText::_('VBMAINSEASONSDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function PAYMENTS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPAYMENTSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newpayment', JText::_('VBMAINPAYMENTSNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editpayment', JText::_('VBMAINPAYMENTSEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removepayments', JText::_('VBMAINPAYMENTSDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function IVA_MENU() {
		JToolBarHelper::title(JText::_('VBMAINIVATITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newiva', JText::_('VBMAINIVANEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editiva', JText::_('VBMAINIVAEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removeiva', JText::_('VBMAINIVADEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function CAT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCATTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newcat', JText::_('VBMAINCATNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editcat', JText::_('VBMAINCATEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removecat', JText::_('VBMAINCATDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function CARAT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCARATTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newcarat', JText::_('VBMAINCARATNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editcarat', JText::_('VBMAINCARATEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removecarat', JText::_('VBMAINCARATDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function OPTIONALS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINOPTTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newoptionals', JText::_('VBMAINOPTNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editoptional', JText::_('VBMAINOPTEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removeoptionals', JText::_('VBMAINOPTDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function PRICE_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPRICETITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newprice', JText::_('VBMAINPRICENEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editprice', JText::_('VBMAINPRICEEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removeprice', JText::_('VBMAINPRICEDEL'));
			JToolBarHelper::spacer();
		}
	}
	
	public static function NEWCUSTOMF_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCUSTOMFTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createcustomf', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcustomf', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITCUSTOMF_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCUSTOMFTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updatecustomf', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcustomf', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWCOUPON_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCOUPONTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createcoupon', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcoupon', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITCOUPON_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCOUPONTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updatecoupon', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcoupon', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWSEASON_MENU() {
		JToolBarHelper::title(JText::_('VBMAINSEASONTITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createseason', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
			JToolBarHelper::custom('createseason_new', 'save-new', 'save-new', JText::_('VBSAVENEW'), false, false);
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelseason', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITSEASON_MENU() {
		JToolBarHelper::title(JText::_('VBMAINSEASONTITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updateseasonstay', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
			JToolBarHelper::save( 'updateseason', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelseason', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWPAYMENT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPAYMENTTITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createpayment', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelpayment', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITPAYMENT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPAYMENTTITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updatepayment', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelpayment', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWIVA_MENU() {
		JToolBarHelper::title(JText::_('VBMAINIVATITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createiva', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'canceliva', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITIVA_MENU() {
		JToolBarHelper::title(JText::_('VBMAINIVATITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updateiva', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'canceliva', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWPRICE_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPRICETITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createprice', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelprice', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITPRICE_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPRICETITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updateprice', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelprice', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWCAT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCATTITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createcat', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcat', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITCAT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCATTITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updatecat', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcat', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWCARAT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCARATTITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createcarat', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcarat', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITCARAT_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCARATTITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updatecarat', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcarat', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWOPTIONAL_MENU() {
		JToolBarHelper::title(JText::_('VBMAINOPTTITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createoptionals', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'canceloptionals', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITOPTIONAL_MENU() {
		JToolBarHelper::title(JText::_('VBMAINOPTTITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updateoptional', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'canceloptionals', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function NEWROOM_MENU() {
		JToolBarHelper::title(JText::_('VBMAINROOMTITLENEW'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createroom', JText::_('VBSAVECLOSE'));
			JToolBarHelper::apply( 'createroomstay', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITROOM_MENU() {
		JToolBarHelper::title(JText::_('VBMAINROOMTITLEEDIT'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updateroomstay', JText::_('VBSAVE'));
			JToolBarHelper::save( 'updateroom', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function TARIFFE_MENU() {
		JToolBarHelper::title(JText::_('VBMAINTARIFFETITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removetariffe', JText::_('VBMAINTARIFFEDEL'));
			JToolBarHelper::spacer();
			JToolBarHelper::spacer();
		}
		JToolBarHelper::save( 'cancel', JText::_('VBMAINTARIFFEBACK'));
		JToolBarHelper::spacer();
	}
	
	public static function ORDERS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINORDERTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editorder', JText::_('VBMAINORDEREDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.vbo.management', 'com_vikbooking')) {
			JToolBarHelper::custom( 'vieworders', 'file-2', 'file-2', JText::_('VBOGENINVOICES'), true);
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removeorders', JText::_('VBMAINORDERDEL'));
			JToolBarHelper::spacer();
			JToolBarHelper::spacer();
		}
	}
	
	public static function EDITORDER_MENU() {
		JToolBarHelper::title(JText::_('VBMAINORDERTITLEEDIT'), 'vikbooking');
		JToolBarHelper::cancel( 'canceledorder', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}
	
	public static function CALENDAR_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCALTITLE'), 'vikbooking');
		JToolBarHelper::cancel( 'canceledorder', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}
	
	public static function EBUSY_MENU() {
		JToolBarHelper::title(JText::_('VBMAINEBUSYTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updatebusy', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::custom( 'removebusy', 'delete', 'delete', JText::_('VBMAINEBUSYDEL'), false, false);
			JToolBarHelper::spacer();
		}
		$pgoto = JRequest::getString('goto', '', 'request');
		JToolBarHelper::cancel( ($pgoto == 'overview' ? 'canceloverview' : 'cancelbusy'), JText::_('VBBACK'));
		$pvcm = JRequest::getInt('vcm', '', 'request');
		if($pvcm == 1) {
			JToolBarHelper::custom( 'cancelbusyvcm', 'back', 'back', JText::_('VBBACKVCM'), false, false);
		}
		JToolBarHelper::spacer();
	}
	
	public static function CONFIG_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCONFIGTITLE'), 'vikbookingconfig');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'saveconfig', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function CHOOSEBUSY_MENU() {
		$dbo = JFactory::getDBO();
		$pgoto = JRequest::getString('goto', '', 'request');
		$pts = JRequest::getInt('ts', '', 'request');
		$pidroom = JRequest::getInt('idroom', '', 'request');
		$q="SELECT `name` FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($pidroom).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$cname=$dbo->loadResult();
		JToolBarHelper::title(JText::_('VBMAINCHOOSEBUSY')." ".$cname.", ".date('Y-M-d', $pts), 'vikbooking');
		JToolBarHelper::cancel( ($pgoto == 'overview' ? 'canceloverview' : 'cancelcalendar'), JText::_('VBBACK'));
		$pvcm = JRequest::getInt('vcm', '', 'request');
		if($pvcm == 1) {
			JToolBarHelper::custom( 'cancelbusyvcm', 'back', 'back', JText::_('VBBACKVCM'), false, false);
		}
		JToolBarHelper::spacer();
	}
	
	public static function OVERVIEW_MENU() {
		JToolBarHelper::title(JText::_('VBMAINOVERVIEWTITLE'), 'vikbooking');
		JToolBarHelper::cancel( 'canceledorder', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}
	
	public static function RESTRICTIONS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINRESTRICTIONSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newrestriction', JText::_('VBMAINRESTRICTIONNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editrestriction', JText::_('VBMAINRESTRICTIONEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removerestrictions', JText::_('VBMAINRESTRICTIONDEL'));
			JToolBarHelper::spacer();
		}
	}

	public static function NEWRESTRICTION_MENU() {
		JToolBarHelper::title(JText::_('VBMAINNEWRESTRICTIONTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save( 'createrestriction', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelrestriction', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}
	
	public static function EDITRESTRICTION_MENU() {
		JToolBarHelper::title(JText::_('VBMAINEDITRESTRICTIONTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::save( 'updaterestriction', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelrestriction', JText::_('VBANNULLA'));
		JToolBarHelper::spacer();
	}

	public static function RATESOVERVIEW_MENU() {
		JToolBarHelper::title(JText::_('VBMAINRATESOVERVIEWTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newseason', JText::_('VBMAINSEASONSNEW'));
			JToolBarHelper::spacer();
			JToolBarHelper::addNew('newrestriction', JText::_('VBMAINRESTRICTIONNEW'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function TRANSLATIONS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINTRANSLATIONSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking') || JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'savetranslationstay', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
			JToolBarHelper::save( 'savetranslation', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function CUSTOMERS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCUSTOMERSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newcustomer', JText::_('VBMAINCUSTOMERNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editcustomer', JText::_('VBMAINCUSTOMEREDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removecustomers', JText::_('VBMAINCUSTOMERDEL'));
			JToolBarHelper::spacer();
		}
	}

	public static function NEWCUSTOMER_MENU() {
		JToolBarHelper::title(JText::_('VBMAINMANAGECUSTOMERTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::save('savecustomer', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcustomer', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function EDITCUSTOMER_MENU() {
		JToolBarHelper::title(JText::_('VBMAINMANAGECUSTOMERTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updatecustomerstay', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
			JToolBarHelper::save( 'updatecustomer', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcustomer', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function PACKAGES_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPACKAGESTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newpackage', JText::_('VBMAINPACKAGENEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editpackage', JText::_('VBMAINPACKAGEEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removepackages', JText::_('VBMAINPACKAGEDEL'));
			JToolBarHelper::spacer();
		}
	}

	public static function NEWPACKAGE_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPACKAGESTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::apply( 'createpackagestay', JText::_('VBSAVE'));
			JToolBarHelper::save('createpackage', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelpackages', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function EDITPACKAGE_MENU() {
		JToolBarHelper::title(JText::_('VBMAINPACKAGESTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updatepackagestay', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
			JToolBarHelper::save( 'updatepackage', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelpackages', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function CRONS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCRONSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::addNew('newcron', JText::_('VBMAINCRONNEW'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::editList('editcron', JText::_('VBMAINCRONEDIT'));
			JToolBarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_vikbooking')) {
			JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removecrons', JText::_('VBMAINCRONDEL'));
			JToolBarHelper::spacer();
		}
	}

	public static function NEWCRON_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCRONSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.create', 'com_vikbooking')) {
			JToolBarHelper::apply( 'createcronstay', JText::_('VBSAVE'));
			JToolBarHelper::save('createcron', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcrons', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function EDITCRON_MENU() {
		JToolBarHelper::title(JText::_('VBMAINCRONSTITLE'), 'vikbooking');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikbooking')) {
			JToolBarHelper::apply( 'updatecronstay', JText::_('VBSAVE'));
			JToolBarHelper::spacer();
			JToolBarHelper::save( 'updatecron', JText::_('VBSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancelcrons', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function STATS_MENU() {
		JToolBarHelper::title(JText::_('VBMAINSTATSTITLE'), 'vikbookingstats');
		JToolBarHelper::cancel( 'canceledorder', JText::_('VBBACK'));
		JToolBarHelper::spacer();
	}

	public static function INVOICES_MENU() {
		JToolBarHelper::title(JText::_('VBMAININVOICESTITLE'), 'vikbooking');
		JToolBarHelper::custom('downloadinvoices', 'download', 'download', JText::_('VBMAININVOICESDOWNLOAD'), true, false);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('resendinvoices', 'mail', 'mail', JText::_('VBMAININVOICESRESEND'), true, false);
		JToolBarHelper::spacer();
		JToolBarHelper::deleteList(JText::_('VBDELCONFIRM'), 'removeinvoices', JText::_('VBMAININVOICESDEL'));
		JToolBarHelper::spacer();
	}
	
}
?>