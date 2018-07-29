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
<div class="form-inline" data-limit-form>
	<?php echo $this->html( 'grid.boolean' , uniqid() , $params->get( $field->name , $field->default ) == 0 ? true : false , '' , array( 'data-' . str_ireplace( array( '[' , '.' , ']' ) , '' , $field->inputName ) ) , array() ,
							array( 'on' => JText::_( 'Unlimited' ) , 'off' => JText::_( 'Limited' )  )
			); ?>

	<span class="<?php echo $params->get( $field->name , $field->default ) == 0 ? 'hide' : '';?>" data-limit-limited>
		<input data-limit-input type="text" name="<?php echo $field->inputName;?>"
            <?php if (isset($field->maxupload) && $field->maxupload) { ?>
            data-maxupload-check
            data-maxupload="<?php echo $field->maxupload; ?>"
            data-maxupload-key="<?php echo strtoupper(str_replace('.', '_', $field->name)); ?>"
            <?php } ?>
            id="<?php echo $field->name;?>"
            placeholder="<?php echo isset( $field->placeholder ) ? $field->placeholder : '';?>"
			class="form-control input-sm ml-5 input-short text-center <?php echo isset( $field->class ) ? $field->class : '';?>"
			value="<?php echo $params->get( $field->name , $field->default );?>" /> <?php echo isset( $field->suffix ) ? $field->suffix : '';?>
	</span>
</div>
<?php if (isset($field->maxupload) && $field->maxupload) { ?>
<span class="fd-small"><?php echo $field->maxuploadDisplay; ?></span>
<?php } ?>
