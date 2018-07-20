<?php 

class EmailService{
	
	public static function sendPaymentEmail($company, $paymentDetails){
	
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$billingInformation = self::getBillingInformation($company);
		
		$templ = self::getEmailTemplate("Order Email");
		if(empty($templ))
			return false;
		
		$content = self::prepareEmail($paymentDetails, $company, $templ->email_content, $applicationSettings->company_name, $billingInformation, $applicationSettings->vat);
		$content = self::updateCompanyDetails($content);
		
		$subject = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_subject);
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendPaymentDetailsEmail($company, $paymentDetails){

		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$billingInformation = self::getBillingInformation($company);
	
		$templ = self::getEmailTemplate("Payment Details Email");
		if(empty($templ))
			return false;
		
		$content = self::prepareEmail($paymentDetails, $company, $templ->email_content, $applicationSettings->company_name, $billingInformation, $applicationSettings->vat);
		$content = str_replace(EMAIL_PAYMENT_DETAILS, $paymentDetails->details->details, $content);
		$content = self::updateCompanyDetails($content);
	
		$subject = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_subject);
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		
		$result = self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml, $templ->send_to_admin);
		
		
		return $result;
	}
	
	public static function sendNewCompanyNotificationEmailToAdmin($company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$templ = self::getEmailTemplate("New Company Notification Email");
		if(empty($templ))
			return false;
		
		$content = self::prepareNotificationEmail($company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;

		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendNewCompanyNotificationEmailToOwner($company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Listing Creation Notification");
		if(empty($templ))
			return false;
		
		$content = self::prepareNotificationEmail($company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendNewOfferNotification($offer){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Offer Creation Notification");
		if(empty($templ))
			return false;
		
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$offerLink = '<a title="'.$offer->subject.'" href="'.JBusinessUtil::getOfferLink($offer->id, $offer->alias).'" >'.$offer->subject.'</a>';
		$content = str_replace(EMAIL_OFFER_NAME, $offerLink, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;

		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml,  $templ->send_to_admin);
	}
	
	public static function sendApproveOfferNotification($offer, $companyEmail){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Offer Approval Notification");
		if(empty($templ))
			return false;
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$offerLink = '<a title="'.$offer->subject.'" href="'.JBusinessUtil::getOfferLink($offer->id, $offer->alias).'" >'.$offer->subject.'</a>';
		$content = str_replace(EMAIL_OFFER_NAME, $offerLink, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $companyEmail;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendNewEventNotification($event){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Event Creation Notification");
		if(empty($templ))
			return false;
		
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$eventLink = '<a title="'.$event->name.'" href="'.JBusinessUtil::getEventLink($event->id, $event->alias).'" >'.$event->name.'</a>';
		$content = str_replace(EMAIL_EVENT_NAME, $eventLink, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;

		return self::sendEmail($from, $fromName, $from, $toEmail, null, null, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendApproveEventNotification($event, $companyEmail){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Event Approval Notification");
		if(empty($templ))
			return false;
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$eventLink = '<a title="'.$event->name.'" href="'.JBusinessUtil::getEventLink($event->id, $event->alias).'" >'.$event->name.'</a>';
		$content = str_replace(EMAIL_EVENT_NAME, $eventLink, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $companyEmail;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function prepareNotificationEmail($company, $emailTemplate){

		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$emailContent = $emailTemplate;
		
		$emailContent = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $emailContent);
		$companyLink = '<a href="'.JBusinessUtil::getCompanyLink($company).'">'.$company->name.'</a>';
		$emailContent = str_replace(EMAIL_BUSINESS_NAME, $companyLink, $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_ADDRESS, JBusinessUtil::getAddressText($company), $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_WEBSITE, $company->website, $emailContent);
		
		$emailContent = self::updateCompanyDetails($emailContent);
		
		$logoContent = '<img height="111" src="'.(JURI::root().PICTURES_PATH.'/no_image.jpg').'"/>';
		if(!empty($company->logoLocation)){
			$company->logoLocation = str_replace(" ","%20",$company->logoLocation);
			$logoContent = '<img height="111" src="'.(JURI::root().PICTURES_PATH.$company->logoLocation).'"/>';
		}
		
		$logoContent='<a href="'.JBusinessUtil::getCompanyLink($company).'">'.$logoContent.'</a>';
		
		$emailContent = str_replace(EMAIL_BUSINESS_LOGO, $logoContent, $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_CATEGORY, $company->selectedCategories[0]->name, $emailContent);
		$emailContent = str_replace(EMAIL_BUSINESS_CONTACT_PERSON, $company->contact->contact_name, $emailContent);
		
		return $emailContent;
	}
	
	public static function sendApprovalEmail($company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate("Approve Email");
		if(empty($templ))
			return false;
		
		$content = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templ->email_content);
		$companyLink = '<a href="'.JBusinessUtil::getCompanyLink($company).'">'.$company->name.'</a>';
		$content = str_replace(EMAIL_BUSINESS_NAME, $companyLink, $content);
		$content = self::updateCompanyDetails($content);
		
		$subject = $templ->email_subject;
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
	
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	
	public static function getBillingInformation($company){
		$user = JFactory::getUser($company->userId);
		$inf = $user->username."<br/>";
		$inf = $inf.$company->name."<br/>";
		$inf = $inf.JBusinessUtil::getAddressText($company);
	
		return $inf;
	}
	
	public static function getEmailTemplate($template){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$db =JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_emails WHERE email_type = "'.$template.'" and status=1 ';
		$db->setQuery($query);
		$templ= $db->loadObject();
		
		if($applicationSettings->enable_multilingual){
			$lang = JFactory::getLanguage()->getTag();
			$translation = JBusinessDirectoryTranslations::getObjectTranslation(EMAIL_TRANSLATION, $templ->email_id, $lang);
			
			if(!empty($translation)){
				if(!empty($translation->name)){
					$templ->email_subject = $translation->name;
				}
				if(!empty($translation->content)){
					$templ->email_content = $translation->content;
				}
			}
		}
		
		return $templ;
	}
	
	public static function prepareEmail($data, $company, $templEmail, $siteName=null, $billingInformation=null, $vat=null){
		$user = JFactory::getUser($company->userId);
		$customerName= $user->username;
		$templEmail = str_replace(EMAIL_CUSTOMER_NAME,$customerName, $templEmail);
	
		$siteAddress = JURI::root();
		$templEmail = str_replace(EMAIL_SITE_ADDRESS, $siteAddress,	$templEmail);
		$templEmail = str_replace(EMAIL_COMPANY_NAME, $siteName, $templEmail);
		$templEmail = str_replace(EMAIL_ORDER_ID,$data->order_id, $templEmail);
	
		$paymentMethod=$data->details->processor_type;
		$templEmail = str_replace(EMAIL_PAYMENT_METHOD, $paymentMethod, $templEmail);
		
		if(!empty($data->paid_at))
			$templEmail = str_replace(EMAIL_ORDER_DATE, JBusinessUtil::getDateGeneralFormat($data->paid_at), $templEmail);
		else
			$templEmail = str_replace(EMAIL_ORDER_DATE, JBusinessUtil::getDateGeneralFormat($data->details->payment_date), $templEmail);
		
		$totalAmount = $data->amount_paid;
		if(empty($data->amount_paid))
			$totalAmount = $data->amount;
				
		$templEmail = str_replace(EMAIL_TOTAL_PRICE, JBusinessUtil::getPriceFormat($totalAmount), $templEmail);
		
		$templEmail = str_replace(EMAIL_TAX_AMOUNT, JBusinessUtil::getPriceFormat($data->package->price * $vat/100), $templEmail);
		$templEmail = str_replace(EMAIL_SUBTOTAL_PRICE, JBusinessUtil::getPriceFormat($data->package->price), $templEmail);
		
		$templEmail = str_replace(EMAIL_SERVICE_NAME, $data->service, $templEmail);
		$templEmail = str_replace(EMAIL_UNIT_PRICE, JBusinessUtil::getPriceFormat($data->package->price), $templEmail);
		$templEmail = str_replace(EMAIL_BILLING_INFORMATION, $billingInformation, $templEmail);
	
		return "<div style='width: 600px;'>".$templEmail.'</div>';
	}
	
	public static function prepareEmailFromArray($data, $company, $templEmail){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$fistName= isset($data["firstName"])?$data["firstName"]:"";
		$lastName=isset($data["lastName"])?$data["lastName"]:"";
		$description = isset($data["description"])?$data["description"]:"";
		$email = isset($data["email"])?$data["email"]:"";
		$abuseTxt = isset($data["description"])?$data["description"]:"";
		$expDays = isset($data["nrDays"])?$data["nrDays"]:"";
		$reviewName = isset($data["reviewName"])?$data["reviewName"]:"";
		$category = isset($data["category"])?$data["category"]:"";
		
		$templEmail = str_replace(EMAIL_CATEGORY, $category, $templEmail);
		$templEmail = str_replace(EMAIL_FIRST_NAME, $fistName, $templEmail);
		$templEmail = str_replace(EMAIL_LAST_NAME, $lastName, $templEmail);
		
		$companyLink = JBusinessUtil::getCompanyLink($company);
		$companyLink = '<a href="'.$companyLink.'">'.$company->name.'</a>';
		$templEmail = str_replace(EMAIL_BUSINESS_NAME, $companyLink, $templEmail);
		
		$templEmail = str_replace(EMAIL_REVIEW_LINK, $companyLink, $templEmail);
		
		$templEmail = str_replace(EMAIL_CONTACT_EMAIL, $email, $templEmail);
		$templEmail = str_replace(EMAIL_CONTACT_CONTENT, $description, $templEmail);
		$templEmail = str_replace(EMAIL_ABUSE_DESCRIPTION,$description, $templEmail);
		$templEmail = str_replace(EMAIL_EXPIRATION_DAYS, $expDays, $templEmail);
		$templEmail = str_replace(EMAIL_REVIEW_NAME, $reviewName, $templEmail);
		
		$templEmail = str_replace(EMAIL_COMPANY_NAME, $applicationSettings->company_name, $templEmail);
		$templEmail = str_replace(EMAIL_CLAIMED_COMPANY_NAME, $company->name, $templEmail);
		
		return $templEmail;
	}
	
	public static function sendEmail($from, $fromName, $replyTo, $toEmail, $cc, $bcc, $subject, $content, $isHtml, $sendToAdmin=false){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		jimport('joomla.mail.mail');
	
		try{
			$mail = JFactory::getMailer();
			$mail->setSender(array($from, $fromName));
			if(isset($replyTo))
				$mail->addReplyTo($replyTo);
			$mail->addRecipient($toEmail);
			if(isset($cc))
				$mail->addCC($cc);
			if(isset($bcc))
				$mail->addBCC($bcc);
			if($sendToAdmin)
				$mail->addBCC($applicationSettings->company_email);
	
			$mail->setSubject($subject);
			$mail->setBody($content);
			$mail->IsHTML($isHtml);
	
			$ret = $mail->send();
			
			$log = Logger::getInstance();
			$log->LogDebug("E-mail with subject ".$subject." sent from ".$from." to ".$toEmail." ".serialize($bcc)." result:".$ret);
		}catch(Exception $ex) {
				$log = Logger::getInstance();
				$log->LogDebug("E-mail with subject ".$subject." sent from ".$from." to ".$toEmail." failed");
				return 0;
		}

		return $ret;
	}
	
	public static function updateCompanyDetails($emailContent){
		$logo = self::getCompanyLogoCode();
		$socialNetworks = self::getCompanySocialNetworkCode();
		$emailContent = str_replace(EMAIL_COMPANY_LOGO, $logo, $emailContent);
		$emailContent = str_replace(EMAIL_COMPANY_SOCIAL_NETWORKS, $socialNetworks, $emailContent);
		$link='<a style="color:#555;text-decoration:none" target="_blank" href="'.JURI::root(false).'">'.JURI::root(false).'</a>';
		$emailContent = str_replace(EMAIL_DIRECTORY_WEBSITE, $link, $emailContent);
		
		return $emailContent;
	}
	
	public static function getCompanyLogoCode(){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$code ="";
		if(!empty($applicationSettings->logo)){
			$applicationSettings->logo = str_replace(" ","%20",$applicationSettings->logo);
			$logoLocaiton = JURI::root().PICTURES_PATH.$applicationSettings->logo;
			$link = JURI::root(false);
			$code='<a target="_blank" title"'.$applicationSettings->company_name.'" href="'.$link.'"><img height="55" alt="'.$applicationSettings->company_name.'" src="'.$logoLocaiton.'" ></a>';
		}
		
		return $code;
	}
	
	public static function getCompanySocialNetworkCode(){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$code="";
		if(!empty($applicationSettings->twitter)){
			$code.='<a href="'.$applicationSettings->twitter.'" target="_blank"><img title="Twitter" src="'.JURI::root().PICTURES_PATH.'/twitter.png'.'" alt="Twitter" height="32" border="0" width="32"></a>';
		}
			
		if(!empty($applicationSettings->facebook)){
			$code.='<a href="'.$applicationSettings->facebook.'" target="_blank"><img title="Facebook" src="'.JURI::root().PICTURES_PATH.'/facebook.png'.'" alt="Facebook" height="32" border="0" width="32"></a>';
		}
		
		if(!empty($applicationSettings->linkedin)){
			$code.='<a href="'.$applicationSettings->linkedin.'" target="_blank"><img title="LinkedIN" src="'.JURI::root().PICTURES_PATH.'/linkedin.png'.'" alt="LinkedIN" height="32" border="0" width="32"></a>';
		}
		
		if(!empty($applicationSettings->googlep)){
			$code.='<a href="'.$applicationSettings->googlep.'" target="_blank"><img title="Google+" src="'.JURI::root().PICTURES_PATH.'/googlep.png'.'" alt="Google+" height="32" border="0" width="32"></a>';
		}
		
		if(!empty($applicationSettings->youtube)){
			$code.='<a href="'.$applicationSettings->youtube.'" target="_blank"><img title="Youtube" src="'.JURI::root().PICTURES_PATH.'/youtube.png'.'" alt="Youtube" height="32" border="0" width="32"></a>';
		}
		
		return $code;
	}
	
	/**
	 * Send 
	 * @param unknown_type $company
	 * @param unknown_type $data
	 */
	static function sendContactCompanyEmail($company, $data){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$templ = self::getEmailTemplate("Contact Email");

		if(empty($templ))
			return false;
	
		$content =self::prepareEmailFromArray($data, $company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
	
		$subject=sprintf($templ->email_subject, $applicationSettings->company_name);

		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$sender = $data["firstName"]." ".$data["lastName"];
		$fromName = $sender;
		$isHtml = true;
		if(!empty($data["copy-me"])){
			$bcc = array($data["email"]);
		}
		
		return self::sendEmail($from, $fromName, $data["email"], $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	/**
	 * Send claim request email to site administrator
	 * 
	 * @param $company
	 */
	public static function sendClaimEmail($company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		if(!isset($company->email))
			return;
	
		$content = JText::_("LNG_CLAIM_EMAIL_TXT");
		$content = str_replace(EMAIL_COMPANY_NAME, $company->name, $content);
		
		$subject = JText::_("LNG_CLAIM_EMAIL_SUBJECT");
		$subject = str_replace(EMAIL_COMPANY_NAME, $company->name, $subject);
	
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendExpirationEmail($company, $nrDays){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$templ = self::getEmailTemplate("Expiration Notification Email" );
		if( $templ ==null )
			return null;
	
		if(!isset($company->email))
			return;
	
		$data = array("nrDays"=>$nrDays);
		$content = self::prepareEmailFromArray($data, $company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
	
		$subject=$templ->email_subject;
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendReviewEmail($company, $data){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$templ = self::getEmailTemplate("Review Email");
		if( $templ ==null )
			return null;
	
		if(!isset($company->email))
			return;
	
		$content = self::prepareEmailFromArray($data, $company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject=sprintf($templ->email_subject, $applicationSettings->company_name);
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendReviewResponseEmail($company, $data){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$templ = self::getEmailTemplate("Review Response Email");
		if( $templ ==null )
			return null;
	
		if(!isset($company->email))
			return;
	
		$content = self::prepareEmailFromArray($data, $company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject=sprintf($templ->email_subject, $applicationSettings->company_name);
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}	
	
	public static function sendReportAbuseEmail($data, $review, $company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$templ = self::getEmailTemplate("Report Abuse Email");
		if( $templ ==null )
			return null;
	
		if(isset($review)){
			$data["reviewName"]= $review[0]->subject;
		}
		
		$content = self::prepareEmailFromArray($data, $company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject= $templ->email_subject;
		$toEmail = $applicationSettings->company_email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendRequestQuoteEmail($data, $company){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
			
		$templ = self::getEmailTemplate("Request Quote Email");
		if( $templ ==null )
			return null;
	
		$content = self::prepareEmailFromArray($data, $company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
		
		$subject=sprintf($templ->email_subject, $applicationSettings->company_name);
		$toEmail = $company->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
	
	public static function sendClaimResponseEmail($company, $claimDetails, $template){
		$applicationSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	
		$templ = self::getEmailTemplate($template);
		if( $templ ==null )
			return null;
		
		$data=array();
		$content = self::prepareEmailFromArray($data, $company, $templ->email_content);
		$content = self::updateCompanyDetails($content);
	
		$subject = $templ->email_subject;
		$toEmail = $claimDetails->email;
		$from = $applicationSettings->company_email;
		$fromName = $applicationSettings->company_name;
		$isHtml = true;
		$bcc = array();
		
		return self::sendEmail($from, $fromName, $from, $toEmail, null, $bcc, $subject, $content, $isHtml, $templ->send_to_admin);
	}
}

?>