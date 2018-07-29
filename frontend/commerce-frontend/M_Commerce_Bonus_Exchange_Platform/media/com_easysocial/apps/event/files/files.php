<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class SocialEventAppFiles extends SocialAppItem
{
    /**
     * Responsible to return the favicon object
     *
     * @since    1.2
     * @access    public
     * @param    string
     * @return
     */
    public function getFavIcon()
    {
        $obj = new stdClass();
        $obj->color = '#2969B0';
        $obj->icon = 'fa-files-o';
        $obj->label = 'COM_EASYSOCIAL_STREAM_CONTEXT_TITLE_FILES_TOOLTIP';

        return $obj;
    }

    /**
     * Determines if the app should be displayed in the list
     *
     * @since    1.2
     * @access    public
     * @param    string
     * @return
     */
    public function appListing($view, $id, $type)
    {
        $event = FD::event($id);

        // Determines if this event has access to files
        $access = $event->getAccess();

        if (!$access->get('files.enabled', true)) {
            return false;
        }

        $guest = $event->getGuest(FD::user()->id);

        if ($guest->isGuest()) {
            return true;
        }

        return false;
    }

    /**
     * Processes notifications for files
     *
     * @since    1.2
     * @access    public
     * @param    string
     * @return
     */
    public function onNotificationLoad(&$item)
    {
        $allowed = array('files.event.uploaded');

        if (!in_array($item->context_type, $allowed)) {
            return;
        }

        $hook = $this->getHook('notification', $item->type);
        $hook->execute($item);
        return;
    }

    /**
     * Processes when user likes a file
     *
     * @since    1.2
     * @access    public
     * @param    string
     * @return
     */
    public function onAfterLikeSave(&$likes)
    {
        $allowed = array('files.event.uploaded');

        if (!in_array($likes->type, $allowed)) {
            return;
        }

        // Set the default element.
        $uid = $likes->uid;
        $data = explode('.', $likes->type);
        $element = $data[0];
        $verb = $data[2];

        // Get the owner of the post.
        $stream = FD::table('Stream');
        $stream->load($likes->stream_id);

        // Since we have the stream, we can get the event id
        $event = FD::event($stream->cluster_id);

        // Get the actor
        $actor = FD::user($likes->created_by);

        $emailOptions = array(
            'title' => 'APP_EVENT_FILES_EMAILS_LIKE_ITEM_SUBJECT',
            'template' => 'apps/event/files/like.file.item',
            'permalink' => $stream->getPermalink(true, true),
            'actor' => $actor->getName(),
            'event' => $event->getName(),
            'actorAvatar' => $actor->getAvatar(SOCIAL_AVATAR_SQUARE),
            'actorLink' => $actor->getPermalink(true, true)
       );

        $systemOptions = array(
            'context_type' => $likes->type,
            'context_ids' => $stream->cluster_id,
            'url' => $stream->getPermalink(false, false, false),
            'actor_id' => $likes->created_by,
            'uid' => $likes->uid,
            'aggregate' => true
       );

        // Notify the owner first
        if ($likes->created_by != $stream->actor_id) {
            FD::notify('likes.item', array($stream->actor_id), $emailOptions, $systemOptions);
        }

        // Get a list of recipients to be notified for this stream item
        // We exclude the owner of the note and the actor of the like here
        $recipients = $this->getStreamNotificationTargets($likes->uid, $element, 'event', $verb, array(), array($stream->actor_id, $likes->created_by));

        $emailOptions['title'] = 'APP_EVENT_FILES_EMAILS_LIKE_INVOLVED_SUBJECT';
        $emailOptions['template'] = 'apps/event/files/like.file.involved';

        // Notify other participating users
        FD::notify('likes.involved', $recipients, $emailOptions, $systemOptions);

        return;
    }

    /**
     * Processes when user comments on a file
     *
     * @since    1.2
     * @access    public
     * @param    string
     * @return
     */
    public function onAfterCommentSave(&$comment)
    {
        $allowed = array('files.event.uploaded');

        if (!in_array($comment->element, $allowed)) {
            return;
        }

        // Get the stream object
        $stream = FD::table('Stream');
        $stream->load($comment->uid);

        $segments = explode('.', $comment->element);
        $element = $segments[0];
        $verb = $segments[2];

        // Load up the stream object
        $stream = FD::table('Stream');
        $stream->load($comment->stream_id);

        // Get the event object
        $event = FD::event($stream->cluster_id);

        // Get the comment actor
        $actor = FD::user($comment->created_by);

        $emailOptions = array(
            'title' => 'APP_EVENT_FILES_EMAILS_COMMENT_ITEM_SUBJECT',
            'template' => 'apps/event/files/comment.file.item',
            'comment' => $comment->comment,
            'event' => $event->getName(),
            'permalink' => $stream->getPermalink(true, true),
            'actor' => $actor->getName(),
            'actorAvatar' => $actor->getAvatar(SOCIAL_AVATAR_SQUARE),
            'actorLink' => $actor->getPermalink(true, true)
       );

        $systemOptions = array(
            'content' => $comment->comment,
            'context_type' => $comment->element,
            'context_ids' => $stream->cluster_id,
            'url' => $stream->getPermalink(false, false, false),
            'actor_id' => $comment->created_by,
            'uid' => $comment->uid,
            'aggregate' => true
       );


        // Notify the note owner
        if ($comment->created_by != $stream->actor_id) {
            FD::notify('comments.item', array($stream->actor_id), $emailOptions, $systemOptions);
        }

        // Get a list of recipients to be notified for this stream item.
        // We exclude the owner of the note and the actor of the like here
        $recipients = $this->getStreamNotificationTargets($comment->uid, $element, 'event', $verb, array(), array($stream->actor_id, $comment->created_by));

        $emailOptions['title'] = 'APP_EVENT_FILES_EMAILS_COMMENT_INVOLVED_SUBJECT';
        $emailOptions['template'] = 'apps/event/files/comment.file.involved';

        // Notify participating users
        FD::notify('comments.involved', $recipients, $emailOptions, $systemOptions);

        return;
    }

    /**
     * Prepares the stream item
     *
     * @since    1.0
     * @access    public
     * @param    SocialStreamItem    The stream object.
     * @param    bool                Determines if we should respect the privacy
     */
    public function onPrepareStream(SocialStreamItem &$item, $includePrivacy = true)
    {
        if ($item->context != SOCIAL_TYPE_FILES) {
            return;
        }

        // Event access checking
        $event = FD::event($item->cluster_id);

        if (!$event || !$event->canViewItem()) {
            return;
        }

        // Define standard stream looks
        $item->display = SOCIAL_STREAM_DISPLAY_FULL;
        $item->color = '#2969B0';
        $item->fonticon = 'fa fa-files-o';
        $item->label = FD::_('COM_EASYSOCIAL_STREAM_CONTEXT_TITLE_FILES_TOOLTIP', true);

        if ($item->verb == 'uploaded') {
            $this->prepareUploadedStream($item);
        }
    }

    /**
     * Prepares the stream item for new file uploads
     *
     * @since    1.0
     * @access    public
     * @param    SocialStreamItem    The stream item.
     * @return
     */
    private function prepareUploadedStream(&$item)
    {
        $params = FD::registry($item->params);

        // Load up the event object
        $event = FD::event($params->get('event')->id);

        // Do not allow user to repost files
        $item->repost = false;

        // Try to get the file object
        $obj = $params->get('file');

        // Default variables
        $content = '';
        $files = array();

        if (is_object($obj)) {

            // Get the file object
            $file = FD::table('File');
            $file->load($params->get('file')->id);

            if (!$file->id) {
                return;
            }

            $files[] = $file;

        } else {
            $params = FD::registry($item->contextParams[0]);
            $fileItems = $params->get('file');
            $content = $item->content;

            foreach ($fileItems as $fileId) {
                $file = FD::table('File');
                $file->load((int) $fileId);

                $files[] = $file;
            }


        }

        // Apply likes on the stream
        $likes = FD::likes();
        $likes->get($item->uid , $item->context, $item->verb, SOCIAL_APPS_GROUP_EVENT, $item->uid);
        $item->likes = $likes;

        // Get the actor
        $actor = $item->actor;

        $this->set('content', $content);
        $this->set('actor', $actor);
        $this->set('files', $files);
        $this->set('event', $event);

        // Load up the contents now.
        $item->title = parent::display('streams/uploaded.title');
        $item->content = parent::display('streams/uploaded.content');
    }

    /**
     * Prepares what should appear in the story form.
     *
     * @since   1.3
     * @access  public
     * @param   string
     * @return
     */
    public function onPrepareStoryPanel($story)
    {
        $params = $this->getParams();

        // Determine if the user can use this feature
        if (!$params->get('enable_uploads', true)) {
            return;
        }

        // Get the event object
        $event = FD::event($story->cluster);

        // Determines if this event has access to files
        $access = $event->getAccess();

        if (!$access->get('files.enabled', true)) {
            return;
        }

        // Get the guest
        $guest = $event->getGuest($this->my->id);

        if (!$guest->isGuest()) {
            return;
        }

        // Create plugin object
        $plugin = $story->createPlugin('files', 'panel');

        // Get the allowed extensions
        $allowedExtensions = $params->get('allowed_extensions', 'zip,txt,pdf,gz,php,doc,docx,ppt,xls');
        $maxFileSize = $params->get('max_upload_size', 8) . 'M';

        // We need to attach the button to the story panel
        $theme  = FD::themes();

        $plugin->button->html = $theme->output('themes:/apps/user/files/story/panel.button');
        $plugin->content->html = $theme->output('themes:/apps/user/files/story/panel.content');

        // Attachment script
        $script = FD::script();
        $script->set('allowedExtensions', $allowedExtensions);
        $script->set('maxFileSize', $maxFileSize);
        $script->set('type', SOCIAL_TYPE_EVENT);
        $script->set('uid', $story->cluster);

        $plugin->script = $script->output('apps:/user/files/story');

        return $plugin;
    }

    /**
     * Processes after the story is saved so that we can generate a stream item for this
     *
     * @since   1.3
     * @access  public
     * @param   string
     * @return
     */
    public function onAfterStorySave(SocialStream &$stream , SocialTableStreamItem $streamItem, &$template)
    {
        $files = $this->input->get('files', array(), 'array');

        if (!$files) {
            return;
        }

        // We need to set the context id's for the files shared in this stream.
        $params = FD::registry();
        $params->set('file', $files);

        $streamItem->verb = 'uploaded';
        $streamItem->params = $params->toString();
        $streamItem->store();
    }
}
