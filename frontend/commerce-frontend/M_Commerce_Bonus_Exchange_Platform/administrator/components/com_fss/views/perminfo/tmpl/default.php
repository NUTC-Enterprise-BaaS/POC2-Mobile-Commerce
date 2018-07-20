<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo FSS_Helper::PageStylePopup(1); ?>

<?php echo FSS_Helper::PageTitlePopup('SUPPORT_ADMIN',"LIST_HANDLERS"); ?>

<form id="mainform" name="mainform" action="<?php echo FSSRoute::_("index.php?option=com_fss&view=perminfo&tmpl=component"); ?>" method="post" class="form-inline form-condensed">
	<?php echo $this->mode_select; ?>
	<?php echo $this->products_select; ?>
	<?php echo $this->departments_select; ?>
	<?php echo $this->categories_select; ?>
	<?php echo $this->status_select; ?>
</form>

<br />

<table class="table table-bordered table-striped table-striped">
	<thead>
		<tr>
			<th><?php echo JText::_('USERNAME'); ?></th>
			<th><?php echo JText::_('NAME'); ?></th>
			<th><?php echo JText::_('EMAIL'); ?></th>
			<th><?php echo JText::_('ALL_OPEN'); ?></th>
			<th>
				<div class="pull-right">
					<div class="btn-group">
						<a href="#" class="fssTip btn btn-default btn-micro dropdown-toggle" data-toggle="dropdown" title="<?php echo JText::_('CHANGE_STATUS'); ?>"><i class="icon-arrow-down"></i></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
							<?php foreach ($this->statuss as $status): ?>
								<li><a tabindex="-1" href="#" onclick='jQuery("#status").val("<?php echo $status->id; ?>");document.mainform.submit( );return false'><?php echo $status->title; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php echo $this->status_obj->title; ?>
			</th>
			<th><?php echo JText::_('STATUS'); ?></th>
		</tr>
	</thead>
	
	<tbody>
		
		<?php foreach ($this->handler_details as $handler): ?>
	
			<tr>
				<td><?php echo $handler->username; ?></td>
				<td><?php echo $handler->name; ?></td>
				<td><?php echo $handler->email; ?></td>
				<td>
					<?php echo $handler->open_tickets; ?> 
				</td>
				<td>
					<?php echo $handler->status_count; ?>
				</td>
				<td>
						
					<?php if (!empty($handler->settings) && !empty($handler->settings->out_of_office) && $handler->settings->out_of_office): ?>
						<span class="label label-important"><?php echo JText::_('UNAVAILABLE'); ?></span>
					<?php else: ?>
						<span class="label label-success"><?php echo JText::_('AVAILABLE'); ?></span>
					<?php endif; ?>
					
				</td>
			</tr>
		
		<?php endforeach; ?>
	
	</tbody>
</table>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>
