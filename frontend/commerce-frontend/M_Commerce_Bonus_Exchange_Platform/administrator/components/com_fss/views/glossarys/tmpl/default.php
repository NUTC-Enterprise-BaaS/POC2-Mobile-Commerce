<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=glossarys' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = (strpos($this->lists['order'], "ordering") !== FALSE); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo FSS_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.getElementById('ispublished').value='-1';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['published'];
				?>
				<?php FSSAdminHelper::LA_Filter(); ?>
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
            <th width="30%">
                <?php echo JHTML::_('grid.sort',   'Word', 'word', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th  class="title" width="65%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Description', 'description', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<?php FSSAdminHelper::LA_Header($this); ?>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::_( 'index.php?option=com_fss&controller=glossary&task=edit&cid[]='. $row->id );

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
			    <a href="<?php echo $link; ?>"><?php echo $row->word; ?></a>
			</td>
			<td>
			    <?php echo FSSAdminHelper::HTMLDisplay($row->description); ?>
			</td>
			<td align="center">
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					<?php echo $published; ?>
				</a>
			</td>
			<?php FSSAdminHelper::LA_Row($row); ?>
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
<input type="hidden" name="controller" value="glossary" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

