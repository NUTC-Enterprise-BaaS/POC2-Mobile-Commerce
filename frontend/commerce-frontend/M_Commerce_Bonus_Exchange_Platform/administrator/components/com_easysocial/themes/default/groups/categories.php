<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form method="post" name="adminForm" class="esForm" id="adminForm" data-table-grid>

	<div class="app-filter filter-bar form-inline<?php echo $callback ? ' mt-20' : '';?>">
		<div class="form-group">
			<?php echo $this->html('filter.search', $search); ?>
		</div>

		<?php if (!$callback) { ?>
		<div class="form-group">
			<strong><?php echo JText::_('COM_EASYSOCIAL_FILTER_BY'); ?> :</strong>
			<div><?php echo $this->html('filter.published', 'state', $state); ?></div>
		</div>

		<div class="form-group pull-right">
			<div><?php echo $this->html('filter.limit', $limit); ?></div>
		</div>
		<?php } ?>
	</div>

	<div class="panel-table">
		<table class="app-table table table-eb table-striped">
			<thead>
				<tr>
					
					<?php if (!$callback) { ?>
					<th width="1%" class="center">
						<input type="checkbox" name="toggle" data-table-grid-checkall />
					</th>
					<?php } ?>

					<th style="text-align: left;">
						<?php echo $this->html('grid.sort', 'title' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_TITLE' ) , $ordering , $direction ); ?>
					</th>

					<?php if( !$callback ){ ?>
					<th class="center" width="10%">
						<?php echo $this->html( 'grid.sort' , 'state' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_STATUS' ) , $ordering , $direction ); ?>
					</th>

					<th class="center" width="5%">
						<?php echo JText::_('COM_EASYSOCIAL_TABLE_COLUMN_GROUPS');?>
					</th>

					<th class="center" width="10%">
						<?php echo $this->html( 'grid.sort' , 'ordering' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ORDERING' ) , $ordering , $direction ); ?>
					</th>

					<th width="15%" class="center">
						<?php echo $this->html( 'grid.sort' , 'created' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_CREATED' ) , $ordering , $direction ); ?>
					</th>
					<?php } ?>

					<th width="<?php echo $callback ? '10%' : '5%';?>" class="center">
						<?php echo $this->html( 'grid.sort' , 'id' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ) , $ordering , $direction ); ?>
					</th>
				</tr>
			</thead>
			<tbody>

				<?php if( $categories ){ ?>
					<?php $i = 0; ?>
					<?php foreach( $categories as $category ){ ?>
					<tr class="row<?php echo $i; ?>"
						data-profiles-item
						data-grid-row
						data-title="<?php echo $this->html( 'string.escape' , $category->get( 'title' ) );?>"
						data-id="<?php echo $category->id;?>"
					>
						<?php if( !$callback ){ ?>
						<td align="center">
							<?php echo $this->html( 'grid.id' , $i , $category->id ); ?>
						</td>
						<?php } ?>

						<td>
							<a href="<?php echo FRoute::_( 'index.php?option=com_easysocial&view=groups&layout=categoryForm&id=' . $category->id );?>"
								data-category-insert
								data-id="<?php echo $category->id;?>"
								data-avatar="<?php echo $category->getAvatar();?>"
								data-alias="<?php echo $category->alias;?>"
								data-title="<?php echo $this->html( 'string.escape' , $category->get( 'title' ) );?>">
								<?php echo $category->get( 'title' ); ?></a>
						</td>

						<?php if( !$callback ){ ?>
						<td class="center">
							<?php echo $this->html( 'grid.published' , $category , 'groups' , '' , array( 'publishCategory' , 'unpublishCategory' ) ); ?>
						</td>
						<td class="center">
							<?php echo $category->getTotalNodes();?>
						</td>

						<td class="order center">
							<?php echo $this->html('grid.ordering', count($categories), ($i + 1), $ordering == 'ordering',  $category->ordering); ?>
						</td>
						<?php } ?>

						<?php if (!$callback) { ?>
						<td class="center">
							<?php echo $category->created; ?>
						</td>
						<?php } ?>

						<td class="center">
							<?php echo $category->id;?>
						</td>
					</tr>
						<?php $i++; ?>
					<?php } ?>
				<?php } else { ?>
					<tr class="is-empty">
						<td colspan="8" class="center empty">
							<?php echo JText::_('COM_EASYSOCIAL_GROUPS_NO_GROUPS_AVAILABLE');?>
						</td>
					</tr>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="8" class="center">
						<div class="footer-pagination"><?php echo $pagination->getListFooter(); ?></div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="ordering" value="<?php echo $ordering;?>" data-table-grid-ordering />
	<input type="hidden" name="direction" value="<?php echo $direction;?>" data-table-grid-direction />
	<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
	<input type="hidden" name="task" value="" data-table-grid-task />
	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="view" value="groups" />
	<input type="hidden" name="layout" value="<?php echo $layout;?>" />
	<input type="hidden" name="controller" value="groups" />
</form>
