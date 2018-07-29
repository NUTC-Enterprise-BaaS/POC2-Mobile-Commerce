<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="alert">
	<h4 style='line-height: 1.6em;'>If there is any other places within the Freestyle Support Portal component you would like to be able to place help text messages, please let us know the details and we
	will get it added to the next update of the component. <a href='http://freestyle-joomla.com/help/support-tickets/open-support-ticket'>Contact us here</a>.</h4>
</div>

<script language="javascript" type="text/javascript">
<!--
Joomla.submitbutton = function(pressbutton) {
		if (pressbutton == "autosort") {
			if (!confirm("This will sort your FAQs alphabetically. It cannot be undone!"))
				return;
		}
		
        submitform(pressbutton);
}
//-->
</script>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=helptexts' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = (strpos($this->lists['order'], "ordering") !== FALSE); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo FSS_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.getElementById('faq_cat_id').value='0';this.form.getElementById('ispublished').value='-1';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['groups'];
				echo $this->lists['published'];
				?>
			</td>
		</tr>
	</table>

    <table class="adminlist table table-striped">
    <thead>
        <tr>
            <th>
                <?php echo JHTML::_('grid.sort',   'Identifier', 'identifier', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th>
				<?php echo JHTML::_('grid.sort',   'Group', 'group', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   'Description', 'description', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   'Text', 'message', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th>
				<?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
    	$link = JRoute::_( 'index.php?option=com_fss&controller=helptext&task=edit&identifier='. $row->identifier );

		$published = FSS_GetPublishedText($row->published);
    	
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->identifier; ?></a>
			</td>
			<td>
			    <?php echo $row->group; ?>
			</td>
			<td>
			    <?php echo $row->description; ?>
			</td>
			<td>
			    <?php echo substr(strip_tags($row->message), 0, 200); ?>
			</td>
			<td align="center">
			<?php 
				$task = $row->published ? 'unpublish' : 'publish';
				$link = "index.php?option=com_fss&controller=helptext&task=" . $task . "&identifier=" . $row->identifier;
			?>
				<a href="<?php echo $link; ?>" class="jgrid btn btn-micro">
					<?php echo $published; ?>
				</a>
			</td>
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
<input type="hidden" name="controller" value="helptext" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

