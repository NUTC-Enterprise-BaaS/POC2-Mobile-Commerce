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

class SocialDate
{
	private $date = null;

	private static $lang;

	public function __construct( $date = 'now', $withoffset = true , $debug = false )
	{
		require_once( dirname( __FILE__ ) . '/helpers/helper.php' );

		$this->date = new SocialDateHelper( $date , $withoffset );
	}

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  SocialStream	The stream object.
	 */
	public static function factory( $date = 'now' , $withoffset = true )
	{
		return new self( $date , $withoffset );
	}

	public function toMySQL( $local = false )
	{
		return $this->date->toMySQL( $local );
	}

	public function toFormat( $format = 'DATE_FORMAT_LC2', $local = true )
	{
		$format 	= JText::_( $format );
	    return $this->date->toFormat( $format, $local );
	}

	/**
	 * Returns the lapsed time since NOW
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string	The lapsed time.
	 */
	public function toLapsed()
	{
		// Load front end language strings as well since this lib requires it.
		FD::language()->loadSite();

		$now 		= FD::date();
		$time		= $now->date->toUnix( true ) - $this->date->toUnix( true );

	    $tokens = array (
					        31536000 	=> 'COM_EASYSOCIAL_LAPSED_YEARS_COUNT',
					        2592000 	=> 'COM_EASYSOCIAL_LAPSED_MONTHS_COUNT',
					        604800 		=> 'COM_EASYSOCIAL_LAPSED_WEEKS_COUNT',
					        86400 		=> 'COM_EASYSOCIAL_LAPSED_DAYS_COUNT',
					        3600 		=> 'COM_EASYSOCIAL_LAPSED_HOURS_COUNT',
					        60 			=> 'COM_EASYSOCIAL_LAPSED_MINUTES_COUNT',
					        1 			=> 'COM_EASYSOCIAL_LAPSED_SECONDS_COUNT'
	    				);

		if( $time == 0 )
		{
			return JText::_( 'COM_EASYSOCIAL_LAPSED_NOW' );
		}

	    foreach( $tokens as $unit => $key )
		{
			if ($time < $unit)
			{
				continue;
			}

			$units	= floor( $time / $unit );

			$text 	= FD::string()->computeNoun( $key , $units );
			$text 	= JText::sprintf( $text , $units );

			return $text;
	    }

	}

	/**
	 * Returns the JDate/Datetime object
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.2
	 * @access public
	 * @return JDate    The JDate/Datetime object
	 */
	public function getObject()
	{
		return $this->date;
	}

	/**
	 * Middle man method to apply the "modify" method on the helper, but return the current instance instead for chaining.
	 *
	 * @author Jason Rey <jasonrey@stackideas.com>
	 * @since  1.3.10
	 * @access public
	 * @param  string    $string The date modification string
	 * @return SocialDate        The current date instance.
	 */
	public function modify($string)
	{
		$this->date->modify($string);

		return $this;
	}

	public function __call( $method , $args )
	{
		return call_user_func_array( array( $this->date , $method ) , $args );
	}
}
