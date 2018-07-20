<?php
/**
 * @version    SVN: <svn_id>
 * @package    Quick2cart
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access.
defined('_JEXEC') or die();
$jinput = JFactory::getApplication()->input;

if (empty($fieldsArray))
{
	return '';
}

$document = JFactory::getDocument();
$path = JUri::base() . 'modules/mod_tjfields_search/assets/css/tjfilters.css';
$document->addStyleSheet($path);
?>

<div class="tjfield-wrapper <?php  echo $params->get('moduleclass_sfx');?>">

<?php

$baseurl = $jinput->server->get('REQUEST_URI', '', 'STRING');


// Get uRL base part and parameter part
$temp =  explode ('?', $baseurl);
$siteBase =  $temp[0];
$urlArray = array();

if (!empty($temp[1]))
{
	$urlArray = explode ('&',$temp[1]);
}

$clientCatId = $jinput->get($url_cat_param_name, '');

if (!empty($urlArray))
{
	foreach ($urlArray as $key => $url)
	{
		// Unset Not required parameter from array
		if (!empty(strstr($url, 'ModFilterCat=')) || ($url_cat_param_name &&  !empty(strstr($url, $url_cat_param_name))) || !empty(strstr($url, 'tj_fields_value=')) || !empty(strstr($url, 'client=')))
		{
			unset($urlArray[$key]);
		}
	}
}

//$baseurl = implode('&', $urlArray);
$baseurl = $siteBase  . "?" . implode('&', $urlArray);


// Make base URL ends
$selectedFilters = explode(',', $jinput->get('tj_fields_value', '', 'string'));
?>
<?php
	$buttons = $params->get('apply_clear_buttons', '');
	if($buttons == "above" || $buttons == "both")
	{ ?>
	<div class="center">
<!--
		<a class="btn btn-small btn-info" onclick='tj_clearfilters()'><?php echo JText::_('MOD_TJFIELDS_SEARCH_APPLY_BTN');?></a>
-->
		<a class="btn btn-small btn-info" onclick='tj_clearfilters()'><?php echo JText::_('MOD_TJFIELDS_SEARCH_CLEAR_BTN');?></a>
	</div>
	<?php
	}
?>

<!--
<form method="post" name="tjfieldsSearchForm" id="tjfieldsSearchForm">
-->

<?php
$showCategoryFilter = $params->get('showCategoryFilter', 0);

$categoryFilterStyle = 'display:none;';

if ($showCategoryFilter && !empty($fieldsCategorys))
{
	$categoryFilterStyle = '';
}
	?>
	<div class="tj_categoryFilter" style="<?php echo $categoryFilterStyle; ?>">
		<div><b><?php echo JText::_('MOD_TJFIELDS_SEARCH_SELECT_CATEGORY');?></b></div>
		<?php
		echo JHtml::_('select.genericlist', $fieldsCategorys, "category_id", 'class="form-control"  size="1" onchange="submitCategory()" title="' . JText::_('MOD_TJFIELDS_SEARCH_SELECT_CATEGORY') . '"', 'value', 'text', $selectedCategory, 'category_id');
		?>
	</div>

	<?php

	echo $compSpecificFilterHtml;

foreach ($fieldsArray as $key => $fieldOptions)
{
	$i = 0;
	$fieldName = '';

	if (!empty($fieldOptions))
	{
	?>
		<div class="tj-filterwrapper filterwrapper<?php echo $fieldOptions[0]->id; ?>" >
			<div class="qtcfiltername filtername<?php echo $fieldOptions[0]->id; ?>">
				<b><?php echo ucfirst($fieldOptions[0]->label);?></b>
			</div>
			<div class="tj-filterlistwrapper">
				<?php

				foreach ($fieldOptions as $option)
				{

				?>
					<div class="tj-filteritem tjfieldfilters-<?php echo $option->name;?>" >
						<input type="checkbox" class="tjfieldCheck" name="tj_fields_value[]" id="<?php echo $option->name .'||'.  $option->option_id;?>" value="<?php echo $option->option_id;?>" <?php echo in_array($option->option_id, $selectedFilters)?'checked="checked"':'';?>  onclick='tjfieldsapplyfilters()' />
						<label> <?php echo ucfirst($option->options);?></label>
					</div>

					<?php
				}
				?>
			</div>
		</div>
		<?php
	}
}


?>
<p></p>
	<?php
	if($buttons == "below" || $buttons == "both")
	{
		?>
		<div class="center">
<!--
			<a class="btn btn-small btn-info" onclick='tj_clearfilters()'><?php //echo JText::_('MOD_TJFIELDS_SEARCH_APPLY_BTN');?></a>
-->
			<a class="btn btn-small btn-info" onclick='tj_clearfilters()'><?php echo JText::_('MOD_TJFIELDS_SEARCH_CLEAR_BTN');?></a>
		</div>

		<?php
	}
	?>

<!--
</form>
-->
</div> <!--End of wrapper-->
<script>

	techjoomla.jquery = jQuery.noConflict();

	function tjfieldsapplyfilters()
	{
		var redirectlink = '<?php echo $baseurl;?>';
		var client = "com_quick2cart.products";
		var optionStr = "";

		if (typeof(client) != 'undefined')
		{
			if (redirectlink.indexOf('?') === -1)
			{
				optionStr += '?client='+client;
			}
			else
			{
				optionStr += '&client='+client;
			}

			redirectlink += optionStr;
		}

		optionStr = "";

		var urlValueName = "<?php echo $url_cat_param_name;?>";

		if (urlValueName != 'undefined')
		{
			if (redirectlink.indexOf('?') === -1)
			{
				optionStr += "?ModFilterCat="+urlValueName;
			}
			else
			{
				optionStr += "&ModFilterCat="+urlValueName;
			}

			redirectlink += optionStr;
		}

		optionStr = "";

		// Variable to get current filter values
		var category = techjoomla.jQuery('#category_id').val();

		if (typeof(category) != 'undefined')
		{
			if (urlValueName != 'undefined')
			{
				if (redirectlink.indexOf('?') === -1)
				{
					optionStr += "?"+urlValueName+"="+category;
				}
				else
				{
					optionStr += "&"+urlValueName+"="+category;
				}
			}

			redirectlink += optionStr;
		}

		optionStr = "";

		var tjFieldCheckedFilters = "";

		// Flag to add comma in filter fields
		var flag = 0;

		techjoomla.jQuery(".tjfieldCheck:checked").each(function()
		{
			if (Number(flag) != 0)
			{
				tjFieldCheckedFilters += ",";
			}

			flag++;

			tjFieldCheckedFilters += techjoomla.jQuery(this).val();
		});

		if (tjFieldCheckedFilters != '')
		{
			if (redirectlink.indexOf('?') === -1)
			{
				optionStr += "?tj_fields_value="+tjFieldCheckedFilters;
			}
			else
			{
				optionStr += "&tj_fields_value="+tjFieldCheckedFilters;
			}

			redirectlink += optionStr;
		}

		window.location = redirectlink;
	}

	function submitCategory()
	{
		var redirectlink = '<?php echo $baseurl;?>';

		var client = "com_quick2cart.products";
		var optionStr = "";

		if (typeof(client) != 'undefined')
		{
			if (redirectlink.indexOf('?') === -1)
			{
				optionStr += '?client='+client;
			}
			else
			{
				optionStr += '&client='+client;
			}

			redirectlink += optionStr;
		}

		optionStr = "";

		var urlValueName = "<?php echo $url_cat_param_name;?>";

		if (urlValueName != 'undefined')
		{
			if (redirectlink.indexOf('?') === -1)
			{
				optionStr += "?ModFilterCat="+urlValueName;
			}
			else
			{
				optionStr += "&ModFilterCat="+urlValueName;
			}

			redirectlink += optionStr;
		}

		optionStr = "";

		// Variable to get current filter values
		var category = techjoomla.jQuery('#category_id').val();

		if (typeof(category) != 'undefined')
		{
			if (urlValueName != 'undefined')
			{
				if (redirectlink.indexOf('?') === -1)
				{
					optionStr += "?"+urlValueName+"="+category;
				}
				else
				{
					optionStr += "&"+urlValueName+"="+category;
				}
			}

			redirectlink += optionStr;
		}

		/* Comma seperated parameters to be removed on change of category */
		var removeParamList = "<?php echo $removeParamOnchangeCat; ?>";

		if (removeParamList.trim())
		{
			var params_arr = removeParamList.split(",");

			for (var i = 0; i < params_arr.length; i += 1)
			{
				redirectlink = tj_removeParam(params_arr[i], redirectlink);
			}
		}

		window.location = redirectlink;
	}

	function tj_removeParam(key, sourceURL)
	{
		var rtn = sourceURL.split("?")[0],
			param,
			params_arr = [],
			queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

		if (queryString !== "")
		{
			params_arr = queryString.split("&");

			for (var i = params_arr.length - 1; i >= 0; i -= 1)
			{
				param = params_arr[i].split("=")[0];

				if (param === key)
				{
					params_arr.splice(i, 1);
				}
			}

			rtn = rtn + "?" + params_arr.join("&");
		}
		return rtn;
	}

	function tj_clearfilters()
	{
		var redirectlink = '<?php echo $baseurl;?>';

			/* Comma seperated parameters to be removed on change of category */
		var removeParamList = "<?php echo $removeParamOnchangeCat; ?>";

		if (removeParamList.trim())
		{
			var params_arr = removeParamList.split(",");

			for (var i = 0; i < params_arr.length; i += 1)
			{
				redirectlink = tj_removeParam(params_arr[i], redirectlink);
			}
		}

		techjoomla.jQuery(".filter-fieldCheckbox:checked").each(function()
		{
			techjoomla.jQuery(this).attr('checked', false);
		});

		window.location = redirectlink;
	}

</script>
