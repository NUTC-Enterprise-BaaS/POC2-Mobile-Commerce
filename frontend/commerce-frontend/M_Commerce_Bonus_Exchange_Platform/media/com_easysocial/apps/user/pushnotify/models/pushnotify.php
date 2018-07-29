<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 %COMPANY_NAME%. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* %PACKAGE% is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Import the model file from the core
Foundry::import( 'admin:/includes/model' );


class PushnotifyModel extends EasySocialModel
{
	/**
	 * Retrieves the textbooks stored from the database.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		User's id. 
	 * @return	Array	A list of textbook rowset.
	 */
	public function getItems( $userId )
	{
		// The sample below can be totally ignored and you may use your own ways
		// to retrieve data from the database. In this sample, we used our own database manager.

		// Loads up our very own database object.
		// It's an alias to JFactory::getDBO but it has been beautified with some cool functions.
		/*$db 	= Foundry::db();

		// Load up our own sql query manager
		$sql 	= $db->sql();

		// Retrieve items from the database.
		$sql->select( '#__social_apps_textbooks' );
		$sql->where( 'user_id' , $userId );
		
		$db->setQuery( $sql );

		$result = $db->loadObjectList();

		if( !$result )
		{
			return;
		}

		return $result;*/
		return true;
	}

}
