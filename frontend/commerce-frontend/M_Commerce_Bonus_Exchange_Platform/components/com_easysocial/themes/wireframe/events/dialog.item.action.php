<dialog>
    <width>400</width>
    <height>150</height>
    <selectors type="json">
    {
        "{closeButton}": "[data-close-button]",
        "{submitButton}": "[data-submit-button]",
        "{form}": "[data-form]"
    }
    </selectors>
    <bindings type="javascript">
    {
        "{closeButton} click": function()
        {
            this.parent.close();
        },
        "{submitButton} click" : function()
        {
            this.form().submit();
        }
    }
    </bindings>
    <title><?php echo JText::_('COM_EASYSOCIAL_EVENTS_DIALOG_' . strtoupper($action) . '_EVENT_TITLE'); ?></title>
    <content>
        <p><?php echo JText::sprintf('COM_EASYSOCIAL_EVENTS_DIALOG_' . strtoupper($action) . '_EVENT_CONTENT', $event->getName());?></p>

        <form data-form method="post" action="<?php echo JRoute::_('index.php');?>">
            <input type="hidden" name="id" value="<?php echo $event->id;?>" />
            <input type="hidden" name="controller" value="events" />
            <input type="hidden" name="task" value="itemAction" />
            <input type="hidden" name="action" value="<?php echo $action; ?>" />
            <input type="hidden" name="from" value="<?php echo $from; ?>" />
            <?php echo $this->html('form.token'); ?>
        </form>
    </content>
    <buttons>
        <button data-close-button type="button" class="btn btn-es btn-sm"><?php echo JText::_('COM_EASYSOCIAL_CLOSE_BUTTON'); ?></button>
        <button data-submit-button type="button" class="btn btn-sm <?php echo $buttonclass; ?>"><?php echo $buttonlabel; ?></button>
    </buttons>
</dialog>
