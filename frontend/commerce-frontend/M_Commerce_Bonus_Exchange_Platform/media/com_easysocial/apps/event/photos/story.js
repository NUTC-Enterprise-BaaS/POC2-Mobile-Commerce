EasySocial.require()
    .script("story/photos")
    .done(function($) {

        var plugin =
            story.addPlugin("photos", {
                uploader: {
                    settings: {
                        url: "<?php echo FRoute::raw('index.php?option=com_easysocial&controller=photos&task=uploadStory&uid=' . $event->id . '&type=' . SOCIAL_TYPE_EVENT . '&format=json&tmpl=component&' . FD::token() . '=1'); ?>",
                        max_file_size: "<?php echo $maxFileSize; ?>",
                        camera: "image"
                    }
                }
            });
    });
