<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<a href="javascript:void(0);"
	data-original-title="<?php echo JText::sprintf( 'COM_EASYSOCIAL_GRID_SORT_BY' , JString::strtolower( $text ) );?>"
	data-es-provide="tooltip"
	data-table-grid-sort
	data-sort="<?php echo $column;?>"
	data-direction="<?php echo $direction == 'desc' ? 'asc' : 'desc';?>"
>
	<?php echo $text; ?>

	<?php if( str_ireplace( '.' , '' , $column ) == $currentOrdering ){ ?>
		<?php if ($direction == 'asc') { ?>
		<i class="fa fa-caret-up"></i>
		<?php } ?>

		<?php if ($direction == 'desc') { ?>
		<i class="fa fa-caret-down"></i>
		<?php } ?>
	<?php } ?>
</a>
