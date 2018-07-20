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

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="mod-item">
	<div<?php echo ( $params->get( 'layouttype' ) == 'tree' ) ? ' style="padding-left: ' . $padding . 'px;"' : '';?>>
 	<?php if ($params->get('showcavatar', true)) : ?>
		<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id='.$category->id . $menuItemId );?>" class="mod-avatar">
			<img class="avatar" src="<?php echo modEasyBlogCategoriesHelper::getAvatar($category); ?>" width="40" alt="<?php echo $category->title; ?>" />
		</a>
	<?php endif; ?>
 		<div class="mod-category-detail">
			<div class="mod-category-name">
				<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id='.$category->id . $menuItemId );?>"><?php echo JText::_( $category->title ); ?>
					<?php if( $params->get( 'showcount' , true ) ){ ?>
					<b><?php echo JText::sprintf( $category->cnt) ;?></b>
					<?php } ?>
				</a>
			</div>
		 </div>
	</div>
</div>