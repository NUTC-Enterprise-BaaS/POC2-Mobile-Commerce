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
?>
<dialog>
    <width>500</width>
    <height>150</height>
    <selectors type="json">
    {
        "{closeButton}": "[data-close-button]",
        "{deleteAllButton}": "[data-delete-all-button]",
        "{deleteThisButton}": "[data-delete-this-button]",
        "{form}": "[data-form]",
        "{deleteMode}": "[data-delete-mode]"
    }
    </selectors>
    <bindings type="javascript">
    {
        "{closeButton} click": function()
        {
            this.parent.close();
        },
        "{deleteAllButton} click": function()
        {
            this.deleteMode().val('all');
            this.form().submit();
        },
        "{deleteThisButton} click": function()
        {
            this.deleteMode().val('this');
            this.form().submit();
        }
    }
    </bindings>
    <title><?php echo JText::_('COM_EASYSOCIAL_EVENTS_DIALOG_DELETE_RECURRING_EVENT_TITLE'); ?></title>
    <content>
        <p><?php echo JText::sprintf('COM_EASYSOCIAL_EVENTS_DIALOG_DELETE_RECURRING_EVENT_CONTENT', $event->getName());?></p>
        <form data-form method="post" action="<?php echo JRoute::_('index.php'); ?>">
            <input type="hidden" name="option" value="com_easysocial" />
            <input type="hidden" name="controller" value="events" />
            <input type="hidden" name="task" value="itemAction" />
            <input type="hidden" name="action" value="delete" />
            <input type="hidden" name="id" value="<?php echo $event->id; ?>" />
            <input type="hidden" name="deleteMode" data-delete-mode value="this" />
            <?php echo JHTML::_('form.token'); ?>
        </form>
    </content>
    <buttons>
        <button data-close-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CLOSE_BUTTON'); ?></button>
        <button data-delete-all-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_('COM_EASYSOCIAL_DELETE_THIS_AND_RECURRING_EVENTS_BUTTON'); ?></button>
        <button data-delete-this-button type="button" class="btn btn-es-danger btn-sm"><?php echo JText::_('COM_EASYSOCIAL_DELETE_THIS_EVENT_BUTTON'); ?></button>
    </buttons>
</dialog>

