
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
	$return = base64_encode(('index.php?option=com_jbusinessdirectory&view=managecompanyoffers'));
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

<div class="button-row right">
	<?php 
	if ($appSettings->max_offers > $this->total) { ?>
		<button type="submit" class="ui-dir-button ui-dir-button-green" onclick="addOffer()">
			<span class="ui-button-text"><i class="dir-icon-plus-sign"></i> <?php echo JText::_("LNG_ADD_NEW_OFFER")?></span>
		</button>
	<?php } else {
		JError::raiseNotice( 100, JText::_('LNG_MAX_OFFERS_REACHED') );
	} ?>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyoffer');?>" method="post" name="offerForm" id="offerForm">
	<div id="editcell">
		<table class="dir-table dir-panel-table">
			<thead>
				<tr>
					<th align='center'><?php echo JText::_('LNG_SUBJECT'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_PRICE'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_SPECIAL_PRICE'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_START_DATE'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' align='center'><?php echo JText::_('LNG_END_DATE'); ?></th>
					<th width='10%'><?php echo JText::_("LNG_STATUS")?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$nrcrt = 1;
				if(!empty($this->items)){
					foreach($this->items as $offer) { ?>
						<tr class="row<?php echo $nrcrt%2 ?>"
							
							>
							<td align="left">
								<div class="row-fluid">
									<div class="item-image text-center">
										<?php if (!empty($offer->picture_path)) { ?>
											<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyoffer.edit&'.JSession::getFormToken().'=1&id='. $offer->id )?>'>
												<img 
													src="<?php echo JURI::root().PICTURES_PATH.$offer->picture_path ?>" 
													class="img-circle"
												/>
											</a>
										<?php } else { ?>
											<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=managecompanyoffer.edit&'.JSession::getFormToken().'=1&id='. $offer->id ) ?>">
												<img 
													src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>" 
													class="img-circle"
												/>
											</a>
										<?php } ?>
									</div>
									<div class="item-name text-left">
										<div class="row-fluid">
											<a href='<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=managecompanyoffer.edit&'.JSession::getFormToken().'=1&id='.$offer->id )?>'
												title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> 
												<B><?php echo $offer->subject?></B>
											</a>
										</div>
			
										<div class="row-fluid">
											<a href='<?php echo JBusinessUtil::getOfferLink($offer->id, $offer->alias) ?>' target='_blank'
												title="<?php echo JText::_('LNG_CLICK_TO_VIEW'); ?>" class="btn btn-xs btn-primary btn-panel"> 
												<?php echo JText::_('LNG_VIEW'); ?>
											</a>
											<a href="javascript:void(0);" onclick="editOffer(<?php echo $offer->id ?>)"
												title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>" class="btn btn-xs btn-success btn-panel">
												<?php echo JText::_("LNG_EDIT") ?>
											</a>
											<a href="javascript:void(0);" onclick="deleteOffer(<?php echo $offer->id ?>)" 
												title="<?php echo JText::_('LNG_CLICK_TO_DELETE'); ?>" class="btn btn-xs btn-danger btn-panel">
												<?php echo JText::_("LNG_DELETE")?>
											</a>
											<?php if($offer->approved == 1) { ?>
												<a onclick="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyoffer.chageState&id='. $offer->id )?> '"
													title="<?php echo JText::_('LNG_CLICK_TO_CHANGE_STATE'); ?>"
													<?php 
														if($offer->state==0) 
															echo 'class="btn btn-xs btn-info"'; 
														else 
															echo 'class="btn btn-xs btn-warning"'; 
													?>
												>
													<?php 
														if($offer->state==0) 
															echo JText::_('LNG_ACTIVATE'); 
														else 
															echo JText::_('LNG_DEACTIVATE'); 
													?>
												</a>
											<?php } ?>
										</div>
									</div>
								</div>
							</td>
							<td class="hidden-xs hidden-phone">
								<?php echo $offer->price?>
							</td>
							<td class="hidden-xs hidden-phone">
								<?php echo $offer->specialPrice ?>
							</td>
							<td class="hidden-xs hidden-phone">
								<?php echo JBusinessUtil::getDateGeneralFormat( $offer->startDate); ?>
							</td>
							<td class="hidden-xs hidden-phone">
								<?php echo JBusinessUtil::getDateGeneralFormat( $offer->endDate); ?>
							</td>
							<td valign="top" align="center">
								<?php if(($offer->state == 1) && ($offer->approved == 1)) {
									if($offer->expired)
										echo '<span class="status-btn status-btn-warning">'.JText::_("LNG_EXPIRED").'</span>';
									elseif(!$offer->allow_offers)
										echo '<span class="status-btn status-btn-warning warn">'.JText::_("LNG_NOT_INCLUDED").'</span>';
									else
										echo '<span class="status-btn status-btn-success">'.JText::_("LNG_PUBLISHED").'</span>';
								} else {
									switch($offer->approved) {
										case -1:
											echo '<span class="status-btn status-btn-danger">'.JText::_("LNG_DISAPPROVED").'</span>';
											break;
										case 0:
											echo '<span class="status-btn status-btn-info">'.JText::_("LNG_PENDING").'</span>';
											break;
										case 1:
											echo '<span class="status-btn status-btn-primary">'.JText::_("LNG_DEACTIVATED").'</span>';
											break;
									}
								} ?>
							</td>
						</tr>
				<?php } 
				}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="10">
						<a href="#" id="open_legend">
							<h5 class="right"><?php echo JText::_('LNG_STATUS_MESSAGES_LEGEND'); ?></h5>
						</a>
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="option"	value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" id="task" value="" /> 
	<input type="hidden" name="id" id="id" value="" />
	<input type="hidden" name="Itemid" id="Itemid" value="163" />
	<input type="hidden" name="companyId" id="companyId" value="<?php echo $this->companyId ?>" />	
	<?php echo JHTML::_('form.token'); ?> 
</form>
<div class="clear"></div>

<!-- Modal -->
<div id="legend" style="display:none;">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span title="Cancel" class="dialogCloseButton" onclick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<div class="row-fluid">
				<div class="row-fluid">
					<div class="span10 offset1">
						<dl class="dl-horizontal">
							<dt><span class="status-btn status-btn-success"><?php echo JText::_('LNG_PUBLISHED'); ?></span></dt>
							<dd><?php echo JText::_('LNG_PUBLISHED_LEGEND'); ?></dd>
							<dt><span class="status-btn status-btn-primary"><?php echo JText::_('LNG_DEACTIVATED'); ?></span></dt>
							<dd><?php echo JText::_('LNG_DEACTIVATED_LEGEND'); ?></dd>
							<dt><span class="status-btn status-btn-info"><?php echo JText::_('LNG_PENDING'); ?></span></dt>
							<dd><?php echo JText::_('LNG_PENDING_LEGEND'); ?></dd>
							<?php if($this->appSettings->enable_packages){?>
								<dt><span class="status-btn status-btn-warning"><?php echo JText::_('LNG_EXPIRED'); ?></span></dt>
								<dd><?php echo JText::_('LNG_EXPIRED_LEGEND'); ?></dd>
							<?php } ?>
							<dt><span class="status-btn status-btn-warning warn"><?php echo JText::_('LNG_NOT_INCLUDED'); ?></span></dt>
							<dd><?php echo JText::_('LNG_NOT_INCLUDED_LEGEND'); ?></dd>
							<dt><span class="status-btn status-btn-danger"><?php echo JText::_('LNG_DISAPPROVED'); ?></span></dt>
							<dd><?php echo JText::_('LNG_DISAPPROVED_LEGEND'); ?></dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function editOffer(offerId){
		jQuery("#id").val(offerId);
		jQuery("#task").val("managecompanyoffer.edit");
		jQuery("#offerForm").submit();
	}

	function addOffer(){
		jQuery("#id").val(0);
		jQuery("#task").val("managecompanyoffer.add");
		jQuery("#offerForm").submit();
	}

	function deleteOffer(offerId){
		if(confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_OFFERS_CONFIRM_DELETE', true);?>')){
			jQuery("#id").val(offerId);
			jQuery("#task").val("managecompanyoffers.delete");
			jQuery("#offerForm").submit();
		}
	}

	jQuery(document).ready(function() {
		jQuery('#open_legend').click(function() {
			jQuery.blockUI({ message: jQuery('#legend'), css: {width: 'auto', top: '25%', left:"0", position:"absolute", cursor:'default'} });
			jQuery('.blockUI.blockMsg').center();
			jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI);
			jQuery(document).scrollTop( jQuery("#legend").offset().top );
			jQuery("html, body").animate({ scrollTop: 0}, "slow");

			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
		});
	});
</script>