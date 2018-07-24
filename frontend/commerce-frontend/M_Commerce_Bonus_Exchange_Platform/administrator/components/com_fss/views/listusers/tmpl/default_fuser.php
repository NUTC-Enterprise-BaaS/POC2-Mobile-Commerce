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
<?php echo FSS_Helper::PageTitlePopup('Add User'); ?>

<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=listusers' );?>" method="post" name="adminForm" id="adminForm">
<style>
table.adminlist td
{
	padding-top:0px;
	padding-bottom:0px;
	/*padding:0px;*/
}
</style>
<div id="editcell">
	<table class="fss_admin_header">
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo FSS_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.getElementById('gid').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['gid']; ?>
			</td>
		</tr>
	</table>
	
    <table class="table table-condensed table-striped table-bordered">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20" class="title">
   				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Username', 'u.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Name', 'u.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'EMail', 'u.email', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Joomla_Group', 'gid', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
    </thead>
<?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>

			<td>
			    <a href='#' onclick="window.parent.selectUser(<?php echo $row->id; ?>, '<?php echo $row->username; ?>');return false;"><?php echo $row->username; ?></a>			
			</td>

			<td>
			    <?php echo $row->name; ?>			</td>

			<td>
			    <?php echo $row->email; ?>			</td>

			<td>
			    <?php echo $row->lf1; ?>			</td>

		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>

    </table>
</div>

<?php if (FSSJ3Helper::IsJ3()): ?>
	<div class='pull-right'>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
<?php endif; ?>
<?php echo $this->pagination->getListFooter(); ?>

<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="groupid" value="<?php echo JRequest::getVar('groupid'); ?>" />
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="listuser" />
<input type="hidden" name="tpl" value="fuser" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

</div>

<div class="modal-footer">
	<a href='#' class="btn btn-default" onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_('Cancel'); ?></a>
</div>
	