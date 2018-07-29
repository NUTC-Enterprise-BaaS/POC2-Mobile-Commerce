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
<?php if( !empty( $post->videos) && $params->get( 'video_show' , 1 ) ){ ?>
<div class="post-video">
	<?php foreach( $post->videos as $video ){ ?>
		<?php echo EasyBlogHelper::getHelper( 'Videos' )->processVideoLink( $video->video , $params->get( 'video_width' , 250 ) , $params->get( 'video_height' , 250 ) ); ?>
	<?php } ?>
</div>
<?php } ?>