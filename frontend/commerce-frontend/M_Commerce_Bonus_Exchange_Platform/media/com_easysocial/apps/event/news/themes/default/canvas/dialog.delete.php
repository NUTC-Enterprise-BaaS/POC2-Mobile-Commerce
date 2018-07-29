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
?>
<dialog>
    <width>400</width>
    <height>150</height>
    <selectors type="json">
    {
        "{actionButton}"    : "[data-action-button]",
        "{cancelButton}"    : "[data-cancel-button]",
        "{form}"            : "[data-action-form]"
    }
    </selectors>
    <bindings type="javascript">
    {
        "{cancelButton} click": function()
        {
            this.parent.close();
        },
        "{actionButton} click" : function()
        {
            this.form().submit();
        }
    }
    </bindings>
    <title><?php echo JText::_('APP_EVENT_NEWS_DELETE_NEWS'); ?></title>
    <content>
        <p><?php echo JText::_('APP_EVENT_NEWS_DELETE_NEWS_DESC'); ?></p>

        <form method="post" action="<?php echo JRoute::_('index.php'); ?>" data-action-form>
            <input type="hidden" name="option" value="com_easysocial" />
            <input type="hidden" name="controller" value="apps" />
            <input type="hidden" name="task" value="controller" />
            <input type="hidden" name="appController" value="news" />
            <input type="hidden" name="appTask" value="delete" />
            <input type="hidden" name="appId" value="<?php echo $appId; ?>" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <input type="hidden" name="groupId" value="<?php echo $event->id; ?>" />
            <?php echo $this->html('form.token'); ?>
        </form>
    </content>
    <buttons>
        <button data-cancel-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
        <button data-action-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_('COM_EASYSOCIAL_DELETE_BUTTON'); ?></button>
    </buttons>
</dialog>
