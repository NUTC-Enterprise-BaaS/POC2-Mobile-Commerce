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
	if (task == 'spepoint.cancel' || !validateCmpForm()) {
		Joomla.submitform(task, document.getElementById('item-form'));
	}
	jQuery("#item-form").validationEngine('attach');
}
</script>

<div id="j-main-container">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">

		<div class="clr mandatory oh">
			<p><?php echo JText::_("特約店家 發送點數記錄")?></p>
		</div>
			<h2> <?php echo JText::_('Rcord');?></h2>
			<div class="clr clearfix">You will find a list of points which the user has lost from the listing below</div>
    <table class="table table-striped adminlist" id="itemList">
        <thead>
            <tr>
                <th width="1%" class="hidden-phone"></th>
                <th nowrap="nowrap" width='10%' >編號</th>
                <th class="hidden-phone" nowrap="nowrap" width='23%' >點數</th>
                <th nowrap="nowrap" width='30%' >事由</th>
                <th nowrap="nowrap" width='10%' >狀態</th>
                <th nowrap="nowrap" width='20%' >時間</th>
            </tr>
        </thead>
			<?php
				//取出特約店家發送點數紀錄
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query
					->select(array('a.id', 'a.user_id', 'a. points', 'a.message', 'a.created', 'a.state'))
					->from($db->quoteName('#__spe_points_history', 'a'))
					->where($db->quoteName('a.user_id') . ' = ' . $this->item->userId);
				$db->setQuery($query);
				$points = $db->loadObjectList();
				foreach ($points as $key => $point) {
			?>
		<tr>
			<td class="center hidden-phone"></td>
			<td align="center" class="hidden-phone"><?php echo $point->id ?></td>
			<td align="left"><?php echo '發出 ' . abs($point->points) . ' 點數' ?></td>
			<td class="hidden-phone">
				<?php echo $point->message ?>
			</td>
			<td>
				<?php if ($point->state == 1) {
					echo '未繳款';
				} else {
					echo '已繳款';
				} ?>
			</td>
			<td>
				<?php echo $point->created ?>
			</td>
		</tr>
		<?php } ?>
	</table>

		<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="view" id="view" value="package" />
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
