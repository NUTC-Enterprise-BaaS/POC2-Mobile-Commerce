<?php
/**
 * @version    SVN: <svn_id>
 * @package    JGive
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die();

?>
<style>
.jgive_override_sub_inst{margin-left:30px;}
</style>
<div class="">
	<form name="adminForm" id="adminForm" class="form-validate" method="post">
		<?php
		if(JVERSION >= '3.0'):
			 if (!empty( $this->sidebar)) : ?>
				<div id="j-sidebar-container" class="span2">
					<?php echo $this->sidebar; ?>
				</div>
				<div id="j-main-container" class="span10">
			<?php else : ?>
				<div id="j-main-container">
			<?php endif;
		endif;

		?>
		<div id="">
			<legend><?php echo JText::_("COM_SOCIALADS_CHANGE_SITE_VIEW_ACCORDING_TO_TEMPLATE_BS");?></legend>
			<div class="alert alert-success">
				<?php echo JText::_("COM_SOCIALADS_INTRO_WE_HV_CAHGNE_DEFALT_FORNTED_VEW_IN_BS3");?>
			</div>
			<h2><?php echo JText::_("COM_SOCIALADS_CHANGE_SITE_VIEW_IN_BS3");?></h2>
			<div class='alert alert-warning'><b><?php echo JText::_("COM_SOCIALADS_IF_UR_TEMPALATE_IN_BS3");?></b></div>
			<p><?php echo JText::_("COM_SOCIALADS_LATEST_VERSION_FRONTEND_IN_BS3");?></p>
			<table class="table table-bordered">
				<tr><td><?php echo JText::_("COM_SOCIALADS_BS3_INST_1");?></td></tr>
				<tr><td><?php echo JText::_("COM_SOCIALADS_BS3_INST_2");?></td></tr>
<!--
				<tr><td><?php //echo JText::_("COM_SOCIALADS_BS3_INST_3");?></td></tr>
-->

			</table>

			<h2><?php echo JText::_("COM_SOCIALADS_CHANGE_SITE_VIEW_IN_BS2");?></h2>
			<div class='alert alert-warning'><b><?php echo JText::_("COM_SOCIALADS_IF_UR_TEMPALATE_IN_BS2");?></b></div>
			<p><?php echo JText::_("COM_SOCIALADS_LATEST_VERSION_FRONTEND_IN_BS2");?></p>
			<table class="table table-bordered">
				<tr><td><?php echo JText::_("COM_SOCIALADS_BS2_INST_1");?></td></tr>
				<tr>
					<td>
						<p><?php echo JText::_("COM_SOCIALADS_BS2_INST_2");?></p>
						<!-- Automation-->
						<?php
						$actionLink = JURI::base() . "index.php?option=com_socialads&task=setup.setup";
						$overrideLink = $actionLink . "&takeBackUp=0";
						$bakupLink = $actionLink . "&takeBackUp=1";
						?>
						<p>
							<div class="alert alert-success">
								<b><i><u><?php echo JText::_("COM_SOCIALADS_BS2_AUTOMATION_INST_2");?></u></i></b>
							</div>
						</p>
						<span class="label label label-warning jgive_override_sub_inst">
							<?php echo JText::_("COM_SOCIALADS_EACH_UPGRADATION_INST");?>
						</span>
						<div class="jgive_override_sub_inst">
							<dl>
							  <dt><?php echo JText::_("COM_SOCIALADS_BS2_CLEAN_INSTALL");?></dt>
							  <dd>
								  <a class="btn btn-primary" href="<?php echo $overrideLink; ?>" target="_blank">
										<?php echo JText::_("COM_SOCIALADS_BS2_OVERRIDE_BTN");?>
									</a>
									<i class="icon-arrow-left"></i>
									<?php echo JText::_("COM_SOCIALADS_BS2_CLEAN_INSTALL_MSG");?>
							   </dd>
							</dl>

							<dl>
							  <dt><?php echo JText::_("COM_SOCIALADS_BS2_UPGRADE");?></dt>
							  <dd>
								<ol>
									<li>
										 <a class="btn btn-m btn-primary" href="<?php echo $overrideLink; ?>" target="_blank">
										<?php echo JText::_("COM_SOCIALADS_BS2_OVERRIDE_BTN_UPGRADE");?>
										</a>
										<i class="icon-arrow-left"></i>
									<?php echo JText::sprintf('COM_SOCIALADS_BS2_UPGRADE_NO_CUSTOM_CHANGE_HELP', '');?>
									<br />
									</li>
									<li>
										<a class="btn btn-medium btn-primary" href="<?php echo $bakupLink; ?>" target="_blank">
										<?php echo JText::_("COM_SOCIALADS_BS2_BACKUP_AND_OVERRIDE_BTN");?>
										</a>
										<i class="icon-arrow-left"></i>
										<?php echo JText::sprintf('COM_SOCIALADS_BS2_UPGRADE_DONE_CUSTOM_CHANGE_HELP','[LINK]');?>
									</li>

								</ol>
							  </dd>
							</dl>
						</div>

						<hr/>

						<p>
							<div class="alert alert-success">
								<b><i><u><?php echo JText::_("COM_SOCIALADS_BS2_MANUAL_INST_2");?></u></i></b>
							</div>
						</p>

						<span class="label label label-warning jgive_override_sub_inst">
							<?php echo JText::_("COM_SOCIALADS_EACH_UPGRADATION_INST");?>
						</span>

						<div class="jgive_override_sub_inst">
							<h4><?php echo JText::_("COM_SOCIALADS_BS2_SITE_COM_OVERRIDE");?></h4>
							<ol>
							  <li><?php echo JText::_("COM_SOCIALADS_BS2_SITE_COM_OVERRIDE_STEP_1");?></li>
							  <li><?php echo JText::_("COM_SOCIALADS_BS2_SITE_COM_OVERRIDE_STEP_2");?></li>
							</ol>

							<h4><?php echo JText::_("COM_SOCIALADS_BS2_SITE_MOD_OVERRIDE");?></h4>
							<ol>
							  <li><?php echo JText::_("COM_SOCIALADS_BS2_SITE_MOD_OVERRIDE_STEP_1");?></li>
							  <li><?php echo JText::_("COM_SOCIALADS_BS2_SITE_MOD_OVERRIDE_STEP_2");?></li>
							</ol>
						</div>
					</td>
				</tr>

			</table>

		</div
	</div> <!-- j-main-container -->
	</form>
</div>
