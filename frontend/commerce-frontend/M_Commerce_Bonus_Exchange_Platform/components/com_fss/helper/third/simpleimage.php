<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class SimpleImage {
	
	var $image;
	var $image_type;
	
	function load($filename) {
		
		$image_info = @getimagesize($filename);
		$this->image_type = $image_info[2];
		//echo "Load Image : {$this->image_type}<br>";
		if( $this->image_type == IMAGETYPE_JPEG ) {
			
			$this->image = imagecreatefromjpeg($filename);
		} elseif( $this->image_type == IMAGETYPE_GIF ) {
			
			$this->image = imagecreatefromgif($filename);
		} elseif( $this->image_type == IMAGETYPE_PNG ) {
			
			$this->image = imagecreatefrompng($filename);
		}
	}
	
	function save($filename) {
		imagepng($this->image,$filename);
	}
	
	function output($image_type = IMAGETYPE_PNG) {
		
		if ($image_type == 0)
			$image_type = $this->image_type;
		
		if( $image_type == IMAGETYPE_JPEG ) {
			
			imagejpeg($this->image);
			
		} elseif( $image_type == IMAGETYPE_GIF ) {
			
			imagegif($this->image);
			
		} elseif( $image_type == IMAGETYPE_PNG ) {
			
			imagepng($this->image);
			
		}
	}
	function getWidth() {
		
		return imagesx($this->image);
	}
	
	function getHeight() {
		
		return imagesy($this->image);
	}
	
	function resizeToHeight($height) {
		
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
	
	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		if ($this->getWidth() < $width)
			return false;
		$this->resize($width,$height);
		return true;
	}
	
	function scale($scale) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize($width,$height);
	}
	
	function resize($width,$height) {
		$new_image = imagecreatetruecolor($width, $height);
		
		imagealphablending( $new_image, false );
		//imagesavealpha( $new_image, true );
		
		$transparent = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
		imagefill($new_image, 0, 0, $transparent);
		imagesavealpha( $new_image, true );
		imagealphablending( $new_image, false );
		
		$source_w = $this->getWidth();
		$source_h = $this->getHeight();
		$source_ar = $source_w / $source_h;
		
		$dest_x = 0;
		$dest_y = 0;
		$dest_w = $width;
		$dest_h = $height;		
		$dest_ar = $dest_w / $dest_h;
		
		/*if ($source_w < $width && $source_h < $dest_h)
			return;*/
		
		//echo "Source : $source_w x $source_h = $source_ar<br>";
		//echo "Dest: $dest_w x $dest_h = $dest_ar ($dest_x, $dest_y)<br>";
		if ($source_ar < $dest_ar)
		{
			$dest_w = $dest_h * $source_ar;
			$dest_x = ($width - $dest_w) / 2;
		} else {
			$dest_h = $dest_w / $source_ar;		
			$dest_y = ($height - $dest_h) / 2;
		}
		//echo "Dest: $dest_w x $dest_h = " . ($dest_w/$dest_h) . " ($dest_x, $dest_y)<br>";		
		//exit;
		
		imagecopyresampled($new_image, $this->image, $dest_x, $dest_y, 0, 0, $dest_w, $dest_h, $source_w, $source_h);
		$this->image = $new_image;
	}      
	
}