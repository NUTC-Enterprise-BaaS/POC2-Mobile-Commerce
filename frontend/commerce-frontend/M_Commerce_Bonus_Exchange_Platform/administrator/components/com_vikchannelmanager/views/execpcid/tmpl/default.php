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

defined('_JEXEC') or die('Restricted access');

?>

<div class="vcm-pcid-header">
	<h3><?php echo JText::_('VCMPCIDRESPONSETITLE'); ?></h3>
</div>

<div class="vcm-pcid-body">

	<div class="vcm-pcid-body-block" id="vcm-pcid-body-left">
		<pre><?php foreach( $this->creditCardResponse as $key => $val ) {
				echo ucwords(str_replace("_", " ", $key)).": ".$val."\n";
		} ?></pre>
	</div>

	<div class="vcm-pcid-body-block off" id="vcm-pcid-body-right">
		<pre><?php echo htmlentities(urldecode($this->order['paymentlog'])); ?></pre>
	</div>

</div>

<script>

jQuery(document).ready(function(){

	jQuery('.vcm-pcid-body-block').hover(function(){
		if( jQuery(this).hasClass('off') ) {
			jQuery('.vcm-pcid-body-block').addClass('off');
			jQuery(this).removeClass('off');
		}
	}, function(){
		// do nothing on exit
	});

});

</script>