<?php
/**------------------------------------------------------------------------
 * mod_vikbooking_horizontalsearch - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/
 
// no direct access
defined('_JEXEC') or die('Restricted Area');

//Joomla 3.0
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
//

require_once (dirname(__FILE__).DS.'helper.php');

$params->def('showcat', 2);

$vrtext  = modVikbooking_horizontalsearchHelper::getFormattingText($params);

require(JModuleHelper::getLayoutPath('mod_vikbooking_horizontalsearch'));

?>
