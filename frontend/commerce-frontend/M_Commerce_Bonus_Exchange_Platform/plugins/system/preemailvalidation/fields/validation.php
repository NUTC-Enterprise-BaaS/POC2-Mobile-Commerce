<?php

/**
* @copyright	Copyright (C) 2013 Function90.
* @license		GNU/GPL, see LICENSE.php
* @contact		dev.function90+ contact@gmail.com
* @author		Function90
*/

defined( '_JEXEC' ) or die( 'Restricted access' );


JFormHelper::loadFieldClass('list');

/** 
 * Pre Email Validation Field
 */
class F90PEVFormFieldValidation extends JFormField
{
	protected function getInput()
	{
		$version = new JVersion();
		if($version->RELEASE == '2.5'){
			return '<button type="button" class="btn" id="f90sendvalidationcode" >'.
					JText::_('PLG_SYSTEM_PREEMAILVALIDATION_SEND_CODE').'</button></dd>
					<dt>&nbsp;</dt><dd class="err-f90-sendvalidation-code"></dd>
					<dt>&nbsp;</dt><dd>
						<input required="required" class="required validate-checkValidationCode" type="text" name="'.$this->name.'" id="f90validationcode">
					</dd>
					<dt>&nbsp;</dt><dd class="err-f90-code-validation"></dd>
					<dt></dt><dd>';
		}
		
		// else
		return '<button type="button" class="btn" id="f90sendvalidationcode" >'.JText::_('PLG_SYSTEM_PREEMAILVALIDATION_SEND_CODE').'</button>
				<div class="err-f90-sendvalidation-code">&nbsp;</div>
				<input required="required" class="required validate-checkValidationCode" type="text" name="'.$this->name.'" id="f90validationcode">
				<div class="err-f90-code-validation">&nbsp;</div>';
	}
}