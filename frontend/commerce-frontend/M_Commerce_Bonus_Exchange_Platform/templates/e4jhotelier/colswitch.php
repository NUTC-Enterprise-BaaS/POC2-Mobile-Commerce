<?php
$arraycolors = array('0'=>'', '1'=>'', '2'=>'', '3'=>'');
$tplcolor = intval($this->params->get('tcolour'));
$resp = intval($this->params->get('responsive'));
$font = intval($this->params->get('hfont'));
$bfont = intval($this->params->get('bfont'));


switch ($tplcolor) {
	case 1:
		$cssname='style_blue.less';
		break;
	case 2:
		$cssname='style_azure.less';
		break;
	case 3:
		$cssname='style_green.less';
		break;
	case 4:
		$cssname='style_red.less';
		break;
	default:
		$cssname='style.less';
		break;
}

switch ($font) {
	case 1:
		$fontfamily='<link href=\'http://fonts.googleapis.com/css?family=PT+Sans:400,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$fontname='ptsans';
		break;
	case 2:
		$fontfamily='<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$fontname='opensans';
		break;
	case 3:
		$fontfamily='<link href=\'http://fonts.googleapis.com/css?family=Droid+Sans:400,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$fontname='droidsans';
		break;
	case 4:
		$fontfamily='';
		$fontname='centurygothic';
		break;
	case 4:
		$fontfamily='';
		$fontname='arial';
		break;
	default:
		$fontfamily='<link href=\'http://fonts.googleapis.com/css?family=Lato:300,400,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$fontname='lato';
		break;
}

switch ($bfont) {
	case 1:
		$fontfamilybd='<link href=\'http://fonts.googleapis.com/css?family=PT+Sans:400,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$bodyfont='ptsansbd';
		break;
	case 2:
		$fontfamilybd='<link href=\'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$bodyfont='opensansbd';
		break;
	case 3:
		$fontfamilybd='<link href=\'http://fonts.googleapis.com/css?family=Droid+Sans:400,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$bodyfont='droidsansbd';
		break;
	case 4:
		$fontfamilybd='';
		$bodyfont='centurygothicbd';
		break;
	case 5:
		$fontfamilybd='';
		$bodyfont='arialbd';
		break;
	default:
		$fontfamilybd='<link href=\'http://fonts.googleapis.com/css?family=Lato:300,400,700\' rel=\'stylesheet\' type=\'text/css\'>';
		$bodyfont='latobd';
		break;
}
?>