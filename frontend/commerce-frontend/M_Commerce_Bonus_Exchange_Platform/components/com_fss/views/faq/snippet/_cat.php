<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	    	
	<div class="media faq_cat faq_cat_<?php echo $cat['id']; ?>">
		
		<a class="pull-left" href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&catid=' . $cat['id']);?>"
				<?php if ($this->view_mode_cat == "accordian"): ?>
					style="cursor: pointer" data-toggle="collapse" data-target="#cat_content_<?php echo $cat['id']; ?>" data-parent="#faq_categories" onclick="return false;"
				<?php endif; ?>
				>
			<?php if ($cat['image']) : ?>
					<?php if (substr($cat['image'],0,1) == "/") : ?>
	    			<img class="media-object" src='<?php echo JURI::root( true ); ?><?php echo FSS_Helper::escape($cat['image']); ?>' width='64' height='64'>
	    		<?php else: ?>
	    			<img class="media-object" src='<?php echo JURI::root( true ); ?>/images/fss/faqcats/<?php echo FSS_Helper::escape($cat['image']); ?>' width='64' height='64'>
	    		<?php endif; ?>
			<?php endif; ?>
		</a>
		
		<div class="media-body">
			<div <?php if ($cat['image']) : ?>style="min-height: 64px"<?php endif; ?>>
				<div
					<?php if ($this->view_mode_cat == "accordian"): ?>
						style="cursor: pointer" data-toggle="collapse" data-target="#cat_content_<?php echo $cat['id']; ?>" data-parent="#faq_categories"
					<?php endif; ?>
					>
					<h4 class="media-heading">
						<?php if ($this->view_mode_cat == "popup") : ?>
	
							<a class="show_modal_iframe" href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&tmpl=component&catid=' . $cat['id'] . '&view_mode=' . $this->view_mode_incat); ?>'>
								<?php echo $cat['title'] ?>
							</a>

						<?php elseif ($this->view_mode_cat == "accordian"): ?>

							<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&catid=' . $cat['id']);?>" onclick='return false;'><?php echo $cat['title'] ?></a>
						
						<?php else: ?>

							<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&catid=' . $cat['id']);?>'><?php echo $cat['title'] ?></a>

						<?php endif; ?>
					</h4>
			
					<?php echo $cat['description']; ?>
				</div>
			</div>
			
			<!-- INLINE FAQS -->
			<?php if ($this->view_mode_cat == "inline" || $this->view_mode_cat == "accordian") : ?>
				<div id="cat_content_<?php echo $cat['id']; ?>" class="<?php if ($this->view_mode_cat == "accordian") : ?>collapse<?php endif; ?>" style="<?php if (!$cat['image']) : ?>margin-left: 64px;<?php endif; ?>">
				
					<?php if ($this->view_mode_cat == "accordian") $acl = 2; ?>
					<?php $this->view_mode = $this->view_mode_incat; ?>
					
					<?php if (array_key_exists('faqs',$cat) && count($cat['faqs']) > 0): ?>
						
						<?php foreach ($cat['faqs'] as &$faq) : ?>
							<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'faq'.DS.'snippet'.DS.'_faq.php'); ?>
						<?php endforeach; ?>	
						
					<?php else: ?>

						<p><?php echo JText::_("NO_FAQS_FOUND_IN_THIS_CATEGORY");?></p>

					<?php endif; ?>
					
					<?php if ($this->view_mode_cat == "accordian") $acl = 1; ?>
				</div>
			
			<?php endif; ?>				
			<!-- END INLINE FAQS -->

			<!--<div class='fss_clear'></div>-->
		</div>
	</div>		

