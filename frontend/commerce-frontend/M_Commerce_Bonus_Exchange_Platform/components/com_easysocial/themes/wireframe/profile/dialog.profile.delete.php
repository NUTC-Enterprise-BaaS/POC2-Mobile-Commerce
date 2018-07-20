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
	<width>400</width>
	<height>150</height>
	<selectors type="json">
	{
		"{closeButton}"  : "[data-close-button]",
		"{deleteButton}" : "[data-delete-button]",
		"{form}"		 : "[data-profile-delete-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function()
		{
			this.parent.close();
		},
		"{deleteButton} click" : function()
		{
			this.form().submit();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_EASYSOCIAL_PROFILE_DIALOG_DELETE_TITLE'); ?></title>
	<content>
		<p>
			<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_DELETE_CONFIRMATION' ); ?>
		</p>

		<form name="deleteProfile" data-profile-delete-form method="post" action="<?php echo JRoute::_( 'index.php' );?>">
			<input type="hidden" name="controller" value="profile" />
			<input type="hidden" name="option" value="com_easysocial" />
			<input type="hidden" name="task" value="delete" />
			<input type="hidden" name="<?php echo FD::token();?>" value="1" />
		</form>
	</content>
	<buttons>
		<button data-close-button type="button" class="btn btn-es"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-delete-button type="button" class="btn btn-es-danger"><?php echo JText::_('COM_EASYSOCIAL_PLEASE_DELETE_ACCOUNT_BUTTON'); ?></button>
	</buttons>
</dialog>
