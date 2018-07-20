<?php

class SupportActionsAcySMS extends SupportActionsPlugin
{
	var $title = "AcySMS Plugin";
	var $description = "This plugin will allow you to notify your user via SMS each time a ticket is submitted or answered";

	function User_Open($ticket, $params)
	{
		JPluginHelper::importPlugin('acysms');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onAcySMS_FreestyleSupportSendNotification', array($ticket, $params,'ticketCreated'));
	}

	function User_Reply($ticket, $params){
		JPluginHelper::importPlugin('acysms');
		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onAcySMS_FreestyleSupportSendNotification', array($ticket, $params,'ticketReplied'));
	}
}