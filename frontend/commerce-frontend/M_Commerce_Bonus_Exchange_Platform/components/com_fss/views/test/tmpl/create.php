<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo $this->tmpl ? FSS_Helper::PageStylePopup(true) : FSS_Helper::PageStyle(); ?>


	<?php echo $this->tmpl ? FSS_Helper::PageTitlePopup("TESTIMONIALS","ADD_A_TESTIMONIAL") : FSS_Helper::PageTitle("TESTIMONIALS","ADD_A_TESTIMONIAL"); ?>
	<div class='fss_kb_comment_add' id='add_comment'>
		<?php $this->comments->DisplayAdd(); ?>
	</div>

	<div id="comments"></div>

	<div class='fss_comments_result_<?php echo $this->comments->uid; ?>'></div>

<?php $this->comments->IncludeJS() ?>

<?php echo $this->tmpl ? FSS_Helper::PageStylePopupEnd() : FSS_Helper::PageStyleEnd(); ?>