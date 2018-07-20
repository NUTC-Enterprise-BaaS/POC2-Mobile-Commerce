function loader(){
		//var msg = '<div id="loader" style="display:none"  class="main_loader_sec"><div class="loader_block">    <div class="loader_block_inner"></div>    <div class="loader_text">Please wait...</div>  </div></div>';
		var browser=navigator.appName;
		if (browser != "Microsoft Internet Explorer")
		{
			jQuery.blockUI({message: '<div id="loader" class="main_loader_sec"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>', css: {top:'40%', left:'45%', width: 'auto'}});
		}
		else
		{
			jQuery(document).ready(function() {
				var IEVersion = getIEVersionNumber();

				if(IEVersion > 7)
				{
					if(varFileNameJS != 'dashboard.php')
						jQuery.blockUI({message: '<div id="loader" class="main_loader_sec"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>',  centerY: true, centerX: true, css: {top:'40%', left:'45%'}});
				}
				/*else
				{
					$.blockUI({message: '<div id="loader" class="main_loader_sec"><div class="loader_block"><div class="loader_block_inner"></div><div class="loader_text">Please wait...</div></div></div>',  centerY: true, centerX: true, css: {top:'30%', left:''} });
				}*/
			});

		}

	    // setTimeout('test()', '7000');
	}


function removeRow(id){
	jQuery('#'+id).remove();
}

function closePopup()	{
	jQuery.unblockUI();
}


function compareVersions (installed, required) {

    var a = installed.split('.');
    var b = required.split('.');

    for (var i = 0; i < a.length; ++i) {
        a[i] = Number(a[i]);
    }
    for (var i = 0; i < b.length; ++i) {
        b[i] = Number(b[i]);
    }
    if (a.length == 2) {
        a[2] = 0;
    }

    if (a[0] > b[0]) return true;
    if (a[0] < b[0]) return false;

    if (a[1] > b[1]) return true;
    if (a[1] < b[1]) return false;

    if (a[2] > b[2]) return true;
    if (a[2] < b[2]) return false;

    return true;
}

