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
<div data-field-gender class="form-group">
	<label class="radio-inline">
		<input type="radio" value="1" id="<?php echo $inputName;?>" name="<?php echo $inputName;?>" data-field-gender-select /> <?php echo JText::_( 'PLG_FIELDS_GENDER_SELECT_MALE' );?>
	</label>
	<label class="radio-inline">
		<input type="radio" value="2" id="<?php echo $inputName;?>" name="<?php echo $inputName;?>" data-field-gender-select /> <?php echo JText::_( 'PLG_FIELDS_GENDER_SELECT_FEMALE' );?>
	</label>
    <?php if ($params->get('custom')) { ?>
	<label class="radio-inline">
		<input type="radio" value="3" id="<?php echo $inputName;?>" name="<?php echo $inputName;?>" data-field-gender-select /> <?php echo JText::_( 'PLG_FIELDS_GENDER_SELECT_CUSTOM' );?>
	</label>
    <?php } ?>
</div>
