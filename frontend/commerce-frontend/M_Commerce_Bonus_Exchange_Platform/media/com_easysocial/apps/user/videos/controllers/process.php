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

class VideosControllerProcess extends SocialAppsController
{
    /**
     * Processes a video
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function process()
    {
        // Determines the type of video being inserted
        $type = $this->input->get('type', '', 'word');

        $method = 'process' . ucfirst($type);
        
        $this->$method();
    }

    /**
     * Processes story creation via video uploads
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function processUpload()
    {
        dump($_POST, 'hello');
    }

    /**
     * Processes story creation via external video linking
     *
     * @since   1.4
     * @access  public
     * @param   string
     * @return  
     */
    public function processLink()
    {
        $link = $this->input->get('link', '', 'default');
        $link = trim($link);
        
        // We need to format the url properly.
        $video = ES::video();
        $link = $video->format($link);

        $crawler = ES::crawler();
        $crawler->crawl($link);

        $data = (object) $crawler->getData();

        $html = '';
        $thumbnail = '';

        // If there is an oembed property, try to use it.
        if (isset($data->oembed->html)) {
            $html = $data->oembed->html;
        }

        if (isset($data->oembed->thumbnail_url)) {
            $thumbnail = $data->oembed->thumbnail_url;
        }

        // If there is no thumbnail, we should try to get from the opengraph tag if it exists
        if (!isset($data->oembed->thumbnail_url) && isset($data->opengraph->image)) {
            $thumbnail = $data->opengraph->image;
        }

        if (!isset($data->oembed->html) && isset($data->opengraph->video)) {
            $html = '<iframe src="' . $data->opengraph->video . '" frameborder="0"></iframe>';
        }

        return $this->ajax->resolve($data, $thumbnail, $html);
    }
}
