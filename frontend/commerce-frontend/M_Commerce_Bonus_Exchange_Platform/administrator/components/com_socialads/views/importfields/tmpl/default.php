<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC' ) or die(';)');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
jimport( 'joomla.filesystem.folder');

$params      = JComponentHelper::getParams('com_socialads');
$integration = $params->get('social_integration');

// Load CB language file
if ($integration == 'Community Builder')
{
	$cbpath = JPATH_SITE . '/administrator/components/com_comprofiler';

	if (JFolder::exists($cbpath))
	{
		global $_CB_framework, $_CB_database, $ueConfig, $mainframe;
		include_once JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php';
		require JPATH_SITE . '/components/com_comprofiler/plugin/language/default_language/language.php';
	}
}

// Load Easy social language file
if ($integration == 'EasySocial')
{
	$espath       = JPATH_SITE . '/administrator/components/com_easysocial';
	$lang         = JFactory::getLanguage();
	$extension    = 'com_easysocial';
	$base_dir     = JPATH_ADMINISTRATOR;
	$language_tag = 'en-GB';
	$reload       = true;
	$lang->load($extension, $base_dir, $language_tag, $reload);
}

// Import helper for declaring language constant
JLoader::import('SocialadsHelper', JUri::root().'administrator/components/com_socialads/helpers/socialads.php');

// Call helper function
SocialadsHelper::getLanguageConstant();
?>
<div class="<?php echo SA_WRAPPER_CLASS;?> sa-ad-importfields">
	<?php
	// Version 3.0 Jhtmlsidebar for menu
	if (JVERSION >= 3.0)
	{
		if (!empty( $this->sidebar))
		{?>
			<div id="j-sidebar-container" class="span2">
				<?php echo $this->sidebar; ?>
			</div>
			<div id="j-main-container" class="span10">
		<?php
		}
		else
		{ ?>
			<div id="j-main-container">
		<?php
		}
	}
	?>
	<form action="" class="form-validate" method="post" name="adminForm" onSubmit="return saAdmin.importfields.formValidation(this);">
		<?php
		$k = 0;
		$flag = 0;
		$i = 0;
		$model = $this->getModel();

		if (empty($this->fields)  &&  ($integration == 'JomSocial'))
		{?>
			<div class="alert alert-info">
				<span ><?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_JS_IS_NOTINSTALL'); ?> </span>
			</div>

		<?php
		}
		elseif(empty($this->fields)  && ($integration == 'Community Builder'))
		{
				if (!JFolder::exists($cbpath))
				{?>
					<div class="alert alert-info">
						<span>
							<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_CB_IS_NOTINSTALL'); ?>
						</span>
					</div>
				<?php
				}
				else
				{?>
					<div class="alert alert-info">
						<span>
							<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_CBINSTALL_FIELDS'); ?>
						</span>
					</div>
				<?php
				}
		}
		elseif (empty($this->fields)  && ($integration == 'EasySocial'))
		{
			if (!JFolder::exists($cbpath))
			{?>
				<div class="alert alert-info">
					<span >
						<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_ES_IS_NOTINSTALL'); ?>
					</span>
				</div>
			<?php
			}
			else
			{?>
				<div class="alert alert-info">
					<span>
						<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_ESINSTALL_FIELDS'); ?>
					</span>
				</div>
			<?php
			}
		}
		elseif ($integration == 'Joomla')
		{?>
			<div class="alert alert-info">
				<span>
					<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_NO_SOCIAL_TAR'); ?>
				</span>
			</div>
		<?php
		}
		else
		{ ?>
			<div id='no-more-tables'>
				<table class="table table-striped" id="List">
					<thead>
						<tr>
							<th>
								<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL_TOOLTIP'), JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL_HEAD'),  '', JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL'));?>
							</th>
							<th>
								<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL_FOR_ADVERTISER_TOOLTIP'), JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL_FOR_ADVERTISER_HEAD'),  '', JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL_FOR_ADVERTISER'));?>
							</th>
							<th>
								<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_SOCIAL_TARGETING_MAP_WITH_FIELD_TYPE_TOOLTIP'), JText::_('COM_SOCIALADS_SOCIAL_TARGETING_MAP_WITH_FIELD_TYPE_HEAD'),  '', JText::_('COM_SOCIALADS_SOCIAL_TARGETING_MAP_WITH_FIELD_TYPE'));?>
							</th>
							<th>
							<?php echo JHtml::tooltip(JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FUZZY_OR_EXACT_MATCH_TOOLTIP'), JText::_('COM_SOCIALADS_SOCIAL_TARGETING_SELECT_FUZZY_OR_EXACT_HEAD'),  '', JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FUZZY_OR_EXACT_MATCH'));?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$count = 0;

						foreach ($this->fields as $row)
						{
							if ($row->mapping_fieldid)
							{
								$disabled = 'disabled="true"';
							}
							else
							{
								$disabled = '';
							}
							$flag++;
							?>
							<tr class="<?php echo 'row'.$k; ?>" id="<?php echo 'row'.$count; ?>">
								<?php
								if ($integration == 'Community Builder')
								{
									$row->field_label = htmlspecialchars( getLangDefinition( $row->field_label));
								}
								else
								{
									$row->field_label = JText::_("$row->field_label");
								}
								?>
								<td id="<?php echo 'row'.$count.'[1]'; ?>" data-title="<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL');?>">
									<?php echo $row->field_label . " (" . $row->id . ")"; ?>
								</td>
								<td id="<?php echo 'row'.$count.'[2]'; ?>" data-title="<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FIELD_LABEL_FOR_ADVERTISER');?>">
									<?php
									$mapval = JText::_("$row->mapping_label") ? JText::_("$row->mapping_label") : JText::_("$row->field_label");
									?>
									<input type="text" class = "input-medium" name="mappinglist[<?php echo $row->id; ?>][label]" <?php echo $disabled; ?> value="<?php echo $mapval; ?>" />
								</td>
								<td style="display:none;" id="<?php echo 'row'.$count.'[3]'; ?>" data-title="<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_MAP_WITH_FIELD_TYPE');?>">
									<?php echo JHtml::_('select.genericlist', $this->fields, 'mappinglist['.$row->id.'][fieldid]',
									'class="input-medium inputbox" '.$disabled, 'id', 'field_label', $row->id ); ?>
								</td>
								<td id="<?php echo 'row'.$count.'[4]'; ?>" data-title="<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_MAP_WITH_FIELD_TYPE');?>">
									<?php
									// Validating fields depends upon field type
									if ($row->type=='text'  || $row->type=='lable' || $row->type=='email' || $row->type=='textbox' || $row->type=='joomla_fullname' || $row->type=='joomla_username' || $row->type=='joomla_email' || $row->type=='permalink')
									{
										$list = $this->mappinglistt;
									}
									elseif ($row->type=='textarea' || $row->type=='url' ||  $row->type=='address')
									{
										$list = $this->mappinglista;
									}
									elseif ($row->type=='date' || $row->type=='time' || $row->type=='birthdate'|| $row->type=='joomla_timezone' || $row->type=='birthday')
									{
										$list = $this->mappinglistd;
									}
									else
									{
										// Select,singleselect,list,radio,checkbox,country.
										$list = $this->mappinglists;
									}
									echo JHtml::_('select.genericlist', $list, 'mappinglist['.$row->id.'][fieldtype]', 'class="input-medium inputbox fieldlist " onchange=saAdmin.importfields.numericRangeCheck(this,'.$row->id.','.$count.'); ' .$disabled, 'value', 'text', $row->mapping_fieldtype); ?>
								</td>
								<?php
								$match = array(0=>JText::_("COM_SOCIALADS_SOCIAL_TARGETING_FUZZY"), 1=>JText::_("COM_SOCIALADS_SOCIAL_TARGETING_EXACT"));
								$field_array = array();

								if (!empty($match))
								{
									$options = array();

									foreach ($match as $key => $value)
									{
										$options[] = JHtml::_('select.option', $key, $value);
									} ?>
									<input type="hidden" name="mappinglist[<?php echo $row->id; ?>][fieldcode]" value="<?php echo $row->mapping_fieldname; ?>" <?php echo $disabled; ?> />
									<td id="<?php echo 'row'.$count.'[5]'; ?>" data-title="<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_FUZZY_OR_EXACT_MATCH');?>">
										<span id="<?php echo 'row'.$count.'[5]radios'; ?>" style="display:block">
											<?php
											if ($row->type == "list" ||  $row->type=='textarea' ||  $row->type=='checkbox'  || $row->type=='multiselect' || $row->type=='multicheckbox' || $row->type=='address' || $row->type=='multilist' || $row->type=='dropdown')
											{
												echo JText::_("COM_SOCIALADS_SOCIAL_TARGETING_EXACT");
												echo '<input type="hidden"  name="match['.$row->id.']" value="0" />';
											}
											elseif ($row->type == "select" || $row->type == "singleselect" || $row->type=='country' || $row->type == 'radio' || $row->type == 'boolean' || $row->type == 'gender')
											{
												echo JText::_("COM_SOCIALADS_SOCIAL_TARGETING_EXACT");
												echo '<input type="hidden" name="match['.$row->id.']" value="1" />';
											}
											elseif ($row->type=='text' || $row->type=='textbox')
											{
												if ($row->mapping_fieldtype != "numericrange")
												{
													echo $radiolist = JHtml::_('select.radiolist', $options, 'match['.$row->id.']', 'class="inputbox fieldlist"' .$disabled,
													'value', 'text', $row->mapping_match);
												}
												else
												{
													echo JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DOES_NOT_AAPLY");
												}
											}
											else
											{
												echo JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DOES_NOT_AAPLY");
												echo '<input type="hidden" name="match['.$row->id.']" value="2" />';
											} ?>
										</span>
										<span id="<?php echo 'row'.$count.'[5]noradios' ?>" style="display:none">
											<?php
											echo JText::_("COM_SOCIALADS_SOCIAL_TARGETING_DOES_NOT_AAPLY");
											echo '<input type="hidden" name="match['.$row->id.']" id="match['.$row->id.']" value="2"  />';
											?>
										</span>
									</td>
									<?php
								} ?>
							</tr>
							<?php
								$k = 1 - $k;
								$i ++;
								$count ++;
						} //foreach ends
						$count = 0;

						foreach ($this->pluginresult as $rowplugin)
						{
							$countbutton = 0;
							$currentversion = '';

							// Load the xml file
							if (JVERSION >= '1.6.0')
							{
								$pluginXml = JPATH_SITE."/plugins/socialadstargeting/$rowplugin->element/$rowplugin->element.xml";
							}
							else
							{
								$pluginXml = JPATH_SITE."/plugins/socialadstargeting/$rowplugin->element.xml";
							}

							$xml = JFactory::getXML($pluginXml);
							$currentversion = (string)$xml->version;
							$col_value = array();

							if ($xml)
							{
								$xml = json_decode(json_encode((array)$xml), TRUE);

								foreach ($xml as $key => $var)
								{
									if ($key == 'satargeting')
									{
										foreach ($var as $minikey => $val)
										{
											if ($minikey =='plgfield')
											{
												$col_value[] = $val;
											}
										}
									}
								}
							}

							if (!empty($col_value))
							{
								$field_array = array();

								if (!empty($this->colfields))
								{
									for ($i = 0; $i < count($this->colfields); $i++)
									{
										$field_array[] = $this->colfields[$i]->Field;
									}
								}

								foreach ($col_value as $field_value)
								{
									if (!in_array($field_value, $field_array))
									{
										$countbutton = 1;
									}
								}
							} ?>
							<tr class="<?php echo 'row'.$k; ?>">

								<td>
									<span title="<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_PLUGINS');?>" >
									<?php echo $rowplugin->name;?>
									<input type="hidden" name="plugin[<?php echo $count;?>]" id="plugin[<?php echo $count;?>]" value="plugin<?php echo $rowplugin->id;?>">
								</td>
								<td colspan="3">
									<?php
									$chechvalue = "";

									if ($rowplugin->enabled == "1")
									{
										$chechvalue='checked';
										$check_display="block";

									}
									else
									{
										if ($countbutton == 1)
										{
											$check_display="none";
										}
										else
										{
											$check_display="block";
											$chechvalue='';
										}
									} ?>
									<div class="sa-imprtfields-controls-inline">
										<span name = "chk<?php echo $rowplugin->element; ?>" id="chk<?php echo $rowplugin->element; ?>"
											style="display:<?php echo $check_display;?>">
											<input type="checkbox" name="pluginchk[<?php echo $count;?>]" id="plugin[chk<?php echo $count;?>]" <?php echo $chechvalue;?> >
										</span>
										<?php
										if ($countbutton == 1)
										{?>
											<input class="sa-imprtfields-controls-left-margin btn btn-small" type = "button" onclick = "saAdmin.importfields.installTargetingPlugins(this);" name = "<?php echo $rowplugin->element;?>" id = "<?php echo $rowplugin->element;?>" value = "<?php echo JText::_('COM_SOCIALADS_SOCIAL_TARGETING_PLGINSTALL_CLK');?>" >
										<?php
										} ?>
										<span id="message1<?php echo $rowplugin->element;?>"  ></span>
									</div>
								</td>
							</tr>
							<?php
							$k = 1 - $k;
							$count++;
						} ?>
					</tbody>
				</table>
			</div>
		<?php
		}
		// Install condition ends
		?>
		<input type="hidden" name="check" value="post"/>
		<input type="hidden" name="resetall" value="0"/>
		<input type="hidden" name="option" value="com_socialads" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="importfields" />
		<?php
		if (!empty($this->fields))
		{
			if (count($this->fields)==$flag)
			{  ?>
				<input type="hidden" name="boxchecked" value="0" />
			<?php
			}
			elseif ($this->adcount==0)
			{ ?>
				<input type="hidden" name="boxchecked" value="1" />
			<?php
			}
			else
			{ ?>
				<input type="hidden" name="boxchecked" value="2" />
				<?php
			}
		}?>
		<input type="hidden" name="controller" value="importfields" />
		<?php
		if (!empty( $this->sidebar))
		{ ?>
			</div>
		<?php
		} ?>
	</form>
	<!-- form for showing admin view of social_targetting ends here -->
</div>
</div>
<script type="text/javascript">
	/**To reset selected fields*/
	Joomla.submitbutton = function(task) {saAdmin.importfields.submitImportfields(task);}
	var token = '<?php echo  JSession::getFormToken(); ?>';
</script>
