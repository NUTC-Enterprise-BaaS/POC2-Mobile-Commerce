<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="es-field-datetime-group" style="width:26%;">
<?php echo JText::_('PLG_FIELDS_DATETIME_DAY'); ?>
<select
    data-field-datetime-day
    class="form-control input-sm"

>
    <option value=""><?php echo JText::_('PLG_FIELDS_DATETIME_DAY'); ?></option>

    <?php for ($i = 1; $i <= $maxDay; $i++) { ?>
        <option value="<?php echo $i; ?>" <?php if ($day == $i) { ?>selected="selected"<?php } ?>><?php echo $i; ?></option>
    <?php } ?>
</select>
</div>
