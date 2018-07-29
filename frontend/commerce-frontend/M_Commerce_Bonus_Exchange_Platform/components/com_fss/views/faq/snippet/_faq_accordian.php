<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="media faq_faq faq_<?php echo $cat['id'] . "_" . $faq['id']; ?>_cont faq_<?php echo $faq['id']; ?>_cont">
	<div class="pull-right">
		<?php echo $this->content->EditPanel($faq); ?>
	</div>
	<div class="media-body">
		<h5 class="media-heading" data-toggle="collapse" data-target="#faq_<?php echo $cat['id'] . "_" . $faq['id']; ?>" style='cursor: pointer;'>
			<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faq&faqid=' . $faq['id']); ?>" onclick='return false;'><?php echo $faq['question']; ?></a>
		</h5>
				
		<div class="collapse" id="faq_<?php echo $cat['id'] . "_" . $faq['id']; ?>">
			<?php 
				if (FSS_Settings::get( 'glossary_faqs' )) {
					echo FSS_Glossary::ReplaceGlossary($faq['answer']); 
					if ($faq['fullanswer'])
					{
						echo FSS_Glossary::ReplaceGlossary($faq['fullanswer']); 
					}
				} else {
					echo $faq['answer']; 
					if ($faq['fullanswer'])
					{
						echo $faq['fullanswer']; 
					}
				}		
			?>
					
			<?php if (array_key_exists($faq['id'], $this->tags)): ?>
				<div class='fss_faq_tags'>
	
					<span><?php echo JText::_('TAGS'); ?>:</span>
					<?php echo implode(", ", $this->tags[$faq['id']]); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
		