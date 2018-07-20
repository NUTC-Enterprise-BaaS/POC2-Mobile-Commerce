techjoomla.jQuery(document).ready(function() {
	sa.create.changeAdType();
	show_hide_geo("geo_target");
	show_hide_geo("social_target");
	show_hide_geo("context_target");

	techjoomla.jQuery('.targetting_yes_no label').on("click", function() {
		targetting_yes_no_click(this);
	});
	techjoomla.jQuery('.unlimited_yes_no label').on("click", function() {
		unlimited_yes_no_click(this);
	});

	techjoomla.jQuery(".target").click(function() {
		show_hide_geo(this.id);
	});

	//Edit ad => Show or Hide Targeting Divs
	var geo_target = techjoomla.jQuery("input[name=geo_target]:radio:checked").val();

	if(geo_target == 1) {
		techjoomla.jQuery("#geo_target_div").show("slow");
	} else {
		techjoomla.jQuery("#geo_target_div").hide("slow");
	}

	var social_target = techjoomla.jQuery("input[name=social_target]:radio:checked").val();

	if(social_target == 1) {
		techjoomla.jQuery("#social_target_div").show("slow");
	} else {
		techjoomla.jQuery("#social_target_div").hide("slow");
	}

	var context_target = techjoomla.jQuery("input[name=context_target]:radio:checked").val();

	if(context_target == 1) {
		techjoomla.jQuery("#context_target_div").show("slow");
	} else {
		techjoomla.jQuery("#context_target_div").hide("slow");
	}

	var unlimited_ad = techjoomla.jQuery("input[name=unlimited_ad]:radio:checked").val();
	//var unlimited_ad=techjoomla.jQuery("input[name=unlimited_ad] radio:checked").val();
	if(unlimited_ad == 1) {
		hidePayment(1);
	}
});

//call on alt checkbox
function switchCheckboxalt() {
	var ischecked = 0;
	var is_affliatead = 0;
	if(techjoomla.jQuery('#altadbutton').is(':checked')) {
		techjoomla.jQuery('#guestbutton').attr('checked', false);
		ischecked = 1;
	}
	if(document.getElementById('adtype').value == "affiliate")
		var is_affliatead = 1;

	if(ischecked == 1 || is_affliatead == 1 || (showTargeting == 0 && allowWholeAdEdit == 0)) {
		changenexttoexit(1);
	} else {
		changenexttoexit(0);
	}
}

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

//validation for campaign layout in buildad.
function checkreview_camp(mim_daily, currency) {
	var ncamp = document.getElementById('ad_campaign').value;
	if(document.getElementById('new_campaign').style.display == 'block') {
		var camp_name = document.getElementById('camp_name').value;
		var camp_amount = document.getElementById('camp_amount').value;

		if(camp_name == '' && ncamp == '0') //both list camp or new camp not present
		{
			alert("Enter Campaign");
			document.getElementById('camp_name').focus();
			return false;
		}
		if((camp_name && camp_amount == '') || (camp_name && parseFloat(camp_amount) < parseFloat(mim_daily))) //if new camp then check for daily budget
		{
			alert("The Minimum Allowed Daily Budget for Campaigns is " + mim_daily + " " + currency + ". Please enter a value larger than that.");
			document.getElementById('camp_amount').focus();
			return false;
		}

	} else {
		if(ncamp == '0') //if new camp is none then check for select list camp
		{
			alert("Please select a campaign");
			document.getElementById('ad_campaign').focus();
			return false;
		}
	}
	//		document.getElementById('adsform').setAttribute('target','');
	//		document.getElementById('adsform').setAttribute('action','');

	return true;
}

function checkreview(mim_daily, camp, charge, msg2, currency, msgvaliddate, datemsg, wrongdates, invalid, chkcontextual) {
	/*code for stripping the http:// from url*/
	var urlstring = document.getElementById('url2').value;
	var urlpointer = -1;
	urlpointer = urlstring.indexOf('http://');
	if(urlpointer == 0) {
		var newstr = urlstring.substr(7);
		document.getElementById('url2').value = newstr;
	}

	if(camp == 'wallet_mode') {
		if(!checkreview_camp(mim_daily, currency)) {
			return false;
		} {
			var camp_price_opt = document.getElementById('pricing_opt').value;
			if(camp_price_opt == 'value') //check for payment_type selected or not
			{
				alert("Please select Pricing option");
				document.getElementById('pricing_opt').focus();
				return false;
			}
		}
		return true;
	} else {
		var form = document.adsform;
		var totaldisplay = form.totaldisplay.value;
		var totalamount = form.totalamount.value;
		var charge_points = 0;
		var daterangefrom = form.datefrom.value;

		var chargeoption = document.getElementById('chargeoption').value;
		var unlimited_ad = '';
		if(techjoomla.jQuery(":radio[name=unlimited_ad]").length > 0)
			unlimited_ad = techjoomla.jQuery("input[name=unlimited_ad]:radio:checked").val();

		/**if(document.getElementById('sa_recuring').checked==true)
		{
			totalamount=(document.getElementById('totaldays').value)*totalamount;
		}*/

		if((parseInt(chargeoption) >= 2) && (unlimited_ad != 1)) {
			if((daterangefrom == ' ') && (parseInt(chargeoption) == 2)) {
				alert(datemsg);
				return false;
			}
			var now = new Date();
			var year = now.getFullYear();
			var month = now.getMonth() + 1;
			var date = now.getDate();
			if(date >= 1 && date <= 9) {
				var newdate = '0' + date;
			} else {
				var newdate = date;
			}
			if(month >= 1 && month <= 9) {
				var newmonth = '0' + month;
			} else {
				var newmonth = month;
			}

			today = year + '-' + newmonth + '-' + newdate;

			if((daterangefrom) < (today)) {
				alert(wrongdates);
				return false;
			}
			var daycount = document.getElementById("totaldays").value;

			if(isNaN(daycount) || daycount <= 0) {
				alert(msgvaliddate);
				document.getElementById("totaldays").focus();
				return false;
			}
		}

		if((parseInt(chargeoption) != 2) && (unlimited_ad != 1)) {
			if(parseInt(chargeoption) < 2) {
				if(totaldisplay == '') {
					alert(invalid);
					document.getElementById('totaldisplay').focus();
					return false;
				}

				if(isNaN(totaldisplay) || (totaldisplay <= 0)) {
					alert(invalid);
					document.getElementById('totaldisplay').focus();
					return false;
				}
			}
		}

		if(parseFloat(totalamount) < parseFloat(charge)) {
			alert(msg2);
			document.getElementById('totaldisplay').focus();
			return false;
		}

	} //else end
	return true;
}
//function checkreviews ends here

function targetting_yes_no_click(element) {
	var radio_id = techjoomla.jQuery(element).attr('for');

	techjoomla.jQuery('#' + radio_id).attr('checked', 'checked');

	/*for jQuery 1.9 and higher*/
	techjoomla.jQuery('#' + radio_id).prop("checked", true)

	var radio_btn = techjoomla.jQuery('#' + radio_id);
	var radio_value = radio_btn.val();

	var radio_name = techjoomla.jQuery('#' + radio_id).attr('name');
	var target_div = radio_name + "_div"
	techjoomla.jQuery(element).parent().find('label').removeClass('btn-success').removeClass('btn-danger');
	if(radio_value == 1) {
		techjoomla.jQuery(element).addClass('btn-success');
		techjoomla.jQuery('#' + target_div).show("slow");
	}
	if(radio_value == 0) {
		techjoomla.jQuery(element).addClass('btn-danger');
		techjoomla.jQuery('#' + target_div).hide("slow");
	}
}

function unlimited_yes_no_click(element) {
	var radio_id = techjoomla.jQuery(element).attr('for');

	techjoomla.jQuery('#' + radio_id).attr('checked', 'checked');

	/*for jQuery 1.9 and higher*/
	techjoomla.jQuery('#' + radio_id).prop("checked", true)

	var radio_btn = techjoomla.jQuery('#' + radio_id);
	var radio_value = radio_btn.val();

	var radio_name = techjoomla.jQuery('#' + radio_id).attr('name');
	var target_div = radio_name + "_div"
	techjoomla.jQuery(element).parent().find('label').removeClass('btn-success').removeClass('btn-danger');
	if(radio_value == 1) {
		techjoomla.jQuery(element).addClass('btn-success');
		hidePayment(1);

		// vm:hide price and coupon releated things
		techjoomla.jQuery('.sa_hideForUnlimitedads').hide();
	}
	if(radio_value == 0) {
		techjoomla.jQuery(element).addClass('btn-danger');
		hidePayment(0);

		// vm:show price and coupon releated things
		techjoomla.jQuery('.sa_hideForUnlimitedads').show();
	}
	changenexttoexit(radio_value);
}

function changenexttoexit(ischecked) {
	if(ischecked == 1) {
		techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').removeClass('btn-primary');
		techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').addClass('btn-success');
		techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext span').text(savenexitbtn_text);
	} else {
		techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext span').text(savennextbtn_text);
		techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').addClass('btn-primary');
		techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').removeClass('btn-success');
	}
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

function hidePayment(selector) {

	if(selector == 1) {
		techjoomla.jQuery("#totaldisplay").val('');
		techjoomla.jQuery("#ad_totalamount").html('');
		techjoomla.jQuery("#daterangefrom").val('');

		techjoomla.jQuery("#totaldays").html('');

		techjoomla.jQuery("#chargeoption").attr("disabled", "disabled");
		techjoomla.jQuery("#totaldisplay").attr("disabled", "disabled");
		techjoomla.jQuery("#datefrom").attr("disabled", "disabled");
		techjoomla.jQuery("#totaldays").attr("disabled", "disabled");
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

function selectapplist() {
	document.getElementById('destination_url').style.display = 'none';
	document.getElementById('promotplugin').style.display = 'block';
}

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

/*Select newly added campaign*/
function newCampaignSelect(camp_id) {
	if(techjoomla.jQuery("#new_campaign").length) {
		techjoomla.jQuery("#new_campaign").hide();
	}

	var camp_name = techjoomla.jQuery('#camp_name').val();
	var option = '<option value=' + camp_id + ' selected="selected">' + camp_name + '</option>';
	var select = techjoomla.jQuery('#ad_campaign');
	select.append(option);
}
