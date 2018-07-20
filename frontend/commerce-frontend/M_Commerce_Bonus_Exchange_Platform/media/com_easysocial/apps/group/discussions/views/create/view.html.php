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

/**
 * Displays the canvas view for news app
 *
 * @since   1.2
 * @access  public
 */
class DiscussionsViewCreate extends SocialAppsView
{
    /**
     * Displays the application output in the canvas.
     *
     * @since   1.0
     * @access  public
     * @param   int     The user id that is currently being viewed.
     */
    public function display($uid = null , $docType = null)
    {
        $group = FD::group($uid);

        if (!$group->isMember() && !$this->my->isSiteAdmin()) {
            FD::info()->set(false, JText::_('COM_EASYSOCIAL_GROUPS_ONLY_MEMBER_ARE_ALLOWED'), SOCIAL_MSG_ERROR);
            return $this->redirect($group->getPermalink(false));
        }

        // Set the page title
        FD::page()->title(JText::_('APP_GROUP_DISCUSSIONS_PAGE_TITLE_CREATE'));

        // Get the discussion item
        $discussion = FD::table('Discussion');

        // Determines if we should allow file sharing
        $access = $group->getAccess();
        $files = $access->get('files.enabled', true);

        $this->set('files', $files);
        $this->set('discussion', $discussion);
        $this->set('group', $group);

        echo parent::display('canvas/form');
    }
}
