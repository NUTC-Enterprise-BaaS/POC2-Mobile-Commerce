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
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<ul class="milestone-list fd-reset-list">
	<?php foreach( $milestones as $milestone ){ ?>
	<li class="milestone-item<?php echo $milestone->isDue() ? ' is-due' : '';?><?php echo $milestone->isCompleted() ? ' is-completed' : '';?>" data-group-tasks-milestone-item data-id="<?php echo $milestone->id;?>">
		<div class="milestone-title clearfix">
			<h4>
				<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'customView' => 'item' , 'uid' => $group->getAlias() , 'type' => SOCIAL_TYPE_GROUP , 'id' => $app->getAlias() , 'milestoneId' => $milestone->id ) , false );?>">
					<?php echo $milestone->get( 'title' );?>
				</a>
				<span class="label label-danger label-due milestone-labels"><?php echo JText::_( 'APP_GROUP_TASKS_OVERDUE' ); ?></span>
				<span class="label label-success label-completed milestone-labels"><?php echo JText::_( 'APP_GROUP_TASKS_COMPLETED' ); ?></span>

				<?php if ($group->isAdmin()) { ?>
				<span class="btn-group pull-right">
					<a href="javascript:void(0);" data-bs-toggle="dropdown" class="dropdown-toggle_ btn btn-dropdown">
						<i class="icon-es-dropdown"></i>
					</a>
					<ul class="dropdown-menu">
						<li>
							<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'customView' => 'form' , 'uid' => $group->getAlias() , 'type' => SOCIAL_TYPE_GROUP , 'id' => $app->getAlias() , 'milestoneId' => $milestone->id ) , false );?>"><?php echo JText::_( 'APP_GROUP_TASKS_MILESTONE_EDIT' );?></a>
						</li>

						<?php if( $milestone->isCompleted() ){ ?>
						<li>
							<a href="javascript:void(0);" data-milestone-mark-uncomplete><?php echo JText::_( 'APP_GROUP_TASKS_MILESTONE_MARK_UNCOMPLETED' );?></a>
						</li>
						<?php } ?>

						<?php if( !$milestone->isCompleted() ){ ?>
						<li>
							<a href="javascript:void(0);" data-milestone-mark-complete><?php echo JText::_( 'APP_GROUP_TASKS_MILESTONE_MARK_COMPLETED' );?></a>
						</li>
						<?php } ?>
						<li class="divider"></li>
						<li>
							<a href="javascript:void(0);" data-milestone-delete><?php echo JText::_( 'APP_GROUP_TASKS_MILESTONE_DELETE' );?></a>
						</li>
					</ul>
				</span>
				<?php } ?>
			</h4>
		</div>
		<div class="milestone-meta">
			<ul class="fd-reset-list">
				<li>
					<i class="fa fa-signup"></i> <?php echo JText::sprintf( 'APP_GROUP_TASKS_TOTAL_TASKS' , $milestone->getTotalTasks() );?>
				</li>
				<li>
					<i class="fa fa-calendar"></i> <?php echo JText::sprintf( 'APP_GROUP_TASKS_META_DUE_ON' , FD::date( $milestone->due )->format( JText::_( 'DATE_FORMAT_LC1' ) ) );?></i>
				</li>
				<li>
					<i class="fa fa-user"></i> <?php echo JText::sprintf( 'APP_GROUP_TASKS_MILESTONE_IS_RESPONSIBLE' , $this->html( 'html.user' , $milestone->getAssignee()->id , true ) ); ?></a>
				</li>
			</ul>
		</div>
		<div class="milestone-desc mt-10">
			<?php echo $milestone->getContent(); ?>
		</div>
	</li>
	<?php } ?>
</ul>

<?php if( !$milestones ){ ?>
<div class="empty empty-hero">
	<i class="fa fa-download"></i>

	<div>
		<?php echo JText::_( 'APP_GROUP_TASKS_EMPTY_MILESTONES' ); ?>
	</div>

	<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'uid' => $group->getAlias() , 'type' => SOCIAL_TYPE_GROUP , 'id' => $app->getAlias() , 'customView' => 'form' ) );?>" class="btn btn-es-primary btn-sm mt-20">
		<?php echo JText::_( 'APP_GROUP_TASKS_CREATE_FIRST_MILESTONE' ); ?>
	</a>
</div>
<?php } ?>
