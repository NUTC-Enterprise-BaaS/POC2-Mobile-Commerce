<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Include helper lib
require_once( dirname( __FILE__ ) . '/helper.php' );

/**
 * Helper for joomla user editor field.
 *
 * @since	1.2
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialLanguageHelper
{
	/**
	 * Retrieves a list of languages installed on the site
	 *
	 * @since	1.2
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function getLanguages( $selected = '', $subname = true )
	{
		$languages 	= JLanguageHelper::createLanguageList( $selected , constant( 'JPATH_SITE' ), true, true );

		if (!$subname) {
			for ($i = 0; $i < count($languages); $i++) {
				$languages[$i]['text'] = preg_replace('#\(.*?\)#i', '', $languages[$i]['text']);
			}
		}

		return $languages;
	}
}
