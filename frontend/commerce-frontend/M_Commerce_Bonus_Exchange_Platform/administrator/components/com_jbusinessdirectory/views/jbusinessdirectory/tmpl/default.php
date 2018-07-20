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
?>
<style>
#content-wrapper{
	margin: 20px;
	padding: 0px;
}
</style>

<div id="jbd-dashbord">
	<div class="row-fluid">
		<div class="span3">
			<div class="ibox">
				<div class="ibox-title">
					<span class="dir-label dir-label-info  pull-right"><?php echo JText::_("LNG_TOTAL");?></span>
					<h5><?php echo JText::_("LNG_BUSINESS_LISTINGS");?></h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins"><?php echo $this->statistics->totalListings ?></h1>
					<div class="stat-percent font-bold text-success">
						<?php echo $this->statistics->month ?>
					</div>
					<small><?php echo JText::_("LNG_THIS_MONTH");?></small>
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="dir-label dir-label-success pull-right"><?php echo JText::_("LNG_TOTAL");?></span>
					<h5><?php echo JText::_("LNG_OFFERS");?></h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins"><?php echo $this->statistics->totalOffers ?></h1>
					<div class="stat-percent font-bold text-success">
						<?php echo $this->statistics->totalOffers>0 ? round($this->statistics->activeOffers*100/$this->statistics->totalOffers,2):0 ?>%
					</div>
					<small><?php echo JText::_("LNG_ACTIVE");?></small>
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="dir-label dir-label-primary pull-right"><?php echo JText::_("LNG_TOTAL");?></span>
					<h5><?php echo JText::_("LNG_EVENTS");?></h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins"><?php echo $this->statistics->totalEvents ?></h1>
					<div class="stat-percent font-bold text-success">
						<?php echo $this->statistics->totalEvents>0 ? round($this->statistics->activeEvents*100/$this->statistics->totalEvents,2):0 ?>%
					</div>
					<small><?php echo JText::_("LNG_ACTIVE");?></small>
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="ibox">
				<div class="ibox-title">
					<span class="dir-label dir-label-warning  pull-right"><?php echo JText::_("LNG_TOTAL");?></span>
					<h5><?php echo JText::_("LNG_INCOME");?></h5>
				</div>
				<div class="ibox-content">
					<h1 class="no-margins"><?php echo intval($this->income->total) ?></h1>
					<div class="stat-percent font-bold text-success">
						<?php echo intval($this->income->month) ?>
					</div>
					<small><?php echo JText::_("LNG_THIS_MONTH");?></small>
				</div>
			</div>
		</div>
		
		<div class="span3">
			<div class="ibox">
				<div class="ibox-title">
					<h5><?php echo JText::_("LNG_VERSION_STATUS");?></h5>
				</div>
				<div class="ibox-content">
					<div id="update-status">
						<img class="loading" src='<?php echo JURI::base()."/components/com_jbusinessdirectory";?>/assets/img/loader.gif'>
					</div>
					<div class="row-fluid">
						<div class="span6">	
							<div class="stat-percent"> <span class="dir-label dir-label-info" id="current-version"><?php echo JBusinessUtil::getCurrentVersion()?></span> </div>
							<small><?php echo JText::_("LNG_EXTENSION_VERSION");?></small> 
						</div>
						<div class="span6" id="update-version-holder" style="display:none">
							<div class="stat-percent"><small><?php echo JText::_("LNG_UPDATE_VERSION");?></small>  <span class="dir-label dir-label-primary" id="update-version"></span> </div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="ibox">
				<div class="ibox-content">
					<div class="row-fluid">
						<div class="span9">
							<?php $days_ago = 70; ?>
							<?php $time = strftime('%Y-%m-%d',(strtotime($days_ago.' days ago'))); ?>
							<div id="dir-dashboard-calendar-form">
								<div class="row-fluid">
									<div class="span12">
										<div id="tabs">
											<div id="dir-dashboard-tabs" class="row-fluid">
												<div class="span7" id="dir-dashboard-tabs-col">
													<ul>
														<li><a href="#newCompanies"><?php echo JText::_("LNG_BUSINESS_LISTINGS");?></a></li>
														<li><a href="#newOffers"><?php echo JText::_("LNG_OFFERS");?></a></li>
														<li><a href="#newEvents"><?php echo JText::_("LNG_EVENTS");?></a></li>
														<li><a href="#income"><?php echo JText::_("LNG_INCOME");?></a></li>
													</ul>
												</div>
												<div class="span5 item-calendar remove-margin" id="dir-dashboard-tabs-col">
													<div class="remove-margin item-calendar">
														<div class="detail_box remove-margin">
															<?php echo JHTML::_('calendar', $time, 'start_date', 'start_date', $this->appSettings->calendarFormat, array('id'=>'start_date', 'class'=>'inputbox calendar-date', 'size'=>'10',  'maxlength'=>'10', 'onchange'=>'calendarChange()')); ?>
															<span><?php echo JText::_("LNG_TO")?></span>															
															<?php echo JHTML::_('calendar', date("Y-m-d"), 'end_date', 'end_date', $this->appSettings->calendarFormat, array('id'=>'end_date', 'class'=>'inputbox calendar-date', 'size'=>'10',  'maxlength'=>'10', 'onchange'=>'calendarChange()')); ?>
															<div class="clear"></div>
														</div>
													</div>
													<div class="clear"></div>
												</div>
											</div>
											<div id="newCompanies">
												<div id="graph"></div>
											</div>
											<div id="newOffers">
											</div>
											<div id="newEvents">
											</div>
											<div id="income">
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="span3">
							<div>
								<h2><?php echo JText::_("LNG_TOTAL_VIEWS")?></h2> <h3><?php echo $this->statistics->totalViews?></h3>
							</div>
							<br/><br/><br/>
							<ul class="stat-list">
								<li>
									<h2 class="no-margins"><?php echo $this->statistics->listingsTotalViews ?></h2> <?php echo JText::_("LNG_BUSINESS_LISTING_VIEWS")?>
									<div class="stat-percent">
										<?php echo $this->statistics->totalViews> 0 ? round($this->statistics->listingsTotalViews * 100/$this->statistics->totalViews): 0 ?>%
									</div>
									<div class="dir-progress progress-mini">
										<div class="dir-progress-bar" style="width: <?php echo round($this->statistics->listingsTotalViews * 100/$this->statistics->totalViews)?>%;"></div>
									</div>
								</li>
								<li>
									<h2 class="no-margins "><?php echo $this->statistics->offersTotalViews ?></h2><?php echo JText::_("LNG_OFFER_VIEWS")?>
									<div class="stat-percent">
										<?php echo $this->statistics->totalViews>0 ? round($this->statistics->offersTotalViews * 100/$this->statistics->totalViews): 0?>%
									</div>
									<div class="dir-progress progress-mini">
										<div class="dir-progress-bar" style="width: <?php echo round($this->statistics->offersTotalViews * 100/$this->statistics->totalViews)?>%;"></div>
									</div>
								</li>
								<li>
									<h2 class="no-margins "><?php echo $this->statistics->eventsTotalViews ?></h2><?php echo JText::_("LNG_EVENT_VIEWS")?>
									<div class="stat-percent">
										<?php echo $this->statistics->totalViews > 0 ?round($this->statistics->eventsTotalViews * 100/$this->statistics->totalViews): 0?>%
									</div>
									<div class="dir-progress progress-mini">
										<div class="dir-progress-bar" style="width: <?php echo $this->statistics->totalViews > 0 ? round($this->statistics->eventsTotalViews * 100/$this->statistics->totalViews): 0?>%;"></div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span4">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Support & Documentation</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="dir-icon-chevron-up"></i></a>
						<a class="close-link"> <i class="dir-icon-times"></i></a>
					</div>
				</div>
				<div class="ibox-content">
					
						<div class="feed-element feed-small">
							<i class="pull-left dir-icon-life-saver dir-icon-custom rounded-x dir-icon-bg-sea "></i>
							<div class="media-body">
								<a href="http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1">Community forum</a>	
								<p>Get in touch with our community to find the best solutions</p>
							</div>
						</div>
						<div class="feed-element">
							<i class="pull-left dir-icon-book dir-icon-custom rounded-x dir-icon-bg-green"></i>
							<div class="media-body">
								<a href="http://www.cmsjunkie.com/docs/jbusinessdirectory/businessdiradmin.html">Online documentation</a>	
								<p>Find details about the extension features & functionality</p>
							</div>
						</div>
						<div class="feed-element">
							<i class="pull-left dir-icon-ticket dir-icon-custom rounded-x dir-icon-bg-orange "></i>
							<div class="media-body">
								<a href="https://www.cmsjunkie.com/helpdesk/customer/index/">Support Ticket</a>	
								<p>could not found a solution to your issue? Post a ticket.</p>
							</div>
						</div>
					
						<div class="feed-element">
							<i class="pull-left dir-icon-bell dir-icon-custom rounded-x dir-icon-bg-dark-blue "></i>
							<div class="media-body">
								<a href="http://www.cmsjunkie.com/contacts/">Contact us</a>	
								<p>Post a sales question</p>
							</div>
						</div>
					
				</div>
			</div>
			
			<div class="ibox">
				<div class="ibox-title">
					<h5>Connect with us</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="dir-icon-chevron-up"></i></a>
						<a class="close-link"> <i class="dir-icon-times"></i></a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="block-content" id="informations">
						<ul class="social">
							<li><a target="social" href="http://twitter.com/cmsjunkie"
								class="twitter"><span>Twitter</span> </a></li>
							<li><a target="social" href="http://facebook.com/cmsjunkie"
								class="facebook"><span>Facebook</span> </a></li>
							<li><a href="mailto:info@cmsjunkie.com" class="email"><span>Email</span>
							</a></li>
							<li><a target="social"
								href="https://plus.google.com/100376620356699373069/posts"
								class="google"><span>Google</span> </a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div class="span4">
			<div class="ibox">
				<div class="ibox-title">
					<h5>About CMS Junkie</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="dir-icon-chevron-up"></i></a>
						<a class="close-link"> <i class="dir-icon-times"></i></a>
					</div>
				</div>
				<div class="ibox-content">
					<p>
						CMSJunkie offers <strong>top quality</strong> commercial CMS products: extensions,
						templates, themes, modules for open sources content management
						systems. All products are completely customizable and ready to be
						used as a basis for a clean and high-quality website. We are now
						working with following CMS systems: Magento, Wordperss, Joomla. <br />
					</p>
					<p>The CMSJunkie Store team can answer your questions about
						purchasing, usage of our products, returns, and more. Our aim is to
						<strong> keep every one of our customers happy</strong> and we are not just saying
						that. We understand the importance of deadlines to our clients and
						we deliver on time and keep everything on schedule.</p>
				</div>
			</div>
			<div class="ibox">
				<div class="ibox-title">
					<h5>Custom Services</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="dir-icon-chevron-up"></i></a>
						<a class="close-link"> <i class="dir-icon-times"></i></a>
					</div>
				</div>
				<div class="ibox-content">
						<p>
							We do offer <strong>custom development</strong>. If you are
							interested to contract us to perform some customizations, please
							feel free to <a href="http://www.cmsjunkie.com/contacts/"
								title="Contact CMS Junkie">contact us</a>!
						</p>
				</div>
			</div>
		</div>
		<div class="span4">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Latest news</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="dir-icon-chevron-up"></i></a>
						<a class="close-link"> <i class="dir-icon-times"></i></a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="feed-activity-list">
						<?php if(!empty($this->news)){?>
							<?php foreach($this->news as $news) { ?>
								<div class="feed-element">
									<div>
										<small class="pull-right text-navy"><?php echo  $news->publish_ago; ?></small> 
										<?php 
											if($news->new) { ?>
											<span class="dir-label dir-label-warning pull-left"><?php echo JText::_("LNG_NEW")?></span>&nbsp;
										<?php } ?>
										<a target="_blank" href="<?php echo $news->link; ?>">
											<strong><?php echo $news->title; ?></strong>
										</a>
										<div><?php echo $news->description; ?></div>
										<small class="text-muted"><?php echo $news->publishDateS; ?></small>
									</div>
								</div>
							<?php } ?>
						<?php }else{ ?>
							<p>
								<?php echo JText::_("LNG_RETRIEVING_REFRESH_PAGE");?>
							</p>
						<?php } ?>
						<a href="http://www.cmsjunkie.com/blog/" target="_blank" class="pull-right"><?php echo JText::_("LNG_VIEW_ALL_NEWS")?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery("#tabs").tabs();

	var curTab = jQuery("#tabs").tabs('option', 'active');
	var siteRoot = '<?php echo JURI::root(); ?>';
	var compName = '<?php echo JBusinessUtil::getComponentName(); ?>';   
	var url = siteRoot+'administrator/index.php?option='+compName;
	var urlNews = siteRoot+'administrator/index.php?option='+compName+'&task=jbusinessdirectory.getLatestServerNewsAjax';
	var urlReport = siteRoot+'administrator/index.php?option='+compName+'&task=jbusinessdirectory.newCompanies';
	
	var start_date = jQuery("#start_date").val();
	var end_date = jQuery("#end_date").val();

	//retrieve the latest news
	jQuery.ajax({
		url: urlNews,
		type: 'GET'
	})
	
	function requestData(urlReport, start_date, end_date, chart) {
		jQuery.ajax({
			url: urlReport,
			dataType: 'json',
			type: 'GET',
			data: { start_date: start_date, end_date: end_date },
		})
		.done(function(data) {
			console.log(JSON.stringify(data));
			chart.setData(data);
		})
		.fail(function(data) {
			console.log("Error");
			console.log(JSON.stringify(data));
		});
	}

	var chart = Morris.Area({
		element: 'graph',
		data: [{date: '<?php echo date("d-m-Y"); ?>', value: 0}],
		fillOpacity: 0.6,
		hideHover: 'auto',
		behaveLikeLine: true,
		resize: true,
		lineColors: ['#54cdb4'],
		xkey: 'date',
		ykeys: ['value'],
		labels: ['Total'],
	});

	requestData(urlReport, start_date, end_date, chart);

	jQuery("#tabs").click(function(e) {
		e.preventDefault();
		calendarChange();
	});

	jQuery("#start_date, #end_date").bind("paste keyup", function(e) {
		e.preventDefault();
		calendarChange();
	});

	function calendarChange() {
		var curTab = jQuery("#tabs .ui-tabs-panel:visible").attr("id");
		var start_date = jQuery("#start_date").val();
		var end_date = jQuery("#end_date").val();
		var urlReport = siteRoot+'administrator/index.php?option='+compName+'&task=jbusinessdirectory.'+curTab;
		jQuery("#graph").appendTo("#"+curTab);
		requestData(urlReport, start_date, end_date, chart);
	}

	
	//retrieve current version status; 
	var versionCheckTask = '&task=updates.getVersionStatus';
	jQuery.ajax({
		url: url+versionCheckTask,
		dataType: 'json',
		type: 'POST',
		success: function(data){
				
                if(compareVersions(data.currentVersion,data.updateVersion)){
             	  	jQuery("#update-status").html("<span class='text-success'><?php echo JText::_("LNG_UP_TO_DATE")?></span>");	
                }else{
                	jQuery("#update-status").html("<span class='text-danger'><?php echo JText::_("LNG_OUT_OF_DATE")?></span>");	
                	jQuery("#update-version").html(data.updateVersion);
             	  	jQuery("#update-version-holder").show();
             	  	jQuery("#current-version").removeClass("dir-label-info");
             	  	jQuery("#current-version").addClass("dir-label-warning");
                }

                if(data.message.indexOf("Please enter your order details")>0){
                	jQuery("#update-status").html(data.message);
                }  	
        }
	})
</script>