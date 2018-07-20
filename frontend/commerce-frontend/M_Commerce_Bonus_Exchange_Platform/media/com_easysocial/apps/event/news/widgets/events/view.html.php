<?php
/**
* @package        EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license        GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

/**
 * Widgets for group
 *
 * @since    1.0
 * @access    public
 */
class NewsWidgetsEvents extends SocialAppsWidgets
{
    /**
     * Display admin actions for the event
     *
     * @since   1.2
     * @access  public
     * @param   string
     * @return
     */
    public function eventAdminStart($event)
    {
        if ($this->app->state == SOCIAL_STATE_UNPUBLISHED) {
            return;
        }

        if (!$event->getParams()->get('news', true)) {
            return;
        }

        $theme = FD::themes();
        $theme->set('app', $this->app);
        $theme->set('event', $event);

        echo $theme->output('themes:/apps/event/news/widgets/widget.menu');
    }

    /**
     * Display user photos on the side bar
     *
     * @since    1.0
     * @access    public
     * @param    string
     * @return
     */
    public function sidebarBottom($eventId)
    {
        // Set the max length of the item
        $params = $this->app->getParams();
        $enabled = $params->get('widget', true);

        if (!$enabled) {
            return;
        }

        // Get the event
        $event = FD::event($eventId);

        if (!$event->getParams()->get('news', true)) {
            return;
        }

        $options = array('limit' => (int) $params->get('widget_total', 5));

        $model = FD::model('Events');
        $items = $model->getNews($event->id, $options);

        $theme = FD::themes();
        $theme->set('event', $event);
        $theme->set('app', $this->app);
        $theme->set('items', $items);

        echo $theme->output('themes:/apps/event/news/widgets/widget.news');
    }
}
