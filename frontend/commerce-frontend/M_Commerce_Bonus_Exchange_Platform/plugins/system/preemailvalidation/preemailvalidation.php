<?php

/**
* @copyright	Copyright (C) 2013 Function90.
* @license		GNU/GPL, see LICENSE.php
* @contact		dev.function90+ contact@gmail.com
* @author		Function90
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$version = new JVersion();
if($version->RELEASE == '2.5'){
	require_once dirname(__FILE__).'/rule25.php';
}
else{
	require_once dirname(__FILE__).'/rule.php';
}
class PlgSystemPreemailvalidation extends JPlugin
{
	protected $autoloadLanguage = true;
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	public function onContentPrepareForm($form, $data)
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		
		if($form->getName() != 'com_users.registration'){
			return true;
		}
			
		$xml = '
				<fieldset name="preemailvalidation">
					<field 
						type="f90pev.validation"
						name="f90validationcode"
						required="true"
						validate="checkValidationCode"
						label="'.JText::_('PLG_SYSTEM_PREEMAILVALIDATION_VALIDATE_EMAIL').'"
						description="'.JText::_('PLG_SYSTEM_PREEMAILVALIDATION_VALIDATE_EMAIL_DESC').'"
						addfieldpath="/plugins/system/preemailvalidation/fields/">					
					</field>
				</fieldset>
				';
		
		$form->setField(new SimpleXMLElement($xml));				
	}
	
	public function onBeforeRender()
    {
    	$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		
		JText::script('PLG_SYSTEM_PREEMAILVALIDATION_SENDING_CODE');
		JText::script('PLG_SYSTEM_PREEMAILVALIDATION_SEND_AGAIN');
		JText::script('PLG_SYSTEM_PREEMAILVALIDATION_ENTER_VALIDEMAIL');
			
		if('com_users' == $app->input->get('option', '') && 'registration' == $app->input->get('view', '') && '' == $app->input->get('task', '')){
			$doc = JFactory::getDocument();
			if($this->params->get('load_jquery', false)){
				$doc->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
			}
			$doc->addScript('plugins/'.$this->_type.'/'.$this->_name.'/validation.js');
			return true;
		}
		
		if($app->input->get('option') === 'com_community' && $app->input->get('view') === 'register' && $app->input->get('task', '') == ''){
			$doc = JFactory::getDocument();
			$doc->addScript('plugins/'.$this->_type.'/'.$this->_name.'/tmpl/jomsocial.js');
			return true;
		}
    }
    
	public function onAfterRender()
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			return true;
		}
		
		$input = $app->input;
        
        // if it is Jomsocial registration page then add its html        
        if($input->get('option') === 'com_community' && $input->get('view') === 'register' && $input->get('task', '') == ''){
		
			ob_start();
			require_once dirname(__FILE__).'/tmpl/jomsocial.php';
			$contents = ob_get_contents();
			ob_end_clean();
			
			$body = JResponse::getBody();
			$body = str_ireplace('<li class="form-action', $contents.'<li class="form-action', $body);
			JResponse::setBody($body);
        }
		return true;
	}
	
 	public function onAfterRoute()
    {
    	$app = JFactory::getApplication();
        if($app->isAdmin()){
        	return true;
        }
        
        $input = $app->input;
        if($input->get('plg') === 'f90pev' && $input->get('task') === 'sendValidationCode'){
        	
	        // TODO : Validate email
	        $email 	= $input->getHtml('email', '');        
	        $code 	= rand(10000000, 99999999);
	
	        $session = JFactory::getSession();
	        $session->set('F90PEV_VALIDATION_CODE', array('email'=>$email, 'code'=>$code));
	        
	        $result = $this->_sendEmail($email, $code);
	        if($result == false ||  ($result instanceof Exception)){
	        	echo json_encode(array('error' => true, 'html' => JText::_('PLG_SYSTEM_PREEMAILVALIDATION_ERROR_IN_SENDING_CODE')));
	        }
	        else{
	        	echo json_encode(array('error' => false, 'html' => JText::_('PLG_SYSTEM_PREEMAILVALIDATION_CODE_SENT')));
	        }
	        
     	   exit();
		}
		
		if($input->get('plg') === 'f90pev' && $input->get('task') === 'checkValidationCode'){
			$email 	= $input->getHtml('email', '');
			$code 	= $input->get('code', '');
			$session = JFactory::getSession();
	        $value = $session->get('F90PEV_VALIDATION_CODE', false);
			if($value == false  || !isset($value['email']) || $value['email'] != $email
				|| !isset($value['code']) || $value['code'] != $code){
	        	echo json_encode(array('error' => true, 'html' => JText::_('PLG_SYSTEM_PREEMAILVALIDATION_INVALID_CODE')));
			}
			else{
				echo json_encode(array('error' => false, 'html' => ''));
			}
			
			exit();
		}		
    }
	
 	public function _sendEmail($email, $code)
    {
    	$config = JFactory::getConfig();
    	$data['fromname'] = $config->get('fromname');
    	$data['mailfrom'] = $config->get('mailfrom');
                
    	$user = JFactory::getUser();
    	$emailSubject = JText::_('PLG_SYSTEM_PREEMAILVALIDATION_EMAIL_SUBJECT');
    	$emailBodyAdmin = JText::sprintf('PLG_SYSTEM_PREEMAILVALIDATION_EMAIL_BODY', $email, $code);
    		
		return JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $email, $emailSubject, $emailBodyAdmin);
	}
}
