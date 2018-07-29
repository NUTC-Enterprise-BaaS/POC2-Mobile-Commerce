<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FSSView extends JViewLegacy
{
	function snippet($filename)
	{
		// parse path and extract view and file, call snip();
		
		$key = "com_fss".DS."views".DS;
		
		$pos = stripos($filename, $key);
		
		if ($pos === FALSE)	return $filename;

		$snip = substr($filename, $pos + strlen($key));
		
		list($view, $chunk) = @explode(DS."snippet".DS, $snip);

		if (!$view || !$chunk) return $filename;

		return $this->snip($view, $chunk);
	}
	
	function snip($view, $file)
	{
		$filename = $this->findSnip($view, $file);
		
		if (!$filename) $filename = JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.$view.DS.'snippet'.DS.$file;
		
		return $filename;
	}
	
	public function findSnip($view, $file)
	{
		// Clear prior output
		$this->_output = null;

		$template = JFactory::getApplication()->getTemplate();

		// Create the template file name based on the layout
		$file = "snippet" . $file;
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', $file);

		$paths = array(JPATH_ROOT.DS.'templates'.DS.$template.DS.'html'.DS.'com_fss'.DS.$view);

		// Load the template script
		jimport('joomla.filesystem.path');
		$this->_template = JPath::find($paths, $file);

		return $this->_template;
	}
}
	 	  			  	  	 	