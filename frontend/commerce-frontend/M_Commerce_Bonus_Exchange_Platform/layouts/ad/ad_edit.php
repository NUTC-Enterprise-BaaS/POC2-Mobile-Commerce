<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/sa.css');
$document->addStyleSheet(JUri::root(true) . '/media/com_sa/vendors/fuelux/fuelux2.3.1.css');
$document->addScript(JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer-3.2.13.min.js');
$paymentMode = $displayData->sa_params->get('payment_mode');
?>

<!--techjoomla-bootstrap -->
<div class="tjBs3" id="sa_create">
	<div class="container-fluid">
		<!--row-->
		<div class="row">
			<!-- main div starts here-->
			<div class="sa-form">
				<div class="fuelux wizard-example">
					<div class="sa_steps_parent row">
						<!--wizard-->
						<div id="MyWizard" class="wizard">
							<?php $s = 1; ?>
							<ol class="sa-steps-ol steps clearfix span12" id="sa-steps">
								<li id="ad-design" data-target="#step1" class="active">
									<span class="badge badge-info">
										<?php echo $s++; ?>
									</span>
									<span class="hidden-xm hidden-sm">
										<?php echo JText::_('COM_SOCIALADS_DESIGN_TAB'); ?>
									</span>
									<span class="chevron"></span>
								</li>

								<?php
								if ($displayData->showTargeting == 1)
								{
									?>
									<li id="ad-targeting" data-target="#step2">
										<span class="badge">
											<?php echo $s++; ?>
										</span>
										<span class="hidden-xm hidden-sm">
											<?php echo JText::_('COM_SOCIALADS_TARGETING_TAB'); ?>
										</span>
										<span class="chevron"></span>
									</li>
								<?php
								} ?>
								<li id="ad-pricing" data-target="#step3" >
									<span class="badge">
										<?php echo $s++; ?>
									</span>
									<span class="hidden-xm hidden-sm">
										<?php echo JText::_('COM_SOCIALADS_PRICING_TAB'); ?>
									</span>
									<span class="chevron"></span>
								</li>

								<?php
								if ($paymentMode == 'pay_per_ad_mode')
								{
									$sa_stpeNo = 4;

									if (!empty($displayData->showBilltab))
									{
										?>
										<li id="ad-billing" data-target="#step<?php echo $sa_stpeNo; ?>" >
											<span class="badge">
												<?php echo $s++; ?>
											</span>
											<span class="hidden-xm hidden-sm">
												<?php echo JText::_('COM_SOCIALADS_CKOUT_BILL_DETAILS_TAB')?>
											</span>
											<span class="chevron"></span>
										</li>
										<?php
										$sa_stpeNo++;
									}
									else
									{
										// Already billing address is saved
										?>
										<input type="hidden" id="sa_hide_billTab" name="sa_hide_billTab" value="1" />
										<?php
									}
									?>

									<li id="ad-summery" data-target="#step<?php echo $sa_stpeNo; ?>">
										<span class="badge">
											<?php echo $s++; ?>
										</span>
										<span class="hidden-xm hidden-sm">
											<?php echo JText::_('COM_SOCIALADS_CKOUT_ADS_SUMMARY_TAB')?>
										</span>
										<span class="chevron"></span>
									</li>
									<?php
								}
								else
								{
									$sa_stpeNo = 4;
									?>
									<li id="ad-review" data-target="#step<?php echo $sa_stpeNo; ?>" >
										<span class="badge">
											<?php echo $s++; ?>
										</span>
										<span class="hidden-xm hidden-sm">
											<?php echo JText::_('COM_SOCIALADS_REVIEW_AD_TAB')?>
										</span>
										<span class="chevron"></span>
									</li>
									<?php
									$sa_stpeNo++;
								}
								?>
							</ol>
						</div>
					</div>

					<!--tab-content step-content-->
					<div id="TabConetent" class="tab-content step-content">
						<form method="post" name="adsform" id="adsform" enctype="multipart/form-data" class="form-vertical form-validate"
						onsubmit="return validateForm();">
							<!--step1-->
							<div class="tab-pane step-pane active" id="step1">
								<?php
								$saLayout = new JLayoutFile('ad.ad_design');
								echo $saLayout->render($displayData);
								?>
							</div>
							<!--step1-->

							<!--step2-->
							<?php
							if ($displayData->showTargeting == 1)
							{
								?>
								<div class="tab-pane step-pane " id="step2">
									<?php
									$saLayout = new JLayoutFile('ad.ad_targeting');
									echo $saLayout->render($displayData);
									?>
								</div>
								<?php
							}
							?>
							<!--step2-->

							<!--step3-->
							<div class="tab-pane step-pane" id="step3">
								<?php
								// Pay per ad
								if ($paymentMode == 'pay_per_ad_mode')
								{
									$saLayout = new JLayoutFile('ad.ad_pricing');
									echo $saLayout->render($displayData);
								}
								// Wallet mode
								else
								{
									$saLayout = new JLayoutFile('ad.ad_camp');
									echo $saLayout->render($displayData);
								}
								?>
							</div>
							<!--step3-->
						</form>

						<?php
						// Pay per ad
						if ($paymentMode == 'pay_per_ad_mode')
						{
							$sa_stpeNo = 4;
							?>
							<form method="post" name="sa_BillForm" id="sa_BillForm" class="form-horizontal form-validate" onsubmit="return validateForm();">
								<?php
								if (!empty($displayData->showBilltab))
								{
									?>
									<div class="tab-pane step-pane sa_build_ad_billing" id="step<?php echo $sa_stpeNo; ?>">
										<?php
										$saLayout = new JLayoutFile('ad.ad_billing');
										echo $saLayout->render($displayData);
										?>
									</div>
									<?php
									$sa_stpeNo++;
								}
								else
								{
									// This field should be in the <form name='adsform'
									// Already billing address is saved
									?>
									<input type="hidden" id="sa_hide_billTab" name="sa_hide_billTab" value="1" />
									<?php
								}
								?>
							</form>
							<?php
						}
						// Wallet
						else
						{
							$sa_stpeNo = 4;
							?>
							<div class="tab-pane step-pane" id="step<?php echo $sa_stpeNo; ?>">
								<div id="adPreviewHtml">
								</div>
							</div>
							<?php
						}
						?>

						<!--step5-->
						<div class="tab-pane step-pane" id="step<?php echo $sa_stpeNo; ?>">
							<!-- bill msg -->
							<div class="row ">
								<?php
								if (empty($displayData->showBilltab))
								{
									?>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sa_reomveMargin">
										<?php
										JHtml::_('behavior.modal');
										$catid  = 0;
										$itemid = SaCommonHelper::getSocialadsItemid('ad');

										$terms_link = JUri::root() . substr(
										JRoute::_('index.php?option=com_socialads&view=ad&layout=updatebill&tmpl=component&itemid=' . $itemid),
											strlen(JUri::base(true)) + 1
										);
										?>
										<div class="alert alert-success" id="">
											<?php echo JText::_('COM_SOCIALADS_BILL_INFO_ALREADY_STORED'); ?>
											<a rel="{handler: 'iframe', size: {x: 600, y: 600}}" href="<?php echo $terms_link; ?>" class="modal">
												<strong><?php echo JText::_('COM_SOCIALADS_BILL_CLICK_HERE'); ?></strong>
											</a>
											<?php echo JText::_('COM_SOCIALADS_UPDATE_BILLING_ADDRESS'); ?>
										</div>
									</div>
									<?php
								}
								?>
							</div>

							<div id="ad_reviewAndPayHTML">
							</div>
						</div>
						<!--step5-->
					</div>
					<!--tab-content step-content ENDS-->

					<!--pull-right-->
					<div class="prev_next_wizard_actions">
						<div class="form-actions">
							<button id="btnWizardPrev" type="button" style="display:none" class="btn btn-default btn-primary pull-left" >
								<span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span> <?php echo JText::_('COM_SOCIALADS_PREV'); ?>
							</button>
							<button id="btnWizardNext" type="button" class="btn btn-default btn-primary pull-right" data-last="Finish" >
								<span><?php echo JText::_("COM_SOCIALADS_BTN_SAVEANDNEXT"); ?></span>
								<i class="glyphicon glyphicon-circle-arrow-right"></i>
							</button>
							<button id="sa_cancel" type="button" class="btn btn-default btn-danger pull-right" style="margin-right:1%;" onclick="sa.create.cancelCreate()">
								<?php echo JText::_('COM_SOCIALADS_CANCEL'); ?>
							</button>
						</div>
					</div>
					<!--pull-right-->
				</div>
				<!--fuelux wizard-example  ENDS-->

				<input type="hidden" name="config_estimated_reach" id="config_estimated_reach"
				value="<?php echo $displayData->sa_params->get('reach_offset'); ?>" />
				<input type="hidden" name="bootstrap_version" id="bootstrap_version" value="<?php echo 3.0 ?>" />

				<?php
				if ($displayData->edit_ad_id)
				{
					?>
					<input type="hidden" name="editview" id="editview" value="1" />
					<?php
				}
				else
				{
					?>
					<input type="hidden" name="editview" id="editview" value="<?php echo $displayData->input->get('frm', '', 'STRING') == 'editad'? '1' : '0'; ?>">
					<?php
				}
				?>

				<div style="clear:both;"></div>
				<div id="result"></div>
			</div>
			<!-- main div ENDS here-->
		</div>
	</div>
</div>
<!--techjoomla-bootstrap ENDS-->

<?php
// Load inline javascript
$saLayout = new JLayoutFile('ad_script', $basePath = JPATH_ROOT . '/layouts/bs2/ad');
echo $saLayout->render($displayData);
