<?php

/**
* @copyright	Copyright (C) 2013 Function90.
* @license		GNU/GPL, see LICENSE.php
* @contact		dev.function90+ contact@gmail.com
* @author		Function90
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<li>
		<label class="form-label" for="f90validationcode" id="pw2msg"><?php echo JText::_('PLG_SYSTEM_PREEMAILVALIDATION_VALIDATE_EMAIL');?><span class="required-sign">&nbsp;*</span></label>

		<div class="form-field">
				<input type="button" 
						class="btn" id="f90sendvalidationcode" 
						value="<?php echo JText::_('PLG_SYSTEM_PREEMAILVALIDATION_SEND_CODE');?>"></input>
				<div>&nbsp;</div>
			
				<input class="input text validate-checkValidationCode" type="text" id="f90validationcode" style="width:50%">
		    	<div>&nbsp;</div>
		    
		</div>
	</li>
<?php 