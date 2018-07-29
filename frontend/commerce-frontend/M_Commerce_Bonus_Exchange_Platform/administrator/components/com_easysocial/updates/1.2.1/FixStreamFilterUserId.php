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
defined('_JEXEC') or die('Unauthorized Access');

FD::import('admin:/includes/maintenance/dependencies');

class SocialMaintenanceScriptFixStreamFilterUserId extends SocialMaintenanceScript
{
	public static $title = 'Migrate user id in stream filter table';

	public static $description = 'Migrating user id from column uid into user_id in stream filter table.';

	public function main()
	{
		$db 	= FD::db();
		$sql 	= $db->sql();

		// updating stream filter table.
		$query = "update `#__social_stream_filter` set `user_id` = `uid` where `utype` = 'user'";
		$sql->raw( $query );

		$db->setQuery( $sql );
		$db->query();

		return true;
	}
}
