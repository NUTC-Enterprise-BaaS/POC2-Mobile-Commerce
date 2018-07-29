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
<div class="es-story-event-form" data-story-event-base>
    <div class="form-group mb-0">
        <select data-story-event-category class="form-control input-sm">
            <option value=""><?php echo JText::_('COM_EASYSOCIAL_EVENTS_SELECT_CATEGORY'); ?></option>
        <?php foreach ($categories as $category) { ?>
            <option value="<?php echo $category->id; ?>"><?php echo $category->get('title'); ?></option>
        <?php } ?>
        </select>
    </div>

    <div data-story-event-form style="display: none;" class="mt-10">
    </div>
</div>
