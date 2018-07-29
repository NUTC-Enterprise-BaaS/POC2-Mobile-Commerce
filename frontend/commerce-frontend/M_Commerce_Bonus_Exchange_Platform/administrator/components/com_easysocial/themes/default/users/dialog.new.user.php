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
	<width>550</width>
	<height>150</height>
	<selectors type="json">
	{
		"{continueButton}"	: "[data-continue-button]",
		"{cancelButton}"	: "[data-cancel-button]",
		"{profile}"			: "[data-input-profile]"
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
	<title><?php echo JText::_( 'COM_EASYSOCIAL_USERS_NEW_USER_DIALOG_TITLE' ); ?></title>
	<content>
		<form name="approveUser" method="post" action="index.php" data-users-assign-form>
			<p>
				<?php echo JText::_( 'COM_EASYSOCIAL_USERS_NEW_USER_CONFIRMATION' );?>
			</p>

			<div class="form-group">
				<label for="profileId" class="col-md-3"><?php echo JText::_( 'COM_EASYSOCIAL_USERS_SELECT_USER_GROUP' );?></label>

				<div class="col-md-9">
					<?php echo $this->html( 'form.profiles' , 'profileId'  , 'profileId' , null , array( 'data-input-profile' ) ); ?>
				</div>
			</div>
		</form>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-continue-button type="button" class="btn btn-es-primary btn-sm"><?php echo JText::_( 'COM_EASYSOCIAL_CONTINUE_BUTTON' ); ?></button>
	</buttons>
</dialog>
