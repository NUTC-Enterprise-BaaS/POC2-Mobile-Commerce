<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * An example plugin showing how to modify the "From" domain of an email being imported
 * 
 * IF you wish to use this please create a copy or any changes will be overwritten when you update
 * the component. Please dont forget to replace the EMailRecv part of the class name with the 
 * name of the file you copy to.
 */
class SupportActionsEMailRecv extends SupportActionsPlugin
{
	var $title = "Modify Emails being imported";
	var $description = "";

	static function beforeEMailImport($ticket, $params)
	{
		if ($params['headers']->from[0]->host == "from_domain.com") 
		{
			$params['headers']->from[0]->host = "to_domain.com";
		}
	}
}