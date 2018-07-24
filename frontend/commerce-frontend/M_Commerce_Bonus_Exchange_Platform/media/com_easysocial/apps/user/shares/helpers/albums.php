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

require_once( dirname( __FILE__ ) . '/abstract.php' );

class SocialSharesHelperAlbums extends SocialSharesHelper
{
	public function getContent()
	{
		$message 	= $this->formatContent( $this->share->content );
		$sourceId 	= $this->share->uid;

		// Load the album object
		$album = FD::table( 'Album' );
		$album->load( $sourceId );

		// Get user's privacy.
		$my         = FD::user();
		$privacy 	= FD::privacy( $my->id );

		if (!$privacy->validate('albums.view', $album->id, SOCIAL_TYPE_ALBUM, $album->uid)) {
			$theme = ES::themes();
			$html = $theme->output('site/stream/restricted.text');
			
			return $html;
		}

		$theme 		= FD::get( 'Themes' );
		$theme->set( 'album'		, $album );
		$theme->set( 'message'		, $message );

		$html	= $theme->output( 'apps/user/shares/streams/albums/content' );

		return $html;
	}

	public function getLink()
	{
		$link = FRoute::albums( array( 'id' => $this->item->contextId ) );

		return $link;
	}

	public function getTitle()
	{
		$actors = $this->item->actors;
		$names  = FD::string()->namesToStream( $actors, true, 3 );

		// Load the album
		$album = FD::table( 'Album' );
		$album->load( $this->share->uid );
		$albumCreator	= FD::user( $album->uid );

		$theme 		= FD::get( 'Themes' );
		$theme->set( 'names'		, $names );
		$theme->set( 'album' 	, $album );
		$theme->set( 'creator'	, $albumCreator );

		$html	= $theme->output( 'apps/user/shares/streams/albums/title' );

		return $html;
	}
}
