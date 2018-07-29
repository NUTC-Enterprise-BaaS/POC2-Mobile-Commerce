<?php
/*------------------------------------------------------------------------
# plg_completeimagestyles - Complete Image Styles
# ------------------------------------------------------------------------
# version 2.1.4
# author Impression eStudio
# copyright Copyright (C) 2013 Impression eStudio. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.impression-estudio.gr
# Technical Support: info@impression-estudio.gr
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
jimport( 'joomla.plugin.plugin' );

class plgSystemCompleteImageStyles extends JPlugin
{	
	function plgSystemCompleteImageStyles( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}
	
	function onAfterRoute() 
	{
		// Load jQuery before all other scripts using onAfterRoute event.	<----------- IMPORTANT.
		if (strcmp(substr(JURI::base(), -15), "/administrator/")!=0)	// Apply styles only to front-end.
		{
			$enabled = false;
			$javascript = '';

			$string_styles = $this->params->get('styles');
			$array_styles = json_decode($string_styles, true);

			for ($i=1; $i<=15; $i++)
			{	
				if (isset($array_styles[$i]['enabled']) && $array_styles[$i]['enabled'] == 1)
				{
					$enabled = true;
				}
			}
			
			if ($enabled)
			{
				// Load jQuery.
				$document = JFactory::getDocument();
				$version = new JVersion();
				$application = JFactory::getApplication();	
				if (strcmp($version->RELEASE, "2.5")==0)
				{
					JHtml::_('behavior.framework');	// Load jQuery for Joomla 2.5
				}
				else
				{
					JHtml::_('jquery.framework');		// Load jQuery for Joomla 3.x or newer.
				}
			}
		}
	}
	
	function onBeforeCompileHead()
	{
		// Load CIS scripts at the end using onBeforeCompileHead event.	<----------- IMPORTANT.
		if (strcmp(substr(JURI::base(), -15), "/administrator/")!=0)	// Apply styles only to front-end.
		{
			$enabled = false;
			$javascript = '';

			$string_styles = $this->params->get('styles');
			$array_styles = json_decode($string_styles, true);

			for ($i=1; $i<=15; $i++)
			{	
				if (isset($array_styles[$i]) && isset($array_styles[$i]['enabled']) && $array_styles[$i]['enabled'] == 1)
				{
					$enabled = true;
					$javascript .= "completeImageStyles('".json_encode($array_styles[$i])."'); ";
				}
			}
			
			if ($enabled)
			{
				$document = JFactory::getDocument();
				
				$document->addScript(JURI::base().'plugins/system/completeimagestyles/completeimagestyles/js/imagesloaded.pkgd.min.js');
				$document->addScript(JURI::base().'plugins/system/completeimagestyles/completeimagestyles/js/cis.js');
				$document->addCustomTag('<script type="text/javascript"> jQuery(document).ready(function($) {'.$javascript.'}); </script>');
			}
		}
	}
}