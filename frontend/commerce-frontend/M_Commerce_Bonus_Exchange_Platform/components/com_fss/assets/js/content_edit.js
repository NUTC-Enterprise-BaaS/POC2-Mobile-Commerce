
<?php if (empty($this->content->changeclass)) $this->content->changeclass = ""; ?>

jQuery(document).ready(function () {
	jQuery('.fss_publish_button').click(function (ev) {
		ev.preventDefault();
<?php if ($this->content->auth("core.edit.state")): ?>
		var current = jQuery(this).attr('state');
		var id = jQuery(this).attr('id').split('_')[1];
		if (current == 0)
		{
			// publish
			jQuery(this).attr('state',1);
			jQuery(this).children('img').attr('src','<?php echo JURI::base(); ?>/components/com_fss/assets/images/save_16.png');
			jQuery(this).children('img').attr('alt',"<?php echo JText::_('PUBLISHED'); ?>");
			jQuery(this).attr('title',"<?php echo JText::_('CONTENT_PUB_TIP'); ?>");
			var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->content->id . '&what=publish', false);?>&id=' + id;

<?php if ($this->content->changeclass) : ?>
           jQuery(this).parent().parent().removeClass('content_edit_unpublished');
<?php endif; ?>
		} else {
			// unpublish
			jQuery(this).attr('state',0);
			jQuery(this).children('img').attr('src','<?php echo JURI::base(); ?>/components/com_fss/assets/images/cancel_16.png');
			jQuery(this).children('img').attr('alt',"<?php echo JText::_('UNPUBLISHED'); ?>");
			jQuery(this).attr('title',"<?php echo JText::_('CONTENT_UNPUB_TIP'); ?>");
			var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->content->id . '&what=unpublish&id=XXIDXX', false);?>';
            url = url.replace("XXIDXX",id);
<?php if ($this->content->changeclass) : ?>
            jQuery(this).parent().parent().addClass('content_edit_unpublished');
<?php endif; ?>
		}
		jQuery(this).unbind('mouseover');
		jQuery(this).unbind('mouseout');

		jQuery.ajax({
			url: url,
			success: function(data) {
				//alert(data);

			}
		});
		
<?php else: ?>		
		alert('<?php echo JText::_('NO_PUBLISH_PERM'); ?>');
<?php endif; ?>
	});
});
