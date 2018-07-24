<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php
if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php'))
{
	require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php');
} else if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'settings.php'))
{
	require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'settings.php');
}
	

function FSS_GetPublishedText($ispub)
{
	if (FSSJ3Helper::IsJ3())
	{
		if ($ispub)
		{
			return "<i class='icon-publish'></i>";
			//return '<span class="state publish"><span class="text"><i class="icon-publish"></i>'.JText::_('Published').'</span></span>';
		} else {
			return "<i class='icon-unpublish'></i>";
		}
	} else
	{
		if ($ispub)
		{
			return '<span class="state publish"><span class="text">'.JText::_('Published').'</span></span>';
		} else {
			return '<span class="state unpublish"><span class="text">'.JText::_('Unpublished').'</span></span>';
		}
	}
}

function FSS_GetFeaturedText($ispub)
{
	if (FSSJ3Helper::IsJ3())
	{
		if ($ispub)
		{
			return "<i class='icon-star'></i>";
			//return '<span class="state publish"><span class="text"><i class="icon-publish"></i>'.JText::_('Published').'</span></span>';
		} else {
			return "<i class='icon-star-empty'></i>";
		}
	} else
	{
		if (!$ispub)
		{
			return '<img src="templates/bluestork/images/admin/disabled.png" alt="'.JText::_('Featured_FAQ').'">';
			//return '<span class="state featured"><span class="text">'.JText::_('Featured').'</span></span>';
		} else {
			return '<img src="templates/bluestork/images/admin/featured.png" alt="'.JText::_('Unfeatured_FAQ').'">';
			//return '<span class="state unfeatured"><span class="text">'.JText::_('Unfeatured').'</span></span>';
		}
	}
}

function FSS_GetModerationText($ispub)
{
	$src = JURI::base() . "components/com_fss/assets/images/mod";
	if ($ispub == 2)
	{
		return "<img src='$src/declined.png' width='24' height='24' border='0' alt='".JText::_('DECLINED')."'/>";	
	}
	if ($ispub == 1)
	{
		return "<img src='$src/accepted.png' width='24' height='24' border='0' alt='".JText::_('ACCEPTED')."'/>";	
	}
	if ($ispub == 0)
	{
		return "<img src='$src/waiting.png' width='24' height='24' border='0' alt='".JText::_('AWAITING_MODERATION')."'/>";	
	}
}

function FSS_GetYesNoText($ispub)
{
	$img = 'tick.png';
	$alt = JText::_("YES");

	if ($ispub == 0)
	{
		$img = 'cross.png';
		$alt = JText::_("NO");
	}
	$src = JURI::base() . "/components/com_fss/assets";
	return '<img src="' . $src . '/' . $img . '" width="16" height="16" border="0" alt="' . $alt .'" />';	
}
