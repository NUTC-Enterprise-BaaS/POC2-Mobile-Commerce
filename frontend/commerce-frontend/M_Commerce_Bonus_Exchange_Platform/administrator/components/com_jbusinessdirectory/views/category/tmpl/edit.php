<?php
/**
 */

defined('_JEXEC') or die('Restricted access');

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
//JHtml::_('formbehavior.chosen');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
JHTML::_('behavior.colorpicker');
jimport('joomla.html.pane');

$app = JFactory::getApplication();

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
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {

		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<div class="category-form-container">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<fieldset class="boxed">
			<h2> <?php echo JText::_('LNG_CATEGORY_DETAILS');?></h2>
			<div class="form-box">
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('LNG_NAME')?> </label> 
					<?php 
						if($this->appSettings->enable_multilingual) {
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages  as $k=>$lng ) {
								echo JHtml::_('tabs.panel', $lng, 'tab-'.$lng );
								$langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
								if($lng==JFactory::getLanguage()->getTag() && empty($langContent)){
									$langContent = $this->item->name;
								}
								echo "<input type='text' name='name_$lng' id='name_$lng' class='input_txt validate[required]' value=\"".stripslashes($langContent)."\"  maxLength='100'>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						} else { ?>
							<input type="text" name="name" id="name" class="input_txt validate[required]" value="<?php echo $this->item->name ?>"  maxLength="100">
						<?php } ?>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="name"><?php echo JText::_('LNG_ALIAS')?> </label> 
					<input type="text"	name="alias" id="alias"  placeholder="<?php echo JText::_('LNG_AUTO_GENERATE_FROM_NAME')?>" class="input_txt text-input" value="<?php echo $this->item->alias ?>"  maxLength="100">
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="name"><?php echo JText::_('LNG_PARENT')?> </label> 
					<select id="parent_id" name="parent_id" class="inputbox input-medium">
						<?php echo JHtml::_('select.options', $this->categoryOptions, 'value', 'text', $this->item->parent_id);?>
					</select>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="type"><?php echo JText::_('LNG_TYPE')?> </label> 
					<select id="type" name="type" class="inputbox input-medium input_sel validate[required]" disabled>
						<?php echo JHtml::_('select.options', $this->types, 'value', 'text', $this->typeSelected); ?>
					</select>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="icon"><?php echo JText::_('LNG_ICON')?></label>
					<select id="icon-holder" name="icon" data-placeholder="<?php echo JText::_('LNG_CHOOSE_ICON') ?>" class="icon-select">
						<option value=""></option>
				        <option data-icon="dir-icon-adjust">adjust</option>
						<option data-icon="dir-icon-adn">adn</option>
						<option data-icon="dir-icon-align-center">align-center</option>
						<option data-icon="dir-icon-align-justify">align-justify</option>
						<option data-icon="dir-icon-align-left">align-left</option>
						<option data-icon="dir-icon-align-right">align-right</option>
						<option data-icon="dir-icon-ambulance">ambulance</option>
						<option data-icon="dir-icon-anchor">anchor</option>
						<option data-icon="dir-icon-android">android</option>
						<option data-icon="dir-icon-angellist">angellist</option>
						<option data-icon="dir-icon-angle-double-down">angle-double-down</option>
						<option data-icon="dir-icon-angle-double-left">angle-double-left</option>
						<option data-icon="dir-icon-angle-double-right">angle-double-right</option>
						<option data-icon="dir-icon-angle-double-up">angle-double-up</option>
						<option data-icon="dir-icon-angle-down">angle-down</option>
						<option data-icon="dir-icon-angle-left">angle-left</option>
						<option data-icon="dir-icon-angle-right">angle-right</option>
						<option data-icon="dir-icon-angle-up">angle-up</option>
						<option data-icon="dir-icon-apple">apple</option>
						<option data-icon="dir-icon-archive">archive</option>
						<option data-icon="dir-icon-area-chart">area-chart</option>
						<option data-icon="dir-icon-arrow-circle-down">arrow-circle-down</option>
						<option data-icon="dir-icon-arrow-circle-left">arrow-circle-left</option>
						<option data-icon="dir-icon-arrow-circle-o-down">arrow-circle-o-down</option>
						<option data-icon="dir-icon-arrow-circle-o-left">arrow-circle-o-left</option>
						<option data-icon="dir-icon-arrow-circle-o-right">arrow-circle-o-right</option>
						<option data-icon="dir-icon-arrow-circle-o-up">arrow-circle-o-up</option>
						<option data-icon="dir-icon-arrow-circle-right">arrow-circle-right</option>
						<option data-icon="dir-icon-arrow-circle-up">arrow-circle-up</option>
						<option data-icon="dir-icon-arrow-down">arrow-down</option>
						<option data-icon="dir-icon-arrow-left">arrow-left</option>
						<option data-icon="dir-icon-arrow-right">arrow-right</option>
						<option data-icon="dir-icon-arrow-up">arrow-up</option>
						<option data-icon="dir-icon-arrows">arrows</option>
						<option data-icon="dir-icon-arrows-alt">arrows-alt</option>
						<option data-icon="dir-icon-arrows-h">arrows-h</option>
						<option data-icon="dir-icon-arrows-v">arrows-v</option>
						<option data-icon="dir-icon-asterisk">asterisk</option>
						<option data-icon="dir-icon-at">at</option>
						<option data-icon="dir-icon-automobile">automobile</option>
						<option data-icon="dir-icon-backward">backward</option>
						<option data-icon="dir-icon-ban">ban</option>
						<option data-icon="dir-icon-bank">bank</option>
						<option data-icon="dir-icon-bar-chart">bar-chart</option>
						<option data-icon="dir-icon-bar-chart-o">bar-chart-o</option>
						<option data-icon="dir-icon-barcode">barcode</option>
						<option data-icon="dir-icon-bars">bars</option>
						<option data-icon="dir-icon-beer">beer</option>
						<option data-icon="dir-icon-behance">behance</option>
						<option data-icon="dir-icon-behance-square">behance-square</option>
						<option data-icon="dir-icon-bell">bell</option>
						<option data-icon="dir-icon-bell-o">bell-o</option>
						<option data-icon="dir-icon-bell-slash">bell-slash</option>
						<option data-icon="dir-icon-bell-slash-o">bell-slash-o</option>
						<option data-icon="dir-icon-bicycle">bicycle</option>
						<option data-icon="dir-icon-binoculars">binoculars</option>
						<option data-icon="dir-icon-birthday-cake">birthday-cake</option>
						<option data-icon="dir-icon-bitbucket">bitbucket</option>
						<option data-icon="dir-icon-bitbucket-square">bitbucket-square</option>
						<option data-icon="dir-icon-bitcoin">bitcoin</option>
						<option data-icon="dir-icon-bold">bold</option>
						<option data-icon="dir-icon-bolt">bolt</option>
						<option data-icon="dir-icon-bomb">bomb</option>
						<option data-icon="dir-icon-book">book</option>
						<option data-icon="dir-icon-bookmark">bookmark</option>
						<option data-icon="dir-icon-bookmark-o">bookmark-o</option>
						<option data-icon="dir-icon-briefcase">briefcase</option>
						<option data-icon="dir-icon-btc">btc</option>
						<option data-icon="dir-icon-bug">bug</option>
						<option data-icon="dir-icon-building">building</option>
						<option data-icon="dir-icon-building-o">building-o</option>
						<option data-icon="dir-icon-bullhorn">bullhorn</option>
						<option data-icon="dir-icon-bullseye">bullseye</option>
						<option data-icon="dir-icon-bus">bus</option>
						<option data-icon="dir-icon-cab">cab</option>
						<option data-icon="dir-icon-calculator">calculator</option>
						<option data-icon="dir-icon-calendar">calendar</option>
						<option data-icon="dir-icon-calendar-o">calendar-o</option>
						<option data-icon="dir-icon-camera">camera</option>
						<option data-icon="dir-icon-camera-retro">camera-retro</option>
						<option data-icon="dir-icon-car">car</option>
						<option data-icon="dir-icon-caret-down">caret-down</option>
						<option data-icon="dir-icon-caret-left">caret-left</option>
						<option data-icon="dir-icon-caret-right">caret-right</option>
						<option data-icon="dir-icon-caret-square-o-down">caret-square-o-down</option>
						<option data-icon="dir-icon-caret-square-o-left">caret-square-o-left</option>
						<option data-icon="dir-icon-caret-square-o-right">caret-square-o-right</option>
						<option data-icon="dir-icon-caret-square-o-up">caret-square-o-up</option>
						<option data-icon="dir-icon-caret-up">caret-up</option>
						<option data-icon="dir-icon-cc">cc</option>
						<option data-icon="dir-icon-cc-amex">cc-amex</option>
						<option data-icon="dir-icon-cc-discover">cc-discover</option>
						<option data-icon="dir-icon-cc-mastercard">cc-mastercard</option>
						<option data-icon="dir-icon-cc-paypal">cc-paypal</option>
						<option data-icon="dir-icon-cc-stripe">cc-stripe</option>
						<option data-icon="dir-icon-cc-visa">cc-visa</option>
						<option data-icon="dir-icon-certificate">certificate</option>
						<option data-icon="dir-icon-chain">chain</option>
						<option data-icon="dir-icon-chain-broken">chain-broken</option>
						<option data-icon="dir-icon-check">check</option>
						<option data-icon="dir-icon-check-circle">check-circle</option>
						<option data-icon="dir-icon-check-circle-o">check-circle-o</option>
						<option data-icon="dir-icon-check-square">check-square</option>
						<option data-icon="dir-icon-check-square-o">check-square-o</option>
						<option data-icon="dir-icon-chevron-circle-down">chevron-circle-down</option>
						<option data-icon="dir-icon-chevron-circle-left">chevron-circle-left</option>
						<option data-icon="dir-icon-chevron-circle-right">chevron-circle-right</option>
						<option data-icon="dir-icon-chevron-circle-up">chevron-circle-up</option>
						<option data-icon="dir-icon-chevron-down">chevron-down</option>
						<option data-icon="dir-icon-chevron-left">chevron-left</option>
						<option data-icon="dir-icon-chevron-right">chevron-right</option>
						<option data-icon="dir-icon-chevron-up">chevron-up</option>
						<option data-icon="dir-icon-child">child</option>
						<option data-icon="dir-icon-circle">circle</option>
						<option data-icon="dir-icon-circle-o">circle-o</option>
						<option data-icon="dir-icon-circle-o-notch">circle-o-notch</option>
						<option data-icon="dir-icon-circle-thin">circle-thin</option>
						<option data-icon="dir-icon-clipboard">clipboard</option>
						<option data-icon="dir-icon-clock-o">clock-o</option>
						<option data-icon="dir-icon-close">close</option>
						<option data-icon="dir-icon-cloud">cloud</option>
						<option data-icon="dir-icon-cloud-download">cloud-download</option>
						<option data-icon="dir-icon-cloud-upload">cloud-upload</option>
						<option data-icon="dir-icon-cny">cny</option>
						<option data-icon="dir-icon-code">code</option>
						<option data-icon="dir-icon-code-fork">code-fork</option>
						<option data-icon="dir-icon-codepen">codepen</option>
						<option data-icon="dir-icon-coffee">coffee</option>
						<option data-icon="dir-icon-cog">cog</option>
						<option data-icon="dir-icon-cogs">cogs</option>
						<option data-icon="dir-icon-columns">columns</option>
						<option data-icon="dir-icon-comment">comment</option>
						<option data-icon="dir-icon-comment-o">comment-o</option>
						<option data-icon="dir-icon-comments">comments</option>
						<option data-icon="dir-icon-comments-o">comments-o</option>
						<option data-icon="dir-icon-compass">compass</option>
						<option data-icon="dir-icon-compress">compress</option>
						<option data-icon="dir-icon-copy">copy</option>
						<option data-icon="dir-icon-copyright">copyright</option>
						<option data-icon="dir-icon-credit-card">credit-card</option>
						<option data-icon="dir-icon-crop">crop</option>
						<option data-icon="dir-icon-crosshairs">crosshairs</option>
						<option data-icon="dir-icon-css3">css3</option>
						<option data-icon="dir-icon-cube">cube</option>
						<option data-icon="dir-icon-cubes">cubes</option>
						<option data-icon="dir-icon-cut">cut</option>
						<option data-icon="dir-icon-cutlery">cutlery</option>
						<option data-icon="dir-icon-dashboard">dashboard</option>
						<option data-icon="dir-icon-database">database</option>
						<option data-icon="dir-icon-dedent">dedent</option>
						<option data-icon="dir-icon-delicious">delicious</option>
						<option data-icon="dir-icon-desktop">desktop</option>
						<option data-icon="dir-icon-deviantart">deviantart</option>
						<option data-icon="dir-icon-digg">digg</option>
						<option data-icon="dir-icon-dollar">dollar</option>
						<option data-icon="dir-icon-dot-circle-o">dot-circle-o</option>
						<option data-icon="dir-icon-download">download</option>
						<option data-icon="dir-icon-dribbble">dribbble</option>
						<option data-icon="dir-icon-dropbox">dropbox</option>
						<option data-icon="dir-icon-drupal">drupal</option>
						<option data-icon="dir-icon-edit">edit</option>
						<option data-icon="dir-icon-eject">eject</option>
						<option data-icon="dir-icon-ellipsis-h">ellipsis-h</option>
						<option data-icon="dir-icon-ellipsis-v">ellipsis-v</option>
						<option data-icon="dir-icon-empire">empire</option>
						<option data-icon="dir-icon-envelope">envelope</option>
						<option data-icon="dir-icon-envelope-o">envelope-o</option>
						<option data-icon="dir-icon-envelope-square">envelope-square</option>
						<option data-icon="dir-icon-eraser">eraser</option>
						<option data-icon="dir-icon-eur">eur</option>
						<option data-icon="dir-icon-euro">euro</option>
						<option data-icon="dir-icon-exchange">exchange</option>
						<option data-icon="dir-icon-exclamation">exclamation</option>
						<option data-icon="dir-icon-exclamation-circle">exclamation-circle</option>
						<option data-icon="dir-icon-exclamation-triangle">exclamation-triangle</option>
						<option data-icon="dir-icon-expand">expand</option>
						<option data-icon="dir-icon-external-link">external-link</option>
						<option data-icon="dir-icon-external-link-square">external-link-square</option>
						<option data-icon="dir-icon-eye">eye</option>
						<option data-icon="dir-icon-eye-slash">eye-slash</option>
						<option data-icon="dir-icon-eyedropper">eyedropper</option>
						<option data-icon="dir-icon-facebook">facebook</option>
						<option data-icon="dir-icon-facebook-square">facebook-square</option>
						<option data-icon="dir-icon-fast-backward">fast-backward</option>
						<option data-icon="dir-icon-fast-forward">fast-forward</option>
						<option data-icon="dir-icon-fax">fax</option>
						<option data-icon="dir-icon-female">female</option>
						<option data-icon="dir-icon-fighter-jet">fighter-jet</option>
						<option data-icon="dir-icon-file">file</option>
						<option data-icon="dir-icon-file-archive-o">file-archive-o</option>
						<option data-icon="dir-icon-file-audio-o">file-audio-o</option>
						<option data-icon="dir-icon-file-code-o">file-code-o</option>
						<option data-icon="dir-icon-file-excel-o">file-excel-o</option>
						<option data-icon="dir-icon-file-image-o">file-image-o</option>
						<option data-icon="dir-icon-file-movie-o">file-movie-o</option>
						<option data-icon="dir-icon-file-o">file-o</option>
						<option data-icon="dir-icon-file-pdf-o">file-pdf-o</option>
						<option data-icon="dir-icon-file-photo-o">file-photo-o</option>
						<option data-icon="dir-icon-file-picture-o">file-picture-o</option>
						<option data-icon="dir-icon-file-powerpoint-o">file-powerpoint-o</option>
						<option data-icon="dir-icon-file-sound-o">file-sound-o</option>
						<option data-icon="dir-icon-file-text">file-text</option>
						<option data-icon="dir-icon-file-text-o">file-text-o</option>
						<option data-icon="dir-icon-file-video-o">file-video-o</option>
						<option data-icon="dir-icon-file-word-o">file-word-o</option>
						<option data-icon="dir-icon-file-zip-o">file-zip-o</option>
						<option data-icon="dir-icon-files-o">files-o</option>
						<option data-icon="dir-icon-film">film</option>
						<option data-icon="dir-icon-filter">filter</option>
						<option data-icon="dir-icon-fire">fire</option>
						<option data-icon="dir-icon-fire-extinguisher">fire-extinguisher</option>
						<option data-icon="dir-icon-flag">flag</option>
						<option data-icon="dir-icon-flag-checkered">flag-checkered</option>
						<option data-icon="dir-icon-flag-o">flag-o</option>
						<option data-icon="dir-icon-flash">flash</option>
						<option data-icon="dir-icon-flask">flask</option>
						<option data-icon="dir-icon-flickr">flickr</option>
						<option data-icon="dir-icon-floppy-o">floppy-o</option>
						<option data-icon="dir-icon-folder">folder</option>
						<option data-icon="dir-icon-folder-o">folder-o</option>
						<option data-icon="dir-icon-folder-open">folder-open</option>
						<option data-icon="dir-icon-folder-open-o">folder-open-o</option>
						<option data-icon="dir-icon-font">font</option>
						<option data-icon="dir-icon-forward">forward</option>
						<option data-icon="dir-icon-foursquare">foursquare</option>
						<option data-icon="dir-icon-frown-o">frown-o</option>
						<option data-icon="dir-icon-futbol-o">futbol-o</option>
						<option data-icon="dir-icon-gamepad">gamepad</option>
						<option data-icon="dir-icon-gavel">gavel</option>
						<option data-icon="dir-icon-gbp">gbp</option>
						<option data-icon="dir-icon-ge">ge</option>
						<option data-icon="dir-icon-gear">gear</option>
						<option data-icon="dir-icon-gears">gears</option>
						<option data-icon="dir-icon-gift">gift</option>
						<option data-icon="dir-icon-git">git</option>
						<option data-icon="dir-icon-git-square">git-square</option>
						<option data-icon="dir-icon-github">github</option>
						<option data-icon="dir-icon-github-alt">github-alt</option>
						<option data-icon="dir-icon-github-square">github-square</option>
						<option data-icon="dir-icon-gittip">gittip</option>
						<option data-icon="dir-icon-glass">glass</option>
						<option data-icon="dir-icon-globe">globe</option>
						<option data-icon="dir-icon-google">google</option>
						<option data-icon="dir-icon-google-plus">google-plus</option>
						<option data-icon="dir-icon-google-plus-square">google-plus-square</option>
						<option data-icon="dir-icon-google-wallet">google-wallet</option>
						<option data-icon="dir-icon-graduation-cap">graduation-cap</option>
						<option data-icon="dir-icon-group">group</option>
						<option data-icon="dir-icon-h-square">h-square</option>
						<option data-icon="dir-icon-hacker-news">hacker-news</option>
						<option data-icon="dir-icon-hand-o-down">hand-o-down</option>
						<option data-icon="dir-icon-hand-o-left">hand-o-left</option>
						<option data-icon="dir-icon-hand-o-right">hand-o-right</option>
						<option data-icon="dir-icon-hand-o-up">hand-o-up</option>
						<option data-icon="dir-icon-hdd-o">hdd-o</option>
						<option data-icon="dir-icon-header">header</option>
						<option data-icon="dir-icon-headphones">headphones</option>
						<option data-icon="dir-icon-heart">heart</option>
						<option data-icon="dir-icon-heart-o">heart-o</option>
						<option data-icon="dir-icon-history">history</option>
						<option data-icon="dir-icon-home">home</option>
						<option data-icon="dir-icon-hospital-o">hospital-o</option>
						<option data-icon="dir-icon-html5">html5</option>
						<option data-icon="dir-icon-ils">ils</option>
						<option data-icon="dir-icon-image">image</option>
						<option data-icon="dir-icon-inbox">inbox</option>
						<option data-icon="dir-icon-indent">indent</option>
						<option data-icon="dir-icon-info">info</option>
						<option data-icon="dir-icon-info-circle">info-circle</option>
						<option data-icon="dir-icon-inr">inr</option>
						<option data-icon="dir-icon-instagram">instagram</option>
						<option data-icon="dir-icon-institution">institution</option>
						<option data-icon="dir-icon-ioxhost">ioxhost</option>
						<option data-icon="dir-icon-italic">italic</option>
						<option data-icon="dir-icon-joomla">joomla</option>
						<option data-icon="dir-icon-jpy">jpy</option>
						<option data-icon="dir-icon-jsfiddle">jsfiddle</option>
						<option data-icon="dir-icon-key">key</option>
						<option data-icon="dir-icon-keyboard-o">keyboard-o</option>
						<option data-icon="dir-icon-krw">krw</option>
						<option data-icon="dir-icon-language">language</option>
						<option data-icon="dir-icon-laptop">laptop</option>
						<option data-icon="dir-icon-lastfm">lastfm</option>
						<option data-icon="dir-icon-lastfm-square">lastfm-square</option>
						<option data-icon="dir-icon-leaf">leaf</option>
						<option data-icon="dir-icon-legal">legal</option>
						<option data-icon="dir-icon-lemon-o">lemon-o</option>
						<option data-icon="dir-icon-level-down">level-down</option>
						<option data-icon="dir-icon-level-up">level-up</option>
						<option data-icon="dir-icon-life-bouy">life-bouy</option>
						<option data-icon="dir-icon-life-buoy">life-buoy</option>
						<option data-icon="dir-icon-life-ring">life-ring</option>
						<option data-icon="dir-icon-life-saver">life-saver</option>
						<option data-icon="dir-icon-lightbulb-o">lightbulb-o</option>
						<option data-icon="dir-icon-line-chart">line-chart</option>
						<option data-icon="dir-icon-link">link</option>
						<option data-icon="dir-icon-linkedin">linkedin</option>
						<option data-icon="dir-icon-linkedin-square">linkedin-square</option>
						<option data-icon="dir-icon-linux">linux</option>
						<option data-icon="dir-icon-list">list</option>
						<option data-icon="dir-icon-list-alt">list-alt</option>
						<option data-icon="dir-icon-list-ol">list-ol</option>
						<option data-icon="dir-icon-list-ul">list-ul</option>
						<option data-icon="dir-icon-location-arrow">location-arrow</option>
						<option data-icon="dir-icon-lock">lock</option>
						<option data-icon="dir-icon-long-arrow-down">long-arrow-down</option>
						<option data-icon="dir-icon-long-arrow-left">long-arrow-left</option>
						<option data-icon="dir-icon-long-arrow-right">long-arrow-right</option>
						<option data-icon="dir-icon-long-arrow-up">long-arrow-up</option>
						<option data-icon="dir-icon-magic">magic</option>
						<option data-icon="dir-icon-magnet">magnet</option>
						<option data-icon="dir-icon-mail-forward">mail-forward</option>
						<option data-icon="dir-icon-mail-reply">mail-reply</option>
						<option data-icon="dir-icon-mail-reply-all">mail-reply-all</option>
						<option data-icon="dir-icon-male">male</option>
						<option data-icon="dir-icon-map-marker">map-marker</option>
						<option data-icon="dir-icon-maxcdn">maxcdn</option>
						<option data-icon="dir-icon-meanpath">meanpath</option>
						<option data-icon="dir-icon-medkit">medkit</option>
						<option data-icon="dir-icon-meh-o">meh-o</option>
						<option data-icon="dir-icon-microphone">microphone</option>
						<option data-icon="dir-icon-microphone-slash">microphone-slash</option>
						<option data-icon="dir-icon-minus">minus</option>
						<option data-icon="dir-icon-minus-circle">minus-circle</option>
						<option data-icon="dir-icon-minus-square">minus-square</option>
						<option data-icon="dir-icon-minus-square-o">minus-square-o</option>
						<option data-icon="dir-icon-mobile">mobile</option>
						<option data-icon="dir-icon-mobile-phone">mobile-phone</option>
						<option data-icon="dir-icon-money">money</option>
						<option data-icon="dir-icon-moon-o">moon-o</option>
						<option data-icon="dir-icon-mortar-board">mortar-board</option>
						<option data-icon="dir-icon-music">music</option>
						<option data-icon="dir-icon-navicon">navicon</option>
						<option data-icon="dir-icon-newspaper-o">newspaper-o</option>
						<option data-icon="dir-icon-openid">openid</option>
						<option data-icon="dir-icon-outdent">outdent</option>
						<option data-icon="dir-icon-pagelines">pagelines</option>
						<option data-icon="dir-icon-paint-brush">paint-brush</option>
						<option data-icon="dir-icon-paper-plane">paper-plane</option>
						<option data-icon="dir-icon-paper-plane-o">paper-plane-o</option>
						<option data-icon="dir-icon-paperclip">paperclip</option>
						<option data-icon="dir-icon-paragraph">paragraph</option>
						<option data-icon="dir-icon-paste">paste</option>
						<option data-icon="dir-icon-pause">pause</option>
						<option data-icon="dir-icon-paw">paw</option>
						<option data-icon="dir-icon-paypal">paypal</option>
						<option data-icon="dir-icon-pencil">pencil</option>
						<option data-icon="dir-icon-pencil-square">pencil-square</option>
						<option data-icon="dir-icon-pencil-square-o">pencil-square-o</option>
						<option data-icon="dir-icon-phone">phone</option>
						<option data-icon="dir-icon-phone-square">phone-square</option>
						<option data-icon="dir-icon-photo">photo</option>
						<option data-icon="dir-icon-picture-o">picture-o</option>
						<option data-icon="dir-icon-pie-chart">pie-chart</option>
						<option data-icon="dir-icon-pied-piper">pied-piper</option>
						<option data-icon="dir-icon-pied-piper-alt">pied-piper-alt</option>
						<option data-icon="dir-icon-pinterest">pinterest</option>
						<option data-icon="dir-icon-pinterest-square">pinterest-square</option>
						<option data-icon="dir-icon-plane">plane</option>
						<option data-icon="dir-icon-play">play</option>
						<option data-icon="dir-icon-play-circle">play-circle</option>
						<option data-icon="dir-icon-play-circle-o">play-circle-o</option>
						<option data-icon="dir-icon-plug">plug</option>
						<option data-icon="dir-icon-plus">plus</option>
						<option data-icon="dir-icon-plus-circle">plus-circle</option>
						<option data-icon="dir-icon-plus-square">plus-square</option>
						<option data-icon="dir-icon-plus-square-o">plus-square-o</option>
						<option data-icon="dir-icon-power-off">power-off</option>
						<option data-icon="dir-icon-print">print</option>
						<option data-icon="dir-icon-puzzle-piece">puzzle-piece</option>
						<option data-icon="dir-icon-qq">qq</option>
						<option data-icon="dir-icon-qrcode">qrcode</option>
						<option data-icon="dir-icon-question">question</option>
						<option data-icon="dir-icon-question-circle">question-circle</option>
						<option data-icon="dir-icon-quote-left">quote-left</option>
						<option data-icon="dir-icon-quote-right">quote-right</option>
						<option data-icon="dir-icon-ra">ra</option>
						<option data-icon="dir-icon-random">random</option>
						<option data-icon="dir-icon-rebel">rebel</option>
						<option data-icon="dir-icon-recycle">recycle</option>
						<option data-icon="dir-icon-reddit">reddit</option>
						<option data-icon="dir-icon-reddit-square">reddit-square</option>
						<option data-icon="dir-icon-refresh">refresh</option>
						<option data-icon="dir-icon-remove">remove</option>
						<option data-icon="dir-icon-renren">renren</option>
						<option data-icon="dir-icon-reorder">reorder</option>
						<option data-icon="dir-icon-repeat">repeat</option>
						<option data-icon="dir-icon-reply">reply</option>
						<option data-icon="dir-icon-reply-all">reply-all</option>
						<option data-icon="dir-icon-retweet">retweet</option>
						<option data-icon="dir-icon-rmb">rmb</option>
						<option data-icon="dir-icon-road">road</option>
						<option data-icon="dir-icon-rocket">rocket</option>
						<option data-icon="dir-icon-rotate-left">rotate-left</option>
						<option data-icon="dir-icon-rotate-right">rotate-right</option>
						<option data-icon="dir-icon-rouble">rouble</option>
						<option data-icon="dir-icon-rss">rss</option>
						<option data-icon="dir-icon-rss-square">rss-square</option>
						<option data-icon="dir-icon-rub">rub</option>
						<option data-icon="dir-icon-ruble">ruble</option>
						<option data-icon="dir-icon-rupee">rupee</option>
						<option data-icon="dir-icon-save">save</option>
						<option data-icon="dir-icon-scissors">scissors</option>
						<option data-icon="dir-icon-search">search</option>
						<option data-icon="dir-icon-search-minus">search-minus</option>
						<option data-icon="dir-icon-search-plus">search-plus</option>
						<option data-icon="dir-icon-send">send</option>
						<option data-icon="dir-icon-send-o">send-o</option>
						<option data-icon="dir-icon-share">share</option>
						<option data-icon="dir-icon-share-alt">share-alt</option>
						<option data-icon="dir-icon-share-alt-square">share-alt-square</option>
						<option data-icon="dir-icon-share-square">share-square</option>
						<option data-icon="dir-icon-share-square-o">share-square-o</option>
						<option data-icon="dir-icon-shekel">shekel</option>
						<option data-icon="dir-icon-sheqel">sheqel</option>
						<option data-icon="dir-icon-shield">shield</option>
						<option data-icon="dir-icon-shopping-cart">shopping-cart</option>
						<option data-icon="dir-icon-sign-in">sign-in</option>
						<option data-icon="dir-icon-sign-out">sign-out</option>
						<option data-icon="dir-icon-signal">signal</option>
						<option data-icon="dir-icon-sitemap">sitemap</option>
						<option data-icon="dir-icon-skype">skype</option>
						<option data-icon="dir-icon-slack">slack</option>
						<option data-icon="dir-icon-sliders">sliders</option>
						<option data-icon="dir-icon-slideshare">slideshare</option>
						<option data-icon="dir-icon-smile-o">smile-o</option>
						<option data-icon="dir-icon-soccer-ball-o">soccer-ball-o</option>
						<option data-icon="dir-icon-sort">sort</option>
						<option data-icon="dir-icon-sort-alpha-asc">sort-alpha-asc</option>
						<option data-icon="dir-icon-sort-alpha-desc">sort-alpha-desc</option>
						<option data-icon="dir-icon-sort-amount-asc">sort-amount-asc</option>
						<option data-icon="dir-icon-sort-amount-desc">sort-amount-desc</option>
						<option data-icon="dir-icon-sort-asc">sort-asc</option>
						<option data-icon="dir-icon-sort-desc">sort-desc</option>
						<option data-icon="dir-icon-sort-down">sort-down</option>
						<option data-icon="dir-icon-sort-numeric-asc">sort-numeric-asc</option>
						<option data-icon="dir-icon-sort-numeric-desc">sort-numeric-desc</option>
						<option data-icon="dir-icon-sort-up">sort-up</option>
						<option data-icon="dir-icon-soundcloud">soundcloud</option>
						<option data-icon="dir-icon-space-shuttle">space-shuttle</option>
						<option data-icon="dir-icon-spinner">spinner</option>
						<option data-icon="dir-icon-spoon">spoon</option>
						<option data-icon="dir-icon-spotify">spotify</option>
						<option data-icon="dir-icon-square">square</option>
						<option data-icon="dir-icon-square-o">square-o</option>
						<option data-icon="dir-icon-stack-exchange">stack-exchange</option>
						<option data-icon="dir-icon-stack-overflow">stack-overflow</option>
						<option data-icon="dir-icon-star">star</option>
						<option data-icon="dir-icon-star-half">star-half</option>
						<option data-icon="dir-icon-star-half-o">star-half-o</option>
						<option data-icon="dir-icon-star-half-full">star-half-full</option>
						<option data-icon="dir-icon-star-half-o">star-half-o</option>
						<option data-icon="dir-icon-star-o">star-o</option>
						<option data-icon="dir-icon-steam">steam</option>
						<option data-icon="dir-icon-steam-square">steam-square</option>
						<option data-icon="dir-icon-step-backward">step-backward</option>
						<option data-icon="dir-icon-step-forward">step-forward</option>
						<option data-icon="dir-icon-stethoscope">stethoscope</option>
						<option data-icon="dir-icon-stop">stop</option>
						<option data-icon="dir-icon-strikethrough">strikethrough</option>
						<option data-icon="dir-icon-stumbleupon">stumbleupon</option>
						<option data-icon="dir-icon-stumbleupon-circle">stumbleupon-circle</option>
						<option data-icon="dir-icon-subscript">subscript</option>
						<option data-icon="dir-icon-suitcase">suitcase</option>
						<option data-icon="dir-icon-sun-o">sun-o</option>
						<option data-icon="dir-icon-superscript">superscript</option>
						<option data-icon="dir-icon-support">support</option>
						<option data-icon="dir-icon-table">table</option>
						<option data-icon="dir-icon-tablet">tablet</option>
						<option data-icon="dir-icon-tachometer">tachometer</option>
						<option data-icon="dir-icon-tag">tag</option>
						<option data-icon="dir-icon-tags">tags</option>
						<option data-icon="dir-icon-tasks">tasks</option>
						<option data-icon="dir-icon-taxi">taxi</option>
						<option data-icon="dir-icon-tencent-weibo">tencent-weibo</option>
						<option data-icon="dir-icon-terminal">terminal</option>
						<option data-icon="dir-icon-text-height">text-height</option>
						<option data-icon="dir-icon-text-width">text-width</option>
						<option data-icon="dir-icon-th">th</option>
						<option data-icon="dir-icon-th-large">th-large</option>
						<option data-icon="dir-icon-th-list">th-list</option>
						<option data-icon="dir-icon-thumb-tack">thumb-tack</option>
						<option data-icon="dir-icon-thumbs-down">thumbs-down</option>
						<option data-icon="dir-icon-thumbs-o-down">thumbs-o-down</option>
						<option data-icon="dir-icon-thumbs-o-up">thumbs-o-up</option>
						<option data-icon="dir-icon-thumbs-up">thumbs-up</option>
						<option data-icon="dir-icon-ticket">ticket</option>
						<option data-icon="dir-icon-times">times</option>
						<option data-icon="dir-icon-times-circle">times-circle</option>
						<option data-icon="dir-icon-times-circle-o">times-circle-o</option>
						<option data-icon="dir-icon-tint">tint</option>
						<option data-icon="dir-icon-toggle-down">toggle-down</option>
						<option data-icon="dir-icon-toggle-left">toggle-left</option>
						<option data-icon="dir-icon-toggle-off">toggle-off</option>
						<option data-icon="dir-icon-toggle-on">toggle-on</option>
						<option data-icon="dir-icon-toggle-right">toggle-right</option>
						<option data-icon="dir-icon-toggle-up">toggle-up</option>
						<option data-icon="dir-icon-trash">trash</option>
						<option data-icon="dir-icon-trash-o">trash-o</option>
						<option data-icon="dir-icon-tree">tree</option>
						<option data-icon="dir-icon-trello">trello</option>
						<option data-icon="dir-icon-trophy">trophy</option>
						<option data-icon="dir-icon-truck">truck</option>
						<option data-icon="dir-icon-try">try</option>
						<option data-icon="dir-icon-tty">tty</option>
						<option data-icon="dir-icon-tumblr">tumblr</option>
						<option data-icon="dir-icon-tumblr-square">tumblr-square</option>
						<option data-icon="dir-icon-turkish-lira">turkish-lira</option>
						<option data-icon="dir-icon-twitch">twitch</option>
						<option data-icon="dir-icon-twitter">twitter</option>
						<option data-icon="dir-icon-twitter-square">twitter-square</option>
						<option data-icon="dir-icon-umbrella">umbrella</option>
						<option data-icon="dir-icon-underline">underline</option>
						<option data-icon="dir-icon-undo">undo</option>
						<option data-icon="dir-icon-university">university</option>
						<option data-icon="dir-icon-unlink">unlink</option>
						<option data-icon="dir-icon-unlock">unlock</option>
						<option data-icon="dir-icon-unlock-alt">unlock-alt</option>
						<option data-icon="dir-icon-unsorted">unsorted</option>
						<option data-icon="dir-icon-upload">upload</option>
						<option data-icon="dir-icon-usd">usd</option>
						<option data-icon="dir-icon-user">user</option>
						<option data-icon="dir-icon-user-md">user-md</option>
						<option data-icon="dir-icon-users">users</option>
						<option data-icon="dir-icon-video-camera">video-camera</option>
						<option data-icon="dir-icon-vimeo-square">vimeo-square</option>
						<option data-icon="dir-icon-vine">vine</option>
						<option data-icon="dir-icon-vk">vk</option>
						<option data-icon="dir-icon-volume-down">volume-down</option>
						<option data-icon="dir-icon-volume-off">volume-off</option>
						<option data-icon="dir-icon-volume-up">volume-up</option>
						<option data-icon="dir-icon-warning">warning</option>
						<option data-icon="dir-icon-wechat">wechat</option>
						<option data-icon="dir-icon-weibo">weibo</option>
						<option data-icon="dir-icon-weixin">weixin</option>
						<option data-icon="dir-icon-wheelchair">wheelchair</option>
						<option data-icon="dir-icon-wifi">wifi</option>
						<option data-icon="dir-icon-windows">windows</option>
						<option data-icon="dir-icon-won">won</option>
						<option data-icon="dir-icon-wordpress">wordpress</option>
						<option data-icon="dir-icon-wrench">wrench</option>
						<option data-icon="dir-icon-xing">xing</option>
						<option data-icon="dir-icon-xing-square">xing-square</option>
						<option data-icon="dir-icon-yahoo">yahoo</option>
						<option data-icon="dir-icon-yelp">yelp</option>
						<option data-icon="dir-icon-yen">yen</option>
						<option data-icon="dir-icon-youtube">youtube</option>
						<option data-icon="dir-icon-youtube-play">youtube-play</option>
						<option data-icon="dir-icon-youtube-square">youtube-square</option>
					</select>
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<label for="color"> <?php echo JText::_('LNG_COLOR')?> </label>
					<input type="text" name="color" class="minicolors" id="colorpicker" value="<?php echo $this->item->color ?>" />
					<div class="clear"></div>
				</div>
				<div class="detail_box">
					<div class="form-detail req"></div>
					<label for="description_id"><?php echo JText::_('LNG_DESCRIPTION')?>  &nbsp;&nbsp;&nbsp;</label>
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
								$langContent = isset($this->translations[$lng])?$this->translations[$lng]:"";
								if($lng==JFactory::getLanguage()->getTag() && empty($langContent)){
									$langContent = $this->item->description;
								}
								echo "<textarea id='description_$lng' name='description_$lng' class='input_txt' cols='75' rows='10' maxLength='".CATEGORY_DESCRIPTIION_MAX_LENGHT."'>$langContent</textarea>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						} else { ?>
							<textarea name="description" id="description" class="input_txt validate[required]"  cols="75" rows="10"  maxLength="<?php echo CATEGORY_DESCRIPTIION_MAX_LENGHT?>" onkeyup="calculateLenght();"><?php echo $this->item->description ?></textarea>
							<div class="clear"></div>
							<div class="description-counter">	
								<input type="hidden" name="descriptionMaxLenght" id="descriptionMaxLenght" value="<?php echo CATEGORY_DESCRIPTIION_MAX_LENGHT?>" />	
								<label for="decriptionCounter">(Max. <?php echo CATEGORY_DESCRIPTIION_MAX_LENGHT?> characters).</label>
								<?php echo JText::_('LNG_REMAINING')?><input type="text" value="0" id="descriptionCounter" name="descriptionCounter">			
							</div>
					<?php } ?>
				</div>
				<div class="form-box">
					<h3> <?php echo JText::_('LNG_IMAGE');?></h3>
					<div class="form-upload-elem">
						<div class="form-upload">
							<input type="hidden" name="imageLocation" id="imageLocation" value="<?php echo $this->item->imageLocation?>">
							<input type="file" id="imageUploader" name="uploadfile" size="50">		
							<div class="clear"></div>
							<a href="javascript:removeLogo();"><?php echo JText::_("LNG_REMOVE")?></a>
						</div>					
					</div>
					<div class="picture-preview" id="picture-preview">
						<?php
							if(!empty($this->item->imageLocation)) {
								echo "<img  id='itemImg' src='".JURI::root().PICTURES_PATH.$this->item->imageLocation."'/>";
							}
						?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="form-box">
					<h3><?php echo JText::_('LNG_MARKER');?></h3>
					<div class="form-upload-elem">
						<div class="form-upload">
							<input type="hidden" name="markerLocation" id="markerLocation" value="<?php echo $this->item->markerLocation ?>">
							<input type="file" id="markerfile" name="markerfile" size="50">		
							<div class="clear"></div>
							<a href="javascript:removeMarker();"><?php echo JText::_("LNG_REMOVE")?></a>
						</div>					
					</div>
					
					<div class="picture-preview" id="marker-preview">
						<?php 
						if(!empty($this->item->markerLocation)) {
							echo "<img id='markerImg' src='".JURI::root().PICTURES_PATH.$this->item->markerLocation."'/>";
						} ?>
					</div>
					<div class="clear"></div>
				</div>	
			</div>
		</fieldset>
		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" /> 
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->item->id ?>" /> 
		<input type="hidden" name="view" id="view" value="company" />
		<input type="hidden" name="type" value="<?php echo $this->typeSelected ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>

<?php include JPATH_COMPONENT_SITE.'/assets/uploader.php'; ?>

<script>
	jQuery(document).ready(function() {
		if(jQuery("#descriptionCounter").val())
			jQuery("#descriptionCounter").val(parseInt(jQuery("#description").attr('maxlength')) - jQuery("#description").val().length);

		jQuery("#icon-holder").val("<?php echo $this->item->icon ?>");
		
		jQuery("#icon-holder").chosenIcon({
			disable_search_threshold: 10
		});

		jQuery("#parent_id").chosen({
			disable_search_threshold: 10
		});

		jQuery("#type").chosen({
			disable_search_threshold: 10
		});
	});

	function validateCmpForm() {
		var isError = jQuery("#item-form").validationEngine('validate');
		return !isError;
	}

	function calculateLenght(){
		var obj = jQuery("#description");
		var max = parseInt(obj.attr('maxlength'));

		if(obj.val().length > max){
			obj.val(obj.val().substr(0, obj.attr('maxlength')));
		}

		jQuery("#descriptionCounter").val((max - obj.val().length));
	}

	var categoryFolder = '<?php echo CATEGORY_PICTURES_PATH ?>';
	var categoryFolderPath = '<?php echo JURI::root()?>components/<?php echo JBusinessUtil::getComponentName()?>/assets/upload.php?t=<?php echo strtotime("now")?>&picture_type=<?php echo PICTURE_TYPE_LOGO?>&_root_app=<?php echo urlencode(JPATH_ROOT."/".PICTURES_PATH) ?>&_target=<?php echo urlencode(CATEGORY_PICTURES_PATH)?>';
	var removePath = '<?php echo JURI::root()?>/components/<?php echo JBusinessUtil::getComponentName()?>/assets/remove.php?_root_app=<?php echo urlencode(JPATH_COMPONENT_SITE)?>&_filename=';

	imageUploader(categoryFolder, categoryFolderPath);
	markerUploader(categoryFolder, categoryFolderPath);
</script>