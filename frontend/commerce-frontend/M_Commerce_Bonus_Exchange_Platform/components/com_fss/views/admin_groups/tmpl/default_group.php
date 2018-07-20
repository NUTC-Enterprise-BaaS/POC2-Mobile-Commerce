<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$cbno = 0;
?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"EDIT_GROUP"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<div class="pull-right" style="margin-bottom: 6px;">
	<a href="#" id='fss_form_save' class="btn btn-success">
		<i class="icon-apply icon-white"></i>
		<?php echo JText::_('SAVE');?>
	</a>
	<?php if (!$this->creating): ?>
		<a href="#" id='fss_form_saveclose' class="btn btn-default">
			<i class="icon-apply"></i>
			<?php echo JText::_('SAVE_AND_CLOSE');?>
		</a>
	<?php endif; ?>
	<a href="#" id='fss_form_cancel' class="btn btn-default">
		<i class="icon-cancel"></i>
		<?php echo JText::_('CANCEL');?>
	</a>
	<?php if (!$this->creating && FSS_Permission::auth("fss.groups", "com_fss.groups")): ?>
		<a href="#" id='fss_form_delete' class="btn btn-default">
			<i class="icon-delete"></i>
			<?php echo JText::_('DELETE');?>
		</a>
	<?php endif; ?>
</div>

<?php echo  FSS_Helper::PageSubTitle('GROUP_DETAILS'); ?>

<form action="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups&what=savegroup',false);?>" method="post" name="groupForm" id="groupForm" class="form-horizontal form-condensed">
	<input type="hidden" name="groupid" value="<?php echo FSS_Helper::escape($this->group->id); ?>" />
	<input type="hidden" name="what" value="savegroup" />

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("NAME"); ?></label>
		<div class="controls">
			<input class="text_area" type="text" name="groupname" id="groupname" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->group->groupname);?>" required />
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("DESCRIPTION"); ?></label>
		<div class="controls">
			<input class="text_area" type="text" name="description" id="description" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->group->description);?>" />
		</div>
	</div>	
	

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("ALL_SEE"); ?></label>
		<div class="controls">
			<?php echo $this->allsee; ?>
			<span class="help-inline">
				<?php echo JText::_("ALL_SEE_HELP"); ?>
			</span>
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("ALL_EMAIL"); ?></label>
		<div class="controls">
			<input type='checkbox' name='allemail' value='1' <?php if ($this->group->allemail) { echo " checked='yes' "; } ?>>
			<span class="help-inline">
				<?php echo JText::_("ALL_EMAIL_HELP"); ?>
			</span>
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("CCEXCLUDE"); ?></label>
		<div class="controls">
			<input type='checkbox' name='ccexclude' value='1' <?php if ($this->group->ccexclude) { echo " checked='yes' "; } ?>>
			<span class="help-inline">
				<?php echo JText::_("CCEXCLUDE_HELP"); ?>
			</span>
		</div>
	</div>	

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("SHOW_ALL_PRODUCTS_ON_TICKET_OPEN"); ?></label>
		<?php echo $this->allprod; ?>
	</div>	

	<div class="control-group" id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
		<label class="control-label"><?php echo JText::_("PRODUCTS"); ?></label>
		<div class="controls">
			<div>
				<?php echo $this->products; ?>
			</div>
		</div>
	</div>	
	

</form>
	
<?php if (!$this->creating): ?>
	<div class="pull-right" style="margin-bottom: 6px;">
		<a href="#" id='fss_form_add' class="btn btn-default"><i class="icon-new"></i> <?php echo JText::_('ADD');?></a>
		<a href="#" id='fss_form_remove' class="btn btn-default"><i class="icon-delete"></i> <?php echo JText::_('REMOVE');?></a>
	</div>
	<?php echo  FSS_Helper::PageSubTitle('MEMBERS'); ?>

<form action="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups&groupid=' . $this->group->id,false);?>" method="post" name="fssForm" id="fssForm">
<input type="hidden" name="what" value="" />
<table class="table table-bordered table-condensed table-striped">
    <thead>

        <tr>
            <th width="20">
			</th>
            <th>
				<?php echo FSS_Helper::sort('User', 'username', @$this->order_Dir, @$this->order ); ?>
            </th>
            <th>
 				<?php echo FSS_Helper::sort('ISADMIN', 'isadmin', @$this->order_Dir, @$this->order ); ?>
			</th>
            <th>
 				<?php echo FSS_Helper::sort('ALL_EMAIL_USER', 'allemail', @$this->order_Dir, @$this->order ); ?>
			</th>
            <th>
				<?php echo FSS_Helper::sort('ALL_SEE_USER', 'allsee', @$this->order_Dir, @$this->order ); ?>
			</th>
		</tr>
    </thead>
    <?php
    if (count($this->groupmembers) == 0)
    {
    ?>
		<tbody>
			<tr>
				<td colspan="5"><?php echo JText::_('NO_USERS'); ?></td>
			</tr>
		</tbody>
	<?php	
    }
    $k = 0;
    for ($i=0, $n=count( $this->groupmembers ); $i < $n; $i++)
	{
		$row = $this->groupmembers[$i];
    	
		$user = JFactory::getUser();
		$userid = $user->id;
	
		$allemail = FSS_Helper::GetYesNoText($row->allemail);
    	$isadmin = FSS_Helper::GetYesNoText($row->isadmin);
    	
	
    	if ($row->allsee == 0)
    	{
    		$allsee = JText::_('INHERITED');//"None";	
    		$allsee .= " (";
    		$perm = $this->group->allsee;
    		if ($perm == 0)
    		{
    			$allsee .= JText::_('VIEW_NONE');//"None";	
    		} elseif ($perm == 1)
    		{
    			$allsee .= JText::_('VIEW');//"See all tickets";	
    		} elseif ($perm == 2)
    		{
    			$allsee .= JText::_('VIEW_REPLY');//"Reply to all tickets";	
    		} elseif ($perm == 3)
    		{
    			$allsee .= JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
    		}
    		$allsee .= ")";
    	} elseif ($row->allsee == -1)
    	{
    		$allsee = JText::_('VIEW_NONE');//"See all tickets";	
    	} elseif ($row->allsee == 1)
    	{
    		$allsee = JText::_('VIEW');//"See all tickets";	
    	} elseif ($row->allsee == 2)
    	{
    		$allsee = JText::_('VIEW_REPLY');//"Reply to all tickets";	
    	} elseif ($row->allsee == 3)
    	{
    		$allsee = JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
    	}
    	
    ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
				<?php if ($userid != $row->user_id || FSS_Permission::auth("fss.groups", "com_fss.groups")): ?>
					<input type="checkbox" id="cb<?php echo $cbno; ?>" name="cid[]" value="<?php echo $row->user_id; ?>">
					<?php $cbno++; ?>
				<?php endif; ?>
			</td>
			<td>
			    <?php echo $row->name; ?> (<?php echo $row->username; ?>)<br /><?php echo $row->email; ?>
			<td>
				<?php if ($userid != $row->user_id || FSS_Permission::auth("fss.groups", "com_fss.groups")): ?>
					<a href='#' id='is_admin_<?php echo (int)$row->user_id; ?>' class='is_admin'>
						<?php echo $isadmin; ?>
					</a>
				<?php else: ?>	
					<img src="<?php echo JURI::base(); ?>/components/com_fss/assets/images/tickgray.png" width="16" height="16" border="0" />
				<?php endif; ?>
			</td>
			<td>
				<a href='#' id='all_email_<?php echo (int)$row->user_id; ?>' class='all_email'>
					<?php echo $allemail; ?>
				</a>
			</td>
			<td>
				<div class="pull-right">
					<a class="fssTip edit_perm" href='#' id="editperm_<?php echo (int)$row->user_id; ?>" title="<?php echo JText::_('EDIT'); ?>">
						<img src="<?php echo JURI::base(); ?>/components/com_fss/assets/images/edit.png" width="18" height="18" alt="<?php echo JText::_('EDIT'); ?>"/>
					</a>
				</div>
				<div id="perm_<?php echo (int)$row->user_id; ?>"><?php echo $allsee; ?></div>
			</td>
		</tr>
        <?php
        $k = 1 - $k;
       }
        ?>
		
		<?php $footer = $this->pagination->getListFooter(); ?>
		<?php if ($footer): ?>
			<tfoot>
				<tr>
					<td colspan="9"><?php echo $footer; ?></td>
				</tr>
			</tfoot>
		<?php endif;?>
    </table>

	<input type="hidden" name="limit_start" id="limitstart" value="<?php echo (int)$this->limit_start; ?>">
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo FSS_Helper::escape($this->order); ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo FSS_Helper::escape($this->order_Dir); ?>" />
</form>
<?php else: ?>
	<?php echo  FSS_Helper::PageSubTitle('MEMBERS'); ?>
	<div class="alert alert-info"><?php echo JText::_("PLEASE_SAVE_FIRST"); ?></div>
<?php endif; ?>

<div class="modal fss_modal" id="popup_html" style='display: none; max-width: 560px;margin-left: -280px;'>
	<div class="modal-header">
		<button class="close simplemodal-close" data-dismiss="modal">&times;</button>
		<h3><?php echo JText::_("CHOOSE_NEW_PERM"); ?></h3>
	</div>
	<div class="modal-body">
		<p><?php echo JText::_('CHOOSE_NEW_PERM_HELP'); ?></p>
		<p><?php echo JText::_('GROUP_DEFAULT'); ?>: 
		<?php 
		$perm = $this->group->allsee;
		if ($perm == 0)
		{
			echo JText::_('VIEW_NONE');//"None";	
		} elseif ($perm == 1)
		{
			echo JText::_('VIEW');//"See all tickets";	
		} elseif ($perm == 2)
		{
			echo JText::_('VIEW_REPLY');//"Reply to all tickets";	
		} elseif ($perm == 3)
		{
			echo JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
		}
		?></p>
		<div class="fss_gp_item"><a href="#" id="popup_perm_0" class="popup_perm"><?php echo JText::_('INHERITED'); ?></a></div>
		<div class="fss_gp_item"><a href="#" id="popup_perm_-1" class="popup_perm"><?php echo JText::_('VIEW_NONE'); ?></a></div>
		<div class="fss_gp_item"><a href="#" id="popup_perm_1" class="popup_perm"><?php echo JText::_('VIEW'); ?></a></div>
		<div class="fss_gp_item"><a href="#" id="popup_perm_2" class="popup_perm"><?php echo JText::_('VIEW_REPLY'); ?></a></div>
		<div class="fss_gp_item"><a href="#" id="popup_perm_3" class="popup_perm"><?php echo JText::_('VIEW_REPLY_CLOSE'); ?></a></div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-default simplemodal-close" data-dismiss="modal"><?php echo JText::_('JCANCEL'); ?></a>
	</div>
</div>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>
function DoAllProdChange()
{
	var form = document.groupForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		prodlist.style.display = 'none';
	} else {
		prodlist.style.display = 'inline';
	}
}

jQuery(document).ready(function () {

	// move popup html to end of page
	jQuery('body').append(jQuery('#popup_html'));

	jQuery('#fss_form_save').click(function (ev) {
		ev.preventDefault();
		document.groupForm.submit();
	});
	
	jQuery('#fss_form_saveclose').click(function (ev) {
		ev.preventDefault();
		document.groupForm.what.value = 'saveclose';
		document.groupForm.submit();
	});

	jQuery('#fss_form_remove').click(function (ev) {
		ev.preventDefault();
		//if (document.fssForm.boxchecked.value == 0)
		//{
		//	alert("<?php echo JText::_('MUST_SELECT'); ?>");
		//} else {		
			document.fssForm.what.value = 'removemembers';
			document.fssForm.submit();
		//}
	});

	jQuery('#fss_form_add').click(function (ev) {
		ev.preventDefault();
		fss_modal_show('<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups&tmpl=component&what=pickuser&groupid='. $this->group->id); ?>', true, 720);
		//TINY.box.show({iframe:'<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups&tmpl=component&what=pickuser&groupid='. $this->group->id); ?>', width:630, height:440 });
	});
	
	jQuery('#fss_form_cancel').click( function (ev) {
		ev.preventDefault();
		window.location = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups', false); ?>';
	});
	
	jQuery('#fss_form_delete').click( function (ev) {
		ev.preventDefault();
		if (confirm('<?php echo JText::_('CONFIRM_DELETE'); ?>'))
		{
			document.groupForm.what.value = "deletegroup";
			document.groupForm.submit();
		}
	});
	
	var permtext = new Object();
	
    permtext['0'] = '<?php $allsee = JText::_('INHERITED');//"None";	
    $allsee .= " (";
    $perm = $this->group->allsee;
    if ($perm == 0)
    {
    	$allsee .= JText::_('VIEW_NONE');//"None";	
    } elseif ($perm == 1)
    {
    	$allsee .= JText::_('VIEW');//"See all tickets";	
    } elseif ($perm == 2)
    {
    	$allsee .= JText::_('VIEW_REPLY');//"Reply to all tickets";	
    } elseif ($perm == 3)
    {
    	$allsee .= JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
    }
    $allsee .= ")";
    echo $allsee; ?>';
			
    permtext['-1'] = '<?php echo JText::_('VIEW_NONE'); ?>';
    permtext['1'] = '<?php echo JText::_('VIEW'); ?>';
    permtext['2'] = '<?php echo JText::_('VIEW_REPLY'); ?>';
    permtext['3'] = '<?php echo JText::_('VIEW_REPLY_CLOSE'); ?>';	

	var popup_id = '';
	
	jQuery('.all_email').click(function (ev) {
		ev.preventDefault();
		
		var id = jQuery(this).attr('id').split('_')[2];
		var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups&what=toggleallemail&userid=XXUIDXX&groupid=' . $this->group->id, false); ?>';
		url = url.replace('XXUIDXX', id);
		var t = this;
		jQuery(t).html('<?php echo JText::_('PLEASE_WAIT'); ?>');
		jQuery.ajax({
			url: url,
			context: document.body,
			success: function(result){
				jQuery(t).html(result);
			}
		});
	});
	
	jQuery('.is_admin').click(function (ev) {
		ev.preventDefault();

		var id = jQuery(this).attr('id').split('_')[2];
		var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups&what=toggleadmin&userid=XXUIDXX&groupid=' . $this->group->id, false); ?>';
		url = url.replace('XXUIDXX',id);
		var t = this;
		jQuery(t).html('<?php echo JText::_('PLEASE_WAIT'); ?>');
		jQuery.ajax({
			url: url,
			context: document.body,
			success: function(result){
				jQuery(t).html(result);
			}
		});
	});
	
	jQuery('.popup_perm').click( function (ev) {
		ev.preventDefault();
		var newid = jQuery(this).attr('id').split('_')[2];
		var text = permtext[newid];
		var url = '<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_groups&what=setperm&userid=XXUIDXX&perm=XXPXX&groupid=' . $this->group->id, false); ?>';
		url = url.replace('XXUIDXX', popup_id);
		url = url.replace('XXPXX', newid);
				
		jQuery('#perm_' + popup_id).html('<?php echo JText::_('PLEASE_WAIT'); ?>');
		jQuery.ajax({
			url: url,
			context: document.body,
			success: function(result){
				if (result == "1")
				{
					jQuery('#perm_' + popup_id).html(text);
				} else {
					alert("Error changing permission");
				}
			}
		});
		jQuery('#popup_html').modal("hide");
	});
			
    jQuery('.edit_perm').click(function (ev) {
		ev.preventDefault();
		popup_id = jQuery(this).attr('id').split('_')[1];
		jQuery('#popup_html').modal("show");
		fss_modal_bs3_fix();
	});

	jQuery('.pagenav').each(function () {
		jQuery(this).attr('href','#');
		jQuery(this).click(function (ev) {
			ev.preventDefault();
			jQuery('#limitstart').val(jQuery(this).attr('limit'));
			document.fssForm.submit( );
		});
	});

});

function ChangePageCount(perpage)
{
	document.fssForm.submit( );
}

</script>