<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStylePopup(true); ?>
<?php echo FSS_Helper::PageTitlePopup(JText::_("ADD_USERS_TO_TICKET_GROUP")); ?>

<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_groups&tmpl=component', false );?>" method="post" name="fssForm" id="adminForm">
<input name="boxchecked" value="0" type="hidden" />
<style>
table.adminlist td
{
	padding-top:0px;
	padding-bottom:0px;
	/*padding:0px;*/
}
</style>
<?php if (FSS_Permission::auth("fss.groups", "com_fss.groups")): ?>		
		<div class="input-append">
			<input type="text" name="search" id="filter" value="<?php echo FSS_Helper::escape($this->search);?>" class="text_area" onchange="document.fssForm.submit();" placeholder="<?php echo JText::_( 'SEARCH' );?>"/>
			<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button class="btn btn-default" onclick="document.getElementById('filter').value='';this.form.getElementById('gid').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</div>
		
<?php else: ?>
	<p class="form-inline">
		<label>
			<?php echo JText::_( 'UserName' ); ?>
		</label>
		<input type="text" class="input-small" name="username" id="username" value="<?php echo FSS_Helper::escape($this->username);?>" class="text_area" placeholder="<?php echo JText::_( 'UserName' ); ?>" />
		<label>
			<?php echo JText::_( 'EMail' ); ?>
		</label>
		<input type="text" class="input-medium"  name="email" id="email" value="<?php echo FSS_Helper::escape($this->email);?>" class="text_area" placeholder="<?php echo JText::_( 'EMail' ); ?>" />
		<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
		<button class="btn btn-default" onclick="document.getElementById('username').value='';this.form.getElementById('email').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
	</p>	
<?php endif; ?>

	<table class="table table-bordered table-condensed table-striped">
    <thead>
			<tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" id="checkall" onclick="Joomla.checkAll(this);" />
			</th>

            <th>
				<?php echo JHTML::_('grid.sort',   'Username', 'username', @$this->order_Dir, @$this->order ); ?>
			</th>

            <th>
				<?php echo JHTML::_('grid.sort',   'Name', 'name', @$this->order_Dir, @$this->order ); ?>
			</th>

            <th>
				<?php echo JHTML::_('grid.sort',   'EMail', 'email', @$this->order_Dir, @$this->order ); ?>
			</th>

<?php if (FSS_Permission::auth("fss.groups", "com_fss.groups")): ?>	
            <th>
				<?php echo JHTML::_('grid.sort',   'Joomla_Group', 'gid', @$this->order_Dir, @$this->order ); ?>
			</th>
<?php endif; ?>
		</tr>
    </thead>
<?php
if (count($this->users) == 0): ?>
	<tbody>
		<tr>
			<td colspan="5">
				<?php if (FSS_Permission::auth("fss.groups", "com_fss.groups")): ?>	
					<?php echo JText::_('NO_USERS_FOUND'); ?>
				<?php else: ?>
					<?php echo JText::_('NO_USERS_FOUND_ENTER'); ?>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
<?php endif;
    $k = 0;
    for ($i=0, $n=count( $this->users ); $i < $n; $i++)
    {
        $row = $this->users[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
		$link = FSSRoute::_( 'index.php?option=com_fss&view=admin_groups&what=adduser&cid[]='. $row->id . '&groupid=' . FSS_Input::getInt('groupid') );


?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href='<?php echo $link; ?>'>	<?php echo $row->username; ?></a>			
			</td>
			<td>
			    <?php echo $row->name; ?>			
			</td>
			<td>
			    <?php echo $row->email; ?>			
			</td>
<?php if (FSS_Permission::auth("fss.groups", "com_fss.groups")): ?>				
			<td>
				<?php echo $row->lf1; ?>			
			</td>
<?php endif; ?>	
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>
<?php $footer = $this->pagination->getListFooter();
	if ($footer): ?>
	<tfoot>
		<tr>
			<td colspan="9"><?php echo $footer; ?></td>
		</tr>
	</tfoot>
	<?php endif; ?>

    </table>

<input type="hidden" name="groupid" value="<?php echo FSS_Input::getInt('groupid'); ?>" />
<input type="hidden" name="what" value="pickuser" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo FSS_Helper::escape($this->order); ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo FSS_Helper::escape($this->order_Dir); ?>" />
<input type="hidden" name="limit_start" id="limitstart" value="<?php echo (int)$this->limit_start; ?>">
</form>


</div>
<div class="modal-footer">
<a href='#' class="btn btn-primary" id="addlink" onclick='document.fssForm.what.value="adduser";document.fssForm.submit();return false;'><?php echo JText::_('ADD_USERS_TO_GROUP'); ?></a>
<a href='#' class="btn btn-default" onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_('CANCEL'); ?></a>
</div>
	
<script>

function ChangePageCount(perpage)
{
	document.fssForm.submit( );
}
	
jQuery(document).ready(function () {
	jQuery('.pagenav').each(function () {
		jQuery(this).attr('href','#');
		jQuery(this).click(function (ev) {
			ev.preventDefault();
			jQuery('#limitstart').val(jQuery(this).attr('limit'));
			document.fssForm.submit( );
		});
	});
});

function checkAll(count)
{
	for (i = 0 ; i < count ; i++)
	{
		if (jQuery('#checkall').is(':checked'))
		{	
			jQuery('#cb' + i).attr('checked', 'checked');
		} else {
			jQuery('#cb' + i).removeAttr('checked');
		}
	}
}

</script>
