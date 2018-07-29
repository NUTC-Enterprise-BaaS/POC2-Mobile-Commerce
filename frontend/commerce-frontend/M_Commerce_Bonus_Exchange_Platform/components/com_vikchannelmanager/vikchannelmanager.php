<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

defined('VIKCHANNELMANAGER_SOFTWARE_VERSION') or define('VIKCHANNELMANAGER_SOFTWARE_VERSION', '1.4.0');

if( !defined('DS') ) {
    define( 'DS', DIRECTORY_SEPARATOR );
}

require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."lib.vikchannelmanager.php");
require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."vcm_config.php");

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'components/com_vikchannelmanager/vikchannelmanager.css');

new VersionListener();

jimport('joomla.application.component.controller');
$controller = JControllerLegacy::getInstance('Vikchannelmanager');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

?>
