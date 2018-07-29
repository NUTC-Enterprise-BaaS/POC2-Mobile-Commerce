<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class='fss_kb_prodlist'>
	
	<div id='prod_search_res'>
		<?php if ($this->main_prod_colums > 1): ?>
			<?php $colwidth = floor(100 / $this->main_prod_colums) . "%"; ?>
		
			<table width='100%' cellspacing="0" cellpadding="0">
			<?php $column = 1; ?>
			
			<?php foreach ($this->products as &$product) : ?>
			
				<?php if ($column == 1) : ?>
	        		<tr><td width='<?php echo $colwidth; ?>' valign='top'>
				<?php else: ?>
	        		<td width='<?php echo $colwidth; ?>' valign='top'>
				<?php endif; ?>

				<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php'); ?>
				
				<?php if ($column == $this->main_prod_colums): ?>
						</td></tr>
				<?php else: ?>
		        		</td>
				<?php endif; ?>
			     
				<?php        
					$column++;
					if ($column > $this->main_prod_colums)
						$column = 1;
				?>
			<?php endforeach; ?>
		
		<?php	
			if ($column > 1)
			{ 
				while ($column <= $this->main_prod_colums)
				{
					echo "<td valign='top'><div></div></td>";	
					$column++;
				}
				echo "</tr>"; 
				$column = 1;
			}
		?>

			</table> 	
			
		<?php else: ?>
		
			<?php foreach ($this->products as &$product): ?>
				<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php'); ?>
			<?php endforeach; ?>
			
		<?php endif; ?>
		
		<?php if ($this->main_prod_pages) echo $this->pagination->getListFooter(); ?>
		
	</div>
</div>

<script>

<?php if ($this->main_prod_search): ?>
jQuery(document).ready( function () {
	SetupProdSearch();
});

function SetupProdSearch()
{
	jQuery('#prod_submit').click( function(ev){
		// Stops the submission of the form.
		ev.preventDefault();
		
		var value = jQuery('#prodsearch').val();
		var limit = jQuery('#limit').val();
		if (value == '') value = '__all__';
		var url = jQuery('#searchProd').attr('action');
		url = fss_url_append(url, 'tmpl', 'component');
		url = fss_url_append(url, 'prodsearch', value);
		url = fss_url_append(url, 'limit', limit);

		jQuery('#prod_search_res').load(url);
		return false;
	});

	jQuery('#prod_reset').click( function(ev){
		ev.preventDefault();
		jQuery('#prodsearch').val('');

		var url = jQuery('#searchProd').attr('action');
		url = fss_url_append(url, 'tmpl', 'component');
		url = fss_url_append(url, 'prodsearch', '__all__');
	
		jQuery('#prod_search_res').load(url);
		return false;
	});				
}
<?php endif; ?>

function ChangePage(newpage)
{
	var limitstart = document.getElementById('limitstart');
	if (!newpage)
		newpage = 0;
	limitstart.value = newpage;
	
	{
		var value = jQuery('#prodsearch').val();
		var limit = jQuery('#limit').val();
		var limitstart = jQuery('#limitstart').val();
		if (value == '') value = '__all__';
		
		var url = jQuery('#searchProd').attr('action');
		url = fss_url_append(url, 'tmpl', 'component');
		url = fss_url_append(url, 'prodsearch', value);
		url = fss_url_append(url, 'limit', limit);
		url = fss_url_append(url, 'limitstart', limitstart);

		jQuery('#prod_search_res').load(url);
	}
}
function ChangePageCount(newcount)
{
	var limit = document.getElementById('limit');
	if (!newcount)
		newcount = 10;
	limit.value = newcount;
		
	var limitstart = document.getElementById('limitstart');
	limitstart.value = 0;
	
	{
		var value = jQuery('#prodsearch').val();
		var limit = jQuery('#limit').val();
		var limitstart = jQuery('#limitstart').val();
		if (value == '') value = '__all__';
		
		var url = jQuery('#searchProd').attr('action');
		url = fss_url_append(url, 'tmpl', 'component');
		url = fss_url_append(url, 'prodsearch', value);
		url = fss_url_append(url, 'limit', limit);
		url = fss_url_append(url, 'limitstart', limitstart);

		jQuery('#prod_search_res').load(url);
	}

}

</script>

