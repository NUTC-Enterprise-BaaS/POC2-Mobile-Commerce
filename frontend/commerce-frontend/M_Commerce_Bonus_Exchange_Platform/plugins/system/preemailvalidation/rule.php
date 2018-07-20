<?php

/**
* @copyright	Copyright (C) 2013 Function90.
* @license		GNU/GPL, see LICENSE.php
* @contact		dev.function90+ contact@gmail.com
* @author		Function90
*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla formrule library
jimport('joomla.form.formrule');
 
/**
 * Form Rule class for the Joomla Framework.
 */
class JFormRuleCheckValidationCode extends JFormRule
{
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		$email 	= $input->get('email1', '');
		$code 	= $input->get('f90validationcode', '');
		
		$session = JFactory::getSession();
        $value = $session->get('F90PEV_VALIDATION_CODE', false);
		if($value == false  || !isset($value['email']) || $value['email'] != $email
			|| !isset($value['code']) || $value['code'] != $code){
			$app = JFactory::getApplication();
			//$app->enqueueMessage()
        	//echo json_encode(array('error' => true, 'html' => JText::_('PLG_SYSTEM_PREEMAILVALIDATION_INVALID_CODE')));
        	return false;
		}
		else{
			return  true;
			echo json_encode(array('error' => false, 'html' => ''));
		}
	}
}