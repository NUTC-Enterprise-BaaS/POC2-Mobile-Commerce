<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (FSSJ3Helper::IsJ3())
{
	abstract class JHtmlFSJTabs
	{
		/**
		 * Creates a panes and creates the JavaScript object for it.
		 *
		 * @param   string  $group   The pane identifier.
		 * @param   array   $params  An array of option.
		 *
		 * @return  string
		 *
		 * @since   11.1
		 */
		public static function start($group = 'tabs', $params = array())
		{
			self::_loadBehavior($group, $params);

			return '<ul class="nav nav-tabs" id="tabs_' . $group . '"><div>';
		}

		/**
		 * Close the current pane
		 *
		 * @return  string  HTML to close the pane
		 *
		 * @since   11.1
		 */
		public static function end($group = 'tabs')
		{
			return '</div></ul><div class="tab-content" id="tabcontent_' . $group . '"></div>';
		}

		/**
		 * Begins the display of a new panel.
		 *
		 * @param   string  $text  Text to display.
		 * @param   string  $id    Identifier of the panel.
		 *
		 * @return  string  HTML to start a new panel
		 *
		 * @since   11.1
		 */
		public static function panel($text, $id, $active = false)
		{
			if ($active)
			{
				return '</div><li class="active"><a href="#'.$id.'" data-toggle="tab">' . $text . '</a></li><div class="tab-pane active" id="'.$id.'">';
			} else {
				return '</div><li><a href="#'.$id.'" data-toggle="tab">' . $text . '</a></li><div class="tab-pane" id="'.$id.'">';
			}
		}

		/**
		 * Load the JavaScript behavior.
		 *
		 * @param   string  $group   The pane identifier.
		 * @param   array   $params  Array of options.
		 *
		 * @return  void
		 *
		 * @since   11.1
		 */
		protected static function _loadBehavior($group, $params = array())
		{
			static $loaded = array();

			if (!array_key_exists((string) $group, $loaded))
			{
				$js = "
					jQuery(document).ready( function () {
						jQuery('ul#tabs_$group div').each( function () {
							jQuery('div#tabcontent_$group').append(this);
						});
					});";

				$document = JFactory::getDocument();
				$document->addScriptDeclaration($js);

				$loaded[(string) $group] = true;
			}
		}
	}
} else {
	abstract class JHtmlFSJTabs
	{
		static $pane;
		static $inpanel = false;
		public static function start($group = 'tabs', $params = array())
		{
			JHtmlFSJTabs::$pane =JPane::getInstance($group, array('allowAllClose' => true));
			return JHtmlFSJTabs::$pane->startPane($group);
		}
	
		public static function end($group = 'tabs')
		{
			return JHtmlFSJTabs::$pane->endPanel() . JHtmlFSJTabs::$pane->endPane();
		}	
		
		public static function panel($text, $id, $active = false)
		{
			$output = "";
			if (JHtmlFSJTabs::$inpanel)
			{
				$output .= JHtmlFSJTabs::$pane->endPanel();
			}
			$output .= JHtmlFSJTabs::$pane->startPanel( $text, $id );
			JHtmlFSJTabs::$inpanel = true;
			
			return $output;
		}
	}
}