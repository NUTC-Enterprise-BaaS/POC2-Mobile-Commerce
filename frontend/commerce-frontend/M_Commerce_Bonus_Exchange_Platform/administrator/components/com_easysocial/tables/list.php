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

FD::import( 'admin:/tables/table' );
FD::import( 'admin:/includes/indexer/indexer' );

/**
 * Object mapping for lists.
 *
 * @author	Mark Lee <mark@stackideas.com>
 * @since	1.0
 */
class SocialTableList extends SocialTable
	implements ISocialIndexerTable
{
	/**
	 * The unique id which is auto incremented.
	 * @var int
	 */
	public $id					= null;

	/**
	 * The list title.
	 * @var string
	 */
	public $title				= null;

	/**
	 * The alias.
	 * @var string
	 */
	public $alias				= null;

	/**
	 * The description.
	 * @var string
	 */
	public $description				= null;


	/**
	 * Creation date of the list.
	 * @var datetime
	 */
	public $created				= null;

	/**
	 * Modified date of the list.
	 * @var datetime
	 */
	public $modified			= null;

	/**
	 * Determines if the list is used by default.
	 * @var int
	 */
	public $default				= null;

	/**
	 * The state of the list, 1 - published , 0 - unpublished.
	 * @var int
	 */
	public $state				= null;

	/**
	 * The owner of the list.
	 * @var int
	 */
	public $user_id				= null;

	/**
	 * Class construct
	 *
	 * @since	1.0
	 * @param	JDatabase
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__social_lists' , 'id' , $db );
	}

	/**
	 * Binds a given array or object in this table's properties.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	object|array 	Data to be binded.
	 * @param	array 			An optional array or space separated list of properties to ignore while binding.
	 * @return	bool			State of storing.
	 */
	public function bind( $data , $ignore = array() )
	{
		$state	= parent::bind( $data , $ignore );

		// @task: If created is not set, we need to set it here.
		if( empty( $this->created ) )
		{
			$this->created	= FD::get( 'Date' )->toMySQL();
		}

		// @task: If created is not set, we need to set it here.
		if( empty( $this->state ) )
		{
			// @TODO: Make this configurable. Default state to be published
			$this->state	= SOCIAL_FRIENDS_LIST_PUBLISHED;
		}

		return $state;
	}

	/**
	 * Sets a list as the default list
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	Determines the state.
	 */
	public function setDefault()
	{
		$model 	= FD::model( 'Lists' );
		$state 	= $model->setDefault( $this->id , $this->user_id );

		if( !$state )
		{
			$this->setError( $model->getError() );
		}
		return $state;
	}

	/**
	 * Override parent's store method.
	 *
	 * @access	public
	 * @param	bool	$updateModified		Update modified time if this is true. Default true.
	 * @return	bool	True on success, false on error.
	 */
	public function store( $updateModified = true )
	{
		if (empty($this->title)) {
			return false;
		}

		$isNew 	= ( empty( $this->id ) ) ? true : false ;
		$now	= FD::get( 'Date' )->toMySQL();

		// If script needs us to alter the modified date or if it's a new record,
		// ensure that the modified column contains proper values.
		if( $updateModified || empty($this->modified) )
		{
			$this->modified	= $now;
		}

		if( $isNew )
		{
			// @badge: friends.list.create
			$badge 	= FD::badges();
			$badge->log( 'com_easysocial' , 'friends.list.create' , $this->user_id , JText::_( 'COM_EASYSOCIAL_FRIENDS_BADGE_CREATED_FRIEND_LIST' ) );

			// @points: friends.list.create
			// Assign points when the user creates a new list.
			$points 	= FD::points();
			$points->assign( 'friends.list.create' , 'com_easysocial' , $this->user_id );
		}

		return parent::store();
	}

	/**
	 * Returns the permalink of the list
	 *
	 * @since	1.0
	 * @access	public
	 * @return	string	The absolute url to this list.
	 */
	public function getPermalink( $xhtml = true, $external = false )
	{
		return FRoute::friends( array( 'listId' => $this->id, 'external' => $external ) , $xhtml );
	}

	/**
	 * Retrieves the friend count in a particular list item.
	 *
	 * @access	public
	 * @param	null
	 * @return	int	The total number of friends in this list.
	 **/
	public function getCount()
	{
		$model	= FD::model( 'Lists' );
		$total 	= $model->getCount( $this->id );

		return $total;
	}

	/**
	 * Determines if a user is already in the list.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function mapExists( $targetId , $targetType = SOCIAL_TYPE_USER )
	{
		$map 	= FD::table( 'ListMap' );

		// Item already exist.
		if( $map->loadByType( $this->id , $targetId , $targetType ) )
		{
			return true;
		}

		return false;
	}

	/**
	 * Overrides parent implementation of delete so that we can delete the mappings.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	mixed		An optional primary key value to delete.  If not set the instance property value is used.
	 * @return	boolean		True on success, false otherwise.
	 */
	public function delete( $pk = null )
	{
		$model	= FD::model( 'Lists' );

		$state 	= $model->deleteMapping( $this->id );

		// When there's some error deleting the mapping, just throw an error.
		if( !$state )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_FRIENDS_LIST_ERROR_REMOVING_MAPPING' ) );
			return $state;
		}

		$state 	= parent::delete( $pk );

		// @points: friends.list.delete
		// Assign points when the user deletes an existing list
		$points 	= FD::points();
		$points->assign( 'friends.list.delete' , 'com_easysocial' , $this->user_id );

		return $state;
	}

	/**
	 * Adds a list of user id's into the current list.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @param	Array	An array of user id's.
	 * @return	bool
	 */
	public function addFriends( $ids = array() , $targetType = SOCIAL_TYPE_USER )
	{
		// Ensure that it's an array.
		$ids 	= FD::makeArray( $ids );

		if( !$ids )
		{
			return;
		}

		$friendsModel 	= FD::model( 'Friends' );

		foreach( $ids as $id )
		{
			// Check if the user is really a friend with the target user.
			if( $friendsModel->isFriends( $this->user_id , $id ) )
			{
				$this->addItem( $id , $targetType );
			}
		}

		return true;
	}

	/**
	 * Adds a user into the current list.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $listId 	= JRequest::getInt( 'id' );
	 * $userId 	= JRequest::getInt( 'user_id' );
	 * $table 	= FD::table( 'List' );
	 * $table->load( $listId );
	 * $table->addItem( $userId );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @param	int 	The target id. Could be user id, group id etc.
	 * @param	string	The target type. Could be people, events etc.
	 * @return	bool
	 */
	public function addItem( $target_id , $target_type = SOCIAL_TYPE_USER )
	{
		$map 	= FD::table( 'ListMap' );
		$exists	= $map->load( array( 'list_id' => $this->id , 'target_id' => $target_id , 'target_type' => $target_type ) );

		// Item already exist.
		if( $exists )
		{
			return true;
		}

		$map->set( 'list_id'	, $this->id );
		$map->set( 'target_id'	, $target_id );
		$map->set( 'target_type', $target_type );

		// @points: friends.list.add
		// Assign points when the user inserts a friend into the list.
		$points 	= FD::points();
		$points->assign( 'friends.list.add' , 'com_easysocial' , $this->user_id );

		return $map->store();
	}

	/**
	 * Delete's an item from the curernt list.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $listId 	= JRequest::getInt( 'id' );
	 * $userId 	= JRequest::getInt( 'user_id' );
	 * $table 	= FD::table( 'List' );
	 * $table->load( $listId );
	 * $table->removeItem( $userId );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @param	int 	The target id. Could be user id, group id etc.
	 * @param	string	The target type. Could be people, events etc.
	 * @return	SocialTable
	 */
	public function deleteItem( $targetId , $targetType = SOCIAL_TYPE_USER )
	{
		$map 	= FD::table( 'ListMap' );

		// Item don't exist.
		if( !$map->loadByType( $this->id , $targetId , $targetType ) )
		{
			$this->setError( JText::_( 'Item does not exist in this list.' ) );
			return false;
		}

		return $map->delete();
	}

	/**
	 * Gets a list of users from this list.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array		An array of friends
	 */
	public function getMembers()
	{
		static $members 	= array();

		if( !$this->id )
		{
			return false;
		}

		if( !isset( $members[ $this->id ] ) )
		{
			$model	= FD::model( 'Lists' );

			$result = $model->getMembers( $this->id , true );

			$members[ $this->id ]	= $result;
		}

		return $members[ $this->id ];
	}

	/**
	 * Determines if the provided user is an owner of this list.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id. Null to get the current user.
	 * @return	bool	True if user owns the list. False otherwise.
	 */
	public function isOwner( $userId = null )
	{
		$userId 	= FD::user( $userId )->id;

		return $this->user_id == $userId;
	}

	public function syncIndex()
	{
		$indexer = FD::get( 'Indexer' );

		$item 	= $indexer->getTemplate();

		$item->setContent( $this->title, $this->title . ' ' . $this->description );

		// $url = FRoute::_( 'index.php?option=com_easysocial&view=friends&listid=' . $this->id );
		$url 	= FRoute::friends( array( 'listid' => $this->id ) );
		$url 	= '/' . ltrim( $url , '/' );
		$url 	= str_replace('/administrator/', '/', $url );

		$item->setSource($this->id, SOCIAL_INDEXER_TYPE_LISTS, $this->user_id, $url);

		$date = FD::date();
		$item->setLastUpdate( $date->toMySQL() );

		return $indexer->index( $item );
	}

	public function deleteIndex()
	{
		$indexer = FD::get( 'Indexer' );
		$indexer->delete( $this->id, SOCIAL_INDEXER_TYPE_LISTS);
	}


}
