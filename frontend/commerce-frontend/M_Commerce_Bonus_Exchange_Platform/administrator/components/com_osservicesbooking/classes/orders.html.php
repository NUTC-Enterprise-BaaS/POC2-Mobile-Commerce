<?php
/*------------------------------------------------------------------------
# orders.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;


class HTML_OSappscheduleOrders{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function orders_list($option,$rows,$pageNav,$lists){
		global $mainframe,$_jversion,$configClass;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_MANAGE_ORDERS'),'list');
		JToolBarHelper::addNew('orders_addnew');
		JToolBarHelper::custom('orders_sendnotify','envelope','envelope',JText::_('OS_SEND_NOTIFY_EMAIL'));
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'orders_remove');
		JtoolBarHelper::custom('orders_export','download.png','download.png',JText::_('OS_EXPORT_CSV'),false);
		JtoolBarHelper::custom('orders_dowloadInvoice','download.png','download.png',JText::_('OS_EXPORT_PDF'),true);
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osservicesbooking&task=orders_list" name="adminForm" id="adminForm">
			<div class="row-fluid">
				<div class="span12">
					<div class="span3 pull-left">
						<div class="btn-wrapper input-append">
							<input type="text" 	class="input-medium" placeholder="<?php echo JText::_('OS_SEARCH');?>" name="keyword" value="<?php echo  $lists['keyword']; ?>" />
							<button class="btn hasTooltip" title="" type="submit" data-original-title="<?php echo JText::_('OS_SEARCH');?>">
								<i class="icon-search"></i>
							</button>
						</div>	
					</div>
					<div class="span9 pull-right">
						<?php echo $lists['filter_service']; ?>
						<?php echo $lists['filter_employee']; ?>
						<?php echo $lists['filter_status']; ?>
						<?php echo JHtml::_('calendar',$lists['filter_date_from'],'filter_date_from','filter_date_from','%Y-%m-%d',array('placeholder' => JText::_('OS_FROM'),'onchange' => '', 'class' => 'input-small'));?>
						<?php echo JHtml::_('calendar',$lists['filter_date_to'],'filter_date_to','filter_date_to','%Y-%m-%d',array('placeholder' => JText::_('OS_TO'),'', 'class' => 'input-small'))?>
						<button class="btn hasTooltip" title="" type="submit" data-original-title="<?php echo JText::_('OS_SEARCH');?>">
							<i class="icon-search"></i>
						</button>
					</div>
				</div>
			</div>					
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
							<?php echo JHTML::_('grid.sort',   JText::_('ID'), 'a.id', @$lists['order_Dir'], @$lists['order'] ,'orders_list'); ?>
						</th>
						<th width="10%">
							<?php echo JText::_('OS_CUSTOMER_DETAILS');?>
						</th>
						<th width="25%">
							<?php echo JText::_('OS_SERVICES');?>
						</th>
						<?php
						if($configClass['disable_payment'] == 0){
						?>
							<th width="15%">
								<?php echo JText::_('OS_ORDER_PAYMENT');?>
							</th>
						<?php
						} 
						?>
						<th width="18%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_STATUS'), 'a.order_status', @$lists['order_Dir'], @$lists['order'] ,'orders_list'); ?>
						</th>
						<th width="10%" style="text-align:center;">
							<?php echo JHTML::_('grid.sort',   JText::_('OS_DATE'), 'a.order_date', @$lists['order_Dir'], @$lists['order'],'orders_list' ); ?>
						</th>
						<th width="8%" style="text-align:center;">
							<?php echo JText::_('OS_SENDMAIL');?>
						</th>
					</tr>
				</thead>
				<?php
				if($configClass['disable_payment'] == 0){
					$cols = 13;
				}else{
					$cols = 12;
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
					$link 		= JRoute::_( 'index.php?option=com_osservicesbooking&task=orders_detail&cid[]='. $row->id );
					
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
						<td align="left" ><font style="font-size:11px;"><?php echo $service?></font></td>
						<?php
						if($configClass['disable_payment'] == 0){
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
						<td style="text-align:center;">
							<div id="div_orderstatus<?php echo $row->id;?>">
								<?php 
								echo "<span style='color:gray;'>".JText::_('OS_CURRENT_STATUS').": <strong>".OSBHelper::orderStatus(0,$row->order_status)."</strong></span>";
								echo "<BR />";
								echo "<span style='color:gray;font-size:11px;'>".JText::_('OS_CHANGE_STATUS')."</span>";
								echo JHtml::_('select.genericlist',$optionArr,'orderstatus'.$row->id,'class="input-small"','value','text',$row->order_status);
								?>
								<a href="javascript:updateOrderStatusAjax(<?php echo $row->id;?>,'<?php echo JUri::root();?>')">
									<i class="icon-edit"></i>
								</a>
							</div>	
						</td>
						<td align="center" ><font style="font-size:11px;text-align:center;"><?php 
						echo date($configClass["date_time_format"],strtotime($row->order_date));?></font></td>
						<td style="text-align:center;">
							<?php
							if($row->send_email == 1){
								?>
								<img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/publish.png">
								<?php
							}else{
								?>
								<img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/unpublish.png">
								<?php
							}
							?>
						</td>
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
			<input type="hidden" name="task" value="orders_list" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $lists['order'];?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir'];?>" />
			<input type="hidden" name="live_site" id="live_site" value="<?php echo Juri::root();?>" />
			<input type="hidden" name="current_order_id" id="current_order_id" value="" />
		</form>
		<?php
	}
	
	
	/**
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 * @param unknown_type $lists
	 */
	function orders_detail($option,$row,$rows,$pageNav,$fields,$lists){
		global $mainframe, $_jversion,$configClass;
		$config = new JConfig();
		$offset = $config->offset;
		date_default_timezone_set($offset);	
		$version 	= new JVersion();
		$_jversion	= $version->RELEASE;		
		$mainframe 	= JFactory::getApplication();
		JRequest::setVar( 'hidemainmenu', 1 );
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_ORDER_DETAIL').$title,'list');
		JToolBarHelper::save('orders_save');
		JToolBarHelper::apply('orders_apply');
		JToolBarHelper::cancel('orders_cancel');
		?>
		<form method="POST" action="index.php?option=com_osservicesbooking&task=orders_detail&cid[]=<?php echo $row->id;?>" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<div class="row-fluid">
			<div style="width:100%;padding:20px;" class="span12 form-horizontal">
				<fieldset>
					<legend><?php echo JText::_('OS_ORDER_DETAIL')?></legend>
					<?php
					if($id > 0){
					?>
					<div class="control-group">
						<div class="control-label">
							<label title="<?php echo JText::_( 'OS_ORDER_NUMBER' );?>::<?php echo JText::_('OS_ORDER_NUMBER_DESC'); ?>" class="hasTip" ><?php echo JText::_("OS_ORDER_NUMBER"); ?></label>
						</div>
						<div class="controls">
							<span class="readonly"><?php echo str_pad($row->id,6,'000000',0); ?></span>
						</div>
					</div>
					<?php
					}
					?>
					<div class="control-group">
						<div class="control-label">					
							<label title="<?php echo JText::_( 'OS_CUSTOMER') ;?>" class="hasTip"><?php echo JText::_( 'OS_CUSTOMER') ;?></label>
						</div>
						<div class="controls">
							<?php 
							echo OSappscheduleOrders::getUserInput($row->user_id);
							?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">					
							<label title="<?php echo JText::_( 'OS_NAME') ;?>::<?php echo JText::_( 'OS_NAME_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_NAME') ;?></label>
						</div>
						<div class="controls">
							<input type="text" class="input-large" value="<?php echo $row->order_name; ?>" name="order_name" id="order_name" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">	
							<label title="<?php echo JText::_( 'OS_EMAIL') ;?>::<?php echo JText::_( 'OS_EMAIL_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_EMAIL') ;?></label>
						</div>
						<div class="controls">
							<input type="text" class="input-large" value="<?php echo $row->order_email; ?>" name="order_email" id="order_email" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">	
							<label title="<?php echo JText::_( 'OS_PHONE') ;?>::<?php echo JText::_( 'OS_PHONE_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_PHONE') ;?></label>
							</div>
						<div class="controls">
							
							<input type="text" class="input-mini" value="<?php echo $row->dial_code; ?>" name="dial_code" />
							<input type="text" class="input-small" value="<?php echo $row->order_phone; ?>" name="order_phone" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">	
							<label title="<?php echo JText::_( 'OS_COUNTRY') ;?>::<?php echo JText::_( 'OS_COUNTRY_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_COUNTRY') ;?></label>
							</div>
						<div class="controls">
							<?php echo $lists['country'];?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">	
							<label title="<?php echo JText::_( 'OS_CITY') ;?>::<?php echo JText::_( 'OS_CITY_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_CITY') ;?></label>
							</div>
						<div class="controls">
							<input type="text" class="input-large" value="<?php echo $row->order_city; ?>" name="order_city" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">	
							<label title="<?php echo JText::_( 'OS_STATE') ;?>::<?php echo JText::_( 'OS_STATE_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_STATE') ;?></label>
							</div>
						<div class="controls">
							<input type="text" class="input-large" value="<?php echo $row->order_state; ?>" name="order_state" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">	
							<label title="<?php echo JText::_( 'OS_ZIP') ;?>::<?php echo JText::_( 'OS_ZIP_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_ZIP') ;?></label>
							</div>
						<div class="controls">
							<input type="text" class="input-mini" value="<?php echo $row->order_zip; ?>" name="order_zip" />
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">	
							<label title="<?php echo JText::_( 'OS_ADDRESS') ;?>::<?php echo JText::_( 'OS_ADDRESS_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_ADDRESS') ;?></label>
							</div>
						<div class="controls">
							<input type="text" class="input-large" value="<?php echo $row->order_address; ?>" name="order_address" />
						</div>
					</div>
						<?php
						$db = JFactory::getDbo();
						$db->setQuery("Select * from #__app_sch_fields where field_area = '1' and published = '1'");
						$fields = $db->loadObjectList();
						if(count($fields) > 0){
							for($i=0;$i<count($fields);$i++){
								$field = $fields[$i];
								?>
								<div class="control-group">
									<div class="control-label">	
										<label title="<?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$row->order_lang); //$field->field_label ;?>" class="hasTip"><?php echo OSBHelper::getLanguageFieldValueOrder($field,'field_label',$row->order_lang); //$field->field_label;?></label>
										</div>
									<div class="controls">
										<span class="readonly" style="font-weight:normal !important;"> 
											<?php
											OsAppscheduleDefault::orderField($field,$row->id);
											?>
										</span>
									</div>
								</div>
								<?php
							}
						}
						?>
						<div class="control-group">
							<div class="control-label">	
								<label title="<?php echo JText::_( 'OS_NOTES') ;?>" class="hasTip"><?php echo JText::_( 'Notes') ;?></label>
								</div>
							<div class="controls">
								<textarea name="notes" class="input-large"><?php echo $row->order_notes; ?></textarea>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">	
								<label title="<?php echo JText::_( 'OS_PAYMENT') ;?>::<?php echo JText::_( 'OS_PAYMENT_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_PAYMENT') ;?></label>
								</div>
							<div class="controls">
								<?php 
								$order_payment = $row->order_payment;
								?>
								<input type="text" class="input-large" value="<?php echo JText::_(os_payments::loadPaymentMethod($order_payment)->title); ?>" name="order_payment" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label title="<?php echo JText::_( 'OS_TOTAL') ;?>::<?php echo JText::_( 'OS_TOTAL_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_TOTAL') ;?></label>
							</div>
							<div class="controls">
								<input type="text" class="input-small" value="<?php echo $row->order_total; ?>" name="order_total" /> <?php echo $configClass['currency_format'];?>
							</div>
						</div>
						<?php
						if($configClass['enable_tax']==1){
						?>
						<div class="control-group">
							<div class="control-label">	
								<label title="<?php echo JText::_( 'OS_TAX') ;?>::<?php echo JText::_( 'OS_TAX_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_TAX') ;?></label>
							</div>
							<div class="controls">
								<input type="text" class="input-small" value="<?php echo $row->order_tax; ?>" name="order_tax" /> <?php echo $configClass['currency_format'];?>
							</div>
						</div>
						<?php } ?>
						<div class="control-group">
							<div class="control-label">
								<label title="<?php echo JText::_( 'OS_DISCOUNT') ;?>" class="hasTip"><?php echo JText::_( 'OS_DISCOUNT') ;?></label>
							</div>
							<div class="controls">
								<input type="text" class="input-small" value="<?php echo $row->order_discount; ?>" name="order_discount" /> <?php echo $configClass['currency_format'];?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">	
								<label title="<?php echo JText::_( 'OS_FINAL_COST') ;?>::<?php echo JText::_( 'OS_FINAL_COST_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_FINAL_COST') ;?></label>
							</div>
							<div class="controls">
								<input type="text" class="input-small" value="<?php echo $row->order_final_cost; ?>" name="order_final_cost" /> <?php echo $configClass['currency_format'];?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">	
								<label title="<?php echo JText::_( 'OS_UPFRONT') ;?>::<?php echo JText::_( 'OR_UPFRONT_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_UPFRONT') ;?></label>
							</div>
							<div class="controls">
								<input type="text" class="input-small" value="<?php echo $row->order_upfront; ?>" name="order_upfront" /> <?php echo $configClass['currency_format'];?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">	
								<label title="<?php echo JText::_( 'OS_DATE') ;?>::<?php echo JText::_( 'OS_DATE_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_DATE') ;?></label>
							</div>
							<div class="controls">
								<?php
								echo JHTML::_('calendar',$row->order_date, 'order_date', 'order_date', '%Y-%m-%d %H:%i%ss', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19','style'=>'width:80px;'));
								?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label title="<?php echo JText::_( 'OS_STATUS') ;?>::<?php echo JText::_( 'OS_STATUS_DESC') ;?>" class="hasTip"><?php echo JText::_( 'OS_STATUS') ;?></label>
							</div>
							<div class="controls">
								<span class="readonly"> <?php echo $row->order_status_select_list; ?></span>
							</div>
						</div>
				</fieldset>
			</div>
		</div>
		<?php
		
		if($row->id > 0){
		?>
		<div class="row-fluid">
			<div class="span12 form-horizontal">
				<?php echo JText::_('OS_ORDER_DETAILS');?>:
				<a href="<?php echo JUri::root()?>index.php?option=com_osservicesbooking&task=default_orderDetailsForm&id=<?php echo $row->id?>&ref=<?php echo md5($row->id);?>" target="_blank">
					<?php echo JUri::root()?>index.php?option=com_osservicesbooking&task=default_orderDetailsForm&id=<?php echo $row->id?>&ref=<?php echo md5($row->id);?>
				</a>
				<BR />
				<?php echo JText::_('OS_CANCEL_LINK');?>:
				<a href="<?php echo JUri::root()?>index.php?option=com_osservicesbooking&task=default_cancelorder&id=<?php echo $row->id?>&ref=<?php echo md5($row->id);?>" target="_blank">
					<?php echo JUri::root()?>index.php?option=com_osservicesbooking&task=default_cancelorder&id=<?php echo $row->id?>&ref=<?php echo md5($row->id);?>
				</a>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12 form-horizontal">
				<fieldset>
					<legend><?php echo JText::_('OS_SERVICELIST')?></legend>
					<div class="navbar">
						<div class="navbar-inner" style="text-align:right;">
							<a href="index.php?option=com_osservicesbooking&task=orders_addservice&order_id=<?php echo $row->id?>" style="color:white;font-weight:bold;" class="btn btn-info">Add service</a>
						</div>
					</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th width="20" align="left">
									<?php echo JText::_( '#' ); ?>
								</th>
								<th class="title" width="15%">
									<?php echo JText::_('OS_SERVICES');?>
								</th>
								<th class="title" width="15%">
									<?php echo JText::_('OS_EMPLOYEE');?>
								</th>
								<th width="15%">
									<?php echo JText::_('OS_WORKTIME_START_TIME');?>
								</th>
								<th width="15%">
									<?php echo JText::_('OS_WORKTIME_END_TIME');?>
								</th>
								<th width="15%">
									<?php echo JText::_('OS_DATE');?>
								</th>
								<th width="25%">
									<?php echo JText::_('OS_OTHER_INFORMATION');?>
								</th>
                                <th width="5%">
                                    <?php echo JText::_('OS_CHECKED_IN');?>
                                </th>
								<th width="5%">
									<?php echo JText::_('OS_REMOVE');?>
								</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="9">
									<?php echo $pageNav->getListFooter(); ?>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<?php
							$config = new JConfig();
							$offset = $config->offset;
							date_default_timezone_set($offset);
							$k = 0;
							if( count( $rows ) ) {
							for ($i=0, $n=count( $rows ); $i < $n; $i++) {
								$item = &$rows[$i];
							?>
								<tr class="<?php echo "row$k"; ?>">
									<td >
										<?php echo $pageNav->getRowOffset( $i ); ?>
									</td>
									<td style="padding-left:10px;text-align:left;"><?php echo $item->service_name?></td>
									<td style="padding-left:10px;text-align:left;"><?php echo $item->employee_name?></td>
									<td align="center"><?php echo date($configClass['time_format'],$item->start_time); ?></td>
									<td align="center"><?php echo date($configClass['time_format'],$item->end_time); ?></td>
									<td align="center"><?php echo date($configClass['date_format'],strtotime($item->booking_date)) ; ?></td>
									<td align="left">
										<?php
										if($item->service_time_type ==1){
											echo JText::_('OS_NUMBER_SLOT').": ".$item->nslots."<BR />";
										}
										$db->setQuery("Select * from #__app_sch_fields where field_area = '0' and published = '1'");
										$fields = $db->loadObjectList();
										if(count($fields) > 0){
											for($i1=0;$i1<count($fields);$i1++){
												$field = $fields[$i1];
												$db->setQuery("Select count(id) from #__app_sch_order_field_options where order_item_id = '$item->id' and field_id = '$field->id'");
												$count = $db->loadResult();
												if($count > 0){
													if($field->field_type == 1){
														$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$item->id' and field_id = '$field->id'");
														$option_id = $db->loadResult();
														$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
														$optionvalue = $db->loadObject();
														?>
														<?php echo $field->field_label;?>:
														<?php
														$field_data = $optionvalue->field_option;
														if($optionvalue->additional_price > 0){
															$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
														}
														echo $field_data;
														echo "<BR />";
													}elseif($field->field_type == 2){
														$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$item->id' and field_id = '$field->id'");
														$option_ids = $db->loadObjectList();
														$fieldArr = array();
														for($j=0;$j<count($option_ids);$j++){
															$oid = $option_ids[$j];
															$db->setQuery("Select * from #__app_sch_field_options where id = '$oid->option_id'");
															$optionvalue = $db->loadObject();
															$field_data = $optionvalue->field_option;
															if($optionvalue->additional_price > 0){
																$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
															}
															$fieldArr[] = $field_data;
														}
														?>
														<?php echo $field->field_label;?>:
														<?php
														echo implode(", ",$fieldArr);
														echo "<BR />";
													}
												}
											}
										}
										?>
									</td>
                                    <td width="10%" style="text-align:center;">
                                        <?php
                                        if($item->checked_in == 1){
                                            ?>
                                            <img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/publish.png" />
                                            <?php
                                        }else{
                                            ?>
                                            <img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/unpublish.png" />
                                            <?php
                                        }
                                        ?>
                                    </td>
									<td width="10%" style="text-align:center;">
										<a href="javascript:removeService(<?php echo $item->id?>,<?php echo $row->id?>);" title="<?php echo JText::_('OS_REMOVE_ORDER_ITEM');?>">
											<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/unpublish.png" />
										</a>
									</td>
								</tr>
							<?php }
                            }
                            ?>
						</tbody>
					</table>
				</fieldset>
			</div>
		</div>
		<?php
		}else{
			?>
			<div class="row-fluid">
				<div class="span12 form-horizontal">
					<fieldset>
						<legend><?php echo JText::_('OS_SERVICELIST')?></legend>
					    <div class="alert alert-block">
						    <h4><?php echo JText::_('OS_NOTICE')?></h4>
						    <?php
								echo JText::_('OS_YOU_CAN_ONLY_ADD_SERVICES_AFTER_SAVING_ORDER_DETAILS');
							?>
						</div>
					</fieldset>
				</div>
			</div>
			<?php
		}
		?>
		<input type="hidden" name="option" value="<?php echo $option?>" /> 
		<input type="hidden" name="task" value="orders_detail" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="old_status" value="<?php echo $row->order_status;?>" />
		</form>
		<div style="clear:both;"></div>
		<script language="javascript">
		function removeService(id,order_id){
			var answer = confirm("<?php echo JText::_('OS_DO_YOU_WANT_TO_REMOVE_SERVICE')?>");
			if(answer == 1){
				location.href = "index.php?option=com_osservicesbooking&task=orders_removeservice&id=" + id + "&order_id=" + order_id;
			}
		}
		</script>
		<?php
	}
	
	/**
	 * Add services Form
	 *
	 * @param unknown_type $order_id
	 * @param unknown_type $lists
	 */
	function addServicesForm($order_id,$lists,$show_date,$sid,$vid,$eid,$booking_date){
		global $mainframe,$_jversion,$configClass;
		JHtml::_('behavior.multiselect');
		JToolBarHelper::title(JText::_('OS_ADD_ORDER_ITEM'),'order.png');
		JToolBarHelper::save('orders_saveservice');
		JToolBarHelper::cancel('orders_gotoorderdetails');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=com_osservicesbooking&task=orders_addservice" name="adminForm" id="adminForm">
		<div class="row-fluid">
			<div class="cleafix"></div>
			<div class="span12">
				<div class="span2 boldtext">
					<?php echo JText::_('OS_FILTER_EMPLOYEE_FOR_SERVICE');?>
				</div>
				<div class="span10">
					<?php echo $lists['services'];?>
				</div>
			</div>
			<div class="span12">
				<div class="span2 boldtext">
					<?php echo JText::_('OS_SELECT_VENUE');?>
				</div>
				<div class="span10">
					<?php echo $lists['venues'];?>
				</div>
			</div>
			<div class="span12">
				<div class="span2 boldtext">
					<?php echo JText::_('OS_SELECT_EMPLOYEES')?>
				</div>
				<div class="span10">
					<?php echo $lists['employees'];?>
				</div>
			</div>
			<?php
			if($show_date == 1){
				?>
				<div class="span12">
					<div class="span2 boldtext">
						<?php echo JText::_('OS_SELECT_BOOKING_DATE')?>
					</div>
					<div class="span10">
						<?php
						echo JHTML::_('calendar',JRequest::getVar('booking_date',''), 'booking_date', 'booking_date', '%Y-%m-%d', array('class'=>'input-small', 'size'=>'19',  'maxlength'=>'19','style'=>'width:80px;'));
						?>
					</div>
				</div>
				<div class="span12" style="text-align:center;padding:20px;">
					<a href="javascript:document.adminForm.submit();" class="btn btn-danger" style="color:white;"><?php echo JText::_('OS_SHOW_TIME_SLOTS');?></a>
				</div>
				<?php
				if(($sid > 0) and ($eid > 0) and ($booking_date != "")){
					if(OSBHelper::checkAvailableDate($sid,$eid,$booking_date)){
						?>
						<div class="span12 bookingformdiv">
							<div class="span12 btn btn-danger boldtext">
								<?php echo JText::_('OS_OFF_DATE_PLEASE_SELECT_ANOTHER_DATE');?>
							</div>
						</div>
						<?php
					}elseif(OSBHelper::isEmployeeAvailableInSpecificDate($sid,$eid,$booking_date)){
						?>
						<div class="clearfix"></div>
						<div class="span12 bookingformdiv" style="margin-left:0px;">
							<div class="span12 btn btn-warning boldtext">
								<?php echo JText::_('OS_PLEASE_SELECT_TIME_SLOTS_BELLOW');?>
							</div>
							<div class="row-fluid" style="width:100%;">
								<div class="span7" style="margin-left:10px;">
									<BR />
									<?php
									OSBHelper::loadTimeSlots($sid,$eid,$booking_date);
									?>
								</div>
								<div class="span5" style="margin-left:10px;">
									<?php echo OsAppscheduleDefault::loadExtraFields($sid,$eid);?>	
								</div>
							</div>
						</div>
						<?php
					}else{
						?>
						<div class="span12 bookingformdiv">
							<div class="span12 btn btn-danger boldtext">
								<?php echo JText::_('OS_UNAVAILABLE');?>
							</div>
						</div>
						<?php
					}
				}
			}
			?>
		</div>
		<input type="hidden" name="option" value="com_osservicesbooking" />
		<input type="hidden" name="task" value="orders_addservice" />
		<input type="hidden" name="order_id" value="<?php echo $order_id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="nslots" id="nslots" value="" />
		</form>
		<script language="javascript">
		function addBackendBooking(id,start_time,end_time){
			var select = document.getElementById('selected_timeslots');
			for ( var i = 0, l = select.options.length, o; i < l; i++ ){
			   o = select.options[i];
			   if ( o.value == start_time + "-" + end_time ){
			   	   if(o.selected == true){
			       	   o.selected = false;
			   	   }else{
			   	   	   o.selected = true;
			   	   }
			   }
			}
		}
		function updateNslots(id){
			var temp = document.getElementById(id);
			if(temp.checked == true){
				var nslots = document.getElementById('nslots' + id);
				if(nslots != null){
					document.getElementById('nslots').value = nslots.value;
				}
			}
		}
		Joomla.submitbutton = function(pressbutton){
			var form = document.adminForm;
			if(pressbutton == "orders_saveservice"){
				submitform(pressbutton);
			}else{
				submitform(pressbutton);
			}
		}
		</script>
		<?php
	}
	
	function exportReport($rows,$lists){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		?>
		<style>
		.header_td{
			font-weight:bold;
			background:#394D84;
			border:1px solid black;
			text-align:center;
			color:white;
			height:35px;
		}
		.data_td{
			text-align:left;
			padding-left:10px;
			padding-top:5px;
			padding-bottom:5px;
			border-right:1px solid black;
			border-bottom:1px solid black;
		}
		.data_first{
			border-left:1px solid black;
		}
		</style>
		<table width="100%">
			<tr>
				<td width="100%" style="padding:10px;" colspan="2">
					<font style="font-size:22px;font-weight:bold;color:#8496CE;font-family:Tahoma;">
						<?php echo JText::_('OS_REPORT');?>
					</font>
				</td>
			</tr>
			<tr>
				<td width="50%" valign="top">
					<?php
					if(($lists['sid'] > 0) or ($lists['eid'] > 0) or ($lists['order_status'] != "")){
						?>
						<table width="100%">
						<?php
						if($lists['sid'] > 0){
							$db->setQuery("Select id,service_name from #__app_sch_services where id = '".$lists['sid']."'");
							$service = $db->loadObject();
							$service_name = $service->service_name;
							?>
							<tr>
								<td width="20%" style="font-size:14px;text-align:left;padding:10px;font-weight:bold;">
									<?php echo JText::_('OS_SERVICE');?>:
								</td>
								
								<td width="80%" style="font-size:14px;text-align:left;padding:10px;border-bottom:1px dotted gray;">
									<?php echo $service_name;?>
								</td>
							</tr>
							<?php
						}
						?>
						<?php
						if($lists['eid'] > 0){
							$db->setQuery("Select id,employee_name from #__app_sch_employee where id = '".$lists['eid']."'");
							$employee = $db->loadObject();
							$employee_name = $employee->employee_name;
							?>
							<tr>
								<td width="20%" style="font-size:14px;text-align:left;padding:10px;font-weight:bold;">
									<?php echo JText::_('OS_EMPLOYEE');?>:
								</td>
								
								<td width="80%" style="font-size:14px;text-align:left;padding:10px;border-bottom:1px dotted gray;">
									<?php echo $employee_name;?>
								</td>
							</tr>
							<?php
						}
						?>
						<?php
						if($lists['order_status'] != ""){
							?>
							<tr>
								<td width="20%" style="font-size:14px;text-align:left;padding:10px;font-weight:bold;">
									<?php echo JText::_('OS_STATUS');?>:
								</td>
								
								<td width="80%" style="font-size:14px;text-align:left;padding:10px;border-bottom:1px dotted gray;">
									<?php 
									echo OSBHelper::orderStatus(0,$lists['order_status']);
									?>
								</td>
							</tr>
							<?php
						}
						?>
						</table>
						<?php
					}
					?>
				</td>
				<td width="50%" style="padding:10px;text-align:right;" valign="top">
					<?php
					if(($lists['date_from'] != "") or ($lists['date_to'] != "")){
						?>
						
						<table width="100%">
							<tr>
								<td style="font-size:14px;border:1px solid #000;background:#394D84;color:white;font-weight:bold;text-align:center;padding:10px;" colspan="2">
									<?php echo JText::_('OS_PERIOD');?>
								</td>
							</tr>
							<?php
							if($lists['date_from'] != ""){
							?>
							<tr>
								<td width="40%" style="font-size:14px;background:#E7EBF7;text-align:right;border-bottom:1px solid black;padding:10px;">
									<?php echo JText::_('OS_FROM')?>: 
								</td>
								<td align="center" style="font-size:14px;border-bottom:1px solid black;padding:10px;">
									<?php echo $lists['date_from']; ?>
								</td>
							</tr>
							<?php
							}
							?>
							<?php
							if($lists['date_to'] != ""){
							?>
							<tr>
								<td width="40%" style="font-size:14px;background:#E7EBF7;text-align:right;border-bottom:1px solid black;padding:10px;">
									<?php echo JText::_('OS_TO')?>: 
								</td>
								<td align="center" style="font-size:14px;border-bottom:1px solid black;padding:10px;">
									<?php echo $lists['date_to']; ?>
								</td>
							</tr>
							<?php
							}
							?>
						</table>
						<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2" style="padding:20px;">
					<table width="100%">
						<tr>
							<td class="header_td" width="2%">
								#
							</td>
							<td class="header_td" width="10%">
								<?php echo JText::_('OS_SERVICE');?>
							</td>
							<td class="header_td" width="10%">
								<?php echo JText::_('OS_EMPLOYEE');?>
							</td>
							<td class="header_td" width="6%">
								<?php echo JText::_('OS_FROM');?>
							</td>
							<td class="header_td" width="6%">
								<?php echo JText::_('OS_TO');?>
							</td>
							<td class="header_td" width="6%">
								<?php echo JText::_('OS_BOOKING_DATE');?>
							</td>
							<td class="header_td" width="14%">
								<?php echo JText::_('OS_ORDER');?>
							</td>
							<td class="header_td" width="18%">
								<?php echo JText::_('OS_CUSTOMER');?>
							</td>
							<td class="header_td" width="20%">
								<?php echo JText::_('OS_OTHER_INFORMATION');?>
							</td>
							<td class="header_td" width="10%">
								<?php echo JText::_('OS_STATUS');?>
							</td>
						</tr>
						<?php
						for($i=0;$i<count($rows);$i++){
							$row = $rows[$i];
							if($i % 2 == 0){
								$bgcolor = "#fff";
							}else{
								$bgcolor = "#efefef";
							}
							
							?>
							<tr>
								<td class="data_td data_first" style="padding-left:0px;background:<?php echo $bgcolor?>;text-align:center;">
									<?php echo $i + 1;?>
								</td>
								<td class="data_td" style="background:<?php echo $bgcolor?>;">
									<?php echo $row->service_name;?>
								</td>
								<td class="data_td" style="background:<?php echo $bgcolor?>;">
									<?php echo $row->employee_name;?>
								</td>
								<td class="data_td" style="padding-left:0px;background:<?php echo $bgcolor?>;text-align:center;">
									<?php echo date($configClass['time_format'],$row->start_time);?>
								</td>
								<td class="data_td" style="padding-left:0px;background:<?php echo $bgcolor?>;text-align:center;">
									<?php echo date($configClass['time_format'],$row->end_time);?>
								</td>
								<td class="data_td" style="padding-left:0px;background:<?php echo $bgcolor?>;text-align:center;">
									<?php echo date($configClass['date_format'],$row->start_time);?>
								</td>
								<td class="data_td" style="padding-left:0px;background:<?php echo $bgcolor?>;text-align:center;">
									<?php echo $row->order_id;?> (<?php echo $row->order_date;?>)
								</td>
								<td class="data_td" style="background:<?php echo $bgcolor?>;">
									<?php 
									echo $row->order_name." (".$row->order_email.") ".$row->order_phone;
									?>
								</td>
								<td class="data_td" style="background:<?php echo $bgcolor?>;">
								<?php
								if($row->service_time_type ==1){
									echo JText::_('OS_NUMBER_SLOT').": ".$row->nslots."<BR />";
								}
								$db->setQuery("Select * from #__app_sch_fields where field_area = '0' and published = '1'");
								$fields = $db->loadObjectList();
								if(count($fields) > 0){
									for($i1=0;$i1<count($fields);$i1++){
										$field = $fields[$i1];
										$db->setQuery("Select count(id) from #__app_sch_order_field_options where order_item_id = '$row->order_item_id' and field_id = '$field->id'");
										$count = $db->loadResult();
										if($count > 0){
											if($field->field_type == 1){
												$db->setQuery("Select option_id from #__app_sch_order_field_options where order_item_id = '$row->order_item_id' and field_id = '$field->id'");
												$option_id = $db->loadResult();
												$db->setQuery("Select * from #__app_sch_field_options where id = '$option_id'");
												$optionvalue = $db->loadObject();
												?>
												<?php echo $field->field_label;?>:
												<?php
												$field_data = $optionvalue->field_option;
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
													$optionvalue = $db->loadObject();
													$field_data = $optionvalue->field_option;
													if($optionvalue->additional_price > 0){
														$field_data.= " - ".$optionvalue->additional_price." ".$configClass['currency_format'];
													}
													$fieldArr[] = $field_data;
												}
												?>
												<?php echo $field->field_label;?>:
												<?php
												echo implode(", ",$fieldArr);
												echo "<BR />";
											}
										}
									}
								}
								?>
								</td>
								<td class="data_td" style="padding-left:0px;background:<?php echo $bgcolor?>;text-align:center;">
									<?php
									/*
									switch ($row->order_status){
										case "P":
											echo JText::_('OS_PENDING');
										break;
										case "S":
											echo JText::_('OS_COMPLETE');
										break;
										case "C":
											echo JText::_('OS_CANCEL');
										break;
									}
									*/
									echo OSBHelper::orderStatus(0,$row->order_status);
									?>
									
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</td>
			</tr>
		</table>
		<script language="javascript">
		window.print();
		</script>
		<?php
	}
}
?>