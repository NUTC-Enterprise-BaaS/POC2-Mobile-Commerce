<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

/**
 * Hooks for Storage
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 *
 */
class SocialCronHooksEmail
{
	public function execute( &$states )
	{
		// Offload photos to remote location
		$states[] = $this->dispatchEmails();
	}


	/**
	 * Dispatches Pending Emails
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function dispatchEmails()
	{
		$config		= FD::config();

		// Dispatch mails here.
		$mailer 	= FD::mailer();
		$state 		= $mailer->cron( $config->get( 'general.cron.limit' ) );

		if ($state) {
			return JText::_( 'COM_EASYSOCIAL_CRONJOB_PROCESSED' );
		}

		return JText::_( 'COM_EASYSOCIAL_CRONJOB_NOTHING_TO_EXECUTE' );
	}
}
