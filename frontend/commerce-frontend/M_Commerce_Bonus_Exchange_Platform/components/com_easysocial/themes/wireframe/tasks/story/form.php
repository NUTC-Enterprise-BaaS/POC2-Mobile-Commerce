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
<div class="es-story-tasks-form">
	<div class="es-story-tasks-textbox">
		<ul class="fd-reset-list tasks-list mb-10" data-story-tasks-list>
			<li data-story-tasks-form>
				<input type="text" class="input-sm form-control" data-story-tasks-input placeholder="<?php echo JText::_('APP_GROUP_TASKS_STORY_TITLE_PLACEHOLDER', true );?>" />
			</li>
		</ul>

		<div class="row">
			<div class="col-sm-6">
				<select class="form-control input-sm" data-story-tasks-milestone>
					<?php foreach ($milestones as $milestone) { ?>
					<option value="<?php echo $milestone->id;?>"><?php echo $milestone->title;?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-6">
				<?php echo $this->html('form.calendar', 'due'); ?>
			</div>
		</div>
	</div>
</div>
