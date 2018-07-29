jQuery(document).ready(function () {
    jQuery('.fss_glossary_word').each(function () {
        // set up title attribute for glossary
        var ref = jQuery(this).attr('ref');
        
        var lookedup = jQuery('#glossary_' + ref);

        jQuery(this).removeAttr('title');
        if (lookedup.length > 0) {
            jQuery(this).attr('data-original-title', lookedup.html());
        } else {
            jQuery(this).attr('data-original-title', "Unable to find " + '#glossary_' + ref);
        }
        jQuery(this).addClass('glossaryTip');
    });
    jQuery('#glossary_words').remove();

    if (jQuery.fn.fss_tooltip) {
        jQuery('.glossaryTip').fss_tooltip({ width: '400px' });
    }
});