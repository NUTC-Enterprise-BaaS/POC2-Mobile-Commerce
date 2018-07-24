jQuery(document).ready(function () {

    jQuery('#newticket input[name=\"task\"]').val('attach.process');
    jQuery('#newticket').fileupload({
        url: fss_file_upload_post
    });

    jQuery('#newticket').fileupload('option', {
        maxFileSize: fss_file_upload_max_size,
        autoUpload: true,
        dropZone: jQuery('#dropzone'),
        acceptFileTypes: fss_file_upload_file_types,
    });

    jQuery('#inlinereply input[name=\"task\"]').val('attach.process');
    jQuery('#inlinereply').fileupload({
        url: fss_file_upload_post,
    });

    jQuery('#inlinereply').fileupload('option', {
        maxFileSize: fss_file_upload_max_size,
        autoUpload: true,
        dropZone: jQuery('#dropzone'),
        acceptFileTypes: fss_file_upload_file_types,
    });

    jQuery('#attach_files').disableSelection();
    setInterval('fssUploadUploads();', 500);

});

function fsj_attach_remove(fileid) {
    jQuery('#files_delete').val(jQuery('#files_delete').val() + "|" + fileid + "|");
    jQuery('#fsj_attach_' + fileid).remove();
}

function fsj_attach_update_order() {
    var order = 1;
    jQuery('#attach_files').find('.order').each(function () {
        jQuery(this).val(order);
        order++;
    });
}

var fssUploadUploadsActive = false;

function fssUploadUploads() {
    var upload = 0;

    if (jQuery('#newticket').length > 0)
        upload = jQuery('#newticket').fileupload('active');
    if (jQuery('#inlinereply').length > 0)
        upload = jQuery('#inlinereply').fileupload('active');

    if (upload > 0) {
        if (!fssUploadUploadsActive) {
            fssUploadUploadsActive = true;
            jQuery('#addcomment').attr('disabled', 'disabled');
            jQuery('#replyclose').attr('disabled', 'disabled');
        }
    } else {
        if (fssUploadUploadsActive) {
            fssUploadUploadsActive = false;
            jQuery('#addcomment').removeAttr('disabled');
            jQuery('#replyclose').removeAttr('disabled');

        }
    }
}