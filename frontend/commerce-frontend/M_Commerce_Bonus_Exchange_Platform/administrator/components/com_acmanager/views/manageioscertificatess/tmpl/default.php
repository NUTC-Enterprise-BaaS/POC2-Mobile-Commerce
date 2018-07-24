<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Acmanager
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  Copyright (C) 2016. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
//$document->addStyleSheet(JPATH_ROOT . '/media/com_acmanager/css/edit.css');
$document->addScript(JURI::root().'administrator/components/com_acmanager/assets/js/ajax_file_upload.js');

?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});
	
	Joomla.submitbutton = function (task) {
		if (task == 'manageioscertificatess.cancel') {
			//Joomla.submitform(task));
			var root_url = "<?php echo JURI::root(); ?>";
			var url = root_url+"administrator/index.php?option=com_acmanager&view=appusers";
			window.location= url;
			
		}
		else {
			
			if (task != 'manageioscertificatess.cancel' && document.formvalidator.isValid(document.id('manageios-form'))) {
	
				Joomla.submitform(task, document.getElementById('manageios-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php'); ?>" 
	method="post" enctype="multipart/form-data" name="adminForm" id="manageios-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('Manage Ios Certificates', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

				<div class="control-group">
				<!--<input type="file" name="ad_image" id="ad_image" value="<?php //echo $this->ad_image; ?>" onchange="ajaxUpload(this.form,'&filename=ad_image')">
				-->
					<div class="row-fluid">
						<div class="upload-progress" style="display:none;">
							<!--<div class="progress progress-striped active">
								<div class="bar" style="min-width: 70%; width:auto;" >Uploading</div>
								</div>-->
							<div class="progress progress-pr  active">
								<div class="bar" style="min-width: 70%; width:auto;" ><?php JTEXT::_('COM_ACMANAGER_FORM_MNG_CERT_UPLODING'); ?></div>
							</div>
						</div>
						<div class="upload-msg" style="display:none;">
							<div class="process_done alert alert-success"><img src="<?php JURI::root();?> components/com_acmanager/assets/images/portal_reimooc_peer_reviews_radius_s.png" alt="Tick" />&nbsp; File  uploaded!</div>
						</div>
					</div>
					
					<div class="controls">
						<div class="fileupload fileupload-new pull-left fileupload-image" data-provides="fileupload">
							<div class="input-append">
								<span class="btn btn-file">
									<span class="fileupload-new">
										<?php echo JText::_('COM_ACMANAGER_FORM_MNG_CERT_UPLOD_IOS'); ?>
									</span>
										<input type="file" id="idea_image" name="idea_image" onchange="validate_file(this,0,1)">
								</span>
							</div>

						</div>
					</div>
					
					<!-- Show uploded certificates -->
					<?php 
					if(!empty($this->file))
					{
					?>
					<div class="controls">
						<div>
							
						<span class="label label-default">
							<?php echo JText::_('COM_ACMANAGER_FORM_MNG_CERT_UPLODED_IOS'); ?>
						</span>
						<span class="label label-success">
							<?php echo $this->file; ?>
						</span>

						</div>
					</div>
					
					<?php
					}
					?>
					
				
				</div>
<!--
				<div class="control-group">
					<div class="row-fluid">
						<div class="upload-progress" style="display:none;">
							<div class="progress progress-pr  active">
								<div class="bar" style="min-width: 70%; width:auto;" >Uploading</div>
							</div>
						</div>
						<div class="upload-msg" style="display:none;">
							<div class="process_done alert alert-success"><img src="<?php JURI::root();?> components/com_acmanager/assets/images/portal_reimooc_peer_reviews_radius_s.png" alt="Tick" />&nbsp; File  uploaded!</div>
						</div>
					</div>
					
					<div class="controls">
						<div class="fileupload fileupload-new pull-left fileupload-image" data-provides="fileupload">
							<div class="input-append">
								<span class="btn btn-file">
									<span class="fileupload-new">
										<?php echo JText::_('COM_ACMANAGER_FORM_MNG_CERT_UPLOD_IOS'); ?>
									</span>
										<input type="file" id="idea_image" name="idea_image2" onchange="validate_file(this,0,1)">
								</span>
							</div>

						</div>
					</div>
-->
				</div>


				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>


