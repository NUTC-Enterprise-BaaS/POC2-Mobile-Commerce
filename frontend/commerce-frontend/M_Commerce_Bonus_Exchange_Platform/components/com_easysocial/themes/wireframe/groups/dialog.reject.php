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
		"{closeButton}"		: "[data-close-button]",
		"{rejectButton}"	: "[data-reject-button]",
		"{rejectForm}"		: "[data-reject-group-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function()
		{
			this.parent.close();
		},
		"{rejectButton} click" : function()
		{
			this.rejectForm().submit();
		}
	}
	</bindings>
	<title><?php echo JText::_( 'COM_EASYSOCIAL_GROUPS_DIALOG_REJECT_REQUEST_TITLE' ); ?></title>
	<content>
		<p><?php echo JText::sprintf( "COM_EASYSOCIAL_GROUPS_DIALOG_REJECT_REQUEST_CONTENT" , $user->getName() , $group->getName() );?></p>

		<form data-reject-group-form method="post" action="<?php echo JRoute::_( 'index.php' );?>">
			<input type="hidden" name="id" value="<?php echo $group->id;?>" />
			<input type="hidden" name="userId" value="<?php echo $user->id;?>" />
			<input type="hidden" name="controller" value="groups" />
			<input type="hidden" name="task" value="reject" />
			<?php echo $this->html( 'form.token' ); ?>
		</form>
	</content>
	<buttons>
		<button data-close-button type="button" class="btn btn-es btn-sm"><?php echo JText::_( 'COM_EASYSOCIAL_CLOSE_BUTTON' ); ?></button>
		<button data-reject-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_( 'COM_EASYSOCIAL_REJECT_REQUEST_BUTTON' ); ?></button>
	</buttons>
</dialog>
