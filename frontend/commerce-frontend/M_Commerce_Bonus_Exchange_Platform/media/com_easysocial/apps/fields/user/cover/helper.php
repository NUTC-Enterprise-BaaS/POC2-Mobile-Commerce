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

// Include the fields library
FD::import( 'admin:/includes/fields/dependencies' );

// Import necessary library
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );


/**
 * Processes ajax calls for the Joomla_Email field.
 *
 * @since	1.0
 * @author	Jason Rey <jasonrey@stackideas.com>
 */
class SocialFieldsUserCoverHelper
{
	/**
	 * Checks if the cover photo is a valid image
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function isValid( $filePath )
	{
		// Load image library
		$image 	= FD::get( 'Image' );

		// Generate a temporary name for this image
		$name 	= md5( $filePath );

		// Load up the image
		$image->load( $filePath , $name );

		// Test if it is valid.
		$valid 	= $image->isValid();

		return $valid;
	}

	/**
	 * Shorthand function to get the album
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.2
	 * @access public
	 * @param  int       $uid  The unique id of the target object
	 * @param  string    $type The type of the unique id
	 * @return SocialTableAlbum    The album table object
	 */
	public static function getDefaultAlbum( $uid, $type = SOCIAL_TYPE_USER )
	{
		$model = FD::model( 'Albums' );
		$album = $model->getDefaultAlbum( $uid, $type, SOCIAL_ALBUM_PROFILE_COVERS );

		return $album;
	}

	/**
	 * Creates the photo object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function createPhotoObject( $uid , $type, $albumId , $title , $oauth = false )
	{
		$photo 				= FD::table( 'Photo' );

		$photo->uid 		= $uid;
		$photo->type 		= $type;
		$photo->user_id 	= FD::user()->id;
		$photo->album_id 	= $albumId;
		$photo->title 		= $title;
		$photo->state		= SOCIAL_STATE_PUBLISHED;

		$photo->caption 	= $oauth ? JText::_( 'Cover from Facebook' ) : '';

		// Store the photo
		$photo->store();

		return $photo;
	}

	/**
	 * Creates the photo meta data
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function createPhotoMeta( SocialTablePhoto $photo , $size , $path )
	{
		$meta 	= FD::table( 'PhotoMeta' );

		$meta->photo_id 	= $photo->id;
		$meta->group 		= SOCIAL_PHOTOS_META_PATH;
		$meta->property 	= $size;
		$meta->value 		= $path;
		$meta->store();

		return $meta;
	}

	/**
	 * Generates a unique id
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function genUniqueId( $inputName )
	{
		$session 		= JFactory::getSession();
		$uid 			= md5( $session->getId() . $inputName );

		return $uid;
	}

	public static function getPath( $inputName )
	{
		$date 		= FD::date();

		// Create a temporary folder for this session.
		$session 	= JFactory::getSession();
		$uid 		= md5( $session->getId() . $inputName );
		$path 		= SOCIAL_MEDIA . '/tmp/' . $uid . '_cover';

		return $path;
	}

	public static function getStoragePath( $inputName )
	{
		$path 	= SocialFieldsUserCoverHelper::getPath( $inputName );

		// If the folder exists, delete them first.
		if( JFolder::exists( $path ) )
		{
			JFolder::delete( $path );
		}

		// Create folder if necessary.
		FD::makeFolder( $path );

		// Re-generate the storage path since we do not want to store the JPATH_ROOT
		$path = str_replace( JPATH_ROOT, '', $path );

		return $path;
	}
}
