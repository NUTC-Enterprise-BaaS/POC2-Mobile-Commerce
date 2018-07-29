<?php
/*------------------------------------------------------------------------
# fields.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class OSappscheduleFields{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));	
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."components/com_osservicesbooking/js/ajax.js");
		switch ($task){
			default:
			case "fields_list":
				OSappscheduleFields::field_list($option);
			break;
			case "fields_edit":
				OSappscheduleFields::field_edit($option,$cid[0]);
			break;
			case "fields_add":
				OSappscheduleFields::field_edit($option,0);
			break;
			case "fields_apply":
				OSappscheduleFields::fields_save($option,0);
			break;
			case "fields_save":
				OSappscheduleFields::fields_save($option,1);
			break;
			case "fields_publish":
				OSappscheduleFields::fields_state($option,$cid,1);
			break;	
			case "fields_unpublish":
				OSappscheduleFields::fields_state($option,$cid,0);
			break;
			case "fields_requiredpublish":
				OSappscheduleFields::fields_requiredstate($option,$cid,1);
			break;	
			case "fields_requiredunpublish":
				OSappscheduleFields::fields_requiredstate($option,$cid,0);
			break;	
			case "fields_remove":
				OSappscheduleFields::fields_remove($option,$cid);
			break;
			case "fields_addOption":
				OSappscheduleFields::addFieldOption();
			break;
			case "fields_removeFieldOption":
				OSappscheduleFields::removeFieldOption();
			break;
			case "fields_editOption":
				OSappscheduleFields::saveEidtOption();
			break;
			case "fields_saveorder":
				OSappscheduleFields::saveOrder($option);
			break;
			case "fields_orderdown":
				OSappscheduleFields::orderdown($option);
			break;
			case "fields_orderup":
				OSappscheduleFields::orderup($option);
			break;
			case "fields_cancel":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=fields_list");
			break;
		}
	}
	
	/**
	 * Save order
	 *
	 * @param unknown_type $option
	 */
	function saveorder($option){
		global $mainframe;
		$db = JFactory::getDBO();
		$msg = JText::_( 'New ordering saved' );
		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		
		$row = &JTable::getInstance('Field','OsAppTable');
		// update ordering values
		for( $i=0; $i < count($cid); $i++ ){
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->field_area;
			if ($row->ordering != $order[$i]){
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$msg = $db->getErrorMsg();
					return false;
				}
			}
		}
		
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
			$row->reorder(' field_area = '.(int) $group.' AND published = 1');
		}
		// execute updateOrder
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=fields_list",$msg);
	}
	
	/**
	 * Order down
	 *
	 * @param unknown_type $option
	 */
	function orderdown($option){
		global $mainframe,$_jversion;
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect(
				'index.php?option=com_osservicesbooking&task=fields_list',
				JText::_('OS_NO_ITEM_SELECTED')
			);
			return false;
		}

		if (OSappscheduleFields::orderItem($id, 1)) {
			$msg = JText::_( 'OS_MENU_ITEM_MOVED_DOWN' );
		} else {
			$msg = $model->getError();
		}
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=fields_list",$msg);
	}
	
	/**
	 * Order down
	 *
	 * @param unknown_type $option
	 */
	function orderup($option){
		global $mainframe,$_jversion;
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect(
				'index.php?option=com_osservicesbooking&task=fields_list',
				JText::_('OS_NO_ITEM_SELECTED')
			);
			return false;
		}

		if (OSappscheduleFields::orderItem($id, -1)) {
			$msg = JText::_( 'OS_MENU_ITEM_MOVED_DOWN' );
		} else {
			$msg = $model->getError();
		}
		
		$mainframe->redirect("index.php?option=com_osservicesbooking&task=fields_list",$msg);
	}
	
	/**
	 * Order Item
	 *
	 * @param unknown_type $item
	 * @param unknown_type $movement
	 * @return unknown
	 */
	function orderItem($item, $movement){
		global $mainframe;
		
		$row = &JTable::getInstance('Field','OsAppTable');
		$row->load( $item );
		if (!$row->move( $movement, ' field_area = '.(int) $row->field_area )) {
			$this->setError($row->getError());
			return false;
		}
		$row->reorder(' field_area = '.$row->field_area.' AND published = 1');
		return true;
	}
	
	function saveEidtOption(){
		global $mainframe;
		$db						= JFactory::getDbo();
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."tables".DS."fieldoption.php");
		$optionid				= JRequest::getVar('optionid',0);
		$field_id				= JRequest::getVar('field_id',0);
		$field_option			= JRequest::getVar('field_option','');
		$additional_price		= JRequest::getVar('additional_price','');
		$ordering				= JRequest::getInt('ordering',0);
		$row					= &JTable::getInstance('FieldOption','OsAppTable');
		$row->id				= $optionid;
		$row->field_id			= $field_id;
		$row->field_option		= $field_option;
		$row->additional_price	= $additional_price;
		$row->ordering			= $ordering;
		$row->store();
		$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field_id'");
		$fields					= $db->loadObjectList();
		OSappscheduleFields::showFieldOptions($fields);
		exit();
	}
	
	/**
	 * Remove field option
	 *
	 */
	function removeFieldOption(){
		global $mainframe;
		$db = JFactory::getDbo();
		$field_id = JRequest::getVar('field_id',0);
		$db->setQuery("Select field_id from #__app_sch_field_options where id = '$field_id'");
		$fid = $db->loadResult();
		$db->setQuery("Delete from #__app_sch_field_options where id = '$field_id'");
		$db->query();
		$db->setQuery("Select * from #__app_sch_field_options where field_id = '$fid'");
		$fields = $db->loadObjectList();
		OSappscheduleFields::showFieldOptions($fields);
		exit();
	}
	
	/**
	 * Add Field Option
	 *
	 */
	function addFieldOption(){
		global $mainframe;
		$db						= JFactory::getDbo();
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."tables".DS."fieldoption.php");
		$field_id				= JRequest::getVar('field_id',0);
		$db->setQuery("Select ordering from #__app_sch_field_options order by ordering desc");
		$ordering				= $db->loadResult();
		$ordering				= (int) $ordering + 1;
		$field_option			= JRequest::getVar('field_option','');
		$additional_price		= JRequest::getVar('additional_price','');
		$row					= &JTable::getInstance('FieldOption','OsAppTable');
		$row->id				= 0;
		$row->field_id			= $field_id;
		$row->field_option		= $field_option;
		$row->additional_price	= $additional_price;
		$row->ordering			= $ordering;
		$row->store();
		$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field_id'");
		$fields					= $db->loadObjectList();
		OSappscheduleFields::showFieldOptions($fields);
		exit();
	}
	
	function showFieldOptions($fields){
		?>
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
		<?php
	}
	
	/**
	 * Manage Options
	 *
	 * @param unknown_type $field_id
	 */
	function manageOptions($field_id){
		global $mainframe;
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_field_options where field_id = '$field_id' order by ordering");
		$fields = $db->loadObjectList();
		HTML_OsAppscheduleFields::manageOptions($field_id,$fields);
	}
	
	/**
	 * Field list
	 *
	 * @param unknown_type $option
	 */
	function field_list($option){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		$filter_order 				= Jrequest::getVar('filter_order','field_area, ordering');
		$filter_order_Dir 			= Jrequest::getVar('filter_order_Dir','');
		$lists['order'] 			= $filter_order;
		$lists['order_Dir'] 		= $filter_order_Dir;
		$order_by 					= " ORDER BY $filter_order $filter_order_Dir";
		$limit = JRequest::getVar('limit',20);
		$limitstart = JRequest::getVar('limitstart',0);
		$field_area = JRequest::getVar('field_area','');
		if($field_area != ""){
			$sql = " WHERE field_area = '$field_area'";
		}else{
			$sql = "";
		}
		$db->setQuery("Select count(id) from #__app_sch_fields $sql");
		$total = $db->loadResult();
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total,$limitstart,$limit);
		$query = "Select * from #__app_sch_fields $sql $order_by";
		$db->setQuery($query,$pageNav->limitstart,$pageNav->limit);
		$rows = $db->loadObjectList();
		
		if(count($rows) > 0){
			for($i=0;$i<count($rows);$i++){
				$row = $rows[$i];
				if($row->field_area == 0){
					$query = "Select b.service_name from #__app_sch_service_fields as a inner join #__app_sch_services as b on b.id = a.service_id where a.field_id = '$row->id'";
					$db->setQuery($query);
					$serviceArr = array();
					$services = $db->loadObjectList();
					if(count($services ) > 0){
						for($j=0;$j<count($services);$j++){
							$serviceArr[] = $services[$j]->service_name;
						}
						$service = implode(", ",$serviceArr);
					}
					$rows[$i]->service = $service;
				}
			}
		}
		
		$typeArea[] = JHTML::_('select.option','',JText::_('OS_ANY'));
		$typeArea[] = JHTML::_('select.option','0',JText::_('OS_SERVICES'));
		$typeArea[] = JHTML::_('select.option','1',JText::_('OS_BOOKING_FORM'));
		$lists['field_area'] = JHTML::_('select.genericlist',$typeArea,'field_area','onChange="javascript:document.adminForm.submit();" class="inputbox"','value','text',$field_area);
		
		HTML_OsAppscheduleFields::listFields($option,$rows,$pageNav,$lists);
	}
	
	/**
	 * Field edit
	 *
	 * @param unknown_type $option
	 * @param unknown_type $id
	 */
	function field_edit($option,$id){
		global $mainframe,$languages;
		$db = JFactory::getDbo();
		$row = &JTable::getInstance('Field','OsAppTable');
		if($id > 0){
			$row->load((int)$id);
		}else{
			$row->published = 1;
		}
		// creat published
		$lists['published'] = JHtml::_('select.booleanlist','published','class="inputbox"',$row->published);
		$lists['required'] = JHtml::_('select.booleanlist','required','class="inputbox"',$row->required);
		$typeArr[] = JHTML::_('select.option','0',JText::_('OS_TEXTFIELD'));
		$typeArr[] = JHTML::_('select.option','1',JText::_('OS_SELECTLIST'));
		$typeArr[] = JHTML::_('select.option','2',JText::_('OS_CHECKBOXES'));
		$lists['field_type'] = JHTML::_('select.genericlist',$typeArr,'field_type','onChange="javascript:showOptions()" class="inputbox"','value','text',$row->field_type);
		
		$typeArea = array();
		if((intval($id) > 0) or ($row->field_type > 0)){
			$typeArea[] = JHTML::_('select.option','0',JText::_('OS_SERVICES'));
		}
		$typeArea[] = JHTML::_('select.option','1',JText::_('OS_BOOKING_FORM'));
		$lists['field_area'] = JHTML::_('select.genericlist',$typeArea,'field_area','onChange="javascript:showDiv()" class="inputbox"','value','text',$row->field_area);
		
		$db->setQuery("Select id as value, service_name as text from #__app_sch_services order by service_name");
		$services = $db->loadObjectList();
		
		$db->setQuery("Select service_id from #__app_sch_service_fields where field_id = '$row->id'");
		$serviceids = $db->loadObjectList();
		$serviceArr = array();
		for($i=0;$i<count($serviceids);$i++){
			$serviceArr[] = $serviceids[$i]->service_id;
		}
		
		$lists['services'] = JHTML::_('select.genericlist',$services,'service_id[]','multiple','value','text',$serviceArr);
		
		$fields = array();
		if($id > 0){
			$db->setQuery("Select a.* from #__app_sch_field_options as a inner join #__app_sch_fields as b on b.id = a.field_id where a.field_id = '$id' and b.field_type in (1,2)");
			$fields = $db->loadObjectList();
		}
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		HTML_OSappscheduleFields::field_edit($option,$row,$lists,$fields,$translatable);
	}
	
	
	function fields_save($option,$save){
		global $mainframe,$languages;
		$db = JFactory::getDbo();
		$field_area = JRequest::getInt('field_area',0);
		$post = JRequest::get('post',JREQUEST_ALLOWHTML);
		$row = &JTable::getInstance('Field','OsAppTable');
		$row->bind($post);
		$row->store();
		$id = JRequest::getVar('id',0);
		if($id == 0){
			$id = $db->insertid();
		}
		
		$db->setQuery("Select a.* from #__app_sch_field_options as a inner join #__app_sch_fields as b on b.id = a.field_id where a.field_id = '$id' and b.field_type in (1,2)");
		$fields = $db->loadObjectList();
		
		$translatable = JLanguageMultilang::isEnabled() && count($languages);
		if($translatable){
			foreach ($languages as $language){	
				$sef = $language->sef;
				$field_label_language = JRequest::getVar('field_label_'.$sef,'');
				if($field_label_language == ""){
					$field_label_language = $row->field_label;
				}
				if($field_label_language != ""){
					$field = &JTable::getInstance('Field','OsAppTable');
					$field->id = $row->id;
					$field->{'field_label_'.$sef} = $field_label_language;
					$field->store();
				}
				
				foreach ($fields as $field){
					$option_id = $field->id;
					$option_name = "field_option_".$sef."_".$option_id;
					$option_value = JRequest::getVar($option_name,'');
					if($option_value == ""){
						$option_value = $field->field_option;
					}
					$option_name = "field_option_".$sef;
					$db->setQuery("Update #__app_sch_field_options set `$option_name` = '".htmlspecialchars($option_value)."' where id = '$option_id'");
					$db->query();
				}
			}
		}


		$service_id = JRequest::getVar('service_id');
		$db->setQuery("Delete from #__app_sch_service_fields where field_id = '$id'");
		$db->query();
		if($field_area == 0){
			if($row->field_area == 0){
				if(count($service_id) > 0){
					for($i=0;$i<count($service_id);$i++){
						$sid = $service_id[$i];
						$db->setQuery("Insert into #__app_sch_service_fields (id,field_id,service_id) values (NULL,'$id','$sid')");
						$db->query();
					}
				}
			}
		}
		
		if($save){
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=fields_list",JText::_('OS_FIELD_HAS_BEEN_SAVED'));
		}else{
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=fields_edit&cid[]=".$id,JText::_('OS_FIELD_HAS_BEEN_SAVED'));
		}
	}
	
	/**
	 * publish or unpublish agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 * @param unknown_type $state
	 */
	function fields_state($option,$cid,$state){
		global $mainframe;
		$mainframe 	= JFactory::getApplication();
		$db 		= JFactory::getDBO();
		if(count($cid)>0)	{
			$cids 	= implode(",",$cid);
			$db->setQuery("UPDATE #__app_sch_fields SET `published` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED"),'message');
		OSappscheduleFields::field_list($option);
	}

	function fields_requiredstate($option,$cid,$state){
		global $mainframe;
		$mainframe 	= JFactory::getApplication();
		$db 		= JFactory::getDBO();
		if(count($cid)>0)	{
			$cids 	= implode(",",$cid);
			$db->setQuery("UPDATE #__app_sch_fields SET `required` = '$state' WHERE id IN ($cids)");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_STATUS_HAS_BEEN_CHANGED"),'message');
		OSappscheduleFields::field_list($option);
	}
	
	/**
	 * remove agent
	 *
	 * @param unknown_type $option
	 * @param unknown_type $cid
	 */
	function fields_remove($option,$cid){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		if(count($cid)>0)	{
			$cids = implode(",",$cid);
			$db->setQuery("DELETE FROM #__app_sch_fields WHERE id IN ($cids)");
			$db->query();
			
			$db->setQuery("DELETE FROM #__app_sch_service_fields WHERE field_id IN ($cids)");
			$db->query();
			
			$db->setQuery("DELETE FROM #__app_sch_field_data WHERE fid IN ($cids)");
			$db->query();
		}
		$mainframe->enqueueMessage(JText::_("OS_ITEMS_HAS_BEEN_DELETED"),'message');
		OSappscheduleFields::field_list($option);
	}
}
?>