<?php FSS_Helper::IncludeChosen(); ?>

<div class="fss_main">

<form name="adminForm" id="adminForm" class="form-condensed form-horizontal" action="<?php echo JRoute::_('index.php?option=com_fss&view=plugins&layout=configure&type=' . $this->plugin->type . "&name=" . $this->plugin->name); ?>" method="post">
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
		<div style="height: 128px">&nbsp;</div>
	  </div>
	<?php endforeach; ?>
	</div>

</form>

</div>