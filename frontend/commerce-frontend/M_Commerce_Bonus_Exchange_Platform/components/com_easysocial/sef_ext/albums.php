<?php
/**
* @package    EasySocial
* @copyright  Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Determine how is the user's current id being set.
if (isset($userid)) {
	$title[] = getUserAlias($userid);

	shRemoveFromGETVarsList( 'userid' );
}

// Add the view to the list of titles
if (isset($view)) {
	addView($title, $view);
}

if (isset($type)) {

	$title[] = JString::ucwords(JText::_('COM_EASYSOCIAL_SH404_ALBUMS_TYPE_' . strtoupper($type)));

	if (isset($uid)) {

		if ($type == SOCIAL_TYPE_USER) {
			$alias = getUserAlias($uid);
		}

		if ($type == SOCIAL_TYPE_GROUP) {
			$alias = getGroupAlias($uid);
		} 

		if ($type == SOCIAL_TYPE_EVENT) {
			$alias = getEventAlias($uid);
		}

		$title[] = $alias;

		shRemoveFromGETVarsList('uid');
	}
	shRemoveFromGETVarsList('type');
}

// For photos, we need to get the beautiful title
if (isset($id)) {
	$id = (int) $id;

	$album = ES::table('Album');
	$album->load($id);

	$albumTitle = $album->core ? JText::_($album->title) : $album->title;
	$title[] = JFilterOutput::stringURLSafe($albumTitle);

	shRemoveFromGETVarsList('id');
	shRemoveFromGETVarsList('layout');
}
