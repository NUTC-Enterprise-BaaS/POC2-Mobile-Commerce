<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/*layout for Image & text ads only (ie. title & img & decrip)
this will be the default layout for the module/zone
*/
$ht='';
if( $addata->ignore !='')
$ht .= '<span class="ad_ignore_button_span" style="display:none;"><img title="'.JText::_('COM_SOCIALADS_CLK_IGN').'" class="ad_ignore_button layout1_ad_ignore_button" src="'.JUri::root(true) . '/media/com_sa/images/cross.gif" alt="" onclick="'.$addata->ignore.'" /></span>';

// $ht.='<div class="ad_prev_wrap layout1_ad_prev_wrap well well-small">';
$ht.='<div class="ad_prev_wrap layout1_ad_prev_wrap">';
/*Ad title starts here...*/
	$ht .= '<!--div for preview ad-title-->
		<div class="layout1_ad_prev_first">';
			$ht.='<a class="preview-title preview-title-lnk layout1_ad_prev_anchor" href="'.$addata->link.'" target="_blank">';
					$ht .= ''.$addata->ad_title;
			$ht.='</a>';
		$ht.= '</div>';
/*Ad title ends here*/

/*Ad image starts here...*/
$ht.='<!--div for preview ad-image-->
	<div class="layout1_ad_prev_second">';
		$ht.='<a '.$upload_area.' href="'.$addata->link.' " target="_blank">';
			//$ht.= '<img class="layout1_ad_prev_img" alt="" src="'.JUri::Root().$addata->ad_image.'" border="0" />';
			//changed in 2.7.5 beta 2
		$ht.=$adHtmlTyped;
		$ht.='</a>';

	$ht.= '</div>';
/*Ad image ends here*/

/*Ad description starts here...*/
	$ht .= '<!--div for preview ad-descrip-->
			<div class="preview-bodytext layout1_ad_prev_third">';
				$ht .=''. $addata->ad_body;
	$ht .='</div>';
/*Ad description ends here*/
$ht .='</div>';
echo $ht;
?>
