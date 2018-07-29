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
<?php $announce = $this->announce; ?>
<?php echo FSS_Helper::PageTitle("ANNOUNCEMENTS",$announce['title']); ?>

<?php 

$this->parser->SetVar('editpanel', $this->content->EditPanel($announce));
$this->parser->SetVar('date', FSS_Helper::Date($announce['added'],FSS_DATE_MID));
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

if (FSS_Settings::get( 'glossary_announce' )) {
	$this->parser->setVar('fulltext', FSS_Glossary::ReplaceGlossary($announce['fulltext'])); 
} else {
	$this->parser->setVar('fulltext', $announce['fulltext']); 
}

echo $this->parser->Parse();

if (FSS_Settings::get('announce_comments_allow') == 1)
{
	$this->comments->DisplayComments();
} else if (FSS_Settings::get('announce_comments_allow') == 2)
{
	$comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
	if (file_exists($comments)) {
		require_once($comments);
		echo JComments::showComments($announce['id'], 'com_fss_announce', $announce['title']);
	}
}

?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>

<?php if (FSS_Settings::get( 'glossary_announce' )) echo FSS_Glossary::Footer(); ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>
