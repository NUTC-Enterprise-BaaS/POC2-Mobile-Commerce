<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class LinkPlugin extends FSSCustFieldPlugin
{
	var $name = "Link";
	
	var $default_params = array(
		'target' => '_blank'
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'link'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'target'		=> FSS_Input::getCmd('link_target')
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		return "<input type='text' name='custom_$id' value='$current'>";
	}

	function Save($id, $params, $value = "")
	{
		return FSS_Input::getString("custom_$id");
	}

	function Display($value, $params, $context, $id) // output the field for display
	{
		$params = $this->parseParams($params);

		$link = $value;
		if (strtolower(substr($link, 0, 4)) != "http")
			$link = "http://" . $link;

		return "<a href='$link' target='".$params->target."'>$value</a>";
	}

	function CanEdit()
	{
		return true;	
	}
}