<?php
/*------------------------------------------------------------------------
# venue.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OSappscheduleVenue{
	/**
	 * List venues
	 *
	 * @param unknown_type $option
	 * @param unknown_type $pageNav
	 * @param unknown_type $rows
	 */
	function listVenues($option,$pageNav,$rows,$lists){
		global $mainframe,$configClass;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_VENUES'),'location');
		JToolBarHelper::addNew('venue_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('venue_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'venue_remove');
			JToolBarHelper::publish('venue_publish');
			JToolBarHelper::unpublish('venue_unpublish');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=venue_list" name="adminForm" id="adminForm">
			<table width="100%">
				<tr>
					<td align="left">
						<input type="text" 	class="input-medium search-query"	name="keyword" value="<?php echo $lists['keyword']; ?>" placeholder="<?php echo JText::_('OS_SEARCH');?>" />
                        <div class="btn-group">
                            <input type="submit" class="btn btn-warning" value="<?php echo JText::_('OS_SEARCH');?>">
                            <input type="reset"  class="btn btn-info" value="<?php echo JText::_('OS_RESET');?>" onclick="this.form.keyword.value='';this.form.filter_state.value='';this.form.submit();">
                        </div>
					</td>
					<td align="right">
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
							<?php echo JHTML::_('grid.sort',   JText::_('OS_VENUE'), 'address', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_SERVICE'), 'service_time_type', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
						<th width="10%">
							<?php echo JText::_('OS_DISABLE_BOOKING_BEFORE') ?>
						</th>
						<th width="10%">
							<?php echo JText::_('OS_DISABLE_BOOKING_AFTER') ?>
						</th>
						<th width="10%">
							<?php echo JText::_('OS_PHONE') ?>
						</th>
						<th width="10%">
							<?php echo JText::_('OS_EMAIL') ?>
						</th>
						<th width="5%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_PUBLISHED'), 'published', @$lists['order_Dir'], @$lists['order'] ,'service_list'); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="9" style="text-align:center;">
							<?php
								echo $pageNav->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<?php
				$k = 0;
				$db = JFactory::getDbo();
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$checked = JHtml::_('grid.id', $i, $row->id);
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=venue_edit&cid[]='. $row->id );
					$published 	= JHTML::_('jgrid.published', $row->published, $i, 'venue_');
				?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center"><?php echo $checked; ?></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php 
						$address = array();
						$address[] = $row->address;
						if($row->city != ""){
							$address[] = $row->city;
						}
						if($row->state != ""){
							$address[] = $row->state;
						}
						if($row->country != ""){
							$address[] = $row->country;
						}
						echo implode(", ",$address);
						?></a>
						</td>
						<td align="left"><a href="<?php echo $link; ?>">
							<?php 
							$db->setQuery("Select service_name from #__app_sch_services where id in (Select sid from #__app_sch_venue_services where vid = '$row->id')");
							$services = $db->loadObjectList();
							if(count($services) > 0){
								$service_name = array();
								for($j=0;$j<count($services);$j++){
									$service_name[] = $services[$j]->service_name;
								}
								echo implode(", ",$service_name);
							}
							?></a>
						</td>
						<td align="left">
							<?php
							switch ($row->disable_booking_before){
								case "1":
									echo JText::_('OS_TODAY');
								break;
								case "2":
									echo $row->number_date_before." ".JText::_('OS_DAYS_FROM_NOW');
								break;
								case "3":
									echo JText::_('OS_BEFORE')." ".$row->disable_date_before;
								break;
								case "4":
									echo $row->number_hour_before." ".JText::_('OS_HOURS_FROM_NOW');
								break;
							}
							?>
						</td>
						<td align="left">
							<?php
							switch ($row->disable_booking_after){
								case "1":
									echo JText::_('OS_NOT_SET');
								break;
								case "2":
									echo $row->number_date_after." ".JText::_('OS_DAYS_FROM_NOW');
								break;
								case "3":
									echo JText::_('OS_AFTER')." ".$row->disable_date_after;
								break;
							}
							?>
						</td>
						<td align="left" style="font-size:11px;">
							<?php echo $row->contact_phone;?>
						</td>
						<td align="left" style="font-size:11px;">
							<?php echo $row->contact_email;?>
						</td>
						<td align="center" style="text-align:center;"><?php echo $published?></td>
					</tr>
				<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="task" value="venue_list"  />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>" />
		</form>
		<?php
	}
	
	/**
	 * Edit venue
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function editVenueHtml($option,$row,$lists,$translatable){
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
		JToolBarHelper::title(JText::_('OS_VENUE').$title,'location');
		JToolBarHelper::save('venue_save');
		JToolBarHelper::apply('venue_apply');
		JToolBarHelper::cancel('venue_cancel');
		?>
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
		<table class="admintable" width="100%">
			<tr>
				<td width="50%" valign="top">
					<table class="admintable">
						<tr>
							<td width="100%" colspan="2" style="text-align:center;padding:10px;font-weight:bold;color:white;background-color:#2A53C8;">
								<?php echo JText::_('OS_LOCATION');?>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_ADDRESS'); ?>: </td>
							<td >
								<input type="text" class="input-large" name="address" id="address" value="<?php echo $row->address?>"/>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_CITY'); ?>: </td>
							<td >
								<input type="text" class="input-small" name="city" id="city" value="<?php echo $row->city?>"/>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_STATE'); ?>: </td>
							<td >
								<input type="text" class="input-small" name="state" id="state" value="<?php echo $row->state?>"/>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_COUNTRY'); ?>: </td>
							<td >
								<?php echo $lists['country'];?>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_LAT_ADDRESS'); ?>: </td>
							<td >
								<input type="text" class="input-small" name="lat_add" id="lat_add" value="<?php echo $row->lat_add?>"/>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_LONG_ADDRESS'); ?>: </td>
							<td >
								<input type="text" class="input-small" name="long_add" id="long_add" value="<?php echo $row->long_add?>"/>
							</td>
						</tr>
						<tr>
							<td width="100%" colspan="2">
							<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
						    <script>
						      var position = new google.maps.LatLng(<?php echo $row->lat_add;?>, <?php echo $row->long_add;?>);
						      var parliament = new google.maps.LatLng(<?php echo $row->lat_add;?>, <?php echo $row->long_add;?>);
						      var marker;
						      var map;
						
						      function initialize() {
						        var mapOptions = {
						          zoom: 13,
						          mapTypeId: google.maps.MapTypeId.ROADMAP,
						          center: position,
						          mapTypeControl: true,
						          mapTypeControlOptions: {
						            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
						          },
						          zoomControl: true,
						          zoomControlOptions: {
						            style: google.maps.ZoomControlStyle.SMALL
						          }
						        };
						
						        map = new google.maps.Map(document.getElementById('map-canvas'),
						                mapOptions);
						
						        marker = new google.maps.Marker({
						          map:map,
						          draggable:false,
						          position: parliament
						        });
						      }
						    </script>
						  	<body onload="initialize()">
						   		 <div id="map-canvas" style="width: 500px; height: 400px;">map div</div>
						 	</body>
							</td>
						</tr>
					</table>
				</td>
				<td width="50%" valign="top">
					<table class="admintable">
						<tr>
							<td width="100%" colspan="2" style="text-align:center;padding:10px;font-weight:bold;color:white;background-color:#F0C633;">
								<?php echo JText::_('OS_CONTACT_INFORMATION');?>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_CONTACT_NAME'); ?>: </td>
							<td >
								<input type="text" class="input-large" name="contact_name" id="contact_name" value="<?php echo $row->contact_name?>"/>
							</td>
						</tr>
						<tr>
							<td class="key"><span class="hasTip" title="<?php echo JText::_('OS_CONTACT_EMAIL'); ?>::<?php echo JText::_('OS_CONTACT_EMAIL_EXPLAIN'); ?>"><?php echo JText::_('OS_CONTACT_EMAIL'); ?>: </span></td>
							<td >
								<input type="text" class="input-large" name="contact_email" id="contact_email" value="<?php echo $row->contact_email?>"/>
							</td>
						</tr>
						<tr>
							<td class="key"><span class="hasTip" title="<?php echo JText::_('OS_CONTACT_PHONE'); ?>::<?php echo JText::_('OS_CONTACT_PHONE_EXPLAIN'); ?>"><?php echo JText::_('OS_CONTACT_PHONE'); ?></span>: </td>
							<td >
								<input type="text" class="input-small" name="contact_phone" id="contact_phone" value="<?php echo $row->contact_phone?>"/>
							</td>
						</tr>
						
						<tr>
							<td width="100%" colspan="2" style="text-align:center;padding:10px;font-weight:bold;color:white;background-color:#F0335B;">
								<?php echo JText::_('OS_OTHER_INFORMATION');?>
							</td>
						</tr>
						<tr>
							<td class="key" valign="top"><?php echo JText::_('OS_SERVICE'); ?>: </td>
							<td >
								<?php
								echo $lists['service'];
								?>
							</td>
						</tr>
						<tr>
							<td class="key"><span class="hasTip" title="<?php echo JText::_('OS_DISABLE_DATES_BEFORE'); ?>::<?php echo JText::_('OS_DISABLE_DATES_BEFORE_EXPLAIN'); ?>"><?php echo JText::_('OS_DISABLE_DATES_BEFORE'); ?></span>: </td>
							<td>
								<?php
								$check1 = "";
								$check2 = "";
								$check3 = "";
								$check4 = "";
								if($row->disable_booking_before == 1){
									$check1 = "checked";
								}elseif($row->disable_booking_before == 2){
									$check2 = "checked";
								}elseif($row->disable_booking_before == 3){
									$check3 = "checked";
								}elseif($row->disable_booking_before == 4){
									$check4 = "checked";
								}else{
									$check1 = "checked";
								}
								?>
								<input type="radio" name="disable_booking_before" id="disable_booking_before" value="1" <?php echo $check1;?> /> <?php echo JText::_('OS_TODAY');?>
								<BR />
								<input type="radio" name="disable_booking_before" id="disable_booking_before" value="4" <?php echo $check4;?> /> 
								<input type="text" class="input-mini" name="number_hour_before" id="number_hour_before" value="<?php echo $row->number_hour_before?>" style="width:20px;"/> <?php echo JText::_('OS_HOURS_FROM_NOW');?>
								<BR />
								<input type="radio" name="disable_booking_before" id="disable_booking_before" value="2" <?php echo $check2;?> /> 
								<input type="text" class="input-mini" name="number_date_before" id="number_date_before" value="<?php echo $row->number_date_before?>" style="width:20px;"/>
								<?php echo JText::_('OS_DAYS_FROM_NOW');?>
								<BR />
								<input type="radio" name="disable_booking_before" id="disable_booking_before" value="3" <?php echo $check3;?> />
								&nbsp;
								<?php echo JText::_('OS_SPECIFIC_DATE')?>:&nbsp;
								<?php 
								$disable_date_before = $row->disable_date_before;
								if($disable_date_before == "0000-00-00"){
									$disable_date_before = "";
								}
								echo JHTML::_('calendar',$disable_date_before, 'disable_date_before', 'disable_date_before', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19')); ?>
							</td>
						</tr>
						<tr>
							<td class="key"><span class="hasTip" title="<?php echo JText::_('OS_DISABLE_DATES_AFTER'); ?>::<?php echo JText::_('OS_DISABLE_DATES_AFTER_EXPLAIN'); ?>"><?php echo JText::_('OS_DISABLE_DATES_AFTER'); ?></span>: </td>
							<td>
								<?php
								$check1 = "";
								$check2 = "";
								$check3 = "";
								if($row->disable_booking_after == 1){
									$check1 = "checked";
								}elseif($row->disable_booking_after == 2){
									$check2 = "checked";
								}elseif($row->disable_booking_after == 3){
									$check3 = "checked";
								}else{
									$check1 = "checked";
								}
								?>
								<input type="radio" name="disable_booking_after" id="disable_booking_after" value="1" <?php echo $check1;?> /> <?php echo JText::_('OS_NOT_SET');?>
								<BR />
								<input type="radio" name="disable_booking_after" id="disable_booking_after" value="2" <?php echo $check2;?> /> 
								<input type="text" class="input-mini" name="number_date_after" id="number_date_after" value="<?php echo $row->number_date_after?>" style="width:20px;"/>
								<?php echo JText::_('OS_DAYS_FROM_NOW');?>
								<BR />
								<input type="radio" name="disable_booking_after" id="disable_booking_after" value="3" <?php echo $check3;?> /> 
								&nbsp;
								<?php echo JText::_('OS_SPECIFIC_DATE')?>:&nbsp;
								<?php 
								$disable_date_after = $row->disable_date_after;
								if($disable_date_after  == "0000-00-00"){
									$disable_date_after = "";
								}
								echo JHTML::_('calendar',$disable_date_after, 'disable_date_after', 'disable_date_after', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19')); ?>
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_VENUE_IMAGE'); ?>: </td>
							<td >
								<?php
								if($row->image != ""){
									?>
									<img src="<?php echo JURI::root()?>images/osservicesbooking/venue/<?php echo $row->image?>" width="150" class="img-polaroid" />
									<div style="clear:both;"></div>
									<input type="checkbox" name="remove_photo" id="remove_photo" value="0" onclick="javascript:changeValue('remove_photo')"  /> <?php echo JText::_('OS_REMOVE');?>
									<?php
								}
								?>
								<input type="file" name="image" id="image" class="input-small" />
							</td>
						</tr>
						<tr>
							<td class="key"><?php echo JText::_('OS_STATE'); ?>: </td>
							<td >
								<?php
								echo $lists['published'];
								?>
							</td>
						</tr>
					</table>
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
										<td class="key"><?php echo JText::_('OS_ADDRESS'); ?>: </td>
										<td >
											<input type="text" class="input-large" name="address_<?php echo $sef; ?>" id="address_<?php echo $sef; ?>" value="<?php echo $row->{'address_'.$sef};?>"/>
										</td>
									</tr>
									<tr>
										<td class="key"><?php echo JText::_('OS_CITY'); ?>: </td>
										<td >
											<input type="text" class="input-small" name="city_<?php echo $sef; ?>" id="city_<?php echo $sef; ?>" value="<?php echo $row->{'city_'.$sef};?>"/>
										</td>
									</tr>
									<tr>
										<td class="key"><?php echo JText::_('OS_STATE'); ?>: </td>
										<td >
											<input type="text" class="input-small" name="state_<?php echo $sef; ?>" id="state_<?php echo $sef; ?>" value="<?php echo $row->{'state_'.$sef};?>"/>
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
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value=""  />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="id" id="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="900000000"  />
		</form>
		<script language="javascript">
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
}
?>