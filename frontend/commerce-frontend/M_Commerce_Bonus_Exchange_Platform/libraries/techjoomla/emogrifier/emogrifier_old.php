<?php
/*
UPDATES

	2008-08-10  Fixed CSS comment stripping regex to add PCRE_DOTALL (changed from '/\/\*.*\*\//U' to '/\/\*.*\*\//sU')
	2008-08-18  Added lines instructing DOMDocument to attempt to normalize HTML before processing
	2008-10-20  Fixed bug with bad variable name... Thanks Thomas!
	2008-03-02  Added licensing terms under the MIT License
				Only remove unprocessable HTML tags if they exist in the array
	2009-06-03  Normalize existing CSS (style) attributes in the HTML before we process the CSS.
				Made it so that the display:none stripper doesn't require a trailing semi-colon.
	2009-08-13  Added support for subset class values (e.g. "p.class1.class2").
				Added better protection for bad css attributes.
				Fixed support for HTML entities.
	2009-08-17  Fixed CSS selector processing so that selectors are processed by precedence/specificity, and not just in order.
	2009-10-29  Fixed so that selectors appearing later in the CSS will have precedence over identical selectors appearing earlier.
	2009-11-04  Explicitly declared static functions static to get rid of E_STRICT notices.
	2010-05-18  Fixed bug where full url filenames with protocols wouldn't get split improperly when we explode on ':'... Thanks Mark!
				Added two new attribute selectors
	2010-06-16  Added static caching for less processing overhead in situations where multiple emogrification takes place
	2010-07-26  Fixed bug where '0' values were getting discarded because of php's empty() function... Thanks Scott!
	2010-09-03  Added checks to invisible node removal to ensure that we don't try to remove non-existent child nodes of parents that have already been deleted
*/
class TjEmogrifier
{
	private $html = '';
	private $css = '';
	private $unprocessableHTMLTags = array('wbr');

	public function __construct($html = '', $css = '')
	{
		$this->html = $html;
		$this->css  = $css;
	}

	public function setHTML($html = '')
	{
		$this->html = $html;
	}

	public function setCSS($css = '')
	{
		$this->css = $css;
	}

		// There are some HTML tags that DOMDocument cannot process, and will throw an error if it encounters them.
		// These functions allow you to add/remove them if necessary.
		//It only strips them from the code (does not remove actual nodes).
	public function addUnprocessableHTMLTag($tag)
	{
		$this->unprocessableHTMLTags[] = $tag;
	}
	public function removeUnprocessableHTMLTag($tag)
	{
		if (($key = array_search($tag,$this->unprocessableHTMLTags)) !== false)
		{
			unset($this->unprocessableHTMLTags[$key]);
		}
	}

		// Applies the CSS you submit to the html you submit. places the css inline
	public function emogrify()
	{
		$body = $this->html;

		// Process the CSS here, turning the CSS style blocks into inline css
		if (count($this->unprocessableHTMLTags))
		{
			$unprocessableHTMLTags = implode('|', $this->unprocessableHTMLTags);
			$body = preg_replace("/<($unprocessableHTMLTags)[^>]*>/i", '', $body);
		}

		$encoding = mb_detect_encoding($body);
		$body = mb_convert_encoding($body, 'HTML-ENTITIES', $encoding);
		$xmldoc = new DOMDocument;
		$xmldoc->encoding = $encoding;
		$xmldoc->strictErrorChecking = false;
		$xmldoc->formatOutput = true;
		@$xmldoc->loadHTML($body);
		$xmldoc->normalizeDocument();
		$xpath = new DOMXPath($xmldoc);

		// Before be begin processing the CSS file, parse the document and normalize all existing CSS attributes (changes 'DISPLAY: none' to 'display: none');
		// We wouldn't have to do this if DOMXPath supported XPath 2.0.
		$nodes = @$xpath->query('//*[@style]');

		// Snehal - Change for Bug #60937
		// If ($nodes->length > 0) foreach ($nodes as $node) $node->setAttribute('style',preg_replace('/[A-z\-]+(?=\:)/Se',"strtolower('\\0')",$node->getAttribute('style')));
		if ($nodes->length > 0)
			foreach ($nodes as $node)
			{
				$result = preg_replace_callback("/[A-z\-]+(?=\:)/S",
						function($m)
						{
							return strtolower($m[0]);
						},

						$node->getAttribute('style')
				);
				$node->setAttribute('style', $result);
			}

		// Get rid of css comment code
		$re_commentCSS = '/\/\*.*\*\//sU';
		$css = preg_replace($re_commentCSS,'',$this->css);
		static $csscache = array();
		$csskey = md5($css);

		if (!isset($csscache[$csskey]))
		{
			// Process the CSS file for selectors and definitions
			$re_CSS = '/^\s*([^{]+){([^}]+)}/mis';
			preg_match_all($re_CSS, $css, $matches);
			$all_selectors = array();

			foreach ($matches[1] as $key => $selectorString)
			{
				// If there is a blank definition, skip
				if (!strlen(trim($matches[2][$key]))) continue;

				// Else split by commas and duplicate attributes so we can sort by selector precedence
				$selectors = explode(',',$selectorString);

				foreach ($selectors as $selector)
				{
					// Don't process pseudo-classes
					if (strpos($selector,':') !== false) continue;
					$all_selectors[] = array('selector' => $selector,
											 'attributes' => $matches[2][$key],
											 'index' => $key,
											 // Keep track of where it appears in the file, since order is important
					);
				}
			}

			// Now sort the selectors by precedence
			usort($all_selectors, array('self','sortBySelectorPrecedence'));
			$csscache[$csskey] = $all_selectors;
		}

		foreach ($csscache[$csskey] as $value)
		{
			// Query the body for the xpath selector
			$nodes = $xpath->query($this->translateCSStoXpath(trim($value['selector'])));

			if($nodes)
				foreach($nodes as $node)
				{
					// If it has a style attribute, get it, process it, and append (overwrite) new stuff
					if ($node->hasAttribute('style'))
					{
						// Break it up into an associative array
						$oldStyleArr = $this->cssStyleDefinitionToArray($node->getAttribute('style'));
						$newStyleArr = $this->cssStyleDefinitionToArray($value['attributes']);

						// New styles overwrite the old styles (not technically accurate, but close enough)
						$combinedArr = array_merge($oldStyleArr,$newStyleArr);
						$style = '';
						foreach ($combinedArr as $k => $v) $style .= (strtolower($k) . ':' . $v . ';');
					}
					else
					{
						// Otherwise create a new style
						$style = trim($value['attributes']);
					}

					$node->setAttribute('style',$style);
				}
		}

		// This removes styles from your email that contain display:none. You could comment these out if you want.
		$nodes = $xpath->query('//*[contains(translate(@style," ",""),"display:none")]');

		// The checks on parentNode and is_callable below are there to ensure that if we've deleted the parent node,
		// We don't try to call removeChild on a nonexistent child node
		if ($nodes->length > 0) foreach ($nodes as $node) if ($node->parentNode && is_callable(array($node->parentNode,'removeChild'))) $node->parentNode->removeChild($node);
			return $xmldoc->saveHTML();
	}

	private static function sortBySelectorPrecedence($a, $b)
	{
		$precedenceA = self::getCSSSelectorPrecedence($a['selector']);
		$precedenceB = self::getCSSSelectorPrecedence($b['selector']);

		// We want these sorted ascendingly so selectors with lesser precedence get processed first and
		// Selectors with greater precedence get sorted last
		return ($precedenceA == $precedenceB) ? ($a['index'] < $b['index'] ? -1 : 1) : ($precedenceA < $precedenceB ? -1 : 1);
	}

	private static function getCSSSelectorPrecedence($selector)
	{
		static $selectorcache = array();
		$selectorkey = md5($selector);

		if (!isset($selectorcache[$selectorkey]))
		{
			$precedence = 0;
			$value = 100;
			$search = array('\#', '\.', ''); // ids: worth 100, classes: worth 10, elements: worth 1

			foreach ($search as $s)
			{
				if (trim($selector == '')) break;
				$num = 0;
				$selector = preg_replace('/' . $s . '\w+/', '', $selector, -1, $num);
				$precedence += ($value * $num);
				$value /= 10;
			}

			$selectorcache[$selectorkey] = $precedence;
		}

		return $selectorcache[$selectorkey];
	}

	// Right now we support all CSS 1 selectors and /some/ CSS2/3 selectors.
	// http://plasmasturm.org/log/444/
	private function translateCSStoXpath($css_selector)
	{
		$css_selector = trim($css_selector);
		static $xpathcache = array();
		$xpathkey = md5($css_selector);

		if (!isset($xpathcache[$xpathkey]))
		{
			// Returns an Xpath selector
			$search = array(
								'/\s+>\s+/', // Matches any F element that is a child of an element E.
								'/(\w+)\s+\+\s+(\w+)/', // Matches any F element that is a child of an element E.
								'/\s+/', // Matches any F element that is a descendant of an E element.
								'/(\w)\[(\w+)\]/', // Matches element with attribute
								'/(\w)\[(\w+)\=[\'"]?(\w+)[\'"]?\]/', // Matches element with EXACT attribute
								'/(\w+)?\#([\w\-]+)/e', // Matches id attributes
								'/(\w+|\*)?((\.[\w\-]+)+)/e', // Matches class attributes
			);
			$replace = array(
								'/',
								'\\1/following-sibling::*[1]/self::\\2',
								'//',
								'\\1[@\\2]',
								'\\1[@\\2="\\3"]',
								"(strlen('\\1') ? '\\1' : '*').'[@id=\"\\2\"]'",
								"(strlen('\\1') ? '\\1' : '*').'[contains(concat(\" \",@class,\" \"),concat(\" \",\"'.implode('\",\" \"))][contains(concat(\" \",@class,\" \"),concat(\" \",\"',explode('.',substr('\\2',1))).'\",\" \"))]'",
			);

			$xpathcache[$xpathkey] = '//' . preg_replace($search,$replace,$css_selector);
		}

		return $xpathcache[$xpathkey];
	}

	private function cssStyleDefinitionToArray($style)
	{
		$definitions = explode(';', $style);
		$retArr = array();
		foreach ($definitions as $def)
		{
			if (empty($def) || strpos($def, ':') === false) continue;
			list($key,$value) = explode(':', $def, 2);
			if (empty($key) || strlen(trim($value)) === 0) continue;
			$retArr[trim($key)] = trim($value);
		}

		return $retArr;
	}
}
