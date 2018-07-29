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

jimport('joomla.application.component.model');

FD::import( 'admin:/includes/model' );

class EasySocialModelUploads extends EasySocialModel
{
	private $data			= null;
	protected $pagination		= null;

	function __construct()
	{
		parent::__construct( 'uploads' );
	}

	/**
	 * Retrieves the pagination object based on the current query.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->pagination ) )
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination( $this->total , $this->getState('limitstart') , $this->getState('limit') );
		}

		return $this->pagination;
	}

	/**
	 * Retrieves a list of files for a particular type.
	 *
	 * @since	1.0
	 * @access	public
	 * @param 	int 		$uid 		The unique id of the type.
	 * @param 	string		$type 		The unique string of the type.
	 * @param	Array 		$options	A list of options. ( state )
	 *
	 * @return	mixed 					False if none found, Array of SocialTableUploads if found.
	 */
	public function getFiles( $uid , $type , $options = array() )
	{
		$db 	= FD::db();

		$query	= 'SELECT * FROM ' . $db->nameQuote( '#__social_files' );

		$query	.= ' WHERE ' . $db->nameQuote( 'uid' ) . '=' . $db->Quote( $uid );
		$query	.= ' AND ' . $db->nameQuote( 'type' ) . '=' . $db->Quote( $type );

		if( isset( $options[ 'state' ] ) )
		{
			$publishOption 	= $options[ 'state' ] ? '1' : '0';

			$query	.= ' AND ' . $db->nameQuote( 'state' ) . '=' . $db->Quote( $publishOption );
		}

		$db->setQuery( $query );

		$result 		= $db->loadObjectList();

		if( !$result )
		{
			return false;
		}

		$files 	= array();

		foreach( $result as $row )
		{
			$file 	= FD::table( 'File' );
			$file->bind( $row );

			$files[]	= $file;
		}

		return $files;
	}
}
