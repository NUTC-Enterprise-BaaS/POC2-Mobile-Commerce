<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=fields' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = (strpos($this->lists['order'], "ordering") !== FALSE); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['ident'];
				?>
				<?php FSSAdminHelper::LA_Filter(true); ?>
			</td>
		</tr>
	</table>
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Description', 'description', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Section', 'ident', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Type', 'type', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Grouping', 'grouping', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Prods', 'allprods', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Depts', 'alldepts', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Search', 'basicsearch', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'ADV_SEARCH', 'advancedsearch', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'IN_LIST', 'inlist', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="8%">
                <?php echo JHTML::_('grid.sort',   'Per User', 'peruser', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th width="<?php echo FSSJ3Helper::IsJ3() ? '130px' : '8%'; ?>">
				<?php echo JHTML::_('grid.sort',   'Order', 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?php if ($ordering) echo JHTML::_('grid.order',  $this->data ); ?>
			</th>
			<?php FSSAdminHelper::LA_Header($this, true); ?>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
    	$row = $this->data[$i];
    	$checked    = JHTML::_( 'grid.id', $i, $row->id );
    	$link = FSSRoute::_( 'index.php?option=com_fss&controller=field&task=edit&cid[]='. $row->id );

    	$published = FSS_GetPublishedText($row->published);
    ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
                <?php echo $row->id; ?>
			</td>
			<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->description; ?></a>
				<div class='small muted'><?php echo $row->alias; ?></div>
			</td>
			<td>
			    <?php echo $this->GetIdentLabel($row->ident); ?>
			</td>
			<td>
			    <?php echo $this->GetTypeLabel($row->type, $row); ?>
			</td>
			<?php if ($row->ident == 0): ?>
			<td>
			    <?php echo $row->grouping; ?>
			</td>
			<td align='center'>
				<?php if ($row->allprods) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::_('index.php?option=com_fss&tmpl=component&controller=field&view=field&task=prods&field_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<td align='center'>
				<?php if ($row->alldepts) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = FSSRoute::_('index.php?option=com_fss&tmpl=component&controller=field&view=field&task=depts&field_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<td align='center'>
				<?php 
				if ($row->type == "text" || $row->type == "combo" || $row->type == "area") 
				{ 
					echo FSS_GetYesNoText($row->basicsearch); 
				} else { 
					echo "<img src='" . JURI::base() . "/components/com_fss/assets/na.png'>";
				} 
				?>
			</td>
			<td align='center'>
				<?php echo FSS_GetYesNoText($row->advancedsearch); ?>
			</td>
			<td align='center'>
				<?php 
				if ($row->type != "area") 
				{ 
					echo FSS_GetYesNoText($row->inlist); 
				} else { 
					echo "<img src='" . JURI::base() . "/components/com_fss/assets/na.png'>";
				} 
				?>
			</td>
			<td align='center'>
				<?php echo FSS_GetYesNoText($row->peruser); ?>
			</td>
			<?php else: ?>
			<td colspan="7" align="center">N/A</td>
			<?php endif; ?>
			<td align="center">
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					<?php echo $published; ?>
				</a>
			</td>
			<td class="order">
			<?php if ($ordering) : ?>
				<span><?php echo $this->pagination->orderUpIcon( $i ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n ); ?></span>
			<?php endif; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php if (!$ordering) {echo 'disabled="disabled"';} ?> class="text_area" style="text-align: center" />
			</td>
			<?php FSSAdminHelper::LA_Row($row, true); ?>
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

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="field" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

