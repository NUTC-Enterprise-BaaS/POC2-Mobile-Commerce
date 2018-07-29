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
<?php echo FSS_Helper::PageTitlePopup("INCLUDE_USER"); ?>

<p><?php echo JText::_('INCLUDE_USER_HELP'); ?></p>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=user&tmpl=component&ticketid=' . FSS_Input::getInt('ticketid') );?>" method="post" name="adminForm" id="adminForm">

<div class="input-append">
	<input type="text" name="search" id="filter" value="<?php echo FSS_Helper::escape($this->search);?>" onchange="document.adminForm.submit();" placeholder="<?php echo JText::_("SEARCH"); ?>"/>
	<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_("Go"); ?></button>
	<button class="btn btn-default" onclick="document.getElementById('filter').value='';this.form.submit();this.form.getElementById('faq_cat_id').value='0';"><?php echo JText::_("RESET"); ?></button>
</div>


<table class="table table-bordered table-condensed table-striped">
<thead>

    <tr>
		<th width="5">#</th>
        <th nowrap="nowrap" style="text-align:left;">
            <?php echo JHTML::_('grid.sort',   'User_ID', 'question', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
        </th>
		<th nowrap="nowrap" style="text-align:left;">
			<?php echo JHTML::_('grid.sort',   'User_Name', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th nowrap="nowrap" style="text-align:left;">
			<?php echo JHTML::_('grid.sort',   'EMail', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
		</th>
		<th nowrap="nowrap" style="text-align:left;">
			<?php echo JText::_("PICK"); ?>
		</th>
	</tr>
</thead>
<?php
if (count($this->users) == 0)
{
    echo "<tr><td colspan=6>" . JText::_('NO_USERS_FOUND') . "</td></tr>";	
}
$k = 0;
foreach ($this->users as $user)
{
    //$link = FSSRoute::_( 'index.php?option=com_fss&controller=faq&task=edit&cid[]='. $row->id );

    ?>
    <tr class="<?php echo "row$k"; ?>">
        <td>
            <?php echo $user->id; ?>
        </td>
        <td>
            <?php echo $user->username; ?>
        </td>
        <td>
            <?php echo $user->name; ?>
        </td>
        <td>
            <?php echo $user->email; ?>
		</td>
		<td>
            <a href="#" class='btn btn-mini btn-success pick_user' id="user_<?php echo $user->id; ?>"><?php echo JText::_("ADD_USER"); ?></a>
			<a href="#" class='btn btn-mini btn-warning pick_user_ro' id="user_<?php echo $user->id; ?>"><?php echo JText::_("ADD_READ_ONLY"); ?></a>
        </td>
		
	</tr>
    <?php
    $k = 1 - $k;
}
?>
</table>


<?php echo $this->pagination->getListFooter(); ?>

<input type="hidden" name="filter_order" value="<?php echo FSS_Helper::escape($this->lists['order']); ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo FSS_Helper::escape($this->lists['order_Dir']); ?>" />
</form>


</div>

<div class="modal-footer">
	<a href='#' class="btn btn-default" onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_('CANCEL'); ?></a>
</div>
	
<script>
jQuery(document).ready(function () {
	jQuery('.pick_user').click(function(ev) { 
		ev.preventDefault();
		window.parent.AddCCUser(jQuery(this).attr('id').split('_')[1], 0);
	});
	jQuery('.pick_user_ro').click(function(ev) { 
		ev.preventDefault();
		window.parent.AddCCUser(jQuery(this).attr('id').split('_')[1], 1);
	});
});
</script>					 	     	 	