<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Import main table to assis this table.
Foundry::import( 'admin:/tables/table' );

/**
 * Tasks object relation mapper.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class CalendarTableCalendar extends SocialTable
{
	/**
	 * The unique task id.
	 * @var	int
	 */
	public $id 		= null;

	/**
	 * The feed title.
	 * @var	string
	 */
	public $title 	= null;

	/**
	 * The feed title.
	 * @var	string
	 */
	public $description = null;

	/**
	 * The feed url.
	 * @var	string
	 */
	public $reminder 	= null;

	/**
	 * The date time this task has been created.
	 * @var	datetime
	 */
	public $date_start 	= null;

	/**
	 * The date time this task has been created.
	 * @var	datetime
	 */
	public $date_end 	= null;

	/**
	 * The owner of the schedule.
	 * @var	int
	 */
	public $user_id	= null;

	/**
	 * Determines if this event runs the entire day
	 * @var	int
	 */
	public $all_day	= null;

	/**
	 * Class Constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct(& $db )
	{
		parent::__construct( '#__social_apps_calendar' , 'id' , $db );
	}

	public function getStartDate()
	{
		static $dates	= array();

		if( !isset( $dates[ $this->id ] ) )
		{
			$dates[ $this->id ]	= Foundry::date( $this->date_start );
		}

		return $dates[ $this->id ];
	}

	public function getEndDate()
	{
		static $dates	= array();

		if( !isset( $dates[ $this->id ] ) )
		{
			$dates[ $this->id ]	= Foundry::date( $this->date_end );
		}

		return $dates[ $this->id ];
	}

	/**
	 * Publishes into the stream
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The verb to be used on the stream
	 * @return
	 */
	public function createStream( $verb = 'create' )
	{
		// Add activity logging when a new schedule is created
		// Activity logging.
		$stream				= Foundry::stream();
		$streamTemplate		= $stream->getTemplate();

		// Set the actor.
		$streamTemplate->setActor( $this->user_id , SOCIAL_TYPE_USER );

		// Set the context.
		$streamTemplate->setContext( $this->id , 'calendar' );

		// Set the verb.
		$streamTemplate->setVerb( $verb );

		$streamTemplate->setPublicStream( 'core.view' );


		// Create the stream data.
		$stream->add( $streamTemplate );
	}
}
