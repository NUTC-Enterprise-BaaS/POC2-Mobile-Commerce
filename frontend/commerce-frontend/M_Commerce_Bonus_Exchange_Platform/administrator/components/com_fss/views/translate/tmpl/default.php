<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="fss_main">

<div class="pull-right">
	<a href="#" class='btn btn-success' onclick='saveTranslated();return false;'>Save</a>
	<a href="#" class='btn btn-danger' onclick='window.parent.TINY.box.hide();return false;'>Cancel</a>
</div>
	
<h1>Translate Item</h1>


<div class="alert" style="padding-right: 6px;">
	<strong>Leave an item blank to use the default text. After saving, you will also need to save the item you are translating.</strong>
</div>

<?php

foreach ($this->data as $field => $fielddata): ?>
	
	<h2>Field: <?php echo $fielddata['title']; ?></h2>
	
	<?php if ($fielddata['type'] == "textarea"): ?>
		<h3>Current:</h3><pre id="current-<?php echo $field; ?>"></pre>
	<?php elseif ($fielddata['type'] == "html"): ?>	
		<h3>Current: <span id="current-<?php echo $field; ?>"></span></h3>
	<?php else: ?>
		<h3>Current: <span id="current-<?php echo $field; ?>"></span></h3>
	<?php endif; ?>
	<table width="100%" class="table table-striped">
	<?php foreach ($this->langs as $key => $language): ?>
		<?php $language['id'] = str_replace("-", "", $language['tag']); ?>
		<tr>
			<td valign="top"><?php echo $language['name']; ?></td>
			<td>
				<?php if ($fielddata['type'] == "textarea"): ?>
					<textarea id="tran-<?php echo $field; ?>-<?php echo $language['id']; ?>" rows="8" cols="60" name="tran-<?php echo $field; ?>-<?php echo $language['id']; ?>"></textarea>
				<?php elseif ($fielddata['type'] == "html"): ?>
					<?php
					$editor = JFactory::getEditor();
					echo $editor->display("tran-{$field}-{$language['id']}", "", '550', '200', '60', '20', array('pagebreak', 'readmore'));
					?>
				<?php else: ?>
					<input id="tran-<?php echo $field; ?>-<?php echo $language['id']; ?>" size="60" name="tran-<?php echo $field; ?>-<?php echo $language['id']; ?>" />
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>

<?php endforeach; ?>

<div class="pull-right">
	<a href="#" class='btn btn-success' onclick='saveTranslated();return false;'>Save</a>
	<a href="#" class='btn btn-danger' onclick='window.parent.TINY.box.hide();return false;'>Cancel</a>
</div>

<script>

function saveTranslated()
{
	<?php
	$editor = JFactory::getEditor();
	foreach ($this->data as $field => $fielddata)
	{
		if ($fielddata['type'] != "html") continue;
		
		foreach ($this->langs as $key => $language)
		{
			$language['id'] = str_replace("-", "", $language['tag']);
			
			$field_name = "tran-{$field}-{$language['id']}";
			echo "\n// $field_name \n";
			echo $editor->save( $field_name );
		}
	}
	?>
				
	window.parent.saveTranslated();	
}

jQuery(document).ready( function () {
	window.parent.doTranlsateLoaded();
});
</script>

</div>