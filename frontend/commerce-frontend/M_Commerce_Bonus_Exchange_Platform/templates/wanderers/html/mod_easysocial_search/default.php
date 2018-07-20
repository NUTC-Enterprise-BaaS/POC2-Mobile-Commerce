<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<form class="theme-helper-search" action="<?php echo JRoute::_( 'index.php' );?>" method="post" data-mod-search>
	<input name="q" type="text" class="form-control input-sm" autocomplete="off" data-nav-search-input placeholder="<?php echo JText::_( 'MOD_EASYSOCIAL_SEARCH_PHASE' , true );?>" />

	<input type="hidden" name="view" value="search" />
	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="Itemid" value="<?php echo FRoute::getItemId('search');?>" />
	<?php echo $modules->html( 'form.token' );?>
</form>
