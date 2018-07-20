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


class SocialStorageJoomla implements SocialStorageInterface
{
	private $lib 	= null;

	public function __construct()
	{
	}

	public function init()
	{
	}

	public function containerExists( $container )
	{
	}

	public function createContainer( $container )
	{
	}

	/**
	 * Returns the absolute path to the object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The storage id
	 * @return	string	The absolute URI to the object
	 */
	public function getPermalink( $relativePath )
	{
		return rtrim( JURI::root() , '/' ) . '/' . $relativePath;
	}

	/**
	 * Pushes a file to the remote repository
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The relative path to the file
	 * @return
	 */
	public function push( $fileName , $source , $dest )
	{
	}

	/**
	 * Pulls a file from the remote repositor
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The relative path to the file
	 * @return
	 */
	/**
	 * Pulls a file from the remote repositor
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The relative path to the file
	 * @return
	 */
	public function pull($relativePath, $saveTo = '')
	{
	}

	/**
	 * Deletes a file from the remote repository
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The relative path to the file
	 * @return
	 */
	public function delete( $paths , $folder = false )
	{
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.folder' );

		if (is_array($paths)) {

			foreach ($paths as $storage) {
				// Ensure that leading / is removed
				$storage 	= ltrim($storage, '/');

				// Restructure the full path now.
				$storage 	= JPATH_ROOT . '/' . $storage;

				if ($folder && JFolder::exists($storage)) {
					JFolder::delete( $storage );
				}

				if (JFile::exists($storage)) {
					JFile::delete( $storage );
				}
			}

			return true;
		}

		$path = JPATH_ROOT . $paths;

		if ($folder) {

			if ( JFolder::exists($path)) {
			    return JFolder::delete($path);
			}
		}

		if (JFile::exists($path)) {
		    return JFile::delete($path);
		}

		return true;
	}
}
