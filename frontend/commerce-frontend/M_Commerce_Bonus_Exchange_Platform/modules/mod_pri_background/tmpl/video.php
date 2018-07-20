<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_pri_background
 * @version     4.0
 *
 * @copyright   Copyright (C) 2016 Devpri SRL. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
//
if(!function_exists('gcd')){
    function gcd( $a, $b ){
        return ($a % $b) ? gcd($b,$a % $b) : $b;
    }
}
//
if(!function_exists('ratio')){
    function ratio( $x, $y ){
        $gcd = gcd($x, $y);
        return ($x/$gcd).'/'.($y/$gcd);
    }
}
// Get video ratio
$ratio = ratio($params->get('video_background_width'), $params->get('video_background_height') );
// SourceUrl
if ($params->get('video_background_source') == 'local'){
	$sourceUrl = JURI::base().'/';
} else {
	$sourceUrl = '';
}

// Check mobile devices
if (!$is_mobile) {
	//Desktop devices
    if ($params->get('video_background_keep_time') === '1'){
        $document->addScript(JURI::base() .'modules/mod_pri_background/assets/js/js.cookie.min.js');
	}
}
$document->addStyleDeclaration('
	#pri-background-video-'.$module_id.' {
        background: url('.$sourceUrl . $params->get('video_background_poster').') !important;
        background-size: cover !important;
        background-position: center !important;
        z-index: -2;
	}
');


$script  = '(function($){';
if ($params->get('video_background_keep_time') === '1'){
    $script .= '
    var startOnTime;
    startOnTime = Cookies.get("pri-background-video-'.$module_id.'");
    if(typeof startOnTime === "undefined"){
        startOnTime = 0;
    };
    $(window).unload(function(){
        var currentTime = video'.$module_id.'.get(0).currentTime;
        if (typeof currentTime === "undefined"){
            currentTime = 0;
        }
        Cookies.set("pri-background-video-'.$module_id.'", currentTime, { expires: 1 }); 
    });
    ';
} else {
    $script .= 'startOnTime = 0;';
}
$script .= '
    var video'.$module_id.' = $("#pri-background-video-player-'.$module_id.'");
    video'.$module_id.'.autoplay = true;
    video'.$module_id.'.load();
    video'.$module_id.'.on("ended", function() {
        if('.$params->get('video_background_loop').' === false) {
            video'.$module_id.'.get(0).currentTime = 0;
            $("#pri-background-video-player-'.$module_id.'").remove();         
        }
    });
    video'.$module_id.'.on("loadedmetadata", function() {
        video'.$module_id.'.prop("volume", "'.$params->get('video_background_volume').'");
        video'.$module_id.'.get(0).currentTime = startOnTime;
    });
    video'.$module_id.'.on("canplaythrough", function() {
        video'.$module_id.'.get(0).play();
    });
';

// Start var fullscreen
$script .= 'var fullscreen'.$module_id.' = function() {';
if ($params->get('video_background_position') === "absolute") {
    $script .= '
        var width = $("'.$params->get('background_selector').'").width(),
            playerWidth,
            height = $("'.$params->get('background_selector').'").height(),
            playerHeight,
            $videoWrap = $("#pri-background-video-'.$module_id.'");
    ';
} else {
    $script .= '
        var width = $(window).width(),
            playerWidth,
            height = $(window).height(),
            playerHeight,
            $videoWrap = $("#pri-background-video-'.$module_id.'");
    ';
}
$script .='
    if (width / ('.$ratio.') < height) { 
        playerWidth = Math.ceil(height * ('.$ratio.'));
        $videoWrap.width(playerWidth).height(height).css({left: (width - playerWidth) / 2, top: 0}); 
    } else {
        playerHeight = Math.ceil(width / ('.$ratio.')); 
        $videoWrap.width(width).height(playerHeight).css({left: 0, top: (height - playerHeight) / 2}); 
    }
';
// End var fullscreen
$script .= '}';
$script .= '
    fullscreen'.$module_id.'();
        $(window).on("resize", function() {
        fullscreen'.$module_id.'();
    })
';
$script .= '})(jQuery)'; 
$script = \JShrink\Minifier::minify($script);

?>

<div id="pri-background-container-<?php echo $module_id; ?>" class="pri-background-container 
    pri-background-container-<?php echo $params->get('video_background_position'); ?>">
    <div id="pri-background-<?php echo $module_id; ?>" class="pri-background-inner pri-background-size">
        <div id="pri-background-video-<?php echo $module_id; ?>" class="pri-background-video pri-background-size">
            <?php if(!$is_mobile){ ?>
                <video id="pri-background-video-player-<?php echo $module_id; ?>" class="pri-background-size"
                poster="<?php echo $sourceUrl .''.$params->get('video_background_poster'); ?>"
                <?php if($params->get('video_background_loop') === "trie"){?>
                    loop="true" 
                <?php } ?>
                preload="auto">
                    <source type="video/mp4" src="<?php echo $sourceUrl .''.$params->get('video_background_mp4'); ?>"></source>
                    <source type="video/webm" src="<?php echo $sourceUrl .''.$params->get('video_background_webm'); ?>"></source>
                    <source type="video/ogg" src="<?php echo $sourceUrl .''.$params->get('video_background_ogg'); ?>"></source>
                </video>        
            <?php } ?>
        </div>
        <?php include JPATH_ROOT . '/modules/mod_pri_background/includes/overlay.php'; ?>
    </div>
</div>

<?php if(!$is_mobile){ ?>
	<script type="text/javascript">
        <?php echo $script; ?>
    </script>
<?php } ?>