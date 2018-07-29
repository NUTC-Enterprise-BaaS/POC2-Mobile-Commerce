/* Call this function when click on CSV Export button and call ajax for every limit set on view
 * limitStart is start position of record
 * fileName is CSV file name
 * divId is Id to append progress bar
 */
var tjexport = {
	exportCsv: function(limitStart, fileName, divId){

		if (divId == '' || typeof(divId) == 'undefined')
		{
			divId = 'adminForm';
		}

		if (limitStart == 0)
		{
			tjexport.showProgressBar(divId);
			tjexport.displayNotice(divId,'info',csv_export_inprogress);
		}

		data = JSON.stringify({"limit_start":limitStart, "file_name":fileName});
		tjexport.updateLoader(Math.round((limitStart * 100)/ 100000));

		jQuery.ajax({
		url: csv_export_url,
		type: 'POST',
		data: data,
		dataType : 'JSON',
		success: function(response){
				if (response['limit_start'] == response['total'])
				{
					console.log(response['limit_start']);
					console.log(response['total']);
					location.href = response['download_file'];
					tjexport.displayNotice(divId,'success',csv_export_success);
					tjexport.hideProgressBar(divId);
					console.log("Download Successfully.");
				}
				else
				{
					tjexport.exportCsv(response['limit_start'], response['file_name']);
				}

				tjexport.updateLoader(Math.round((response['limit_start'] * 100)/ response['total']));
			},
		error: function(xhr, status, error) {
				tjexport.displayNotice(divId,'error', csv_export_error);
				console.log("Something went wrong.");
			}
		});
	},
	displayNotice:function(divId, alert, message){
		jQuery('#'+divId).children('.alert').remove();
		jQuery('#'+divId).prepend("<div class='center alert alert-"+alert+"'>"+message+"</div>");
	},
	showProgressBar:function(divId){
		jQuery('#'+divId).prepend("<div class='progress progress-striped active'><div class='bar'></div></div>");
	},
	updateLoader:function(percent){
		jQuery(".progress .bar").css("width", percent+'%');
		jQuery(".progress .bar").text(percent+'%');
	},
	hideProgressBar:function(divId){
		jQuery('#'+divId).children('.progress').remove();
	}
}
