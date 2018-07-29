<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$ticket_user_id = JFactory::getUser()->id;
?>

<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>

<form id='newticket' action="<?php echo JRoute::_("index.php?option=com_fss&view=quickopen&Itemid=" . FSS_Input::getInt('Itemid')); ?>" method="post"  enctype="multipart/form-data">
	<input type='hidden' name='what' id='what' value='add'>

	<?php 
	/*$grouping = "";
	$open = false;

	foreach ($this->fields as $field)
	{
		if ($field['grouping'] == "")
			continue;
	
	
		// if permission is user see only, or only admin, dont output the field
		if ($field['permissions'] == 1 || $field['permissions'] == 2)
		{
			continue;
		}
		
		if ($field['grouping'] != $grouping)
		{
			if ($open)
			{
			?>
				</div>
				<?php
			}
			echo FSS_Helper::PageSubTitle($field['grouping']);
			?>
			<div class="form-horizontal form-condensed">
			<?php	
			$open = true;	
			$grouping = $field['grouping'];
		}
	
		?>
			<div class="control-group <?php if (FSSCF::HasErrors($field, $this->errors)) echo "error"; ?>">
							<label class="control-label"><?php echo FSSCF::FieldHeader($field,true, false); ?></label>
			<div class="controls">
								<?php echo FSSCF::FieldInput($field,$this->errors,'ticket',array('ticketid' => 0, 'userid' => $ticket_user_id), true); ?>
			</div>
			</div>
				<?php
			}

			if ($open)
			{
			?>
		</div>
		<?php
	}*/

	?>

	<div class="form-horizontal form-condensed">
		
		<div class="control-group <?php echo $this->errors['subject'] ? 'error' : ''; ?>">
			<label class="control-label"><?php echo JText::_("SUBJECT"); ?></label>
			<div class="controls">
				<input type="text" class="input-xlarge" name='subject' id='subject' size='<?php echo FSS_Settings::get('support_subject_size'); ?>' value="<?php echo FSS_Helper::escape($this->ticket->subject) ?>" required>
				<span class="help-inline"><?php echo $this->errors['subject'] ? $this->errors['subject'] : FSS_Helper::HelpText("support_open_main_field_subject"); ?></span>
			</div>
		</div>

		<?php

		/*foreach ($this->fields as $field)
		{
			if ($field['grouping'] != "")
				continue;
	
			// not an admin created ticket
			// if permission is user see only, or only admin, dont output the field
			if ($this->admin_create == 0 && ($field['permissions'] == 1 || $field['permissions'] == 2)) 
				continue;
		?>
					<div class="control-group <?php if (FSSCF::HasErrors($field, $this->errors)) echo "error"; ?>">
						<label class="control-label"><?php echo FSSCF::FieldHeader($field,true, false); ?></label>
						<div class="controls">
							<?php echo FSSCF::FieldInput($field,$this->errors,'ticket',array('ticketid' => 0, 'userid' => $ticket_user_id), true); ?>
						</div>
					</div>
			<?php
		}*/
		?>	
	
		<?php if (count($this->cats) > 0 && !FSS_Settings::get('support_hide_category')): ?>	
			<div class="control-group <?php echo $this->errors['cat'] ? 'error' : ''; ?>">
				<label class="control-label"><?php echo JText::_("CATEGORY"); ?></label>
					<div class="controls">

					<select id='catid' class='input-large' name='catid' required='required'>
						<option value=""><?php echo JText::_("SELECT_CATEGORY"); ?></option>
						<?php $sect = ""; $open = false; ?>
						<?php FSS_Translate_Helper::Tr($this->cats); ?>
						<?php foreach ($this->cats as $cat): ?>
							<?php 
								if ($cat->section != $sect && $cat->section != "") {
									if ($open)
										echo "</optgroup>";
									$open = true;
									echo "<optgroup label='" . $cat->section . "'>";
									$sect = $cat->section;	
								}								
							?>
							<option value='<?php echo $cat->id; ?>' <?php if ($cat->id == $this->ticket->catid) echo "selected='selected'"; ?>><?php echo $cat->title; ?></option>
						<?php endforeach; ?>
						<?php if ($open) echo "</optgroup>"; ?>
					</select>
					<span class="help-inline"><?php echo $this->errors['cat'] ? $this->errors['cat'] : FSS_Helper::HelpText("support_open_main_field_category"); ?></span>
				</div>
			</div>
		<?php endif; ?>


		<?php if (!FSS_Settings::get('support_hide_priority')) : ?>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_("PRIORITY"); ?></label>
				<div class="controls">
					<select class='input-large' id='priid' name='priid'>
						<?php FSS_Translate_Helper::Tr($this->pris); ?>
						<?php foreach ($this->pris as $pri): ?>
							<option value='<?php echo $pri->id; ?>'
								style='color: <?php echo $pri->color; ?>'
								<?php if ($pri->id == $this->ticket->priid) echo "selected='selected'"; ?>>
							<?php echo $pri->title; ?>
							</option>
						<?php endforeach; ?>
					</select>
					<span class="help-inline"><?php FSS_Helper::HelpText("support_open_main_field_priority"); ?></span>
				</div>
			</div>
		<?php endif; ?>	

		<input type='hidden' name="handler" id="handler" value="0" />
		
		<?php if (FSS_Settings::get('support_subject_message_hide') != "message"): ?>
			<?php if ($this->errors['body']): ?>
				<div class="control-group error">
					<span class='help-inline' id='error_subject'><?php echo $this->errors['body']; ?></span>
				</div>
			<?php endif; ?>
			<?php FSS_Helper::HelpText("support_open_main_message_before"); ?>
			<textarea name='body' id='body' class='sceditor' rows='<?php echo (int)FSS_Settings::get('support_user_reply_height'); ?>' cols='<?php echo (int)FSS_Settings::get('support_user_reply_width'); ?>' style='width:95%;height:<?php echo (int)((FSS_Settings::get('support_user_reply_height') * 15) + 80); ?>px'><?php echo FSS_Helper::escape($this->ticket->body) ?></textarea>
			<?php FSS_Helper::HelpText("support_open_main_message_after"); ?>
		<?php endif; ?>
	</div>

	<?php if (FSS_Settings::get('support_user_attach')): ?>
		<?php echo FSS_Helper::PageSubTitle(JText::sprintf("UPLOAD_FILE",FSS_Helper::display_filesize(FSS_Helper::getMaximumFileUploadSize())),false); ?>
		<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'tmpl'.DS.'attach.php'; ?>
	<?php endif; ?>

	<p>
		<input class='btn btn-primary' type='submit' value='<?php echo JText::_("CREATE_NEW_TICKET"); ?>' id='addcomment'>
	</p>

</form>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>