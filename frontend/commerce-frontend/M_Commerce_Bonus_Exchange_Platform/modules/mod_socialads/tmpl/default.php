<?php
/**
 * @package Social Ads
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$lang =  JFactory::getLanguage();
$lang->load('mod_socialads', JPATH_ROOT);

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::base() . 'modules/mod_socialads/assets/css/style.css');

if ($params->get('ad_rotation', 0) == 1)
{
	SaCommonHelper::loadScriptOnce(JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer-3.2.13.min.js');
	SaCommonHelper::loadScriptOnce(JUri::root(true) . '/media/com_sa/js/rotation.js');
	$ad_rotationdelay = $params->get('ad_rotation_delay',10);
	?>
	<script>
		var site_link="";
		var user_id="";

		techjoomla.jQuery(document).ready(function()
		{
			var countdown;
			var module_id =	<?php echo $moduleid?>;
			var ad_rotationdelay	=	<?php echo $ad_rotationdelay?>;
			techjoomla.jQuery(".sa_mod_<?php echo $moduleid?> .ad_prev_main").each(function()
			{
				if (techjoomla.jQuery(this).attr('ad_entry_number'))
				{
					sa_init(this, module_id, ad_rotationdelay);
				}
			});

		});
	</script>
<?php
}

// Display ad html
$user   = JFactory::getUser();
$reqURI = JUri::root();
?>
<div class="sa_mod_<?php echo $moduleid?> modsa" havezone="<?php echo $zone_id?>">
<?php
//~ $cache = JFactory::getCache('mod_socialads');
$cache = JFactory::getCache('mod_socialads_' . $moduleid);

if ($sa_params->get('enable_caching') == 1)
{
	$cache->setCaching(1);
}
else
{
	$cache->setCaching(0);
}

foreach ($ads as $ad)
{
	// $addata  = $cache->call(array($adRetriever, 'getAdDetails'), $ad);
	$addata = $cache->call(array(SaAdEngineHelper::getInstance(), 'getAdDetails'), $ad);

	// $adHTML  = $cache->call(array($adRetriever, 'getAdHtml'), $addata, 0, $params->get('ad_rotation', 0), $moduleid);
	$adHTML  = $cache->call(array(SaAdEngineHelper::getInstance(), 'getAdHtml'), $addata, 0, $params->get('ad_rotation', 0), $moduleid);

	echo $adHTML;

	/*echo "<pre>";
	$e = new Exception;
	var_dump($e->getTraceAsString());
	echo "</pre>";*/

	// This is for feedback
	if ($sa_params->get('feedback_on_ignore') != 0)
	{
		?>
		<div id="feedback_msg<?php echo $addata->ad_id; ?>" class="ad_prev_main_feedback alert alert-info  alert-help-inline" style="display:none;">
		<?php echo JText::_('MOD_SOCIALADS_FEEDBACK_MSG'); ?>
		</div>
		<div id="feedback<?php echo $addata->ad_id; ?>" class="" style="display:none;">
		<input id="undo" type="button" name="undo" value="<?php echo JText::_('MOD_SOCIALADS_UNDO'); ?>" onclick ="saRender.undoIgnore(this,<?php echo $addata->ad_id; ?>)" class="btn btn-info"/>
		<input type="radio" name="group1" value="<?php 	echo JText::_('MOD_SOCIALADS_UNINTRESTING'); ?>" onclick ="saRender.ignoreFeedback(this,<?php echo $addata->ad_id; ?>)" /><?php echo JText::_('MOD_SOCIALADS_UNINTRESTING'); ?><br />
		<input type="radio" name="group1" value="<?php echo JText::_('MOD_SOCIALADS_IRRELEVANT'); ?>" onclick="saRender.ignoreFeedback(this,<?php echo $addata->ad_id; ?>)" /><?php echo JText::_('MOD_SOCIALADS_IRRELEVANT'); ?><br />
		<input type="radio" name="group1" value="<?php echo JText::_('MOD_SOCIALADS_MISLEADING'); ?>" onclick="saRender.ignoreFeedback(this,<?php echo $addata->ad_id; ?>)" /><?php echo JText::_('MOD_SOCIALADS_MISLEADING'); ?><br />
		<input type="radio" name="group1" value="<?php echo JText::_('MOD_SOCIALADS_OFFENSIVE'); ?>" onclick="saRender.ignoreFeedback(this,<?php echo $addata->ad_id; ?>)" /><?php echo JText::_('MOD_SOCIALADS_OFFENSIVE'); ?><br />
		<input type="radio" name="group1" value="<?php echo JText::_('MOD_SOCIALADS_REPETATIVE'); ?>" onclick="saRender.ignoreFeedback(this,<?php echo $addata->ad_id; ?>)" /><?php echo JText::_('MOD_SOCIALADS_REPETATIVE'); ?><br />
		<input type="radio" name="group1" value="<?php echo JText::_('MOD_SOCIALADS_OTHER'); ?>" onclick="saRender.ignoreFeedback(this,<?php echo $addata->ad_id; ?>)" /><?php echo JText::_('OTHER'); ?><br />
		</div>
		<?php
	}

	// Load JBolo Chat HTML
	if ($sa_params->get('jbolo_integration'))
	{
		require JModuleHelper::getLayoutPath('mod_socialads', $params->get('layout', 'jbolo_layout'));
	}

	// Load social sharing buttons
	if ($sa_params->get('social_sharing'))
	{
		require JModuleHelper::getLayoutPath('mod_socialads', $params->get('layout', 'social_layout'));
	}

}

// To show create ad link
if($params->get('create',1))
{
	if( $params->get('create_page',0) == 0)
		$createpage = "";
	else
		$createpage = "_blank";
	$my = JFactory::getUser();
	$link = JUri::root().substr(JRoute::_('index.php?option=com_socialads&view=adform&adtype='.$ad_type.'&adzone='.$zone_id.'&Itemid='.$Itemid),strlen(JUri::base(true))+1);

	if ($params->get('adlink_secure',0) == 1)
	{
		$link = str_replace('http:','https:',$link );
	}

	if (!$my->id)
	{
		if ( $params->get('create_guest') == 1)
		{
			?>
			<div style="clear:both;"></div>
			<div class="ad_create_link">
				<a class ="create" target="<?php echo $createpage; ?>" href="<?php echo $link; ?>"><?php echo $params->get('create_text','Create Ad'); ?>
				</a>
			</div>
	<?php
		}
	}
	else
	{
	?>
		<div style="clear:both;"></div>
		<div class="ad_create_link">
			<a class ="create" target="<?php echo $createpage; ?>" href="<?php echo $link; ?>"><?php echo $params->get('create_text','Create Ad'); ?>
			</a>
		</div>
	<?php
	}
}
?>
<div style="clear:both;"></div>
</div>
