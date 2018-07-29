<?php
/*------------------------------------------------------------------------
# ajax.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;


class HTML_OsAppscheduleAjax{
	
	function loadServicesHTML($option,$services,$year,$month,$day,$is_day_off,$category_id,$employee_id,$vid,$sid1,$eid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		//jimport('joomla.html.pane');
        $pane =& JPane::getInstance('tabs');
		?>
		<?php
		if($is_day_off == 1){
			?>
			<div class="row-fluid">
				<div class="span12" style="border:1px solid #B3BED3;">
					<div class="sub_header">
						<?php
						$tday = strtotime($year."-".$month."-".$day);
						echo date($configClass['date_format'],$tday);
						?>
					</div>
					<div class="clearfix"></div>
					<div style="padding:10px;">
						<?php echo JText::_('OS_DAY_OFF')?>
					</div>
				</div>
			</div>
			<?php
		}else{
			$db = JFactory::getDbo();
			if(count($services) > 0){
			?>
			<div class="row-fluid">
				<div class="span12">
					<div class="tabbable">
						<?php 
						if(($configClass['hidetabs'] == 1) and (count($services) == 1)){
							//nothing show
						}else{						
							if($configClass['usingtab'] == 0){
								?>
								<ul class="nav nav-pills osbtabs" style="margin-bottom:0px !important;">
								<?php 
								for($i=0;$i<count($services);$i++){
									$service = $services[$i];
									if($sid1 > 0){
										if($sid1 == $service->id){
											$class = 'class="active"';
										}else{
											$class = '';
										}
									}else{
										if($i==0){
											$class = 'class="active"';
										}else{
											$class = '';
										}
									}
									?>
									<li <?php echo $class; ?> style="background:none !important;padding:0px;list-style-type:none !important;"><a href="#pane<?php echo $service->id?>" data-toggle="tab"><?php
										echo OSBHelper::getLanguageFieldValue($service,'service_name');
									?></a></li>
									<?php
								}
								?>
								</ul>
								<?php 
							}else{
								if(count($services) > 1){
									?>
									<div class="row-fluid bookingformdiv" style="text-align:center;">
										<div class="span12 <?php echo $configClass['header_style']?>" style="margin-bottom:10px;">
											<strong><?php echo JText::_('OS_SELECT_SERVICE');?></strong>
										</div>
										<select name="serviceslist" id="serviceslist" class="input-large" onChange="javascript:changingService();">
										<?php
										$tempArr = array();
										for($i=0;$i<count($services);$i++){
											$service = $services[$i];
											?>
											<option value="<?php echo $service->id;?>"><?php echo OSBHelper::getLanguageFieldValue($service,'service_name'); ?></option>
											<?php
											$tempArr[] = $service->id;
										}
										?>
										</select>
									</div>
									<?php 
									$temp = implode("|",$tempArr);
									?>
									<input type="hidden" name="serviceslist_ids" id="serviceslist_ids" value="<?php echo $temp; ?>" />
									<?php 
								}
							}
						 } ?>
					<div class="tab-content">
						<?php
						for($i=0;$i<count($services);$i++){
							
						$service = $services[$i];
						
						//$db->setQuery("Select a.fvalue,c.field_label from #__app_sch_field_data as a inner join #__app_sch_service_fields as b on b.service_id = a.sid inner join #__app_sch_fields as c on c.id = b.field_id where c.published = '1' and a.sid = '$service->id' group by c.id");
						//$fields = $db->loadObjectList();
						$date = $year."-".$month."-".$day;
						$dateArr[0] = $day;
						$dateArr[1] = $month;
						$dateArr[2] = $year;
						$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where a.sid = '$service->id' and b.order_status like 'P'");
						$count_pending = $db->loadResult();
						$count_pending = intval($count_pending);
						
						$db->setQuery("Select count(a.id) from #__app_sch_order_items as a inner join #__app_sch_orders as b on b.id = a.order_id where a.sid = '$service->id' and b.order_status like 'S'");
						$count_success = $db->loadResult();
						$count_success = intval($count_success);
						
						$userdata 			= $_COOKIE['userdata'];
						$temp 				= explode("||",$userdata);
						$count_select  = 0;
						for($j=0;$j<count($temp);$j++){
							$data 				= $temp[$j];
							$data 				= explode("|",$data);
							$sid  				= $data[0];
							$start_booking_date = $data[1];
							$end_booking_date   = $data[2];
							
							if($month < 10){
								$m = "0".$month;
							}else{
								$m = $month;
							}
							
							if($day < 10){
								$d = "0".$day;
							}else{
								$d = $day;
							}
							if(($sid == $service->id) and (date("Y-m-d",$start_booking_date) == $year."-".$m."-".$d)){
								$count_select++;
							}
						}
						
						if($sid1 > 0){
							if($sid1 == $service->id){
								$class = ' active';
							}else{
								$class = '';
							}
						}else{
							if($i==0){
								$class = ' active';
							}else{
								$class = '';
							}
						}
						?>
						<div id="pane<?php echo $service->id;?>" class="tab-pane<?php echo $class;?>">
							<div class="row-fluid bookingformdiv">
								<?php 
								if(count($services) > 1){
								?>
									<div class="span12 <?php echo $configClass['header_style']?>">
										<?php
										echo OSBHelper::getLanguageFieldValue($service,'service_name');
										?>
										(<?php
										$tday = strtotime($year."-".$month."-".$day);
										echo date($configClass['date_format'],$tday);
										?>)
									</div>
									<div class="clearfix"></div>
								<?php
								}
								if(((($configClass['show_service_photo'] == 1) or ($configClass['show_service_description'] == 1) or ($configClass['show_service_info_box'] == 1)) and (count($services) > 1))){
								?>
									
								<div class="row-fluid">
									<div class="span12">
										<?php
										if($configClass['show_service_photo'] == 1){
										?>
											<div style="float:left;margin-right:5px;">
												<?php
												if( $service->service_photo != ""){
												?>
													<img src="<?php echo JURI::root()?>images/osservicesbooking/services/<?php echo $service->service_photo?>" width="120" style="border:1px solid #CCC;padding:2px;">
												<?php
												}else{
												?>
													<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/noimage.png" width="120" style="border:1px solid #CCC;padding:2px;">
												<?php
												}
												?>
											</div>
										<?php
										}
										if($configClass['show_service_info_box'] == 1){
											echo '<div class="service_information_box">';
												HelperOSappscheduleCommon::getServiceInformation($service); 
											echo '</div>';
										}
										if($configClass['show_service_description'] == 1){
											echo OSBHelper::getLanguageFieldValue($service,'service_description');
										}
										?>
									</div>
								</div>
								<?php } ?>
								<div class="clearfix"></div>
								<div class="row-fluid">
									<div class="span12">
										<?php
										OsAppscheduleAjax::loadEmployees($option,$service->id,$employee_id,$dateArr,$vid,$sid1,$eid);
										?>	
									</div>
								</div>
							</div>
						</div>
						<?php
							//echo $pane->endPanel();
						}
						//echo $pane->endPane();
						?>
						</div>
					</div> <!-- Tab content -->
				</div>
			</div>
			<?php
			}else{
				?>
				<div class="row-fluid">
					<div class="span12" style="border:1px solid #B3BED3;">
						<div class="sub_header">
							<?php
							$tday = strtotime($year."-".$month."-".$day);
							echo date($configClass['date_format'],$tday);
							?>
						</div>
						<div class="clearfix"></div>
						<div style="padding:10px;">
							<?php echo JText::_('OS_UNAVAILABLE')?>
						</div>
					</div>
				</div>
				<?php
			}
		}
		
	}
	
	
	/**
	 * Load Employees frames
	 *
	 * @param unknown_type $option
	 * @param unknown_type $employees
	 */
	function loadEmployeeFrames($option,$employees,$sid,$date,$vid,$service_id,$eid){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		if(count($employees) > 1){
		?>

		<div class="row-fluid">
			<div class="span12">
				<strong><?php echo JText::_('OS_SELECT_EMPLOYEE_AND_MAKE_A_BOOKING');?></strong>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php } ?>
		<div class="row-fluid">
			<div class="span12">
				<?php
				//jimport('joomla.html.pane');
				//$panetab =& JPane::getInstance('Tabs');
				if(count($employees) > 0){
					?>
					<div class="tabbable">
						<?php
						if(($configClass['hidetabs'] == 1) and (count($employees) == 1)){
							//nothing show
						}else{
                            if($configClass['usingtab'] == 0) {
                                ?>
                                <ul class="nav nav-pills osbtabs"
                                    style="padding-bottom:0px !important;margin-bottom:0px !important;">
                                    <?php
                                    for ($i = 0; $i < count($employees); $i++) {
                                        $employee = $employees[$i];
                                        if ($eid > 0) {
                                            if ($eid == $employee->id) {
                                                $class = 'class="active"';
                                            } else {
                                                $class = '';
                                            }
                                        } else {
                                            if ($i == 0) {
                                                $class = 'class="active"';
                                            } else {
                                                $class = '';
                                            }
                                        }
                                        ?>
                                        <li <?php echo $class; ?>
                                            style="background:none !important;padding:0px;list-style-type:none !important;">
                                            <a href="#pane<?php echo $sid?><?php echo $i?>" data-toggle="tab"><?php
                                                echo $employee->employee_name;
                                                ?></a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            <?php
                            }else{
                                if(count($employees) > 1){
                                ?>
                                    <select name="employeeslist_<?php echo $sid;?>" id="employeeslist_<?php echo $sid;?>" class="input-large" onChange="javascript:changingEmployee(<?php echo $sid;?>);">
                                        <?php
                                        $tempArr = array();
                                        for($i=0;$i<count($employees);$i++){
                                            $employee = $employees[$i];
                                            ?>
                                            <option value="<?php echo $i;?>"><?php echo $employee->employee_name; ?></option>
                                            <?php
                                            $tempArr[] = $i;
                                        }
                                        ?>
                                    </select>
                                <?php
                                $temp = implode("|",$tempArr);
                                ?>
                                <input type="hidden" name="employeeslist_ids<?php echo $sid;?>" id="employeeslist_ids<?php echo $sid;?>" value="<?php echo $temp; ?>" />
                                <?php
                                }
                            }
						}
                        ?>
						<div class="tab-content">
						<?php
						for($i=0;$i<count($employees);$i++){
							$employee = $employees[$i];
							if($eid > 0){
								if($eid == $employee->id){
									$class = ' active';
								}else{
									$class = '';
								}
							}else{
								if($i==0){
									$class = ' active';
								}else{
									$class = '';
								}
							}
							?>
							<div id="pane<?php echo $sid?><?php echo $i;?>" class="tab-pane<?php echo $class;?>">
								<?php
								$db->setQuery("Select a.id from #__app_sch_venues as a inner join #__app_sch_employee_service as b on a.id = b.vid where b.employee_id = '$employee->id' and b.service_id = '$sid'");
								$venue_id = $db->loadObject();
								if($venue_id > 0){
								?>
								<div class="row-fluid">
									<div class="span12">
										<?php
										HelperOSappscheduleCommon::loadVenueInformation($sid,$employee->id);
										?>
									</div>
								</div>
								<div class="clearfix"></div>
								<?php } ?>
								<?php 
								if($configClass['employee_bar'] == 1){
								?>
								<div class="row-fluid">
									<div class="span12">
										<div class="sub_header">
											<?php
											echo $employee->employee_name;
											?>
											<div class="employee_information">
												<table  width="100%">
													<tr>
														<?php
														if($employee->employee_email != ""){
															?>
															<td width="16">
																<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/email.png">
															</td>
															<td class="employee-email-td">
																<a href="mailto:<?php echo $employee->employee_email; ?>" target="_blank">
																 	<?php echo $employee->employee_email; ?>
																</a>
															</td>
															<?php
														}
														?>
														<?php
														if($employee->employee_phone != ""){
															?>
															<td width="16">
																<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/telephone.png">
															</td>
															<td class="employee-email-td">
																 <?php echo $employee->employee_phone; ?>
															</td>
															<?php
														}
														?>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
								<?php } ?>
								<div class="row-fluid">
									<div class="span12">
										<div id="employee<?php echo $sid?>_<?php echo $employee->id?>">
											<?php
												OsAppscheduleAjax::loadEmployee($sid,$employee->id,$date,$vid);
											?>
										</div>
									</div>
								</div>
							</div>
						<?php
						}
						?>
						</div>
					</div>
					<?php
				}else{
					?>
					<div class="row-fluid">
						<div class="span12">
							<?php echo Jtext::_('OS_NO_STAFF_AVAILABLE'); ?>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
	
	function loadEmployeeFrame($sid,$eid,$date,$vid){
		global $mainframe;
		HelperOSappscheduleCalendar::getAvaiableTimeFrameOfOneEmployee($date,$eid,$sid,$vid);
	}
	
	function showInforFormHTML($option,$lists,$fields){
		global $mainframe,$configClass;
		$user = JFactory::getUser();
		$methods = $lists['methods'];
		?>
		<table  width="100%" style="border:1px solid #B3BED3 !important;">
			<tr>
				<td width="100%" class="header">
					<?php echo JText::_('OS_BOOKING_FORM')?>
					<div style="float:right;padding-right:10px;">
					<a href="javascript:closeForm(<?php echo intval(date("d",HelperOSappscheduleCommon::getRealTime()));?>,<?php echo intval(date("m",HelperOSappscheduleCommon::getRealTime()));?>,<?php echo intval(date("Y",HelperOSappscheduleCommon::getRealTime()));?>);">
						<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/close.png" border="0">
					</a>
					</div>
				</td>
			</tr>
			<tr>
				<td width="100%" style="padding:5px;">
					<table  width="100%">
						<tr>
							<td width="100%" colspan="2" style="color:gray;font-size:11px;padding:5px;">
								<?php echo JText::_('OS_PLEASE_FILL_THE_FORM_BELLOW')?>
							</td>
						</tr>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_NAME')?>
							</td>
							<td class="infor_right_col">
								<input type="text" class="input-large" size="20" name="order_name" id="order_name" value="<?php echo $user->name?>">
							</td>
						</tr>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_EMAIL')?>
							</td>
							<td class="infor_right_col">
								<input type="text" class="input-large" value="<?php echo $user->email?>" size="20" name="order_email" id="order_email">
							</td>
						</tr>
						<?php
						if($configClass['value_sch_include_phone']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_PHONE')?>
							</td>
							<td class="infor_right_col">
								<input type="text" class="input-small" value="" size="10" name="order_phone" id="order_phone">
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_country']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_COUNTRY')?>
							</td>
							<td class="infor_right_col">
								<?php echo $lists['country'];?>
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_address']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_ADDRESS')?>
							</td>
							<td class="infor_right_col">
								<input type="text" class="inputbox" value="" size="20" name="order_address" id="order_address">
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_city']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_CITY')?>
							</td>
							<td class="infor_right_col">
								<input type="text" class="inputbox" value="" size="20" name="order_city" id="order_city">
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_state']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_STATE')?>
							</td>
							<td class="infor_right_col">
								<input type="text" class="inputbox" value="" size="10" name="order_state" id="order_state">
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_zip']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_ZIP')?>
							</td>
							<td class="infor_right_col">
								<input type="text" class="inputbox" value="" size="10" name="order_zip" id="order_zip">
							</td>
						</tr>
						<?php
						}
						?>
						<?php
						$fieldArr = array();
						for($i=0;$i<count($fields);$i++){
							$field = $fields[$i];
							$fieldArr[] = $field->id;
							?>
							<tr>
								<td width="30%" class="infor_left_col">
									<?php echo $field->field_label;?>
								</td>
								<td class="infor_right_col">
									<?php
									OsAppscheduleDefault::orderField($field);
									?>
								</td>
							</tr>
							<?php
						}
						?>
						<tr>
							<td width="30%" class="infor_left_col" valign="top">
								<?php echo JText::_('OS_NOTES')?>
							</td>
							<td class="infor_right_col">
								<textarea name="notes" id="notes" cols="40" rows="4" class="inputbox"></textarea>
							</td>
						</tr>
						<?php
						if($configClass['value_sch_include_captcha'] == 2){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_CAPCHA')?>
							</td>
							<td class="infor_right_col">
								<?php
								$resultStr = md5(HelperOSappscheduleCommon::getRealTime());// md5 to generate the random string
								$resultStr = substr($resultStr,0,5);//trim 5 digit 
								?>
								<img src="<?php echo JURI::root()?>index.php?option=com_osservicesbooking&no_html=1&task=ajax_captcha&resultStr=<?php echo $resultStr?>"> 
								<input type="text" class="inputbox" id="security_code" name="security_code" maxlength="5" style="width: 50px; margin: 0;" class="inputbox"/>
								<input type="hidden" name="resultStr" id="resultStr" value="<?php echo $resultStr?>">
							</td>
						</tr>
						<?php
						}
						?>
						<input type="hidden" name="field_ids" id="field_ids" value="<?php echo implode(",",$fieldArr)?>">
						<input type="hidden" name="nmethods" id="nmethods" value="<?php echo count($methods)?>">
						<?php
						if($configClass['disable_payments'] == 1){
							if(count($methods) > 0){
							?>
								<tr>
									<td class="infor_left_col" valign="top">
										<?php echo JText::_('OS_PAYMENT_OPTION'); ?>
										<span class="required">*</span>						
									</td>
									<td class="infor_right_col">
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
												<input onclick="changePaymentMethod();" type="radio" name="payment_method" id="pmt<?php echo $i?>" value="<?php echo $paymentMethod->getName(); ?>" <?php echo $checked; ?> /><?php echo JText::_($paymentMethod->title) ; ?> <br />
											<?php		
											}	
										?>
									</td>						
								</tr>				
							<?php					
							} else {
								$method = $methods[0] ;
							}		
						
							if ($method->getCreditCard()) {
								$style = '' ;	
							} else {
								$style = 'style = "display:none"';
							}			
							?>			
							<tr id="tr_card_number" <?php echo $style; ?>>
								<td class="infor_left_col"><?php echo  JText::_('OS_AUTH_CARD_NUMBER'); ?><span class="required">*</span></td>
								<td class="infor_right_col">
									<input type="text" name="x_card_num" id="x_card_num" class="osm_inputbox inputbox" onkeyup="checkNumber(this)" value="<?php echo $x_card_num; ?>" size="20" />
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
									<input type="text" name="x_card_code" id="x_card_code" class="osm_inputbox inputbox" onKeyUp="checkNumber(this)" value="<?php echo $x_card_code; ?>" size="20" />
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
										<input type="text" name="card_holder_name" id="card_holder_name" class="osm_inputbox inputbox"  value="<?php echo $cardHolderName; ?>" size="40" />
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
							      <td class="infor_right_col"><input type="text" name="x_bank_aba_code" class="osm_inputbox inputbox"  value="<?php echo $x_bank_aba_code; ?>" size="40" onKeyUp="checkNumber(this);" /></td>
							    </tr>
							    <tr id="tr_bank_account_number" <?php echo $style; ?>>
							      <td class="infor_left_col" class="infor_left_col"><?php echo JText::_('OSM_BANK_ACCOUNT_NUMBER'); ?><span class="required">*</span></td>
							      <td class="infor_right_col"><input type="text" name="x_bank_acct_num" class="osm_inputbox inputbox"  value="<?php echo $x_bank_acct_num; ?>" size="40" onKeyUp="checkNumber(this);" /></td>
							    </tr>
							    <tr id="tr_bank_account_type" <?php echo $style; ?>>
							      <td class="infor_left_col"  class="infor_left_col"><?php echo JText::_('OSM_BANK_ACCOUNT_TYPE'); ?><span class="required">*</span></td>
							      <td class="infor_right_col"><?php echo $lists['x_bank_acct_type']; ?></td>
							    </tr>
							    <tr id="tr_bank_name" <?php echo $style; ?>>
							      <td class="infor_left_col" class="infor_left_col"><?php echo JText::_('OSM_BANK_NAME'); ?><span class="required">*</span></td>
							      <td class="infor_right_col"><input type="text" name="x_bank_name" class="osm_inputbox inputbox"  value="<?php echo $x_bank_name; ?>" size="40" /></td>
							    </tr>
							    <tr id="tr_bank_account_holder" <?php echo $style; ?>>
							      <td class="infor_left_col" class="infor_left_col"><?php echo JText::_('OSM_ACCOUNT_HOLDER_NAME'); ?><span class="required">*</span></td>
							      <td class="infor_right_col"><input type="text" name="x_bank_acct_name" class="osm_inputbox inputbox"  value="<?php echo $x_bank_acct_name; ?>" size="40" /></td>
							    </tr>	
							<?php			
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
						}
						?>
						<tr>
							<td colspan="2">
								<input type="button" class="btn btn-primary" value="Submit" onclick="javascript:confirmBooking()">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
	}
	
	function confirmInforFormHTML($option,$total,$fieldObj,$lists){
		global $mainframe,$configClass;
		?>
		<table  width="100%" style="border:1px solid #B3BED3 !important;">
			<tr>
				<td width="100%" class="header">
					<?php echo JText::_('OS_CONFIRM_INFORMATION')?>
					<div style="float:right;padding-right:10px;">
					<a href="javascript:closeForm()">
						<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/close.png" border="0">
					</a>
					</div>
				</td>
			</tr>
			<tr>
				<td width="100%" style="padding:5px;">
					<table  width="100%">
						<?php
						if($configClass['disable_payments'] == 1){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_PRICE')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo OSBHelper::showMoney($total,1);
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
								$tax = round($total*intval($configClass['tax_payment'])/100);
								echo OSBHelper::showMoney($tax,1);
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
								$final = $total + $tax;
								echo OSBHelper::showMoney($final,1);
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
								echo OSBHelper::showMoney($deposit_payment,1);
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
								<?php
								echo JRequest::getVar('order_name','');
								?>
							</td>
						</tr>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_EMAIL')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo JRequest::getVar('order_email','');
								?>
							</td>
						</tr>
						<?php
						
						if($configClass['value_sch_include_phone']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_PHONE')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo JRequest::getVar('order_phone','');
								?>
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_country']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_COUNTRY')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo JRequest::getVar('order_country','');
								?>
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_address']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_ADDRESS')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo JRequest::getVar('order_address','');
								?>
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_city']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_CITY')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo JRequest::getVar('order_city','');
								?>
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_state']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_STATE')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo JRequest::getVar('order_state','');
								?>
							</td>
						</tr>
						<?php
						}
						if($configClass['value_sch_include_zip']){
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_ZIP')?>
							</td>
							<td class="infor_right_col">
								<?php
								echo JRequest::getVar('order_zip','');
								?>
							</td>
						</tr>
						<?php
						}
						
						if(count($fieldObj) > 0){
							for($i=0;$i<count($fieldObj);$i++){
								$f = $fieldObj[$i];
								?>
								<tr>
									<td width="30%" class="infor_left_col" valign="top" style="padding-top:5px;">
										<?php echo $f->field->field_label;?>
									</td>
									<td class="infor_right_col">
										<?php
										echo $f->fvalue;
										?>
									</td>
								</tr>
								<?php
							}
						}
						?>
						<?php
						$note = JRequest::getVar('notes');
						$note = str_replace("(@)","&",$note);
						//$note = str_replace("@r@","\r",$note);
						//$note = str_replace("@n@","\n",$note);
						?>
						<tr>
							<td width="30%" class="infor_left_col">
								<?php echo JText::_('OS_NOTES');?>
							</td>
							<td class="infor_right_col">
								<?php
								echo nl2br($note);
								?>
							</td>
						</tr>
						<?php
						if($configClass['disable_payments'] == 1){
							$method = $lists['method'];
							?>
							<tr>
								<td width="30%" class="infor_left_col">
									<?php echo JText::_('OS_SELECT_PAYMENT')?>
								</td>
								<td class="infor_right_col">
									<?php echo  JText::_(os_payments::loadPaymentMethod($lists['select_payment'])->title); ?>
								</td>
							</tr>
							<?php
						}
						$method = $lists['method'] ;
						if($lists['select_payment'] != ""){
							if ($method->getCreditCard()) {
							?>	
								<tr>
									<td class="infor_left_col"><?php echo  JText::_('OS_AUTH_CARD_NUMBER'); ?>
									<td class="infor_right_col">
										<?php
											$len = strlen($lists['x_card_num']) ;
											$remaining =  substr($lists['x_card_num'], $len - 4 , 4) ;
											echo str_pad($remaining, $len, '*', STR_PAD_LEFT) ;
										?>												
									</td>
								</tr>
								<tr>
									<td class="infor_left_col">
										<?php echo JText::_('OS_AUTH_CARD_EXPIRY_DATE'); ?>
									</td>
									<td class="infor_right_col">						
										<?php echo $lists['exp_month'] .'/'.$lists['exp_year'] ; ?>
									</td>
								</tr>
								<tr>
									<td class="infor_left_col">
										<?php echo JText::_('OS_AUTH_CVV_CODE'); ?>
									</td>
									<td class="infor_right_col">
										<?php echo $lists['x_card_code'] ; ?>
									</td>
								</tr>
								<?php
									if ($method->getCardType()){
									?>
										<tr>
											<td class="infor_left_col">
												<?php echo JText::_('OS_CARD_TYPE'); ?>
											</td>
											<td class="infor_right_col">
												<?php echo $lists['card_type'] ; ?>
											</td>
										</tr>
									<?php	
									}
								?>
							<?php				
							}						
							if ($method->getCardHolderName()) {
							?>
								<tr>
									<td class="infor_left_col">
										<?php echo JText::_('OSM_CARD_HOLDER_NAME'); ?>
									</td>
									<td class="infor_right_col">
										<?php echo $lists['cardHolderName'];?>
									</td>
								</tr>
							<?php												
							}
						}
						?>
						<tr>
							<td colspan="2">
								<input type="button" class="btn btn-primary" value="Confirm" onclick="javascript:createBooking()">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<!-- hidden tags -->
		<input type="hidden" name="order_name" 			id="order_name" 		value="<?php echo JRequest::getVar('order_name')?>">
		<input type="hidden" name="order_email" 		id="order_email" 		value="<?php echo JRequest::getVar('order_email')?>">
		<input type="hidden" name="order_phone" 		id="order_phone" 		value="<?php echo JRequest::getVar('order_phone')?>">
		<input type="hidden" name="order_country" 		id="order_country" 		value="<?php echo JRequest::getVar('order_country')?>">
		<input type="hidden" name="order_address" 		id="order_address" 		value="<?php echo JRequest::getVar('order_address')?>">
		<input type="hidden" name="order_state" 		id="order_state" 		value="<?php echo JRequest::getVar('order_state')?>">
		<input type="hidden" name="order_city" 			id="order_city" 		value="<?php echo JRequest::getVar('order_city')?>">
		<input type="hidden" name="order_zip" 			id="order_zip" 			value="<?php echo JRequest::getVar('order_zip')?>">
		
		<input type="hidden" name="x_card_num" 			id="x_card_num" 		value="<?php echo $lists['x_card_num']?>">
		<input type="hidden" name="x_card_code" 		id="x_card_code" 		value="<?php echo $lists['x_card_code']?>">
		<input type="hidden" name="card_holder_name" 	id="card_holder_name" 	value="<?php echo $lists['card_holder_name']?>">
		<input type="hidden" name="exp_year" 			id="exp_year" 			value="<?php echo $lists['exp_year']?>">
		<input type="hidden" name="exp_month" 			id="exp_month" 			value="<?php echo $lists['exp_month']?>">
		<input type="hidden" name="card_type" 			id="card_type" 			value="<?php echo $lists['card_type']?>">
		
		<div style="display:none;">
		<input type="hidden" name="select_payment" 		id="select_payment" 	value="<?php echo JRequest::getVar('select_payment')?>">
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
		
		<?php
	}
	
	/**
	 * Show service details 
	 *
	 * @param unknown_type $service
	 */
	function showServiceDetails($service,$date,$nbook){
		global $mainframe,$configClass;
		?>
		<table  width="100%">
		<tr>
			<td class="header" style="padding:0px;">
				
				<div style="float:left;margin-right:5px;">
					<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/details.png">
				</div>
				<div style="float:left;padding-top:4px;">
					<?php echo JText::_('OS_SELECTED_SERVICES')?>
				</div>
			</td>
		</tr>
		<tr>
			<td width="100%" class="service-details-td">
				<strong>
					<?php echo $service->service_name?> <font color='gray'>(<?php echo $date[0]?>/<?php echo $date[1]?>/<?php echo $date[2]?>)</font>
				</strong>
				<BR />
				<font style="font-size:11px;color:gray;">
				<div style="padding-top:5px;padding-bottom:5px;">
				<?php echo JText::_('OS_LENGTH')?>: <strong><?php echo $service->service_total?> <?php echo JText::_('OS_MINS')?></strong>
				&nbsp;&nbsp;
				<?php echo JText::_('OS_PRICE')?>: <strong>
				<?php 
				echo OSBHelper::showMoney($service->service_price,1);
				?>
				</strong>
				
				<BR />
				<?php
				if($nbook > 0){
					?>
					<font color='red'><?php echo JText::_('OS_THERE_IS')?> <?php echo $nbook?> <?php echo JText::_('OS_BOOKS_ALREADY')?></font>
					<?php
				}else{
					?>
					<font color='green'><?php echo JText::_('OS_AVAILABLE_FOR_BOOKING')?></font>
					<?php
				}
				?></div>
				
				<?php
					echo stripslashes(strip_tags($service->service_description));
				?>
				</font>
				<BR />
				<div style="padding-top:5px;text-align:center;width:100%;">
					<a href="javascript:selectEmployee(<?php echo $service->id?>,<?php echo $date[2]?>,<?php echo $date[1]?>,<?php echo $date[0]?>);" class="applink">
						<img src="<?php echo JURI::root()?>components/com_osservicesbooking/style/images/availability.png" border="0" />
					</a>
				</div>
			</td>
		</tr>
		</table>
		<?php
	}
}

?>