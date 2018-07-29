
<?php if (!FSS_Settings::get('user_hide_print')): ?>
	<?php 
	$prints = Support_Print::getPrintList(true, $this->ticket); 
	if (count($prints) > 0): ?>
		<div class="pull-right btn-group" style="z-index: 10">
			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-print"></i> <?php echo JText::_("Print"); ?> 
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=view&print=1&tmpl=component&ticketid=' . $this->ticket->id); ?>' target='_new' onclick="return doPrint(this);">
						<?php echo JText::_("Ticket"); ?> 
					</a>
				</li>
				<?php foreach ($prints as $name => $title): ?>
					<li>
						<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=view&print=1&tmpl=component&ticketid=' . $this->ticket->id. "&print=" . $name); ?>' target='_new' onclick="return doPrint(this);">
							<?php echo JText::_($title); ?>
						</a>
					</li>	
				<?php endforeach; ?>
			</ul>
		</div>
	<?php else: ?>	
		<div class="pull-right">
			<a class="btn btn-default" href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=view&print=1&tmpl=component&ticketid=' . $this->ticket->id); ?>' target='_new' onclick="return doPrint(this);">
				<i class="icon-print"></i>
				<?php echo JText::_("USER_PRINT"); ?> 
			</a>
		</div>
	<?php endif; ?>
<?php endif; ?>