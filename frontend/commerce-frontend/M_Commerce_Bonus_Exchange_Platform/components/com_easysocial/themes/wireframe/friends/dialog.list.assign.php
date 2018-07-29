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
	<height>250</height>
	<selectors type="json">
	{
		"{insertButton}"  : "[data-insert-button]",
		"{cancelButton}"  : "[data-cancel-button]",
		"{suggest}"		  : "[data-friends-suggest]"
	}
	</selectors>
	<bindings type="javascript">
	{
		init: function() {

			// Implement friend suggest.
			this.suggest()
				.addController("EasySocial.Controller.Friends.Suggest",
				{
					exclusion: <?php echo $users; ?>
				});
		},

		"{cancelButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_EASYSOCIAL_FRIENDS_LIST_ADD_DIALOG_TITLE' ); ?></title>
	<content>
		<form method="post" action="<?php echo JRoute::_( 'index.php' );?>" data-list-assignFriends>

			<div data-assignFriends-notice></div>

			<p class="fd-small mb-20"><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_ADD_DIALOG_CONTENT' ); ?></p>

			<div class="controls textboxlist disabled" data-friends-suggest>
				<input type="text" class="input-xlarge textboxlist-textField" name="members" data-textboxlist-textField disabled />
			</div>

			<input type="hidden" name="option" value="com_easysocial" />
			<input type="hidden" name="controller" value="friendslist" />
			<input type="hidden" name="task" value="delete" />
			<input type="hidden" name="id" value="<?php echo $list->id;?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es"><?php echo JText::_( 'COM_EASYSOCIAL_CANCEL_BUTTON' ); ?></button>
		<button data-insert-button type="button" class="btn btn-es-primary"><?php echo JText::_( 'COM_EASYSOCIAL_ADD_BUTTON' ); ?></button>
	</buttons>
</dialog>
