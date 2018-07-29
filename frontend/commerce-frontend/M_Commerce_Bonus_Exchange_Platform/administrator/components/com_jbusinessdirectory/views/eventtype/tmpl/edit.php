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

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		var defaultLang="<?php echo JFactory::getLanguage()->getTag() ?>";

		jQuery("#item-form").validationEngine('detach');
		var evt = document.createEvent("HTMLEvents");
		evt.initEvent("click", true, true);
		var tab = ("tab-"+defaultLang);
		if(!(document.getElementsByClassName(tab)[0] === undefined || document.getElementsByClassName(tab)[0] === null))
			document.getElementsByClassName(tab)[0].dispatchEvent(evt);
		if (task == 'eventtype.cancel' || !validateCmpForm()) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		jQuery("#item-form").validationEngine('attach');
	}
</script>

<?php
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

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=eventtype');?>" method="post" name="adminForm" id="item-form">

	<fieldset class="adminform">
		<legend><?php echo JText::_('LNG_EVENT_TYPE'); ?></legend>
		
		<TABLE class="admintable"  border=0>
			<TR>
				<TD width=10% nowrap class="key"><?php echo JText::_('LNG_NAME'); ?> :</TD>
				<TD nowrap width=1% align=left>
					<!--<input
						type		= "text"
						name		= "name"
						id			= "name"
						value		= '<?php /* echo $this->item->name */ ?>'
						size		= 32
						maxlength	= 128
						AUTOCOMPLETE=OFF
					/>-->
					<?php
					if($this->appSettings->enable_multilingual){
						echo JHtml::_('tabs.start', 'tab_groupsd_id', $options);
						foreach( $this->languages as $k=>$lng ){
							echo JHtml::_('tabs.panel', $lng, 'tab-'.$lng );
							$langContent = isset($this->translations[$lng."_name"])?$this->translations[$lng."_name"]:"";
							if($lng == JFactory::getLanguage()->getTag() && empty($langContent)){
								$langContent = $this->item->name;
							}
							echo "<input type='text' name='name_$lng' id='name_$lng' class='input_txt validate[required]' value=\"".stripslashes($langContent)."\"  maxLength='100'>";
							echo "<div class='clear'></div>";
						}
						echo JHtml::_('tabs.end');
					} else { ?>
						<input type="text" name="name" id="name" class="input_txt validate[required]" value="<?php echo $this->item->name ?>"  maxLength="100">
					<?php } ?>
				</TD>
				<TD>&nbsp;</TD>
			</TR>
			<tr>
				<td class="key"><?php echo JText::_('LNG_ID'); ?></td>
				<td><?php echo $this->item->id ?></td>
				<TD>&nbsp;</TD>
			</tr>	
		</TABLE>
	</fieldset>
	
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
	<?php echo JHTML::_( 'form.token' ); ?> 
</form>
<script>
	function validateCmpForm() {
		var isError = jQuery("#item-form").validationEngine('validate');
		return !isError;
	}
</script>