<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="fss_main fss_settings">

<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="what" value="save">
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="view" value="settings" />
<input type="hidden" name="tab" id='tab' value="<?php echo $this->tab; ?>" />
<input type="hidden" name="version" value="<?php echo $this->settings['version']; ?>" />
<input type="hidden" name="fsj_username" value="<?php echo $this->settings['fsj_username']; ?>" />
<input type="hidden" name="fsj_apikey" value="<?php echo $this->settings['fsj_apikey']; ?>" />

<ul class="nav nav-tabs">

	<li class="active">
		<a href='#general'><?php echo JText::_("GENERAL_SETTINGS"); ?></a> 
	</li>
	<li>
		<a href='#visual'><?php echo JText::_("VISUAL"); ?></a>
	</li>
	<li>
		<a href='#support'><?php echo JText::_("SUPPORT"); ?></a>
	</li>
	<li>
		<a href='#announce'><?php echo JText::_("ANNOUNCEMENTS"); ?></a> 
	</li>
	<li>
		<a href='#faq'><?php echo JText::_("FAQS"); ?></a> 
	</li>
	<li>
		<a href='#glossary'><?php echo JText::_("GLOSSARY"); ?></a>
	</li>
	<li>
		<a href='#kb'><?php echo JText::_("KNOWLEDGE_BASE"); ?></a> 
	</li>
	<li>
		<a href='#test'><?php echo JText::_("TESTIMONIALS"); ?></a>
	</li>
</ul>

<div class="tab-content">

	<div class="tab-pane active" id="general">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_general.php'; ?>
	</div>

	<div class='tab-pane' id="announce">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_announce.php'; ?>
	</div>

	<div class='tab-pane' id="kb">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_kb.php'; ?>
	</div>

	<div class='tab-pane' id="test">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_test.php'; ?>
	</div>

	<div class='tab-pane' id="support">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_support.php'; ?>
	</div>

	<div class='tab-pane' id="glossary">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_glossary.php'; ?>
	</div>

	<div class='tab-pane' id="faq">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_faq.php'; ?>
	</div>

	<div class='tab-pane' id="visual">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settings'.DS.'tmpl'.DS.'default_visual.php'; ?>
	</div>

</div>

</form>

</div>