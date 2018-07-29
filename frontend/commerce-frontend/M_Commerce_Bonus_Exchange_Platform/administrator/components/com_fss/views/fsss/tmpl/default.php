<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="fss_main">


<?php if (FSS_Settings::get('bootstrap_template') != FSS_Helper::GetTemplate()): ?>
	<div class="alert alert-danger">
	<h4><?php echo JText::_('FREESTYLE_SUPPORT_HAS_NOT_BEEN_CONFIGURED_FOR_YOUR_CURRENT_TEMPLATE'); ?></h4>
	<?php echo JText::_('WRONG_TEMPLATE_MESSAGE'); ?>

		<p class="pull-right"><a class="btn fssTip" title="<?php echo JText::_('WARNING_ONLY_DO_THIS_IF_THIS_MESSAGE_WONT_HIDE_BY'); ?>" href="<?php echo JRoute::_('index.php?option=com_fss&view=fsss&hide_template_warning=1'); ?>"><?php echo JText::_('HIDE_MESSAGE'); ?></a></p>
		<p><a class="btn" href="<?php echo JRoute::_('index.php?option=com_fss&view=settings#visual'); ?>"><?php echo JText::_('GOTO_SETTINGS'); ?></a></p>
	</div>
<?php endif; ?>

<?php if( !(version_compare(PHP_VERSION, '5.3.0') >= 0) ): ?>
	<div class="alert alert-danger">
	<h4><?php echo JText::_('FREESTYLE_SUPPORT_REQUIRES_PHP_5_3_OR_ABOVE_TO_FUNCTION'); ?></h4>
		<p><?php echo JText::_('YOU_ARE_CURRENTLY_RUNNING'); ?> <strong><?php echo PHP_VERSION; ?></strong></p>
		<p><?php echo JText::_('PLEASE_UPDATE_YOUR_PHP_VERSION_OR_THIS_COMPONENT_WILL_DISPLAY_NUMEROUS_ERRORS_'); ?></p>
	</div>
<?php endif; ?>

<script>
jQuery(document).ready(function (ev) {
	resize_cols();
	
	/*jQuery(window).resize(function (ev) {
		resize_cols();
	});*/
});

function resize_cols()
{
	var w = jQuery('.main_icons').width();
	var colcount = Math.floor(w / 180);

	if (colcount < 1) colcount = 3;

	jQuery('.main_icons').css('column-count', colcount);
	jQuery('.main_icons').css('-webkit-column-count', colcount);
	jQuery('.main_icons').css('-moz-column-count', colcount);
}
</script>

<style>

.main_icons {
	-webkit-column-count: 2;
	-moz-column-count: 2;
	column-count: 2;

	-moz-column-gap: 6px;
	-webkit-column-gap: 6px;
	column-gap: 6px;
}

.main_icons div.well {
	width: 180px;
	column-break-inside: avoid;
	-webkit-column-break-inside: avoid;
	-moz-column-break-inside: avoid;
	margin-right: 0 !important;
	margin-left: 0 !important;
	display: inline-block;
}
</style>

<table width="100%">
	<tr>
		<td width="55%" valign="top">
			<div class="main_icons">
				<div class="well well-mini margin-small" style="margin-top: 0px !important;">
					<h4 class="margin-mini"><?php echo JText::_("SETTINGS"); ?></h4>
					<?php $this->Item("SETTINGS","index.php?option=com_fss&view=settings","settings","FSS_HELP_SETTINGS"); ?>
					<?php $this->Item("TEMPLATES","index.php?option=com_fss&view=templates","templates","FSS_HELP_TEMPLATES"); ?>
					<?php $this->Item("VIEW_SETTINGS","index.php?option=com_fss&view=settingsview","viewsettings","FSS_HELP_VIEWSETTINGS"); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("GENERAL"); ?></h4>
					<?php $this->Item("PERMISSIONS","index.php?option=com_fss&view=fusers","users","FSS_HELP_USER_PERMISSIONS"); ?>
					<?php $this->Item("EMAIL_TEMPLATES","index.php?option=com_fss&view=emails","emails","FSS_HELP_EMAIL_TEMPLATES"); ?>
					<?php $this->Item("CUSTOM_FIELDS","index.php?option=com_fss&view=fields","customfields","FSS_HELP_CUSTOM_FIELDS"); ?>
					<?php $this->Item("MAIN_MENU_ITEMS","index.php?option=com_fss&view=mainmenus","menu","FSS_HELP_MAIN_MENU_ITEMS"); ?>
					<?php $this->Item("MODERATION","index.php?option=com_fss&view=tests","moderate","MODERATION"); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("SUPPORT_TICKETS"); ?></h4>
					<?php $this->Item("PRODUCTS","index.php?option=com_fss&view=prods","prods","FSS_HELP_SUPPORT_PRODUCTS"); ?>
					<?php $this->Item("CATEGORIES","index.php?option=com_fss&view=ticketcats","categories","FSS_HELP_TICKET_CATEGORIES"); ?>
					<?php $this->Item("DEPARTMENTS","index.php?option=com_fss&view=ticketdepts","ticketdepts","FSS_HELP_TICKET_DEPARTMENTS"); ?>
					<?php $this->Item("PRIORITIES","index.php?option=com_fss&view=ticketpris","ticketpris","FSS_HELP_TICKET_PRIORITIES"); ?>
					<?php $this->Item("GROUPS","index.php?option=com_fss&view=ticketgroups","groups","FSS_HELP_TICKET_GROUPS"); ?>
					<?php $this->Item("STATUSES","index.php?option=com_fss&view=ticketstatuss","ticketstatus","FSS_HELP_TICKET_STATUS"); ?>
					<?php $this->Item("TICKETS_EMAIL_ACCOUNTS","index.php?option=com_fss&view=ticketemails","emailaccounts","FSS_HELP_TICKET_EMAIL_ACCOUNTS"); ?>
					<?php $this->Item("HELP_TEXT","index.php?option=com_fss&view=helptexts","helptext",""); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("KNOWELEDGE_BASE"); ?></h4>
					<?php $this->Item("PRODUCTS","index.php?option=com_fss&view=prods","prods","FSS_HELP_KB_PRODUCTS"); ?>
					<?php $this->Item("KB_CATS","index.php?option=com_fss&view=kbcats","categories","FSS_HELP_KB_CATS"); ?>
					<?php $this->Item("KB_ARTS","index.php?option=com_fss&view=kbarts","kb","FSS_HELP_KB_ARTS"); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("FAQS"); ?></h4>
					<?php $this->Item("FAQ_CATS","index.php?option=com_fss&view=faqcats","categories","FSS_HELP_FAQ_CATS"); ?>
					<?php $this->Item("FAQS","index.php?option=com_fss&view=faqs","faqs","FSS_HELP_FAQS"); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("ANNOUNCEMENTS"); ?></h4>
					<?php $this->Item("ANNOUNCEMENTS","index.php?option=com_fss&view=announces","announce","FSS_HELP_ANNOUNCEMENTS"); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("GLOSSARY"); ?></h4>
					<?php $this->Item("GLOSSARY_ITEMS","index.php?option=com_fss&view=glossarys","glossary","FSS_HELP_GLOSSARY_ITEMS"); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("TESTIMONIALS"); ?></h4>
					<?php $this->Item("PRODUCTS","index.php?option=com_fss&view=prods","prods","FSS_HELP_TEST_PRODUCTS"); ?>
					<?php $this->Item("MODERATION","index.php?option=com_fss&view=tests","moderate","MODERATION"); ?>
					<div style="clear: both;"></div>
				</div>

				<div class="well well-mini margin-small">
					<h4 class="margin-mini"><?php echo JText::_("COM_FSS_ADMIN"); ?></h4>
					<?php $this->Item("LOG","index.php?option=com_fss&view=cronlog","cronlog","FSS_LOG_HELP"); ?>
					<?php $this->Item("EMAIL_LOG","index.php?option=com_fss&view=emaillog","cronlog","FSS_EMAIL_LOG_HELP"); ?>
					<?php $this->Item("TICKET_ATTACH_CLEANUP","index.php?option=com_fss&view=attachclean","attachclean","TICKET_ATTACH_CLEANUP_HELP"); ?>
					<?php $this->Item("COM_FSS_ADMIN","index.php?option=com_fss&view=backup","settings","COM_FSS_ADMIN"); ?>
					<?php $this->Item("PLUGINS","index.php?option=com_fss&view=plugins","viewsettings",""); ?>
					<?php //$this->Item("Timezone","index.php?option=com_fss&view=timezone","settings","Timezone"); ?>
					<div style="clear: both;"></div>
				</div>

				<?php 
					$xmlfile = JPATH_ROOT.DS."administrator".DS."components".DS."com_fsj_fssadd".DS."fssadd.xml";
					$xml = @simplexml_load_file($xmlfile);

					if ($xml) 
					{
						if ($xml && $xml->admin && $xml->admin->section) foreach ($xml->admin->section as $section): ?>
							<div class="well well-mini margin-small">
								<h4 class="margin-mini"><?php echo JText::_((string)$section->attributes()->name); ?></h4>
									<?php
										$lang = JFactory::getLanguage();
										$lang->load("com_fsj_fssadd");

									?>
									<?php foreach ($section->item as $item): ?>
									<?php
											$component = (string)$item->attributes()->component;
											$url = (string)$item->attributes()->url;
											$id = (string)$item->attributes()->id;
											$icon = (string)$item->attributes()->icon;
											$com = "fsj_fssadd";
										?>
										<?php if ($id == "spacer"): ?>
										<?php elseif ($url): ?>
											<?php echo $this->FSJItem((string)$item->title,$url,'com_fsj_'.$component,$icon,(string)$item->description); ?>
										<?php elseif ($component): ?>
											<?php echo $this->FSJItem((string)$item->title,"index.php?option=com_fsj_{$component}&admin_com=".str_replace("fsj_", "", $com)."&view={$id}s",'com_fsj_'.$component,$icon,(string)$item->description); ?>
										<?php else: ?>
											<?php echo $this->FSJItem((string)$item->title,"index.php?option=com_{$com}&view={$id}s","com_".$com,$icon,(string)$item->description); ?>
										<?php endif; ?>									
									<?php endforeach; ?>
											<div style="clear: both;"></div>
										</div>
									<?php
						endforeach;
					}
					?>
			</div>
		</td>
		<td width="45%" valign="top">


<div class="alert"><h4>If you like Freestyle Support, please vote or review us at the <a href='http://extensions.joomla.org/extensions/clients-a-communities/help-desk/11912' target="_blank">Joomla extensions directory</a></h4></div>

<?php JHTML::addIncludePath(array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fss'.DS.'html'));	 ?>	

<?php
echo JHTML::_( 'fsjtabs.start' );

$title = JText::_("VERSION");
echo JHTML::_( 'fsjtabs.panel', $title, 'cpanel-panel-version', true );

$ver_inst = FSSAdminHelper::GetInstalledVersion();
$ver_files = FSSAdminHelper::GetVersion();

?>
<?php
echo "<h4>". JText::_('CURRENTLY_INSTALLED_VERISON') . " : <b>$ver_files</b></h4>";
if ($ver_files != $ver_inst)
	echo "<h4>".JText::sprintf('INCORRECT_VERSION',FSSRoute::_('index.php?option=com_fss&view=backup&task=update'))."</h4>";

?>
<div id="please_wait"><?php echo JText::_('PLEASE_WAIT_WHILE_FETCHING_LATEST_VERSION_INFORMATION___'); ?></div>

<iframe id="frame_version" height="300" width="100%" frameborder="0" border="0"></iframe>	
<?php

$title = JText::_("ANNOUNCEMENTS");
echo JHTML::_( 'fsjtabs.panel', $title, 'cpanel-panel-announcements' );
?>
<iframe id="frame_announce" height="600" width="100%" frameborder="0" border="0"></iframe>
<?php

$title = JText::_("HELP");
echo JHTML::_( 'fsjtabs.panel', $title, 'cpanel-panel-help' );
?>
<iframe id="frame_help" height="600" width="100%" frameborder="0" border="0"></iframe>
<?php
echo JHTML::_( 'fsjtabs.end' );

?>

		</td>	
	</tr>
</table>

<script>
jQuery(document).ready(function () {
	jQuery('#frame_version').attr('src',"http://freestyle-joomla.com/latestversion-fss?ver=<?php echo FSSAdminHelper::GetVersion();?>");
	jQuery('#frame_version').load(function() 
    {
        jQuery('#please_wait').remove();
    });

	jQuery('#frame_announce').attr('src',"http://freestyle-joomla.com/news?tmpl=component");
	jQuery('#frame_help').attr('src',"http://freestyle-joomla.com/index.php?option=com_content&view=article&id=81&tmpl=component");
});
</script>

</div>