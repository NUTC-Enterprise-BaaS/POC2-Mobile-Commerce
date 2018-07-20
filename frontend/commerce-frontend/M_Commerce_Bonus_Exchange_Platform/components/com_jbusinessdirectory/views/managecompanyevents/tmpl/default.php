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
if($user->id == 0 || (!$this->actions->get('directory.access.events') && $appSettings->front_end_acl)){
	$app = JFactory::getApplication();
	$return = base64_encode(('index.php?option=com_jbusinessdirectory&view=managecompanyevents'));
	$app->redirect(JRoute::_('index.php?option=com_users&return='.$return,false));
}

$isProfile = true;
?>
<script>
	var isProfile = true;
</script>
<style>
#header-box, #control-panel-link{
	display: none;
}
</style>

<div class="button-row right">
	<?php 
	if ($appSettings->max_events > $this->total) { ?>
		<button type="submit" class="ui-dir-button ui-dir-button-green" onclick="addDirEvent()">
			<span class="ui-button-text"><i class="dir-icon-plus-sign"></i> <?php echo JText::_("LNG_ADD_NEW_EVENT")?></span>
		</button>
	<?php } else {
		JError::raiseNotice( 100, JText::_('LNG_MAX_EVENTS_REACHED') );
	} ?>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanyevents');?>" method="post" name="eventForm" id="adminForm">
	<div id="editcell">
		<table class="dir-table dir-panel-table">
			<thead>
				<tr>
					<th ><?php echo JText::_( 'LNG_NAME'); ?></th>
					<th class="hidden-xs hidden-phone" width='13%' ><?php echo JText::_( 'LNG_COMPANY'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' ><?php echo JText::_( 'LNG_START_DATE'); ?></th>
					<th class="hidden-xs hidden-phone" width='10%' ><?php echo JText::_( 'LNG_END_DATE'); ?></th>
					<th width='10%' class="hidden-xs hidden-phone"><?php echo JText::_( 'LNG_VIEW_NUMBER'); ?></th>
					<th width='10%' ><?php echo JText::_( 'LNG_STATUS'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$nrcrt=1;
				$i=0;
				if(!empty($this->items)){
					foreach($this->items as $event) { ?>
						<tr class="row<?php echo $i % 2; ?>"
							
							>
							<td align="left">
								<div class="row-fluid">
									<div class="item-image text-center">
										<?php if (!empty($event->picture_path)) { ?>
												<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyevent.edit&'.JSession::getFormToken().'=1&id='. $event->id )?>'>
													<img 
														src="<?php echo JURI::root().PICTURES_PATH.$event->picture_path ?>" 
														class="img-circle"
													/>
												</a>
										<?php } else { ?>
											<a href="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=managecompanyevent.edit&'.JSession::getFormToken().'=1&id='. $event->id ) ?>">
												<img 
													src="<?php echo JURI::root().PICTURES_PATH.'/no_image.jpg' ?>" 
													class="img-circle"
												/>
											</a>
										<?php } ?>
									</div>
								
									<div class="item-name text-left">
										<div class="row-fluid">
											<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyevent.edit&'.JSession::getFormToken().'=1&id='. $event->id )?>'
												title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>">
												<b><?php echo $event->name ?></b>
											</a>
										</div>
										<div class="row-fluid">
											<a href='<?php echo JBusinessUtil::getEventLink($event->id, $event->alias) ?>' target='_blank'
												title="<?php echo JText::_('LNG_CLICK_TO_VIEW'); ?>" class="btn btn-xs btn-primary btn-panel"> 
												<?php echo JText::_('LNG_VIEW'); ?>
											</a>
											<a href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=managecompanyevent.edit&'.JSession::getFormToken().'=1&id='. $event->id )?>'
												title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>" class="btn btn-xs btn-success btn-panel">
												<?php echo JText::_("LNG_EDIT") ?>
											</a>
											<a href="javascript:deleteDirEvent(<?php echo $event->id ?>)"
												title="<?php echo JText::_('LNG_CLICK_TO_DELETE'); ?>" class="btn btn-xs btn-danger btn-panel">
												<?php echo JText::_("LNG_DELETE")?>
											</a>
											<?php if($event->approved == 1) { ?>
												<a onclick="document.location.href='<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&task=managecompanyevent.chageState&id='. $event->id )?> '"
													title="<?php echo JText::_('LNG_CLICK_TO_CHANGE_STATE'); ?>"
													<?php 
														if($event->state==0)
															echo 'class="btn btn-xs btn-info"';
														else 
															echo 'class="btn btn-xs btn-warning"';
													?>
												>
													<?php 
														if($event->state==0) 
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
								<?php echo $event->companyName ?>
							</td>
							<td class="hidden-xs hidden-phone">
								<?php echo JBusinessUtil::getDateGeneralFormat($event->start_date) ?>
							</td>
							<td class="hidden-xs hidden-phone">
								<?php echo JBusinessUtil::getDateGeneralFormat($event->end_date) ?>
							</td>
							<td class="hidden-xs hidden-phone">
								<?php echo $event->view_count ?>
							</td>
							<td valign="top" align="center">
								<?php if(($event->state == 1) && ($event->approved == 1)) {
									if($event->expired)
										echo '<span class="status-btn status-btn-warning">'.JText::_("LNG_EXPIRED").'</span>';
									elseif(!$event->allow_events)
										echo '<span class="status-btn status-btn-warning warn">'.JText::_("LNG_NOT_INCLUDED").'</span>';
									else
										echo '<span class="status-btn status-btn-success">'.JText::_("LNG_PUBLISHED").'</span>';
								} else {
									switch($event->approved) {
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
					<?php
						$i++;
					} 
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
	<input type="hidden" name="cid" id="id" value="" />
	<input type="hidden" name="delete_mode" id="delete_mode" value="" />
	<input type="hidden" name="Itemid" id="Itemid" value="163" />
	<input type="hidden" name="companyId" id="companyId" value="<?php echo $this->companyId ?>" />
		
	<?php echo JHTML::_( 'form.token' ); ?> 
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

<div id="delete-event-dialog" style="display:none">
	<div id="dialog-container">
		<div class="titleBar">
			<span class="dialogTitle" id="dialogTitle"></span>
			<span  title="Cancel"  class="dialogCloseButton" onClick="jQuery.unblockUI();">
				<span title="Cancel" class="closeText">x</span>
			</span>
		</div>
		<div class="dialogContent">
			<h3 class="title"><?php echo JText::_('LNG_DELETE_RECCURING_EVENT') ?></h3>
	  		<div class="dialogContentBody" id="dialogContentBody">
				<p>
					<?php echo JText::_('LNG_DELETE_RECCURING_EVENT_INFO') ?>
				</p>
				<fieldset>
					<div>
						<button type="button" class="ui-dir-button ui-dir-button" onclick="deleteEvent()">
							<span class="ui-button-text"> <?php echo JText::_("LNG_ONLY_THIS_EVENT")?></span>
						</button>
						<?php echo JText::_('LNG_DELETE_ONLY_THIS_EVENT_INFO') ?>
					</div>
					<div>
						<button type="button" class="ui-dir-button ui-dir-button" onclick="deleteAllFollowignEvents()">
							<span class="ui-button-text"> <?php echo JText::_("LNG_FOllOWINGS_EVENT")?></span>
						</button>
						<?php echo JText::_('LNG_DELETE_ALL_FOllOWINGS_EVENT_INFO') ?>
					</div>
					<div>
						<button type="button" class="ui-dir-button ui-dir-button" onclick="deleteAllSeriesEvents()">
							<span class="ui-button-text"> <?php echo JText::_("LNG_ALL_SERIES_EVENTS")?></span>
						</button>
						<?php echo JText::_('LNG_DELETE_ALL_SERIES_EVENTS_INFO') ?>
					</div>
				</fieldset>			
			</div>
		</div>
	</div>
</div>

<script>
	function editEvent(eventId){
		jQuery("#id").val(eventId);
		jQuery("#task").val("managecompanyevent.edit");
		jQuery("#adminForm").submit();
	}

	function addDirEvent(){
		jQuery("#id").val(0);
		jQuery("#task").val("managecompanyevent.add");
		jQuery("#adminForm").submit();
	}

	function deleteDirEvent(eventId){
		jQuery("#id").val(eventId);
		//showDeleteDialog();
		
		if(confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_EVENTS_CONFIRM_DELETE', true);?>')){
			jQuery("#id").val(eventId);
			jQuery("#task").val("managecompanyevents.delete");
			jQuery("#adminForm").submit();
		}
	}

	function showDeleteDialog(){
		jQuery.blockUI({ message: jQuery('#delete-event-dialog'), css: {width: 'auto',top: '10%', left:"0", position:"absolute"} });
		jQuery('.blockUI.blockMsg').center();
		jQuery('.blockOverlay').attr('title','Click to unblock').click(jQuery.unblockUI); 
	} 

	function deleteEvent(){
		jQuery("#delete_mode").val(1);
		Joomla.submitform('managecompanyevents.delete');
		jQuery.unblockUI();
	}

	function deleteAllFollowignEvents(){
		jQuery("#delete_mode").val(2);
		Joomla.submitform('managecompanyevents.delete');
		jQuery.unblockUI();
	}
	
	function deleteAllSeriesEvents(){
		jQuery("#delete_mode").val(3);
		Joomla.submitform('managecompanyevents.delete');
		jQuery.unblockUI();
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

