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

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
?>

<?php
$appSetings = JBusinessUtil::getInstance()->getApplicationSettings();
$user = JFactory::getUser();
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

<script>

Joomla.submitbutton = function(task) {

	var defaultLang="<?php echo JFactory::getLanguage()->getTag() ?>";

	jQuery("#item-form").validationEngine('detach');
	var evt = document.createEvent("HTMLEvents");
	evt.initEvent("click", true, true);
	var tab = ("tab-"+defaultLang);
	if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
		document.getElementsByClassName(tab)[0].dispatchEvent(evt);
	if (task == 'package.cancel' || !validateCmpForm()) {
		Joomla.submitform(task, document.getElementById('item-form'));
	}
	jQuery("#item-form").validationEngine('attach');
}
</script>

<div class="category-form-container">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">
		<div class="clr mandatory oh">
			<p><?php echo JText::_("LNG_REQUIRED_INFO")?></p>
		</div>
		<fieldset class="boxed">

			<h2> <?php echo JText::_('會員詳細資訊');?></h2>
			<p><?php echo JText::_('此資訊詳請將顯示在您的會員資料');?></p>
			<div class="form-box">
				<div class="detail_box" id="name_input">
					<div  class="form-detail req"></div>
					<label for="subject"><?php echo JText::_('會員名稱')?> </label>
					<?php
						if($this->appSettings->enable_multilingual){
							echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
							foreach( $this->languages as $k=>$lng ){
								echo JHtml::_('tabs.panel', $lng, 'tab-'.$lng);
								$langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
								if($lng==JFactory::getLanguage()->getTag() && empty($langContent)){
									$langContent = $this->item->name;
								}
								echo "<input type='text' name='name_$lng' id='name_$lng' class='input_txt validate[required]' value=\"".stripslashes($langContent)."\"  maxLength='100'>";
								echo "<div class='clear'></div>";
							}
							echo JHtml::_('tabs.end');
						} else { ?>
							<input type="text" name="name" id="name" class="input_txt" value="<?php echo $this->item->name ?>">
						<?php } ?>
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="username"><?php echo JText::_('帳號')?> </label>
					<input type="text"
						name="username" id="username" class="input_txt"
						value="<?php echo $this->item->username ?>" disabled>
					<div class="clear"></div>
				</div>
				<?php
					//取出電話
					$db = JFactory::getDbo();
		           	$query = $db->getQuery(true);
		           	$query->select($db->quoteName(array('user_id', 'profile_key', 'profile_value', 'ordering')));
				   	$query->from($db->quoteName('#__user_profiles'));
					$query->where($db->quoteName('user_id') . ' = ' . $db->quote($this->item->id));
					$query->where($db->quoteName('ordering') . ' = ' . $db->quote(2));
		            $db->setQuery($query);
		            $userProfiles = $db->loadObjectList();
				?>
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label for="phone"><?php echo JText::_('電話')?> </label>
					<input type="text"
						name="phone" id="phone" class="input_txt"
						value="<?php echo $userProfiles[0]->profile_value; ?>" disabled>
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label  id="email" for="email"><?php echo JText::_('Email')?> </label>
					<input type="text"	name="email" id="email" class="input_txt"	value="<?php echo $this->item->email ?>" disabled>
					<div class="clear"></div>
				</div>
				<?php
					//取出點數
		            $db = JFactory::getDbo();
			        $query = $db->getQuery(true);
			        $query
					   	->select(array('user_id', 'points'))
					   	->from($db->quoteName('#__social_points_history'))
						->where($db->quoteName('user_id') . ' = ' . $db->quote($this->item->id));
			        $db->setQuery($query);
			        $pointHistorys = $db->loadObjectList();
			        if (empty($pointHistorys)) {
			        	$num = 0;
			        } else {
			        	$num = 0;
				        foreach ($pointHistorys as $key => $pointHistory) {
				        	$points = $pointHistory->points;
				        	$num += $points;
				        }
				    }
				   //若為業務 取出 業務PV
				   $db = JFactory::getDbo();
		           $query = $db->getQuery(true);
		            $query
				    ->select(array('user_id', 'points'))
				    ->from($db->quoteName('#__business_points_history'))
				    ->where($db->quoteName('user_id') . ' = ' . $db->quote($this->item->id));
		            $db->setQuery($query);
		            $pvHistorys = $db->loadObjectList();
		            if (empty($pvHistorys)) {
			        	$numPv = 0;
			        } else {
			        	$numPv = 0;
				        foreach ($pvHistorys as $key => $pvHistory) {
				        	$pointsPv = $pvHistory->points;
				        	$numPv += $pointsPv;
				        }
				    }
				?>
				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label  id="points" for="points"><?php echo JText::_('點數')?> </label>
					<input type="text"	name="points" id="points" class="input_txt"	value="<?php echo $num ?>" disabled>
					<div class="clear"></div>
				</div>

				<div class="detail_box">
					<div  class="form-detail req"></div>
					<label  id="super_points" for="super_points"><?php echo JText::_('業務 PV')?> </label>
					<input type="text"	name="super_points" id="super_points" class="input_txt" value="<?php echo $numPv ?>" disabled>
					<div class="clear"></div>
				</div>
			</div>
			</fieldset>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<input type="hidden" name="view" id="view" value="user" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#item-form").validationEngine('attach');
		jQuery(".multiselect").multiselect();
		jQuery("#price").change(function(){
			if(jQuery(this).val() == 0 ){
				jQuery("#days").val(0);
			}
		});
	});
	function validateCmpForm() {
		var isError = jQuery("#item-form").validationEngine('validate');
		return !isError;
	}

</script>
