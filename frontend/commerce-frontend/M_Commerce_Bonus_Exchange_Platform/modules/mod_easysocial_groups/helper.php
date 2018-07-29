<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

class EasySocialModGroupsHelper
{
	public static function getGroups(&$params)
	{
		$my = FD::user();
		$model = FD::model('Groups');

		// Determine filter type
		$filter = $params->get('filter', 0);

		// Determine the ordering of the groups
		$ordering = $params->get('ordering', 'latest');

		// Default options
		$options = array();

		// Limit the number of groups based on the params
		$options['limit'] = $params->get('display_limit', 5);
		$options['ordering'] = $ordering;
		$options['state'] = SOCIAL_STATE_PUBLISHED;
		$options['inclusion'] = $params->get('group_inclusion');

		if ($filter == 0) {
			$groups = $model->getGroups( $options );
		}

		if ($filter == 1) {
			$category 	= trim( $params->get( 'category' ) );

			if (!$category) {
				return array();
			}

			// Since category id's are stored as ID:alias, we only want the id portion
			$category = explode(':', $category);

			$options['category'] = $category[0];

			$groups = $model->getGroups($options);
		}

		// Featured groups only
		if ($filter == 2) {
			$options['featured'] = true;

			$groups = $model->getGroups($options);
		}

		// Groups from logged in user
		if ($filter == 3) {
			$options['types'] = 'currentuser';
			$options['userid'] = $my->id;
			$groups = $model->getGroups($options);
		}

		return $groups;
	}
}
