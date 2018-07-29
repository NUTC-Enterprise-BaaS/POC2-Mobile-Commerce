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

// Import parent view
ES::import('site:/views/views');

class EasySocialViewVideos extends EasySocialSiteView
{
	/**
	 * Post process after a video has been uploaded via story form.
	 *
	 * @since	1.4
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function uploadStory(SocialVideo $video)
	{
		$response = new stdClass();

		if ($this->hasErrors()) {
			$response->error = $this->getMessage();

			return $this->json->send($response);
		}

		$response->error = false;
		$response->data = $video->export();
		$response->thumbnail = $video->getThumbnail();
		$response->html = $video->getEmbedCodes();

		// This needs to respect the settings whether on the fly conversion should be supported or not.
		$response->isEncoding = $video->isProcessing();

		return $this->json->send($response);
	}
}
