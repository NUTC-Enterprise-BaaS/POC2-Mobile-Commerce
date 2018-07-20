<?php
/*------------------------------------------------------------------------
# cpanel.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class HTML_OSappscheduleCpanel{
	function showControlpanel($lists){
		global $mainframe,$configClass;
		JToolBarHelper::title(JText::_('OS_DASHBOARD'), 'home');
		JToolbarHelper::preferences('com_osservicesbooking');
		?>
		<table border="0" width="100%">
			<tr>
				<td valign="top" width="15%" style="background:#F4F4F4;text-align:center;">
					<table width="100%" >
						<tr>
							<td width="100%" colspan="2">
								<img src="<?php echo JUri::root()?>administrator/components/com_osservicesbooking/asset/images/osb_jed_small.png" width="220" />
							</td>
						</tr>
						<tr>
							<td width="100%" colspan="2">
								<div style="width:100%;text-align:center;color:#747894;">
									<h3>
										OS Services Booking
									</h3>
								</div>
							</td>
						</tr>
						<tr>
							<td width="50%" style="text-align:right;font-size:10px;">
								INSTALLED VERSION.
							</td>
							<td width="50%" style="text-align:left;font-weight:bold;padding-left:10px;">
								2.4.6
							</td>
						</tr>
						<tr>
							<td width="55%" style="text-align:right;font-size:10px;">
								AUTHOR.
							</td>
							<td width="45%" style="text-align:left;font-weight:bold;padding-left:10px;">
								OSSOLUTION
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:center;font-size:11px;" colspan="2">
								<a href="http://joomdonation.com/joomla-extensions/joomla-services-appointment-booking.html" target="_blank" title="OS Services Booking official page">OSB OFFICIAL PAGE.</a>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:center;font-size:11px;" colspan="2">
								<a href="http://joomdonation.com/forum/os-services-booking.html" target="_blank" title="OS Services Booking forum">FORUM SUPPORT.</a>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:center;font-size:11px;" colspan="2">
								<a href="http://joomdonation.com/support-tickets.html" target="_blank" title="OS Services Booking support ticket">SUPPORT TICKET.</a>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:center;font-size:11px;" colspan="2">
								<a href="http://osb.ext4joomla.com/OS_ServicesBooking_Instructions.pdf" target="_blank" title="OS Services Booking documentation">DOCUMENTATION.</a>
							</td>
						</tr>
					</table>
				</td>
				<td valign="top" width="40%" style="padding-left:20px;">
					<table class="adminlist">
						<tr>
							<td>
								<div id="cpanel">
									<?php							
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=category_list', 'icon-48-categories.png', JText::_('OS_MANAGE_CATEGORIES'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=category_add', 'icon-48-category-add.png', JText::_('OS_NEW_CATEGORY'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=venue_list', 'icon-48-marker.png', JText::_('OS_MANAGE_VENUES'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=venue_add', 'icon-48-newvenue.png', JText::_('OS_NEW_VENUE'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=service_list', 'icon-48-calendar.png', JText::_('OS_MANAGE_SERVICES'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=service_add', 'icon-48-calendar-add.png', JText::_('OS_NEW_SERVICE'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=employee_list', 'icon-48-employee.png', JText::_('OS_EMPLOYEE_MANAGE'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=employee_add', 'icon-48-document-add.png', JText::_('OS_NEW_EMPLOYEE'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=orders_list', 'icon-48-orders.png', JText::_('OS_MANAGE_ORDERS'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=plugin_list', 'icon-48-payments.png', JText::_('OS_MANAGE_PAYMENT_PLUGINS'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=coupon_list', 'icon-48-coupon.png', JText::_('OS_MANAGE_COUPONS'));
                                    //OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=coupon_add', 'icon-48-add-coupon.png', JText::_('OS_ADD_COUPON'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=balance_list', 'icon-48-user-balance.png', JText::_('OS_USER_BALANCE'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=worktime_list', 'icon-48-worktime.png', JText::_('OS_WORKING_TIME'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=worktimecustom_list', 'icon-48-worktimecustom.png', JText::_('Custom time'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=fields_list', 'icon-48-customfield.png', JText::_('OS_CUSTOM_FIELD'));
									OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=fields_add', 'icon-48-levels-add.png', JText::_('OS_ADD_CUSTOM_FIELD'));
									OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=translation_list', 'icon-48-languages.png', JText::_('OS_TRANSLATION'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=emails_list', 'icon-48-emails.png', JText::_('OS_EMAIL_TEMPLATES'));
                                    OSappscheduleCpanel::quickiconButton('index.php?option=com_osservicesbooking&amp;task=configuration_list', 'icon-48-config.png', JText::_('OS_CONFIGURATION_CONFIGURATION'));
									OSappscheduleCpanel::quickiconButton('', 'updated_failure.png', JText::_('OS_CHECKING_VERSION'));
									?>
									<script language="javascript">
										window.onload = function() {
										   checkingVersion('2.4.6');
										};
									</script>
								</div>
							</td>
						</tr>
					</table>
				</td>
				<td valign="top" width="40%" style="padding: 0 0 0 5px;text-align:center;">
                    <script type="text/javascript" src="<?php echo JUri::root(); ?>components/com_osservicesbooking/js/jquery.flot.min.js"></script>
                    <script type="text/javascript" src="<?php echo JUri::root(); ?>components/com_osservicesbooking/js/jquery.flot.pie.min.js"></script>
                    <div class="row-fluid">
						<table class="table dashboard-table" style="width:100%;">
							<tbody>
							<tr>
								<td class="dashboard-table-header" style="width:100%;">
									<?php echo Jtext::_('OS_MONTHLY_REPORT'); ?>
								</td>
							</tr>
							<tr>
								<td style="width:100%;">
									<?php
									global $currentMonthOffset;
									$currentMonthOffset = (int)date('m');
									if (JRequest::getInt('month') != 0)
										$currentMonthOffset = JRequest::getInt('month');
									?>
									<div class="monthly-stats">
										<p>
											<?php
											if($currentMonthOffset != date('m'))
											{
												?><a href="index.php?option=com_osservicesbooking&amp;task=cpanel_list&amp;month=<?php echo $currentMonthOffset + 1; ?>" class="next"><?php echo JText::_('OS_NEXT_MONTH'); ?></a>
											<?php
											}
											?>
											<a href="index.php?option=com_osservicesbooking&amp;task=cpanel_list&amp;month=<?php echo $currentMonthOffset - 1; ?>" class="previous"><?php echo JText::_('OS_PREVIOUS_MONTH'); ?></a>
										</p>
										<div class="inside">
											<div id="placeholder" style="width:100%; height:300px; position:relative;"></div>
											<script type="text/javascript">
												/* <![CDATA[ */
												jQuery(function(){
													function weekendAreas(axes)
													{
														var markings = [];
														var d = new Date(axes.xaxis.min);
														// go to the first Saturday
														d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7));
														d.setUTCSeconds(0);
														d.setUTCMinutes(0);
														d.setUTCHours(0);
														var i = d.getTime();
														do
														{
															// when we don't set yaxis, the rectangle automatically
															// extends to infinity upwards and downwards
															markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
															i += 7 * 24 * 60 * 60 * 1000;
														}
														while(i < axes.xaxis.max);
														return markings;
													}
													<?php
													global $currentMonthOffset;
													$month = $currentMonthOffset;
													$year = (int) date('Y');
													$firstDay = strtotime("{$year}-{$month}-01");
													$lastDay = strtotime('-1 second', strtotime('+1 month', $firstDay));
													$after = date('Y-m-d H:i:s', $firstDay);
													$before = date('Y-m-d H:i:s', $lastDay);
													$orders = OSappscheduleCpanel::getMonthlyReport($currentMonthOffset, $before, $after);
													$orderCounts = array();
													$orderAmounts = array();
													// Blank date ranges to begin
													$month = $currentMonthOffset;
													$year = (int) date('Y');
													$firstDay = strtotime("{$year}-{$month}-01");
													$lastDay = strtotime('-1 second', strtotime('+1 month', $firstDay));
													if ((date('m') - $currentMonthOffset)==0) :
														$upTo = date('d', strtotime('NOW'));
													else :
														$upTo = date('d', $lastDay);
													endif;
													$count = 0;
													while ($count < $upTo)
													{
														$time = strtotime(date('Ymd', strtotime('+ '.$count.' DAY', $firstDay))).'000';
														$orderCounts[$time] = 0;
														$orderAmounts[$time] = 0;
														$count++;
													}
													if ($orders)
													{
														foreach ($orders as $order)
														{
															$time = strtotime(date('Ymd', strtotime($order->order_date))) . '000';
															if (isset($orderCounts[$time]))
															{
																$orderCounts[$time]++;
															}
															else
															{
																$orderCounts[$time] = 1;
															}
															if (isset($orderAmounts[$time]))
															{
																$orderAmounts[$time] = $orderAmounts[$time] + $order->order_final_cost;
															}
															else
															{
																$orderAmounts[$time] = (float) $order->order_final_cost;
															}
														}
													}
													?>
													var d = [
														<?php
														$values = array();
														foreach ($orderCounts as $key => $value)
														{
															$values[] = "[$key, $value]";
														}
														echo implode(',', $values);
														?>
													];
													for(var i = 0; i < d.length; ++i) d[i][0] += 60 * 60 * 1000;
													var d2 = [
														<?php
														$values = array();
														foreach ($orderAmounts as $key => $value)
														{
															$values[] = "[$key, $value]";
														}
														echo implode(',', $values);
														?>
													];
													for(var i = 0; i < d2.length; ++i) d2[i][0] += 60 * 60 * 1000;
													var plot = jQuery.plot(jQuery("#placeholder"), [
														{ label: "<?php echo JText::_('OS_TOTAL_ORDERS'); ?>", data: d },
														{ label: "<?php echo JText::_('OS_TOTAL_AMOUNT'); ?>", data: d2, yaxis: 2 }
													], {
														series: {
															lines: { show: true },
															points: { show: true }
														},
														grid: {
															show: true,
															aboveData: false,
															color: '#ccc',
															backgroundColor: '#fff',
															borderWidth: 2,
															borderColor: '#ccc',
															clickable: false,
															hoverable: true,
															markings: weekendAreas
														},
														xaxis: {
															mode: "time",
															timeformat: "%d %b",
															tickLength: 1,
															minTickSize: [1, "day"]
														},
														yaxes: [
															{ min: 0, tickSize: 1, tickDecimals: 0 },
															{ position: "right", min: 0, tickDecimals: 2 }
														],
														colors: ["#21759B", "#ed8432"]
													});
													function showTooltip(x, y, contents){
														jQuery('<div id="tooltip">' + contents + '</div>').css({
															position: 'absolute',
															display: 'none',
															top: y + 5,
															left: x + 5,
															border: '1px solid #fdd',
															padding: '2px',
															'background-color': '#fee',
															opacity: 0.80
														}).appendTo("body").fadeIn(200);
													}
													var previousPoint = null;
													jQuery("#placeholder").bind("plothover", function(event, pos, item){
														if(item){
															if(previousPoint != item.dataIndex){
																previousPoint = item.dataIndex;
																jQuery("#tooltip").remove();
																if(item.series.label == "<?php echo JText::_('OS_TOTAL_ORDERS','jigoshop'); ?>"){
																	var y = item.datapoint[1];
																	showTooltip(item.pageX, item.pageY, item.series.label + " - " + y);
																} else {
																	var y = item.datapoint[1].toFixed(2);
																	showTooltip(item.pageX, item.pageY, item.series.label + " - <?php echo '$'; ?>" + y);
																}
															}
														}
														else {
															jQuery("#tooltip").remove();
															previousPoint = null;
														}
													});
												});
												/* ]]> */
											</script>
										</div>
                                     
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
					<div class="row-fluid">
						<div class="span12">
							<div class="tabs clearfix">
							    <div class="tabbable">
							        <ul class="nav nav-tabs">
							        	<li class="active"><a href="#generalstatitics" data-toggle="tab"><?php echo JText::_('OS_GENERAL_STATITICS');?></a></li>
							        	<li><a href="#report" data-toggle="tab"><?php echo JText::_('OS_GENERAL_REPORT');?></a></li>
							        	<li><a href="#optimize" data-toggle="tab"><?php echo JText::_('OS_OPTIMIZE_DATABASE');?></a></li>
							        </ul>            
					    		</div>
					    		<div class="tab-content">
					    			<div class="tab-pane active" id="generalstatitics" style="text-align:left;">
						        		<table width="100%"	class="table table-striped">
											<thead>
												<tr>
													<th style="text-align:left;">
														<?php echo JText::_('OS_TIME');?>
													</th>
													<th style="text-align:left;">
														<?php echo JText::_('OS_ORDER_INCOME');?>
													</th>
												</tr>
											</thead>
											<tbody>
												<tr class="row0">
													<td align="left">
														<?php
														echo JText::_("OS_TODAY");
														?>
													</td>
													<td align="left">
														<?php
														echo $configClass['currency_symbol'];
														echo " ";
														echo $lists['today'];
														?>
													</td>
												</tr>
												<tr class="row1">
													<td align="left">
														<?php
														echo JText::_("OS_YESTERDAY");
														?>
													</td>
													<td align="left">
														<?php
														echo $configClass['currency_symbol'];
														echo " ";
														echo $lists['yesterday'];
														?>
													</td>
												</tr>
												<tr class="row0">
													<td align="left">
														<?php
														echo JText::_("OS_CURRENT_MONTH");
														?>
													</td>
													<td align="left">
														<?php
														echo $configClass['currency_symbol'];
														echo " ";
														echo $lists['current_month'];
														?>
													</td>
												</tr>
												<tr class="row1">
													<td align="left">
														<?php
														echo JText::_("OS_LAST_MONTH");
														?>
													</td>
													<td align="left">
														<?php
														echo $configClass['currency_symbol'];
														echo " ";
														echo $lists['last_month'];
														?>
													</td>
												</tr>
												<tr class="row0">
													<td align="left">
														<?php
														echo JText::_("OS_CURRENT_YEAR");
														?>
													</td>
													<td align="left">
														<?php
														echo $configClass['currency_symbol'];
														echo " ";
														echo $lists['current_year'];
														?>
													</td>
												</tr>
												<tr class="row1">
													<td align="left">
														<?php
														echo JText::_("OS_LAST_YEAR");
														?>
													</td>
													<td align="left">
														<?php
														echo $configClass['currency_symbol'];
														echo " ";
														echo $lists['last_year'];
														?>
													</td>
												</tr>
											</tbody>
										</table>
						        	</div>
						        	<div class="tab-pane" id="report" style="text-align:left;">
						        		<form class="form-horizontal" action="index.php?option=com_osservicesbooking&task=orders_exportreport" method="POST" target="_blank">
						        			<div class="control-group" style="text-align:center;padding:20px;"> 
						        				<label>
						        					<?php echo JText::_('OS_PLEASE_SELECT_FILTER_PARAMETERS_TO_EXPORT_REPORT');?>
						        				</label>
						        			</div>
						        			<div class="control-group">
												<label class="control-label" style="width:135px;"><?php echo JText::_('OS_DATE_FROM')?>: </label>
												<div class="controls">
													<?php 
													echo JHtml::_('calendar','','date_from','date_from','%Y-%m-%d',array('class' => 'input-small'));
													?>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" style="width:135px;"><?php echo JText::_('OS_DATE_TO')?>: </label>
												<div class="controls">
													<?php 
													echo JHtml::_('calendar','','date_to','date-to','%Y-%m-%d',array('class' => 'input-small'));
													?>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" style="width:135px;"><?php echo JText::_('OS_SERVICE')?>: </label>
												<div class="controls">
													<?php 
													echo $lists['services'];
													?>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" style="width:135px;"><?php echo JText::_('OS_EMPLOYEE')?>: </label>
												<div class="controls">
													<?php 
													echo $lists['employee'];
													?>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label" style="width:135px;"><?php echo JText::_('OS_STATUS')?>: </label>
												<div class="controls">
													<?php 
													echo $lists['order_status'];
													?>
												</div>
											</div>
											<div class="control-group" style="text-align:center;padding:20px;">
						        				<input type="submit" class="btn btn-info" value="<?php echo JText::_('OS_EXPORT_REPORT');?>"/>
						        			</div>
						        			<input type="hidden" name="option" value="com_osservicesbooking" />
						        			<input type="hidden" name="task" value="orders_exportreport" />
						        			<input type="hidden" name="tmpl" value="component" />
						        		</form>
						        	</div>
						        	<div class="tab-pane" id="optimize" style="text-align:left;">
						        		<div class="row-fluid">
						        			<div class="span12" style="text-align:center;padding:20px;">
						        				<?php echo JText::_('OS_OPTIMIZE_DATABASE_EXPLAIN');?>
						        				<div class="clearfix"></div>
						        				<input type="button" class="btn btn-warning" value="<?php echo JText::_('OS_OPTIMIZE_DATABASE');?>" onclick="javascript:optimizedatabase();"/>
						        			</div>
						        		</div>
						        	</div> <!-- end tab -->
					    		</div>
					    	</div>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JUri::root();?>" />
		<script language="javascript">
		function optimizedatabase(){
			var answer = confirm("<?php echo JText::_('OS_ARE_YOU_SURE_YOU_WANT_TO_OPTIMIZE_OSB_DATABASE')?>");
			if(answer == 1){
				location.href = "index.php?option=com_osservicesbooking&task=cpanel_optimizedatabase";
			}
		}
		</script>
		<?php
	}
}
?>