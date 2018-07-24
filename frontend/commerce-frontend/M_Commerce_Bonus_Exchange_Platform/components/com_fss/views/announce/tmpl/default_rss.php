<?php echo "<"; ?>?xml version="1.0" encoding="UTF-8"?>
<?php

/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

$uri = JURI::getInstance();
$baseUrl = $uri->toString( array('scheme', 'host', 'port'));
?>
<rss xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">  
    <channel>
        <title><?php echo JText::_("ANNOUNCEMENTS"); ?></title>
        <link><?php echo JURI::base(); ?></link>
		<description><?php echo JText::_("ANNOUNCEMENTS"); ?></description>
<?php foreach($this->announces as $announce): ?>
		<item>
		<title><?php echo $announce['title']; ?></title>
		<description><?php echo htmlspecialchars($announce['body']); ?></description>
		<link><?php echo $baseUrl; ?><?php echo htmlentities(FSSRoute::_( 'index.php?option=com_fss&view=announce&announceid=' . $announce['id'] )); ?></link>
		<guid><?php echo $baseUrl; ?><?php echo htmlentities(FSSRoute::_( 'index.php?option=com_fss&view=announce&announceid=' . $announce['id'] )); ?></guid>
		<pubDate><?php echo date('r', strtotime($announce['added'])); ?></pubDate>
</item>
<?php endforeach; ?>
    </channel>
</rss>
