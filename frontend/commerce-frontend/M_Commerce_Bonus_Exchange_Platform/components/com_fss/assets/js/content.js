
jQuery(document).ready(function () {
	/*jQuery('.fss_content_toolbar_item').mouseenter(function () {
		jQuery(this).css('background-color', '<?php echo FSS_Settings::get('css_hl'); ?>');
	});
	jQuery('.fss_content_toolbar_item').mouseleave(function () {
		jQuery(this).css('background-color' ,'white');
	});*/
	
	jQuery('#fss_content_new').click(function(ev) {
        ev.preventDefault();
		window.location = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->id . '&what=new', false);?>';
	});
	
	jQuery('#fss_content_reset').click(function () {
		jQuery('#fss_search').val("");
		jQuery('#userid').val("");
		jQuery('#ispublished').val("");
		jQuery('#limitstart').val(0);
<?php foreach ($this->filters as $filter) : ?>
		jQuery('#<?php echo $filter->field; ?>').val(0);
<?php endforeach; ?>
	});
	
	jQuery('.filter_field').click(function (ev) {
		ev.preventDefault();
        var current = jQuery('#fss_order').val();
		jQuery('#fss_order').val(jQuery(this).attr('order'));
		if (current == jQuery(this).attr('order') && jQuery('#fss_order_dir').val() == "ASC")
		{
			jQuery('#fss_order_dir').val("DESC");
		} else {
			jQuery('#fss_order_dir').val("ASC");
		}
      
		document.fssForm.submit( );
	});

    jQuery('.filter_field').each(function () {
        var filed = jQuery(this).attr('order');
        var order = jQuery('#fss_order').val();
        if (filed == order)
        {
            var dir = jQuery('#fss_order_dir').val();
            var img = "sort_asc.png";
            if (dir == "DESC")
                img = "sort_desc.png";

            var html = "&nbsp;<img src=\"<?php echo JURI::root( true ); ?>/media/system/images/" + img + "\" border='0'>";
            jQuery(this).append(html);
        }
    });
	
	jQuery('.pagenav').each(function () {
		jQuery(this).attr('href','#');
		jQuery(this).click(function (ev) {
			ev.preventDefault();
			jQuery('#limitstart').val(jQuery(this).attr('limit'));
			document.fssForm.submit( );
		});
	});

	jQuery('.fss_publish_button').click(function (ev) {
		ev.preventDefault();
<?php if (FSS_Permission::auth("core.edit.state", $this->getAsset())): ?>
		var current = jQuery(this).attr('state');
		var id = jQuery(this).attr('id').split('_')[1];
		if (current == 0)
		{
			// publish
			jQuery(this).attr('state',1);
			jQuery(this).children('img').attr('src','<?php echo JURI::base(); ?>/components/com_fss/assets/images/save_16.png');
			jQuery(this).children('img').attr('alt',"<?php echo JText::_('PUBLISHED'); ?>");
			var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->id . '&what=publish&id=XXIDXX', false);?>';
            url = url.replace('XXIDXX',id);
		} else {
			// unpublish
			jQuery(this).attr('state',0);
			jQuery(this).children('img').attr('src','<?php echo JURI::base(); ?>/components/com_fss/assets/images/cancel_16.png');
			jQuery(this).children('img').attr('alt',"<?php echo JText::_('UNPUBLISHED'); ?>");
			var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->id . '&what=unpublish&id=XXIDXX', false);?>';
            url = url.replace('XXIDXX',id);
		}
			
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

function ChangePage(perpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!perpage)
		perpage = 0;
	limitstart.value = perpage;
	
    document.fssForm.submit();
}

function ChangePageCount(perpage)
{
	document.fssForm.submit();
}
