<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<?php $text = $faq['answer']; 
	if ($faq['fullanswer'])
		$text .= "<div class='fss_faq_more'><a href='#'>click for more...</a></div>";
				
	$output = str_replace("'", "", $text);
	if (array_key_exists($faq['id'], $this->tags))
	{
		$output .= '<div class="fss_faq_tags">';
		$output .= '<span>' . JText::_('TAGS') . ':</span> ';
		$output .= str_replace("'","\"",implode(", ", $this->tags[$faq['id']]));
		$output .= '</div>';
	}			
?>
		
<div class="media faq_faq faq_<?php echo $cat['id'] . "_" . $faq['id']; ?>_cont faq_<?php echo $faq['id']; ?>_cont"">
	<div class="pull-right">
		<?php echo $this->content->EditPanel($faq); ?>
	</div>
	<div class="media-body">
		<h5 class="media-heading">
			<a href='<?php echo FSSRoute::_( '&faqid=' . $faq['id'] );// FIX LINK ?>' 
				class='fssTip' 
				data-placement="bottom"
				data-width="600px" 
				title="<?php echo htmlentities($output,ENT_QUOTES,"utf-8"); ?>">
				<?php echo $faq['question']; ?>
			</a>
		</h5>
			
	</div>
</div>	