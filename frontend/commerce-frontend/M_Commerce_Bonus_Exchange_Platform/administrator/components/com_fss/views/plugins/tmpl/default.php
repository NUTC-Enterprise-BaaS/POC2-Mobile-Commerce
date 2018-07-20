<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$k = 0;
?>

<div class="fss_main">

<script>
Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == "cancel") {
		window.location = '<?php echo JRoute::_('index.php?option=com_fss'); ?>';
	}
	Joomla.submitform(pressbutton);
}
</script>

<div id="editcell">
    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th>
                Type
            </th>
			<th>
                Title
            </th>
            <th>
                Description
            </th>
			<th width="1%" nowrap="nowrap">
				Status
			</th>
		</tr>
    </thead>
    <?php foreach ($this->plugins as $plugin): ?>
        <tr class="<?php echo "row$k"; $k = 1 - $k; ?>">
			<td nowrap>
			    <?php 
					switch ($plugin->type)
			    {
			    	case 'tickets':
			    		echo "Ticket Action Plugin";
			    		break;
			    	case 'gui':
			    		echo "GUI Plugin";
			    		break;
			    	case 'ticketprint':
			    		echo "Ticket Print Layout";
			    		break;
			    	case 'ticketopensearch':
			    		echo "Ticket Open Search";
			    		break;
			    	case 'cron':
			    		echo "CRON Plugin";
			    		break;
			    	case 'userlist':
			    		echo "User Pick List";
			    		break;
			    	default:
			    		echo $plugin->type;
			    		break;
			    }
				 ?>
			</td>
			<td nowrap>
			    <div>
					<?php if ($plugin->settingsfile): ?>
						<a href="<?php echo JRoute::_('index.php?option=com_fss&view=plugins&layout=configure&type=' . $plugin->type . "&name=" . $plugin->name); ?>">
					<?php endif; ?>
					<?php echo $plugin->title; ?>
					<?php if ($plugin->settingsfile): ?>
						</a>
					<?php endif; ?>
				</div>
				<div class="small"><?php echo $plugin->type . " / " . $plugin->name; ?></div>
			</td>
			<td>
   				<?php echo $plugin->description; ?>
			</td>
			<td align="center" nowrap>
				<?php if ($plugin->enabled): ?>
					<a href='<?php echo JRoute::_('index.php?option=com_fss&view=plugins&task=disable&type=' . $plugin->type . "&name=" . $plugin->name); ?>' class="btn btn-micro fssTip" title="Click to disable"><i class="icon-publish"></i></a>
				<?php else: ?>
					<a href="<?php echo JRoute::_('index.php?option=com_fss&view=plugins&task=enable&type=' . $plugin->type . "&name=" . $plugin->name); ?>" class="btn btn-micro fssTip" title="Click to Enable"><i class="icon-unpublish"></i></a>
				<?php endif; ?>
				<?php if ($plugin->settingsfile): ?>
					<a href="<?php echo JRoute::_('index.php?option=com_fss&view=plugins&layout=configure&type=' . $plugin->type . "&name=" . $plugin->name); ?>" class="btn btn-micro">
						<i class="icon-options"></i>Options
					</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	
    </table>
</div>

</div>