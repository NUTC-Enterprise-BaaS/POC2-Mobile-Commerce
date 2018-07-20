
<?php /*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

$user = JFactory::getUser();

if($user->id == 0 || (!$this->actions->get('directory.access.offers') && $appSettings->front_end_acl)){
	$app = JFactory::getApplication();
	$return = base64_encode(('index.php?option=com_jbusinessdirectory&view=managecompanyoffercoupons'));
	$app->redirect(JRoute::_('index.php?option=com_users&return='.$return,false));
}

$isProfile = true;
?>
<script>
	var isProfile = true;
</script>
<style>
#header-box, #control-panel-link {
	display: none;
}
</style>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffercoupon');?>" method="post" name="couponForm" id="couponForm">
	<div id="editcell">
		<table class="dir-table dir-panel-table">
			<thead>
				<tr>
					<th align='center'><?php echo JText::_('LNG_COUPON'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_OFFER'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_COMPANY'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_GENERATED_TIME'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_EXPIRATION_TIME'); ?></th>
					<th width='10%'><?php echo JText::_("LNG_PDF"); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$nrcrt = 1;
				if(!empty($this->items)){
					foreach($this->items as $coupon) { ?>
						<tr class="row<?php echo $nrcrt%2 ?>">
							<td align="left">
								<div class="row-fluid">
									<div class="item-image text-left">
										<b><?php echo strtoupper($coupon->code); ?></b>
									</div>
								</div>
								<div class="row-fluid">
									<a href="javascript:void(0);" onclick="deleteCoupon(<?php echo $coupon->id ?>)" 
										title="<?php echo JText::_('LNG_CLICK_TO_DELETE'); ?>" class="btn btn-xs btn-danger btn-panel">
										<?php echo JText::_("LNG_DELETE")?>
									</a>
								</div>
							</td>
							<td class="hidden-xs hidden-phone">
								<div class="item-image text-left">
									<?php echo $coupon->offer; ?>
								</div>
							</td>
							<td class="hidden-xs hidden-phone">
								<div class="item-name text-left">
									<?php echo $coupon->company; ?>
								</div>
							</td>
							<td class="hidden-xs hidden-phone">
								<div class="item-name text-left">
									<?php echo JBusinessUtil::getDateGeneralFormat($coupon->generated_time); ?>
								</div>
							</td>
							<td class="hidden-xs hidden-phone">
								<div class="item-name text-left">
									<?php echo JBusinessUtil::getDateGeneralFormat($coupon->expiration_time); ?>
								</div>
							</td>
							<td>
								<div class="item-name text-left">
									<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyoffercoupon.show&id='. $coupon->id )?>'
										title="<?php echo JText::_('LNG_CLICK_TO_VIEW'); ?>" class="btn btn-xs btn-primary btn-panel" 
										target="_blank">
										<?php echo JText::_("LNG_VIEW")?>
									</a>
								</div>
							</td>
						</tr>
				<?php } 
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" id="task" value="" /> 
	<input type="hidden" name="id" id="id" value="" />
	<input type="hidden" name="Itemid" id="Itemid" />
	<?php echo JHTML::_('form.token'); ?> 
</form>
<div class="clear"></div>

<script>
	function deleteCoupon(couponId) {
		if(confirm('<?php echo JText::_("COM_JBUSINESS_DIRECTORY_COUPONS_CONFIRM_DELETE", true);?>')) {
			jQuery("#id").val(couponId);
			jQuery("#task").val("managecompanyoffercoupons.delete");
			jQuery("#couponForm").submit();
		}
	}
</script>