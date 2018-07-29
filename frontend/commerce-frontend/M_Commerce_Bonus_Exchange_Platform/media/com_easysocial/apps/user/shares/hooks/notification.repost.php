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

class SocialUserAppSharesHookNotificationRepost
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
        // We know that all repost notifications is targeted to the owner of the previous item that is currently being reposted.
        $actor  = FD::user($item->actor_id);

        // Load up the share item
        $share  = FD::table('Share');
        $share->load($item->context_ids);

        $item->title    = JText::sprintf('APP_USER_SHARES_USER_REPOSTED_YOUR_POST', $actor->getName());
    }

}
