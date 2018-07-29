 <?php
/**
 * @package			QRCode Elite
 * @subpackage		mod_qrcode_elite
 * @copyright		Copyright (C) 2013 Elite Developers All rights reserved.
 * @license			GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

	require_once __DIR__ . '/helper.php';
	$mob = $params->get('mob');
	$mobsel = $params->get('mobsel');
	if ($mob){
		require_once 'fields/ismob.php';
		$detect = new Mobile_Detect;
		if (($mob == 1) && ($detect->isMobile())){
			return ;
		} elseif ($mob == 2){
			switch ($usertype) {
				case 'p': 
					if( $detect->isMobile() && !$detect->isTablet() ){
						return ;
					}
					break;
				case 't': 
					if( $detect->isTablet() ){
						return ;
					}
					break;
				case 'a':
					if( $detect->isAndroidOS() ){
						return ;
					}
					break;
				case 'i':
					if( $detect->isiOS() ){
						return ;
					}
					break;
			}
		}
	}
	$moduleclass_sfx = htmlspecialchars( $params->get( 'moduleclass_sfx' ) );
	$layout =  htmlspecialchars( $params->get( 'layout' , 'default' ) );
	$html = ModQRCodeEliteHelper::getCode( $params );
	require( JModuleHelper::getLayoutPath( 'mod_qrcode_elite' , $layout ) );