<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 %COMPANY_NAME%. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* %PACKAGE% is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// We want to import our app library
Foundry::import( 'admin:/includes/apps/apps' );

/**
 * Some application for EasySocial. Take note that all classes must be derived from the `SocialAppItem` class
 *
 * Remember to rename the Textbook to your own element.
 * @since	1.0
 * @author	Author Name <author@email.com>
 */
class SocialUserAppPushnotify extends SocialAppItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Triggers the preparation of stream.
	 *
	 * If you need to manipulate the stream object, you may do so in this trigger.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream object.
	 * @param	bool				Determines if we should respect the privacy
	 */
	public function onPrepareStream( SocialStreamItem &$item, $includePrivacy = true )
	{
		// You should be testing for app context
		if( $item->context !== 'appcontext' )
		{
			return;
		}
	}

	/**
	 * Triggers the preparation of activity logs which appears in the user's activity log.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialStreamItem	The stream object.
	 * @param	bool				Determines if we should respect the privacy
	 */
	public function onPrepareActivityLog( SocialStreamItem &$item, $includePrivacy = true )
	{
	}

	/**
	 * Triggers after a like is saved.
	 *
	 * This trigger is useful when you want to manipulate the likes process.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableLikes	The likes object.
	 *
	 * @return	none
	 */
	public function onAfterLikeSave( &$likes )
	{
	}

	/**
	 * Triggered when a comment save occurs.
	 *
	 * This trigger is useful when you want to manipulate comments.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableComments	The comment object
	 * @return
	 */
	public function onAfterCommentSave( &$comment )
	{
	}

	/**
	 * Renders the notification item that is loaded for the user.
	 *
	 * This trigger is useful when you want to manipulate the notification item that appears
	 * in the notification drop down.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function onNotificationBeforeCreate( $item_dt)
	{
		//checking table is exists in database then only execute patch.
		$db = FD::db();		
		$stbl = $db->getTableColumns('#__acpush_users');
		$reg_ids=array();
		$reg_ids_iOS = array();
		$targetUsers=array();
		$test_result = array();
		$test_message = JText::_('COM_ACMANAGER_FORM_MNG_TITLE_TEST_ADMIN_NFOUND');
		$test_ios_message = '';
		//load ac manager language
		$lang = JFactory::getLanguage();
		$lang->load('com_acmanager', JPATH_ADMINISTRATOR, '', true);			

		// Select records from the user social_gcm_users table".
		/*$query->select($db->quoteName(array('device_id', 'sender_id', 'server_key', 'user_id')));
		$query->from($db->quoteName('#__social_gcm_users'));
		$query->where($db->quoteName('send_notify')." = 1");*/
		
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select records from the user acpush_users table".
		$query->select($db->quoteName(array('is_prod','params','type')));
		$query->from($db->quoteName('#__acpush_config'));
		$query->where("(type LIKE '%Android%' OR type LIKE '%iOS%')");
		$query->where("active = 1 ");

		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$sconfig = $db->loadObjectList('type');
		
		$is_prod_and = 0;
		$is_prod_ios = 0;
		$server_k = '';
		$p_number = 0;
		//for sandbox default mode
		$passphrase = 'demo';

		if(!empty($sconfig['Android']))
		{
			//set required variable
			$a_sconfig = $sconfig['Android'];

			$is_prod_and = $a_sconfig->is_prod;
			$a_sconfig = json_decode($a_sconfig->params);

			$server_k = $a_sconfig->server_key;
			$p_number = $a_sconfig->project_number;
		}
		
		if(!empty($sconfig['iOS']))
		{
	
			$i_sconfig = $sconfig['iOS'];
			$is_prod_ios = $i_sconfig->is_prod;
			$ip_sconfig = json_decode($i_sconfig->params);
			$passphrase = $ip_sconfig->passphrase;
		}

		if(empty($sconfig))
		{
			//configuration in com_acmanager not set properly
			return false;
		}
		// Create a new query object.
		$query = $db->getQuery(true);
		// Select records from the user acpush_users table".
		$query->select($db->quoteName(array('device_id','user_id','type')));
		$query->from($db->quoteName('#__acpush_users'));
		$query->where($db->quoteName('active')." = 1");
		
		$db->setQuery($query);
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$urows = $db->loadObjectList();

		$rule = $item_dt->rule;

		//Generate element from rule.
		$segments = explode('.', $rule);
		$element = array_shift($segments);

		$participants = $item_dt->participant;
		$emailOptions = $item_dt->email_options;
		$systemOptions = $item_dt->sys_options;
		
		$msg_data = $this->createMessageData( $element,$emailOptions, $systemOptions, $participants );

		//$targetUsers=$msg_data['tusers'];
		//hari change for like issue
		if(!empty($msg_data['tusers']))
		{
			$targetUsers = $msg_data['tusers'];
		}
		else
		{
			$targetUsers = null;
			return false;
		}

		$count = rand(1,100);
		$user = FD::user();
		
		//follow es config to send message
		$es_params = FD::config();

		$actor_name = $user->name;		

		//ES config dependent username
		if($es_params->get('users')->displayName == 'username')
		{
			$actor_name = $user->username;
		}
		
		$tit=JText::_($msg_data['title']);
	 
		$tit=str_replace('{actor}',$actor_name,$tit);

		$msg_data['mssge']=strip_tags($msg_data['mssge']);
		
		if(empty($msg_data['mssge']))
		{
			$msg_data['mssge'] = ' ';
		}

		// new code for ios and android
		//these variable will be set only if reg_ids are available for android and iOS.
		$type_iOS='';
		$type_droid='';

		foreach($urows as $notfn)
		{

			if(in_array($notfn->user_id,$targetUsers))
			{

					if(($notfn->type == 'ios' || $notfn->type == 'iOS') && isset($sconfig['iOS']) ){


						$reg_ids_iOS[]=$notfn->device_id;
						//$server_k_ios=$notfn->server_key;
						$type_iOS='iOS';
					}
					if(($notfn->type=='android' || $notfn->type=='Android') && isset($sconfig['Android'])){
						$reg_ids[]=$notfn->device_id;
						//$server_k=$notfn->server_key;
						$type_droid='Android';
					}
			}
		}

		if( (!empty($reg_ids))  || (!empty($reg_ids_iOS)) )
		{
			//send notification for iOS device.
			//increment counter
			$registatoin_ids = $reg_ids;
			// Message to be sent
			$message = $tit;
			
			//Ios registration ids
			$reg_ios=sizeof($reg_ids_iOS);

			if($type_iOS == 'iOS' && !empty($reg_ids_iOS))
			{

				//this for loop is for sending notification one to one as ios doesnt send it as array.
				for($i=0;$i<$reg_ios;$i++)
				{
					$devicetok=$reg_ids_iOS[$i];
					
					// Put your device token here (without spaces):
					$deviceToken = $devicetok;

					// Put your private key's passphrase here:
					//$passphrase = 'demo';

					// Put your alert message here:
					$message = $message;

					$ctx = stream_context_create();

					stream_context_set_option($ctx, 'ssl', 'local_cert', 'ios_certificates/ck.pem');

					if(isset($item_dt->test))
					{
						stream_context_set_option($ctx, 'ssl', 'local_cert', '../ios_certificates/ck.pem');
					}


					stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

					//$is_prod = $sconfig
					$fp = 0;
					if($sconfig['iOS']->is_prod)
					{

						$fp = stream_socket_client(
						'ssl://gateway.push.apple.com:2195', $err,
						$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

					}
					else
					{
						// Open a connection to the APNS server
					$fp = stream_socket_client(
						'ssl://gateway.sandbox.push.apple.com:2195', $err,
						$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);	

					}


					if (!$fp)
					{

						if(isset($item_dt->test))
						{

						$test_message = JText::_('COM_ACMANAGER_FORM_MNG_IOS_NOTIFY_FAIL'). $errstr; 

						}
						else
						{										
							exit("Failed to connect: $err $errstr" . PHP_EOL);
						}
					}
					else
					{

					//echo 'Connected to APNS' . PHP_EOL;

					// Create the payload body
					$body['aps'] = array(
						'alert' => $message,
						'sound' => 'default',
						"badge" => $count,
						"url" => $msg_data['ul'],
					);
					// Encode the payload as JSON
					$payload = json_encode($body);

					// Build the binary notification
					$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

					// Send it to the server
					$result = fwrite($fp, $msg, strlen($msg));

					if(isset($item_dt->test) && !empty($result))
					{
						$test_ios_message = JText::_('COM_ACMANAGER_FORM_MNG_TEST_IOS_NOTIFY_SENT');
					}

					}					
					// Close the connection to the server
					fclose($fp);

				}
			}
			//send notification for android device.
			if($type_droid=='Android' && !empty($reg_ids)){
				//Google cloud messaging GCM-API url
				$url = 'https://gcm-http.googleapis.com/gcm/send';
				//Setting headers for gcm service.
				$headers = array(
				'Authorization'=>'key='.$server_k,
				'Content-Type'=> 'application/json'
				);

				//Setting fields for gcm service.
				//fields contents what data to be sent.
				$fields = array(
				'registration_ids' => $registatoin_ids,
				'data' => array( "title" => $message,"message" => $msg_data['mssge'] ,"notId"=>$count,"url" => $msg_data['ul'], "body"=>$msg_data['mssge']),
				); 

				//Making call to GCM  API using POST.
				jimport('joomla.client.http');
				//Using JHttp for API call
				$http      = new JHttp;
				$options   = new JRegistry;
	
				//$transport = new JHttpTransportStream($options);
				$http = new JHttp($options);
	
				$gcmres = $http->post($url,json_encode($fields),$headers);


				if(isset($item_dt->test) && !empty($gcmres->body))
				{
				$test_message = $test_ios_message.'  '.JText::_('COM_ACMANAGER_FORM_MNG_TEST_ANDROID_NOTIFY_SENT');
					
				}

			}


			if(isset($item_dt->test))
			{
				return $test_message;
			}
	
			return true;
		}
		else
		{
			return $test_message;
		}
		
	}	
 
 public function createMessageData($element,$emailOptions, $systemOptions, $participants)
 {	
		$data = array();
		//switch case for getting url,avatar of actor,data for particular view
		
		$emailOptions = (is_object($emailOptions))?(array)$emailOptions:$emailOptions;
		$systemOptions = (is_object($systemOptions))?(array)$systemOptions:$systemOptions;
		
		$data['title'] = $emailOptions['title'];
		$data['title'] = JText::_($data['title']);

		//$data['view'] = $element;
		//hari - change for like issue events
		if(!empty($participants))
		{
			$data['tusers'] = $this->createParticipents($participants);
		}
		else
		{
			$data['tusers'] = null;
		}

		switch($element){
			case 'conversations':	$data['ul'] = $emailOptions['conversationLink'];
									$data['mssge'] = $emailOptions['message'];
									$data['authorAvatar'] = $emailOptions['authorAvatar'];	
									$data['actor'] = $emailOptions['authorName'];	
								
			break;
			case 'friends':			$data['ul'] = $emailOptions['params']['requesterLink'];
									$data['authorAvatar'] = $emailOptions['params']['requesterAvatar'];
									$data['authorlink'] = $emailOptions['params']['requesterLink'];
									$data['mssge']= ' ';
									$data['actor']=$emailOptions['actor'];
			break;
			case 'profile':
									
									if($rulename=='followed')
									{
										$data['ul']=$emailOptions['targetLink'];
										$data['mssge']=' ';	
										$data['authorAvatar']=$emailOptions['actorAvatar'];
									}
									else
									{
										$data['ul'] = $emailOptions['params']['permalink'];
										$data['authorAvatar'] = $emailOptions['params']['actorAvatar'];
										$data['mssge'] = $emailOptions['params']['content'];
										$data['actor'] = $emailOptions['params']['actor'];
										$data['target_user'] = $systemOptions['target_id'];
									}
			break;						
			case 'likes':			
									$data['authorAvatar']=$emailOptions['actorAvatar'];
									$data['mssge']=' ';
									$data['ul']=$emailOptions['permalink'];
									$data['target_link']=(isset($emailOptions['targetLink']))?$emailOptions['targetLink']:' ';
									//$data['target_name']=$systemOptions['target'];
									$data['target_name']=(isset($systemOptions['target']))?$systemOptions['target']:' ';
			break;
			case 'comments':
									$data['authorAvatar']=$emailOptions['actorAvatar'];
									$data['mssge']=$emailOptions['comment'];
									$data['ul']=$emailOptions['permalink'];
									$data['target_link']=(isset($emailOptions['targetLink']))?$emailOptions['targetLink']:' ';
//$data['target_name']=(isset($systemOptions['target']))?$systemOptions['target']:$emailOptions['target'];
									$data['target_name']=(isset($systemOptions['target']))?$systemOptions['target']:' ';
			break;
			case 'events':
//print_r($emailOptions);die("in notify");	
	                            $data['authorAvatar']= (isset($emailOptions['posterAvatar']))?$emailOptions['posterAvatar']:$emailOptions['actorAvatar'];
                                    $data['mssge']=(isset($emailOptions['message']))?$emailOptions['message']:$emailOptions['params']->content;
                                    $data['ul']=(isset($emailOptions['eventLink']))?$emailOptions['eventLink']:$emailOptions['params']->permalink;
                                    $data['actor']=$emailOptions['actor'];
                                    //this line is for getting event name in notification.
                                    $data['title']=str_replace('{event}',$emailOptions['event'],$data['title']);
                                    
            break;
            case 'groups':

                                    $data['authorAvatar']=(isset($emailOptions['posterAvatar']))?$emailOptions['posterAvatar']:$emailOptions['params']->userAvatar;
                                     $data['mssge']=(isset($emailOptions['message']))?$emailOptions['message']:$emailOptions['params']->content;
                                    $data['ul']= (isset($emailOptions['groupLink']))?$emailOptions['groupLink']:$emailOptions['params']->permalink;
				    //$data['ul'] = (isset($data['ul']))?$emailOptions['params']->groupLink:' ';
				     if(!isset($data['ul']))
                                   {
                                    	$data['ul'] = $emailOptions['params']->groupLink;
                                   } 	
					
                                    /*$data['ul']= (isset($emailOptions['groupLink']))?$emailOptions['groupLink']:$emailOptions['params']->groupLink;*/
                                    //this line is for getting group name in notification.
				    $group_ttl = (isset($emailOptions['group']))?$emailOptions['group']:$emailOptions['params']->group;	
                                    $data['title']=str_replace('{group}',$group_ttl,$data['title']);
            break;
            case 'stream':
									$data['authorAvatar']=$emailOptions['actorAvatar'];
                                    $data['mssge']=$emailOptions['message'];
                                    $data['ul']=$emailOptions['permalink'];                                 
            break;
		}

		return $data;
 }
 
 //create participents unique abjects
 public function createParticipents($pUsers)
 {	
	$userObj = is_object($pUsers[count($pUsers)-1]);
	if($userObj)
	{
		$myarr = array();
		foreach($pUsers as $ky=>$row)
		{
			$myarr[]= $row->id;
		}		
		return $myarr;
	}
	else
	{		
		return $pUsers;
	}
 }
}
