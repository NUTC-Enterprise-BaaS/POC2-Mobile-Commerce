<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<dialog>
	<width>500</width>
	<height>300</height>
	<selectors type="json">
	{
		"{doneButton}"		: "[data-done-button]",
		"{cancelButton}" 	: "[data-cancel-button]",
		"{form}"			: "[data-assign-points-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{cancelButton} click": function()
		{
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_ASSIGN_POINTS_DIALOG_TITLE' ); ?></title>
	<content>
		<form name="assignPoints" method="post" action="index.php" data-assign-points-form>
			<p><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_ASSIGN_POINTS_MESSAGE' ); ?></p>

			<div class="form-group">
				<label class="col-md-3" for="total"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS' ); ?></label>
				<div class="col-md-9">
					<input type="text" name="points" class="form-control input-mini center" value="" /> <?php echo JText::_('COM_EASYSOCIAL_POINTS'); ?>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3" for="total"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_CUSTOM_MESSAGE' ); ?></label>
				<div class="col-md-9">
					<textarea name="message" class="form-control" placeholder="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_ASSIGN_POINTS_CUSTOM_MESSAGE' );?>" style="height: 100px;"></textarea>
				</div>
			</div>

			<input type="hidden" name="option" value="com_easysocial" />
			<input type="hidden" name="controller" value="users" />
			<input type="hidden" name="task" value="insertPoints" />
			<?php echo $this->html( 'form.token' ); ?>

			<?php foreach( $uids as $uid ){ ?>
			<input type="hidden" name="uid[]" value="<?php echo $uid;?>" />
			<?php } ?>

		</form>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-done-button type="button" class="btn btn-es-primary btn-sm"><?php echo JText::_('COM_EASYSOCIAL_ASSIGN_BUTTON'); ?></button>
	</buttons>
</dialog>
