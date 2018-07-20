<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

FD::import( 'site:/views/views' );

class EasySocialViewSharing extends EasySocialSiteView
{
	/**
	 * Displays the share dialog
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function shareDialog()
	{
		$url = $this->input->get('url', '', 'default');
		$title = $this->input->get('title', '', 'default');
		$summary = $this->input->get('summary', '', 'default');

		// Load up the sharing library
		$options = array('url' => $url, 'title' => $title, 'summary' => $summary);
		$sharing = FD::sharing($options);

		// Get the contents
		$contents = $sharing->getContents();

		return $this->ajax->resolve($contents);
	}

	/**
	 * 
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function send($state, $msg = '')
	{
		if ($state) {
			return $this->ajax->resolve();
		}

		return $this->ajax->reject($msg);

		return true;
	}
}
