<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$group_totals = false;
$grand_totals = false;

if (isset($this->report->grouping->totals))
{
	$group_totals = array();
}

if (isset($this->report->totals))
{
	$grand_totals = array();
}

//print_p(reset($this->report->data));
?>

<div id='tab_data'>
	<table class="table table-condensed table-bordered table-report">
		<thead>
			<?php $max_row = 1; ?>
			<?php for ($rowno = 1 ; $rowno <= $max_row ; $rowno++): ?>
				<tr class="row<?php echo $rowno; ?>">
				<?php foreach ($this->report->field as $field): ?>
						<?php 
							if (!isset($field->row))
								$field->row = 1;
							
							$field->row = (int)$field->row;
							if ($field->row > $max_row)
							{
								$max_row = $field->row;
							}
							
							if ($field->row > 0 && $field->row != $rowno)
								continue; 
							
							if (isset($field->sum) && is_array($group_totals))
								$group_totals[$field->name] = 0;
							
							if (isset($field->sum) && is_array($grand_totals))
								$grand_totals[$field->name] = 0;

						?>
					<th <?php if (isset($field->span)) echo " colspan='".(int)$field->span."' " ?>><?php echo JText::_($field->text); ?></th>
				<?php endforeach; ?>
			</tr>
			<?php endfor; ?>
		</thead>
		<tbody>
			<?php $max_row = 1; ?>		
			
			<?php 
			$grouping = false;
			if (isset($this->report->grouping))
			{
				$grouping = $this->report->grouping->field;
				$group_base = "xxx-xxx-xxx";
			}
			$odd = 0;
			?>
				
			<?php foreach ($this->report->data as $row): ?>
				<?php 
					$odd = 1 - $odd;
					// sort out grouping headers
					if ($grouping)
					{
						$newval = $row->$grouping;
						if ($newval != $group_base)
						{
							if ($group_base != "xxx-xxx-xxx" && is_array($group_totals))
							{
								ShowTotals($this->report, $group_totals, $group_base . " total");
							}
							
							if ($group_base != "xxx-xxx-xxx" && empty($this->report->grouping->nogap))
								echo "<tr class='grouping'><td colspan='{$this->report->grouping->span}' style='height:6px;'></td></tr>";	
							
							echo "<tr class='grouping'><th colspan='{$this->report->grouping->span}'>";
							
							if (isset($this->report->grouping->display))
							{
								echo $this->parseText($this->report->grouping->display, $row);
							} else {
								echo $newval;
							}
							
							echo "</th></tr>";	
							$group_base = $newval;
						}						
					}
					
					// add current row to totals
					foreach ($this->report->field as $field)
					{
						$name = $field->name;
						if (isset($field->sum) && is_array($group_totals))
							$group_totals[$field->name] += $row->$name;	
						
						if (isset($field->sum) && is_array($grand_totals))
							$grand_totals[$field->name] += $row->$name;		
					}
				?>				
				<?php for ($rowno = 1 ; $rowno <= $max_row ; $rowno++): ?>
				<tr class="row<?php echo $rowno; ?> <?php if (!$odd && empty($this->report->norowhl)) echo 'row_highlight'; ?>">
					<?php foreach ($this->report->field as $field): ?>
						<?php 
							if (!isset($field->row))
								$field->row = 1;
							
							$field->row = (int)$field->row;
							if ($field->row > $max_row)
							{
								$max_row = $field->row;
							}
								
							if ($field->row > 0 && $field->row != $rowno)
								continue; 
						?>
						<?php $name = $field->name; ?>
						<td 
							<?php if (isset($field->span)) echo " colspan='{$field->span}' " ?>
							<?php if (isset($field->style)) echo " style='{$field->style}' " ?>
							<?php if (isset($field->nowrap)) echo " nowrap='1' " ?>
							>
							<?php if (isset($field->link)): ?>
								<a href="<?php echo $this->parseLink($field->link, $row); ?>" target='_blank'>
							<?php endif; ?>
							<?php echo OutputField($field, $row->$name, $row); ?>
							<?php if (isset($field->link)): ?>
								</a>
							<?php endif; ?>
						</td>
					
					<?php endforeach; ?>
				</tr>
				<?php endfor; ?>
			<?php endforeach; ?>
			
			<?php if (is_array($group_totals)): ?>
				<?php ShowTotals($this->report, $group_totals, JText::sprintf("FSS_REPORT_GTOTAL", JText::_($group_base))); ?>
				<tr><td colspan='<?php echo (int)$this->report->grouping->span; ?>' style='height:6px;'></td></tr>
			<?php endif; ?>
			<?php if (is_array($grand_totals)): ?>
				<?php ShowTotals($this->report, $grand_totals, JText::_("Total")); ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php

function OutputField($field, $value, $row = null)
{
	if (!isset($field->format))
	{
		echo $value;
		return;	
	}
	
	if ($field->format == "date")
	{
		if ($value != "" && $value != "0000-00-00" && $value != "0000-00-00 00:00:00")
		{
			$format = "Y-m-d"; 
			if (isset($field->dateformat)) 
			$format = $field->dateformat;
			if (substr($format, 0, 5) == "DATE_")
				$format = JText::_($format);
			$jdate = new JDate($value);
			echo $jdate->format($format);
		} elseif (isset($field->blank)) {
			echo $field->blank; 
		}
	} elseif ($field->format == "messagetime")
	{
		if ($value < 1)
		{
			echo "";
		} else if ($value > 0 && $value < 86400 * 10)
		{
			echo date("H:i", $value);
		} else {
			$format = "Y-m-d"; 
			if (isset($field->dateformat)) 
			$format = $field->dateformat;
			$jdate = new JDate($value);
			echo $jdate->format($format);
		}
	} elseif ($field->format == "hm") {
		$val = $value; 
		$mins = $val % 60;
		$hrs = floor($val / 60);
									
		echo sprintf("%d:%02d", $hrs, $mins); 								
	} elseif ($field->format == "bbcode") {
		echo FSS_Helper::ParseBBCode($value); 								
	} elseif ($field->format == "custfield") {

		$cfid = (int)str_ireplace("custom", "", $field->name);
		if ($cfid < 1)
		{
			echo "Please name custom field as customXX, where XX is the ID of your field<br>".$value;
			return;
		}

		$allcf = FSSCF::GetAllCustomFields(true);
		$custfield = null;
		foreach ($allcf as $field)
		{
			if ($field['id'] == $cfid) $custfield = $field;
		}

		if (!$custfield)
		{
			echo "Unable to find custom field with id $cfid<br>".$value;
			return;
		}

		$fielddata = array();
		$fielddata_inner = array('ticket_id' => 0, 'field_id' => $cfid, 'value' => $value);
		$fielddata[$cfid] = $fielddata_inner;
		echo FSSCF::FieldOutput($custfield,$fielddata,array('report' => 1, 'data' => $row));

		//echo $value;
	} else {
		echo $value;
	}
}

function ShowTotals($report, &$totals, $heading)
{
	$max_row = 1
?>
	<?php for ($rowno = 1 ; $rowno <= 1/*$max_row*/ ; $rowno++): ?>
	<tr class="row<?php echo $rowno; ?>">
		<?php foreach ($report->field as $field): ?>
			<?php 
				if (!isset($field->row))
					$field->row = 1;
							
				$field->row = (int)$field->row;
				if ($field->row > $max_row)
				{
					$max_row = $field->row;
				}
								
				if ($field->row > 0 && $field->row != $rowno)
					continue; 
			?>
			<?php $name = $field->name; ?>

			<th <?php if (isset($field->span)) echo " colspan='".(int)$field->span."' " ?>>
				<?php 
					if (isset($field->totalheader))
					{
						echo $heading;
					} else if (isset($field->sum))
					{
						echo OutputField($field, $totals[$field->name]);
					}
				?>
			</th>
					
		<?php endforeach; ?>
	</tr>
	<?php endfor; ?>
<?php

	foreach ($totals as $offset => $value)
		$totals[$offset] = 0;	
}