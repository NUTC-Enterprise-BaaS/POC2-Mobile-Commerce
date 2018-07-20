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
	<input type="hidden" name="view" value="templates" />
	<input type="hidden" name="tab" id='tab' value="<?php echo $this->tab; ?>" />


	<ul class="nav nav-tabs">

		<li class="active">
			<a href='#tcomments'><?php echo JText::_("COMMENTS"); ?></a> 
		</li>
		<li>
			<a href='#tsupport'><?php echo JText::_("SUPPORT"); ?></a>
		</li>
		<li>
			<a href='#tannounce'><?php echo JText::_("ANNOUNCEMENTS"); ?></a>
		</li>
	</ul>

	<div class="tab-content">

		<div class="tab-pane active" id="tcomments">
			<?php include JPATH_COMPONENT.DS.'views'.DS.'templates'.DS.'tmpl'.DS.'default_comments.php'; ?>
		</div>

		<div class='tab-pane' id="tsupport">
			<?php include JPATH_COMPONENT.DS.'views'.DS.'templates'.DS.'tmpl'.DS.'default_support.php'; ?>
		</div>

		<div class='tab-pane' id="tannounce">
			<?php include JPATH_COMPONENT.DS.'views'.DS.'templates'.DS.'tmpl'.DS.'default_announce.php'; ?>
		</div>

	</div>

</form>

<div class="alert alert-notice">
<p>For all of the template, you can also limit the number of characters output within a tag. To do so, use the following format</p>
<pre>{body,150}</pre>
<p>This will limit the {body} tag to 150 characters. You can also specify the truncation text, which is appended to the text if it is appended, ie:</p>
<pre>{body,150,&hellp;}</pre>
</div>
<form action="<?php echo JURI::root(); ?>/index.php?view=admin_support&option=com_fss&preview=1" method="post" name="adminForm2" id="adminForm2" target="_blank">
	<input type="hidden" name="list_template" id="list_template" value="" />
	<textarea style='display:none;' name="list_head" id="list_head"></textarea>
	<textarea style='display:none;' name="list_row" id="list_row"></textarea>
</form>

</div>
