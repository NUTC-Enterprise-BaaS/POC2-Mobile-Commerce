<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$date_format = VikChannelManager::getClearDateFormat(true);

?>

<button type="button" class="btn vcm-diagnostic-btn" onClick="launchInputOutputDiagnostic();"><?php echo JText::_("VCMSTARTIODIAGNOSTICBTN"); ?></button>

<div class="vcm-diagnostic-goodresponse" style="display: none;">
	<div class="head-title"><?php echo JText::_('VCMIODIAGNOSTICGOODTITLE'); ?></div>
	<div class="body-content"></div>
</div>

<div class="vcm-diagnostic-badresponse" style="display: none">
	<div class="head-title"><?php echo JText::_('VCMIODIAGNOSTICBADTITLE'); ?></div>
	<div class="body-content"></div>
</div>

<script>

	function launchInputOutputDiagnostic() {

		enableDiagnosticButton(false);

		jQuery.noConflict();

		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_vikchannelmanager&task=input_output_diagnostic&tmpl=component",
			data: { }
		}).done(function(res) { 

			if( res.substr(0, 9) == 'e4j.error' ) {
				jQuery('.vcm-diagnostic-badresponse .body-content').html( res.substr(10) );
				jQuery('.vcm-diagnostic-badresponse').show();
			} else {
				var obj = jQuery.parseJSON(res);
				jQuery('.vcm-diagnostic-goodresponse .body-content').html( obj );
				jQuery('.vcm-diagnostic-goodresponse').show();
			}

			enableDiagnosticButton(true);

		}).fail(function(res) { 
			alert(res);

			enableDiagnosticButton(true);
		});

	}

	function enableDiagnosticButton(status) {
		jQuery('.vcm-diagnostic-btn').prop('disabled', (status ? false : true));

		if( !status ) {
			jQuery('.vcm-diagnostic-goodresponse, .vcm-diagnostic-badresponse').hide();
		}
	}

</script>
