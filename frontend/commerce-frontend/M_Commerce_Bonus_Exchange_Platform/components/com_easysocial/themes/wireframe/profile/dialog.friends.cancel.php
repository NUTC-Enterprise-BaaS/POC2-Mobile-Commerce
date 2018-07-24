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
	<width>350</width>
	<height>150</height>
	<selectors type="json">
	{
		"{confirmButton}"  : "[data-proceed-button]",
		"{cancelButton}"  : "[data-cancel-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{cancelButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_DIALOG_CANCEL_REQUEST' ); ?></title>
	<content>
		<p><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_DIALOG_CANCEL_CONFIRMATION' ); ?></p>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-proceed-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_('COM_EASYSOCIAL_YES_CANCEL_MY_REQUEST_BUTTON'); ?></button>
	</buttons>
</dialog>