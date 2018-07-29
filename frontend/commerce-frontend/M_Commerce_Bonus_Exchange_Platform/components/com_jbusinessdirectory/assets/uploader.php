<?php
$app = JFactory::getApplication();
$input = $app->input;
$view = $input->get('view');
?>
<script type="text/javascript">
var view = '<?php echo $view; ?>';

var maxAttachments = '<?php echo isset($this->item->package)?$this->item->package->max_attachments :$this->appSettings->max_attachments ?>';
var maxPictures = '<?php echo isset($this->item->package)?$this->item->package->max_pictures :$this->appSettings->max_pictures ?>';

var picturesFolder = '<?php echo JURI::root().PICTURES_PATH ?>';
var checkedIcon = '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>';
var uncheckedIcon = '<?php echo JURI::root()."administrator/components/".JBusinessUtil::getComponentName() ?>/assets/img/unchecked.gif';
var deleteIcon = '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>';
var upIcon = '<?php echo JURI::root()."administrator/components/".JBusinessUtil::getComponentName() ?>/assets/img/up-icon.png';
var downIcon = '<?php echo JURI::root()."administrator/components/".JBusinessUtil::getComponentName() ?>/assets/img/down-icon.png';


function imageUploader(folderID, folderIDPath, type) {
	if(type === undefined || type === null)
		type= '';
	jQuery("#"+type+"imageUploader").change(function()  {
		jQuery("#remove-image-loading").remove();
		jQuery("#"+type+"picture-preview").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span> Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var fisRe = /^.+\.(jpg|bmp|gif|png|jpeg|PNG|JPG|GIF|JPEG)$/i;
		var path = jQuery(this).val();
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert('JPG, JPEG, BMP, GIF, PNG only!');
			return false;
		}
		jQuery(this).upload(folderIDPath, function(responce)  {
			if( responce == '' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						setUpImage(
							folderID + jQuery(this).attr("path"),
							jQuery(this).attr("name"),
							type
						);
						jQuery("#remove-image-loading").remove();
					}
					
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
			}
		});
		jQuery("#item-form").validationEngine('attach');
	});
}

function setUpImage(path, name, type) {
	jQuery("#"+type+"imageLocation").val(path);
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('id', 'itemImg');
	img_new.setAttribute('class', 'item-image');
	if(view == 'speaker') img_new.setAttribute("style", "width:100px;height:100px;");
	jQuery("#"+type+"picture-preview").empty();
	jQuery("#"+type+"picture-preview").append(img_new);
}

function markerUploader(folderID, folderIDPath) {
	jQuery("#markerfile").change(function() {
		jQuery("#remove-image-loading").remove();
		jQuery("#marker-preview").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span></p>');
		var fisRe 	= /^.+\.(jpg|bmp|gif|png)$/i;
		var path = jQuery(this).val();
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert(' JPG, BMP, GIF, PNG only!');
			return false;
		}
		jQuery(this).upload(folderIDPath, function(responce) {
			if( responce == '' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						setUpMarker(
							folderID + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
						jQuery("#remove-image-loading").remove();
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
			}
		});
		jQuery("#item-form").validationEngine('attach');
	});
}

function setUpMarker(path, name) {
	jQuery("#markerLocation").val(path);
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('id', 'markerImg');
	img_new.setAttribute('class', 'marker-image');
	jQuery("#marker-preview").empty();
	jQuery("#marker-preview").append(img_new);
}

function multiImageUploader(folder, folderPath) {
	jQuery("#multiImageUploader").change(function() {
		jQuery("#remove-image-loading").remove();
		jQuery("#table_pictures").append('<p id="remove-image-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span>Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var fisRe = /^.+\.(jpg|bmp|gif|png|jpeg|PNG|JPG|GIF|JPEG)$/i;
		var path = jQuery(this).val();
		
		if (path.search(fisRe) == -1) {
			jQuery("#remove-image-loading").remove();
			alert(' JPG, JPEG, BMP, GIF, PNG only!');
			return false;
		}	
		jQuery(this).upload(folderPath, function(responce) {
			if( responce =='' ) {
				jQuery("#remove-image-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						addPicture(
							folder + jQuery(this).attr("path"),
							jQuery(this).attr("name")
						);
						jQuery("#remove-image-loading").remove();
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
				jQuery(this).val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('attach');
	});
}

function addPicture(path, name) {
	var tb = document.getElementById('table_pictures');
	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}

	var td2_new	= document.createElement('td');  
	var img_new	= document.createElement('img');
	img_new.setAttribute('src', picturesFolder + path );
	img_new.setAttribute('class', 'img_picture');
	td2_new.appendChild(img_new);
	var span_new = document.createElement('span');
	span_new.innerHTML = name;
	td2_new.appendChild(span_new);
	
	var input_new_1 = document.createElement('input');
	input_new_1.setAttribute('type', 'hidden');
	input_new_1.setAttribute('name', 'picture_enable[]');
	input_new_1.setAttribute('id', 'picture_enable[]');
	input_new_1.setAttribute('value', '1');
	td2_new.appendChild(input_new_1);
	
	var input_new_2	= document.createElement('input');
	input_new_2.setAttribute('type', 'hidden');
	input_new_2.setAttribute('name', 'picture_path[]');
	input_new_2.setAttribute('id', 'picture_path[]');
	input_new_2.setAttribute('value', path);
	td2_new.appendChild(input_new_2);

	var br_tag	= document.createElement('br');
	td2_new.appendChild(br_tag);

	var textarea_new = document.createElement('textarea');
	textarea_new.setAttribute("name","picture_info[]");
	textarea_new.setAttribute("id","picture_info");
	textarea_new.setAttribute("cols","50");
	textarea_new.setAttribute("rows","1");
	td2_new.appendChild(textarea_new);

	var td3_new	= document.createElement('td');  
	td3_new.style.textAlign='center';
	
	var img_del	= document.createElement('img');
	img_del.setAttribute('src', deleteIcon);
	img_del.setAttribute('class', 'btn_picture_delete');
	img_del.setAttribute('id', 	tb.rows.length);
	img_del.setAttribute('name', 'del_img_' + tb.rows.length);
	img_del.onmouseover = function(){ this.style.cursor='hand';this.style.cursor='pointer' };
	img_del.onmouseout = function(){ this.style.cursor='default' };
	img_del.onclick = function() { 
		if( !confirm('<?php echo JText::_("LNG_CONFIRM_DELETE_PICTURE",true)?>' )) 
			return; 					
		var row = jQuery(this).parents('tr:first');
		var row_idx = row.prevAll().length;
		jQuery('#crt_pos').val(row_idx);
		jQuery('#crt_path').val( path );
		jQuery('#btn_removefile').click();
	};
		
	td3_new.appendChild(img_del);
	
	var td4_new	= document.createElement('td');  
	td4_new.style.textAlign='center';
	var img_enable = document.createElement('img');
	img_enable.setAttribute('src', checkedIcon);
	img_enable.setAttribute('class', 'btn_picture_status');
	img_enable.setAttribute('id', 	tb.rows.length);
	img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);
	
	img_enable.onclick = function() { 
		var form = document.adminForm;
		var v_status = null; 
		if( form.elements['picture_enable[]'].length == null ) {
			v_status  = form.elements['picture_enable[]'];
		}
		else {
			var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
			var tb = document.getElementById('table_pictures');
			if( pos >= tb.rows.length )
				pos = tb.rows.length-1;
			v_status  = form.elements['picture_enable[]'][ pos ];
		}
		if(v_status.value=='1') {
			jQuery(this).attr('src', uncheckedIcon);
			v_status.value ='0';
		}
		else {
			jQuery(this).attr('src', checkedIcon);
			v_status.value ='1';
		}
	};
	td4_new.appendChild(img_enable);
	
	var td5_new = document.createElement('td');  
	td5_new.style.textAlign ='center';
			
	td5_new.innerHTML = '<span class=\'span_up\' onclick=\'var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());\'><img src="' + upIcon + '"></span>'+
						'<span class=\'span_down\' onclick=\'var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());\'><img src="' + downIcon + '"></span>';
	
	var tr_new = tb.insertRow(tb.rows.length);
	
	tr_new.appendChild(td2_new);
	tr_new.appendChild(td3_new);
	tr_new.appendChild(td4_new);
	tr_new.appendChild(td5_new);

	checkNumberOfPictures();
}

function removePicture(pos) {
	var tb = document.getElementById('table_pictures');

	if(tb==null) {
		alert('Undefined table, contact administrator !');
	}

	if(pos >= tb.rows.length)
		pos = tb.rows.length-1;
	tb.deleteRow(pos);

	if(view == 'company' || view == 'managecompany') {
		jQuery("#deleted").val(1);
		checkNumberOfPictures();
	}
}

function btn_removefile(removePath) {
	jQuery('#btn_removefile').click(function() {
		pos = jQuery('#crt_pos').val();
		path = jQuery('#crt_path').val();
		jQuery( this ).upload(removePath + path + '&_pos='+pos, function(responce) {
			if( responce =='' ) {
				alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						removePicture( jQuery(this).attr("pos") );
					}
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_FILE_DOESNT_EXIST',true)?>");
				});
				jQuery('#crt_pos').val('');
				jQuery('#crt_path').val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('detach');
	});
}

function multiFileUploader(folderID, folderIDPath) {
	jQuery("#multiFileUploader").change(function() {
		jQuery("#remove-file-loading").remove();
		jQuery("#table_attachments").append('<p id="remove-file-loading" class="text-center"><span class="icon-refresh icon-refresh-animate"></span> Loading...</p>');
		jQuery("#item-form").validationEngine('detach');
		var path = jQuery(this).val();
		jQuery(this).upload(folderIDPath, function(responce) {
			if( responce =='' ) {
				jQuery("#remove-file-loading").remove();
				alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("attachment").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						if(jQuery("#table_attachments tr").length < maxAttachments) {
							addAttachment(
								folderID + jQuery(this).attr("path"),
								jQuery(this).attr("name")
							);
						} else {
							alert("<?php echo JText::_('LNG_MAX_ATTACHMENTS_ALLOWED',true)?>"+maxAttachments);
						}
						jQuery("#remove-file-loading").remove();
					}
					else if( jQuery(this).attr("error") == 1 )
						alert("<?php echo JText::_('LNG_FILE_ALLREADY_ADDED',true)?>");
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_ADDING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_ERROR_GD_LIBRARY',true)?>");
					else if( jQuery(this).attr("error") == 4 )
						alert("<?php echo JText::_('LNG_ERROR_RESIZING_FILE',true)?>");
				});
			}
		}, 'html');
		jQuery("#item-form").validationEngine('attach');
	});
}

function addAttachment(path, name) {
	var tb = document.getElementById('table_attachments');
	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}
	
	var td1_new	= document.createElement('td');  
	td1_new.style.textAlign = 'left';
	var input_new = document.createElement('input');
	input_new.setAttribute("name","attachment_name[]");
	input_new.setAttribute("id","attachment_name");
	input_new.setAttribute("type","text");
	td1_new.appendChild(input_new);

	var span_new = document.createElement('span');
	span_new.innerHTML = name;
	td1_new.appendChild(span_new);
	
	var input_new_1 = document.createElement('input');
	input_new_1.setAttribute('type', 'hidden');
	input_new_1.setAttribute('name', 'attachment_status[]');
	input_new_1.setAttribute('id', 'attachment_status');
	input_new_1.setAttribute('value', '1');
	td1_new.appendChild(input_new_1);

	var input_new_2 = document.createElement('input');
	input_new_2.setAttribute('type', 'hidden');
	input_new_2.setAttribute('name', 'attachment_path[]');
	input_new_2.setAttribute('id', 'attachment_path');
	input_new_2.setAttribute('value', path);
	td1_new.appendChild(input_new_2);
	
	var td3_new	= document.createElement('td');  
	td3_new.style.textAlign = 'center';
	
	var img_del	= document.createElement('img');
	img_del.setAttribute('src', deleteIcon);
	img_del.setAttribute('class', 'btn_attachment_delete');
	img_del.setAttribute('id', 	tb.rows.length);
	img_del.setAttribute('name', 'del_attachment_' + tb.rows.length);
	img_del.onmouseover = function() { this.style.cursor='hand';this.style.cursor='pointer' };
	img_del.onmouseout = function() { this.style.cursor='default' };
	img_del.onclick = function() { 
		if( !confirm('<?php echo JText::_("LNG_CONFIRM_DELETE_ATTACHMENT",true)?>' )) 
			return; 
		var row = jQuery(this).parents('tr:first');
		var row_idx = row.prevAll().length;
		jQuery('#crt_pos_a').val(row_idx);
		jQuery('#crt_path_a').val( path );
		jQuery('#btn_removefile_at').click();
	};

	td3_new.appendChild(img_del);

	var td4_new	= document.createElement('td');  
	td4_new.style.textAlign='center';
	var img_enable = document.createElement('img');
	img_enable.setAttribute('src', checkedIcon);
	img_enable.setAttribute('class', 'btn_attachment_status');
	img_enable.setAttribute('id', 	tb.rows.length);
	img_enable.setAttribute('name', 'enable_img_' + tb.rows.length);

	img_enable.onclick = function() { 
		var form = document.adminForm;
		var v_status = null; 
		if( form.elements['attachment_status[]'].length == null ) {
			v_status  = form.elements['attachment_status[]'];
		}
		else {
			var pos = jQuery(this).closest('tr')[0].sectionRowIndex;
			var tb = document.getElementById('table_attachments');
			if( pos >= tb.rows.length )
				pos = tb.rows.length-1;
			v_status  = form.elements['attachment_status[]'][pos];
		}

		if(v_status.value=='1') {
			jQuery(this).attr('src', uncheckedIcon);
			v_status.value ='0';
		}
		else {
			jQuery(this).attr('src', checkedIcon);
			v_status.value ='1';
		}
	};

	td4_new.appendChild(img_enable);
	var td5_new	= document.createElement('td');  
	td5_new.style.textAlign = 'center';
			
	td5_new.innerHTML = '<span class=\'span_up\' onclick=\'var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());\'><img src="' + upIcon + '"></span>'+
						'<span class=\'span_down\' onclick=\'var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());\'><img src="' + downIcon + '"></span>';

	var tr_new = tb.insertRow(tb.rows.length);

	tr_new.appendChild(td1_new);
	tr_new.appendChild(td3_new);
	tr_new.appendChild(td4_new);
	tr_new.appendChild(td5_new);
}

function removeAttachment(pos) {
	var tb = document.getElementById('table_attachments');

	if( tb==null ) {
		alert('Undefined table, contact administrator !');
	}

	if(pos >= tb.rows.length)
		pos = tb.rows.length-1;
	tb.deleteRow( pos );
}

function btn_removefile_at(removePath_at) {
	jQuery('#btn_removefile_at').click(function() {
		jQuery("#item-form").validationEngine('detach');
		pos = jQuery('#crt_pos_a').val();
		path = jQuery('#crt_path_a').val();
		jQuery(this).upload(removePath_at + path + '&_pos='+pos, function(responce) {
			if( responce =='' ) {
				alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
				jQuery(this).val('');
			}
			else {
				var xml = responce;
				jQuery(xml).find("picture").each(function() {
					if(jQuery(this).attr("error") == 0 ) {
						removeAttachment( jQuery(this).attr("pos") );
					}
					else if( jQuery(this).attr("error") == 2 )
						alert("<?php echo JText::_('LNG_ERROR_REMOVING_FILE',true)?>");
					else if( jQuery(this).attr("error") == 3 )
						alert("<?php echo JText::_('LNG_FILE_DOESNT_EXIST',true)?>");
				});
				jQuery('#crt_pos_a').val('');
				jQuery('#crt_path_a').val('');
			}
		}, 'html');
		jQuery("#item-form").validationEngine('detach');
	});
}

function removeCoverImage() {
	jQuery("#cover-imageLocation").val("");
	jQuery("#cover-picture-preview").html("");
	jQuery("#cover-imageUploader").val("");
}

function removeLogo() {
	jQuery("#imageLocation").val("");
	jQuery("#picture-preview").html("");
	jQuery("#imageUploader").val("");
} /* Company & Conference & SessionLocation & Speaker */

function removeMarker() {
	jQuery("#markerLocation").val("");
	jQuery("#marker-preview").html("");
	jQuery("#markerfile").val("");
} /* Category */


function removeRow(id) {
	jQuery('#'+id).remove();
}

function checkNumberOfPictures() {
	var nrPictures = jQuery('textarea[name*="picture_info[]"]').length;
	if(nrPictures < maxPictures) {
		jQuery("#add-pictures").show();
		jQuery("#file-upload").show();
	}
	else {
		jQuery("#add-pictures").hide();
		jQuery("#file-upload").hide();
	}
}

</script>