<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
		<fieldset class="adminform">
			<legend><?php echo JText::_("TESTIMONIAL_SETTINGS"); ?></legend>
			<table class="table table-bordered table-condensed table-striped table-settings">
				<tr>
					<td align="left" class="key" style="width:250px;">
					
							<?php echo JText::_("TESTIMONIALS_ARE_MODERATED_BEFORE_DISPLAY"); ?>:
					
					</td>
					<td style="width:250px;">
						<select name="test_moderate">
							<option value="all" <?php if ($this->settings['test_moderate'] == "all") echo " SELECTED"; ?> ><?php echo JText::_('ALL_TESTIMONIALS_MODERATED'); ?></option>
							<option value="guests" <?php if ($this->settings['test_moderate'] == "guests") echo " SELECTED"; ?> ><?php echo JText::_('GUEST_TESTIMONIALS_MODERATED'); ?></option>
							<option value="registered" <?php if ($this->settings['test_moderate'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_AND_GUEST_TESTIMONIALS_MODERATED'); ?></option>
							<option value="none" <?php if ($this->settings['test_moderate'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('NO_TESTIMONIALS_ARE_MODERATED'); ?></option>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_moderate'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("ALLOW_NO_PRODUCT_TESTS"); ?>:
					</td>
					<td>
						<input type='checkbox' name='test_allow_no_product' value='1' <?php if ($this->settings['test_allow_no_product'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_allow_no_product'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
							<?php echo JText::_("HIDE_EMPTY_PROD_WHEN_LISTING"); ?>:
					</td>
					<td>
						<input type='checkbox' name='test_hide_empty_prod' value='1' <?php if ($this->settings['test_hide_empty_prod'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_hide_empty_prod'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("WHO_CAN_ADD_TESTIMONIALS"); ?>:
					</td>
					<td>
						<select name="test_who_can_add">
							<option value="anyone" <?php if ($this->settings['test_who_can_add'] == "anyone") echo " SELECTED"; ?> ><?php echo JText::_('ANYONE'); ?></option>
							<option value="registered" <?php if ($this->settings['test_who_can_add'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_USERS_ONLY'); ?></option>
							<option value="moderators" <?php if ($this->settings['test_who_can_add'] == "moderators") echo " SELECTED"; ?> ><?php echo JText::_('MODERATORS_ONLY'); ?></option>
						
							<!-- add access levels here too -->
							<?php 
								FSSAdminHelper::LoadAccessLevels(); 
								$options = FSSAdminHelper::$access_levels;		
							foreach ($options as $option): ?>
								<option value="<?php echo $option->value; ?>" <?php if ($this->settings['test_who_can_add'] == $option->value) echo " SELECTED"; ?>>ACL: <?php echo $option->text; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_who_can_add'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("EMAIL_ON_SUBMITTED"); ?>:
					
					</td>
					<td>
						<input name='test_email_on_submit' type="text" size="40" value='<?php echo $this->settings['test_email_on_submit']; ?>'>
						</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_email_on_submit'); ?></div>
				</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("TEST_USE_EMAIL"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='test_use_email' value='1' <?php if ($this->settings['test_use_email'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_use_email'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key">
						<?php echo JText::_("TEST_USE_WEBSITE"); ?>:
					
					</td>
					<td>
						<input type='checkbox' name='test_use_website' value='1' <?php if ($this->settings['test_use_website'] == 1) { echo " checked='yes' "; } ?>>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_use_website'); ?></div>
					</td>
				</tr>
				<tr>
					<td align="left" class="key" style="width:250px;">
						<?php echo JText::_("TEST_COMMENTS_PER_PAGE"); ?>:
					</td>
					<td>
						<?php $this->PerPage('test_comments_per_page'); ?>
					</td>
					<td>
						<div class='fss_help'><?php echo JText::_('SETHELP_test_comments_per_page'); ?></div>
					</td>
				</tr>
			</table>
		</fieldset>
