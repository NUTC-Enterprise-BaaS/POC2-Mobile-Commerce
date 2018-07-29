<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=canned&tmpl=component'); ?>" method="get" id="cannedForm">

<?php echo FSS_Helper::PageStylePopup(true); ?>
<?php echo FSS_Helper::PageTitlePopup('SUPPORT_ADMIN',"CANNED_REPLIES"); ?>


<div class="fss_spacer"></div>

<table class="table table-bordered table-condensed table-striped">
	<thead>
	<tr>
		<th style="text-align:left;"><?php echo JText::_('Description'); ?></th>	
		<th><?php echo JText::_('GROUPING'); ?></th>
		<th width="1%"></th>
	</tr>
	</thead>
	<tbody>
		<?php foreach ($this->canned as $canned): ?>
			<tr>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=canned&cannedid=' . $canned->id); ?>"><?php echo $canned->description; ?></a>
				</td>
				<td>
					<?php echo $canned->grouping; ?>
				</td>
				<td nowrap>	
					<a class="btn btn-default btn-mini" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=canned&cannedid=' . $canned->id); ?>"><?php echo JText::_('EDIT'); ?></a>
					<a class="btn btn-default btn-mini" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=canned&deleteid=' . $canned->id); ?>"><?php echo JText::_('DELETE'); ?></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
</div>

<div class="modal-footer">
	<div class='pull-left'>
		<input placeholder='<?php echo JText::_('SEARCH'); ?>' type='text' name="search" value="<?php echo JRequest::getVar('search'); ?>" style='margin-bottom: 0;'/>
		<a class="btn btn-default" href="#" onclick='jQuery("#cannedForm").submit();return false;'><?php echo JText::_('SEARCH'); ?></a>
	</div>
	<a class="btn btn-success" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=canned&cannedid=-1'); ?>"><?php echo JText::_('JNEW'); ?></a>
	<a href='#' class="btn btn-default" onclick='parent.cannedRefresh(); parent.fss_modal_hide(); return false;'><?php echo JText::_('Done'); ?></a>
</div>

</form>
