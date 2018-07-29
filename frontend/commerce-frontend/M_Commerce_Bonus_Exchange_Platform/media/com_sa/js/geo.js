techjoomla.jQuery(function() {
	var availableTags = '';

	function split(val) {
		return val.split(/,\s*/);
	}

	function extractLast(term) {
		return split(term).pop();
	}
	/*get foucus on the input box*/
	techjoomla.jQuery(".selections").click(function() {
		var ul_id = this.id;
		var setfocus = ul_id.split("_");
		techjoomla.jQuery("#" + setfocus[1]).focus();
		techjoomla.jQuery("#" + setfocus[1]).val('');
	}).focusout(function() {
		var ul_id = this.id;
		var setfocus = ul_id.split("_");
		techjoomla.jQuery("#" + setfocus[1]).val('');
	});
	/*show hide the options*/
	techjoomla.jQuery("input[name=geo_type]").change(function() {
		if(techjoomla.jQuery("input[name=geo_type]:checked").val() == 'everywhere') {
			techjoomla.jQuery(".byregion_ul").hide();
			techjoomla.jQuery(".bycity_ul").hide();
		} else if(techjoomla.jQuery("input[name=geo_type]:checked").val() == 'byregion') {
			techjoomla.jQuery(".byregion_ul").show();
			techjoomla.jQuery(".bycity_ul").hide();
		} else { /*code for bycity*/
			techjoomla.jQuery(".byregion_ul").hide();
			techjoomla.jQuery(".bycity_ul").show();
		}

	});
	/*function to find out the suggestions for geo targeting*/
	techjoomla.jQuery(".geo_fields")
		.bind('keypress', function(e, previousText) {
			/*alert(this.value);*/
			if((e.which < 97 /* a */ || e.which > 122 /* z */ ) && (e.which < 65 /* A */ || e.which > 90 /* Z */ ) && (trim(this.value) != '' && e.which != 32 /* Space */ && e.which != 45 /* Hyfen */ ) && e.which != 8 /* Backspace */ ) {
				e.preventDefault();
			}
		});
	techjoomla.jQuery(".geo_fields")
		.autocomplete({
			minLength: 0,

			source: function(request, response) {
				if(request.term) {
					var geofields = techjoomla.jQuery(".geo_fields_hidden").serializeArray();
					techjoomla.jQuery.ajax({
						url: root_url + "index.php?option=com_sa&task=create.findGeolocations&element=" + this.element[0].id + "&request_term=" + request.term,
						type: "POST",
						data: geofields, /*{geofields:geofields,current:this.value},*/
						dataType: "json",
						success: function(data) {
							if(data != "") {
								availableTags = data;
							}
							/*delegate back to autocomplete, but extract the last term*/
							response(techjoomla.jQuery.ui.autocomplete.filter(
								availableTags, extractLast(request.term)));
						}
					});
					/*delegate back to autocomplete, but extract the last term*/
					/*response( techjoomla.jQuery.ui.autocomplete.filter(
					//availableTags, extractLast( request.term ) ) );*/
				}
			},
			focus: function() {
				/*prevent value inserted on focus*/
				return false;
			},
			select: function(event, ui) {
				this.value = '';
				var add_li = '<li class="selection" id="selection-' + this.id + '_' + techjoomla.jQuery(this).index() + '">';
				add_li += '<a class="close" id="close-' + techjoomla.jQuery(this).index() + '" onclick="remove_li(\'' + this.id + '\',' + techjoomla.jQuery(this).index() + ',\'' + ui.item.value + '\');">×</a>' + ui.item.value + '</li>';
				techjoomla.jQuery(this).before(add_li);
				availableTags = '';
				add_tag(this.id, ui.item.value);
				show_hide_others();
				return false;
			}

		});
});

techjoomla.jQuery(function() {
	if(document.getElementById('editview').value == '1') {
		techjoomla.jQuery("input[name=geo_type]").change();
		var elements = techjoomla.jQuery('.geo_fields');
		terms = new Array();
		elements.each(function() {
			terms = dosplit(techjoomla.jQuery(this).val());

			for(var i = 0; i < terms.length; i++) {
				if(terms[i]) {
					var add_li = '<li class="selection" id="selection-' + this.id + '_' + i + '">';
					add_li += '<a class="close" id="close-' + i + '" onclick="remove_li(\'' + this.id + '\',' + i + ',\'' + terms[i] + '\');">×</a>' + terms[i] + '</li>';
					techjoomla.jQuery(this).before(add_li);
					this.value = '';
					add_tag(this.id, terms[i]);

				}
			}
		});
		show_hide_others();
	}
});
/*show hide the options based on count*/
function show_hide_others() {
	var seletion_childCount = techjoomla.jQuery("ul#selections_country > li").length;
	if(seletion_childCount > 1 || seletion_childCount == 0)
		techjoomla.jQuery("#geo_others").hide('fast');
	else if(seletion_childCount == 1)
		techjoomla.jQuery("#geo_others").show('fast');
}

function ltrim(s) {
	var l = 0;
	while(l < s.length && s[l] == ' ') {
		l++;
	}
	return s.substring(l, s.length);
}

function rtrim(s) {
	var r = s.length - 1;
	while(r > 0 && s[r] == ' ') {
		r -= 1;
	}
	return s.substring(0, r + 1);
}

function trim(str) {
	return ltrim(rtrim(str));
}

function dosplit(value) {
	value = value.replace(/^.(\s+)?/, "");
	value = value.replace(/(\s+)?.$/, "");
	return value.split("||");
}

function add_tag(id, push_val) {

	var hidden_value = techjoomla.jQuery("#" + id + "_hidden").val();
	if(hidden_value) {
		hidden_values = dosplit(hidden_value);
		if(techjoomla.jQuery.inArray(push_val, hidden_values) == '-1') {
			hidden_values.push(push_val);
		}
		val_to_push = hidden_values.join("||");
	} else
		val_to_push = push_val;
	techjoomla.jQuery("#" + id + "_hidden").val("|" + val_to_push + "|");

}

function remove_li(thisid, ind, item) {
	var temp = new Array();
	var flag = 0;
	temp = dosplit(document.getElementById(thisid + "_hidden").value);
	for(var i = 0; i < temp.length; i++) {
		if(temp[i] != '') {
			if(trim(temp[i]) == item) {
				removeByIndex(temp, i);
				break;
			}
		}
	}
	val_to_push = temp.join("||");
	val_to_push = temp.join("||");
	if(val_to_push)
		techjoomla.jQuery("#" + thisid + "_hidden").val("|" + val_to_push + "|");
	else
		techjoomla.jQuery("#" + thisid + "_hidden").val("");

	techjoomla.jQuery('#' + 'selection-' + thisid + '_' + ind).remove();
	show_hide_others();
}

function removeByIndex(arrayName, arrayIndex) {
	arrayName.splice(arrayIndex, 1);
}
