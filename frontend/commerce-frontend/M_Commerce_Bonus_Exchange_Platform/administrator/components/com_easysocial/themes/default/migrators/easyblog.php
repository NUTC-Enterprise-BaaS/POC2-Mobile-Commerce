<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<form name="adminForm" id="adminForm" class="migratorsForm" method="post" enctype="multipart/form-data">
<div class="row">
	<div class="col-md-6">
		<div class="panel" data-start-widget>
			<div class="panel-head">
				<b><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_READ_FIRST_TITLE' );?></b>
				<p><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_INSTRUCTION' ); ?></p>
			</div>

			<div class="panel-body">
				<ol>
					<li>
						<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_NEW_POST_STREAM' );?>
					</li>
					<li>
						<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_NEW_COMMENT_STREAM' );?>
					</li>
				</ol>

				<p class="mt-20"><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_ENSURE' ); ?></p>

				<ol class="mb-20">
					<li><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_UPGRADE_TO_LATEST' );?></li>
					<li><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_RUN_PRIVACY_RULE_SCAN' );?></li>
					<li><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_BACKUP_EXISTING_DB' );?></li>
					<li><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_SET_OFFLINE' );?></li>
				</ol>
				<hr>
				<?php if( $installed ){ ?>
						<a href="javascript:void(0);" class="btn btn-large btn-es-primary" data-start-migration><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_RUN_NOW' );?></a>
				<?php } else { ?>
					<div>
						<strong>
							<i class="icon-es-delete mr-5"></i>
							<span class="text-danger"><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_NOT_FOUND' ); ?></span>
						</strong>
					</div>
				<?php } ?>
				<hr>
				<div class="mt-20 small">
					<span class="label label-danger small"><?php echo JText::_( 'COM_EASYSOCIAL_FOOTPRINT_NOTE' );?>:</span>
					<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_FOOTNOTE' );?>
				</div>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-md-6">

		<div class="panel" data-migration-result style="display: none;">
			<div class="panel-head">
				<b><?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_RESULT' );?></b>
			</div>

			<div class="panel-body">
				<div class="es-progress-wrap">
					<div class="discoverProgress" stlye="display: none;">
						<div style="width: 0%;text-align:left;padding-left: 5px;" class="bar"></div>
						<div class="progress-result"></div>
					</div>
				</div>

				<a href="javascript:void(0);" class="viewLog btn btn-es-inverse btn-medium" style="display: none;">
					<?php echo JText::_( 'COM_EASYSOCIAL_VIEW_LOGS_BUTTON' );?>
				</a>

				<ul class="scannedResult es-scanned-result list-unstyled">
					<li class="empty">
						<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_EASYBLOG_NO_ITEM' ); ?>
					</li>
				</ul>
			</div>
		</div>

		<a href="<?php echo JRoute::_( 'index.php?option=com_easysocial&view=migrators&layout=easyblog' ); ?>" style="display: none;" data-jomsocial-back-button >
			<?php echo JText::_( 'COM_EASYSOCIAL_MIGRATOR_BACK_TO_EASYBLOG_PAGE' );?>
		</a>
	</div>
</div>
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="controller" value="migrators" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' );?>

</form>
