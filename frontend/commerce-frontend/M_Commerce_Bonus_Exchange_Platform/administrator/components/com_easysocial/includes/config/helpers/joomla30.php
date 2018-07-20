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

/**
 * Wrapper for JConfig
 *
 * @since	1.0
 * @access	public
 */
class SocialConfigJoomla30 extends JConfig
{
	private $config 	= null;

	public function __construct()
	{
		$this->config 	= JFactory::getConfig();
	}

	public function getValue( $key , $default = null )
	{
		return $this->config->get( $key , $default );
	}
}
