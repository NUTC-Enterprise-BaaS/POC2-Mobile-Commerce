<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();

$user = JFactory::getUser();
if($user->id == 0) {
    $app = JFactory::getApplication();
    $app->redirect(JRoute::_('index.php?option=com_users&view=login'));
}
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= true;
$saveOrder	= $listOrder == 'cm.ordering';

$isProfile = true;
?>
<script>
    var isProfile = true;
</script>
<style>
    #header-box, #control-panel-link {
        display: none;
    }
</style>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=managecompanymessages');?>" method="post" name="adminForm" id="adminForm">
    <table class="dir-table dir-panel-table" id="itemList">
        <thead>
        <tr>
            <th class="hidden-xs hidden-phone" width="1%" >#</th>
            <th width="5%"><?php echo JText::_('LNG_NAME') ?></th>
            <th width="1%" class="hidden-xs hidden-phone"><?php echo JText::_('LNG_EMAIL') ?></th>
            <th width="10%" class="hidden-xs hidden-phone"><?php echo JText::_('LNG_COMPANY_NAME') ?></th>
            <th width="25%" class=""><?php echo JText::_('LNG_MESSAGE') ?></th>
            <th width="1%" class="hidden-xs hidden-phone"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td>
        </tr>
        </tfoot>
        <tbody>
        <?php if(!empty($this->items)) : ?>
            <?php foreach($this->items as $i=>$item) : ?>
                <tr>
                    <td class="hidden-xs hidden-phone">
                        <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td>
                        <?php echo $item->name." ".$item->surname; ?>
                    </td>
                    <td class="hidden-xs hidden-phone">
                        <?php echo $item->email; ?>
                    </td>
                    <td class="hidden-xs hidden-phone">
                        <?php echo $item->companyName; ?>
                    </td>
                    <td>
                        <?php echo $item->message; ?>
                    </td>
                    <td class="hidden-xs hidden-phone">
                    	 <a  href="javascript:deleteMessage(<?php echo $item->id; ?>)"
	                           title="<?php echo JText::_('LNG_CLICK_TO_DELETE'); ?>">
	                           	<i class="dir-icon-trash-o dir-icon-large">&nbsp;</i>
	                      </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
    <input type="hidden" name="task" id="task" value="" />
    <input type="hidden" name="id" id="id" value="" />
    <input type="hidden" name="Itemid" id="Itemid" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<script>
    function deleteMessage(id) {
        if(confirm("<?php echo JText::_('COM_JBUSINESS_DIRECTORY_COMPANY_MESSAGE_CONFIRM_DELETE', true);?>")) {
            jQuery("#id").val(id);
            jQuery("#task").val("managecompanymessages.delete");
            jQuery("#adminForm").submit();
        }
    }
</script>


