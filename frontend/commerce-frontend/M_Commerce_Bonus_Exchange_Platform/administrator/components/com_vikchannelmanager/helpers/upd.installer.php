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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class VikUpdaterInstaller {

	public static function unzip($src, $dest) {
		jimport('joomla.filesystem.archive');
		
		return JArchive::extract($src, $dest);
	}
	
	public static function executeQueries($arr) {
		$dbo = JFactory::getDBO();
		
		foreach( $arr as $q ) {
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	
	public static function uninstall($root) {
		if( is_dir($root) ) {
			return self::unlinkDir($root);
		} else {
			return unlink($root);
		}
	}
	
	private static function unlinkDir($root) { 
   		$files = array_diff(scandir($root), array('.','..')); 
    	foreach( $files as $file ) { 
      		(is_dir("$root/$file")) ? self::unlinkDir("$root/$file") : unlink("$root/$file"); 
    	} 
    	return rmdir($root); 
	}
	
	public static function copyFile($src, $dest) {
		if( file_exists($src) ) {
			unlink($dest);
			return copy($src, $dest);
		}
		return false;
	}
	
	public static function smartCopy($source, $dest, $options=array('folderPermission' => 0755, 'filePermission' => 0755)) {
		$result = false;
	
		if( is_file($source) ) {
			$__dest = $dest;
			if( $dest[strlen($dest)-1] == '/' ) {
				if( !file_exists($dest) ) {
					cmfcDirectory::makeAll($dest, $options['folderPermission'], true);
				}
				$__dest = $dest . "/" . basename($source);
			}
			
			$result = copy($source, $__dest);
			chmod($__dest, $options['filePermission']);
		} else if( is_dir($source) ) {
			if( $dest[strlen($dest)-1] == '/' ) {
				if( $source[strlen($source)-1] == '/' ) {
					//Copy only contents
				} else {
					//Change parent itself and its contents
					$dest = $dest . basename($source);
					@mkdir($dest);
					chmod($dest, $options['filePermission']);
				}
			} else {
				if( $source[strlen($source)-1] == '/' ) {
					//Copy parent directory with new name and all its content
					@mkdir($dest, $options['folderPermission']);
					chmod($dest, $options['filePermission']);
				} else {
					//Copy parent directory with new name and all its content
					@mkdir($dest, $options['folderPermission']);
					chmod($dest, $options['filePermission']);
				}
			}
	
			$dirHandle = opendir($source);
			while( $file = readdir($dirHandle) ) {
				if( $file != "." && $file != ".." ) {
					if( !is_dir($source . "/" . $file) ) {
						$__dest = $dest . "/" . $file;
					} else {
						$__dest = $dest . "/" . $file;
					}
					$result = self::smartCopy($source . "/" . $file, $__dest, $options);
				}
			}
			closedir($dirHandle);
	
		} else {
			$result = false;
		}
		return $result;
	}
	
}

?>