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


//Joomla 3.0
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
//

require_once(JPATH_ADMINISTRATOR . DS . "components". DS ."com_vikbooking" . DS . 'toolbar.vikbooking.html.php');

switch ( $task ) {		
	case 'viewroom':
		TOOLBAR_vikbooking::DEFAULT_MENU();
		break;
	case 'seasons':
		TOOLBAR_vikbooking::SEASONS_MENU();
		break;
	case 'payments':
		TOOLBAR_vikbooking::PAYMENTS_MENU();
		break;			
	case 'viewiva':
		TOOLBAR_vikbooking::IVA_MENU();
		break;	
	case 'viewcategories':
		TOOLBAR_vikbooking::CAT_MENU();
		break;	
	case 'viewprices':
		TOOLBAR_vikbooking::PRICE_MENU();
		break;
	case 'viewcarat':
		TOOLBAR_vikbooking::CARAT_MENU();
		break;
	case 'viewoptionals':
		TOOLBAR_vikbooking::OPTIONALS_MENU();
		break;			
	case 'editseason':
		TOOLBAR_vikbooking::EDITSEASON_MENU();
		break;
	case 'newseason':
		TOOLBAR_vikbooking::NEWSEASON_MENU();
		break;
	case 'editpayment':
		TOOLBAR_vikbooking::EDITPAYMENT_MENU();
		break;
	case 'newpayment':
		TOOLBAR_vikbooking::NEWPAYMENT_MENU();
		break;				
	case 'newiva':
		TOOLBAR_vikbooking::NEWIVA_MENU();
		break;
	case 'editiva':
		TOOLBAR_vikbooking::EDITIVA_MENU();
		break;		
	case 'newprice':
		TOOLBAR_vikbooking::NEWPRICE_MENU();
		break;
	case 'editprice':
		TOOLBAR_vikbooking::EDITPRICE_MENU();
		break;	
	case 'newcat':
		TOOLBAR_vikbooking::NEWCAT_MENU();
		break;
	case 'editcat':
		TOOLBAR_vikbooking::EDITCAT_MENU();
		break;	
	case 'newcarat':
		TOOLBAR_vikbooking::NEWCARAT_MENU();
		break;
	case 'editcarat':
		TOOLBAR_vikbooking::EDITCARAT_MENU();
		break;	
	case 'newoptionals':
		TOOLBAR_vikbooking::NEWOPTIONAL_MENU();
		break;
	case 'editoptional':
		TOOLBAR_vikbooking::EDITOPTIONAL_MENU();
		break;	
	case 'newroom':
		TOOLBAR_vikbooking::NEWROOM_MENU();
		break;
	case 'editroom':
		TOOLBAR_vikbooking::EDITROOM_MENU();
		break;	
	case 'viewtariffe':
		TOOLBAR_vikbooking::TARIFFE_MENU();
		break;
	case 'vieworders':
		TOOLBAR_vikbooking::ORDERS_MENU();
		break;
	case 'editorder':
		TOOLBAR_vikbooking::EDITORDER_MENU();
		break;
	case 'calendar':
		TOOLBAR_vikbooking::CALENDAR_MENU();
		break;
	case 'editbusy':
		TOOLBAR_vikbooking::EBUSY_MENU();
		break;		
	case 'config':
		TOOLBAR_vikbooking::CONFIG_MENU();
		break;
	case 'choosebusy':
		TOOLBAR_vikbooking::CHOOSEBUSY_MENU();
		break;
	case 'overview':
		TOOLBAR_vikbooking::OVERVIEW_MENU();
		break;
	case 'viewcustomf':
		TOOLBAR_vikbooking::CUSTOMF_MENU();
		break;
	case 'newcustomf':
		TOOLBAR_vikbooking::NEWCUSTOMF_MENU();
		break;
	case 'editcustomf':
		TOOLBAR_vikbooking::EDITCUSTOMF_MENU();
		break;
	case 'viewcoupons':
		TOOLBAR_vikbooking::COUPON_MENU();
		break;
	case 'newcoupon':
		TOOLBAR_vikbooking::NEWCOUPON_MENU();
		break;
	case 'editcoupon':
		TOOLBAR_vikbooking::EDITCOUPON_MENU();
		break;
	case 'rooms':
		TOOLBAR_vikbooking::ROOMS_MENU();
		break;
	case 'restrictions':
		TOOLBAR_vikbooking::RESTRICTIONS_MENU();
		break;
	case 'newrestriction':
		TOOLBAR_vikbooking::NEWRESTRICTION_MENU();
		break;
	case 'editrestriction':
		TOOLBAR_vikbooking::EDITRESTRICTION_MENU();
		break;
	case 'ratesoverview':
		TOOLBAR_vikbooking::RATESOVERVIEW_MENU();
		break;
	case 'translations':
		TOOLBAR_vikbooking::TRANSLATIONS_MENU();
		break;
	case 'customers':
		TOOLBAR_vikbooking::CUSTOMERS_MENU();
		break;
	case 'newcustomer':
		TOOLBAR_vikbooking::NEWCUSTOMER_MENU();
		break;
	case 'editcustomer':
		TOOLBAR_vikbooking::EDITCUSTOMER_MENU();
		break;
	case 'packages':
		TOOLBAR_vikbooking::PACKAGES_MENU();
		break;
	case 'newpackage':
		TOOLBAR_vikbooking::NEWPACKAGE_MENU();
		break;
	case 'editpackage':
		TOOLBAR_vikbooking::EDITPACKAGE_MENU();
		break;
	case 'stats':
		TOOLBAR_vikbooking::STATS_MENU();
		break;
	case 'crons':
		TOOLBAR_vikbooking::CRONS_MENU();
		break;
	case 'newcron':
		TOOLBAR_vikbooking::NEWCRON_MENU();
		break;
	case 'editcron':
		TOOLBAR_vikbooking::EDITCRON_MENU();
		break;
	case 'invoices':
		TOOLBAR_vikbooking::INVOICES_MENU();
		break;
	default:
		TOOLBAR_vikbooking::DEFAULT_MENU();
		break;
}
?>