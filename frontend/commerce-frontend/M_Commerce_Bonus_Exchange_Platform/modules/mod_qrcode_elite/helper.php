 <?php
 /**
 * @package			QRCode Elite
 * @subpackage		mod_qrcode_elite
 * @copyright		Copyright (C) 2013 Elite Developers All rights reserved.
 * @license			GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

class modQRCodeEliteHelper {

	public static function getCode( &$params ) {

		$qrtype = $params->get( 'qrtype' );
		$ext = $url = '' ;
		switch ( $qrtype ) {
			case '1': 
				$link = JURI::getInstance( );
				if ( $params->get( 'siteurl' ) == 'w' ) {
					$link = $link->getHost( );
				} else {
					$jURI = JURI::getInstance( );
					$link = $jURI->toString( );
				}
				$data = $link ;
				$url = $data ;
				break ;
			case '2': 
				$url = $params->get( 'customurl' );
				$data = filter_var( $url , FILTER_VALIDATE_URL ) ? $url : '' ;
				$url = $data ;
				$ext = '1' ;
				break ;
			case '3': 
				$url = $params->get( 'youtube' );
				$data = ( filter_var( $url , FILTER_VALIDATE_URL ) && ( $url != 'http://www.youtube.com/watch?v=' ) )? $url : '' ;
				$url = $data ;
				$ext = '1' ;
				break ;
			case '4': 
				$text = $params->get( 'text' );
				$data = $text ? $text : '' ;
				break ;
			case '5': 
				$tel = $params->get( 'phone' );
				$data = $tel ? "TEL:" . $tel : '' ;
				break ;
			case '6': 
				$tel = $params->get( 'phonenumber' );
				$sms = $params->get( 'sms' );
				$data = $tel && $sms ? "SMSTO:" . $tel . ":" . $sms : '' ; 
				break ;
			case '8': 
				$email = $params->get( 'email' );
				$data = filter_var( $email , FILTER_VALIDATE_EMAIL ) ? "MAILTO:" . $email : '' ;		
				break ;
			case '9': 
				$email = $params->get( 'emailaddr' );
				$subject = $params->get( 'subject' );
				$message = $params->get( 'message' );
				$data = filter_var( $email , FILTER_VALIDATE_EMAIL ) && $subject ? "MATMSG:TO:" . $email . ";SUB:" . $subject . ";BODY:" . $message . ";;" : '' ;
				break ;
			case '10': 
				$firstname = $params->get( 'firstname' );
				$lastname = $params->get( 'lastname' );
				$jobtitle = $params->get( 'jobtitle' );
				$telephonenumber = $params->get( 'telephonenumber' );
				$cellphone = $params->get( 'cellphone' );
				$faxnumber = $params->get( 'faxnumber' );
				$emailaddress = $params->get( 'emailaddress' );
				$website = $params->get( 'website' );
				$organization = $params->get( 'organization' );
				$streetaddress = $params->get( 'streetaddress' );
				$city = $params->get( 'city' );
				$state = $params->get( 'state' );
				$zip = $params->get( 'zip' );
				$country = $params->get( 'country' );
				if ( $firstname || $lastname && $telephonenumber ) {
					$data  = 'BEGIN:VCARD' . "\n" ; 
					$data .= 'VERSION:2.1' . "\n" ;
					$data .= $firstname || $lastname  ? 'N;CHARSET=utf-8:' . $lastname . ";" . $firstname . ";;;\n" : "" ;
					$data .= $firstname || $lastname  ? 'FN;CHARSET=utf-8:' . $firstname . " " . $lastname . "\n" : "" ;
					$data .= $organization ? 'ORG;CHARSET=utf-8:' . $organization . "\n" : "" ;
					$data .= $jobtitle ? 'TITLE;CHARSET=utf-8:' . $jobtitle . "\n" : "" ;
					$data .= $telephonenumber ? 'TEL:' . $telephonenumber . "\n" : "" ;
					$data .= $cellphone ? 'TEL;CELL:' . $cellphone . "\n" : "" ;
					$data .= $faxnumber ? 'TEL;FAX:' . $faxnumber . "\n" : "" ;
					$data .= filter_var( $emailaddress , FILTER_VALIDATE_EMAIL ) ? 'EMAIL;CHARSET=utf-8:' . $emailaddress . "\n" : "" ;
					$data .= filter_var( $website , FILTER_VALIDATE_URL ) ? 'URL;CHARSET=utf-8:' . $website . "\n" : "" ;
					$data .= $streetaddress || $city || $state || $zip || $country ? 'ADR;CHARSET=utf-8:;;' . $streetaddress . ";" . $city . ";" . $state . ";" . $zip . ";" . $country . "\n" : "" ;
					$data .= 'END:VCARD' ; 
				} else {
					$data  = '' ;
				}
				break;
		}
		if ( isset( $data ) && $data ) { 
			$ecc = $params->get( 'ecc' );
			$size = $params->get( 'size' );
			$margin = $params->get( 'margin' );
			$helper = $params->get( 'fields' );
			$backcolor = $params->get( 'backcolor' );
			$forecolor = $params->get( 'forecolor' );
			$backcolor = ( preg_match('/^#[a-f0-9]{6}$/i' , $backcolor ) ) ? intval( substr( $backcolor , 1 ), 16 ) : 0xFFFFFF ;
			$forecolor = ( preg_match('/^#[a-f0-9]{6}$/i' , $forecolor ) ) ? intval( substr( $forecolor , 1 ), 16 ) : 0x000000 ;
			$PNG_TEMP_DIR = JFactory::getApplication()->getCfg('tmp_path') ;
			$str = JPATH_SITE ;
			$str = str_replace('\'', '/', $str);
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$PNG_WEB_DIR = substr( str_replace( $str , "" , $PNG_TEMP_DIR ) , strlen($str) + 1 );
			} else { 
				$PNG_WEB_DIR = substr( str_replace( $str , "" , $PNG_TEMP_DIR ) , 1 );
			}
			require_once $helper ;
			$filename = $PNG_TEMP_DIR  . "/" . 'qr' . md5( $data . '|' . $ecc . '|' . $size ) . '.png' ;
			$html = QRcode::png( $data , $filename , $ecc , $size , $margin , false , $backcolor , $forecolor );
			list($width, $height) = ( ( getimagesize( $filename ) ) ? getimagesize( $filename ) : array( '','' ) );
			if ( $url ) {
				$html = '<a href="' . $url . '" target="' . ( $ext ? "_blank" : "_self" ) . '"><img src="' . JURI::base() .  $PNG_WEB_DIR  . "/" . basename( $filename ) . '" alt="Qr Code" width="'.$width.'" height="'.$height.'" /></a>';
			} else {
				$html = '<img src="' . JURI::base() .  $PNG_WEB_DIR . "/" . basename( $filename ) . '" alt="Qr Code" width="'.$width.'" height="'.$height.'" />' ;
			}
		} else {    
			$html = "\r\n<div style='color:#ff0000;'><h5>Please enter valid data in QRcode Elite Module options before activating it.</h5></div>\r\n\n" ;   
		}    
		$alg = $params->get( 'alg' );
		$acss = $params->get( 'acss' );
		$before = htmlspecialchars ( $params->get( 'before' ) );
		$after = htmlspecialchars ( $params->get( 'after' ) );
		$befcss = $params->get( 'befcss' );
		$aftcss = $params->get( 'aftcss' );
		$before = "<div" . ( $befcss ? " style=\"" . $befcss . "\""  : '') . ">" . $before . "</div>" ;
		$after = "<div" . ( $aftcss ? " style=\"" . $aftcss . "\""  : '') . ">" . $after . "</div>" ;
		$html = $before . $html . $after ;
		$alg = ( ( $alg == 'css' ) ? $acss : $alg );
		$html = ( $alg ? "\r\n<div style=\"" . $alg . "\">\r\n\n" . $html . "</div>" : "\r\n" . $html . "\r\n" ); 
		return $html ;
	}
}