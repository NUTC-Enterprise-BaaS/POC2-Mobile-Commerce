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
defined('_JEXEC') or die('Unauthorized Access');

class SocialEventAppPhotosHookNotificationComments
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
        $model = FD::model('Comments');
        $users = $model->getParticipants($item->uid, $item->context_type);

        // Include the actor of the stream item as the recipient
        $users = array_merge(array($item->actor_id), $users);

        // Ensure that the values are unique
        $users = array_unique($users);
        $users = array_values($users);

        // Exclude myself from the list of users.
        $index = array_search(FD::user()->id, $users);

        if ($index !== false)
        {
            unset($users[ $index ]);

            $users  = array_values($users);
        }

        // Convert the names to stream-ish
        $names = FD::string()->namesToNotifications($users);

        // Load the comment object since we have the context_ids
        $comment = FD::table('Comments');
        $comment->load($item->uid);

        // When user likes on an album or a event of photos from an album on the stream
        if ($item->context_type == 'albums.event.create') {

            $album = FD::table('Album');
            $album->load($item->context_ids);

            $item->content = $comment->comment;
            $item->image = $album->getCover();

            // We need to determine if the user is the owner
            if ($album->user_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
                $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_YOUR_PHOTO_ALBUM', $names);

                return;
            }

            // For other users, we just post a generic message
            $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_USERS_PHOTO_ALBUM', $names, FD::user($album->user_id)->getName());

            return;
        }

        // If user uploads multiple photos on the stream
        if ($item->context_type == 'stream.event.upload') {

            //Get the stream item object
            $streamItem = FD::table('StreamItem');
            $streamItem->load(array('uid' => $item->context_ids));

            // Get the photo object
            $photo  = FD::table('Photo');
            $photo->load($streamItem->context_id);

            $item->content = '';

            // We could also set an image preview
            $item->image = $photo->getSource();

            // Because we know that this is coming from a stream, we can display a nicer message
            if ($photo->user_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
                $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_YOUR_PHOTO_SHARED_ON_THE_STREAM', $names);

                return;
            }

            // For other users, we just post a generic message
            $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_USERS_PHOTO_SHARED_ON_THE_STREAM', $names, FD::user($photo->user_id)->getName());

            return;
        }

       if ($item->context_type == 'photos.event.updateCover') {

            // Get the photo object
            $photo = FD::table('Photo');
            $photo->load($item->context_ids);

            // Set the photo image
            $item->image = $photo->getSource();
            $item->content = '';


            // We need to determine if the user is the owner
            if ($photo->user_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
                $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_YOUR_PROFILE_COVER', $names);

                return;
            }

            // For other users, we just post a generic message
            $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_USERS_PROFILE_COVER', $names, FD::user($photo->user_id)->getName());

            return;
        }

       if ($item->context_type == 'photos.event.uploadAvatar') {

            // Get the photo object
            $photo = FD::table('Photo');
            $photo->load($item->context_ids);

            // Set the photo image
            $item->image = $photo->getSource();
            $item->content = '';


            // We need to determine if the user is the owner
            if ($photo->user_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
                $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_YOUR_PROFILE_PHOTO', $names);

                return;
            }

            // For other users, we just post a generic message
            $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_USERS_PROFILE_PHOTO', $names, FD::user($photo->user_id)->getName());

            return;
        }

        if ($item->context_type == 'photos.event.upload' || $item->context_type == 'photos.event.add') {

            // Get the photo object
            $photo = FD::table('Photo');
            $photo->load($item->context_ids);

            // Set the photo image
            $item->image = $photo->getSource();

            // Set the comment message
            $item->content = JString::substr($comment->comment, 0, 50) . JText::_('COM_EASYSOCIAL_ELLIPSES');

            // We need to determine if the user is the owner
            if ($photo->user_id == $item->target_id && $item->target_type == SOCIAL_TYPE_USER) {
                $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_YOUR_PHOTO', $names);

                return;
            }

            // For other users, we just post a generic message
            $item->title = JText::sprintf('APP_EVENT_PHOTOS_NOTIFICATIONS_COMMENTED_ON_USERS_PHOTO', $names, FD::user($photo->user_id)->getName());

            return;
        }

        return;
    }

}
