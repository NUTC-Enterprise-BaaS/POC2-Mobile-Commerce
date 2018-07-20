<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$user = JFactory::getUser();
?>

<div class="review-questions">
<?php if(!empty($this->reviewQuestions)){?>
	<?php foreach($this->reviewQuestions as $reviewQuestion) { ?>
	    <?php if($reviewQuestion->published) { ?>
	        <?php if($reviewQuestion->type == 0) { ?>
	        	<div class="form-item">
		            <label for="user_rating"><?php echo $reviewQuestion->name ?></label>
		            <div class="outer_input">
		          		<textarea class="<?php echo $reviewQuestion->is_mandatory?'validate[required]':'' ?>" name='question-<?php echo $reviewQuestion->id ?>' id='question-<?php echo $reviewQuestion->id ?>'></textarea>
		          	 </div>
				</div>
	        <?php }
	        else if($reviewQuestion->type == 1) { ?>
	               <div class="form-item">
	                   <label id="question-<?php echo $reviewQuestion->id; ?>-lbl" for="question-<?php echo $reviewQuestion->id; ?>" class="hasTooltip" title=""><?php echo $reviewQuestion->name; ?></label>
	                    <div class="outer_input controlls">
	                        <fieldset id="question-<?php echo $reviewQuestion->id; ?>_fld" class="radio btn-group btn-group-yesno">
	                            <input type="radio" name="question-<?php echo $reviewQuestion->id; ?>" id="question-<?php echo $reviewQuestion->id; ?>1" value="1" <?php echo $reviewQuestion->is_mandatory?'checked="checked"' :"" ?> />
	                            <label class="btn" for="question-<?php echo $reviewQuestion->id; ?>1"><?php echo JText::_('LNG_YES')?></label>
	                            <input type="radio" name="question-<?php echo $reviewQuestion->id; ?>" id="question-<?php echo $reviewQuestion->id; ?>0" value="0" />
	                            <label class="btn" for="question-<?php echo $reviewQuestion->id; ?>0"><?php echo JText::_('LNG_NO')?></label>
	                        </fieldset>
	                    </div>
	                </div>
	        <?php }
	        else if($reviewQuestion->type == 2) { ?>
	                <div class="user-rating clearfix">
	                    <label for="user_rating"><?php echo $reviewQuestion->name ?></label><div class="rating-question"></div>
	                    <input type="text" style="visibility: hidden;" class="<?php echo $reviewQuestion->is_mandatory?'validate[required]':'' ?>" name="question-<?php echo $reviewQuestion->id ?>" id="review-question" value="">
	                </div>
	        <?php } ?>
	    <?php } ?>
	<?php } ?>
<?php } ?>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user->id ?>" />
</div>
