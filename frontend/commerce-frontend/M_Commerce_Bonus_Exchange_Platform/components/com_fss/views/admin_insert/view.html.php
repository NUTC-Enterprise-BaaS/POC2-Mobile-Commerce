<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated e4e88880204f034016d3ca54714611a4
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'pagination.php');

class FssViewAdmin_Insert extends FSSView
{
	function display($tpl = null)
	{
		FSS_Helper::noBots();
		FSS_Helper::noCache();
		
		$this->type = FSS_Input::getCmd('type');
		$this->editor = FSS_Input::getCmd('editor');
		
		$this->Init($this->type);
		
		parent::display();
	}
	
	function Init($table, $component = "")
	{
		$xmlfile = JPATH_ROOT.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'picktable'.DS."{$table}.xml";
			
		$this->xml = simplexml_load_file($xmlfile);
		$this->xml = $this->xml->table;
	
		$this->LoadData();			
	}
	
	function LoadData()
	{
		if (!$this->xml)
			return;
			
		$qry = (string)$this->xml->sql;
		$where = array();
		
		if ($this->xml->where)
		{
			foreach ($this->xml->where as $w)
			{
				$where[] = (string)$w;
			}
		}
		
		if ($this->xml->addbtntext)
			$this->addbtntext = (string)$this->xml->addbtntext;
		
		if ($this->xml->use_auth)
		{
			// sort out which articles the user can view here, based on published, access, author
			// sort published out here	
			$published = (string)$this->xml->use_auth->attributes()->published;
			$access = (string)$this->xml->use_auth->attributes()->access;
			$author = (string)$this->xml->use_auth->attributes()->author;
			$where[] = "{$published} = 1";
		}

		
		$this->search = FSS_Input::getString('search');
		
		if ($this->search != "")
		{
			foreach ($this->xml->filters->search->field as $field)
			{
				$field = (string)$field;
				
				$where[] = "$field LIKE '%" . FSSJ3Helper::getEscaped($db, $this->search) . "%'";	
			}	
		}
		
		foreach ($this->xml->filters->filter as $filter)
		{
			$type = (string)$filter->attributes()->type;
			$field = (string)$filter->attributes()->field;
			$filter_id = (string)$filter->attributes()->id;
			
			if ($type == "lookup")
			{
				$key = (string)$filter->key;
				$display = (string)$filter->display;
				
				$var = "filter_" . $filter_id;
				$value = trim(FSS_Input::getString($var));
				$this->$var = FSS_Input::getString($var);
	
				if ($value != "")
					$where[] = "$field = '" . FSSJ3Helper::getEscaped($db, $value) . "'";
			}
		}
		
		if (count($where) > 0)
		{
			$qry .= " WHERE " . implode(" AND ",$where);	
		}
		
		$this->order = FSS_Input::getCmd('filter_order');
		$this->orderdir = FSS_Input::getCmd('filter_order_Dir','ASC');
		
		if ($this->order == "" && $this->xml->ordering)
			$this->order = (string)$this->xml->ordering;
			
		if ($this->order)
		{
			$qry .= " ORDER	BY {$this->order} {$this->orderdir} ";
		}
		
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		
		//echo "Qry : $qry<br>";
		$db->query();
		$this->num_rows = $db->getNumRows();
		
		$mainframe = JFactory::getApplication();
		$this->limit = $mainframe->getUserStateFromRequest('global.list.limitpick', 'limit', 10, 'int');
		$this->limitstart = FSS_Input::getInt('limitstart');
		$this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
		
		$this->pagination = new JPaginationEx($this->num_rows, $this->limitstart, $this->limit );
		$db->setQuery($qry, $this->limitstart, $this->limit);
		
		$this->data = $db->loadObjectList();
		//echo $qry."<br>";
		//print_p($this->data);
	}
	
	function Process()
	{
		if (!$this->xml)
			return;
	}
	
	function OutputTable()
	{
		if (!$this->xml)
			return;
?>

		<p>
			<div class="pull-right">
<?php
		
		foreach ($this->xml->filters->filter as $filter)
		{
			$type = (string)$filter->attributes()->type;
			$field = (string)$filter->attributes()->field;
			$filter_id = (string)$filter->attributes()->id;
			$var = "filter_" . $filter_id;
			$value = $this->$var;
			
			if ($type == "lookup")
			{
				$key = (string)$filter->key;
				$display = (string)$filter->display;
				
				echo "<select name='filter_{$filter_id}' id='filter_{$filter_id}' onchange='jQuery(\"#fssForm\").submit()'>";
				
				$db = JFactory::getDBO();
				$db->setQuery($filter->sql);
				$items = $db->loadAssocList();
				
				echo "<option value=''>" . (string)$filter->heading . "</option>";
				foreach ($items as $item)
				{
					$selected = "";
					if ($item[$key] == $value)
						$selected = "selected='selected'";
					echo "<option value='" . $item[$key] . "' {$selected}>" . $item[$display] . "</option>";
				}
				
				echo "</select>";
			}
		}
		// output header
?>
			</div>
			
			<div class='input-append'>
				<input name='search' id="search" type='text' value="<?php echo htmlentities($this->search,ENT_QUOTES,"utf-8"); ?>" class="input-medium" placeholder="Search" />
				<button class='btn btn-primary'>Go</button>
				<button" class='btn btn-default' onclick="resetForm();">Reset</button>
			</div>
		</p>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th width="5">#</th>
<?php foreach ($this->xml->displayfields->field as $field): ?>
				<th><?php echo JHTML::_('grid.sort',  $field->attributes()->id, $field->attributes()->sort, $this->orderdir, $this->order ); ?></th>
<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
<?php

	$displayfield = (string)$this->xml->displayfield;

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
       
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
			<?php foreach ($this->xml->displayfields->field as $field): ?>
				<td>
					<?php 
					if ($field->attributes()->link)
					{
						$link = (string)$this->xml->link;
						$keyfield = (string)$this->xml->keyfield;
						$link = FSSRoute::x(str_replace("%ID%", $row->$keyfield, $link), false);
						$link = JURI::base().substr($link, strlen(JURI::base(true)) + 1);
						
						echo "<a href='$link' class='pick_link' id='pick_{$row->id}'>";
					}
					$field_name = (string)$field->attributes()->name; 
					if ((string)$field->attributes()->type == "yesno")
					{
						echo FSJ_Helper::GetYesNoText($row->$field_name);
					} else {
						echo $row->$field_name; 
					}
					if ($field->attributes()->link)
					{
						echo "</a>";
					}
					?>
				</td>
			<?php endforeach; ?>
			<td id="title_<?php echo $row->id; ?>" style='display:none'><?php echo $row->$displayfield; ?></td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>		
		</tbody>
	</table>
	<?php echo $this->pagination->getListFooter(); ?>

<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->order; ?>" />
<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->orderdir; ?>" />
<input type="hidden" name="boxchecked" id='boxchecked' value="0" />
<input type="hidden" name="type" id="type" value="<?php echo $this->type; ?>" />
<input type="hidden" name="editor" id="editor" value="<?php echo $this->editor; ?>" />
	
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="view" value="admin_insert" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="tmpl" value="component" />

<script>

function resetForm() {
	jQuery('#search').val("");
	<?php foreach ($this->xml->filters->filter as $filter)
		{
			$type = (string)$filter->attributes()->type;
			$field = (string)$filter->attributes()->field;
			$filter_id = (string)$filter->attributes()->id; ?>
	jQuery('#filter_<?php echo $filter_id; ?>').val("");		
	<?php } ?>
	
	jQuery('#fssForm').submit();
}

function tableOrdering(field, order)
{
	jQuery('#order').val(field);
	jQuery('#orderdir').val(order);
	jQuery('#pickRelForm').submit();
}	

jQuery(document).ready(function () {
	jQuery('.pick_link').click(function (ev) {
		ev.preventDefault();
		var url = jQuery(this).attr('href');
		var title = jQuery(this).text();
		
		window.parent.insertLink(url, title, '<?php echo FSS_Input::getCmd('editor'); ?>');
	});
});
</script>
<?php
	}	
}
