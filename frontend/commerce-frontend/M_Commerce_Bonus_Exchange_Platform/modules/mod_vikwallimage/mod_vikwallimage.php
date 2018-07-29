<?php  
/**
* Mod_VikWallImage
* http://www.extensionsforjoomla.com
*/

defined('_JEXEC') or die('Restricted Area');
//Joomla 3.0
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
// 

require_once (dirname(__FILE__).DS.'helper.php');

$moduleName = 'mod_vikwallimage';
$moduleID = $module->id;
$document = JFactory::getDocument();

$helper = new vikWallImageHelper($params, $moduleID);

$document->addStylesheet(JURI::base(true).'/modules/'.$moduleName.'/mod_vikwallimage.css');
require(JModuleHelper::getLayoutPath($moduleName));

?>