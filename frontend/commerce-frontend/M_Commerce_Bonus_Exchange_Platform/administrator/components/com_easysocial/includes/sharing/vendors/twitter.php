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

require_once( dirname( __FILE__ ) . '/vendor.php' );

class SocialSharingTwitter extends SocialSharingVendor
{
	public $base	= 'http://twitter.com/intent/tweet';

	public $map		= array(
		'url'		=> 'url',
		'summary'	=> 'text'
	);

    /**
     * Generates the external link
     *
     * @since   1.3
     * @access  public
     * @param   string
     * @return  
     */
    public function getLink()
    {
        $url = $this->getParam('url');

        $this->link = $this->base . '?url=' . urlencode($this->getParam('url'));

        return $this->link;
    }
}
