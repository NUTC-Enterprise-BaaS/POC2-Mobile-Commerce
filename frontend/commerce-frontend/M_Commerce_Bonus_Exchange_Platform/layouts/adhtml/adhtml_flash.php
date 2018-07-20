<?php
/**
 *  @package    Social Ads
 *  @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 *  @license    GNU General Public License version 2, or later
 */

defined('_JEXEC') or die( 'Restricted access' ); ?>

<?php if ($displayData->track): ?>

<?php SaCommonHelper::loadScriptOnce(JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer-3.2.13.min.js'); ?>
<?php $ht_wd = 'width:' . $displayData->zone_d->img_width . 'px;height:' . $displayData->zone_d->img_height . 'px'; ?>

	<div
		href="<?php echo JUri::root() . $displayData->ad_image; ?>"
		style="display:block;<?php echo $ht_wd; ?>"
		id="vid_player_<?php echo $displayData->ad_id;?>">
	</div>

	<script>
		flowplayer("vid_player_<?php echo $displayData->ad_id; ?>",
		{
			src:"<?php echo JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer-3.2.18.swf'; ?>",
			wmode:"opaque"
		},
		{
			clip : {
				scaling: "scale",
				autoPlay: true
			},

			/** Default settings for the play button */
			play: {
				opacity: 0.0,
				label: null,
				replayLabel: null,
				fadeSpeed: 500,
				rotateSpeed: 50
			},

			plugins:{
				controls: null,

				content: {
					url:"<?php echo JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer.content-3.2.9.swf'; ?>",
					width:<?php echo $displayData->zone_d->img_width; ?>,
					height:<?php echo $displayData->zone_d->img_height; ?>,
					backgroundColor: "#112233",
					opacity: 0.0,
					onClick: function() {
						window.open("<?php echo $displayData->ad_link ;?> ","_blank");/** opens in new tab*/
					}
				}
			}
		});
	</script>
<?php else: ?>

	<div class="vid_ad_preview"
		href="<?php echo JUri::root() . $displayData->ad_image; ?>"
		style="background:url(<?php echo JUri::root(true) . '/media/com_sa/images/black.png'; ?>);width:<?php echo $displayData->zone_d->img_width; ?>px; height:<?php echo $displayData->zone_d->img_height; ?>px;">
	</div>

	<!-- This is needed for ad preview from backend -->
	<script type="text/javascript">
		flowplayer("div.vid_ad_preview",
		{
			src:"<?php echo JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer-3.2.18.swf'; ?>",
			wmode:"opaque"
		},
		{
			canvas: {
				backgroundColor:"#000000",
				width:<?php echo $displayData->zone_d->img_width; ?>,
				height:<?php echo $displayData->zone_d->img_height; ?>
			},

			/** Default settings for the play button */
			play: {
				opacity: 0.0,
				 label: null,
				 replayLabel: null,
				 fadeSpeed: 500,
				 rotateSpeed: 50
			},

			plugins:{
				controls: null
			}
		});
	</script>

<?php endif;?>

