<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
FSS_Helper::ModuleStart("mod_fss_catprods mod_fss_catprods_kbprod");
?>
<div class="faq_mod_category_cont">
	<?php foreach ($rows as $cat): ?>

		<?php $link = FSSRoute::_( 'index.php?option=com_fss&view=kb&prodid=' . $cat['id'] ); ?>
		<div style='cursor:pointer;clear:both;padding-bottom:6px;'>
		
			<a href='<?php echo $link ?>'>
				<?php if ($params->get('show_images') && $cat['image']) : ?>
					<img src='<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $cat['image']; ?>' width='24' height='24'>
				<?php endif; ?>
				
				<?php echo $cat['title'] ?>
			</a>
		</div>

	<?php endforeach; ?>

</div>
<?php FSS_Helper::ModuleEnd(); ?>