<?php
/**
 * @copyright   (C) 2011 - 2016 Mike Feng Jinglong - All rights reserved.
 * @license  GNU General Public License, version 3 (http://www.gnu.org/licenses/gpl-3.0.html)
 * @author  Mike Feng Jinglong <mike@simbunch.com>
 * @url   http://www.simbunch.com/license/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class ProfileURLWidgetsProfile extends SocialAppsWidgets {
	public function sidebarBottom($user) {
		JHtml::_('behavior.framework', true);
		require_once(JPATH_SITE.'/components/com_profileurl/helpers/plugin.php');
		$puPlugin = new PUPlugin();

		$puPlugin->uid = $user->id;
		echo $puPlugin->outputProfilePlugin();	
	}
}