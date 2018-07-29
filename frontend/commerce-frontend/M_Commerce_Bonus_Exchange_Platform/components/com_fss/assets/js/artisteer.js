/* Fix the mess that artisteer makes of Freestyle Supprot Portal */

jQuery(document).ready(function () {
    
    setTimeout("fss_fix_artisteer();", 250);
    setTimeout("fss_fix_artisteer();", 500);
    setTimeout("fss_fix_artisteer();", 1000);
    setTimeout("fss_fix_artisteer();", 2000);
    setTimeout("fss_fix_artisteer();", 4000);
    fss_fix_artisteer();
})

function fss_fix_artisteer()
{
    try {
        jQuery('.fss_main .art-button').removeClass('art-button');
    } catch (e) {
    }
}