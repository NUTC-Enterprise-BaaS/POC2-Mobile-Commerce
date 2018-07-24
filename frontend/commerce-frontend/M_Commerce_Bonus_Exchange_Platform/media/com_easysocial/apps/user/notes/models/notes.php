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

class NotesModel extends EasySocialModel
{
	/**
	 * Retrieves a list of notes created by a particular user.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		$userId		The user's / creator's id.
	 *
	 * @return	Array				A list of notes item.
	 */
	public function getItems( $userId )
	{
		$db 		= FD::db();

		// Get sql helper.
		$sql 		= $db->sql();

		// Select the table.
		$sql->select( '#__social_notes' );

		// Add conditions.
		$sql->where( 'user_id' , $userId );

		// Build the ordering.
		$sql->order( 'created' , 'DESC');

		// Execute the query.
		$db->setQuery( $sql );

		// Get the result.
		$notes		= $db->loadObjectList();

		return $notes;
	}

}
