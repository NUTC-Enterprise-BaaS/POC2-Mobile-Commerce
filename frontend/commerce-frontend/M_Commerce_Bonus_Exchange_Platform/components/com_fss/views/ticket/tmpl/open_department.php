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

<div class="fss_spacer"></div>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php'); ?>

<?php echo FSS_Helper::PageSubTitle("PLEASE_SELECT_A_DEPARTMENT_FOR_YOUR_SUPPORT_ENQUIRY"); ?>

<?php FSS_Helper::HelpText("support_open_dept_header"); ?>

<?php unset($this->product); ?>

<?php if (isset($this->product) && $this->product && FSS_Settings::get('support_sel_prod_dept')): ?>
	
	<h4 class='product-small'>
		<?php echo JText::_("PRODUCT"); ?>:
		<?php if ($this->product->image) : ?>
			<img class="media-object" src="<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $this->product->image; ?>">
		<?php endif; ?>
		<?php echo $this->product->title ?>
	</h4>
<?php endif; ?>

<?php FSS_Helper::HelpText("support_open_dept_after_product"); ?>

<?php if (FSS_Settings::get('support_advanced_department')): ?>

	<form id="searchDept" action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open&task=open.department' . ($this->prodid > 0 ? "&prodid=" . $this->prodid : '') );?>" method="post" name="fssForm"
		<?php if (!FSS_Settings::get('support_advanced_search')) echo 'style="display: none;"' ?> >

		<div class="input-append">
			<input type="text" id='deptsearch' name='deptsearch' class="input-medium" placeholder="<?php echo JText::_("SEARCH_FOR_A_DEPARTMENT"); ?>" value="<?php echo FSS_Helper::escape($this->search); ?>">
			<input id='dept_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='dept_reset' class='btn btn-default' type='submit' value='<?php echo JText::_("RESET"); ?>' />
		</div>

		<input type=hidden name='searchtype' value='departments' />
		<input type="hidden" name="limitstart" id='limitstart' value="0">
		<input type="hidden" name="limit" id='limit' value="<?php echo (int)$this->limit; ?>">
	</form>		
<?php endif; ?>

<form name='deptselect' id='deptselect' action='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open'); ?>' method='post'>
	<?php echo FSS_Helper::openPassthrough(); ?>
	<?php if (!FSS_Helper::hasPassthrough('deptid')): ?><input type='hidden' name='deptid' id='deptid' /><?php endif; ?>

	<div id='dept_search_res' class="dept_search_res" style="clear:both;">
		<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'tmpl'.DS.'open_searchdept.php'; ?>
	</div>

	<p>
		<?php if ($this->prodid > 0): ?>
			<?php if (FSS_Input::getInt('admincreate') > 0): ?>
				<a class='btn btn-default backprod' href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open&admincreate=' . FSS_Input::getInt('admincreate')); ?>"><?php echo JText::_("BACK"); ?></a>
			<?php else: ?>
				<a class='btn btn-default backprod' href="<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open'); ?>"><?php echo JText::_("BACK"); ?></a>
			<?php endif; ?>
		<?php endif; ?>
	</p>
</form>

<?php FSS_Helper::HelpText("support_open_dept_footer"); ?>

<script>

var productpicked = false;

function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		//alert(radioObj.checked);
		productpicked = true;
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
			productpicked = true;
		}
	}
	document.forms.deptselect.submit();
}

function setupFormRedirect() {
	
}

function ChangePage(newpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!newpage)
		newpage = 0;
	limitstart.value = newpage;
	
	productpicked = false;
	var value = jQuery('#deptsearch').val();
	var prodid = jQuery('#prodid').val();
	var limit = jQuery('#limit').val();
	var limitstart = jQuery('#limitstart').val();
	if (value == '') value = '__all__';
	var url = '<?php echo str_replace("&amp;","&",FSSRoute::_( '&tmpl=component' ));// FIX LINK ?>&deptsearch=' + value + '&limit=' + limit + '&limitstart=' + limitstart + '&time=' + new Date().getTime() + '&prodid=' + prodid;

	jQuery('#dept_search_res').load(url);
}

function ChangePageCount(newcount)
{
	productpicked = false;
	if (!newcount)
		newcount = 10;
	jQuery('#limit').val(newcount);
		
	jQuery('#limitstart').val("0");
	
	var value = jQuery('#deptsearch').val();
	var prodid = jQuery('#prodid').val();
	var limit = jQuery('#limit').val();
	var limitstart = jQuery('#limitstart').val();
	
	if (value == '') value = '__all__';
	var url = '<?php echo str_replace("&amp;","&",FSSRoute::_( '&tmpl=component' ));// FIX LINK ?>&deptsearch=' + value + '&limit=' + limit + '&limitstart=' + limitstart + '&prodid=' + prodid;
		
	jQuery('#dept_search_res').load(url);
}

jQuery(document).ready(function(){
	jQuery('#dept_submit').click( function(ev)
	{
		ev.preventDefault();
		
		productpicked = false;

		var value = jQuery('#deptsearch').val();
		var prodid = jQuery('#prodid').val();
		var limit = jQuery('#limit').val();
		if (value == '') value = '__all__';
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::_( '&tmpl=component' ));// FIX LINK ?>&deptsearch=' + value + '&limit=' + limit + '&prodid=' + prodid ;

		jQuery('#dept_search_res').load(url);
		
		return false;
	});
	
	
	jQuery('#dept_reset').click( function(ev)
	{
		ev.preventDefault();
		jQuery('#prodsearch').val('');
		var prodid = jQuery('#prodid').val();
		
		productpicked = false;
		var url = '<?php echo str_replace("&amp;","&",FSSRoute::_( '&tmpl=component' ));// FIX LINK ?>&deptsearch=__all__&prodid=' + prodid;
		
		jQuery('#dept_search_res').load(url);

		return false;
	});


	jQuery('#pickdept').click (function(evt){
		// Stops the submission of the form.
		
		if (!productpicked)
		{
			alert("<?php echo FSS_Helper::escapeJavaScriptTextForAlert(JText::_("YOU_MUST_SELECT_A_DEPARTMENT")); ?>");
			//new Event(evt).stop();
			return false;	
		}
	} );
	
	jQuery('.backprod').click(function(evt){
		// Stops the submission of the form.
		jQuery('#prodid').val('');		
	} );
});

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>