<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if ($this->can_add): ?>

<div id="recaptcha_public_key" style="display:none;"><?php echo FSS_Settings::get('recaptcha_public'); ?></div>

	<div class='fss_kb_comment_add' id='add_comment'>
		<?php if ($this->comments_hide_add): ?>
			<p>
				<a id="commentaddbutton" href='#' onclick='return false;' class='btn btn-default'><?php echo $this->add_a_comment; ?></a>
			</p>
			
			<div id="commentadd" style="display:none;">
	
			<script>
				jQuery(document).ready(function () {
					jQuery('#commentaddbutton').click( function (ev) {
						ev.preventDefault();
						jQuery('#commentadd').css('display','block');
						jQuery('#commentaddbutton').css('display','none');
					});
				});
			</script>
		<?php endif; ?>

		<?php echo FSS_Helper::PageSubTitle2($this->add_a_comment); ?>

		<form id='addcommentform' action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=comments&tmpl=component' );?>" method="post" class="form-horizontal form-condensed">
			<input type='hidden' name='comment' value='add' >
			<input type='hidden' name='uid' value='<?php echo (int)$this->uid; ?>' >
			<input type='hidden' name='task' value='commentpost' >
			<input type='hidden' name='ident' value='<?php echo (int)$this->ident ?>' >
			<input type='hidden' name='identifier' value='<?php echo $this->identifier ?>' >
			<input type='hidden' name='opt_show_posted_message_only' value='<?php echo $this->opt_show_posted_message_only ?>' >
			<?php if ($this->itemid): ?>
				<input type='hidden' name='itemid' value='<?php echo (int)$this->itemid ?>' >
			<?php endif; ?>
			
			<?php if ($this->show_item_select) : ?>
				<div class="control-group <?php echo $this->errors['itemid'] ? 'error' : ''; ?>">
					<label class="control-label"><?php echo $this->handler->email_article_type; ?></label>
					<div class="controls">
						<?php echo $this->GetItemSelect(); ?>
						<span class="help-inline"><?php echo $this->errors['itemid']; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="control-group <?php echo $this->errors['name'] ? 'error' : ''; ?>">
				<label class="control-label"><?php echo JText::_('Name'); ?></label>
				<div class="controls">
					<?php if (!FSS_Permission::CanModerate() && $this->loggedin) : ?>
						<input name='name' type='hidden' id='comment_name' value='<?php echo FSS_Helper::escape($this->post['name']) ?>' placeholder="<?php echo JText::_('Name'); ?>" required>
						<?php echo $this->post['name']; ?>
					<?php else: ?>
						<input type="text" name='name' id='comment_name' value='<?php echo FSS_Helper::escape($this->post['name']) ?>'  placeholder="<?php echo JText::_('Name'); ?>" required>
					<?php endif; ?>
					<span class="help-inline"><?php echo $this->errors['name']; ?></span>
				</div>
			</div>
			
			<?php if ($this->use_email && !($this->loggedin)): ?>
				<div class="control-group <?php echo $this->errors['email'] ? 'error' : ''; ?>">
					<label class="control-label"><?php echo JText::_('EMail'); ?></label>
					<div class="controls">
						<input type="text" name='email' value='<?php echo FSS_Helper::escape($this->post['email']) ?>' placeholder="<?php echo JText::_('EMail'); ?>">
						<span class="help-inline"><?php echo $this->errors['email']; ?></span>
					</div>
				</div>
			<?php endif; ?>
			
			<?php if ($this->use_website): ?>
				<div class="control-group <?php echo $this->errors['website'] ? 'error' : ''; ?>">
					<label class="control-label"><?php echo JText::_('website'); ?></label>
					<div class="controls">
						<input type="text" name='website' value='<?php echo FSS_Helper::escape($this->post['website']) ?>' placeholder="<?php echo JText::_('website'); ?>">
						<span class="help-inline"><?php echo $this->errors['website']; ?></span>
					</div>
				</div>
			<?php endif; ?>

			<?php foreach ($this->customfields as $custfield): ?>
				<div class="control-group <?php if (FSSCF::HasErrors($custfield, $this->errors)) echo "error"; ?>">
					<label class="control-label"><?php echo FSSCF::FieldHeader($custfield,true, false); ?></label>
					<div class="controls">
						<?php echo FSSCF::FieldInput($custfield,$this->errors,'comments'); ?>
					</div>
				</div>
			<?php endforeach; ?>
			<script>
			<?php foreach ($this->customfields as $custfield): ?>
				<?php if ($custfield['javascript']) echo $custfield['javascript']; ?>
			<?php endforeach; ?>	
			</script>
			<div class="control-group <?php echo $this->errors['body'] ? 'error' : ''; ?>">
				<label class="control-label"><?php echo JText::_('COMMENT_BODY'); ?></label>
				<div class="controls">
					<textarea name='body' rows='5' cols='60' id='comment_body' style="height:150px;width:400px" required><?php echo FSS_Helper::escape($this->post['body']) ?></textarea>
					<span class="help-inline"><?php echo $this->errors['body']; ?></span>
				</div>
			</div>
		
			<?php if ($this->captcha) : ?>
				<div class="control-group <?php echo $this->errors['captcha'] ? 'error' : ''; ?>">
					<label class="control-label"><?php echo JText::_('Verification'); ?></label>
					<div class="controls">
						<span id="captcha_cont"><?php echo $this->captcha ?></span>
						<?php if ($this->errors['captcha']): ?>
							<span class="help-inline"><?php echo $this->errors['captcha']; ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>	
				
			<div class="control-group">
				<label class="control-label"></label>
				<div class="controls">
					<input type=submit value=' <?php echo $this->post_comment ?> ' id='addcomment' class='btn btn-primary'>
				</div>
			</div>				
		</form>
		<?php if ($this->comments_hide_add): ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>