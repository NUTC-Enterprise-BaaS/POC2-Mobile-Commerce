<?php // no direct access
/**
 * @copyright	Copyright (C) 2008-2009 CMSJunkie. All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();

?>
<div id="order-content" class="order-content">
	<!-- Header section -->
	<div class="row-fluid order-header">
		<div class="span6 text-left">
			<?php echo !empty($this->appSettings->logo)?"<img src='".JURI::root().PICTURES_PATH.$this->appSettings->logo."'/>":""; ?>
		</div>
		<div class="span6 text-right">
			<div class="printbutton">
				<a onclick="window.print()" href="javascript:void(0);"><div class="dir-icon-print"> </div><?php echo JText::_("LNG_PRINT")?></a>
			</div> 
			<div class="order-header-info">
				<i class="dir-icon-phone"></i> <?php echo $this->appSettings->invoice_company_phone ?> | <i class="dir-icon-envelope"></i> <?php echo $this->appSettings->invoice_company_email; ?>
			</div>
		</div>
	</div>
	<!-- Invoice Details Section -->
	<div class="row-fluid title-section">
		<div class="span6 text-left">
			<h3 class="title"><?php echo JText::_('LNG_INVOICE'); ?></h3>
		</div>
		<div class="span6">
			<div class="order-identifier">
				<dl>
					<dt><?php echo JText::_('LNG_NUMBER')?>:</dt>
					<dd><?php echo $this->item->id?></dd>
					<dt><?php echo JText::_('LNG_DATE')?>:</dt>
					<dd><?php echo JBusinessUtil::getDateGeneralFormat($this->item->created) ?></dd>
				</dl>	
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6 text-left">
			<strong><?php echo JText::_('LNG_ISSUED_TO'); ?>: </strong>
			<ul class="entity-details">
				<li>
					<?php
					if(!empty($this->item->billingDetails->first_name) || !(empty($this->item->billingDetails->last_name)))
						echo $this->item->billingDetails->first_name.' '.$this->item->billingDetails->last_name.'<br/>';
					?>
				</li>
				<li>
					<?php
						echo !empty($this->item->billingDetails->company_name)?$this->item->billingDetails->company_name:$this->item->companyName.'<br/>';
					?>
				</li>
				<li>
					<?php if(!empty($this->item->billingDetails->address) || !empty($this->item->billingDetails->city) || !empty($this->item->billingDetails->country) || !empty($this->item->billingDetails->region)) {
						echo implode(", ", array_filter(array($this->item->billingDetails->address, $this->item->billingDetails->city))).'<br/>';
						echo implode(", ", array_filter(array($this->item->billingDetails->region, $this->item->billingDetails->postal_code))).'<br/>';
						echo $this->item->billingDetails->country.'<br/>';
					}
					else{
						echo implode(", ", array_filter(array($this->item->company->address, $this->item->company->city))).'<br/>';
						echo implode(", ", array_filter(array($this->item->company->county, $this->item->company->postalCode))).'<br/>';
						echo $this->item->company->country_name.'<br/>';
					}
					?>
				</li>
				<li>
					<?php
					if(!empty($this->item->billingDetails->phone) || !empty($this->item->billingDetails->email))
						echo implode(", ", array_filter(array($this->item->billingDetails->phone, $this->item->billingDetails->email))).'<br/>';
					else
						echo implode(", ", array_filter(array($this->item->company->phone, $this->item->company->email)));
					?>
				</li>
				<li>
					<?php if(!empty($this->item->company->taxCode)) {?>
						<?php echo $this->item->company->taxCode ?>
					<?php } ?>
				</li>
			</ul>
		</div>
		<div class="span6 text-left">
			<strong><?php echo JText::_('LNG_FROM'); ?>: </strong>
			<ul class="entity-details">
				<li>
					<?php echo $this->appSettings->invoice_company_name; ?>
				</li>
				<li>
					<?php echo $this->appSettings->invoice_company_address ?>
				</li>
				<li>
					<?php echo implode(", ", array_filter(array($this->appSettings->invoice_company_phone, $this->appSettings->invoice_company_email))); ?>
				</li>
				<li>
					<?php echo $this->appSettings->invoice_vat ?>
				</li>
			</ul>
				
		</div>
	</div>
	<!-- Product Description table section -->
	<table class="table">
		<thead>
			<tr>
				<th>
					<?php echo JText::_('LNG_PRODUCT_SERVICE'); ?>
				</th>
				<th class="hidden-xs hidden-phone">
					<?php echo JText::_('LNG_DESCRIPTION'); ?>
				</th>
				<th class="hidden-xs hidden-phone">
					<?php echo JText::_('LNG_QUANTITY'); ?>
				</th>
				<th class="hidden-xs hidden-phone">
					<?php echo JText::_('LNG_UNIT_PRICE'); ?>
				</th>
				<th>
					<?php echo JText::_('LNG_TOTAL'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo $this->item->service ?></td>
				<td class="hidden-xs hidden-phone"><?php echo $this->item->description ?></td>
				<td class="text-right hidden-xs hidden-phone">1</td>
				<td class="text-right hidden-xs hidden-phone"><?php echo $this->item->initial_amount ?> <?php echo $this->appSettings->currency_name?></td>
				<td class="text-right"><?php echo $this->item->initial_amount ?> <?php echo $this->appSettings->currency_name?></td>
			</tr>
			<tr>
				<td colspan="3" style="border-bottom-width:0px;border-left-width:0px;" />
				<td><b><?php echo JText::_('LNG_SUB_TOTAL'); ?></b></td>
				<td><?php echo number_format($this->item->initial_amount- $this->item->discount_amount,2).' '.$this->appSettings->currency_name ?></td>
			</tr>
			<?php
			if($this->item->discount_amount>0){
				echo '<tr>';
				echo '<td colspan="3" style="border-bottom-width:0px;border-left-width:0px;border-top-width:0px;" />';
				echo '<td>'.JText::_("LNG_DISCOUNT").'</td>';
				echo '<td>'.$this->item->discount_amount.' '.$this->appSettings->currency_name.'</td>';
				echo '</tr>';
			}
			?>
			<?php if(!empty($this->item->vat_amount) && $this->item->vat_amount>0){ ?>
				<tr>
					<td colspan="3" style="border-bottom-width:0px;border-left-width:0px;border-top-width:0px;" />
					<td><b><?php echo JText::_('LNG_VAT'); ?> (<?php echo $this->appSettings->vat?>%)</b></td>
					<td><?php echo number_format($this->item->vat_amount,2).' '.$this->appSettings->currency_name ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="3" style="border-bottom-width:0px;border-left-width:0px;border-top-width:0px;" />
				<td><b><?php echo JText::_('LNG_TOTAL') ?></b></td>
				<td><?php echo number_format($this->item->amount,2).' '.$this->appSettings->currency_name ?></td>
			</tr>
		</tbody>
	</table>
	
	<?php if(!empty($this->appSettings->invoice_details)){ ?>
		<div class="row-fluid">
			<div class="order-text">
				<?php echo $this->appSettings->invoice_details ?>
			</div>
		</div>
	<?php } ?>
	
	<!-- Footer section -->
	<div class="row-fluid text-right ">
		<div class="order-footer">
			<?php  echo $this->appSettings->invoice_company_name.' | '; ?>
			<?php echo JText::_('LNG_INVOICE_FOR'); ?>
			<?php echo !empty($this->billingDetails->company_name)?$this->billingDetails->company_name:$this->item->companyName; ?>
		</div>
	</div>
</div>