<?php
/*------------------------------------------------------------------------
# booking.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OsAppscheduleForm{
	/**
	 * Confirm information form
	 *
	 * @param unknown_type $option
	 * @param unknown_type $total
	 * @param unknown_type $fieldObj
	 * @param unknown_type $lists
	 */
	function confirmInforFormHTML($total,$fieldObj,$lists,$coupon){
		global $mainframe,$configClass,$deviceType;
		//jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$methods = os_payments::getPaymentMethods(true, false) ;
		?>
		<div class="row-fluid">
			<div class="span12">
				<?php if(($configClass['using_cart'] == 1) or !OSBHelper::isTheSameDate($lists['date_from'],$lists['date_to'])){
				$secondDiv = "span8";
				?>
				<div class="span4" id="calendardivleft">
					<?php if((!OSBHelper::isTheSameDate($lists['date_from'],$lists['date_to'])) and ($configClass['show_calendar_box'] == 1)){?>
					<div class="row-fluid">
						<div class="span12">
							<?php
								HelperOSappscheduleCalendar::initCalendarForSeveralYear(intval(date("Y",HelperOSappscheduleCommon::getRealTime())),$lists['category'],$lists['employee_id'],$lists['vid'],$lists['date_from'],$lists['date_to']);
								?>
								<input type="hidden" name="ossmh" id="ossmh" value="<?php echo intval(date("m",$lists['current_time']))?>">
								<input type="hidden" name="ossyh" id="ossyh" value="<?php echo intval(date("Y",$lists['current_time']))?>">
						</div>
					</div>
					<div class="clearfix" style="height:10px;"></div>
					<?php }
					if(($configClass['using_cart'] == 1) and ($deviceType != "mobile")){
					?>
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
												OsAppscheduleAjax::cart($userdata,$lists['vid'],$lists['category'],$lists['employee_id'],$lists['date_from'],$lists['date_to']);
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
						HTML_OsAppscheduleForm::showConfirmFormHTML($total,$fieldObj,$lists,$coupon);
						?>
					</div>
					<div  style="display:none;">
						<?php
						echo JHTML::_('calendar','', 'calendarvl', 'calendarvl', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19','style'=>'width:80px;'));
						?>
					</div>
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
		<input type="hidden" name="option" value="com_osservicesbooking" /> 
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="month"  id="month" value="<?php echo intval(date("m",$lists['current_time']))?>" />
		<input type="hidden" name="year"  id="year" value="<?php echo date("Y",$lists['current_time'])?>" />
		<input type="hidden" name="day"  id="day" value="<?php echo intval(date("d",$lists['current_time']));?>" />
		<input type="hidden" name="select_day" id="select_day" value="<?php echo $day;?>" />
		<input type="hidden" name="select_month" id="select_month" value="<?php echo $month;?>" />
		<input type="hidden" name="select_year" id="select_year" value="<?php echo $year;?>" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>"  />
		<input type="hidden" name="order_id" id="order_id" value="" />
		<input type="hidden" name="current_date" id="current_date" value=""  />
		<input type="hidden" name="use_captcha" id="use_captcha" value="<?php echo $configClass['value_sch_include_captcha'];?>" />
		<input type="hidden" name="category_id" id="category_id" value="<?php echo JRequest::getVar('category_id',0)?>" />
		<input type="hidden" name="employee_id" id="employee_id" value="<?php echo JRequest::getVar('employee_id',0)?>" />
		<input type="hidden" name="vid" id="vid" value="<?php echo JRequest::getVar('vid',0)?>" />
		<input type="hidden" name="selected_item" id="selected_item" value="" />
		<input type="hidden" name="sid" id="sid" value="<?php echo JRequest::getInt('sid',0);?>" />
		<input type="hidden" name="eid" id="eid" value="" />
		<input type="hidden" name="coupon_id" id="coupon_id" value="" />
		<input type="hidden" name="current_link" id="current_link" value="<?php echo $configClass['current_link']?>" />
		<input type="hidden" name="calendar_normal_style" id="calendar_normal_style" value="<?php echo $configClass['calendar_normal_style'];?>" />
		<input type="hidden" name="calendar_currentdate_style" id="calendar_currentdate_style" value="<?php echo $configClass['calendar_currentdate_style'];?>" />
		<input type="hidden" name="calendar_activate_style" id="calendar_activate_style" value="<?php echo $configClass['calendar_activate_style'];?>" />
		<input type="hidden" name="use_js_popup" id="use_js_popup" value="<?php echo $configClass['use_js_popup'];?>" />
		<input type="hidden" name="using_cart" id="using_cart" value="<?php echo $configClass['using_cart'];?>" />
		<input type="hidden" name="date_from" id="date_from" value="<?php echo $lists['date_from'];?>" />
		<input type="hidden" name="date_to" id="date_to" value="<?php echo $lists['date_to'];?>" />
		<input type="hidden" name="temp_item" id="temp_item" value="" />
		<input type="hidden" name="Itemid" id="Itemid" value="<?php echo Jrequest::getInt('Itemid',0);?>" />
		<input type="hidden" name="count_services" id="count_services" value="" />
		<div  id="divtemp" style="width:1px;height:1px;"></div>
		<script language="javascript">
		<?php
			os_payments::writeJavascriptObjects();
		?>
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
		</script>
		<?php
		
	}
	
	
	function showConfirmFormHTML($total,$fieldObj,$lists,$coupon){
		global $mainframe,$configClass;
		$user = JFactory::getUser();
		
		$year = intval(date("Y",HelperOSappscheduleCommon::getRealTime()));
		$month = intval(date("m",HelperOSappscheduleCommon::getRealTime()));
		$day = intval(date("d",HelperOSappscheduleCommon::getRealTime()));
		$date_from = $lists['date_from'];
		if($date_from != ""){
			$date_from_array = explode(" ",$date_from);
			$date_from_int = strtotime($date_from_array[0]);
			if($date_from_int > HelperOSappscheduleCommon::getRealTime()){
				$year = date("Y",$date_from_int);
				$month = intval(date("m",$date_from_int));
				$day = intval(date("d",$date_from_int));
			}
		}
		
		$methods = $lists['methods'];
		?>
		<?php
		if($configClass['use_ssl'] == 1){
		?>
		<form method="POST" action="<?php echo $configClass['root_link'].'index.php?option=com_osservicesbooking';?>" name="appform" id="bookingForm">
		<?php
		}else{
		?>
		<form method="POST" action="<?php echo JURI::root().'index.php?option=com_osservicesbooking';?>" name="appform" id="bookingForm">
		<?php
		}
		?>
		<div class="row-fluid bookingformdiv">
			<div class="span12 <?php echo $configClass['header_style']?>">
				<?php echo JText::_('OS_BOOKING_FORM')?>
				<?php
				if($configClass['show_calendar_box'] == 1){
                    $back_link = JRoute::_("index.php?option=com_osservicesbooking&task=default_layout&category_id=".Jrequest::getInt('category_id',0)."&employee_id=".Jrequest::getInt('employee_id',0)."&vid=".Jrequest::getInt('vid',0)."&sid=".Jrequest::getInt('sid',0)."&date_from=".Jrequest::getVar('date_from','')."&date_to=".Jrequest::getVar('date_to',''));
				?>
					<div style="float:right;padding-right:10px;">
						<a href="<?php echo $back_link;?>">
							<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/close.png" border="0"  />
						</a>
					</div>
				<?php } ?>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="clearfix"></div>
					<?php
					if($configClass['disable_payments'] == 1){
						if($total > 0){
						?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_PRICE')?>
						</div>
						<div class="span8">
							<?php
								echo OSBHelper::showMoney($total,1);
							 ?>
						</div>
						<div class="clearfix"></div>
						<?php
						//}
						if($configClass['enable_tax']==1){
							
						?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_TAX')?>
						</div>
						<div class="span8">
							<?php
							$tax = $total*intval($configClass['tax_payment'])/100;
							?>
							<?php
								echo OSBHelper::showMoney($tax,1);
							 ?>
						</div>
						<div class="clearfix"></div>
						<?php
						}
						?>
						<?php
						$final = $total + $tax;
						
						if($coupon->id > 0){
						?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_DISCOUNT')?>
						</div>
						<div class="span8">
							<?php
								if($coupon->discount_type == 0){
									$discount_amount = $final*$coupon->discount/100;
								}else{
									$discount_amount = $coupon->discount;
									if($discount_amount > $final){
										$discount_amount = $final;
									}
								}
								echo OSBHelper::showMoney($discount_amount,1);
							 ?>
						</div>
						<div class="clearfix"></div>
						<?php
						}
						?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_TOTAL')?>
						</div>
						<div class="span8">
							<?php
							if($coupon->id > 0){
								if($coupon->discount_type == 0){
									$final -= $discount_amount;
								}else{
									$final -= $discount_amount;
									if($final < 0){
										$final = 0;
									}
								}
							}
							?>
							<?php
								echo OSBHelper::showMoney($final,1);
							 ?>
						</div>
						<div class="clearfix"></div>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_DEPOSIT')?>
						</div>
						<div class="span8">
							<?php
							$deposit_payment = $configClass['deposit_payment'];
							$deposit_payment = $deposit_payment*$final/100;
							?>
							<?php
								echo OSBHelper::showMoney($deposit_payment,1);
							 ?>
						</div>
						<div class="clearfix"></div>
					<?php
						}
					}
					?>
					<div class="span3 boldtext">
						<?php echo JText::_('OS_NAME')?>
					</div>
					<div class="span8">
						<?php
						echo JRequest::getVar('order_name','');
						?>
					</div>
					<div class="clearfix"></div>
					<div class="span3 boldtext">
						<?php echo JText::_('OS_EMAIL')?>
					</div>
					<div class="span8">
						<?php
						echo JRequest::getVar('order_email','');
						?>
					</div>
					<div class="clearfix"></div>
					<?php
					
					if($configClass['value_sch_include_phone']){
						if(JRequest::getVar('order_phone','')!= ""){
					?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_PHONE')?>
						</div>
						<div class="span8">
							<?php
							$dial_code = JRequest::getVar('dial_code','');
							if($dial_code != ""){
								echo $dial_code."-";
							}
							echo JRequest::getVar('order_phone','');
							?>
						</div>
						<div class="clearfix"></div>
					<?php
						}
					}
					if($configClass['value_sch_include_country']){
						if(JRequest::getVar('order_country','')!= ""){
					?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_COUNTRY')?>
						</div>
						<div class="span8">
							<?php
							echo JRequest::getVar('order_country','');
							?>
						</div>
						<div class="clearfix"></div>
					<?php
						}
					}
					if($configClass['value_sch_include_address']){
						if(JRequest::getVar('order_address','')!= ""){
					?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_ADDRESS')?>
						</div>
						<div class="span8">
							<?php
							echo JRequest::getVar('order_address','');
							?>
						</div>
						<div class="clearfix"></div>
					<?php
						}
					}
					if($configClass['value_sch_include_city']){
						if(JRequest::getVar('order_city','')!= ""){
					?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_CITY')?>
						</div>
						<div class="span8">
							<?php
							echo JRequest::getVar('order_city','');
							?>
						</div>
						<div class="clearfix"></div>
					<?php
						}
					}
					if($configClass['value_sch_include_state']){
						if(JRequest::getVar('order_state','')!= ""){
					?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_STATE')?>
						</div>
						<div class="span8">
							<?php
							echo JRequest::getVar('order_state','');
							?>
						</div>
						<div class="clearfix"></div>
					<?php
						}
					}
					if($configClass['value_sch_include_zip']){
						if(JRequest::getVar('order_zip','')!= ""){
					?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_ZIP')?>
						</div>
						<div class="span8">
							<?php
							echo JRequest::getVar('order_zip','');
							?>
						</div>
						<div class="clearfix"></div>
					<?php
						}
					}
					
					if(count($fieldObj) > 0){
						for($i=0;$i<count($fieldObj);$i++){
							$f = $fieldObj[$i];
							if($f->fvalue != ""){
							?>
							<div class="span3 boldtext">
								<?php echo OSBHelper::getLanguageFieldValue($f->field,'field_label');?>
							</div>
							<div class="span8">
								<?php
								echo $f->fvalue;
								?>
							</div>
							<div class="clearfix"></div>
							<?php
							}
						}
					}
					?>
					<?php
					$note = JRequest::getVar('notes');
					if($note != ""){
					$note = str_replace("(@)","&",$note);
					?>
					<div class="span3 boldtext">
						<?php echo JText::_('OS_NOTES');?>
					</div>
					<div class="span8">
							<?php
							echo nl2br($note);
							?>
					</div>
					<div class="clearfix"></div>
					<?php
					}
					if($configClass['disable_payments'] == 1){
						$method = $lists['method'];
						?>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_SELECT_PAYMENT')?>
						</div>
						<div class="span8">
							<?php echo  JText::_(os_payments::loadPaymentMethod($lists['select_payment'])->title); ?>
						</div>
						<div class="clearfix"></div>
						<?php
					}
					$method = $lists['method'] ;
					if($lists['select_payment'] != ""){
						if ($method->getCreditCard()) {
						?>	
							<div class="span3 boldtext">
								<?php echo  JText::_('OS_AUTH_CARD_NUMBER'); ?>
							</div>
							<div class="span8">
								<?php
									$len = strlen($lists['x_card_num']) ;
									$remaining =  substr($lists['x_card_num'], $len - 4 , 4) ;
									echo str_pad($remaining, $len, '*', STR_PAD_LEFT) ;
								?>												
							</div>
							<div class="clearfix"></div>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_AUTH_CARD_EXPIRY_DATE'); ?>
							</div>
							<div class="span8">						
								<?php echo $lists['exp_month'] .'/'.$lists['exp_year'] ; ?>
							</div>
							<div class="clearfix"></div>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_AUTH_CVV_CODE'); ?>
							</div>
							<div class="span8">
								<?php echo $lists['x_card_code'] ; ?>
							</div>
							<div class="clearfix"></div>
							<?php
								if ($method->getCardType()){
								?>
									<div class="span3 boldtext">
										<?php echo JText::_('OS_CARD_TYPE'); ?>
									</div>
									<div class="span8">
										<?php echo $lists['card_type'] ; ?>
									</div>
								<div class="clearfix"></div>
								<?php	
								}
							?>
						<?php				
						}						
						if ($method->getCardHolderName()) {
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_CARD_HOLDER_NAME'); ?>
							</div>
								<div class="span8">
								<?php echo $lists['card_holder_name'];?>
							</div>
							<div class="clearfix"></div>
						<?php												
						}
					}
					if(OsAppscheduleAjax::isAnyItemsInCart()){
					?>
					<div class="span12">
						<input type="button" id="confirmSubmit" class="btn btn-success" value="<?php echo JText::_('OS_CONFIRM')?>">
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<!-- hidden tags -->
		<input type="hidden" name="order_name" 			id="order_name" 		value="<?php echo JRequest::getVar('order_name')?>"   />
		<input type="hidden" name="order_email" 		id="order_email" 		value="<?php echo JRequest::getVar('order_email')?>" />
		<input type="hidden" name="dial_code" 			id="dial_code" 			value="<?php echo JRequest::getVar('dial_code')?>" />
		<input type="hidden" name="order_phone" 		id="order_phone" 		value="<?php echo JRequest::getVar('order_phone')?>" />
		<input type="hidden" name="order_country" 		id="order_country" 		value="<?php echo JRequest::getVar('order_country')?>" />
		<input type="hidden" name="order_address" 		id="order_address" 		value="<?php echo JRequest::getVar('order_address')?>" />
		<input type="hidden" name="order_state" 		id="order_state" 		value="<?php echo JRequest::getVar('order_state')?>" />
		<input type="hidden" name="order_city" 			id="order_city" 		value="<?php echo JRequest::getVar('order_city')?>"  />
		<input type="hidden" name="order_zip" 			id="order_zip" 			value="<?php echo JRequest::getVar('order_zip')?>" />
		<input type="hidden" name="select_payment" 		id="select_payment" 	value="<?php echo JRequest::getVar('payment_method')?>" />
		<input type="hidden" name="x_card_num" 			id="x_card_num" 		value="<?php echo $lists['x_card_num']?>" />
		<input type="hidden" name="x_card_code" 		id="x_card_code" 		value="<?php echo $lists['x_card_code']?>"  />
		<input type="hidden" name="card_holder_name" 	id="card_holder_name" 	value="<?php echo $lists['card_holder_name']?>" />
		<input type="hidden" name="exp_year" 			id="exp_year" 			value="<?php echo $lists['exp_year']?>" />
		<input type="hidden" name="exp_month" 			id="exp_month" 			value="<?php echo $lists['exp_month']?>" />
		<input type="hidden" name="card_type" 			id="card_type" 			value="<?php echo $lists['card_type']?>" />
		<input type="hidden" name="bank_id" 			id="bank_id" 			value="<?php echo Jrequest::getVar('bank_id');?>" />
		<input type="hidden" name="coupon_id"			id="coupon_id" 			value="<?php echo $coupon->id?>" />
		<input type="hidden" name="unique_cookie"		id="unique_cookie" 		value="<?php echo $_COOKIE['unique_cookie']?>" />
		<input type="hidden" name="use_js_popup" 		id="use_js_popup" 		value="<?php echo $configClass['use_js_popup'];?>" />
		<input type="hidden" name="using_cart" 			id="using_cart" 		value="<?php echo $configClass['using_cart'];?>" />
		<input type="hidden" name="date_from" 			id="date_from" 			value="<?php echo $date_from?>" />
		<input type="hidden" name="date_to" 			id="date_to" 			value="<?php echo $date_to?>" />
        <input type="hidden" name="temp_item" id="temp_item" value="" />
		<input type="hidden" name="Itemid" 				id="Itemid" 			value="<?php echo Jrequest::getInt('Itemid',0);?>" />
		<div style="display:none;">
			<textarea name="notes" id="notes" cols="40" rows="4" class="inputbox"><?php echo $note?></textarea>
		</div>
		<?php
		if(count($fieldObj) > 0){
			for($i=0;$i<count($fieldObj);$i++){
				$f = $fieldObj[$i];
				?>
				<input type="hidden" name="field_<?php echo $f->field->id?>" id="field_<?php echo $f->field->id?>" value="<?php echo $f->fieldoptions?>">
				<?php
			}
		}
		?>
		<input type="hidden" name="option" value="com_osservicesbooking" />
		<input type="hidden" name="task" value="default_completeorder" />
		<input type="hidden" name="Itemid" value="<?php echo Jrequest::getVar('Itemid')?>"  />
		</form>
		<script language="javascript">
		jQuery("#confirmSubmit").click(function()
        {
			jQuery("#confirmSubmit").attr('disabled','disabled');
            jQuery("#confirmSubmit").prop('disabled',true);
			document.getElementById("bookingForm").submit();
        });
		function addtoCart(sid,eid,time_length){
			var form			= document.appform;
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
					addtoCartAjax(bookitem.value,end_bookitem,sid,eid,live_site.value,str,repeat);
				}	
			}
		}
		</script>
		<?php
	}
	/**
	 * Show Checkout form - Step1
	 *
	 * @param unknown_type $option
	 * @param unknown_type $lists
	 * @param unknown_type $fields
	 */
	function showInforFormHTML($lists,$fields,$profile){
		global $mainframe,$configClass,$deviceType;
		$year = intval(date("Y",HelperOSappscheduleCommon::getRealTime()));
		$month = intval(date("m",HelperOSappscheduleCommon::getRealTime()));
		$day = intval(date("d",HelperOSappscheduleCommon::getRealTime()));
		$date_from = $lists['date_from'];
		if($date_from != ""){
			$date_from_array = explode(" ",$date_from);
			$date_from_int = strtotime($date_from_array[0]);
			if($date_from_int > HelperOSappscheduleCommon::getRealTime()){
				$year = date("Y",$date_from_int);
				$month = intval(date("m",$date_from_int));
				$day = intval(date("d",$date_from_int));
			}
		}
		
		JHTML::_("behavior.modal","a.osmodal");
		$user  = JFactory::getUser();
		$name = "";
		$email = "";
		$show_booking_form = 1;
		if($user->id > 0){
			$name  = ($profile->order_name != "" ? $profile->order_name : $user->name);
			$email = ($profile->order_email != "" ? $profile->order_email : $user->email);
		}else{
			//check the option "allow_regitered_only"
			if($configClass['allow_registered_only'] == 1){
				$show_booking_form = 0;
			}
		}
		$methods = $lists['methods'];
		?>
		<div class="row-fluid bookingformdiv">
			<div class="span12 <?php echo $configClass['header_style']?>">
				<?php echo JText::_('OS_BOOKING_FORM')?>
				<div style="float:right;padding-right:10px;">
                    <?php
                    $back_link = JRoute::_("index.php?option=com_osservicesbooking&task=default_layout&category_id=".Jrequest::getInt('category_id',0)."&employee_id=".Jrequest::getInt('employee_id',0)."&vid=".Jrequest::getInt('vid',0)."&sid=".Jrequest::getInt('sid',0)."&date_from=".Jrequest::getVar('date_from','')."&date_to=".Jrequest::getVar('date_to',''));
                    ?>
                    <a href="<?php echo $back_link;?>">
					<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/close.png" border="0"  />
				</a>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12" style="padding-top:15px;">
					<?php
					if($user->id == 0){ //show login form and registration form
						if($configClass['allow_registered_only'] == 1){
							$actionUrl = JRoute::_('index.php');	
							
							if($configClass['use_ssl'] == 1){
								$returnUrl = JRoute::_($configClass['root_link'].'index.php?option=com_osservicesbooking&task=form_step1&category_id='.JRequest::getInt('category_id',0).'&employee_id='.JRequest::getInt('employee_id',0).'&vid='.JRequest::getInt('vid',0).'&sid='.JRequest::getInt('sid',0).'&Itemid='.Jrequest::getInt('Itemid')."&date_from=".$lists['date_from']."&date_to=".$lists['date_to']);
							}else{
								$returnUrl = JRoute::_(JURI::root().'index.php?option=com_osservicesbooking&task=form_step1&category_id='.JRequest::getInt('category_id',0).'&employee_id='.JRequest::getInt('employee_id',0).'&vid='.JRequest::getInt('vid',0).'&sid='.JRequest::getInt('sid',0).'&Itemid='.Jrequest::getInt('Itemid')."&date_from=".$lists['date_from']."&date_to=".$lists['date_to']);	
							}
						?>
						<!-- Login form-->
						<form id="osbloginForm" name="osbloginForm" method="POST" action="<?php echo $actionUrl;?>">
						<div class="row-fluid">
							<div class="span12 boldtext">
								<strong>
								<?php echo  JText::_('OS_EXISTING_USERS_LOGIN');?>
								</strong>
							</div>
							<div class="clearfix"></div>
							<div class="span3 boldtext">
									<?php echo JText::_('OS_USERNAME')?>
							</div>
							<div class="span8">
								<input type="text" class="input-large" size="20" name="username" id="username" value="" />
							</div>
							<div class="clearfix"></div>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_PASSWORD')?>
							</div>
							<div class="span8">
								<input type="password" class="input-large" size="20" name="password" id="password" value="" />
							</div>
							<div class="clearfix"></div>
							<div class="span12">
									<input type="submit" value="<?php echo JText::_('OS_LOGIN')?>" class="btn btn-success" onclick="javascript:checkLoginForm()" />
							</div>
						</div>
						<input type="hidden" name="remember" value="0" />
						<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0);?>" />
						<input type="hidden" name="option" value="com_users" />
						<input type="hidden" name="task" value="user.login" />
						<input type="hidden" name="return" value="<?php echo base64_encode($returnUrl) ; ?>" />
						<?php echo JHTML::_( 'form.token' ); ?>	
						</form>
						<?php
						}
					}
					if($user->id == 0){
						if(($configClass['allow_registered_only']==1) and ($configClass['allow_registration'] == 1)){
							?>
							<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osservicesbooking');?>" name="osregisterForm" id="osregisterForm">
							<div class="row-fluid">
								<div class="span12 boldtext">
									<strong>
									<?php echo  JText::_('OS_NEW_USER_REGISTER');?>
									</strong>
								</div>
								<div class="span3 boldtext">
									<?php echo JText::_('OS_USERNAME')?>
								</div>
								<div class="span8">
									<input type="text" class="input-medium" size="20" name="username" id="username" value="" />
								</div>
								<div class="clearfix"></div>
								
								<div class="span3 boldtext">
									<?php echo JText::_('OS_PASSWORD')?>
								</div>
								<div class="span8">
									<input type="password" class="input-medium" size="20" name="password1" id="password1" value="" />
								</div>
								<div class="clearfix"></div>
								<div class="span3 boldtext">
									<?php echo JText::_('OS_REPASSWORD')?>
								</div>
								<div class="span8">
									<input type="password" class="input-medium" size="20" name="password2" id="password2" value="" />
								</div>
								<div class="clearfix"></div>
								<div class="span3 boldtext">
									<?php echo JText::_('OS_NAME')?>
								</div>
								<div class="span8">
									<input type="text" class="input-large" size="20" name="order_name" id="order_name" value="<?php echo $name?>" />
								</div>
								<div class="clearfix"></div>
								<div class="span3 boldtext">
									<?php echo JText::_('OS_EMAIL')?>
								</div>
								<div class="span8">
									<input type="text" class="input-medium" value="<?php echo $email?>" size="20" name="order_email" id="order_email" />
								</div>
								<div class="clearfix"></div>
								<div class="span12 boldtext">
									<input type="button" value="<?php echo JText::_('OS_REGISTER')?>" class="btn btn-warning" onclick="javascript:submitRegisterForm();" />
								</div>
							</div>
							<input type="hidden" name="option" value="com_osservicesbooking" />
							<input type="hidden" name="task" value="form_register" />
							<input type="hidden" name="Itemid" value="<?php echo Jrequest::getVar('Itemid')?>" />
							<input type="hidden" name="category_id" id="category_id" value="<?php echo Jrequest::getVar('category_id',0)?>" />
							<input type="hidden" name="employee_id" id="employee_id" value="<?php echo Jrequest::getVar('employee_id',0)?>" />
							<input type="hidden" name="vid" id="vid" value="<?php echo Jrequest::getVar('vid',0)?>" />
							</form>
							<script language="javascript">
							function submitRegisterForm(){
								var form = document.osregisterForm
								if (form.username.value == "") {
									alert("<?php echo JText::_('OS_ENTER_USERNAME'); ?>");
									form.username.focus();
									return ;
								}
								if (form.password1.value == "") {
									alert("<?php echo JText::_('OS_ENTER_PASSWORD'); ?>");
									form.password1.focus();
									return ;
								}
								if (form.password2.value != form.password1.value) {
									alert("<?php echo JText::_('OS_PASSWORD_DOES_NOT_MATCH'); ?>");
									form.password1.focus();
									return ;
								}
								if(form.order_email.value == ""){
									alert("<?php echo JText::_('OS_ENTER_EMAIL'); ?>");
									form.order_email.focus();
									return ;
								}
								if(form.order_name.value == ""){
									alert("<?php echo JText::_('OS_ENTER_NAME'); ?>");
									form.order_name.focus();
									return ;
								}
								
								form.submit();
							}
							</script>
							<?php
						}
					}

					if(($configClass['allow_registered_only']==0) or ($user->id > 0)) {
					?>
					<?php
					if($configClass['use_ssl'] == 1){
					?>
						<form method="POST" action="<?php echo JRoute::_($configClass['root_link'].'index.php?option=com_osservicesbooking&task=form_step2&vid='.JRequest::getInt('vid',0).'&sid='.JRequest::getInt('sid',0).'&category_id='.JRequest::getInt('category_id',0).'&employee_id='.JRequest::getInt('employee_id',0).'&date_from='.Jrequest::getVar('date_from','').'&date_to='.Jrequest::getVar('date_to',''));?>" name="appform" id="appform">
					<?php
					}else{
					?>
						<form method="POST" action="<?php echo JRoute::_('index.php?option=com_osservicesbooking&task=form_step2&vid='.JRequest::getInt('vid',0).'&category_id='.JRequest::getInt('category_id',0).'&sid='.JRequest::getInt('sid',0).'&employee_id='.JRequest::getInt('employee_id',0).'&date_from='.Jrequest::getVar('date_from','').'&date_to='.Jrequest::getVar('date_to',''));?>" name="appform" id="bookingForm">
					<?php
					}
					?>
					<div class="row-fluid">
						<div class="clearfix"></div>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_NAME')." (*)";?>
						</div>
						<div class="span8">
							<input type="text" class="input-large" size="20" name="order_name" id="order_name" value="<?php echo $name?>" />
						</div>
						<div class="clearfix"></div>
						<div class="span3 boldtext">
							<?php echo JText::_('OS_EMAIL')." (*)";?>
						</div>
						<div class="span8">
							<input type="text" class="input-large" value="<?php echo $email?>" size="20" name="order_email" id="order_email" />
						</div>
						<?php
						if($configClass['value_sch_include_phone']){
						?>
						
							<div class="span3 boldtext">
								<?php echo JText::_('OS_PHONE')?>
								<?php
								if($configClass['value_sch_include_phone'] == 2){
									echo "(*)";
								}
								?>
							</div>
							<div class="span8">
								<?php
								if($configClass['clickatell_showcodelist'] == 1){
								?>
								<?php echo $lists['dial']?>
								<?php
								}
								?>
								
								<input type="text" class="input-small" value="<?php echo $profile->order_phone;?>" size="10" name="order_phone" id="order_phone" />
								<input type="hidden" value="<?php echo $configClass['value_sch_include_phone'];?>" name="order_phone_required" id="order_phone_required" />
							</div>
							<div class="clearfix"></div>
						<?php
						}
						if($configClass['value_sch_include_country']){
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_COUNTRY')?>
							</div>
							<div class="span8">
								<?php echo $lists['country'];?>
							</div>
							<div class="clearfix"></div>
						<?php
						}
						if($configClass['value_sch_include_address']){
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_ADDRESS')?>
								<?php
								if($configClass['value_sch_include_address'] == 2){
									echo "(*)";
								}
								?>
							</div>
							<div class="span8">
								<input type="text" class="input-large" value="<?php echo $profile->order_address;?>" size="20" name="order_address" id="order_address" />
								<input type="hidden" value="<?php echo $configClass['value_sch_include_address'];?>" name="order_address_required" id="order_address_required" />
							</div>
							<div class="clearfix"></div>
						<?php
						}
						if($configClass['value_sch_include_city']){
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_CITY')?>
							</div>
							<div class="span8">
								<input type="text" class="input-small" value="<?php echo $profile->order_city;?>" size="20" name="order_city" id="order_city" />
							</div>
							<div class="clearfix"></div>
						<?php
						}
						if($configClass['value_sch_include_state']){
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_STATE')?>
							</div>
							<div class="span8">
									<input type="text" class="input-small" value="<?php echo $profile->order_state;?>" size="10" name="order_state" id="order_state" />
							</div>
							<div class="clearfix"></div>
						<?php
						}
						if($configClass['value_sch_include_zip']){
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_ZIP')?>
							</div>
							<div class="span8">
								<input type="text" class="input-small" value="<?php echo $profile->order_zip;?>" size="10" name="order_zip" id="order_zip" />
							</div>
							<div class="clearfix"></div>
						<?php
						}
						?>
						<?php
						$fieldArr = array();
						$commercial_ids = array();
						for($i=0;$i<count($fields);$i++){
							$field = $fields[$i];
							$fieldArr[] = $field->id;
							$commercial_ids[] = OSBHelper::checkCommercialOptions($field);
							?>
							<div class="span3 boldtext">
								<?php echo OSBHelper::getLanguageFieldValue($field,'field_label');?>
								<?php
								if($field->required == 1){
									echo " (*)";
								}
								?>
							</div>
							<div class="span8">
								<?php
								OsAppscheduleDefault::orderField($field,0);
								?>
							</div>
							<div class="clearfix"></div>
							<?php
						}
						?>
						<input type="hidden" name="commercial_ids" id="commercial_ids" value="<?php echo implode(",",$commercial_ids)?>" />
						<div class="span3 boldtext">
							<?php echo JText::_('OS_NOTES')?>
						</div>
						<div class="span8">
							<textarea name="notes" id="notes" cols="40" rows="4" class="inputbox"></textarea>
						</div>
						<div class="clearfix"></div>
						<?php
						if(OSBHelper::checkCouponAvailable()){
							?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_COUPON_CODE');?>
							</div>
							<div class="span8" id="couponcodediv">
								<input type="text" class="input-small search-query" value="" size="10" name="coupon_code" id="coupon_code" />
								<input type="button" class="btn" value="<?php echo JText::_('OS_CHECK_COUPON');?>" onclick="javascript:checkCoupon();"/>
							</div>
							<?php
						}
						?>
						<div class="clearfix"></div>
						
						<input type="hidden" name="field_ids" id="field_ids" value="<?php echo implode(",",$fieldArr)?>"  />
						<input type="hidden" name="nmethods" id="nmethods" value="<?php echo count($methods)?>" />
						<?php
						if($configClass['disable_payments'] == 1){
							if(count($methods) > 0){
							?>
								<div class="span3 boldtext">
									<?php echo JText::_('OS_PAYMENT_OPTION'); ?>
									<span class="required">*</span>						
								</div>
								<div class="span8">
									<?php
										$method = null ;
										for ($i = 0 , $n = count($methods); $i < $n; $i++) {
											$paymentMethod = $methods[$i];
											if ($paymentMethod->getName() == $lists['paymentMethod']) {
												$checked = ' checked="checked" ';
												$method = $paymentMethod ;
											}										
											else 
												$checked = '';	
										?>
											<input onclick="changePaymentMethod();" type="radio" name="payment_method" id="pmt<?php echo $i?>" value="<?php echo $paymentMethod->getName(); ?>" <?php echo $checked; ?> /><label for="pmt<?php echo $i?>" class="payment_plugin_label"><?php echo JText::_($paymentMethod->title) ; ?></label> <br />
										<?php		
										}	
									?>
								</div>
								<div class="clearfix"></div>				
							<?php					
							} else {
								$method = $methods[0] ;
							}
                            //print_r($methods);die();

							if ($method->getCreditCard()) {
								$style = '' ;	
							} else {
								$style = 'style = "display:none"';
							}			
							?>
							<div class="span12">
								<table>
									<tr id="tr_card_number" <?php echo $style; ?>>
										<td class="infor_left_col"><?php echo  JText::_('OS_AUTH_CARD_NUMBER'); ?><span class="required">*</span></td>
										<td class="infor_right_col">
											<input type="text" name="x_card_num" id="x_card_num" class="osm_inputbox input-medium" onkeyup="checkNumber(this,'<?php echo JText::_('OS_ONLY_NUMBER'); ?>')" value="<?php echo $x_card_num; ?>" size="20" />
										</td>					
									</tr>
									<tr id="tr_exp_date" <?php echo $style; ?>>
										<td class="infor_left_col">
											<?php echo JText::_('OS_AUTH_CARD_EXPIRY_DATE'); ?><span class="required">*</span>
										</td>
										<td class="infor_right_col">	
											<?php echo $lists['exp_month'] .'  /  '.$lists['exp_year'] ; ?>
										</td>					
									</tr>
									<tr id="tr_cvv_code" <?php echo $style; ?>>
										<td class="infor_left_col">
											<?php echo JText::_('OS_AUTH_CVV_CODE'); ?><span class="required">*</span>
										</td>
										<td class="infor_right_col">
											<input type="text" name="x_card_code" id="x_card_code" class="osm_inputbox input-medium" onKeyUp="checkNumber(this,'<?php echo JText::_('OS_ONLY_NUMBER'); ?>')" value="<?php echo $x_card_code; ?>" size="20" />
										</td>					
									</tr>
									<?php
										if ($method->getCardType()) {
											$style = '' ;
										} else {
											$style = ' style = "display:none;" ' ;										
										}
									?>
										<tr id="tr_card_type" <?php echo $style; ?>>
											<td class="infor_left_col">
												<?php echo JText::_('OS_CARD_TYPE'); ?><span class="required">*</span>
											</td>
											<td class="infor_right_col">
												<?php echo $lists['card_type'] ; ?>
											</td>						
										</tr>					
									<?php
										if ($method->getCardHolderName()) {
											$style = '' ;
										} else {
											$style = ' style = "display:none;" ' ;										
										}
									?>
										<tr id="tr_card_holder_name" <?php echo $style; ?>>
											<td class="infor_left_col">
												<?php echo JText::_('OS_CARD_HOLDER_NAME'); ?><span class="required">*</span>
											</td>
											<td class="infor_right_col">
												<input type="text" name="card_holder_name" id="card_holder_name" class="osm_inputbox input-medium"  value="<?php echo $cardHolderName; ?>" size="40" />
											</td>						
										</tr>
									<?php									
										if ($method->getName() == 'os_echeck') {
											$style = '';												
										} else {
											$style = ' style = "display:none;" ' ;
										}
									?>
									
									    <tr id="tr_bank_rounting_number" <?php echo $style; ?>>
									      <td class="infor_left_col"  class="infor_left_col"><?php echo JText::_('OSM_BANK_ROUTING_NUMBER'); ?><span class="required">*</span></td>
									      <td class="infor_right_col"><input type="text" name="x_bank_aba_code" class="osm_inputbox input-medium"  value="<?php echo $x_bank_aba_code; ?>" size="40" onKeyUp="checkNumber(this,'<?php echo JText::_('OS_ONLY_NUMBER'); ?>');" /></td>
									    </tr>
									    <tr id="tr_bank_account_number" <?php echo $style; ?>>
									      <td class="infor_left_col" class="infor_left_col"><?php echo JText::_('OSM_BANK_ACCOUNT_NUMBER'); ?><span class="required">*</span></td>
									      <td class="infor_right_col"><input type="text" name="x_bank_acct_num" class="osm_inputbox input-medium"  value="<?php echo $x_bank_acct_num; ?>" size="40" onKeyUp="checkNumber(this,'<?php echo JText::_('OS_ONLY_NUMBER'); ?>');" /></td>
									    </tr>
									    <tr id="tr_bank_account_type" <?php echo $style; ?>>
									      <td class="infor_left_col"  class="infor_left_col"><?php echo JText::_('OSM_BANK_ACCOUNT_TYPE'); ?><span class="required">*</span></td>
									      <td class="infor_right_col"><?php echo $lists['x_bank_acct_type']; ?></td>
									    </tr>
									    <tr id="tr_bank_name" <?php echo $style; ?>>
									      <td class="infor_left_col" class="infor_left_col"><?php echo JText::_('OSM_BANK_NAME'); ?><span class="required">*</span></td>
									      <td class="infor_right_col"><input type="text" name="x_bank_name" class="osm_inputbox input-medium"  value="<?php echo $x_bank_name; ?>" size="40" /></td>
									    </tr>
									    <tr id="tr_bank_account_holder" <?php echo $style; ?>>
									      <td class="infor_left_col" class="infor_left_col"><?php echo JText::_('OSM_ACCOUNT_HOLDER_NAME'); ?><span class="required">*</span></td>
									      <td class="infor_right_col"><input type="text" name="x_bank_acct_name" class="osm_inputbox input-medium"  value="<?php echo $x_bank_acct_name; ?>" size="40" /></td>
									    </tr>	
									<?php
									$idealEnabled = HelperOSappscheduleCommon::idealEnabled();		
									if ($idealEnabled) {
								        if ($method->getName() == 'os_ideal') {
											$style = '' ;
										} else {
											$style = ' style = "display:none;" ' ;
										}	
									?>
										<tr id="tr_bank_list" <?php echo $style; ?>>
											<td class="infor_left_col">
												<?php echo JText::_('OS_BANK_LIST'); ?><span class="required">*</span>
											</td>
											<td class="infor_right_col">
												<?php echo $lists['bank_id'] ; ?>
											</td>
										</tr>
									<?php	
								    }
							    ?>
							    </table>
						    </div>
						    <div class="clearfix"></div>
						    <?php
						}
						if($configClass['value_sch_include_captcha'] == 3){
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_CAPCHA')?>
							</div>
							<div class="span8">
								<?php
								JPluginHelper::importPlugin('captcha');
								$dispatcher = JDispatcher::getInstance();
								$dispatcher->trigger('onInit','dynamic_recaptcha_1');
								?>
								<div id="dynamic_recaptcha_1"></div>
							</div>
						<?php
						}elseif($configClass['value_sch_include_captcha'] == 2){
						?>
							<div class="span3 boldtext">
								<?php echo JText::_('OS_CAPCHA')?>
							</div>
							<div class="span8">
								<?php
								$resultStr = md5(HelperOSappscheduleCommon::getRealTime());// md5 to generate the random string
								$resultStr = substr($resultStr,0,5);//trim 5 digit 
								?>
								<img src="<?php echo JURI::root()?>index.php?option=com_osservicesbooking&no_html=1&task=ajax_captcha&resultStr=<?php echo $resultStr?>" />  
								<input type="text" class="input-small" id="security_code" name="security_code" maxlength="5" style="width: 50px; margin: 0;"/>
								<input type="hidden" name="resultStr" id="resultStr" value="<?php echo $resultStr?>">
							</div>
							<div class="clearfix"></div>
						<?php
						}
						if($configClass['enable_termandcondition'] ==1){
						?>
						<div class="span12">
								<input type="checkbox" name="term_and_condition" id="term_and_condition" value="0" style="margin:0px !important;" onclick="javascript:changeValue('term_and_condition');"/>
								&nbsp;<strong>
								<a href="<?php echo JURI::root()?>index.php?option=com_content&view=article&id=<?php echo $configClass['article_id'];?>&tmpl=component" class="osmodal" rel="{handler: 'iframe', size: {x: 500, y: 400}}">
								<?php echo JText::_('OS_I_AGREE_WITH_THE_TERM_AND_CONDITION');?>
								</a>
								</strong>
						</div>
						<?php
						}
						if(OsAppscheduleAjax::isAnyItemsInCart()){
						?>
						<div class="span12" style="text-align:center;">
							<input type="button" class="btn btn-success" value="<?php echo JText::_('OS_SUBMIT')?>" onclick="javascript:confirmBooking()">
						</div>
						<?php } ?>
					</div>
					<input type="hidden" name="fields" id="fields" value="" />
					<input type="hidden" name="option" value="com_osservicesbooking" />
					<input type="hidden" name="task" value="form_step2" />
					<input type="hidden" name="category_id" id="category_id" value="<?php echo Jrequest::getVar('category_id',0)?>" />
					<input type="hidden" name="employee_id" id="employee_id" value="<?php echo Jrequest::getVar('employee_id',0)?>" />
					<input type="hidden" name="vid" id="vid" value="<?php echo Jrequest::getVar('vid',0)?>" />
					<input type="hidden" name="enable_termandcondition" id="enable_termandcondition" value="<?php echo $configClass['enable_termandcondition'];?>" />
					<input type="hidden" name="coupon_id" id="coupon_id" value=""/>
					<input type="hidden" name="discount_100" id="discount_100" value="0" />
					<input type="hidden" name="final_cost" id="final_cost" value="<?php echo $lists['total'];?>" />
					</form>
					<?php
					}
					?>
				</div>
			</div>
		</div>
			
		
		<script language="javascript">
		function addtoCart(sid,eid,time_length){
			var form			= document.appform;
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
			var repeat_day      = document.getElementById('repeat_to_day_' + repeat_name);
			var repeat_month    = document.getElementById('repeat_to_month_' + repeat_name);
			var repeat_year     = document.getElementById('repeat_to_year_' + repeat_name);
			
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
					addtoCartAjax(bookitem.value,end_bookitem,sid,eid,live_site.value,str,repeat);
				}	
			}
		}
		function confirmBooking(){
			var form					=   document.appform;
			var order_name 				= 	document.getElementById('order_name');
			var order_email 			= 	document.getElementById('order_email');
			var order_phone 			= 	document.getElementById('order_phone');
			var order_phone_required 	= 	document.getElementById('order_phone_required');
			var order_country 			= 	document.getElementById('order_country');
			var order_city 				= 	document.getElementById('order_city');
			var order_state 			= 	document.getElementById('order_state');
			var order_zip				= 	document.getElementById('order_zip');
			var order_address			=   document.getElementById('order_address');
			var order_address_required	=   document.getElementById('order_address_required');
			var live_site 				= 	document.getElementById('live_site');
			var resultStr 				=   document.getElementById('resultStr');
			var use_captcha				= 	document.getElementById('use_captcha');
			var field_ids				= 	document.getElementById('field_ids');
			var notes		 			= 	document.getElementById('notes');
			var enable_termandcondition = document.getElementById('enable_termandcondition');
			notes						= 	notes.value;
			notes						= 	notes.replace("&","(@)");
			notes						= 	notes.replace("\"","'");

			var commercial_ids			= document.getElementById('commercial_ids');
			commercial_ids				= commercial_ids.value;
			commercial_ids				= commercial_ids.split(',');
			var fieldtype				= "";
			var objid					= "";
			var objitem					= "";
			var check					= 0;
			if(commercial_ids.length > 0){
				for(i=0;i<commercial_ids.length;i++){
					temp = commercial_ids[i];
					temp = temp.split("||");
					fieldtype = temp[1];
					objid     = temp[0];
					objitem   = document.getElementById(objid);
					if(fieldtype == "1"){
						if(objitem.selected == true){
							check = 1;
						}
					}else if(fieldtype == "2"){
						if(objitem.checked == true){
							check = 1;
						}
					}
				}
			}
			var coupon_code	 			= document.getElementById('coupon_code');
			if(coupon_code != null){
				if(coupon_code.value != ""){
					var answer = confirm("<?php echo JText::_('OS_YOU_ENTER_COUPON_CODE')?>");
					if(answer == 1){
						alert("<?php echo JText::_('OS_CLICK_CHECK_COUPON');?>");
						coupon_code.focus();
						return false;
					}else{
						coupon_code.value = "";
					}
				}
			}
			var methodpass				= 1;
			var paymentMethod 			= "";
			var x_card_num				= "";
			var x_card_code				= "";
			var card_holder_name		= "";
			var exp_month				= "";
			var exp_year				= "";
			var card_type				= "";
			<?php
			if($configClass['disable_payments'] == 1){
				if (count($methods) > 0) {
					if (count($methods) > 1) {
					?>
						var paymentValid = false;
						var nmethods = document.getElementById('nmethods');
						var methodtemp;
						for (var i = 0 ; i < nmethods.value; i++) {
							methodtemp = document.getElementById('pmt' + i);
							if(methodtemp.checked == true){
								paymentValid = true;
								paymentMethod = methodtemp.value;
								break;
							}
						}
						
						if (!paymentValid) {
							alert("<?php echo JText::_('OS_REQUIRE_PAYMENT_OPTION'); ?>");
							methodpass = 0;
						}		
					<?php	
					} else {
					?>
						paymentMethod = "<?php echo $methods[0]->getName(); ?>";
					<?php	
					}				
					?>
					var discount_100	= document.getElementById('discount_100');
					method = methods.Find(paymentMethod);	
					if ((method.getCreditCard()) && (discount_100.value == "0") && (check == 1)) {
						var x_card_nume = document.getElementById('x_card_num');
						if (x_card_nume.value == "") {
							alert("<?php echo  JText::_('OS_ENTER_CARD_NUMBER'); ?>");
							x_card_nume.focus();
							methodpass	= 0;		
						}else{
							x_card_num	= x_card_nume.value;
						}
						
						var x_card_codee = document.getElementById('x_card_code');
						if (x_card_codee.value == "") {
							alert("<?php echo JText::_('OS_ENTER_CARD_CODE'); ?>");
							x_card_codee.focus();
							methodpass	= 0;
						}else{
							x_card_code = x_card_codee.value;
						}
					}
					
					if (method.getCardHolderName()) {
						card_holder_namee = document.getElementById('card_holder_name');
						if (card_holder_namee.value == '') {
							alert("<?php echo JText::_('OS_ENTER_CARD_HOLDER_NAME') ; ?>");
							card_holder_namee.focus();
							methodpass = 0;
						}else{
							card_holder_name = card_holder_namee.value;
						}
					}
	
					var exp_yeare		= document.getElementById('exp_year');
					exp_year			= exp_yeare.value;
					var exp_monthe		= document.getElementById('exp_month');
					exp_month			= exp_monthe.value;
					var card_typee		=  document.getElementById('card_type');
					card_type			= card_typee.value;
				<?php
				}
			}
			?>
			
			field_ids					= 	field_ids.value;
			var fieldArr				= 	new Array();
			fieldArr					= 	field_ids.split(",");
			var str						=	"";
			var temp;
			var element;
			if(fieldArr.length > 0){
				for(i=0;i<fieldArr.length;i++){
					temp = fieldArr[i];
					element				= document.getElementById('field_' + temp);
					required			= document.getElementById('field_' + temp + '_required');
					label				= document.getElementById('field_' + temp + '_label');
					if(element != null){
						if(element.value != ""){
							str += temp + "|" + element.value + "||";
						}else if(required.value == "1"){
							alert(label.value + "<?php echo JText::_('OS_IS_MANDATORY_FIELD');?>");
							return false;
						}
					}
				}
				if(str != ""){
					str					= str.substring(0,str.length - 2);
				}
				str						= str.replace("\"","'");
				document.getElementById('fields').value = str;
			}
			
			if(order_name != null){
				order_name				= order_name.value;
			}else{
				order_name				= "";
			}
			if(order_email != null){
				order_email				= order_email.value;
			}else{
				order_email				= "";
			}
			if(order_phone != null){
				order_phone				= order_phone.value;
			}else{
				order_phone				= "";
			}
			if(order_country != null){
				order_country			= order_country.value;
			}else{
				order_country			= "";
			}
			if(order_city != null){
				order_city				= order_city.value;
			}else{
				order_city				= "";
			}
			if(order_state != null){
				order_state				= order_state.value;
			}else{
				order_state				= "";
			}
			if(order_address != null){
				order_address			= order_address.value;
			}else{
				order_address			= "";
			}
			if(order_zip != null){
				order_zip				= order_zip.value;
			}else{
				order_zip				= "";
			}
			
			var check_captcha			= 0;
			var captcha_pass			= 0;
			if(use_captcha.value == "2"){
				check_captcha			= 1;
				var security_code		=   document.getElementById('security_code');
				if(security_code.value == ""){
					captcha_pass		= 0;
				}else if(security_code.value != resultStr.value){
					captcha_pass		= 0;
				}else{
					captcha_pass		= 1;
				}
			}
			
			var pass_term = 1;
			if(enable_termandcondition.value == 1){
				var term_and_condition	= document.getElementById('term_and_condition');
                if(! document.getElementById('term_and_condition').checked){
                    pass_term = 0;
                }
			}
			
			if(methodpass == 1){
				if((check_captcha == 1) && (captcha_pass == 0)){
					var security_code   =   document.getElementById('security_code');
					alert("<?php echo Jtext::_('OS_CAPTCHA_IS_NOT_VALID');?>");
					security_code.focus();
				}else if(order_name == ""){
					alert("<?php echo JText::_('OS_PLEASE_ENTER_YOUR_NAME')?>");
					document.getElementById('order_name').focus();
				}else if(order_email == ""){
					alert("<?php echo JText::_('OS_PLEASE_ENTER_YOUR_EMAIL')?>");
					document.getElementById('order_email').focus();
				}else if(validateEmail('appform','order_email') == false){
					alert("<?php echo JText::_('OS_EMAIL_IS_NOT_VALID')?>");
					document.getElementById('order_email').focus();
				<?php
				if($configClass['value_sch_include_address'] == 2){
					?>
					}else if(order_address == ""){
						alert("<?php echo JText::_('OS_PLEASE_ENTER_YOUR_ADDRESS')?>");
						document.getElementById('order_address').focus();
					<?php
				}
				?>
				<?php
				if($configClass['value_sch_include_phone'] == 2){
					?>
					}else if(order_phone == ""){
						alert("<?php echo JText::_('OS_PLEASE_ENTER_YOUR_PHONE_NUMBER')?>");
						document.getElementById('order_phone').focus();
					<?php
				}
				?>
				}else if(pass_term == 0){
					alert("<?php echo JText::_('OS_PLEASE_AGREE_TERM_AND_CONDITION');?>");
				}else{
					form.submit();
				}
			}
		}
		</script>
		<?php
	}
	
	function checkoutLayout($lists,$fields,$profile){
		global $mainframe,$configClass;
		//jimport('joomla.html.pane');
		$pane =& JPane::getInstance('tabs');
		$methods = os_payments::getPaymentMethods(true, false) ;
		?>
		
		<div class="row-fluid">
			<div class="span12">
				<?php if(($configClass['using_cart'] == 1) or !OSBHelper::isTheSameDate($lists['date_from'],$lists['date_to'])){
				$secondDiv = "span8";
				?>
				<div class="span4" id="calendardivleft">
					<?php if(!OSBHelper::isTheSameDate($lists['date_from'],$lists['date_to'])){?>
					<div class="row-fluid">
						<div class="span12">
							<?php
							HelperOSappscheduleCalendar::initCalendarForSeveralYear(intval(date("Y",HelperOSappscheduleCommon::getRealTime())),$lists['category'],$lists['employee_id'],$lists['vid'],$lists['date_from'],$lists['date_to']);
							?>
							<input type="hidden" name="ossmh" id="ossmh" value="<?php echo intval(date("m",$lists['current_time']))?>">
							<input type="hidden" name="ossyh" id="ossyh" value="<?php echo intval(date("Y",$lists['current_time']))?>">
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
												OsAppscheduleAjax::cart($userdata,$lists['vid'],$lists['category'],$lists['employee_id'],$lists['date_from'],$lists['date_to']);
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
						<div id="maincontentdiv">
						<?php
						HTML_OsAppscheduleForm::showInforFormHTML($lists,$fields,$profile);
						?>
						</div>
						<div  style="display:none;">
							<?php
							echo JHTML::_('calendar','', 'calendarvl', 'calendarvl', '%Y-%m-%d', array('class'=>'input-medium', 'size'=>'19',  'maxlength'=>'19','style'=>'width:80px;'));
							?>
						</div>
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
		<input type="hidden" name="option" value="com_osservicesbooking" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="month"  id="month" value="<?php echo intval(date("m",$lists['current_time']))?>" />
		<input type="hidden" name="year"  id="year" value="<?php echo date("Y",$lists['current_time'])?>" />
		<input type="hidden" name="day"  id="day" value="<?php echo intval(date("d",$lists['current_time']));?>" />
		<input type="hidden" name="select_day" id="select_day" value="<?php echo $day;?>" />
		<input type="hidden" name="select_month" id="select_month" value="<?php echo $month;?>" />
		<input type="hidden" name="select_year" id="select_year" value="<?php echo $year;?>" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>"  />
		<input type="hidden" name="order_id" id="order_id" value="" />
		<input type="hidden" name="current_date" id="current_date" value=""  />
		<input type="hidden" name="use_captcha" id="use_captcha" value="<?php echo $configClass['value_sch_include_captcha'];?>" />
		<input type="hidden" name="category_id" id="category_id" value="<?php echo JRequest::getVar('category_id',0)?>" />
		<input type="hidden" name="employee_id" id="employee_id" value="<?php echo JRequest::getVar('employee_id',0)?>" />
		<input type="hidden" name="vid" id="vid" value="<?php echo JRequest::getVar('vid',0)?>" />
		<input type="hidden" name="selected_item" id="selected_item" value="" />
		<input type="hidden" name="sid" id="sid" value="<?php echo JRequest::getInt('sid',0);?>" />
		<input type="hidden" name="eid" id="eid" value="" />
		<input type="hidden" name="current_link" id="current_link" value="<?php echo $configClass['current_link']?>" />
		<input type="hidden" name="calendar_normal_style" id="calendar_normal_style" value="<?php echo $configClass['calendar_normal_style'];?>" />
		<input type="hidden" name="calendar_currentdate_style" id="calendar_currentdate_style" value="<?php echo $configClass['calendar_currentdate_style'];?>" />
		<input type="hidden" name="calendar_activate_style" id="calendar_activate_style" value="<?php echo $configClass['calendar_activate_style'];?>" />
		<input type="hidden" name="use_js_popup" id="use_js_popup" value="<?php echo $configClass['use_js_popup'];?>" />
		<input type="hidden" name="using_cart" id="using_cart" value="<?php echo $configClass['using_cart'];?>" />
		<input type="hidden" name="date_from" id="date_from" value="<?php echo $lists['date_from'];?>" />
		<input type="hidden" name="date_to" id="date_to" value="<?php echo $lists['date_to'];?>" />
        <input type="hidden" name="temp_item" id="temp_item" value="" />
		<input type="hidden" name="Itemid" id="Itemid" value="<?php echo Jrequest::getInt('Itemid',0);?>" />
		<div  id="divtemp" style="width:1px;height:1px;"></div>
		<script language="javascript">
		<?php
			os_payments::writeJavascriptObjects();
		?>
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
		
		function checkCoupon(){
			var coupon_code = document.getElementById('coupon_code');
			if(coupon_code.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_ENTER_COUPON_CODE');?>");
			}else{
				checkCouponCodeAjax(coupon_code.value,"<?php echo JURI::root();?>");
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
		</script>
		<?php
	}
}
?>