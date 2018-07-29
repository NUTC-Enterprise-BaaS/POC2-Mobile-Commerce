<?php
/*------------------------------------------------------------------------
# employee.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;


class HTML_OSappscheduleEmployee{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function employee_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_EMPLOYEE_MANAGE'),'user');
		JToolBarHelper::addNew('employee_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('employee_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'employee_remove');
			JToolBarHelper::custom('employee_duplicate','copy.png','copy.png',JText::_('OS_DUPLICATE_EMPLOYEE'));
			JToolBarHelper::publish('employee_publish');
			JToolBarHelper::unpublish('employee_unpublish');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		$ordering = ($lists['order'] == 'b.ordering');
	?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=employee_list" name="adminForm" id="adminForm">
			<table  width="100%" border="0">
				<tr>
					<td align="left">
						<input type="text" placeholder="<?php echo JText::_('OS_SEARCH');?>"	class="input-medium search-query" name="keyword" value="<?php echo $lists['keyword']; ?>">
                        <div class="btn-group">
                            <input type="submit" class="btn btn-warning" value="<?php echo JText::_('OS_SEARCH');?>">
                            <input type="reset"  class="btn btn-info" value="<?php echo JText::_('OS_RESET');?>"  onclick="this.form.keyword.value='';this.form.filter_service.value=0;this.form.filter_state.value='';this.form.submit();">
                        </div>
					</td>
					<td align="right">
						<?php echo $lists['filter_service'];?>
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
						<th width="15%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_EMPLOYEE_NAME'), 'a.employee_name', @$lists['order_Dir'], @$lists['order'] ,'employee_list'); ?>
						</th>
						<th width="10%">
							<?php echo JText::_('OS_USER');?>
						</th>
						<th width="20%"><?php echo JText::_('OS_SERVICES'); ?></th>
						<th width="12%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_EMAIL'), 'a.employee_email', @$lists['order_Dir'], @$lists['order'] ,'employee_list'); ?>
						</th>
						<th width="12%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_PHONE'), 'a.employee_phone', @$lists['order_Dir'], @$lists['order'] ,'employee_list'); ?>
						</th>
						
						<?php if ($lists['have_order']){?>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_ORDER'), 'b.ordering', @$lists['order_Dir'], @$lists['order'] ,'employee_list'); ?>
							<?php if ($ordering) echo JHTML::_('grid.order',  $rows ,"filesave.png","employee_saveorder"); ?>
						</th>
						<?php }?>
						<th width="8%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISHED'), 'a.published', @$lists['order_Dir'], @$lists['order'] ,'employee_list'); ?>
						</th>
						<th width="4%"  style="text-align:center;">
							<?php echo JText::_('OS_AVAIABILITY'); ?>
						</th>
						<th width="4%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_ID'), 'a.id', @$lists['order_Dir'], @$lists['order'] ,'employee_list'); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="10" style="text-align:center;">
							<?php
								echo $pageNav->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$checked = JHtml::_('grid.id', $i, $row->id);
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=employee_edit&cid[]='. $row->id );
					$published 	= JHTML::_('jgrid.published', $row->published, $i, 'employee_');
				?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center"><?php echo $checked; ?></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->employee_name; ?></a></td>
						<td align="left">
							<?php
							if($row->user_id > 0){
								$user = JFactory::getUser($row->user_id);
								echo $user->username;
							}else{
								echo "N/A";
							}
							?>
						</td>
						<td align="left" style="font-size:11px;"><?php echo $row->service_name; ?></td>
						<td align="left" style="padding-right: 10px;"><?php echo $row->employee_email; ?> </td>
						<td align="left" style="padding-right: 10px;"><?php echo $row->employee_phone; ?></td>
						
						<?php if ($lists['have_order']){?>
						<td class="order" style="text-align:right;">
							<span><?php echo $pageNav->orderUpIcon( $i, true, 'employee_orderup', 'Move Up', 1); ?></span>
							<span><?php echo $pageNav->orderDownIcon( $i, $n, true, 'employee_orderdown', 'Move Down',1); ?></span>
							<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
							<input type="hidden" name="cid_<?php echo $row->id?>" id="cid_<?php echo $row->id?>" value="<?php echo $row->eid?>">
						</td>						
						<?php }?>
						<td align="center" style="text-align:center;"><?php echo $published?></td>
						<td align="center" style="text-align:center;">
							<a href="index.php?option=com_osservicesbooking&task=employee_availability&eid=<?php echo $row->id?>" title="<?php echo JText::_('OS_MANAGE_AVAILABILITY_CALENDAR')?>">
								<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/calendarx16.png" style="border:0px;"/>
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
			<input type="hidden" name="task" value="employee_list" />
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
	function employee_modify($option,$row,$lists,$rests,$services){
		global $mainframe, $_jversion,$configClass;
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
		JToolBarHelper::title(JText::_('OS_EMPLOYEE').$title,'user');
		JToolBarHelper::save('employee_save');
		JToolBarHelper::apply('employee_apply');
		JToolBarHelper::cancel('employee_cancel');
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
		function resetRow(id){
			var start_time   = document.getElementById('start_time' + id);
			start_time.value = "";
			var end_time     = document.getElementById('end_time' + id);
			end_time.value   = "";
			var extra_cost   = document.getElementById('extra_cost' + id);
			extra_cost.value = "";
		}
		</script>
		<?php
		if (version_compare(JVERSION, '3.5', 'ge')){
		?>
			<script src="<?php echo JUri::root()?>media/jui/js/fielduser.min.js" type="text/javascript"></script>
		<?php } ?>
		<script language="javascript" src="<?php echo JURI::root()?>components/com_osservicesbooking/js/ajax.js"></script>
		<script language="javascript">
		function removeBreakDate(rid){
			removeBreakDateAjax(rid,"<?php echo JURI::root()?>");
		}
		</script>
		<form method="POST" action="index.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
			<table class="admintable">
				<tr>
					<td class="key" ><?php echo JText::_('Select user'); ?>: </td>
					<td >
						<?php //echo $lists['user_id'];
						echo OSappscheduleEmployee::getUserInput($row->user_id);
						?>
					</td>
				</tr>
				<tr>
					<td class="key" ><?php echo JText::_('OS_EMPLOYEE_NAME'); ?>: </td>
					<td >
						<input class="inputbox required" type="text" name="employee_name" id="employee_name" size="40" value="<?php echo $row->employee_name?>" />
						<div id="employee_name_invalid" style="display: none; color: red;"><?php echo JText::_('OS_THIS_FIELD_IS_REQUIRED')?></div>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_EMAIL'); ?>: </td>
					<td >
						<input class="inputbox email" type="text" name="employee_email" id="employee_email" size="40" value="<?php echo $row->employee_email?>" >
						<input class="inputbox" type="checkbox" name="employee_send_email" id="employee_send_email" <?php if ($row->employee_send_email) echo 'checked="checked"'?> value="1" >
						<?php echo JText::_('OS_SEND_EMAIL_WHEN_NEW_BOOKING_IS_MADE')?>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_PHONE'); ?>: </td>
					<td >
						<input class="input-small" type="text" name="employee_phone" id="employee_phone" value="<?php echo $row->employee_phone?>" >
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('Google Client ID'); ?>: </td>
					<td >
						<input class="input-large" type="text" name="client_id" id="client_id"  value="<?php echo $row->client_id?>" >
						Get this from your Google App Credentials page.
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('App Name'); ?>: </td>
					<td >
						<input class="input-medium" type="text" name="app_name" id="app_name"  value="<?php echo $row->app_name?>" >
						This is the name of the App you create on Google. You need to create a Google `App` so that OSB is allowed to talk to your calendar(s)
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('App Email Address'); ?>: </td>
					<td >
						<input class="input-medium" type="text" name="app_email_address" id="app_email_address"  value="<?php echo $row->app_email_address?>" >
						Get this from your Google App Credentials page. You will also need to share your calendar to this email address.
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('P12 Key filename'); ?>: </td>
					<td >
						<input class="input-medium" type="text" name="p12_key_filename" id="p12_key_filename"  value="<?php echo $row->p12_key_filename;?>" >
						This is the key file provided by Google and uploaded to your site.
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_GCALENDAR_ID'); ?>: </td>
					<td >
						<input class="input-large" type="text" name="gcalendarid" id="gcalendarid" value="<?php echo $row->gcalendarid?>" >
						This is obtained on the Google Calendar 'Calendar Settings' screen, Calendar Address section.
					</td>
				</tr>
				<tr>
					<td class="key" valign="top"><?php echo JText::_('OS_NOTES'); ?>: </td>
					<td > 
						<textarea rows="5" cols="50" name="employee_notes" id="employee_notes"><?php echo $row->employee_notes; ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_PUBLISHED'); ?>: </td>
					<td width="80%"><?php echo $lists['published'];?></td>
				</tr>
				<tr>
					<td class="key" valign="top"><?php echo JText::_('Photo'); ?>: </td>
					<td >
						<?php
						if($row->employee_photo != ""){
							?>
							<img src="<?php echo JURI::root()?>images/osservicesbooking/employee/<?php echo $row->employee_photo?>" width="150">
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
					<td class="key" valign="top"><?php echo JText::_('OS_REST_DAYS'); ?>: </td>
					<td width="80%" style="font-size:12px;">
						<?php echo JText::_('OS_REST_DAYS_EXPLAIN'); ?>
						<BR />
						<div id="rest_div">
						<?php
						if(count($rests) > 0){
							?>
							<table width="80%" style="border:1px solid #CCC;">
								<tr>
									<td width="30%" style="text-align:center;font-weight:bold;border-bottom:1px solid #CCC;">
										<?php echo JText::_('OS_DATE')?>
									</td>
									<td width="20%" style="text-align:center;font-weight:bold;border-bottom:1px solid #CCC;">
										<?php echo JText::_('OS_REMOVE')?>
									</td>
								</tr>
								<?php
								for($i=0;$i<count($rests);$i++){
									$rest = $rests[$i];
									?>
									<tr>
										<td width="30%" align="left" style="padding-left:10px;">
											<?php
											$timestemp = strtotime($rest->rest_date);
											echo date("D, jS M Y",  $timestemp);
											$timestemp = strtotime($rest->rest_date_to);
											echo " - ";
											echo date("D, jS M Y",  $timestemp)
											?>
										</td>
										<td width="30%" align="center">
											<a href="javascript:removeBreakDate(<?php echo $rest->id?>)">
												<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-delete.png">
											</a>
										</td>
									</tr>
									<?php
								}
								?>
							</table>
							<?php
						}
						?>
						</div>
						<BR />
						<B><?php echo JText::_('OS_ADD_REST_DAY')?></B>
						<BR />
						<?php
						for($i=1;$i<=5;$i++){
							echo JText::_('OS_DATE');
							echo " #".$i.": ";
							echo JText::_('OS_FROM').": ";
							echo JHTML::_('calendar','', 'date'.$i, 'date'.$i, '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19')); 
							echo " - ";
							echo JText::_('OS_TO').": ";
							echo JHTML::_('calendar','', 'date_to_'.$i, 'date_to_'.$i, '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19')); 
							echo "<BR />";
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top"><?php echo JText::_('OS_SERVICES'); ?>: </td>
					<td width="80%">
					<?php //
					if($row->id == 0){
						echo JText::_('OS_AFTER_SAVING_THIS_EMPLOYEE_YOU_WILL_BE_ABLE_TO_ASSIGN_EMPLOYEE_TO_SERVICES');
					}else{
					?>
						<table class="table table-striped">
							<thead>
								<th width="5%">
									#
								</th>
								<th width="15%">
									<?php echo JText::_('OS_SERVICE');?>
								</th>
								<th width="15%">
									<?php echo JText::_('OS_VENUE');?>
								</th>
								<th width="25%">
									<?php echo JText::_('OS_WORKING_DATE');?>
								</th>
								<th width="30%">
									<?php echo JText::_('OS_BREAK_TIME');?>
								</th>
								<th width="10%">
									<?php echo JText::_('OS_SETUP');?>
								</th>
							</thead>
							<tbody>
								<?php
								$k = 0;
								for($i=0;$i<count($services);$i++){
									$k = 1 - $k;
									$service = $services[$i];
									$db->setQuery("Select count(id) from #__app_sch_employee_service where employee_id = '$row->id' and service_id = '$service->id'");
									$count = $db->loadResult();
									$workingdateArr = array();
									if($count > 0){
										$db->setQuery("Select * from #__app_sch_employee_service where employee_id = '$row->id' and service_id = '$service->id'");
										$relation = $db->loadObject();
										if($relation->vid > 0){
											$db->setQuery("Select address from #__app_sch_venues where id = '$relation->vid'");
											$address = $db->loadResult();
										}
										if($relation->mo == 1){
											$workingdateArr[] = JText::_('OS_MON');
											$db->setQuery("Select count(id) from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '1'");
											$countMonday = $db->loadResult();
											$breakMonday = array();
											if($countMonday > 0){
												$db->setQuery("Select * from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '1'");
												$mondays = $db->loadObjectList();
												if(count($mondays) > 0){
													for($j=0;$j<count($mondays);$j++){
														$breakMonday[$j] = $mondays[$j]->break_from." - ".$mondays[$j]->break_to;
														//$breakMonday[$j]->break_to   = $mondays[$j]->break_to;
													}
												}
											}
										}
										if($relation->tu == 1){
											$workingdateArr[] = JText::_('OS_TUE');
											$db->setQuery("Select count(id) from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '2'");
											$countTuesday = $db->loadResult();
											$breakTuesday = array();
											if($countTuesday > 0){
												$db->setQuery("Select * from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '2'");
												$tuesdays = $db->loadObjectList();
												if(count($tuesdays) > 0){
													for($j=0;$j<count($tuesdays);$j++){
														$breakTuesday[$j] = $tuesdays[$j]->break_from." - ".$tuesdays[$j]->break_to;
														//$breakTuesday[$j]->break_to   = $tuesdays[$j]->break_to;
													}
												}
											}
										}
										if($relation->we == 1){
											$workingdateArr[] = JText::_('OS_WED');
											$db->setQuery("Select count(id) from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '3'");
											$countWednesday = $db->loadResult();
											$breakWednesday = array();
											if($countWednesday > 0){
												$db->setQuery("Select * from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '3'");
												$wednesday = $db->loadObjectList();
												if(count($wednesday) > 0){
													for($j=0;$j<count($wednesday);$j++){
														$breakWednesday[$j] = $wednesday[$j]->break_from." - ".$wednesday[$j]->break_to;
														//$breakWednesday[$j]->break_to   = $wednesday[$j]->break_to;
													}
												}
											}
										}
										if($relation->th == 1){
											$workingdateArr[] = JText::_('OS_THU');
											$db->setQuery("Select count(id) from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '4'");
											$countThursday = $db->loadResult();
											$breakThursday = array();
											if($countThursday > 0){
												$db->setQuery("Select * from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '4'");
												$thursdays = $db->loadObjectList();
												if(count($thursdays) > 0){
													for($j=0;$j<count($thursdays);$j++){
														$breakThursday[$j] = $thursdays[$j]->break_from." - ".$thursdays[$j]->break_to;
														//$breakThursday[$j]->break_to   = $thursdays[$j]->break_to;
													}
												}
											}
										}
										if($relation->fr == 1){
											$workingdateArr[] = JText::_('OS_FRI');
											$db->setQuery("Select count(id) from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '5'");
											$countFriday = $db->loadResult();
											$breakFriday = array();
											if($countFriday > 0){
												$db->setQuery("Select * from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '5'");
												$fridays = $db->loadObjectList();
												if(count($fridays) > 0){
													for($j=0;$j<count($fridays);$j++){
														$breakFriday[$j] = $fridays[$j]->break_from." - ".$fridays[$j]->break_to;
														//$breakFriday[$j]->break_to   = $fridays[$j]->break_to;
													}
												}
											}
										}
										if($relation->sa == 1){
											$workingdateArr[] = JText::_('OS_SAT');
											$db->setQuery("Select count(id) from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '6'");
											$countSatuday = $db->loadResult();
											$breakSatuday = array();
											if($countSatuday > 0){
												$db->setQuery("Select * from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '6'");
												$satudays = $db->loadObjectList();
												if(count($satudays) > 0){
													for($j=0;$j<count($satudays);$j++){
														$breakSatuday[$j] = $satudays[$j]->break_from." - ".$satudays[$j]->break_to;
														//$breakSatuday[$j]->break_to   = $satudays[$j]->break_to;
													}
												}
											}
										}
										if($relation->su == 1){
											$workingdateArr[] = JText::_('OS_SUN');
											$db->setQuery("Select count(id) from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '7'");
											$countSunday = $db->loadResult();
											$breakSunday = array();
											if($countSunday > 0){
												$db->setQuery("Select * from #__app_sch_employee_service_breaktime where eid = '$row->id' and sid = '$service->id' and date_in_week = '7'");
												$sundays = $db->loadObjectList();
												if(count($sundays) > 0){
													for($j=0;$j<count($sundays);$j++){
														$breakSunday[$j] = $sundays[$j]->break_from." - ".$sundays[$j]->break_to;
														//$breakSunday[$j]->break_to   = $sundays[$j]->break_to;
													}
												}
											}
										}
									}
									?>
									<tr class="row<?php echo $k?>">
										<td style="text-align:center;">
											<?php echo $i + 1;?>
										</td>
										<td>
											<?php echo $service->service_name;?>
										</td>
										<td>
											<?php
											if($count > 0){
												if($relation->vid > 0){
													echo $address;
												}else{
													echo "N/A";
												}
											}else{
												echo "N/A";
											}
											?>
										</td>
										<td>
											<?php
											if($count > 0){
												echo "<font color='green'>".implode(", ",$workingdateArr)."</font>";
											}else{
												echo "<font color='red'>".JText::_('OS_NO_WORKING_IN_THIS_SERVICE')."</font>";
											}
											?>
										</td>
										<td>
											<?php
											if($count > 0){
												if($countMonday > 0){
													echo JText::_('OS_MON').": ".implode(", ",$breakMonday)."<br />";
												}
												if($countTuesday > 0){
													echo JText::_('OS_TUE').": ".implode(", ",$breakTuesday)."<br />";
												}
												if($countWednesday > 0){
													echo JText::_('OS_WED').": ".implode(", ",$breakWednesday)."<br />";
												}
												if($countThursday > 0){
													echo JText::_('OS_THU').": ".implode(", ",$breakThursday)."<br />";
												}
												if($countFriday > 0){
													echo JText::_('OS_FRI').": ".implode(", ",$breakFriday)."<br />";
												}
												if($countSatuday > 0){
													echo JText::_('OS_SAT').": ".implode(", ",$breakSatuday)."<br />";
												}
												if($countSunday > 0){
													echo JText::_('OS_SUN').": ".implode(", ",$breakSunday)."<br />";
												}
											}
											?>
										</td>
										<td style="text-align:center;">
											<a href="index.php?option=com_osservicesbooking&task=employee_setupbreaktime&eid=<?php echo $row->id?>&sid=<?php echo $service->id?>" title="<?php echo JText::_('OS_CONFIGURE_EMPLOYEE_WITH_THIS_SERVICE');?>">
												<img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/calendar.png" width="20" />
											</a>
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
						<?php
					}
						?>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top"><?php echo JText::_('OS_ADDITIONAL_PRICE_BY_HOUR'); ?>: </td>
					<td width="60%">
						<table width="100%" class="table table-striped"> 
							<thead>
								<tr>
									<th width="20%">
										<?php echo JText::_('Week day');?>
									</th>
									<th width="20%" align="center">
										<?php
											echo JText::_('OS_WORKTIME_START_TIME');
										?>
									</th>
									<th width="20%" align="center">
										<?php
											echo JText::_('OS_WORKTIME_END_TIME');
										?>
									</th>
									<th width="20%" align="center">
										<?php echo JText::_('OS_ADDITIONAL_PRICE');?>
									</th>
									<th width="20%" align="center">
										<?php echo JText::_('OS_RESET');?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 0;
								if(count($lists['extra']) > 0){
									$k = 0;
									for($i=0;$i<count($lists['extra']);$i++){
										$rs = $lists['extra'][$i];
										
										?>
										<tr class="row<?php echo $k?>">
											<td width="20%" align="center">
												<?php
												echo JHTML::_('select.genericlist',$lists['week_day'],'week_day'.$i,'class="input-small"','value','text',$rs->week_date);
												?>
											</td>
											<td width="20%" align="center">
												<?php
												echo JHTML::_('select.genericlist',$lists['hours'],'start_time'.$i,'class="input-small"','value','text',$rs->start_time);
												?>
											</td>
											<td width="20%" align="center">
												<?php
												echo JHTML::_('select.genericlist',$lists['hours'],'end_time'.$i,'class="input-small"','value','text',$rs->end_time);
												?>
											</td>
											<td width="20%" align="center">
												<input type="text" name="extra_cost<?php echo $i?>" id="extra_cost<?php echo $i?>" class="input-mini" size="5" value="<?php echo $rs->extra_cost?>" /> <?php echo $configClass['currency_format'];?>
											</td>
											<td width="20%" align="center">
												<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_RESET')?>" onClick="javascript:resetRow(<?php echo $i?>);" />
											</td>
										</tr>
										<?php
										$k = 1 - $k;
									}
								}
								if($i<10){
									if($i > 0){
										$j = $i + 1;
									}else{
										$j = 0;
									}
									$k = 0;
									for($i=$j;$i<15;$i++){
										?>
										<tr class="row<?php echo $k?>">
											<td width="20%" align="center">
												<?php
												echo JHTML::_('select.genericlist',$lists['week_day'],'week_day'.$i,'class="input-small"','value','text','');
												?>
											</td>
											<td width="20%" align="center">
												<?php
												echo JHTML::_('select.genericlist',$lists['hours'],'start_time'.$i,'class="input-small"','value','text');
												?>
											</td>
											<td width="20%" align="center">
												<?php
												echo JHTML::_('select.genericlist',$lists['hours'],'end_time'.$i,'class="input-small"','value','text');
												?>
											</td>
											<td width="20%" align="center">
												<input type="text" name="extra_cost<?php echo $i?>" id="extra_cost<?php echo $i?>" class="input-mini" size="5" /> <?php echo $configClass['currency_format'];?>
											</td>
											<td width="20%" align="center">
												<input type="button" class="btn btn-info" value="<?php echo JText::_('OS_RESET')?>" onClick="javascript:resetRow(<?php echo $i?>);" />
											</td>
										</tr>
										<?php
										$k = 1 - $k;
									}
								}
								?>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			<input type="hidden" name="option" value="<?php echo $option?>">
			<input type="hidden" name="task" value="">
			<input type="hidden" name="id" value="<?php echo $row->id?>">
			<input type="hidden" name="boxchecked" value="0">
			<input type="hidden" name="MAX_FILE_SIZE" value="9000000">
		</form>
		<script type="text/javascript">
			window.addEvent('domready', function() {
				$$('.required').each(function(el) {					
					el.onblur=function(){
						if (this.value == ''){
							$$('#' + this.id + "_invalid").setStyle('display','');
							this.addClass("invalid");
						}
					}
					el.onkeyup=function(){
						if (this.value == ''){
							$$('#' + this.id + "_invalid").setStyle('display','');
							this.addClass("invalid");
						}else{
							$$('#' + this.id + "_invalid").setStyle('display','none');
							this.removeClass("invalid");
						}
					}
				});

				$$('.email').each(function(el){
					el.onblur=function(){
						var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
						if (this.value != '' && !filter.test(this.value)){
							this.addClass("invalid");
							$$('#employee_send_email').each(function(el) {
								el.checked = false;
							})
						}else{
							this.removeClass("invalid");
						}
					}
				});
			});

			
			Joomla.submitbutton = function(pressbutton){
				var form = document.adminForm;
				var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if (pressbutton == 'employee_cancel'){
					submitform( pressbutton );
					return;
				}else if (form.employee_name.value == ''){
					$$('.required').each(function(el) {	
						el.onblur();
					});
					return;
				}else if (form.employee_email.value != '' && !filter.test(form.employee_email.value)){
					$$('.email').each(function(el){
						el.onblur();
					});	
					return;
				}else{
					submitform( pressbutton );
					return;
				}
			}
			
			function changeValue(id){
				var temp = document.getElementById(id);
				if(temp.value == 0){
					temp.value = 1;
				}else{
					temp.value = 0;
				}
			}
		</script>
		<?php
	}
	
	/**
	 * Calendar Manager
	 *
	 * @param unknown_type $employee
	 */
	function calendarManage($employee){
		global $mainframe;
		JToolBarHelper::title( JText::_('OS_MANAGE_AVAIABILITY_CALENDAR')."[".$employee->employee_name."]");
		JToolBarHelper::cancel('employee_gotoemployeelist');
		?>
		<style>
		.header_calendar{
			font-weight:bold;
			text-align:center;
			padding:5px;
			font-size:14px;
		}
		.td_calendar_date{
			font-size:13px;
			text-align:center;
			vertical-align:middle;
			border:1px dotted #CCC !important;
			padding:5px;
			font-weight:bold;
		}
		</style>
		<form method="POST" action="index.php?option=com_oscalendar" name="adminForm" id="adminForm">
		<table class="admintable" width="100%">
			<tr>
				<td width="100%">
					<?php
					$year = JRequest::getVar('year',date("Y",time()));
					$month =  intval(JRequest::getVar('month',date("m",time())));
					OSBHelper::initCalendarInBackend($employee->id,$year,$month);
					?>
				</td>
			</tr>
		</table>
		<input type="hidden" name="task"    	id="task" 	value=""/>
		<input type="hidden" name="option"  	id="option" value="com_osservicesbooking"/>
		<input type="hidden" name="boxchecked"				value="0" />
		<input type="hidden" name="year"    	id="year" 	value="<?php echo $year;?>">
		<input type="hidden" name="month"   	id="month" 	value="<?php echo $month;?>">
		</form>
		<?php
	}
	
	/**
	 * Break time form
	 *
	 * @param unknown_type $service
	 * @param unknown_type $employee
	 * @param unknown_type $lists
	 */
	function breaktimeForm($service,$employee,$lists,$customs){
		global $mainframe;
		JToolbarHelper::title(JText::_('OS_SETUP_BREAKTIME_OF_EMPLOYEE')." [".$employee->employee_name."] ".JText::_('OS_OF')." ".JText::_('OS_SERVICE')." [".$service->service_name."]");
		JToolbarHelper::save('employee_savebreaktime');
		JToolbarHelper::apply('employee_applybreaktime');
		JToolbarHelper::cancel('employee_gotoemployeeedit');
		?>
		<form method="POST" action="index.php?option=com_osservicesbooking" name="adminForm" id="adminForm">
		<table class="admintable" width="100%">
			<tr>
				<td>
					<?php
					echo $lists['services'];
					?>
				</td>
			</tr>
		</table>
		<bR />
		<h3><?php echo Jtext::_('OS_CUSTOM_BREAK_TIME');?></h3>
		<div id="rest_div">
			<?php
			if(count($customs) > 0){
				?>
				<table width="80%" style="border:1px solid #CCC;">
					<tr>
						<td width="30%" class="headerajaxtd">
							<?php echo JText::_('OS_DATE')?>
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
								$timestemp = strtotime($rest->bdate);
								echo date("D, jS M Y",  $timestemp);
								echo "&nbsp;&nbsp;";
								echo $rest->bstart." - ".$rest->bend;
								?>
							</td>
							<td width="30%" align="center">
								<a href="javascript:removeCustomBreakDate(<?php echo $rest->id?>,'<?php echo JUri::root();?>')">
									<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-delete.png">
								</a>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<?php
				echo "<BR /><BR />";
			}
			?>
		</div>
		<?php 
		echo "<strong>".Jtext::_('OS_ADD_BREAKTIME').'</strong>:&nbsp;';
		echo JHTML::_('calendar','', 'bdate', 'bdate', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19'));
		$hourArray = OSappscheduleEmployee::generateHoursIncludeSecond();
		echo "&nbsp;&nbsp;".Jtext::_('OS_FROM').':&nbsp;';
		echo JHTML::_('select.genericlist',$hourArray,'bstart','class="input-small"','value','text');
		echo "&nbsp;&nbsp;".Jtext::_('OS_TO').':&nbsp;';
		echo JHTML::_('select.genericlist',$hourArray,'bend','class="input-small"','value','text');
		echo "&nbsp;&nbsp;";
		?>
		<input type="button" value="<?php echo Jtext::_('OS_SAVE');?>" class="btn btn-warning" onClick="javascript:saveCustomBreakTime('<?php echo JUri::root();?>');" />
		<input type="hidden" name="task" id="task" value=""/>
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="eid" id="eid" value="<?php echo $employee->id?>" />
		<input type="hidden" name="sid" id="sid" value="<?php echo $service->id?>" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JUri::root()?>" />
		</form>
		<?php
	}
}
?>