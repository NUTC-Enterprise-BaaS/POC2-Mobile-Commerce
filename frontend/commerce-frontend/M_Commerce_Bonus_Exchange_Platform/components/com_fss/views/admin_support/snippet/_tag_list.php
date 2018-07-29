<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$alltags = SupportHelper::getTags()
?>

<?php foreach ($alltags as $tag): ?>
	<li>
		<a href='#' onclick="tag_add(jQuery(this).text());return false;"><?php echo $tag->tag; ?></a>
	</li>
<?php endforeach; ?>

<?php if (count($alltags) > 0): ?>
	<li class="divider"></li>
<?php endif; ?>

<li style="padding-left: 8px;padding-right: 8px;" id="tags_new">
	<div class="input-append">
		<input type="text" name="tag" id="new_tag" class="input-small tag_add_input" value="" size="30" style="width:150px">
		<button class="btn btn-success" href="#" onclick="tag_add(jQuery('#new_tag').val());return false;">
			<i class="icon-save"></i>
		</button>
	</div>
</li>

<script>

jQuery(document).ready( function () {
	jQuery('#tags_new').click (function (ev) {
		ev.preventDefault();
		ev.stopPropagation();
		
	});
});
</script>