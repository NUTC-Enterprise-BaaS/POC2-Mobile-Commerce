
jQuery(document).ready(function () {
    
    jQuery('.fsj_dual_combo_group').change(function () {
        fsj_dual_combo_update(this);
    })

    jQuery('.fsj_dual_combo_group').each(function () {
        fsj_dual_combo_update(this);
    })
});

function fsj_dual_combo_update(element)
{
    var subcomboid = jQuery(element).attr('id').replace("dsgroup_", "");
    var group = jQuery(element).val();
    jQuery('#' + subcomboid).val("");

    if (group == "")
    {
        // hide combo
        jQuery('#' + subcomboid).hide();
    } else {
        jQuery('#' + subcomboid).show();

        // filter entries
        var $s = jQuery('#' + subcomboid);

        var html = $s.data("originalHTML");

        if (typeof(html) != 'undefined' && html.length > 0) {
            $s.html(html);
        } else {
            $s.data("originalHTML", $s.html());
        }

        jQuery('#' + subcomboid).find("option").each(function () {
            if (jQuery(this).attr("value") == "") return;

            var parts = jQuery(this).attr("value").split("=>");
            
            if (parts[0] == group)
            {
                jQuery(this).show();
            } else {
                jQuery(this).remove();
            }
        })
    }
}