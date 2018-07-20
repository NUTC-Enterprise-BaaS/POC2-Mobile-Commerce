<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');

defined('_VIKBOOKINGEXEC') OR die('Restricted Area');

?>

<style type="text/css">
<!--
.confirmed {color: #009900;}
.standby {color: #cc9a04;}
.cancelled {color: #ff0000;}
-->
</style>

<center style="background:#fff; color: #666; width: 100%; table-layout: fixed;">
	<div style="max-width: 800px;">
		<!--[if (gte mso 9)|(IE)]>
			<table width="800" align="center">
			<tr>
			<td>
			<![endif]-->
		<table style="margin: 0 auto; width: 100%; max-width: 800px; border-spacing: 0; font-family: sans-serif;">
			<tbody>
				<tr>
					<td style="font-size: 0; padding:0;">
						<!--[if (gte mso 9)|(IE)]>
						<table width="100%">
						<tr>
						<td width="50%" valign="top">
						<![endif]-->
						<div style="width: 100%; max-width: 396px; display: inline-block; vertical-align: top; text-align: left;">
							<table width="90%" style="margin: 10px auto 0; padding: 5px; font-size: 14px;">
								<tr>
									<td style="padding: 10px; line-height: 1.4em; vertical-align:middle;">
										<div>
											<h1 style="font-size: 22px; font-weight: 500; color: #45c29d; margin:0 0 10px; padding:0;">{company_name}</h1>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<!--[if (gte mso 9)|(IE)]>
						</td><td width="50%" valign="top">
						<![endif]-->
						<div style="width: 100%; max-width: 396px; display: inline-block; vertical-align: top; text-align: left;">
							<table width="90%" style="margin: 10px auto 0; padding: 5px; font-size: 14px;">
								<tr>
									<td style="padding: 10px; line-height: 1.4em; text-align:right;">
										 <div>
											<p>{logo}</p>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<!--[if (gte mso 9)|(IE)]>
						</td>
						</tr>
						</table>
						<![endif]-->
					</td>
				</tr>
				<tr>
					<td style="font-size: 0; padding:0;">
						<!--[if (gte mso 9)|(IE)]>
						<table width="100%">
						<tr>
						<td width="50%" valign="top">
						<![endif]-->
						<div style="width: 100%; max-width: 396px; display: inline-block; vertical-align: top; text-align: left;">
							<table width="90%" style="margin: 10px auto 0; padding: 5px; font-size: 14px; background:#f2f3f7;">
								<tr>
									<td style="padding: 10px; line-height: 1.4em;">
										<div style="min-height: 270px;">
											<h3 style="background:#78B8C4; display:inline-block; padding:5px 10px; text-transform:uppercase; font-size:16px; color:#fff;"><?php echo JText::_('VBMAILYOURBOOKING'); ?>:</h3>
											<div>
												<p><span><?php echo JText::_('VBORDERNUMBER'); ?>:</span> <span>{order_id}</span></p>
											</div>
											{confirmnumb_delimiter}
											<div>
												<p><span><?php echo JText::_('VBCONFIRMNUMB'); ?>:</span> <span>{confirmnumb}</span></p>
											</div>
											{/confirmnumb_delimiter}
											<div>
												<p><span><?php echo JText::_('VBLIBSEVEN'); ?>:</span> <span class="{order_status_class}">{order_status}</span></p>
											</div>
											<div>
												<p><span><?php echo JText::_('VBLIBEIGHT'); ?>:</span> <span>{order_date}</span></p>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<!--[if (gte mso 9)|(IE)]>
						</td><td width="50%" valign="top">
						<![endif]-->
						<div style="width: 100%; max-width: 396px; display: inline-block; vertical-align: top; text-align: left;">
							<table width="90%" style="margin: 10px auto 0; padding: 5px; font-size: 14px; background:#f2f3f7;">
								<tr>
									<td style="padding: 10px; line-height: 1.4em;">
										<div style="min-height: 270px;">
											<h3 style="background:#78B8C4; display:inline-block; padding:5px 10px; text-transform:uppercase; font-size:16px; color:#fff;"><?php echo JText::_('VBLIBNINE'); ?>:</h3>
											<p>{customer_info}</p>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<!--[if (gte mso 9)|(IE)]>
						</td>
						</tr>
						</table>
						<![endif]-->
					</td>
				</tr>
				<tr>
					<td style="font-size: 0; padding:0;">
						<!--[if (gte mso 9)|(IE)]>
						<table width="100%">
						<tr>
						<td width="50%" valign="top">
						<![endif]-->
						<div style="width: 100%; max-width: 396px; display: inline-block; vertical-align: top; text-align: left;">
							<table width="90%" style="background:#f2f3f7; margin: 10px auto 0; padding: 5px; font-size: 14px;">
								<tr>
									<td style="padding: 10px; line-height: 1.4em;">
										<div>
											<div><strong><?php echo JText::_('VBLIBTEN'); ?>:</strong><span> {rooms_count}</span></div>
											<div>
												{rooms_info}
												<?php
												//BEGIN: Rooms Distinctive Features - Default code
												//Each unit of your rooms can have some distinctive features.
												//Here you can list some of them for the customer email.
												//The distintive features are composed of Key-Value pairs where Key is the name of the feature (i.e. Key: Room Number - Value: 102)
												//By default the system generates 1 empty Key (Feature): Room Number.
												//in this example we will only be listing this Key and others could be used for management purposes only.
												//each Key (feature) can be expressed as a language definition contained in your .INI Translation Files. You could also express a Key literally as "Room Number" without translating it.
												//By using the special-syntax {roomfeature KEY_NAME} the system will replace the Key with the corresponding value that you would like to display.
												//By default the Key "Room Number" corresponds to the language definition VBODEFAULTDISTFEATUREONE.
												//Let's display the Room Number (if it is not empty for the rooms booked):
												?>
												{roomfeature VBODEFAULTDISTFEATUREONE}
												<?php
												//END: Rooms Distinctive Features - Default code
												?>
											</div>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<!--[if (gte mso 9)|(IE)]>
						</td><td width="50%" valign="top">
						<![endif]-->
						<div style="width: 100%; max-width: 396px; display: inline-block; vertical-align: top; text-align: left;">
							<table width="90%" style="margin: 10px auto 0; padding: 5px; font-size: 14px; background:#f2f3f7;">
								<tr>
									<td style="padding: 10px; line-height: 1.4em;">
										 <div>
											<p><span style="font-weight:600;"><?php echo JText::_('VBLIBELEVEN'); ?>:</span>
											<span>{checkin_date}</span></p>
										</div>
										<div class="hiredate">
											<p><span style="font-weight:600;"><?php echo JText::_('VBLIBTWELVE'); ?>: </span>
											<span>{checkout_date}</span></p>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<!--[if (gte mso 9)|(IE)]>
						</td>
						</tr>
						</table>
						<![endif]-->
					</td>
				</tr>
				<tr>
					<td style="padding: 0; text-align: center;">
						<table width="95%" style="border-spacing: 0; margin: 10px auto 0; padding: 15px; font-size: 14px; background: #fff;">
							<tr>
								<td style="padding: 10px; line-height: 1.4em; text-align: left;">
									<div>
										<p><h3 style="background:#78B8C4; display:inline-block; padding:5px 10px; text-transform:uppercase; font-size:16px; color:#fff;"><?php echo JText::_('VBORDERDETAILS'); ?>:</h3></p>
										<div style="padding:10px; margin:2px 0;">
											<div>
												{order_details}
											</div>
											<div style="padding:10px; background:#f2f3f7; border:1px solid #45C29D; margin:10px 0;">
												<span><?php echo JText::_('VBLIBSIX'); ?></span>
												<span style="float:right;">
													<strong>{order_total}</strong>
												</span>
											</div>
											<div>{order_deposit}</div>
											<div>{order_total_paid}</div>
										</div>
									</div>	
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style="padding: 0; text-align: center;">
						<table width="95%" style="border-spacing: 0; margin: 0 auto; font-size: 14px; background: #fff;">
							<tr>
								<td style="line-height: 1.4em; text-align: left;">
									<div>
										<strong><?php echo JText::_('VBLIBTENTHREE'); ?>:</strong><br/>
										{order_link}
									</div>	
									<div>
										<div>{footer_emailtext}</div>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<!--[if (gte mso 9)|(IE)]>
		</td>
		</tr>
		</table>
		<![endif]-->
	</div>
</center>