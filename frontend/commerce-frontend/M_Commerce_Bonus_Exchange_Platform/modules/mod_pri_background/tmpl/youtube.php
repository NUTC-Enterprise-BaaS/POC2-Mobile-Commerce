<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_pri_background
 * @version     4.0
 *
 * @copyright   Copyright (C) 2010 - 2016 Devpri. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
if (!$is_mobile) { 
	if ($params->get('youtube_background_keep_time') === '1'){
        $document->addScript(JURI::base() .'modules/mod_pri_background/assets/js/js.cookie.min.js');
	}
	$document->addScriptDeclaration("
	    var tag = document.createElement('script');
	    tag.src = 'https://www.youtube.com/iframe_api';
	    var firstScriptTag = document.getElementsByTagName('script')[0];
	    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	");
}
$document->addStyleDeclaration('
	#pri-background-youtube-'.$module_id.' {
		background: url('.JURI::base() .'/'. $params->get('youtube_background_poster').');
		background-size: cover;
		z-index:-2;
	}
	'.$params->get('background_selector').' {
		position: relative !important;
		z-index:1;
	}
');
$ratio = $params->get('youtube_background_ratio');
if ($params->get('youtube_background_keep_time') === '1'){
    $script = '
    var startOnTime;
    startOnTime = Cookies.get("pri-background-youtube-'.$module_id.'");
    if(typeof startOnTime === "undefined"){
        startOnTime = 0;
    };
    ';
} else {
    $script = 'startOnTime = 0;';
}
$script .= '
	var currentTime;
	var player'.$module_id.';
      	function onYouTubeIframeAPIReady() {
            player'.$module_id.' = new YT.Player("pri-background-youtube-iframe-'.$module_id.'", {
            playerVars: { "autoplay": 1, 
		  				"controls": '.$params->get('youtube_background_controls').',
						"autohide":1,
						"iv_load_policy": 3,
						"showinfo":0,
						"vq": "'.$params->get('youtube_background_quality').'",
						"loop":'.$params->get('youtube_background_loop').',
						"wmode":"opaque",
						"playlist": "'.($params->get('youtube_background_loop') === '1' ? $params->get('youtube_background_id'): '').'"
					   },
            videoId: "'.$params->get('youtube_background_id').'",
            height: "100%",
            width: "100%",
		    events: {
      			"onReady": onPlayerReady'.$module_id.',
			    "onStateChange": onPlayerStateChange'.$module_id.'
    	 	} 
        });
      }
	  function onPlayerReady'.$module_id.'(event) {
	    event.target.setVolume('.$params->get('youtube_background_volume').');
	    event.target.seekTo(startOnTime);
	  }
	  function onPlayerStateChange'.$module_id.'(event) {
		document.getElementById("pri-background-youtube-iframe-'.$module_id.'").style.visibility = "visible";
		if(event.data === 0 && '.$params->get('youtube_background_loop').' === 0) {
			player'.$module_id.'.destroy();
			currentTime = 0;    
		}
	  }
';
$script .= '(function($){';
if ($params->get('youtube_background_keep_time') === '1'){
    $script .= '
    $(window).unload(function(){
        if (typeof currentTime === "undefined"){
        	currentTime = player'.$module_id.'.getCurrentTime();
        }
        Cookies.set("pri-background-youtube-'.$module_id.'", currentTime, { expires: 1 });
    });
    ';
}
if ($params->get('youtube_background_fullscreen') === "1") { 
	// Start var fullscreen
	$script .= 'var fullscreen'.$module_id.' = function() {';
	if ($params->get('youtube_background_position') === "absolute") {
	    $script .= '
	        var width = $("'.$params->get('background_selector').'").width(),
	            playerWidth,
	            height = $("'.$params->get('background_selector').'").height(),
	            playerHeight,
	            $videoWrap = $("#pri-background-youtube-'.$module_id.'");
	    ';
	} else {
	    $script .= '
	        var width = $(window).width(),
	            playerWidth,
	            height = $(window).height(),
	            playerHeight,
	            $videoWrap = $("#pri-background-youtube-'.$module_id.'");
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
	pri-background-container-<?php echo $params->get('youtube_background_position'); ?>">
    <div id="pri-background-<?php echo $module_id; ?>" class="pri-background-inner pri-background-size">
       	<div id="pri-background-youtube-<?php echo $module_id; ?>" class="pri-background-youtube pri-background-size">
       		<?php if (!$is_mobile){?>
       			<div id="pri-background-youtube-iframe-<?php echo $module_id; ?>"></div>
       		<?php } ?>
       	</div>
        <?php include JPATH_ROOT . '/modules/mod_pri_background/includes/overlay.php'; ?>
    </div>
</div>

<?php if (!$is_mobile){?>
	<script type="text/javascript">
	    <?php echo $script; ?>
    </script>
<?php } ?>
 
