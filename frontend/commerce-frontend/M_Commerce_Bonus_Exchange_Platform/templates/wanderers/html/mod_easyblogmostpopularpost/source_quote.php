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
<?php if( $params->get( 'showauthor' ) || $params->get( 'showdate' , true ) ) { ?>
<div class="post-author small quote-source">
	<?php if( $params->get( 'showauthor' ) ) { ?>
		<?php echo JText::_( 'MOD_EASYBLOGMOSTPOPULARPOST_META_QUOTE' ); ?>
		<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=blogger&layout=listings&id=' . $post->author->id . $menuItemId );?>"><?php echo $post->author->getName();?></a>
	<?php } ?>

	<?php if( $params->get( 'showdate' , true ) ) { ?>
		<?php echo JText::_( 'MOD_EASYBLOGMOSTPOPULARPOST_ON' );?> <?php echo $post->date;?>
	<?php } ?>
</div>
<?php } ?>