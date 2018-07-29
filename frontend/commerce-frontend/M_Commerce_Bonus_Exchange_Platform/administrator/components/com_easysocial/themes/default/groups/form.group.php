<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form name="adminForm" id="adminForm" class="groupsForm" method="post" enctype="multipart/form-data" data-groups-form data-table-grid>
	<div class="es-user-form">
		<div class="wrapper accordion">
			<?php if (!$isNew) { ?>
			<div class="tab-box tab-box-alt">
				<div class="tabbable">
					<ul id="userForm" class="nav nav-tabs nav-tabs-icons nav-tabs-side">
						<li class="tabItem <?php if($activeTab == 'profile') { ?>active<?php } ?>" data-tabnav data-for="profile">
							<a href="#profile" data-bs-toggle="tab">
								<?php echo JText::_('COM_EASYSOCIAL_GROUPS_FORM_GROUP_DETAILS');?>
							</a>
						</li>
						<li class="tabItem <?php if($activeTab == 'members') { ?>active<?php } ?>" data-tabnav data-for="members">
							<a href="#members" data-bs-toggle="tab">
								<?php echo JText::_('COM_EASYSOCIAL_GROUPS_FORM_GROUP_MEMBERS');?>
							</a>
						</li>
					</ul>

					<div class="tab-content tab-content-side">
						<div id="profile" class="tab-pane <?php if($activeTab == 'profile') { ?>active<?php } ?>" data-tabcontent data-for="profile">
							<?php echo $this->includeTemplate('admin/groups/form.group.fields'); ?>
						</div>

						<div id="members" class="tab-pane <?php if($activeTab == 'members') { ?>active<?php } ?>" data-tabcontent data-for="members">
							<?php echo $this->includeTemplate('admin/groups/form.group.users'); ?>
						</div>
					</div>
				</div>
			</div>
			<?php } else { ?>
				<?php echo $this->includeTemplate('admin/groups/form.group.fields'); ?>
			<?php } ?>
		</div>
	</div>

	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="controller" value="groups" />
	<input type="hidden" name="task" value="" data-table-grid-task />
	<input type="hidden" name="ordering" value="<?php echo $ordering;?>" data-table-grid-ordering />
	<input type="hidden" name="direction" value="<?php echo $direction;?>" data-table-grid-direction />
	<input type="hidden" name="id" value="<?php echo $group->id ? $group->id : ''; ?>" />
	<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
	<input type="hidden" name="activeTab" data-active-tab value="<?php echo $activeTab; ?>" />
	<?php echo JHTML::_('form.token');?>
</form>
