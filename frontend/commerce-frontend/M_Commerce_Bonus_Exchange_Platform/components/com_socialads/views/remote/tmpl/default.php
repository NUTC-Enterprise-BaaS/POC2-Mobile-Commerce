<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// $params = $this->adRetriever ;
// $adRetriever = $this->adRetriever;

$lang =  JFactory::getLanguage();
$lang->load('mod_socialads', JPATH_ROOT);

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root(true) . 'modules/mod_socialads/css/style.css');

//if($params->get('ad_rotation',0) == 1)
if ($this->ad_rotation == 1);
{
	SaCommonHelper::loadScriptOnce(JUri::root(true) . '/media/com_sa/vendors/flowplayer/flowplayer-3.2.13.min.js');
	SaCommonHelper::loadScriptOnce(JUri::root(true) . '/media/com_sa/js/rotation.js');

	// $ad_rotationdelay = $this->ad_rotation_delay;
	?>
	<script>
		var site_link="";
		var user_id="";

		techjoomla.jQuery(document).ready(function(){
			var countdown;
			var module_id = '<?php echo $this->moduleid; ?>';
			var ad_rotationdelay = <?php echo $this->ad_rotation_delay; ?>;
			techjoomla.jQuery(".sa_mod_<?php echo $this->moduleid; ?> .ad_prev_main").each(function(){
				if(techjoomla.jQuery(this).attr('ad_entry_number')){
					sa_init(this,module_id, ad_rotationdelay);
				}
			});
		});
	</script>
	<?php
}

// Display ad html
// $user   = JFactory::getUser();
// $reqURI = JUri::root();
?>

<div class="sa_mod_<?php echo $this->moduleid; ?>"  havezone="<?php echo $this->zone; ?>" >
	<?php
	foreach($this->ads as $ad)
	{
		// $addata = $adRetriever->getAdDetails($ad);
		$addata = RemoteSaAdEngineHelper::getInstance()->getAdDetails($ad);

		//echo $adRetriever->getAdHTML($addata, 0, $params->get('ad_rotation', 0), $this->moduleid);
		echo RemoteSaAdEngineHelper::getInstance()->getAdHtml($addata, 0, $this->ad_rotation, $this->moduleid);
	}
	?>
</div>
