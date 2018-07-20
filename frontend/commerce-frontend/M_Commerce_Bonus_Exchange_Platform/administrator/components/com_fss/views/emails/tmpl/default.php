<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=faqs' );?>" method="post" name="adminForm" id="adminForm">
<div id="editcell">
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
            <th width="20%">
                <?php echo JText::_("TEMPLATE"); ?>
            </th>
            <th>
                <?php echo JText::_("DESCRIPTION"); ?>
            </th>
            <th>
                <?php echo JText::_("SUBJECT"); ?>
            </th>
            <th width="8%" norwap>
                <?php echo JText::_("IS_HTML"); ?>
            </th>
		</tr>
    </thead>
    <?php
	$k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::_( 'index.php?option=com_fss&controller=email&task=edit&cid[]='. $row->id );
		
    	if ($row->ishtml)
    	{
    		$ishtml_img = "tick";
    	} else {
    		$ishtml_img = "cross";
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
  			    <a href="<?php echo $link; ?>"><?php echo $row->tmpl; ?></a>
			</td>
			<td>
  			    <?php echo JText::_($row->description); ?>
			</td>
			<td>
  			    <?php echo $row->subject; ?>
			</td>
			<td align='center'>
				<img src='<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $ishtml_img; ?>.png' width='16' height='16' />
			</td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </table>
</div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="email" />
</form>

