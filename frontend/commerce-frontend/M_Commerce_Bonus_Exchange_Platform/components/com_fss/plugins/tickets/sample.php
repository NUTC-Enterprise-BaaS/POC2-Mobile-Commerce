<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Handle all ticket events from the ticket plugins we have available
 * 
 * Included here is a full list of the calls that can be made to this plugin, inluding some example code.
 * 
 * Please do not use this file for your plugins, make a copy of the parts you require, and change the class name to
 * match your file name, ie SupportActions{filename}
 * 
 * You will need to enable your new plugin in the Plugins page (Components -> Freestyle Support -> Overview -> Plugins)
 */

class SupportActionsSample extends SupportActionsPlugin
{
	var $title = "Sample Plugin";
	var $description = "";

	/**
	 * The following events are called when changes are made to a support ticket
	 **/

	/**
	 * This is method Ticket_updatePriority
	 *
	 * Called when the tickets priority is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_pri_id'] Old priority id
	 * @param static $params['new_pri_id'] New priority id
	 *
	 */	
	/*function Ticket_updatePriority($ticket, $params)
	{
	}*/

	/**
	 * This is method Ticket_updateStatus
	 *
	 * Called when the tickets status is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_status_id'] Old status id
	 * @param static $params['new_status_id'] New status id
	 *
	 */	
	/*function Ticket_updateStatus($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_updateCategory
	 *
	 * Called when the tickets category is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_cat_id'] Old category id
	 * @param static $params['new_cat_id'] New category id
	 *
	 */	
	/*function Ticket_updateCategory($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_updateUser
	 *
	 * Called when the tickets user is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_user_id'] Old user id
	 * @param static $params['new_user_id'] New user id
	 *
	 */	
	/*function Ticket_updateUser($ticket, $params)
	{

	}*/



	/**
	 * This is method Ticket_updateProduct
	 *
	 * Called when the tickets product is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_prod_id'] Old user id
	 * @param static $params['new_prod_id'] New user id
	 *
	 */	
	/*function Ticket_updateProduct($ticket, $params)
	{

	}*/




	/**
	 * This is method Ticket_updateDepartment
	 *
	 * Called when the tickets product is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_department_id'] Old department id
	 * @param static $params['new_department_id'] New department id
	 *
	 */	
	/*/function Ticket_updateDepartment($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_updateUnregEMail
	 *
	 * Called when the tickets unregsiterd users email is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_email'] Old email
	 * @param static $params['new_email'] New email
	 *
	 */	
	/*function Ticket_updateProduct($ticket, $params)
	{

	}*/


	

	/**
	 * This is method Ticket_updateSubject
	 *
	 * Called when the tickets subject is updated
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['old_subject'] Old subject
	 * @param static $params['new_subject'] New subject
	 *
	 */	
	/*function Ticket_updateSubject($ticket, $params)
	{

	}*/

	

	/**
	 * This is method Ticket_updateLock
	 *
	 * Called when the tickets lock status is changed (when a handler accesses a ticket)
	 * 
	 * @param static $ticket Ticket object
	 *
	 */	
	/*function Ticket_updateLock($ticket)
	{

	}*/


	/**
	 * This is method Ticket_updateCustomField
	 *
	 * Called when the tickets custom field value
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['field_id'] ID of the custom field
	 * @param static $params['old'] Old value
	 * @param static $params['new'] New value
	 *
	 */	
	/*function Ticket_updateCustomField($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_addTag
	 *
	 * Called when the ticket has a tag added to it
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['tag'] Name of the tag
	 *
	 */	
	/*function Ticket_addTag($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_removeTag
	 *
	 * Called when the ticket has a tag removed from it
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['tag'] Name of the tag
	 *
	 */	
	/*function Ticket_removeTag($ticket, $params)
	{

	}*/



	/**
	 * This is method Ticket_addTime
	 *
	 * Called when the ticket has a time entry added to it. The time can be negative when removing time
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['time'] Time in minutes
	 * @param static $params['notes'] Notes
	 *
	 */	
	/*function Ticket_addTime($ticket, $params)
	{

	}*/



	/**
	 * This is method Ticket_deleteAttach
	 *
	 * Called when the ticket has an attachment removed
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['attach'] Attachment details
	 *
	 */	
	/*function Ticket_deleteAttach($ticket, $params)
	{

	}*/



	/**
	 * This is method Ticket_addMessage
	 *
	 * Called when the ticket has a reply added to it
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['user_id'] User ID
	 * @param static $params['type'] Type of message - see below
	 * @param static $params['subject'] subject of message
	 * @param static $params['body'] body
	 * @param static $params['message_id'] message id in database
	 *
	 * Message Types:
	 * 0 - User - TICKET_MESSAGE_USER
	 * 1 - Handler - TICKET_MESSAGE_ADMIN
	 * 2 - Private - TICKET_MESSAGE_PRIVATE
	 * 3 - Audit - TICKET_MESSAGE_AUDIT
	 * 4 - Draft - TICKET_MESSAGE_DRAFT
	 * 5 - Time Entry - TICKET_MESSAGE_TIME
	 * 6 - Opened By - TICKET_MESSAGE_OPENEDBY
	 */	
	/*function Ticket_addMessage($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_addFile
	 *
	 * Called when the ticket has a file attached to it
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['file'] Details of the attachment
	 */	
	/*function Ticket_addFile($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_updateMessage
	 *
	 * Called when the ticket has message edited
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['message_id'] Message id
	 * @param static $params['old_subject'] Old subject
	 * @param static $params['new_subject'] New subject
	 * @param static $params['old_body'] Old Body
	 * @param static $params['new_body'] New body
	 */	
	/*function Ticket_updateMessage($ticket, $params)
	{

	}*/


	/**
	 * This is method Ticket_assignHandler
	 *
	 * Called when the ticket has its handler changed
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['handler'] Handlers user id
	 * @param static $params['type'] Type of assign
	 * 
	 * Types
	 * 0 - Forwarded - TICKET_ASSIGN_FORWARD
	 * 1 - Took ownership on reply - TICKET_ASSIGN_TOOK_OWNER
	 * 2 - Set Unassigned - TICKET_ASSIGN_UNASSIGNED
	 * 3 - Assigned - TICKET_ASSIGN_ASSIGNED
	 */	
	/*function Ticket_updateMessage($ticket, $params)
	{

	}*/


	/**
	 * The following are called when a ticket is either opened or replied to
	 * 
	 * These are primarily used to trigger any notifications for the system
	 **/


	/**
	 * This is method Admin_Reply
	 *
	 * Called when a handler has replied to a ticket
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['subject'] Subject
	 * @param static $params['user_message'] Message to send to user
	 * @param static $params['status'] New status id
	 * @param static $params['files'] Any files that have been attached
	 */	
	/*function Admin_Reply($ticket, $params)
	{

	}*/


	/**
	 * This is method Admin_Private
	 *
	 * Called when a handler has added a private message to a ticket
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['subject'] Subject
	 * @param static $params['handler_message'] Message body
	 * @param static $params['status'] New status id
	 * @param static $params['files'] Any files that have been attached
	 */	
	/*function Admin_Private($ticket, $params)
	{

	}*/

	/**
	 * This is method Admin_ForwardUser
	 *
	 * Called when a handler has forwarded a ticket to another user
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['subject'] Subject
	 * @param static $params['user_message'] Message body
	 * @param static $params['user_id'] User id
	 * @param static $params['files'] Any files that have been attached
	 */	
	/*function Admin_ForwardUser($ticket, $params)
	{

	}*/

	/**
	 * This is method Admin_ForwardProduct
	 *
	 * Called when a handler has forwarded a ticket to another product or department
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['subject'] Subject
	 * @param static $params['user_message'] Message body to user
	 * @param static $params['handler_message'] Message body to new handler
	 * @param static $params['product_id'] Product id
	 * @param static $params['department_id'] Department id
	 * @param static $params['files'] Any files that have been attached
	 */	
	/*function Admin_ForwardProduct($ticket, $params)
	{

	}*/

	/**
	 * This is method Admin_ForwardHandler
	 *
	 * Called when a handler has forwarded a ticket to another handler
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['subject'] Subject
	 * @param static $params['user_message'] Message body to user
	 * @param static $params['handler_message'] Message body to new handler
	 * @param static $params['handler_id'] Handlers user id
	 * @param static $params['files'] Any files that have been attached

	 */	
	/*function Admin_ForwardHandler($ticket, $params)
	{

	}*/

	/**
	 * This is method User_Reply
	 *
	 * Called when a user has replied to a ticket
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['subject'] Subject
	 * @param static $params['user_message'] Message body
	 * @param static $params['files'] Any files that have been attached

	 */	
	/*function User_Reply($ticket, $params)
	{

	}*/



	/**
	 * This is method User_Open
	 *
	 * Called when a user has opened a ticket
	 * 
	 * @param static $ticket Ticket object
	 * @param static $params Array containing the parameters of the call
	 * 
	 * @param static $params['subject'] Subject
	 * @param static $params['user_message'] Message body
	 * @param static $params['files'] Any files that have been attached

	 */	
	/*function User_Open($ticket, $params)
	{

	}*/


	/** 
	 * Other events
	 * 
	 * Please let us know if you want any extra event calls adding for your own plugin usage
	 **/


	/**
	 * This is method beforeEMailSend
	 * 
	 * Allows you to modify any of the parameters for an email being sent, such as modifying the from address based on the ticket etc
	 *
	 * @param static $ticket Will be set to the current ticekt
	 * @param static $params Array of parameters being passed. 'mailer' is the mailer object for the email being sent
	 *
	 */	
	/*function beforeEMailSend($ticket, $params)
	{
		if ($ticket['ticket_dept_id'] == 3)
		{
			$params['mailer']->SetFrom("test@domain.com", "Test Name", 0);
		}
	}*/

	/**
	 * This is method beforeEMailImport
	 * 
	 * Allows you to modify any of the headers for the email as they are being imported
	 *
	 * @param static $ticket Will be set to Null
	 * @param static $params Array of parameters, 'headers' contains the headers of the email being imported
	 *
	 */	
	/*function beforeEMailImport($ticket, $params)
	{
		if ($params['headers']->from[0]->host == "from_domain.com") 
		{
			$params['headers']->from[0]->host = "to_domain.com";
		}
	}*/

	/**
	 * This is method ticketCreateRedirect
	 *
	 * Called before the ticket create redirect. Use this if you wish to redirect your users to a different
	 * page instead of the default one
	 * 
	 * @param static $ticket Will be set to Null
	 * @param static $params Array of parameters, 'ticketid' contains the id of the ticket
	 */	
	/*function ticketCreateRedirect($ticket, $params)
	{
		$ticket = new SupportTicket();
		$ticket->load($params['ticketid']);

		if ($ticket->ticket_dept_id == 3)
			JFactory::getApplication()->redirect("/your/url/here");
	}*/

	/**
	 * Used to override an auto assigned handler on a ticket
	 * 
	 * Params is an array containing:
	 * 
	 * [title] => Subject of ticket
	 * [user_id] => User id of ticket. 0 If unregistered    
	 * [email] => EMail of user if unregistered. Blank if registered
	 * [unregname] => Email of unregistered user. Blank if registered
	 * [source] => Source of request. Details below
	 * [admin_id] => Auto assigned admin id
	 * [prodid] => Product id
	 * [deptid] => Department id
	 * [catid] => Category
	 *
	 * Sources:
	 * 
	 * new_ticket_email - Ticekt created by email
	 * forward_product - Ticket forwarded to new product or department
	 * forward_handler - Forwarded to a "Auto Assign" handler
	 * outofoffice - Reassigned due to out of office
	 * new_ticket - New ticket opened by user
	 * 
	 * Return the id of the user the ticket should be assigned to. Return null to use the auto assign value.
	 **/

	/*function Tickets_customAssign($params)
	{
		return null;
	}*/
}