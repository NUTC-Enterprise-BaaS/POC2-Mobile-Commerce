<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class FSS_CSSParse
{
	static $added = array();
	
	static function OutputCSS($file, $use_less17 = false)
	{
		if (in_array($file, self::$added))
			return;
		
		$jpc = JPATH_CACHE;
		$jpc = str_ireplace("administrator", "", $jpc);
		
		if (!file_exists($jpc.DS.'fss'.DS.'css'))
			mkdir($jpc.DS.'fss'.DS.'css',0777,true);
		
		$in_file = JPATH_ROOT.DS.$file;
		
		if (!is_file($in_file))
		{
			echo "Missing $in_file<br>";
			return;
		}

		$out_filename = str_replace(".less",".css",str_replace("/","_",str_replace("\\","_",$file)));
	
		if (JFactory::getDocument()->direction == "rtl")
			$out_filename = "rtl_" . $out_filename;
	
		if (FSS_Settings::get('css_indirect'))
			$out_filename = str_replace(".","_", $out_filename);
				
		$out_file = $jpc.DS.'fss'.DS.'css'.DS.$out_filename;
		
		if (!is_file($out_file) || filemtime($in_file) > filemtime($out_file)) {

			if ($use_less17)
			{
				if (!class_exists("Less_Parser")) require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'lessc170.php');
				$less = new Less_Parser;
				$less->parseFile($in_file);
				$output = $less->getCss();
			} else {
				require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'lessc.php');
				$less = new fss_lessc;
				$output = $less->compileFile($in_file);
			}

			if (JFactory::getDocument()->direction == "rtl")
				$output .= self::rtlCSS($output);
			
			JFile::write($out_file, $output);
		}

		$document = JFactory::getDocument();
		if (FSS_Settings::get('css_indirect'))
		{
			$document->addStyleSheet(JRoute::_("index.php?option=com_fss&view=css&file=" . $out_filename, false));
		} else {
			$document->addStyleSheet(JURI::root(true) . "/cache/fss/css/" . $out_filename);
		}
		
		self::$added[] = $file;
	}	
	
	static function OutputCSS_New($file)
	{
		if (in_array($file, self::$added))
			return;
		
		$jpc = JPATH_CACHE;
		$jpc = str_ireplace("administrator", "", $jpc);
		
		if (!file_exists($jpc.DS.'fss'.DS.'css'))
			mkdir($jpc.DS.'fss'.DS.'css',0777,true);
		
		$in_file = JPATH_ROOT.DS.$file;
		
		if (!is_file($in_file))
		{
			echo "Missing $in_file<br>";
			return;
		}

		$out_filename = str_replace(".less",".css",str_replace("/","_",str_replace("\\","_",$file)));
	
		if (JFactory::getDocument()->direction == "rtl")
			$out_filename = "rtl_" . $out_filename;
	
		if (FSS_Settings::get('css_indirect'))
			$out_filename = str_replace(".","_", $out_filename);
				
		$out_file = $jpc.DS.'fss'.DS.'css'.DS.$out_filename;
		
		if (!is_file($out_file) || filemtime($in_file) > filemtime($out_file)) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'lessc170.php');

			//jimport('fsj_core.less.lessc');
			$less = new Less_Parser;
			//$less = new \fss_lessc170\Less_Parser;
			$less->parseFile($in_file);
			$output = $less->getCss();
			
			if (JFactory::getDocument()->direction == "rtl")
				$output .= self::rtlCSS($output);
			
			JFile::write($out_file, $output);
		}

		$document = JFactory::getDocument();
		if (FSS_Settings::get('css_indirect'))
		{
			$document->addStyleSheet(JRoute::_("index.php?option=com_fss&view=css&file=" . $out_filename, false));
		} else {
			$document->addStyleSheet(JURI::root(true) . "/cache/fss/css/" . $out_filename);
		}
		
		self::$added[] = $file;
	}	
		
	static function OutputCSSText($ident, $css, $updated)
	{
		$jpc = JPATH_CACHE;
		$jpc = str_ireplace("administrator", "", $jpc);

		if (!file_exists($jpc.DS.'fss'.DS.'css'))
			mkdir($jpc.DS.'fss'.DS.'css',0777,true);
		
		$out_filename = "$ident.css";
		$out_file = $jpc.DS.'fss'.DS.'css'.DS.$out_filename;
			
		if (!is_file($out_file) || $updated > filemtime($out_file)) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'lessc.php');
			$less = new lessc;
			$output = $less->compile($css);
			JFile::write($out_file, $output);
		}

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true) . "/cache/fss/css/" . $out_filename);
	}
	
	static $failed = array();
	static function ParseStaticFiles()
	{
		ob_start();
		
		$ok = true;
		
		self::$failed = array();
		
		if (!self::ParseStaticFile(JPATH_SITE.'/components/com_fss/assets/css/bootstrap/bootstrap_missing.less', JPATH_SITE.'/components/com_fss/assets/css/bootstrap/bootstrap_missing.parsed.less'))
		{
			$ok = false;
			self::$failed[] = JPATH_SITE.'/components/com_fss/assets/css/bootstrap/bootstrap_missing.parsed.less';
		}
		
		if (!self::ParseStaticFile(JPATH_SITE.'/components/com_fss/assets/css/variables.less', JPATH_SITE.'/components/com_fss/assets/css/variables.parsed.less'))
		{
			$ok = false;
			self::$failed[] = JPATH_SITE.'/components/com_fss/assets/css/variables.parsed.less';
		}
		
		if (FSS_Settings::get('bootstrap_variables') != "")
		{
			if (!self::ParseStaticFile(JPATH_SITE.'/components/com_fss/assets/css/bootstrap/variables.override.less', JPATH_SITE.'/components/com_fss/assets/css/bootstrap/variables.parsed.less'))
			{
				$ok = false;
				self::$failed[] = JPATH_SITE.'/components/com_fss/assets/css/bootstrap/variables.parsed.less';
			}
		} else {
			file_put_contents(JPATH_SITE.'/components/com_fss/assets/css/bootstrap/variables.parsed.less', "");
		}
	
		if (!$ok)
			return false;	
		
		// tag the fss.less file so the parser knows it needs to be updated
		@touch(JPATH_SITE.'/components/com_fss/assets/css/fss.less');
		@touch(JPATH_SITE.'/components/com_fss/assets/css/bootstrap/bootstrap.less');
		@touch(JPATH_SITE.'/components/com_fss/assets/css/bootstrap/bootstrap_fssonly.less');
		
		return true;
	}
	
	static function ParseStaticFile($source, $dest)
	{
		ob_clean();
		include($source);
		$result = ob_get_clean();
		if (!@file_put_contents($dest, $result))
		{
			// try to chmod the file as rw
			@chmod($dest, 0777);
			
			if (!@file_put_contents($dest, $result)) return false;
		}
	
		return true;
	}
	
	
	static function rtlCSS($css_data, $escaped=array('.no-convert')) {
		
		$dir='RTL';
		
		//$css_data = file_get_contents($css_file);
		//remove comments 
		$css_data = preg_replace('/\/\*(.*)?\*\//Usi','' ,$css_data);
		//rewrite padding,margin,border
		$css_data = preg_replace('/(\h*)(padding|margin|border):(\d+.+)\h+(\d+.+)\h+(\d+.+)\h+(\d+.+)\h*;/Ui',"\\1\\2-right:\\4;\\1\\2-left:\\5;" ,$css_data);
		//rewrite border-radius 
		$css_data = preg_replace('/(\h*|)border-radius:(.+)\h+(.+)\h+(.+)\h+(.+)\h*;/Ui',"\\1border-top-left-radius:\\2;\\1border-top-".
			"right-radius:\\3;\\1border-bottom-right-radius:\\4;\\1border-bottom-left-radius:\\5;", $css_data);
		//start parsing css file
		$css_data = preg_replace('/(@media .+){(.+)}\s*}/Uis', '\1$$$\2}$$$', $css_data);
		preg_match_all('/(.+){(.+)(}\$\$\$|})/Uis', $css_data, $css_arr);
		$css_flipped    = "/* Created by flipcss.php 0.7 by daif alotaibi (http://daif.net) */\n\n";
		foreach($css_arr[0] as $key=>$val) {
			//ignore escaped classes
			if(!preg_match('/('.implode('|', array_map('preg_quote', $escaped)).')/i', $css_arr[1][$key])) {
				if(preg_match('/left|right/i', $css_arr[2][$key])) {
					if($rules = FSS_CSSParse::rtlCSSRule($css_arr[2][$key])) {
						$css_flipped .= trim(str_replace('$$$','{',$css_arr[1][$key]));
						$css_flipped .= " {\n\t".trim($rules)."\n";
						$css_flipped .= str_replace('$$$',"\n}",$css_arr[3][$key])."\n\n";
					}
				}
			}
		}
		
		return $css_flipped;
	}
	
	static function rtlCSSRule($rules) {
		$return         = '';
		$rules_arr      = explode(";", $rules);
		foreach($rules_arr as $rule) {
			//ignore rules that doesn't need flipping
			if(preg_match('/(left|right)/i', $rule)) {
				//flip float
				if(preg_match('/float\h*:\h*(.+)/i', $rule, $rule_arr)) {
					$rule = 'float: '.((trim($rule_arr[1])=='left')?'right':'left');
					$return .="\t".trim($rule).";\n";
					
					//flip text-align
				} elseif(preg_match('/text-align\h*:\h*(.+)/i', $rule, $rule_arr)) {
					$rule = 'text-align: '.((trim($rule_arr[1])=='left')?'right':'left');
					$return .="\t".trim($rule).";\n";
					
					//flip padding, margin, border
				} elseif(preg_match('/(\*|)(margin|padding|border)-(left|right)\h*:\h*(.+)/i', $rule, $rule_arr)) {
					$dir = ((trim($rule_arr[3])=='left')?'right':'left');
					//reset direction rule
					if((trim($rule_arr[3]) == 'left' && !preg_match('/'.trim($rule_arr[2]).'\-right/i', $rules)) || (trim($rule_arr[2]) == 'right' && !preg_match('/'.trim($rule_arr[2]).'\-left/i', $rules))) {
						$rule = trim($rule_arr[1]).trim($rule_arr[2]).'-'.$rule_arr[3].": 0;\n\t";
					} else {
						$rule = '';
					}
					$rule .= trim($rule_arr[1]).trim($rule_arr[2]).'-'.$dir.': '.$rule_arr[4];
					$return .="\t".trim($rule).";\n";
					
					//flip border-radius
				} elseif(preg_match('/border-(top|bottom)-(left|right)-radius\h*:\h*(.+)/i', $rule, $rule_arr)) {
					$dir = ((trim($rule_arr[2])=='left')?'right':'left');
					//reset direction rule
					if((trim($rule_arr[2]) == 'left' && !preg_match('/'.trim($rule_arr[1]).'\-right/i', $rules)) || (trim($rule_arr[2]) == 'right' && !preg_match('/'.trim($rule_arr[1]).'\-left/i', $rules))) {
						$rule = 'border-'.$rule_arr[1].'-'.$rule_arr[2].'-radius: 0;'."\n\t";
					} else {
						$rule = '';
					}
					//write new direction rule
					$rule .= 'border-'.$rule_arr[1].'-'.$dir.'-radius: '.$rule_arr[3];
					$return .="\t".trim($rule).";\n";
					
					//flip left, right
				} elseif(preg_match('/\h+(left|right)\h*:\h*(.+)/i', $rule, $rule_arr)) {
					$dir = ((trim($rule_arr[1])=='left')?'right':'left');
					//reset LTR rule
					if((trim($rule_arr[1]) == 'left' && !preg_match('/\h+right\h*:/i', $rules)) || (trim($rule_arr[1]) == 'right' && !preg_match('/\h+left\h*:/i', $rules))) {
						$rule = trim($rule_arr[1]).": auto;\n\t";
					} else {
						$rule = '';
					}
					$rule .= $dir.': '.$rule_arr[2];
					$return .="\t".trim($rule).";\n";
				}
			}
		}
		return($return);
	}
}