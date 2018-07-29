<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php 
$basicstyle = ''; 
$advancedstyle = 'display:none;';
$default = "basic";
if (FSS_Settings::get('support_advanced_default'))
	$default = "advanced";

$searchtype = FSS_Input::getCmd('searchtype',$default);

if (FSS_Input::getInt('showbasic') == 1)
	$searchtype = "basic";

if ($searchtype == "advanced")
{
	$basicstyle = $advancedstyle;
	$advancedstyle = "";
}

?>

<?php if (FSS_Input::getInt('showbasic') == 1): ?>
<input type="hidden" id="showbasic" name="showbasic" value="1">	
<?php endif; ?>

<input type="hidden" id="searchtype" name="searchtype" value="<?php echo FSS_Helper::escape(FSS_Input::getCmd('searchtype',$default)); ?>">
<input type="hidden" name="what" id="fss_what" value="<?php echo FSS_Helper::escape(FSS_Input::getCmd('what','')); ?>">

<div class="pull-right btn-group">
	<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
		<i class="icon-cog"></i>
		<?php echo JText::_('Tools'); ?>
		<span class="caret"></span>
	</a>
	
	<ul class="dropdown-menu">
		<li>
			<a href="#" onclick="showbasicsearch(); return false;">
				<?php echo JText::_("BASIC_SEARCH") ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="showadvsearch(); return false;">
				<?php echo JText::_("ADVANCED_SEARCH") ?>
			</a>
		</li>
		
		<li class="divider"></li>
		<li>
			<a href="#" onclick="toggleBatch(1);return false;">
				<?php echo JText::_("BATCH_ACTIONS"); ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="toggleBatch(2);return false;">
				<?php echo JText::_("BATCH_PRINT"); ?>
			</a>
		</li>

		<li class="divider"></li>
		<li>
			<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=emails'); ?>">
				<?php echo JText::_("EMAIL_IMPORTS"); ?>
			</a>
		</li>			
		<li class="divider"></li>
		<li>
			<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=settings'); ?>">
				<?php echo JText::_("MY_SETTINGS"); ?>
			</a>
		</li>		
		<li>
			<a class="show_modal_iframe" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=signature&tmpl=component'); ?>">
				<?php echo JText::_("SIGNATURES"); ?>
			</a>
		</li>
		<li>
			<a class="show_modal_iframe" href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=canned&tmpl=component'); ?>">
				<?php echo JText::_("CANNED_REPLIES"); ?>
			</a>
		</li>	
		<li>
			<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=outofoffice'); ?>">
				<?php echo JText::_("OUT_OF_OFFICE"); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=listhandlers'); ?>">
				<?php echo JText::_("LIST_HANDLERS"); ?>
			</a>
		</li>

		<?php echo FSS_GUIPlugins::output("adminTicketListTools"); ?>
	</ul>
</div>

<div class="form-horizontal form-condensed">

	<?php if (!FSS_Settings::get('support_no_admin_for_user_open')): ?>
 	<div class="control-group">
		<label class="control-label"><?php echo JText::_("CREATE_TICKET_FOR"); ?></label>
		<div class="controls">
			<a class="btn btn-default" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=registered' ); ?>"><?php echo JText::_("REGISTERED_USER"); ?></a>
			<?php if (FSS_settings::get('support_allow_unreg')): ?>
				<a class="btn btn-default" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=unregistered' ); ?>"><?php echo JText::_("UNREGISTERED_USER"); ?></a>	
			<?php endif; ?>
			<div style="line-height: 25px;display: inline-block;">&nbsp;</div>
		</div>
	</div>
	<?php endif; ?>


	<div class="control-group" id="basicsearch" style="<?php echo $basicstyle ?>">
		<label class="control-label"><?php echo JText::_("SEARCH_TICKETS"); ?></label>
		<div class="controls">
			<div class="input-append">
				<input type="text" name="search" class='input-medium' id="basic_search" value="<?php echo FSS_Helper::escape(FSS_Input::getString('search','')); ?>">
				<input class='btn btn-primary' type="submit" onclick="jQuery('#searchtype').val('basic');fss_submit_search();" value="<?php echo JText::_("SEARCH") ?>">
				<input class='btn btn-default' type="submit" onclick="resetbasic(); return false;" value="<?php echo JText::_("RESET") ?>">
			</div>

			<span id='basic_ordering'>
			<?php echo $this->orderSelect(); ?>
			</span>
		</div>
	</div>

	<table class="advsearch table table-borderless table-condensed" style="<?php echo $advancedstyle ?>;margin-bottom: 0px;">

<?php
$fieldnum = 0;
$resetadvanced = "";
global $resetadvanced;

function NextField()
{
	global $fieldnum;
	$fieldnum++;

	if ($fieldnum == 1)
	{
		echo "<tr>";
		return;	
	}

	if ($fieldnum % 2 == 1)
	{
		echo "</tr><tr>";	
	}
}

function AddAdvancedReset($name)
{
	global $resetadvanced;
	$resetadvanced .= "jQuery('#$name').val('');\n";
}
?>

	<?php NextField(); ?>
			<td><?php echo JText::_("SUBJECT") ?>:</td>
			<td><input type="text" name="subject" id="advanced_subject" value="<?php echo FSS_Helper::escape(FSS_Input::getString('subject','')); ?>"></td>
			<?php AddAdvancedReset("advanced_subject"); ?>
			
	<?php NextField(); ?>
			<td><?php echo JText::_("TICKET_REF") ?>:</td>
			<td><input type="text" name="reference" id="advanced_ref" value="<?php echo FSS_Helper::escape(FSS_Input::getString('reference','')); ?>"></td>
			<?php AddAdvancedReset("advanced_ref"); ?>
			
	<?php NextField(); ?>
			<td><?php echo JText::_("USERNAME") ?>:</td>
			<td>
				<div id="user_select" class="btn-group">
					<input type="text" name="username" id="advanced_username" autocomplete="off" value="<?php echo FSS_Helper::escape(FSS_Input::getString('username','')); ?>">
					<ul class="dropdown-menu" id="user_select_list">
						<li><a href="#">OK!</a></li>
					</ul>
				</div>
			</td>
			<?php AddAdvancedReset("advanced_username"); ?>
			
	<?php NextField(); ?>
			<td><?php echo JText::_("EMAIL") ?>:</td>
			<td><input type="text" name="useremail" id="advanced_email" value="<?php echo FSS_Helper::escape(FSS_Input::getString('useremail','')); ?>"></td>
			<?php AddAdvancedReset("advanced_email"); ?>
			
	<?php NextField(); ?>
			<td><?php echo JText::_("NAME") ?>:</td>
			<td><input type="text" name="userfullname" id="advanced_name" value="<?php echo FSS_Helper::escape(FSS_Input::getString('userfullname','')); ?>"></td>
			<?php AddAdvancedReset("advanced_name"); ?>
			
	<?php if (FSS_Settings::get('support_hide_handler') != 1) : ?>
	<?php NextField(); ?>
			<td><?php echo JText::_("HANDLER") ?>:</td>
			<td>
			<select name="handler[]" id="advanced_handler" class="select-color" multiple="true" data-placeholder="<?php echo htmlspecialchars(JText::_("ALL_HANDLERS")); ?>">
					<?php $handlerids = FSS_Input::getIntArray('handler',''); ?>
					<optgroup label="<?php echo JText::_('QUICK'); ?>">
						<option value="-1" class="text-success" <?php if (in_array(-1, $handlerids)) echo " SELECTED "; ?>><?php echo JText::_('MY_TICKETS'); ?></option>
						<option value="-2" class="text-info" <?php if (in_array(-2, $handlerids)) echo " SELECTED "; ?>><?php echo JText::_('OTHER_HANDLERS_TICKETS'); ?></option>
						<option value="-3" class="text-warning" <?php if (in_array(-3, $handlerids)) echo " SELECTED "; ?>><?php echo JText::_('UNASSIGNED'); ?></option>
						<option value="-4" class="text-info" <?php if (in_array(-4, $handlerids)) echo " SELECTED "; ?>><?php echo JText::_('MY_CC_TICKETS'); ?></option>
						<option value="-5" class="text-success" <?php if (in_array(-5, $handlerids)) echo " SELECTED "; ?>><?php echo JText::_('MY_ASSIGNED_TICKETS'); ?></option>
					</optgroup>
					<optgroup label="<?php echo JText::_('HANDLERS'); ?>">
						<?php foreach ($this->handlers as $handler) :?>
							<option value="<?php echo $handler->id ?>" <?php if (in_array($handler->id, $handlerids)) echo " SELECTED "; ?>><?php echo $handler->name ?></option>
						<?php endforeach; ?>
					</optgroup>
				</select>			
				<?php AddAdvancedReset("advanced_handler"); ?>
			</td>
	<?php endif; ?>
	
	<?php NextField(); ?>
			<td><?php echo JText::_("MESSAGE") ?>:</td>
			<td><input type="text" name="content" id="advanced_message" value="<?php echo FSS_Helper::escape(FSS_Input::getString('content','')); ?>"></td>
			<?php AddAdvancedReset("advanced_message"); ?>
			
	<?php NextField(); ?>
			<td><?php echo JText::_("STATUS") ?>:</td>
			<td>
				<?php $statusids = FSS_Input::getCmdArray('status'); ?>
				<select name="status[]" id="advanced_status" multiple="true" class="select-color" data-placeholder="<?php echo htmlspecialchars(JText::_("ALL_STATUSES")); ?>">
					<optgroup label="<?php echo JText::_('STATUSS'); ?>">
						<?php foreach ($this->statuss as $status) :?>
							<option value="<?php echo $status->id ?>" <?php if (in_array($status->id, $statusids)) echo " SELECTED "; ?> style='color: <?php echo FSS_Helper::escape($status->color); ?>'><?php echo $status->title ?></option>
						<?php endforeach; ?>
					</optgroup>
					<optgroup label="<?php echo JText::_('GROUPS'); ?>">
						<option value="allopen" <?php if (in_array("allopen", $statusids)) echo " SELECTED "; ?>><?php echo JText::_('S_ALLOPEN'); ?></option>
						<option value="closed" <?php if (in_array("closed", $statusids)) echo " SELECTED "; ?>><?php echo JText::_('S_CLOSED'); ?></option>
						<option value="all" <?php if (in_array("all", $statusids)) echo " SELECTED "; ?>><?php echo JText::_('S_ALL'); ?></option>
					</optgroup>
				</select>				
			</td>
			<?php AddAdvancedReset("advanced_status"); ?>
			
	<?php if (count($this->products) > 0): ?>
	<?php NextField(); ?>
			<td><?php echo JText::_("PRODUCT") ?>:</td>
			<td>
			<?php $products = FSS_Input::getIntArray('product'); ?>
				<select name="product[]" id="advanced_product" multiple="true" data-placeholder="<?php echo htmlspecialchars(JText::_("ALL_PRODUCTS")); ?>">
					<?php foreach ($this->products as $product) :?>
						<option value="<?php echo $product->id ?>" <?php if (in_array($product->id, $products)) echo " SELECTED "; ?>><?php echo $product->title ?></option>
					<?php endforeach; ?>
				</select>		
			</td>
			<?php AddAdvancedReset("advanced_product"); ?>
	<?php endif; ?>
	
	<?php if (count($this->departments) > 0): ?>
	<?php NextField(); ?>
			<td><?php echo JText::_("DEPARTMENT") ?>:</td>
			<td>
			<?php $departmentids = FSS_Input::getIntArray('department'); ?>
				<select name="department[]" id="advanced_department" multiple="true" data-placeholder="<?php echo htmlspecialchars(JText::_("ALL_DEPARTMENTS")); ?>">
					<?php foreach ($this->departments as $department) :?>
						<option value="<?php echo $department->id ?>" <?php if (in_array($department->id, $departmentids)) echo " SELECTED "; ?>><?php echo $department->title ?></option>
					<?php endforeach; ?>
				</select>		
			</td>
			<?php AddAdvancedReset("advanced_department"); ?>
	<?php endif; ?>
	
	<?php if (count($this->categories) > 0 && FSS_Settings::get('support_hide_category') != 1): ?>
	<?php NextField(); ?>
			<td><?php echo JText::_("CATEGORY") ?>:</td>
			<td>
				<?php $catids = FSS_Input::getIntArray('cat'); ?>
				<select name="cat[]" id="advanced_cat" multiple="true" data-placeholder="<?php echo htmlspecialchars(JText::_("ALL_CATEGORIES")); ?>">
					<?php foreach ($this->categories as $cat) :?>
						<option value="<?php echo $cat->id ?>" <?php if (in_array($cat->id, $catids)) echo " SELECTED "; ?>><?php echo $cat->title ?></option>
					<?php endforeach; ?>
				</select>			
			</td>
			<?php AddAdvancedReset("advanced_cat"); ?>
	<?php endif; ?>
	
	<?php if (FSS_Settings::get('support_hide_priority') != 1) : ?>
	<?php NextField(); ?>
			<td><?php echo JText::_("PRIORITY") ?>:</td>
			<td>
			<?php $priorityids = FSS_Input::getIntArray('priority'); ?>
				<select name="priority[]" id="advanced_priority" class="select-color" multiple="true" data-placeholder="<?php echo htmlspecialchars(JText::_("ALL_PRIORITIES")); ?>">
					<?php foreach ($this->priorities as $priority) :?>
						<option value="<?php echo $priority->id ?>" <?php if (in_array($priority->id, $priorityids)) echo " SELECTED "; ?> style='color: <?php echo $priority->color; ?>'><?php echo $priority->title ?></option>
						<?php endforeach; ?>
				</select>			
			</td>
			<?php AddAdvancedReset("advanced_priority"); ?>
	<?php endif; ?>
	
	<?php if (count($this->ticketgroups) > 0): ?>
	<?php NextField(); ?>
			<td><?php echo JText::_("GROUPS") ?>:</td>
			<td>
			<?php $groupids = FSS_Input::getIntArray('group'); ?>
				<select name="group[]" id="advanced_group" multiple="true" data-placeholder="<?php echo htmlspecialchars(JText::_("ALL_GROUPS")); ?>">
					<?php foreach ($this->ticketgroups as $groups) :?>
						<option value="<?php echo $groups->id ?>" <?php if (in_array($groups->id, $groupids)) echo " SELECTED "; ?>><?php echo $groups->groupname ?></option>
					<?php endforeach; ?>
				</select>			
			</td>
			<?php AddAdvancedReset("advanced_group"); ?>
	<?php endif; ?>
	<?php
	$customfields = FSSCF::GetAllCustomFields(true);
	foreach($customfields as $field): ?>
	<?php if (!$field['advancedsearch']) continue; ?>
	<?php NextField(); ?>
			<td><?php echo $field['description'] ?>:</td>
			<td>
			<?php if ($field['type'] == "area" || $field['type'] == "text") : ?>
					<input type="text" name="custom_<?php echo $field['id']; ?>" id="advanced_custom_<?php echo (int)$field['id']; ?>" value="<?php echo FSS_Helper::escape(FSS_Input::getString('custom_'.$field['id'],'')); ?>">
				<?php elseif ($field['type'] == "checkbox") : ?>
					<select name="custom_<?php echo $field['id']; ?>" id="advanced_custom_<?php echo (int)$field['id']; ?>">
						<option value=""><?php echo JText::_("ALL_VALUES") ?></option>		
						<option value="1" <?php if (FSS_Input::getString('custom_'.$field['id'],'') == "1") echo "SELECTED"; ?> ><?php echo JText::_("YES") ?></option>		
						<option value="0" <?php if (FSS_Input::getString('custom_'.$field['id'],'') == "0") echo "SELECTED"; ?> ><?php echo JText::_("NO") ?></option>		
					</select>			
				<?php elseif ($field['type'] == "radio" || $field['type'] == "combo") : ?>
					<select name="custom_<?php echo $field['id']; ?>" id="advanced_custom_<?php echo $field['id']; ?>">
						<option value=""><?php echo JText::_("ALL_VALUES") ?></option>		
						<?php foreach($field['values'] as $value): ?>
							<?php list($offset, $value) = explode("|", $value); ?>
							<option value="<?php echo FSS_Helper::escape($value); ?>" <?php if (FSS_Input::getString('custom_'.$field['id'],'') == $value) echo "SELECTED"; ?> ><?php echo $value; ?></option>
						<?php endforeach; ?>	
					</select>
				<?php else: ?>
					<?php 
					// need to load the plugin see if it has search overridden, if so display the search
					
					// otherwise just show the plain search
					?>
					<input type="text" name="custom_<?php echo $field['id']; ?>" id="advanced_custom_<?php echo (int)$field['id']; ?>" value="<?php echo FSS_Helper::escape(FSS_Input::getString('custom_'.$field['id'],'')); ?>">
					<?php //print_p($field); ?>			
				<?php endif; ?>
			</td>
			<?php AddAdvancedReset("advanced_custom_".$field['id']); ?>
	<?php endforeach; ?>

	<?php NextField(); ?>
			<?php AddAdvancedReset("advanced_date_from"); ?>
			<td><?php echo JText::_("DATE_FROM") ?>:</td>
			<td>
				<input type="text" name="date_from" id="advanced_date_from" value="<?php echo FSS_Helper::escape(FSS_Input::getString('date_from','')); ?>" size="12">
			</td>
	<?php NextField(); ?>
			<?php AddAdvancedReset("advanced_date_to"); ?>
			<td><?php echo JText::_("DATE_TO") ?>:</td>
			<td>
				<input type="text" name="date_to" id="advanced_date_to" value="<?php echo FSS_Helper::escape(FSS_Input::getString('date_to','')); ?>"  size="12">
			</td>

	<?php NextField(); ?>
			<td><?php echo JText::_("ORDERING") ?>:</td>
			<td id='advanced_ordering'>
				
			</td>

		</tr>
	</table>

	<div class="control-group advsearch" style="<?php echo $advancedstyle ?>">
		<label class="control-label"></label>
		<div class="controls">
			<div class="btn-group">
				<input class='btn btn-primary' type="submit" onclick="jQuery('#searchtype').val('advanced');fss_submit_search();" value="<?php echo JText::_("SEARCH") ?>">
				<input class='btn btn-default' type="submit" onclick="resetadvanced(); return false;" value="<?php echo JText::_("RESET") ?>">
			</div>
		</div>
	</div>


<?php if (!FSS_Settings::get('support_hide_tags')) : ?>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("TICKET_TAGS"); ?></label>
		<div class="controls">
		
			<div class="btn-group pull-left">
				<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
					<?php echo JText::_("TAGS") ?>
					<span class="caret"></span>
					</a>
					
					<ul class="dropdown-menu">
					<?php if ($this->taglist && count($this->taglist) > 0): ?>
						<?php foreach ($this->taglist as $tag): ?>
							<li>
								<a href='#' onclick="tag_add('<?php echo FSS_Helper::escape($tag->tag); ?>');return false;"><?php echo $tag->tag; ?></a>
							</li>
						<?php endforeach; ?>
					<?php else: ?>
						<li><?php echo JText::_('NO_TAGS_DEFINED'); ?></li>
					<?php endif; ?>
				</ul>
	
			</div>

			<?php if (isset($this->tags)): ?>
				<?php foreach($this->tags as $tag): ?>
					<div class="fss_tag label label-info" id="tag_<?php echo FSS_Helper::escape($tag); ?>">
						<button class="close" onclick="tag_remove('<?php echo FSS_Helper::escape($tag); ?>');return false;">&times;</button>
						<?php echo $tag; ?>&nbsp;
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<span class="help-inline">
					<?php echo JText::_('NO_TAGS_SELECTED'); ?>
				</span>
			<?php endif; ?>			

			<input name="tags" id="tags" type="hidden" value="<?php echo FSS_Helper::escape(FSS_Input::getString('tags','')); ?>">
		</div>
	</div>

<?php endif; ?>

</div>
	
<script> 
function resetadvanced()
{
	<?php echo $resetadvanced; ?>
	jQuery('.select-color').each(function () {
        select_update_color(this);
    });
	jQuery('#tags').val('');
	jQuery('#fssForm').submit();	
}

function fss_submit_search()
{
	jQuery('#showbasic').remove();
	jQuery('#fss_what').val('search');
	jQuery('input[name=\"limitstart\"]').val(0);
}

jQuery(document).ready( function () {
	jQuery('table.advsearch input').keypress(function(e) {
		if(e.which == 13) {
			e.preventDefault();
			jQuery('#searchtype').val('advanced');
			fss_submit_search();
			jQuery('#fssForm').submit();
		}
	});
	
	var setResponsive = false;

	if (jQuery(window).width() < 766 && !setResponsive)
	{
		setResponsive = true;
		updateLayoutResponsive();
	}
		
	jQuery(window).on('resize', function(){
		if (setResponsive)
			return;
			
		if (jQuery(window).width() < 766)
		{
			setResponsive = true;
			updateLayoutResponsive();
		}
	});
});

function updateLayoutResponsive()
{
	jQuery('#basic_search').parent().removeClass('input-append');
	jQuery('.advsearch tr').each(function () {
		var newtr = jQuery('<tr />');
		jQuery(this).find('td:nth-child(3)').appendTo(newtr);
		jQuery(this).find('td:nth-child(3)').appendTo(newtr);
		//jQuery(jQuery(this).children[2]).appendTo(newtr);
		
		newtr.insertAfter(jQuery(this));
	});
}
</script>

<div id="users_search_url" style="display: none;"><?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&task=users.search", false); ?></div>
			 		  			   		