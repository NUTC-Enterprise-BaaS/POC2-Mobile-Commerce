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

FD::import( 'admin:/tables/cluster' );
FD::import( 'admin:/includes/indexer/indexer' );

/**
 * Object mapping for `#__social_clusters` table.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.2
 */
class SocialTableGroup extends SocialTableCluster
	implements ISocialIndexerTable
{
	/**
	 * Retrieves the name of the group
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The group's title
	 */
	public function getName()
	{
		return $this->title;
	}

	/**
	 * Retrieves the avatar of the group.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The group's title
	 */
	public function getAvatar()
	{
		return $this->title;
	}

	public function syncIndex()
	{
		$indexer = FD::get( 'Indexer' );

		$tmpl 	= $indexer->getTemplate();

		$group = FD::group( $this->id );
		$url   	= $group->getPermalink();
		$url 	= '/' . ltrim( $url , '/' );
		$url 	= str_replace('/administrator/', '/', $url );

		$tmpl->setSource( $this->id , SOCIAL_INDEXER_TYPE_GROUPS , $this->creator_uid , $url );

		$content = ( $this->description ) ? $this->title . ' ' . $this->description : $this->title;
		$tmpl->setContent( $this->title, $content );

		$thumbnail 	= $group->getAvatar( SOCIAL_AVATAR_SQUARE );
		if( $thumbnail )
		{
			$tmpl->setThumbnail( $thumbnail );
		}

		$date = FD::date();
		$tmpl->setLastUpdate( $date->toMySQL() );

		$state = $indexer->index( $tmpl );
		return $state;
	}

	public function deleteIndex()
	{
		$indexer = FD::get( 'Indexer' );
		$indexer->delete( $this->id, SOCIAL_INDEXER_TYPE_GROUPS);
	}
}
