<?php
/**
 * @package    JBusinessDirectory
 * @subpackage com_jbusinessdirectory
 *
 * @copyright  Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license    GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'report.cancel' || !validateCmpForm()) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<?php 
	$appSetings = JBusinessUtil::getInstance()->getApplicationSettings();
	$user = JFactory::getUser(); 
?>

<div class="category-form-container">	
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>

		<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_REPORT_DETAILS');?></h2>
			<p><?php echo JText::_('LNG_REPORT_INFO_TXT');?></p>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_NAME')?> </label> 
					<input type="text"
						name="name" id="name" class="input_txt validate[required]" value="<?php echo $this->item->name ?>">
					<div class="clear"></div>
					<span class="error_msg" id="frmCompanyName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
				</div>
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="description_id"><?php echo JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
					<textarea name="description" id="description" class="input_txt validate[required]"  cols="75" rows="5"><?php echo $this->item->description ?></textarea>
					<div class="clear"></div>
				</div>
			</div>
		</fieldset>

		<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_REPORT_TYPE');?></h2>
			<p> <?php echo JText::_('LNG_REPORT_TYPE_INFORMATION_TEXT');?>.</p>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_TYPE')?> </label> 
					<select id="type" name="type" class="validate[required]">
						<?php
						if($this->item->type == 1) { ?>
							<option value='0'><?php echo JText::_('LNG_COMPANY')?></option>
							<option value='1' selected><?php echo JText::_('LNG_CONFERENCE')?></option>
						<?php } else { ?>
							<option value='0' selected><?php echo JText::_('LNG_COMPANY')?></option>
							<option value='1'><?php echo JText::_('LNG_CONFERENCE')?></option>
						<?php } ?>
					</select>
					<div class="clear"></div>
				</div>
			</div>
		</fieldset>

		<fieldset id="business_params" class="boxed">
			<h2> <?php echo JText::_('LNG_REPORT_PARAMS');?></h2>
			<p> <?php echo JText::_('LNG_REPORT_PARAMS_INFORMATION_TEXT');?>.</p>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_PARAMS')?> </label> 
					<select id="features" class="multiselect" multiple="multiple" name="selected_params[]" size="10">
						<?php
						foreach($this->params as $key=>$param){
							if(in_array($key, $this->item->selected_params)>0)
								$selected = "selected='selected'";
							else
								$selected = "";
							echo "<option value='$key' $selected> ".JText::_($param)."</option>";
						} ?>
					</select>
					<div class="clear"></div>
				</div>
			</div>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_CUSTOM_PARAMS')?> </label> 
					<select id="features" class="multiselect" multiple="multiple" name="custom_params[]" size="10">
						<?php
						foreach($this->customFeatures as $feature){
							if(in_array($feature->code,$this->item->custom_params)>0)
								$selected = "selected='selected'";
							else
								$selected = "";
							echo "<option value='$feature->code' $selected>$feature->name</option>";
						} ?>
					</select>
					<div class="clear"></div>
				</div>
			</div>
		</fieldset>
		
		<fieldset id="conference_params" class="boxed">
			<h2> <?php echo JText::_('LNG_REPORT_PARAMS');?></h2>
			<p> <?php echo JText::_('LNG_REPORT_PARAMS_INFORMATION_TEXT');?>.</p>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_PARAMS')?> </label> 
					<select id="features" class="multiselect" multiple="multiple" name="selected_conference_params[]" size="10">
						<?php 
						foreach($this->conferenceParams as $key=>$conferenceParam){
							if(in_array($key, $this->item->selected_params)>0)
								$selected = "selected='selected'";
							else
								$selected = "";
							echo "<option value='$key' $selected> ".JText::_($conferenceParam)."</option>";
						} ?>
					</select>
					<div class="clear"></div>
				</div>
			</div>
		</fieldset>

		<script  type="text/javascript">
			function save() {
				if(validateCmpForm())
					return false;
				jQuery("#task").val('report.save');
				var form = document.adminForm;
				form.submit();
			}
			function cancel() {
				jQuery("#task").val('report.cancel');
				var form = document.adminForm;
				form.submit();
			}
			function validateCmpForm() {
				var isError = jQuery("#item-form").validationEngine('validate');
				return !isError;
			}

			jQuery(document).ready(function() {
				jQuery(".multiselect").multiselect();

				jQuery('#type').on('change', function() {
					if ( this.value == '1') {
						jQuery("select[name='selected_params']").empty();
						jQuery("select[name='custom_params']").empty();
						jQuery("#business_params").hide();
						jQuery("#conference_params").show();
					} else {
						jQuery("select[name='selected_conference_params']").empty();
						jQuery("#conference_params").hide();
						jQuery("#business_params").show();
					}
				}).trigger('change');
			});
		</script>
		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
		<input type="hidden" name="task" id="task" value="" /> 
		<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
