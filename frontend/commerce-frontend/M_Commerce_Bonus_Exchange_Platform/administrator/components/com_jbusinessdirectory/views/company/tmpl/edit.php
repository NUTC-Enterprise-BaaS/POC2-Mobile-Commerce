<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
require_once JPATH_COMPONENT_SITE.'/classes/attributes/attributeservice.php';

JHTML::_('script', 'components/com_jbusinessdirectory/assets/js/bootstrap-tagsinput.min.js');
JHTML::_('stylesheet', 	'components/com_jbusinessdirectory/assets/css/bootstrap-tagsinput.css');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');

$attributeConfig = $this->item->defaultAtrributes;
$enablePackages = $this->appSettings->enable_packages;

$app = JFactory::getApplication();
$showSteps = JRequest::getVar("showSteps",false);
$options = array(
	'onActive' => 'function(title, description) {
		description.setStyle("display", "block");
		title.addClass("open").removeClass("closed");
	}',
	'onBackground' => 'function(title, description) {
		description.setStyle("display", "none");
		title.addClass("closed").removeClass("open");
	}',
	'startOffset' => 1,  // 0 starts on the first tab, 1 starts the second, etc...
	'useCookie' => true, // this must not be a string. Don't use quotes.
);

$maxPictures = isset($this->item->package)?$this->item->package->max_pictures:$this->appSettings->max_pictures;
$nrPictures = count($this->item->pictures);
$allowedNr = $maxPictures - $nrPictures;
$allowedNr=($allowedNr<0)?0:$allowedNr;

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {

		var defaultLang="<?php echo JFactory::getLanguage()->getTag() ?>";

		jQuery("#item-form").validationEngine('detach');
		var evt = document.createEvent("HTMLEvents");
		evt.initEvent("click", true, true);
		var tab = ("tab-"+defaultLang);
		if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
			document.getElementsByClassName(tab)[0].dispatchEvent(evt);
		if (task == 'company.cancel' || task == 'company.aprove' || task == 'company.disaprove' || !validateCmpForm()){
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		jQuery("#item-form").validationEngine('attach');
	}
</script>

<?php $user = JFactory::getUser(); ?>
<?php  
if(isset($isProfile) && !$showSteps) { ?>
	<div class="button-row">
		<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveCompanyInformation();">
				<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
		</button>
		<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
				<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
		</button>
	</div>
		
	<div class="clear"></div>		
<?php  
} ?>

<?php 
if($showSteps) { ?>
	<div id="process-container" class="process-container">
		<ol class="process-steps">
			<li class="is-complete dir-icon-inbox" data-step="1">
				<p><?php echo JText::_("LNG_CHOOSE_PACKAGE")?></p>
			</li>
			<li class="is-complete dir-icon-user" data-step="2">
				<p><?php echo JText::_("LNG_BASIC_INFO")?></p>
			</li>
			<li class="progress__last is-active dir-icon-file-text-o" data-step="3">
				<p><?php echo JText::_("LNG_LISTING_INFO")?></p>
			</li>
		</ol>
		<div class="clear"></div>
	</div>
<?php 
} ?>
	
<div class="category-form-container <?php if(!$showSteps) { echo 'company-form-container'; } ?>">
	<div class="clr mandatory">
		<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
	</div>
	<?php 
	if ($showSteps) { ?>
		<div id="process-steps" class="process-steps" style="display:none"> 
			<div id="step1" class="process-step">
				<div class="step">
					<div class="step-number">1</div>
				</div>
				<?php echo JText::_("LNG_STEP_1")?>
			</div>
			<div id="step2" class="process-step">
				<div class="step">
					<div class="step-number">2</div>
				</div>
				<?php echo JText::_("LNG_STEP_2")?>
			</div>
			<div id="step3" class="process-step">
				<div class="step">
					<div class="step-number">3</div>
				</div>
				<?php echo JText::_("LNG_STEP_3")?>
			</div>
			<div id="step4" class="process-step">
				<div class="step">
					<div class="step-number">4</div>
				</div>
				<?php echo JText::_("LNG_STEP_4")?>
			</div>
			<?php  
			if(((!$enablePackages || (!empty($this->item->package->features) && in_array(SOCIAL_NETWORKS,$this->item->package->features))) 
				|| (!$enablePackages || (!empty($this->item->package->features) && in_array(VIDEOS,$this->item->package->features)))
				|| (!$enablePackages || (!empty($this->item->package->features) && in_array(IMAGE_UPLOAD,$this->item->package->features))))){ ?>
				<div id="step5" class="process-step">
					<div class="step">
						<div class="step-number">5</div>
					</div>
					<?php echo JText::_("LNG_STEP_5")?>
				</div>
			<?php 
			} ?>
			<div id="steps-info" class="steps-info">
				<div id="active-step" class="step active-step">
					<div class="step-number" id="active-step-number">1</div>
				</div>
				<div class="step-divider">/</div>
				<div id="step" class="step">
					<div id="max-tabs" class="step-number">5</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="process-tabs" class="process-tabs">
			<div id="tab1" class="process-tab">
				<i class="dir-icon-info"></i>
				<span><?php echo JText::_("LNG_TAB_1")?></span>
			</div>
			<div id="tab2" class="process-tab">
				<i class="dir-icon-database"></i>
				<span><?php echo JText::_("LNG_TAB_2")?></span>
			</div>
			<div id="tab3" class="process-tab">
				<i class="dir-icon-map-marker"></i>
				<span><?php echo JText::_("LNG_TAB_3")?></span>
			</div>
			<div id="tab4" class="process-tab">
				<i class="dir-icon-phone"></i>
				<span><?php echo JText::_("LNG_TAB_4")?></span>
			</div>
			<?php  if(((!$enablePackages || (!empty($this->item->package->features) && in_array(SOCIAL_NETWORKS,$this->item->package->features)))
			    || (!$enablePackages || (!empty($this->item->package->features) && in_array(VIDEOS,$this->item->package->features)))
				||(!$enablePackages || (!empty($this->item->package->features) && in_array(IMAGE_UPLOAD,$this->item->package->features))))){ ?>
			
				<div id="tab5" class="process-tab">
					<i class="dir-icon-rss"></i>
					<span><?php echo JText::_("LNG_TAB_5")?></span>
				</div>
			<?php 
			} ?>
		</div>
	<?php } ?>
	<div class="edit-container">
		<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
			<div class="jbd-admin-column <?php if(!$showSteps) { echo 'span6'; } ?>">
				<?php 
				if($enablePackages && !$showSteps) { ?>
					<fieldset class="boxed package">
						<div class="package_content">
							<label><?php echo JText::_('LNG_UPGRADE_PACKAGE')?></label> 
							<select name="filter_package" class="inputbox input-medium" onchange="this.form.submit()">
								<?php echo JHtml::_('select.options', $this->packageOptions, 'value', 'text', $this->state->get('company.packageId'));?>
							</select>
							<br><br>
							<p>
								<?php echo JText::_('LNG_CURRENT_PACKAGE')?>: <?php echo $this->item->package->name ?><br/>
								<?php 
								if(isset($this->item->paidPackage)){ ?>
									<?php echo JText::_('LNG_STATUS')?>: <?php echo !$this->item->paidPackage->expired ? JText::_("LNG_VALID"): JText::_("LNG_EXPIRED") ?> 
									<br/> 
									<?php echo JText::_('LNG_START_DATE')?>: <?php echo JBusinessUtil::getDateGeneralFormat($this->item->paidPackage->start_date) ?> <br/>
									<?php echo JText::_('LNG_EXPIRATION_DATE')?>: <?php echo JBusinessUtil::getDateGeneralFormat($this->item->paidPackage->expirationDate) ?><a href="javascript:extendPeriod()"> <?php echo JText::_("LNG_EXTEND_PERIOD")?></a>
								<?php 
								} else {?>
									<?php echo JText::_('LNG_STATUS')?>: <?php echo $this->item->package->price == 0? JText::_("LNG_FREE"):JText::_("LNG_NOT_PAID") ?>
								<?php 
								} ?>
							</p>
							<?php 
							if(!isset($this->item->paidPackage) && isset($this->item->lastActivePackage)) { ?>
								<div class="package-info">
									<?php echo JText::_('LNG_LAST_PAID_PACKAGE')?>: <?php echo $this->item->lastActivePackage->name ?><br/>
									<?php echo JText::_('LNG_STATUS')?>: <?php echo !$this->item->lastActivePackage->expired ? JText::_("LNG_VALID"): JText::_("LNG_EXPIRED") ?><br/>
									<?php echo JText::_('LNG_START_DATE')?>: <?php echo JBusinessUtil::getDateGeneralFormat($this->item->lastActivePackage->start_date) ?> <br/>
									<?php echo JText::_('LNG_EXPIRATION_DATE')?>: <?php echo JBusinessUtil::getDateGeneralFormat($this->item->lastActivePackage->expirationDate) ?> <a href="javascript:extendPeriod()"> <?php echo JText::_("LNG_EXTEND_PERIOD")?></a>
								</div>
							<?php 
							} ?>
						</div>
					</fieldset>
				<?php 
				} else { 
					$packageId = JRequest::getVar("filter_package");
					if(empty($packageId) && !empty($this->item->package)){
						$packageId = $this->item->package->id;
					}
				?>
					<input type="hidden" name="filter_package" id="filter_package" value="<?php echo $packageId ?>"/>
				<?php 
				} ?>
				<div id="edit-tab1" class="edit-tab">
					<fieldset class="boxed">
						<h2> <?php echo JText::_('LNG_COMPANY_DETAILS');?></h2>
						<p><?php echo JText::_('LNG_DISPLAY_INFO_TXT');?></p>
						<div class="form-box">
							<div class="detail_box">

								<?php
							if($attributeConfig["company_type"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<div class="detail_box">
									<?php if($attributeConfig["company_type"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="shop_class"><?php echo JText::_('特約店/優惠店')?> </label>
									<select data-placeholder="<?php echo JText::_("選擇特約店家 / 優惠店家") ?>" class="input_sel <?php echo $attributeConfig["company_type"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> select chosen-select" name="shop_class" id="shop_class">
									<?php if ($this->item->shop_class == 0) {
								 	?>
										<option value="1" >特約店家</option>
										<option value="2" >優惠店家</option>
									<?php } ?>
									<?php if ($this->item->shop_class == 1) {
								 	?>
										<option value="1" selected>特約店家</option>
										<option value="2" >優惠店家</option>
									<?php } ?>
									<?php if ($this->item->shop_class == 2) {
								 	?>
										<option value="1">特約店家</option>
										<option value="2" selected>優惠店家</option>
									<?php } ?>
									</select>
									<div class="clear"></div>
									<span class="error_msg" id="frmCompanyType_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							<?php
							} ?>

								<div  class="form-detail req"></div>
								<label for="name"><?php echo JText::_('LNG_COMPANY_NAME')?> </label>
								<?php
								if($this->appSettings->enable_multilingual){
									echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
									foreach( $this->languages as $k=>$lng ){
										echo JHtml::_('tabs.panel', $lng, 'tab-'.$lng );
										$langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
										if($lng == JFactory::getLanguage()->getTag() && empty($langContent)){
											$langContent = $this->item->name;
										}
										echo "<input type='text' name='name_$lng' id='name_$lng' class='input_txt validate[required]' value=\"".stripslashes($langContent)."\"  maxLength='100'>";
										echo "<div class='clear'></div>";
									}
									echo JHtml::_('tabs.end');
								} else { ?>
									<input type="text" name="name" id="name" class="input_txt validate[required]" value="<?php echo $this->item->name ?>"  maxLength="100">
								<?php } ?>
								<div class="clear"></div>
								<span class="error_msg" id="company_exists_msg" style="display: none;"><?php echo JText::_('LNG_COMPANY_NAME_ALREADY_EXISTS')?></span>
								<span class="" id="claim_company_exists_msg" style="display: none;"><?php echo JText::_('LNG_CLAIM_COMPANY_EXISTS')?> <a id="claim-link" href=""><?php echo JText::_("LNG_HERE")?></a></span>
								<span class="error_msg" id="frmCompanyName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</div>
							<div class="detail_box" style="<?php echo isset($isProfile)?"display:none":""?>">
								<label for="name"><?php echo JText::_('LNG_ALIAS')?> </label> 
								<input type="text"	name="alias" id="alias"  placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="input_txt text-input" value="<?php echo $this->item->alias ?>">
								<div class="clear"></div>
							</div>
							<?php 
							if($attributeConfig["comercial_name"]!=ATTRIBUTE_NOT_SHOW){?>
								<div class="detail_box">
									<?php if($attributeConfig["comercial_name"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="comercialName"><?php echo JText::_('LNG_COMPANY_COMERCIAL_NAME')?> </label>
									<input type="text"
										name="comercialName" id="comercialName" class="input_txt <?php echo $attributeConfig["comercial_name"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->comercialName ?>">
									<div class="clear"></div>
									<span class="error_msg" id="frmCompanyComercialName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["tax_code"]!=ATTRIBUTE_NOT_SHOW){ ?>
								<div class="detail_box">
									<?php if($attributeConfig["tax_code"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="taxCode"><?php echo JText::_('LNG_TAX_CODE')?> </label>
									<input type="text" name="taxCode" id="taxCode" class="input_txt <?php echo $attributeConfig["tax_code"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->taxCode ?>">
									<div class="clear"></div>
									<span class="error_msg" id="frmTaxCode_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["registration_code"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<div class="detail_box">
									<?php if($attributeConfig["registration_code"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="registrationCode"><?php echo JText::_('LNG_REGISTRATION_CODE')?> </label>
									<input type="text"
										name="registrationCode" id="registrationCode" class="input_txt <?php echo $attributeConfig["registration_code"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" 	value="<?php echo $this->item->registrationCode ?>">
									<div class="clear"></div>
									<span class="error_msg" id="frmRegistrationCode_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["website"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<?php 
								if(!$enablePackages || isset($this->item->package->features) && in_array(WEBSITE_ADDRESS,$this->item->package->features)) { ?>
									<div class="detail_box">
										<?php if($attributeConfig["website"] == ATTRIBUTE_MANDATORY){?>
											<div  class="form-detail req"></div>
										<?php }?>
										<label for="website"><?php echo JText::_('LNG_WEBSITE')?> </label>
										<input type="text" name="website" id="website" value="<?php echo $this->item->website ?>"	class="input_txt <?php echo $attributeConfig["website"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>">
										<div class="clear"></div>
									</div>
								<?php 
								} ?>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["company_type"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<div class="detail_box">
									<?php if($attributeConfig["company_type"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="companyTypes"><?php echo JText::_('LNG_COMPANY_TYPE')?> </label> 
									<select data-placeholder="<?php echo JText::_("LNG_SELECT_COMPANYTYPE") ?>" class="input_sel <?php echo $attributeConfig["company_type"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> select chosen-select" name="typeId" id="companyTypes">
										<?php
										foreach( $this->item->types as $type ) { ?>
											<option <?php echo $this->item->typeId==$type->id? "selected" : ""?> value='<?php echo $type->id?>'><?php echo $type->name ?></option>
										<?php
										} ?>
									</select>
									<div class="clear"></div>
									<span class="error_msg" id="frmCompanyType_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["slogan"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<div class="detail_box">
									<?php if($attributeConfig["slogan"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="slogan"><?php echo JText::_("LNG_COMPANY_SLOGAN")?> &nbsp;&nbsp;&nbsp;</label>
									<p class="small"><?php echo JText::_("LNG_COMPANY_SLOGAN_INFO")?></p>
									<?php 
									if($this->appSettings->enable_multilingual) {
										echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
										foreach($this->languages  as $k=>$lng ) {
											echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
											$langContent = isset($this->translationsSlogan[$lng])?$this->translationsSlogan[$lng]:"";
											if($lng==JFactory::getLanguage()->getTag() && empty($langContent)){
												$langContent = $this->item->slogan;
											}
											echo "<textarea id='slogan_$lng' name='slogan_$lng' class='input_txt' cols='75' rows='5' maxLength='".COMPANY_SLOGAN_MAX_LENGHT."'>$langContent</textarea>";
											echo "<div class='clear'></div>";
										}
										echo JHtml::_('tabs.end');
									} else { ?>
										<textarea name="slogan" id="slogan" class="input_txt text-input <?php echo $attributeConfig["slogan"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>"  cols="75" rows="5"  maxLength="<?php echo COMPANY_SLOGAN_MAX_LENGHT?>"><?php echo $this->item->slogan ?></textarea>
									<?php 
									} ?>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["short_description"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<div class="detail_box">
									<?php if($attributeConfig["short_description"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="description_id"><?php echo JText::_("LNG_COMPANY")." ".JText::_('LNG_SHORT_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
									<p class="small"><?php echo JText::_("LNG_COMPANY_SHORT_DESCR_INFO")?></p>
									<?php 
									if($this->appSettings->enable_multilingual) {
										echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
										foreach( $this->languages  as $k=>$lng ) {
											echo JHtml::_('tabs.panel', $lng, 'tab'.$k );
											$langContent = isset($this->translations[$lng."_short"])?$this->translations[$lng."_short"]:"";
											if($lng==JFactory::getLanguage()->getTag() && empty($langContent)) {
												$langContent = $this->item->short_description;
											}
											echo "<textarea id='short_description_$lng' name='short_description_$lng' class='input_txt' cols='75' rows='5' maxLength='".COMPANY_SHORT_DESCRIPTIION_MAX_LENGHT."'>$langContent</textarea>";
											echo "<div class='clear'></div>";
										}
										echo JHtml::_('tabs.end');
									} else { ?>
										<textarea name="short_description" id="short_description" class="input_txt <?php echo $attributeConfig["short_description"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input"  cols="75" rows="5"  maxLength="<?php echo COMPANY_SHORT_DESCRIPTIION_MAX_LENGHT?>" onkeyup="calculateLenghtShort();"><?php echo $this->item->short_description ?></textarea>
										<div class="clear"></div>
										<span class="error_msg" id="frmDescription_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
										<div class="description-counter">	
											<input type="hidden" name="descriptionMaxLenghtShort" id="descriptionMaxLenghtShort" value="<?php echo COMPANY_SHORT_DESCRIPTIION_MAX_LENGHT?>" />	
											<label for="decriptionCounterShort">(Max. <?php echo COMPANY_SHORT_DESCRIPTIION_MAX_LENGHT?> <?php JText::_('LNG_CHARACTRES')?>).</label>
											<?php echo JText::_('LNG_REMAINING')?><input type="text" value="0" id="descriptionCounterShort" name="descriptionCounterShort">			
										</div>
									<?php 
									} ?>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["description"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<div class="detail_box">
									<?php if($attributeConfig["description"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="description_id"><?php echo JText::_("LNG_COMPANY")." ".JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
									<p class="small"><?php echo JText::_("LNG_COMPANY_DESCR_INFO")?></p>
									<?php 
									if($this->appSettings->enable_multilingual) {
										echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
										foreach( $this->languages  as $k=>$lng ) {
											echo JHtml::_('tabs.panel', $lng, 'tab_description_'.$lng );
											$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
											if($lng==JFactory::getLanguage()->getTag() && empty($langContent)) {
												$langContent = $this->item->description;
											}
											if(!$enablePackages || isset($this->item->package->features) && in_array(HTML_DESCRIPTION,$this->item->package->features)) {
												$editor = JFactory::getEditor();
												echo $editor->display('description_'.$lng, $langContent, '95%', '200', '70', '10', false);
											} else {
												echo "<textarea id='description_$lng' name='description_$lng' class='input_txt' cols='75' rows='10' maxLength='".COMPANY_DESCRIPTIION_MAX_LENGHT."'>$langContent</textarea>";
												echo "<div class='clear'></div>";
											}
										}
										echo JHtml::_('tabs.end');
									} else {
										if(!$enablePackages || isset($this->item->package->features) && in_array(HTML_DESCRIPTION,$this->item->package->features)) {
											$editor = JFactory::getEditor();
											echo $editor->display('description', $this->item->description, '95%', '200', '70', '10', false);
										} else { ?>
											<textarea name="description" id="description" class="input_txt <?php echo $attributeConfig["description"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input"  cols="75" rows="10"  maxLength="<?php echo COMPANY_DESCRIPTIION_MAX_LENGHT?>" onkeyup="calculateLenght();"><?php echo $this->item->description ?></textarea>
											<div class="clear"></div>
											<span class="error_msg" id="frmDescription_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
											<div class="description-counter">	
												<input type="hidden" name="descriptionMaxLenght" id="descriptionMaxLenght" value="<?php echo COMPANY_DESCRIPTIION_MAX_LENGHT?>" />	
												<label for="descriptionCounter">(Max. <?php echo COMPANY_DESCRIPTIION_MAX_LENGHT?> characters).</label>
												<?php echo JText::_('LNG_REMAINING')?><input type="text" value="0" id="descriptionCounter" name="descriptionCounter">			
											</div>
										<?php 
										} ?>
									<?php 
									} ?>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["keywords"]!=ATTRIBUTE_NOT_SHOW) { ?>
								<div class="bootstrap-tags detail_box">
									<?php if($attributeConfig["keywords"] == ATTRIBUTE_MANDATORY){?>
										<div class="form-detail req"></div>
									<?php }?>
									<label for="keywords"><?php echo JText::_('LNG_KEYWORDS')?> </label> 
									<p class="small"><?php echo JText::_('LNG_COMPANY_KEYWORD_INFO')?></p>
									<input type="text" data-role="tagsinput" name="keywords" class="input_txt <?php echo $attributeConfig["keywords"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" id="keywords" value="<?php echo $this->item->keywords ?>"/>
									<div class="clear"></div>
								</div>
							<?php 
							} ?>
						</div>
					</fieldset>
					<?php 
					if($this->appSettings->enable_attachments == 1  && ((!$enablePackages || isset($this->item->package->features) && in_array(ATTACHMENTS,$this->item->package->features)))) { ?>
						<?php 
						if($attributeConfig["attachments"]!=ATTRIBUTE_NOT_SHOW) { ?>
							<fieldset class="boxed">
								<h2> <?php echo JText::_('LNG_ATTACHMENTS');?> </h2>
								<p> <?php echo JText::_('LNG_ATTACHMENTS_INFORMATION_TEXT');?>.</p>
								<input type='button' name='btn_removefile_at' id='btn_removefile_at' value='x' style='display:none'>
								<input type='hidden' name='crt_pos_a' id='crt_pos_a' value=''>
								<input type='hidden' name='crt_path_a' id='crt_path_a' value=''>
								<TABLE class='picture-table' align='left' border='0'>
									<TR>
										<TD>
											<TABLE class="admintable" align='left' id='table_attachments' name='table_ttachments' >
												<?php 
												if(!empty($this->item->attachments))
													foreach( $this->item->attachments as $attachment ) { ?>
														<TR>
															<TD align='left'>
																<input type="text" name='attachment_name[]' id='attachment_name' value="<?php echo $attachment->name ?>" /><br/>
																<?php echo basename($attachment->path)?>
															</TD>
															<td align='center'>
																<img class='btn_attachment_delete' 
																	src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>'
																	onclick=" 
																		if(!confirm('<?php echo JText::_('LNG_CONFIRM_DELETE_ATTACHMENT',true)?>')) 
																			return; 
																		var row = jQuery(this).parents('tr:first');
																		var row_idx = row.prevAll().length;
																		jQuery('#crt_pos_a').val(row_idx);
																		jQuery('#crt_path_a').val('<?php echo $attachment->path?>');
																		jQuery('#btn_removefile_at').click();"/>
															</td>
															<td align='center'>
																<input type='hidden' value='<?php echo $attachment->status?>' name='attachment_status[]' id='attachment_status'>
																<input type='hidden' value='<?php echo $attachment->path?>' name='attachment_path[]' id='attachment_path'>
																<img class='btn_attachment_status' 
																	src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($attachment->status ? 'checked' : 'unchecked').".gif"?>'
																	onclick="
																		var form = document.adminForm;
																		var v_status = null;
																		var pos = jQuery(this).closest('tr')[0].sectionRowIndex;

																		if( form.elements['attachment_status[]'].length == null ) {
																			v_status  = form.elements['attachment_status[]'];
																		}
																		else {
																			v_status  = form.elements['attachment_status[]'][pos];
																		}
																		if( v_status.value == '1') {
																			jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/unchecked.gif"?>');
																			v_status.value ='0';
																		}
																		else {
																			jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>');
																			v_status.value ='1';
																		}" />
															</td>
															<td align="center">
																<span class="span_up" onclick='var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());'>
																	<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/up-icon.png">
																</span>
																<span class="span_down" onclick='var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());'>
																	<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/down-icon.png">
																</span>
															</td>
														</TR>
													<?php 
													} ?>
											</TABLE>
										</TD>
									</TR>
									<TR>
										<TD colspan ="2">
											<?php echo JText::_('LNG_PLEASE_CHOOSE_A_FILE'); ?> <input name="uploadAttachment" id="multiFileUploader" size="50" type="file" />
										</TD>
									</TR>
								</TABLE>
							</fieldset>
						<?php 
						} ?>
					<?php 
					} ?>

					<?php
					if($attributeConfig["opening_hours"]!=ATTRIBUTE_NOT_SHOW) { ?>
						<?php
						if(!$enablePackages || isset($this->item->package->features) && in_array(OPENING_HOURS,$this->item->package->features)) { ?>
						<fieldset class="boxed">
							<fieldset class="fieldset-business_hours">
								<label for="business_hours"><i class="icon-plus-circle"></i><?php echo JText::_('LNG_OPENING_HOURS');?> <small> <?php $attributeConfig["opening_hours"]=ATTRIBUTE_OPTIONAL?"(". JText::_('LNG_OPTIONAL').")":"" ?></small></label>
								<div class="field">
									<table>
										<tr>
											<th width="50%">&nbsp;</th>
											<th align="left"><?php echo JText::_("LNG_OPEN")?></th>
											<th align="left"><?php echo JText::_("LNG_CLOSE")?></th>
										</tr>

										<?php $dayNames = array(JText::_("MONDAY"),JText::_("TUESDAY"),JText::_("WEDNESDAY"),JText::_("THURSDAY"),JText::_("FRIDAY"),JText::_("SATURDAY"),JText::_("SUNDAY")); ?>
										<?php
										foreach($dayNames as $index=>$day){?>
											<tr>
												<td align="left"><?php echo $day?></td>
												<td align="left" class="business-hour"><input type="text" class="timepicker regular-text" name="business_hours[]" value="<?php echo !empty($this->item->business_hours)?$this->item->business_hours[$index*2]:"" ?>"/></td>
												<td align="left" class="business-hour"><input type="text" class="timepicker regular-text" name="business_hours[]" value="<?php echo !empty($this->item->business_hours)?$this->item->business_hours[$index*2+1]:"" ?>"/></td>
											</tr>
										<?php
										} ?>
									</table>
								</div>
							</fieldset>
						</fieldset>
						<?php } ?>
					<?php } ?>

					<?php 
					if($attributeConfig["logo"]!=ATTRIBUTE_NOT_SHOW){?>
						<?php 
						if(!$enablePackages || isset($this->item->package->features) && in_array(SHOW_COMPANY_LOGO,$this->item->package->features)) { ?>
							<fieldset  class="boxed">
								<div class="form-box">
									<?php if($attributeConfig["logo"] == ATTRIBUTE_MANDATORY){?>
										<div class="detail_box">
											<div class="form-detail req"></div>
										</div>
									<?php } ?>
									<h2> <?php echo JText::_('LNG_ADD_LOGO');?></h2>
									<div>
										<?php echo JText::_('LNG_ADD_LOGO_TEXT');?>									
									</div>			
									<div class="form-upload-elem">
										<div class="form-upload">
											<label class="optional" for="logo"><?php echo JText::_("LNG_SELECT_IMAGE_TYPE") ?>.</label>
											<p class="hint"><?php echo JText::_('LNG_LOGO_MAX_SIZE');?></p>
											<input type="text" style="visibility:hidden;height:1px;" name="logoLocation"  id="imageLocation"  class="input_txt <?php echo $attributeConfig["logo"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> "><br/>
											<input type="hidden" id="MAX_FILE_SIZE" value="2097152" name="MAX_FILE_SIZE">
											<input type="file" id="imageUploader" name="uploadLogo" size="50">		
											<div class="clear"></div>
											<a href="javascript:removeLogo()"><?php echo JText::_("LNG_REMOVE_LOGO")?></a>
										</div>					
										<div class="info">
											<?php if($attributeConfig["logo"]==ATTRIBUTE_OPTIONAL){ ?>
												<p class="small">
													<?php echo JText::_('LNG_ADD_LOGO_CONTINUE');?> 
												</p>
											<?php } ?>
										</div>
									</div>
									<div class="picture-preview" id="picture-preview">
										<?php
											if(!empty($this->item->logoLocation)){
												echo "<img src='".JURI::root().PICTURES_PATH.$this->item->logoLocation."'/>";
											}
										?>
									</div>
									<div class="clear"></div>
									<span class="error_msg" id="frmCompanyName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</fieldset>
						<?php 
						} ?>
					<?php 
					} ?>

					<?php
					if($attributeConfig["cover_image"]!=ATTRIBUTE_NOT_SHOW){ ?>
						<fieldset  class="boxed">
							<div class="form-box">
								<h2> <?php echo JText::_('LNG_ADD_BUSINESS_COVER_IMAGE');?> <?php $attributeConfig["cover_image"]=ATTRIBUTE_OPTIONAL?"(". JText::_('LNG_OPTIONAL').")":"" ?></h2>
								<div>
									<?php echo JText::_('LNG_ADD_BUSINESS_COVER_IMAGE_TEXT');?>
								</div>
								<div class="form-upload-elem">
									<div class="form-upload">
										<label class="optional" for="logo"><?php echo JText::_("LNG_SELECT_IMAGE_TYPE") ?>.</label>
										<input type="hidden" name="business_cover_image" id="cover-imageLocation" value="<?php echo $this->item->business_cover_image?>">
										<input type="file" id="cover-imageUploader" name="uploadfile" size="50">
										<div class="clear"></div>
										<a href="javascript:removeCoverImage()"><?php echo JText::_("LNG_REMOVE_BUSINESS_COVER_IMAGE")?></a>
									</div>
								</div>
								<div class="picture-preview" id="cover-picture-preview">
									<?php
									if(!empty($this->item->business_cover_image)){
										echo "<img src='".JURI::root().PICTURES_PATH.$this->item->business_cover_image."'/>";
									}
									?>
								</div>
								<div class="clear"></div>
								<span class="error_msg" id="frmCompanyName_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</div>
						</fieldset>
					<?php } ?>

					<?php 
					if(!empty($this->item->customFields) && $this->item->containsCustomFields){?>
						<fieldset class="boxed">
							<h2> <?php echo JText::_('LNG_ADDITIONAL_INFO');?></h2>
							<p><?php echo JText::_('LNG_ADDITIONAL_INFO_TEXT');?></p>
							<div class="form-box">
								<?php 
								$packageFeatures = !empty($this->item->package->features)?$this->item->package->features:null;
								$renderedContent = AttributeService::renderAttributes($this->item->customFields, $enablePackages, $packageFeatures);
								echo $renderedContent;
								?>
							</div>
						</fieldset>
					<?php 
					} ?>
				</div>
				
				<div id="edit-tab2" class="edit-tab">
					<?php if($attributeConfig["category"]!=ATTRIBUTE_NOT_SHOW){?>
						<fieldset class="boxed">
							<h2> <?php echo JText::_('LNG_COMPANY_CATEGORIES');?></h2>
							<p><?php echo JText::_('LNG_SELECT_CATEGORY');?></p>
							<div class="form-box">
								<div class="detail_box">
									<?php if($attributeConfig["category"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="category"><?php echo JText::_('LNG_CATEGORY');?></label>
										<select name="selectedSubcategories[]" id="selectedSubcategories" data-placeholder="<?php echo JText::_("LNG_SELECT_CAT") ?>" class="inputbox input-medium chosen-select-categories" multiple>
											<?php echo JHtml::_('select.options', $this->categoryOptions, 'value', 'text', $this->item->selCats);?>
										</select>
									<div class="clear"></div>
								</div>
								<div class="detail_box">
									<?php if($attributeConfig["category"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="subcat_main_id"><?php echo JText::_('LNG_MAIN_SUBCATEGORY');?></label>
									<select data-placeholder="<?php echo JText::_("LNG_SELECT_CAT") ?>" class="input_sel select <?php echo $attributeConfig["category"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" name="mainSubcategory" id="mainSubcategory">
									<?php foreach( $this->item->selectedCategories as $selectedCategory){?>
												<option value="<?php echo $selectedCategory->id ?>" <?php echo $selectedCategory->id == $this->item->mainSubcategory ? "selected":"" ; ?>><?php echo $selectedCategory->name ?></option>
										<?php } ?> 
									</select>
									<div class="clear"></div>
									<span class="error_msg" id="frmMainSubcategory_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							</div>
						<?php }?>
					</fieldset>
					<?php 
					if($this->appSettings->limit_cities == 1) { ?>
						<fieldset class="boxed">
							<h2><?php echo JText::_('LNG_ACTIVITY_CITIES');?></h2>
							<p><?php echo JText::_('LNG_ACTIVITY_CITIES_INFO');?>.</p>
							<div class="form-box">
								<div class="detail_box">
									<label for="activity_cities"><?php echo JText::_('LNG_SELECT_ACTIVITY_CITY')?></label> 
									<select multiple="multiple" id="activity_cities" class="inputbox input-medium chosen-select-categories" name="activity_cities[]">
										<?php
										foreach( $this->item->cities as $city ) {
											$selected = false;
											foreach($this->item->activityCities as $acity) {
												if($acity->city_id == $city->id)
													$selected = true;
											} ?>
											<option <?php echo $selected ? "selected" : ""?> value='<?php echo $city->id ?>'>
												<?php echo $city->name ?>
											</option>
										<?php
										} ?>
									</select>		
									<div class="clear"></div>
								</div>	
							</div>
						</fieldset>	
					<?php 
					} ?>
				</div>
				<div id="edit-tab3" class="edit-tab">
					<fieldset class="boxed">
						<h2> <?php echo JText::_('LNG_COMPANY_LOCATION');?></h2>
						<p><?php echo JText::_('LNG_COMPANY_LOCATION_TXT');?></p>
						<p><?php echo JText::_("LNG_ADDRESS_SUGESTION")?></p>
						<div class="form-box">
			
							<div class="detail_box">
								<label for="address_id"><?php echo JText::_('LNG_ADDRESS')?></label> 
								<input type="text" id="autocomplete" class="input_txt" placeholder="<?php echo JText::_("LNG_ENTER_ADDRESS") ?>" onFocus="" ></input>
								<div class="clear"></div>
							</div>

							<?php if($attributeConfig["street_number"]!=ATTRIBUTE_NOT_SHOW){?>
							<div class="detail_box">
								<?php if($attributeConfig["street_number"] == ATTRIBUTE_MANDATORY){?>
									<div  class="form-detail req"></div>
								<?php }?>
								<label for="address_id"><?php echo JText::_('LNG_STREET_NUMBER')?></label> 
								<input type="text" name="street_number" id="street_number" class="input_txt text-input <?php echo $attributeConfig["street_number"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->street_number ?>">
								<div class="clear"></div>
								<span class="error_msg" id="frmStreetNumber_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</div>
							<?php } ?>
							
							<?php if($attributeConfig["address"]!=ATTRIBUTE_NOT_SHOW){?>
							<div class="detail_box">
								<?php if($attributeConfig["address"] == ATTRIBUTE_MANDATORY){?>
								<div  class="form-detail req"></div>
							<?php }?>
								<label for="address_id"><?php echo JText::_('LNG_ADDRESS')?></label> 
								<input type="text" name="address" id="route" class="input_txt <?php echo $attributeConfig["address"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input" value="<?php echo $this->item->address ?>">
								<div class="clear"></div>
								<span class="error_msg" id="frmAddress_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</div>
							<?php } ?>
							
							<?php if($attributeConfig["country"]!=ATTRIBUTE_NOT_SHOW){?>
							<div class="detail_box">
								<?php if($attributeConfig["country"] == ATTRIBUTE_MANDATORY){?>
									<div  class="form-detail req"></div>
								<?php }?>
								<label for="country"><?php echo JText::_('LNG_COUNTRY')?> </label>
								<div class="clear"></div>
								<select data-placeholder="<?php echo JText::_("LNG_SELECT_COUNTRY") ?>" class="input_sel <?php echo $attributeConfig["country"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> select" name="countryId" id="country" >
										<option value=''></option>
										<?php
										foreach( $this->item->countries as $country ) { ?>
											<option <?php echo $this->item->countryId==$country->id? "selected" : ""?> value='<?php echo $country->id?>'><?php echo $country->country_name ?></option>
										<?php
										} ?>
								</select>
								<div class="clear"></div>
								<span class="error_msg" id="frmCountry_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</div>
							<?php } ?>
							
							<?php if($attributeConfig["city"]!=ATTRIBUTE_NOT_SHOW){?>
							
							<div class="detail_box">
								<?php if($attributeConfig["city"] == ATTRIBUTE_MANDATORY){?>
									<div  class="form-detail req"></div>
								<?php }?>
								<label for="city_id"><?php echo JText::_('LNG_CITY')?> </label> 
								<input class="input_txt <?php echo $attributeConfig["city"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input" type="text" name="city" id="locality" value="<?php echo $this->item->city ?>">
								<div class="clear"></div>
								<span class="error_msg" id="frmCity_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</div>
							<?php } ?>
							
							<?php if($attributeConfig["region"]!=ATTRIBUTE_NOT_SHOW){?>
							
							<div class="detail_box" id="districtContainer">
								<?php if($attributeConfig["region"] == ATTRIBUTE_MANDATORY){?>
									<div  class="form-detail req"></div>
								<?php }?>
								<label for="district_id"><?php echo JText::_('LNG_COUNTY')?> </label> 
								<input class="input_txt <?php echo $attributeConfig["region"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input" type="text" name="county" id="administrative_area_level_1" value="<?php echo $this->item->county ?>" />
								<div class="clear"></div>
								<span class="error_msg" id="frmDistrict_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</div>
							<?php } ?>
							
							<?php if($attributeConfig["postal_code"]!=ATTRIBUTE_NOT_SHOW){?>
							
							<div class="detail_box" id="districtContainer">
								<?php if($attributeConfig["postal_code"] == ATTRIBUTE_MANDATORY){?>
									<div  class="form-detail req"></div>
								<?php }?>
								<label for="district_id"><?php echo JText::_('LNG_POSTAL_CODE')?> </label> 
								<input class="input_sel <?php echo $attributeConfig["postal_code"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" type="text" name="postalCode" id="postal_code" value="<?php echo $this->item->postalCode ?>" />
								<div class="clear"></div>
							</div>
							<?php } ?>
							
							<div class="detail_box">
								
								<label for="longitude"><?php echo JText::_('LNG_PUBLISH_ONLY_CITY')?> </label>
								<input class="" type="checkbox" name="publish_only_city" id="publish_only_city" value="1" <?php echo $this->item->publish_only_city?"checked":"" ?>>
								<div class="clear"></div>
							</div>

							<?php if(!$enablePackages || isset($this->item->package->features) && in_array(GOOGLE_MAP,$this->item->package->features)){ ?>
							
							<?php if($attributeConfig["map"]!=ATTRIBUTE_NOT_SHOW){?>
							
							<div class="detail_box" id="districtContainer">
								<label for="district_id"><?php echo JText::_('LNG_ACTIVITY_RADIUS')?> </label> 
								<input class="input_sel" type="text" name="activity_radius" id="activity_radius" value="<?php echo $this->item->activity_radius ?>" />
								<div class="clear"></div>
							</div>
							
							<div class="detail_box">
								<?php if($attributeConfig["map"] == ATTRIBUTE_MANDATORY){?>
									<div  class="form-detail req"></div>
								<?php }?>
								<label for="latitude"><?php echo JText::_('LNG_LATITUDE')?> </label> 
								<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
								<input class="input_txt validate[custom[number]] <?php echo $attributeConfig["map"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" type="text" name="latitude" id="latitude" value="<?php echo $this->item->latitude ?>">
								<div class="clear"></div>
							</div>
			
							<div class="detail_box">
								<?php if($attributeConfig["map"] == ATTRIBUTE_MANDATORY){?>
									<div  class="form-detail req"></div>
								<?php }?>
								<label for="longitude"><?php echo JText::_('LNG_LONGITUDE')?> </label>
								<p class="small"><?php echo JText::_('LNG_MAP_INFO')?></p>
								<input class="input_txt validate[custom[number]] <?php echo $attributeConfig["map"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" type="text" name="longitude" id="longitude" value="<?php echo $this->item->longitude ?>">
								<div class="clear"></div>
							</div>
							
							
							
							<div id="map-container">
								<div id="company_map">
								</div>
							</div>
							<?php }?>
							<?php } ?>
						</div>
						
					</fieldset>
					<?php 
					if($this->appSettings->show_secondary_locations == 1 && !$showSteps && !empty($this->item->id)) { ?>
						<fieldset class="boxed">
							<h2> <?php echo JText::_('LNG_COMPANY_SECONDARY_LOCATIONS');?></h2>
							<p> <?php echo JText::_('LNG_COMPANY_SECONDARY_LOCATIONS_TXT');?>.</p>
							<div class="form-box" id="company-locations">
							<?php foreach ( $this->item->locations as $location){ ?>
								<div class="detail_box" id="location-box-<?php echo $location->id?>">
									<div id="location-<?php echo $location->id?>"><?php echo $location->name." - ".$location->street_number.", ".$location->address.", ".$location->city.", ".$location->county.", ".$location->country?></div>
									<a href="javascript:editLocation(<?php echo $location->id ?>)"><?php echo JText::_("LNG_EDIT") ?></a> | <a href="javascript:deleteLocation(<?php echo $location->id ?>)"><?php echo JText::_("LNG_DELETE") ?></a>
								</div>
							<?php } ?>
							</div>
							<div>
								<a href="javascript:editLocation(0)"><?php echo JText::_("LNG_ADD_NEW_LOCATION") ?></a>
							</div>
						</fieldset>
					<?php 
					} ?>
					
					<?php if($attributeConfig["custom_tab"]!=ATTRIBUTE_NOT_SHOW) { ?>
						<?php if(!$enablePackages || isset($this->item->package->features) && in_array(CUSTOM_TAB,$this->item->package->features)) { ?>
							<fieldset class="boxed">
								<h2> <?php echo JText::_('LNG_ADDITIONAL_TAB');?></h2>
								<p> <?php echo JText::_('LNG_ADDITIONAL_TAB_TXT');?></p>
								<div class="form-box">
									<div class="detail_box">
										<?php if($attributeConfig["custom_tab"] == ATTRIBUTE_MANDATORY){?>
											<div  class="form-detail req"></div>
										<?php }?>
										<label for="custom_tab_name"><?php echo JText::_('LNG_CUSTOM_TAB_NAME')?></label> 
										<input type="text" name="custom_tab_name" id="custom_tab_name" class="input_txt text-input <?php echo $attributeConfig["custom_tab"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->custom_tab_name ?>">
										<div class="clear"></div>
									</div>
									<div class="detail_box">
										<label for="custom_tab_content"><?php echo JText::_('LNG_CUSTOM_TAB_DESCRIPTION')?></label> 
										<?php 
											$editor = JFactory::getEditor();
											echo $editor->display('custom_tab_content', $this->item->custom_tab_content, '95%', '200', '70', '10', false);
										?>
										<div class="clear"></div>
									</div>
								</div>
								<span class="error_msg" id="frmCustomTab_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
							</fieldset>
						<?php } ?>
					<?php } ?>
				</div>
				<div id="edit-tab4" class="edit-tab">
					<fieldset class="boxed">
						<h2> <?php echo JText::_('LNG_COMPANY_CONTACT_INFORMATION');?></h2>
						<p> <?php echo JText::_('LNG_COMPANY_CONTACT_INFORMATION_TEXT');?></p>
						<div class="form-box">
							<?php 
							if($attributeConfig["phone"]!=ATTRIBUTE_NOT_SHOW) { ?>					
								<div class="detail_box">
									<?php if($attributeConfig["phone"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="phone"><?php echo JText::_('LNG_TELEPHONE')?></label> 
									<input type="text"	name="phone" id="phone" class="input_txt <?php echo $attributeConfig["phone"] == ATTRIBUTE_MANDATORY?"validate[required]":""?> text-input"
										value="<?php echo $this->item->phone ?>">
									<div class="clear"></div>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["mobile_phone"]!=ATTRIBUTE_NOT_SHOW) { ?>					
								<div class="detail_box">
									<?php if($attributeConfig["mobile_phone"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="phone"><?php echo JText::_('LNG_MOBILE_PHONE')?></label> 
									<input type="text"	name="mobile" id="mobile" class="input_txt <?php echo $attributeConfig["mobile_phone"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->mobile ?>">
									<div class="clear"></div>						
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["email"]!=ATTRIBUTE_NOT_SHOW) { ?>					
								<div class="detail_box">
									<?php if($attributeConfig["email"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="email"><?php echo JText::_('LNG_EMAIL')?></label> 
									<input type="text" name="email" id="email" class="input_txt <?php echo $attributeConfig["email"] == ATTRIBUTE_MANDATORY?"validate[required,custom[email]]":""?> text-input" value="<?php echo $this->item->email ?>">
									<div class="description">e.g. office@site.com</div>
									<div class="clear"></div>
									<span class="error_msg" id="frmEmail_error_msg" style="display: none;"><?php echo JText::_('LNG_REQUIRED_FIELD')?></span>
								</div>
							<?php 
							} ?>
							<?php 
							if($attributeConfig["fax"]!=ATTRIBUTE_NOT_SHOW) { ?>			
								<div class="detail_box">
									<?php if($attributeConfig["fax"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="fax"><?php echo JText::_('LNG_FAX')?></label> 
									<input type="text" name="fax" id="fax" class="input_txt <?php echo $attributeConfig["fax"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->fax ?>">				
									<div class="clear"></div>
								</div>
							<?php 
							} ?>
						</div>
					</fieldset>
					<?php 
					if($attributeConfig["contact_person"]!=ATTRIBUTE_NOT_SHOW) { ?>			
						<fieldset class="boxed">
							<h2> <?php echo JText::_('LNG_COMPANY_CONTACT_PERSON_INFORMATION');?></h2>
							<p> <?php echo JText::_('LNG_COMPANY_CONTACT_PERSON_INFORMATION_TEXT');?></p>
							<div class="form-box">
								<div class="detail_box">
									<?php if($attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="contact_name"><?php echo JText::_('LNG_NAME')?></label> 
									<input type="text" name="contact_name" id="contact_name" class="input_txt <?php echo $attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->contact->contact_name ?>">				
									<div class="clear"></div>
								</div>	
								<div class="detail_box">
									<?php if($attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="contact_email"><?php echo JText::_('LNG_EMAIL')?></label> 
									<input type="text" name="contact_email" id="contact_email" class="input_txt <?php echo $attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>"
										value="<?php echo $this->item->contact->contact_email ?>">
										<div class="description">e.g. office@site.com</div>
									<div class="clear"></div>
								</div>
								<div class="detail_box">
									<?php if($attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="contact_phone"><?php echo JText::_('LNG_TELEPHONE')?></label> 
									<input type="text" name="contact_phone" id="contact_phone" class="input_txt <?php echo $attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>"
										value="<?php echo $this->item->contact->contact_phone ?>">
									<div class="clear"></div>
								</div>
								<div class="detail_box">
									<?php if($attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY){?>
										<div  class="form-detail req"></div>
									<?php }?>
									<label for="contact_fax"><?php echo JText::_('LNG_FAX')?></label> 
									<input type="text" name="contact_fax" id="contact_fax" class="input_txt <?php echo $attributeConfig["contact_person"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->contact->contact_fax ?>">				
									<div class="clear"></div>
								</div>
							</div>
						</fieldset>
					<?php 
					} ?>
				</div>
				<?php 
				if(!$showSteps || ((!$enablePackages || (isset($this->item->package->features) && in_array(SOCIAL_NETWORKS,$this->item->package->features)))
				    || (!$enablePackages || (isset($this->item->package->features) && in_array(VIDEOS,$this->item->package->features)))
					||(!$enablePackages || (isset($this->item->package->features) && in_array(IMAGE_UPLOAD,$this->item->package->features))))){ ?>
					<div id="edit-tab5"  class="edit-tab">			
						<?php 
						if($attributeConfig["pictures"]!=ATTRIBUTE_NOT_SHOW) {
							if(!$enablePackages || isset($this->item->package->features) && in_array(IMAGE_UPLOAD,$this->item->package->features)) { ?>
								<fieldset class="boxed">
									<h2> <?php echo JText::_('LNG_COMPANY_PICTURES');?></h2>
									<p> <?php echo JText::_('LNG_COMPANY_PICTURE_INFORMATION_TEXT');?></p>
									<input type='button' name='btn_removefile' id='btn_removefile' value='x' style='display:none'>
									<input type='hidden' name='crt_pos' id='crt_pos' value=''>
									<input type='hidden' name='crt_path' id='crt_path' value=''>
									<input type='hidden' name='images_included' value="1">
									<TABLE class='picture-table' align='left' border='0'>
										<TR>
											<TD>
												<TABLE class="admintable" align='center'  id='table_pictures' name='table_pictures'>
													<?php
													foreach( $this->item->pictures as $picture ) { ?>
														<TR>
															<td>
																<img class='img_picture' src='<?php echo JURI::root().PICTURES_PATH.$picture['picture_path']?>'/>
																<?php echo basename($picture['picture_path'])?>
																<input type='hidden' value='<?php echo $picture['picture_enable']?>' name='picture_enable[]' id='picture_enable'>
																<input type='hidden' value='<?php echo $picture['picture_path']?>' name='picture_path[]' id='picture_path'>
																<br>
																<textarea cols='50' rows='1' name='picture_info[]' id='picture_info'><?php echo $picture['picture_info']?></textarea>
															</td>
															<td align='center'>
																<img class='btn_picture_delete' 
																	src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_options.gif"?>'
																	onclick="
																		if(!confirm('<?php echo JText::_('LNG_CONFIRM_DELETE_PICTURE',true)?>')) 
																			return; 
																		var row = jQuery(this).parents('tr:first');
																		var row_idx = row.prevAll().length;
																		jQuery('#crt_pos').val(row_idx);
																		jQuery('#crt_path').val('<?php echo $picture['picture_path']?>');
																		jQuery('#btn_removefile').click();"/>
															</td>
															<td align='center'>
																<img class='btn_picture_status' 
																	src='<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/".($picture['picture_enable'] ? 'checked' : 'unchecked').".gif"?>'
																	onclick="
																		var form = document.adminForm;
																		var v_status = null;
																		var pos = jQuery(this).closest('tr')[0].sectionRowIndex;

																		if( form.elements['picture_enable[]'].length == null ) {
																			v_status  = form.elements['picture_enable[]'];
																		}
																		else {
																			v_status  = form.elements['picture_enable[]'][pos];
																		}
																		if( v_status.value == '1') {
																			jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/unchecked.gif"?>');
																			v_status.value ='0';
																		}
																		else {
																			jQuery(this).attr('src', '<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/checked.gif"?>');
																			v_status.value ='1';
																		}"/>
															</td>
															<td>
																<span class="span_up" onclick='var row = jQuery(this).parents("tr:first");  row.insertBefore(row.prev());'>
																	<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/up-icon.png">
																</span>
																<span class="span_down"onclick='var row = jQuery(this).parents("tr:first"); row.insertAfter(row.next());'>
																	<img src="<?php echo JURI::root()?>administrator/components/<?php echo JBusinessUtil::getComponentName()?>/assets/img/down-icon.png">
																</span>
															</td>
														</TR>
													<?php 
													} ?>
												</TABLE>
											</TD>
										</TR>
										<?php if($allowedNr!=0) { ?>
										<TR>
											<TD>
												<div class="dropzone dropzone-previews" id="file-upload">
													<div id="actions" style="margin-left:-15px;margin-top:-15px;" class="row">
														<div class="col-lg-7">
															<!-- The fileinput-button span is used to style the file input field as button -->
															 <span class="btn btn-success fileinput-button dz-clickable">
																<i class="glyphicon glyphicon-plus"></i>
																<span><?php echo JText::_('LNG_ADD_FILES'); ?></span>
															 </span>
															<button  class="btn btn-primary start" id="submitAll">
																<i class="glyphicon glyphicon-upload"></i>
																<span><?php echo JText::_('LNG_UPLOAD_ALL'); ?></span>
															</button>
														</div>
													</div>
												</div>
											</TD>
										</TR>
									<?php } ?>
									</TABLE>
								</fieldset>
							<?php 
							} ?>
						<?php 
						} ?>
						<?php 
						if($attributeConfig["video"]!=ATTRIBUTE_NOT_SHOW) { 
							if(!$enablePackages || isset($this->item->package->features) && in_array(VIDEOS,$this->item->package->features)) { ?>
								<fieldset class="boxed">
									<h2> <?php echo JText::_('LNG_COMPANY_VIDEOS');?></h2>
									<p> <?php echo  htmlentities(JText::_('LNG_COMPANY_VIDEO_INFORMATION_TEXT')); ?>.</p>
									<input type="hidden" name="videos-included" value="1"/>
									<div class="form-box">
										<div id="video-container">  
											<?php
											if(count($this->item->videos) == 0){?>
												<div class="detail_box" id="detailBox0">
													<label for="video1"><?php echo JText::_('LNG_VIDEO')?></label> 
													<textarea name="videos[]" id="0" class="input_txt" cols="75" rows="1"></textarea>
													<img height="12px" align="left" width="12px" 
														src="<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_icon.png"?>" alt="Delete video" onclick="removeRow('detailBox0')" style="cursor: pointer; margin: 3px;">
													<div class="clear"></div>
												</div>
											<?php 
											} ?> 
									
											<?php $index = 0;
											if(count($this->item->videos)>0)
												foreach($this->item->videos as $video) { ?>
													<div class="detail_box" id="detailBox<?php echo $index ?>">
														<?php if($attributeConfig["video"] == ATTRIBUTE_MANDATORY){?>
															<div  class="form-detail req"></div>
														<?php }?>
														<label for="<?php echo $video->id?>"><?php echo JText::_('LNG_VIDEO')?></label> 
														<textarea name="videos[]" id="<?php echo $video->id?>" class="input_txt" cols="75" rows="1"><?php echo $video->url ?></textarea>
														<img height="12px" align="left" width="12px" src="<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_icon.png"?>" alt="Delete video" onclick="removeRow('detailBox<?php echo $index++; ?>')" style="cursor: pointer; margin: 3px;">
														<div class="clear"></div>
													</div>
												<?php 
												} ?>
										</div>
										<a id="add-video" href="javascript:void(0);" onclick="addVideo()"><?php echo JText::_('LNG_ADD_VIDEO')?></a>
									</div>
								</fieldset>
							<?php 
							} ?>
						<?php 
						}?>
					
						<?php 
						if($attributeConfig["social_networks"]!=ATTRIBUTE_NOT_SHOW) { 
							if(!$enablePackages || isset($this->item->package->features) && in_array(SOCIAL_NETWORKS,$this->item->package->features)) { ?>
								<fieldset class="boxed">
									<h2> <?php echo JText::_('LNG_SOCIAL_NETWORKS');?></h2>
									<p><?php echo JText::_('LNG_SOCIAL_NETWORKS_TEXT');?></p>
									<div class="form-box">
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="facebook"><?php echo JText::_('LNG_FACEBOOK')?></label> 
											<input type="text" name="facebook" id="facebook" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->facebook ?>">
											<div class="clear"></div>
										</div>
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="twitter"><?php echo JText::_('LNG_TWITTER')?></label> 
											<input type="text" name="twitter" id="twitter" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->twitter ?>">
											<div class="clear"></div>
										</div>
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="googlep"><?php echo JText::_('LNG_GOOGLE_PLUS')?></label> 
											<input type="text" name="googlep" id="googlep" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->googlep ?>">				
											<div class="clear"></div>
										</div>
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="linkedin"><?php echo JText::_('LNG_LINKEDIN')?></label> 
											<input type="text" name="linkedin" id="linkedin" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->linkedin?>">				
											<div class="clear"></div>
										</div>
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="linkedin"><?php echo JText::_('LNG_SKYPE_ID')?></label> 
											<input type="text" name="skype" id="skype" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->skype?>">				
											<div class="clear"></div>
										</div>
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="youtube"><?php echo JText::_('LNG_YOUTUBE')?></label>
											<input type="text" name="youtube" id="youtube" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->youtube?>">
											<div class="clear"></div>
										</div>
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="youtube"><?php echo JText::_('LNG_INSTAGRAM')?></label> 
											<input type="text" name="instagram" id="instagram" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->instagram?>">				
											<div class="clear"></div>
										</div>
										
										<div class="detail_box">
											<?php if($attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY){?>
												<div  class="form-detail req"></div>
											<?php }?>
											<label for="pinterest"><?php echo JText::_('LNG_PINTEREST')?></label> 
											<input type="text" name="pinterest" id="pinterest" class="input_txt <?php echo $attributeConfig["social_networks"] == ATTRIBUTE_MANDATORY?"validate[required]":""?>" value="<?php echo $this->item->pinterest?>">				
											<div class="clear"></div>
										</div>
										
									</div>
								</fieldset>
							<?php 
							} ?>
						<?php 
						} ?>
					</div>
				<?php 
				} ?>

				<?php 
				if(!isset($isProfile)) { 
					if(!isset($this->item->userId)) {
						$this->item->userId = 0;
					}
					$companyOwner = JFactory::getUser($this->item->userId); ?>
					<fieldset class="boxed">
						<h2> <?php echo JText::_('LNG_COMPANY_USER');?></h2>
						<p>User information</p>
						<div class="form-box">
							<div class="detail_box">
								<div class="field-user-wrapper"
									data-url="index.php?option=com_users&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;required=0&amp;field={field-user-id}&amp;ismoo=0&amp;excluded=WyIiXQ=="
									data-modal=".modal"
									data-modal-width="100%"
									data-modal-height="400px"
									data-input=".field-user-input"
									data-input-name=".field-user-input-name"
									data-button-select=".button-select"
									>
										<div class="input-append">
											<label for="userId"><?php echo JText::_('LNG_USERID')?></label> 
											<input
												type="text" id="jform_created_by"
												value="<?php echo $companyOwner->name ?>"
												placeholder="Select a User."
												readonly
												class="field-user-input-name "/>
											<a class="btn btn-primary button-select" title="Select User"><span class="icon-user"></span></a>
											<div id="userModal_jform_created_by" tabindex="-1" class="modal hide fade">
											<div class="modal-header">
													<button type="button" class="close novalidate" data-dismiss="modal">?/button>
														<h3>Select User</h3>
													</div>
											<div class="modal-body">
												</div>
											<div class="modal-footer">
												<button class="btn" data-dismiss="modal">Cancel</button></div>
											</div>
										</div>
										<input type="hidden" id="jform_created_by_id" name="userId" value="<?php echo $this->item->userId ?>"  class="field-user-input " data-onchange=""/>
								</div>
							</div>
						</div>
							
					</fieldset>
				<?php 
				} ?>
			</div>
			<?php if(!$showSteps) { ?>
				<div class="jbd-admin-column span6 padding-left-15">
					<fieldset class="boxed">
						<h2> <?php echo JText::_('LNG_METADATA_INFORMATION');?></h2>
						<p> <?php echo JText::_('LNG_METADATA_INFORMATION_TEXT');?>.</p>
						<div class="form-box">
							<div class="detail_box">
								<label for="meta_title"><?php echo JText::_('LNG_META_TITLE')?></label> 
								<input type="text" name="meta_title" id="meta_title" class="input_txt" value="<?php echo $this->item->meta_title ?>" maxLength="255">
								<div class="clear"></div>
							</div>
						</div>
						<div class="form-box">
							<div class="detail_box">
								<label for="meta_description"><?php echo JText::_('LNG_META_DESCRIPTION')?></label>
								<textarea  name="meta_description" id="meta_description" rows="4" maxLength="255"><?php echo $this->item->meta_description ?></textarea>
								<div class="clear"></div>
							</div>
						</div>
					</fieldset>
				</div>
				
				<?php if(isset($this->claimDetails) && !isset($isProfile)) { ?>
				<div class="jbd-admin-column span6 padding-left-15">
					<div id="claim-details" class="claim-details-wrapper">
						<div class="claim-details">
							<p><?php echo JText::_("LNG_CLAIM_DETAILS_TEXT")?></p>
							<table>
								<tr>
									<th><?php echo JText::_('LNG_FIRST_NAME')?></th>
									<td><?php echo $this->claimDetails->firstName ?></td>
								</tr>
								<tr>
									<th><?php echo JText::_('LNG_LAST_NAME')?></th>
									<td><?php echo $this->claimDetails->lastName ?></td>
								</tr>
								<tr>
									<th><?php echo JText::_('LNG_FUNCTION')?></th>
									<td><?php echo $this->claimDetails->function ?></td>
								</tr>
								<tr>
									<th><?php echo JText::_('LNG_PHONE')?></th>
									<td><?php echo $this->claimDetails->phone ?></td>
								</tr>
								<tr>
									<th><?php echo JText::_('LNG_EMAIL_ADDRESS')?></th>
									<td><?php echo $this->claimDetails->email ?></td>
								</tr>
							</table>
							<p><?php echo JText::_("LNG_USER_DETAILS_TXT")?></p>
							<?php $claimUser = JFactory::getUser($this->item->userId); ?>
							<table>
								<tr>
									<th><?php echo JText::_('LNG_FIRST_NAME')?></th>
									<td><?php echo $claimUser->name ?></td>
								</tr>
								<tr>
									<th><?php echo JText::_('LNG_USERNAME')?></th>
									<td><?php echo $claimUser->username ?></td>
								</tr>
								<tr>
									<th><?php echo JText::_('LNG_EMAIL')?></th>
									<td><?php echo $claimUser->email ?></td>
								</tr>
							</table>
						</div>
					</div>
					<br>
				</div>
			<?php } ?>
				
			<?php } ?>
			<div class="jbd-admin-column span12">
				<?php 
				if(isset($isProfile)) { 
					if($this->item->id == 0) { ?>
						<div class="term_conditions" id="term_conditions">
							<input class="validate[required]" type='checkbox' id='accept_policies' name='accept_policies'>
							&nbsp;<a href="javascript:void(0);" onclick="showTerms()"><?php echo JText::_('LNG_AGREE_WITH_TERMS')?></a>
						</div>
					<?php 
					} ?>
					<?php
					if(!$showSteps) { ?>
						<div class="button-row">
							<button type="button" class="ui-dir-button ui-dir-button-green" onclick="saveCompanyInformation();">
								<span class="ui-button-text"><i class="dir-icon-edit"></i> <?php echo JText::_("LNG_SAVE")?></span>
							</button>
							<button type="button" class="ui-dir-button ui-dir-button-grey" onclick="cancel()">
									<span class="ui-button-text"><i class="dir-icon-remove-sign red"></i> <?php echo JText::_("LNG_CANCEL")?></span>
							</button>
						</div>
					<?php 
					} ?>
				<?php 
				} ?>
				<?php 
				if($showSteps) { ?>
					<div class="button-row">
						<button id="prev-btn" type="button" class="ui-dir-button" onclick="previousTab();">
							<span class="ui-button-text"><?php echo JText::_("LNG_PREVIOUS")?></span>
						</button>
						<button id="next-btn" type="button" class="ui-dir-button ui-dir-button-green right" onclick="nextTab()">
							<span class="ui-button-text"><?php echo JText::_("LNG_NEXT")?></span>
						</button>
						<button id="save-btn" type="button" class="ui-dir-button ui-dir-button-green right" onclick="saveCompanyInformation()">
							<span class="ui-button-text"><?php echo JText::_("LNG_SAVE")?></span>
						</button>
					</div>
				<?php 
				} ?>
			</div>

			<script  type="text/javascript">
				function saveCompanyInformation() {
					var defaultLang="<?php echo JFactory::getLanguage()->getTag() ?>";
					var evt = document.createEvent("HTMLEvents");
					evt.initEvent("click", true, true);
					var tab = ("tab-"+defaultLang);
					if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
						document.getElementsByClassName(tab)[0].dispatchEvent(evt);
					if(validateCmpForm())
						return false;
					jQuery("#task").val('managecompany.save');
					
					var form = document.adminForm;
					form.submit();
				}
				function cancel() {
					jQuery("#task").val('managecompany.cancel');
					var form = document.adminForm;
					form.submit();
				}
				function validateCmpForm() {
					<?php if((!$enablePackages || isset($this->item->package->features) && in_array(HTML_DESCRIPTION,$this->item->package->features)) && $attributeConfig["description"] == ATTRIBUTE_MANDATORY) { ?>
						validateRichTextEditors();
					<?php } ?>
					var isError = jQuery("#item-form").validationEngine('validate', {validateNonVisibleFields: true});
					return !isError;
				}

				function validateRichTextEditors(){
					var lang = '';
					<?php if($this->appSettings->enable_multilingual){ ?>
						lang = '_<?php echo JFactory::getLanguage()->getTag() ?>';
						var evt = document.createEvent("HTMLEvents");
						evt.initEvent("click", true, true);
						var tab = ("tab_description"+lang);
						if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
							document.getElementsByClassName(tab)[0].dispatchEvent(evt);
					<?php } ?>

					jQuery(".editor").each(function(){
						var textarea = jQuery(this).find('textarea');
						tinyMCE.triggerSave();
						if(textarea.attr('id') == 'description'+lang) {
							if (jQuery.trim(textarea.val()).length > 0) {
								if (jQuery(this).hasClass("validate[required]"))
									jQuery(this).removeClass("validate[required]");
							}
							else {
								if (!jQuery(this).hasClass("validate[required]"))
									jQuery(this).addClass("validate[required]");
							}
						}
					});
				}
			</script>
			<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
			<input type="hidden" name="task" id="task" value="" />
			<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
			<input type="hidden" name="exists" id="exists" value="" /> 
			<input type="hidden" name="deleted" id="deleted" value="" />
	
			<?php 
			if(isset($isProfile)) { ?>
				<input type="hidden" id="userId" name="userId" value="<?php echo $this->item->userId? $this->item->userId : $user->id ?>" />
				<input type="hidden" name="view" id="view" value="managecompany" /> 
			<?php 
			} else { ?>
				<input type="hidden" name="view" id="view" value="company" />
			<?php 
			}?>
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>
<div class="clear"></div>
<div id="location-dialog"  style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"> </span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<a id="locationD" name="locationD"></a>
			<h3 class="title"><?php echo JText::_("LNG_COMPANY_LOCATION")?> </h3>
			<iframe id="location-frame" height="500" width="700" src="about:blank"></iframe>
		</div>
	</div>
</div>
<div id="conditions" class="terms-conditions" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<a id="termsc" name="termsc"></a>
			<h3 class="title"> <?php echo JText::_('LNG_TERMS_AND_CONDITIONS');?></h3>
			<div class="dialogContentBody" id="dialogContentBody">
				<?php echo $this->appSettings->terms_conditions ?>
			</div>
		</div>
	</div>
</div>

<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>

<script>
	var companyFolder = '<?php echo COMPANY_PICTURES_PATH.($this->item->id+0)."/" ?>';
	var companyFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_LOGO?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH) ?>&_target=<?php echo urlencode(COMPANY_PICTURES_PATH.($this->item->id+0)."/")?>';
	var companyAttachFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/uploadFile.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".ATTACHMENT_PATH)?>&_target=<?php echo urlencode(COMPANY_PICTURES_PATH.((int)$this->item->id)."/")?>';
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_SITE)?>&_filename=';

	jQuery(document).ready(function () {
		imageUploaderDropzone("#file-upload", '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH) ?>&_target=<?php echo urlencode(COMPANY_PICTURES_PATH.($this->item->id+0)."/")?>',".fileinput-button","<?php echo JText::_('LNG_DRAG_N_DROP',true); ?>", companyFolder ,<?php echo $allowedNr ?>,"addPicture");
		jQuery("#imageLocation").val('<?php echo $this->item->logoLocation?>');
	});


	imageUploader(companyFolder, companyFolderPath);
	imageUploader(companyFolder, companyFolderPath, 'cover-');
	multiImageUploader(companyFolder, companyFolderPath);
	multiFileUploader(companyFolder, companyAttachFolderPath);
	btn_removefile(removePath);
	btn_removefile_at(removePath);

	var maxPictures = <?php echo isset($this->item->package)?$this->item->package->max_pictures:$this->appSettings->max_pictures ?>;
	var maxVideos = <?php echo isset($this->item->package)?$this->item->package->max_videos :$this->appSettings->max_video ?>;
	var maxCategories = <?php echo isset($this->item->package)?$this->item->package->max_categories :$this->appSettings->max_categories ?>;
	
	jQuery(document).ready(function() {

		jQuery("#tab1").validationEngine('attach');

		jQuery(".chosen-select").chosen({width:"95%", disable_search_threshold: 5});

		jQuery(".chosen-select-categories").chosen({width:"95%", max_selected_options: maxCategories});
		
		jQuery(".fieldset-business_hours").click(function(){
			jQuery(this).toggleClass("open");
		});

		jQuery(".fieldset-business_hours > .field").click(function(event) {
			event.stopPropagation();
		});

		jQuery('.timepicker').timepicker({ 'timeFormat': '<?php echo $this->appSettings->time_format?>', 'minTime': '6:00am', });
		
		checkNumberOfVideos();
		checkNumberOfPictures();

		jQuery('select#selectedSubcategories').on('change', function() {
			jQuery('select#mainSubcategory').find('option').remove();
			jQuery('select#selectedSubcategories option:selected').each(function () {
				if (jQuery(this).length) {
					var selCategoryOption = jQuery(this).clone();
					jQuery('select#mainSubcategory').append(selCategoryOption);
					jQuery('select#mainSubcategory').trigger("chosen:updated");
				}
			});
		});

		initializeAutocomplete();
		<?php if ($showSteps) {?>
			showTab(1);
		<?php } ?>

		jQuery('#userModal_jform_created_by').on('show', function() {
		       var modalBodyHeight = jQuery(window).height()-147;
		       jQuery('.modal-body').css('max-height', modalBodyHeight);
		       jQuery('body').addClass('modal-open');
		   }).on('hide', function () {
		       jQuery('body').removeClass('modal-open');
		   });
	});

	function addVideo() {
		var count = jQuery("#video-container").children().length+1;
		id=0;
		var outerDiv = document.createElement('div');
		outerDiv.setAttribute('class',		'detail_box');
		outerDiv.setAttribute('id',		'detailBox'+count);

		var newLabel = document.createElement('label');
		newLabel.setAttribute("for",		id);
		newLabel.innerHTML="<?php echo JText::_('LNG_VIDEO',true)?>";
		
		var newInput = document.createElement('textarea');
		newInput.setAttribute('name',		'videos[]');
		newInput.setAttribute('id',			id);
		newInput.setAttribute('class',		'input_txt');
		
		var img_del	= document.createElement('img');
		img_del.setAttribute('src', "<?php echo JURI::root() ."administrator/components/".JBusinessUtil::getComponentName()."/assets/img/del_icon.png"?>");
		img_del.setAttribute('alt', 'Delete option');
		img_del.setAttribute('height', '12px');
		img_del.setAttribute('width', '12px');
		img_del.setAttribute('align', 'left');
		img_del.setAttribute('onclick', 'removeRow("detailBox'+count+'")');
		img_del.setAttribute('style', "cursor: pointer; margin:3px;");

		var clearDiv = document.createElement('div');
		clearDiv.setAttribute('class',		'clear');
		
		outerDiv.appendChild(newLabel);
		outerDiv.appendChild(newInput);
		outerDiv.appendChild(img_del);
		outerDiv.appendChild(clearDiv);
		
		var facilityContainer =jQuery("#video-container");
		facilityContainer.append(outerDiv);

		checkNumberOfVideos();
	}

	function removeRow(id) {
		jQuery('#'+id).remove();
		checkNumberOfVideos();
	}

	function checkNumberOfVideos() {
		var nrVideos = jQuery('textarea[name*="videos[]"]').length;
		if(nrVideos < maxVideos) {
			jQuery("#add-video").show();
		}
		else {
			jQuery("#add-video").hide();
		}
	}

	function extendPeriod() {
		<?php  if(!empty($isProfile)) { ?>
			jQuery("#task").val("managecompany.extendPeriod");
		<?php } else { ?>
			jQuery("#task").val("company.extendPeriod");
		<?php } ?>
		jQuery("#item-form").submit();
	}

	var placeSearch, autocomplete;
	var component_form = {
		'street_number': 'short_name',
		'route': 'long_name',
		'locality': 'long_name',
		'administrative_area_level_1': 'long_name',
		'country': 'long_name',
		'postal_code': 'short_name'
	};

	function initializeAutocomplete() {
		autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), { types: [ 'geocode' ] });
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			fillInAddress();
		});
	}

	function fillInAddress() {
		var place = autocomplete.getPlace();
		for (var component in component_form) {
			var obj = document.getElementById(component);
			if(typeof maybeObject != "undefined") {
				document.getElementById(component).value = "";
				document.getElementById(component).disabled = false;
			}
		}
		for (var j = 0; j < place.address_components.length; j++) {
			var att = place.address_components[j].types[0];
			if (component_form[att]) {
				var val = place.address_components[j][component_form[att]];
				jQuery("#"+att).val(val);
				if(att=='country') {
					jQuery('#country option').filter(function () {
						return jQuery(this).text() === val;
					}).attr('selected', true);
				}
			}
		}

		if(typeof map != "undefined") {

			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} 
			
			map.setCenter(place.geometry.location);
			addMarker(place.geometry.location);
			
		}
	}

	function geolocate() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var geolocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
				autocomplete.setBounds(new google.maps.LatLngBounds(geolocation, geolocation));
			});
		}
	}

	var map;
	var markers = [];

	function initialize() {
		<?php 
			$map_latitude = $this->appSettings->map_latitude;
			if ((empty($map_latitude)) || (!is_numeric($map_latitude)))
				$map_latitude = 0;

			$map_longitude = $this->appSettings->map_longitude;
			if ((empty($map_longitude)) || (!is_numeric($map_longitude)))
				$map_longitude = 0;
			
			$map_zoom = $this->appSettings->map_zoom;
			if ((empty($map_zoom)) || (!is_numeric($map_zoom)))
				$map_zoom = 10;

			$latitude = !empty($this->item->latitude)?$this->item->latitude:$map_latitude;
			$longitude = !empty($this->item->longitude)?$this->item->longitude:$map_longitude;
		?>

		var companyLocation = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
		var mapOptions = {
			zoom: <?php echo empty($this->item->latitude)?$map_zoom:15?>,
			center: companyLocation,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		var mapdiv = document.getElementById("company_map");
		mapdiv.style.width = '100%';
		mapdiv.style.height = '400px';
		map = new google.maps.Map(mapdiv,  mapOptions);
		var latitude = '<?php echo  $this->item->latitude ?>';
		var longitude = '<?php echo  $this->item->longitude ?>';
		if(latitude && longitude)
			addMarker(new google.maps.LatLng(latitude, longitude ));
		google.maps.event.addListener(map, 'click', function(event) {
			deleteOverlays();
			addMarker(event.latLng);
		});
	}

	// Add a marker to the map and push to the array.
	function addMarker(location) {
		document.getElementById("latitude").value = location.lat();
		document.getElementById("longitude").value = location.lng();
		marker = new google.maps.Marker({
			position: location,
			map: map
		});
		markers.push(marker);
	}

	// Sets the map on all markers in the array.
	function setAllMap(map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}

	// Removes the overlays from the map, but keeps them in the array.
	function clearOverlays() {
		setAllMap(null);
	}

	// Shows any overlays currently in the array.
	function showOverlays() {
		setAllMap(map);
	}

	// Deletes all markers in the array by removing references to them.
	function deleteOverlays() {
		clearOverlays();
		markers = [];
	}

	function loadScript() {
		<?php if($attributeConfig["map"] != ATTRIBUTE_NOT_SHOW){?>
			initialize();
		<?php } ?>
	}

	<?php if(!$enablePackages || isset($this->item->package->features) && in_array(GOOGLE_MAP,$this->item->package->features)){ ?>
		window.onload = loadScript;
	<?php } ?>

	function checkAllActivityCities() {
		uncheckAllActivityCities();
		jQuery(".cities_ids-select option").each(function() { 
			if(jQuery(this).val()!="") {
				activityCitiesList.add(jQuery(this));
			}
		});
		jQuery("#activity_cities option").each(function() { 
			jQuery(this).attr("selected","selected"); 
		});
	}

	function uncheckAllActivityCities() {
		jQuery("#activity_cities option").each(function() { 
			jQuery(this).removeAttr("selected"); 
		});
		activityCitiesList.remove();
	}

	function editLocation(locationId) {
		var baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=company&tmpl=component&layout=locations&id='.$this->item->id,false); ?>";
		<?php if(isset($isProfile)) { ?>
			baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompany&tmpl=component&layout=locations&id='.$this->item->id,false); ?>";
		<?php } ?>
		baseUrl = baseUrl + "&locationId=" + locationId;
		jQuery("#location-frame").attr("src",baseUrl);
		jQuery.blockUI({ message: jQuery('#location-dialog'),  css: {width: 'auto', top: '10%', left:"0", position:"absolute", cursor:'default'}});
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
		jQuery(document).scrollTop( jQuery("#locationD").offset().top );
		jQuery("html, body").animate({ scrollTop: 0}, "slow");
	}
	
	function deleteLocation(locationId) {
		if(!confirm("<?php echo JText::_("LNG_DELETE_LOCATION_CONF") ?>")) {
			return;
		}
		var baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=company.deleteLocation',false); ?>";
		<?php  if(isset($isProfile)) { ?>
			baseUrl = "<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=managecompany.deleteLocation',false); ?>";
		<?php } ?>

		var postData="&locationId="+locationId;
		jQuery.post(baseUrl, postData, processDeleteLocationResult);
	}

	function processDeleteLocationResult(responce) {
		var xml = responce;
		jQuery(xml).find('answer').each(function() {
			if( jQuery(this).attr('error') == '1' )
				jQuery("#location-box-"+jQuery(this).attr('locationId')).remove();
			else {
				jQuery.blockUI({
					message: '<h3><?php echo JText::_("LNG_LOCATION_DELETE_FAILED",true)?></h3>'
				});
				setTimeout(jQuery.unblockUI, 2000); 
			}
		});
	}

	function updateLocation(id, name, streetNumber, address, city, county, country) {
		if (jQuery("#location-0").length > 0) {
			jQuery("#location-0").html(name +" - "+ streetNumber+", "+address+", "+city+", "+county+", "+country);
			jQuery("#location-0").attr("id","#location-"+id);
		}else if(jQuery("#location-"+id).length > 0) {
			jQuery("#location-"+id).html(name +" - "+ streetNumber+", "+address+", "+city+", "+county+", "+country);
		}
		else {
			var locationContainer = '<div id="location-box-'+id+'" class="detail_box">';
			locationContainer += '<div id="location-'+id+'">'+name +" - "+streetNumber+", "+address+", "+city+", "+county+" ,"+country+'</div>';
			locationContainer += '</div>';
			jQuery("#company-locations").append(locationContainer);
		}
	}

	function closeLocationDialog() {
		jQuery.unblockUI();
	}

	function showTerms() {
		jQuery.blockUI({ message: jQuery('#conditions'),  css: {width: 'auto', top: '10%', left:"0",position:"absolute",cursor:'default'},overlayCSS: { backgroundColor: '#000',opacity: 0.7,cursor:'pointer' }});
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
		jQuery(document).scrollTop( jQuery("#termsc").offset().top );
		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
	}

	var currentTab =1;
	var maxTabs = 5;
	var tabMapInitialized = 0;
	<?php  
	if(!((!$enablePackages || isset($this->item->package->features) && in_array(SOCIAL_NETWORKS,$this->item->package->features)) 
		|| (!$enablePackages || isset($this->item->package->features) && in_array(VIDEOS,$this->item->package->features)) 
		|| (!$enablePackages || isset($this->item->package->features) && in_array(IMAGE_UPLOAD,$this->item->package->features)))) {
		echo "maxTabs = 4;";
	} ?>

	jQuery("#max-tabs").html(maxTabs);

	function showTab(tab) {
		jQuery(".edit-tab").each(function(){ 
			jQuery(this).hide(); 
		});	
		jQuery(".process-step").each(function(){ 
			jQuery(this).hide(); 
			jQuery(this).removeClass("active"); 
			
		});
		jQuery(".process-tab").each(function(){ 
			jQuery(this).removeClass("active"); 
		});	
		if(tab==1) {
			jQuery("#prev-btn").hide();	
		}
		else {
			jQuery("#prev-btn").show();	
		}
		if(tab==maxTabs) {
			jQuery("#next-btn").hide();	
			jQuery("#save-btn").show();	
			jQuery("#term_conditions").show();	
		}
		else {
			jQuery("#next-btn").show();	
			jQuery("#save-btn").hide();	
			jQuery("#term_conditions").hide();	
		}
		jQuery("#edit-tab"+tab).show();
		jQuery("#step"+tab).show();
		jQuery(window).scrollTop(10);
		jQuery("#step"+tab).addClass("active");
		jQuery("#tab"+tab).addClass("active");
		jQuery("#active-step-number").html(tab);
		if(tab==3 && tabMapInitialized==0) {
			initialize();
			tabMapInitialized = 1;
		}
	}

	function nextTab() {
		var isOK = jQuery("#item-form").validationEngine('validate');
		if(isOK) {
			if(currentTab < maxTabs)
				currentTab ++;
			showTab(currentTab);
		}
	}

	function previousTab() {
		if(currentTab >1)
			currentTab --;
		showTab(currentTab);
	}
</script>