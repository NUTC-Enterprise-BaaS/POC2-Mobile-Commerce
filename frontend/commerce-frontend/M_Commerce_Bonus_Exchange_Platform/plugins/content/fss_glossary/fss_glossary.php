<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');
	require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');
	require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php');
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );

	class plgContentfss_glossary extends JPlugin
	{
		public function onContentPrepare($context, &$row, &$params, $page = 0)
		{
			if (is_object($row))
			{
				if (property_exists($row, "id"))
					$context .= "." . $row->id;	
			} else if (is_array($row))
			{
				if (array_key_exists("id", $row))
					$context .= "." . $row['id'];
			}
			
			FSS_Glossary::$context = $context;
					
			$ignore = FSS_Settings::Get('glossary_ignore');
			$option = FSS_Input::getCmd('option');
			
			// fix fabrik hiding itself. only works with non sef urls
			if (isset($_GET['option']) && $_GET['option'] == "com_fabrik") $option = "com_fabrik";

			if (trim($ignore) != "")
			{
				$ignore = explode("\n", $ignore);
						
				foreach ($ignore as $ign)
				{
					$ign = trim($ign);
					if ($ign == "") continue;
							
					if (stripos($context, $ign) !== FALSE)
					{
						return true;	
					}
							
					if ($option)
					{
						if (stripos($option, $ign) !== FALSE)
						{
							return true;
						}	
					}
				}				
			}

			// skip plugin on freestyle components
			if (strpos($context, "_fss") > 0)
			{
				return true;
			}

			// Don't run this plugin when the content is being indexed
			if (strpos($context, 'finder.indexer') > 0) {
				return true;
			}

			if (is_object($row)) {
				if (!empty($row->noglossary)) // skip glossary plugin on fss content
					return true;
						
				//$row->text .= "\n\n\n<div style='display:none;' id='fss_glossary_context'>$context</div>\n\n\n";
						
				return $this->_glossary($row->text, $params);
						
			} else if (is_array($row)) {	
				//$row['text'] .= "\n\n\n<div style='display:none;' id='fss_glossary_context'>$context</div>\n\n\n";
				return $this->_glossary($row['text'], $params);
			}
					
			//$row .= "<div style='display:none;' id='fss_glossary_context'>$context</div>";
			return $this->_glossary($row, $params);
		}

		protected function _glossary(&$text, &$params)
		{
			$text = FSS_Glossary::ReplaceGlossary($text);
			$text .= FSS_Glossary::Footer();
					
			FSS_Helper::StylesAndJS();

			return true;
		}
	}

}