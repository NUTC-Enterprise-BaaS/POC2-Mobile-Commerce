<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_GENERAL_SETTINGS'); ?></legend>
			<div class="row-fluid">
				<div class="span6 general-settings">
					<div class="control-group">
						<div class="control-label"><label id="enable_packages-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_PACKAGES'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_packages" id="enable_packages1" value="1" <?php echo $this->item->enable_packages==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_packages1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_packages" id="enable_packages0" value="0" <?php echo $this->item->enable_packages==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_packages0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
							<div id="assign-packages" style="display:none">
								<span> <?php echo JText::_("LNG_UPDATE_COMPANIES_TO_PACKAGE") ?></span>
								<select name="package" class="inputbox input-medium">
									<option value="0"><?php echo JText::_("LNG_SELECT_PACKAGE") ?></option>
									<?php echo JHtml::_('select.options', $this->packageOptions, 'value', 'text',0);?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_ratings-lbl" for="enable_ratings" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_RATINGS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_ratings_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_ratings" id="enable_ratings1" value="1" <?php echo $this->item->enable_ratings==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_ratings1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_ratings" id="enable_ratings0" value="0" <?php echo $this->item->enable_ratings==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_ratings0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_reviews-lbl" for="enable_reviews" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_REVIEWS'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_reviews_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_reviews" id="enable_reviews1" value="1" <?php echo $this->item->enable_reviews==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_reviews" id="enable_reviews0" value="0" <?php echo $this->item->enable_reviews==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="enable_reviews_users-lbl" for="enable_reviews_users" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_REVIEWS_USERS_ONLY'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_reviews_users_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="enable_reviews_users" id="enable_reviews_users1" value="1" <?php echo $this->item->enable_reviews_users==true? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews_users1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="enable_reviews_users" id="enable_reviews_users0" value="0" <?php echo $this->item->enable_reviews_users==false? 'checked="checked"' :""?> />
								<label class="btn" for="enable_reviews_users0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="claim_business-lbl" for="claim_business" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_CLAIM_BUSINESS'); ?></label></div>
						<div class="controls">
							<fieldset id="claim_business_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="claim_business" id="claim_business1" value="1" <?php echo $this->item->claim_business==true? 'checked="checked"' :""?> />
								<label class="btn" for="claim_business1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="claim_business" id="claim_business0" value="0" <?php echo $this->item->claim_business==false? 'checked="checked"' :""?> />
								<label class="btn" for="claim_business0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label"><label id="show_pending_approval-lbl" for="show_pending_approval" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_PENDING_APPROVAL'); ?></label></div>
						<div class="controls">
							<fieldset id="show_pending_approval_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="show_pending_approval" id="show_pending_approval1" value="1" <?php echo $this->item->show_pending_approval==true? 'checked="checked"' :""?> />
								<label class="btn" for="show_pending_approval1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="show_pending_approval" id="show_pending_approval0" value="0" <?php echo $this->item->show_pending_approval==false? 'checked="checked"' :""?> />
								<label class="btn" for="show_pending_approval0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
				</div>
				<div class="span6 general-settings">
					<div class="control-group">
						<div class="control-label"><label id="limit_cities-lbl" for="limit_cities" class="hasTooltip" title=""><?php echo JText::_('LNG_LIMIT_CITIES'); ?></label></div>
						<div class="controls">
							<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="limit_cities" id="limit_cities1" value="1" <?php echo $this->item->limit_cities==true? 'checked="checked"' :""?> />
								<label class="btn" for="limit_cities1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="limit_cities" id="limit_cities0" value="0" <?php echo $this->item->limit_cities==false? 'checked="checked"' :""?> />
								<label class="btn" for="limit_cities0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="show_details_user-lbl" for="show_details_user" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_DETAILS_ONLY_FOR_USERS'); ?></label></div>
						<div class="controls">
							<fieldset id="show_details_user_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="show_details_user" id="show_details_user1" value="1" <?php echo $this->item->show_details_user==true? 'checked="checked"' :""?> />
								<label class="btn" for="show_details_user1"><?php echo JText::_('LNG_YES')?></label>
								<input type="radio" class="validate[required]" name="show_details_user" id="show_details_user0" value="0" <?php echo $this->item->show_details_user==false? 'checked="checked"' :""?> />
								<label class="btn" for="show_details_user0"><?php echo JText::_('LNG_NO')?></label>
							</fieldset>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="show_email-lbl" for="show_email" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_EMAIL'); ?></label></div>
						<div class="controls">
							<fieldset id="show_email_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="show_email" id="show_email1" value="1" <?php echo $this->item->show_email==true? 'checked="checked"' :""?> />
								<label class="btn" for="show_email1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="show_email" id="show_email0" value="0" <?php echo $this->item->show_email==false? 'checked="checked"' :""?> />
								<label class="btn" for="show_email0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
					
					<div class="control-group" style="display:none">
						<div class="control-label"><label data-original-title="<strong><?php echo JText::_('LNG_NR_IMAGES_SLIDE'); ?></strong><br />Enter the number of images per slide for business detail view slider" id="nr_images_slide-lbl" for="nr_images_slide" class="hasTooltip required" title=""><?php echo JText::_('LNG_NR_IMAGES_SLIDE'); ?></label></div>
						<div class="controls"><input name="nr_images_slide" id="nr_images_slide" value="<?php echo $this->item->nr_images_slide?>" size="50" type="text"></div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="max_pictures-lbl" for="max_pictures" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_PICTURES'); ?></label></div>
						<div class="controls">
							<input type="text" size=40 maxlength=20  id="max_pictures" name = "max_pictures" value="<?php echo $this->item->max_pictures?>">
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="max_video-lbl" for="max_video" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_VIDEOS'); ?></label></div>
						<div class="controls">
							<input type="text" size=40 maxlength=20  id="max_video" name = "max_video" value="<?php echo $this->item->max_video?>">
						</div>
					</div>

					<div class="control-group">
						<div class="control-label"><label id="max_business-lbl" for="max_business" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_BUSINESS_LISTINGS'); ?></label></div>
						<div class="controls">
							<input type="text" size=40 maxlength=20  id="max_business" name = "max_business" value="<?php echo $this->item->max_business?>">
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label"><label id="show_secondary_locations-lbl" for="show_secondary_locations" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_SECONDARY_LOCATIONS'); ?></label></div>
						<div class="controls">
							<fieldset id="show_secondary_locations_fld" class="radio btn-group btn-group-yesno">
								<input type="radio" class="validate[required]" name="show_secondary_locations" id="show_secondary_locations1" value="1" <?php echo $this->item->show_secondary_locations==true? 'checked="checked"' :""?> />
								<label class="btn" for="show_secondary_locations1"><?php echo JText::_('LNG_YES')?></label> 
								<input type="radio" class="validate[required]" name="show_secondary_locations" id="show_secondary_locations0" value="0" <?php echo $this->item->show_secondary_locations==false? 'checked="checked"' :""?> />
								<label class="btn" for="show_secondary_locations0"><?php echo JText::_('LNG_NO')?></label> 
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_SEARCH'); ?></legend>
				<div class="row-fluid">
					<div class="span6 general-settings">
						<div class="control-group">
							<div class="control-label"><label id="submit_method-lbl" for="submit_method" class="hasTooltip" title=""><?php echo JText::_('LNG_SUBMIT_METHOD'); ?></label></div>
							<div class="controls">
								<fieldset id="submit_method_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="submit_method" id="submit_method1" value="post" <?php echo $this->item->submit_method=="post"? 'checked="checked"' :""?> />
									<label class="btn" for="submit_method1"><?php echo JText::_('LNG_POST')?></label> 
									<input type="radio" class="validate[required]" name="submit_method" id="submit_method2" value="get" <?php echo $this->item->submit_method=="get"? 'checked="checked"' :""?> />
									<label class="btn" for="submit_method2"><?php echo JText::_('LNG_GET')?></label> 
								</fieldset>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><label id="enable_geolocation-lbl" for="enable_packages" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_GEOLOCATION'); ?></label></div>
							<div class="controls">
								<fieldset id="enable_geolocation_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="enable_geolocation" id="enable_geolocation1" value="1" <?php echo $this->item->enable_geolocation==true? 'checked="checked"' :""?> />
									<label class="btn" for="enable_geolocation1"><?php echo JText::_('LNG_YES')?></label> 
									<input type="radio" class="validate[required]" name="enable_geolocation" id="enable_geolocation0" value="0" <?php echo $this->item->enable_geolocation==false? 'checked="checked"' :""?> />
									<label class="btn" for="enable_geolocation0"><?php echo JText::_('LNG_NO')?></label> 
								</fieldset>
							</div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><label id="country_ids-lbl" for="country_ids[]" class="hasTooltip" title=""><?php echo JText::_('LNG_SELECT_ZIPCODE_COUNTRY'); ?></label></div>
							<div class="controls">
								<select	id="country_ids" name="country_ids[]" data-placeholder="<?php echo JText::_("LNG_SELECT_COUNTRY") ?>" class="chzn-color" multiple>
									<?php 
									foreach($this->item->countries as $country) {
										$selected = "";
										if (!empty($this->item->country_ids)) {
											if (in_array($country->id, $this->item->country_ids)) 
												$selected = "selected";
										} ?>
										<option value='<?php echo $country->id ?>' <?php echo $selected ?>> <?php echo $country->country_name ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><label id="search_view_mode-lbl" for="search_view_mode" class="hasTooltip" title=""><?php echo JText::_('LNG_DEFAULT_SEARCH_VIEW'); ?></label></div>
							<div class="controls">
								<fieldset id="search_view_mode_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="search_view_mode" id="search_view_mode1" value="1" <?php echo $this->item->search_view_mode==true? 'checked="checked"' :""?> />
									<label class="btn" for="search_view_mode1"><?php echo JText::_('LNG_GRID_MODE')?></label> 
									<input type="radio" class="validate[required]" name="search_view_mode" id="search_view_mode0" value="0" <?php echo $this->item->search_view_mode==false? 'checked="checked"' :""?> />
									<label class="btn" for="search_view_mode0"><?php echo JText::_('LNG_LIST_MODE')?></label> 
								</fieldset>
							</div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><label id="enable_numbering-lbl" for="enable_numbering" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_NUMBERING'); ?></label></div>
							<div class="controls">
								<fieldset id="enable_numbering_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="enable_numbering" id="enable_numbering1" value="1" <?php echo $this->item->enable_numbering==true? 'checked="checked"' :""?> />
									<label class="btn" for="enable_numbering1"><?php echo JText::_('LNG_YES')?></label> 
									<input type="radio" class="validate[required]" name="enable_numbering" id="enable_numbering0" value="0" <?php echo $this->item->enable_numbering==false? 'checked="checked"' :""?> />
									<label class="btn" for="enable_numbering0"><?php echo JText::_('LNG_NO')?></label> 
								</fieldset>
							</div>
						</div>
					</div>

					<div class="span6 general-settings">
						<div class="control-group">
							<div class="control-label"><label id="show_search_map-lbl" for="show_search_map" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_SEARCH_MAP'); ?></label></div>
							<div class="controls">
								<fieldset id="show_search_map_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="show_search_map" id="show_search_map1" value="1" <?php echo $this->item->show_search_map==true? 'checked="checked"' :""?> />
									<label class="btn" for="show_search_map1"><?php echo JText::_('LNG_YES')?></label> 
									<input type="radio" class="validate[required]" name="show_search_map" id="show_search_map0" value="0" <?php echo $this->item->show_search_map==false? 'checked="checked"' :""?> />
									<label class="btn" for="show_search_map0"><?php echo JText::_('LNG_NO')?></label> 
								</fieldset>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><label id="show_secondary_map_locations-lbl" for="show_secondary_map_locations" class="hasTooltip" title=""><?php echo JText::_('LNG_SHOW_SECONDARY_MAP_LOCATIONS'); ?></label></div>
							<div class="controls">
								<fieldset id="show_secondary_map_locations_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="show_secondary_map_locations" id="show_secondary_map_locations1" value="1" <?php echo $this->item->show_secondary_map_locations==true? 'checked="checked"' :""?> />
									<label class="btn" for="show_secondary_map_locations1"><?php echo JText::_('LNG_YES')?></label> 
									<input type="radio" class="validate[required]" name="show_secondary_map_locations" id="show_secondary_map_locations0" value="0" <?php echo $this->item->show_secondary_map_locations==false? 'checked="checked"' :""?> />
									<label class="btn" for="show_secondary_map_locations0"><?php echo JText::_('LNG_NO')?></label> 
								</fieldset>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><label id="enable_search_filter-lbl" for="enable_search_filter" class="hasTooltip" title=""><?php echo JText::_("LNG_ENABLE_SEARCH_FILTER"); ?></label></div>
							<div class="controls">
								<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="enable_search_filter" id="enable_search_filter1" value="1" <?php echo $this->item->enable_search_filter==true? 'checked="checked"' :""?> />
									<label class="btn" for="enable_search_filter1"><?php echo JText::_('LNG_YES')?></label> 
									<input type="radio" class="validate[required]" name="enable_search_filter" id="enable_search_filter0" value="0" <?php echo $this->item->enable_search_filter==false? 'checked="checked"' :""?> />
									<label class="btn" for="enable_search_filter0"><?php echo JText::_('LNG_NO')?></label> 
								</fieldset>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><label id="search_type-lbl" for="search_type" class="hasTooltip" title=""><?php echo JText::_("LNG_SEARCH_FILTER"); ?></label></div>
							<div class="controls">
								<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="search_type" id="search_type1" value="1" <?php echo $this->item->search_type==true? 'checked="checked"' :""?> />
									<label class="btn" for="search_type1"><?php echo JText::_('LNG_FACETED')?></label> 
									<input type="radio" class="validate[required]" name="search_type" id="search_type0" value="0" <?php echo $this->item->search_type==false? 'checked="checked"' :""?> />
									<label class="btn" for="search_type0"><?php echo JText::_('LNG_REGULAR')?></label> 
								</fieldset>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><label id="zipcode_search_type-lbl" for="zipcode_search_type" class="hasTooltip" title=""><?php echo JText::_('LNG_ZIPCODE_SEARCH_TYPE'); ?></label></div>
							<div class="controls">
								<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
									<input type="radio" class="validate[required]" name="zipcode_search_type" id="zipcode_search_type1" value="1" <?php echo $this->item->zipcode_search_type==true? 'checked="checked"' :""?> />
									<label class="btn" for="zipcode_search_type1"><?php echo JText::_('LNG_BY_BUSINESS_ACTIVITY_RADIUS')?></label> 
									<input type="radio" class="validate[required]" name="zipcode_search_type" id="zipcode_search_type0" value="0" <?php echo $this->item->zipcode_search_type==false? 'checked="checked"' :""?> />
									<label class="btn" for="zipcode_search_type0"><?php echo JText::_('LNG_BY_DISTANCE')?></label> 
								</fieldset>
							</div>
						</div>
					</div>
				</div>
				<div class="control-group">
				<div class="control-label"><label id="search_result_view-lbl" for="search_result_view" class="hasTooltip" title=""><?php echo JText::_('LNG_SEARCH_RESULT_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="search_result_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view1" value="1" <?php echo $this->item->search_result_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view1"><?php echo JText::_('LNG_STYLE_1')?></label> 
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view2" value="2" <?php echo $this->item->search_result_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view2"><?php echo JText::_('LNG_STYLE_2')?></label> 
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view3" value="3" <?php echo $this->item->search_result_view==3? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view3"><?php echo JText::_('LNG_STYLE_3')?></label>
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view4" value="4" <?php echo $this->item->search_result_view==4? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view4"><?php echo JText::_('LNG_STYLE_4')?></label>
						<input type="radio" class="validate[required]" name="search_result_view" id="search_result_view5" value="5" <?php echo $this->item->search_result_view==5? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_view5"><?php echo JText::_('LNG_STYLE_5')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="search_result_grid_view-lbl" for="search_result_grid_view" class="hasTooltip" title=""><?php echo JText::_('LNG_SEARCH_RESULTS_GRID_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="search_result_grid_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="search_result_grid_view" id="search_result_grid_view1" value="1" <?php echo $this->item->search_result_grid_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_grid_view1"><?php echo JText::_('LNG_STYLE_1')?></label> 
						<input type="radio" class="validate[required]" name="search_result_grid_view" id="search_result_grid_view2" value="2" <?php echo $this->item->search_result_grid_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="search_result_grid_view2"><?php echo JText::_('LNG_STYLE_2')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="order_search_listings-lbl" for="order_search_listings" class="hasTooltip" title=""><?php echo JText::_('LNG_ORDER_SEARCH_LISTINGS'); ?></label></div>
				<div class="controls">
					<fieldset id="order_search_listings_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings1" value="packageOrder desc" <?php echo $this->item->order_search_listings=="packageOrder desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings1"><?php echo JText::_('LNG_RELEVANCE')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings6" value="id desc" <?php echo $this->item->order_search_listings=="id desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings6"><?php echo JText::_('LNG_LAST_ADDED')?></label>
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings8" value="id asc" <?php echo $this->item->order_search_listings=="id asc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings8"><?php echo JText::_('LNG_FIRST_ADDED')?></label>
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings2" value="companyName" <?php echo $this->item->order_search_listings=="companyName"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings2"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings3" value="city asc" <?php echo $this->item->order_search_listings=="city asc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings3"><?php echo JText::_('LNG_CITY')?></label> <br/>
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings4" value="averageRating desc" <?php echo $this->item->order_search_listings=="averageRating desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings4"><?php echo JText::_('LNG_RATING')?></label>
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings7" value="review_score desc" <?php echo $this->item->order_search_listings=="review_score desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings7"><?php echo JText::_('LNG_REVIEW')?></label> 
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings5" value="rand()" <?php echo $this->item->order_search_listings=="rand()"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings5"><?php echo JText::_('LNG_RANDOM')?></label>
						<input type="radio" class="validate[required]" name="order_search_listings" id="order_search_listings9" value="typeName" <?php echo $this->item->order_search_listings=="typeName"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_listings9"><?php echo JText::_('LNG_TYPE')?></label>
					</fieldset>
				</div>
			</div>
			
			
		</fieldset>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_BUSINESS_LISTING_DETAILS'); ?></legend>
				<div class="control-group">
				<div class="control-label"><label id="company_view-lbl" for="company_view" class="hasTooltip" title=""><?php echo JText::_('LNG_COMPANY_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="company_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="company_view" id="company_view1" value="1" <?php echo $this->item->company_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="company_view1"><?php echo JText::_('LNG_TABS_STYLE_1')?></label> 
						<input type="radio" class="validate[required]" name="company_view" id="company_view2" value="2" <?php echo $this->item->company_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="company_view2"><?php echo JText::_('LNG_TABS_STYLE_2')?></label> 
						<input type="radio" class="validate[required]" name="company_view" id="company_view3" value="3" <?php echo $this->item->company_view==3? 'checked="checked"' :""?> />
						<label class="btn" for="company_view3"><?php echo JText::_('LNG_ONE_PAGE')?></label>
						<input type="radio" class="validate[required]" name="company_view" id="company_view4" value="4" <?php echo $this->item->company_view==4? 'checked="checked"' :""?> />
						<label class="btn" for="company_view4"><?php echo JText::_('LNG_STYLE_4')?></label>
						<input type="radio" class="validate[required]" name="company_view" id="company_view5" value="5" <?php echo $this->item->company_view==5? 'checked="checked"' :""?> />
						<label class="btn" for="company_view5"><?php echo JText::_('LNG_STYLE_5')?></label>
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>