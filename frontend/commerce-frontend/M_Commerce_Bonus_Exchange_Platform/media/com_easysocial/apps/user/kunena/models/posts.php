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

FD::import( 'admin:/includes/model' );

class PostsModel extends EasySocialModel
{
	/**
	 * Retrieves the stats for kunena posts
	 *
	 * @since	1.2
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getStats( $userId )
	{
		$db			= FD::db();
		$sql 		= $db->sql();
		$dates 		= array();

		// Get the past 7 days
		$curDate 	= FD::date();
		for( $i = 0 ; $i < 7; $i++ )
		{
			$obj = new stdClass();

			if( $i == 0 )
			{
				$dates[]		= $curDate->toSql( true );
			}
			else
			{
				$unixdate 		= $curDate->toUnix( true );
				$new_unixdate 	= $unixdate - ( $i * 86400);
				$newdate  		= FD::date( $new_unixdate );

				$dates[]		= $newdate->toSql( true );
			}
		}

		// Reverse the dates
		$dates 		= array_reverse( $dates );
		$result 	= array();

		foreach( $dates as $date )
		{
			// Registration date should be Y, n, j
			$date	= FD::date( $date )->format( 'Y-m-d' );

			$query 		= array();
			$query[]	= 'SELECT COUNT(1) AS `cnt` FROM `#__kunena_messages` AS a';
			$query[]	= 'WHERE DATE_FORMAT( from_unixtime( a.' . $db->nameQuote( 'time' ) . ') , GET_FORMAT( DATE , "ISO" ) ) =' . $db->Quote( $date );
			$query[]	= 'AND a.' . $db->nameQuote( 'userid' ) . '=' . $db->Quote( $userId );
			$query[]	= 'AND a.' . $db->nameQuote( 'hold' ) . '=' . $db->Quote( 0 );
			$query[]	= 'AND a.' . $db->nameQuote( 'parent' ) . '=' . $db->Quote( 0 );
			// $query[] 	= 'group by a.`userid`';

			// $query[]	= 'SELECT COUNT(1) AS `cnt` FROM `#__kunena_topics` AS a';
			// $query[]	= 'WHERE DATE_FORMAT( from_unixtime( a.' . $db->nameQuote( 'first_post_time' ) . ') , GET_FORMAT( DATE , "ISO" ) ) =' . $db->Quote( $date );
			// $query[]	= 'AND a.' . $db->nameQuote( 'first_post_userid' ) . '=' . $db->Quote( $userId );
			// $query[]	= 'AND a.' . $db->nameQuote( 'hold' ) . '=' . $db->Quote( 0 );

			$query 		= implode( ' ' , $query );

			$sql->raw( $query );

			$db->setQuery( $sql );

			$total		= $db->loadResult();

			$result[]	= $total;
		}

		return $result;
	}

	/**
	 * Retrieves a list of tasks created by a particular user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		$userId		The user's / creator's id.
	 *
	 * @return	Array				A list of notes item.
	 */
	public function getPosts( $userId , $total = 10 )
	{
		$db 	= FD::db();

		$sql 	= $db->sql();


		$sql->select( '#__kunena_messages' , 'a' );
		$sql->column( 'a.thread' );
		$sql->where( 'a.parent' , 0 );
		$sql->where( 'a.userid' , $userId );
		$sql->where( 'a.hold' , '0' , '=' );
		$sql->order( 'a.time' , 'DESC' );
		$sql->limit( 0 , $total );

		$db->setQuery( $sql );

		$result	= $db->loadColumn();

		if( !$result )
		{
			return array();
		}

		$posts 	= KunenaForumTopicHelper::getTopics( $result );

		return $posts;
	}

	/**
	 * Retrieves the total replies posted in Kunena
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The author's id.
	 *
	 * @return	Array		A list of notes item.
	 */
	public function getTotalReplies($userId)
	{
		$db = FD::db();
		$sql = $db->sql();

		$sql->select('#__kunena_messages', 'a');
		$sql->column('COUNT(1)');
		$sql->join( '#__kunena_categories' , 'b' );
		$sql->on('a.catid', 'b.id');

		$sql->join('#__kunena_messages_text' , 'c' );
		$sql->on('a.id' , 'c.mesid' );

		$sql->where('a.parent', 0, '!=');
		$sql->where('a.userid', $userId);
		$sql->where('b.published', 1);

		$db->setQuery($sql);

		$posts	= (int) $db->loadResult();

		return $posts;
	}


	/**
	 * Retrieves replies posted in Kunena
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int			The author's id.
	 *
	 * @return	Array		A list of notes item.
	 */
	public function getReplies( $userId )
	{
		$db 	= FD::db();

		$sql 	= $db->sql();


		$sql->select( '#__kunena_messages' , 'a' );
		$sql->column( 'a.*' );
		$sql->column( 'b.*' );
		$sql->column( 'c.message' , 'content' );
		$sql->join( '#__kunena_categories' , 'b' );
		$sql->on( 'a.catid' , 'b.id' );

		$sql->join( '#__kunena_messages_text' , 'c' );
		$sql->on( 'a.id' , 'c.mesid' );

		$sql->where( 'a.parent' , 0 , '!=' );
		$sql->where( 'a.userid' , $userId );
		$sql->where( 'b.published' , 1 );

		$sql->order( 'a.time' , 'DESC' );

		$db->setQuery( $sql );

		$posts	= $db->loadObjectList();

		return $posts;
	}

}
