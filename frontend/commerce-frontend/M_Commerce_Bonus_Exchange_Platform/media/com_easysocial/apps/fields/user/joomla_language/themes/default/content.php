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
<div data-field-joomla_user_editor>
	<select name="<?php echo $inputName;?>" class="form-control input-sm">
		<option value=""<?php echo !$value ? ' selected="selected"' : '';?>><?php echo JText::_( 'PLG_FIELDS_USER_JOOMLA_LANGUAGE_USE_DEFAULT' ); ?></option>
		<?php foreach( $languages as $language ){ ?>
		<option value="<?php echo $language['value'];?>" <?php echo $value === $language['value'] ? 'selected="selected"' : ''; ?>><?php echo $language['text'];?></option>
		<?php } ?>
	</select>
</div>
