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

class SocialGroupAppNewsHookNotificationLikes
{
    /**
     * Processes likes notifications
     *
     * @since   1.2
     * @access  public
     * @param   string
     * @return
     */
    public function execute(&$item)
    {
        // Get likes participants
        $model      = FD::model('Likes');
        $users      = $model->getLikerIds($item->uid, $item->context_type);

        // Include the actor of the stream item as the recipient
        $users      = array_merge(array($item->actor_id), $users);

        // Ensure that the values are unique
        $users      = array_unique($users);
        $users      = array_values($users);

        // Exclude myself from the list of users.
        $index      = array_search( FD::user()->id , $users );

        if( $index !== false )
        {
            unset( $users[ $index ] );

            $users  = array_values( $users );
        }

        // Convert the names to stream-ish
        $names  = FD::string()->namesToNotifications($users);

        // When someone likes on the photo that you have uploaded in a group
        if ($item->context_type == 'news.group.create') {

            // We do not want to display any content if the person likes a group announcement
            $item->content     = '';

            // Get the news object
            $news       = FD::table('ClusterNews');
            $news->load($item->uid);

            // Get the group from the stream
            $group      = FD::group($news->cluster_id);

            // Set the content
            if ($group) {
                $item->image    = $group->getAvatar();
            }

            // We need to generate the notification message differently for the author of the item and the recipients of the item.
            if( $news->created_by == $item->target_id && $item->target_type == SOCIAL_TYPE_USER )
            {
                $langString     = FD::string()->computeNoun('APP_GROUP_NEWS_USER_LIKES_YOUR_ANNOUNCEMENT', count($users));
                $item->title    = JText::sprintf($langString, $names, $group->getName());

                return $item;
            }

            // This is for 3rd party viewers
            $langString     = FD::string()->computeNoun('APP_GROUP_NEWS_USER_LIKES_USER_ANNOUNCEMENT', count($users));
            $item->title    = JText::sprintf($langString, $names, FD::user($news->created_by)->getName(), $group->getName());

            return;
        }

        return;
    }

}
