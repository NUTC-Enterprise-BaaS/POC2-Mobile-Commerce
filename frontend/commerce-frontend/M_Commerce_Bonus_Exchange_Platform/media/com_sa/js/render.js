var pathname;
pathname= window.location.pathname;
/* all content including images has been loaded */
window.onload = function() {
	var queries = (window.location.search || '?').substr(1).split("&"), params = {};

	/* Convert the array of strings into an object */
	for ( i = 0, l = queries.length; i < l; i++ ) {
		temp = queries[i].split('=');
		params[temp[0]] = temp[1];
	}

	if(params['option'] == 'com_socialads' & params['view'] == 'ads' & params['template'] == 'system' )
	{
		var sbody = document.body,
		html = document.documentElement,
		height = sbody.scrollHeight;
		/*var height = Math.max(sbody.scrollHeight, sbody.offsetHeight,
		html.clientHeight, html.scrollHeight, html.offsetHeight); */

	/*
		console.log( jQuery(html).height() + "PX innerHeight");
		console.log( sbody.scrollHeight +" PX sbody scrollHeight");
		console.log( sbody.offsetHeight +" PX sbody offsetHeight");
		console.log( html.clientHeight +" PX html clientHeight");
		console.log( html.scrollHeight +" PX html. scrollHeight");
		console.log( html.offsetHeight +" PX html offsetHeight");
		console.log("body "+ height +" PX");
	*/

	/* post our message to the parent */
		window.parent.postMessage(
			/* get height of the content */
			height
			/* set target domain */
			,"*"
		)
	}
};

var saRender = {
	ignore: function (el,id,remove){
		jQuery.ajax({
			url: pathname,
			type: 'GET',
			dataType: 'html',
			data : {
				option:'com_socialads',
				task:'track.ignore',
				ignore_id:id
			},
			error: function (xhr, errorType, exception){
				var errorMessage = exception || xhr.statusText;
				alert("There was an error : " + errorMessage);
			},
			success: function(someResponse){
				if(someResponse){
					var el_par;
					if(remove){
						el_par = jQuery(el).closest(".ad_prev_main[preview_for='"+id+"']");
						el_par.hide("slow");
						document.getElementById("feedback"+id).style.display = 'block';
					}
					else{
						el_par = jQuery(el).closest(".ad_prev_main[preview_for='"+id+"']");
						el_par.hide("slow");
					}
				}
			}
		});
	},

	ignoreFeedback: function (el,id){
		var v = el.value ;
		jQuery.ajax({
			url: pathname,
			type: 'GET',
			dataType: 'html',
			data : {
				option:'com_socialads',
				task:'track.ignore',
				ignore_id:id,
				feedback:v
			},
			timeout: 3500,
			error: function (xhr, errorType, exception){
				var errorMessage = exception || xhr.statusText;
				alert("There was an error : " + errorMessage);
			},
			success: function(someResponse){
				if(someResponse){
					var el_par = jQuery(el).parent();
					el_par.hide("slow");
					document.getElementById("feedback_msg"+id).style.display = 'block';
				}
			}
		});
	},

	undoIgnore: function (el,id){
		jQuery.ajax({
			url: pathname,
			type: 'GET',
			dataType: 'html',
			data:{
				option:'com_socialads',
				task:'track.undoIgnore',
				ignore_id:id
			},
			error: function (xhr, errorType, exception){
				var errorMessage = exception || xhr.statusText;
				alert("There was an error : " + errorMessage);
			},
			success: function(someResponse){
				if(someResponse){
					jQuery(el).parent().hide("slow");
					jQuery(el).parent().prev().prev().show("slow");
				}
			}
		});
	}
}
