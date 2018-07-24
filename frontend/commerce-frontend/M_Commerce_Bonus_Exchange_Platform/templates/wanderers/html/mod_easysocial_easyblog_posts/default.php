<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div id="fd" class="es mod-es-recent-blogs module-social<?php echo $suffix;?>">
	<ul class="widget-list es-nav es-nav-stacked es-nav-blogs">
		<?php foreach( $posts as $post ){ ?>
		<li>
			<div class="media widget-main-link">
				<?php if( $params->get( 'show_image' , true ) ){ ?>
				<div class="media-object pull-left">
					<span class="es-avatar es-avatar-small es-borderless ">
						<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $post->id );?>" title="<?php echo $modules->html( 'string.escape' , $post->title );?>">
						<?php if( $post->getImage() ){ ?>
								<img src="<?php echo $post->getImage()->getSource( 'frontpage' );?>" />
						<?php } else { ?>
							<img src="<?php echo rtrim( JURI::root() , '/' );?>/modules/mod_easysocial_easyblog_posts/styles/default.png" />
						<?php } ?>
						</a>
					</span>
				</div>
				<?php } ?>

				<div class="media-body">
					<div class="es-mod-title">
						<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $post->id );?>"><?php echo $post->title;?></a>
					</div>
					
					<?php if( $params->get( 'show_category' , true ) ){ ?>
					<div class="es-mod-desp">
						<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=categories&layout=listings&id=' . $post->category_id );?>"><?php echo JText::_( $post->category ); ?></a>
					</div>
					<?php } ?>

					<?php if( $params->get( 'show_author' , true ) ){ ?>
					<div class="es-mod-author">
						<?php echo JText::_( 'MOD_EASYSOCIAL_EASYBLOG_BY' );?> <a href="<?php echo $post->user->getPermalink();?>"<?php echo $params->get( 'popover' , true ) ? ' data-popbox="module://easysocial/profile/popbox" data-user-id="' . $post->user->id . '"' : '';?>><?php echo $post->user->getName();?></a>
					</div>
					<?php } ?>
				</div>
			</div>
		</li>
		<?php } ?>
	</ul>
</div>
