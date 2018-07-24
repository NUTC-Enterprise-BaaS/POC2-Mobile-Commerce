<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=tests' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = (strpos($this->lists['order'], "ordering") !== FALSE); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo FSS_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.submit();this.form.getElementById('prod_id').value='0';this.form.getElementById('ispublished').value='-1';"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
// ##NOT_TEST_START##
				if (array_key_exists("sections",$this->lists)) echo $this->lists['sections'];
// ##NOT_TEST_END##
				if (array_key_exists("published",$this->lists)) echo $this->lists['published'];
				?>
			</td>
		</tr>
	</table>

	<table class="adminlist table table-striped">
	<thead>

		<tr>
			<th width="5">#</th>
			<th width="20" class="title">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
			<th  class="title" width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Name', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<!-- If not got a type selected -->
			
			<!-- If a type has been selected, then be more specific -->
			<?php if ($this->ident): ?>
<!--// ##NOT_TEST_START## -->
				<th  class="title" width="8%" nowrap="nowrap">
					<?php echo $this->comment_objs[$this->ident]->handler->email_article_type; ?>
				</th>
<!--// ##NOT_TEST_END## -->
			<?php else: ?>
<!--// ##NOT_TEST_START## -->
				<th  class="title" width="8%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'Section', 'ident', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
<!--// ##NOT_TEST_END## -->
				<th  class="title" width="8%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort',   'Article/Product', 'itemid', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			<?php endif; ?>
			
			<th class="title">
				<?php echo JText::_("BODY"); ?>
			</th>
			<th  class="title" width="8%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Added', 'added', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'MOD_STATUS', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
	</thead>
	<?php

	$k = 0;
	for ($i=0, $n=count( $this->data ); $i < $n; $i++)
	{
		$row = $this->data[$i];
		$checked    = JHTML::_( 'grid.id', $i, $row->id );
		$link = FSSRoute::_( 'index.php?option=com_fss&controller=test&task=edit&cid[]='. $row->id );

		$published = FSS_GetModerationText($row->published);

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<?php echo $row->name; ?>
			</td>
<!--// ##NOT_TEST_START## -->
			<?php if (!$this->ident): ?>
				<td>
					<?php echo $this->comment_objs[$row->ident]->handler->descriptions; ?>
				</td>
			<?php endif; ?>
<!--// ##NOT_TEST_END## -->
			<td>
				<?php echo $this->comment_objs[$row->ident]->handler->GetItemTitle($row->itemid); ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo FSSAdminHelper::HTMLDisplay($row->body); ?></a>
			</td>
			<td>
				<?php echo FSS_Helper::Date($row->added,FSS_DATETIME_MID); ?>
			</td>
			<td align="center">
				<a href="#" class="modchage" id="comment_<?php echo $row->id;?>" current='<?php echo $row->published; ?>'>
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
<input type="hidden" name="controller" value="test" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<script>
jQuery(document).ready(function () {
	jQuery('.modchage').click( function () {
		var id = jQuery(this).attr('id').split('_')[1];
		var current = jQuery(this).attr('current');
		if (current == 1)
		{
			fss_remove_comment(id);		
		} else {
			fss_approve_comment(id);
		}
	});
});


function fss_remove_comment(commentid) {
	var obj = jQuery('#comment_' + commentid);
	obj.attr('current',2);
	var img = jQuery('#comment_' + commentid + ' img');
	var src = img.attr('src');
	
	var curimg = src.split("/").pop();
	src = src.replace(curimg, "declined.png");
	img.attr('src',src);
	
	var url = "<?php echo FSSRoute::_('index.php?option=com_fss&view=tests&task=removecomment&commentid=XXCIDXX',false); ?>";
	url = url.replace("XXCIDXX",commentid);
	jQuery.get(url);
}
function fss_approve_comment(commentid) {
	var obj = jQuery('#comment_' + commentid);
	obj.attr('current',1);
	var img = jQuery('#comment_' + commentid + ' img');
	var src = img.attr('src');
	
	var curimg = src.split("/").pop();
	src = src.replace(curimg, "accepted.png");
	img.attr('src',src);
	
	var url = "<?php echo FSSRoute::_('index.php?option=com_fss&view=tests&task=approvecomment&commentid=XXCIDXX',false); ?>";
	url = url.replace("XXCIDXX",commentid);
	jQuery.get(url);
}

</script>