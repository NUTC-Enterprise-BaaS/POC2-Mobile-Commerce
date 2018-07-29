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
<span class="input-append">
	<input type="text" class="input-medium" disabled="disabled" size="35" value="<?php echo JText::_( $label ); ?>" data-jfield-groupcategory-title />
	<a class="btn btn-primary" data-jfield-groupcategory href="javascript:void(0);" style="display: inline-block; vertical-align: middle;margin: 5px; line-height:19px">
		<i class="icon-users"></i> <?php echo JText::_( 'COM_EASYSOCIAL_JFIELD_SELECT_GROUP_CATEGORY' ); ?>
	</a>
	<input type="hidden" id="<?php echo $id;?>_id" name="<?php echo $name;?>" value="<?php echo $value;?>" data-jfield-groupcategory-value />
</span>
