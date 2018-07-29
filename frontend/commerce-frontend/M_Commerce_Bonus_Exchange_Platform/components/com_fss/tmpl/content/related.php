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
<?php echo FSS_Helper::PageTitlePopup(JText::_("Select related article")); ?>

<form method="post" action="<?php echo FSSRoute::x('&'); ?>" id="fssForm" name="fssForm">
	
	<div class="pull-right">
		<?php echo $this->filter_html['published']; ?>
		<?php if (FSS_Permission::auth("core.edit", "com_fss.kb")): ?>
			<?php echo $this->filter_html['userid']; ?>
		<?php endif; ?>

		<?php foreach ($this->fields[$this->pick_field]->filters as $filter): ?>
			<?php echo $this->filter_html[$filter->field]; ?>
		<?php endforeach; ?>
	</div>
	
	<div class="input-append">
		<input type="text" name="search" class="input-medium" size="12" id="fss_search" value="<?php echo $this->filter_values['search']; ?>" placeholder="<?php echo JText::_('SEARCH'); ?>">
		<button class="btn btn-primary"><?php echo JText::_('GO'); ?></button>
		<button class="btn btn-default" id="fss_content_reset"><?php echo JText::_('RESET'); ?></button>
	</div>
	
	<input name="order" type="hidden" id="fss_order" value="<?php echo $this->filter_values['order']; ?>">
	<input name="order_dir" type="hidden" id="fss_order_dir" value="<?php echo $this->filter_values['order_dir']; ?>">
	
	<input name="limit_start" type="hidden" type="hidden" id="limitstart" value="<?php echo $this->filter_values['limitstart']; ?>">
	
	<?php if (count($this->pick_data) > 0): ?>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<td width="30">ID</td>
				<?php foreach($field->rel_lookup_display as $fieldname => $finfo): ?>
				<td><a href='#' class="filter_field" order="<?php echo $fieldname; ?>"><?php echo $finfo['desc']; ?></a></td>	
				<?php endforeach; ?>			
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($this->pick_data as $item) : ?>
				<tr>
					<td><?php echo $item[$field->rel_lookup_id]; ?></td>
					<?php foreach($field->rel_lookup_display as $fieldname => $finfo): ?>
					<td>
						<?php if ($field->rel_lookup_pick_field == $fieldname) : ?>
							<a href="#" id="pick_<?php echo $item[$field->rel_lookup_id]; ?>" class='pick_link'>
						<?php endif; ?>
						<?php echo $item[$finfo['alias']]; ?>
						<?php if ($field->rel_lookup_pick_field == $fieldname) : ?>
							</a>
						<?php endif; ?>
					</td>	
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->_pagination->getListFooter(); ?>
	<?php else: ?>
		<div class="alert alert-info">No articles found matching your search criteria</div>
	<?php endif; ?>
</form>

<script>
jQuery(document).ready(function () {
	jQuery('.pick_link').click(function (ev) {
		ev.preventDefault();
		var id = jQuery(this).attr('id').split('_')[1];
		title = jQuery(this).text();
		parent.AddRelatedItem('<?php echo $this->pick_field; ?>', id, title);
	});
});
</script>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content.js'; ?>
</script>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>