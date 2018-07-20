function createStatusbar(obj )
{
	this.statusbar = jQuery("<div class='statusbar'></div>");
	this.showfiles = jQuery("<div class='ShowFiles'></div>").appendTo(this.statusbar);
	this.filename = jQuery("<div class='filename'></div>").appendTo(this.statusbar);
	// this.filetype = jQuery("<div class='filetype'></div>").appendTo(this.statusbar);
	this.size = jQuery("<div class='filesize'></div>").appendTo(this.statusbar);
	this.msg = jQuery('<div class="msg alert"></div>').appendTo(this.statusbar);
	//this.progressBar = jQuery('<div class="progress"><div class="progress-bar progress-bar-uploading"><span class="progress_bar_text">Uploading <span class="progress_per"></span></div></div>').appendTo(this.statusbar);
	//this.abort = jQuery("<div class='abort'>Abort</div>").appendTo(this.statusbar);
	//this.processBar = jQuery('<div class="process"><div class="progress-bar-processing"><span class="process_bar_text">Processing <span class="process_per"></span></div></div>').appendTo(this.statusbar);
	//this.processBarStatus = jQuery('<div class="process_done alert alert-success"></div>').appendTo(this.statusbar);

	obj.closest('.submission').append(this.statusbar);

	this.setFileNameSize = function(name,size)
	{
		var sizeStr="";
		var sizeKB = size/1024;
		if(parseInt(sizeKB) > 1024)
		{
			var sizeMB = sizeKB/1024;
			sizeStr = sizeMB.toFixed(2)+" MB";
		}
		else
		{
			sizeStr = sizeKB.toFixed(2)+" KB";
		}

		this.filename.html(name);
		this.size.html(sizeStr);
	}
	this.setMsg = function(msg,classname)
	{
		this.statusbar.show();
		//this.progressBar.hide();
		this.msg.attr('class','msg alert');
		this.msg.addClass(classname);
		this.msg.html(msg);
		this.msg.show();
	}
	this.setFileName = function(name)
	{
		this.filename.html(name);
	}
	this.setShowFiles = function(name)
	{
		this.showfiles.html(name);
	}
}


function startImporting(file,status,thisfile,ref,lesson_id)
{
	if(file == undefined)
	{
		status.setMsg(file_not_selected_error,'alert-error');
		return false;
	}

	var filename = file.name;

	if(window.FormData !== undefined)  // for HTML5 browsers
	{
		status.setMsg('Validating file...');

		var newfilename = sendFileToServer(file,status,thisfile,ref,lesson_id);

		return false;

	}
   else  //for older browsers
	{
		alert("You need to upgrade your browser as it does not support FormData");
	}
}

/*
 * filename = namne of the file
 * lesson_id =  id of the lesson against which we are uploading file
 * formData =  Formdata object- file-format-and lessonid
 * format_lesson_form =  the form in which this uploading is going on
 * format = format
 * subformat = pugin of selected format type
 * fileinputtag = the <input type=file>
 * */
function sendFileToServer(file,status,fileinputtag, ref, lesson_id)
{

	var formData = new FormData();
	formData.append( 'FileInput', file );

	var returnvar	= true;
	var jqXHR = jQuery.ajax({
		 xhr: function() {
			var xhrobj = jQuery.ajaxSettings.xhr();
			if (xhrobj.upload) {
				xhrobj.upload.addEventListener('progress', function(event) {
					var percent = 0;
					var position = event.loaded || event.position;
					var total = event.total;
					if (event.lengthComputable) {
						percent = Math.ceil(position / total * 100);
					}
					//status.setProgress(percent);
				}, false);
			}
			return xhrobj;
		},
		/*url: 'index.php?option=com_ideas&task=fileupload.processupload&ref='+ref,*/
		//url: 'index.php?option=com_tjlms&task=callSysPlgin&plgType=tjassignment&plgName=submission&plgtask=processupload&callType=0&ref='+ref,

		url: 'index.php?option=com_acmanager&task=manageioscertificatess.processupload',
		type: 'POST',
		data:  formData,
		mimeType:"multipart/form-data",
		contentType: false,
		dataType:'json',
		cache: false,
		processData:false,
		success: function(response)
		{

			//console.log(response);
			//var output = response['OUTPUT'];
			var output = response;
			var result	=	output['flag'];

			if(result == 0)
			{
				status.setMsg(output['msg'],'alert-error');
			}
			if(result == 1)
			{
				//console.log(output['filename']);
				/* File uploading on local is done*/
				//status.setProgress(100);
				status.setMsg(output['msg'],'alert-success');
				status.setFileName(output['filename']);
				// append hidden files
				jQuery('.files').append('<div class="row-fluid alert-info" id="sub_upload'+output['media_id']+'"><div class="pull-left">'+output['ShowFiles']+'</div><div class="pull-right"><i onclick="removeSubFiles(\'sub_upload'+output['media_id']+'\',\''+output['media_id']+'\')" title="Remove this file" class="icon-remove"></i></div></div>');
				jQuery('.files').append(output['filename']);
				jQuery('#idea_image').val('');
				jQuery('.upload-progress').css('display','none');
				jQuery('.upload-msg').css('display','block');

				status.setShowFiles(output['ShowFiles']);
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			status.setMsg(jqXHR.responseText,'alert-error');
			returnvar	= false;
		}
   });
	return returnvar;
	status.setAbort(jqXHR);

}

function removeSubFiles(div_id, media_id)
{
	var removeConfirm = confirm("Do you really want to remove this file?");

	if (removeConfirm == 1)
	{
		techjoomla.jQuery.ajax({
			url: 'index.php?option=com_tjlms&task=assignment.removeSubFiles&media_id='+media_id,
			datatype:'json',
			success: function(data)
			{

				if (data == 1)
				{
					techjoomla.jQuery('#'+div_id).remove();
				}
				else
				{
					alert("Some error occured.");
				}
			},
			error: function()
			{
				alert("Some error occured.");
			}
		});
	}
	else
	{
		return false;
	}
}

/*Function to check if a file with valid extension has been uploaded for assignment*/
function validate_file(thisfile,ref,lesson_id)
{
	jQuery('.upload-msg').css('display','none');

	/*if(jQuery('#idea_image').val() == '')
	{
		alert('Please Select the file first');
		return false;
	}/

	//~ console.log(thisfile);

	jQuery('.msg').hide();

	if (ref == 1)
	{
		jQuery('.ShowFiles').hide();
	}

	/* Hide all alerts msgs */
	var obj = jQuery(thisfile);

	/* Using this we can set progress.*/
	var status = new createStatusbar(obj);

	/* Get uploaded file object */
	var uploadedfile	=	jQuery(thisfile)[0].files[0];

	/* Get uploaded file name */
	var filename = uploadedfile.name;

	/* pop out extension of file*/
	var ext = filename.split('.').pop().toLowerCase();

	if (ref == 1)
	{
		if (ext != 'jpg' && ext != "png" && ext != "jpeg" && ext != "gif")
		{
			alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.','alert-error');
			return false;
		}
	}
	if (ref == 0)
	{
		//if (ext != 'txt' && ext != "doc" && ext != "docx" && ext != "csv" && ext != "pdf" && ext != 'jpg' && ext != "png" && ext != "jpeg" && ext != "gif" && ext != 'avi')
		if (ext != 'pem')
		{
			alert('Sorry, only .pem file allowed in ios certificates');
			return false;
		}
	}

	/* if file size is greater than allowed*/
	/*if((lesson_upload_size * 1024 * 1024) < uploadedfile.size)
	{
		status.setMsg(filesize_exceeded,'alert-error');
		return false;
	}*/

	/* If evrything is correct so far, popolate file name in fileupload-preview*/
	var file_name_container	=	jQuery(".fileupload-preview",jQuery(thisfile).closest('.fileupload-new'));

	jQuery(file_name_container).show();
	jQuery(file_name_container).text(filename);

	if (ref == 1)
	{
		jQuery("#jform_idea_image").val(filename);
	}

	jQuery('#upload-progress').html('<div class="progress"><div class="progress-bar progress-bar-uploading"><span class="progress_bar_text">Uploading <span class="progress_per"></span></div></div>');
	/* Now start inporting csv file*/
	jQuery('.upload-progress').css('display','block');
	//~ console.log(uploadedfile);
	//~ console.log(status);
	//~ console.log(thisfile);
	//~ console.log(ref);
	//~ console.log(ref,lesson_id);
	startImporting(uploadedfile, status, thisfile,ref,0);
}

function showsubmission()
{
	// SHow submission form
	jQuery('#submit_form_head').removeClass('collapsed');
	jQuery('#collapseTwo').addClass('in');
	jQuery('#collapseTwo').height('auto');

}
