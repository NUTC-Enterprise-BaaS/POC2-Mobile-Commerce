<?php
/**
 * @version		$Id: grid.php 14276 2010-01-18 14:20:28Z laurelle $
 * @package		Joomla.Administrator
 * @subpackage	mod_kc_admin_quickicons
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @copyright	Copyright (C) 2010 - 2014 Keashly.ca Consulting
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

$buttons = KC_Admin_QuickIconHelper::getButtons( $params );
?>
<div class="<?php echo $params->get('moduleclass_sfx'); ?> kc_admin_quickicons_cpanel<?php echo $params->get('moduleclass_sfx'); ?> grid">
	<?php
  	foreach ($buttons as $button):  ?>
    	<div class="kc_icon-wrapper">
            <div class="kc_icon">
								<?php if ($button['target'] == '') : ?>
                  <a href="<?php echo $button['link']; ?>">
                <?php else : ?>
                  <a href="<?php echo $button['link']; ?>" target="<?php echo $button['target']; ?>">
                <?php endif; ?>
                <?php if ($button['icomoonicon'] != '') : ?>
                	<i class="<?php echo $button['icomoonicon']; ?>"></i>
      						<span class="kc_inline"><?php echo JText::_($button['text']); ?></span>
                <?php else : ?>
									<!--<img src="<?php echo $button['path'] . $button['image']; ?>" alt="<?php echo JText::_($button['text']); ?>" /> -->
									<?php echo JHTML::_('image', $button['path'].$button['image'], NULL, NULL, false); ?>
									<span><?php echo JText::_($button['text']); ?></span>
                <?php endif; ?>
              </a>
            </div>
      </div>
  <?php
    endforeach; ?>
</div>