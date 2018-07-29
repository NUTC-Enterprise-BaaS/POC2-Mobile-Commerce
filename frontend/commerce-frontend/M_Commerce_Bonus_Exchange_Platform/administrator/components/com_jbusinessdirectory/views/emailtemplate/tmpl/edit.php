<?php
/**
 * @package    JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('formbehavior.chosen', 'select');
// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=emailtemplate');?>" method="post" name="adminForm" id="item-form">
	<div class="row-fluid">
		<div class="span12">
			<fieldset class="form-horizontal">
				<legend><?php echo JText::_('LNG_EMAIL_DETAILS'); ?></legend>
				<div class="control-group">
					<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_NAME'); ?></strong><br />Name of template" id="email_name-lbl" for="email_name" class="hasTooltip required" title=""><?php echo JText::_('LNG_NAME'); ?></label></div>
					<div class="controls"><input name="email_name" id="email_name" value="<?php echo $this->item->email_name?>" size="50" type="text"></div>
				</div>
				<div class="control-group">
					<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_TYPE'); ?></strong><br />Type of template" id="email_type-lbl" for="email_type" class="hasTooltip required" title=""><?php echo JText::_('LNG_TYPE'); ?></label></div>
					<div class="controls">
						<select id="email_type" name="email_type">
							<option <?php echo $this->item->email_type=='New Company Notification Email'? "selected" : ""?> value='New Company Notification Email'><?php echo JText::_('LNG_NEW_COMPANY_NOTIFICATION_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Listing Creation Notification'? "selected" : ""?> value='Listing Creation Notification'><?php echo JText::_('LNG_LISTING_CREATION_NOTIFICATION_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Approve Email'? "selected" : ""?> value='Approve Email'><?php echo JText::_('LNG_APPROVE_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Claim Response Email'? "selected" : ""?> value='Claim Response Email'><?php echo JText::_('LNG_CLAIM_RESPONSE_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Claim Negative Response Email'? "selected" : ""?> value='Claim Negative Response Email'><?php echo JText::_('LNG_CLAIM_NEGATIVE_RESPONSE_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Contact Email'? "selected" : ""?> value='Contact Email'><?php echo JText::_('LNG_CONTACT_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Request Quote Email'? "selected" : ""?> value='Request Quote Email'><?php echo JText::_('LNG_REQUEST_QUOTE'); ?></option>
							<option <?php echo $this->item->email_type=='Order Email'? "selected" : ""?> value='Order Email'><?php echo JText::_('LNG_ORDER_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Payment Details Email'? "selected" : ""?> value='Payment Details Email'><?php echo JText::_('LNG_PAYMENT_DETAILS_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Expiration Notification Email'? "selected" : ""?> value='Expiration Notification Email'><?php echo JText::_('LNG_EXPIRATION_NOTIFICATION_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Review Email'? "selected" : ""?> value='Review Email'><?php echo JText::_('LNG_REVIEW_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Review Response Email'? "selected" : ""?> value='Review Response Email'><?php echo JText::_('LNG_REVIEW_RESPONSE_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Report Abuse Email'? "selected" : ""?> value='Report Abuse Email'><?php echo JText::_('LNG_REPORT_ABUSE_EMAIL'); ?></option>
							<option <?php echo $this->item->email_type=='Offer Creation Notification'? "selected" : ""?> value='Offer Creation Notification'><?php echo JText::_('LNG_OFFER_CREATION_NOTIFICATION'); ?></option>
							<option <?php echo $this->item->email_type=='Offer Approval Notification'? "selected" : ""?> value='Offer Approval Notification'><?php echo JText::_('LNG_OFFER_APPROVAL_NOTIFICATION'); ?></option>
							<option <?php echo $this->item->email_type=='Event Creation Notification'? "selected" : ""?> value='Event Creation Notification'><?php echo JText::_('LNG_EVENT_CREATION_NOTIFICATION'); ?></option>
							<option <?php echo $this->item->email_type=='Event Approval Notification'? "selected" : ""?> value='Event Approval Notification'><?php echo JText::_('LNG_EVENT_APPROVAL_NOTIFICATION'); ?></option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label"><label id="send_to_admin-lbl" for="send_to_admin" class="hasTooltip" title=""><?php echo JText::_('LNG_SEND_TO_ADMIN'); ?></label></div>
					<div class="controls">
						<fieldset id="send_to_admin_fld" class="radio btn-group btn-group-yesno">
							<input type="radio" class="validate[required]" name="send_to_admin" id="send_to_admin1" value="1" <?php echo $this->item->send_to_admin==true? 'checked="checked"' :""?> />
							<label class="btn" for="send_to_admin1"><?php echo JText::_('LNG_YES')?></label> 
							<input type="radio" class="validate[required]" name="send_to_admin" id="send_to_admin0" value="0" <?php echo $this->item->send_to_admin==false? 'checked="checked"' :""?> />
							<label class="btn" for="send_to_admin0"><?php echo JText::_('LNG_NO')?></label> 
						</fieldset>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_SUBJECT'); ?></strong><br />Subject of template" id="email_subject-lbl" for="email_subject" class="hasTooltip required" title=""><?php echo JText::_('LNG_SUBJECT'); ?></label></div>
					<div class="controls">
						<?php 
						if($this->appSettings->enable_multilingual) {
							$options = array(
								'onActive' => 'function(title, description){
									description.setStyle("display", "block");
									title.addClass("open").removeClass("closed");
								}',
								'onBackground' => 'function(title, description){
									description.setStyle("display", "none");
									title.addClass("closed").removeClass("open");
								}',
								'startOffset' => 0,  // 0 starts on the first tab, 1 starts the second, etc...
								'useCookie' => true, // this must not be a string. Don't use quotes.
							);
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ) {
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
								if($lng==JFactory::getLanguage()->getTag() && empty($langContent)){
									$langContent = $this->item->email_subject;
								}
								echo "<input type='text' name='name_$lng' id='name_$lng' class='input_txt validate[required]' value=\"".stripslashes($langContent)."\"  maxLength='100'>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						} else { ?>
							<input name="email_subject" id="email_subject" value="<?php echo $this->item->email_subject?>" size="50" type="text">
						<?php } ?>
					</div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_CONTENT'); ?></strong><br />Subject of template" id="email_content-lbl" for="email_content" class="hasTooltip required" title=""><?php echo JText::_('LNG_CONTENT'); ?></label></div>
					<div class="controls">
					<?php 
						if($this->appSettings->enable_multilingual) {
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ) {
								echo JHtml::_('tabs.panel', $lng, 'tab'.$k );						
								$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
								if($lng==JFactory::getLanguage()->getTag() && empty($langContent)){
									$langContent = $this->item->email_content;
								}
								$editor = JFactory::getEditor();
								echo $editor->display('description_'.$lng, $langContent, '95%', '200', '70', '10', false);
							}
							echo JHtml::_('tabs.end');
						} else { 
							$editor = JFactory::getEditor();
							echo $editor->display('email_content', $this->item->email_content, '750', '400', '60', '20', false);
						} 
					?>
					</div>
				</div>
			</fieldset>
		</div>
	</div>

	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="email_id" value="<?php echo $this->item->email_id ?>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>
