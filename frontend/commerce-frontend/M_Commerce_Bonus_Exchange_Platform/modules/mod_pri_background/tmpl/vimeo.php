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
// Check mobile devices
if ($is_mobile) { 
	// Use image background for mobile devices
	$document->addStyleDeclaration('
		'.$params->get('background_selector').' {
			background: url('.JURI::base() .'/'. $params->get('vimeo_background_poster').') !important;
			background-size: cover !important;
		}
	');
	if ($params->get('vimeo_background_position') === 'fixed') {
		$document->addStyleDeclaration('
			'.$params->get('background_selector').' {
				background-attachment: fixed !important;
			}
		');
	}
} else {
	// Desktop devices
	$document->addScript('//f.vimeocdn.com/js/froogaloop2.min.js');
	if ($params->get('vimeo_background_keep_time') === '1'){
        $document->addScript(JURI::base() .'modules/mod_pri_background/assets/js/js.cookie.min.js');
	}
	$document->addStyleDeclaration('
		#pri-background-'.$module_id.' {
			background: url('.JURI::base() .'/'. $params->get('vimeo_background_poster').');
			background-size: cover;
			z-index:-2;
		}
		'.$params->get('background_selector').' {
			position: relative !important;
			z-index:1;
		}
	');
}
$ratio = $params->get('vimeo_background_ratio');
if ($params->get('vimeo_background_keep_time') === '1'){
    $script = '
    var startOnTime;
    startOnTime = Cookies.get("pri-background-vimeo-'.$module_id.'");
    if(typeof startOnTime === "undefined"){
        startOnTime = 0;
    };
    ';
} else {
    $script = 'startOnTime = 0;';
}
$script .= '
	var currentTime;
	var iframe'.$module_id.' = document.getElementById("pri-vimeo-iframe-'.$module_id.'");
	var player'.$module_id.' = $f(iframe'.$module_id.');
	player'.$module_id.'.addEvent("ready", function() {
		player'.$module_id.'.api("setVolume", '.$params->get('vimeo_background_volume').');
		player'.$module_id.'.api("seekTo", startOnTime);
		player'.$module_id.'.addEvent("finish", onFinish);
	});
	function onFinish(id) {
		iframe'.$module_id.'.parentNode.removeChild(iframe'.$module_id.');
		currentTime = 0;
    }
';
$script .= '(function($){';
if ($params->get('vimeo_background_keep_time') === '1'){
    $script .= '
		$(window).bind("beforeunload", function(){
        player'.$module_id.'.api("getCurrentTime", function (value) {
           	if (typeof currentTime === "undefined"){
				currentTime = value;
			}
			//console.log(currentTime);
            Cookies.set("pri-background-vimeo-'.$module_id.'", currentTime, { expires: 1 });
        });
    });
    ';
}
if ($params->get('vimeo_background_fullscreen') === "1") { 
	// Start var fullscreen
	$script .= 'var fullscreen'.$module_id.' = function() {';
	if ($params->get('vimeo_background_position') === "absolute") {
	    $script .= '
	        var width = $("'.$params->get('background_selector').'").width(),
	            playerWidth,
	            height = $("'.$params->get('background_selector').'").height(),
	            playerHeight,
	            $videoWrap = $("#pri-background-vimeo-'.$module_id.'");
	    ';
	} else {
	    $script .= '
	        var width = $(window).width(),
	            playerWidth,
	            height = $(window).height(),
	            playerHeight,
	            $videoWrap = $("#pri-background-vimeo-'.$module_id.'");
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
}

$script .= '})(jQuery)'; 
$script = \JShrink\Minifier::minify($script);
?>


 
<div id="pri-background-container-<?php echo $module_id; ?>" class="pri-background-container 
	pri-background-container-<?php echo $params->get('vimeo_background_position'); ?>">
	<div id="pri-background-<?php echo $module_id; ?>" class="pri-background-inner pri-background-size">        
		<div id="pri-background-vimeo-<?php echo $module_id; ?>" class="pri-background-size">
	    	<?php if (!$is_mobile) {?>
	        	<iframe id="pri-vimeo-iframe-<?php echo $module_id; ?>" 
	            src="//player.vimeo.com/video/<?php echo $params->get('vimeo_background_id'); ?>?api=1&autoplay=1
	            &loop=<?php echo $params->get('vimeo_background_loop'); ?>&badge=0&byline=0&=portrait=0&title=0
		        &player_id=pri-vimeo-iframe-<?php echo $module_id; ?>" width="100%" height="100%" 
		        frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	        <?php } ?>
	    	</div>
	    <?php include JPATH_ROOT . '/modules/mod_pri_background/includes/overlay.php'; ?>
	</div>
</div>

<?php if (!$is_mobile) {?>
	<script type="text/javascript">
	    <?php echo $script; ?>
    </script>
<?php } ?>
