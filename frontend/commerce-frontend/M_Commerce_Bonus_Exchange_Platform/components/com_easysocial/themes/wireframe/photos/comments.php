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
?>
<?php echo FD::comments( $photo->id, SOCIAL_TYPE_PHOTO, 'upload', SOCIAL_APPS_GROUP_USER, array( 'url' => FRoute::photos( array( 'layout' => 'item', 'id' => $photo->id ) ) ) )->getHTML(); ?>
