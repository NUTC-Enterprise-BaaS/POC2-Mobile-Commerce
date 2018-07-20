<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

class SocialUserAppStoryHookNotificationMentions
{
    /**
     * Processes likes notifications
     *
     * @since   1.2
     * @access  public
     * @param   string
     * @return
     */
    public function execute(SocialTableNotification &$item)
    {
        // Get the actor
        $actor      = FD::user( $item->actor_id );

        // We need to reload the content to ensure that we get the raw data
        $table      = FD::table( 'StreamItem' );
        $table->load($item->uid);

        $stream     = FD::table( 'Stream' );
        $stream->load($table->uid);

        // Get the content from the stream table.
        $item->content      = JString::substr($stream->content, 0, 100) . JText::_('COM_EASYSOCIAL_ELLIPSES');
        $item->title        = JText::sprintf( 'APP_USER_STORY_NOTIFICATIONS_USER_TAGGED_YOU_IN_A_POST' , $actor->getName() );
    }
}
