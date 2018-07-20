<div class="row-fluid">
	<div class="span12">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('LNG_EVENTS'); ?></legend>
			<div class="control-group">
				<div class="control-label"><label id="enable_events-lbl" for="enable_events" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_EVENTS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_events_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_events" id="enable_events1" value="1" <?php echo $this->item->enable_events==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_events1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_events" id="enable_events0" value="0" <?php echo $this->item->enable_events==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_events0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><label id="max_events-lbl" for="max_events" class="hasTooltip" title=""><?php echo JText::_('LNG_MAX_EVENTS'); ?></label></div>
				<div class="controls">
					<input type="text" size="40" maxlength="20"  id="max_events" name="max_events" value="<?php echo $this->item->max_events ?>">
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="enable_search_filter_events-lbl" for="enable_search_filter_events" class="hasTooltip" title=""><?php echo JText::_('LNG_ENABLE_SEARCH_FILTER_EVENTS'); ?></label></div>
				<div class="controls">
					<fieldset id="enable_search_filter_events_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="enable_search_filter_events" id="enable_search_filter_events1" value="1" <?php echo $this->item->enable_search_filter_events==true? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_events1"><?php echo JText::_('LNG_YES')?></label> 
						<input type="radio" class="validate[required]" name="enable_search_filter_events" id="enable_search_filter_events0" value="0" <?php echo $this->item->enable_search_filter_events==false? 'checked="checked"' :""?> />
						<label class="btn" for="enable_search_filter_events0"><?php echo JText::_('LNG_NO')?></label> 
					</fieldset>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="events_search_view-lbl" for="events_search_view" class="hasTooltip" title=""><?php echo JText::_("LNG_DEFAULT_EVENTS_VIEW"); ?></label></div>
				<div class="controls">
					<fieldset id="enable_packages_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="events_search_view" id="events_search_view1" value="1" <?php echo $this->item->events_search_view==1? 'checked="checked"' :""?> />
						<label class="btn" for="events_search_view1"><?php echo JText::_('LNG_GRID')?></label> 
						<input type="radio" class="validate[required]" name="events_search_view" id="events_search_view0" value="2" <?php echo $this->item->events_search_view==2? 'checked="checked"' :""?> />
						<label class="btn" for="events_search_view0"><?php echo JText::_('LNG_LIST')?></label> 
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
				<div class="control-label"><label id="order_search_events-lbl" for="order_search_events" class="hasTooltip" title=""><?php echo JText::_('LNG_ORDER_SEARCH_EVENTS'); ?></label></div>
				<div class="controls">
					<fieldset id="order_search_events_fld" class="radio btn-group btn-group-yesno">
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events1" value="" <?php echo $this->item->order_search_events==""? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events1"><?php echo JText::_('LNG_RELEVANCE')?></label> 
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events2" value="name" <?php echo $this->item->order_search_events=="name"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events2"><?php echo JText::_('LNG_NAME')?></label> 
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events3" value="city" <?php echo $this->item->order_search_events=="city"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events3"><?php echo JText::_('LNG_CITY')?></label>
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events4" value="rand()" <?php echo $this->item->order_search_events=="rand()"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events4"><?php echo JText::_('LNG_RANDOM')?></label>
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events5" value="id desc" <?php echo $this->item->order_search_events=="id desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events5"><?php echo JText::_('LNG_LAST_ADDED')?></label><br/>
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events6" value="id asc" <?php echo $this->item->order_search_events=="id asc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events6"><?php echo JText::_('LNG_FIRST_ADDED')?></label>
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events7" value="start_date asc" <?php echo $this->item->order_search_events=="start_date asc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events7"><?php echo JText::_('LNG_EARLIEST_DATE')?></label>
						<input type="radio" class="validate[required]" name="order_search_events" id="order_search_events8" value="start_date desc" <?php echo $this->item->order_search_events=="start_date desc"? 'checked="checked"' :""?> />
						<label class="btn" for="order_search_events8"><?php echo JText::_('LNG_LATEST_DATE')?></label>
					</fieldset>
				</div>
			</div>
			
		</fieldset>
	</div>
</div>
			