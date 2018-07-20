<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo  FSS_Helper::PageSubTitle($this->descs); ?>

<form method="post" action="<?php echo FSSRoute::x('&'); ?>" id="fssForm" name="fssForm">
	
	<?php if (FSS_Permission::auth("core.create", $this->getAsset())): ?>
	<div class="pull-right">
			<a href="#" id='fss_content_new' class="btn btn-success"><i class="icon-new icon-white"></i> <?php echo JText::_('NEW');?></a> 
	</div>
	<?php endif; ?>
	<div class="input-append" style="margin-bottom: 6px;">
		<input type="text" class="input-medium" name="search" size="12" id="fss_search" value="<?php echo $this->filter_values['search']; ?>" placeholder="<?php echo JText::_('SEARCH'); ?>">
		<button class="btn btn-primary"><?php echo JText::_('GO'); ?></button>
		<button class="btn btn-default" id="fss_content_reset"><?php echo JText::_('RESET'); ?></button>
	</div>
		
	<?php echo $this->filter_html['published']; ?>
	
	<?php if ($this->has_author && FSS_Permission::auth("core.edit.state", $this->getAsset())): ?>
			<?php echo $this->filter_html['userid']; ?>
	<?php endif; ?>
	<?php foreach ($this->filters as $filter): ?>
			<?php echo $this->filter_html[$filter->field]; ?>
	<?php endforeach; ?>

	<input name="order" type="hidden" id="fss_order" value="<?php echo $this->filter_values['order']; ?>">
	<input name="order_dir" type="hidden" id="fss_order_dir" value="<?php echo $this->filter_values['order_dir']; ?>">
	<input name="limit_start" type="hidden" id="limitstart" value="<?php echo $this->filter_values['limitstart']; ?>">


	<?php if (count($this->data) > 0): ?>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<?php foreach ($this->list as $list) :
					$field = $this->fields[$list]; ?>
					<th><a href='#' class="filter_field" order="<?php echo $field->field; ?>"><?php echo $field->desc; ?></a></th>
				<?php endforeach; ?>
				<?php if ($this->list_added): ?><th><a href='#' class="filter_field" order="added"><?php echo JText::_('ADDED'); ?></a></th><?php endif; ?>
				<?php if ($this->list_published): ?><th><a href='#' class="filter_field" order="published"><?php echo JText::_('PUB'); ?></a></th><?php endif; ?>
				<?php if (FSS_Permission::auth("core.edit", $this->getAsset())): ?>
					<?php if ($this->list_user): ?><th><a href='#' class="filter_field" order="u.name"><?php echo JText::_('AUTHOR'); ?></a></th><?php endif; ?>
				<?php endif; ?>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($this->data as &$item) :?>
				<tr>
					<?php foreach ($this->list as $list):
						$field = $this->fields[$list]; ?>
						<td>
							<?php if ($field->link): ?>
								<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_content&type=' . $this->id . '&what=edit&id=' . $item['id']); ?>'>
							<?php endif; ?>
							<?php if ($field->type == "lookup"): ?>
								<?php echo $this->GetLookupText($field, $item[$field->field]); ?>
							<?php elseif ($field->type == "checkbox"): ?>
								<?php echo FSS_Helper::GetYesNoText($item[$field->field]); ?>
							<?php else: ?>
								<?php echo $item[$field->field]; ?>
							<?php endif; ?>
							<?php if ($field->link): ?></a><?php endif; ?>
						</td>
					<?php endforeach; ?>
					<?php if ($this->list_added): ?>
						<td nowrap><?php echo FSS_Helper::Date($item['added'],FSS_DATE_SHORT); ?></td>
					<?php endif; ?>

					<?php if ($this->list_published): ?>
						<td nowrap>
							<a href="#" id="publish_<?php echo $item['id']; ?>" class="fss_publish_button" state="<?php echo $item['published']; ?>">
								<?php echo FSS_Helper::GetPublishedText($item['published']); ?>
							</a>
						</td>
					<?php endif; ?>

					<?php if (FSS_Permission::auth("core.edit", $this->getAsset())): ?>
					<?php if ($this->list_user): ?>
						<td><?php echo $item['name']; ?></td>
					<?php endif; ?>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>	
		
		
	</table>
	<?php echo $this->_pagination->getListFooter(); ?>
	<?php else: ?>
		<div class="alert alert-info"><?php echo JText::sprintf("YOU_HAVE_NO",$this->descs); ?></div>
	<?php endif; ?>
</form>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content.js'; ?>
</script>