<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo FSS_Helper::PageStyle(); ?>

<div class="pull-right">
	<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=announce&feed=rss", false); ?>">
		<img src='<?php echo JURI::base();?>/components/com_fss/assets/images/rss.png' width='24' height='24'>
	</a>
</div>

<?php echo FSS_Helper::PageTitle("ANNOUNCEMENTS"); ?>


<?php if (count($this->announces) > 0) : ?>
	<form method="post" action="<?php echo FSSRoute::_(''); ?>" name="form">

		<?php 
	
		foreach($this->announces as $announce)
		{
			$this->parser->SetVar('editpanel', $this->content->EditPanel($announce));
			$this->parser->SetVar('date', FSS_Helper::Date($announce['added'],FSS_DATE_MID));
			$this->parser->SetVar('link', FSSRoute::_( 'index.php?option=com_fss&view=announce&announceid=' . $announce['id'] ));
			$this->parser->setVar('title', FSS_Helper::PageSubTitle($announce['title']));
			$this->parser->setVar('subtitle', $announce['subtitle']);

			$authid = $announce['author'];
			$user = JFactory::getUser($authid);
			if ($user->id > 0)
			{
				$this->parser->setVar('author', $user->name);	
				$this->parser->setVar('author_username', $user->username);	
			} else {
				$this->parser->setVar('author', JText::_('UNKNOWN'));	
				$this->parser->setVar('author_username', JText::_('UNKNOWN'));	
			}
	
			if (FSS_Settings::get( 'glossary_announce' )) {
				$this->parser->setVar('body', FSS_Glossary::ReplaceGlossary($announce['body'])); 
			} else {
				$this->parser->setVar('body', $announce['body']); 
			}
	
			$this->parser->SetVar('needsreadmore', $announce['fulltext'] || FSS_Settings::get('announce_comments_allow') ? '1' : '');

			if (FSS_Settings::get( 'glossary_announce' )) {
				$this->parser->setVar('fulltext', FSS_Glossary::ReplaceGlossary($announce['fulltext'])); 
			} else {
				$this->parser->setVar('fulltext', $announce['fulltext']); 
			}

			if ($announce['fulltext']) {
				$this->parser->SetVar('readmore', JText::_("READ_MORE"));
			} else {
				$this->parser->SetVar('readmore', JText::_("COMMENTS"));
			}

			if (preg_match("/\{body,(\d{1,5})/i", $this->parser->template, $matches))
			{
				if (isset($matches[1]) && $matches[1] > 0)
				{
					if (strlen($this->parser->GetVar('body')) > $matches[1])
					{
						$this->parser->SetVar('readmore', JText::_("READ_MORE"));
						$this->parser->SetVar('needsreadmore', 1);
					}
				}
			}

			if (FSS_Settings::get('announce_comments_allow') && $this->comments->GetCount($announce['id']) > 0)
			{
				$this->parser->SetVar('commentcount', $this->comments->GetCount($announce['id']) . "<img class='fss_comment_count_img fssTip' src='" . JURI::root( true ) . "/components/com_fss/assets/images/comments.png' title='". $this->comments->GetCount($announce['id'])." Comments'>");
			} else {
				$this->parser->SetVar('commentcount', "");
			}

			echo $this->parser->getTemplate();
		} 

		?>

		<div class='fss_pagewrapper'>
			<?php echo $this->pagination->getListFooter(); ?>
		</div>

	</form>
<?php else: ?>
	
	<?php echo FSS_Helper::PageSubTitle("THERE_ARE_CURRENTLY_NO_ANNOUNCEMENTS"); ?>
	
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php if (FSS_Settings::get( 'glossary_announce' )) echo FSS_Glossary::Footer(); ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>
	    	  	 	 		 