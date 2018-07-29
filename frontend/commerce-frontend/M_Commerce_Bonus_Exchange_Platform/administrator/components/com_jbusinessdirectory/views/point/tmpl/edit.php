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
	if (task == 'point.cancel' || !validateCmpForm()) {
		Joomla.submitform(task, document.getElementById('item-form'));
	}
	jQuery("#item-form").validationEngine('attach');
}
</script>

<div id="j-main-container">
	<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-horizontal">

		<div class="clr mandatory oh">
			<p><?php echo JText::_("使用者個人消費紀錄")?></p>
		</div>
			<h2> <?php echo JText::_('Rcord');?></h2>
			<div class="clr clearfix">You will find a list of points which the user has earned or lost from the listing below</div>
    <table class="table table-striped adminlist" id="itemList">
        <thead>
            <tr>
                <th width="1%" class="hidden-phone">#</th>
                <th width="1%" class="hidden-phone"></th>
                <th nowrap="nowrap" width='15%' >UserId</th>
                <th class="hidden-phone" nowrap="nowrap" width='23%' >Achievement</th>

                <th nowrap="nowrap" width='20%' >Reason</th>
                <th nowrap="nowrap" width='20%' >Data</th>
               
            </tr>
        </thead>
				<?php //拿取#__social_points_history資料表資料
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select($db->quoteName(array('id','user_id','points','message','created')))
						->from($db->quoteName('#__social_points_history').'AS p');
					$db->setQuery($query);
					$result = $db->loadObjectList();
					
					foreach ($result as $results) {
						$IdPoint=array();
						$message=array();
						$created=array();
						$user_id = $results->user_id;
						$user_points = $results->points;
						$user_message = $results->message;
						$user_created = $results->created;
					 	$abc = $_GET['id'];
					 	if ($user_id==$abc)
					 		$IdPoint[$user_id] = $user_points;
					 		$message[$user_id] = $user_message;
					 		$created[$user_id] = $user_created;

					 		$nrcrt = 1; $i=0;
					           	while($element = current($IdPoint)) {
					                ?>
									<TR class="row<?php echo $i; ?>">
				                    <TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
				                    <TD align="center" class="hidden-phone"></TD>
				                    <TD align="left"><?php echo key($IdPoint) ?></TD>
				                    <td class="hidden-phone">
				                        <?php 
				                        	if ($element<0) {
				                        		echo str_pad("Lost",5).str_pad($element,8)."points";
				                        	}else
				                        		echo str_pad("Earned",8).str_pad($element,8)."points";
				                        ?> 
				                    </td>
				                    <td>
				                        <?php echo $message[key($IdPoint)];?>
				                    </td>
				                    <td>
				                        <?php echo $created[key($IdPoint)];?>
				                    </td>		
				                	</TR>
					            <?php
					            $i++;
					            next($IdPoint);
					            } 
					}
				?>
				<tr>
				</tr>

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
