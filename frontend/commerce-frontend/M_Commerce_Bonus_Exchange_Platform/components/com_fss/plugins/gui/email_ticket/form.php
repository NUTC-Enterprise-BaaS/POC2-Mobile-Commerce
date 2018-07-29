<form name="adminForm" id="adminForm" class="form-condensed form-horizontal" action="<?php echo JRoute::_('index.php?option=com_fss&view=plugin&type=gui&name=email_ticket&tmpl=component&ticketid=' . $this->ticket->id) ?>" method="post">

<?php
echo FSS_Helper::PageStylePopup(true);
echo FSS_Helper::PageTitlePopup("EMail Ticket", $this->ticket->title);
?>
<?php FSS_Helper::IncludeChosen(); ?>

	<input name="task" id="task" value="" type="hidden" />
	<ul class="nav nav-tabs">
	<?php $first = true; ?>
	<?php foreach ($this->xml->fields as $field): ?>
		<li <?php if ($first) { echo 'class="active"'; $first = false; }; ?>><a href="#<?php echo $field->attributes()->name; ?>" data-toggle="tab"><?php echo JText::_($field->attributes()->label); ?></a></li>
	<?php endforeach; ?>
	</ul>


	<?php $first = true; ?>
	<div class="tab-content">
	<?php foreach ($this->xml->fields as $field): ?>
	  <div class="tab-pane <?php if ($first) { echo 'active'; $first = false; }; ?>" id="<?php echo $field->attributes()->name; ?>">
		<?php $fieldsets = $this->form->getFieldsets($field->attributes()->name); ?>

		<?php foreach ($fieldsets as $fieldset): ?>
		<fieldset>
			<?php if ($fieldset->label): ?>
				<legend><?php echo JText::_($fieldset->label); ?></legend>
			<?php endif; ?>

			<?php $fields = $this->form->getFieldset($fieldset->name); ?>
			<?php foreach ($fields as $name => $field): ?>
			<div class="control-group">
				<label class="control-label"><?php echo $field->label; ?></label>
				<div class="controls">
				  <?php echo $field->input; ?>
				</div>
			</div>
			<?php endforeach; ?>

		</fieldset>
		<?php endforeach; ?>
	  </div>
	<?php endforeach; ?>
	</div>

</div>
</div>

<div class="modal-footer">
	<a class="btn btn-success" onclick="jQuery('#adminForm').submit();return false;"><?php echo JText::_('SEND'); ?></a>
	<a href='#' class="btn btn-default" onclick='parent.fss_modal_hide(); return false;'><?php echo JText::_('CANCEL'); ?></a>
</div>

</form>
