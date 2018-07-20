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
<input type="hidden" name="view" value="settingsview" />
<input type="hidden" name="tab" id='tab' value="<?php echo $this->tab; ?>" />

<ul class="nav nav-tabs">

	<li class="active">
		<a href='#kb'><?php echo JText::_("KB_ARTICLES_LIST"); ?></a> 
	</li>
	<li>
		<a href='#faqs'><?php echo JText::_("FAQS_LIST"); ?></a>
	</li>
	<li>
		<a href='#glossary'><?php echo JText::_("Glossary"); ?></a>
	</li>
	<li>
		<a href='#test'><?php echo JText::_("TESTIMONIALS_LIST"); ?></a>
	</li>
</ul>

<div class="tab-content">

	<div class="tab-pane active" id="kb">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settingsview'.DS.'tmpl'.DS.'default_kb.php'; ?>
	</div>

	<div class='tab-pane' id="faqs">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settingsview'.DS.'tmpl'.DS.'default_faqs.php'; ?>
	</div>

	<div class='tab-pane' id="glossary">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settingsview'.DS.'tmpl'.DS.'default_glossary.php'; ?>
	</div>

	<div class='tab-pane' id="test">
		<?php include JPATH_COMPONENT.DS.'views'.DS.'settingsview'.DS.'tmpl'.DS.'default_test.php'; ?>
	</div>

</div>

</form>

</div>