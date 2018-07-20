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
<?php echo FSS_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php'); ?>

<?php echo FSS_Helper::PageSubTitle("PLEASE_SELECT_A_PRODUCT_FOR_YOUR_SUPPORT_ENQUIRY"); ?>

<form id="searchProd" action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open&task=open.product' );?>" method="post" name="fssForm"
	<?php if (!FSS_Settings::get('support_advanced_search')) echo 'style="display: none;"' ?> >

	<div class="input-append">
		<input type="text" id='prodsearch' name='prodsearch' class="input-medium" placeholder="<?php echo JText::_("SEARCH_FOR_A_PRODUCT"); ?>" value="<?php echo FSS_Helper::escape($this->search); ?>">
		<input id='prod_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
		<input id='prod_reset' class='btn btn-default' type='submit' value='<?php echo JText::_("RESET"); ?>' />
	</div>

	<input type="hidden" name="limitstart" id='limitstart' value="0">
	<input type="hidden" name="limit" id='limit' value="<?php echo (int)$this->limit; ?>">
</form>		

<?php FSS_Helper::HelpText("support_open_prod_header"); ?>
<form name='prodselect' id='prodselect' action='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open');?>' method='post'>
	<?php echo FSS_Helper::openPassthrough(); ?>
	<?php if (!FSS_Helper::hasPassthrough('prodid')): ?><input type='hidden' name='prodid' id='prodid' /><?php endif; ?>

	<div id='prod_search_res' class="prod_search_res" style="clear:both;">
		<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'tmpl'.DS.'open_search.php'; ?>
	</div>

</form>

<?php FSS_Helper::HelpText("support_open_prod_footer"); ?>

<script>

var productpicked = false;

function setCheckedValue(radioObj, newValue) {
	
	if(!radioObj)
		return;
	
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		productpicked = true;
		document.forms.prodselect.submit();
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
			productpicked = true;
		}
	}
	document.forms.prodselect.submit();
}

jQuery(document).ready( function () {
	jQuery('#prod_submit').click( function(ev)
	{
		ev.preventDefault();
		
		productpicked = false;

		var value = jQuery('#prodsearch').val();
		var limit = jQuery('#limit').val();
		if (value == '') value = '__all__';
		var url = jQuery('#searchProd').attr('action') + '&tmpl=component&search=' + value;
		
		jQuery('#prod_search_res').load(url);
		
		return false;
	});
	
	
	jQuery('#prod_reset').click( function(ev)
	{
		ev.preventDefault();
		jQuery('#prodsearch').val('');
		
		productpicked = false;
		var url = jQuery('#searchProd').attr('action') + '&tmpl=component&search=__all__';
		
		jQuery('#prod_search_res').load(url);

		return false;
	});
	
	/* Click one of the "Next" buttons */
	jQuery('.pickproduct').click (function(evt){
		// Stops the submission of the form.
		if (!productpicked)
		{
			alert("<?php echo FSS_Helper::escapeJavaScriptTextForAlert(JText::_("YOU_MUST_SELECT_A_PRODUCT")); ?>");
			//new Event(evt).stop();
			return false;	
		}	
	});
});

function ChangePage(newpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!newpage)
		newpage = 0;
	limitstart.value = newpage;
	
	productpicked = false;
	var value = jQuery('#prodsearch').val();
	var limit = jQuery('#limit').val();
	var limitstart = jQuery('#limitstart').val();
	if (value == '') value = '__all__';
	var url = jQuery('#searchProd').attr('action') + '&tmpl=component&prodsearch=' + value + '&limit=' + limit + '&limitstart=' + limitstart + '&time=' + new Date().getTime();
		
	jQuery('#prod_search_res').load(url);
}

function ChangePageCount(newcount)
{
	productpicked = false;
	if (!newcount)
		newcount = 10;
	jQuery('#limit').val(newcount);
		
	jQuery('#limitstart').val("0");
	
	var value = jQuery('#prodsearch').val();
	var limit = jQuery('#limit').val();
	var limitstart = jQuery('#limitstart').val();
	
	if (value == '') value = '__all__';
	var url = jQuery('#searchProd').attr('action') + '&tmpl=component&prodsearch=' + value + '&limit=' + limit + '&limitstart=' + limitstart ;
		
	jQuery('#prod_search_res').load(url);
}

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
