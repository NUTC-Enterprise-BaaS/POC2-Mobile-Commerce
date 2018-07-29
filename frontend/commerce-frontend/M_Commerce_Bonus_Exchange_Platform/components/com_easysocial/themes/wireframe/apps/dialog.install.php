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
	<width>450</width>
	<height>350</height>
	<selectors type="json">
	{
		"{installButton}"	: "[data-confirm-install-button]",
		"{agreeCheckbox}"	: "[data-apps-install-agree]",
		"{cancelButton}"	: "[data-cancel-button]",
		"{termsError}"		: "[data-terms-error]"
	}
	</selectors>
	<title><?php echo JText::_( 'COM_EASYSOCIAL_APPS_CONFIRM_INSTALL_DIALOG_TITLE' ); ?></title>
	<content>
		<div data-apps-install-form>

			<textarea style="width:100%;height: 200px"><?php echo JText::_( $this->config->get( 'apps.tnc.message' ) ); ?></textarea>

			<?php if( $this->config->get( 'apps.tnc.required' ) ){ ?>
			<div class="checkbox">
				<label for="agreeAppInstall" class="small mt-20">
					<input id="agreeAppInstall" type="checkbox" data-apps-install-agree /> <?php echo JText::_( 'COM_EASYSOCIAL_APPS_TNC_AGREE' ); ?>
				</label>
			</div>


			<div class="text-error" data-terms-error style="display:none;"><?php echo JText::_( 'COM_EASYSOCIAL_APPS_PLEASE_ACCEPT_TERMS' );?></div>
			<?php } ?>
		</div>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_( 'COM_EASYSOCIAL_CANCEL_BUTTON' ); ?></button>
		<button data-confirm-install-button type="button" class="btn btn-es-primary btn-sm"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALL_APP_BUTTON' ); ?></button>
	</buttons>
</dialog>
