<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (!FSSJ3Helper::IsJ3() || FSS_Settings::get('support_attach_use_old_system')): ?>

<?php for ($i = 1; $i < 10 ; $i++): ?>

	<div id="file_<?php echo $i; ?>" <?php if ($i > 1): ?> style='display:none;' <?php endif; ?>>
		<input 
			type="file" 
			size="60" 
			id="filedata_<?php echo $i; ?>" 
			name="filedata_<?php echo $i; ?>" 
			onchange="jQuery('#file_<?php echo $i+1; ?>').show();jQuery('#file_<?php echo $i; ?>_btn').show();" 
			/>
		<a 
			id="file_<?php echo $i; ?>_btn" 
			class="btn btn-mini btn-warning"
			style='display: none' 
			onclick="fss_ClearFileInput(jQuery('#filedata_<?php echo $i; ?>')[0])"
			>&times;</a>
	</div>

<?php endfor; ?>

<?php else: ?>

<?php FSS_Helper::IncludeFileUpload(); ?>

<div style="position: relative;">

	<div class="fileupload-buttonbar">
		<div class="col-lg-7">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<span class="btn fileinput-button pull-left">
				<span><?php echo JText::_('UPLOAD_FILES'); ?></span>
				<input type="file" name="files[]" multiple>
			</span>
		
			<div class="pull-left">&nbsp;</div>
			<div id="dropzone" class="pull-left btn"><?php echo JText::_('DROP_FILES_HERE'); ?></div>

			<!-- The global file processing state -->
			<span class="progress-extended"></span>
			<div class="col-lg-5 fileupload-progress in" style="display: inline-block;">
				<div class="progress-extended"></div>
			</div>
		</div>
		<!-- The global progress state -->
	</div>
</div>

<input id="files_delete" type="hidden" name="files_delete" value="" />
		
<table role="presentation" class="table table-striped table-condensed table-valign-middle" style="margin-bottom: 0;" id="attach_files">
	<tbody class="files">		
	</tbody>
</table>
		
<div id="template-upload" type="text/x-tmpl" style="display: none;">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    &lt;tr class="template-upload fade"&gt;
        &lt;td&gt;
            &lt;div class='name'&gt;{%=file.name%}, &lt;span class='size'&gt;Processing...&lt;/span&gt;&lt;/div&gt;
            &lt;strong class="error text-danger"&gt;&lt;/strong&gt;
        &lt;/td&gt;
		&lt;td width='100'&gt;
            &lt;div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"&gt;
				&lt;div class="bar bar-success" style="width:0%;"&gt;&lt;/div&gt;
			&lt;/div&gt;
		&lt;/td&gt;
		&lt;td width='20' style='text-align: right'&gt;
            &lt;button class="btn btn-mini btn-danger cancel"&gt;
                &times;
            &lt;/button&gt;
        &lt;/td&gt;
    &lt;/tr&gt;
{% } %}
</div>

<!-- The template to display files available for download -->
<div id="template-download" type="text/x-tmpl" style="display: none;">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    &lt;tr class="template-download fade" style='cursor: move'&gt;
        &lt;td&gt;
			&lt;div&gt;
				&lt;input type='hidden' name='new_filename[]' value='{%=file.name%}'&gt;
				&lt;input type='hidden' name='new_fileorder[]' class='order' value=''&gt;
			&lt;/div&gt;
			&lt;div style='padding: 3px 6px'&gt;
				&lt;span class="name"&gt;&lt;a href='{%=file.url%}' title='{%=file.url%}'&gt;{%=file.name%}&lt;/a&gt;&lt;/span&gt;, 
				&lt;span class='size'&gt;{%=o.formatFileSize(file.size)%}&lt;/span&gt;
			&lt;/div&gt;
			
            {% if (file.error) { %}
                &lt;div&gt;&lt;span class="label label-danger"&gt;Error&lt;/span&gt; {%=file.error%}&lt;/div&gt;
            {% } %}
        &lt;/td&gt;
        &lt;td colspan='2' style='text-align: right'&gt;
            &lt;button class="btn btn-mini btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}&gt;
                &times;
            &lt;/button&gt;
        &lt;/td&gt;
    &lt;/tr&gt;
{% } %}
</div>

<?php endif; ?>