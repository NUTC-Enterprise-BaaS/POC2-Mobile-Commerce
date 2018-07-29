<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 6135c5111704adb871a73436904cccc5
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');

class fssViewImage extends FSSView
{
	function display($tpl = null)
	{
		$fileid = FSS_Input::getInt('fileid'); 
		
		$key = FSS_Input::getCmd('key'); 
		$decoded = FSS_Helper::decrypt(FSS_Helper::base64url_decode($key), FSS_Helper::getEncKey("file"));

		if ($fileid != $decoded)
			exit;

		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__fss_ticket_attach WHERE id = " . $fileid;
		$db->setQuery($sql);
		$attach = $db->loadObject();

		$image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif'));
			
		$image_file = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS."support".DS.$attach->diskfile;
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'files.php');
		FSS_File_Helper::OutputImage($image_file, pathinfo($attach->filename, PATHINFO_EXTENSION));	
	}
}
