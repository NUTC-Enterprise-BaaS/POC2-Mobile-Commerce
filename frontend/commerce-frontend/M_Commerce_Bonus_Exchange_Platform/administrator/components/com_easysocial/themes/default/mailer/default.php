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
<form name="adminForm" id="adminForm" class="esForm" action="index.php" method="post" data-table-grid>
	<div class="app-filter filter-bar form-inline">
		<div class="form-group">
			<?php echo $this->html( 'filter.search' , $search ); ?>
		</div>

		<div class="form-group">
			<strong><?php echo JText::_( 'COM_EASYSOCIAL_FILTER_BY' ); ?> :</strong>
			<div>
				<select class="form-control input-sm" name="published" id="filterState" data-table-grid-filter>
					<option value="all"<?php echo $published == 'all' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_FILTER_SELECT_STATUS' ); ?></option>
					<option value="1"<?php echo $published === 1 ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_MAILER_SENT' ); ?></option>
					<option value="0"<?php echo $published === 0 ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_MAILER_PENDING' ); ?></option>
					<option value="2"<?php echo $published === 2 ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_MAILER_SENDING' ); ?></option>
				</select>
			</div>
		</div>
		<div class="form-group pull-right">
			<div><?php echo $this->html( 'filter.limit' , $limit ); ?></div>
		</div>
	</div>

	<div class="app-filter filter-bar">
		<strong>
			<i class="icon-es-help mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_MAILER_DESCRIPTION' ); ?> <a href="http://stackideas.com/docs/easysocial/administrators/cronjobs/cronjobs" target="_blank"><?php echo JText::_( 'COM_EASYSOCIAL_LEARN_MORE' ); ?></a>
		</strong>
	</div>

	<div class="panel-table">
        <table class="app-table table table-eb table-striped" data-mailer-list>
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="toggle" class="checkAll" data-table-grid-checkall />
					</th>
					<th>
						<?php echo $this->html( 'grid.sort' , 'title' , JText::_( 'COM_EASYSOCIAL_MAILER_EMAIL_TITLE' ) , $ordering , $direction ); ?>
					</th>
					<th width="20%" class="center">
						<?php echo $this->html( 'grid.sort' , 'recipient_email' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_RECIPIENT' ) , $ordering , $direction ); ?>
					</th>
					<th width="5%" class="center">
						<?php echo $this->html( 'grid.sort' , 'priority' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_PRIORITY' ) , $ordering , $direction ); ?>
					</th>
					<th width="5%" class="center">
						<?php echo $this->html( 'grid.sort' , 'state' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_STATE' ) , $ordering , $direction ); ?>
					</th>
					<th width="10%" class="center">
						<?php echo $this->html( 'grid.sort' , 'created' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_CREATED' ) , $ordering , $direction ); ?>
					</th>
					<th width="5%" class="center">
						<?php echo $this->html( 'grid.sort' , 'id' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ) , $ordering , $direction ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if( $emails ){ ?>

					<?php $i = 0; ?>
					<?php foreach( $emails as $email ){ ?>
					<tr data-mailer-item data-id="<?php echo $email->id;?>">
						<td class="center">
							<?php echo $this->html( 'grid.id' , $i , $email->id ); ?>
						</td>
						<td>
							<a href="javascript:void(0);" data-mailer-item-preview><?php echo $email->title; ?></a>
						</td>
						<td class="center">
							<a href="mailto:<?php echo $email->recipient_email;?>" target="_blank"><?php echo $email->recipient_email;?></a>
						</td>
						<td class="center">
							<i class="fa fa-flag  priority-<?php echo $email->priority;?>"
								data-es-provide="tooltip"
								data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_MAILER_PRIORITY_' . $email->priority , true ); ?>"
								data-placement="bottom"
							></i>
						</td>
						<td class="center">
							<?php echo $this->html( 'grid.published' , $email , 'mailer' , 'state' ); ?>
						</td>
						<td class="center">
							<?php echo $email->created; ?>
						</td>
						<td class="center">
							<?php echo $email->id; ?>
						</td>
					</tr>
					<?php $i++; ?>
					<?php } ?>

				<?php } else { ?>
				<tr class="is-empty">
					<td colspan="8" class="empty">
						<?php echo JText::_( 'COM_EASYSOCIAL_MAILER_NO_EMAILS_YET' ); ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="8">
						<div class="footer-pagination">
						<?php echo $pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>


<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="ordering" value="<?php echo $ordering;?>" data-table-grid-ordering />
<input type="hidden" name="direction" value="<?php echo $direction;?>" data-table-grid-direction />
<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
<input type="hidden" name="task" value="" data-table-grid-task />
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="view" value="mailer" />
<input type="hidden" name="controller" value="mailer" />
</form>
