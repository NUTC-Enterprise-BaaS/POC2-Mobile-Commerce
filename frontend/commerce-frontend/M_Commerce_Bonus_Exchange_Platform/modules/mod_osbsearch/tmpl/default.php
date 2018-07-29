<?php
/*------------------------------------------------------------------------
# default.php - OSB Search
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2014 joomdonation.com. All Rights Reserved.
# @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

?>
<div class="row-fluid modosbsearch<?php echo $moduleclass_sfx; ?>">
	<form method="post" action="<?php echo JRoute::_('index.php?option=com_osservicesbooking&Itemid=9999');?>" class="form-horizontal">
		<?php 
		if($show_venue == 1){
		?>
			<div class="span12" style="margin-top:10px;">
			
				<?php echo JText::_('OS_VENUE');?>
			</div>
			<div class="span12">
				<?php echo $lists['venue'];?>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
		<?php 
		if($show_category == 1){
		?>
			<div class="span12" style="margin-top:10px;">
				<?php echo JText::_('OS_CATEGORY');?>
			</div>
			<div class="span12">
					<?php echo $lists['category'];?>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
        <?php
        if($show_service == 1){
            ?>
            <div class="span12" style="margin-top:10px;">

                <?php echo JText::_('OS_SERVICE');?>
            </div>
            <div class="span12">
                <?php echo $lists['service'];?>
            </div>
            <div class="clearfix"></div>
        <?php } ?>
		<?php 
		if($show_employee == 1){
		?>
			<div class="span12" style="margin-top:10px;">
				<?php echo JText::_('OS_EMPLOYEE');?>
			</div>
			<div class="span12">
				<?php echo $lists['employee'];?>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
		<?php 
		if($show_date == 1){
		?>
			<div class="span12" style="margin-top:10px;">
				<?php echo JText::_('OS_SELECT_DATE');?>
			</div>
			<div class="span12">
				<?php 
				echo JHtml::_('calendar',Jrequest::getVar('selected_date',''),'selected_date','selected_date','%d-%m-%Y',array("class" => "input-small"));
				?>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
		<div class="span12" style="margin-top:10px;">
			<input type="submit" class="btn btn-success" value="<?php echo JText::_('OS_SUBMIT');?>" />
		</div>
	</form>
</div>