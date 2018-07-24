<?php
/*------------------------------------------------------------------------
# manage.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class HTML_OSappscheduleManage{
    static function listOrders($rows,$lists,$pageNav){
		global $mainframe,$configClass;
		?>
		<div class="row-fluid">
			<div class="span6">
				<div class="page-header">
					<h1><?php echo JText::_('OS_MANAGE_ORDERS');?></h1>
				</div>
			</div>
			<div class="span6 pull-right" style="text-align:right;margin-top:15px;">
				<input type="button" class="btn btn-danger" value="<?php echo JText::_('OS_REMOVE_ORDER')?>" title="<?php echo JText::_('OS_REMOVE_ORDER')?>" onclick="javascript:removeOrders()"/>
				<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_BACK')?>" title="<?php echo JText::_('OS_GO_BACK')?>" onclick="javascript:history.go(-1);"/>
			</div>
		</div>
		<form method="POST" action="<?php echo Jroute::_('index.php?option=com_osservicesbooking&view=manageallorders&Itemid='.JRequest::getInt('Itemid',0));?>" name="ftForm" id="ftForm">
			<div class="row-fluid">
				<div class="span12 pull-right">
					<strong><?php echo JText::_('OS_FILTER')?>:</strong>
					<?php echo $lists['filter_service']; ?>
					<?php echo $lists['filter_employee']; ?>
					<?php echo $lists['filter_status']; ?>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<?php 
					$optionArr = array();
					$statusArr = array(JText::_('OS_PENDING'),JText::_('OS_COMPLETED'),JText::_('OS_CANCELED'),JText::_('OS_ATTENDED'),JText::_('OS_TIMEOUT'),JText::_('OS_DECLINED'),JText::_('OS_REFUNDED'));
					$statusVarriableCode = array('P','S','C','A','T','D','R');
					for($j=0;$j<count($statusArr);$j++){
						$optionArr[] = JHtml::_('select.option',$statusVarriableCode[$j],$statusArr[$j]);				
					}
					if(count($rows) > 0){
					?>
					<table class="adminlist table table-striped" width="100%">
						<thead>
							<tr>
								<th width="2%">
									<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
								</th>
								<th width="3%">
									ID
								</th>
								<th width="15%">
									<?php echo JText::_('OS_CUSTOMER_DETAILS');?>
								</th>
								<th width="50%">
									<?php echo JText::_('OS_DETAILS');?>
								</th>
								<?php
								if($configClass['disable_payments'] == 0){
								?>
									<th width="25%">
										<?php echo JText::_('OS_ORDER_PAYMENT');?>
									</th>
								<?php
								} 
								?>
							</tr>
						</thead>
						<?php
						if($configClass['disable_payments'] == 0){
							$cols = 5;
						}else{
							$cols = 4;
						}
						?>
						<tfoot>
							<tr>
								<td width="100%" colspan="<?php echo $cols?>" style="text-align:center;">
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
						$config = new JConfig();
						$offset = $config->offset;
						date_default_timezone_set($offset);	
						for ($i=0, $n=count($rows); $i < $n; $i++) {
							$row = $rows[$i];
							$checked = JHtml::_('grid.id', $i, $row->id);
							$link 		= JRoute::_( 'index.php?option=com_osservicesbooking&task=default_orderDetailsForm&id='. $row->id.'&ref='.md5($row->id) );
							
							$db->setQuery("SELECT * FROM #__app_sch_order_items WHERE order_id = '$row->id'");
							$items = $db->loadObjectList();
							$servicesArr = array();
							for($j=0;$j<count($items);$j++){
								$item = $items[$j];
								$db->setQuery("Select * from #__app_sch_services where id = '$item->sid'");
								$s = $db->loadObject();
								$service_name = $s->service_name;
								$service_time_type = $s->service_time_type;
								
								$db->setQuery("Select id,employee_name from #__app_sch_employee where id = '$item->eid'");
								$employee_name = $db->loadObject();
								$employee_name = $employee_name->employee_name;
								$temp = $j + 1;
								$temp .= ". ".$service_name." [".date($configClass['date_format'],$item->start_time)." ".date($configClass['time_format'],$item->start_time)." - ".date($configClass['date_format'],$item->end_time)." ".date($configClass['time_format'],$item->end_time)."] ".JText::_('OS_EMPLOYEE').": ".$employee_name."";
								if($service_time_type == 1){
									$temp .= ". ".JText::_('OS_NUMBER_SLOT').": ".$item->nslots;
								}
								$servicesArr[] = $temp;
								
							}
							$service = implode("<BR />",$servicesArr);
							?>
							<tr class="<?php echo "row$k"; ?>">
								<td align="center"><?php echo $checked; ?></td>
								<td align="left"><a href="<?php echo $link; ?>"><?php echo str_pad($row->id,6,'000000',0); ?></a></td>
								<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->order_name; ?></a>
									<BR />
									<a href="mailto:<?php echo $row->order_email?>" target="_blank"><?php echo $row->order_email?></a></td>
								<td align="left" >
								<?php 
								echo "<span style='color:gray;'>".JText::_('OS_CURRENT_STATUS').": <strong>".OSBHelper::orderStatus(0,$row->order_status)."</strong>";
								?>
								<BR />
								<?php 
								echo JText::_('OS_ORDER_DATE')." <strong>".date($configClass["date_time_format"],strtotime($row->order_date));?></strong></span>
								<BR />
								<font style="font-size:11px;"><?php echo $service?></font></td>
								<?php
								if($configClass['disable_payments'] == 0){
								?>
									<td align="left" style="font-size:11px;">
									<?php
										echo JText::_('OS_TOTAL').": ".OSBHelper::showMoney($row->order_final_cost,1);
									?>
									<br />
									<?php
										echo JText::_('OS_DISCOUNT').": ".OSBHelper::showMoney($row->order_discount,1);
									?>
									<br />
									<?php
										echo JText::_('OS_DEPOSIT').": ".OSBHelper::showMoney($row->order_upfront,1);
									?>
									<br />
									<?php 
									$order_payment = $row->order_payment;
									if($order_payment != ""){
										echo Jtext::_('OS_PAYMENT')." <strong>".JText::_(os_payments::loadPaymentMethod($order_payment)->title)."</strong>";
									}
								?></td>
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
					<?php 
					}else{
						?>
						<div class="alert alert-no-items"><?php echo Jtext::_('OS_NO_MATCHING_RESULTS');?></div>
						<?php 
					}?>
					<input type="hidden" name="option" value="com_osservicesbooking" />
					<input type="hidden" name="task" value="manage_orders" />
					<input type="hidden" name="boxchecked" value="0" />
					<input type="hidden" name="live_site" id="live_site" value="<?php echo Juri::root();?>" />
					<input type="hidden" name="current_order_id" id="current_order_id" value="" />
				</div>
			</div>
		</form>
		<?php
	}
}
?>