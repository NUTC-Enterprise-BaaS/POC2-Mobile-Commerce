<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticketgroups' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = (strpos($this->lists['order'], "ordering") !== FALSE); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo FSS_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
			</td>
		</tr>
	</table>

    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Name', 'groupname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Description', 'description', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'MEMBER_COUNT', 'cnt', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'ALL_EMAIL', 'allemail', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th>
                <?php echo JHTML::_('grid.sort',   'ALL_SEE', 'allsee', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="200" class="title" nowrap="nowrap">
                <?php echo JText::_("PRODUCTS"); ?>
            </th>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::_( 'index.php?option=com_fss&controller=ticketgroup&task=edit&cid[]='. $row->id );
	
		$allemail = FSS_GetYesNoText($row->allemail);
		if ($row->allsee == 0)
    	{
    		$allsee = JText::_('VIEW_NONE');//"None";	
    	} elseif ($row->allsee == 1)
    	{
    		$allsee = JText::_('VIEW');//"See all tickets";	
    	} elseif ($row->allsee == 2)
    	{
    		$allsee = JText::_('VIEW_REPLY');//"Reply to all tickets";	
    	} elseif ($row->allsee == 3)
    	{
    		$allsee = JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
    	}
	
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->groupname; ?></a>
			</td>
			<td>
			    <?php echo $row->description; ?>
			</td>
			<td>
				<a href="<?php echo FSSRoute::_("index.php?option=com_fss&view=members&groupid={$row->id}"); ?>" style="position:relative;top:-3px;" title="Edit Members">
				<img src="<?php echo JURI::root( true ); ?>/administrator/components/com_fss/assets/members.png" width="16" height="16" style="position:relative;top:3px;">	
				<?php 
				if ($row->cnt == 0)
				{
					echo JText::_("NO_MEMBERS"); 
				} else if ($row->cnt == 1) {
			    	echo JText::sprintf("X_MEMBER",$row->cnt); 
				} else {
			    	echo JText::sprintf("X_MEMBERS",$row->cnt); 
				}				
			    ?>
				</a>
			</td>
			<td>
				<?php echo $allemail; ?>
			</td>
			<td>
				<?php echo $allsee; ?>
			</td>
	            <td align='center'>
				<?php if ($row->allprods) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
					<?php 
					$db = JFactory::getDBO();
					$id = $row->id;
					
					$qry = "SELECT * FROM #__fss_ticket_group_prod as gp LEFT JOIN ";
					$qry .= " #__fss_prod as p ON gp.prod_id = p.id WHERE ";
					$qry .= " group_id = $id ORDER BY p.title";
					
						$db->setQuery($qry);
						
						$items = $db->loadObjectList();
						$count = 0;
						foreach ($items as $item)
						{
							$count++;
							if ($count > 4)
							{
								$extra = count($items) - $count + 1;
								echo "Plus $extra others";
								break;	
							}
							echo $item->title . "<br>";
						}
					?>
				<?php } ?>
			</td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>

    </table>
</div>

<?php if (FSSJ3Helper::IsJ3()): ?>
	<div class='pull-right'>
		<?php echo $this->pagination->getLimitBox(); ?>
</div>
<?php endif; ?>
<?php echo $this->pagination->getListFooter(); ?>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="ticketgroup" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

