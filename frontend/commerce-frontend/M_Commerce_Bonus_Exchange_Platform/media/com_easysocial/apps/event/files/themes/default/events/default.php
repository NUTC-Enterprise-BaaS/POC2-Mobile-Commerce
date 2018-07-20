<?php
/**
* @package        EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license        GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="app-explorer app-groups" data-id="<?php echo $event->id; ?>">

    <div class="es-filterbar row-table">
        <div class="col-cell filterbar-title"><?php echo JText::_('APP_EVENT_FILES_FILE_MANAGER'); ?></div>
    </div>

    <div class="app-contents-wrap">
        <?php echo $explorer->render('site/controllers/events/explorer', array('allowUpload' => $allowUpload, 'uploadLimit' => $uploadLimit, 'controllerName' => 'events')); ?>
    </div>

</div>

