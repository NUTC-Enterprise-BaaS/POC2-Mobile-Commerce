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
<!-- Photo items -->
<?php if( $post->images && $params->get( 'photo_show' , 1 ) ){ ?>
	<?php foreach( $post->images as $image ){ ?>
		<p class="mod-post-photo">
			<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id='.$post->id . $menuItemId); ?>" style="max-height:<?php echo $params->get( 'photo_height' , 250 ); ?>px;">
				<img src="<?php echo $image;?>" style="max-width:<?php echo $params->get( 'photo_width' , 250 );?>px;" />
			</a>
		</p>
	<?php } ?>
<?php } ?>