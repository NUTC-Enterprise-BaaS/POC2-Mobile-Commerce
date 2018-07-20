<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Snehal - Added for estimated reach count on Social Targeting page
$displayReachCount    = '';
$displayReachFunction = '';
$displayCount = $displayData->sa_params->get('display_count');
$socialIntegration = $displayData->sa_params->get('social_integration');

if ($displayCount)
{
	$displayReachCount    = ' onchange=" sa.create.calculateReach() "';
	$displayReachFunction = "'onchange' => 'sa.create.calculateReach()'";
}

if ($socialIntegration != 'Joomla')
{
	if (!empty($displayData->social_target))
	{
		$social_dis = 'style="display:block;"';
	}
	else
	{
		$social_dis = 'style="display:none;"';
	}

	$publish1 = $publish2 = $publish1_label = $publish2_label = '';

	if (!empty($displayData->social_target))
	{
		if ($displayData->social_target)
		{
			$publish1       = 'checked';
			$publish1_label = 'btn-success';
		}
		else
		{
			$publish2       = 'checked';
			$publish2_label	= 'btn-danger';
		}
	}
	else
	{
		$publish2       = 'checked';
		$publish2_label	= 'btn-danger';
	}
	?>
	<div class="form-horizontal">
		<div id="social_target_space" class="target_space well">
			<div class="form-group">
				<label label-default class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
					<?php echo JHtml::tooltip(
					JText::_('COM_SOCIALADS_AD_SOCIAL_TARGETING_DESC'),
					JText::_('COM_SOCIALADS_AD_SOCIAL_TARGETING'),
					'', JText::_('COM_SOCIALADS_AD_SOCIAL_TARGETING')
					); ?>
				</label>
				<div class="col-lg-9 col-md-9 col-sm-8 col-xs-6 input-group targetting_yes_no">
					<input type="radio" name="social_targett" id="social_target1" value="1" <?php echo $publish1; ?> />
					<label label-default class="first btn btn-default <?php echo $publish1_label; ?>" type="button" for="social_target1">
						<?php echo JText::_('JYES'); ?>
					</label>
					<input type="radio" name="social_targett" id="social_target2" value="0" <?php echo $publish2; ?> />
					<label label-default class="last btn btn-default <?php echo $publish2_label; ?>" type="button" for="social_target2">
						<?php echo JText::_('JNO'); ?>
					</label>
				</div>
			</div>
			<!--sa_h3_chkbox-->
			<div id="social_targett_div" <?php echo $social_dis; ?> class="targetting">
				<div class="alert alert-info">
					<i id="sa-form-span">
						<?php
						if ($displayData->fields == null)
						{
							echo JText::_('COM_SOCIALADS_AD_TARGET_NOT_SET');
						}
						else
						{
							echo JText::_('COM_SOCIALADS_AD_TARGET_MESSAGE1');
							?>
							<br/>
							<?php
							echo JText::_('COM_SOCIALADS_AD_TARGET_MESSAGE2');
							?>
							<?php
						}
						?>
					</i>
				</div>

				<?php
				if (!empty($displayData->fields))
				{ ?>
					<!-- field_target starts here -->
					<div id="field_target">
						<!-- floatmain starts here -->
						<div id="floatmain" >
							<div id="mapping-field-table">
								<!-- For loop which shows JS fields with select types-->
								<?php
								// Load easy social language file
								if ($socialIntegration == 'EasySocial')
								{
									$lang = JFactory::getLanguage();
									$extension = 'com_easysocial';
									$base_dir  = JPATH_SITE;
									$language_tag = 'en-GB';
									$reload = true;
									$lang->load($extension, $base_dir, $language_tag, $reload);
								}

								// @TODO - needs to check $displayData->flds this variable used below
								foreach ($displayData->fields as $key => $fields)
								{
									if ($fields->mapping_fieldtype != 'targeting_plugin')
									{ ?>
										<div class="row">
											<div class="form-group span6">
												<label label-default class="ad-fields-lable col-lg-3 col-md-3 col-sm-4 col-xs-12">
													<?php
													if ($socialIntegration == 'Community Builder')
													{
														$fields->mapping_label = htmlspecialchars(getLangDefinition($fields->mapping_label));
													}
													else
													{
														$fields->mapping_label = JText::_("$fields->mapping_label");
													}

													echo $fields->mapping_label;
													?>
												</label>
												<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
													<!--Numeric Range-->
													<?php
													// For easysocial fileds of those app are created..(gender,boolean and address)
													if ($fields->mapping_fieldtype == "gender")
													{
														$gender[] = JHtml::_('select.option', '', JText::_("SELECT"));
														$gender[] = JHtml::_('select.option', '2', JText::_("COM_SOCIALADS_AD_TARGET_FEMALE"));
														$gender[] = JHtml::_('select.option', '1', JText::_("COM_SOCIALADS_AD_TARGET_MALE"));

														echo JHtml::_(
														'select.genericlist', $gender, 'mapdata[][' . $fields->mapping_fieldname . ',select]',
														' class="sa-fields-inputbox chzn-done" id="mapdata[][' . $fields->mapping_fieldname . ',select]" size="1"',
														'value', 'text', $displayData->flds[$fields->mapping_fieldname . ',select']
														);
													}

													if ($fields->mapping_fieldtype == "boolean")
													{
														$boolean[] = JHtml::_('select.option', '', JText::_("SELECT"));
														$boolean[] = JHtml::_('select.option', '1', JText::_("JYES"));
														$boolean[] = JHtml::_('select.option', '0', JText::_("JNO"));

														echo JHtml::_('select.genericlist', $boolean,
														'mapdata[][' . $fields->mapping_fieldname . ',select]',
														' class="sa-fields-inputbox chzn-done" id="mapdata[][' . $fields->mapping_fieldname . ',select]" size="1"',
														'value', 'text', $displayData->flds[$fields->mapping_fieldname . ',select']
														);
													}

													/*
													if ($fields->mapping_fieldtype=="address")
													{

													}
													*/

													if ($fields->mapping_fieldtype == "numericrange")
													{
														$lowvar  = $fields->mapping_fieldname . '_low';
														$highvar = $fields->mapping_fieldname . '_high';
														$onkeyup = " ";

														if (isset($displayData->flds[$fields->mapping_fieldname . '_low']) || isset($displayData->addata_for_adsumary_edit->$lowvar))
														{
															$grad_low  = 0;
															$grad_high = 2030;

															if ($displayData->edit_ad_id)
															{


																if (strcmp($displayData->addata_for_adsumary_edit->$lowvar, $grad_low) == 0)
																{
																	$displayData->addata_for_adsumary_edit->$lowvar = '';
																}

																if ((strcmp($displayData->addata_for_adsumary_edit->$highvar, $grad_high) == 0) || (strcmp($displayData->addata_for_adsumary_edit->$highvar, $grad_low) == 0))
																{
																	$displayData->addata_for_adsumary_edit->$highvar = '';
																} ?>

																<input type="textbox" class="sa-fields-inputbox input-small"
																name="mapdata[][<?php echo $fields->mapping_fieldname . '_low|numericrange|0'; ?>]"
																value="<?php echo $displayData->addata_for_adsumary_edit->$lowvar; ?>"
																<?php echo $displayReachCount; ?> />

																<?php echo JText::_('COM_SOCIALADS_TO'); ?>

																<input type="textbox" class="sa-fields-inputbox input-small"
																	name="mapdata[][<?php echo $fields->mapping_fieldname . '_high|numericrange|1'; ?>]"
																	value="<?php echo $displayData->addata_for_adsumary_edit->$highvar?>"
																	<?php echo $displayReachCount; ?> />
															<?php
															}
															else
															{
																$onkeyup = " onkeyup=checkforalpha(this);";

																if (strcmp($displayData->flds[$fields->mapping_fieldname . '_low'], $grad_low) == 0)
																{
																	$displayData->flds[$fields->mapping_fieldname . '_low'] = '';
																}

																if ((strcmp($displayData->flds[$fields->mapping_fieldname . '_high'], $grad_high) == 0) || (strcmp($displayData->flds[$fields->mapping_fieldname . '_high'], $grad_high) == 0))
																{
																	$displayData->flds[$fields->mapping_fieldname . '_high'] = '';
																}
																?>

																<input type="textbox" class="sa-fields-inputbox input-small"
																	name="mapdata[][<?php echo $fields->mapping_fieldname . '_low|numericrange|0'; ?>]"
																	value="<?php echo $displayData->flds[$fields->mapping_fieldname . '_low']?>" onkeyup="checkforalpha(this);"
																	<?php echo $displayReachCount; ?> />

																<?php echo JText::_('COM_SOCIALADS_TO'); ?>

																<input type="textbox" class="sa-fields-inputbox input-small"
																	name="mapdata[][<?php echo $fields->mapping_fieldname . '_high|numericrange|1'; ?>]"
																	value="<?php echo $displayData->flds[$fields->mapping_fieldname . '_high']?>"
																	<?php echo $displayReachCount; ?>
																	onkeyup="checkforalpha(this);" />

															<?php
															}
														}
														else
														{
															?>
															<input type="textbox"  class="sa-fields-inputbox input-small" name="mapdata[][<?php echo $fields->mapping_fieldname . '_low|numericrange|0'; ?>]"
																value=""
																<?php echo $displayReachCount; ?>
																<?php echo $onkeyup; ?> />

															<?php echo JText::_('COM_SOCIALADS_TO'); ?>

															<input type="textbox" class="sa-fields-inputbox input-small" name="mapdata[][<?php echo $fields->mapping_fieldname . '_high|numericrange|1'; ?>]"
																value="" <?php echo $displayReachCount; ?>
																<?php echo $onkeyup; ?> />

															<?php
														}
													}
													?>

													<!--Freetext-->
													<?php
													if ($fields->mapping_fieldtype == "textbox")
													{
														$textvar = $fields->mapping_fieldname;

														if (isset($displayData->flds[$fields->mapping_fieldname]) || isset($displayData->addata_for_adsumary_edit->$textvar))
														{
															if ($displayData->edit_ad_id)
															{
																?>
																<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php  echo $fields->mapping_fieldname; ?>]"
																	value="<?php echo $displayData->addata_for_adsumary_edit->$textvar; ?>"
																	<?php echo $displayReachCount; ?> />
																<?php
															}
															else
															{
																?>
																<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php echo $fields->mapping_fieldname; ?>]"
																	value="<?php echo $displayData->flds[$fields->mapping_fieldname]; ?>"
																	<?php echo $displayReachCount; ?>/>
																<?php
															}
														}
														else
														{
															?>
															<input type="textbox" class="sa-fields-inputbox" name="mapdata[][<?php echo $fields->mapping_fieldname; ?>]"
																value=""
																<?php echo $displayReachCount; ?> />
															<?php
														}
													}
													?>

													<!--Single Select-->
													<?php
													if ($fields->mapping_fieldtype == "singleselect")
													{
														$singlevar = $fields->mapping_fieldname;

														if (isset($displayData->flds[$fields->mapping_fieldname . ',select']) || isset($displayData->addata_for_adsumary_edit->$singlevar))
														{
															$singleselect = $fields->mapping_options;
															$singleselect = explode("\n", $singleselect);

															for ($count = 0; $count < count($singleselect); $count++)
															{
																$options[] = JHtml::_('select.option', $singleselect[$count], JText::_($singleselect[$count]), 'value', 'text');
															}

															$s = array();
															$s[0] = new stdClass;
															$s[0]->value = '';
															$s[0]->text = JText::_('COM_SOCIALADS_AD_TARGET_SINGSELECT');
															$options = array_merge($s, $options);

															if ($displayData->edit_ad_id)
															{
																$mdata = str_replace('||', ',', $displayData->addata_for_adsumary_edit->$singlevar);
																$mdata = str_replace('|', '', $mdata);
																echo JHtml::_('select.genericlist', $options, 'mapdata[][' . $fields->mapping_fieldname . ',select]', 'class="sa-fields-inputbox chzn-done" size="1" ' . $displayReachCount, 'value', 'text', $mdata);
															}
															else
															{
																echo JHtml::_('select.genericlist', $options, 'mapdata[][' . $fields->mapping_fieldname . ',select]', ' class="sa-fields-inputbox chzn-done"' . $displayReachCount . ' id="mapdata[][' . $fields->mapping_fieldname . ',select]" size="1"', 'value', 'text', $displayData->flds[$fields->mapping_fieldname . ',select']);
															}

															$options = array();
														}
														else
														{
															$singleselect = $fields->mapping_options;
															$singleselect = explode("\n", $singleselect);

															for ($count = 0; $count < count($singleselect); $count++)
															{
																$options[] = JHtml::_('select.option', $singleselect[$count], JText::_($singleselect[$count]), 'value', 'text');
															}

															$s           = array();
															$s[0]        = new stdClass;
															$s[0]->value = '';
															$s[0]->text  = JText::_('COM_SOCIALADS_AD_TARGET_SINGSELECT');
															$options = array_merge($s, $options);

															echo JHtml::_('select.genericlist', $options, 'mapdata[][' . $fields->mapping_fieldname . ',select]', 'class="sa-fields-inputbox chzn-done"  id="mapdata[][' . $fields->mapping_fieldname . ',select]"' . $displayReachCount . ' size="1"', 'value', 'text', '');

															$options = array();
														}
													}

													// Multiselect
													if ($fields->mapping_fieldtype == "multiselect" )
													{
														$multivar = $fields->mapping_fieldname;

														if (isset($displayData->flds[$fields->mapping_fieldname . ',select']) || isset($displayData->addata_for_adsumary_edit->$multivar))
														{
															$multiselect = $fields->mapping_options;
															$multiselect = explode("\n", $multiselect);

															if ($displayData->edit_ad_id)
															{
																$mdata = str_replace('||', ',', $displayData->addata_for_adsumary_edit->$multivar);
																$mdata = str_replace('|', '', $mdata);
																$multidata = explode(",", $mdata);
															}

															for ($cnt = 0; $cnt < count($multiselect); $cnt++)
															{
																$options[] = JHtml::_('select.option', $multiselect[$cnt], JText::_($multiselect[$cnt]), 'value', 'text');
															}

															if ($cnt > 20)
															{
																$size = '6';
															}
															else
															{
																$size = '3';
															}

															echo JHtml::_('select.genericlist', $options, 'mapdata[][' . $fields->mapping_fieldname . ',select]', 'class="sa-fields-inputbox chzn-done" id="mapdata[][' . $fields->mapping_fieldname . ',select]" size="' . $size . '" multiple="true"' . $displayReachCount, 'value', 'text', $multidata);

															$options = array();
														}
														else
														{
															$multiselect = $fields->mapping_options;
															$multiselect = explode("\n", $multiselect);

															for ($cnt = 0; $cnt < count($multiselect); $cnt++)
															{
																$options[] = JHtml::_('select.option', $multiselect[$cnt], JText::_($multiselect[$cnt]), 'value', 'text');
															}

															if ($cnt > 20)
															{
																$size = '6';
															}
															else
															{
																$size = '3';
															}

															echo JHtml::_('select.genericlist', $options, 'mapdata[][' . $fields->mapping_fieldname . ',select]', 'class="sa-fields-inputbox chzn-done" size="' . $size . '" id="mapdata[][' . $fields->mapping_fieldname . ',select]" multiple="true"' . $displayReachCount, 'value', 'text', '');

															$options = array();
														}
													}

													// Daterange
													if ($fields->mapping_fieldtype == "daterange")
													{
														$datelowvar = $fields->mapping_fieldname . '_low';
														$datehighvar = $fields->mapping_fieldname . '_high';

														if (isset($displayData->flds[$fields->mapping_fieldname . '_low']) || isset($displayData->addata_for_adsumary_edit->$datelowvar))
														{
															$date_low  = date('Y-m-d 00:00:00', mktime(0, 0, 0, 01, 1, 1910));
															$date_high = date('Y-m-d 00:00:00', mktime(0, 0, 0, 01, 1, 2030));

															if ($displayData->edit_ad_id)
															{
																if (strcmp($displayData->addata_for_adsumary_edit->$datelowvar, $date_low) == 0)
																{
																	$displayData->addata_for_adsumary_edit->$datelowvar = '';
																}

																if (strcmp($displayData->addata_for_adsumary_edit->$datehighvar, $date_high) == 0)
																{
																	$displayData->addata_for_adsumary_edit->$datehighvar = '';
																}

																echo JHtml::_('calendar', $displayData->addata_for_adsumary_edit->$datelowvar, 'mapdata[][' . $fields->mapping_fieldname . '_low|daterange|0]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small', $displayReachFunction));

																echo JText::_('COM_SOCIALADS_TO');

																echo JHtml::_('calendar', $displayData->addata_for_adsumary_edit->$datehighvar, 'mapdata[][' . $fields->mapping_fieldname . '_high|daterange|1]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small', $displayReachFunction));
															}
																else
																{
																	if (strcmp($displayData->flds[$fields->mapping_fieldname . '_low'], $date_low) == 0)
																	{
																		$displayData->flds[$fields->mapping_fieldname . '_low'] = '';
																	}

																	if (strcmp($displayData->flds[$fields->mapping_fieldname . '_high'], $date_high) == 0)
																	{
																		$displayData->flds[$fields->mapping_fieldname . '_high'] = '';
																	}

																	echo JHtml::_('calendar', $displayData->flds[$fields->mapping_fieldname . '_low'], 'mapdata[][' . $fields->mapping_fieldname . '_low]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small', $displayReachFunction));
																	echo JText::_('COM_SOCIALADS_TO');
																	echo JHtml::_('calendar', $displayData->flds[$fields->mapping_fieldname . '_high'], 'mapdata[][' . $fields->mapping_fieldname . '_high]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox input-small', $displayReachFunction));
																}
														}
														else
														{
															if ($displayData->edit_ad_id)
															{
																	echo JHtml::_('calendar', '', 'mapdata[][' . $fields->mapping_fieldname . '_low|daterange|0]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox','onchange' => 'sa.create.calculateReach()'));
																	echo JText::_('COM_SOCIALADS_TO');
																	echo JHtml::_('calendar', '', 'mapdata[][' . $fields->mapping_fieldname . '_high|daterange|1]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox', $displayReachFunction));
															}
															else
															{
																	echo JHtml::_('calendar', '', 'mapdata[][' . $fields->mapping_fieldname . '_low|daterange|0]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_low]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox', $displayReachFunction));
																	echo JText::_('COM_SOCIALADS_TO');
																	echo JHtml::_('calendar', '', 'mapdata[][' . $fields->mapping_fieldname . '_high|daterange|1]', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . '_high]', '%Y-%m-%d', array('class' => 'sa-fields-inputbox', $displayReachFunction));
															}
														}

														// +Manoj v3.1re
														if (!isset($displayData->datelow))
														{
															$displayData->datelow = '';
														}

														if ($displayData->datelow == null)
														{
															$displayData->datelow = $fields->mapping_fieldname;
														}
														else
														{
															$displayData->datelow .= ',' . $fields->mapping_fieldname;
														}
													}


													// Date
													if ($fields->mapping_fieldtype == "date")
													{
														$datevar = $fields->mapping_fieldname;

														if (isset($displayData->flds[$fields->mapping_fieldname]) || isset($displayData->addata_for_adsumary_edit->$datevar))
														{
															if ($displayData->edit_ad_id)
															{
																echo JHtml::_('calendar', $displayData->addata_for_adsumary_edit->$datevar, 'mapdata[][' . $fields->mapping_fieldname . ']', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . ']', '%Y-%m-%d', array('class' => 'sa-fields-inputbox', $displayReachFunction));
															}
															else
															{
																echo JHtml::_('calendar', $displayData->flds[$fields->mapping_fieldname], 'mapdata[][' . $fields->mapping_fieldname . ']', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . ']', '%Y-%m-%d', array('class' => 'sa-fields-inputbox', $displayReachFunction));
															}
														}
														else
														{
															echo JHtml::_('calendar', '', 'mapdata[][' . $fields->mapping_fieldname . ']', 'mapdata[' . $key . '][' . $fields->mapping_fieldname . ']', '%Y-%m-%d', array('class' => 'sa-fields-inputbox', $displayReachFunction));
														}
													}
													?>
												</div>
											</div>
										</div>
										<?php
									}
								}

								$adid[0] = $displayData->edit_ad_id;
								JPluginHelper::importPlugin('socialadstargeting');
								$dispatcher = JDispatcher::getInstance();

								// $results = $dispatcher->trigger('onFrontendTargetingDisplay', array($adid, $displayData->adfieldsTableColumn));

								// $results = $dispatcher->trigger('onFrontendTargetingDisplay', array($displayData->edit_ad_id, $displayData->adfieldsTableColumn));

								$results = $dispatcher->trigger('onFrontendTargetingDisplay', array($displayData->addata_for_adsumary_edit, $displayData->adfieldsTableColumn));

								foreach ($results as $value)
								{
									if (!empty($value))
									{
										foreach ($value as $val)
										{
											if ($val)
											{
												echo "<div class='row'>";
												echo $val;
												echo "</div>";
											}
										}
									}
								}
								?>
								<div style="clear:both"></div>
							</div>
						</div>
						<!-- End fo floatmain div -->

						<?php
						// @TODO need rewrite for estimated reach

						/* if ($displayData->socialads_config['display_reach'])*/

						if ($displayCount)
						{
							?>
							<div id="fixedElement" >
								<div id="estimated_reach"></div>
							</div>
							<?php
						}
						?>
					</div>

					<!-- End fo field_target div -->
					<?php
				}
				// End for fields not empty condition
				?>
			</div>
			<!--end of social_target div -->
			<div style="clear:both;"></div>
		</div>
	</div>
<?php
}
