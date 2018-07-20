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

class SocialCrawlerOpengraph
{
	private $videos			= array(
									'youtube.com'		=> 'youtube',
									'youtu.be'			=> 'youtube',
									'vimeo.com'			=> 'vimeo',
									'yahoo.com'			=> 'yahoo',
									'metacafe.com'		=> 'metacafe',
									'google.com'		=> 'google',
									'mtv.com'			=> 'mtv',
									'liveleak.com'		=> 'liveleak',
									'revver.com'		=> 'revver',
									'dailymotion.com'	=> 'dailymotion'
								);


	public $patterns = array(
							// Example: <meta property="og:image" content="http://stackideas.com/images/easyblog_images/1257/b2ap3_thumbnail_easyblog-37-supports-joomla-3.jpg"/>
							'image'	=> 'og:image',

							// Example: <meta property="og:title" content="EasyBlog 3.7 is now Joomla 3.0 ready" />
							'title'	=> 'og:title',

							// Example: <meta property="og:description" content="EasyBlog 3.7 now works in Joomla 3.0 and comes with some new features." />
							'desc'	=> 'og:description',

							// Example: <meta property="og:type" content="article" />
							'type'	=> 'og:type',

							// Example: <meta property="og:type" content="article" />
							'url'	=>	'og:url',

							// Example: <meta property="og:video" content="http://www.youtube.com/v/T39GhB5uBGQ?version=3&amp;autohide=1">
							'video'	=> 'og:video',

							// Example: <meta property="og:video:type" content="application/x-shockwave-flash">
							'video_type'	=> 'og:video:type',

							// Example: <meta property="og:video:width" content="640">
							'video_width'	=> 'og:video:width',

							// Example: <meta property="og:video:height" content="640">
							'video_width'	=> 'og:video:height',
						);


	/**
	 * Ruleset to process document opengraph tags
	 *
	 * @params	string $contents	The html contents that needs to be parsed.
	 * @return	boolean				True on success false otherwise.
	 */
	public function process( $parser , &$contents , $uri )
	{
		$result = new stdClass();

		foreach ($this->patterns as $key => $pattern) {

			// Try to find the pattern now
			$items = $parser->find('meta[property=' . $pattern . ']');

			foreach ($items as $meta) {
				$result->$key = $meta->content;
				break;
			}
		}

		return $result;
	}

	public function getVideoProvider( $video )
	{
		preg_match( '/http\:\/\/(.*)\//i' , $video , $matches );
		$url	= $matches[0];
		$url	= parse_url( $url );
		$url	= explode( '.' , $url[ 'host' ] );

		// Last two parts will always be the domain name.
		$url	= $url[ count( $url ) - 2 ] . '.' . $url[ count( $url ) - 1 ];

		if( !empty( $url ) && array_key_exists( $url , $this->videos ) )
		{
			$provider 	=  $this->videos[ $url ];

			return $provider;
		}

		return false;
	}

	/**
	 * Returns the charset of the document.
	 *
	 * @return	string		The document charset.
	 */
	public function get()
	{
		return $this->charset;
	}
}
