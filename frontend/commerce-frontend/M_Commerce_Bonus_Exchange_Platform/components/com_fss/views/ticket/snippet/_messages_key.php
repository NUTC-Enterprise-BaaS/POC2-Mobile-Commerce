
<?php FSS_Helper::HelpText("support_user_view_mes_key"); ?>

<?php if (!FSS_Settings::get('user_hide_key')): ?>
	<p>
		<?php echo JText::_('MESSAGE_KEY'); ?>
		<span class="label label-warning"><?php echo JText::_('MESSAGE_KEY_USER'); ?></span>
		<span class="label label-success"><?php echo JText::_('MESSAGE_KEY_HANDLER'); ?></span>
		<span class="label label-info"><?php echo JText::_('MESSAGE_KEY_OTHER'); ?></span>
	</p>
<?php endif; ?>
