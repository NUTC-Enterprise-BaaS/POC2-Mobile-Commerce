EasySocial.require()
.script('admin/grid/grid')
.done(function($) {

    // Implement generic grid system
    $('[data-table-grid]').implement(EasySocial.Controller.Grid);

    <?php if ($this->tmpl == 'component') { ?>
        
        $('[data-category-insert]').on('click', function(event) {
            
            event.preventDefault();

            // Supply all the necessary info to the caller
            var element = $(this);
            var data = {
                        "id": element.data('id'),
                        "title" : element.data('title'),
                        "alias" : element.data('alias')
                    };

            window.parent["<?php echo JRequest::getCmd('jscallback');?>" ](data);
        });
        
    <?php } else { ?>
        $.Joomla('submitbutton', function(task) {

            if (task == 'add') {
                window.location = '<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&view=videocategories&layout=form';
                return;
            }

            if (task == 'remove') {

                EasySocial.dialog({
                    content: EasySocial.ajax("admin/views/videocategories/confirmDelete"),
                    bindings: {
                        "{submit} click": function() {
                            $.Joomla('submitform', ['delete']);
                        }
                    }
                });

                return false;
            }

            $.Joomla('submitform', [task]);
        });
    <?php } ?>

});