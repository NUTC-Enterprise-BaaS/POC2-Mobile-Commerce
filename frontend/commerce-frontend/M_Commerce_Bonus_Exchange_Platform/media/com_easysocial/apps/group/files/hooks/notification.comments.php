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

class SocialGroupAppFilesHookNotificationComments
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
        // Get comment participants
        $model      = FD::model( 'Comments' );
        $users      = $model->getParticipants($item->uid, $item->context_type);

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
        if ($item->context_type == 'files.group.uploaded') {

            $file       = FD::table('File');
            $file->load($item->uid);

            // Get the group from the stream
            $group      = FD::group($file->uid);

            // Set the content
            if ($file->hasPreview()) {
                $item->image    = $file->getPreviewURI();
            }

            // We need to get the comment that is tied to this stream
            if (count($users) == 1) {

                $comment        = FD::table('Comments');
                $comment->load(array('element' => 'files.group.uploaded', 'uid' => $item->uid));

                $item->content  = $comment->comment;
            }

            // We need to generate the notification message differently for the author of the item and the recipients of the item.
            if ($file->user_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
                $item->title    = JText::sprintf('APP_GROUP_FILES_USER_COMMENTED_ON_YOUR_FILE', $names, $group->getName());

                return $item;
            }

            // This is for 3rd party viewers
            $item->title    = JText::sprintf('APP_GROUP_FILES_USER_COMMENTED_ON_USERS_FILE', $names, FD::user($file->user_id)->getName(), $group->getName());

            return;
        }

        return;
    }

}
