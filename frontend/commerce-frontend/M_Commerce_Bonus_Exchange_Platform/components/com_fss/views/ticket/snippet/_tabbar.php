<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if ($this->ticket_view == "open")
{
	$cst = null;
} else {
	$cst = FSS_Ticket_Helper::GetStatusByID($this->ticket_view); 
}
FSS_Translate_Helper::TrSingle($cst);

$tabs = FSS_Ticket_Helper::GetStatuss("own_tab"); 
FSS_Translate_Helper::Tr($tabs);
?>

<ul class="nav nav-tabs fss_user_tabbar">

	<?php echo FSS_GUIPlugins::output("userSupportTabs_Start"); ?>

	<?php if (FSS_Input::getString('search') != ""): ?>
		<li class="active">
			<a href='#' onclick="return false;">
				Search
			</a>
		</li>	
	<?php endif; ?>

	<?php if (!FSS_Settings::Get('support_only_admin_open') && FSS_Permission::AllowSupportOpen()): ?>
		<li class='<?php if (FSS_Input::getCmd('layout') == 'open') echo 'active'; ?>'>
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open&task=open.reset' ); ?>'>
				<?php echo JText::_("OPEN_NEW_TICKET"); ?>
			</a>
		</li>
	<?php endif; ?>
	
	<?php echo FSS_GUIPlugins::output("userSupportTabs_AfterNew"); ?>

	<?php if (FSS_Settings::get('support_simple_userlist_tabs')): ?>
		<?php if ($this->userid > 0): ?>
			<li <?php if (FSS_Input::getCmd('search') == "" && FSS_Input::getInt('ticketid') < 1 && FSS_Input::getCmd('layout') != 'open'): ?>class='active'<?php endif; ?>>
				<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=all'); ?>'>
					<?php echo JText::_('SUPPORT_TICKETS'); ?> (<?php echo $this->count['all']; ?>)
				</a>
			</li>
		<?php endif; ?>
	<?php else: ?>

		<?php if ($cst && !$cst->own_tab): ?>
			<li class="active">
				<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=' . $cst->id ); ?>'>
					<?php echo $cst->userdisp ? $cst->userdisp : $cst->title; ?> (<?php echo $this->count[$cst->id]; ?>)
				</a>
			</li>
		<?php endif; ?>				

		<?php if ($this->userid > 0): ?>
	
			<?php foreach ($tabs as $tab): ?>
				<?php if ($tab->combine_with > 0) continue; ?>
				<li <?php if ($cst && $cst->id == $tab->id) echo "class='active'";?>>
					<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=' . $tab->id ); ?>'>
						<?php echo $tab->userdisp ? $tab->userdisp : $tab->title; ?> (<?php echo $this->count[$tab->id]; ?>)
					</a>
				</li>
			<?php endforeach; ?>	

			<?php if (FSS_Settings::get('support_tabs_allopen') || $this->ticket_view == "open"): ?>
				<li <?php if ($this->ticket_view == "open") echo "class='active'";?>>
					<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=open' ); ?>'>
						<?php echo JText::sprintf("SA_ALLOPEN",$this->count['open']); ?>
					</a>
				</li>
			<?php endif; ?>
	
			<?php if (FSS_Settings::get('support_tabs_allclosed') || $this->ticket_view == "closed"): ?>
				<li <?php if ($this->ticket_view == "closed") echo "class='active'";?>>
					<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=closed' ); ?>'>
						<?php echo JText::sprintf("SA_CLOSED",$this->count['closed']); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if (FSS_Settings::get('support_tabs_all') || $this->ticket_view == "all" || ($this->ticket_view == "" && FSS_Input::getCmd('layout') != "open" && FSS_Input::getInt('ticketid') < 1)): ?>
				<li <?php if (FSS_Input::getCmd('layout') != "open" && FSS_Input::getInt('ticketid') < 1 && ($this->ticket_view == "all" || $this->ticket_view == "")) echo "class='active'";?>>
					<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=all' ); ?>'>
						<?php echo JText::sprintf("SA_ALL",$this->count['all']); ?>
					</a>
				</li>
			<?php endif; ?>


			<?php foreach (SupportSource::getUser_Tabs() as $tab): ?>
				<li class="<?php if ($tab->active) echo "active";?>">
					<a href='<?php echo strpos($tab->link, 'index.php') === FALSE ? $tab->link : FSSRoute::_($tab->link); ?>'>
						<?php echo $tab->tabname; ?>
					</a>
				</li>
			<?php endforeach; ?>

			<?php 
			$nottabs = FSS_Ticket_Helper::GetStatuss("own_tab", true); 
			FSS_Translate_Helper::Tr($nottabs);

			$showother = (count($nottabs) > 0);
	
			if ($showother || !FSS_Settings::get('support_tabs_allopen') || !FSS_Settings::get('support_tabs_allclosed') || !FSS_Settings::get('support_tabs_all')) :
			?>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" onclick="return false;">
						<?php echo JText::_('OTHER'); ?><b class="caret bottom-up"></b>
					</a>
				
					<ul class="dropdown-menu bottom-up pull-left">  
			
						<?php foreach ($nottabs as $tab): ?>
							<?php if ($tab->combine_with > 0) continue; ?>

							<li>
								<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=' . $tab->id ); ?>'>
									<?php echo $tab->userdisp ? $tab->userdisp : $tab->title; ?> (<?php echo $this->count[$tab->id]; ?>)
								</a>
							</li>
						<?php endforeach; ?>	
		
						<?php if (count($nottabs) > 0 && (!FSS_Settings::get('support_tabs_allopen') || !FSS_Settings::get('support_tabs_allclosed') || !FSS_Settings::get('support_tabs_all'))): ?>
							<li class="divider"></li>  
						<?php endif; ?>
				
						<?php if (!FSS_Settings::get('support_tabs_allopen')): ?>
							<li>
								<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=open' ); ?>'>
									<?php echo JText::sprintf("SA_ALLOPEN",$this->count['open']); ?>
								</a>
							</li>
						<?php endif; ?>

						<?php if (!FSS_Settings::get('support_tabs_allclosed')): ?>
							<li>
								<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=closed' ); ?>'>
									<?php echo JText::sprintf("SA_CLOSED",$this->count['closed']); ?>
								</a>
							</li>
						<?php endif; ?>

						<?php if (!FSS_Settings::get('support_tabs_all')): ?>
							<li>
								<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=support&tickets=all' ); ?>'>
									<?php echo JText::sprintf("SA_ALL",$this->count['all']); ?>
								</a>
							</li>
						<?php endif; ?>
					</ul> 
				</li>
			<?php endif; ?>
	
		<?php endif; ?>
		
	<?php endif; ?>
	
	<?php echo FSS_GUIPlugins::output("userSupportTabs_BefreView"); ?>

		<?php if (isset($this->ticketid) && $this->ticketid > 0): ?>
		<?php if ($this->userid < 1): ?>
		<li>
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket' ); ?>'>
				<?php echo JText::_("VIEW_DIFFERENT_TICKET"); ?>
			</a>
		</li>
		<?php endif; ?>
		<li class="active">
			<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . $this->ticketid ); ?>'>
				<?php echo JText::_("VIEW_TICKET"); ?>
			</a>
		</li>
	<?php endif; ?>
	
	<?php echo FSS_GUIPlugins::output("userSupportTabs_End"); ?>

</ul>
