<?php 
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$config = new JConfig();

//retrieving current menu item parameters
$currentMenuId = null;
$activeMenu = JFactory::getApplication()->getMenu()->getActive();
$menuItemId="";
if(isset($activeMenu)){
	$currentMenuId = $activeMenu->id ; // `enter code here`
	$menuItemId="&Itemid=".$currentMenuId;
}
$document = JFactory::getDocument(); // `enter code here`
$app = JFactory::getApplication(); // `enter code here`
if(isset($activeMenu)) {
	$menuitem   = $app->getMenu()->getItem($currentMenuId); // or get item by ID `enter code here`
	$params = $menuitem->params; // get the params `enter code here`
} else {
	$params = null;
}

//set page title
if(!empty($params) && $params->get('page_title') != '') {
	$title = $params->get('page_title', '');
}
if(empty($title)) {
	$title = JText::_("LNG_BUSINESS_LISTINGS");
	
	if(!empty($this->category->name) || !empty($this->citySearch) || !empty($this->regionSearch) || !empty($this->countrySearch)){
		$title .= " in ";
	}
	
	$items = array();
	if(!empty($this->category->name))
		$items[] = $this->category->name;
	if(!empty($this->citySearch))
		$items[] = $this->citySearch;
	if(!empty($this->regionSearch))
		$items[] = $this->regionSearch;
	if(!empty($this->countrySearch))
		$items[]= $this->country->country_name;

	if(!empty($items)){
		$title .= implode("|",$items);
	}
}
$document->setTitle($title);

//set page meta description and keywords
$description = $this->appSettings->meta_description;
$document->setDescription($description);
$document->setMetaData('keywords', $this->appSettings->meta_keywords);

if(!empty($params) && $params->get('menu-meta_description') != '') {
	$document->setMetaData( 'description', $params->get('menu-meta_description') );
	$document->setMetaData( 'keywords', $params->get('menu-meta_keywords') );
}

$user = JFactory::getUser();
$enableSearchFilter = $this->appSettings->enable_search_filter;
$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

//add the possibility to chage the view and layout from http params
$list_layout = JRequest::getVar('list_layout');
if(!empty($list_layout)) {
	$this->appSettings->search_result_view = $list_layout;
}
$view_mode = JRequest::getVar('view_mode');
if(!empty($view_mode)) {
	$this->appSettings->search_view_mode = $view_mode;
}

$showClear = 0;
?>

<?php if (!empty($this->params) && $this->params->get('show_page_heading', 1)) { ?>
    <div class="page-header">
        <h1 class="title"> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php } ?>

<div id="search-path">
	<ul>
		<?php if(isset($this->category)) { ?>
			<li>
				<a class="search-filter-elem" href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=search') ?>"><?php echo JText::_('LNG_ALL_CATEGORIES') ?></a>
			</li>
		<?php } ?>
		<?php 
		if(isset($this->searchFilter["path"])) {
			foreach($this->searchFilter["path"] as $path) {
				if($path[0]==1)
					continue;
			?>
				<li>
					<a  class="search-filter-elem" href="<?php echo JBusinessUtil::getCategoryLink($path[0], $path[2]) ?>"><?php echo $path[1]?></a>
				</li>
			<?php } ?>
		<?php } ?>
		<li>
			<?php if(!empty($this->category)) echo $this->category->name ?>
		</li>
		<?php if(!empty($this->selectedParams["type"]) && !empty($this->searchFilter["types"])) {?>
			<span class="filter-type-elem"><?php echo $this->searchFilter["types"][$this->selectedParams["type"][0]]->typeName; ?><a class="remove" onclick="removeFilterRule('type', <?php echo $this->selectedParams["type"][0] ?>)">x</a></span>
		<?php $showClear++; } ?>
		<?php if(!empty($this->selectedParams["country"]) && !empty( $this->searchFilter["countries"])) {?>
			<span class="filter-type-elem"><?php echo $this->searchFilter["countries"][$this->selectedParams["country"][0]]->countryName; ?><a class="remove" onclick="removeFilterRule('country', <?php echo $this->selectedParams["country"][0] ?>)">x</a></span>
		<?php $showClear++; } ?>
		<?php if(!empty($this->selectedParams["region"]) && !empty( $this->searchFilter["regions"])) {?>
			<span class="filter-type-elem"><?php echo $this->searchFilter["regions"][$this->selectedParams["region"][0]]->regionName; ?><a class="remove" onclick="removeFilterRule('region', '<?php echo $this->selectedParams["region"][0] ?>')">x</a></span>
		<?php $showClear++; } ?>
		<?php if(!empty($this->selectedParams["city"]) && !empty($this->searchFilter["cities"])) {?>
			<span class="filter-type-elem"><?php echo $this->searchFilter["cities"][$this->selectedParams["city"][0]]->cityName; ?><a class="remove" onclick="removeFilterRule('city', '<?php echo $this->selectedParams["city"][0] ?>')">x</a></span>
		<?php $showClear++; } ?>
		<?php if($showClear > 1) { ?>
			<span class="filter-type-elem"><a href="javascript:resetFilters(false)" style="text-decoration: none;"><?php echo JText::_('LNG_CLEAR_ALL'); ?></a></span>
		<?php } ?>
	</ul>
</div>

<div class="clear"></div>
<div class="row-fluid">
<?php if($enableSearchFilter) { ?>
<div id="search-filter" class="search-filter moduletable span3">
	<h3 style="float:left;"><?php echo JText::_("LNG_SEARCH_FILTER"); ?></h3>
	<a href="javascript:resetFilters(true)" style="float:right;padding:5px;"><?php echo JText::_('LNG_RESET_FILTER'); ?></a>
	<div class="search-category-box">
	 <?php if(!empty($this->location["latitude"])) { ?>
		<h4><?php echo JText::_("LNG_DISTANCE"); ?></h4>
		<ul>
			<li>
				<?php if($this->radius != 50) { ?>
					<a href="javascript:setRadius(50)">50 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
				<?php } else { ?>
					<strong>50 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
				<?php } ?>
			</li>
			<li>
				<?php if($this->radius != 25) { ?>
					<a href="javascript:setRadius(25)">25 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
				<?php } else { ?>
					<strong>25 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
				<?php } ?>
			</li>
			<li>
				<?php if($this->radius != 10) { ?>
					<a href="javascript:setRadius(10)">10 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></a>
				<?php } else { ?>
					<strong>10 <?php echo $this->appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?></strong>
				<?php } ?>
			</li>
			<li>
				<?php if($this->radius != 0) { ?>
					<a href="javascript:setRadius(0)"><?php echo JText::_("LNG_ALL")?></a>
				<?php } else { ?>
					<strong><?php echo JText::_("LNG_ALL")?></strong>
				<?php } ?>
			</li>
		</ul>
	<?php } ?>

	<div id="filterCategoryItems">
		<h4><?php echo JText::_("LNG_CATEGORIES") ?></h4>
		<?php if($this->appSettings->search_type==0){ ?>
			<ul>
				<?php 
				if(isset($this->searchFilter["categories"])) {
					foreach($this->searchFilter["categories"] as $filterCriteria) { 
						if($filterCriteria[1]>0) { ?>
							<li>
								<?php if(isset($this->category) && $filterCriteria[0][0]->id == $this->category->id) {  ?>
									<strong><?php echo $filterCriteria[0][0]->name; ?>&nbsp;</strong><?php //echo '('.$filterCriteria[1].')' ?>
								<?php } else { ?>
									<a href="javascript:chooseCategory(<?php echo $filterCriteria[0][0]->id?>)"><?php echo $filterCriteria[0][0]->name; ?></a>
									<?php //echo '('.$filterCriteria[1].')' ?>
								<?php } ?>
							</li>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</ul>
		<?php } else { ?>
			<ul>
				<?php 
				if(isset($this->searchFilter["categories"])) {
					foreach($this->searchFilter["categories"] as $filterCriteria) { 
						if($filterCriteria[1]>0) { ?>
							<li <?php if(in_array($filterCriteria[0][0]->id,$this->selectedCategories)) echo 'class="selectedlink"'; ?>>
								<div <?php if(in_array($filterCriteria[0][0]->id,$this->selectedCategories)) echo 'class="selected"'; ?>>
									<a href="javascript:void(0)" onclick="<?php echo in_array($filterCriteria[0][0]->id,$this->selectedCategories)?"removeFilterRuleCategory(".$filterCriteria[0][0]->id.")":"addFilterRuleCategory(".$filterCriteria[0][0]->id.")";?>"> <?php echo $filterCriteria[0][0]->name ?>  <?php echo in_array($filterCriteria[0][0]->id,$this->selectedCategories) ? '<span class="cross">(remove)</span>':"";  ?></a>
									<?php //echo '('.$filterCriteria[1].')' ?>
								</div>
								<?php if(isset($filterCriteria[0]["subCategories"])) { ?>
									<ul>
										<?php foreach($filterCriteria[0]["subCategories"] as $subcategory) { ?>
											<li <?php if(in_array($subcategory[0]->id,$this->selectedCategories)) echo 'class="selectedlink"'; ?>>
												<div <?php if(in_array($subcategory[0]->id,$this->selectedCategories)) echo 'class="selected"'; ?>>
													<a href="javascript:void(0)" onclick="<?php echo in_array($subcategory[0]->id,$this->selectedCategories)?"removeFilterRuleCategory(".$subcategory[0]->id.")":"addFilterRuleCategory(".$subcategory[0]->id.")";?>"> <?php echo $subcategory[0]->name ?>  <?php echo in_array($subcategory[0]->id,$this->selectedCategories) ? '<span class="cross">(remove)</span>':"";  ?></a>
												</div>	
											</li>
										<?php }?>
									</ul>
								<?php } ?>
							</li>
						<?php } ?>
					<?php } ?>
				<?php } ?> 
			</ul>
		<?php } ?>
	
		<?php $searchType = 1;?>
	
	
		<h4><?php echo JText::_("LNG_TYPES") ?></h4>
		<ul>
			<?php
			if(isset($this->searchFilter["types"])) {
				foreach($this->searchFilter["types"] as $filterCriteria) { ?>
					<?php $selected = isset($this->selectedParams["type"]) && in_array($filterCriteria->typeId, $this->selectedParams["type"]); ?>
					<li <?php if($searchType == 1 && $selected) echo 'class="selectedlink"'; ?>>
						<div <?php if($selected) echo 'class="selected"'; ?>>
							<a href="javascript:void(0)" onclick="<?php echo ($selected)?"removeFilterRule('type', ".$filterCriteria->typeId.")":"addFilterRule('type', ".$filterCriteria->typeId.")";?>"><?php echo $filterCriteria->typeName; ?><?php echo ($selected)?'<span class="cross">(remove)</span>':"";  ?></a>
						</div>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>

		<h4><?php echo JText::_("LNG_COUNTRIES") ?></h4>
		<ul>
			<?php
			if(isset($this->searchFilter["countries"])) {
				foreach($this->searchFilter["countries"] as $filterCriteria) { ?>
				<?php if(empty($filterCriteria->countryName)) continue; ?>
					<?php $selected = isset($this->selectedParams["country"]) && in_array($filterCriteria->countryId, $this->selectedParams["country"]); ?>
					<li <?php if($searchType == 1 && $selected) echo 'class="selectedlink"'; ?>>
						<div <?php if($selected) echo 'class="selected"'; ?>>
							<a href="javascript:void(0)" onclick="<?php echo $selected?"removeFilterRule('country', ".$filterCriteria->countryId.")":"addFilterRule('country', ".$filterCriteria->countryId.")";?>"><?php echo $filterCriteria->countryName; ?><?php echo ($selected)?'<span class="cross">(remove)</span>':"";  ?></a>
						</div>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>

		<h4><?php echo JText::_("LNG_REGIONS") ?></h4>
		<ul>
			<?php
			if(isset($this->searchFilter["regions"])) {
				foreach($this->searchFilter["regions"] as $filterCriteria) { ?>
					<?php if(empty($filterCriteria->regionName)) continue; ?>
					<?php $selected = isset($this->selectedParams["region"]) && in_array($filterCriteria->regionName, $this->selectedParams["region"]); ?>
					<li <?php if($searchType == 1 && $selected) echo 'class="selectedlink"'; ?>>
						<div <?php if($selected) echo 'class="selected"'; ?>>
							<a href="javascript:void(0)" onclick="<?php echo $selected?"removeFilterRule('region', '".$filterCriteria->regionName."')":"addFilterRule('region', '".$filterCriteria->regionName."')";?>"><?php echo $filterCriteria->regionName; ?><?php echo ($selected)?'<span class="cross">(remove)</span>':"";  ?></a>
						</div>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>

		<h4><?php echo JText::_("LNG_CITIES") ?></h4>
		<ul>
			<?php
			if(isset($this->searchFilter["cities"])) {
				foreach($this->searchFilter["cities"] as $filterCriteria) { ?>
					<?php if(empty($filterCriteria->cityName)) continue; ?>
					<?php $selected = isset($this->selectedParams["city"]) && in_array($filterCriteria->cityName, $this->selectedParams["city"]); ?>
					<li <?php if($searchType == 1 && $selected) echo 'class="selectedlink"'; ?>>
						<div <?php if($selected) echo 'class="selected"'; ?>>
							<a href="javascript:void(0)" onclick="<?php echo  $selected?"removeFilterRule('city', '".$filterCriteria->cityName."')":"addFilterRule('city', '".$filterCriteria->cityName."')";?>"><?php echo $filterCriteria->cityName; ?><?php echo ($selected)?'<span class="cross">(remove)</span>':"";  ?></a>
						</div>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
</div>

	<?php 
	jimport('joomla.application.module.helper');
	// this is where you want to load your module position
	$modules = JModuleHelper::getModules('search-banners');
	$fullWidth = true;
	?>
	<?php if(isset($modules) && count($modules)>0) { ?>
		<div class="search-banners">
			<?php 
			$fullWidth = false;
			foreach($modules as $module) {
				echo JModuleHelper::renderModule($module);
			} ?>
		</div>
	<?php } ?>
	
</div>
<?php }?>
<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory'.$menuItemId) ?>" method="<?php echo $this->appSettings->submit_method ?>" name="adminForm" id="adminForm">
	<div id="search-results" class="search-results <?php echo !$enableSearchFilter ?'search-results-full span12':'search-results-normal span9' ?> ">
		<?php if(isset($this->category) && $this->appSettings->show_cat_description) { ?>
			<div class="category-container">
				<?php if(!empty($this->category->imageLocation)) { ?>
					<div class="categoy-image"><img alt="<?php echo $this->category->name?>" src="<?php echo JURI::root().PICTURES_PATH.$this->category->imageLocation ?>"></div>
				<?php } ?>
				<h3><?php echo $this->category->name?></h3>
				<div>
					<?php echo $this->category->description?>
				</div>
				<div class="clear"></div>
			</div>
		<?php } else if(!empty($this->country) && $this->appSettings->show_cat_description) { ?>
			<div class="category-container">
				<?php if(!empty($this->country->logo)) { ?>
					<div class="categoy-image"><img alt="<?php echo $this->country->country_name?>" src="<?php echo JURI::root().PICTURES_PATH.$this->country->logo ?>"></div>
				<?php } ?>
				<h3><?php echo $this->country->country_name?></h3>
				<div>
					<?php echo $this->country->description?>
				</div>
				<div class="clear"></div>
			</div>
		<?php } ?>

		<div id="search-details">
			<div class="search-toggles">
				<span class="sortby"><?php echo JText::_('LNG_SORT_BY');?>: </span>
				<select name="orderBy" class="orderBy inputbox input-medium" onchange="changeOrder(this.value)">
					<?php echo JHtml::_('select.options', $this->sortByOptions, 'value', 'text',  $this->orderBy);?>
				</select>

				<?php if($this->appSettings->search_result_view != 5) { ?>
					<p class="view-mode">
						<label><?php echo JText::_('LNG_VIEW')?></label>
						<a id="grid-view-link" class="grid" title="Grid" href="javascript:showGrid()"><?php echo JText::_("LNG_GRID") ?></a>
						<a id="list-view-link" class="list active" title="List" href="javascript:showList()"><?php echo JText::_("LNG_LIST") ?></a>
					</p>
					
					<?php if($this->appSettings->show_search_map) { ?>
						<p class="view-mode">
							<a id="map-link" class="map" title="Grid" href="javascript:showMap(true)"><?php echo JText::_("LNG_SHOW_MAP") ?></a>
						</p>
					<?php } ?>
				<?php } ?>
				<div class="clear"></div>
			</div>
			
			<span class="search-keyword">
				<div class="result-counter"><?php echo $this->pagination->getResultsCounter()?></div> 
				<?php if( !empty($this->customAtrributesValues) || !empty($this->categoryId) || !empty($this->typeSearch) || !empty($this->searchkeyword) || !empty($this->citySearch) || !empty($this->countrySearch) || !empty($this->regionSearch) || !empty($this->zipCode)) {
					$searchText="";
					if(!empty($this->searchkeyword) || !empty($this->customAtrributesValues)){
						echo "<strong>".JText::_('LNG_FOR')."</strong> ";
	
						
						$searchText.= !empty($this->searchkeyword)? $this->searchkeyword:"";
						
						if(!empty($this->searchkeyword) && !empty($this->customAtrributesValues)){
							$searchText .=", ";
						}
						
						if( !empty($this->customAtrributesValues) ) {
							foreach($this->customAtrributesValues as $attribute) {
								$searchText.= !empty($attribute)?$attribute->name.", ":"";
							}
						}
						
						$searchText = trim(trim($searchText), ",");
						
						$searchText .=" ";
					}

					if(!empty($this->citySearch) || !empty($this->countrySearch) || !empty($this->regionSearch) || !empty($this->zipCode)) {
						$searchText.= "<strong>".JText::_('LNG_INTO')."</strong>".' ';
						$searchText.= !empty($this->zipCode)?$this->zipCode.", ":"";
						$searchText.= !empty($this->citySearch)?$this->citySearch.", ":"";
						$searchText.= !empty($this->regionSearch)?$this->regionSearch.", ":"";
						$searchText.= !empty($this->countrySearch)?$this->country->country_name.", ":"";
						$searchText = trim(trim($searchText), ",");
						$searchText.=" ";
					} 

					$searchText.= !empty($this->category->name)?"<strong>".JText::_('LNG_IN')."</strong>".' '.$this->category->name." ":"";
					$searchText.= !empty($this->type->name)?"<strong>".JText::_('LNG_IN')."</strong>".' '.$this->type->name.", ":"";
					$searchText = trim(trim($searchText), ",");

					echo $searchText;
					echo '';
				} ?>
			</span>		
		</div>

		<?php if($this->appSettings->search_result_view != 5 && $appSettings->show_search_map) { ?>
			<div id="companies-map-container" style="display:none">
				<?php require_once JPATH_COMPONENT_SITE.'/include/search-map.php' ?>
			</div>
		<?php } ?>

		<?php 
		require_once JPATH_COMPONENT_SITE.'/include/companies-grid-view.php';
		
		if($this->appSettings->search_result_view == 1) {
			require_once JPATH_COMPONENT_SITE.'/include/companies-list-view.php';
		} else if($this->appSettings->search_result_view == 2) {
			require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-intro.php';
		} else if($this->appSettings->search_result_view == 3) {
			require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-contact.php';
		} else if($this->appSettings->search_result_view == 4) {
			require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-compact.php';
		} else if($this->appSettings->search_result_view == 5) {
			require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-map.php';
		} else {
			require_once JPATH_COMPONENT_SITE.'/include/companies-list-view.php';
		} ?>

		<div class="pagination" <?php echo $this->pagination->total==0 ? 'style="display:none"':''?>>
			<?php echo $this->pagination->getListFooter(); ?>
			<div class="clear"></div>
		</div>
	</div>
	<input type='hidden' name='task' value='searchCompaniesByName'/>
	<input type='hidden' name='option' value='com_jbusinessdirectory'/>
	<input type='hidden' name='controller' value='search' />
	<input type='hidden' name='categories' id="categories-filter" value='<?php echo !empty($this->categories)?$this->categories:"" ?>' />
	<input type='hidden' name='view' value='search' />
	<input type='hidden' name='categoryId' id='categoryId' value='<?php echo !empty($this->categoryId)?$this->categoryId:"0" ?>' />
	<input type='hidden' name='searchkeyword' value='<?php echo !empty($this->searchkeyword)?$this->searchkeyword:'' ?>' />
	<input type='hidden' name='categorySearch' value='<?php echo !empty($this->categorySearch)?$this->categorySearch: '' ?>' />
	<input type='hidden' name='citySearch' id='citySearch' value='<?php echo !empty($this->citySearch)?$this->citySearch: '' ?>' />
	<input type='hidden' name='regionSearch' id='regionSearch' value='<?php echo !empty($this->regionSearch)?$this->regionSearch: '' ?>' />
	<input type='hidden' name='countrySearch' id='countrySearch' value='<?php echo !empty($this->countrySearch)?$this->countrySearch: '' ?>' />
	<input type='hidden' name='typeSearch' id='typeSearch' value='<?php echo !empty($this->typeSearch)?$this->typeSearch: '' ?>' />
	<input type='hidden' name='zipcode' value='<?php echo !empty($this->zipCode)?$this->zipCode: '' ?>' />
	<input type='hidden' name='radius' id="radius" value='<?php echo !empty($this->radius)?$this->radius: '' ?>' />
	<input type='hidden' name='filter_active' id="filter_active" value="<?php echo !empty($this->filterActive)?$this->filterActive: '' ?>" />
	<input type='hidden' name='selectedParams' id='selectedParams' value='<?php echo !empty($this->selectedParams["selectedParams"])?$this->selectedParams["selectedParams"]:"" ?>' />
	<?php if(!empty($this->customAtrributes)){ ?>
		<?php foreach($this->customAtrributes as $key=>$val){?>
			<input type='hidden' name='attribute_<?php echo $key?>' value='<?php echo $val ?>' />
		<?php } ?>
	<?php } ?>
	
</form>
<div class="clear"></div>

</div>
<?php 
if($this->appSettings->search_result_view == 3) {
	require_once JPATH_COMPONENT_SITE.'/include/companies-list-view-contact-util.php';
}
?>

<script>
jQuery(document).ready(function() {
	jQuery('.rating-average').raty({
		half:       true,
		precision:  false,
		size:       24,
		starHalf:   'star-half.png',
		starOff:    'star-off.png',
		starOn:     'star-on.png',
		hintList:	  ["<?php echo JText::_('LNG_BAD') ?>","<?php echo JText::_('LNG_POOR') ?>","<?php echo JText::_('LNG_REGULAR') ?>","<?php echo JText::_('LNG_GOOD') ?>","<?php echo JText::_('LNG_GORGEOUS') ?>"],
		noRatedMsg: "<?php echo JText::_('LNG_NOT_RATED_YET') ?>",
		start:   	  function() { return jQuery(this).attr('title')},
		path:		  '<?php echo COMPONENT_IMAGE_PATH?>',
		click: function(score, evt) {
			<?php $user = JFactory::getUser(); 
			if($this->appSettings->enable_reviews_users && $user->id ==0) { ?>
				jQuery(this).raty('start',jQuery(this).attr('title'));
				jQuery(this).parent().parent().find(".rating-awareness").show();
			<?php } else {  ?>
				updateCompanyRate(jQuery(this).attr('alt'),score);
			<?php } ?>
		}
	});

	jQuery('.button-toggle').click(function() {  
		if(!jQuery(this).hasClass("active")) {
			jQuery(this).addClass('active');
		}
		jQuery('.button-toggle').not(this).removeClass('active'); // remove buttonactive from the others
	});

	<?php if ($this->appSettings->map_auto_show == 1) { ?>
		showMap(true);
	<?php } ?>

	<?php if ($this->appSettings->search_view_mode == 1 && $this->appSettings->search_result_view != 5) { ?>
		showGrid();
	<?php } else { ?>
		showList();
	<?php }?>

	//disable all empty fields to have a nice url
    <?php if($this->appSettings->submit_method=="get"){?>
	    jQuery('#adminForm').submit(function() {
	    	jQuery(':input', this).each(function() {
	            this.disabled = !(jQuery(this).val());
	        });
	
	    	jQuery('#adminForm select').each(function() {
		    	if(!(jQuery(this).val()) || jQuery(this).val()==0){
	            	jQuery(this).attr('disabled', 'disabled');
		    	}
	        });
	    });

     <?php }?>
});

function saveSelectedCategory(){
	var catId;
	var checked = jQuery("#filterCategoryItems input[type='checkbox']:checked");
	catId = checked.attr('id');

	<?php if(isset($this->category)) { ?>
		catId =  <?php echo $this->categoryId; ?>
	<?php } ?>

	jQuery("#adminForm #categoryId").val(catId);
	jQuery("#adminForm input[name=limitstart]").val(0);
}

function changeOrder(orderField) {
	jQuery("#orderBy").val(orderField);
	jQuery("#adminForm").submit();	
}

function showMap(display) {
	jQuery("#map-link").toggleClass("active");

	if(jQuery("#map-link").hasClass("active")) {
		jQuery("#companies-map-container").show();
		jQuery("#map-link").html("<?php echo JText::_("LNG_HIDE_MAP")?>");
		loadMapScript();
	} else {
		jQuery("#map-link").html("<?php echo JText::_("LNG_SHOW_MAP")?>");
		jQuery("#companies-map-container").hide();
	}
}

function showList() {
	jQuery("#results-container").show();
	jQuery("#jbd-grid-view").hide();

	jQuery("#grid-view-link").removeClass("active");
	jQuery("#list-view-link").addClass("active");
}

function showGrid() {
	jQuery("#results-container").hide();
	jQuery("#jbd-grid-view").show();
	applyIsotope();
	jQuery(window).resize();
	
	jQuery("#grid-view-link").addClass("active");
	jQuery("#list-view-link").removeClass("active");
}

function chooseCategory(categoryId) {
	if(categoryId.toString().substring(0, 3)=="chk"){
		categoryId= categoryId.substring(3);
	}
	categoryId = categoryId.toString().replace(";","");
	jQuery("#adminForm #categoryId").val(categoryId);
	jQuery("#adminForm input[name=limitstart]").val(0);
	jQuery("#adminForm").submit();
}

function addFilterRule(type, id) {
	var val = type+'='+id+';';
	if (jQuery("#selectedParams").val().length > 0) {
		jQuery("#selectedParams").val(jQuery("#selectedParams").val() + val);
	} else {
		jQuery("#selectedParams").val(val);
	}
	<?php if(!isset($this->category)) { ?>
		jQuery("#filter_active").val("1");
	<?php } ?>
	jQuery("#adminForm input[name=limitstart]").val(0);
		saveSelectedCategory();
	jQuery("#adminForm").submit();
}

function removeFilterRule(type, id) {
	var val = type+'='+id+';';
	var str = jQuery("#selectedParams").val();
	jQuery("#selectedParams").val((str.replace(val, "")));
	jQuery("#filter_active").val("1");
	saveSelectedCategory();

	if(type=="city")
		jQuery("#adminForm #citySearch").val("");
	if(type=="region")
		jQuery("#adminForm #regionSearch").val("");
	if(type=="country")
		jQuery("#adminForm #countrySearch").val("");
	if(type=="type")	
		jQuery("#adminForm #typeSearch").val("");
	
	jQuery("#adminForm").submit();
	
}

function resetFilters(resetCategories){
	jQuery("#selectedParams").val("");
	if(resetCategories)
		jQuery("#categories-filter").val("");
	else
		saveSelectedCategory();
	jQuery("#adminForm #categoryId").val("");

	jQuery("#adminForm #citySearch").val("");
	jQuery("#adminForm #regionSearch").val("");
	jQuery("#adminForm #countrySearch").val("");
	jQuery("#adminForm #typeSearch").val("");
		
	jQuery("#adminForm").submit();
}

function addFilterRuleCategory(catId) {
	catId = catId +";";
	if (jQuery("#categories-filter").val().length > 0) {
		jQuery("#categories-filter").val(jQuery("#categories-filter").val() + catId);
	} else {
		jQuery("#categories-filter").val(catId);
	}
	jQuery("#filter_active").val("1");
	jQuery("#adminForm input[name=limitstart]").val(0);
	chooseCategory(catId);
}

function removeFilterRuleCategory(catId) {
	var categoryId = catId +";";
	var str = jQuery("#categories-filter").val();
	jQuery("#categories-filter").val((str.replace(categoryId, "")));
	jQuery("#filter_active").val("1");
	var checked = jQuery("#filterCategoryItems input[type='checkbox']:checked");
	if(checked.length > 0) {
		checked.each(function(){
			var id = jQuery(this).attr('id');
			if(id != catId) {
				chooseCategory(id);
				return false;
			}
		});
	}
	else if(checked.length == 0){
		var categoryIds = jQuery("#categories-filter").val();
		var categoryId = categoryIds.slice(0, categoryIds.length-1);
		var start = categoryId.lastIndexOf(';') + 1;
		if(start == -1)
			start = 0;

		categoryId = categoryId.slice(start, categoryId.length);
		chooseCategory(categoryId);
	}
}

function setRadius(radius) {
	jQuery("#adminForm > #radius").val(radius);
	jQuery("#adminForm input[name=limitstart]").val(0);
	jQuery("#adminForm").submit();
}
</script>