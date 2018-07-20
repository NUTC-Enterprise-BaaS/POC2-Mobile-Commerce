<div class="row-fluid">
	<div class="span6 general-settings">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_COMPANY_DETAILS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_NAME'); ?></strong><br />Enter the name of your business" id="company_name-lbl" for="company_name" class="hasTooltip required" title=""><?php echo JText::_('LNG_NAME'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="company_name" id="company_name" value="<?php echo $this->item->company_name?>" size="50" type="text"></div>
			</div>
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_EMAIL'); ?></strong><br />Enter your business email" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_EMAIL'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="company_email" id="company_email" value="<?php echo $this->item->company_email?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_FACEBOOK'); ?></strong><br />Enter your business facebook" id="facebook-lbl" for="facebook" class="hasTooltip required" title=""><?php echo JText::_('LNG_FACEBOOK'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="facebook" id="facebook" value="<?php echo $this->item->facebook?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_TWITTER'); ?></strong><br />Enter your twitter" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_TWITTER'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="twitter" id="twitter" value="<?php echo $this->item->twitter?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_GOOGLE_PLUS'); ?></strong><br />Enter your googlep" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_GOOGLE_PLUS'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="googlep" id="googlep" value="<?php echo $this->item->googlep?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_LINKEDIN'); ?></strong><br />Enter your linkedin" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_LINKEDIN'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="linkedin" id="linkedin" value="<?php echo $this->item->linkedin?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_YOUTUBE'); ?></strong><br />Enter your youtube" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_YOUTUBE'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls"><input name="youtube" id="youtube" value="<?php echo $this->item->youtube?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_LOGO'); ?></strong><br />Enter your youtube" id="company_email-lbl" for="company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_LOGO'); ?><span class="star">&nbsp;</span></label></div>
				<div class="controls">
					<div class="form-upload-elem">
						<div class="form-upload">
							<input type="hidden" name="logo" id="imageLocation" value="<?php echo $this->item->logo?>">
							<input type="file" id="imageUploader" name="uploadfile" size="50">
							<div class="clear"></div>
							<a href="javascript:removeLogo();"><?php echo JText::_("LNG_REMOVE")?></a>
						</div>

					</div>
					<div class="picture-preview-settings" id="picture-preview">
							<?php
								if(!empty($this->item->logo)) {
									echo "<img  id='logoImg' src='".JURI::root().PICTURES_PATH.$this->item->logo."'/>";
								}
							?>
						</div>
				</div>
			</div>
		</fieldset>
	</div>

    <div class="span6 general-settings">
        <fieldset class="form-horizontal">
            <legend><?php echo JText::_('LNG_INVOICE_INFORMATION'); ?></legend>

            <div class="control-group">
                <div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_NAME'); ?></strong><br />Enter the name of your business" id="invoice_company_name-lbl" for="invoice_company_name" class="hasTooltip required" title=""><?php echo JText::_('LNG_NAME'); ?><span class="star">&nbsp;</span></label></div>
                <div class="controls"><input name="invoice_company_name" id="invoice_company_name" value="<?php echo $this->item->invoice_company_name?>" size="50" type="text"></div>
            </div>

            <div class="control-group">
                <div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_ADDRESS'); ?></strong><br />Enter your business address" id="invoice_company_address-lbl" for="invoice_company_address" class="hasTooltip required" title=""><?php echo JText::_('LNG_ADDRESS'); ?><span class="star">&nbsp;</span></label></div>
                <div class="controls"><input name="invoice_company_address" id="invoice_company_address" value="<?php echo $this->item->invoice_company_address?>" size="50" type="text"></div>
            </div>

            <div class="control-group">
                <div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_TELEPHONE_NUMBER'); ?></strong><br />Enter your business phone number" id="invoice_company_phone-lbl" for="invoice_company_phone" class="hasTooltip required" title=""><?php echo JText::_('LNG_TELEPHONE_NUMBER'); ?><span class="star">&nbsp;</span></label></div>
                <div class="controls"><input name="invoice_company_phone" id="invoice_company_phone" value="<?php echo $this->item->invoice_company_phone?>" size="50" type="text"></div>
            </div>

            <div class="control-group">
                <div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_EMAIL'); ?></strong><br />Enter your business email" id="invoice_company_email-lbl" for="invoice_company_email" class="hasTooltip required" title=""><?php echo JText::_('LNG_EMAIL'); ?><span class="star">&nbsp;</span></label></div>
                <div class="controls"><input name="invoice_company_email" id="invoice_company_email" value="<?php echo $this->item->invoice_company_email?>" size="50" type="text"></div>
            </div>

            <div class="control-group">
                <div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_VAT_NUMBER'); ?></strong><br />Enter your business VAT number" id="invoice_vat-lbl" for="invoice_vat" class="hasTooltip required" title=""><?php echo JText::_('LNG_VAT_NUMBER'); ?><span class="star">&nbsp;</span></label></div>
                <div class="controls"><input name="invoice_vat" id="invoice_vat" value="<?php echo $this->item->invoice_vat?>" size="50" type="text"></div>
            </div>
		
			<div class="control-group">
				<div class="control-label"><label id="vat-lbl" for="vat" class="hasTooltip" title=""><?php echo JText::_('LNG_VAT'); ?></label></div>
				<div class="controls">
					<input type="text" size=40 maxlength=20  id="vat" name = "vat" value="<?php echo $this->item->vat?>">
				</div>
			</div>
            <div class="control-group">
                <div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_INVOICE_DETAILS'); ?></strong><br />Enter the invoice details" id="invoice_details-lbl" for="invoice_details" class="hasTooltip required" title=""><?php echo JText::_('LNG_INVOICE_DETAILS'); ?><span class="star">&nbsp;</span></label></div>
                <div class="controls"><textarea rows="5" cols="300" name="invoice_details" id="invoice_details" name="meta_keywords"><?php echo $this->item->invoice_details ?></textarea></div>
            </div>
        </fieldset>
    </div>
</div>

<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>
<script>
	var appImgFolder = '<?php echo APP_PICTURES_PATH ?>';
	var appImgFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_LOGO?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH) ?>&_target=<?php echo urlencode(APP_PICTURES_PATH)?>';
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_SITE)?>&_filename=';

	imageUploader(appImgFolder, appImgFolderPath);
</script>