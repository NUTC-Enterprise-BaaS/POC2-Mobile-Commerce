/*
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
var statesListBackup;
if(typeof(techjoomla) == 'undefined') {
	var techjoomla = {};
}
if(typeof techjoomla.jQuery == "undefined")
	techjoomla.jQuery = jQuery;

var sa = {
	ajax: function(url, callback) {
		techjoomla.jQuery.ajax({
			url: 'index.php',
			data: url
		}).done(callback);
	},

	/*Function to check integer */
	checkForZeroAndAlpha: function(ele, allowedChar, msg) {
		if(ele.value && ele.value != 0) {
			sa.ad_checkforalpha(ele, allowedChar, msg)
		} else if(ele.value == 0) {
			alert(Joomla.JText._('COM_SOCIALADS_PAYMENT_MIN_AMT_SHOULD_GREATER_MSG'));
			ele.value = '';
		}
	},

	ad_checkforalpha: function(el, allowed_ascii, enter_numerics) {
		allowed_ascii = (typeof allowed_ascii === "undefined") ? "" : allowed_ascii;
		var i = 0;
		for(i = 0; i < el.value.length; i++) {
			if((el.value.charCodeAt(i) <= 47 || el.value.charCodeAt(i) >= 58) || (el.value.charCodeAt(i) == 45)) {
				/*allowing for phone no at first char*/
				if(allowed_ascii == el.value.charCodeAt(i)) {
					var temp = 1;
				} else {
					alert(enter_numerics);
					el.value = el.value.substring(0, i);
					return false;
				}

			}
		}
		return true;
	},

	create: {
		/*Initialize create ad js*/
		initCreateJs: function() {
			techjoomla.jQuery(document).ready(function() {
				techjoomla.jQuery("#url2").blur(function(){
					jQuery("#url2").css("border-color", "");
				});
				var id = document.getElementById("ad_creator_id").value;
				/*Get selected user social promoter plugins*/
				sa.create.getPromoterPlugins(id);
				/*Call steps js code*/
				sa.create.initStepsJs();
			});
		},

		initStepsJs: function() {
			techjoomla.jQuery(document).ready(function() {
				techjoomla.jQuery('#btnWizardNext').on('click', function() {
					techjoomla.jQuery('#MyWizard').wizard('next', 'foo');
				});

				/*vm: to avoid repetative mail while editing confirm ads*/
				/*@TODO - manoj - chk for frontend*/
				/*var sa_sentApproveMail = 0;*/
				/*@TODO end*/
				techjoomla.jQuery('#MyWizard').on('change', function(e, data) {
					if(techjoomla.jQuery("#sa_BillForm").length) {
						values = techjoomla.jQuery('#adsform,#sa_BillForm').serialize();
					} else {
						values = techjoomla.jQuery('#adsform').serialize();
					}

					/*Get tab ID */
					var stepId = techjoomla.jQuery("#sa-steps li[class='active']")[0].id;

					/** Check if ad more credit btn value is set to 1*/
					var isCheckedAdMoreCredit = 0 ;

					if (addMoreCredit == 1)
					{
						isCheckedAdMoreCredit = document.querySelector("input[name='add_more_credit']:checked").value;
					}

					if(data.direction === 'next') {
						/*Check if unlimited ad radio value */
						var unlimited_ad = '';
						if(techjoomla.jQuery(":radio[name=unlimited_ad]").length > 0)
							unlimited_ad = techjoomla.jQuery("input[name=unlimited_ad]:radio:checked").val();

						/*First step1 validation */
						if(!sa.create.validateCreateForm(stepId, unlimited_ad)) {
							return false;
						}

						loadingImage();

						//var stepId = data.step;  // added by vm
						techjoomla.jQuery.ajax({
							url: base_url + 'index.php?option=com_sa&task=create.autoSave&stepId=' + stepId + '&tmpl=component',
							/*url: '?option=com_socialads&controller=buildad&task=autoSave&stepId=' + stepId + '&tmpl=component&format=raw&sa_sentApproveMail=' + sa_sentApproveMail,*/
							type: 'POST',
							async: false,
							data: values,
							dataType: 'json',
							beforeSend: function() {
								/*//console.log('___ befor send *');
								techjoomla.jQuery('#confirm-order').after('<div class=\"com_jgive_ajax_loading\"><div class=\"com_jgive_ajax_loading_text\">".JText::_('COM_JGIVE_LOADING_PAYMET_FORM_MSG')."</div><img class=\"com_jgive_ajax_loading_img\" src=\"".JUri::root()."media/com_sa/images/ajax.gif\"></div>');
								// CODE TO HIDE EDIT LINK
								jgive_hideAllEditLinks(); */
							},
							complete: function() {
								/*
								techjoomla.jQuery('#jgive_order_details_tab').show()
								techjoomla.jQuery('.com_jgive_ajax_loading').remove();
								jgive_showAllEditLinks();
								*/
							},
							success: function(response) {
								/*@TODO - manoj - chk for frontend */
								/*var sa_special_access = techjoomla.jQuery("#sa_special_access").val();

								if(response.sa_sentApproveMail == 1) {
									sa_sentApproveMail = 1;
								}*/
								//redirect to the manage ad after geo targetting data fill up
								if(stepId == 'ad-design' && data.direction === 'next') {
									var altadbutton = 0;
									var affiliate = 0;

									if(techjoomla.jQuery('#altadbutton').length) {
										if(document.getElementById("altadbutton").checked == true) {
											altadbutton = 1;
										}
									}

									if(techjoomla.jQuery('#adtype').length) {
										if(document.getElementById('adtype').value == 'affiliate') {
											affiliate = 1;
										}
									}

									if((altadbutton == 1) || (affiliate == 1)) {
										window.location.assign(base_url + "?option=com_socialads&task=create.cleanup");
										return e.preventDefault();
									}
									/* Added by snehal for user selected campaign list */
									if(isAdmin == 1)
									{
										var id = document.getElementById("ad_creator_id_ad").value;
										if(selected_pricing_mode === "wallet_mode"){
											/*Get selected user campaign*/
											sa.create.getUserCampaign(id);
										}
									}
								}
								//Check if unlimited ad radio value

								//Unlimited ad =Yes then redirect to the manage ad after geo targetting data fill up
								if(stepId == 'ad-pricing' && data.direction === 'next') {
									if(unlimited_ad == 1) {
										window.location.assign(base_url + "?option=com_socialads&task=create.cleanup");
										return e.preventDefault();
									}

									//Select newly added campaign
									if(response['camp_id']) {
										newCampaignSelect(response['camp_id'])
									}

								}

								//IF ad payment is confrimed then allow only edit of ad desing & geo targeting ()

								/** Get Active Step **/
								var active_step = techjoomla.jQuery("#"+stepId).next();
								active_step = active_step.attr("id");

								if((stepId == 'ad-targeting' && allowWholeAdEdit == 0 ) || (showTargeting == 0 && allowWholeAdEdit == 0))
								{
									// Ad payment already done, user don't want to pay again so close on second step
									if (addMoreCredit == 1 && isCheckedAdMoreCredit == 0)
									{
										window.location.assign(base_url + "?option=com_socialads&task=create.cleanup");
										return e.preventDefault();
									}
									// Ad payment not done so user can edit full ad
									else if (allowWholeAdEdit == 1)
									{
										window.location.assign(base_url + "?option=com_socialads&task=create.cleanup");
										return e.preventDefault();
									}
								}

								// Add billing details
								if(response['billingDetail']) {
									techjoomla.jQuery('.sa_build_ad_billing').html(response['billingDetail']);
								}

								// add payment relreated detail
								if(response['payAndReviewHtml']) {
									techjoomla.jQuery('#ad_reviewAndPayHTML').html(response['payAndReviewHtml']);
								}

								if(response['adPreviewHtml']) {
									techjoomla.jQuery('#adPreviewHtml').html(response['adPreviewHtml']);
								}
								techjoomla.jQuery('#' + stepId + '-error').hide('slow');
							},
							error: function(jqXHR, textStatus, errorThrown) {
								/*Notify the user so he might not wonder.*/
								alert('Something went wrong! Please try again.');
								techjoomla.jQuery('#' + stepId + '-error').show('slow');
								/*techjoomla.jQuery('#result').html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');*/
								console.log('jqXHR:');
								console.log(jqXHR);
								console.log('textStatus:');
								console.log(textStatus);
								console.log('errorThrown:');
								console.log(errorThrown);
								return e.preventDefault();
							}
						});

						setTimeout(function() {
							hideImage()
						}, 10);
					}

					// Scroll to top
					techjoomla.jQuery('html,body').animate({
						scrollTop: techjoomla.jQuery("#sa-steps").offset().top
					});

					if(data.step === 1 && data.direction === 'next') {
						// return e.preventDefault();
					}
				});

				techjoomla.jQuery('#MyWizard').on('changed', function(e, data) {

					// The save & exit button remains same even if we navigate to first tab hence added code
					sa.create.changenexttoexit(0);

					var thisactive = techjoomla.jQuery("#sa-steps li[class='active']");
					stepthisactive = thisactive[0].id;
					if(stepthisactive == techjoomla.jQuery("#sa-steps li").first().attr('id'))
						techjoomla.jQuery(".sa-form #btnWizardPrev").hide();
					else
						techjoomla.jQuery(".sa-form #btnWizardPrev").show();

					/* In case of page refresh*/
					if(stepthisactive == techjoomla.jQuery("#sa-steps li").last().attr('id')) {
						techjoomla.jQuery(".sa-form .prev_next_wizard_actions").hide();
						var prev_button_html = '<button id="btnWizardPrev1" onclick="techjoomla.jQuery(\'#MyWizard\').wizard(\'previous\');"	type="button" class="btn btn-prev" > <i class="icon-circle-arrow-left icon-white"></i>Prev</button>';

						if(stepthisactive == "ad-summery") {
							techjoomla.jQuery('#sa_payHtmlDiv div.form-actions').prepend(prev_button_html);
							techjoomla.jQuery('#sa_payHtmlDiv div.form-actions input[type="submit"]').addClass('pull-right');
						}
						if(stepthisactive == "ad-review") {
							techjoomla.jQuery('.ad_reviewAdmainContainer div.form-actions').prepend(prev_button_html);
						}
					} else
						techjoomla.jQuery(".sa-form .prev_next_wizard_actions").show();

					var unlimited_ad_checked = techjoomla.jQuery("input[name=unlimited_ad]:radio:checked").val();

					if((stepthisactive == 'ad-targeting' && allowWholeAdEdit == 0) || (stepthisactive == 'ad-pricing' && unlimited_ad_checked == 1))
					{
						/** Check if ad more credit btn value is set to 1*/
						var isCheckedAdMoreCredit = 0 ;

						if (addMoreCredit == 1)
						{
							isCheckedAdMoreCredit = document.querySelector("input[name='add_more_credit']:checked").value;
						}

						if (addMoreCredit == 1 && isCheckedAdMoreCredit == 1)
						{
							sa.create.changenexttoexit(0);
						}
						else if (allowWholeAdEdit == 1)
						{
							sa.create.changenexttoexit(1);
						}
						else
						{
							sa.create.changenexttoexit(1);
						}
					}
				});
				techjoomla.jQuery('#MyWizard').on('finished', function(e, data) {});
				techjoomla.jQuery('#btnWizardPrev').on('click', function() {
					techjoomla.jQuery('#MyWizard').wizard('previous');
				});

				/*
				 techjoomla.jQuery('#btnWizardNext').on('click', function()
				{
					techjoomla.jQuery('#MyWizard').wizard('next','foo');
				});
				*/

				techjoomla.jQuery('#btnWizardStep').on('click', function() {
					var item = techjoomla.jQuery('#MyWizard').wizard('selectedItem');
				});
				techjoomla.jQuery('#MyWizard').on('stepclick', function(e, data) {
					if(data.step === 1) {
						// return e.preventDefault();
					}
				});

				// optionally navigate back to 2nd step
				techjoomla.jQuery('#btnStep2').on('click', function(e, data) {
					techjoomla.jQuery('[data-target=#step2]').trigger("click");
				});

				sa.create.calculateTotal();
			});
		},

		calculateReach: function(){
			var targetfields=techjoomla.jQuery('.sa-fields-inputbox').serializeArray();
			var estimated_reach=techjoomla.jQuery('#config_estimated_reach').val();

			techjoomla.jQuery.ajax({
				type: 'POST',
				url: sa_base_url + 'index.php?option=com_sa&task=create.calculateReach',
				data: targetfields,
				dataType: 'json',
				success: function(data){
					if (data==null){
						return;
					}
					var totalreach = 0;
					if (isNaN(estimated_reach)){
						estimated_reach = 0;
					}
					var reach = parseInt(estimated_reach) + parseInt(data.reach);
					if(parseInt(reach)==0){
						totalreach=0;
					}
					else{
						totalreach=reach;
					}
					techjoomla.jQuery('#estimated_reach').html(Joomla.JText._('COM_SOCIALADS_SOCIAL_ESTIMATED_REACH_HEAD') + ' ' + totalreach + '  ' + Joomla.JText._('COM_SOCIALADS_SOCIAL_ESTIMATED_REACH_END'));
				}
			});
		},

		cancelCreate: function (){
			if(isAdmin == 1)
			{
				if (confirm(Joomla.JText._('COM_SOCIALADS_CANCEL_AD')) === true){
					window.location.assign(base_url + "index.php?option=com_socialads&view=forms");
				}
				else{
				return false;
				}
			}
			else
			{
				if (confirm(Joomla.JText._('COM_SOCIALADS_CANCEL_AD')) === true){
				window.location.assign(cancelUrl);
				}
				else{
					return false;
				}
			}
		},

		getUserCampaign: function(userid) {
			jQuery.ajax({
				url: base_url + 'index.php?option=com_sa&task=create.getUserCampaigns&userid=' + userid,
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					var op = "<option value=''>" + Joomla.JText._('COM_SOCIALADS_SELECT_CAMPAIGN') + "</option>";
					select = techjoomla.jQuery('#ad_campaign');
					select.find('option').remove().end();
					select.append(op);

					var option, index;

					for(index = 0; index < data.length; ++index) {
						var option = data[index];
						var op = "<option value=" + option['id'] + ">" + option['campaign'] + '</option>';
						techjoomla.jQuery('#ad_campaign').append(op);
					}
					jQuery("select").trigger("liszt:updated");
				},
				error: function() {
					console.log("Someting went wrong");
				}
			});
		},

		/*vm:*/
		paymentListShowHide: function (){
			/*Get the DOM reference of bill details*/
			var billEle = document.getElementById("sa_paymentlistWrapper");

			if(document.getElementById('sa_termsCondCk').checked){
				billEle.style.display = "block";
			}
			else{
				/*if not visible then show*/
				billEle.style.display = "none";
			}
		},

		/*function for the ad-type change and sort out the ad zones.*/
		/*selected_pricingmode depends on the pricing mode selected. (0- pay per ad) (1-Ad wallet mode).*/
		changeAdType: function() {
			if(document.getElementById('adtype').value == '') {
				alert('There are no Zones created for this Ad type!!');
				return;
			}
			techjoomla.jQuery.ajax({
				url: sa_base_url + 'index.php?option=com_sa&task=create.getZones&a_type=' + document.getElementById('adtype').value,
				type: 'GET',
				dataType: "json",
				success: function(data) {

					techjoomla.jQuery("#zone").html('');
					var ad_zone_id = (document.getElementById('ad_zone_id').value);

					if(data != '') {
						for(i = 0; i < data.length; i++) {
							if(parseInt(data[i].zone_id) == parseInt(ad_zone_id)) {
								techjoomla.jQuery("#zone").append('<option value="' + data[i].zone_id + '" selected>' + data[i].zone_name + '</option>');
							} else {
								techjoomla.jQuery("#zone").append('<option value="' + data[i].zone_id + '" >' + data[i].zone_name + '</option>');
							}
						}
						techjoomla.jQuery('select').trigger('liszt:updated');
						if(document.getElementById('adtype').value != 'affiliate') {
							techjoomla.jQuery("#body").removeAttr('style');
							/*call the zones to get the layouts*/
							/*change by aniket to call caltotal function from get sa.create.getZonesData function--which helps in getting proper value for total amount during edit ad*/
							sa.create.getZonesData(selected_pricing_mode);
						}
						/*added in 2.8 for priority_random Bug #12664*/
						else if(document.getElementById('adtype').value == 'affiliate') {
							techjoomla.jQuery('#ad_zone_id').val(techjoomla.jQuery('#zone option:selected').val());
						}
						techjoomla.jQuery("select").trigger("liszt:updated"); /* IMP : to update to chz-done selects*/
						//End added in 2.8 for priority_random Bug #12664
					} else {
						alert('There are no Zones created for this Ad type!!');
						//	document.getElementById('adsform').adtype[0].selected = true;
						techjoomla.jQuery('#adtype option:selected').next('option').attr('selected', 'selected');
						return;

					}
				}
			});
			if(techjoomla.jQuery("#upload_area").find("div").children("[name=upimg]").val() != null)
				techjoomla.jQuery('#upimg').val(techjoomla.jQuery('#upload_area').find('div').children('[name=upimg]').val());

			switch(document.getElementById('adtype').value) {
				case "media":
					techjoomla.jQuery("#ad_img_name").show();
					techjoomla.jQuery("#ad_img_box").show();
					techjoomla.jQuery("#ad_title_name").show();
					techjoomla.jQuery("#ad_title_box").show();
					techjoomla.jQuery(".sa_charlimit").show();
					techjoomla.jQuery("#ad_body_name").hide();
					techjoomla.jQuery("#ad_body_box").hide();
					techjoomla.jQuery("#layout_div").show();
					techjoomla.jQuery("#sa_preview").show();
					/*bug fixed for 2.7.5 beta 2*/
					techjoomla.jQuery("#defaulturl").show();
					break;

				case "text":
					techjoomla.jQuery("#ad_title_name").show();
					techjoomla.jQuery("#ad_title_box").show();
					techjoomla.jQuery("#ad_body_name").show();
					techjoomla.jQuery("#ad_body_box").show();
					techjoomla.jQuery("#ad_img_name").hide();
					techjoomla.jQuery("#ad_img_box").hide();
					techjoomla.jQuery(".sa_charlimit").show();
					techjoomla.jQuery("#max_tit1").show();
					techjoomla.jQuery("#max_body1").show();
					techjoomla.jQuery("#layout_div").show();
					techjoomla.jQuery("#sa_preview").show();
					/*bug fixed for 2.7.5 beta 2*/
					techjoomla.jQuery("#defaulturl").show();
					break;

				case "text_media":
					techjoomla.jQuery("#ad_title_name").show();
					techjoomla.jQuery("#ad_title_box").show();
					techjoomla.jQuery("#ad_body_name").show();
					techjoomla.jQuery("#ad_body_box").show();
					techjoomla.jQuery("#ad_img_name").show();
					techjoomla.jQuery("#ad_img_box").show();
					techjoomla.jQuery(".sa_charlimit").show();
					techjoomla.jQuery("#max_tit1").show();
					techjoomla.jQuery("#max_body1").show();
					techjoomla.jQuery("#layout_div").show();
					techjoomla.jQuery("#sa_preview").show();
					/*bug fixed for 2.7.5 beta 2*/
					techjoomla.jQuery("#defaulturl").show();
					break;

				case "affiliate":
					techjoomla.jQuery("#defaulturl").hide();
					techjoomla.jQuery("#ad_body_name").show();
					techjoomla.jQuery("#ad_body_box").show();
					techjoomla.jQuery("#max_body1").hide();
					techjoomla.jQuery("#ad_body_chars").hide();
					techjoomla.jQuery("#body").height(221);
					techjoomla.jQuery("#body").width(198);
					techjoomla.jQuery("#body").removeAttr('maxlength');
					techjoomla.jQuery("#ad_title_name").show();
					techjoomla.jQuery("#ad_title_box").show();
					techjoomla.jQuery(".sa_charlimit").hide();
					techjoomla.jQuery("#max_tit1").hide();
					techjoomla.jQuery("#ad_title_chars").hide();

					techjoomla.jQuery("#ad_img_name").hide();
					techjoomla.jQuery("#ad_img_box").hide();
					techjoomla.jQuery("#layout_div").hide();
					techjoomla.jQuery("#sa_preview").hide();
					break;

				default:
					;
			}
			switchCheckboxalt();
		},

		/*function to get zones*/
		/*"camp_price" decide the pricing mode. (0- pay per ad) (1-Ad wallet mode).*/
		getZonesData: function (camp_price) {
			techjoomla.jQuery('#ad_zone_id').val(techjoomla.jQuery('#zone option:selected').val());

			techjoomla.jQuery.ajax({
				url: sa_base_url + 'index.php?option=com_sa&task=create.getZonesData&zone_id=' + document.getElementById('zone').value,
				type: 'GET',
				dataType: "json",
				success: function(data) {
					techjoomla.jQuery("#layout1").html('');
					/*document.getElementById('max_tit1').innerHTML=data[0].max_des;*/
					techjoomla.jQuery(".max_tit").val(data[0].max_title); //hidden i/p box for max limits
					if(document.getElementById('adtype').value != 'affiliate')
						techjoomla.jQuery(".max_body").val(data[0].max_des); //

					techjoomla.jQuery("#max_tit1").text(data[0].max_title); // spans for max limits
					if(document.getElementById('adtype').value != 'affiliate')
						techjoomla.jQuery("#max_body1").text(data[0].max_des); //
					techjoomla.jQuery(".sa_charlimit").show();
					techjoomla.jQuery('#ad_title_chars').show();
					techjoomla.jQuery('#ad_body_chars').show();

					techjoomla.jQuery("#ad_title").attr('maxlength', data[0].max_title); /*apply maxlength to the i/p box*/
					if(document.getElementById('adtype').value != 'affiliate')
						techjoomla.jQuery("#body").attr('maxlength', data[0].max_des);
					sa.create.countChars('ad_title', 'ad_title_charsText', '{CHAR} ' + techjoomla.jQuery('#char_text').text(), techjoomla.jQuery('#max_tit').val(), techjoomla.jQuery('#ad_title').val(), '');
					if(document.getElementById('adtype').value != 'affiliate')
						sa.create.countChars('body', 'ad_body_charsText', '{CHAR} ' + techjoomla.jQuery('#char_text').text(), techjoomla.jQuery('#max_body').val(), techjoomla.jQuery('#body').val(), '');

					if(document.getElementById('adtype').value == 'affiliate')
						return;
					techjoomla.jQuery("#img_wid").text(data[0].img_width);
					techjoomla.jQuery("#img_ht").text(data[0].img_height);
					ad_layout_nm = selected_layout;

					for(i = 0; i < data[0].layout.length; i++) //showing the layout's radio buttons
					{
						sel = '';
						if(ad_layout_nm == data[0].layout[i]) {
							sel = 'checked';
						} else if(i == 0) //needs to be veried
						{
							sel = 'checked';
						}
						//added by sagar
						bootstrap_version = document.getElementById('bootstrap_version').value;

						if(parseFloat(bootstrap_version) == 3.0) {

							techjoomla.jQuery("#layout1").append('<span class="layout_span col-lg-8 col-md-8 col-sm-12 col-xs-12"><input class="layout_radio" type="radio" name="layout" value="' + data[0].layout[i] + '" ' + sel + ' onclick="sa.create.changeLayout(this.value)" ><img class="layout_radio" src="' + data[0].base + 'plugins/socialadslayout/plug_' + data[0].layout[i] + '/plug_' + data[0].layout[i] + '/layout.png" ></span>');

						}
						else
						{
							techjoomla.jQuery("#layout1").append('<span class="layout_span span8"><input class="layout_radio" type="radio" name="layout" value="' + data[0].layout[i] + '" ' + sel + ' onclick="sa.create.changeLayout(this.value)" ><img class="layout_radio" src="' + data[0].base + 'plugins/socialadslayout/plug_' + data[0].layout[i] + '/plug_' + data[0].layout[i] + '/layout.png" ></span>');
						}
						//added by sagar
						if(sel == 'checked')
							sa.create.changeLayout(data[0].layout[i]);

						if(techjoomla.jQuery("#upload_area").find("div").children("[name=upimg]").val() != null)
							techjoomla.jQuery('#upimg').val(techjoomla.jQuery('#upload_area').find('div').children('[name=upimg]').val());
					}

					if(document.getElementById('ad_image').value != '' && document.getElementById('adtype').value != 'text') {
						ajaxUpload(document.adsform, '&filename=ad_image', 'upload_area', 'IMG_UP<br /><img src=\'' + root_url + '/media/com_sa/images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' />', '<img src=\'/media/com_sa/images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /> Error in Upload, check settings and path info in source code.');
					}

					if(document.getElementById('adtype').value == 'media' && data[0].layout.length == 1) //for ad type= image
					{
						techjoomla.jQuery("#layout_div").hide();
					} else
						techjoomla.jQuery("#layout_div").show();

					techjoomla.jQuery("#pric_imp").val(data[0].per_imp); //put pricing values in hidden fields
					techjoomla.jQuery("#pric_click").val(data[0].per_click);
					techjoomla.jQuery("#pric_day").val(data[0].per_day);
					techjoomla.jQuery("#pric_month").val(data[0].per_month);

					//if(document.getElementById('camp_dis').style.display=="none")		//call helper.php function
					//condition changed for wrong calculation while editing when changed adtype//
					if(camp_price == "wallet_mode") {
						if((document.getElementById('totalamount').value != ''))
							caltotal();
					}

					sa.create.getZonePriceForInfo();
				}
			});
		},

		/*@TODO implement price per click/impression like frontend*/
		getZonePriceForInfo: function(){
			return;
		},

		changeLayout: function (rad) {
			if(techjoomla.jQuery("#upload_area").find("div").children("[name=upimg]").val() != null)
				techjoomla.jQuery('#upimg').val(techjoomla.jQuery('#upload_area').find('div').children('[name=upimg]').val());
			var adtype = '' + document.getElementById('adtype').value; /*added by manoj 2.7.5.beta.2*/
			var zone = document.getElementById('zone').options[document.getElementById('zone').selectedIndex].value;
			/*added by manoj

				2.7.5 stable*/
			techjoomla.jQuery.ajax({
				url: sa_base_url + 'index.php?option=com_sa&task=create.changeLayout&layout=' + rad + '&title=' + techjoomla.jQuery("#ad_title").val() + '&body=' + techjoomla.jQuery("#body").val() + '&img=' + techjoomla.jQuery('#upimg').val() + '&a_type=' + adtype + '&a_zone=' + zone,
				type: 'GET',
				dataType: "json",
				success: function(data) {
					if(!document.getElementById(rad + 'css')) //to check if the css is already added
					{

						var head = document.getElementsByTagName('head')[0];
						var link = document.createElement('link');
						link.id = rad + 'css';
						link.rel = 'stylesheet';
						link.type = 'text/css';
						link.href = data.css;
						link.media = 'all';
						head.appendChild(link);
					}
					document.getElementById('preview_sa').innerHTML = data.html;
					if(data.js) { /*added by manoj 2.7.5 stable*/
						eval(data.js);
					}
				},
				error: function(data) {}
			});
		},

		/*function getcount for counting chars in title*/
		countChars: function (entrance, exit, msg, characters, value, event) {
			if(event.keyCode != 9) {
				if(entrance == 'ad_title')
				{
					techjoomla.jQuery("#preview_sa").find(".preview-title").text(value);
				}
				else
				{
					techjoomla.jQuery("#preview_sa").find(".preview-bodytext").text(value);
				}
			}
			if(document.getElementById('adtype').value == 'affiliate') {
				return;
			}
			var entranceObj = getObject(entrance);
			var exitObj = getObject(exit);
			var length = characters - entranceObj.value.length;
			if(length <= 0) {
				length = 0;
				var data = msg.replace("{CHAR}", length);
				techjoomla.jQuery('#' + exit).html('<span class="text-error"> ' + data + ' </span>');
				entranceObj.value = entranceObj.value.substr(0, characters);
			} else {
				exitObj.innerHTML = msg.replace("{CHAR}", length);
				techjoomla.jQuery('#' + exit).show();
			}
		},

		getPaymentGatewayHtml: function (ele, orderid, payPerAd, loadingMsg, loadingImgPath) {
			techjoomla.jQuery.ajax({
				url: sa_base_url + 'index.php?option=com_sa&task=payment.getPaymentGatewayHtml&payPerAd=' + payPerAd + '&gateway=' + ele + '&order_id=' + orderid + '&tmpl=component',
				type: 'POST',
				data: '',
				dataType: 'text',
				beforeSend: function() {
					techjoomla.jQuery('#sa_paymentGatewayList').after('<div class=\"com_socialad_ajax_loading\"><div class=\"com_socialad_ajax_loading_text\">' + loadingMsg + ' ...</div><img class=\"com_socialad_ajax_loading_img\" src="' + root_url + 'media/com_sa/images/ajax.gif"></div>');
				},
				complete: function() {
					techjoomla.jQuery('.com_socialad_ajax_loading').remove();
				},
				success: function(data) {
					if(data) {
						techjoomla.jQuery('#sa_payHtmlDiv').html(data);
						techjoomla.jQuery('#sa_payHtmlDiv div.form-actions input[type="submit"]').addClass('pull-right');
						var prev_button_html = '<button id="btnWizardPrev1" onclick="techjoomla.jQuery(\'#MyWizard\').wizard(\'previous\');"	type="button" class="btn btn-primary pull-left" > <i class="icon-circle-arrow-left icon-white" ></i>Prev</button>';
						techjoomla.jQuery('#sa_payHtmlDiv div.form-actions').prepend(prev_button_html);
					}
				}
			});
		},

		getStatesList: function(countryId, Dbvalue, selOptionMsg) {
			var country = techjoomla.jQuery('#' + countryId).val();
			if(country == undefined) {
				return(false);
			}
			techjoomla.jQuery.ajax({
				url: sa_base_url + 'index.php?option=com_sa&task=checkout.loadState&country=' + country + '&tmpl=component',
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					if(countryId == 'country') {
						statesListBackup = data;
					}
					sa.create.generateStatesOptions(data, countryId, Dbvalue, selOptionMsg);
				}
			});
		},

		generateStatesOptions: function(data, countryId, Dbvalue, selOptionMsg) {
			var country = techjoomla.jQuery('#' + countryId).val();
			var options, index, select, option;

			// add empty option according to billing or shipping
			select = techjoomla.jQuery('#state');
			default_opt = selOptionMsg; //"<?php echo JText::_('COM_SOCIALADS_BILLING_SELECT_STATE')?>";

			// REMOVE ALL STATE OPTIONS
			select.find('option').remove().end();

			// To give msg TASK  "please select country START"
			selected = "selected=\"selected\"";
			var op = '<option ' + selected + ' value="">' + default_opt + '</option>';
			techjoomla.jQuery('#state').append(op);
			// END OF msg TASK

			if(data) {
				options = data.options;
				for(index = 0; index < data.length; ++index) {
					var opObj = data[index];
					selected = "";

					if(opObj.id == Dbvalue) {
						selected = "selected=\"selected\"";
					}
					var op = '<option ' + selected + ' value=\"' + opObj.id + '\">' + opObj.region + '</option>';

					techjoomla.jQuery('#state').append(op);
					techjoomla.jQuery("#state").trigger("liszt:updated"); /* IMP : to update to chz-done selects*/
				} // end of for
			}
		},

		getPromoterPlugins: function(uid) {
			techjoomla.jQuery.ajax({
				url: sa_base_url + 'index.php?option=com_sa&task=create.getPromoterPlugins&tmpl=component&uid=' + uid,
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					techjoomla.jQuery("#promote_plg_select ").html(response['html']);
					techjoomla.jQuery("select").trigger("liszt:updated"); /* IMP : to update to chz-done selects*/
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// Notify the user so he might not wonder.
					alert('Something went wrong! Please try again.');

					//techjoomla.jQuery('#result').html('<p>status code: '+jqXHR.status+'</p><p>errorThrown: ' + errorThrown + '</p><p>jqXHR.responseText:</p><div>'+jqXHR.responseText + '</div>');
					console.log('jqXHR:');
					console.log(jqXHR);
					console.log('textStatus:');
					console.log(textStatus);
					console.log('errorThrown:');
					console.log(errorThrown);
				}
			});
		},

		insertUrl: function() {
			var blanklist = document.getElementById('addatapluginlist').value = '';
			document.getElementById('promotplugin').style.display = 'none';
			techjoomla.jQuery('#destination_url').show();
		},

		populatePromotePlgList: function () {
			techjoomla.jQuery.ajax({
				url: sa_base_url + "index.php?option=com_sa&task=create.getPromotePluginPreviewData&id=" + (techjoomla.jQuery(".promotplglist").val()),
				type: "GET",
				dataType: "json",
				success: function(result) {
					techjoomla.jQuery("#upload_area").html(result.image);
					techjoomla.jQuery("#upimg").val(result.imagesrc);
					techjoomla.jQuery("#addataad_url1").val(result.url1);
					techjoomla.jQuery("#url2").val(result.url2);
					techjoomla.jQuery("#upimgcopy").val(result.imagesrc);
					techjoomla.jQuery("#ad_title").val(result.title);
					techjoomla.jQuery("#body").val(result.bodytext);

					sa.create.changeLayout(techjoomla.jQuery("input[name=layout]:radio:checked").val());
				}
			});
		},

		validateCreateForm: function(stepId,unlimited_ad){
			/* Code for stripping the http:// from url*/
			var urlstring = document.getElementById('url2').value;
			var urlpointer = -1;
			urlpointer = urlstring.indexOf('http://');
			urlpointer1 = urlstring.indexOf('https://');
			if(urlpointer == 0)
			{
				var newstr = urlstring.substr(7);
				document.getElementById('url2').value = newstr;
			}

			else if(urlpointer1 == 0)
			{
				var newstr = urlstring.substr(8);
				document.getElementById('url2').value = newstr;
			}

			if(jQuery("#upload_area").find("div").children("[name=upimg]").val() != null){
				jQuery("#upimg").val(jQuery("#upload_area").find("div").children("[name=upimg]").val());
			}

			/*Url validation starts here*/
			if(document.getElementById("adtype").value == "text_media" || document.getElementById("adtype").value == "text" || document.getElementById("adtype").value == "media"){
				var theurl=document.adsform.url2.value;
				if(theurl == ""){
					techjoomla.jQuery("#url2").css("border-color", "red");
					alert(Joomla.JText._('COM_SOCIALADS_URL_VALID'));
					return false;
				}

				if(!theurl.match(/([A-Za-z0-9\.-]*)\.{0,1}([A-Za-z0-9-]{1,})(\.[A-Za-z]{2,})+/i)){
					techjoomla.jQuery("#url2").css("border-color", "red");
					alert(Joomla.JText._('COM_SOCIALADS_URL_VALID'));
					return false;
				}
			}

			/*Title validation*/
			if(document.getElementById("adtype").value == "text_media" || document.getElementById("adtype").value == "text" || document.getElementById("adtype").value == "affiliate"){
				if(document.getElementById("ad_title").value == ""){
					alert(Joomla.JText._('COM_SOCIALADS_TITLE_VALID'));
					return false;
				}
			}

			/*Body text validation*/
			if(document.getElementById("adtype").value == "text_media" || document.getElementById("adtype").value == "text" || document.getElementById("adtype").value == "affiliate"){
				if(document.getElementById("body").value == ""){
					alert(Joomla.JText._('COM_SOCIALADS_BODY_VALID'));
					return false;
				}
			}

			if(document.getElementById("adtype").value == "media" || document.getElementById("adtype").value == "text_media"){
				if(document.getElementById("ad_image").value == "" && document.getElementById("upimg").value == ""){
					alert(Joomla.JText._('COM_SOCIALADS_MEDIA_VALID'));
					return false;
				}
			}

			if(stepId=="ad-pricing" && unlimited_ad==0){

				if (!sa.create.pricingValidations(camp_currency_daily, selected_pricing_mode, sa_minimum_charge, sa_minimum_charge_msg, currency, sa_invalid_date_msg, sa_ad_date_need_msg, sa_wrong_dates_msg, sa_invalid_credits_msg,sa_chk_contextual_msg))
				{
					return false;
				}
			}

			/*for billing tab*/
			if(stepId=="ad-billing" && techjoomla.jQuery("#sa_BillForm").length){
				var sa_BillForm = document.sa_BillForm;
				if (document.formvalidator.isValid(sa_BillForm)){
					/*return true;*/
				}
				else{
					return false;
				}
			}

			return true;
		},

		/* Added By Snehal */
		pricingValidations: function (camp_currency_daily,selected_pricing_mode,sa_minimum_charge, sa_minimum_charge_msg, currency, sa_invalid_date_msg, sa_ad_date_need_msg, sa_wrong_dates_msg, sa_invalid_credits_msg,sa_chk_contextual_msg)
		{
			/*code for stripping the http:// from url*/
			var urlstring = document.getElementById('url2').value;
			var urlpointer = -1;
			urlpointer = urlstring.indexOf('http://');
			if(urlpointer == 0){
				var newstr = urlstring.substr(7);
				document.getElementById('url2').value = newstr;
			}
			if(selected_pricing_mode== "wallet_mode")
			{
				if(!sa.create.walletModeValidation(camp_currency_daily,currency))
				{
					return false;
				}
				{
					var camp_price_opt = document.getElementById('pricing_opt').value;
															//alert(camp_price_opt);
					if(camp_price_opt=="")				//check for payment_type selected or not
					{
						alert(Joomla.JText._('COM_SOCIALADS_AD_PRICING_OPTION'));
						document.getElementById('pricing_opt').focus();
						return false;
					}
				}
				return true;
			}
			else
			{
			var form = document.adsform;
			var totaldisplay = form.totaldisplay.value;
			var totalamount = form.totalamount.value;
			var charge_points = 0;
			var daterangefrom = form.datefrom.value;

			var chargeoption = document.getElementById('chargeoption').value;
			var unlimited_ad = '';
			if(techjoomla.jQuery(":radio[name=unlimited_ad]").length > 0)
				unlimited_ad=techjoomla.jQuery("input[name=unlimited_ad]:radio:checked").val();


			//~ if(document.getElementById('sa_recuring').checked==true)
			//~ {
				//~ totalamount=(document.getElementById('totaldays').value)*totalamount;
			//~ }

			if((parseInt(chargeoption) >= 2) &&  (unlimited_ad != 1) )
			{
				if((daterangefrom == ' ' ) && (parseInt(chargeoption)== 2))
				{
					alert(sa_ad_date_need_msg);
					return false;
				}
				var now=new Date();
				var year = now.getFullYear();
				var month = now.getMonth()+1;
				var date = now.getDate();
				if(date >=1 && date <=9)
				{
					var newdate = '0'+date;
				}
				else
				{
					var newdate = date;
				}
				if(month >=1 && month <=9)
				{
					var newmonth = '0'+month;
				}
				else
				{
					var newmonth = month;
				}

				today = year+'-'+newmonth+'-'+newdate;

				if((daterangefrom) < (today))
				{
					alert(sa_wrong_dates_msg);
					return false;
				}
				var daycount=document.getElementById("totaldays").value;

				if(isNaN(daycount) || daycount<=0)
				{
					alert(sa_invalid_date_msg);
					document.getElementById("totaldays").focus();
					return false;
				}
			}

			if((parseInt(chargeoption)!=2) && (unlimited_ad != 1))
			{
				if(parseInt(chargeoption)<2)
				{
					if(totaldisplay == '' ){
						alert(sa_invalid_credits_msg);
						document.getElementById('totaldisplay').focus();
					return false;
					}

					if(isNaN(totaldisplay) || (totaldisplay <= 0)){
						alert(sa_invalid_credits_msg);
						document.getElementById('totaldisplay').focus();
						return false;
					}
				}
			}

			if(parseFloat(totalamount) < parseFloat(sa_minimum_charge))
			{
				alert(sa_minimum_charge_msg);
				document.getElementById('totaldisplay').focus();
				return false;
			}
		}
		return true;
		},

		walletModeValidation: function (camp_currency_daily,currency)
		{
			var ncamp = document.getElementById('ad_campaign').value;
			if(document.getElementById('new_campaign').style.display=='block')
			{
				var camp_name = document.getElementById('camp_name').value;
				var camp_amount = document.getElementById('camp_amount').value;

				if(camp_name=='' && ncamp=='0')			//both list camp or new camp not present
				{
					alert(Joomla.JText._('COM_SOCIALADS_AD_ENTER_CAMPAIGN'));
					document.getElementById('camp_name').focus();
					return false;
				}
				if((camp_name && camp_amount=='') || (camp_name && parseFloat(camp_amount) < parseFloat(camp_currency_daily)))		//if new camp then check for daily budget
				{
					alert(minimumBalence);
					document.getElementById('camp_amount').focus();
					return false;
				}

			}
			else
			{
				if (ncamp=='0')		//if new camp is none then check for select list camp
				{
					alert(Joomla.JText._('COM_SOCIALADS_AD_SELECT_CAMPAIGN'));
					document.getElementById('ad_campaign').focus();

					return false;
				}
			}

			return true;
		},

		/**call on guest checkbox*/
		switchCheckboxguest: function (guestbutton, altadbutton) {
		if(techjoomla.jQuery('#guestbutton').is(':checked')) {
			techjoomla.jQuery('#altadbutton').attr('checked', false);
		}
		},
		/**function switchCheckboxguest ends*/

		/**call on alt checkbox*/
		switchCheckboxalt: function () {
			var ischecked = 0;
			var is_affliatead = 0;
			if(techjoomla.jQuery('#altadbutton').is(':checked')) {
			techjoomla.jQuery('#guestbutton').attr('checked', false);
			ischecked = 1;
			}
			if(document.getElementById('adtype').value == "affiliate")
				var is_affliatead = 1;
				if(ischecked == 1 || is_affliatead == 1 || (showTargeting == 0 && allowWholeAdEdit == 0)) {
				sa.create.changenexttoexit(1);
				} else {
				sa.create.changenexttoexit(0);
				}
		},
		changenexttoexit:function (ischecked) {
			if(ischecked == 1) {
				techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').removeClass('btn-primary');
				techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').addClass('btn-success');
				techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext span').text(savenexitbtn_text);
			} else {
				techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext span').text(savennextbtn_text);
				techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').addClass('btn-primary');
				techjoomla.jQuery('.prev_next_wizard_actions #btnWizardNext').removeClass('btn-success');
			}

			if (addMoreCredit == 1 && ischecked == 0)
			{
				techjoomla.jQuery("#sa_ad_more_credit_radio").show();
			}
			else
			{
				techjoomla.jQuery("#sa_ad_more_credit_radio").hide();
			}
		},
		/* Snehal end */

		/*@TODO - added by manoj, needs check once*/
		hideNewCampaign: function (){
			/*If click on new button reset the value of campaign select box to 0*/
			document.getElementById('camp_name').value='';
			document.getElementById("camp_amount").value='';
			document.getElementById("new_campaign").style.display="none";
		},

		showNewCamp: function (){
			/*If click on new button reset the value of campaign select box to 0*/
			document.getElementById('ad_campaign').value=0;
			document.getElementById("new_campaign").style.display="block";
		},

		getZonePricing: function (){
			var click_price=0;
			var click_date=0;
			var click_imp=0;
			var a = document.getElementById('pricing_opt');
			var bid_val = document.getElementById('bid_div');
			var val = a.options[a.selectedIndex].value;

			if(sa_zone_pricing != 0){
				click_price = document.getElementById('pric_click').value;
				click_imp = document.getElementById('pric_imp').value;
			}
			else if(sa_zone_pricing == 0){
				click_price = sa_price_per_clicks;
				click_imp = sa_price_per_impressions;
			}

			jQuery('#click_span').html(Joomla.JText._('COM_SOCIALADS_RATE_PER_CLICK') + click_price + ' ' +  currency);
			jQuery('#imps_span').html(Joomla.JText._('COM_SOCIALADS_RATE_PER_IMP') + click_imp + ' ' + currency);

			if(val==1){
				document.getElementById('click').style.display='block';
				document.getElementById('imps').style.display='none';
			}else if(val==0){
				document.getElementById('imps').style.display='block';
				document.getElementById('click').style.display='none';
			}
		},

		showCoupon: function (){
			if(techjoomla.jQuery('#sa_coupon_chk').is(':checked')){
				techjoomla.jQuery('#sa_cop_tr').show();
			}
			else{
				/*hide all sa releated things*/
				techjoomla.jQuery('.sa_cop_details').hide();
				techjoomla.jQuery('#sa_cop_tr').hide();
				/*make cop empty*/
				techjoomla.jQuery('#sa_coupon_code').val('');
			}
		},

		applyCoupon: function (ck_val){
			if(techjoomla.jQuery('#sa_coupon_chk').is(':checked')){
				if(techjoomla.jQuery('#totalamount').val() ==''){
					alert(Joomla.JText._('COM_SOCIALADS_TOTAL_SHOULDBE_VALID_VALUE'));
				}
				else if(techjoomla.jQuery('#sa_coupon_code').val() ==''){
					if(ck_val == 1){
						alert(Joomla.JText._('COM_SOCIALADS_ENTER_COP_COD'));
					}
					/*commented BZ called on refresh*/
				}
				else{
					techjoomla.jQuery.ajax({
						url: base_url+'index.php?option=com_sa&task=payment.getcoupon&coupon_code='+document.getElementById('sa_coupon_code').value,
						type: 'GET',
						dataType: 'json',
						success: function(data) {
							amt=0;
							val=0;

							if(data != 0){
								var subtotal = techjoomla.jQuery('#totalamount').val();
								if(data[0].val_type == 1){
									val = (data[0].value/100)* subtotal;
								}
								else{
									val = data[0].value;
								}

								amt = sa.create.round(subtotal- val);

								if(amt <= 0){
									amt=0;
								}

								techjoomla.jQuery('.sa_cop_details').show();
								techjoomla.jQuery('#sa_cop_price').html(amt + ' ' + currency);
								techjoomla.jQuery('#sa_cop_afterprice').html(val + ' ' + currency)
								techjoomla.jQuery('#dis_cop').html(val + ' ' + currency);
								/*techjoomla.jQuery('#dis_amt').show();*/
							}
							else{
								alert(document.getElementById('sa_coupon_code').value + ' ' + Joomla.JText._('COM_SOCIALADS_COP_EXISTS'));
							}
						}
					});
				}
			}
		},

		addOption: function (select_opt){
			for (i=0; i<select_opt.length; i++){
				alert(valuecurrentgatewayliststr);
				var result=valuecurrentgatewayliststr.search(select_opt[i].value);
				if(result==-1){
					jQuery('#gateway').append(jQuery('<option/>', {
						value: select_opt[i].value,
						text: select_opt[i].name
					}));
				}
			}
		},

		removeOption: function (select_opt){
			for (i=0; i<select_opt.length; i++){
				jQuery('#gateway option[value=\"'+select_opt[i].value+'\"]').remove();
			}
		},

		round: function (n) {
			return Math.round(n*100+((n*1000)%10>4?1:0))/100;
		},

		calculateTotal: function(){
			var chargeoptionsel=document.getElementById('chargeoption').value;
			var click_price=0;
			var click_date=0;
			var click_imp=0;

			if(sa_zone_pricing != 0){
				click_price = document.getElementById('pric_click').value;
				click_date = document.getElementById('pric_day').value;
				click_imp = document.getElementById('pric_imp').value;
			}
			else if(sa_zone_pricing == 0){
				click_price = sa_price_per_clicks;
				click_date = sa_price_per_day;
				click_imp = sa_price_per_impressions;
			}

			var totaldisplay=document.getElementById('totaldisplay').value;
			var gateway = document.getElementById('gateway').value;
			var re_select = jQuery.parseJSON(re_jsondata);

			if (jQuery('#sa_recuring').is(':checked')){
				document.getElementById('total_days_label').innerHTML = Joomla.JText._('COM_SOCIALADS_AD_NUMBER_OF') + ' ' + jQuery('#chargeoption option:selected').text();
			}
			else{
				document.getElementById('total_days_label').innerHTML = Joomla.JText._('COM_SOCIALADS_AD_NUMBER_OF') + ' ' + jQuery('#chargeoption option:selected').text();
			}

			/*if(recurring_gateway.search(gateway)==-1){

				if(document.getElementById('sa_recuring').checked==true){
					document.getElementById('sa_recuring').checked=false;
				}
			}
			else{
				 if(" . $displayData->sa_params->get('recurring_payments') . "==1)
				  document.getElementById('sa_recuring').checked=true;
			}*/

			if(!gateway){
				return;
			}

			if(document.getElementById('chargeoption').value == '1'){
				document.getElementById('total_days').style.display = 'none';
				document.getElementById('priceperdate').style.display = 'none';
				document.getElementById('priceperclick').style.display = 'block';

				if(totaldisplay==''){
					document.getElementById('ad_totalamount').innerHTML = '';
					document.getElementById('currency').innerHTML='';
				}
				else{
					amt1=sa.create.round(totaldisplay * click_price);
					document.getElementById('ad_totalamount').innerHTML= amt1;
					document.getElementById('totalamount').value = amt1;
					document.getElementById('currency').innerHTML = currency;
					document.getElementById('hcurrency').value = currency;
					document.getElementById('hrate').value='';
				}
			}
			else if(document.getElementById('chargeoption').value == '0'){
				document.getElementById('total_days').style.display = 'none';
				document.getElementById('priceperdate').style.display = 'none';
				document.getElementById('priceperclick').style.display = 'block';

				if(totaldisplay==''){
					document.getElementById('ad_totalamount').innerHTML='';
					document.getElementById('currency').innerHTML='';
				}
				else {
					amt1=sa.create.round(totaldisplay *  click_imp);
					document.getElementById('ad_totalamount').innerHTML = amt1;
					document.getElementById('totalamount').value =  amt1;
					document.getElementById('currency').innerHTML=currency;
					document.getElementById('hcurrency').value=currency;
					document.getElementById('hrate').value='';
				}
			}
			else if(document.getElementById('chargeoption').value == '2'){
				document.getElementById('total_days').style.display = '';
				document.getElementById('priceperclick').style.display = 'none';
				/*added by sagar feb9*/

				document.getElementById('total_days_label').innerHTML = sa_per_day_msg;
				/*document.getElementById('sa_recuring_div').style.display = 'none';*/

				var ad_chargeoption_day = 0;
				if(document.getElementById('ad_chargeoption_day')){
					ad_chargeoption_day = document.getElementById('ad_chargeoption_day').value;
				}

				if(ad_chargeoption_day){
					if(document.getElementById('totaldays').value==' ' ){
						document.getElementById('ad_totalamount').innerHTML='';
						document.getElementById('currency').innerHTML='';
					}
					else{
						var daycount=document.getElementById('totaldays').value;
						document.getElementById('ad_totaldays').value = daycount;
						amt1=sa.create.round(daycount *  click_date);
						document.getElementById('ad_totalamount').innerHTML = amt1;
						document.getElementById('totalamount').value =  amt1;
						document.getElementById('currency').innerHTML=currency;
						document.getElementById('hcurrency').value=currency;
						document.getElementById('hrate').value='';
					}
				}
				else{
					document.getElementById('priceperdate').style.display = '';
					var daterangefrom = document.getElementById('datefrom').value;

					if(daterangefrom==' ' || document.getElementById('totaldays').value == ''){
						document.getElementById('ad_totalamount').innerHTML='';
						document.getElementById('currency').innerHTML='';
					}
					else{
						var daycount;
						daycount=document.getElementById('totaldays').value;

						document.getElementById('ad_totaldays').value = daycount;
						amt1=sa.create.round(daycount *  click_date);
						document.getElementById('ad_totaldays').value = daycount;
						document.getElementById('totaldays').innerHTML = daycount;

						document.getElementById('ad_totalamount').innerHTML = amt1;
						document.getElementById('totalamount').value =  amt1;
						document.getElementById('currency').innerHTML=currency;
						document.getElementById('hcurrency').value= currency;
						document.getElementById('hrate').value='';
					}
				}
			}
			else{
				document.getElementById('priceperdate').style.display = '';
				document.getElementById('total_days').style.display = '';
				document.getElementById('priceperclick').style.display = 'none';
				/*added by sagar feb9*/

				/*if(sa_recurring_payments==0){
					if(recurring_gateway.search(gateway)==-1){
						document.getElementById('sa_recuring_div').style.display=	'none'
					}
					else{
						document.getElementById('sa_recuring_div').style.display = '';
					}
				}
				else if(recurring_gateway.search(gateway)==-1){
					document.getElementById('sa_recuring_div').style.display=	'none'
				}*/

				var daterangefrom = document.getElementById('datefrom').value;
				if(daterangefrom==' ' || document.getElementById('totaldays').value == ''){
					document.getElementById('ad_totalamount').innerHTML='';
					document.getElementById('currency').innerHTML='';
				}
				else
				{
					var jsondata = slabs_json;
					var slab = jQuery.parseJSON(jsondata);

					for (i=0; i < slab.length; i++)
					{
						if (parseInt(slab[i].duration) == parseInt(chargeoptionsel))
						{
							amt1=slab[i].price;
							daycount=slab[i].duration;
							break;
						}
					}

					daycount = document.getElementById('totaldays').value;
					amt1 = sa.create.round(daycount *  amt1);


					document.getElementById('totalamtspan').innerHTML = Joomla.JText._('TOTAL');

					/*if(document.getElementById('sa_recuring').checked==true){
						chargeselected=document.getElementById('chargeoption').value;
						if(parseInt(chargeselected)>2){
							document.getElementById('totalamtspan').innerHTML =Joomla.Text._('TOTAL_SLAB') + ' ' + jQuery('#chargeoption option:selected').text();
						}
					}*/

					document.getElementById('ad_totaldays').value = daycount;
					document.getElementById('ad_totalamount').innerHTML = amt1;
					document.getElementById('totalamount').value =  amt1;
					document.getElementById('currency').innerHTML=currency;
					document.getElementById('hcurrency').value= currency;
					document.getElementById('hrate').value='';
				}
			}
			/*DJ removed call of sa.create.calculatePoints()*/
			/*check for coupon*/
			sa.create.applyCoupon(0);
		},

		calculatePoints: function(){
			var amt=document.getElementById('totalamount').value;
			var totaldisplay=document.getElementById('totaldisplay').value;
			if(!document.getElementById('gateway').value){
				return;
			}
			var chargeoption=document.getElementById('chargeoption').value;
			jQuery.ajax({
				url: root_url+'?option=com_sa&task=payment.getpoints&plugin_name='+document.getElementById('gateway').value,
				success: function(data) {
					var data1 = data.split('|');
					jpoints=data1[0];
					jconver=data1[1];
					document.getElementById('jpoints').value=jpoints;

					if(totaldisplay=='' && chargeoption != '2'){
						if(parseInt(chargeoption)<='2'){
							document.getElementById('ad_totalamount').innerHTML='';
							document.getElementById('currency').innerHTML='';
						}
					}
					else if(!(jpoints<0)){
						document.getElementById('ad_totalamount').innerHTML= '<big>'+ Math.round(amt1 * jconver) + '</big>';
						document.getElementById('totalamount').value =  Math.round(amt1 * jconver);
						var points = document.getElementById('jpoints').value;
						if(points == ''){
							points = 0;
						}
						document.getElementById('currency').innerHTML='<big>' + Joomla.JText._('POINT') + '</big>' + '<small><i>' + Joomla.JText._('POINTS_AVAILABLE') + ' ' + points + ' ' + Joomla.JText._('POINT') + '</i></small>';
						document.getElementById('hcurrency').value='<big>' + Joomla.JText._('POINT') + '</big>' + '<small><i>' + Joomla.JText._('POINTS_AVAILABLE') + ' ' + points + ' ' + Joomla.JText._('POINT') + '</i></small>';
						document.getElementById('rate').innerHTML=jconver + Joomla.JText._('POINT') + ' = 1 ' + currency;
						document.getElementById('hrate').value=jconver + ' ' + Joomla.JText._('POINT') + ' = 1 ' + currency;
					}
					else{
						document.getElementById('rate').innerHTML='';
						sa.create.calculateTotal();
					}
				}
			});
		}
	},

	ads: {
		submitButtonAction: function(action) {
			if(action == 'ads.publish' || action == 'ads.unpublish') {
				Joomla.submitform(action);
			}
			else if(action == 'ads.delete') {
				var r = confirm(Joomla.JText._('COM_SOCIALADS_DELETE_AD'));
				if(r != true) {
					return;
				}
			} else {
				window.location = 'index.php?option=com_socialads&view=ads';
			}

			var form = document.adminForm;
			Joomla.submitform(action);
			return;
		},
		deleteItem: function() {
			var item_id = techjoomla.jQuery(this).attr('data-item-id');

			if(confirm(Joomla.JText._('COM_SOCIALADS_DELETE_MESSAGE'))) {
				window.location.href = url + item_id;
			}
		}
	},
	campaigns: {
		submitButtonAction: function(action) {
			if(action == 'campaigns.publish' || action == 'campaigns.unpublish') {
				Joomla.submitform(action);
			} else if(action == 'campaigns.delete') {
				var r = confirm(Joomla.JText._('COM_SOCIALADS_CAMPAIGNS_DELETE_CONFIRM'));
				if(r != true) {
					return;
				}
			} else {
				window.location = 'index.php?option=com_socialads&view=campaigns';
			}

			var form = document.adminForm;
			Joomla.submitform(action);
			return;
		},
		deleteItem: function() {
			var item_id = techjoomla.jQuery(this).attr('data-item-id');

			if(confirm(Joomla.JText._('COM_SOCIALADS_DELETE_MESSAGE'))) {
				window.location.href = url + item_id;
			}
		}
	},
	payment: {
		/*Function to apply a coupon code*/
		applyCoupon: function() {
			var cal = document.getElementById('jform_amount').value;
			var coupon_code = document.getElementById('jform_coupon_code').value;

			if(techjoomla.jQuery('#jform_coupon_code').val() == '') {
				alert(Joomla.JText._('COM_SOCIALAD_PAYMENT_ENTER_COUPON_CODE'));
			} else {
				techjoomla.jQuery.ajax({
					url: '?option=com_sa&task=payment.getcoupon&coupon_code=' + coupon_code,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						var amt = 0;
						var val = 0;

						if(data != 0) {
							if(data[0].val_type == 1)
								val = (data[0].value / 100) * cal;
							else
								val = data[0].value;
							amt = sa.payment.round(cal - val);

							if(amt <= 0)
								amt = 0;
							techjoomla.jQuery('#coupon_value').html(val + currency);
							techjoomla.jQuery('#coupon_discount').show();
							techjoomla.jQuery('#dis_amt').html(amt + currency);
							techjoomla.jQuery('#dis_amt1').show();

							if(amt == 0) {
								techjoomla.jQuery('#pay_gateway').hide();
								techjoomla.jQuery('#coupon').html('<input type="button" class="button btn btn-primary" id="add_coupon" value="'+submit+'" onclick="sa.payment.AddCouponPayment(\'' + coupon_code + '\',\'' + val + '\')">');
								techjoomla.jQuery('#coupon_div').show();
							}
						} else {
							alert(document.getElementById('jform_coupon_code').value  + ' ' + Joomla.JText._('COM_SOCIALADS_PAYMENT_COUPON_NOT_EXISTS'));
							document.getElementById('jform_coupon_code').value = '';
						}
					},
					error: function(xhr, status, error) {
						var err = eval("(" + xhr.responseText + ")");
						alert(err.Message);
					}
				});
			}
		},
		/*Function to add payment in wallet*/
		makePayment: function(pay_method) {
			var cal = document.getElementById('jform_amount').value;
			var cop_dis_opn_hide = 0;

			if(techjoomla.jQuery('#couponEffect').is(':visible')) {
				var cop_text = document.getElementById('jform_coupon_code').value;

				if(techjoomla.jQuery('#dis_amt1').is(':hidden') && cop_text) {
					var cop_dis_opn_hide = 1;
				}
			}

			if(cal == '' || isNaN(cal)) {
				alert(Joomla.JText._('COM_SOCIALADS_PAYMENT_ENTER_CORRECT_AMT'));
				document.getElementById('jform_amount').focus();
				techjoomla.jQuery('#jform_amount').val('');
				return false;
			}
			techjoomla.jQuery.ajax({
				url: '?option=com_sa&task=payment.makePayment&processor=' + pay_method + '&amount=' + cal + '&cop=' + document.getElementById('jform_coupon_code').value + '&cop_dis_opn_hide=' + cop_dis_opn_hide,
				type: 'GET',
				dataType: 'html',
				beforeSend: function() {
					var loadingMsg = Joomla.JText._('COM_SOCIALAD_PAYMENT_GATEWAY_LOADING_MSG')
					techjoomla.jQuery('#pay_gateway').after('<div class=\"com_socialad_ajax_loading\"><div class=\"com_socialad_ajax_loading_text\">' + loadingMsg + ' ...</div><img class=\"com_socialad_ajax_loading_img\" src="' + imgpath + '"></div>');
				},
				complete: function() {
					techjoomla.jQuery('.com_socialad_ajax_loading').remove();
				},
				success: function(response) {
					var str_resp = response.toString();
					var aa = str_resp.search('coupon_discount_all');

					if(aa > -1) {
						window.location.href = 'index.php?option=com_socialads&view=payment';
					}
					techjoomla.jQuery('#html-container').html(response);
				}
			});
		},
		/*Function to round the output*/
		round: function(n) {
			return Math.round(n * 100 + ((n * 1000) % 10 > 4 ? 1 : 0)) / 100;
		},

		/*Function for validation on 'yes' 'no' of coupon */
		showCoupon: function(rad, mim_bal) {
			var amt = document.getElementById('jform_amount').value;

			if(amt == '' || isNaN(amt)) {
				alert(Joomla.JText._('COM_SOCIALADS_PAYMENT_ENTER_CORRECT_AMT'));
				document.getElementById('jform_amount').focus();
				techjoomla.jQuery('input[name=jform\\[coupon_result\\]]:checked').val([0]);

				return false;
			} else if(parseInt(amt) < parseInt(mim_bal)) {
				alert(Joomla.JText._('COM_SOCIALAD_PAYMENT_AMOUNT_NEEDS_TO_PAY') + mim_bal);
				document.getElementById('jform_amount').focus();
				techjoomla.jQuery('input[name=jform\\[coupon_result\\]]:checked').val([0]);
				return false;
			}

			if(rad == 1) {
				techjoomla.jQuery("#couponHide").show();
			} else {
				techjoomla.jQuery("#couponHide").hide();
			}
		},

		/*Function to add coupon payment*/
		AddCouponPayment: function(coupon_code, value) {
			techjoomla.jQuery.ajax({
				url: '?option=com_sa&task=payment.addCouponPayment&coupon_code=' + coupon_code + '&value=' + value,
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					if(data != 0) {
						window.location = "?option=com_socialads&view=wallet";
					}
				}
			});
		}
	},
	wallet: {
		/*Add payment in wallet*/
		addPayment: function() {
			window.location = 'index.php?option=com_socialads&view=payment';
		},
		/*Function to validate coupon code*/
		applyCouponCode: function() {
			var coupon_code = document.getElementById('coupon_code').value;

			if(jQuery('#coupon_code').val() == '') {
				alert(Joomla.JText._('COM_SOCIALAD_PAYMENT_ENTER_COUPON_CODE'));
			} else {
				jQuery.ajax({
					url: '?option=com_sa&task=payment.getcoupon&coupon_code=' + coupon_code,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						if(data != 0) {
							if(data[0].val_type == 1) {
								alert(Joomla.JText._('COM_SOCIALAD_PAYMENT_COUPON_CODE_IN_PERCENT'));

								return false;
							} else {
								var value = data[0].value;
								sa.wallet.addCouponPayment(coupon_code, value);
							}
						} else {
							alert(document.getElementById('coupon_code').value + ' ' + Joomla.JText._('COM_SOCIALADS_PAYMENT_COUPON_NOT_EXISTS'));
							document.getElementById('coupon_code').value = '';
						}
					}
				});
			}
		},
		/*Funtion to add coupon payment*/
		addCouponPayment: function(coupon_code, value) {
			jQuery.ajax({
				url: '?option=com_sa&task=payment.addCouponPayment&coupon_code=' + coupon_code + '&value=' + value,
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					if(data != 0) {
						alert(Joomla.JText._('COM_SOCIALADS_WALLET_COUPON_ADDED_SUCCESS'));
						window.location = "?option=com_socialads&view=wallet";
					}
				}
			});
		}
	},
	registration: {
		submitButtonAction: function(task) {
			var form = document.adminForm;
			if(task == 'registration.cancel') {
				sa.registration.submitform(task);
				return;
			}

			sa.registration.submitform(task);
			return;
		},
		submitform: function(task) {
			if(task) {
				document.adminForm.task.value = task;
			}
			if(typeof document.adminForm.onsubmit == 'function') {
				document.adminForm.onsubmit();
			}

			document.adminForm.submit();
		}

	}
}
var saAdmin = {
	ajax: function(url, type, data, callback) {
		techjoomla.jQuery.ajax({
			url: url,
			type: type,
			data: data
		}).done(callback);
	},
	/*Initialize ads list view js*/
	initSaJs: function() {
		techjoomla.jQuery(document).ready(function() {
			techjoomla.jQuery('#clear-search-button').on('click', function() {
				techjoomla.jQuery('#filter_search').val('');
				techjoomla.jQuery('#adminForm').submit();
			});
		});
	},
	checkForAlpha: function(el, allowed_ascii) {
		allowed_ascii = (typeof allowed_ascii === 'undefined') ? '' : allowed_ascii;
		var i = 0;
		for(i = 0; i < el.value.length; i++) {
			if(el.value == '0') {
				alert(Joomla.JText._('COM_SOCIALADS_ZERO_VALUE_VALI_MSG'));
				el.value = el.value.substring(0, i);
				break;
			}
			if((el.value.charCodeAt(i) <= 47 || el.value.charCodeAt(i) >= 58) || (el.value.charCodeAt(i) == 45)) {
				if(allowed_ascii != el.value.charCodeAt(i)) {
					alert(Joomla.JText._('COM_SOCIALADS_NUMONLY_VALUE_VALI_MSG'));
					el.value = el.value.substring(0, i);
					break;
				}
			}
		}
	},

	ads: {
		selectAdStatus: function(appid, ele) {
			var selInd = ele.selectedIndex;
			var status = ele.options[selInd].value;
			var reply = '';
			if(status == 1) {
				ele.options[0].disabled = true;
			}
			if(status == 2) {
				var reply = prompt(Joomla.JText._('COM_SOCIALADS_ADS_STATUS_PROMPT_BOX'));
			}
			if(!(reply == null)) {
				document.getElementById('reason').value = reply;
				document.getElementById('hidid').value = appid;
				document.getElementById('hidstat').value = status;
				submitbutton('forms.save');
				return;
			} else {
				return false;
			}
		},

		selectZoneIfNotExists: function(appid, ele) {
			var selInd = ele.selectedIndex;
			var zone = ele.options[selInd].value;
			document.getElementById('hidid').value = appid;
			document.getElementById('hidzone').value = zone;
			submitbutton('forms.updatezone');
			return;
		},

		submitButtonAction: function(task) {
			if(task == 'forms.delete') {
				var r = confirm(Joomla.JText._('COM_SOCIALADS_ADS_DELETE_CONFIRM'));
				if(r === false) {
					return false;
				}
			}
			Joomla.submitform(task);
			return;
		}
	},

	adorders: {
		selectstatusorder: function(appid, ele) {
			var selInd = ele.selectedIndex;
			var status = ele.options[selInd].value;
			document.getElementById('hidid').value = appid;
			document.getElementById('hidstat').value = status;
			submitbutton('adorders.save');
			return;
		}
	},

	campaigns: {
		submitButtonAction: function(action) {
			if(action == 'campaigns.publish' || action == 'campaigns.unpublish') {
				Joomla.submitform(action);
			} else if(action == 'campaigns.delete') {
				var r = confirm(Joomla.JText._('COM_SOCIALADS_CAMPAIGNS_DELETE_CONFIRM'));
				if(r != true) {
					return;
				}
			} else {
				window.location = 'index.php?option=com_quick2cart&view=campaigns';
			}
			var form = document.adminForm;
			Joomla.submitform(action);
			return;
		}
	},
	coupon: {
		/*Initialize campaign js*/
		initCouponJs: function() {
			techjoomla.jQuery(document).ready(function() {
				techjoomla.jQuery('.alphaDecimalCheck').keyup(function() {
					saAdmin.checkForAlpha(this, 46);
				});
			});
		},

		checkDates: function() {
			var selectedFromDate = document.getElementById('jform_from_date').value;
			var selectedToDate = document.getElementById('jform_exp_date').value;
			startDate = new Date(selectedFromDate);
			startDate.setHours(0, 0, 0, 0);
			endDate = new Date(selectedToDate);
			endDate.setHours(0, 0, 0, 0);

			var today = new Date();
			today.setHours(0, 0, 0, 0);

			/*Coupon start date should not be less than todays date*/
			if(startDate < today) {
				alert(Joomla.JText._('COM_SOCIALADS_DATE_START_ERROR_MSG'));
				document.getElementById('jform_from_date').value = '';
				return 0;
			}
			/*Coupon expiry date should not be less than todays date*/
			if(endDate < today) {
				alert(Joomla.JText._('COM_SOCIALADS_DATE_END_ERROR_MSG'));
				document.getElementById('jform_exp_date').value = '';
				return 0;
			}
			/*Coupon expiry date should not be less than from date*/
			if(document.getElementById('jform_exp_date').value != '') {
				if(document.getElementById('jform_from_date').value > document.getElementById('jform_exp_date').value) {
					alert(Joomla.JText._('COM_SOCIALADS_DATE_ERROR_MSG'));
					document.getElementById('jform_exp_date').value = '';
					return 0;
				} else {
					return 1;
				}
			}
		},

		submitButtonAction: function(task) {
			if(task == 'coupon.apply') {
				var checkDate = saAdmin.coupon.checkDates();
				if(checkDate === 0) {
					return false;
				}
				var check = saAdmin.coupon.coupounDuplicateCheck();
				if(check === 0) {
					return false;
				}
			}

			if(task == 'coupon.save') {
				var checkDate = saAdmin.coupon.checkDates();
				if(checkDate === 0) {
					return false;
				}
				var check = saAdmin.coupon.coupounDuplicateCheck();
				if(check === 0) {
					return false;
				}
			}

			if(task == 'coupon.save2new') {
				var checkDate = saAdmin.coupon.checkDates();
				if(checkDate === 0) {
					return false;
				}
				var check = saAdmin.coupon.coupounDuplicateCheck();
				if(check === 0) {
					return false;
				}
			}

			if(task == 'coupon.cancel') {
				Joomla.submitform(task, document.getElementById('coupon-form'));
			} else {
				if(task != 'coupon.cancel' && document.formvalidator.isValid(document.id('coupon-form'))) {
					Joomla.submitform(task, document.getElementById('coupon-form'));
				} else {
					alert(Joomla.JText._('JGLOBAL_VALIDATION_FORM_FAILED'));
				}
			}
		},
		coupounDuplicateCheck: function() {
			var coupon_code = document.getElementById('jform_code').value;
			var duplicatecode = 0;
			if(parseInt(cid) == 0) {
				var url = "index.php?option=com_sa&tmpl=component&task=coupon.getcode&selectedcode=" + coupon_code;
			} else {
				var url = "index.php?option=com_sa&task=coupon.getselectcode&couponid=" + cid + "&selectedcode=" + coupon_code;
			}
			jQuery.ajax({
				url: url,
				type: 'GET',
				async: false,
				success: function(response) {
					if(parseInt(response) == 1) {
						alert(Joomla.JText._('COM_SOCIALADS_DUPLICATE_COUPON'));
						duplicatecode = 1;
					} else {
						return 1;
					}
				}
			});
			if(duplicatecode === 1) {
				return 0;
			}
		}
	},
	coupons: {
		submitButtonAction: function(action) {
			if(action == 'coupons.delete') {
				var delCoupon = confirm(Joomla.JText._('COM_SOCIALADS_COUPONS_DELETE_CONFORMATION'));
				if(delCoupon === false) {
					return false;
				}
			}
			Joomla.submitform(action);
			return;
		}
	},
	dashboard: {
		/*Initialize dashboard js*/
		initDashboardJs: function() {
			techjoomla.jQuery(document).ready(function() {
				saAdmin.dashboard.initBarChart();
				saAdmin.dashboard.initDonutChart();
				saAdmin.dashboard.showNewsFeeds();

				techjoomla.jQuery('#btnRefresh').click(function() {
					if(saAdmin.dashboard.validatePeriodicDates()) {
						Joomla.submitform();
					}
				});
			});
		},

		/*Initialize bar chart*/
		initBarChart: function() {
			saAdmin.ajax('index.php?option=com_sa&task=dashboard.getBarChartData', 'GET', '', saAdmin.dashboard.drawBarChart);
		},

		/*Draw bar chart*/
		drawBarChart: function(ajaxRespData) {
			/*Check if any orders*/
			var salesDoneFlag = 0;
			techjoomla.jQuery.each(ajaxRespData, function(i, monthData) {
				if((monthData['amount']) > 0) {
					salesDoneFlag = 1;
					return true;
				}
			});
			if(salesDoneFlag == 0) {
				return;
			}
			/*Hide no data msg*/
			techjoomla.jQuery('#graph-monthly-sales-msg').hide();
			/*Draw Chart*/
			Morris.Bar({
				element: 'graph-monthly-sales',
				data: ajaxRespData,
				xkey: 'month',
				ykeys: ['amount'],
				labels: [Joomla.JText._('COM_SOCIALADS_AMOUNT')],
				barColors: ['#428bca'],
				barRatio: 0.4,
				xLabelAngle: 35,
				hideHover: 'auto',
				resize: true
			});
		},

		/*Initialize donut chart*/
		initDonutChart: function() {
			var data = {
				from: techjoomla.jQuery('#from').val(),
				to: techjoomla.jQuery('#to').val()
			};
			saAdmin.ajax('index.php?option=com_sa&task=dashboard.getDonutChartData', 'POST', data, saAdmin.dashboard.drawDonutChart);
		},

		/*Draw donut chart*/
		drawDonutChart: function(ajaxRespData) {
			/*Check if any orders*/
			if(ajaxRespData[0]['porders'] == 0 && ajaxRespData[0]['corders'] == 0 && ajaxRespData[0]['rorders'] == 0) {
				return;
			}
			/*Hide no data msg*/
			techjoomla.jQuery('#donut-chart-msg').hide();
			/*Draw Chart*/
			Morris.Donut({
				element: 'donut-chart',
				data: [{
					label: Joomla.JText._('COM_SOCIALADS_PENDING_ORDERS'),
					value: ajaxRespData[0]['porders']
				}, {
					label: Joomla.JText._('COM_SOCIALADS_CONFIRMED_ORDERS'),
					value: ajaxRespData[0]['corders']
				}, {
					label: Joomla.JText._('COM_SOCIALADS_REJECTED_ORDERS'),
					value: ajaxRespData[0]['rorders']
				}],
				colors: ["#f0ad4e", "#5cb85c", "#FF0000"]
			});
		},

		/*Validate periodic dates*/
		validatePeriodicDates: function() {
					fromDate = document.getElementById('from').value;
				toDate = document.getElementById('to').value;
				fromDate1 = new Date(fromDate.toString());
				toDate1 = new Date(toDate.toString());
				difference = toDate1 - fromDate1;
				days = Math.round(difference/(1000*60*60*24));

				if (parseInt(days)<=0)
				{
					alert(Joomla.JText._("COM_SOCIALADS_DATE_ERROR_MSG_DASHBOARD"));

					return;
				}
		},

		/*Show latest news feed*/
		showNewsFeeds: function() {
			techjoomla.jQuery.ajax({
				beforeSend: function() {
					techjoomla.jQuery('#tj-dashboard-news').html('<div class="center"><div>&nbsp;</div><img src="../media/com_sa/images/ajax.gif"/></div>');
				},
				type: 'GET',
				url: 'index.php?option=com_sa&task=dashboard.getNewsFeeds',
				async: true,
				dataType: 'json',
				success: function(data) {
					if(!data) {
						techjoomla.jQuery('#tj-dashboard-news').html('<div>&nbsp;</div><span class="text text-danger">' + Joomla.JText._('COM_SOCIALADS_ERROR_LOADING_FEEDS') + '</span>');
					} else {
						var newsFeedsHtml = '';

						var weekDays = [
							Joomla.JText._('SUN'),
							Joomla.JText._('MON'),
							Joomla.JText._('TUE'),
							Joomla.JText._('WED'),
							Joomla.JText._('THU'),
							Joomla.JText._('FRI'),
							Joomla.JText._('SAT')
						];

						var monthNames = [
							Joomla.JText._('JANUARY_SHORT'),
							Joomla.JText._('FEBRUARY_SHORT'),
							Joomla.JText._('MARCH_SHORT'),
							Joomla.JText._('APRIL_SHORT'),
							Joomla.JText._('MAY_SHORT'),
							Joomla.JText._('JUNE_SHORT'),
							Joomla.JText._('JULY_SHORT'),
							Joomla.JText._('AUGUST_SHORT'),
							Joomla.JText._('SEPTEMBER_SHORT'),
							Joomla.JText._('OCTOBER_SHORT'),
							Joomla.JText._('NOVEMBER_SHORT'),
							Joomla.JText._('DECEMBER_SHORT')
						];

						newsFeedsHtml += '<div>&nbsp;</div>';
						newsFeedsHtml += '<div class="row">';
						newsFeedsHtml += '<div class="col-sm-12 col-md-12 col-lg-12 tj-news-items">';

						techjoomla.jQuery.each(data, function(i, feeds) {
							feeds.link = feeds.link + '?utm_source=clientinstallation&utm_medium=dashboard&utm_term=socialads&utm_content=newslink&utm_campaign=socialads_ci';

							newsFeedsHtml += '<div class="row tj-news-item">';
							newsFeedsHtml += '<div class="col-sm-4 col-md-4 col-lg-4 ">';
							newsFeedsHtml += '<time class="icon" datetime="' + feeds.date + '">';
							var d = new Date(feeds.date);
							/*Get date, month and day*/
							newsFeedsHtml += '<em>' + weekDays[d.getDay()] + '</em>';
							newsFeedsHtml += '<strong>' + monthNames[d.getMonth()] + '</strong>';
							newsFeedsHtml += '<span>' + d.getDate() + '</span>';
							newsFeedsHtml += '</time>';
							newsFeedsHtml += '</div>';
							newsFeedsHtml += '<div class="col-sm-7 col-md-7 col-lg-7">';
							newsFeedsHtml += '<div>';
							newsFeedsHtml += '<a target="_blank" href="' + feeds.link + '">';
							newsFeedsHtml += feeds.title;
							newsFeedsHtml += '</a>';
							newsFeedsHtml += '</div>';
							newsFeedsHtml += '<div>';
							newsFeedsHtml += '<br/><p class="sa-text-justify">';
							newsFeedsHtml += feeds.text;
							newsFeedsHtml += '</p>';
							newsFeedsHtml += '</div>';
							newsFeedsHtml += '</div>';
							newsFeedsHtml += '</div>';
						});
						newsFeedsHtml += '</div>';
						newsFeedsHtml += '</div>';
						techjoomla.jQuery('#tj-dashboard-news').html(newsFeedsHtml);
					}
				}
			});
		}
	},

	importfields: {
		/*If user selects numeric range then fuzzy/exact match is not applicable*/
		numericRangeCheck: function(field, id, rowid) {
			if(field.value == "numericrange") {
				document.getElementById('row' + rowid + '[5]radios').style.display = "none";
				document.getElementById('row' + rowid + '[5]noradios').style.display = "block";
				document.getElementById('match[' + id + ']').disabled = false;
			} else {
				document.getElementById('row' + rowid + '[5]noradios').style.display = "none";
				document.getElementById('row' + rowid + '[5]radios').style.display = "block";
				document.getElementById('match[' + id + ']').disabled = true;
			}
		},

		/*To give pop-up box on before fields are save*/
		saveTargeting: function() {
			var r = confirm(Joomla.JText._("COM_SOCIALADS_SOCIAL_TARGETING_CONFIG_JSMESSAGE"));
			if(r == true) {
				document.adminForm.task.value = "importfields.save";
				document.adminForm.onsubmit();
				document.adminForm.submit();
				return true;
			}
			return false;
		},

		/*To show pop-up box before fields are reset*/
		resetTargeting: function() {
			var r = confirm(Joomla.JText._('COM_SOCIALADS_SOCIAL_TARGETING_CONFIG_JSMESSAGE1'));
			if(r == true) {
				document.adminForm.resetall.value = "1";
				document.adminForm.task.value = "importfields.save";
				document.adminForm.onsubmit();
				document.adminForm.submit();
				return true;
			} else {
				return false;
			}
		},

		/*To install targeting plugins*/
		installTargetingPlugins: function(namep) {
			var check = "#chk" + namep.name;
			jQuery.ajax({
				url: '?option=com_sa&task=importfields.addcolumn&col_name=' + namep.name,
				type: 'GET',
				dataType: "json",
				success: function(data) {
					if(data) {
						if(data.inmessage == "true") {
							jQuery(namep).attr('disabled', true)
							jQuery("#message1" + namep.name).append(data.smessage);
							jQuery(check).show('fast');
							jQuery(check + " input:checkbox").each(function() {
								this.checked = "checked";
							});
						}
						if(data.inmessage == "false") {
							jQuery(namep).attr('disabled', true)
							jQuery("#message1" + namep.name).append(data.smessage);
							jQuery(check).hide('fast');
							jQuery(check + " input:checkbox").each(function() {
								this.checked = "";
							});
						}
					}
				}
			});
		},

		submitImportfields: function(action) {
			var form = document.adminForm;
			var i = 0;
			if(action == 'importfields.reset') {
				var r = confirm(Joomla.JText._('COM_SOCIALADS_SOCIAL_TARGETING_CONFIG_JSMESSAGE1'));
				if(r == true) {
					Joomla.submitform('importfields.save', document.getElementById('adminForm'));
					return true;
				} else {
					return false;
				}
			}
			if(action == 'importfields.apply') {
				var r = confirm(Joomla.JText._('COM_SOCIALADS_SOCIAL_TARGETING_CONFIG_JSMESSAGE'));
				if(r == true) {
					Joomla.submitform('importfields.save', document.getElementById('adminForm'));
					return true;
				}
				return false;
			}
		},
		/*Form validation*/
		formValidation: function(value) {
			if(document.formvalidator.isValid(value)) {
				value.check.value = token;
				return true;
			} else {
				return false;
			}
		}
	},

	campaign: {
		/*Initialize campaign js*/
		initCampaignJs: function() {
			techjoomla.jQuery(document).ready(function() {
				techjoomla.jQuery('.alphaDecimalCheck').keyup(function() {
					saAdmin.checkForAlpha(this, 46);
				});
			});
		},
		campaignSubmitButton: function(task) {
			if(task == 'campaign.cancel') {
				Joomla.submitform(task, document.getElementById('campaign-form'));
			} else {
				if(task != 'campaign.cancel' && document.formvalidator.isValid(document.id('campaign-form'))) {
					Joomla.submitform(task, document.getElementById('campaign-form'));
				} else {
					alert(Joomla.JText._('JGLOBAL_VALIDATION_FORM_FAILED'));
				}
			}
		}
	},
	orders: {
		selectStatusOrder: function(appid, ele) {
			var selInd = ele.selectedIndex;
			var status = ele.options[selInd].value;
			document.getElementById('hidid').value = appid;
			document.getElementById('hidstat').value = status;
			submitbutton('orders.save');
			return;
		}
	},
	zone: {
		zoneAdTypes: function(value) {
			if(value == "text") {
				techjoomla.jQuery('#max_title').show();
				techjoomla.jQuery('#max_desc').show();
				techjoomla.jQuery('#layout_row').show();
				techjoomla.jQuery('#img_width').hide();
				techjoomla.jQuery('#img_height').hide();
				/*Code to Populate Layout*/
				var txtSelectedValuesObj = document.getElementById("layout");
				txtSelectedValuesObj.value = "";
				/*Remove required attribute for media fields*/
				techjoomla.jQuery('#jform_img_height').removeAttr("required");
				techjoomla.jQuery('#jform_img_width').removeAttr("required");
				/*Remove required class for media fields*/
				techjoomla.jQuery('#jform_img_height').removeClass("required");
				techjoomla.jQuery('#jform_img_width').removeClass("required");
				/*Make * required fields for text fields*/
				techjoomla.jQuery('#jform_max_title').attr("required", true);
				techjoomla.jQuery('#jform_max_des').attr("required", true);
			} else if(value == "media") {
				techjoomla.jQuery('#img_width').show();
				techjoomla.jQuery('#img_height').show();
				techjoomla.jQuery('#max_title').hide();
				techjoomla.jQuery('#max_des').hide();
				var txtSelectedValuesObj = document.getElementById("layout");
				txtSelectedValuesObj.value = "";
				/*Remove required attribute for text fields*/
				techjoomla.jQuery('#jform_max_title').removeAttr("required");
				techjoomla.jQuery('#jform_max_des').removeAttr("required");
				/*Remove required class for text fields*/
				techjoomla.jQuery('#jform_max_title').removeClass("required");
				techjoomla.jQuery('#jform_max_des').removeClass("required");
				/*Make * required fields for media fields*/
				techjoomla.jQuery('#jform_img_height').attr("required", true);
				techjoomla.jQuery('#jform_img_width').attr("required", true);
			} else if(value == "text_media") {
				techjoomla.jQuery('#max_title').show();
				techjoomla.jQuery('#max_des').show();
				techjoomla.jQuery('#img_height').show();
				techjoomla.jQuery('#img_width').show();
				var txtSelectedValuesObj = techjoomla.jQuery('#layout');
				document.getElementById("layout_row").style.display = "";
				txtSelectedValuesObj.value = "";
				/*Make * required fields for all fields*/
				techjoomla.jQuery('#jform_max_title').attr("required", true);
				techjoomla.jQuery('#jform_max_des').attr("required", true);
				techjoomla.jQuery('#jform_img_height').attr("required", true);
				techjoomla.jQuery('#jform_img_width').attr("required", true);
			}
		},
		trim: function(str) {
			return str.replace(/^\s+|\s+$/g, "");
		},

		getvalue: function(a, def) {
			if(a == undefined) {
				return null;
			}
			if(a.value) {
				return a.value;
			} else if(a.length) {
				if(a.options) {
					if(a.selectedIndex != -1)
						return a.options[a.selectedIndex].value;
				} else {
					for(var b = 0; b < a.length; b++) {
						if(a[b].checked) {
							return a[b].value;
						}
					}
				}
			}
			return def;
		},

		populatelayout: function() {
			var txtSelectedValuesObj = document.getElementById("layout");
			txtSelectedValuesObj.value == "";
			var count = "0";
			count = techjoomla.jQuery("[name=layout_select]:checkbox:checked").length;

			var allVals = [];
			techjoomla.jQuery("[name=layout_select]:checkbox:checked").each(function() {
				allVals.push(techjoomla.jQuery(this).val());
			});
			techjoomla.jQuery("#layout").val(allVals);
			return count;
		},

		yesnoToggle: function(elem) {
			var radio_id = techjoomla.jQuery(elem).attr("for");
			techjoomla.jQuery("#" + radio_id).attr("checked", "checked");
			/*for jQuery 1.9 and higher*/
			techjoomla.jQuery("#" + radio_id).prop("checked", true)
			var radio_btn = techjoomla.jQuery("#" + radio_id);
			var radio_value = radio_btn.val();
			var radio_name = techjoomla.jQuery("#" + radio_id).attr("name");
			var target_div = radio_name + "_div";
			techjoomla.jQuery(elem).parent().find("label").removeClass("btn-success").removeClass("btn-danger");
			if(radio_value == 1) {
				techjoomla.jQuery(elem).addClass("btn-success");
			}
			if(radio_value == 0) {
				techjoomla.jQuery(elem).addClass("btn-danger");
			}
			return radio_value;
		},

		codechanger: function(where) {
			var wid_code = "";
			if(where == "target") {
				wid_code = "<script>\n var Ad_widget_sitebase = \"" + saWidgetSiteRootUrl + "\";\n";
				if(techjoomla.jQuery("#field_target :input").length) {
					wid_code2 = "";
					techjoomla.jQuery("#field_target :input").each(function(i) {
						fname = techjoomla.jQuery(this).attr("name");
						fval = saAdmin.zone.getvalue(this, "")
						if(fval != "") {
							var fieldarr = fname.split("][")[1].split(",");
							if(fieldarr[0].charAt(fieldarr[0].length - 1) == "]") {
								fieldarr[0] = fieldarr[0].substr(0, fieldarr[0].length - 1);
							}
							wid_code2 += fieldarr[0] + " : \"" + fval + "\",";
						}
					});
					if(wid_code2 !== "") {
						wid_code2 = wid_code2.substring(0, wid_code2.length - 1);
						wid_code += "var Ad_targeting = {\n";
						wid_code += "social_params : {";
						wid_code += wid_code2;
						wid_code += "}\n}" + ";\n";
					}
				}
				wid_code += "</" + "script>";
				document.getElementById("wid_code").innerHTML = wid_code;
			}

			if(where == "widget") {
				wid_code = "<script>\n";
				wid_code += "var Ad_widget = {\n";
				var ad_unit = "sa_ads" + Math.floor(Math.random() * 50);
				wid_code += "ad_unit : \"" + ad_unit + "\",\n";
				wid_code += "zone : " + saWidgetZoneId + ",\n";
				wid_code += "num_ads : " + saAdmin.zone.getvalue(document.adminForm.num_ads, 2) + ",\n";
				wid_code += "ad_rotation : ";
				if(saAdmin.zone.getvalue(document.adminForm.rotate, 0) == "1") {
					wid_code += "1,\n";
					wid_code += "ad_rotation_delay : " + saAdmin.zone.getvalue(document.adminForm.rotate_delay, 10) + ",\n";
				} else {
					wid_code += "0,\n";
				}
				wid_code += "no_rand : ";
				if(saAdmin.zone.getvalue(document.adminForm.rand, 0) == "1") {
					wid_code += "1\n";
				} else {
					wid_code += "0\n";
				}
				wid_code += "}" + ";\n";
				wid_code += "</" + "script>\n";
				wid_code += "<div id=\"" + ad_unit + "\"></div>\n";
				wid_code += "<script type=\"text/javascript\" src=\""+widgetUrl;
				if(document.getElementById("if_ht").value || document.getElementById("if_wid").value || saAdmin.zone.getvalue(document.adminForm.if_seam, 0) == "1") {
					wid_code += "?";
					if(document.getElementById("if_ht").value) {
						wid_code += "ifheight=" + document.getElementById("if_ht").value + "&";
					}
					if(document.getElementById("if_wid").value) {
						wid_code += "ifwidth=" + document.getElementById("if_wid").value + "&";
					}
					if(saAdmin.zone.getvalue(document.adminForm.if_seam, 0) == "1") {
						wid_code += "ifseamless=" + saAdmin.zone.getvalue(document.adminForm.if_seam, 0) + "&";
					}
					wid_code = wid_code.substring(0, wid_code.length - 1);
				}
				wid_code += "\">";
				wid_code += "</" + "script>";
				wid_code += "\n";
				wid_code += "<script type=\"text/javascript\" >";
				wid_code += "\n// browser compatibility: get method for event ";
				wid_code += "\n// addEventListener(FF, Webkit, Opera, IE9+) and attachEvent(IE5-8) ";
				wid_code += "\nvar myEventMethod = window.addEventListener ? \"addEventListener\" : \"attachEvent\" ";
				wid_code += "\n// create event listener";
				wid_code += "\n var myEventListener = window[myEventMethod]; ";
				wid_code += "\n// browser compatibility: attach event uses onmessage";
				wid_code += "\nvar myEventMessage = myEventMethod == \"attachEvent\" ? \"onmessage\" : \"message\"; ";
				wid_code += "\n// register callback function on incoming message";
				wid_code += "\nmyEventListener(myEventMessage, function (e) { ";
				wid_code += "\n	// we will get a string (better browser support) and validate ";
				wid_code += "\n	// if it is an int - set the height of the iframe #my-iframe-id ";
				wid_code += "\n	if (e.data === parseInt(e.data)) ";
				wid_code += "\n		document.getElementById(\"idIframe_"+ad_unit+"\").height = e.data + \"px\"; ";
				wid_code += "\n}, false); ";
				wid_code += "\n";
				wid_code += "</"+"script>";
				document.getElementById("widunit_code").innerHTML = wid_code;
			}
		},
		validateFields: function(task) {
			if(task == 'zone.cancel') {
				Joomla.submitform(task, document.getElementById('adminForm'));
			} else {
				if(task != 'zone.cancel' && document.formvalidator.isValid(document.id('adminForm'))) {
					var form = document.adminForm;
					var add_type_label = form.jform_ad_type;
					var addtype = add_type_label.options[add_type_label.selectedIndex].value;
					var zone_type_label = form.jform_zone_type
					var flag = 0;
					if(addtype == "text") {

						document.getElementById("validate_img_width").innerHTML = "";
						document.getElementById("validate_img_height").innerHTML = "";

						if((saAdmin.zone.trim(form.jform_max_title.value) == "")) {
							document.getElementById("validate_max_title").innerHTML = Joomla.JText._('COM_SOCIALADS_YOU_MUST_PROVIDE_A_MAX_TITLE_CHAR');
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_max_title.value)) || (parseInt(form.jform_max_title.value) == 0)) {
								document.getElementById("validate_max_title").innerHTML = Joomla.JText._('COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC');
								flag = 1;
								return false;
							}
						}
						if(saAdmin.zone.trim(form.jform_max_des.value) == "") {
							document.getElementById("validate_max_des").innerHTML = Joomla.JText._('COM_SOCIALADS_YOU_MUST_PROVIDE_A_MAX_DESC_CHAR');
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_max_des.value)) || (parseInt(form.jform_max_des.value) == 0)) {
								document.getElementById("validate_max_des").innerHTML = Joomla.JText._('COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC');
								flag = 1;
								return false;
							}
						}
					}
					if(addtype == "media") {
						document.getElementById("validate_max_title").innerHTML = "";
						document.getElementById("validate_max_des").innerHTML = "";
						if(saAdmin.zone.trim(form.jform_img_width.value) == "") {
							document.getElementById("validate_img_width").innerHTML = Joomla.JText._("COM_SOCIALADS_YOU_PROVIDE_A_IMG_WIDTH");
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_img_width.value)) || (parseInt(form.jform_img_width.value) == 0)) {
								document.getElementById("validate_img_width").innerHTML = Joomla.JText._("COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC");
								flag = 1;
								return false;
							}
						}
						if(saAdmin.zone.trim(form.jform_img_height.value) == "") {
							document.getElementById("validate_img_height").innerHTML = Joomla.JText._("COM_SOCIALADS_YOU_PROVIDE_A_IMG_HEIGHT");
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_img_height.value)) || (parseInt(form.jform_img_height.value) == 0)) {
								document.getElementById("validate_img_height").innerHTML = Joomla.JText._("COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC");
								flag = 1;
								return false;
							}
						}
					}
					if(addtype == "text_media") {
						document.getElementById("validate_img_width").innerHTML = "";
						document.getElementById("validate_img_height").innerHTML = "";
						document.getElementById("validate_max_title").innerHTML = "";
						document.getElementById("validate_max_des").innerHTML = "";
						if(saAdmin.zone.trim(form.jform_max_title.value) == "") {
							document.getElementById("validate_max_title").innerHTML = Joomla.JText._('COM_SOCIALADS_YOU_MUST_PROVIDE_A_MAX_TITLE_CHAR');
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_max_title.value)) || (parseInt(form.jform_max_title.value) == 0)) {
								document.getElementById("validate_max_title").innerHTML = Joomla.JText._('COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC');
								flag = 1;
								return false;
							}
						}
						if(saAdmin.zone.trim(form.jform_max_des.value) == "") {
							document.getElementById("validate_max_des").innerHTML = Joomla.JText._('COM_SOCIALADS_YOU_MUST_PROVIDE_A_MAX_DESC_CHAR');
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_max_des.value)) || (parseInt(form.jform_max_des.value) == 0)) {
								document.getElementById("validate_max_des").innerHTML = Joomla.JText._('COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC');
								flag = 1;
								return false;
							}
						}
						if(saAdmin.zone.trim(form.jform_img_width.value) == "") {
							document.getElementById("validate_img_width").innerHTML = Joomla.JText._('COM_SOCIALADS_YOU_PROVIDE_A_IMG_WIDTH');
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_img_width.value)) || (parseInt(form.jform_img_width.value) == 0)) {
								document.getElementById("validate_img_width").innerHTML = Joomla.JText._('COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC');
								flag = 1;
								return false;
							}
						}
						if(saAdmin.zone.trim(form.jform_img_height.value) == "") {
							document.getElementById("validate_img_height").innerHTML = Joomla.JText._('COM_SOCIALADS_YOU_PROVIDE_A_IMG_HEIGHT');
							flag = 1;
							return false;
						} else {
							if(isNaN(saAdmin.zone.trim(form.jform_img_height.value)) || (parseInt(form.jform_img_height.value) == 0)) {
								document.getElementById("validate_img_height").innerHTML = Joomla.JText._('COM_SOCIALADS_VALIDATE_NON_ZERO_NUMERIC');
								flag = 1;
								return false;
							}
						}
					}

					if(!flag)
					{
						if(recordsCount != 0)
						{
							var con = confirm(Joomla.JText._('COM_SOCIALADS_ZONE_MSG_ON_EDIT_ZONE'));

							if (con == true)
							{
								return true;
							}
							else
							{
								return false;
							}
						}

						return true;
					}

					return true;

					// Joomla.submitform(task, document.getElementById('adminForm'));
				}
			}
		}
	},
	zones: {
		submitButtonAction: function(action) {
			cnt=0;
			if(action == 'zones.delete')
			{
					for(i=0;i<cblength;i++)
					{
						if (document.getElementById("cb"+i).checked==true)
						{
							var no_ad=document.getElementById("no_of_ads"+i).value;

							if(parseInt(no_ad))
							{
								alert(Joomla.JText._('COM_SOCIALADS_ZONE_DEL_NOT_ABLE_TO_DELETE'));
								techjoomla.jQuery("#cb"+i).attr('checked', false);
								return;
							}
							else
							{
								var delCoupon = confirm(Joomla.JText._('COM_SOCIALADS_ZONE_DEL_SURE_MSG'));

								if(delCoupon === false)
								{
									return;
								}
							}
						}
					}

				Joomla.submitform(action);
				return;
			}
		else
		{
			Joomla.submitform(action);
		}
	}
	}
}

/*Create ad, user selection field function*/
function jSelectUser_jform_created_by(id, title){
	var old_id = document.getElementById("ad_creator_id").value;
	if (old_id != id) {
		document.getElementById("ad_creator_id").value = id;
		document.getElementById("ad_creator_name").value = title;
	}
	SqueezeBox.close();
}

/*Modified J3.x code for backend list view ordering*/
Joomla.orderTable = function() {
	table = document.getElementById("sortTable");
	direction = document.getElementById("directionTable");
	order = table.options[table.selectedIndex].value;
	if(order != tjListOrderingColumn) {
		dirn = 'asc';
	} else {
		dirn = direction.options[direction.selectedIndex].value;
	}
	Joomla.tableOrdering(order, dirn, '');
}

loadingImage = function() {
	techjoomla.jQuery('<div id="appsloading"></div>')
		.css("background", "rgba(255, 255, 255, .8) url('" + root_url + "media/com_sa/images/ajax.gif') 50% 15% no-repeat")
		.css("top", techjoomla.jQuery('#TabConetent').position().top - techjoomla.jQuery(window).scrollTop())
		.css("left", techjoomla.jQuery('#TabConetent').position().left - techjoomla.jQuery(window).scrollLeft())
		.css("width", techjoomla.jQuery('#TabConetent').width())
		.css("height", techjoomla.jQuery('#TabConetent').height())
		.css("position", "fixed")
		.css("z-index", "1000")
		.css("opacity", "0.80")
		.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
		.css("filter", "alpha(opacity = 80)")
		.appendTo('#TabConetent');
}

hideImage = function() {
	techjoomla.jQuery('#appsloading').remove();
}
