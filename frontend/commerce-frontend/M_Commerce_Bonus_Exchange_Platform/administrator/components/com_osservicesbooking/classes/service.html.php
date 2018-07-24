<?php
/*------------------------------------------------------------------------
# service.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;


class HTML_OSappscheduleService{
	/**
	 * Install sample data confirm form
	 *
	 * @param unknown_type $option
	 */
	function confirmInstallSampleDataForm($option){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_INSTALLSAMPLEDATA'));
		JToolBarHelper::cancel();
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<script language="javascript">
		function activeContinueButton(){
			checkbox = document.getElementById('agree');
			startbutton = document.getElementById('startbutton');
			if(checkbox.value == 0){
				checkbox.value = 1;
				startbutton.disabled = false;
			}else{
				checkbox.value = 0;
				startbutton.disabled = true;
			}
		}
		</script>
		<form method="POST" action="index.php?option=com_osproperty" name="adminForm" id="adminForm">
		<table 	  width="100%" class="admintable">
			<tr>
				<td width="100%" style="padding:20px;">
					
					<table   width="100%" style="border-bottom:1px solid #CCC;border-right:1px solid #CCC;background-color:#FFF;">
						<tr>
							<td width="100%" style="text-align:left;padding:20px;">
								<strong>
								    <?php echo JText::_('OS_NOTICE')?>:
                                </strong>
								<br />
								<br />
								To install new sample data, we should empty service, employee and custom fields data tables. So please backup those data before install sample data. 
							</td>
						</tr>
						<tr>
							<td style="padding:20px;text-align:center;border:1px solid red;background-color:pink;font-weight:bold;">
								<input type="checkbox" name="agree" id="agree" value="0" onclick="javascript:activeContinueButton()">&nbsp;
								<?php
									echo JText::_('OS_READ_AND_ACCEPTED');
								?>
								<BR><BR>
								<input type="submit" id="startbutton" class="btn btn-info" value="<?php echo JText::_('OS_START_INSTALL')?>" disabled="true">
								
							</td>
						</tr>
					</table>
					
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osservicesbooking">
		<input type="hidden" name="task" value="service_installdata">
		<input type="hidden" name="boxchecked" value="0">
		</form>
		<?php
	}
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function service_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion,$configClass;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_SERVICES'),'folder');
		JToolBarHelper::addNew('service_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('service_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'service_remove');
			JToolBarHelper::custom('service_duplicate','copy.png','copy.png',JText::_('OS_DUPLICATE_SERVICE'));
			JToolBarHelper::publish('service_publish');
			JToolBarHelper::unpublish('service_unpublish');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		$ordering = ($lists['order'] == 'ordering');
		?>
		
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=service_list" name="adminForm" id="adminForm">
			<table width="100%">
				<tr>
					<td align="left">
						<input type="text" 	class="input-medium search-query" placeholder="<?php echo JText::_('OS_SEARCH');?>"	name="keyword" value="<?php echo  $lists['keyword']; ?>" />
                        <div class="btn-group">
                            <input type="submit" class="btn btn-warning" value="<?php echo JText::_('OS_SEARCH');?>" />
                            <input type="reset"  class="btn btn-info" value="<?php echo JText::_('OS_RESET');?>" onclick="this.form.keyword.value='';this.form.filter_state.value='';this.form.submit();" />
                        </div>
					</td>
					<td align="right" style="text-align:right;">
						<?php echo $lists['filter_state'];?>
					</td>
				</tr>
			</table>
	
			<table class="adminlist table table-striped" width="100%">
				<thead>
					<tr>
						<th width="3%">#</th>
						<th width="2%">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="20%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_SERVICE_NAME'), 'service_name', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<th width="15%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_TIME_SLOT'), 'service_time_type', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<th width="10%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_SERVICE_PRICE'), 'service_price', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<!--
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_SERVICE_LENGTH_MINUTES'), 'service_length', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_SERVICE_TOTAL_MINUTES'), 'service_total', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						-->
						<th width="7%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_ACCESS'), 'access', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<th width="15%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_ORDER'), 'ordering', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
							<?php if ($ordering) echo JHTML::_('grid.order',  $rows ,"filesave.png","service_saveorder"); ?>
						</th>
						<th width="10%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISHED'), 'published', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<th width="10%" style="text-align:center;">
							<?php echo JText::_('OS_AVAILABILITY');?>
						</th>
						<th width="5%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_ID'), 'id', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="12" style="text-align:center;">
							<?php
								echo $pageNav->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$k = 0;
				$db = JFactory::getDbo();
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$checked = JHtml::_('grid.id', $i, $row->id);
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=service_edit&cid[]='. $row->id );
					$published 	= JHTML::_('jgrid.published', $row->published, $i, 'service_');
				?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center"><?php echo $checked; ?></td>
						<td align="left" style="width:20%;"><a href="<?php echo $link; ?>"><?php echo $row->service_name; ?></a>
							<br />
							<span style="font-size:11px;">
							<strong><?php echo JText::_('OS_CATEGORY')?>:</strong>
							<?php
							$db->setQuery("Select category_name from #__app_sch_categories where id = '$row->category_id'");
							$category_name = $db->loadResult();
							echo $category_name;
							
							$db->setQuery("Select concat(a.address,' ',a.city) as vname from #__app_sch_venues as a inner join #__app_sch_venue_services as b on a.id = b.vid where b.sid = '$row->id'");
							$venues = $db->loadColumn(0);
							if(count($venues) > 0){
								?>
								<BR />
								<strong><?php echo JText::_('OS_VENUE')?>:</strong>
								<?php 
								echo implode(", ",$venues);
							}
							?>
							
							</span>
						</td>
						<td align="center" style="text-align:center;width:15%;">
							<?php
							if($row->service_time_type == 0){
								echo JText::_('OS_NORMALLY_TIME_SLOT');
							}elseif($row->service_time_type == 1){
								echo JText::_('OS_CUSTOM_TIME_SLOT');
							}
							if($row->service_time_type == 1){?>
							<a href="index.php?option=com_osservicesbooking&task=service_managetimeslots&sid=<?php echo $row->id;?>" title="Manage Custom Time Slots">
								<img src="<?php echo JUri::root()?>components/com_osservicesbooking/asset/images/timeslot.png" border="0" />
							</a>
							<?php } ?>
						</td>
						<td align="center" style="text-align:center;"><?php echo number_format($row->service_price,2,'.','');?> <?php echo $configClass['currency_format']?>
						</td>
						<!--
						<td align="center"><?php echo $row->service_length?></td>
						<td align="center"><?php echo $row->service_total?></td>
						-->
						<td align="center" style="text-align:center;">
							<?php 
							switch ($row->access){
								case "0":
									echo JText::_('OS_PUBLIC');
								break;
								case "1":
									echo JText::_('OS_REGISTERED');
								break;
								case "2":
									echo JText::_('OS_SPECIAL'); 
								break;
							}
							?>
						</td>
						<td class="order" style="text-align:right;">
							<span><?php echo $pageNav->orderUpIcon( $i, true, 'service_orderup', 'Move Up', 1); ?></span>
							<span><?php echo $pageNav->orderDownIcon( $i, $n, true, 'service_orderdown', 'Move Down',1); ?></span>
							<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" style="width:30px;" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="input-mini" style="text-align: center" />
						</td>						
						<td align="center" style="text-align:center;"><?php echo $published?></td>
						<td align="center" style="text-align:center;">
							<a href="index.php?option=com_osservicesbooking&task=service_manageavailability&id=<?php echo $row->id;?>" title="<?php echo JText::_('OS_MANAGE_AVAILABILITY');?>">
								<img src="<?php echo JURI::root()?>administrator/components/com_osservicesbooking/asset/images/calendar.png" />
							</a>
						</td>
						<td align="center" style="text-align:center;"><?php echo $row->id; ?></td>
					</tr>
				<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="service_list"  />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>" />
		</form>
		<?php
	}
	
	
	/**
	 * Agent field
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function service_modify($option,$row,$lists,$customs,$translatable){
		global $mainframe, $_jversion,$configClass,$languages;
		$db = JFactory::getDbo();
		$version 	= new JVersion();
		$_jversion	= $version->RELEASE;		
		$mainframe 	= JFactory::getApplication();
		JRequest::setVar( 'hidemainmenu', 1 );
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_SERVICES').$title,'folder');
		JToolBarHelper::save('service_save');
		JToolBarHelper::apply('service_apply');
		JToolBarHelper::cancel('service_cancel');
		?>
		<script language="javascript">
		function changeValue(id){
			var temp = document.getElementById(id);
			if(temp.value == 0){
				temp.value = 1;
			}else{
				temp.value = 0;
			}
		}
	
		function showDiv(){
			var service_time_type = document.getElementById('service_time_type');
			var time_slot_div 	  = document.getElementById('time_slot_div');
			if(service_time_type.value == 0){
				time_slot_div.style.display = "block";
			}else{
				time_slot_div.style.display = "none";
			}
		}
		
		function resetRow(id){
			var start_time   = document.getElementById('start_hour' + id);
			start_time.value = "";
			var start_min   = document.getElementById('start_min' + id);
			start_min.value = "";
			var end_time     = document.getElementById('end_hour' + id);
			end_time.value   = "";
			var end_min   = document.getElementById('end_min' + id);
			end_min.value = "";
			var nslots = document.getElementById('nslots' + id);
			nslots.value = "";
		}
		</script>
		<form method="POST" action="index.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<?php 
		if ($translatable)
		{
		?>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('OS_GENERAL'); ?></a></li>
				<li><a href="#translation-page" data-toggle="tab"><?php echo JText::_('OS_TRANSLATION'); ?></a></li>									
			</ul>		
			<div class="tab-content">
				<div class="tab-pane active" id="general-page">			
		<?php	
		}
		?>
			<table class="admintable">
				<tr>
					<td class="key"><?php echo JText::_('OS_CATEGORY_NAME'); ?>: </td>
					<td >
						<?php echo $lists['category']?>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_SERVICE_NAME'); ?>: </td>
					<td>
						<input class="inputbox required" type="text" name="service_name" id="service_name" size="70" value="<?php echo $row->service_name?>" >
						<div id="service_name_invalid" style="display: none; color: red;"><?php echo JText::_('OS_THIS_FIELD_IS_REQUIRED')?></div>
					</td>
				</tr>
				<tr>
					<td class="key" style="vertical-align:top;"><?php echo JText::_('OS_SERVICE_PRICE'); ?>: </td>
					<td >
						<input class="input-mini required"  type="text" name="service_price" id="service_price" size="5" value="<?php echo $row->service_price?>" /> <?php echo $configClass['currency_format'];?>
						<div id="service_price_invalid" style="display: none; color: red;"><?php echo JText::_('OS_THIS_FIELD_IS_REQUIRED')?></div>
						<div id="service_price_value_error" style="display: none; color: red;"><?php echo JText::_('OS_PLEASE_ENTER_A_VALID_NUMBER'); ?></div>
						<div class="clearfix"></div>
						<?php
						if($row->id > 0){
							?>
							<h4>
								<?php echo JText::_('OS_PRICE_ADJUSTMENT')?>
							</h4>
							<strong>
								<?php echo JText::_('OS_BY_DATE_IN_WEEK'); ?>
							</strong>
							<div class="clearfix"></div>
							<table width="80%" style="border:1px solid #CCC;">
								<tr>
									<td width="30%" class="headerajaxtd">
										<?php echo JText::_('OS_DATE_IN_WEEK')?>
									</td>
									<td width="30%" class="headerajaxtd">
										<?php echo JText::_('OS_SAME_AS_ORIGINAL')?>
									</td>
									<td width="30%" class="headerajaxtd">
										<?php echo JText::_('OS_PRICE')?> <?php echo $configClass['currency_format'];?>
									</td>
								</tr>
								<?php
								$dateArr = array(JText::_('OS_MON'),JText::_('OS_TUE'),JText::_('OS_WED'),JText::_('OS_THU'),JText::_('OS_FRI'),JText::_('OS_SAT'),JText::_('OS_SUN'));
								for($i=1;$i<=7;$i++){ 
									if($i % 2 == 0){
										$bgcolor = "#efefef";
									}else{
										$bgcolor = "#FFF";
									}
									$db->setQuery("Select * from #__app_sch_service_price_adjustment where date_in_week = '$i' and sid = '$row->id'");
									$price = $db->loadObject();
									if($price->same_as_original == ""){
										$price->same_as_original = 1;
									}
									if($price->same_as_original == 1){
										$checked = "checked";
										$disable = "disabled";
										$value   = $row->service_price;
									}else{
										$checked = "";
										$disable = "";
										$value   = $price->price;
									}
								?>
								<tr>
									<td width="30%" align="left" style="text-align:center;background-color:<?php echo $bgcolor;?>;">
										<?php
										echo $dateArr[$i-1];
										?>
									</td>
									<td width="30%" align="left" style="text-align:center;background-color:<?php echo $bgcolor;?>;">
										<input onClick="javascript:addCustomPricebyDate(<?php echo $i;?>);" id="same<?php echo $i?>" name="same<?php echo $i?>" type="checkbox" <?php echo $checked;?> id="date<?php echo $i?>" value="1" /> <span style="color:#CCC;">(<?php echo $row->service_price?>)</span>
									</td>
									<td width="30%" align="left" style="text-align:center;background-color:<?php echo $bgcolor;?>;">
										<input class="input-mini"  <?php echo $disable;?> type="text" name="price<?php echo $i?>" id="price<?php echo $i?>" size="5" value="<?php echo $value;?>" /> 
									</td>
								</tr>
								<?php 
								}
								?>	
							</table>
							<BR /><BR />
							<strong>
								<?php echo JText::_('OS_BY_SPECIFIC_DATE_PERIOD'); ?>
							</strong>
							<div class="clearfix"></div>
							<div id="rest_div">
							<?php
							if(count($customs) > 0){
								?>
								<table width="80%" style="border:1px solid #CCC;">
									<tr>
										<td width="40%" class="headerajaxtd">
											<?php echo JText::_('OS_DATE_PERIOD')?>
										</td>
										<td width="20%" class="headerajaxtd">
											<?php echo JText::_('OS_PRICE')?> <?php echo $configClass['currency_format'];?>
										</td>
										<td width="20%" class="headerajaxtd">
											<?php echo JText::_('OS_REMOVE')?>
										</td>
									</tr>
									<?php
									for($i=0;$i<count($customs);$i++){
										$rest = $customs[$i];
										?>
										<tr>
											<td width="30%" align="left" style="text-align:center;">
												<?php
												$timestemp = strtotime($rest->cstart);
												$timestemp1 = strtotime($rest->cend);
												echo date("D, jS M Y",  $timestemp);
												echo "&nbsp;-&nbsp;";
												echo date("D, jS M Y",  $timestemp1);
												?>
											</td>
											<td width="30%" align="left" style="text-align:center;">
												<?php
												echo $rest->amount;
												?>
											</td>
											<td width="30%" align="center">
												<a href="javascript:removeCustomPrice(<?php echo $rest->id?>,<?php echo $row->id?>,'<?php echo JUri::root();?>')">
													<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-delete.png">
												</a>
											</td>
										</tr>
										<?php
									}
									?>
								</table>
								<BR /><BR />
								<?php 
							}
							?>
							</div>
							<?php 
							echo "<strong>".Jtext::_('OS_PRICE_ADJUSTMENT_BY_SPECIAL_PERIOD').'</strong>:&nbsp;';
							echo "<strong>".Jtext::_('OS_FROM')."</strong>&nbsp;&nbsp;".JHTML::_('calendar','', 'cstart', 'cstart', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19'));
							echo "&nbsp;&nbsp;<strong>".Jtext::_('OS_TO')."</strong>&nbsp;&nbsp;".JHTML::_('calendar','', 'cend', 'cend', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19'));
							echo "&nbsp;&nbsp;<strong>".Jtext::_('OS_PRICE')."</strong>&nbsp;&nbsp;";
							?>
							<input type="text" name="camount" id="camount" class="input-mini"/>
							<input type="button" value="<?php echo JText::_('OS_SAVE');?>" class="btn btn-warning" onClick="javascript:saveCustomPrice('<?php echo JUri::root();?>');"/> 
							<input type="hidden" name="live_site" id="live_site" value="<?php echo JUri::root()?>" />
							<?php 
						}
						?>
					</td>
				</tr>
				<?php
				if($configClass['early_bird'] == 1){
					?>
					<tr>
						<td class="key" style="vertical-align:top;padding-top:10px;">
							<?php echo JText::_('OS_EARLY_BIRD'); ?>: 
						</td>
						<td >
							<input type="text" name="early_bird_amount" id="early_bird_amount" class="input-mini" value="<?php echo $row->early_bird_amount;?>" />
							<?php echo $lists['early_bird_type'];?>
							<input type="text" name="early_bird_days" id="early_bird_days" class="input-mini" value="<?php echo $row->early_bird_days;?>" />
							&nbsp;
							<?php echo Jtext::_('OS_DAYS');?>
							<BR />
							<?php echo Jtext::_('OS_EARLY_BIRD_EXPLAIN');?>
						</td>
					</tr>
					<?php 
				} 
				?>
				<?php
				if($configClass['enable_slots_discount'] == 1){
					if(($row->id > 0) and ($row->service_time_type == 1)){
						?>
						<tr>
							<td class="key" style="vertical-align:top;padding-top:10px;">
								<?php echo JText::_('OS_DISCOUNT_BY_NUMBERSLOTS'); ?>: 
							</td>
							<td >
								<input type="text" name="discount_amount" id="discount_amount" class="input-mini" value="<?php echo $row->discount_amount;?>" />
								<?php echo $lists['discount_type'];?>
								&nbsp;
								<?php echo JText::_('OS_WHEN_CUSTOMER_ADD_MORE_THAN');?>&nbsp;
								<input type="text" name="discount_timeslots" id="discount_timeslots" class="input-mini" value="<?php echo $row->discount_timeslots;?>" />
								&nbsp;
								<?php echo Jtext::_('OS_SLOTS');?>
								<BR />
								<?php echo Jtext::_('OS_DISCOUNT_BY_NUMBERSLOTS_EXPLAIN');?>
							</td>
						</tr>
						<?php
					} 
				} 
				?>	
				<tr>
					<td class="key"><?php echo JText::_('OS_SERVICE_LENGTH_MINUTES'); ?>: </td>
					<td >
						<input class="input-mini required calculatored" type="text" name="service_length" id="service_length" size="5" value="<?php echo $row->service_length?>" ><span style="color:red;"><?php echo JText::_('OS_ONLY_FOR_NORMAL_TIME_SLOTS')?></span>
						<div id="service_length_invalid" style="display: none; color: red;"><?php echo JText::_('OS_THIS_FIELD_IS_REQUIRED')?></div>
						<div id="service_length_value_error" style="display: none; color: red;"><?php echo JText::_('OS_PLEASE_ENTER_ONLY_DIGITS'); ?></div>
					</td>
				</tr>
				
				<!--
				<tr>
					<td class="key"><?php echo JText::_('OS_SERVICE_BEFORE_MINUTES'); ?>: </td>
					<td >
						<input class="input-mini calculator calculatored" type="text" name="service_before" id="service_before" size="5" value="<?php echo $row->service_before?>">
						<div id="service_before_value_error" style="display: none; color: red;""><?php echo JText::_('OS_PLEASE_ENTER_ONLY_DIGITS'); ?></div>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_SERVICE_AFTER_MINUTES'); ?>: </td>
					<td >
						<input class="input-mini calculator calculatored" type="text" name="service_after" id="service_after" size="5" value="<?php echo $row->service_after?>">
						<div id="service_after_value_error" style="display: none; color: red;""><?php echo JText::_('OS_PLEASE_ENTER_ONLY_DIGITS'); ?></div>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_NUMBER_SLOTS'); ?>: </td>
					<td >
						<input class="inputbox calculator calculatored" type="text" name="service_slots" id="service_slots" size="5" value="<?php echo $row->service_slots?>">
					</td>
				</tr>
				-->
				<tr>
					<td class="key" valign="top"><?php echo JText::_('OS_PHOTO'); ?>: </td>
					<td >
						<?php
						if($row->service_photo != ""){
							?>
							<img src="<?php echo JURI::root()?>images/osservicesbooking/services/<?php echo $row->service_photo?>" width="150">
							<div class="clr"></div>
							<input type="checkbox" name="remove_image" id="remove_image" value="0" onclick="javascript:changeValue('remove_image')"> Remove photo
							<div class="clr"></div>
							<?php
						}
						?>
						<input type="file" name="image" id="image" size="30" onchange="javascript:checkUploadPhotoFiles('image');">
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_ORDER'); ?>: </td>
					<td width="80%"><?php echo $lists['ordering'];?></td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_ACCESS'); ?>: </td>
					<td width="80%"><?php echo $lists['access'];?></td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_PUBLISHED'); ?>: </td>
					<td width="80%"><?php echo $lists['published'];?></td>
				</tr>
				
				<tr>
					<td class="key" valign="top"><?php echo JText::_('OS_SERVICE_DESCRIPTION'); ?>: </td>
					<td>
						<textarea rows="8" style="width: 70%" name="service_description" id="service_description"><?php echo $row->service_description; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
					<span class="editlinktip hasTip" title="<?php echo JText::_('OS_REPEAT_BOOKING');?>::<?php echo JText::_('OS_REPEAT_BOOKING_EXPLAIN'); ?>">
						<?php echo JText::_('OS_REPEAT_BOOKING'); ?>: 
					</span>
					</td>
					<td>
						<?php
						if($row->repeat_day == 1){
							$daycheck = "checked";
						}else{
							$daycheck = "";
						}
						if($row->repeat_week == 1){
							$weekcheck = "checked";
						}else{
							$weekcheck = "";
						}
						if($row->repeat_month == 1){
							$monthcheck = "checked";
						}else{
							$monthcheck = "";
						}
						?>
						<input type="checkbox" name="repeat_day" id="repeat_day" value="<?php echo $row->repeat_day?>" <?php echo $daycheck;?> onclick="javascript:changeValue('repeat_day')"/>  <?php echo JText::_('OS_REPEAT_DAY')?>
						<BR />
						<input type="checkbox" name="repeat_week" id="repeat_week" value="<?php echo $row->repeat_week;?>" <?php echo $weekcheck;?> onclick="javascript:changeValue('repeat_week')"/>  <?php echo JText::_('OS_REPEAT_WEEK')?>
						<BR />
						<input type="checkbox" name="repeat_month" id="repeat_month" value="<?php echo $row->repeat_month;?>" <?php echo $monthcheck;?> onclick="javascript:changeValue('repeat_month')"/>  <?php echo JText::_('OS_REPEAT_MONTH')?>
					</td>
				</tr>
				
				<tr>
					<td class="key" valign="top">
						<span class="editlinktip hasTip" title="<?php echo JText::_('OS_TIME_SLOT_TYPE');?>::<?php echo JText::_('OS_TIME_SLOT_TYPE_EXPLAIN'); ?>"><?php echo JText::_('OS_TIME_SLOT_TYPE'); ?>: 
						</span>
					</td>
					<td>
						<?php echo $lists['time_slot'];?>
						<?php
						if($row->service_time_type == 0){
							$display = "block";
						}else{
							$display = "none";
						}
						?>
						<div id="time_slot_div" style="display:<?php echo $display;?>;padding-top:10px;">
							<?php echo JText::_('OS_STEP_IN_MINUTES')?>: <?php echo $lists['step_in_minutes'];?>
							<span style="font-style:italic;color:gray;"><?php echo JText::_('OS_STEP_IN_MINUTES_EXPLAIN');?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top">
						<?php echo JText::_('OS_ACYMAILING_LIST');?> 
					</td>
					<td>
						<select name="acymailing_list_id" class="input-large">
					    	<option value="0" <?php if($row->acymailing_list_id == "0"){echo " selected='selected' ";}?> ><?php echo JText::_('OS_USE_GLOBAL');?></option>        
					    	<option value="-1" <?php if($row->acymailing_list_id == "-1"){echo " selected='selected' ";}?> ><?php echo JText::_('OS_NONE');?></option>
						    <?php 
								foreach($lists['acyLists'] as $List){ ?>			
									<option value="<?php echo $List->listid;?>"<?php if($row->acymailing_list_id == $List->listid){echo " selected='selected' ";} ?>><?php echo $List->name;?></option>
						    <?php } ?>          
					    </select>
					</td>
				</tr>
			</table>
		<?php 
		if ($translatable)
		{
		?>
		</div>
			<div class="tab-pane" id="translation-page">
				<ul class="nav nav-tabs">
					<?php
						$i = 0;
						foreach ($languages as $language) {						
							$sef = $language->sef;
							?>
							<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#translation-page-<?php echo $sef; ?>" data-toggle="tab"><?php echo $language->title; ?>
								<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" /></a></li>
							<?php
							$i++;	
						}
					?>			
				</ul>
				<div class="tab-content">			
					<?php	
						$i = 0;
						foreach ($languages as $language)
						{												
							$sef = $language->sef;
						?>
							<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="translation-page-<?php echo $sef; ?>">													
								<table width="100%" class="admintable" style="background-color:white;">
									<tr>
										<td class="key"><?php echo JText::_('OS_SERVICE_NAME'); ?>: </td>
										<td>
											<input class="inputbox required" type="text" name="service_name_<?php echo $sef; ?>" id="service_name_<?php echo $sef; ?>" size="70" value="<?php echo $row->{'service_name_'.$sef};?>" />
										</td>
									</tr>
									<tr>
										<td class="key" valign="top"><?php echo JText::_('OS_SERVICE_DESCRIPTION'); ?>: </td>
										<td>
											<textarea rows="8" style="width: 70%" name="service_description_<?php echo $sef; ?>" id="service_description_<?php echo $sef; ?>"><?php echo $row->{'service_description_'.$sef}; ?></textarea>
										</td>
									</tr>
								</table>
							</div>										
						<?php				
							$i++;		
						}
					?>
				</div>
			</div>
		<?php				
		}
		?>
		<input type="hidden" name="option" value="<?php echo $option?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" id="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="field_require" value="service_name,service_price,service_length" />
		<input type="hidden" name="field_is_number" value="service_name,service_price,service_length" />
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
		</form>
		<script type="text/javascript">
			window.addEvent('domready', function() {
				$$('.required').each(function(el) {					
					el.onblur=function(){
						if (this.value == ''){
							$$('#' + this.id + "_invalid").setStyle('display','');
							this.addClass("invalid");
						}
						if (this.hasClass('calculatored'))calculator();
					}
					el.onkeyup=function(){
						if (this.value == ''){
							$$('#' + this.id + "_invalid").setStyle('display','');
							$$('#' + this.id + "_value_error").setStyle('display','none');
							this.addClass("invalid");
						}else if (this.name !== 'service_name' && isNaN(this.value)){
							$$('#' + this.id + "_invalid").setStyle('display','none');
							$$('#' + this.id + "_value_error").setStyle('display','');
							this.addClass("invalid");
						}else{
							this.removeClass("invalid");
							$$('#' + this.id + "_invalid").setStyle('display','none');
							if (this.name !== 'service_name')$$('#' + this.id + "_value_error").setStyle('display','none');
														
						}
					}
				});

				$$('.calculator').each(function(el) {
					el.onblur=function(){
						calculator();
					}
					
					el.onkeyup=function(){
						if (isNaN(this.value)){
							$$('#' + this.id + "_value_error").setStyle('display','');
							this.addClass("invalid");
						}else{
							$$('#' + this.id + "_value_error").setStyle('display','none');
							this.removeClass("invalid");							
						}
					}
				});

				function calculator(){
					var total = 0;
					$$('.calculatored').each(function(el) {
						if (!isNaN(el.value)) total += eval(el.value);
					});
					$$('#service_total').each(function(el) {
						el.value = total;
					})		
				}
				
			});
			
			Joomla.submitbutton = function(pressbutton)
				{
				var form = document.adminForm;
				if (pressbutton == 'service_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.service_name.value == ''){
					$$('.required').each(function(el) {	
						el.onblur();
					});
				}else if (form.service_price.value == ''){
					$$('.required').each(function(el) {	
						el.onblur();
					});
				}else if (isNaN(form.service_price.value)){
					form.service_price.focus();
				}else if (form.service_length.value == ''){
					$$('.required').each(function(el) {	
						el.onblur();
					});	
				}else if (isNaN(form.service_length.value)){
					form.service_length.focus();
				}else{
					submitform( pressbutton );
					return;
				}
			}

			function addCustomPricebyDate(id){
				var checkbox = document.getElementById('same' + id);
				var price	 = document.getElementById('price' + id);
				if(checkbox.checked == false){
					price.disabled = false;
				}else{
					price.disabled = true;
				}
			}
		</script>
		<?php
	}
	
	/**
	 * List services, dates
	 *
	 * @param unknown_type $option
	 * @param unknown_type $service
	 * @param unknown_type $dates
	 */
	function manageAvailability($option,$service,$dates){
		global $mainframe,$configClass;
		JToolBarHelper::title(JText::_('OS_MANAGE_AVAILABILITY_TIME')." [".$service->service_name."]");
		JToolBarHelper::cancel('service_gotolist');
		?>
		<div class="row-fluid">
			<div class="span12" style="text-align:center;">
				<div class="span6">
					<table class="table table-striped">
						<thead>
							<tr>
								<th style="text-align:center;">
									<?php echo JText::_('OS_DATE'); ?>
								</th>
								<th style="text-align:center;">
									<?php echo JText::_('OS_UNAVAILABLE_TIME'); ?>
								</th>
								<th style="text-align:center;">
									<?php echo JText::_('OS_REMOVE'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if(count($dates) > 0){
								$k = 0;
								for($i=0;$i<count($dates);$i++){
									$date = $dates[$i];
									?>
									<tr class="<?php echo $row?>k">
										<td style="text-align:center;">
											<?php echo date($configClass['date_format'],strtotime($date->avail_date));?>
										</td>
										<td style="text-align:center;">
											<?php echo date($configClass['time_format'],strtotime($date->avail_date." ".$date->start_time));?>
										&nbsp;-&nbsp;
											<?php echo date($configClass['time_format'],strtotime($date->avail_date." ".$date->end_time));?>
										</td>
										<td style="text-align:center;">
											<a href="javascript:removeUnvailableTime(<?php echo $date->id?>,<?php echo $service->id; ?>);" title="<?php echo JText::_('OS_REMOVE_UNAVAILABLE_TIME');?>">
												<img src="<?php echo JURI::root()?>administrator/components/com_osservicesbooking/asset/images/unpublish.png" />
											</a>
										</td>
									</tr>
									<?php
									$k = 1 - $k;
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<div class="span6" style="border-left:1px solid gray;">
					<form method="POST" action="index.php?option=com_osservicesbooking" name="adminForm" id="adminForm" class="form-horizontal">
						<strong>
							<?php echo JText::_('OS_ADD_UNVAILABLE_TIME');?>
						</strong>
						<BR /><BR />
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('OS_DATE');?></label>
							<div class="controls">
								<?php echo JHtml::_('calendar','','avail_date','avail_date','%Y-%m-%d','placeholder="2014-01-01" class="input-small"')?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('OS_START_TIME');?> (hh:mm:ss)</label>
							<div class="controls">
								<input type="text" name="start_time" id="start_time" class="input-small" placeholder="01:02:03"/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('OS_END_TIME');?> (hh:mm:ss)</label>
							<div class="controls">
								<input type="text" name="end_time" id="end_time" class="input-small" placeholder="01:02:03" />
							</div>
						</div>
						<div class="clearfix"></div>
						<input type="button" value="<?php echo JText::_('OS_ADD')?>" class="btn btn-info" onclick="javascript:submitForm();"/>
						<input type="hidden" name="option" value="com_osservicesbooking" />
						<input type="hidden" name="task" value="service_addunvailabletime" />
						<input type="hidden" name="id" value="<?php echo $service->id?>" />
					</form>
				</div>
			</div>
		</div>
		<script language="javascript">
		function submitForm(){
			var form = document.adminForm;
			var avail_date = form.avail_date;
			var start_time = form.start_time;
			var end_time   = form.end_time;
			if(avail_date.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_SELECT_DATE');?>");
				avail_date.focus();
				return false;
			}else if(start_time.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_SELECT_START_TIME');?>");
				start_time.focus();
				return false;
			}else if(end_time.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_SELECT_END_TIME');?>");
				end_time.focus();
				return false;
			}else{
				form.submit();
			}
		}
		
		function removeUnvailableTime(id,sid){
			var answer = confirm("<?php echo JText::_('OS_DO_YOU_WANT_TO_REMOVE_UNAVAILABLE_TIME');?>")	;
			if(answer == 1){
				location.href = "index.php?option=com_osservicesbooking&task=service_removeunvailabletime&id=" + id + "&sid=" + sid;
			}
		}
		</script>
		<?php
	}
	
	/**
	 * Manage Time Slots
	 *
	 * @param unknown_type $service
	 * @param unknown_type $slots
	 * @param unknown_type $pageNav
	 */
	function manageTimeSlots($service,$slots,$pageNav)
	{
		global $mainframe,$configClass;
		JToolBarHelper::title($service->service_name." > ".JText::_('OS_MANAGE_CUSTOM_TIME_SLOTS'),'service.png');
		JToolBarHelper::addNew('service_timeslotadd');
		JToolBarHelper::editList('service_timeslotedit');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'service_removetimeslots');
		JToolBarHelper::cancel('service_gotolist');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osservicesbooking&task=service_managetimeslots" name="adminForm" id="adminForm">
			<table class="adminlist table table-striped" width="100%">
				<thead>
					<tr>
						<th width="3%" style="text-align:center;">#</th>
						<th width="2%" style="text-align:center;">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="10%" style="text-align:center;">
							Start Time
						</th>
						<th width="10%" style="text-align:center;">
							End Time
						</th>
						<th width="10%" style="text-align:center;">
							Number Seats
						</th>
						<th width="8%" style="text-align:center;">
							Mon
						</th>
						<th width="8%" style="text-align:center;">
							Tue
						</th>
						<th width="8%" style="text-align:center;">
							Wed
						</th>
						<th width="8%" style="text-align:center;">
							Thu
						</th>
						<th width="8%" style="text-align:center;">
							Fri
						</th>
						<th width="8%" style="text-align:center;">
							Sat
						</th>
						<th width="8%" style="text-align:center;">
							Sun
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="14" style="text-align:center;">
							<?php
								echo $pageNav->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$k = 0;
				$db = JFactory::getDbo();
				for ($i=0, $n=count($slots); $i < $n; $i++) {
					$row = $slots[$i];
					$checked = JHtml::_('grid.id', $i, $row->id);
					$link 	 = JRoute::_( 'index.php?option=com_osservicesbooking&task=service_timeslotedit&cid[]='. $row->id .'&sid='.$service->id);
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center"><?php echo $checked; ?></td>
						<td align="center" style="text-align:center;">
							<a href="<?php echo $link?>">
								<?php echo $row->start_hour?>:<?php echo $row->start_min;?>
							</a>
						</td>
						<td align="center" style="text-align:center;">
							<a href="<?php echo $link?>">
								<?php echo $row->end_hour?>:<?php echo $row->end_min;?>
							</a>
						</td>
						<td align="center" style="text-align:center;">
							<?php echo $row->nslots;?>
						</td>
						<?php 
						for($j=1;$j<=7;$j++)
						{
							?>
							<td align="center" style="text-align:center;">
								<div id="date<?php echo $row->id?><?php echo $j?>">
								<?php 
								$db->setQuery("Select count(id) from #__app_sch_custom_time_slots_relation where time_slot_id = '$row->id' and date_in_week = '$j'");
								$count = $db->loadResult();
								if($count > 0)
								{
									?>
									<a href="javascript:changeTimeSlotDate(0,<?php echo $j?>,<?php echo $service->id?>,<?php echo $row->id?>,'<?php echo JUri::root();?>');" title="Unselect this day">
										<img alt="Unselect this day" src="<?php echo JUri::root()?>components/com_osservicesbooking/asset/images/publish.png" border="0" />
									</a>
									<?php
								}else{
									?>
									<a href="javascript:changeTimeSlotDate(1,<?php echo $j?>,<?php echo $service->id?>,<?php echo $row->id?>,'<?php echo JUri::root();?>');" title="Select this day">
										<img alt="Select this day" src="<?php echo JUri::root()?>components/com_osservicesbooking/asset/images/unpublish.png" border="0" />
									</a>
									<?php 
								}
								?>
								</div>
							</td>
							<?php 
						}
						?>
					</tr>
				<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="com_osservicesbooking" />
			<input type="hidden" name="task" value="service_managetimeslots"  />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="sid" id="sid" value="<?php echo $service->id;?>"/>
			<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root()?>" />
			<input type="hidden" name="selected_item" id="selected_item" value="" />
			
		</form>
		<?php
	}
	
	function editTimeSlot($slot,$lists,$sid)
	{
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		if($slot->id > 0){
			$edit = JText::_('OS_EDIT');
		}else{
			$edit = JText::_('OS_ADD');
		}
		JToolBarHelper::title(JText::_('OS_MANAGE_CUSTOM_TIME_SLOTS')." [$edit]",'service.png');
		JToolBarHelper::save('service_timeslotsave');
		JToolBarHelper::apply('service_timeslotapply');
		JToolbarHelper::save2new('service_timeslotsavenew');
		JToolBarHelper::cancel('service_gotolisttimeslot');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osservicesbooking" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<table class="admintable">
			<tr>
				<td class="key"><?php echo JText::_('OS_START'); ?>: </td>
				<td >
					<?php
					echo JHTML::_('select.genericlist',$lists['hours'],'start_hour','class="input-mini"','value','text',$slot->start_hour);
					echo JHTML::_('select.genericlist',$lists['mins'],'start_min','class="input-mini"','value','text',$slot->start_min);
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_END'); ?>: </td>
				<td >
					<?php
					echo JHTML::_('select.genericlist',$lists['hours'],'end_hour','class="input-mini"','value','text',$slot->end_hour);
					echo JHTML::_('select.genericlist',$lists['mins'],'end_min','class="input-mini"','value','text',$slot->end_min);
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_NUMBER_SEATS'); ?>: </td>
				<td>
					<input class="input-mini required" type="text" name="nslots" id="nslots"  value="<?php echo intval($slot->nslots);?>" />
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('Activate On'); ?>: </td>
				<td>
					<?php
					$date_array = array(JText::_('OS_MON'),JText::_('OS_TUE'),JText::_('OS_WED'),JText::_('OS_THU'),JText::_('OS_FRI'),JText::_('OS_SAT'),JText::_('OS_SUN'));
					for($j=1;$j<=7;$j++)
					{
						$db->setQuery("Select count(id) from #__app_sch_custom_time_slots_relation where time_slot_id = '$slot->id' and date_in_week = '$j'");
						$count = $db->loadResult();
						if($count > 0)
						{
							$check = "checked";
						}else{
							$checked = "";
						}
						?>
						<input type="checkbox" name="date_in_week[]" id="date<?php echo $j?>" <?php echo $check?> value="<?php echo $j?>" />&nbsp; <?php echo $date_array[$j-1];?>
						<BR />
						<?php 
					}
					?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_osservicesbooking" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $slot->id?>" />
		<input type="hidden" name="sid" id="sid" value="<?php echo $sid;?>"/>
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>