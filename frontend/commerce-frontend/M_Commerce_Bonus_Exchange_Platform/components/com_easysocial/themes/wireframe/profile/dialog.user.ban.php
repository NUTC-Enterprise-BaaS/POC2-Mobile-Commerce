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
	<height>200</height>
	<selectors type="json">
	{
		"{closeButton}"  : "[data-close-button]",
		"{banButton}" : "[data-ban-button]",
		"{periodInput}" : "[data-ban-period]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_EASYSOCIAL_PROFILE_ADMINTOOL_DIALOG_BAN_USER_TITLE'); ?></title>
	<content>
		<form>
			<p>
				<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_ADMINTOOL_BAN_CONFIRMATION' ); ?> <br /><br />
				<input type="text" value="0" name="period" class="input-sm input-short text-center" style="width:50px !important;" data-ban-period /> <?php echo JText::_('COM_EASYSOCIAL_PROFILE_ADMINTOOL_BAN_MINUTES');?>
			</p>
		</form>
	</content>
	<buttons>
		<button data-close-button type="button" class="btn btn-sm btn-es"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-ban-button type="button" class="btn btn-sm btn-es-danger"><?php echo JText::_('COM_EASYSOCIAL_PROFILE_ADMINTOOL_BAN_BUTTON'); ?></button>
	</buttons>
</dialog>
