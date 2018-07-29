<?php
/*
# mod_sp_quickcontact - Ajax based quick contact Module by JoomShaper.com
# -----------------------------------------------------------------------	
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="sp_quickcontact<?php echo $uniqid ?>" class="sp_quickcontact">
	<div id="sp_qc_status"></div>
	<form id="sp-quickcontact-form">
		<div class="sp_qc_clr"></div>
		<input type="text" name="name" id="name" onfocus="if (this.value=='<?php echo $name_text ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo $name_text ?>';" value="<?php echo $name_text ?>" required />
		<div class="sp_qc_clr"></div>
		<input type="email" name="email" id="email" onfocus="if (this.value=='<?php echo $email_text ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo $email_text ?>';" value="<?php echo $email_text ?>" required />
		<div class="sp_qc_clr"></div>
		<input type="text" name="subject" id="subject" onfocus="if (this.value=='<?php echo $subject_text ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo $subject_text ?>';" value="<?php echo $subject_text ?>" />
		<div class="sp_qc_clr"></div>
		<textarea name="message" id="message" onfocus="if (this.value=='<?php echo $msg_text ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo $msg_text ?>';" cols="" rows=""><?php echo $msg_text ?></textarea>	
		<div class="sp_qc_clr"></div>
		<?php if($formcaptcha) { ?>
			<input type="text" name="sccaptcha" placeholder="<?php echo $captcha_question ?>" required />
		<?php } ?>
		<div class="sp_qc_clr"></div>
		<input id="sp_qc_submit" class="btn btn-primary sp-contact" type="submit" value="<?php echo $send_msg ?>" />
		<div class="sp_qc_clr"></div>
	</form>
</div>