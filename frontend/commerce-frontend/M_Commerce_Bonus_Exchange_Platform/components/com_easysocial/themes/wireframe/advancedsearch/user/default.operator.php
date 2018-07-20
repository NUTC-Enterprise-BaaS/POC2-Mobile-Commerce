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
<span class="" data-itemOperator >
	<select autocomplete="off" class="form-control input-sm" name="operators[]">
		<?php foreach( $operators as $operator => $label ){ ?>
			<option value="<?php echo $operator; ?>"<?php echo $operator == $selected ? ' selected="selected"' : '';?>><?php echo JText::_( $label ); ?></option>
		<?php } ?>
	</select>
</span>
