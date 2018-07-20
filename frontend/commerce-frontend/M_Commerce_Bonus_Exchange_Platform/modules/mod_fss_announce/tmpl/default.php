<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
FSS_Helper::ModuleStart("mod_fss_announce");
?>

<?php if ($maxheight > 0): ?>
<script>

jQuery(document).ready(function () {
	setTimeout("announce_scrollDown()",3000);
});

function announce_scrollDown()
{
	var settings = { 
		direction: "down", 
		step: 40, 
		scroll: true, 
		onEdge: function (edge) { 
			if (edge.y == "bottom")
			{
				setTimeout("announce_scrollUp()",3000);
			}
		} 
	};
	jQuery(".fss_announce_scroll").autoscroll(settings);
}

function announce_scrollUp()
{
	var settings = { 
		direction: "up", 
		step: 40, 
		scroll: true,    
		onEdge: function (edge) { 
			if (edge.y == "top")
			{
				setTimeout("announce_scrollDown()",3000);
			}
		} 
	};
	jQuery(".fss_announce_scroll").autoscroll(settings);
}
</script>

<style>
#fss_announce_scroll {
	max-height: <?php echo $maxheight; ?>px;
	overflow: hidden;
}
</style>
<?php endif; ?>

<div id="fss_announce_scroll" class="fss_announce_scroll">

<?php 
foreach($rows as $announce)
{
	$parser->SetVar('title', $announce['title']);
	$parser->SetVar('subtitle', $announce['subtitle']);
	$parser->SetVar('date', FSS_Helper::Date($announce['added'], FSS_DATE_MID));
	$parser->SetVar('time', FSS_Helper::Date($announce['added'], FSS_TIME_SHORT));
	$parser->SetVar('body', $announce['body']);
	$parser->SetVar('link', FSSRoute::_( 'index.php?option=com_fss&view=announce&announceid=' . $announce['id'] ));
	
	$authid = $announce['author'];
	$user = JFactory::getUser($authid);
	if ($user->id > 0)
	{
		$parser->setVar('author', $user->name);	
		$parser->setVar('author_username', $user->username);	
	} else {
		$parser->setVar('author', JText::_('UNKNOWN'));	
		$parser->setVar('author_username', JText::_('UNKNOWN'));	
	}

	echo $parser->Parse();
}
?>

</div>

<?php if ($params->get('show_more')) : ?>
<div class='fss_mod_announce_all'><a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=announce&announceid=' ); ?>'><?php echo JText::_("SHOW_ALL_ANNOUNCEMENTS"); ?></a></div>
<?php endif; ?>

<?php FSS_Helper::ModuleEnd(); ?>