<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_OFFERS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="enable_offers-lbl" for="enable_offers" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_OFFERS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_offers_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_offers" id="enable_offers1" value="1" <?php echo $this->item->enable_offers==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_offers1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_offers" id="enable_offers0" value="0" <?php echo $this->item->enable_offers==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_offers0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="max_offers-lbl" for="max_offers" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_OFFERS'); ?></label></div>
				<div class="controls">
					<input type="text" size="40" maxlength="20"  id="max_offers" name="max_offers" value="<?php echo $this->item->max_offers ?>">
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="enable_offer_coupons-lbl" for="enable_offer_coupons" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_OFFER_COUPONS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_offer_coupons_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_offer_coupons" id="enable_offer_coupons1" value="1" <?php echo $this->item->enable_offer_coupons==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_offer_coupons1"><?php echo JText::_('LNG_YES')?></label> 
						
						<input type="radio" class="validate[required]" name="enable_offer_coupons" id="enable_offer_coupons0" value="0" <?php echo $this->item->enable_offer_coupons==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_offer_coupons0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="enable_search_filter_offers-lbl" for="enable_search_filter_offers" class="hasTooltip" title=""><?php echo JText::_('LNG_enable_search_filter_offers'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_search_filter_offers_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_search_filter_offers" id="enable_search_filter_offers1" value="1" <?php echo $this->item->enable_search_filter_offers==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_offers1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_search_filter_offers" id="enable_search_filter_offers0" value="0" <?php echo $this->item->enable_search_filter_offers==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_offers0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="offer_search_results_grid_view-lbl" for="offer_search_results_grid_view" class="hasTooltip" title=""><?php echo JText::_('LNG_OFFER_SEARCH_RESULT_GRID_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="offer_search_results_grid_view_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="offer_search_results_grid_view" id="offer_search_results_grid_view1" value="1" <?php echo $this->item->offer_search_results_grid_view==true? 'checked="checked"' :""?> />
						<label class="btn" for="offer_search_results_grid_view1"><?php echo JText::_('LNG_STYLE_2')?></label> 
						<input type="radio" class="validate[required]" name="offer_search_results_grid_view" id="offer_search_results_grid_view0" value="0" <?php echo $this->item->offer_search_results_grid_view==false? 'checked="checked"' :""?> />
						<label class="btn" for="offer_search_results_grid_view0"><?php echo JText::_('LNG_STYLE_1')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="offers_view_mode-lbl" for="offers_view_mode" class="hasTooltip" title=""><?php echo JText::_('LNG_DEFAULT_OFFERS_VIEW'); ?></label></div>
				<div class="controls">
					<fieldset id="offers_view_mode_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="offers_view_mode" id="offers_view_mode1" value="1" <?php echo $this->item->offers_view_mode==true? 'checked="checked"' :""?> />
						<label class="btn" for="offers_view_mode1"><?php echo JText::_('LNG_GRID_MODE')?></label> 
						<input type="radio" class="validate[required]" name="offers_view_mode" id="offers_view_mode0" value="0" <?php echo $this->item->offers_view_mode==false? 'checked="checked"' :""?> />
						<label class="btn" for="offers_view_mode0"><?php echo JText::_('LNG_LIST_MODE')?></label> 
					</fieldset>
				</div>
			</div>
		</fieldset>
	</div>
</div>	

<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_SEARCH'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="order_search_offers-lbl" for="order_search_offers" class="hasTooltip" title=""><?php echo JText::_('LNG_order_search_offers'); ?></label></div>
				<div class="controls">
					<fieldset id="order_search_offers_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers1" value="" <?php echo $this->item->order_search_offers==""? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers1"><?php echo JText::_('LNG_RELEVANCE')?></label> 
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers2" value="co.subject" <?php echo $this->item->order_search_offers=="co.subject"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers2"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers3" value="co.city" <?php echo $this->item->order_search_offers=="co.city"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers3"><?php echo JText::_('LNG_CITY')?></label>
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers4" value="rand()" <?php echo $this->item->order_search_offers=="rand()"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers4"><?php echo JText::_('LNG_RANDOM')?></label>
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers5" value="co.id desc" <?php echo $this->item->order_search_offers=="co.id desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers5"><?php echo JText::_('LNG_LAST_ADDED')?></label><br/>
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers6" value="co.id asc" <?php echo $this->item->order_search_offers=="co.id asc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers6"><?php echo JText::_('LNG_FIRST_ADDED')?></label>
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers7" value="co.startDate asc" <?php echo $this->item->order_search_offers=="co.startDate asc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers7"><?php echo JText::_('LNG_EARLIEST_DATE')?></label>
						<input type="radio" class="validate[required]" name="order_search_offers" id="order_search_offers8" value="co.startDate desc" <?php echo $this->item->order_search_offers=="co.startDate desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_offers8"><?php echo JText::_('LNG_LATEST_DATE')?></label>
					</fieldset>
				</div>
			</div>
			
		</fieldset>
	</div>
</div>
