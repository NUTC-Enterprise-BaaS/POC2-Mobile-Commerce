<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// no direct access
defined('_JEXEC') or die;
?>

<div class="clearfix">&nbsp;</div>

<div class="list-group">
	<div class="list-group-item">
		<div class="pull-left">
			<?php echo JText::_('COM_SA_FACEBOOK');?>
		</div>
		<div class="pull-right">
			<!-- facebook button code -->
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>
			<div class="fb-like" data-href="https://www.facebook.com/techjoomla" data-send="true" data-layout="button_count" data-width="250" data-show-faces="false" data-font="verdana"></div>
		</div>
		<div class="clearfix">&nbsp;</div>
	</div>

	<div class="list-group-item">
		<div class="pull-left">
			<?php echo JText::_('COM_SA_TWITTER'); ?>
		</div>
		<div class="pull-right">
			<!-- twitter button code -->
			<a href="https://twitter.com/techjoomla" class="twitter-follow-button" data-show-count="false">Follow @techjoomla</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
		<div class="clearfix">&nbsp;</div>
	</div>

	<div class="list-group-item">
		<div class="pull-left">
			<?php echo JText::_('COM_SA_GPLUS'); ?></div>
		<div class="pull-right">
			<!-- Place this tag where you want the  1 button to render. -->
			<div class="g-plusone" data-annotation="inline" data-width="120" data-href="https://plus.google.com/102908017252609853905"></div>
			<!-- Place this tag after the last  1 button tag. -->
			<script type="text/javascript">
				(function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>
		</div>
		<div class="clearfix">&nbsp;</div>
	</div>
</div>
