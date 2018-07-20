<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSS_Captcha
{
	function getRecapKeys()
	{
		$keys = new stdClass();
		$keys->public = FSS_Settings::get('recaptcha_public');
		$keys->private = FSS_Settings::get('recaptcha_private');

		if (!$keys->public || !$keys->private)
		{
			$db = JFactory::getDBO();
			$sql = "SELECT * FROM #__extensions WHERE name = 'plg_captcha_recaptcha'";
			$db->setQuery($sql);
			$data = $db->loadObject();

			if ($data)
			{
				$params = json_decode($data->params);
				if ($params)
				{
					$keys->public = $params->public_key;
					$keys->private = $params->private_key;
				}
			}
		}

		return $keys;
	}

	function GetCaptcha($setting = 'captcha_type', $direct = '')
	{
		$usecaptcha = FSS_Settings::get( $setting );

		if ($direct != "") $usecaptcha = $direct;
		
		if ($usecaptcha == "") return "";

		if ($usecaptcha == "fsj")
			return "<img src='" . FSSRoute::_("index.php?option=com_fss&task=captcha_image&random=" . rand(0,65535)) . "' /><input id='security_code' name='security_code' type='text' style='position: relative; left: 3px;'/>";

		if ($usecaptcha == "recaptcha")
		{
			$document = JFactory::getDocument();
			$document->addScript("https://www.google.com/recaptcha/api.js");

			$fss_publickey = $this->getRecapKeys()->public;
			if (!$fss_publickey) $fss_publickey = "12345";

			$html = '<div class="g-recaptcha" data-sitekey="' . $fss_publickey . '"></div>';

			return $html;
		}

		return "";
	}
	
	function ValidateCaptcha($setting = 'captcha_type', $direct = '')
	{
		$usecaptcha = FSS_Settings::get($setting);

		if ($direct != "") $usecaptcha = $direct;

		if ($usecaptcha == "") return true;

		if ($usecaptcha == "fsj")
		{
			if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) { 
				return true;
			}
			return false;
		}

		if ($usecaptcha == "recaptcha")
		{
			if (!class_exists("ReCaptcha\ReCaptcha"))
			{
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'ReCaptcha.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'RequestMethod.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'RequestParameters.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'Response.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'RequestMethod'.DS.'Post.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'RequestMethod'.DS.'Curl.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'RequestMethod'.DS.'CurlPost.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'RequestMethod'.DS.'Socket.php');
				require(JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'ReCaptcha'.DS.'RequestMethod'.DS.'SocketPost.php');
			}

			$secret = $this->getRecapKeys()->private;
			if (!$secret) $secret = "12345";

			$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

			if ($resp->getErrorCodes())
			{
				$recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\CurlPost());
				$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			}

			if ($resp->getErrorCodes())
			{
				$recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());
				$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			}

			if ($resp->getErrorCodes())
			{
				echo "ReCaptcha is unavailable as your sites does not have any of the required libraries installed. Please ensure that you have 'allow_url_fopen' in your php.ini file as enabled, or the Curl or Socket modules installed";
				exit;
			}

			if ($resp->isSuccess())
			{
				return true;
			} else {
				return false;
			}
		}

		return true;
	}
	
	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	
	function GetImage($width='150',$height='40',$characters='6') {
		$this->font = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'fonts'.DS.'captcha.ttf';
		
		$code = $this->generateCode($characters);
		$_SESSION['security_code'] = $code;
		$code2 = "";
		for ($i = 0; $i < strlen($code); $i++)
			$code2 .= substr($code,$i,1) . " ";
		$code = $code2;
		/* font size will be 75% of the image height */
		$font_size = $height * 0.60;
		$image = imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 10, 20, 50);
		$noise_color = imagecolorallocate($image, 150, 160, 100);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/300; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
	}
}