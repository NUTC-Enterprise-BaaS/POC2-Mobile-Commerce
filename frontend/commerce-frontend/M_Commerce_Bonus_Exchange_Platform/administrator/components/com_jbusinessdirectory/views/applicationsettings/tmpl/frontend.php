<div class="row-fluid">
	<div class="span6 general-settings">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_GENERAL'); ?></legend>

			<div class="control-group">
				<div class="control-label"><label id="add_country_address-lbl" for="add_country_address" class="hasTooltip" title=""><?php echo JText::_('LNG_ADD_COUNTRY_ADDRESS'); ?></label></div>
				<div class="controls">
					<fieldset id="add_country_address_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="add_country_address" id="add_country_address1" value="1" <?php echo $this->item->add_country_address==true? 'checked="checked"' :""?> />
						<label class="btn" for="add_country_address1"><?php echo JText::_('LNG_YES')?></label>
						<input type="radio" class="validate[required]" name="add_country_address" id="add_country_address0" value="0" <?php echo $this->item->add_country_address==false? 'checked="checked"' :""?> />
						<label class="btn" for="add_country_address0"><?php echo JText::_('LNG_NO')?></label>
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="address_format-lbl" for="address_format" class="hasTooltip" title=""><?php echo JText::_('LNG_ADDRESS_FORMAT'); ?></label></div>
				<div class="controls">
					<fieldset id="address_format_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="address_format" id="address_format4" value="4" <?php echo $this->item->address_format==4? 'checked="checked"' :""?> />
						<label class="btn" for="address_format4"><?php echo JText::_('LNG_EUROPEAN')." 2"?></label>
						<input type="radio" class="validate[required]" name="address_format" id="address_format3" value="3" <?php echo $this->item->address_format==3? 'checked="checked"' :""?> />
						<label class="btn" for="address_format3"><?php echo JText::_('LNG_AMERICAN')." 2"?></label><br/>
						<input type="radio" class="validate[required]" name="address_format" id="address_format2" value="2" <?php echo $this->item->address_format==2? 'checked="checked"' :""?> />
						<label class="btn" for="address_format2"><?php echo JText::_('LNG_EUROPEAN')?></label>
						<input type="radio" class="validate[required]" name="address_format" id="address_format1" value="1" <?php echo $this->item->address_format==1? 'checked="checked"' :""?> />
						<label class="btn" for="address_format1"><?php echo JText::_('LNG_AMERICAN')?></label>
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="adaptive_height_gallery-lbl" for="adaptive_height_gallery" class="hasTooltip" title=""><?php echo JText::_('LNG_GALLERY_ADAPTIVE_HEIGHT'); ?></label></div>
				<div class="controls">
					<fieldset id="adaptive_height_gallery_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="adaptive_height_gallery" id="adaptive_height_gallery1" value="1" <?php echo $this->item->adaptive_height_gallery==true? 'checked="checked"' :""?> />
						<label class="btn" for="adaptive_height_gallery1"><?php echo JText::_('LNG_YES')?></label>
						<input type="radio" class="validate[required]" name="adaptive_height_gallery" id="adaptive_height_gallery0" value="0" <?php echo $this->item->adaptive_height_gallery==false? 'checked="checked"' :""?> />
						<label class="btn" for="adaptive_height_gallery0"><?php echo JText::_('LNG_NO')?></label>
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="autoplay_gallery-lbl" for="autoplay_gallery" class="hasTooltip" title=""><?php echo JText::_('LNG_GALLERY_AUTOPLAY'); ?></label></div>
				<div class="controls">
					<fieldset id="autoplay_gallery_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="autoplay_gallery" id="autoplay_gallery1" value="1" <?php echo $this->item->autoplay_gallery==true? 'checked="checked"' :""?> />
						<label class="btn" for="autoplay_gallery1"><?php echo JText::_('LNG_YES')?></label>
						<input type="radio" class="validate[required]" name="autoplay_gallery" id="autoplay_gallery0" value="0" <?php echo $this->item->autoplay_gallery==false? 'checked="checked"' :""?> />
						<label class="btn" for="autoplay_gallery0"><?php echo JText::_('LNG_NO')?></label>
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
	<div class="span6 general-settings">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_MAP'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="google_map_key-lbl" for="google_map_key" class="hasTooltip" title=""><?php echo JText::_("LNG_GOOGLE_MAP_KEY"); ?></label></div>
				<div class="controls">
					<input type="text" id="google_map_key" name="google_map_key" value="<?php echo $this->item->google_map_key ?>">
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="map_auto_show-lbl" for="map_auto_show" class="hasTooltip" title=""><?php echo JText::_('LNG_MAP_AUTO_SHOW'); ?></label></div>
				<div class="controls">
					<fieldset id="map_auto_show_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="map_auto_show" id="map_auto_show1" value="1" <?php echo $this->item->map_auto_show==true? 'checked="checked"' :""?> />
						<label class="btn" for="map_auto_show1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="map_auto_show" id="map_auto_show0" value="0" <?php echo $this->item->map_auto_show==false? 'checked="checked"' :""?> />
						<label class="btn" for="map_auto_show0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="enable_google_map_clustering-lbl" for="enable_google_map_clustering" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_GOOGLE_MAP_CLUSTERING'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_google_map_clustering_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_google_map_clustering" id="enable_google_map_clustering1" value="1" <?php echo $this->item->enable_google_map_clustering==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_google_map_clustering1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_google_map_clustering" id="enable_google_map_clustering0" value="0" <?php echo $this->item->enable_google_map_clustering==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_google_map_clustering0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
					
			<div class="control-group">
				<div class="control-label"><label id="map_latitude-lbl" for="map_latitude" class="hasTooltip" title=""><?php echo JText::_("LNG_LATITUDE"); ?></label></div>
				<div class="controls">
					<input type="text" id="map_latitude" name="map_latitude" value="<?php echo $this->item->map_latitude ?>">
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="map_longitude-lbl" for="map_longitude" class="hasTooltip" title=""><?php echo JText::_("LNG_LONGITUDE"); ?></label></div>
				<div class="controls">
					<input type="text" id="map_longitude" name="map_longitude" value="<?php echo $this->item->map_longitude ?>">
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="map_zoom-lbl" for="map_zoom" class="hasTooltip" title=""><?php echo JText::_("LNG_ZOOM"); ?></label></div>
				<div class="controls">
					<input type="text" id="map_zoom" name="map_zoom" value="<?php echo $this->item->map_zoom ?>">
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="map_enable_auto_locate-lbl" for="map_enable_auto_locate" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_AUTO_LOCATE'); ?></label></div>
				<div class="controls">
					<fieldset id="map_enable_auto_locate_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="map_enable_auto_locate" id="map_enable_auto_locate1" value="1" <?php echo $this->item->map_enable_auto_locate==true? 'checked="checked"' :""?> />
						<label class="btn" for="map_enable_auto_locate1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="map_enable_auto_locate" id="map_enable_auto_locate0" value="0" <?php echo $this->item->map_enable_auto_locate==false? 'checked="checked"' :""?> />
						<label class="btn" for="map_enable_auto_locate0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="map_apply_search-lbl" for="map_apply_search" class="hasTooltip" title=""><?php echo JText::_('LNG_APPLY_SEARCH'); ?></label></div>
				<div class="controls">
					<fieldset id="map_apply_search_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="map_apply_search" id="map_apply_search1" value="1" <?php echo $this->item->map_apply_search==true? 'checked="checked"' :""?> />
						<label class="btn" for="map_apply_search1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="map_apply_search" id="map_apply_search0" value="0" <?php echo $this->item->map_apply_search==false? 'checked="checked"' :""?> />
						<label class="btn" for="map_apply_search0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_CATEGORIES'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="category_view-lbl" for="category_view" class="hasTooltip" title=""><?php echo JText::_('LNG_CATEGORIES_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="category_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="category_view" id="category_view1" value="1" <?php echo $this->item->category_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="category_view1"><?php echo JText::_('LNG_ACCORDION')?></label> 
						<input type="radio" class="validate[required]" name="category_view" id="category_view2" value="2" <?php echo $this->item->category_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="category_view2"><?php echo JText::_('LNG_BOXES')?></label> 
						<input type="radio" class="validate[required]" name="category_view" id="category_view3" value="3" <?php echo $this->item->category_view==3? 'checked="checked"' :""?> />
						<label class="btn" for="category_view3"><?php echo JText::_('LNG_SIMPLE')?></label>
						<input type="radio" class="validate[required]" name="category_view" id="category_view4" value="4" <?php echo $this->item->category_view==4? 'checked="checked"' :""?> />
						<label class="btn" for="category_view4"><?php echo JText::_('LNG_ICONS')?></label>
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="show_cat_description-lbl" for="show_cat_description" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_CAT_DESCRIPTION'); ?></label></div>
				<div class="controls">
					<fieldset id="show_cat_description_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_cat_description" id="show_cat_description1" value="1" <?php echo $this->item->show_cat_description==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_cat_description1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="show_cat_description" id="show_cat_description0" value="0" <?php echo $this->item->show_cat_description==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_cat_description0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="max_categories-lbl" for="max_categories" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_CATEGORIES'); ?></label></div>
				<div class="controls">
					<input type="text" size="40" maxlength="20"  id="max_categories" name="max_categories" value="<?php echo $this->item->max_categories?>">
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="show_total_business_count-lbl" for="show_total_business_count" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_TOTAL_BUSINESS_COUNT'); ?></label></div>
				<div class="controls">
					<fieldset id="show_total_business_count" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="show_total_business_count" id="show_total_business_count1" value="1" <?php echo $this->item->show_total_business_count==true? 'checked="checked"' :""?> />
						<label class="btn" for="show_total_business_count1"><?php echo JText::_('LNG_YES')?></label>
						<input type="radio" class="validate[required]" name="show_total_business_count" id="show_total_business_count0" value="0" <?php echo $this->item->show_total_business_count==false? 'checked="checked"' :""?> />
						<label class="btn" for="show_total_business_count0"><?php echo JText::_('LNG_NO')?></label>
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>


