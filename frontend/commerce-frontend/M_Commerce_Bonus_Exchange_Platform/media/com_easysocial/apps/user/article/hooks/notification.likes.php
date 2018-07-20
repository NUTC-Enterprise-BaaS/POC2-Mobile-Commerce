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

class SocialUserAppArticleHookNotificationLikes
{
    public function execute($item)
    {
        $model = FD::model('likes');
        $users = $model->getLikerIds($item->uid, $item->context_type);

        $users[] = $item->actor_id;

        $users = array_values(array_unique(array_diff($users, array(FD::user()->id))));

        $names = FD::string()->namesToNotifications($users);

        $plurality = count($users) > 1 ? '_PLURAL' : '_SINGULAR';

        list($element, $group, $verb) = explode('.', $item->context_type);

        $streamItem = FD::table('streamitem');
        $state = $streamItem->load(array('context_type' => $element, 'actor_type' => $group, 'verb' => $verb, 'context_id' => $item->uid));

        if (!$state) {
            return;
        }

        $owner = $streamItem->actor_id;

        if ($item->target_type === SOCIAL_TYPE_USER && $item->target_id == $owner) {
            $item->title = JText::sprintf('APP_USER_ARTICLE_USER_LIKES_YOUR_ITEM' . $plurality, $names);

            return $item;
        }

        if ($item->actor_id == $owner && count($users) == 1) {
            $item->title = JText::sprintf('APP_USER_ARTICLE_OWNER_LIKES_ITEM' . FD::user($owner)->getGenderLang(), $names);

            return $item;
        }

        $item->title = JText::sprintf('APP_USER_ARTICLE_USER_LIKES_USER_ITEM' . $plurality, $names, FD::user($owner)->getName());

        return $item;
    }
}
