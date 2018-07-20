<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticketemails' );?>" method="post" name="adminForm" id="adminForm">
<?php if (!$this->imap_ok): ?>
	<?php JError::raiseWarning( 100, 'Your server currently does not have mod_imap enabled in your php configuration. Email account checking will NOT work without this enabled (even for POP3 accounts)' ); ?>
	<?php JError::raiseWarning( 100, 'For more information on this requirement <a href="http://freestyle-joomla.com/help/freestyle-support?kbartid=89" target="_blank">Click Here</a>' ); ?>
	<?php JError::raiseWarning( 100, 'Your php.ini file is currently located at ' . $this->ini_location ); ?>
<?php endif; ?>
<div class='alert alert-info'><?php echo JText::sprintf('CRON_AUTOCLOSE_MSG', JText::_('CRON_MIDDLE'), JURI::root() . 'index.php?option=com_fss&view=cron', JURI::root() . 'index.php?option=com_fss&view=cron'); ?></div>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo FSS_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'Filter by title or enter article ID' );?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.submit();this.form.getElementById('prod_id').value='0';this.form.getElementById('ispublished').value='-1';"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
	</table>
	
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20" class="title">
   				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
            <th class="title"   >
				<?php echo JText::_('VALIDATE_ACCOUNT'); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'ACCOUNT_NAME', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>

            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'SERVER_ADDRESS', 'server', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th class="title"   >
				<?php echo JHTML::_('grid.sort',   'Username', 'username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
            <th class="title"   >
				<?php echo JText::_('Destination'); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'a.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
		</tr>
    </thead>
<?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
		$row = $this->data[$i];
        /*echo "<pre>";
		print_r($row);
		echo "</pre>";*/
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = FSSRoute::_( 'index.php?option=com_fss&controller=ticketemail&task=edit&cid[]='. $row->id );

    	$img = 'tick.png';
		$alt = JText::_( 'Published' );

		if ($row->published == 0)
		{
			$img = 'cross.png';
			$alt = JText::_( 'Unpublished' );
		}

		$type_values = array(
			'pop3' => 'POP3',
			'imap' => 'IMAP',
			);
			
		$newticketsfrom_values = array(
			'registered' => 'Registered Users Only',
			'everyone' => 'Everyone',
			);
?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
			<td>
   				<?php echo $checked; ?>
</td>

<td>
   				<a href='#' onclick='return validateAccount(<?php echo $row->id; ?>);'><?php echo JText::_('VALIDATE_ACCOUNT'); ?></a> | 
   				<a href='#' onclick='return runCronNow(<?php echo $row->id; ?>,<?php echo $row->cronid; ?>);'><?php echo JText::_('Run Cron Now'); ?></a>
				<div id="validate_result_<?php echo $row->id; ?>"></div>
			</td>

			<td>
			    <a href='<?php echo $link; ?>'>	<?php echo $row->name; ?></a>			</td>






			<td>
			    <?php echo strtoupper($row->type); ?>, <?php echo $row->server; ?>:<?php echo $row->port;?>	<?php if ($row->usessl) echo "SSL"; ?> <?php if ($row->usetls) echo "TLS"; ?>		
			</td>

			<td>
			    <?php echo $row->username; ?>			
			</td>
			<td>
			    <?php 
					$out = array();
			    if ($row->prod_id) $out[] = "<strong>".JText::_('PRODUCT').": </strong> " . $row->lf1; 
			    if ($row->dept_id) $out[] = "<strong>".JText::_('DEPARTMENT').": </strong> " . $row->lf2; 
			    if ($row->cat_id) $out[] = "<strong>".JText::_('CATEGORY').": </strong> " . $row->lf3; 
			    if ($row->pri_id) $out[] = "<strong>".JText::_('PRIORITY').": </strong> " . $row->lf4; 
			    if ($row->handler) $out[] = "<strong>".JText::_('HANDLER').": </strong> " . $row->lf5; 
					if (count($out) > 0) echo implode(", ", $out);
					?>			
			</td>


         	<td align="center">
				<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					<img src="<?php echo JURI::base(); ?>/components/com_fss/assets/<?php echo $img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" />
				</a>
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
<input type="hidden" name="controller" value="ticketemail" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<script>
function validateAccount(id)
{
	jQuery('#validate_result_'+id).html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	var url = "<?php echo FSSRoute::_('index.php?option=com_fss&view=ticketemails',false);?>&test=" + id + "&random=" + Math.floor((Math.random() * 1000000) + 100000);
	jQuery('#validate_result_' + id).load(url);
	return false;
}

function runCronNow(id, cronid)
{
	jQuery('#validate_result_'+id).html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	var url = "<?php echo JURI::root() . 'index.php?option=com_fss&view=cron';?>&test=" + cronid + "&random=" + Math.floor((Math.random() * 1000000) + 100000);
	jQuery('#validate_result_' + id).load(url);
}
</script>

<div class='alert'>
<h4 style='margin-bottom: 12px'>EMail Trimming Config</h4>
<p>When an email is replied to, the original email is usually included in the message. There are many ways of including this message, so it is tricky to remove from
when importing the email as a ticket reply. Due to this Freestyle Support has a system where you can add your own markers to split a message as required.</p>
<p>To add your own markers, create an XML file (any name is OK) in the folder <b>components/com_fss/plugins/emailcheck/trim</b> on your site. You can add your own
entries to this file. Please look at the core.xml file for some examples on how to add various entries and matches.</p>
<p>Once you have your custom file, you can use the button below to test your matched. You will need to use the plain text version of the email for testing.</p>
<p><a class="btn btn-default" href="<?php echo JRoute::_("index.php?option=com_fss&view=trimtest"); ?>">Test EMail Trimming</a></p>
</div>
