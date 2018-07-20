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
<div class="row" data-base>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-head">
                <b><?php echo JText::_('COM_EASYSOCIAL_MAINTENANCE_DATABASE'); ?></b>
                <p><?php echo JText::_('COM_EASYSOCIAL_MAINTENANCE_DATABASE_DESC'); ?></p>
            </div>

            <div class="panel-body">
                <a href="javascript:void(0);" class="btn btn-success" data-start><?php echo JText::_('COM_EASYSOCIAL_MAINTENANCE_DATABASE_START'); ?></a>

                <div class="mt-20" data-progress style="display: none;">
                    <div class="es-progress-wrap">
                        <div class="progress progress-info" data-progress-box>
                            <div class="bar" style="width: 0%" data-progress-bar></div>
                            <div class="progress-result" data-progress-percentage >0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
