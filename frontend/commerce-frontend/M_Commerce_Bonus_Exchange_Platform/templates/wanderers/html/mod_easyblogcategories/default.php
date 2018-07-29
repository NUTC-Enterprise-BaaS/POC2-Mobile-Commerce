<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>

<div id="ezblog-categories" class="ezb-mod mod_easyblogcategories<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<?php if(!empty($categories)){ ?>
		<?php echo modEasyBlogCategoriesHelper::accessNestedCategories( $categories , $selected , $params ); ?>
	<?php } else { ?>
			<?php echo JText::_('MOD_EASYBLOGCATEGORIES_NO_CATEGORY'); ?>
	<?php } ?>
</div>
