/*------------------------------------------------------------------------
# ajax.js - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2010 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

var xmlHttp;

function next(live_site,category_id,employee_id,vid,date_from,date_to) {
    var month = document.getElementById('month');
    var year = document.getElementById('year');
    month = month.value;
    year = year.value;
    if (month < 12) {
        month = parseInt(month) + 1;
    } else {
        year = parseInt(year) + 1;
        month = 1;
    }
    document.getElementById('month').value = month;
    document.getElementById('year').value = year;
    var ossm = document.getElementById('ossm');
    if (ossm != null) {
        document.getElementById('ossm').value = month;
    }
    var ossy = document.getElementById('ossy');
    if (ossy != null) {
        document.getElementById('ossy').value = year;
    }

    var ossmh = document.getElementById('ossmh');
    if (ossmh != null) {
        document.getElementById('ossmh').value = month;
    }
    var ossyh = document.getElementById('ossyh');
    if (ossyh != null) {
        document.getElementById('ossyh').value = year;
    }

    xmlHttp=GetXmlHttpObject();
    if (xmlHttp==null){
        alert ("Browser does not support HTTP Request")
        return
    }

    url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_loadCalendatDetails&month=" + month + "&year=" + year + "&category_id=" + category_id + "&employee_id=" + employee_id + "&vid=" + vid + "&date_from=" + date_from + "&date_to=" + date_to;
    xmlHttp.onreadystatechange=ajax4k;
    xmlHttp.open("GET",url,true)
    xmlHttp.send(null)
}

function prev(live_site,category_id,employee_id,vid,date_from,date_to){
    var month = document.getElementById('month');
    var year = document.getElementById('year');
    month 	  = month.value;
    year	  = year.value;
    var div   = document.getElementById("cal" + month + year);
    div.style.display = "none";
    if(month > 1){
        month = parseInt(month) - 1;
    }else{
        year  = parseInt(year) - 1;
        month = 12;
    }
    document.getElementById('month').value = month;
    document.getElementById('year').value = year;

    var ossm = document.getElementById('ossm');
    if (ossm != null) {
        document.getElementById('ossm').value = month;
    }
    var ossy = document.getElementById('ossy');
    if (ossy != null) {
        document.getElementById('ossy').value = year;
    }

    var ossmh = document.getElementById('ossmh');
    if (ossmh != null) {
        document.getElementById('ossmh').value = month;
    }
    var ossyh = document.getElementById('ossyh');
    if (ossyh != null) {
        document.getElementById('ossyh').value = year;
    }

    xmlHttp=GetXmlHttpObject();
    if (xmlHttp==null){
        alert ("Browser does not support HTTP Request")
        return
    }

    url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_loadCalendatDetails&month=" + month + "&year=" + year + "&category_id=" + category_id + "&employee_id=" + employee_id + "&vid=" + vid + "&date_from=" + date_from + "&date_to=" + date_to;
    //alert(url);
    xmlHttp.onreadystatechange=ajax4k;
    xmlHttp.open("GET",url,true)
    xmlHttp.send(null)
}

function calendarMovingSmall(live_site,category_id,employee_id,vid,date_from,date_to){
    var ossmh = document.getElementById('ossmh');
    var ossyh  = document.getElementById('ossyh');
    sm     = ossmh.value;
    sy     = ossyh.value;
    var month = document.getElementById('month');
    var year  = document.getElementById('year');

    document.getElementById('month').value = sm;
    document.getElementById('year').value = sy;

    xmlHttp=GetXmlHttpObject();
    if (xmlHttp==null){
        alert ("Browser does not support HTTP Request")
        return
    }

    url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_loadCalendatDetails&month=" + sm + "&year=" + sy + "&category_id=" + category_id + "&employee_id=" + employee_id + "&vid=" + vid + "&date_from=" + date_from + "&date_to=" + date_to;
    //alert(url);
    xmlHttp.onreadystatechange=ajax4k;
    xmlHttp.open("GET",url,true)
    xmlHttp.send(null)
}

function changeTimeSlotDate(tstatus,date,sid,tid,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	var live_site = document.getElementById('live_site');
	live_site = live_site.value;
	document.getElementById("selected_item").value = "date" + tid + date;
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_changeTimeSlotDate&tstatus=" + tstatus + "&sid=" + sid + "&tid=" + tid + "&date=" + date;
	xmlHttp.onreadystatechange=ajax4l;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function checkCouponCodeAjax(coupon_code,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	var live_site = document.getElementById('live_site');
	live_site = live_site.value;
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_checkcouponcode&coupon_code=" + coupon_code;
	
	xmlHttp.onreadystatechange=ajax4g;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function updateTempDateAjax(sid,eid,start_time,end_time,value){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	var live_site = document.getElementById('live_site');
	live_site = live_site.value;
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_updatenslots&start_time=" + start_time + "&end_time=" + end_time + "&sid=" + sid + "&eid=" + eid  + "&newvalue=" + value;
	xmlHttp.onreadystatechange=ajax4c;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
	
}

function addtoCart2(live_site,sid,eid,start_time,vid,year, month,day){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}		 
	var date_from = document.getElementById('date_from');
	var date_to   = document.getElementById('date_to');

	var service_time_type = document.getElementById('service_time_type_' + sid);
	service_time_type = service_time_type.value;
	var cansubmit =  1;
	var suff = "";
	if(service_time_type == "1"){
		var nslots = document.getElementById('nslots_' + sid + '_' + eid + '_' + day + '_' + month + '_' + year);
		alert('nslots_' + sid + '_' + eid + '_' + day + '_' + month + '_' + year);
		nslots = nslots.value;
		if(nslots == ""){
			alert("Invalid number");
			document.getElementById('nslots_' + sid + '_' + eid + '_' + day + '_' + month + '_' + year).focus();
			cansubmit = 0;
		}else if(isNaN (nslots)){
			alert("Invalid number");
			document.getElementById('nslots_' + sid + '_' + eid + '_' + day + '_' + month + '_' + year).focus();
			cansubmit = 0;
		}else{
			suff = "&nslots=" + nslots;
		}
	}

	if(cansubmit ==  1){
		var itemid = document.getElementById('Itemid');
		itemid = itemid.value;
		url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_addtocart&update_temp_table=0&start_booking_time=" + start_time + "&sid=" + sid + "&eid=" + eid + "&employee_id=" + eid + "&vid=" + vid + "&date_from=" + date_from.value + "&date_to=" + date_to.value + suff + "&Itemid=" + itemid;
		//alert(url);
		xmlHttp.onreadystatechange=ajax1;
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
}

function addtoCartAjax(start_booking_time,end_booking_time,sid,eid,live_site,additional_information,repeat,vid,category_id,employee_id){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}

	var service_time_type = document.getElementById('service_time_type_' + sid);
	service_time_type = service_time_type.value;
	var cansubmit =  1;
	var suff = "";
	if(service_time_type == "1"){
		var nslots = document.getElementById('nslots_' + sid + '_' + eid);
		nslots = nslots.value;
		if(nslots == ""){
			alert("Invalid number");
			document.getElementById('nslots_' + sid + '_' + eid).focus();
			cansubmit = 0;
		}else if(isNaN (nslots)){
			alert("Invalid number");
			document.getElementById('nslots_' + sid + '_' + eid).focus();
			cansubmit = 0;
		}else{
			suff = "&nslots=" + nslots;
		}
	}
	var	count_services = document.getElementById('count_services');
	if(count_services != null){
		count_services = count_services.value;
	}else{
		count_services = 1;
	}
	var date_from = document.getElementById('date_from');
	var date_to   = document.getElementById('date_to');
	if(cansubmit ==  1){
		var itemid = document.getElementById('Itemid');
		itemid = itemid.value;
		url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_addtocart&start_booking_time=" + start_booking_time + "&end_booking_time=" + end_booking_time + "&sid=" + sid + "&eid=" + eid + "&additional_information=" + additional_information + suff + "&update_temp_table=1&repeat=" + repeat + "&vid=" +  vid + "&category_id=" + category_id + "&employee_id=" + employee_id + "&date_from=" + date_from.value + "&date_to=" + date_to.value + "&Itemid=" + itemid + "&count_services=" + count_services;
		//alert(url);
		xmlHttp.onreadystatechange=ajax1;
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
}

function reselectItem(live_site,sid,eid,start_booking_time){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_reselect&date=" + start_booking_time + "&sid=" + sid + "&eid=" + eid;
	xmlHttp.onreadystatechange=ajax1;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function ajax1() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		responseTxt = xmlHttp.responseText ;
		firstitem   = responseTxt.indexOf('@@@');
		if(firstitem > 0){
			responseTxt = responseTxt.substring(firstitem + 3);
			alert("Duplicate booking time");
		}
		
		var pos = responseTxt.indexOf("1111");
		str1 = responseTxt.substring(pos + 4);
		pos  = str1.indexOf("@3333");
		str2 = str1.substring(0,pos);
		str3 = str1.substring(pos+5);
		pos  = str3.indexOf("2222");
		str4 = str3.substring(0,pos);
		var use_js_popup = document.getElementById('use_js_popup');
		if(str4 != ""){
			//var answer = confirm(str4);
			if(use_js_popup.value == 1){
				//alert(str4);
				  //document.write(str4);
				  document.getElementById("dialogstr4").innerHTML = str4 ; 
				  jQuery(function() {
					jQuery( "#dialogstr4" ).dialog({
					  modal: true
					});
				  });
			}
			//if(answer == 1){
				//var current_link = document.getElementById('current_link');
				//location.href = current_link + "index.php?option=com_osservicesbooking&task=form_step1";
			//}
		}
		str5 = str3.substring(pos+4);
		var using_cart = document.getElementById("using_cart");
		if(using_cart.value == 0){
			if(str2 != "#"){
				location.href = str2;
			}
		}else{
			document.getElementById("cartdiv").innerHTML = str2 ;
		}
		var selected_item = document.getElementById('selected_item');
		//var div = document.getElementById(selected_item.value);
		var div = document.getElementById("maincontentdiv");
		div.innerHTML = str5;
		
		var sid 			= document.getElementById("sid").value;
		var eid 			= document.getElementById("eid").value;
		var calendar_name 	= "repeat_to" + sid + "_" + eid;
		window.addEvent('domready', function(){ $$('dl.tabs').each(function(tabs){ new JTabs(tabs, {}); }); });
		var repeat_to = document.getElementById('repeat_to');
		if(repeat_to != null){
			Calendar._DN = new Array ("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); Calendar._SDN = new Array ("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"); Calendar._FD = 0; Calendar._MN = new Array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); Calendar._SMN = new Array ("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); Calendar._TT = {};Calendar._TT["INFO"] = "About the Calendar"; Calendar._TT["ABOUT"] =
 "DHTML Date/Time Selector\n" +
 "(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the � and � buttons to select year\n" +
"- Use the < and > buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

			Calendar._TT["PREV_YEAR"] = "Click to move to the previous year. Click and hold for a list of years."; Calendar._TT["PREV_MONTH"] = "Click to move to the previous month. Click and hold for a list of the months."; Calendar._TT["GO_TODAY"] = "Go to today"; Calendar._TT["NEXT_MONTH"] = "Click to move to the next month. Click and hold for a list of the months."; Calendar._TT["NEXT_YEAR"] = "Click to move to the next year. Click and hold for a list of years."; Calendar._TT["SEL_DATE"] = "Select a date."; Calendar._TT["DRAG_TO_MOVE"] = "Drag to move"; Calendar._TT["PART_TODAY"] = "Today"; Calendar._TT["DAY_FIRST"] = "Display %s first"; Calendar._TT["WEEKEND"] = "0,6"; Calendar._TT["CLOSE"] = "Close"; Calendar._TT["TODAY"] = "Today"; Calendar._TT["TIME_PART"] = "(Shift-)Click or Drag to change the value."; Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d"; Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e"; Calendar._TT["WK"] = "wk"; Calendar._TT["TIME"] = "Time:";
			
				window.addEvent('domready', function() {Calendar.setup({
					// Id of the input field
					inputField: calendar_name,
					// Format of the input field
					ifFormat: "%Y-%m-%d",
					// Trigger for the calendar (button ID)
					button: "repeat_to_img",
					// Alignment (defaults to "Bl")
					align: "Tl",
					singleClick: true,
					firstDay: 0
					});});
		}
		var venue_available = document.getElementById('venue_available');
		if(venue_available != null){
			if(venue_available.value == 1){
				window.addEvent('domready', function() {
					SqueezeBox.initialize({});
					SqueezeBox.assign($$('a.osmodal'), {
						parse: 'rel'
					});
				});
			}
		}
		
		window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
		});

		
	} 
}

function closeDialog(){
	jQuery( "#dialogstr4" ).dialog("close");
}

function ajax1copied() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		responseTxt = xmlHttp.responseText ;
		firstitem   = responseTxt.indexOf('@@@');
		if(firstitem > 0){
			responseTxt = responseTxt.substring(firstitem + 3);
			alert("Duplicate booking time");
		}
		
		var pos = responseTxt.indexOf("1111");
		str1 = responseTxt.substring(pos + 4);
		pos  = str1.indexOf("@3333");
		str2 = str1.substring(0,pos);
		str3 = str1.substring(pos+5);
		pos  = str3.indexOf("2222");
		str4 = str3.substring(0,pos);
		var use_js_popup = document.getElementById('use_js_popup');
		if(str4 != ""){
			//var answer = confirm(str4);
			if(use_js_popup.value == 1){
				alert(str4);
			}
			//if(answer == 1){
				//var current_link = document.getElementById('current_link');
				//location.href = current_link + "index.php?option=com_osservicesbooking&task=form_step1";
			//}
		}
		str5 = str3.substring(pos+4);
		var using_cart = document.getElementById("using_cart");
		//if(using_cart.value == 1){
			//location.href = str2;
		//}else{
			document.getElementById("cartdiv").innerHTML = str2 ;
		//}
		var selected_item = document.getElementById('selected_item');
		//var div = document.getElementById(selected_item.value);
		var div = document.getElementById("maincontentdiv");
		div.innerHTML = str5;
		
		var sid 			= document.getElementById("sid").value;
		var eid 			= document.getElementById("eid").value;
		var calendar_name 	= "repeat_to" + sid + "_" + eid;
		window.addEvent('domready', function(){ $$('dl.tabs').each(function(tabs){ new JTabs(tabs, {}); }); });
		var repeat_to = document.getElementById('repeat_to');
		if(repeat_to != null){
			Calendar._DN = new Array ("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); Calendar._SDN = new Array ("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"); Calendar._FD = 0; Calendar._MN = new Array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); Calendar._SMN = new Array ("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); Calendar._TT = {};Calendar._TT["INFO"] = "About the Calendar"; Calendar._TT["ABOUT"] =
 "DHTML Date/Time Selector\n" +
 "(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the � and � buttons to select year\n" +
"- Use the < and > buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

			Calendar._TT["PREV_YEAR"] = "Click to move to the previous year. Click and hold for a list of years."; Calendar._TT["PREV_MONTH"] = "Click to move to the previous month. Click and hold for a list of the months."; Calendar._TT["GO_TODAY"] = "Go to today"; Calendar._TT["NEXT_MONTH"] = "Click to move to the next month. Click and hold for a list of the months."; Calendar._TT["NEXT_YEAR"] = "Click to move to the next year. Click and hold for a list of years."; Calendar._TT["SEL_DATE"] = "Select a date."; Calendar._TT["DRAG_TO_MOVE"] = "Drag to move"; Calendar._TT["PART_TODAY"] = "Today"; Calendar._TT["DAY_FIRST"] = "Display %s first"; Calendar._TT["WEEKEND"] = "0,6"; Calendar._TT["CLOSE"] = "Close"; Calendar._TT["TODAY"] = "Today"; Calendar._TT["TIME_PART"] = "(Shift-)Click or Drag to change the value."; Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d"; Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e"; Calendar._TT["WK"] = "wk"; Calendar._TT["TIME"] = "Time:";
			
				window.addEvent('domready', function() {Calendar.setup({
					// Id of the input field
					inputField: calendar_name,
					// Format of the input field
					ifFormat: "%Y-%m-%d",
					// Trigger for the calendar (button ID)
					button: "repeat_to_img",
					// Alignment (defaults to "Bl")
					align: "Tl",
					singleClick: true,
					firstDay: 0
					});});
		}
		var venue_available = document.getElementById('venue_available');
		if(venue_available != null){
			if(venue_available.value == 1){
				window.addEvent('domready', function() {
					SqueezeBox.initialize({});
					SqueezeBox.assign($$('a.osmodal'), {
						parse: 'rel'
					});
				});
			}
		}
		
		window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
		});

		
	} 
}

function showInforFormAjax(live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&task=ajax_showinfor";
	xmlHttp.onreadystatechange=ajax1b;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function ajax1b() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("maincontentdiv").innerHTML = xmlHttp.responseText ;
	} 
}

function confirmBookingAjax(order_name,order_email,order_phone,order_country,order_city,order_state,order_zip,order_address,live_site,fields,notes,paymentMethod,x_card_num,x_card_code,card_holder_name,exp_year,exp_month,card_type){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_confirminfo&order_name=" + order_name + "&order_email=" + order_email + "&order_phone=" + order_phone + "&order_country=" + order_country + "&order_city=" + order_city + "&order_state=" + order_state + "&order_zip=" + order_zip + "&order_address=" + order_address + "&fields=" + fields + "&notes=" + notes + "&select_payment=" + paymentMethod + "&x_card_num=" + x_card_num + "&x_card_code=" + x_card_code + "&card_holder_name=" + card_holder_name + "&exp_year=" + exp_year + "&exp_month=" + exp_month  + "&card_type=" + card_type;
	xmlHttp.onreadystatechange=ajax1b;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function createBookingAjax(order_name,order_email,order_phone,order_country,order_city,order_state,order_zip,order_address,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&task=ajax_createOrder&order_name=" + order_name + "&order_email=" + order_email + "&order_phone=" + order_phone + "&order_country=" + order_country + "&order_city=" + order_city + "&order_state=" + order_state + "&order_zip=" + order_zip + "&order_address=" + order_address; 
	xmlHttp.onreadystatechange=ajax1b;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function loadServicesAjax(live_site,year,month,day){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	var category_id		= document.getElementById('category_id');
	var employee_id		= document.getElementById('employee_id');
	var vid 			= document.getElementById('vid');
	var sid				= document.getElementById('sid');
	var count_services	= document.getElementById('count_services');
	var catid = 0;
	var eid   = 0;
	if(category_id != null){
		catid = category_id.value
	}else{
		catid = "";
	}


	if(employee_id != null){
		eid = employee_id.value
	}else{
		eid = "";
	}
	
	if(sid != null){
		sid = sid.value
	}else{
		sid = "";
	}
	
	if(vid != null){
		vid = vid.value
	}else{
		vid = "";
	}

	if(count_services != null){
		count_services = count_services.value;
	}else{
		count_services = "";
	}

	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_loadServices&year=" + year + "&month=" + month + "&day=" + day + "&category_id=" + catid + "&employee_id=" + eid + "&vid=" + vid + "&sid=" + sid + "&count_services=" + count_services;  
	xmlHttp.onreadystatechange=ajax1e;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function ajax1e() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		//document.getElementById("cartbox").style.display = "block";
		//document.getElementById("cartbox1").style.display = "none";
		//document.getElementById("servicebox").style.display = "none";
		document.getElementById("maincontentdiv").innerHTML = xmlHttp.responseText ;
		var sid 			= document.getElementById("sid").value;
		var eid 			= document.getElementById("eid").value;
		var calendar_name 	= "repeat_to" + sid + "_" + eid;
		window.addEvent('domready', function(){ $$('dl.tabs').each(function(tabs){ new JTabs(tabs, {}); }); });
		var repeat_to = document.getElementById('repeat_to');
		if(repeat_to != null){
			Calendar._DN = new Array ("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"); Calendar._SDN = new Array ("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"); Calendar._FD = 0; Calendar._MN = new Array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"); Calendar._SMN = new Array ("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"); Calendar._TT = {};Calendar._TT["INFO"] = "About the Calendar"; Calendar._TT["ABOUT"] =
 "DHTML Date/Time Selector\n" +
 "(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" +
"For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
"Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
"\n\n" +
"Date selection:\n" +
"- Use the � and � buttons to select year\n" +
"- Use the < and > buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

			Calendar._TT["PREV_YEAR"] = "Click to move to the previous year. Click and hold for a list of years."; Calendar._TT["PREV_MONTH"] = "Click to move to the previous month. Click and hold for a list of the months."; Calendar._TT["GO_TODAY"] = "Go to today"; Calendar._TT["NEXT_MONTH"] = "Click to move to the next month. Click and hold for a list of the months."; Calendar._TT["NEXT_YEAR"] = "Click to move to the next year. Click and hold for a list of years."; Calendar._TT["SEL_DATE"] = "Select a date."; Calendar._TT["DRAG_TO_MOVE"] = "Drag to move"; Calendar._TT["PART_TODAY"] = "Today"; Calendar._TT["DAY_FIRST"] = "Display %s first"; Calendar._TT["WEEKEND"] = "0,6"; Calendar._TT["CLOSE"] = "Close"; Calendar._TT["TODAY"] = "Today"; Calendar._TT["TIME_PART"] = "(Shift-)Click or Drag to change the value."; Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d"; Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e"; Calendar._TT["WK"] = "wk"; Calendar._TT["TIME"] = "Time:";
			
				window.addEvent('domready', function() {Calendar.setup({
					// Id of the input field
					inputField: calendar_name,
					// Format of the input field
					ifFormat: "%Y-%m-%d",
					// Trigger for the calendar (button ID)
					button: "repeat_to_img",
					// Alignment (defaults to "Bl")
					align: "Tl",
					singleClick: true,
					firstDay: 0
					});});
		}
		var venue_available = document.getElementById('venue_available');
		if(venue_available != null){
			if(venue_available.value == 1){
				window.addEvent('domready', function() {
					SqueezeBox.initialize({});
					SqueezeBox.assign($$('a.osmodal'), {
						parse: 'rel'
					});
				});
			}
		}
		
		window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
		});
		

		
	}else{
		var live_site = document.getElementById('live_site');
		live_site = live_site.value;
		document.getElementById("maincontentdiv").innerHTML = "<img src='" + live_site +"/components/com_osservicesbooking/style/images/loading.gif'>";
	}
}


function selectEmployeeAjax(live_site,year,month,day,sid){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&task=ajax_selectEmployee&year=" + year + "&month=" + month + "&day=" + day + "&sid=" + sid; 
	xmlHttp.onreadystatechange=ajax1d;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function ajax1d(){
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		responseTxt = xmlHttp.responseText ;
		var pos = responseTxt.indexOf("@@@@");
		str1 = responseTxt.substring(0,pos);
		str2 = responseTxt.substring(pos + 4);
		document.getElementById("maincontentdiv").innerHTML = str1 ;
		document.getElementById("cartbox").style.display = "none";
		document.getElementById("cartbox1").style.display = "block";
		document.getElementById("servicebox").style.display = "block";
		document.getElementById("servicebox").innerHTML = str2 ;
	}else{
		var live_site = document.getElementById('live_site');
		live_site = live_site.value;
		document.getElementById("maincontentdiv").innerHTML = "<img src='" + live_site +"/components/com_osservicesbooking/style/images/loading.gif'>";
	}
}

function removeItemAjax(itemid,live_site,sid,start_time,end_time,eid,category_id,employee_id,vid){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	var select_day = document.getElementById('select_day');
	var select_month = document.getElementById('select_month');
	var select_year = document.getElementById('select_year');

	var date_from = document.getElementById('date_from');
	var date_to = document.getElementById('date_to');
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&task=ajax_removeItem&sid=" + sid + "&start_time=" + start_time + "&end_time=" + end_time + "&eid=" + eid + "&itemid=" + itemid + "&category_id=" + category_id + "&employee_id=" + employee_id + "&vid=" + vid + "&select_day=" + select_day.value + "&select_month=" + select_month.value + "&select_year=" + select_year.value + "&date_from=" + date_from.value + "&date_to=" + date_to.value;
	xmlHttp.onreadystatechange=ajax1copied;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function ajax1g(){
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		responseTxt = xmlHttp.responseText ;
		document.getElementById("cartdiv").innerHTML = responseTxt;
	} 
}

function saveNewOptionAjax(live_site,field_option,additional_price,field_id){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=fields_addOption&field_id=" + field_id + "&field_option=" + field_option + "&additional_price=" + additional_price; 
	xmlHttp.onreadystatechange=ajax3a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function ajax3a(){
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		var field_option = document.getElementById('field_option');
		var additional_price = document.getElementById('additional_price');
		field_option.value = "";
		additional_price.value = "";
		responseTxt = xmlHttp.responseText ;
		document.getElementById("field_option_div").innerHTML = responseTxt;
	} 
}

function removeFieldOptionAjax(live_site,field_id){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=fields_removeFieldOption&field_id=" + field_id; 
	xmlHttp.onreadystatechange=ajax3a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function saveEditOptionAjax(live_site,field_option,additional_price,ordering,field_id,optionid){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=fields_editOption&field_id=" + field_id + "&field_option=" + field_option + "&additional_price=" + additional_price + "&optionid=" + optionid + "&ordering=" + ordering; 
	xmlHttp.onreadystatechange=ajax3a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function saveCustomPrice(live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	sid			= document.getElementById('id').value;
	cstart		= document.getElementById('cstart').value;
	cend		= document.getElementById('cend').value;
	camount		= document.getElementById('camount').value;
	url = live_site + "administrator/index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=service_addcustomprice&sid=" + sid + "&cend=" + cend + "&cstart=" + cstart+ "&camount=" + camount; 
	xmlHttp.onreadystatechange=ajax4a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function removeCustomPrice(id,sid,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	url = live_site + "administrator/index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=service_removecustomprice&id=" + id + "&sid=" + sid; 
	xmlHttp.onreadystatechange=ajax4a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}


function saveCustomBreakTime(live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	sid = document.getElementById('sid').value;
	eid = document.getElementById('eid').value;
	bdate = document.getElementById('bdate').value;
	bstart = document.getElementById('bstart').value;
	bend = document.getElementById('bend').value;
	url = live_site + "administrator/index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=employee_addcustombreaktime&sid=" + sid + "&eid=" + eid + "&bdate=" + bdate+ "&bstart=" + bstart+ "&bend=" + bend; 
	xmlHttp.onreadystatechange=ajax4a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function removeCustomBreakDate(id,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	sid = document.getElementById('sid').value;
	eid = document.getElementById('eid').value;
	url = live_site + "administrator/index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=employee_removecustombreaktime&sid=" + sid + "&eid=" + eid + "&id=" + id; 
	xmlHttp.onreadystatechange=ajax4a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function removeBreakDateAjax(rid,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "administrator/index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=employee_removeRestday&rid=" + rid; 
	xmlHttp.onreadystatechange=ajax4a;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function removeTempTimeSlotAjax(id,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_removetemptimeslot&id=" + id; 
	xmlHttp.onreadystatechange=ajax4b;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function removerestdayAjax(day,eid,item,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_removerestdayAjax&day=" + day + "&eid=" + eid + "&item=" + item; 
	xmlHttp.onreadystatechange=ajax4d;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function addrestdayAjax(day,eid,item,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_addrestdayAjax&day=" + day + "&eid=" + eid + "&item=" + item; 
	xmlHttp.onreadystatechange=ajax4d;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function removeOrderItemAjax(order_id,id,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_removeOrderItemAjax&id=" + id + "&order_id=" + order_id; 
	xmlHttp.onreadystatechange=ajax4e;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function changeCheckinAjax(order_id,id,live_site){
    xmlHttp=GetXmlHttpObject();
    if (xmlHttp==null){
        alert ("Browser does not support HTTP Request")
        return
    }
    url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_changeCheckinOrderItemAjax&id=" + id + "&order_id=" + order_id;
    xmlHttp.onreadystatechange=ajax4t;
    xmlHttp.open("GET",url,true)
    xmlHttp.send(null)
}

function removeOrderItemAjaxCalendar(order_id,order_item_id,i,date,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	
	url = live_site + "index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=ajax_removeOrderItemAjaxCalendar&id=" + order_item_id + "&order_id=" + order_id + "&i=" + i + "&date=" + date; 
	xmlHttp.onreadystatechange=ajax4f;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function updateOrderStatusAjax(order_id,live_site){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	var	current_order_id = document.getElementById('current_order_id');
	current_order_id.value = order_id;
	var orderstatus = document.getElementById('orderstatus' + order_id);
	orderstatus = orderstatus.value;
	url = live_site + "administrator/index.php?option=com_osservicesbooking&tmpl=component&no_html=1&task=orders_updateNewOrderStatus&order_id=" + order_id + "&new_status=" + orderstatus; 
	xmlHttp.onreadystatechange=ajax4i;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function checkingVersion(current_version){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
		 alert ("Browser does not support HTTP Request")
	 	 return
	}
	live_site = document.getElementById('live_site');
	live_site = live_site.value;
	url = live_site + "index.php?option=com_osservicesbooking&no_html=1&tmpl=component&task=ajax_checkingVersion&current_version=" + current_version;
	xmlHttp.onreadystatechange=ajax4i1;
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function ajax4i1() {
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("oschecking_div").innerHTML = xmlHttp.responseText ;
	}else{
		var live_site = document.getElementById('live_site');
        live_site = live_site.value;
        document.getElementById("oschecking_div").innerHTML = "<div class='icon'><a href='#'><img src='" + live_site +"/administrator/components/com_osservicesbooking/asset/images/updated_failure.png'><span>Checking..</span></a></div>";
	}
}



function ajax4i() {
	var	current_order_id = document.getElementById('current_order_id');
	current_order_id = current_order_id.value;
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("div_orderstatus" + current_order_id).innerHTML = xmlHttp.responseText ;
	}else{
		var live_site = document.getElementById('live_site');
        live_site = live_site.value;
        document.getElementById("div_orderstatus" + current_order_id).innerHTML = "<img src='" + live_site +"/components/com_osservicesbooking/style/images/loading.gif'>";
	}
}



function ajax4a() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		//var oid = document.getElementById('oid').value;
		document.getElementById("rest_div").innerHTML = xmlHttp.responseText ;
	}else{
		var live_site = document.getElementById('live_site');
        live_site = live_site.value;
        document.getElementById("rest_div").innerHTML = "<img src='" + live_site +"/components/com_osservicesbooking/style/images/loading.gif'>";
	}
}

function ajax4b() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("bookingerrDiv").innerHTML = xmlHttp.responseText ;
	}
}

function ajax4c() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		document.getElementById("divtemp").innerHTML = xmlHttp.responseText ;
	}
}

function ajax4d() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		var item_value = document.getElementById('current_item_value').value;
		document.getElementById("a" + item_value).innerHTML = xmlHttp.responseText ;
	}
}

function ajax4e() {
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		var oid = document.getElementById('oid').value;
		document.getElementById("order" + oid).innerHTML = xmlHttp.responseText ;
	}
}

function ajax4t(){
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){
        var order_item_id = document.getElementById('order_item_id').value;
        document.getElementById("order" + order_item_id).innerHTML = xmlHttp.responseText ;
    }
}


function ajax4f() {
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		var oid = document.getElementById('current_td').value;
		document.getElementById("a" + oid).innerHTML = xmlHttp.responseText ;
		window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
		});
		function keepAlive() {	var myAjax = new Request({method: "get", url: "index.php"}).send();} window.addEvent("domready", function(){ keepAlive.periodical(840000); });
jQuery(document).ready(function() {
			jQuery('.hasTooltip').tooltip({});
		});
	}
}

function ajax4g() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		responseText = xmlHttp.responseText ;
		var pos = responseText.indexOf("@return@");
		responseText = responseText.substring(pos+8);
		pos = responseText.indexOf("||");
		var prefix = responseText.substring(0,pos);
		var xpos = prefix.indexOf("XXX");
		var discount100 = prefix.substring(xpos + 3);
		discount100 = parseInt(discount100);
		prefix = prefix.substring(0,xpos);
		var res    = responseText.substring(pos + 2);
		if((prefix != "0") && (prefix != "9999")){
			document.getElementById('coupon_id').value = prefix;
			document.getElementById('discount_100').value = discount100;
			
		}
		document.getElementById('couponcodediv').innerHTML = res;
	}
}

function ajax4l(){
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		responseText = xmlHttp.responseText ;
		var selected_item = document.getElementById('selected_item').value;
		var temp = document.getElementById(selected_item);
		temp.innerHTML = responseText;
	}
}

function ajax4k(){
    if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){
        responseText = xmlHttp.responseText ;
        var temp = document.getElementById('calendardetails');
        temp.innerHTML = responseText;
    }else{
        var live_site = document.getElementById('live_site');
        live_site = live_site.value;
        document.getElementById("calendardetails").innerHTML = "<img src='" + live_site +"/components/com_osservicesbooking/style/images/loading.gif'>";
    }
}

function GetXmlHttpObject(){
	var xmlHttp = null;
	try{
		xmlHttp = new XMLHttpRequest();
	}
	catch (e)
	{
		try{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}