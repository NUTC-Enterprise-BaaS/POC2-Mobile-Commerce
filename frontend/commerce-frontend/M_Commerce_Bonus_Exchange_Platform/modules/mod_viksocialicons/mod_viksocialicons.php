<?php  
/**
* Mod_VikSocialIcons
* http://www.extensionsforjoomla.com
*/

defined('_JEXEC') or die('Restricted Area');

//Joomla 3.0
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
//

//require_once (dirname(__FILE__).DS.'helper.php');

$moduleName = 'mod_viksocialicons';
//$moduleID = $module->id;
$document = JFactory::getDocument();

//$helper = new modVikSocialIconsHelper($params, $moduleID);

$document->addStylesheet(JURI::base(true).'/modules/'.$moduleName.'/mod_viksocialicons.css');

require(JModuleHelper::getLayoutPath($moduleName));

?>