<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (empty($depth)) $depth = 1;if (empty($catdepth)) $catdepth = 1; ?>

<?php if (  (array_key_exists("subcats",$cat) && count($cat['subcats']) > 0) || (array_key_exists("arts",$cat) && count($cat['arts'])) || $this->view_mode_cat == "normal" || FSS_Input::getInt('catid') == $cat['id'] ): ?>

	<?php 
	// check products that the cat can be shown in
	$prodid = FSS_Input::getInt('prodid');
	$can_show = true;
	if ($prodid > 0)
	{
		$prodids = $cat['prodids'];
		if ($prodids != "")
		{
			$prodids = explode(";", $prodids);
			if (!in_array($prodid, $prodids))
				$can_show = false;
		}
	}

	if ($can_show): ?>
		<div class='media' >
			<?php if ($cat['image']) : ?>
			<a class='pull-left' href="<?php if ($this->view_mode_cat == "accordian"): ?>#<?php else: ?><?php echo FSSRoute::_( $this->base_url . '&catid=' . $cat['id'] );// FIX LINK?><?php endif; ?>" onclick="return false;"
				<?php if ($this->view_mode_cat == "accordian"): ?>
					style="cursor: pointer" data-toggle="collapse" data-target="#kb_cat_content_<?php echo $cat['id']; ?>" data-parent="#kb_categories"
				<?php endif; ?>
				>
				<?php if ($catdepth > 1 && FSS_Settings::get('kb_smaller_subcat_images')): ?>
					<img class='media-object' src='<?php echo JURI::root( true ); ?>/images/fss/kbcats/<?php echo FSS_Helper::escape($cat['image']); ?>' width='32' height='32'>
				<?php else : ?>
					<img class='media-object' src='<?php echo JURI::root( true ); ?>/images/fss/kbcats/<?php echo FSS_Helper::escape($cat['image']); ?>' width='64' height='64'>
				<?php endif; ?>
			</a>
			<?php endif; ?>
			<div class="media-body">
				<div>	
					<div 
						<?php if ($this->view_mode_cat == "accordian"): ?>
							style="cursor: pointer" data-toggle="collapse" data-target="#kb_cat_content_<?php echo $cat['id']; ?>" data-parent="#kb_categories"
						<?php endif; ?>
						> 	
						<h4 class='media-heading' <?php if ($this->view_mode_cat == "accordian") echo " style='padding-top:6px;padding-bottom:6px;' "; ?>>
							<?php if ($cat['id'] == $this->curcatid) : ?><b><?php endif; ?>
					
							<?php if ($this->view_mode_cat == "popup") : ?>
	
								<a class="show_modal_iframe" href='<?php echo FSSRoute::_( $this->base_url . '&tmpl=component&catid=' . $cat['id'] . '&view_mode=' . $this->view_mode_incat );// FIX LINK ?>'>
									<?php echo $cat['title'] ?>
								</a>

							<?php elseif ($this->view_mode_cat == "accordian"): ?>
								<a href="#" onclick='return false;'><?php echo $cat['title'] ?></a> 			
							<?php else: ?>
			
								<?php if ($this->view_mode_cat == "normal"): ?>
									<a href='<?php echo FSSRoute::_( $this->base_url . '&catid=' . $cat['id'] );?>'><?php echo $cat['title'] ?></a>
								<?php else: ?>
									<?php echo $cat['title'] ?>
								<?php endif; ?>

							<?php endif; ?>
					
							<?php if ($cat['id'] == $this->curcatid) : ?></b><?php endif; ?>
						</h4>
						<?php echo $cat['description']; ?>
					</div>
				</div>
			
				<?php if ($this->view_mode_cat == "links" || $this->view_mode_cat == "accordian") : ?>
				<div class="fss_clear"></div>
					<div id="kb_cat_content_<?php echo $cat['id']; ?>" class="<?php if ($this->view_mode_cat == "accordian") echo "collapse" ?>" <?php if (!$cat['image']) : ?> style="margin-left: 16px;" <?php endif; ?>>
						<!-- Category contents -->
						<?php if (empty($catold)) $catold = array(); ?>
						<!-- Sub categories -->
						<?php $catdepth++; ?>
						<?php if (array_key_exists("subcats",$cat) && count($cat['subcats']) > 0) : ?>
							<?php array_push($catold, $cat); $depth++;?>
								<?php foreach ($cat['subcats'] as &$cat): ?>
									<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php'); ?>
								<?php endforeach; ?>
							<?php $cat = array_pop($catold); $depth--; ?>
						<?php endif; ?>
						<?php $catdepth--; ?>
						<!-- Articles -->
						<?php if (array_key_exists("arts",$cat) && count($cat['arts'])): ?>
							<?php foreach ($cat['arts'] as &$art) : ?>
								<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art.php'); ?>
							<?php endforeach; ?>
						<?php endif; ?>	
					</div>
				<?php endif; ?>				
			</div>
		</div>			
	<?php endif; ?>

<?php endif; ?>