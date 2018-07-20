<?php
/*------------------------------------------------------------------------
# defaultsimple.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OsAppscheduleDefaultSimple{
	/**
	 * Default layout of OS Services Booking component
	 *
	 * @param unknown_type $option
	 * @param unknown_type $services
	 * @param unknown_type $year
	 * @param unknown_type $month
	 * @param unknown_type $day
	 * @param unknown_type $category
	 * @param unknown_type $employee_id
	 * @param unknown_type $vid
	 */
	function defaultLayoutHTML($option,$services,$year,$month,$day,$category,$employee_id,$vid,$date_from,$date_to,$lists){
		global $mainframe,$configClass;
		//jimport('joomla.html.pane');
		$methods = os_payments::getPaymentMethods(true, $onlyRecurring) ;
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osservicesbooking');?>" name="appform">
		<div class="row-fluid" id="osbcontainer">
			<?php
			if(($category->id >0) and ($category->show_desc == 1)){
			?>
				<div class="span12">
					<div class="div_category_details">
						<div class="div_category_name">
							<?php
							echo OSBHelper::getLanguageFieldValue($category,'category_name');
							?>
						</div>
						<?php
						if($category->category_photo != ""){
							if(file_exists(JPATH_ROOT.'/images/osservicesbooking/category/'.$category->category_photo)){
								?>
								<div style="float:left;">
									<img src="<?php echo JUri::root();?>images/osservicesbooking/category/<?php echo $category->category_photo; ?>" style="max-width:170px;margin-right:10px;" /> 
								</div>
								<?php 
							}
						}
						echo OSBHelper::getLanguageFieldValue($category,'category_description');
						?>
					</div>
				</div>
				<div class="clearfix"></div>
			<?php
			}
			if(count($services) == 1){
				?>
				<div class="row-fluid">
					<div class="span12">
						<div class="div_service_details">
							<div class="div_service_name">
								<?php
								echo OSBHelper::getLanguageFieldValue($services[0],'service_name');
								?>
							</div>
							
							<?php
							if($services[0]->service_photo != ""){
								if(file_exists(JPATH_ROOT.'/images/osservicesbooking/services/'.$services[0]->service_photo)){
									?>
									<div style="float:left;">
										<img src="<?php echo JUri::root();?>images/osservicesbooking/services/<?php echo $services[0]->service_photo; ?>" style="max-width:170px;margin-right:10px;" /> 
									</div>
									<?php 
								}
							}
							echo OSBHelper::getLanguageFieldValue($services[0],'service_description');
							?>
							<div class="div_service_information_box">
								<?php HelperOSappscheduleCommon::getServiceInformation($services[0]);?>
							</div>
						</div>
					</div>
				</div>
				<?php 
			}
			?>
			
			<div class="row-fluid">
				<?php if(($configClass['using_cart'] == 1) or (!OSBHelper::isTheSameDate($date_from,$date_to))){
				$secondDiv = "span8";
				?>
				<div class="span4" id="calendardivleft">
					<?php 
					if(count($services) > 1){
						?>
						<div class="row-fluid">
							<div class="span12">
								<div class="row-fluid bookingformdiv">
									<div class="span12 <?php echo $configClass['header_style']?>" style="margin-bottom:10px;">
										<strong>
											<?php
											echo JText::_('OS_SELECT_SERVICE');
											?>
										</strong>
									</div>
									<?php 
									echo $lists['services'];
									?>
								</div>
							</div>
						</div>
						<?php 
					}
					if(intval($employee_id) == 0){
						
						?>
						
						<?php 
					}
					?>
					<?php if(!OSBHelper::isTheSameDate($date_from,$date_to)){?>
					<div class="row-fluid">
						<div class="span12">
							<?php
							HelperOSappscheduleCalendar::initCalendarForSeveralYear(intval(date("Y",HelperOSappscheduleCommon::getRealTime())),$category->id,$employee_id,$vid,$date_from,$date_to);
							?>
							<input type="hidden" name="ossmh" id="ossmh" value="<?php echo intval(date("m",HelperOSappscheduleCommon::getRealTime()))?>" />
							<input type="hidden" name="ossyh" id="ossyh" value="<?php echo intval(date("Y",HelperOSappscheduleCommon::getRealTime()))?>" />
						</div>
					</div>
					<?php }
					if($configClass['using_cart'] == 1){
					?>
					<div class="clearfix" style="height:10px;"></div>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid bookingformdiv">
								<div class="span12 <?php echo $configClass['header_style']?>">
									<?php
									if($configClass['disable_payments'] == 1){
									?>
									<div style="float:left;margin-right:5px;">
										<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/arttocart.png">
									</div>
									<div style="float:left;padding-top:4px;">
										<?php echo JText::_('OS_CART')?>
									</div>
									<?php
									}else{
									?>
									<div style="float:left;padding-top:4px;">
										<?php echo JText::_('OS_BOOKING_INFO');?>
									</div>
									<?php
									}
									?>
								</div>
								<table  width="100%">
									<tr>
										<td width="100%" style="padding:5px;" valign="top">
											<div id="cartdiv">
												<?php
												$userdata = $_COOKIE['userdata'];
												OsAppscheduleAjax::cart($userdata,$vid,$category->id,$employee_id,$date_from,$date_to);
												?>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div id="servicebox" style="display:none;">
								
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="clearfix"></div>
				</div>
				<?php }else{
					$secondDiv = "span12";
				}
				?>
				<div class="<?php echo $secondDiv;?>" id="maindivright">
					<div id="maincontentdiv">
					<?php
					OsAppscheduleAjax::loadServices($option,$services,$year,$month,$day,$category->id,$employee_id,$vid);
					?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
			if($configClass['show_footer'] == 1){
				if($configClass['footer_content'] != ""){
					?>
					<div class="osbfootercontent">
						<?php echo $configClass['footer_content'];?>
					</div>
					<?php
				}
			}
			?>
		</div>
		</div>
		<input type="hidden" name="option" value="com_osservicesbooking"  />
		<input type="hidden" name="task" value="">
		<input type="hidden" name="month"  id="month" value="<?php echo intval(date("m",HelperOSappscheduleCommon::getRealTime()))?>" />
		<input type="hidden" name="year"  id="year" value="<?php echo date("Y",HelperOSappscheduleCommon::getRealTime())?>" />
		<input type="hidden" name="day"  id="day" value="<?php echo intval(date("d",HelperOSappscheduleCommon::getRealTime()));?>" />
		<input type="hidden" name="select_day" id="select_day" value="<?php echo $day;?>" />
		<input type="hidden" name="select_month" id="select_month" value="<?php echo $month;?>" />
		<input type="hidden" name="select_year" id="select_year" value="<?php echo $year;?>" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>"  />
		<input type="hidden" name="order_id" id="order_id" value="" />
		<input type="hidden" name="current_date" id="current_date" value=""  />
		<input type="hidden" name="use_captcha" id="use_captcha" value="<?php echo $configClass['value_sch_include_captcha'];?>" />
		<input type="hidden" name="category_id" id="category_id" value="<?php echo intval($category->id);?>" />
		<input type="hidden" name="employee_id" id="employee_id" value="<?php echo intval($employee_id);?>" />
		<input type="hidden" name="vid" id="vid" value="<?php echo intval($vid);?>" />
		<input type="hidden" name="selected_item" id="selected_item" value="" />
		<input type="hidden" name="sid" id="sid" value="" />
		<input type="hidden" name="eid" id="eid" value="" />
		<input type="hidden" name="current_link" id="current_link" value="<?php echo $configClass['current_link']?>" />
		<input type="hidden" name="calendar_normal_style" id="calendar_normal_style" value="<?php echo $configClass['calendar_normal_style'];?>" />
		<input type="hidden" name="calendar_currentdate_style" id="calendar_currentdate_style" value="<?php echo $configClass['calendar_currentdate_style'];?>" />
		<input type="hidden" name="calendar_activate_style" id="calendar_activate_style" value="<?php echo $configClass['calendar_activate_style'];?>" />
		<input type="hidden" name="use_js_popup" id="use_js_popup" value="<?php echo $configClass['use_js_popup'];?>" />
		<input type="hidden" name="using_cart" id="using_cart" value="<?php echo $configClass['using_cart'];?>" />
		<input type="hidden" name="date_from" id="date_from" value="<?php echo $date_from?>" />
		<input type="hidden" name="date_to" id="date_to" value="<?php echo $date_to?>" />
		<input type="hidden" name="unique_cookie" id="unique_cookie" value="<?php echo $_COOKIE['unique_cookie'];?>" />
		<input type="hidden" name="Itemid" id="Itemid" value="<?php echo Jrequest::getInt('Itemid',0);?>" />
		</form>
		<div  id="divtemp" style="width:1px;height:1px;"></div>
		<script language="javascript">
		<?php
		os_payments::writeJavascriptObjects();
		?>
		function addtoCart(sid,eid,time_length){
			var form			= document.appform;
			var category_id		= document.getElementById('category_id');
			var employee_id     = document.getElementById('employee_id');
			var bookitem		= document.getElementById('book_' + sid +  '_' + eid);
			var end_bookitem 	= document.getElementById('end_book_' + sid +  '_' + eid);
			end_bookitem		= end_bookitem.value;
			var startitem 		= document.getElementById('start_' + sid +  '_' + eid);
			var enditem 		= document.getElementById('end_' + sid +  '_' + eid);
			var summary 		= document.getElementById('summary_' + sid +  '_' + eid);
			var str = "";
			var selected_item   = document.getElementById('selected_item');
			selected_item.value = 'employee' + sid + '_' + eid;

			var repeat_name     = sid + "_"+ eid;
			var repeat_type		= document.getElementById('repeat_type_' + repeat_name);
			var repeat_type1	= document.getElementById('repeat_type_' + repeat_name + '1');
			var repeat_amount   = document.getElementById('repeat_to_' + repeat_name);
			var rtype		  	= "";
			var rtype1		  	= "";
			var ramount			= "";
			var repeat          = "";
			if(repeat_amount != null){
				ramount = repeat_amount.value;
			}
			if(repeat_type != null){
				rtype = repeat_type.value;
			}
			if(repeat_type1 != null){
				rtype1 = repeat_type1.value;
			}
			if((ramount != "") && (repeat_type != "") && (repeat_type1 != "")){
				repeat_to		= ramount + "|" + rtype1;
				repeat  		= "" + rtype + "|" + repeat_to;
			}
			
			var vidElement = document.getElementById('vid');
			if(vidElement != null){
				vid = vidElement.value;
			}else{
				vid =  0;
			}
			
			var hasValue = 0;
			if(bookitem.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_SELECT_START_TIME');?>");
				return false;
			}else{
				var field_ids   = document.getElementById('field_ids' + sid);
				field_ids		= field_ids.value;
				if(field_ids != ""){
					var fieldArr 	= new Array();
					fieldArr 		= field_ids.split(",");
					var temp;
					var label;
					if(fieldArr.length > 0){
						for(i=0;i<fieldArr.length;i++){
							temp = fieldArr[i];
							var element = document.getElementById('field_' + sid + '_' + eid + '_' + temp + '_selected');
							//label = document.getElementById('field_' + sid + '_' + eid + '_' + temp + '_label');
							//if(element.value != ""){
							//	str += "<strong>" + label.value + ":</strong> " + element.value + "<BR />";
							//}
							if(element != null){
								if(element.value != ""){
									hasValue = 1;
									str += temp + "-" + element.value  + "@@";
								}
							}
						}
						//summary.innerHTML = str;
						if(hasValue == 1){
							str = str.substring(0,str.length - 1);
						}

					}
				}
				<?php if($configClass['use_js_popup'] == 1){?>
				var answer = confirm("<?php echo JText::_('OS_ARE_YOU_SURE_TO_BOOK')?>");
				<?php }else{ ?>
				var answer = 1;
				<?php } ?>
				var end_booking_time = parseInt(bookitem.value) + parseInt(time_length);
				if(answer == 1){
					var live_site = document.getElementById('live_site');
					var x = document.getElementsByName("addtocartbtn");
					var i;
					//disable all buttons in the form
					for (i = 0; i < x.length; i++) {
							x[i].disabled = true;
					}
					
					addtoCartAjax(bookitem.value,end_bookitem,sid,eid,live_site.value,str,repeat,vid,category_id.value,employee_id.value);
				}
			}
		}

		function removeItem(itemid,sid,start_time,end_time,eid){
			<?php if($configClass['use_js_popup'] == 1){?>
			var answer = confirm("<?php  echo JText::_('OS_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_BOOKING')?>");
			<?php }else{ ?>
			var answer = 1;
			<?php } ?>
			if(answer == 1){
				var category_id		= document.getElementById('category_id');
				var employee_id     = document.getElementById('employee_id');
				var vid				= document.getElementById('vid');
				var live_site = document.getElementById('live_site');
				removeItemAjax(itemid,live_site.value,sid,start_time,end_time,eid, category_id.value, employee_id.value,vid.value);
			}
		}
		
		var screenWidth = jQuery(window).width();
		if(screenWidth < 350){
			jQuery(".buttonpadding10").removeClass("buttonpadding10").addClass("buttonpadding5");
		}else{
			jQuery(".buttonpadding5").removeClass("buttonpadding5").addClass("buttonpadding10");
			if(document.getElementById('calendardivleft') != null){
				var leftwidth = jQuery("#calendardivleft").width();
				if(leftwidth > 250){
					jQuery("#calendardivleft").removeClass("span5").removeClass("span6").addClass("span4");
					jQuery("#maindivright").removeClass("span7").removeClass("span6").addClass("span8");
				}else if(leftwidth < 210){
					jQuery("#calendardivleft").removeClass("span5").removeClass("span4").addClass("span6");
					jQuery("#maindivright").removeClass("span7").removeClass("span8").addClass("span6");
				}else{
					jQuery("#calendardivleft").removeClass("span4").removeClass("span6").addClass("span5");
					jQuery("#maindivright").removeClass("span8").removeClass("span6").addClass("span7");
				}
			}
		}

		//alert(jQuery("#osbcontainer").width());
		//article only
		flexScreen = jQuery("#osbcontainer").width();
		if(flexScreen < 500){
			jQuery("#calendardivleft").removeClass("span6").removeClass("span5").removeClass("span4").addClass("span12");
			jQuery("#maindivright").removeClass("span6").removeClass("span7").removeClass("span8").addClass("span12");
			jQuery(".timeslots").removeClass("span6").addClass("span12");
			jQuery("#maindivright").attr("style","margin-left:0px !important;margin-top:10px !important");
		}
		</script>
		<?php
	}
}
?>