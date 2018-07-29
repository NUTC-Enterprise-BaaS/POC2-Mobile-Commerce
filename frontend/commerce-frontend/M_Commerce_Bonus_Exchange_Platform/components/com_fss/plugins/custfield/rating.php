<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class RatingPlugin extends FSSCustFieldPlugin
{
	var $name = "Rating";
	
	var $default_params = array();

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'rating'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return "";
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		// used when opening a ticket only!
		$input = "<input type='hidden' class='input-small' name='custom_$id' id='custom_{$id}' value='' >";
		$input .= SupportHelper::ratingChoose(0, "custom_$id", "inline", false, true, "CLICK_TO_RATE");
		return $input;
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		$canedit = true;
		
		$t = $context['ticket'];

		if (is_array($t))
		{
			$t = new SupportTicket($context['ticketid']);
			$t->load($context['ticketid'], true);
		}
		
		$t->setupUserPerimssions();

		$view = JRequest::getVar('view');
		if ($view == "ticket")
		{
			if ($t->readonly) $canedit = false;
			if ($t->isClosed()) $canedit = false;
		}

		if (!$canedit) return SupportHelper::displayRating($value, false);
		
		$rerate = true;
		$url = 'index.php?option=com_fss&view=ticket&task=update.customrating';
		return SupportHelper::ratingChoose($value, $context['ticketid'] . "-" . $id, JRoute::_($url), false, $rerate, "CLICK_TO_RATE");
	}
		
	function Save($id, $params, $value = null)
	{
		return $value;
	}

	function CanEdit()
	{
		return false;	
	}
}