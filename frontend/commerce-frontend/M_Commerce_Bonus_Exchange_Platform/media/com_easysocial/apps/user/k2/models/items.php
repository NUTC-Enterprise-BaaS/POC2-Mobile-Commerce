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

class ItemsModel extends EasySocialModel
{
	/**
	 * Retrieves a list of k2 items created by the user
	 *
	 * @since	1.2
	 * @access	public
	 * @param	int		$userId		The user's / creator's id.
	 *
	 * @return	Array				A list of notes item.
	 */
	public function getItems( $userId , $limit = 0 )
	{
		$db 		= FD::db();
		$sql 		= $db->sql();

		$sql->select('#__k2_items');
		$sql->where('created_by', $userId);
		$sql->where('published', 1);
		$sql->where('trash', 0);

		if( $limit )
		{
			$sql->limit( $limit );
		}

		// Always order by creation date
		$sql->order( 'created' , 'DESC' );

		$db->setQuery( $sql );

		$result	= $db->loadObjectList();

		if (!$result) {
			return $result;
		}

		$this->format($result);
		
		return $result;
	}

	public function format(&$rows)
	{
		$params = JFactory::getApplication()->getParams('com_k2');

		foreach ($rows as &$item) {
			$path = JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_S.jpg';
			$exists = JFile::exists($path);

			$item->image = false;

			if ($exists) {
				
				$item->image = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_S.jpg';
				
				if ($params->get('imageTimestamp')) {
					$item->image .= $timestamp;
				}
			}
		}
	}
}
