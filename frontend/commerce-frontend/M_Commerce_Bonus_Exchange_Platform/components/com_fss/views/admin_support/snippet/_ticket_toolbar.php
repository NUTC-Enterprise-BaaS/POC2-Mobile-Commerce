<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo FSS_GUIPlugins::output("adminTicketViewTools", array('ticket'=> $this->ticket)); ?>

<div class="pull-right btn-group" style="z-index: 10">
	<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
		<i class="icon-cog"></i> <?php echo JText::_("Tools"); ?>
		<span class="caret"></span>
	</a>
	<ul class="dropdown-menu">
		<li class="dropdown-header">
			<i class="icon-print"></i> <?php echo JText::_("Print"); ?> 
		</li>
		<li>
			<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&print=all&tmpl=component&ticketid=' . $this->ticket->id); ?>' target='_new'>
				<?php echo JText::_('ALL_DETAILS'); ?>
			</a>
		</li>
		<li>
			<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&print=clean&tmpl=component&ticketid=' . $this->ticket->id); ?>' target='_new'>
				<?php echo JText::_('NO_PRIVATE_MESSAGES'); ?>
			</a>
		</li>
		<?php $prints = Support_Print::getPrintList(true, $this->ticket); ?>			
		<?php foreach ($prints as $name => $title): ?>
		<li>
			<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=ticket&print=' . $name . '&tmpl=component&ticketid=' . $this->ticket->id); ?>' target='_new'>
				<?php echo JText::_($title); ?>
			</a>
		</li>			
		<?php endforeach; ?>
		<li role="presentation" class="divider"></li>

		<?php if (FSS_Permission::auth("core.create", "com_fss.kb") || FSS_Permission::auth("core.create", "com_fss.faq")): ?>
			<li class="dropdown-header">
				<i class="icon-chevron-right"></i> <?php echo JText::_("EXPORT_TO"); ?>
			</li>
			<?php if (FSS_Permission::auth("core.create", "com_fss.kb")): ?>
				<li>
					<a href='#' onclick='jQuery("#ticket_to_kb").submit();return false;'>
						<?php echo JText::_('MAIN_MENU_KB'); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if (FSS_Permission::auth("core.create", "com_fss.faq")): ?>
				<li>
					<a href='#' onclick='jQuery("#ticket_to_faq").submit();return false;'>
						<?php echo JText::_('MAIN_MENU_FAQS'); ?>
					</a>
				</li>
			<?php endif; ?>				
			<li role="presentation" class="divider"></li>
		<?php endif; ?>


		<?php if ($this->ticket->user_id == 0): ?>
			<li class="dropdown-header">
				<i class="icon-user"></i> <?php echo JText::_('UNREGISTERED_TICKET_'); ?>
			</li>
			<li>
				<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=ticket.resend_password&ticketid=' . $this->ticket->id, false); ?>'>
					<?php echo JText::_('RE_SEND_PASSWORD_EMAIL'); ?>
				</a>
			</li>
			<li>
				<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&task=ticket.resend_all_passwords&ticketid=' . $this->ticket->id, false); ?>'>
					<?php echo JText::_('SEND_LIST_OF_ALL_TICKETS'); ?>
				</a>
			</li>
			<li>
				<?php 
					$username = preg_replace('/[^a-zA-Z0-9]+/', '_', $this->ticket->unregname);
					$username = str_replace("__", "_", $username);
					$username = strtolower($username);
				?>
				<script>
				function PickUser(userid, username, name)
				{
					fss_modal_hide();
					location.reload();
				}
				</script>
				<a class='show_modal_iframe' href="<?php echo FSSRoute::_("index.php?option=com_fss&view=admin_support&layout=createuser&tmpl=component&email=" . urlencode($this->ticket->email) . "&name=" . urlencode($this->ticket->unregname) . "&username=" . urlencode($username) . "&ticketid=" . $this->ticket->id); ?>" >
					<?php echo JText::_('CREATE_USER'); ?>
				</a>
			</li>		
			
			<li role="presentation" class="divider"></li>
		<?php endif; ?>
	
		<li class="dropdown-header">
			<i class="icon-link"></i> <?php echo JText::_('MERGE_TICKETS'); ?>
		</li>
		<li>
			<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=list&merge=into&ticketid=' . $this->ticket->id . '&searchtype=advanced&what=search&status=&username=' . $this->ticket->username, false); ?>'>
				<?php echo JText::_('THIS_TICKET_INTO_ANOTHER'); ?>
			</a>
		</li>
		<li>
			<a href='<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=list&merge=from&ticketid=' . $this->ticket->id . '&searchtype=advanced&what=search&status=&username=' . $this->ticket->username, false); ?>'>
				<?php echo JText::_('ANOTHER_TICKET_INTO_THIS_ONE'); ?>
			</a>
		</li>
		<li role="presentation" class="divider"></li>

		<li>
			<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=list&merge=related&ticketid=' . $this->ticket->id, false); ?>">
				<?php echo JText::_('ADD_RELATED_TICKET'); ?>
			</a>
		</il>

		<?php echo FSS_GUIPlugins::output("adminTicketViewToolsMenu", array('ticket'=> $this->ticket)); ?>

		<?php if (FSS_Settings::get('support_delete')): ?>
			<li role="presentation" class="divider"></li>

			<li>
				<a href="<?php echo FSSRoute::_('index.php?option=com_fss&view=admin_support&layout=list&task=ticket.delete&ticketid=' . $this->ticket->id, false); ?>" onclick="return confirm('<?php echo JText::_('ARE_YOU_SURE_DELETE'); ?>');">
					<?php echo JText::_('DELETE_TICKET'); ?>
				</a>
			</il>
		<?php endif; ?>
	</ul>
</div>

<div class="pull-right btn-group" style="margin-right: 8px; z-index: 10;">
	<a class="btn btn-default fssTip" title="<?php echo JText::_("GOTO_PREVIOUS_TICKET_OF_THIS_STATUS"); ?>" href="<?php echo FSSRoute::x('task=navigate.prev&ticketid=' . $this->ticket->id . '&nocache=' . time(), false); ?>"><?php echo JText::_('PREV'); ?></a>
	<a class="btn btn-default fssTip" title="<?php echo JText::_("GOTO_NEXT_TICKET_OF_THIS_STATUS"); ?>" href="<?php echo FSSRoute::x('task=navigate.next&ticketid=' . $this->ticket->id . '&nocache=' . time(), false); ?>"><?php echo JText::_('NEXT'); ?></a>
</div>
