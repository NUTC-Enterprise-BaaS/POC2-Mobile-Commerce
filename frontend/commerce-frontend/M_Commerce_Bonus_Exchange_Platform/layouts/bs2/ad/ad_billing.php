<?php
/**
 *  @package    Social Ads
 *  @copyright  Copyright (c) 2009-2013 TechJoomla. All rights reserved.
 *  @license    GNU General Public License version 2, or later
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
//JHtmlBehavior::framework();
$userbill = $displayData->userbill;
$rootURL = JUri::root();
$vatNo = $displayData->sa_params->get('vat_no');
?>

<br/>
<fieldset class="sa-fieldset">
	<br>
	<div id="ads_mainwrapper" class="row-fluid form-horizontal">
		<div class="control-group">
			<label  for="fnam" class="control-label"><?php echo "* ".JText::_('COM_SOCIALADS_BILLING_FNAM')?></label>
			<div class="controls">
				<input id="fnam" class="input-medium bill inputbox required validate-name" type="text" value="<?php echo (isset($userbill->firstname))?$userbill->firstname:''; ?>" maxlength="250" size="32" name="bill[fnam]" title="<?php echo JText::_('COM_SOCIALADS_BILLING_FNAM_DESC')?>">
			</div>
		</div>

		<?php
		if (!empty($params) && $params->get( 'ads_middlenmae' ) == 1)
		{ ?>
			<div class="control-group">
				<label  for="fnam" class="control-label"><?php echo "* ".JText::_('COM_SOCIALADS_BILLING_MNAM')?></label>
				<div class="controls">
					<input id="mnam" class="input-medium bill inputbox required validate-name" type="text" value="<?php echo (isset($userbill->middlename))?$userbill->middlename:''; ?>" maxlength="250" size="32" name="bill[mnam]" title="<?php echo JText::_('COM_SOCIALADS_BILLING_MNAM_DESC')?>">
				</div>
			</div>
		<?php
		} ?>

		<div class="control-group">
			<label for="lnam" class="control-label"><?php echo "* ".JText::_('COM_SOCIALADS_BILLING_LNAM')?>	</label>
			<div class="controls">
				<input id="lnam" class="input-medium bill inputbox required validate-name" type="text" value="<?php echo (isset($userbill->lastname))?$userbill->lastname:''; ?>" maxlength="250" size="32" name="bill[lnam]" title="<?php echo JText::_('COM_SOCIALADS_BILLING_LNAM_DESC')?>">
			</div>
		</div>

		<div class="control-group">
			<label for="email1" class="control-label">
				<?php echo "* ".JText::_('COM_SOCIALADS_BILLING_EMAIL')?>
			</label>
			<div class="controls">
				<input id="email1" class="input-medium bill inputbox required validate-email" type="text" value="<?php echo (isset($userbill->user_email))?$userbill->user_email:'' ; ?>" maxlength="250" size="32" name="bill[email1]"  title="<?php echo JText::_('COM_SOCIALADS_BILLING_EMAIL_DESC')?>">
			</div>
		</div>

		<?php
		// $enable_bill_vat = !empty($params) ? $displayData->sa_params('vat_no') : 0;;
		if ($vatNo)
		{ ?>
			<div class="control-group">
				<label for="vat_num"  class="control-label"><?php echo  JText::_('COM_SOCIALADS_BILLING_VAT_NUM')?></label>
				<div class="controls">
					<input id="vat_num" class="input-small bill inputbox validate-integer" type="text" value="<?php echo (isset($userbill->vat_number))?$userbill->vat_number:''; ?>" size="32" name="bill[vat_num]" title="<?php echo JText::_('COM_SOCIALADS_BILLING_VAT_NUM_DESC')?>">
				</div>
			</div>
		<?php
		}

		$entered_numerics = "'" . JText::_('COM_SOCIALADS_JS_MSG_NUMERICS') . "'";
		?>
		<div class="control-group">
			<label for="phon"  class="control-label">
				<?php echo "* ".JText::_('COM_SOCIALADS_BILLING_PHON')?>
			</label>
			<div class="controls">
				<input id="phon" class="input-small bill inputbox required validate-integer" type="text" onkeyup="sa.ad_checkforalpha(this,43,<?php echo $entered_numerics; ?>);" maxlength="12" value="<?php echo (isset($userbill->phone))?$userbill->phone:''; ?>" size="32" name="bill[phon]" title="<?php echo JText::_('COM_SOCIALADS_BILLING_PHON_DESC')?>">
			</div>
		</div>

		<div class="control-group">
			<label for="addr"  class="control-label">
				<?php echo "* ".JText::_('COM_SOCIALADS_BILLING_ADDR')?>
			</label>
			<div class="controls">
				<textarea id="addr" class="input-medium bill inputbox required" name="bill[addr]"  maxlength="250" rows="3" title="<?php echo JText::_('COM_SOCIALADS_BILLING_ADDR_DESC')?>" ><?php echo (isset($userbill->address))?$userbill->address:''; ?></textarea>
				<p class="help-block"><?php echo JText::_('COM_SOCIALADS_BILLING_ADDR_HELP')?> </p>
			</div>
		</div>

		<div class="control-group">
			<label for="zip"  class="control-label">
				<?php echo "* ".JText::_('COM_SOCIALADS_BILLING_ZIP')?>
			</label>
			<div class="controls">
				<input id="zip"  class="input-small bill inputbox required " type="text" value="<?php echo (isset($userbill->zipcode))?$userbill->zipcode:''; ?>"  maxlength="20" size="32" name="bill[zip]" title="<?php echo JText::_('COM_SOCIALADS_BILLING_ZIP_DESC')?>">
			</div>
		</div>

		<div class="control-group">
			<label for="sa_bill_country"  class="control-label">
				<?php echo "* ".JText::_('COM_SOCIALADS_BILLING_COUNTRY')?>
			</label>
			<div class="controls">
				<?php
				$default   = ((isset($userbill->country_code)) ? $userbill->country_code : '');
				$options   = array();
				$options[] = JHtml::_('select.option', "", JText::_('COM_SOCIALADS_BILLING_SELECT_COUNTRY'));

				foreach ($displayData->country as $key => $value)
				{
					$options[] = JHtml::_('select.option', $value['id'], $value['country']);
				}

				$taxval = 0;

				echo $displayData->dropdown = JHtml::_(
					'select.genericlist', $options,'bill[country]',
					'class=" ads_ckout_select bill sa_Mediumselect required"  required="true" aria-invalid="false" size="1"
					onchange=\'sa.create.getStatesList(this.id,"","' . JText::_('COM_SOCIALADS_BILLING_SELECT_STATE').'")\' ',
					'value', 'text', $default,'sa_bill_country'
					); ?>
			</div>
		</div>

		<div class="control-group">
			<label for="state" class="control-label">
				<?php echo JText::_('COM_SOCIALADS_BILLING_STATE')?>
			</label>
			<div class="controls">
				<select name="bill[state]" id="state" class="bill sa_Mediumselect">
					<option selected="selected" value="" ><?php echo JText::_('COM_SOCIALADS_BILLING_SELECT_STATE')?></option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label for="city" class="control-label">
				<?php echo "* ".JText::_('COM_SOCIALADS_BILLING_CITY')?>
			</label>
			<div class="controls">
				<input id="city" class="input-medium bill inputbox required validate-name" type="text" value="<?php echo (isset($userbill->city))?$userbill->city:''; ?>" maxlength="250" size="32" name="bill[city]" title="<?php echo JText::_('COM_SOCIALADS_BILLING_CITY_DESC')?>">
			</div>
		</div>
	</div>
</fieldset>

<script type="text/javascript">
techjoomla.jQuery(document).ready(function(){
	var DBuserbill="<?php echo (isset($userbill->state_code)) ? $userbill->state_code : ''; ?>";
	sa.create.getStatesList("sa_bill_country", DBuserbill, "<?echo JText::_('COM_SOCIALADS_BILLING_SELECT_STATE'); ?>");
});
</script>
