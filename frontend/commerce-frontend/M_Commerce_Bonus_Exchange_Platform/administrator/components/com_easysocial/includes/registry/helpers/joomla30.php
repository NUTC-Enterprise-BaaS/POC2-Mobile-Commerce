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

jimport('joomla.html.parameter');

class SocialRegistryJoomla30 extends JRegistry
{
	var $_defaultNameSpace = null;

	public function __construct($data = '', $path = '')
	{
		$this->_defaultNameSpace = 'default';

		parent::__construct( $data , $path );
	}

	public function __get( $key )
	{
		if( $key == '_registry' )
		{
			return (array) $this->data;
		}

		return $this->get( $key );
	}

	public function load( $contents = '' )
	{
		$this->loadString( $contents );
	}

	public function get( $key , $default = '' )
	{

		return parent::get( $key , $default );
	}

	public function bind( $data )
	{

		return $this->bindData( $this->data , $data );
	}

	public function setData( $obj )
	{
		$this->bindData( $this->data , $obj );
	}

	public function getData()
	{
		return (array) $this->data;
	}
}
