<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo JHTML::_( 'form.token' ); ?>
<style>
label {
	width: auto !important;
	float: none !important;
	clear: none !important;
	display: inline !important;
}
input {
	float: none !important;
	clear: none !important;
	display: inline !important;
}
</style>

<script language="javascript" type="text/javascript">


var to_translate = {
	description: {
		title: "<?php echo JText::_("DESCRIPTION"); ?>",
		type: 'input'
		},
	default: {
		title: "<?php echo JText::_("DEFAULT_VALUE"); ?>",
		type: 'input'
		},
	blankmessage: {
		title: "<?php echo JText::_("MISSING_MESSAGE"); ?>",
		type: 'input'
		},
	helptext: {
		title: "<?php echo JText::_("Help Text"); ?>",
		type: 'html'
		}
}

jQuery(document).ready( function () {
	displayTranslations();
});

<?php 

$langs = array();
$jl = JLanguage::getKnownLanguages();
foreach ($jl as $key => $language)
{
	$langs[] = str_replace("-", "", $language['tag']) . ": '" . $language['name'] . "'";
}

?>

var tr_langs = { <?php echo implode(", ", $langs); ?> };

function doTranslate()
{
	var url = '<?php echo JRoute::_('index.php?option=com_fss&view=translate&tmpl=component', false); ?>&data=' + encodeURIComponent(JSON.stringify(to_translate));
	TINY.box.show({iframe:url, width:820,height:640});
}

Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == "translate") {
		return doTranslate();
	}
	Joomla.submitform(pressbutton);
}

</script>

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }
        submitform(pressbutton);
}
//-->

function DoAllProdChange()
{
	var form = document.adminForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		jQuery('#prodlist').hide();
	} else {
		jQuery('#prodlist').show();
	}
}

function DoAllDeptChange()
{
	var form = document.adminForm;
		
	if (form.alldepts[1].checked)
    {
		jQuery('#deptlist').hide();
	} else {
		jQuery('#deptlist').show();
	}
}

function HideAllTypeSettings()
{
	jQuery('#no_settings').hide();
	jQuery('#checkbox_settings').hide();
	jQuery('#text_settings').hide();
	jQuery('#radio_settings').hide();
	jQuery('#combo_settings').hide();
	jQuery('#area_settings').hide();
	jQuery('#plugin_settings').hide();
	
	jQuery('#checkbox_default').hide();
	jQuery('#text_default').hide();
	jQuery('#radio_default').hide();
	jQuery('#combo_default').hide();
	jQuery('#area_default').hide();
	jQuery('#plugin_default').hide();
}

function ShowType(atype)
{
	if (atype == '') atype = 'no';
	
	jQuery('#' + atype + '_settings').show();
	
	if (atype != 'no')
		jQuery('#' +atype + '_default').show();

	if (atype == "text" || atype == "combo" || atype == "area" || atype == "plugin")
	{
		jQuery('#basicsearch').show();
	} else {
		jQuery('#basicsearch').hide();
	}

	/*if (atype == "plugin")
	{
		jQuery('#advsearch').hide();
	} else {
		jQuery('#advsearch').show();
	}*/

	if (atype != "area")
	{
		jQuery('#inlist').show();
	} else {
		jQuery('#inlist').hide();
	}
}

function DoTypeChange(control)
{
	HideAllTypeSettings();
	ShowType(control.value);
}

function plugin_changed()
{
	var plugin = jQuery('#plugin').val();
	if (plugin == "")
	{
		jQuery('#plugin_sub_settings').html("Please select a plugin");
		return;
	}
	jQuery('#plugin_sub_settings').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	
	var url = '<?php echo FSSRoute::_('index.php?option=com_fss&controller=field&task=plugin_form', false); ?>&plugin=' + jQuery('#plugin').val();
	jQuery.get(url, function (data) {
		jQuery('#plugin_sub_settings').html(data);
	});

	fname = "try { " + plugin + "_plugin_selected(); } catch (e) {}";
	setTimeout(fname, 100);
}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="description" id="description" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->field->description);?>" />
			</td>
		</tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("Alias"); ?>:
			    </label>
		    </td>
		    <td>
				<input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->field->alias);?>" />
		    </td>
	    </tr>		
		<?php FSSAdminHelper::LA_Form($this->field, true); ?>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("WHERE_USED"); ?>:
			    </label>
		    </td>
		    <td>
				<?php echo $this->ident; ?>
		    </td>
	    </tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("TYPE"); ?>:
				</label>
			</td>
			<td>
				<?php 
				$options = array(
					'checkbox' => JText::_("CHECKBOX"),
					'text' => JText::_("TEXT_ENTRY"),
					'radio' => JText::_("RADIO_GROUP"),
					'combo' => JText::_("COMBO_BOX"),
					'area' => JText::_("TEXT_AREA"),
					'plugin' => JText::_("PLUGIN"),
					);
					
				$dropdown = JHTML::_('select.genericlist',  $options, 'type', 'class="inputbox" size="1" onchange="DoTypeChange(this);"', '', '', $this->field->type);
				echo $dropdown;
				?>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key" valign="top">
				<label for="question">
					<?php echo JText::_("FIELD_SETTINGS"); ?>:
				</label>
			</td>
			<td>
				<div id='no_settings' class="form-horizontal plugin-settings form-condensed">
					<?php echo JText::_("PLEASE_SELECT_A_FIELD_TYPE"); ?>
				</div>
				<!-- Settings for checkbox -->
				<div id="checkbox_settings" class="form-horizontal plugin-settings form-condensed">
					<?php echo JText::_("NO_SETTINGS_NEEDED_FOR_A_CHECKBOX_FIELD"); ?>
				</div>
				<!-- Settings for text -->
				<div id="text_settings" class="form-horizontal plugin-settings form-condensed">
					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("MINIMUM_CHARACTERS"); ?></label>
						</div>
						<div class="controls">
							<input type='text' name="text_min" value="<?php echo $this->text_min; ?>">
						</div>
					</div>					
					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("MAXIMUM_CHARACTERS"); ?></label>
						</div>
						<div class="controls">
							<input type='text' name="text_max" value="<?php echo $this->text_max; ?>">
						</div>
					</div>					
					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("INPUT_SIZE"); ?></label>
						</div>
						<div class="controls">
							<input type='text' name="text_size" value="<?php echo $this->text_size; ?>">
						</div>
					</div>

				</div>
				<!-- Settings for radio -->
				<div id="radio_settings" class="form-horizontal plugin-settings form-condensed">
					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("RADIO_GROUP_VALUES"); ?></label>
						</div>
						<div class="controls">
							<textarea cols="40" rows="5" style="width: 420px;" name="radio_values"><?php echo $this->values; ?></textarea>
							<span class="help-inline">
								<i><?php echo JText::_("PLEASE_ENTER_ONE_VALUE_PER_ROW"); ?></i>
							</span>
						</div>
					</div>
				</div>

				<!-- Settings for combo -->
				<div id="combo_settings" class="form-horizontal plugin-settings form-condensed">
					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("COMBO_BOX_VALUES"); ?></label>
						</div>
						<div class="controls">
							<textarea cols="40" rows="10" style="width: 420px;" name="combo_values"><?php echo $this->values; ?></textarea>
							<span class="help-inline">
								<i><?php echo JText::_("PLEASE_ENTER_ONE_VALUE_PER_ROW"); ?></i>
							</span>
						</div>
					</div>
				</div>
				<!-- Settings for area -->
				<div id="area_settings" class="form-horizontal plugin-settings form-condensed">
					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("AREA_WIDTH"); ?></label>
						</div>
						<div class="controls">
							<input type='text' name="area_width" value="<?php echo $this->area_width; ?>">
						</div>
					</div>					
					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("AREA_HEIGHT"); ?></label>
						</div>
						<div class="controls">
							<input type='text' name="area_height" value="<?php echo $this->area_height; ?>">
						</div>
					</div>					
				</div>
				<!-- Settings for plugin -->
				<div id="plugin_settings" class="form-horizontal plugin-settings form-condensed">

					<div class="control-group">
						<div class="control-label">
							<label class="control-label"><?php echo JText::_("PLUGIN"); ?></label>
						</div>
						<div class="controls">
							<?php echo $this->pllist; ?>
							<span class="help-inline"><a href='http://freestyle-joomla.com/help/other/knowledge-base?kbartid=91' target="_blank">Creating Plugins Help</a></span>
						</div>
					</div>

					<div id='plugin_sub_settings'>
						<?php echo $this->plugin_form; ?>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("DEFAULT_VALUE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="default" id="default" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->field->default);?>" />
				<div stlye='display: inline-block'>
				<div id='checkbox_default'><?php echo JText::_("ENTER_ON_TO_HAVE_THE_CHECKBOX_CHECKED_BY_DEFAULT"); ?></div>
				<div id='text_default'><?php echo JText::_("ENTER_THE_DEFAULT_TEXT"); ?></div>
				<div id='radio_default'><?php echo JText::_("ENTER_ONE_OF_THE_VALUES_TO_HAVE_IT_SELECTED_BY_DEFAULT_LEAVE_THIS_BLANK_TO_HAVE_NOTHING_SELECTED_BY_DEFAULT"); ?></div>
				<div id='combo_default'><?php echo JText::_("ENTER_ONE_OF_THE_VALUES_TO_HAVE_IT_SELECTED_BY_DEFAULT_LEAVE_THIS_BLANK_TO_HAVE_NOTHING_SELECTED_BY_DEFAULT"); ?></div>
				<div id='area_default'><?php echo JText::_("ENTER_THE_DEFAULT_TEXT"); ?></div>
				</div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("REQUIRED_FIELD"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='required' value='1' <?php if ($this->field->required) { echo " checked='yes' "; } ?>>
	        </td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("MISSING_MESSAGE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="blankmessage" id="blankmessage" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->field->blankmessage);?>" /><br>
            </td>
		</tr>		<tr>
			<td width="135" align="right" class="key" valign="top">
				<label for="description">
					<?php echo JText::_("Javascript"); ?>:
				</label>
			</td>
			<td>
				<textarea name="javascript" id="javascript" cols="80" rows="6" style="width:544px;"><?php echo FSS_Helper::escape($this->field->javascript);?></textarea>
				<p><?php echo JText::_("You can enter any custom javascript that you would like run when a field is shown. You do not need to output the script tags."); ?></p>
            </td>
		</tr>
		
	</table>
	</fieldset>
</div>

<div id="ticket_field_settings">
	<fieldset class="adminform">
		<legend><?php echo JText::_("TICKET_FIELD_SETTINGS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("GROUPING"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="grouping" id="grouping" size="32" maxlength="250" value="<?php echo FSS_Helper::escape($this->field->grouping);?>" />
				<div style='display: inline-block'><?php echo JText::_("USE_THIS_TO_SEPARATE_A_SET_OF_OPTIONS_INTO_A_DIFFERENT_GROUPING_WHEN_USER_CREATES_A_SUPPORT_TICKET"); ?></div>
            </td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("PER_USER_FIELD"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='peruser' value='1' <?php if ($this->field->peruser) { echo " checked='yes' "; } ?>><div style='display: inline'>
				<?php echo JText::_("PER_USER_FIELD_HELP"); ?>
				<b>WARNING: If you change this for an existing field, any stored data will be lost</b></div>
            </td>
		</tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("SUPPORT_FOR_WHICH_PRODUCTS"); ?>:
			    </label>
		    </td>
		    <td>
				<div>
					<?php echo JText::_("ALL_PRODUCTS"); ?>
					<?php echo $this->allprod; ?>
				</div>
				<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
					<?php echo $this->products; ?>
				</div>
		    </td>
	    </tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("SUPPORT_FOR_WHICH_DEPARTMENTS"); ?>:
			    </label>
		    </td>
		    <td>
				<div>
					<?php echo JText::_("ALL_DEPARTMENTS"); ?>
					<?php echo $this->alldept; ?>
				</div>
				<div id="deptlist" <?php if ($this->alldepts) echo 'style="display:none;"'; ?>>
					<?php echo $this->departments; ?>
				</div>
		    </td>
	    </tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("FIELD_PERMISSIONS"); ?>:
			    </label>
		    </td>
		    <td>
				<?php echo $this->fieldperm; ?>
		    </td>
	    </tr>
		<tr id="basicsearch">
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SEARCH_IN_BASIC"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='basicsearch' value='1' <?php if ($this->field->basicsearch) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
		<tr id="advsearch">
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SEARCH_IN_ADAVANCE"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='advancedsearch' value='1' <?php if ($this->field->advancedsearch) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
		<tr id="inlist">
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SHOW_ON_TICKET_LIST"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='inlist' value='1' <?php if ($this->field->inlist) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("Hide on admin pages"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='adminhide' value='1' <?php if ($this->field->adminhide) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>		
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("Hide on open ticket pages"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='openhide' value='1' <?php if ($this->field->openhide) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>		
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("Show for which users"); ?>:
			    </label>
		    </td>
		    <td>
				<?php echo $this->whichusers; ?>
		    </td>
	    </tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("Help Text"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor = JFactory::getEditor();
				echo $editor->display('helptext', htmlspecialchars($this->field->helptext, ENT_COMPAT, 'UTF-8'), '550', '400', '60', '20', array('pagebreak'));
				?>
            </td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->field->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="field" />
<input type="hidden" name="ordering" value="<?php echo $this->field->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->field->published; ?>" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->field->translation,ENT_QUOTES,"utf-8"); ?>" />
</form>

<script>
HideAllTypeSettings();
ShowType('<?php echo $this->field->type; ?>');

function ident_changed()
{
	var value = jQuery('#ident').val();
	
	if (value == 0)
	{
		jQuery('#ticket_field_settings').show();
		jQuery('#plugin_opt').show();
	} else {
		jQuery('#ticket_field_settings').hide();
		jQuery('#plugin_opt').hide();
	}
}

ident_changed();
</script>
