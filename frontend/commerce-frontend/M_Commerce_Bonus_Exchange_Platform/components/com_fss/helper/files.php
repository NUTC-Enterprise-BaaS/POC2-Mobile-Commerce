<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

// helper to file attachments to tickets and articles

// functions to make thumbnails and download the attachments

class FSS_File_Helper
{
	static function Thumbnail($image_file, $thumb_file,$width = 48, $height = 48)
	{
		$ctype = FSS_Helper::datei_mime("png");

		ob_end_clean();  
		while (ob_get_level() > 0) ob_end_clean();

		// thumb file exists
		if (file_exists($thumb_file) && filesize($thumb_file) > 0)
		{
		
			header("Content-Type: " . $ctype);
			header('Cache-control: max-age='.(60*60*24*365));
			header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*365));
			
			@readfile($thumb_file);
			exit;	
		}
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'simpleimage.php');
		$im = new SimpleImage();
		$im->load($image_file);
		
		if (!$im->image)
		{
			// return a blank thumbnail of some sort!	
			$im->load(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'images'.DS.'blank_16.png');
		}
		
		$im->resize($width, $height);

		$im->output();
		$im_data = ob_get_clean();

		if (strlen($im_data) > 0) // did the output buffer stuff work ok? 
		{
			// if so use JFile to write the thumbnail image
			JFile::write($thumb_file, $im_data);
		} else {
			// it failed for some reason, try doing a direct write of the thumbnail
			$im->save($thumb_file);
		}

		header('Cache-control: max-age='.(60*60*24*365));
		header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*365));
		header("Content-Type: " . $ctype);
		
		if (file_exists($thumb_file && filesize($thumb_file) > 0))
		{ 
			@readfile($thumb_file);
		} else {
			$im->output();
		}
		exit;
		
	}	
	
	static function OutputImage($filename, $ext = "")
	{
		if ($ext == "")
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$ctype = FSS_Helper::datei_mime("png");		

		ob_end_clean();  
		while (ob_get_level() > 0) ob_end_clean();

		header('Cache-control: max-age='.(60*60*24*365));
		header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*365));
		header("Content-Type: " . $ctype);
			
		readfile($filename);
		exit;		
	}	
	
	static function DownloadFile($filename, $display_name, $ext = "")
	{
		// TODO: We need a much better piece of code for downloading files here
		
		// it will end up being used for large attachments etc so needs to work with big files!
		
		if ($ext == "")
			$ext = pathinfo($display_name, PATHINFO_EXTENSION);
		
		$ctype = FSS_Helper::datei_mime($ext);		

		ob_end_clean();  
		while (ob_get_level() > 0) ob_end_clean();

		ini_set('zlib.output_compression', 'Off');
		ini_set('output_buffering', 'Off');
		ini_set('output_handler', '');

		if (function_exists("apache_setenv"))
			apache_setenv('no-gzip', 1);
			
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'bigfiletools.php');
		$f = BigFileTools::fromPath($filename);
		$file_size  = $f->getSize();

		header("Pragma: no-cache");
		header("Expires: -1");
		header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");

		header("Content-Type: " . $ctype);
		header("Content-Disposition: attachment; filename=\"$display_name\"");

		//check if http_range is sent by browser (or download manager)
		if(isset($_SERVER['HTTP_RANGE']))
		{
			list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			if ($size_unit == 'bytes')
			{
				//multiple ranges could be specified at the same time, but for simplicity only serve the first range
				//http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
				list($range, $extra_ranges) = explode(',', $range_orig, 2);
			}
			else
			{
				$range = '';
				header('HTTP/1.1 416 Requested Range Not Satisfiable');
				exit;
			}
		}
		else
		{
			$range = '';
		}

		//figure out download piece from range (if set)
		if ($range && strpos($range, "-") !== false)
		{
			list($seek_start, $seek_end) = @explode('-', $range, 2);
		} else {
			$seek_start = 0;
			$seek_end = 0;
		}
		
		//set start and end based on range (if set), else set defaults
		//also check for invalid ranges.
		$seek_end   = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)),($file_size - 1));
		$seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);
		
		//Only send partial content header if downloading a piece of the file (IE workaround)
		if ($seek_start > 0 || $seek_end < ($file_size - 1))
		{
			header('HTTP/1.1 206 Partial Content');
			header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$file_size);
			header('Content-Length: '.($seek_end - $seek_start + 1));
		}
		else
		{
			header("Content-Length: $file_size");
		}
		set_time_limit(0);
		$file = fopen($filename,"rb");
		while(!feof($file))
		{
			echo @fread($file, 1024*256);
			@ob_flush();
			@flush();
			if (connection_status()!=0) 
			{
				@fclose($file);
				exit;
			}	
		}
		@fclose($file);
		exit;		
	}

	static function shortUID($base)
	{
		return substr(md5($base.time()), 0, 6);
	}

	static function makeAttachFilename($subfolder, $filename, $date = null, $ticket = null, $userid = null)
	{
		$base = JPATH_SITE.DS.FSS_Helper::getAttachLocation().DS.$subfolder.DS;

		$path = '';

		$path_info = pathinfo($filename);
		
		$file_template = $path_info['filename'] . "-{UID}." . $path_info['extension'];

		if ($date == null)
			$date = date("Y-m-d");

		$time = strtotime($date);

		if ($userid == null)
			$userid = $ticket->user_id;

		if ($ticket != null)
		{
			switch (FSS_Settings::get('attach_storage_filename'))
			{
				case 1:
					$path .= $ticket->id . DS;
					break;
				case 2:
					$path .= date("Y", $time) . DS . date("Y-m", $time) . DS;
					break;
				case 3:
					$path .= date("Y", $time) . DS . date("Y-m", $time) . DS . date("Y-m-d", $time) . DS;
					break;
				case 4:
					$user = JFactory::getUser($userid);
					if ($user->id > 0)
					{
						$path .= $user->username . DS;
					} else {
						$path .= '_unregistered' . DS;
					}
			}
		}

		if (!file_exists($base.$path))
			mkdir($base.$path, 0755, true);

		$filename = str_replace("{UID}", FSS_File_Helper::shortUID($file_template), $file_template);
		while (JFile::exists($path . $filename))
		{
			$filename = str_replace("{UID}", FSS_File_Helper::shortUID($file_template), $file_template);
		}

		return $path.$filename;
	}

	static function makeUploadSubdir($token)
	{
		$config = new JConfig();
		return substr(md5("fss_incoming".$token.$config->secret),0,8);
	}

	static function CleanupIncoming()
	{
		// TODO: Find all folders in the incoming folder that are older than 24 hours and remove.
	}
}