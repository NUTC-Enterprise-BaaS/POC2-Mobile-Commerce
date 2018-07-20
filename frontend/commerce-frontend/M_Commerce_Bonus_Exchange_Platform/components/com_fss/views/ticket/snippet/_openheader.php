<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if ($this->admin_create == 1): ?>
	<?php $regname = "{$this->user->name} ({$this->user->username})"; ?>
	<div class="alert alert-info" style="padding-right: 6px;">
		<div class="pull-right" style="position: relative; top: -2px;">
			<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=registered&regname=' . urlencode($regname) . "&user_id=" .  $this->user->id); ?>" class="btn btn-mini btn-info"><?php echo JText::_('CHANGE'); ?></a>
			<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&cancel_create=1', false ); ?>" class="btn btn-mini btn-danger"><?php echo JText::_('CANCEL'); ?></a>
		</div>
		<?php echo JText::sprintf("YOU_ARE_CREATING_A_NEW_SUPPORT_TICKET_FOR_EMAIL",$this->user->name,$this->user->username,$this->user->email); ?>
	</div>
	
<?php elseif ($this->admin_create == 2): ?>
	<div class="alert alert-info" style="padding-right: 6px;">
		<div class="pull-right" style="position: relative; top: -2px;">
			<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&layout=new&type=unregistered&admin_create_email=' . urlencode($this->unreg_email) . '&admin_create_name=' . urlencode($this->unreg_name) ); ?>" class="btn btn-mini btn-info"><?php echo JText::_('CHANGE'); ?></a>
			<?php if (FSS_settings::get('support_allow_unreg')): ?>
				<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin_support&cancel_create=1', false ); ?>" class="btn btn-mini btn-danger"><?php echo JText::_('CANCEL'); ?></a>
			<?php endif; ?>
		</div>
		<?php echo JText::sprintf("YOU_ARE_CREATING_A_NEW_SUPPORT_TICKET_FOR_UNREGISTERED_USER_EMAIL",$this->unreg_name,$this->unreg_email ? $this->unreg_email : JText::_('NO_EMAIL')); ?>
	</div>
<?php else: ?>
	
	<?php if ($this->userid > 0): ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_tabbar.php'); ?>
	<?php  else: ?>

		<?php if ($this->email): ?>
				<div class="alert alert-info">
				<?php echo JText::sprintf("CREATE_UNREG",$this->email,FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open&task=open.reset' )); ?>
			</div>
		<?php endif; ?>

		<ul class="nav nav-tabs">
			<?php if (!FSS_Settings::Get('support_only_admin_open') && FSS_Permission::AllowSupportOpen()): ?>
			<li class="active">
				<a class='ffs_tab fss_tab_selected' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open' ); ?>'>
					<?php echo JText::_('OPEN_NEW_TICKET'); ?>
				</a>
			</li>
			<?php endif; ?>
			<li>
				<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket' );// FIX LINK ?>'>
					<?php echo JText::_('VIEW_TICKET'); ?>
				</a>
			</li>
		</ul>

	<?php endif; ?>
<?php endif; ?>

<?php if ($this->admin_create > 0): ?>
	<?php 
		$additional = array();
		
		$additionalusers = JRequest::getVar('additionalusers');
		$additionalusers = explode(",", $additionalusers);
		foreach ($additionalusers as $userid)
		{
			if ($userid < 1) continue;
			
			$user = JFactory::getUser($userid);
			$additional[] = $user->name . " (" . $user->username. ", " . $user->email . ")";
		}
		
		$additionalemails = JRequest::getVar('additionalemails');
		$additionalemails = explode(",", $additionalemails);
		foreach ($additionalemails as $email)
		{
			$email = trim($email);
			if ($email == "") continue;
			
			$additional[] = $email;
		}

		if (count($additional) > 0): ?>
			
			<div class="alert alert-warning">
				<h4>The following users will be included on this ticket:</h4>
				<p>
					<ul>
					<?php foreach ($additional as $user): ?>
						<li><?php echo $user; ?></li>
					<?php endforeach; ?>	
					<ul>
				</p>
			</div>
			
	<?php endif; ?>
<?php endif; ?>
	
