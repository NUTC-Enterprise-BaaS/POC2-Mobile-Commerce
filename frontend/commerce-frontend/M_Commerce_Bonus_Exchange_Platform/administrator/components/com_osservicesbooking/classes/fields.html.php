<?php
/*------------------------------------------------------------------------
# fields.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;
class HTML_OSappscheduleFields{
	function listFields($option,$rows,$pageNav,$lists){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_CUSTOM_FIELDS_MANAGEMENT'));
		JToolBarHelper::addNew('fields_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('fields_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'fields_remove');
			JToolBarHelper::publish('fields_publish');
			JToolBarHelper::unpublish('fields_unpublish');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osservicesbooking&task=fields_list" name="adminForm" id="adminForm">
			<table  width="100%">
				<tr>
					<td width="100%" align="right" style="padding-top:5px;">
						<b><?php echo JText::_('OS_FIELD_AREA');?>: <?php echo $lists['field_area'];?></b>
					</td>
				</tr>
			</table>
			<table width="100%" class="adminlist table table-striped">
				<thead>
					<tr>
						<th width="2%">#</th>
						<th width="3%">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="20%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_CUSTOM_FIELD'), 'field_label', @$lists['order_Dir'], @$lists['order'],'fields_list' ); ?>
						</th>
						<th width="25%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_SERVICES'), 'service_id', @$lists['order_Dir'], @$lists['order'],'fields_list' ); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_FIELD_AREA'), 'field_area', @$lists['order_Dir'], @$lists['order'],'fields_list' ); ?>
						</th>
						<th width="10%">
							
							<?php echo JHTML::_('grid.sort',   JText::_('OS_FIELD_TYPE'), 'field_type', @$lists['order_Dir'], @$lists['order'],'fields_list' ); ?>
						</th>
						<th width="15%" style="text-align:center;">
							<?php echo JText::_('OS_ORDERING')?>
							<?php echo JHTML::_('grid.order',  $rows ,"filesave.png","fields_saveorder"); ?>
						</th>
						<th width="5%"  style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_STATUS'), 'published', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
						<th width="5%"  style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_REQUIRED'), 'required', @$lists['order_Dir'], @$lists['order'] ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="8" style="text-align:center;">
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
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=fields_edit&cid[]='. $row->id );
					$published 	= JHTML::_('jgrid.published', $row->published, $i , 'fields_');
					$required 	= JHTML::_('jgrid.published', $row->required, $i , 'fields_required');

					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center"><?php echo $checked; ?></td>
						<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->field_label; ?></a></td>
						<td align="left" style="padding-right: 10px;"><?php echo $row->service;?></td>
						<td align="left" style="padding-right: 10px;">
						<?php
						switch ($row->field_area){
							case "0":
								echo JText::_('OS_SERVICES');
							break;
							case "1":
								echo JText::_('OS_BOOKING_FORM');
							break;
						}
						?>
						</td>
						<td align="center">
						<?php
						switch ($row->field_type){
							case "0":
								echo JText::_('OS_TEXTFIELD');
							break;
							case "1":
								echo JText::_('OS_SELECTLIST');
							break;
							case "2":
								echo JText::_('OS_CHECKBOXES');
							break;
							
						}
						?>
						</td>
						<td align="center" class="order">
							<?php
							$ordering = "ordering";
							?>
				 			<span><?php echo $pageNav->orderUpIcon($i,$row->parent_id == 0 || $row->parent_id == @$rows[$i-1]->parent_id, 'fields_orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
							<span><?php echo $pageNav->orderDownIcon($i, $n,  $row->parent_id == 0 || $row->parent_id == @$rows[$i+1]->parent_id, 'fields_orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="input-mini" style="text-align: center;width:20px;" />
							
						</td>
						<td align="center" style="text-align:center;"><?php echo $published?></td>
						<td align="center" style="text-align:center;"><?php echo $required?></td>
					</tr>
				<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="com_osservicesbooking" />
			<input type="hidden" name="task" value="fields_list" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>" />
		</form>
		<?php
	}
	
	
	/**
	 * Edit field
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function field_edit($option,$row,$lists,$fields,$translatable){
		global $mainframe, $languages,$configClass;
		$version 	= new JVersion();
		$_jversion	= $version->RELEASE;		
		$mainframe 	= JFactory::getApplication();
		JRequest::setVar( 'hidemainmenu', 1 );
		if ($row->id > 0){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('Custom field').$title);
		JToolBarHelper::save('fields_save');
		JToolBarHelper::apply('fields_apply');
		JToolBarHelper::cancel('fields_cancel');
		?>
		<script language="javascript">
		function showDiv(){
			var field_area = document.getElementById('field_area');
			var service_div = document.getElementById('service_div');
			if(field_area.value == 0){
				service_div.style.display = "block";
			}else{
				service_div.style.display = "none";
			}
		}
		function showOptions(){
			var field_type = document.getElementById('field_type');
			var service_div = document.getElementById('other_info');
			var service_div1 = document.getElementById('service_div');
			if((field_type.value == 1) || (field_type.value == 2)){
				service_div.style.display = "block";
				service_div1.style.display = "block";
			}else{
				service_div.style.display = "none";
				service_div1.style.display = "none";
			}
			
			var field_area = document.getElementById('field_area');
			if(field_type.value == 0){
				var len = field_area.options.length;
				field_area.options[0] = null;
				field_area.options[1] = null;
				field_area.options[0] = null;
				field_area.options[1] = null;
				
				var option = document.createElement("option");
				option.text = "<?php echo JText::_('OS_BOOKING_FORM');?>";
				option.value = "1";
				field_area.appendChild(option);
				
				//service_div.style.display = "none";
			}else{
				var len = field_area.options.length;
				field_area.options[0] = null;
				field_area.options[1] = null;
				field_area.options[0] = null;
				field_area.options[1] = null;
				
				var option = document.createElement("option");
				option.text = "<?php echo JText::_('OS_SERVICES');?>";
				option.value = "0";
				field_area.appendChild(option);
				var option = document.createElement("option");
				option.text = "<?php echo JText::_('OS_BOOKING_FORM');?>";
				option.value = "1";
				field_area.appendChild(option);
				
				//service_div.style.display = "block";
			}
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
					<td class="key"><?php echo JText::_('OS_FIELD'); ?>: </td>
					<td >
						<input type="text" name="field_label" id="field_label" size="40" value="<?php echo $row->field_label?>" >
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_FIELD_TYPE'); ?>: </td>
					<td >
						<?php echo $lists['field_type'];?>
					</td>
				</tr>
				<tr>
					<td class="key" valign="top"><?php echo JText::_('OS_SELECT_FIELD_AREA'); ?>: 
					<BR />
					<font style="font-weight:normal !important;color:red;font-size:11px;">
					<?php echo JText::_('OS_SELECT_FIELD_AREA_EXPLAIN'); ?>
					</font>
					</td>
					<td valign="top">
						<?php echo $lists['field_area'];?>
						<?php
						if($row->id == 0){
							$display = "none";
						}else{
							if(($row->field_area == 1) or ($row->field_type == 0)){
								$display = "none";
							}else{
								$display = "block";
							}
						}
						?>
						<div id="service_div" style="display:<?php echo $display?>;">
							<?php echo Jtext::_('OS_SELECT_SERVICES');?>
							<br />
							<?php echo $lists['services']; ?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_REQUIRED'); ?>: </td>
					<td width="80%"><?php echo $lists['required'];?></td>
				</tr>
				<tr>
					<td class="key"><?php echo JText::_('OS_PUBLISHED'); ?>: </td>
					<td width="80%"><?php echo $lists['published'];?></td>
				</tr>
				<tr>
					<td class="key" valign="top"><?php echo JText::_('OS_OTHER_INFORMATION'); ?>:
					<BR />
					<font style="color:gray;font-weight:normal !important;font-size:11px;">
					<?php
						echo "[".JText::_('OS_SELECTLIST').",".JText::_('OS_CHECKBOXES')."]";
					?>	
					</font>
					</td>
					<td width="80%">
						<?php
						if(($row->field_type == 1) or ($row->field_type == 2)){
							$display = "block";
						}else{
							$display = "none";
						}
						?>
						<div id="other_info" style="display:<?php echo $display?>;">
							<table  width="100%">
								<tr>
									<td width="100%" valign="top" align="left">
										<?php
										if($row->id == 0){
											echo JText::_('OS_AFTER_SAVING_YOU_CAN_MANAGE_OPTIONS_FOR_THIS_FIELD');
										}else{
											OSappscheduleFields::manageOptions($row->id);
										}
										?>
									</td>
								</tr>
							</table>
						</div>
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
										<td class="key"><?php echo JText::_('OS_FIELD'); ?>: </td>
										<td >
											<input type="text" name="field_label_<?php echo $sef; ?>" id="field_label_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'field_label_'.$sef};?>" />
										</td>
									</tr>
									<?php 
									if(count($fields) > 0){
										foreach ($fields as $field){
											?>
											<tr>
												<td class="key"><?php echo $field->field_option; ?>: </td>
												<td >
													<input type="text" name="field_option_<?php echo $sef; ?>_<?php echo $field->id;?>" id="field_option_<?php echo $sef; ?>_<?php echo $field->id;?>" size="40" value="<?php echo $field->{'field_option_'.$sef};?>" />
												</td>
											</tr>
											<?php 
										}
									}
									?>
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
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::base()?>" />
		</form>
		<?php
	}
	
	/**
	 * Manage options of Field
	 *
	 * @param unknown_type $field_id
	 * @param unknown_type $fields
	 */
	function manageOptions($field_id,$fields){
		global $mainframe,$configClass;
		JHTML::_('behavior.tooltip');
		?>
		<table width="100%" class="admintable">
			<tr>
				<td colspan="2" class="key" style="text-align:left;">
					<?php echo JText::_('OS_NEW_OPTION');?>
				</td>
			</tr>
			<tr>
				<td class="key">
					<span title="<?php echo JText::_('OS_FIELD_OPTION')?>::<?php echo JText::_('OS_FIELD_OPTION_EXPLAIN')?>" class="hasTip">
					<?php echo JText::_('OS_FIELD_OPTION')?>
					</span>
				</td>
				<td>
					<input type="text" class="inputbox" name="field_option" id="field_option">
				</td>
			</tr>
			<tr>
				<td class="key">
					<span title="<?php echo JText::_('OS_ADDITIONAL_PRICE')?>::<?php echo JText::_('OS_ADDITIONAL_PRICE_EXPLAIN')?>" class="hasTip">
					<?php echo JText::_('OS_ADDITIONAL_PRICE')?>
					</span>
				</td>
				<td>
					<input type="text" class="inputbox" name="additional_price" id="additional_price" size="5"> <?php echo $configClass['currency_format'];?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="key" style="text-align:left;">
					<input type="button" class="btn btn-warning" value="<?php echo JText::_("OS_SAVE")?>" onclick="javascript:saveNewOption();">
					<input type="button" class="btn btn-info" value="<?php echo JText::_("OS_RESET")?>" onclick="javascript:resetOption();">
				</td>
			</tr>
			
		</table>
		<div id="field_option_div">
		<table width="100%" class="adminlist" style="font-size:12px;">
			<thead>
				<tr>
					<th width="2%" align="center">
						#
					</th>
					<th width="30%" align="center">
						<?php echo JText::_('OS_FIELD_OPTION')?>
					</th>
					<th width="25%" align="center">
						<?php echo JText::_('OS_ADDITIONAL_PRICE')?>
					</th>
					<th width="13%" align="center">
						<?php echo JText::_('OS_ORDERING')?>
					</th>
					<th width="5%" align="center">
						<?php echo JText::_('OS_REMOVE')?>
					</th>
					<th width="5%" align="center">
						<?php echo JText::_('OS_SAVE')?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$k = 0;
				for($i=0;$i<count($fields);$i++){
					$field = $fields[$i];
					?>
					<tr class="rows<?php echo $k?>">
						<td style="text-align:center;">
							<?php echo $i+1;?>
						</td>
						<td  style="text-align:center;">
							<input type="text" class="input-large " name="field_option<?php echo $field->id?>" id="field_option<?php echo $field->id?>" value="<?php echo $field->field_option?>" />
						</td>
						<td style="text-align:center;">
							<input type="text" class="input-mini" name="additional_price<?php echo $field->id?>" id="additional_price<?php echo $field->id?>" value="<?php echo $field->additional_price?>" size="5" /> <?php echo $configClass['currency_format'];?>
						</td>
						<td style="text-align:center;">
							<input type="text" class="input-mini" name="ordering<?php echo $field->id?>" id="ordering<?php echo $field->id?>" value="<?php echo $field->ordering; ?>" size="5" />
						</td>
						<td style="text-align:center;">
							<a href="javascript:removeFieldOption(<?php echo $field->id?>)" title="<?php echo JText::_('OS_REMOVE_FIELD_OPTION');?>">
								<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-delete.png">
							</a>
						</td>
						<td style="text-align:center;">
							<a href="javascript:saveFieldOption(<?php echo $field->id?>)" title="<?php echo JText::_('OS_SAVE_FIELD_OPTION');?>">
								<img src="<?php echo JURI::base()?>templates/hathor/images/menu/icon-16-checkin.png">
							</a>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		</div>
		<script language="javascript">
		function saveFieldOption(field_id){
			var field_option     = document.getElementById('field_option' + field_id);
			var additional_price = document.getElementById('additional_price' + field_id);
			var ordering		 = document.getElementById('ordering' + field_id);

			if(field_option.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_ENTER_FIELD_OPTION');?>");
				field_option.focus();
			}else{
				saveEditOptionAjax("<?php echo JURI::base()?>",field_option.value,additional_price.value,ordering.value,"<?php echo $field_id?>",field_id);
			}
		}
		function removeFieldOption(field_id){
			var answer = confirm("<?php echo JText::_('OS_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_FIELD_OPTION')?>");
			if(answer == 1){
				removeFieldOptionAjax("<?php echo JURI::base()?>",field_id);
			}
		}
		function saveNewOption(){
			var field_option = document.getElementById('field_option');
			var additional_price = document.getElementById('additional_price');
			if(field_option.value == ""){
				alert("<?php echo JText::_('OS_PLEASE_ENTER_FIELD_OPTION');?>");
				field_option.focus();
			}else{
				saveNewOptionAjax("<?php echo JURI::base()?>",field_option.value,additional_price.value,"<?php echo $field_id?>");
			}
		}
		function resetOption(){
			var field_option = document.getElementById('field_option');
			var additional_price = document.getElementById('additional_price');
			field_option.value = "";
			additional_price.value = "";
		}
		</script>
		<?php
	}
}
?>