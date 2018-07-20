<?php
/*------------------------------------------------------------------------
# default.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OsAppscheduleDefault{
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
	function defaultLayoutHTML($option,$services,$year,$month,$day,$category,$employee_id,$vid,$sid,$date_from,$date_to){
		global $mainframe,$configClass,$deviceType;
		//jimport('joomla.html.pane');
        if(intval($month) < 10){
            $month1 = "0".$month;
        }else{
            $month1 = $month;
        }
        if(intval($day) < 10){
            $day1 = "0".$day;
        }else{
            $day1 = $day;
        }
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
                $sid = $services[0]->id;
                JRequest::setVar('sid',$sid);
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
								<?php HelperOSappscheduleCommon::getServiceInformation($services[0],$year,$month1,$day1);?>
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
				<div class="span4" id="calendardivleft" class="hidden-phone">
					<?php if(!OSBHelper::isTheSameDate($date_from,$date_to)){?>
					<div class="row-fluid">
						<div class="span12">
							<?php
							HelperOSappscheduleCalendar::initCalendarForSeveralYear(intval(date("Y",HelperOSappscheduleCommon::getRealTime())),$category->id,$employee_id,$vid,$date_from,$date_to);
							?>
							<input type="hidden" name="ossmh" id="ossmh" value="<?php echo $month; ?>" />
							<input type="hidden" name="ossyh" id="ossyh" value="<?php echo $year; ?>" />
						</div>
					</div>
					<?php }
					if(($configClass['using_cart'] == 1) and ($deviceType != "mobile")){
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
					OsAppscheduleAjax::loadServices($option,$services,$year,$month,$day,$category->id,$employee_id,$vid,count($servies));
					?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
			if(($configClass['using_cart'] == 1) and ($deviceType == "mobile")){
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
			<?php }
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
		<input type="hidden" name="month"  id="month" value="<?php echo $month; ?>" />
		<input type="hidden" name="year"  id="year" value="<?php echo $year; ?>" />
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
		<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>" />
		<input type="hidden" name="eid" id="eid" value="" />
		<input type="hidden" name="current_link" id="current_link" value="<?php echo $configClass['current_link']?>" />
		<input type="hidden" name="calendar_normal_style" id="calendar_normal_style" value="<?php echo $configClass['calendar_normal_style'];?>" />
		<input type="hidden" name="calendar_currentdate_style" id="calendar_currentdate_style" value="<?php echo $configClass['calendar_currentdate_style'];?>" />
		<input type="hidden" name="calendar_activate_style" id="calendar_activate_style" value="<?php echo $configClass['calendar_activate_style'];?>" />
		<input type="hidden" name="use_js_popup" id="use_js_popup" value="<?php echo $configClass['use_js_popup'];?>" />
		<input type="hidden" name="using_cart" id="using_cart" value="<?php echo $configClass['using_cart'];?>" />
		<input type="hidden" name="date_from" id="date_from" value="<?php echo $date_from?>" />
		<input type="hidden" name="date_to" id="date_to" value="<?php echo $date_to?>" />
		<input type="hidden" name="unique_cookie" id="unique_cookie" value="<?php echo OSBHelper::getUniqueCookie();?>" />
        <input type="hidden" name="temp_item" id="temp_item" value="" />
		<input type="hidden" name="Itemid" id="Itemid" value="<?php echo Jrequest::getInt('Itemid',0);?>" />
		<input type="hidden" name="count_services" id="count_services" value="<?php echo count($services);?>" />
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
                if(field_ids != null) {
                    field_ids = field_ids.value;
                    if (field_ids != "") {
                        var fieldArr = new Array();
                        fieldArr = field_ids.split(",");
                        var temp;
                        var label;
                        if (fieldArr.length > 0) {
                            for (i = 0; i < fieldArr.length; i++) {
                                temp = fieldArr[i];
                                var element		= document.getElementById('field_' + sid + '_' + eid + '_' + temp + '_selected');
								var required	= document.getElementById('field_' + sid + '_' + eid + '_' + temp + '_required');
								var label		= document.getElementById('field_' + sid + '_' + eid + '_' + temp + '_label');
                                if (element != null) {
                                    if (element.value != "") {
                                        hasValue = 1;
                                        str += temp + "-" + element.value + "@@";
                                    }else if(required.value == "1"){
										alert(label.value + "<?php echo JText::_('OS_IS_MANDATORY_FIELD');?>");
										return false;
									}
                                }
                            }
                            //summary.innerHTML = str;
                            if (hasValue == 1) {
                                str = str.substring(0, str.length - 1);
                            }

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
				//alert(sid);
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

        function changingEmployee(sid){
            var select_item = jQuery("#employeeslist_" + sid).val();
            var existing_services = jQuery("#employeeslist_ids" + sid).val();
            existing_services = existing_services.split("|");
            if(existing_services.length > 0){
                for(i=0;i<existing_services.length;i++){
                    jQuery("#pane" + sid +  existing_services[i]).removeClass("active");
                }
            }
            jQuery("#pane" + sid +  select_item).addClass("active");
        }

        function changingService(){
            var select_item = jQuery("#serviceslist").val();
            var existing_services = jQuery("#serviceslist_ids").val();
            existing_services = existing_services.split("|");
            if(existing_services.length > 0){
                for(i=0;i<existing_services.length;i++){
                    jQuery("#pane" + existing_services[i]).removeClass("active");
                }
            }
            jQuery("#pane" + select_item).addClass("active");
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

	/**
	 * Show failure Payment
	 *
	 * @param unknown_type $reason
	 */
	function failureHtml($reason){
		global $mainframe,$configClass;
		?>
		<h1 class="eb_title"><?php echo JText::_('OS_REGISTRATION_FAILURE'); ?></h1>
			<table width="100%">	
				<tr>
					<td colspan="2" align="left">
						<?php echo  JText::_('OS_FAILURE_MESSAGE'); ?>
					</td>
				</tr>	
				<tr>
					<td valign="top">
						<?php echo JText::_('OS_REASON'); ?>
					</td>
					<td>
						<p class="info"><?php echo $reason; ?></p>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="button" class="btn btn-primary" value="<?php echo JText::_('OS_BACK'); ?>" onclick="window.history.go(-1);" />
					</td>
				</tr>	
			</table>
		<?php
	}

	/**
	 * List all orders history
	 *
	 * @param unknown_type $orders
	 */
	function listOrdersHistory($rows){
		global $mainframe,$configClass;
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osservicesbooking&task=default_customer&Itemid='.JRequest::getVar('Itemid'))?>" name="ftForm">
		<table width="100%" class="table table-stripped">
			<tr>
				<td width="50%">
					<div style="font-size:15px;font-weight:bold;">
						<?php echo JText::_('OS_MY_ORDERS');?>
					</div>
				</td>
				<td	width="50%" style="text-align:right;" class="hidden-phone">
					<input type="button" class="btn btn-success" value="<?php echo JText::_('OS_MY_BOOKING_CALENDAR')?>" title="<?php echo JText::_('OS_GO_TO_MY_WORKING_CALENDAR')?>" onclick="javascript:customercalendar('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" title="<?php echo JText::_('OS_GO_BACK')?>" onclick="javascript:history.go(-1);"/>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<div style="float:left;padding-right:10px;">
					<?php echo JText::_('OS_FROM')?>:
					<?php echo JHTML::_('calendar',JRequest::getVar('date1',''), 'date1', 'date1', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'10',  'maxlength'=>'19')); ?>
					</div>
					<div style="float:left;padding-right:10px;">
					<?php echo JText::_('OS_TO')?>:
					<?php echo JHTML::_('calendar',JRequest::getVar('date2',''), 'date2', 'date2', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'10',  'maxlength'=>'19')); ?>
					</div>
					<div style="float:left;">
					<input type="submit" value="<?php echo JText::_('OS_FILTER');?>" class="btn btn-primary">
					</div>
				</td>
			</tr>
			<?php
			if(count($rows) > 0){
			?>
			<tr>
				<td width="100%" style="padding-top:20px;"  colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="3%" class="osbtdheader hidden-phone">
								#
							</td>
							<td width="15%" class="osbtdheader">
								<?php echo JText::_('OS_SERVICE');?>
							</td>
							<td width="10%" class="osbtdheader">
								<?php echo JText::_('OS_DATE');?>
							</td>
							<td width="10%" class="osbtdheader">
								<?php echo JText::_('OS_STATUS');?>
							</td>
							<td width="10%" class="osbtdheader hidden-phone">
								<?php echo JText::_('OS_ORDER_DETAILS');?>
							</td>
							<?php if($configClass['allow_cancel_request'] == 1){?>
								<td width="10%" class="osbtdheader">
									<?php echo JText::_('OS_REMOVE_ORDER');?>
								</td>
							<?php }?>
							<td width="3%" class="osbtdheader hidden-phone">
								ID
							</td>
						</tr>
						<?php
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							if($i % 2 == 0){
								$bgcolor = "#efefef";
							}else{
								$bgcolor = "#fff";
							}
							?>
							<tr>
								<td class="td_data hidden-phone" style="background-color:<?php echo $bgcolor?>;">
									<?php echo $i + 1;?>
								</td>
								<td class="td_data" style="background-color:<?php echo $bgcolor?>;">
									<?php echo $row->service?>
								</td>
								<td class="td_data" style="background-color:<?php echo $bgcolor?>;">
									<?php echo date($configClass['date_time_format'],strtotime($row->order_date));?>
								</td>
								<td class="td_data" style="background-color:<?php echo $bgcolor?>;">
									<?php
									if($row->order_status == "P"){
										?>
										<p class="text-warning">
											<?php echo JText::_('OS_PENDING');?>
										</p>
										<?php
									}elseif($row->order_status == "C"){
										?>
										<p class="text-success">
											<?php echo JText::_('OS_CANCEL');?>
										</p>
										<?php
									}else{
										?>
										<p class="text-success">
											<?php 
											echo OSBHelper::orderStatus(0,$row->order_status);?>
										</p>
										<?php
									}
									?>
								</td>
								<td class="td_data hidden-phone" style="background-color:<?php echo $bgcolor?>;text-align:center;">
									<a href="<?php echo JRoute::_("index.php?option=com_osservicesbooking&task=default_orderDetailsForm&id=".$row->id."&ref=".md5($row->id)."&Itemid=".Jrequest::getVar('Itemid',0));?>" title="<?php echo JText::_('OS_CLICK_HERE_TO_VIEW_ORDER_DETAILS');?>" />
										<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/orderdetails.png" />
									</a>
								</td>
								<?php if($configClass['allow_cancel_request'] == 1){?>
									<td class="td_data" style="background-color:<?php echo $bgcolor?>;text-align:center;">
										<a href="javascript:removeOrder(<?php echo $row->id?>,'<?php echo JText::_('OS_DO_YOU_WANT_T0_REMOVE_ORDER')?>','<?php echo JURI::root()?>','<?php echo Jrequest::getVar('Itemid',0);?>');" title="<?php echo JText::_('OS_CLICK_HERE_TO_REMOVE_ORDER_DETAILS');?>" />
											<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/icon-16-deny.png" />
										</a>
									</td>
								<?php }?>
								<td class="td_data hidden-phone" style="background-color:<?php echo $bgcolor?>;text-align:center;">
									<?php echo $row->id;?>
								</td>
							</tr>
							<tr  class="warning">
								<td colspan="7" style="width:100%">
									<a href="javascript:openOtherInformation(<?php echo $row->id;?>,'<?php echo JText::_('OS_OTHER_INFORMATION');?>');" id="href<?php echo $row->id;?>">
									[+]&nbsp;<?php echo JText::_('OS_OTHER_INFORMATION');?>
									</a>
									<div style="display:none;" id="order<?php echo $row->id?>">
										<?php
										OsAppscheduleDefault::getListOrderServices($row->id);
										?>
									</div>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
			<?php
			}else{
			?>
			<tr>
				<td width="100%" align="center" style="padding:20px;" colspan="2">
					<strong><?php echo JText::_('OS_NO_BOOKING_REQUEST');?></strong>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
		if($configClass['footer_content'] != ""){
			?>
			<div class="osbfootercontent">
				<?php echo $configClass['footer_content'];?>
			</div>
			<?php
		}
		?>
		<input type="hidden" name="option" value="com_osservicesbooking"  />
		<input type="hidden" name="task" value="default_customer" />
		<input type="hidden" name="oid" id="oid" value="" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
		</form>
		<?php
	}
	
	function listEmployeeWorks($employee,$rows){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		jimport('joomla.filesystem.folder');
		?>
		<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osservicesbooking&task=default_employeeworks&Itemid='.JRequest::getVar('Itemid'))?>" name="ftForm">
		<table width="100%" class="table table-stripped">
			<tr>
				<td width="30%">
					<div style="font-size:15px;font-weight:bold;">
						<?php echo JText::_('OS_MY_WORKKING_LIST');?>
					</div>
				</td>
				<td	width="70%" style="text-align:right;" class="hidden-phone">
					<?php
					if($configClass['employee_change_availability'] == 1){
						?>
						<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_AVAILABILITY_STATUS')?>" title="<?php echo JText::_('OS_AVAILABILITY_STATUS')?>" onclick="javascript:workingavailabilitystatus('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
						<?php
					}
					?>
					<input type="button" class="btn btn-success" value="<?php echo JText::_('OS_MY_WORKING_CALENDAR')?>" title="<?php echo JText::_('OS_GO_TO_MY_WORKING_CALENDAR')?>" onclick="javascript:workingcalendar('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
					<?php
					if(($configClass['integrate_gcalendar'] == 1) and (JFolder::exists(JPATH_ROOT.DS."Zend")) and ($employee->gcalendarid != "")){
						?>
						<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_MY_GCALENDAR')?>" title="<?php echo JText::_('OS_MY_GCALENDAR')?>" onclick="javascript:gcalendar('<?php echo JURI::root()?>','<?php  echo Jrequest::getVar('Itemid',0)?>')"/>
						<?php
					}
					?>
					<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" title="<?php echo JText::_('OS_GO_BACK')?>" onclick="javascript:history.go(-1);"/>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<div style="float:left;padding-right:10px;">
					<?php echo JText::_('OS_WORK_FROM')?>:
					<?php echo JHTML::_('calendar',JRequest::getVar('date1',''), 'date1', 'date1', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'10',  'maxlength'=>'19')); ?>
					</div>
					<div style="float:left;padding-right:10px;">
					<?php echo JText::_('OS_WORK_TO')?>:
					<?php echo JHTML::_('calendar',JRequest::getVar('date2',''), 'date2', 'date2', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'10',  'maxlength'=>'19')); ?>
					</div>
					<div style="float:left;">
					<input type="submit" value="<?php echo JText::_('OS_FILTER');?>" class="btn btn-primary">
					</div>
				</td>
			</tr>
			<?php
			if(count($rows) > 0){
			?>
			<tr>
				<td width="100%" style="padding-top:20px;"  colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="3%" class="osbtdheader">
								#
							</td>
							<td width="15%" class="osbtdheader">
								<?php echo JText::_('OS_SERVICE_NAME');?>
							</td>
							<td width="10%" class="osbtdheader">
								<?php echo JText::_('OS_DATE');?>
							</td>
							<td width="10%" class="osbtdheader hidden-phone">
								<?php echo JText::_('OS_START');?>
							</td>
							<td width="10%" class="osbtdheader hidden-phone">
								<?php echo JText::_('OS_END');?>
							</td>
							<td width="15%" class="osbtdheader hidden-phone">
								<?php echo JText::_('OS_CUSTOMER');?>
							</td>
						</tr>
						<?php
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							if($i % 2 == 0){
								$bgcolor = "#efefef";
							}else{
								$bgcolor = "#fff";
							}
							$config = new JConfig();
							$offset = $config->offset;
							date_default_timezone_set($offset);
							?>
							<tr>
								<td class="td_data" style="background-color:<?php echo $bgcolor?>;">
									<?php echo $i + 1;?>
								</td>
								<td class="td_data" style="background-color:<?php echo $bgcolor?>;">
									<?php echo OSBHelper::getLanguageFieldValue($row,'service_name');?>
								</td>
								<td class="td_data" style="background-color:<?php echo $bgcolor?>;">
									<?php echo date($configClass['date_format'],$row->start_time);?>
								</td>
								<td class="td_data hidden-phone" style="background-color:<?php echo $bgcolor?>;">
									<?php echo date($configClass['time_format'],$row->start_time);?>
								</td>
								<td class="td_data hidden-phone" style="background-color:<?php echo $bgcolor?>;">
									<?php echo date($configClass['time_format'],$row->end_time);?>
								</td>
								<td class="td_data hidden-phone" style="background-color:<?php echo $bgcolor?>;">
									<?php echo $row->order_name;?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
			<?php
			}else{
			?>
			<tr>
				<td width="100%" align="center" style="padding:20px;" colspan="2">
					<strong><?php echo JText::_('OS_NO_WORK');?></strong>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		<?php
		if($configClass['footer_content'] != ""){
			?>
			<div class="osbfootercontent">
				<?php echo $configClass['footer_content'];?>
			</div>
			<?php
		}
		?>
		<input type="hidden" name="option" value="com_osservicesbooking"  />
		<input type="hidden" name="task" value="default_employeeworks" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid')?>" />
		</form>
		<?php
	}
	function showOrderDetailsForm($order,$rows,$checkin){
		global $mainframe,$configClass;
		?>
        <h2>
            <?php echo JText::_('OS_ORDER');?> #<?php echo $order->id?>
        </h2>
		<table width="100%">
            <?php
            if(($configClass['show_details_and_orders'] == 1) and ($checkin == 0)){
                ?>
                <tr>
                    <td width="100%" style="border:1px solid #CCC !important;padding:5px;" colspan="2"
                        class="hidden-phone">
                        <?php echo JText::_('OS_ORDER');?> URL: <a
                            href="<?php echo JURI::root()?>index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=<?php echo $order->id?>&ref=<?php echo md5($order->id);?>"
                            style="font-size:10px;"><?php echo JURI::root()?>
                            index.php?option=com_osservicesbooking&task=default_orderDetailsForm&order_id=<?php echo $order->id?>
                            &ref=<?php echo md5($order->id);?></a>
                        <?php if ($configClass['allow_cancel_request'] == 1) { ?>
                            <BR/>
                            <?php echo JText::_('OS_CANCEL_BOOKING_URL');
                            $cancellink = JURI::root() . "index.php?option=com_osservicesbooking&task=default_cancelorder&id=" . $order->id . "&ref=" . md5($order->id); ?>
                            <a href="<?php echo $cancellink ?>" style="font-size:10px;"><?php echo $cancellink ?></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
			<tr>
				<td width="100%" colspan="2">
					<table  width="100%" id="orderdetailstable">
						<?php
                        if($configClass['use_qrcode'] == 1){
                            ?>
                            <tr>
                                <td width="30%" class="infor_left_col">
                                    <?php echo JText::_('OS_QRCODE')?>
                                </td>
                                <td class="infor_right_col">
                                    <?php
                                    if(!file_exists(JPATH_ROOT.'/media/com_osservicesbooking/qrcodes/'.$order->id.'.png')){
                                        OSBHelper::generateQrcode($order->id);
                                    }
                                    ?>
                                    <img src="<?php echo JUri::root()?>media/com_osservicesbooking/qrcodes/<?php echo $order->id?>.png" />
                                </td>
                            </tr>
                        <?php
                        }
						if($configClass['disable_payments'] == 1){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_PRICE')?>
							</td>
							<td class="infor_right_col">
								<?php
									echo OSBHelper::showMoney($order->order_total,1);
								 ?>
								
							</td>
						</tr>
						<?php
						if($configClass['enable_tax']==1){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_TAX')?>
							</td>
							<td class="infor_right_col">
								<?php
								$tax = round($order->order_total*intval($configClass['tax_payment'])/100);
								echo OSBHelper::showMoney($tax,1);
								 ?>
							</td>
						</tr>
						<?php
						}
						if($order->coupon_id > 0){
						?>
							<tr>
								<td width="30%" class="infor_left_col">
									<?php echo JText::_('OS_DISCOUNT')?>
								</td>
								<td class="infor_right_col">
									<?php
										echo OSBHelper::showMoney($order->order_discount,1);
									 ?>
								</td>
							</tr>
						<?php
						}
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_TOTAL')?>
							</td>
							<td class="infor_right_col">
								<?php
									echo OSBHelper::showMoney($order->order_final_cost,1);
								 ?>
							</td>
						</tr>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_DEPOSIT')?>
							</td>
							<td class="infor_right_col">
								<?php
								$deposit_payment = $configClass['deposit_payment'];
								$deposit_payment = $deposit_payment*$final/100;
								?>
								<?php
									echo OSBHelper::showMoney($order->order_upfront,1);
								 ?>
							</td>
						</tr>
                        <tr>
                                <td width="30%" class="infor_left_col">
                                    <?php echo JText::_('OS_PAYMENT')?>
                                </td>
                                <td class="infor_right_col">
                                    <?php
                                    echo JText::_(os_payments::loadPaymentMethod($order->order_payment)->title);
                                    ?>

                                </td>
                            </tr>
						<?php
						}
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_NAME')?>
							</td>
							<td class="infor_right_col">
								<a href="<?php echo $order->order_email;?>" target="_blank">
									<?php
									echo $order->order_name;
									?>
								</a>
							</td>
						</tr>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_EMAIL')?>
							</td>
							<td class="infor_right_col">
								<a href="<?php echo $order->order_email;?>" target="_blank">
									<?php
									echo $order->order_email;
									?>
								</a>
							</td>
						</tr>
						<?php

						if(($configClass['value_sch_include_phone']) and ($order->order_phone != "")){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_PHONE')?>
							</td>
							<td class="infor_right_col">
								<?php
								if($order->dial_code != ""){
									echo $order->dial_code."-";
								}
								echo $order->order_phone;
								?>
							</td>
						</tr>
						<?php
						}
						if(($configClass['value_sch_include_country']) and ($order->order_country != "")){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_COUNTRY')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo $order->order_country;
								?>
							</td>
						</tr>
						<?php
						}
						if(($configClass['value_sch_include_address']) and ($order->order_address != "")){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_ADDRESS')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo $order->order_address;
								?>
							</td>
						</tr>
						<?php
						}
						if(($configClass['value_sch_include_city']) and ($order->order_city != "")){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_CITY')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo $order->order_city;
								?>
							</td>
						</tr>
						<?php
						}
						if(($configClass['value_sch_include_state']) and ($order->order_state != "")){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_STATE')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo $order->order_state;
								?>
							</td>
						</tr>
						<?php
						}
						if(($configClass['value_sch_include_zip']) and ($order->order_zip != "")){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_ZIP')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo $order->order_zip;
								?>
							</td>
						</tr>
						<?php
						}
						$db = JFactory::getDbo();
						$db->setQuery("Select * from #__app_sch_fields where field_area = '1' and published = '1' order by ordering");
						$fields = $db->loadObjectList();
						if(count($fields) > 0){
							for($i=0;$i<count($fields);$i++){
								$field = $fields[$i];
								$db->setQuery("Select count(id) from #__app_sch_order_options where order_id = '$order->id' and field_id = '$field->id'");
								$count = $db->loadResult();
								if($field->field_type == 0){
									$db->setQuery("Select fvalue from #__app_sch_field_data where order_id = '$order->id' and fid = '$field->id'");
									$fvalue = $db->loadResult();
									if($fvalue != ""){
										?>
										<tr>
											<td width="30%" class="infor_left_col" valign="top" style="padding-top:5px;">
												<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);?>
											</td>
											<td class="infor_right_col">
												<?php
												echo $fvalue;
												?>
											</td>
										</tr>
										<?php
									}
								}
								if($count > 0){
									if($field->field_type == 1){
										$db->setQuery("Select option_id from #__app_sch_order_options where order_id = '$order->id' and field_id = '$field->id'");
										$option_id = $db->loadResult();
										$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
										$optionvalue = $db->loadObject();
										?>
										<tr>
											<td width="30%" class="infor_left_col" valign="top" style="padding-top:5px;">
												<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label');?>
											</td>
											<td class="infor_right_col">
												<?php
												$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang); //$optionvalue->field_option;
												if($optionvalue->additional_price > 0){
													$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
												}
												echo $field_data;
												?>
											</td>
										</tr>
										<?php
									}elseif($field->field_type == 2){
										$db->setQuery("Select option_id from #__app_sch_order_options where order_id = '$order->id' and field_id = '$field->id'");
										$option_ids = $db->loadObjectList();
										$fieldArr = array();
										for($j=0;$j<count($option_ids);$j++){
											$oid = $option_ids[$j];
											$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
											$optionvalue = $db->loadObject();
											$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang);//$optionvalue->field_option;
											if($optionvalue->additional_price > 0){
												$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
											}
											$fieldArr[] = $field_data;
										}
										?>
										<tr>
											<td width="30%" class="infor_left_col" valign="top" style="padding-top:5px;">
												<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);?>
											</td>
											<td class="infor_right_col">
												<?php
												echo implode(", ",$fieldArr);
												?>
											</td>
										</tr>
										<?php
									}
								}
							}
						}
                        if($order->order_notes != ""){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_NOTES')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo $order->order_notes;
								?>
							</td>
						</tr>
                        <?php } ?>
					</table>
				</td>
			</tr>
			<tr>
				<td width="100%" style="border:1px solid #CCC !important;padding:5px;" colspan="2">
					<input type="hidden" name="oid" id="oid" value="<?php echo $order->id;?>">
					<div id="order<?php echo $order->id;?>">
						<?php
						OsAppscheduleDefault::getListOrderServices($order->id,$checkin);
						?>
					</div>
				</td>
			</tr>
		</table>
		<?php
		if($configClass['footer_content'] != ""){
			?>
			<div class="osbfootercontent">
				<?php echo $configClass['footer_content'];?>
			</div>
			<?php
		}
		?>
		<?php
	}
	

	/**
	 * List Services / Employees / Start time / End time / extra fields Orders
	 *
	 * @param unknown_type $rows
	 */
	function listOrderServices($rows,$order,$checkin){
		global $mainframe,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);
		$db = JFactory::getDbo();
		if(count($rows) > 0){
		?>
		<div style="padding:5px;">
		<table width="100%" class="table table-bordered" style="border:1px solid #efefef !important;">
			<tr class="success">
				<?php
                if($checkin == 0) {
                    if ($configClass['allow_cancel_request'] == 1) { ?>
                        <td width="10%" align="left" style="font-size:11px;color:gray;font-weight:bold;" class="hidden-phone">
                            <?php echo JText::_('OS_REMOVE') ?>
                        </td>
                    <?php }
                }else{
                    ?>
                    <td width="10%" align="left" style="font-size:11px;color:gray;font-weight:bold;">
                        <?php echo JText::_('OS_CHECKIN') ?>
                    </td>
                    <?php
                }
                ?>
				<td width="15%" align="left" style="font-size:11px;color:gray;font-weight:bold;">
					<?php echo JText::_('OS_SERVICE_NAME')?>
				</td>
				<td width="15%" align="left" style="font-size:11px;color:gray;font-weight:bold;">
					<?php echo JText::_('OS_EMPLOYEE')?>
				</td>
				<td width="15%" align="left" style="font-size:11px;color:gray;font-weight:bold;">
					<?php echo JText::_('OS_BOOKING_DATE')?>
				</td>
				<td width="15%" align="left" style="font-size:11px;color:gray;font-weight:bold;" class="hidden-phone">
					<?php echo JText::_('OS_START_TIME')?>
				</td>
				<td width="15%" align="left" style="font-size:11px;color:gray;font-weight:bold;" class="hidden-phone">
					<?php echo JText::_('OS_END_TIME')?>
				</td>
				<td width="30%" align="left" style="font-size:11px;color:gray;font-weight:bold;" class="hidden-phone"> 
					<?php echo JText::_('OS_ADDITIONAL')?>
				</td>
			</tr>
			<?php
			for($i1=0;$i1<count($rows);$i1++){
				$row = $rows[$i1];
				?>
				<tr>
					<?php
                    if($checkin == 0) {
                        if ($configClass['allow_cancel_request'] == 1) { ?>
                            <td class="hidden-phone" width="10%" align="left" style="font-size:11px;color:gray;">
                                <a href="javascript:removeOrderItem(<?php echo $row->order_id ?>,<?php echo $row->order_item_id ?>,'<?php echo JText::_('OS_DO_YOU_WANT_T0_REMOVE_ORDER_ITEM') ?>','<?php echo JURI::root() ?>','<?php echo Jrequest::getVar('Itemid', 0); ?>');"
                                   title="<?php echo JText::_('OS_CLICK_HERE_TO_REMOVE_ITEM'); ?>"/>
                                <img
                                    src="<?php echo JURI::root() ?>components/com_osservicesbooking/style/images/icon-16-deny.png"/>
                                </a>
                            </td>
                        <?php }
                    }else{
                        ?>
                        <td width="10%" align="left" style="font-size:11px;color:gray;">
                            <div id="order<?php echo $row->order_item_id;?>">
                                <a href="javascript:changeCheckin(<?php echo $row->order_id ?>,<?php echo $row->order_item_id ?>,'<?php echo JURI::root() ?>');" title="<?php echo JText::_('OS_CLICK_HERE_TO_CHANGE_CHECK_IN_STATUS'); ?>"/>
                                    <?php
                                    if($row->checked_in == 1){
                                    ?>
                                        <img src="<?php echo JURI::root() ?>administrator/components/com_osservicesbooking/asset/images/publish.png"/>
                                    <?php } else{
                                        ?>
                                        <img src="<?php echo JURI::root() ?>components/com_osservicesbooking/style/images/icon-16-deny.png"/>
                                        <?php
                                    }?>
                                </a>
                            </div>
                        </td>
                        <?php
                    }?>
					<td width="15%" align="left" style="font-size:11px;color:gray;">
						<strong><?php echo OSBHelper::getLanguageFieldValueOrder($row,'service_name',$order->order_lang);?></strong>
					</td>
					<td width="15%" align="left" style="font-size:11px;color:gray;">
						<?php
						echo $row->employee_name;
						?>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;">
						<?php
						echo date($configClass['date_format'],$row->start_time);
						?>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;" class="hidden-phone">
						<?php
						echo date($configClass['time_format'],$row->start_time);
						?>
					</td>
					<td width="10%" align="left" style="font-size:11px;color:gray;" class="hidden-phone">
						<?php
						echo date($configClass['time_format'],$row->end_time);
						?>
					</td>
					<td width="30%" align="left" style="font-size:11px;color:gray;" class="hidden-phone">
						<?php
						$db->setQuery("Select a.* from #__app_sch_venues as a inner join #__app_sch_employee_service as b on b.vid = a.id where b.employee_id = '$row->eid' and b.service_id = '$row->sid'");
						$venue = $db->loadObject();
						if($venue->address != ""){
							echo JText::_('OS_VENUE').": <B>".$venue->address."</B>";
						}
						if($row->service_time_type == 1){
							echo JText::_('OS_NUMBER_SLOT').": ".$row->nslots."<BR />";
						}
						$db->setQuery("Select * from #__app_sch_fields where field_area = '0' and published = '1' order by ordering");
						$fields = $db->loadObjectList();
						if(count($fields) > 0){
							for($i=0;$i<count($fields);$i++){
								$field = $fields[$i];
								//echo $field->id;
								$db->setQuery("Select count(id) from #__app_sch_order_field_options where order_item_id = '$row->order_item_id' and field_id = '$field->id'");
								//echo $db->getQuery();
								$count = $db->loadResult();
								if($count > 0){
									if($field->field_type == 1){
										$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$row->order_item_id' and field_id = '$field->id'");
										//echo $db->getQuery();
										$option_id = $db->loadResult();
										$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
										$optionvalue = $db->loadObject();
										?>
										<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);?>:
										<?php
										$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang);
										if($optionvalue->additional_price > 0){
											$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
										}
										echo $field_data;
										echo "<BR />";
									}elseif($field->field_type == 2){
										$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$row->order_item_id' and field_id = '$field->id'");
										$option_ids = $db->loadObjectList();
										$fieldArr = array();
										for($j=0;$j<count($option_ids);$j++){
											$oid = $option_ids[$j];
											$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
											//echo $db->getQuery();
											$optionvalue = $db->loadObject();
											$field_data = OSBHelper::getLanguageFieldValueOrder($optionvalue,'field_option',$order->order_lang);
											if($optionvalue->additional_price > 0){
												$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
											}
											$fieldArr[] = $field_data;
										}
										?>
										<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$order->order_lang);?>:
										<?php
										echo implode(", ",$fieldArr);
										echo "<BR />";
									}
								}
							}
						}
						?>
					</td>
				</tr>
				<?php
			}
			?>
            <input type="hidden" name="order_item_id" id="order_item_id" value="" />
		</table>
		</div>
		<?php
		}else{
			?>
			<div style="padding:5px;">
				<?php  echo JText::_('OS_NO_ITEM');?>
			</div>
			<?php
		}
	}
	
	/**
	 * Show Google map for the venue
	 *
	 * @param unknown_type $venue
	 */
	function showMap($venue){
		global $mainframe,$configClass;
		?>
	    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	    <script>
	      function initialize() {
	        var mapOptions = {
	          zoom: 19,
	          center: new google.maps.LatLng(<?php echo $venue->lat_add?>, <?php echo $venue->long_add?>),
	          mapTypeControl: true,
	          mapTypeControlOptions: {
	            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
	          },
	          zoomControl: true,
	          zoomControlOptions: {
	            style: google.maps.ZoomControlStyle.SMALL
	          },
	          mapTypeId: google.maps.MapTypeId.SATELLITE
	        }
	        var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
	        
	         marker = new google.maps.Marker({
	          map:map,
	          draggable:false,
	          animation: google.maps.Animation.DROP,
	          position: new google.maps.LatLng(<?php echo $venue->lat_add?>, <?php echo $venue->long_add?>)
	        });
	      }
	    </script>
	    <body onload="initialize()">
	    	<div id="map-canvas" style="width:560px;height:370px;"></div>
	    </body>
		<?php
		exit();
	}
	
	function listEmployees($employees,$params,$list_type){
		global $mainframe,$configClass;
		JHTML::_('behavior.modal','a.osmodal');

        if($params->get('show_page_heading') == 1){
            if($params->get('page_heading') != ""){
                ?>
                <div class="page-header">
                    <h1>
                        <?php echo $params->get('page_heading');?>
                    </h1>
                </div>
            <?php
            }else{
                ?>
                <div class="page-header">
                    <h1>
                        <?php echo JText::_('OS_LIST_ALL_EMPLOYEES');?>
                    </h1>
                </div>
            <?php
            }
        }
		if(count($employees) > 0){
            if($list_type == 0) {
                foreach ($employees as $employee) {
                    ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span4">
                                    <div id="ospitem-watermark_box">
                                        <a href="<?php echo JText::_('index.php?option=com_osservicesbooking&task=default_layout&employee_id=' . $employee->id)?>" title="<?php echo JText::_('OS_DETAILS');?>">
                                            <?php
                                                if ($employee->employee_photo != "") {
                                                    ?>
                                                    <img
                                                    src="<?php echo JURI::root()?>images/osservicesbooking/employee/<?php echo $employee->employee_photo?>"/>
                                            <?php
                                            } else {
                                                ?>
                                                <img
                                                    src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/no_image_available.png"/>
                                            <?php
                                            }
                                            ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="span8 ospitem-leftpad">
                                    <div class="ospitem-leftpad">
                                        <div class="row-fluid ospitem-toppad">
                                            <div class="span12">
                                                <span class="ospitem-itemtitle title-blue">
                                                    <a href="<?php echo JText::_('index.php?option=com_osservicesbooking&task=default_layout&employee_id=' . $employee->id)?>"
                                                       title="<?php echo JText::_('OS_DETAILS');?>">
                                                        <?php
                                                        echo $employee->employee_name;
                                                        ?>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row-fluid ospitem-toppad">
                                            <div class="span12">
                                                <span>
                                                    <i class="icon-tag"></i> <?php echo HelperOSappscheduleCommon::getServiceNames($employee->id); ?>
                                                    <?php
                                                    echo '<div class="clearfix"></div>';
                                                    if ($employee->employee_phone != "") {
                                                        echo "<i class='icon-phone'></i>&nbsp;".$employee->employee_phone;
                                                        echo '<div class="clearfix"></div>';
                                                    }
                                                    if ($employee->employee_email != "") {
                                                        echo "<i class='icon-mail'></i>&nbsp;<a href='mailto:" . $employee->employee_email . "'>" . $employee->employee_email . "</a>";
                                                        echo '<div class="clearfix"></div>';
                                                    }
                                                    if ($employee->employee_notes != "") {
                                                        echo $employee->employee_notes;
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            }else{
                ?>
                <div id="mainwrapper" class="row-fluid">
                    <div class="span12">
                    <?php
                    $j = 0;
                    foreach ($employees as $employee){
                        $j++;
                        $link = Jroute::_('index.php?option=com_osservicesbooking&task=default_layout&employee_id='.$employee->id.'&Itemid='.JRequest::getInt('Itemid',0));
                        ?>
                        <div class="span4 information_box">
                            <div class="information_box_img">
                                <a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_DETAILS');?>">
                                    <?php
                                    if ($employee->employee_photo != "") {
                                        ?>
                                        <img src="<?php echo JURI::root()?>images/osservicesbooking/employee/<?php echo $employee->employee_photo?>"/>
                                    <?php
                                    } else {
                                        ?>
                                        <img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/no_image_available.png"/>
                                    <?php
                                    }
                                    ?>
                                </a>
                            </div>
                            <span class="full-caption">
                                <h3><a href="<?php echo $link; ?>" title="<?php echo JText::_('OS_DETAILS');?>"><?php echo $employee->employee_name;?></a></h3>
                                <div class="full-desc">
                                    <i class="icon-tag"></i> <?php echo HelperOSappscheduleCommon::getServiceNames($employee->id); ?>
                                    <?php
                                    echo '<div class="clearfix"></div>';
                                    if ($employee->employee_phone != "") {
                                        echo "<i class='icon-phone'></i>&nbsp;".$employee->employee_phone;
                                        echo '<div class="clearfix"></div>';
                                    }
                                    if ($employee->employee_email != "") {
                                        echo "<i class='icon-mail'></i>&nbsp;<a href='mailto:" . $employee->employee_email . "'>" . $employee->employee_email . "</a>";
                                        echo '<div class="clearfix"></div>';
                                    }
                                    if ($employee->employee_notes != "") {
                                        echo $employee->employee_notes;
                                    }
                                    ?>
                                </div>
                            </span>
                        </div>
                        <?php
                        if($j == 3){
                            $j = 0;
                            ?>
                            </div></div><div class="row-fluid"><div class="span12">
                        <?php
                        }
                    }
                    ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            <?php
            }
		}else{
			?>
			<div class="row-fluid">
				<div class="span12" style="text-align:center;padding:10px;">
					<strong>
						<?php
							echo JText::_('OS_NO_EMPLOYEES');
						?>
					</strong>
				</div>
			</div>
			<?php
		}
	}
}
?>