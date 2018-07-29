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
<?php echo JText::sprintf( 'APP_USER_CALENDAR_UPDATED_EVENT_' . $gender,
		$this->html( 'html.user' , $actor->id ) ,
		'<a href="' . FRoute::apps( array( 'layout' => 'canvas' , 'id' => $app->getAlias() , 'uid' => $actor->getAlias() , 'type' => SOCIAL_TYPE_USER , 'customView' => 'item' , 'schedule_id' => $calendar->id ) ) . '">' . $calendar->get( 'title' ) . '</a>',
		'<a href="' . FRoute::apps( array( 'layout' => 'canvas' , 'id' => $app->getAlias() , 'uid' => $actor->getAlias() , 'type' => SOCIAL_TYPE_USER ) ) . '">' . JText::_( 'APP_CALENDAR_STREAM_CALENDAR' ) . '</a>'
	); ?>
