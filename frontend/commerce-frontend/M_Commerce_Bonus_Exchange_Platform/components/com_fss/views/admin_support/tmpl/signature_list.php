<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_Helper::PageStylePopup(true); ?>
<?php echo FSS_Helper::PageTitlePopup('SUPPORT_ADMIN',"Signatures"); ?>


<div class="fss_spacer"></div>

<table class="table table-bordered table-condensed table-striped">
	<thead>
	<tr>
		<th style="text-align:left;"><?php echo JText::_('Description'); ?></th>	
		<th width="1%"><?php echo JText::_('DEFAULT'); ?></th>
		<th width="1%"><?php echo JText::_('PERSONAL'); ?></th>
		<th width="1%"></th>
	</tr>
	</thead>
	<tbody>
		<?php foreach ($this->sigs as $sig): ?>
			<tr>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=signature&sigid=' . $sig->id); ?>"><?php echo $sig->description; ?></a>
				</td>
				<td>
					<?php if ($sig->default): ?>
						<a href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&layout=signature&task=signature.setdefault&sigid=0'); ?>">
							<i class="icon-ok"></i>
						</a>
					<?php else: ?>
						<a href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&layout=signature&task=signature.setdefault&sigid=' . $sig->id); ?>">
							<i class="icon-cancel"></i>
						</a>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($sig->personal): ?>
						<p class="text-success margin-none"><i class="icon-ok"></i></p>
					<?php else: ?>
						<p class="text-error margin-none"><i class="icon-cancel"></i></p>
					<?php endif; ?>
				</td>
				<td nowrap>	
					<a class="btn btn-default btn-mini" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=signature&sigid=' . $sig->id); ?>"><?php echo JText::_('EDIT'); ?></a>
					<a class="btn btn-default btn-mini" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=signature&task=signature.delete&deleteid=' . $sig->id); ?>"><?php echo JText::_('DELETE'); ?></a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

</div>

<div class="modal-footer">
	<a class="btn btn-success" href="<?php echo JRoute::_('index.php?option=com_fss&view=admin_support&tmpl=component&layout=signature&sigid=-1'); ?>"><?php echo JText::_('JNEW'); ?></a>
	<a href='#' class="btn btn-default" onclick='parent.sigsRefresh(); parent.fss_modal_hide(); return false;'><?php echo JText::_('DONE'); ?></a>
</div>
	