<?php  
/**
* Mod_VikSocialIcons
* http://www.extensionsforjoomla.com
*/

// no direct access
defined('_JEXEC') or die('Restricted Area');

$getimg_size = $params->get('img_size');
$getimg_style =  $params->get('img_style');
$getfb_link =  $params->get('fb_link');
$gettw_link =  $params->get('tw_link');
$getgoo_link =  $params->get('goo_link');
$getyoutube_link =  $params->get('youtube_link');
$getpint_link =  $params->get('pint_link');
$gettumb_link =  $params->get('tumb_link');
$getsquare_link =  $params->get('square_link');

$getcolors = $params->get('img_colors');
?>

<div class="viksocialiconscontain<?php echo $params->get('moduleclass_sfx'); ?>">
<div class="viksocialicons">

<!-- facebook -->
<?php
	
	if(!empty($getfb_link)) {
?>
	<a href="<?php echo $getfb_link; ?>" style="background:url(modules/mod_viksocialicons/icons/<?php echo $getimg_style; ?>/fb-<?php echo $getimg_size; if($getcolors=1) { echo "_white";}?>.png)" class="fbclass <?php echo $getimg_size; ?>" target="_blank"></a>
<?php
		
	}
?>

<!-- twitter -->
<?php
	
	if(!empty($gettw_link)) {

?>
	<a href="<?php echo $gettw_link; ?>" style="background:url(modules/mod_viksocialicons/icons/<?php echo $getimg_style; ?>/tw-<?php echo $getimg_size; if($getcolors=1) { echo "_white";}?>.png)" class="twclass <?php echo $getimg_size; ?>" target="_blank"></a>
<?php
		 
	}
?>

<!-- google+ -->
<?php
	
	if(!empty($getgoo_link)) {

?>
	<a href="<?php echo $getgoo_link; ?>" style="background:url(modules/mod_viksocialicons/icons/<?php echo $getimg_style; ?>/goo-<?php echo $getimg_size; if($getcolors=1) { echo "_white";}?>.png)" class="gooclass <?php echo $getimg_size; ?>" target="_blank"></a>
<?php
		 
	}
?>

<!-- Youtube -->
<?php
	
	if(!empty($getyoutube_link)) {

?>
	<a href="<?php echo $getyoutube_link; ?>" style="background:url(modules/mod_viksocialicons/icons/<?php echo $getimg_style; ?>/youtube-<?php echo $getimg_size; if($getcolors=1) { echo "_white";}?>.png)" class="youtubeclass <?php echo $getimg_size; ?>" target="_blank"></a>
<?php
	}
?>

<!-- Pinterest -->
<?php
	
	if(!empty($getpint_link)) {

?>
	<a href="<?php echo $getpint_link; ?>" style="background:url(modules/mod_viksocialicons/icons/<?php echo $getimg_style; ?>/pint-<?php echo $getimg_size; if($getcolors=1) { echo "_white";}?>.png)" class="pintclass <?php echo $getimg_size; ?>" target="_blank"></a>
<?php
	}
?>

<!-- Tumblr -->
<?php
	
	if(!empty($gettumb_link)) {
?>
	<a href="<?php echo $gettumb_link; ?>" style="background:url(modules/mod_viksocialicons/icons/<?php echo $getimg_style; ?>/tumb-<?php echo $getimg_size; if($getcolors=1) { echo "_white";}?>.png)" class="tumbclass <?php echo $getimg_size; ?>" target="_blank"></a>
<?php
	}
?>

<!-- Square -->
<?php
	
	if(!empty($getsquare_link)) {
?>
	<a href="<?php echo $getsquare_link; ?>" style="background:url(modules/mod_viksocialicons/icons/<?php echo $getimg_style; ?>/square-<?php echo $getimg_size; if($getcolors=1) { echo "_white";}?>.png)" class="squareclass <?php echo $getimg_size; ?>" target="_blank"></a>
<?php
	}
?>

</div>
</div>

	