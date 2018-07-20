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
<?php echo FSS_Helper::PageTitlePopup(JText::_("Select a new author")); ?>

<form action="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&tmpl=component&type=' . $this->id . '&what=author'); ?>" method="post" name="fssForm">
<div id="editcell">
	<div class="input-append">
		<input type="text" name="search" id="xsearch" value="<?php echo FSS_Helper::escape($this->search);?>" onchange="document.fssForm.submit();" placeholder="<?php echo JText::_("SEARCH"); ?>" />
		<button class="btn btn-primary" onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
		<button class="btn btn-default" onclick="jQuery('#xsearch').val('');"><?php echo JText::_("RESET"); ?></button>
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
                <a href="#" class="btn btn-default btn-mini" onclick="window.parent.PickUser('<?php echo $user->id; ?>','<?php echo FSS_Helper::escapeJavaScriptText($user->username); ?>','<?php echo FSS_Helper::escapeJavaScriptText($user->name); ?>');parent.fss_modal_hide();return false;">
					<?php echo JText::_("PICK"); ?>
				</a>
            </td>
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
</div>
<input type="hidden" name="view" value="admin_content" />
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="type" value="<?php echo $this->id; ?>" />
<input type="hidden" name="what" value="author" />
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>