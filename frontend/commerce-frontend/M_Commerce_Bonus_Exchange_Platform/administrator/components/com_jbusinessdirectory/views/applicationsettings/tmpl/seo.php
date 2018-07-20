<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_SEO_SETTINGS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="enable_seo-lbl" for="enable_seo" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_SEO'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_seo_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_seo" id="enable_seo1" value="1" <?php echo $this->item->enable_seo==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_seo1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_seo" id="enable_seo0" value="0" <?php echo $this->item->enable_seo==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_seo0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_MENU_ITEM_ID'); ?></strong><br />Enter menu item id that is associated with directory component" id="menu_item_id-lbl" for="menu_item_id" class="hasTooltip required" title=""><?php echo JText::_('LNG_MENU_ITEM_ID'); ?></label></div>
				<div class="controls"><input name="menu_item_id" id="menu_item_id" value="<?php echo $this->item->menu_item_id?>" size="50" type="text"></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="listing_url_type-lbl" for="listing_url_type_fld" class="hasTooltip" title=""><?php echo JText::_('LNG_URL_TYPE'); ?></label></div>
				<div class="controls">
					<fieldset id="listing_url_type_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="listing_url_type" id="listing_url_type1" value="1" <?php echo $this->item->listing_url_type==1? 'checked="checked"' :""?> />
						<label class="btn" for="listing_url_type1"><?php echo JText::_('LNG_SIMPLE')?></label> 
						<input type="radio" class="validate[required]" name="listing_url_type" id="listing_url_type2" value="2" <?php echo $this->item->listing_url_type==2? 'checked="checked"' :""?> />
						<label class="btn" for="listing_url_type2"><?php echo JText::_('LNG_CATEGORY')?></label> 
						<input type="radio" class="validate[required]" name="listing_url_type" id="listing_url_type3" value="3" <?php echo $this->item->listing_url_type==3? 'checked="checked"' :""?> />
						<label class="btn" for="listing_url_type3"><?php echo JText::_('LNG_REGION')?></label> 
					</fieldset>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><label id="category_url_type-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_CATEGORY_URL_TYPE'); ?></label></div>
				<div class="controls">
					<fieldset id="category_url_type_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="category_url_type" id="category_url_type1" value="1" <?php echo $this->item->category_url_type==1? 'checked="checked"' :""?> />
						<label class="btn" for="category_url_type1"><?php echo JText::_('LNG_KEYWORD')?></label>
						<input type="radio" class="validate[required]" name="category_url_type" id="category_url_type2" value="2" <?php echo $this->item->category_url_type==2? 'checked="checked"' :""?> />
						<label class="btn" for="category_url_type2"><?php echo JText::_('LNG_SIMPLE')?></label>
					</fieldset>
				</div>
			</div>

			<div class="control-group">
				<div class="control-label"><label id="enable_menu_alias_url-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_ADD_MENU_ALIAS_URL'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_menu_alias_url_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_menu_alias_url" id="enable_menu_alias_url1" value="1" <?php echo $this->item->enable_menu_alias_url==1? 'checked="checked"' :""?> />
						<label class="btn" for="enable_menu_alias_url1"><?php echo JText::_('LNG_YES')?></label>
						<input type="radio" class="validate[required]" name="enable_menu_alias_url" id="enable_menu_alias_url0" value="0" <?php echo $this->item->enable_menu_alias_url==0? 'checked="checked"' :""?> />
						<label class="btn" for="enable_menu_alias_url0"><?php echo JText::_('LNG_NO')?></label>
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="add_url_id-lbl" for="add_url_id" class="hasTooltip" title=""><?php echo JText::_('LNG_ADD_URL_ID'); ?></label></div>
				<div class="controls">
					<fieldset id="add_url_id_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="add_url_id" id="add_url_id1" value="1" <?php echo $this->item->add_url_id==true? 'checked="checked"' :""?> />
						<label class="btn" for="add_url_id1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="add_url_id" id="add_url_id0" value="0" <?php echo $this->item->add_url_id==false? 'checked="checked"' :""?> />
						<label class="btn" for="add_url_id0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="add_url_language-lbl" for="add_url_language" class="hasTooltip" title=""><?php echo JText::_('LNG_ADD_URL_LANGUAGE'); ?></label></div>
				<div class="controls">
					<fieldset id="add_url_language_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="add_url_language" id="add_url_language1" value="1" <?php echo $this->item->add_url_language==true? 'checked="checked"' :""?> />
						<label class="btn" for="add_url_language1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="add_url_language" id="add_url_language0" value="0" <?php echo $this->item->add_url_language==false? 'checked="checked"' :""?> />
						<label class="btn" for="add_url_language0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>