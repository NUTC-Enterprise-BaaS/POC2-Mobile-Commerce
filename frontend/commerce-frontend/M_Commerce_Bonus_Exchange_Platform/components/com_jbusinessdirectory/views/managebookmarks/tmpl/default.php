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

$user = JFactory::getUser();
if($user->id == 0 || (!$this->actions->get('directory.access.bookmarks') && $this->appSettings->front_end_acl)){
	$app = JFactory::getApplication();
	$return = base64_encode(('index.php?option=com_jbusinessdirectory&view=managebookmarks'));
	$app->redirect(JRoute::_('index.php?option=com_users&return='.$return,false));
}

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.multiselect');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managebookmarks');?>" method="post" name="adminForm" id="adminForm">
	<table class="dir-table dir-panel-table" id="itemList">
		<thead>
			<tr>
				<th align="left" width="35%"><?php echo JText::_("LNG_BOOKMARK")?></th>
				<th align="left" width="65%"><?php echo JText::_("LNG_NOTE")?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $this->items as $bookmark){?>
				<tr>
					<td align="left">
						<div class="row-fluid">
							<div class="span12">
								<a target="_blank" href="<?php echo JBusinessUtil::getCompanyLink($bookmark)?>">
									<?php echo $bookmark->name ?>
								</a>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<a href="javascript:deleteBookmark(<?php echo $bookmark->bookmarkId ?>)"
									title="<?php echo JText::_('LNG_CLICK_TO_DELETE'); ?>" class="btn btn-xs btn-danger btn-panel">
									<?php echo JText::_("LNG_DELETE")?>
								</a>
							</div>
						</div>
					</td>
					<td>
						<span><?php echo $bookmark->note ?></span>
					</td>
				</tr>
			<?php }	?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="11">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" id="task" value="" /> 
	<input type="hidden" name="bookmarkId" value="" />
	<input type="hidden" id="cid" name="cid" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHTML::_('form.token'); ?> 
</form>

<script>
	function deleteBookmark(id) {
		if(confirm("<?php echo JText::_('LNG_ARE_YOU_SURE_YOU_WANT_TO_DELETE', true);?>")){
			jQuery("#cid").val(id);
			jQuery("#task").val("managebookmarks.delete");
			jQuery("#adminForm").submit();
		}
	}
</script>