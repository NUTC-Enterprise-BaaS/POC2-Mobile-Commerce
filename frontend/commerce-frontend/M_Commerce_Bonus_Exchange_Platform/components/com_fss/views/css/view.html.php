<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated e1457ead666ca6443b6d712b76fe2055
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.filesystem.file');
jimport('fsj_core.admin.update');

class fssViewcss extends FSSView
{
	function display($tpl = null)
	{
		$file = JRequest::getVar('file');

		$file = str_replace("/", "", $file);
		$file = str_replace("\\", "", $file);
		
		header("Content-type: text/css");
		
		$filename = JPATH_ROOT . DS . "cache" . DS . "fss" . DS . "css" . DS . $file;
		
		readfile($filename);
		exit;
	}
}
