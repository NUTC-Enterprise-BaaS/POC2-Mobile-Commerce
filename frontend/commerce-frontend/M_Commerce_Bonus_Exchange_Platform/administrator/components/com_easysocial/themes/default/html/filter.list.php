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
<select name="<?php echo $name;?>" class="form-control input-sm" data-table-grid-filter>
	<option value="<?php echo $initialValue; ?>"><?php echo $initial;?></option>

	<?php foreach( $items as $item ){ ?>
	<option value="<?php echo $item;?>"<?php echo $selected == $item ? ' selected="selected"' : '';?>><?php echo $item;?></option>
	<?php } ?>

</select>
