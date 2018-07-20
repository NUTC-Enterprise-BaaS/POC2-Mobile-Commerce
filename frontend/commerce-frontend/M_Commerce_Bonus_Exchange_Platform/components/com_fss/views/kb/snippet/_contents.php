<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<div id="contents_target" class="article-index">
</div>

<script>

var contents_count = 0;

jQuery(document).ready(function () {
	var target = jQuery('#contents_target');
	if (target.length == 0)
		return;
			
	var body = jQuery('#kb_art_body');
	
	var cont = jQuery('<ul>');
	cont.addClass('nav');
	cont.addClass('nav-tabs');
	cont.addClass('nav-stacked');
	cont.attr('id','fss_kb_contents');
	
	target.html("");
	target.append(cont);
	
	AddChildren(body);
	
	if (contents_count < 2)
		jQuery('#contents_container').hide();
});

function AddChildren(node)
{
	jQuery(node).children().each(function () {
		if (this.tagName == "H2" || this.tagName == "H3" || this.tagName == "H4" || this.tagName == "H5")
		{
			var ce = jQuery('<li>');
			var title = jQuery(this).text();
			var cls = "fss_kb_content_" + this.tagName;
			ce.addClass(cls);
			var ident = MakeIdent(title);
			ce.html('<a href="' + window.location.href + '#' + ident + '">' + title + '</a>');
			jQuery('#fss_kb_contents').append(ce);
			
			jQuery(this).prepend("<a name='" + ident + "' />");
			
			contents_count++;
		}
		AddChildren(this);
	});
}

function MakeIdent(text)
{
	text = text.replace(/[^a-zA-Z 0-9]+/g,'');
	text = text.replace(/ /g,'_');
	text = text.toLowerCase();
	return text;
}

</script>
