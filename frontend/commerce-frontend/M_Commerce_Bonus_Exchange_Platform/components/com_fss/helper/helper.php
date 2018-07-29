<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

global $fsjjversion;
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'settings.php' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'translate.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'input.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'guiplugins.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'route.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'view.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');

jimport( 'joomla.utilities.date' );
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

define('FSS_DATE_SHORT',0);
define('FSS_DATE_MID',1);
define('FSS_DATE_LONG',2);

define('FSS_TIME_SHORT',3);
define('FSS_TIME_LONG',4);

define('FSS_DATETIME_SHORT',5);
define('FSS_DATETIME_MID',6);
define('FSS_DATETIME_LONG',7);

define('FSS_DATETIME_MYSQL',8);

define('FSS_DATE_CUSTOM',9);

$FSSRoute_menus = array();
global $FSSRoute_menus;

require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'cssparse.php');

class FSS_Helper
{
	static $styles_incl = false;
	static $styles_sub_incl = array();
	static $message_labels = array();
	
	static function StylesAndJS($params = array(), $css = array(), $js = array())
	{
		$document = JFactory::getDocument();
		
		$force_jquery = false;
		if (isset($params['force_jquery']))
		{
			$force_jquery = true;
			unset($params['force_jquery']);
		}
		
		if (in_array('force_jquery', $params))
		{
			$force_jquery = true;
			$key = array_search('force_jquery', $params);
			unset($params[$key]);
		}
		
		if (!self::$styles_incl)
		{
			// jquery with its various options
			self::IncludeJQuery($force_jquery);

			// bootstrap (css and javascript)
			self::Bootstrap();

			//$document->addStyleSheet(FSSRoute::_( "index.php?option=com_fss&view=css&layout=default&old=1" )); // Add old stylesheets to page

			FSS_CSSParse::OutputCSS('components/com_fss/assets/css/fss.less');
			
			if (!FSSJ3Helper::IsJ3())
				FSS_CSSParse::OutputCSS('components/com_fss/assets/css/fss_j25.less');
				
			if (FSS_Settings::get('hide_warnings'))
				$document->addScriptDeclaration('var fss_no_warn = true;');
				
			$document->addScript( JURI::root(true).'/components/com_fss/assets/js/main.js' );	
			
			if (FSS_Settings::get('bootstrap_v3') && !JFactory::getApplication()->isAdmin())
			{
				FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bs3_fixes.less');
				$document->addScript( JURI::root(true).'/components/com_fss/assets/js/bs3_fixes.js' );	
			}


			self::$styles_incl = true;
		}
		
		foreach ($params as $param)
		{
			if (array_key_exists($param, self::$styles_sub_incl))
				continue;
			
			switch ($param)
			{
				case 'autoscroll':
					$document->addScript( JURI::root(true).'/components/com_fss/assets/js/jquery/jquery.autoscroll.js' );
					break;
				case 'tooltip':
					$document->addScript(JURI::root(true).'/components/com_fss/assets/js/fss_tooltip.js'); 
					break;
				case 'glossary':
					$document->addScript(JURI::root(true).'/components/com_fss/assets/js/glossary.js'); 
					break;
				case 'translate':
					$document->addScript( JURI::root(true).'/administrator/components/com_fss/assets/js/translate.js' );
					$document->addScript( JURI::root(true).'/administrator/components/com_fss/assets/js/popup.js' );
					$document->addStyleSheet(JURI::root(true).'/administrator/components/com_fss/assets/css/popup.css'); 
					break;
				case 'csstest':
					$document->addScript( JURI::root(true).'/administrator/components/com_fss/assets/js/csstest.js' );
					break;
				case 'admin_css':
					$document->addStyleSheet(JURI::root(true).'/administrator/components/com_fss/assets/css/main.css'); 
					break;
				case 'calendar':
					$document->addStyleSheet(JURI::root(true).'/components/com_fss/assets/css/calendar.css'); 
					$document->addStyleSheet(JURI::root(true).'/components/com_fss/assets/css/calendar_omega.css'); 
					$document->addScript(JURI::root(true).'/components/com_fss/assets/js/calendar.js'); 
					break;
				case 'base64':
					$document->addScript(JURI::root(true).'/components/com_fss/assets/js/base64.js'); 	
					break;
				case 'ticket_list':
					FSS_Translate_Helper::CalenderLocale();
					$document->addScript(JURI::root(true).'/components/com_fss/assets/js/ticket_list.js'); 	
					break;
				case 'cookie':
					$document->addScript(JURI::root(true).'/components/com_fss/assets/js/jquery/jquery.cookie.js'); 
					break;
				case 'scrollsneak':
					$document->addScript(JURI::root(true).'/components/com_fss/assets/js/scrollsneak.js'); 
					break;
			}
			
			self::$styles_sub_incl[$param] = 1;
		}
		
		foreach ($css as $c)
		{
			if (array_key_exists($c, self::$styles_sub_incl))
				continue;
			
			$document->addStyleSheet(JURI::root(true)."/".$c); 
			
			self::$styles_sub_incl[$c] = 1;
		}		
		
		foreach ($js as $j)
		{
			if (array_key_exists($j, self::$styles_sub_incl))
				continue;
			
			$document->addScript(JURI::root(true)."/".$j); 
			
			self::$styles_sub_incl[$j] = 1;
		}

		/* Joomla 3.4 fix for admin pagination problem */
		if (JFactory::getApplication()->isAdmin())
		{
			$version = new JVersion();
			if ($version->RELEASE >= 3.4)
			{
				$script = "
				jQuery(document).ready( function () {
					jQuery('.pagination-list').removeClass('pagination');
					jQuery('.pagination-toolbar').addClass('pagination');
				});";
				
				$document->addScriptDeclaration($script);
			}
		} 

		if (!JFactory::getApplication()->isAdmin() && FSS_Settings::get('artisteer_fixes'))
		{
			$document->addScript(JURI::root(true).'/components/com_fss/assets/js/artisteer.js'); 
			FSS_CSSParse::OutputCSS('components/com_fss/assets/css/artisteer.less');
		}

	}
	
	static function Bootstrap()
	{	
		// deal with bootstrap css
		$option = FSS_Settings::get('bootstrap_css');

		if (FSSJ3Helper::IsJ3())
		{
			JHtml::_('bootstrap.framework');
		} else {
			// include bootstrap js	
			
			if (FSS_Settings::get('bootstrap_js') == "yes" || JFactory::getApplication()->isAdmin())
			{
				$document = JFactory::getDocument();
				$document->addScript(JURI::root(true)."/components/com_fss/assets/js/bootstrap/bootstrap.js"); 
				
				//$option = "fssonly";
			}
		}

		if ($option == "fssonly")
		{
			FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap_fssonly.less');
		} else if ($option == "fssonlyv3")
		{
			FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap3/bootstrap3_fssonly.less', true);
		} else if ($option == "partial")
		{		
			FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap_missing.parsed.less');
		} else if ($option == "yes")
		{
			FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap.less');
		}
	}
	
	static function BootstrapAdminForce()
	{
		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true)."/components/com_fss/assets/js/bootstrap/bootstrap.js"); 
		FSS_CSSParse::OutputCSS('components/com_fss/assets/css/bootstrap/bootstrap.j25-admin.less');
	}
	
	static $jquery_incl = false;
	static function IncludeJQuery($force = false)
	{		
		if (FSS_Helper::$jquery_incl)
			return;
		
		FSS_Helper::$jquery_incl = true;
		
		$document = JFactory::getDocument();
		
		if (FSSJ3Helper::IsJ3())
		{
			JHtml::_('jquery.framework');
			return;	
		}
		
		$include = FSS_Settings::get('jquery_include');
		if ($include == "")
			$include = "auto";
		$url = JURI::root(true).'/components/com_fss/assets/js/jquery/jquery-1.10.2.min.js';
		$cpurl = JURI::root(true).'/components/com_fss/assets/js/jquery/jquery-migrate-1.2.1.min.js';
		$ncurl = JURI::root(true).'/components/com_fss/assets/js/jquery/jquery.noconflict.js';

		if ($force)
			$include = "yes";

		if ($include == "yes")
		{
			$document->addScript( $url );
			$document->addScript( $cpurl );
			$document->addScript( $ncurl );
			
		} else if ($include == "yesnonc") // yes, include it, but not with noconflict
		{
			$document->addScript( $url );
			$document->addScript( $cpurl );
		} else // auto detect mode
		{
			$found = false;
			
			foreach ($document->_scripts as $jsurl => $script)
			{
				if (strpos(strtolower($jsurl), "jquery") > 0)
				{
					$found = true;
					break;
				}
			}
			
			if (!$found)
			{
				$document->addScript( $url );
				$document->addScript( $cpurl );
				$document->addScript( $ncurl );
			}
		}
	}
	
	static function GetRouteMenus()
	{
		global $FSSRoute_menus;
		global $FSSRoute_access;
		
		if (empty($FSSRoute_menus))
		{
			$FSSRoute_menus = array();
			$db = JFactory::getDBO();
			$qry = "SELECT id, link, access FROM #__menu WHERE link LIKE '%option=com_fss%' AND published = 1 AND type = 'component' ";			
			$qry .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			
			$db->setQuery($qry);
			$menus = $db->loadObjectList('id');

			$FSSRoute_access = array();
			
			foreach($menus as $menu)
			{
				$FSSRoute_access[$menu->id] = $menu->access;
				$FSSRoute_menus[$menu->id] = FSSRoute::SplitURL($menu->link);
			}
		}
	}
	
	static function GetBaseURL() 
	{
		$uri = JURI::getInstance();
		return $uri->toString( array('scheme', 'host', 'port'));
	}
		
	static function isValidURL($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}


	static $open_header;
	static function PageStyle()
	{
		echo "<style>\n";
		echo FSS_Settings::get('display_style');
		echo "</style>\n";
		echo FSS_Settings::get('display_head');
		$class = '';
		if (FSSJ3Helper::IsJ3())
			$class = "fss_main_j3";
		
		$view = FSS_Input::getCmd('view');
		if ($view)
			$class .= " fss_view_" . $view;
		
		$layout = FSS_Input::getCmd('layout');
		if ($layout)
			$class .= " fss_layout_" . $layout;
		
		$itemid = FSS_Input::getInt('Itemid');
		if ($itemid)
			$class .= " fss_itemid_" . $itemid;
		
		$output = "<div class='fss_main fss_comp $class'>\n";
		
		$msgList = FSS_Helper::getMessageQueue();
		if (count($msgList) > 0)
			include(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'tmpl'.DS.'messages.php');

		if (FSS_Settings::get('page_headingout'))
		{
			self::$open_header = $output;
		} else {
			echo $output;		
		}
	}

	static function PageStyleEnd()
	{
		echo "</div>\n";
		echo FSS_Settings::get('display_foot');
		
		echo "<div id='fss_base_url' style='display: none;'>" . JURI::root(true) . "</div>";
	}

	static function PageStylePopup($iframe = false)
	{
?>
<style>
<?php echo FSS_Settings::get('display_popup_style'); ?>
</style>
<?php
		if ((int)$iframe == 2) {
			// popup window
?>
<script>

jQuery(document).ready(function () {

	jQuery('button.close').click (function (ev) {
		ev.preventDefault();
		window.close();
	});

	jQuery('a.close_popup').click (function (ev) {
		ev.preventDefault();
		window.close();
	});
	
	resize();
	
	jQuery( window ).resize(function() {
		resize();
	});
});

function resize()
{
	var head_height = jQuery('.modal-header').outerHeight(true);
	var foot_height = jQuery('.modal-footer').outerHeight(true);
	
	var win_height = jQuery(window).height();
	
	var res_height = win_height - foot_height - head_height;
	
	jQuery('.modal-body').outerHeight(res_height);	
}
</script>
<style>
.modal-body {
	max-height: 4000px !important;
}
</style>
<?php
		} else if ($iframe) {
			// normal iframe
?>
<script>
	
jQuery(document).ready(function () {
	// add classes to body to make things play nice
	jQuery('body').addClass('body-modal');
	jQuery('body').addClass('fss_main');
	jQuery('body').addClass('fss_popup');
	jQuery('body').removeClass('modal');
	
	// hide system message container
	if (jQuery('#system-message').children().length < 1)
        jQuery('#system-message-container').hide();
	
	// move system message to modal body as some tempaltes it makes a mess
	jQuery('#system-message-container').prependTo(jQuery('.modal-body'));

	jQuery('button.close').click (function (ev) {
		ev.preventDefault();
		parent.fss_modal_hide();
	});

	jQuery('a.close_popup').click (function (ev) {
		ev.preventDefault();
		parent.fss_modal_hide();
	});
	
	fss_resize_popup();
	
	setInterval("fss_resize_popup()", 250);
});

var fss_resize_height = 0;
function fss_resize_popup()
{
	var window_height = jQuery(parent.window).height();
	
	// fix for some stubborn templates with firefix
	if (window_height < parent.window.innerHeight)
		window_height = parent.window.innerHeight;
		
	jQuery('div.modal-body').css('max-height', window_height - 400 + 'px');
	
	var sheight = document.body.scrollHeight;

	var diff = Math.abs(fss_resize_height - sheight);

	if (diff > 10)
	{
		fss_resize_height = sheight;
		var offset = 200;
		if (sheight + offset > window_height)
		{
			sheight = window_height - offset;	
		} else {
			parent.jQuery('#fss_modal iframe').attr("scrolling", "no");
		}
		
		if (sheight < 50)
			sheight = 200;
		
		parent.jQuery('#fss_modal iframe').removeAttr('style');
		parent.jQuery('#fss_modal iframe').css('height', sheight + 'px');	
	}
}

</script>
<?php		
		}

		// include missing js and css as some templates suck!
		$document = JFactory::getDocument();

		$popup_js = FSS_Settings::get("popup_js");
		$popup_css = FSS_Settings::get("popup_css");
		
		$js = explode("\n", $popup_js);
		
		foreach ($js as $script)
		{
			$script = trim($script);
			if (!$script) continue;	
			
			$document->addScript($script); 
		}
		
		$csss = explode("\n", $popup_css);
		
		foreach ($csss as $css)
		{
			$css = trim($css);
			if (!$css) continue;	
			
			$document->addStyleSheet($css); 
		}
	}

	static function PageStylePopupEnd()
	{
		$html[] = '</div>';
		$html[] = '<div class="modal-footer fss_main fss_popup">';
		$html[] = '<a href="#" class="btn btn-default close_popup simplemodal-close" data-dismiss="modal">' . JText::_('CLOSE') .'</a>';
		$html[] = '</div>';
		
		return implode($html);
	}

	static function PageTitlePopup($title,$subtitle = "")
	{		
		if (!empty(self::$open_header) && self::$open_header)
			echo self::$open_header;
		
		self::$open_header = null;
		
		$html[] = '<div class="modal-header fss_main fss_popup">';
		$html[] = '<button class="close simplemodal-close" data-dismiss="modal">&times;</button>';
		$html[] = '<h3>' . JText::_($title);
		if ($subtitle)
			$html[] = " - " . JText::_($subtitle);
		$html[] = '</h3>';
		$html[] = '</div>';
		$html[] = '<div class="modal-body fss_main fss_popup">';
		
		return implode($html);
	}

	static function TitleString($title,$subtitle,$menutitle,$isbrowser)
	{
		if ($isbrowser)
		{
			$setting = FSS_Settings::get('browser_prefix');		
			if ($setting == -1)
				$setting = FSS_Settings::get('title_prefix');		
		} else {
			$setting = FSS_Settings::get('title_prefix');		
		}

		switch ($setting)
		{
			case 0: // Title or Subtitle
				if ($subtitle)
					return $subtitle;
				return $title;
				break;
				
			case 1:	// Title - Subtitle
				if ($subtitle && $title)
					return JText::sprintf('FSS_PAGE_HEAD', $title, $subtitle);
				if ($subtitle)
					return $subtitle;
				return $title;
				break;
				
			case 2: // Title
				return $title;
				break;
				
			case 3: // Menu Title
				return $menutitle;
				break;
				
			case 4: // Menu Title - Title or Subtitle
				if ($subtitle)
					return JText::sprintf('FSS_PAGE_HEAD', $menutitle, $subtitle);
				
				if ($title)
					return JText::sprintf('FSS_PAGE_HEAD', $menutitle, $title);

				return $menutitle;
				break;
			
			case 5: // Menu Title - Title - Subtitle
				if ($subtitle)
					return JText::sprintf('FSS_PAGE_HEAD_TRIPLE', $menutitle, $title, $subtitle);
				
				if ($title)
					return JText::sprintf('FSS_PAGE_HEAD', $menutitle, $title);
			
				return $menutitle;
				break;
				
			case 6:
				if ($title)
					return JText::sprintf('FSS_PAGE_HEAD', $menutitle, $title);
			
				return $menutitle;
				break;	
				
			case 99:
				return "";
				break;		
		}
		
		// something gone wrong!
		if ($subtitle)
			return $subtitle;
		
		return $title;
	}
	
	static function ModuleStart($id)
	{
	?>
		<style>
		<?php echo FSS_Settings::get('display_module_style'); ?>
		</style>
	<?php

		$class = $id;
		if (FSSJ3Helper::IsJ3())
			$class .= " fss_main_j3";
		
		$view = FSS_Input::getCmd('view');
		if ($view)
			$class .= " fss_view_" . $view;
		
		$layout = FSS_Input::getCmd('layout');
		if ($layout)
			$class .= " fss_layout_" . $layout;
		
		$itemid = FSS_Input::getInt('Itemid');
		if ($itemid)
			$class .= " fss_itemid_" . $itemid;
		
		echo "<div class='fss_main fss_module $class'>\n";		
	}
	
	static function ModuleEnd()
	{
		echo "</div>";
	}

	static function PageTitle($title = "",$subtitle = "",$template = 'display_h1')
	{
		// if we have page_headingout set, then the self::$open_header var contains some html to be displayed after the heading 
		// has been output. Must output this otherwise you end up with missing container div tags
		$post = "";
		if (!empty(self::$open_header) && self::$open_header)
			$post = self::$open_header;
		self::$open_header = null;


		$mainframe = JFactory::getApplication();
		$pageparams = $mainframe->getPageParameters('com_fss');			

		$title = JText::_($title);
		$subtitle = JText::_($subtitle);
		$pageheading = $pageparams->get('page_title', '');
		$menutitle = $pageparams->get('page_heading', $pageheading);
	
		$document = JFactory::getDocument();
		
		// setup browser title
		$title_browser = FSS_Helper::TitleString($title, $subtitle, $pageheading, true);
		
		if ($mainframe->getCfg('sitename_pagetitles', 0) == 1)
			$title_browser = JText::sprintf('JPAGETITLE', $mainframe->getCfg('sitename'), $title_browser);

		if ($mainframe->getCfg('sitename_pagetitles', 0) == 2)
			$title_browser = JText::sprintf('JPAGETITLE', $title_browser, $mainframe->getCfg('sitename'));
	
		$document->setTitle($title_browser);

		// setup page title
		$title_page = FSS_Helper::TitleString($title, $subtitle, $menutitle, false);

		// title set to none, just return
		if (FSS_Settings::get('title_prefix') == 99)
			return $post;
		
		// should the "Show Page Heading" option be used?
		if (FSS_Settings::get('use_joomla_page_title_setting'))
		{
			if ($pageparams->get('show_page_heading',1))
				return str_replace("$1",$title_page,FSS_Settings::get($template)) . $post;
			
			return $post;
		}
		
		// Normal, always show title
		$output = str_replace("$1",$title_page,FSS_Settings::get($template));
		
		return $output . $post;
	}

	static function PageSubTitle($title,$usejtext = true)
	{
		if ($usejtext)
			$title = JText::_($title);
	
		return str_replace("$1",$title,FSS_Settings::get('display_h2'));
	}

	static function PageSubTitle2($title,$usejtext = true)
	{
		if ($usejtext)
			$title = JText::_($title);
	
		return str_replace("$1",$title,FSS_Settings::get('display_h3'));
	}


	static function Date($date,$format = FSS_DATE_LONG, $format_custom = null)
	{
		//echo "In : $date<br>";
		//echo "Format : " . $format . "<br>";
		//echo "Offset : " . FSS_Settings::Get('timezone_offset') . "<br>";
		
		if ((int)$date > 10000)
			$date = date("Y-m-d H:i:s", $date);

		if ((int)FSS_Settings::Get('timezone_offset') != 0)
		{
			$time = strtotime($date);
			$time += 3600 * (int)FSS_Settings::Get('timezone_offset');
			$date = date("Y-m-d H:i:s", $time);
		}
		
		switch($format)
		{
			case FSS_DATE_SHORT:	
				$ft = JText::_('DATE_FORMAT_LC4');
				break;
			case FSS_DATE_MID:	
				$ft = JText::_('DATE_FORMAT_LC3');
				break;
			case FSS_DATE_LONG:	
				$ft = JText::_('DATE_FORMAT_LC1');
				break;
			case FSS_TIME_SHORT:	
				$ft = 'H:i';
				break;
			case FSS_TIME_LONG:	
				$ft = 'H:i:s';
				break;
			case FSS_DATETIME_SHORT:	
				$ft = JText::_('DATE_FORMAT_LC4') . ', H:i';
				break;
			case FSS_DATETIME_MID:	
				$ft = JText::_('DATE_FORMAT_LC3') . ', H:i';
				break;
			case FSS_DATETIME_LONG:	
				$ft = JText::_('DATE_FORMAT_LC1') . ', H:i';
				break;
			case FSS_DATETIME_MYSQL:	
				$ft = 'Y-m-d H:i:s';
				break;
			case FSS_DATE_CUSTOM:
				$ft = $format_custom;
				break;
			default:
				$ft = JText::_('DATE_FORMAT_LC');
		}

		if ($format == FSS_DATETIME_SHORT && FSS_Settings::Get('date_dt_short') != "")
			$ft = FSS_Settings::Get('date_dt_short');
		
		if ($format == FSS_DATETIME_MID && FSS_Settings::Get('date_dt_long') != "")
			$ft = FSS_Settings::Get('date_dt_long');
		
		if ($format == FSS_DATE_SHORT && FSS_Settings::Get('date_d_short') != "")
			$ft = FSS_Settings::Get('date_d_short');
		
		if ($format == FSS_DATE_MID && FSS_Settings::Get('date_d_long') != "")
			$ft = FSS_Settings::Get('date_d_long');
	
		$date = new JDate($date, new DateTimeZone("UTC"));
		$date->setTimezone(FSS_Helper::getTimezone());
		
		//echo "Out : " . $date->format($ft, true) . "<br>";
		return $date->format($ft, true);
		
	}
	
	static function getFormat($format = '')
	{
		if ($format == '')
			$format = FSS_Settings::get('date_dt_short');
		
		if ($format == '')
			$format = JText::_('DATE_FORMAT_LC4') . ', H:i';
		
		return $format;
	}
	
	static function getCalFormat($format = '')
	{
		$format = FSS_Helper::getFormat($format);

		$data = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		
		foreach ($data as $letter)
		{
			$format = str_replace($letter, "%" . $letter, $format);		
			$format = str_replace(strtoupper($letter), "%" . strtoupper($letter), $format);		
		}

		return $format;
	}
		
	static function getTimeZone() {
		$userTz = JFactory::getUser()->getParam('timezone');
		
		if (FSSJ3Helper::IsJ3())
		{
			$timeZone = JFactory::getConfig()->get('offset');
		} else {
			$timeZone = JFactory::getConfig()->getValue('offset');
		}
		
		if($userTz) {
			$timeZone = $userTz;
		}
		
		if ((string)$timeZone == "" || (string)$timeZone == "0") 
			$timeZone = "UTC";
			
		return new DateTimeZone($timeZone);
	}

	static function CurDate()
	{
		return date("Y-m-d H:i:s");
	}

	static function GetDBTime()
	{
		return time();	
	}

	static function ToJText($string)
	{
		return strtoupper(str_replace(" ","_",$string));	
	}

	static function escapeJavaScriptText($string)
	{
		return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
	}
	
	static function escapeJavaScriptTextForAlert($string)
	{
		if (function_exists("mb_convert_encoding"))
			return mb_convert_encoding(FSS_Helper::escapeJavaScriptText($string), 'UTF-8', 'HTML-ENTITIES');
		
		return FSS_Helper::escapeJavaScriptText($string);
	}

	/*$FSSRoute_debug = array();
	global $FSSRoute_debug;*/

	static function display_filesize($filesize){
   
		if (stripos($filesize,"k") > 0)
			$filesize = $filesize * 1024;
		if (stripos($filesize,"m") > 0)
			$filesize = $filesize * 1024 * 1024;
		if (stripos($filesize,"g") > 0)
			$filesize = $filesize * 1024 * 1024;
		$filesize = $filesize * 1;
	
		if(is_numeric($filesize)){
			$decr = 1024; $step = 0;
			$prefix = array('Byte','KB','MB','GB','TB','PB');
		   
			while(($filesize / $decr) > 0.9){
				$filesize = $filesize / $decr;
				$step++;
			}
			return round($filesize,2).' '.$prefix[$step];
		} else {
			return 'NaN';
		}
	}

	static function encode($in)
	{
		$out = $in;
		//$out = str_replace("'","&apos;",$out);
		//$out = str_replace('&#039;','&apos;',$out);
		$out = htmlspecialchars($out,ENT_QUOTES);
		//$out = htmlentities($out,ENT_COMPAT);
	
		return $out;		
	}

	static function createRandomPassword() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;

		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}

	static function datei_mime($filetype) {
	
		switch ($filetype) {
			case "ez":  $mime="application/andrew-inset"; break;
			case "hqx": $mime="application/mac-binhex40"; break;
			case "cpt": $mime="application/mac-compactpro"; break;
			case "doc": $mime="application/msword"; break;
			case "bin": $mime="application/octet-stream"; break;
			case "dms": $mime="application/octet-stream"; break;
			case "lha": $mime="application/octet-stream"; break;
			case "lzh": $mime="application/octet-stream"; break;
			case "exe": $mime="application/octet-stream"; break;
			case "class": $mime="application/octet-stream"; break;
			case "dll": $mime="application/octet-stream"; break;
			case "oda": $mime="application/oda"; break;
			case "pdf": $mime="application/pdf"; break;
			case "ai":  $mime="application/postscript"; break;
			case "eps": $mime="application/postscript"; break;
			case "ps":  $mime="application/postscript"; break;
			case "xls": $mime="application/vnd.ms-excel"; break;
			case "ppt": $mime="application/vnd.ms-powerpoint"; break;
			case "wbxml": $mime="application/vnd.wap.wbxml"; break;
			case "wmlc": $mime="application/vnd.wap.wmlc"; break;
			case "wmlsc": $mime="application/vnd.wap.wmlscriptc"; break;
			case "vcd": $mime="application/x-cdlink"; break;
			case "pgn": $mime="application/x-chess-pgn"; break;
			case "csh": $mime="application/x-csh"; break;
			case "dvi": $mime="application/x-dvi"; break;
			case "spl": $mime="application/x-futuresplash"; break;
			case "gtar": $mime="application/x-gtar"; break;
			case "hdf": $mime="application/x-hdf"; break;
			case "js":  $mime="application/x-javascript"; break;
			case "nc":  $mime="application/x-netcdf"; break;
			case "cdf": $mime="application/x-netcdf"; break;
			case "swf": $mime="application/x-shockwave-flash"; break;
			case "tar": $mime="application/x-tar"; break;
			case "tcl": $mime="application/x-tcl"; break;
			case "tex": $mime="application/x-tex"; break;
			case "texinfo": $mime="application/x-texinfo"; break;
			case "texi": $mime="application/x-texinfo"; break;
			case "t":   $mime="application/x-troff"; break;
			case "tr":  $mime="application/x-troff"; break;
			case "roff": $mime="application/x-troff"; break;
			case "man": $mime="application/x-troff-man"; break;
			case "me":  $mime="application/x-troff-me"; break;
			case "ms":  $mime="application/x-troff-ms"; break;
			case "ustar": $mime="application/x-ustar"; break;
			case "src": $mime="application/x-wais-source"; break;
			case "zip": $mime="application/zip"; break;
			case "au":  $mime="audio/basic"; break;
			case "snd": $mime="audio/basic"; break;
			case "mid": $mime="audio/midi"; break;
			case "midi": $mime="audio/midi"; break;
			case "kar": $mime="audio/midi"; break;
			case "mpga": $mime="audio/mpeg"; break;
			case "mp2": $mime="audio/mpeg"; break;
			case "mp3": $mime="audio/mpeg"; break;
			case "aif": $mime="audio/x-aiff"; break;
			case "aiff": $mime="audio/x-aiff"; break;
			case "aifc": $mime="audio/x-aiff"; break;
			case "m3u": $mime="audio/x-mpegurl"; break;
			case "ram": $mime="audio/x-pn-realaudio"; break;
			case "rm":  $mime="audio/x-pn-realaudio"; break;
			case "rpm": $mime="audio/x-pn-realaudio-plugin"; break;
			case "ra":  $mime="audio/x-realaudio"; break;
			case "wav": $mime="audio/x-wav"; break;
			case "pdb": $mime="chemical/x-pdb"; break;
			case "xyz": $mime="chemical/x-xyz"; break;
			case "bmp": $mime="image/bmp"; break;
			case "gif": $mime="image/gif"; break;
			case "ief": $mime="image/ief"; break;
			case "jpeg": $mime="image/jpeg"; break;
			case "jpg": $mime="image/jpeg"; break;
			case "jpe": $mime="image/jpeg"; break;
			case "png": $mime="image/png"; break;
			case "tiff": $mime="image/tiff"; break;
			case "tif": $mime="image/tiff"; break;
			case "wbmp": $mime="image/vnd.wap.wbmp"; break;
			case "ras": $mime="image/x-cmu-raster"; break;
			case "pnm": $mime="image/x-portable-anymap"; break;
			case "pbm": $mime="image/x-portable-bitmap"; break;
			case "pgm": $mime="image/x-portable-graymap"; break;
			case "ppm": $mime="image/x-portable-pixmap"; break;
			case "rgb": $mime="image/x-rgb"; break;
			case "xbm": $mime="image/x-xbitmap"; break;
			case "xpm": $mime="image/x-xpixmap"; break;
			case "xwd": $mime="image/x-xwindowdump"; break;
			case "msh": $mime="model/mesh"; break;
			case "mesh": $mime="model/mesh"; break;
			case "silo": $mime="model/mesh"; break;
			case "wrl": $mime="model/vrml"; break;
			case "vrml": $mime="model/vrml"; break;
			case "css": $mime="text/css"; break;
			case "asc": $mime="text/plain"; break;
			case "txt": $mime="text/plain"; break;
			case "gpg": $mime="text/plain"; break;
			case "rtx": $mime="text/richtext"; break;
			case "rtf": $mime="text/rtf"; break;
			case "wml": $mime="text/vnd.wap.wml"; break;
			case "wmls": $mime="text/vnd.wap.wmlscript"; break;
			case "etx": $mime="text/x-setext"; break;
			case "xsl": $mime="text/xml"; break;
			case "flv": $mime="video/x-flv"; break;
			case "mpeg": $mime="video/mpeg"; break;
			case "mpg": $mime="video/mpeg"; break;
			case "mpe": $mime="video/mpeg"; break;
			case "qt":  $mime="video/quicktime"; break;
			case "mov": $mime="video/quicktime"; break;
			case "mxu": $mime="video/vnd.mpegurl"; break;
			case "avi": $mime="video/x-msvideo"; break;
			case "movie": $mime="video/x-sgi-movie"; break;
			case "asf": $mime="video/x-ms-asf"; break;
			case "asx": $mime="video/x-ms-asf"; break;
			case "wm":  $mime="video/x-ms-wm"; break;
			case "wmv": $mime="video/x-ms-wmv"; break;
			case "wvx": $mime="video/x-ms-wvx"; break;
			case "ice": $mime="x-conference/x-cooltalk"; break;
			case "rar": $mime="application/x-rar"; break;
			default:    $mime="application/octet-stream"; break; 
		}
		return $mime;
	}

	static function NeedBaseBreadcrumb($pathway, $aparams)
	{
		global $FSSRoute_menus;
		// need to determine if a base pathway item needs adding or not
		
		// get any menu items for fss
		FSS_Helper::GetRouteMenus();

		$lastpath = $pathway->getPathway();
		// no pathway, so must have to add
		if (count($lastpath) == 0)
			return true;
			
		$lastpath = $lastpath[count($lastpath)-1];
		$link = $lastpath->link;
		
		$parts = FSSRoute::SplitURL($link);
		
		if (!array_key_exists('Itemid', $parts))
			return true;
			
		//print_p($parts);
		if (!array_key_exists($parts['Itemid'],$FSSRoute_menus))
		{
			//echo "Item ID not found<br>";
			return true;		
		}
		
		$ok = true;
		
		/*foreach($FSSRoute_menus[$parts['Itemid']] as $key => $value)
		{
			if ($value != "")
			{
				if (!array_key_exists($key,$aparams))
				{
					$ok = false;
					break;
				}
			
				if ($aparams[$key] != $value)
				{
					$ok = false;
					break;		
				}
			}
		}*/
		
		foreach($aparams as $key => $value)
		{
			if ($value != "")
			{
				if (!array_key_exists($key,$FSSRoute_menus[$parts['Itemid']]))
				{
					$ok = false;
					break;
				}
			
				if ($FSSRoute_menus[$parts['Itemid']][$key] != $value)
				{
					$ok = false;
					break;		
				}
			}
		}
		
		if ($ok)
			return false;
		/*print_p($aparams);
		print_p($FSSRoute_menus[$parts['Itemid']]);*/
		
		return true;	
	}
	
	
	static function GetPublishedText($ispub,$notip = false)
	{
		$img = 'save_16.png';
		$alt = JText::_("PUBLISHED");

		if ($ispub == 0)
		{
			$img = 'cancel_16.png';
			$alt = JText::_("UNPUBLISHED");
		}
	
		if ($notip)
			return '<img src="components/com_fss/assets/images/' . $img . '" width="16" height="16" border="0" alt="' . $alt .'" />';	
			
		return '<img class="fssTip" src="components/com_fss/assets/images/' . $img . '" width="16" height="16" border="0" alt="' . $alt .'" title="'.$alt.'" />';	

	}

	static function GetYesNoText($ispub)
	{
		$img = 'tick.png';
		$alt = JText::_("YES");

		if ($ispub == 0)
		{
			$img = 'cross.png';
			$alt = JText::_("NO");
		}
		$src = JURI::base() . "/components/com_fss/assets/images";
		return '<img src="' . $src . '/' . $img . '" width="16" height="16" border="0" alt="' . $alt .'" />';	
	}

	static function ShowError(&$errors, $key)
	{
		if (empty($errors))
			return "";
			
		if (!array_key_exists($key, $errors))
			return "";
			
		if ($errors[$key] == "")	
			return "";
		
		return "<div class='fss_ticket_error'>" . $errors[$key] . "</div>";
	}
	
	static function sort($title, $order, $direction = 'asc', $selected = 0, $task = null, $new_direction = 'asc')
	{
		$direction = strtolower($direction);
		$images = array('sort_asc.png', 'sort_desc.png');
		$index = intval($direction == 'desc');

		if ($order != $selected)
		{
			$direction = $new_direction;
		}
		else
		{
			$direction = ($direction == 'desc') ? 'asc' : 'desc';
		}

		$html = '<a href="#" onclick="Joomla.tableOrdering(\'' . $order . '\',\'' . $direction . '\',\'' . $task . '\');return false;" title="'
			. JText::_('JGLOBAL_CLICK_TO_SORT_THIS_COLUMN') . '">';
		$html .= JText::_($title);

		if ($order == $selected)
		{
			$html .= JHtml::_('image', 'system/' . $images[$index], '', null, true);
		}

		$html .= '</a>';

		return $html;
	}

	static function contains($str, array $arr)
	{
		foreach($arr as $a) {
			if (stripos($str,$a) !== false) return true;
		}
		return false;
	}

	static $sceditor = false;
	static function AddSCEditor($for_user = false)
	{
		if (!FSS_Helper::$sceditor)
		{
			if (FSS_Settings::Get('support_sceditor'))
			{
				$document = JFactory::getDocument();
				$document->addScript(JURI::root(true).'/components/com_fss/assets/js/sceditor/jquery.sceditor.bbcode.js'); 
				$document->addScript(JURI::root(true).'/components/com_fss/assets/js/sceditor/include.sceditor.js'); 
				$document->addScriptDeclaration("var sceditor_emoticons_root = '" . JURI::root( true ) . "/components/com_fss/assets/';");
				$document->addScriptDeclaration("var sceditor_style_root = '" . JURI::root( true ) . "/components/com_fss/assets/js/sceditor/';");
				$document->addScriptDeclaration("var sceditor_style_type = '" . FSS_Settings::get('sceditor_content') . "';");
				
				$button_exclude = array();
				if (!FSS_Settings::get('sceditor_emoticons'))
					$button_exclude[] = "emoticon";
				
				if (FSS_Settings::get('sceditor_buttonhide'))
					$button_exclude[] = str_replace(" ", "", FSS_Settings::get('sceditor_buttonhide'));
				
				$document->addScriptDeclaration("var sceditor_toolbar_exclude = '" . implode(",",$button_exclude) . "';");
				if ($for_user)
				{
					$document->addScriptDeclaration("var sceditor_paste = '" . FSS_Settings::get('sceditor_paste_user') . "';");
				} else {
					$document->addScriptDeclaration("var sceditor_paste = '" . FSS_Settings::get('sceditor_paste_admin') . "';");
				}
				
				$document->addStyleSheet(JURI::root(true).'/components/com_fss/assets/js/sceditor/themes/' . FSS_Settings::get('sceditor_theme') . '.css'); 
				//$document->addStyleSheet(JURI::root(true).'/components/com_fss/assets/js/sceditor/themes/square.css'); 
			}
			FSS_Helper::$sceditor = true;
		}
	}
	
	static $bbcode_loaded = false;
	
	static function removeTagBetween($text, $tag)
	{
		while (stripos($text, "<" . $tag) !== false)
		{
			$spos = stripos($text, "<" . $tag);
			$epos = stripos($text, "</" . $tag . ">");
				
			if ($spos && $epos)
			{
				$text = substr($text,0, $spos) . substr($text, $epos + strlen($tag) + 3);		
			} else {
				break;	
			}
		}
		
		return $text;
	}
	
	public static function filterText($text)
	{
		// strip any style tags
		$text = static::removeTagBetween($text, "style");
		$text = static::removeTagBetween($text, "script");
		
		// Filter settings
		$config     = JFactory::getApplication()->getParams('com_config');
		$user       = JFactory::getUser();

		$filters = $config->get('filters');

		$blackListTags       = array();
		$blackListAttributes = array();

		$customListTags       = array();
		$customListAttributes = array();

		$whiteListTags       = array();
		$whiteListAttributes = array();

		$whiteList  = false;
		$blackList  = false;
		$customList = false;
		$unfiltered = false;

		// force public usergroup for content
		$userGroups = array();
		$userGroups[] = 9;

		// Cycle through each of the user groups the user is in.
		// Remember they are included in the Public group as well.
		foreach ($userGroups as $groupId)
		{
			// May have added a group by not saved the filters.
			if (!isset($filters->$groupId))
			{
				continue;
			}

			// Each group the user is in could have different filtering properties.
			$filterData = $filters->$groupId;
			$filterType = strtoupper($filterData->filter_type);

			if ($filterType == 'NH')
			{
				// Maximum HTML filtering.
			}
			elseif ($filterType == 'NONE')
			{
				// No HTML filtering.
				$unfiltered = true;
			}
			else
			{
				// Black or white list.
				// Preprocess the tags and attributes.
				$tags           = explode(',', $filterData->filter_tags);
				$attributes     = explode(',', $filterData->filter_attributes);
				$tempTags       = array();
				$tempAttributes = array();

				foreach ($tags as $tag)
				{
					$tag = trim($tag);

					if ($tag)
					{
						$tempTags[] = $tag;
					}
				}

				foreach ($attributes as $attribute)
				{
					$attribute = trim($attribute);

					if ($attribute)
					{
						$tempAttributes[] = $attribute;
					}
				}

				// Collect the black or white list tags and attributes.
				// Each list is cummulative.
				if ($filterType == 'BL')
				{
					$blackList           = true;
					$blackListTags       = array_merge($blackListTags, $tempTags);
					$blackListAttributes = array_merge($blackListAttributes, $tempAttributes);
				}
				elseif ($filterType == 'CBL')
				{
					// Only set to true if Tags or Attributes were added
					if ($tempTags || $tempAttributes)
					{
						$customList           = true;
						$customListTags       = array_merge($customListTags, $tempTags);
						$customListAttributes = array_merge($customListAttributes, $tempAttributes);
					}
				}
				elseif ($filterType == 'WL')
				{
					$whiteList           = true;
					$whiteListTags       = array_merge($whiteListTags, $tempTags);
					$whiteListAttributes = array_merge($whiteListAttributes, $tempAttributes);
				}
			}
		}

		// Remove duplicates before processing (because the black list uses both sets of arrays).
		$blackListTags        = array_unique($blackListTags);
		$blackListAttributes  = array_unique($blackListAttributes);
		$customListTags       = array_unique($customListTags);
		$customListAttributes = array_unique($customListAttributes);
		$whiteListTags        = array_unique($whiteListTags);
		$whiteListAttributes  = array_unique($whiteListAttributes);

		// Unfiltered assumes first priority.
		if ($blackList)
		{
			// Remove the white-listed tags and attributes from the black-list.
			$blackListTags       = array_diff($blackListTags, $whiteListTags);
			$blackListAttributes = array_diff($blackListAttributes, $whiteListAttributes);

			$filter = JFilterInput::getInstance($blackListTags, $blackListAttributes, 1, 1);

			// Remove white listed tags from filter's default blacklist
			if ($whiteListTags)
			{
				$filter->tagBlacklist = array_diff($filter->tagBlacklist, $whiteListTags);
			}
			// Remove white listed attributes from filter's default blacklist
			if ($whiteListAttributes)
			{
				$filter->attrBlacklist = array_diff($filter->attrBlacklist, $whiteListAttributes);
			}
		}
		// White lists take third precedence.
		elseif ($whiteList)
		{
			// Turn off XSS auto clean
			$filter = JFilterInput::getInstance($whiteListTags, $whiteListAttributes, 0, 0, 0);
		}
		// No HTML takes last place.
		else
		{
			$filter = JFilterInput::getInstance();
		}

		$text = $filter->clean($text, 'html');

		return $text;
	}

	static function ParseBBCode($text, $message = null, $strip_inline_imgs = false, $no_div = false, $foruser = false)
	{
		if (substr($text,0,9) == "{RAWHTML}" && FSS_Settings::get('allow_raw_html_messages'))
		{
			$tidy_config = array(
                     'clean' => true,
                     'output-xhtml' => true,
                     'show-body-only' => true,
                     'wrap' => 0,
                    
                     );

			$text = substr($text,9);

			// purify the HTML from the email so we dont break the layout of the page
			require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'htmlpurifier'.DS.'HTMLPurifier.standalone.php');    
			$purifier = new HTMLPurifier();
			$text = $purifier->purify($text);
			
			// replace any [quoted] blocks as they arent done using bbcode
			if (strpos($text, "[quoted]") !== false)
			{
				$text = str_replace("[quoted]", '<div class="quoted_text_cont"><span class="quoted_text_show small muted">' . \JText::_("FSS_SHOW_QUOTED_TEXT") . '</span><div class="quoted_text">', $text);
				$text = str_replace("[/quoted]", "", $text);
				$text .= '</div></div>';
			}

			$text = self::filterText($text);
			return $text;
		}
		
		if ($strip_inline_imgs)
		{
			$text = preg_replace("/\[img\]data(.*)\[\/img\]/i", "", $text);
		}	
				
		require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'third'.DS.'sbbcodeparser'.DS.'sbbcodeparser.php');
		
		$parser = new \SBBCodeParser\Node_Container_Document(true, false);
		
		if (FSS_Settings::get('sceditor_emoticons'))
		{
			$parser->add_emoticons(array(
                ":)" => JURI::root() . "components/com_fss/assets/emoticons/smile.png",
                ":angel:" => JURI::root() . "components/com_fss/assets/emoticons/angel.png",
                ":angry:" => JURI::root() . "components/com_fss/assets/emoticons/angry.png",
                "8-)" => JURI::root() . "components/com_fss/assets/emoticons/cool.png",
                ":'(" => JURI::root() . "components/com_fss/assets/emoticons/cwy.png",
                ":ermm:" => JURI::root() . "components/com_fss/assets/emoticons/ermm.png",
                ":D" => JURI::root() . "components/com_fss/assets/emoticons/grin.png",
                "<3" => JURI::root() . "components/com_fss/assets/emoticons/heart.png",
                ":(" => JURI::root() . "components/com_fss/assets/emoticons/sad.png",
                ":O" => JURI::root() . "components/com_fss/assets/emoticons/shocked.png",
                ":P" => JURI::root() . "components/com_fss/assets/emoticons/tongue.png",
                ";)" => JURI::root() . "components/com_fss/assets/emoticons/wink.png",
                ":alien:" => JURI::root() . "components/com_fss/assets/emoticons/alien.png",
                ":blink:" => JURI::root() . "components/com_fss/assets/emoticons/blink.png",
                ":blush:" => JURI::root() . "components/com_fss/assets/emoticons/blush.png",
                ":cheerful:" => JURI::root() . "components/com_fss/assets/emoticons/cheerful.png",
                ":devil:" => JURI::root() . "components/com_fss/assets/emoticons/devil.png",
                ":dizzy:" => JURI::root() . "components/com_fss/assets/emoticons/dizzy.png",
                ":getlost:" => JURI::root() . "components/com_fss/assets/emoticons/getlost.png",
                ":happy:" => JURI::root() . "components/com_fss/assets/emoticons/happy.png",
                ":kissing:" => JURI::root() . "components/com_fss/assets/emoticons/kissing.png",
                ":ninja:" => JURI::root() . "components/com_fss/assets/emoticons/ninja.png",
                ":pinch:" => JURI::root() . "components/com_fss/assets/emoticons/pinch.png",
                ":pouty:" => JURI::root() . "components/com_fss/assets/emoticons/pouty.png",
                ":sick:" => JURI::root() . "components/com_fss/assets/emoticons/sick.png",
                ":sideways:" => JURI::root() . "components/com_fss/assets/emoticons/sideways.png",
                ":silly:" => JURI::root() . "components/com_fss/assets/emoticons/silly.png",
                ":sleeping:" => JURI::root() . "components/com_fss/assets/emoticons/sleeping.png",
                ":unsure:" => JURI::root() . "components/com_fss/assets/emoticons/unsure.png",
                ":woot:" => JURI::root() . "components/com_fss/assets/emoticons/w00t.png",
                ":wassat:" => JURI::root() . "components/com_fss/assets/emoticons/wassat.png",
                ":whistling:" => JURI::root() . "components/com_fss/assets/emoticons/whistling.png",
                ":love:" => JURI::root() . "components/com_fss/assets/emoticons/wub.png"
            ));
		}
	
		$text = str_replace("\r\n","\n", $text);

		$links = array();
		$linkno = 1;

		while (stripos($text, "[url=") !== false)
		{
			$before = substr($text, 0, stripos($text, "[url="));
			$rest = substr($text, stripos($text, "[url=") + 5);				
			if (stripos($rest, "[/url]") !== false)
			{
				$end = substr($rest, stripos($rest, "[/url]") + 6);
				$link = substr($rest, 0, stripos($rest, "[/url]"));	
	
				$url = substr($link, 0, strpos($link, "]"));
				$desc = substr($link, strpos($link, "]")+1);
	
				// add http:// to www starting urls
				if(strpos($url, 'www') === 0)
				{
					$url = 'http://' . $url;
				}
				
				// add the base url to any urls not starting with http or ftp as they must be relative
				if(substr($url, 0, 4) !== 'http'
					&& substr($url, 0, 3) !== 'ftp')
				{
					$url = JURI::root() . $url;
				}
				
				$linkid = "XXZZXX" . $linkno++ . "XXZZXX";
				$target = FSS_Settings::get('ticket_link_target') ? '_blank' : '';
				
				$sp = new \SBBCodeParser\Node_Container_Document(true, false);
				$desc = $sp->parse($desc)->get_html(false);
				
				$links[$linkid] = "<a href='$url' target='{$target}'>$desc</a>";
				$text = $before . $linkid . $end;
			} else {
				break;		
			}
		}	
				
		// all inline images get replaced with this, so convert into proper image links depending on where we are in the system at the moment
		while (strpos($text, "[img attachment=") !== false)
		{		
			$start = strpos($text, "[img attachment=");
			$end = strpos($text, "[/img]", $start);
			$content = substr($text, $start+5, ($end-$start)-6);

			list ($temp, $attachid) = explode("=", $content);

			$replace = $attachid;
			
			if (is_object($foruser))
			{
				$link = $foruser($attachid);
			} else if ($foruser)
			{
				$link = JRoute::_('index.php?option=com_fss&view=ticket&ticketid=' . JRequest::getVar('ticketid') . '&fileid=' . $attachid, false);
			} else {
				$link = JRoute::_('index.php?option=com_fss&view=admin_support&task=attach.view&ticketid=' . JRequest::getVar('ticketid') . '&fileid=' . $attachid, false);
			}

			$replace = '[img]' . $link . '[/img]';
			
			$text = substr($text, 0, $start) . $replace . substr($text, $end+6);
		}

		$text = str_replace("|", "&#124;", $text);

		$output = $parser->parse($text)
			->detect_links()
			->detect_emails()
			->detect_emoticons()
			->get_html(false);

		$output = str_replace("&amp;#124;", "|", $output);
		$output = str_replace("&#124;", "|", $output);

		foreach ($links as $find => $replace)
		{
			$output = str_replace($find, $replace, $output);		
		}
			
		//$parsed = str_replace("\n", "\\n", $output);
		//$parsed = str_replace("\r", "\\r", $parsed);
	
		$output = str_replace(array("\r\n", "\r", "\n"), "<br />", $output); 
		$output = str_replace("&amp;#91;", "[", $output);
		$output = str_replace("&amp;#93;", "]", $output);

		//return "<pre>" . $up . "</pre>"."<pre>" . $parsed . "</pre>"."<div class='bbcode'>$output</div>";

		if ($no_div)
			return $output;

		return "<div class='bbcode'>$output</div>";
	}
	
	static function base64url_decode($data)
	{
		//return base64_decode(urldecode($data));
		return base64_decode(str_pad(strtr($data, '-_.', '+/='), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
	
	static function base64url_encode($data) {
		//return urlencode(base64_encode($data));
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
	
	static function IncludeModal()
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'tmpl'.DS.'modal.php');
	}

	static $_escape = 'htmlspecialchars';
	static $_charset = 'UTF-8';
	
	static public function escape($var)
	{
		if (in_array(self::$_escape, array('htmlspecialchars', 'htmlentities')))
		{
			return call_user_func(self::$_escape, $var, ENT_QUOTES, self::$_charset);
		}

		return call_user_func(self::$_escape, $var);
	}
	
	static function DateValidate($in_date)
	{
		//echo "Checking $in_date<br>";
		$time = strtotime($in_date);
		//echo "Time : $time<br>";
		
		if ($time > 0)
		{
			return date("Y-m-d",$time);	
		}
		return "";	
	}
	
	
	static function GetTemplate()
	{
		$db = JFactory::getDBO();
		$db->setQuery("SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1");
		$tmpl = $db->loadObject();
		return $tmpl->template;	
	}
	
	static function NoPerm()
	{
		/*if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = FSS_Input::getCmd('option','');
			$view = FSS_Input::getCmd('view','');
			$layout = FSS_Input::getCmd('layout','');
			$Itemid = FSS_Input::getInt('Itemid',0);
			$url = FSSRoute::_("index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid); 	
		}

		$url = str_replace("&what=find","",$url);
		$url = base64_encode($url);

		$return = $url;*/
		$return = FSS_Helper::getCurrentURLBase64();

		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'tmpl'.DS.'noperm.php');		
		
		return false;
	}
	
	static $_tables;
	static function TableExists($table)
	{
		$db = JFactory::getDBO();

		if (empty(self::$_tables))
		{
		
			$qry = "SHOW TABLES";
			$db->setQuery($qry);
		
			self::$_tables = FSSJ3Helper::loadResultArray($db);
		}

		$prefix = $db->getPrefix();
		$table = str_replace("#__",$prefix,$table);

		foreach(self::$_tables as $exist)
		{
			if ($exist == $table)
				return true;	
		}
		return false;
	}
	
	static $help_texts;
	static function HelpText($ident, $return = false)
	{
		if (empty(self::$help_texts))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_help_text WHERE published = 1";
			$db->setquery($qry);
			self::$help_texts = $db->loadObjectList("identifier");
			
			if (self::$help_texts) FSS_Translate_Helper::Tr(self::$help_texts);
		}
		
		if (!array_key_exists($ident, self::$help_texts))
			return "";

		if ($return)
			return self::$help_texts[$ident]->message;

		echo self::$help_texts[$ident]->message;
	}
	
	static function CheckTicketLink()
	{
		$ticket_id = FSS_Input::getInt('t');
		$ticket_pass = FSS_Input::getString('p');
		if ($ticket_pass && $ticket_id > 0)
		{
			$db = JFactory::getDBO();
			
			$qry = "SELECT * FROM #__fss_ticket_ticket WHERE id = " . $db->escape($ticket_id) . " AND password = '" . $db->escape($ticket_pass) . "'";
			$db->setQuery($qry);
			
			$ticket = $db->loadObject();
			
			if ($ticket)
			{
				$session = JFactory::getSession();
				$session->Set('ticket_pass', $ticket_pass);
				$session->Set('ticket_email', $ticket->email);
			
				$link = FSSRoute::_("index.php?option=com_fss&view=ticket&layout=view&ticketid=" . $ticket_id, false);
				JFactory::getApplication()->redirect($link);
			}
		}
	}
	
	static function ObjectToArray($object)
	{
		if (is_array($object))
			return $object;
		
		$res = array();
		foreach ($object as $field => $value)
			$res[(string)$field] = $value;

		return $res;		
	}

	static function getSiteName()
	{
		$config = JFactory::getConfig();
		if (FSSJ3Helper::IsJ3())
		{
			$sitename = $config->get('sitename');
		} else {
			$sitename = $config->getValue('sitename');	
		}
		
		if (FSS_Settings::get('support_email_site_name') != "")
			$sitename = FSS_Settings::get('support_email_site_name');

		return $sitename;
	}
	
	static function email_decode_utf8($m) { 
		if (function_exists("mb_convert_encoding"))	return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); 
		
		return htmlentities($m[1]);
	}
	
	// split a list of lines and make sure none are longer than 250 chars
	static function MaxLineLength($in)
	{
		$lines = explode("\n", $in);
		
		$maxlen = 250;
		
		$out = array();
		
		foreach	($lines as $line)
		{
			while (strlen($line) > $maxlen)
			{
				$sublen = strrpos(substr($line, 0, $maxlen), " ");
				if ($sublen > 0)
				{
					$out[] = substr($line, 0, $sublen);
					$line = substr($line, $sublen+1);	
				} else {
					$out[] = $line;
					$line = "";	
				}
			}		
			
			$out[] = $line;
		}

		return implode("\r\n", $out);
	}
	
	static function dbPrefix()
	{
		$db = JFactory::getDBO();
		return $db->getPrefix();
	}
	
	static function langEnabled()
	{
		return JFactory::getApplication()->getLanguageFilter();
	}
	
	static function TicketCountSpan($count, $spanid, $text)
	{
		return str_replace($count, "<span class='ticket_count_" . $spanid . "'>" . $count . "</span>", $text);		
	}
	
	static function isSSLConnection()
	{
		return ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) || getenv('SSL_PROTOCOL_VERSION'));
	}
	
	static function allowBack()
	{
		$version = new JVersion();
		if ($version->RELEASE >= 3.3)
		{
			JFactory::getApplication()->allowCache(true);
		} else {
			JResponse::allowCache(true);
		}		
	}
	
	static function GetClientIP()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	static function TicketTime($time, $format = FSS_DATETIME_SHORT) {
		
		$abs = FSS_Helper::Date($time, $format);
		$rel = FSS_Helper::RelativeTime(strtotime($time));

		if (FSS_Settings::get('absolute_last_open') == 3) { // relative with no tip
			return $abs;
		} elseif (FSS_Settings::get('absolute_last_open') == 2) {	// absolute without tip
			return $rel;
		} elseif (FSS_Settings::get('absolute_last_open') == 1) {	// absolute withtip
			return "<span class='fssTip' title='$rel'>$abs</span>";
		} else { // relative with tip
			return "<span class='fssTip' title='$abs'>$rel</span>";
		}
	}
	
	static function RelativeTime($timestamp){
		//echo "Start : $timestamp<br>";
	
		$difference = strtotime(date("Y-m-d H:i:s",time())) - $timestamp;
		//echo "Diff : $difference<br>";
		//exit;
		$periods = array(
			"DTDIFF_SEC",
			"DTDIFF_MIN",
			"DTDIFF_HOUR",
			"DTDIFF_DAY",
			"DTDIFF_WEEK",
			"DTDIFF_MONTH",
			"DTDIFF_YEAR",
			"DTDIFF_DECADE"
		);
		$lengths = array("60","60","24","7","4.35","12","10");

		//$difference /= 60;
		//$difference /= 60;
		//$difference /= 24;

		if ($difference > 0) { // this was in the past
			$ending = JText::_("DTDIFF_AGO");
		} else { // this was in the future
			$difference = -$difference;
			$ending = JText::_("DTDIFF_TOGO");
		}
			
		for($j = 0; $j < count($lengths) && $difference >= $lengths[$j] ; $j++)
		{
			//echo "J: $j, Diff : $difference, ";
			$difference /= $lengths[$j];
			//echo "New Diff : $difference<br>";
		}
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";

		$text = JText::sprintf("DTDIFF_ORDERING", $difference, JText::_($periods[$j]), $ending);
		//$text = "$difference " . JText::_($periods[$j]) . " $ending";
			
		/*if ($j == 0)
		{
			if ($difference == 0)
				$text = JText::_('DTDIFF_TODAY');
			else if ($difference == 1)
				$text = JText::_('DTDIFF_YESTERDAY');
			else {
				$dow = date("l", $timestamp);
				$text .= " - " . $dow;	
			}
		}*/
			
		//echo $text . "<br><br>";
			
		//exit;
		return $text;
	}	
	
	static function truncate($text, $length, &$is_trimmed, $suffix = '&hellip;', $isHTML = true) {
		$i = 0;
		$simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
		$tags = array();
		if($isHTML){
			preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
			foreach($m as $o){
				if($o[0][1] - $i >= $length)
					break;
				$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
				// test if the tag is unpaired, then we mustn't save them
				if($t[0] != '/' && (!isset($simpleTags[$t])))
					$tags[] = $t;
				elseif(end($tags) == substr($t, 1))
					array_pop($tags);
				$i += $o[1][1] - $o[0][1];
			}
		}

		// output without closing tags
		$output = substr($text, 0, $length = min(strlen($text),  $length + $i));
		// closing tags
		$output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');

		// Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
		$r = preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE);
		$r2 = end($r);
		$pos = (int)end($r2);
		// Append closing tags to output
		$output.=$output2;

		// Get everything until last space
		$one = substr($output, 0, $pos);
		// Get the rest
		$two = substr($output, $pos, (strlen($output) - $pos));
		// Extract all tags from the last bit
		preg_match_all('/<(.*?)>/s', $two, $tags);
		
		// Re-attach tags
		$output = $one . implode($tags[0]);
		
		// Add suffix if needed
		if (strlen($text) > $length) 
		{ 
			$output .= $suffix; 
			$is_trimmed = true; 
		}

		//added to remove  unnecessary closure
		$output = str_replace('</!-->','',$output); 

		return $output;
	}
	
	static function basename($filename)
	{
		$pos = max(strrpos($filename, "/"), strrpos($filename, "\\"));
		if ($pos < 1)
			return $filename;
		
		return substr($filename, $pos+1);
	}

	static function getMaximumFileUploadSize($for_admin = false)  
	{  
		$min = -1;
		if (substr(FSS_Input::GetCmd('view'), 0, 5) == "admin" || $for_admin)
		{
			$min = self::convertPHPSizeToBytes(FSS_Settings::get('support_attach_max_size_admins'));
		} else {
			$min = self::convertPHPSizeToBytes(FSS_Settings::get('support_attach_max_size'));
		}
		
		$max_upload = self::convertPHPSizeToBytes(ini_get('upload_max_filesize'));
		$max_post = self::convertPHPSizeToBytes(ini_get('post_max_size'));
				
		$min_ini = min($max_upload, $max_post);
		if ($min > 0) return min($min, $min_ini);
		
		return $min_ini;
	}  
	
	static function convertPHPSizeToBytes($sSize)  
	{  
		if ( is_numeric( $sSize) ) {
			return $sSize;
		}
		
		$sSize = str_ireplace("B", "", $sSize);
		
		$sSuffix = substr($sSize, -1);  
		$iValue = substr($sSize, 0, -1);  
		switch(strtoupper($sSuffix)){  
		case 'P':  
			$iValue *= 1024;  
		case 'T':  
			$iValue *= 1024;  
		case 'G':  
			$iValue *= 1024;  
		case 'M':  
			$iValue *= 1024;  
		case 'K':  
			$iValue *= 1024;  
			break;  
		}  
		return $iValue;  
	}  
			
	static function IncludeFileUpload()
	{
		// need jQuery UI - Not present in Joomla 2.5 so cant play there!
		JHtml::_('jquery.ui');
		
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/components/com_fss/assets/css/fileupload/jquery.fileupload.css'); 
		$document->addStyleSheet(JURI::root(true).'/components/com_fss/assets/css/fileupload/jquery.fileupload-ui.css'); 

		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/fileupload/tmpl.js' );
		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/fileupload/jquery.iframe-transport.js' );
		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/fileupload/jquery.fileupload.js' );
		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/fileupload/jquery.fileupload-process.js' );
		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/fileupload/jquery.fileupload-validate.js' );
		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/fileupload/jquery.fileupload-ui.js' );
		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/fileupload/fileupload.js' );

		$upload_token = substr(md5(time()), 0, 8);
		$post_url = JRoute::_('index.php?option=com_fss&view=attach&upload_token='.$upload_token.'&task=process',false);
		$max_size = self::getMaximumFileUploadSize();
		$formats = self::getAttachFormatRegex();

		$file_types = '*';

		$main_js = "
var fss_file_upload_post = '$post_url';
var fss_file_upload_max_size = '$max_size';
var fss_file_upload_file_types = $formats;
";

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($main_js);

		echo "<input type='hidden' name='upload_token' value='$upload_token' />";
	}

	static function getAttachFormatRegex() 
	{
		if (substr(FSS_Input::GetCmd('view'), 0, 5) == "admin")
		{
			$formats = trim(FSS_Settings::get('support_attach_types_admins'));
		} else {
			$formats = trim(FSS_Settings::get('support_attach_types'));
		}

		if (substr($formats, 0, 1) == "/")
			return $formats;

		$parsed = array();
		if ($formats != "")
		{
			$formats = explode("," , $formats);
			foreach ($formats as $fm)
			{
				$fm = trim($fm);
				if (!$fm) continue;
				$parsed[] = $fm;
			}
		}
			
		if (count($parsed) > 0)
			return "/.(" . implode("|", $parsed) . ")$/i";
		
		return "null";
	}

	static function stringStartsWith($string, $find)
	{
		$len = strlen($find);
		if (strtolower(substr($string, 0, $len)) == strtolower($find))
			return true;

		return false;
	}

	static $_messageQueue = array();
	static function enqueueMessage($msg, $type = 'message')
	{
		// For empty queue, if messages exists in the session, enqueue them first.
		if (!count(self::$_messageQueue))
		{
			$session = JFactory::getSession();
			$sessionQueue = $session->get('fss.queue');

			if (count($sessionQueue))
			{
				self::$_messageQueue = $sessionQueue;
				$session->set('fss.queue', null);
			}
		}

		// Enqueue the message.
		self::$_messageQueue[] = array('message' => $msg, 'type' => strtolower($type));

		$session = JFactory::getSession();
		$session->set('fss.queue', self::$_messageQueue);
	}

	/**
	 * Get the system message queue.
	 *
	 * @return  array  The system message queue.
	 *
	 * @since   11.1
	 * @deprecated  4.0
	 */
	static function getMessageQueue()
	{
		// For empty queue, if messages exists in the session, enqueue them.
		if (!count(self::$_messageQueue))
		{
			$session = JFactory::getSession();
			$sessionQueue = $session->get('fss.queue');

			if (count($sessionQueue))
			{
				self::$_messageQueue = $sessionQueue;
				$session->set('fss.queue', null);
			}
		}

		return self::$_messageQueue;
	}

	/**
	 * Returns an encrypted & utf8-encoded
	 */
	static function encrypt($pure_string, $encryption_key) {
		if (function_exists('mcrypt_encrypt'))
		{
			$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, $pure_string, MCRYPT_MODE_ECB, $iv);
			return $encrypted_string;
		} 
		
		$result = '';
		for($i = 0; $i < strlen($pure_string); $i++) {
    		$char = substr($pure_string, $i, 1);
    		$keychar = substr($encryption_key, ($i % strlen($encryption_key))-1, 1);
    		$char = chr(ord($char) + ord($keychar));
    		$result .= $char;
		}

		return $result;
	}

	/**
	 * Returns decrypted original string
	 */
	static function decrypt($encrypted_string, $encryption_key) {
		if (function_exists('mcrypt_encrypt'))
		{
			$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
			return $decrypted_string;
		}
		
		$result = '';
		for($i = 0; $i < strlen($encrypted_string); $i++) {
    		$char = substr($encrypted_string, $i, 1);
    		$keychar = substr($encryption_key, ($i % strlen($encryption_key))-1, 1);
    		$char = chr(ord($char) - ord($keychar));
    		$result .= $char;
		}

		return $result;
	}

	static function getEncKey($salt = "")
	{
		$config = new JConfig();

		if ($salt == "") $salt = "fss_enc_salt";

		return $config->secret . $salt;
	}

	static function AutoLoginCreate($userid)
	{
		$o = mt_rand(10000,99999) . "|" . time() . "|" . $userid . "|1";

		$enc = self::encrypt($o, self::getEncKey());	
		return FSS_Helper::base64url_encode($enc);
	}

	static function AutoLoginDecrypt($data)
	{
		$data = urldecode($data);
		$data = FSS_Helper::base64url_decode($data);
		$dec = self::decrypt($data, self::getEncKey());

		$bits = @explode("|", $dec);
		if (count($bits) != 4)
			return;

		if ($bits[3] == 1)
			return $bits[2];

		return null;
	}


	static function AutoLogin($autologin)
	{
		require_once(JPATH_ROOT.DS.'libraries'.DS.'joomla'.DS.'user'.DS.'authentication.php');
		require_once(JPATH_ROOT.DS.'plugins'.DS.'user'.DS.'joomla'.DS.'joomla.php');

		// decrypt auto login data

		$login_id = self::AutoLoginDecrypt($autologin);

		if ($login_id < 1)
			return;

		$app = JFactory::getApplication();

		$user = JFactory::getUser();

		if ($user->id == $login_id)
		{
			return;
		} else if ($user->id > 0)
		{
			//echo "Logged in as a different user, logout?<br>";
			//exit;
		}

		$subject = JEventDispatcher::getInstance();

		$login_plugin = new PlgUserJoomla($subject);

		$user = JFactory::getUser($login_id);

		$response = new JAuthenticationResponse();
		$response->status = 1;
		$response->type = 'joomla';
		$response->username = $user->username;

		$options = array();
		$options['action'] = 'core.login.site';

		$results = $app->triggerEvent('onUserLogin', array((array) $response, $options));

		// Logged in ok, remove the login info form the url and reload the page
		$url = $_SERVER['REQUEST_URI'];
		$url = substr($url, 0, strpos($url, "login="));
		$url = trim($url, "?");
		$url = trim($url, "&");

		JFactory::getApplication()->redirect($url);
	}

	static $plugin_data;
	static function IsPluignEnabled($type, $plugin)
	{
		self::loadPlugins();
		
		$key = $type .	"---" . $plugin;
		if (!array_key_exists($key, self::$plugin_data))
			return 0;

		return self::$plugin_data[$key]->enabled;
	}
	
	static function getPlugins($type)
	{
		self::loadPlugins();
		
		$output = array();
		
		foreach (self::$plugin_data as $plugin)
		{
			if ($plugin->type == $type) $output[] = $plugin;
		}
		
		return $output;
	}
	
	static function loadPlugins()
	{
		if (empty(self::$plugin_data))
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_plugins";
			$db->setQuery($qry);
			$pluigns = $db->loadObjectList();

			self::$plugin_data = array();

			foreach ($pluigns as $plg)
			{
				$key = $plg->type .	"---" . $plg->name;
				self::$plugin_data[$key] = $plg;
			}
		}
	}

	static function startsWith($haystack, $needle) {
		// search backwards starting from haystack length characters from the end
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

	static function endsWith($haystack, $needle) {
		// search forward starting from end minus needle length characters
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
	}
	
	
	static function noBots()
	{
		// if the current connection is a bot of any kind, redirect back to homepage
		jimport('joomla.environment.browser');
		$doc = JFactory::getDocument();
        $browser = JBrowser::getInstance();
		
		if ($browser->isRobot())
		{
			echo "Bots are not allowed here.";
			exit;	
		}
		
		// add no crawl info to this page
		$doc->addCustomTag( "<meta name=\"robots\" content=\"noindex\" />" );
	}	
	
	static function noCache()
	{
		// we want to disable caching of the page here!
		$cache = JFactory::getCache();
		$cache->setCaching( 0 );
		JResponse::setHeader('Pragma','no-cache');
	}
		
	static function strForLike(&$in)
	{
		$db = JFactory::getDBO();
		
		if (strpos($in, "%") === false) return "%".$db->escape($in)."%";
		
		return $db->escape($in);
		
	}

	static function isValidEmail($email) 
		{
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)*.([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/" , $email)) {
			return false;
		}else{
			$record = 'MX';
			list($user,$domain) = explode('@',$email);
			if(!checkdnsrr($domain,$record)){
				return false;
			}else{
				return true;
			}
		}
	}		
	
	static function isValidName($name) 
	{
		return (strlen($name) > 4);
	}		

	static function cleanLogs()
	{
		$db = JFactory::getDBO();
		
		if (FSS_Settings::get('support_cronlog_keep') > 0)
		{
			$qry = "DELETE FROM #__fss_cron_log WHERE `when` < DATE_SUB(NOW(), INTERVAL ".(int)FSS_Settings::get('support_cronlog_keep')." DAY)";
			$db->SetQuery($qry);
			$db->Query();	
		}	
		
		if (FSS_Settings::get('support_emaillog_keep') > 0)
		{
			$db = JFactory::getDBO();	
			
			$now = FSS_Helper::CurDate();
			$qry = "DELETE FROM #__fss_ticket_email_log WHERE firstseen < DATE_SUB(NOW(), INTERVAL ".(int)FSS_Settings::get('support_emaillog_keep')." DAY)";
			$db->SetQuery($qry);
			$db->Query();	
		}	
	}
	
	static function getAttachLocation()
	{
		$loc = FSS_Settings::get('attach_location');
		$loc = str_replace("\\", DS, $loc);
		$loc = str_replace("/", DS, $loc);
		return $loc;
	}
	
		
	static function getPassthroughVars()
	{
		$vars = array('prodid', 'deptid', 'subject', 'priid', 'body', 'handler', 'catid');
		
		$fields = FSSCF::GetCustomFields(0, 0, 0);
		foreach ($fields as $field) $vars[] = 'custom_'.$field['id'];
		
		return $vars;
	}
	
	static function openPassthrough()
	{
		$vars = self::getPassthroughVars();
		
		$output = array();
		
		foreach ($vars as $var)
		{
			$value = FSS_Input::getString($var);
			if ($value)
			{
				$output[] = "<input name='$var' type='hidden' value='" . htmlspecialchars($value) . "' />";
			}				
		}	
		
		return implode("\n", $output);
	}	
	
	static function hasPassthrough($var)
	{
		$value = FSS_Input::getString($var);
		if ($value) return true;
		
		return false;
	}
	
	static function urlPassthrough($return)
	{
		// this is not actually used anywhere, but will append any
		// passthrough vars to a url
		$vars = self::getPassthroughVars();
		$return = base64_decode($return);
		
		foreach ($vars as $var)
		{
			$value = FSS_Input::getString($var);
			if ($value)
			{
				if (strpos($return, "?") === false)
				{
					$return .= "?$var=" . urlencode($value);	
				} else {
					$return .= "&$var=" . urlencode($value);	
				}
			}				
		}	

		return base64_encode($return);
	}

	static function IncludeChosen($selector = 'select')
	{
		if (FSSJ3Helper::IsJ3()) 
		{
			JHtml::_('formbehavior.chosen', 'select');
			return;
		}
		   
		// Default settings
		$options['disable_search_threshold'] = 10;
		$options['allow_single_deselect'] = true;
		$options['placeholder_text_multiple'] = JText::_('JGLOBAL_SELECT_SOME_OPTIONS');
		$options['placeholder_text_single'] = JText::_('JGLOBAL_SELECT_AN_OPTION');
		$options['no_results_text'] = JText::_('JGLOBAL_SELECT_NO_RESULTS_MATCH');

		// Options array to json options string
		$options_str = json_encode($options, false);

		$document = JFactory::getDocument();

		$document->addScript( JURI::root(true).'/components/com_fss/assets/js/jquery/jquery.chosen.js' );	
		$document->addStyleSheet(JURI::root(true).'/components/com_fss/assets/css/chosen_j25.css'); 
		JFactory::getDocument()->addScriptDeclaration("
				jQuery(document).ready(function (){
					jQuery('" . $selector . "').chosen(" . $options_str . ");
				});
			"
		);
	}
		
	static function escape_sequence_decode($str) {

		// [U+D800 - U+DBFF][U+DC00 - U+DFFF]|[U+0000 - U+FFFF]
		$regex = '/\\\u([dD][89abAB][\da-fA-F]{2})\\\u([dD][c-fC-F][\da-fA-F]{2})
				  |\\\u([\da-fA-F]{4})/sx';

		return preg_replace_callback($regex, function($matches) {

			if (isset($matches[3])) {
				$cp = hexdec($matches[3]);
			} else {
				$lead = hexdec($matches[1]);
				$trail = hexdec($matches[2]);

				// http://unicode.org/faq/utf_bom.html#utf16-4
				$cp = ($lead << 10) + $trail + 0x10000 - (0xD800 << 10) - 0xDC00;
			}

			// https://tools.ietf.org/html/rfc3629#section-3
			// Characters between U+D800 and U+DFFF are not allowed in UTF-8
			if ($cp > 0xD7FF && 0xE000 > $cp) {
				$cp = 0xFFFD;
			}

			// https://github.com/php/php-src/blob/php-5.6.4/ext/standard/html.c#L471
			// php_utf32_utf8(unsigned char *buf, unsigned k)

			if ($cp < 0x80) {
				return chr($cp);
			} else if ($cp < 0xA0) {
				return chr(0xC0 | $cp >> 6).chr(0x80 | $cp & 0x3F);
			}

			return html_entity_decode('&#'.$cp.';');
		}, $str);
	}

	static function getCurrentURL($raw = true, $remove = array('what' => 'find'))
	{
		JURI::current();// It's very strange, but without this line at least Joomla 3 fails to fulfill the task
		$router = JSite::getRouter();
		$query = $router->getVars();
		$query = array_reverse($query);

		foreach ($remove as $key => $value)
		{
			if ($value == "*" && isset($query[$key])) unset($query[$key]);
			if (isset($query[$key]) && $query[$key] == $value) unset($query[$key]);
		}

		$url = 'index.php?'.JURI::getInstance()->buildQuery($query);

		if (!$raw) $url = FSSRoute::_($url);

		return $url;
	}

	static function getCurrentURLBase64($raw = true, $remove = array('what' => 'find'))
	{
		$url = self::getCurrentURL($raw, $remove);

		return base64_encode($url);
	}
}

if (!function_exists("dumpStack"))
{
	function dumpStack($skip = 0) {
		$trace = debug_backtrace();
		$output = array();
		$pathtrim = $_SERVER['SCRIPT_FILENAME'];
		$pathtrim = str_ireplace("index.php","",$pathtrim);
		$pathtrim = str_ireplace("\\","/",$pathtrim);
		foreach ($trace as $level)
		{
			if ($skip)
			{
				$skip--;
				continue;	
			}
			if (array_key_exists('file', $level))
			{
				$file   = $level['file'];
				$line   = $level['line'];
			
				$func = $level['function'];
				if (array_key_exists("class", $level))
					$func = $level['class'] . "::" . $func;

				$file = str_replace("\\","/",$file);
				$file = str_replace($pathtrim, "", $file);
			
				$output[] = "<tr><td>&nbsp;&nbsp;Line <b>$line</b>&nbsp;&nbsp;</td><td>/$file</td><td>call to $func()</td></tr>";
			}
		}
	
		return "<table width='100%' class='table table-bordered table-condensed'>" . implode("\n",$output) . "</table>";
	}
}
