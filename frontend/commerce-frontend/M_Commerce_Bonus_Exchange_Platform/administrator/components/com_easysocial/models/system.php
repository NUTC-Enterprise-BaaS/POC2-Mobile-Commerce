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

class EasySocialModelSystem extends EasySocialModel
{
	private $data			= null;

	public function __construct( $config = array() )
	{
		parent::__construct( 'system' , $config );
	}

	public function getMenus()
	{
		$db 	= FD::db();
		$sql 	= $db->sql();

		$sql->select( '#__menu_types' );
		$sql->column( 'menutype' );
		$sql->column( 'title' );

		$db->setQuery( $sql );

		$menus 	= $db->loadObjectList();

		return $menus;
	}
}
