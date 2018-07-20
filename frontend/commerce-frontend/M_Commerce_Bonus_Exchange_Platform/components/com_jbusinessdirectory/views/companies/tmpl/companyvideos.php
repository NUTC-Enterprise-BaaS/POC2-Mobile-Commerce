<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
?>
<div class='video-container'>
	<?php 
	if(!empty($this->videos)){
		foreach( $this->videos as $video ){
			if(!empty($video->url))	{ ?>
				<a class="popup-video" href="<?php echo $video->url ?>">
					<div class="videoSitesLoader-holder">
						<div class="play_btn"></div>
						<div class="videoSitesLoader" video-type="<?php echo $video->videoType ?>" style="background-image:url('<?php echo $video->videoThumbnail ?>')"></div>
					</div>
				</a>
			<?php
			}
		}
	} else {
		echo JText::_("LNG_NO_COMPANY_VIDEO");
	} ?>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.popup-video').magnificPopup({
			disableOn: 200,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false,
			mainClass: 'mfp-fade'
		});
	});
</script>