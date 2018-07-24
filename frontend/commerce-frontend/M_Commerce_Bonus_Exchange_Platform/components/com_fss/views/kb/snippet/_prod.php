<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class='media kb_prod_<?php echo $product['id']; ?>' >
	
<?php FSS_Translate_Helper::TrSingle($product); ?>

	<?php if ($product['image']) : ?>
	<div class='pull-left'>
		<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=kb&prodid=' . $product['id'] );?>">
			<img class='media-object' src='<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo FSS_Helper::escape($product['image']); ?>' width='64' height='64'>
		</a>
	</div>
	<?php endif; ?>
	
	<div class="media-body">
		<h4 class='media-heading'>
			<?php if (FSS_Input::getInt('prodid') != 0) : ?>
				<a href='<?php echo FSSRoute::_( $this->base_url . '&prodid=' . $product['id'] );?>'>
					<?php echo $product['title'] ?>
				</a>
					<?php else : ?>
				<a href='<?php echo FSSRoute::_( $this->base_url . '&prodid=' . $product['id'] );?>'>
					<?php echo $product['title'] ?>
				</a>
			<?php endif; ?>
		</h4>
		<?php echo $product['description']; ?>
	</div>
</div>
