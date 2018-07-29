<?php
/*
# ------------------------------------------------------------------------
# Vina Jssor Image Slider for Joomla 3
# ------------------------------------------------------------------------
# Copyright(C) 2014 www.VinaGecko.com. All Rights Reserved.
# @license http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
# Author: VinaGecko.com
# Websites: http://vinagecko.com
# Forum:    http://vinagecko.com/forum/
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class modVinaJssorImageSliderHelper
{
    public static function getSildes($slider)
	{
        switch($slider->src)
		{
			case "dir":
					$rows = self::getDataFromDirectory($slider);
                break;
			default:
					$rows = $slider->list;
				break;
		}
		return $rows;
    }
	
	public static function getDataFromDirectory($slider)
    {
        $dir = $slider->dir->path;
		
        if(strrpos($dir,'/') != strlen($dir) -1) $dir .= '/';
        
		$files 		= JFolder::files($dir);
        $accept 	= explode(',', strtolower($slider->dir->ext));
        $outFiles 	= array();
        $i = 0;
		
        if(count($files))
		{
            foreach($files as $file)
            {
                $lastDot 	= strrpos($file, '.');
                $ext 		= substr($file, $lastDot);
            
                if(in_array(strtolower($ext), $accept))
                {
                    $outFiles[$i]->img = $dir . $file;
                    $i++;
                }
            }
		}
		
        return $outFiles;
    }
	
	public static function getCopyrightText($module)
	{
		echo '<div id="vina-copyright'.$module->id.'">© Free <a href="http://vinagecko.com/joomla-modules" title="Free Joomla! 3 Modules">Joomla! 3 Modules</a>- by <a href="http://vinagecko.com/" title="Beautiful Joomla! 3 Templates and Powerful Joomla! 3 Modules, Plugins.">VinaGecko.com</a></div>';
	}
}