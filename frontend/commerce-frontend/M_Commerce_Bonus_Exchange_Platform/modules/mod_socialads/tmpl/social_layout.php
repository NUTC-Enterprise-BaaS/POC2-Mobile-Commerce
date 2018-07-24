<?php
$ad_url = "";

// Generate unique ad url for social sharing
if (!empty($addata))
{
	$ad_url = 'index.php?option=com_socialads&view=preview&layout=default&id=' . $addata->ad_id;
	$jlikehtml  = SaIntegrationsHelper::DisplayjlikeButton($ad_url, $addata->ad_id, $addata->ad_title);

	// Integration with Jlike
	if ($jlikehtml)
	{
		?>
		<div style="clear:both;"></div>
		<div class="sa_ad_after_display">
		<?php
			echo $jlikehtml;
		?>
		</div>
		<?php
	}
}

// Integration with addthis.com
$ad_url = JUri::root() . substr(JRoute::_($ad_url), strlen(JUri::base(true))+1);
$add_this_share = '';

// AddThis Button begin
$add_this_share = '
<div class="addthis_toolbox addthis_default_style ">
<a href="https://www.addthis.com/bookmark.php"
class="addthis_button"
addthis:url="'.$ad_url.'"
></a>
</div>
' ;
// AddThis Button END

$pid = '';

if ($sa_params['publisher_id'] != '')
{
	$pid = '#pubid='.$sa_params['publisher_id'];
}

$add_this_js='https://s7.addthis.com/js/250/addthis_widget.js'.$pid;

SaCommonHelper::loadScriptOnce($add_this_js);

// Output all social sharing buttons
echo '<div class="social_share_container">
	<div class="social_share_container_inner">'.
		$add_this_share.
	'</div>
</div>';
