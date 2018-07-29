<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');

if (version_compare(JVERSION, '3.0', 'ge'))
{
	JHtml::_('formbehavior.chosen', 'select');
}

JHtml::_('behavior.keepalive');

// Import helper for declaring language constant
JLoader::import('SocialadsHelper', JUri::root().'administrator/components/com_socialads/helpers/socialads.php');

// Call helper function
SocialadsHelper::getLanguageConstant();

if(!empty($this->item->id))
{
	$zoneid =$this->item->id;
	$this->recordsCount;
}
else
{
	$zoneid = 0;
	$this->recordsCount=0;
}

if($this->item->id)
{
	$default_layout = str_replace('||','',$this->item->ad_type);
	$affiliate = str_replace('|', '', $default_layout);
	$default_layout = str_replace('affiliate', '', $affiliate);
}
else
{
	$default_layout='text_media';
}?>

<script type="text/javascript">
	var recordsCount="<?php echo $this->recordsCount; ?>";
	var saWidgetSiteRootUrl="<?php echo JUri::root();?>";
	var layoutName = "<?php echo $this->item->layout?>";
	var widgetUrl="<?php echo JUri::root() . 'media/com_sa/js/sawidget.js';?>";
	var saWidgetZoneId="<?php echo $zoneid; ?>";

	techjoomla.jQuery(document).ready(function()
	{
		/**Code to Populate Layout*/
			var txtSelectedValuesObj = document.getElementById("layout");
			txtSelectedValuesObj.value="";

		/**Code to Populate Layout*/
			txtSelectedValuesObj = saAdmin.zone.populatelayout();

		techjoomla.jQuery(".alphaCheck").keyup(function()
			{
				saAdmin.checkForAlpha(this,46);
			});
		techjoomla.jQuery(".alphaDecimalCheck").keyup(function()
			{
				saAdmin.checkForAlpha(this,46);
			});

			var adtypeSelected = "<?php echo $default_layout; ?>";
			saAdmin.zone.zoneAdTypes(adtypeSelected);
	});

	techjoomla.jQuery('#adminForm select').attr('data-chosen', 'com_socialads');

	Joomla.submitbutton = function(task)
	{
		var isValid = saAdmin.zone.validateFields(task);

		if (isValid == true)
		{

			var atLeastOneIsChecked = false;

			techjoomla.jQuery('input:checkbox').each(function () {

				if (techjoomla.jQuery(this).is(':checked'))
				{
					atLeastOneIsChecked = true;
				}
			});

			if(atLeastOneIsChecked == false)
			{
				alert("<?php echo JText::_('COM_SOCIALADS_FORM_LBL_ZONE_LAYOUT_ALERT'); ?>");
				document.getElementById("validate_layout").innerHTML="<?php echo JText::_('COM_SOCIALADS_FORM_LBL_ZONE_LAYOUT_VALIDATION'); ?>";

				return false;
			}

			submitform( task );
		}
	}

	window.addEvent("domready", function()
	{
		autoFill();
		jQuery("#jform_ad_type").change(autoFill);
		if(<?php echo $zoneid; ?>)
		{
			//console.log(here);
			techjoomla.jQuery("#wtab2").hide();
			saAdmin.zone.codechanger("widget");
		}
		techjoomla.jQuery("#widget :input").bind("keyup change click", function() {
			if(techjoomla.jQuery(this).attr("id") != "wid_code")
			saAdmin.zone.codechanger("widget");
		});
		techjoomla.jQuery("#field_target :input").bind("keyup change", function()
		{
			if(techjoomla.jQuery(this).attr("id") != "wid_code")
			saAdmin.zone.codechanger("target");
		});

		function autoFill()
		{
			//alert("yes");
			var selectedadd = document.getElementById("jform_ad_type").value;
			var selectedzone = "&zonelayout=" + layoutName;
			var url = "?option=com_socialads&task=zone.getSelectedLayouts&addtype="+selectedadd+selectedzone;
			techjoomla.jQuery.ajax({
						type: "get",
						url:url,
						success: function(response)
						{
							var d = document.getElementById("layout_ad_ajax");
							var olddiv = document.getElementById("layout_ad1");
							d.removeChild(olddiv);
							document.getElementById("layout_ad_ajax").innerHTML="<div id=layout_ad1></div>"+response;
						},
						error: function(response)
						{
							techjoomla.jQuery('#'+stepId+'-error').show('slow');
							// show ckout error msg
							console.log(' ERROR!!' );
							return e.preventDefault();
						}
					});
					techjoomla.jQuery( ".yes_no_toggle label" ).on( "click", function()
					{
						var radiovalue = saAdmin.zone.yesnoToggle(this);
					});
		}
	});
</script>
<div class="<?php echo SA_WRAPPER_CLASS;?> "id = "sa-zone">
<form action="<?php echo JRoute::_('index.php?option=com_socialads&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
	<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab"><b><?php echo JText::_('COM_SOCIALADS_TITLE_ZONE_BASIC');?></b></a></li>
			<li><a href="#tab2" data-toggle="tab"><b><?php echo JText::_('COM_SOCIALADS_TITLE_ZONE_PRICING');?></b></a></li>
			<?php if($zoneid){ ?>
			<li><a href="#tab3" data-toggle="tab"><b><?php echo JText::sprintf('COM_SOCIALADS_TITLE_ZONE_AD_WIDGET');?></b></a></li>
			<?php } ?>
		</ul>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<div class="tab-content">
				<div class="tab-pane active" id="tab1">
				<fieldset class="adminform">

					<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
					<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
					<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
					<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
					<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

					<?php if(empty($this->item->created_by))
								{ ?>
									<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
							<?php }
						else
							{ ?>
								<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
						<?php } ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('zone_name'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('zone_name'); ?></div>
					</div>

					<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('ad_type'); ?></div>
					<div class="controls">
					<?php
					$params=JComponentHelper::getParams('com_socialads');
					$allowed_type=$params->get('ad_type_allowed', 'text_media', 'STRING');
					$allowed_type= (array) $allowed_type;
					$add_type = '';
					if(in_array('text_media',$allowed_type))
					$add_type[] = JHtml::_('select.option','text_media', JText::_('COM_SOCIALADS_TITLE_ZONE_AD_TYPE_TEXT_AND_MEDIA'));
					if(in_array('text',$allowed_type))
					$add_type[] = JHtml::_('select.option','text', JText::_('COM_SOCIALADS_TITLE_ZONE_AD_TYPE_TEXT'));
					if(in_array('media',$allowed_type))
					$add_type[] = JHtml::_('select.option','media',JText::_('COM_SOCIALADS_TITLE_ZONE_AD_TYPE_MEDIA'));

						echo JHtml::_('select.genericlist', $add_type,'jform[ad_type]', 'class="inputbox" onchange=saAdmin.zone.zoneAdTypes(this.value);', 'value', 'text',$default_layout, 'jform_ad_type' );
						?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('orientation'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('orientation'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_FORM_DESC_ZONE_AFFILIATE_ADS'), JText::_('COM_SOCIALADS_FORM_LBL_ZONE_AFFILIATE_ADS'), '', JText::_('COM_SOCIALADS_FORM_LBL_ZONE_AFFILIATE_ADS'));?></div>
						<?php
							$rawresult = str_replace('||',',',$this->item->ad_type);
							$rawresult = str_replace('|','',$rawresult);

						$zone_type = explode(",",$rawresult);
						$publish1=$publish2=$publish1_label=$publish2_label='';
						$publish2='checked="checked"';
						$publish2_label = 'btn-danger';
						if($this->item)
						{
								if(in_array('affiliate',$zone_type))
								{
									$publish1='checked="checked"';
									$publish1_label = 'btn-success';
									$publish2 = $publish2_label='';
								}
						}?>
					<div class="controls ">
							<div class="input-append yes_no_toggle">
							<input type="radio" class="inputbox sa_setting_radio" name="affiliate" id="affiliate1" value="1" <?php echo $publish1;?>  >
							<label class="first btn <?php echo $publish1_label;?>" type="button" for="affiliate1"><?php echo JText::_('JYES');?></label>
							<input type="radio" name="affiliate" id="affiliate0" value="0" <?php echo $publish2;?>  >
							<label class="last btn <?php echo $publish2_label;?>" type="button" for="affiliate0"><?php echo JText::_('JNO');?></label>
						</div>
					</div>
				</div>
				<?php
						if($this->item->id)
						{
							$default_layout=$this->item->orientation;
						}
						else
							$default_layout='text_media';
				?>
				<div class="control-group" id = "img_width">
					<div class="control-label"><?php  echo $this->form->getLabel('img_width'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('img_width'); ?></div>
					<span id="validate_img_width" name="validate_img_width" class="invalid validate[numeric]"></span>
				</div>
				<div class="control-group" id = "img_height">
					<div class="control-label"><?php echo $this->form->getLabel('img_height'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('img_height'); ?></div>
					<span id="validate_img_height" name="validate_img_height" class="invalid"></span>
				</div>
				<div class="control-group" id="max_title">
					<div class="control-label"><?php echo $this->form->getLabel('max_title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('max_title'); ?></div>
					<span id="validate_max_title" name="validate_max_title" class="invalid"></span>
				</div>
				<div class="control-group" id="max_des">
					<div class="control-label"><?php echo $this->form->getLabel('max_des'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('max_des'); ?></div>
					<span id="validate_max_des" name="validate_max_des" class="invalid"></span>
				</div>
				<div class="control-group" id="layout_row">
					<div  class = "control-label">
						<label label-default for="zoneLayout">
							<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_FORM_DESC_ZONE_LAYOUT'), JText::_('COM_SOCIALADS_FORM_LBL_ZONE_LAYOUT'), '', JText::_('COM_SOCIALADS_FORM_LBL_ZONE_LAYOUT') . ' *'); ?>
						</label>
					</div>
					<div class = "controls">
					<input type="hidden" id="layout" name="ad_layout" value="<?php echo $this->item->layout;?>">
					<div id='layout_ad_ajax'>
					<div id='layout_ad1'>
					</div>
					</div>
					<span id="validate_layout" name="validate_layout" class="invalid"></span>
					</div>
				</div>
				</div>
				</fieldset>
				<div class="tab-pane" id="tab2">
				<fieldset>
					<?php
					$params = JComponentHelper::getParams('com_socialads');
					$params->get('zone_pricing');
					$pricing_opt = $params->get('pricing_options');
					$pricing_opt = (array) $pricing_opt;
					?>
					<div class="controls"><?php echo JText::_('COM_SOCIALADS_ZONE_PRICING_TOOLTIP'); ?> </div>
					<?php if($params->get('zone_pricing'))
					{ ?>
						<?php if(in_array('perclick', $pricing_opt)){ ?>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('per_click'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('per_click'); ?></div>
						</div>
					<?php }?>
					<?php if(in_array('perimpression', $pricing_opt)){ ?>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('per_imp'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('per_imp'); ?></div>
						</div>
					<?php }?>
					<?php if(in_array('perday', $pricing_opt)){ ?>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('per_day'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('per_day'); ?></div>
						</div>
						<?php }
					}?>
			</fieldset>
			</div>
			<!----------------------------WIDGET CODE START--------------------------------------->
		<div class="tab-pane" id="tab3">
		<fieldset>
		<div class="row-fluid">
		<div class="span6">
			<div class="tabbable tabs-left">
				<ul class="nav nav-pills">
					<li onclick="techjoomla.jQuery('#wtab2').hide();" class="active" ><a href="#wtab1" data-toggle="tab"><?php echo JText::_('COM_SOCIALADS_ZONE_WIDGET_CUSTOM');?></a></li>
					<?php
					$params=JComponentHelper::getParams('com_socialads');
					if($params->get('social_integration')!='joomla'){?>
					<li onclick="techjoomla.jQuery('#wtab2').show();"><a  href="#wtab2" data-toggle="tab"><?php echo JText::_('COM_SOCIALADS_ZONE_WIDGET_TARGET');?></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="wtab1">
						<table id="widget" class="table table-bordered " cellspacing="8px">
							<tr>
								<td  width="25%"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ZONE_NUM_ADS_TOOLTIP'), JText::_('COM_SOCIALADS_ZONE_NUM_ADS'), '', JText::_('COM_SOCIALADS_ZONE_NUM_ADS'));?><span class="star">&nbsp;*</span></td>
								<td >
									<input type="text" name="num_ads" id="num_ads" class="inputbox input-small" size="10" value="2" autocomplete="off"
									onkeyup="saAdmin.checkForAlpha(this,46);"/>
								</td>
							</tr>
							<tr>
								<td  width="25%"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ZONE_ROTATE_ADS_TOOLTIP'), JText::_('COM_SOCIALADS_ZONE_ROTATE_ADS'), '', JText::_('COM_SOCIALADS_ZONE_ROTATE_ADS'));?></td>
								<td >
									<div class="input-append yes_no_toggle">
										<input type="radio" name="rotate" class = "inputbox sa_setting_radio" id="publish1" value="1"  >
										<label class="first btn " type="button" for="publish1"><?php echo JText::_('JYES');?></label>
										<input type="radio" name="rotate" id="publish2" value="0" checked="checked" >
										<label class="last btn btn-danger" type="button" for="publish2"><?php echo JText::_('JNO');?></label>
									</div>
								</td>
							</tr>
							<tr>
								<td  width="25%"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ZONE_ROTATE_ADS_DELAY_TOOLTIP'), JText::_('COM_SOCIALADS_ZONE_ROTATE_ADS_DELAY'), '', JText::_('COM_SOCIALADS_ZONE_ROTATE_ADS_DELAY'));?></td>
								<td >
									<input type="text" name="rotate_delay" id="rotate_delay" class="inputbox input-small" size="10" value="10" autocomplete="off"
									onkeyup="saAdmin.checkForAlpha(this,46);" />
								</td>
							</tr>
							<tr>
								<td  width="25%"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ZONE_RAND_ADS_TOOLTIP'), JText::_('COM_SOCIALADS_ZONE_RAND_ADS'), '', JText::_('COM_SOCIALADS_ZONE_RAND_ADS'));?></td>
								<td >
									<div class="input-append yes_no_toggle">
										<input type="radio" name="rand" id="rand1" value="1"  >
										<label class="first btn" type="button" for="rand1"><?php echo JText::_('JYES');?></label>
										<input type="radio" name="rand" id="rand2" value="0" checked="checked" >
										<label class="last btn btn-danger" type="button" for="rand2"><?php echo JText::_('JNO');?></label>
									</div>
								</td>
							</tr>
							<tr>
								<td  width="25%"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ZONE_IFWID_ADS_TOOLTIP'), JText::_('COM_SOCIALADS_ZONE_IFWID_ADS'), '', JText::_('COM_SOCIALADS_ZONE_IFWID_ADS'));?></td>
								<td >
									<div class="input-append">
										<input type="text" name="if_wid" id="if_wid" class="inputbox input-mini" size="10" value="" placeholder="<?php echo JText::_('COM_SOCIALADS_IF_WID_HOLDER');?>" autocomplete="off" />
										<span class="add-on">px</span>
									</div>
								</td>
							</tr>
							<tr>
								<td  width="25%"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ZONE_IFHT_ADS_TOOLTIP'), JText::_('COM_SOCIALADS_ZONE_IFHT_ADS'), '', JText::_('COM_SOCIALADS_ZONE_IFHT_ADS'));?></td>
								<td >
									<div class="input-append">
									<input type="text" name="if_ht" id="if_ht" class="inputbox input-mini" placeholder="<?php echo JText::_('COM_SOCIALADS_IF_HT_HOLDER');?>" size="10" value="" autocomplete="off" />
										<span class="add-on">px</span>
									</div>
								</td>
							</tr>
							<tr>
								<td  width="25%"><?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_ZONE_IF_SEAMLS_ADS_TOOLTIP'), JText::_('COM_SOCIALADS_ZONE_IF_SEAMLS_ADS'), '', JText::_('COM_SOCIALADS_ZONE_IF_SEAMLS_ADS'));?></td>
								<td >
									<div class="input-append yes_no_toggle">
										<input type="radio" name="if_seam" id="if_seam1" value="1"  checked="checked"  >
										<label class="first btn btn-success" type="button" for="if_seam1"><?php echo JText::_('JYES');?></label>
										<input type="radio" name="if_seam" id="if_seam2" value="0">
										<label class="last btn" type="button" for="if_seam2"><?php echo JText::_('JNO');?></label>
									</div>
								</td>
							</tr>
						</table>

					</div>
					<?php if($params->get('social_integration') !='joomla'){ ?>
					<div class="tab-pane active" id="wtab2">
					<?php
						if(!empty($this->fields)){ ?>
							<!-- field_target starts here -->
							<div id="field_target">
								<!-- floatmain starts here -->
								<div id="floatmain" >
									<div id="mapping-field-table">
								<!--for loop which shows JS fields with select types-->
								<table class="table table-bordered widget" cellspacing="8px">
									<?php
									if($params->get('social_integration') == "Community Builder")
									{
										// require(JPATH_SITE . "/components/com_comprofiler/plugin/language/default_language/default_language.php");
										global $_CB_framework, $_CB_database, $ueConfig, $mainframe;
										include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );
									}
									$i=1;
									foreach($this->fields as $key => $field)
									{
										if($field->mapping_fieldtype!='targeting_plugin')
										{ ?>
										<tr>
											<td>
											<div class="control-group">
												<label class="ad-fields-lable "><?php
													if($params->get('social_integration') == 'Community Builder')
													{
														$field->mapping_label = htmlspecialchars( getLangDefinition( $field->mapping_label));
													}
													else
													{
														$field->mapping_label = JText::_("$field->mapping_label");
													}

													echo $field->mapping_label;?>
												</label>
											</td>
											<td>
												<div class="controls">

												   <!--Numeric Range-->
													<?php
													//for easysocial fileds of those app are created..(gender,boolean and address)

													if($field->mapping_fieldtype=="gender")
													{
														$gender[] = JHtml::_('select.option','', JText::_("SELECT"));
														$gender[] = JHtml::_('select.option','2', JText::_("FEMALE"));
														$gender[] = JHtml::_('select.option','1', JText::_("MALE"));
														echo JHtml::_('select.genericlist', $gender, 'mapdata[][' . $field->mapping_fieldname . ',select]', ' class="sa-fields-inputbox" id="mapdata[][' . $field->mapping_fieldname.',select]" size="1"',   'value', 'text', $flds[$field->mapping_fieldname.',select']);
													}
													if($field->mapping_fieldtype=="boolean")
													{
														$boolean[] = JHtml::_('select.option','', JText::_("SELECT"));
														$boolean[] = JHtml::_('select.option','1', JText::_("YES"));
														$boolean[] = JHtml::_('select.option','0', JText::_("NO"));
														echo JHtml::_('select.genericlist', $boolean, 'mapdata[][' . $field->mapping_fieldname.',select]', ' class="sa-fields-inputbox" id="mapdata[][' . $field->mapping_fieldname.',select]" size="1"',   'value', 'text', $flds[$field->mapping_fieldname.',select']);
													}
													/*
													if($fields->mapping_fieldtype=="address")
													{

													}
													*/
													if($field->mapping_fieldtype=="numericrange")
													{
														$lowvar = $field->mapping_fieldname.'_low';
														$highvar = $field->mapping_fieldname.'_high';
														$onkeyup = " ";

														if (isset($flds[$field->mapping_fieldname.'_low']) || isset($this->addata_for_adsumary_edit->$lowvar))
														{
															$grad_low=0;
															$grad_high=2030;
															if($zoneid)
															{
																if(strcmp($this->addata_for_adsumary_edit->$lowvar,$grad_low)==0)
																{
																		$this->addata_for_adsumary_edit->$lowvar = '';
																}
																if((strcmp($this->addata_for_adsumary_edit->$highvar, $grad_high)==0) || (strcmp($this->addata_for_adsumary_edit->$highvar,$grad_low)==0))
																{
																	$this->addata_for_adsumary_edit->$highvar = '';
																}		?>
																<input type="textbox"  class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname.'_low|numericrange|0'; ?>]" value="<?php echo $this->addata_for_adsumary_edit->$lowvar; ?>" />
																<?php echo JText::_('SA_TO'); ?>
																<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname.'_high|numericrange|1'; ?>]" value="<?php echo $this->addata_for_adsumary_edit->$highvar?>" />
															<?php
															}
															else
															{
																$onkeyup="  Onkeyup = saAdmin.checkForAlpha(this,46)";

																if (strcmp($flds[$field->mapping_fieldname.'_low'], $grad_low) == 0)
																{
																	$flds[$field->mapping_fieldname.'_low'] = '';
																}

																if ((strcmp($flds[$field->mapping_fieldname.'_high'],$grad_high)==0)|| (strcmp($flds[$field->mapping_fieldname.'_high'],$grad_high)==0))
																{
																	$flds[$field->mapping_fieldname.'_high'] = '';
																} ?>
																<input type="textbox"  class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname.'_low|numericrange|0'; ?>]" value="<?php echo $flds[$field->mapping_fieldname.'_low']?>"
																	Onkeyup = "saAdmin.checkForAlpha(this,46);" />
																	<?php echo JText::_('SA_TO'); ?>
																	<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname.'_high|numericrange|1'; ?>]" value="<?php echo $flds[$field->mapping_fieldname.'_high']?>"  Onkeyup = "saAdmin.checkForAlpha(this,46);" />
															<?php
															} ?>
														<?php
														}
														else
														{ ?>
															<input type="textbox"  class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname.'_low|numericrange|0'; ?>]" value="" <?php echo $onkeyup; ?> />
															<?php echo JText::_('SA_TO'); ?>
															<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname.'_high|numericrange|1'; ?>]" value=""<?php echo $onkeyup; ?> />
														<?php
														}
													} ?>
													<!--Freetext-->
													<?php if($field->mapping_fieldtype=="textbox")
													{
														$textvar = $field->mapping_fieldname;

														if (isset($flds[$field->mapping_fieldname]) || isset($this->addata_for_adsumary_edit->$textvar))
														{
															if ($zoneid)
															{
															?>
																<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php  echo $field->mapping_fieldname; ?>]" value="<?php echo $this->addata_for_adsumary_edit->$textvar; ?>"  />
															<?php
															}
															else
															{ ?>
																<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname; ?>]" value="<?php echo $flds[$field->mapping_fieldname]; ?>" />
															<?php
															}
														}
														else
														{?>
															<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php echo $field->mapping_fieldname; ?>]" value="" />
														<?php
														}
													}?>
													<!--Single Select-->
													<?php
														if($field->mapping_fieldtype=="singleselect")
														{
															$singlevar = $field->mapping_fieldname;

															if (isset($flds[$field->mapping_fieldname.',select']) || isset($this->addata_for_adsumary_edit->$singlevar))
															{
																$singleselect = $field->mapping_options;
																$singleselect = explode("\n",$singleselect);

																for ($count = 0;$count < count($singleselect); $count++)
																{
																	$options[] = JHtml::_('select.option',$singleselect[$count],JText::_($singleselect[$count]),'value','text');
																}

																$s = array();
																$s[0]->value = '';
																$s[0]->text = JText::_('COM_SOCIALADS_AD_TARGET_SINGSELECT');
																$options = array_merge($s, $options);

																if ($zoneid)
																{
																	$mdata = str_replace('||', ',', $this->addata_for_adsumary_edit->$singlevar);
																	$mdata = str_replace('|', '', $mdata);
																	echo JHtml::_('select.genericlist', $options, 'mapdata[][' . $field->mapping_fieldname . ',select]', 'class="sa-fields-inputbox" size="1" ' . $display_reach, 'value', 'text', $mdata);
																}
																else
																{
																	echo JHtml::_('select.genericlist', $options, 'mapdata[]['.$field->mapping_fieldname.',select]', ' class="sa-fields-inputbox"'.$display_reach.' id="mapdata[]['.$field->mapping_fieldname.',select]" size="1"',   'value', 'text', $flds[$field->mapping_fieldname.',select']);
																}

																$options= array();
															}
															else
															{
																$singleselect = $field->mapping_options;
																$singleselect = explode("\n", $singleselect);

																for($count = 0;$count<count($singleselect); $count++)
																{
																	$options[] = JHtml::_('select.option', $singleselect[$count], JText::_($singleselect[$count]),'value','text');
																}

																$s = array();
																$s[0] = new stdClass;
																$s[0]->value = '';
																$s[0]->text = JText::_('COM_SOCIALADS_AD_TARGET_SINGSELECT');
																$options = array_merge($s, $options);

																echo JHtml::_('select.genericlist', $options, 'mapdata[][' . $field->mapping_fieldname . ',select]', 'class="sa-fields-inputbox"  id="mapdata[][' . $field->mapping_fieldname . ',select]" size="1"',   'value', 'text', '');
																$options= array();
															}
														}
														// Multiselect
														if ($field->mapping_fieldtype=="multiselect" )
														{
															$multivar = $field->mapping_fieldname;
															$options= array();

															$multivar = $field->mapping_fieldname;
														$options= array();
														if (isset($flds[$field->mapping_fieldname.',select']) || isset($this->addata_for_adsumary_edit->$multivar))
															{
																$multiselect = $field->mapping_options;
																$multiselect = explode("\n",$multiselect);
																if($this->edit_ad_adsumary)
																{
																	$mdata = str_replace('||',',',$this->addata_for_adsumary_edit->$multivar);
																	$mdata = str_replace('|','',$mdata);
																	$multidata = explode(",",$mdata);
																	//print_r($multidata);
																}
																	for($cnt=0;$cnt<count($multiselect); $cnt++)
																	{

																		$options[] = JHtml::_('select.option',$multiselect[$cnt], JText::_($multiselect[$cnt]),'value','text');
																	}

																	if($cnt > 20)
																	{
																		$size = '6';
																	}
																	else
																	{
																		$size = '3';
																	}

																	echo JHtml::_('select.genericlist', $options, 'mapdata[]['.$field->mapping_fieldname.',select]', 'class="sa-fields-inputbox inputbox chzn-done" id="mapdata[]['.$field->mapping_fieldname.',select]" size="'.$size.'"  multiple="multiple" ',   'value', 'text', $multidata);
																	$options= array();
															}
															else
															{
																$multiselect = $field->mapping_options;
																$multiselect = explode("\n",$multiselect);
																for($cnt=0;$cnt<count($multiselect); $cnt++)
																{

																		$options[] = JHtml::_('select.option',$multiselect[$cnt], JText::_($multiselect[$cnt]),'value','text');

																}

																if($cnt > 20)
																{	$size = '6';}
																else
																	$size = '3';
																echo JHtml::_('select.genericlist', $options, 'mapdata[]['.$field->mapping_fieldname.',select]', 'class="sa-fields-inputbox  inputbox chzn-done"  size="'.$size.'" id="mapdata[]['.$field->mapping_fieldname.',select]" multiple="multiple"',   'value', 'text', '');

																$options= array();
															}
														}
														 //daterange
														if($field->mapping_fieldtype=="daterange")
														{
															$this->datelowvar  = $field->mapping_fieldname . '_low';
															$this->datehighvar = $field->mapping_fieldname . '_high';

															if (isset($flds[$field->mapping_fieldname . '_low']) || isset($this->addata_for_adsumary_edit->$this->datelowvar))
															{
																$date_low  = date('Y-m-d 00:00:00', mktime(0, 0, 0, 01, 1, 1910));
																$date_high = date('Y-m-d 00:00:00', mktime(0, 0, 0, 01, 1, 2030));

																if ($zoneid)
																{
																	if (strcmp($this->addata_for_adsumary_edit->$this->datelowvar, $date_low) == 0)
																	{
																		$this->addata_for_adsumary_edit->$this->datelowvar = '';
																	}

																	if (strcmp($this->addata_for_adsumary_edit->$this->datehighvar, $date_high) == 0)
																	{
																		$this->addata_for_adsumary_edit->$this->datehighvar = '';
																	}

																	echo JHtml::_('calendar', $this->addata_for_adsumary_edit->$this->datelowvar, 'mapdata[][' . $field->mapping_fieldname . '_low|daterange|0]', 'mapdata[' . $key . '][' .$field->mapping_fieldname. '][' . $field->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small'));

																	echo JText::_('COM_SOCIALADS_TO');

																	echo JHtml::_('calendar', $this->addata_for_adsumary_edit->$this->datehighvar, 'mapdata[][' . $field->mapping_fieldname . '_high|daterange|1]', 'mapdata[' . $key . '][' .$field->mapping_fieldname.'][' . $field->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small'));
																}
																else
																{
																	if (strcmp($flds[$field->mapping_fieldname . '_low'], $date_low) == 0)
																	{
																		$flds[$field->mapping_fieldname . '_low'] = '';
																	}

																	if (strcmp($flds[$field->mapping_fieldname . '_high'], $date_high) == 0)
																	{
																		$flds[$field->mapping_fieldname . '_high'] = '';
																	}

																	echo JHtml::_('calendar', $flds[$field->mapping_fieldname . '_low'], 'mapdata[][' . $field->mapping_fieldname . '_low]', 'mapdata[' . $key . '][' .$field->mapping_fieldname. '][' . $field->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small'));
																	echo JText::_('COM_SOCIALADS_TO');
																	echo JHtml::_('calendar', $flds[$field->mapping_fieldname . '_high'], 'mapdata[][' . $field->mapping_fieldname . '_high]', 'mapdata[' . $key . '][' .$field->mapping_fieldname. '][' . $field->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small'));
																}
															}
														else
														{
															if ($zoneid)
															{
																echo JHtml::_('calendar', '', 'mapdata[][' . $field->mapping_fieldname . '_low|daterange|0]', 'mapdata[' . $key . '][' .$field->mapping_fieldname. '][' . $field->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox','onchange' => 'calculateReach()'));
																echo JText::_('COM_SOCIALADS_TO');
																echo JHtml::_('calendar', '', 'mapdata[][' . $field->mapping_fieldname . '_high|daterange|1]', 'mapdata[' . $key . '][' .$field->mapping_fieldname. '][' . $field->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox'));
															}
															else
															{
																echo JHtml::_('calendar', '', 'mapdata[][' . $field->mapping_fieldname . '_low|daterange|0]', 'mapdata[' . $key . ']['.$field->mapping_fieldname. '][' . $field->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox'));
																echo JText::_('COM_SOCIALADS_TO');
																echo JHtml::_('calendar', '', 'mapdata[][' . $field->mapping_fieldname . '_high|daterange|1]', 'mapdata[' . $key . ']['.$field->mapping_fieldname.'][' . $field->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox'));
															}
														}

														if ($this->datelowvar == null)
														{
															$this->datelow = $field->mapping_fieldname;
														}
														else
														{
															$this->datelowvar .= ',' . $field->mapping_fieldname;
														}

													}

													 //date
															if($field->mapping_fieldtype=="date")
															{
																$datevar = $field->mapping_fieldname;
																if(isset($flds[$field->mapping_fieldname]) || isset($this->addata_for_adsumary_edit->$datevar))
																{
																	if($zoneid)
																	{
																		echo JHtml::_('calendar', $this->addata_for_adsumary_edit->$datevar , 'mapdata[]['.$field->mapping_fieldname.']', 'mapdata[' . $key . ']['.$field->mapping_fieldname.']','%Y-%m-%d', array('class'=>'sa-fields-inputbox'));
																	}
																	else
																	{
																		echo JHtml::_('calendar', $flds[$field->mapping_fieldname] , 'mapdata[]['.$field->mapping_fieldname.']',
																		'mapdata[' . $key . ']['.$field->mapping_fieldname.']','%Y-%m-%d', array('class'=>'sa-fields-inputbox'));
																	}
																}
																else
																{
																	echo JHtml::_('calendar', '', 'mapdata[]['.$field->mapping_fieldname.']', 'mapdata[' . $key . ']['.$field->mapping_fieldname.']','%Y-%m-%d', array('class'=>'sa-fields-inputbox'));
																} ?>
												  <?php 	}?>

												</div>
											</div>
										</td>
									</tr>
								 <?php
											$i++;
										}
									} ?>
								</table>

										<div style="clear:both"></div>
									</div>
								</div><!-- End fo floatmain div -->
							</div><!-- End fo field_target div -->
							<?php }//end for fields not empty condition
							?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="span6">
			<div class="well">
				<label><?php echo JText::_('COM_SOCIALADS_WIDGET_CODE');?></label>
				<?php
				$widgetCode = "<script>\n var Ad_widget_sitebase = '" . JUri::root() . "';\n";
				$widgetCode .= "</"."script>";
				?>
				<textarea id="wid_code" rows="5" cols="80" onclick="this.select()" spellcheck="false" style="width: 100% !important;"><?php echo $widgetCode;
				  ?></textarea>
				<label><?php echo JText::_('COM_SOCIALADS_WIDGETUNIT_CODE');?></label>
				<textarea id="widunit_code" rows="15" cols="80" onclick="this.select()" spellcheck="false" style="width: 100% !important;"></textarea>
			</div>
		</div>
		</div>
		</fieldset>
		</div>
		</div>
		<?php //} ?>

			<!-----------------WIDGET CODE END------------------------------->
		</div>
	</div>
	<?php
	if (JVERSION >= '3.0')
	{
		echo JHtml::_('bootstrap.endTab');
		echo JHtml::_('bootstrap.endTabSet');
	}
	?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

</div>
</form>
</div>
