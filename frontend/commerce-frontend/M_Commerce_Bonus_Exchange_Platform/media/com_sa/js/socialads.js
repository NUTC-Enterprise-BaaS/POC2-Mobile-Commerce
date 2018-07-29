techjoomla.jQuery(document).ready(function() {
	techjoomla.jQuery('.targetting_yes_no label').on("click", function() {
		var radio_id = techjoomla.jQuery(this).attr('for');

		techjoomla.jQuery('#' + radio_id).attr('checked', 'checked');

		/*for jQuery 1.9 and higher*/
		techjoomla.jQuery('#' + radio_id).prop("checked", true)

		var radio_btn = techjoomla.jQuery('#' + radio_id);
		var radio_value = radio_btn.val();

		var radio_name = techjoomla.jQuery('#' + radio_id).attr('name');
		var target_div = radio_name + "_div"
		techjoomla.jQuery(this).parent().find('label').removeClass('btn-success').removeClass('btn-danger');
		if(radio_value == 1) {
			techjoomla.jQuery(this).addClass('btn-success');
			techjoomla.jQuery('#' + target_div).show("slow");
		}
		if(radio_value == 0) {
			techjoomla.jQuery(this).addClass('btn-danger');
			techjoomla.jQuery('#' + target_div).hide("slow");
		}
	});
	techjoomla.jQuery('.unlimited_yes_no label').on("click", function() {
		var radio_id = techjoomla.jQuery(this).attr('for');

		techjoomla.jQuery('#' + radio_id).attr('checked', 'checked');

		/*for jQuery 1.9 and higher*/
		techjoomla.jQuery('#' + radio_id).prop("checked", true)

		var radio_btn = techjoomla.jQuery('#' + radio_id);
		var radio_value = radio_btn.val();

		var radio_name = techjoomla.jQuery('#' + radio_id).attr('name');
		var target_div = radio_name + "_div"
		techjoomla.jQuery(this).parent().find('label').removeClass('btn-success').removeClass('btn-danger');
		if(radio_value == 1) {
			techjoomla.jQuery(this).addClass('btn-success');
			hidePayment(1);

			// vm:hide price and coupon releated things
			techjoomla.jQuery('.sa_hideForUnlimitedads').hide();
		}
		if(radio_value == 0) {
			techjoomla.jQuery(this).addClass('btn-danger');
			hidePayment(0);

			// vm:show price and coupon releated things
			techjoomla.jQuery('.sa_hideForUnlimitedads').show();
		}
		changenexttoexit(radio_value);
	});
});
//function to get characters written in textarea
function getObject(obj) {
	var theObj;
	if(document.all) {
		if(typeof obj == "string") {
			return document.all(obj);
		} else {
			return obj.style;
		}
	}
	if(document.getElementById) {
		if(typeof obj == "string") {
			return document.getElementById(obj);
		} else {
			return obj.style;
		}
	}
	return null;
}
//function getobject ends here

//function getcount for counting chars in title
function toCount(entrance, exit, text, msg, characters, value, event) {
	if(event.keyCode != 9) {
		if(entrance == 'eBann')
			techjoomla.jQuery("#preview_sa").find(".preview-title").text(value);
	}
	if(document.getElementById('adtype').value == 'affiliate') {
		return;
	}
	var entranceObj = getObject(entrance);
	var exitObj = getObject(exit);
	var length = characters - entranceObj.value.length;
	if(length <= 0) {
		length = 0;
		var data = text.replace("{CHAR}", length);
		//if (event != '')
		techjoomla.jQuery('#max_tit1').html('<span class="disable"> ' + data + msg + ' </span>');
		//				text='<span class="disable"> '+text+' </span>';
		techjoomla.jQuery('#sBann').hide();
		entranceObj.value = entranceObj.value.substr(0, characters);

		//				jQuery("#sBann").addclass('disable');
	} else {
		exitObj.innerHTML = text.replace("{CHAR}", length);
		techjoomla.jQuery('#sBann').show();
	}
}
//function getcount ends here

//function getcount1 for counting chars	in body text
function toCount1(entrance, exit, text, msg, characters, value, event) {
	if(event.keyCode != 9) {
		techjoomla.jQuery("#ad-preview").find(".preview-bodytext").text(value);
	}
	if(document.getElementById('adtype').value == 'affiliate') {
		return;
	}
	var entranceObj = getObject(entrance);
	var exitObj = getObject(exit);
	var length = characters - entranceObj.value.length;
	if(length <= 0) {
		length = 0;
		var data = text.replace("{CHAR}", length)
		techjoomla.jQuery('#max_body1').html('<span class="disable"> ' + data + msg + ' </span>');
		entranceObj.value = entranceObj.value.substr(0, characters);
		//exitObj.innerHTML = text.replace("{CHAR}",length);
		techjoomla.jQuery('#sBann1').hide();
	} else {
		exitObj.innerHTML = text.replace("{CHAR}", length);
		techjoomla.jQuery('#sBann1').show();
	}
}
//function getcount1 ends here

function chk(value) {
	//if(this.value==''){value='Example Ad'; className="preview-title-lnk";}
	document.getElementById("preview-title").innerHTML = value;
}

function show_hide_geo(element) {

	if(techjoomla.jQuery("#" + element).is(":checked")) {
		//show the hidden div
		techjoomla.jQuery("#" + element + "_div").show("slow");
	} else {
		//otherwise, hide it
		techjoomla.jQuery("#" + element + "_div").hide("slow");
	}
}

//function for validation of overview
function open_div(geo, camp) {

	//------------------Remove comment-------------------
	//if(chkAdValid())
	//-------------------------------------
	{
		//alternatead button
		/*if(document.getElementById("altadbutton"))
		{
			if(document.getElementById("altadbutton").checked==true || document.getElementById('adtype').value == 'affiliate' )
			{
			  techjoomla.jQuery("#guestbutton").attr("disabled","disabled");
				//document.myForm.task.value = 'altad';
				document.getElementById('adsform').setAttribute('target','');
				document.getElementById('adsform').setAttribute('action','');
				document.myForm.submit();
				return;
			}
			else
			{
				//techjoomla.jQuery(".altbutton").css("display","none");
			}
		}*/
		//guestbutton
		if(document.getElementById("guestbutton")) {
			if(document.getElementById("guestbutton").checked == true) {
				//document.getElementById('btnWizardNext').style.display="none";
				if(geo == "1") {
					//document.getElementById('lowerdiv').style.display="block";//target0div show
					//document.getElementById('tab2_continue').style.display="block"; //target show
				} else {
					if(camp == 1) {
						document.getElementById('camp_dis').style.display = "block";
					} else {
						if(techjoomla.jQuery('#bottomdiv').length) {
							document.getElementById('bottomdiv').style.display = "block";
						}
					}
					//document.getElementById('btnWizardNext').style.display="none";//ad hide
					//document.getElementById('lowerdiv').style.display="none";//target0div hide
					//document.getElementById('review').style.display="block";//pricing show
				}

				//------------------Remove comment-------------------
				//return false;
				//-------------------------------------
			} else {
				//techjoomla.jQuery(".altbutton").css("display","none");
			}

		}
		// code for geo targeting
		//document.getElementById('btnWizardNext').style.display="none";
		if(techjoomla.jQuery('#lowerdiv').length) {
			document.getElementById('lowerdiv').style.display = "block";
		}
		//document.getElementById('tab2_continue').style.display="block";

		btnWizardNext();

		return true;
	}

} //function open_div() ends here

function onbackone() {

	//document.getElementById('btnWizardNext').style.display="block";
	//document.getElementById('lowerdiv').style.display="none";
	techjoomla.jQuery(".altbutton").css("display", "block");
	techjoomla.jQuery(".guestbutton").css("display", "block");

}

function onbacktwo(camp, geo) {

	document.getElementById('review').style.display = "none";
	if(camp == 1) {
		document.getElementById('camp_dis').style.display = "none";
	} else {
		//document.getElementById('bottomdiv').style.display="none";
	}

	if(geo == 1) {
		//document.getElementById('tab2_continue').style.display="block";
	} else {
		//document.getElementById('btnWizardNext').style.display="block";
	}

	techjoomla.jQuery(".altbutton").css("display", "block");

}



//function switchCheckboxalt ends

/*function to disabled checkbox-guest
function hideguest(check)
{
	if(check.checked==true)
	{
		techjoomla.jQuery("#guestbutton").attr("disabled","disabled");
	}
	else
	{
		techjoomla.jQuery("#guestbutton").removeAttr("disabled");
	}
}

//function to disabled checkbox-alternate
function hidealt(check)
{
	if(check.checked==true)
	{
		techjoomla.jQuery("#altadbutton").attr("disabled","disabled");
	}
	else
	{
		techjoomla.jQuery("#altadbutton").removeAttr("disabled");
	}
}*/

function hidePayment(selector) {

	if(selector == 1) {
		techjoomla.jQuery("#totaldisplay").val('');
		techjoomla.jQuery("#ad_totalamount").html('');
		techjoomla.jQuery("#daterangefrom").val('');

		techjoomla.jQuery("#totaldays").html('');

		techjoomla.jQuery("#chargeoption").attr("disabled", "disabled");
		techjoomla.jQuery("#totaldisplay").attr("disabled", "disabled");
		techjoomla.jQuery("#datefrom").attr("disabled", "disabled");
		techjoomla.jQuery("#dateto").attr("disabled", "disabled");
		//techjoomla.jQuery("#gateway").attr("disabled","disabled");
		techjoomla.jQuery("#pricing_opt").val('');
		techjoomla.jQuery("#pricing_opt").attr("disabled", "disabled");
		techjoomla.jQuery("#bid_value").attr("disabled", "disabled");
		techjoomla.jQuery("select").trigger("liszt:updated");

	} else {
		techjoomla.jQuery("#chargeoption").removeAttr("disabled");
		techjoomla.jQuery("#totaldisplay").removeAttr("disabled");
		//techjoomla.jQuery("#gateway").removeAttr("disabled");
		techjoomla.jQuery("#datefrom").removeAttr("disabled");
		techjoomla.jQuery("#dateto").removeAttr("disabled");
		techjoomla.jQuery("#pricing_opt").removeAttr("disabled");
		techjoomla.jQuery("#bid_value").removeAttr("disabled");
		techjoomla.jQuery("select").trigger("liszt:updated");

	}
}

function selectallList(checkbox, id) {

	var test = document.getElementById('mapdata' + id).options.length;
	if(checkbox.checked) {
		for(var i = 0; i < test; i++) {
			document.getElementById('mapdata' + id).options[i].selected = true;
		}
	} else {
		for(var i = 0; i < test; i++) {
			document.getElementById('mapdata' + id).options[i].selected = false;
		}

	}
	return true;
}

function selectClicked(mulbox) {
	document.getElementById('multiselect-' + mulbox).checked = false;
}

//function for duration showing ad
function open_bottomdiv(multistr, msg, datel, msg2, camp) {
	if(datel) {

		if(!checkdate(datel, '_low', msg2))
			return false;
		if(!checkdate(datel, '_high', msg2))
			return false;
	}

	if(camp == 1) {
		document.getElementById('camp_dis').style.display = "block";
	} else {
		if(techjoomla.jQuery('#bottomdiv').length) {
			document.getElementById('bottomdiv').style.display = "block";
		}
	}

	//document.getElementById('tab2_continue').style.display="none";
	document.getElementById('review').style.display = "block";
} //open_bottomdiv() ends here

function open_datebox() {
	document.getElementById('bottom1').style.display = "block";
}

function close_datebox() {
	document.getElementById('bottom1').style.display = "none";
}

function checkdate(date, str, msg) {
	multiarray = date.split(',');
	for(var j = 0; j < multiarray.length; j++) {
		var flag = 0;
		test = document.getElementById('mapdata[][' + multiarray[j] + str + ']').value;

		if(test.match(/^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/))
			flag = 1;
		if(test == '')
			flag = 1;
		if(flag == 0) {
			alert(msg);
			return;
		}
	}
	return true;
}

/* function for checking multicheck options*/
function checkAllMulti(multistr, msg) {
	multiarray = multistr.split(',');
	for(var j = 0; j < multiarray.length; j++) {
		var flag = 0;
		var test = document.getElementById('mapdata' + multiarray[j]).options.length;
		for(var i = 0; i < test; i++) {
			if(document.getElementById('mapdata' + multiarray[j]).options[i].selected == true) {
				flag = 1;
			}
		}
		if(flag == 0) {
			alert(msg);
			return false;
		}
	}
	return true;
}

function campaignValidation() {
	if(document.getElementById('new_campaign').style.display == 'block') {
		var camp_name = document.getElementById('camp_name').value;
		var camp_amount = document.getElementById('camp_amount').value;

		//both list camp or new camp not present
		if(camp_name == '' && camp_amount == 0) {
			alert("Enter Campaign");
			document.getElementById('camp_name').focus();
			return false;
		}

		//if new camp then check for daily budget
		if((camp_name && camp_amount == '') || (camp_name && parseFloat(camp_amount) < parseFloat(camp_currency_daily))) {
			alert("The Minimum Allowed Daily Budget for Campaigns is " + camp_currency_daily + " " + currency + ". Please enter a value larger than that.");
			document.getElementById('camp_amount').focus();
			return false;
		}
	} else {
		var selectedCampaign = techjoomla.jQuery('#camp option:selected').val();
		if(selectedCampaign == 0) {
			alert('Please select the campaign');
			return false;
		}
	}
	return true;
}
//function camp_review ends

/* vm:this function allow only numberic and specified char (at 0th position)
// ascii (code 43 for +) (48 for 0 ) (57 for 9)  (45 for  - (negative sign))
			(code 46 for dot/full stop .)
	@param el :: html element
	@param allowed_ascii::ascii code that shold allow

*/
function ad_checkforalpha(el, allowed_ascii, enter_numerics) {
	allowed_ascii = (typeof allowed_ascii === "undefined") ? "" : allowed_ascii;
	var i = 0;
	for(i = 0; i < el.value.length; i++) {
		if((el.value.charCodeAt(i) <= 47 || el.value.charCodeAt(i) >= 58) || (el.value.charCodeAt(i) == 45)) {

			if(allowed_ascii == el.value.charCodeAt(i)) //&& i==0)  // + allowing for phone no at first char
			{
				var temp = 1;
			} else {
				alert(enter_numerics);
				el.value = el.value.substring(0, i);
				return false;
			}

		}
	}
	return true;
}

function hideNewCampaign() {
	techjoomla.jQuery("#new_campaign").hide();
	populateCampaign();
}
