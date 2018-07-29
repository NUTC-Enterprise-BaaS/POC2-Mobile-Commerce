<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

if (JVERSION > '3.0')
{
	JHtml::_('formbehavior.chosen', 'select');
}

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canOrder   = $user->authorise('core.edit.state', 'com_socialads');
$saveOrder  = $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_socialads&task=zones.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'zoneList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();

if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}
if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php
else : ?>
	<div id="j-main-container">
<?php
endif;
?>
	<div class="<?php echo SA_WRAPPER_CLASS;?>" id = "sa-zone">
		<form action="<?php echo JRoute::_('index.php?option=com_socialads&view=zones'); ?>" method="post" name="adminForm" id="adminForm">
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_SOCIALADS_ZONES_FILTER_SEARCH'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_SOCIALADS_ZONES_FILTER_SEARCH'); ?>" />
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
						<i class="icon-search"></i>
					</button>
					<button class="btn hasTooltip" id="clear-search-button" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
						<i class="icon-remove"></i>
					</button>
				</div>
				<?php
				if (JVERSION >= '3.0') : ?>
					<div class="btn-group pull-right hidden-phone">
						<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php
				endif; ?>
				<div class="btn-group pull-right hidden-phone">
					<?php echo JHtml::_('select.genericlist', $this->publish_states, "filter_published", 'class="input-medium" size="1" onchange="document.adminForm.submit();" name="filter_published"', "value", "text", $this->state->get('filter.state'));
					?>
				</div>
			</div>
			<div class="clearfix"> </div>
			<?php
			if (empty($this->items)) : ?>
				<div class="clearfix">&nbsp;</div>
				<div class="alert alert-no-items">
					<?php echo JText::_('COM_SOCIALADS_FILTER_SEARCH_NOT_FOUND'); ?>
				</div>
			<?php
			else : ?>
				<div id = "no-more-tables">
					<table class="table table-striped" id="zoneList">
						<thead>
							<tr>
							<!--extra code -->
								<th width="2%" class="title"> </th>
								<th width="2%" align="center" class="title"></th>
								<th class="title" align="left" width="8%"></th>
								<th width="5%" class="title" ></th>
								<th width="6%" class="title"></th>
								<th width="6%" class="title" ></th>
								<th width="6%" class="title"></th>
								<th width="6%" class="title"></th>
								<th width="8%" colspan="2" >
									<?php echo JText::_( 'COM_SOCIALADS_ZONES_MAX_CHAR'); ?>
								</th>
								<th width="8%" colspan="2" class="title">
									<?php echo JText::_( 'COM_SOCIALADS_ZONES_IMG_DIM'); ?>
								</th>
								<th width="9%" colspan="3" class = "center">
									<?php echo JText::_( 'COM_SOCIALADS_ZONES_PRI'); ?>
								</th>
								<th width="2%" class="title" nowrap="nowrap"></th>
							</tr>
							<tr>
								<?php
								if (isset($this->items[0]->ordering)): ?>
									<th width="1%" class="nowrap center hidden-phone">
										<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
									</th>
								<?php
								endif; ?>
								<th width="1%" class="hidden-phone">
									<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
								</th>
								<?php
								if (isset($this->items[0]->state)): ?>
									<th width="1%" class="nowrap center">
										<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
									</th>
								<?php
								endif; ?>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_ZONE_NAME', 'a.zone_name', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_ORIENTATION', 'a.orientation', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_AD_TYPE', 'a.ad_type', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_LAYOUT', 'a.layout', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_NUM_ADS', 'a.num_ads', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_MAX_TITLE', 'a.max_title', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_MAX_DES', 'a.max_des', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_IMG_WIDTH', 'a.img_width', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_IMG_HEIGHT', 'a.img_height', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_PER_CLICK', 'a.per_click', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_PER_IMP', 'a.per_imp', $listDirn, $listOrder); ?>
								</th>
								<th class='left'>
									<?php echo JHtml::_('grid.sort',  'COM_SOCIALADS_ZONES_PER_DAY', 'a.per_day', $listDirn, $listOrder); ?>
								</th>
								<?php
								if (isset($this->items[0]->id)): ?>
									<th width="1%" class="nowrap center hidden-phone">
										<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
									</th>
								<?php
								endif; ?>
							</tr>
						</thead>
						<tfoot>
							<?php
							if (isset($this->items[0]))
							{
								$colspan = count(get_object_vars($this->items[0]));
							}
							else
							{
								$colspan = 10;
							}
							?>
							<tr>
								<td colspan="<?php echo $colspan ?>">
									<?php echo $this->pagination->getListFooter(); ?>
								</td>
							</tr>
						</tfoot>
						<tbody>
							<?php
							foreach ($this->items as $i => $item) :
								$ordering   = ($listOrder == 'a.ordering');
								$canCreate	= $user->authorise('core.create',		'com_socialads');
								$canEdit	= $user->authorise('core.edit',			'com_socialads');
								$canCheckin	= $user->authorise('core.manage',		'com_socialads');
								$canChange	= $user->authorise('core.edit.state',	'com_socialads');
								$model = $this->getModel('zones');
								$adcount = 0;
								$adcount = $model->getZoneaddatacount($item->id); ?>
								<tr class="row<?php echo $i % 2; ?>">
									<?php
									if (isset($this->items[0]->ordering)): ?>
										<td class="order nowrap center hidden-phone">
											<?php
											if ($canChange) :
												$disableClassName = '';
												$disabledLabel	  = '';
												if (!$saveOrder) :
													$disabledLabel    = JText::_('JORDERINGDISABLED');
													$disableClassName = 'inactive tip-top';
												endif; ?>
												<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
													<i class="icon-menu"></i>
												</span>
												<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
											<?php
											else : ?>
												<span class="sortable-handler inactive" >
													<i class="icon-menu"></i>
												</span>
											<?php
											endif; ?>
										</td>
									<?php
									endif; ?>
									<td>
										<?php echo JHtml::_('grid.id', $i, $item->id); ?>
									</td>
									<?php
									if (isset($this->items[0]->state)): ?>
										<td class="center" data-title="<?php echo JText::_('JSTATUS');?>">
											<?php echo JHtml::_('jgrid.published', $item->state, $i, 'zones.', $canChange, 'cb'); ?>
										</td>
									<?php
									endif; ?>
									<td class = "nowrap" data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_ZONE_NAME');?>">
										<?php
										if (isset($item->checked_out) && $item->checked_out) : ?>
											<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'zones.', $canCheckin); ?>
										<?php
										endif; ?>
										<?php
										if ($canEdit) : ?>
											<a href="<?php echo JRoute::_('index.php?option=com_socialads&task=zone.edit&id='.(int) $item->id); ?>">
												<?php echo $this->escape($item->zone_name); ?>
											</a>
											<?php
											if (!in_array($item->id,$this->modules))
											{?>
												<a href="index.php?option=com_modules&filter_module=mod_socialads" target="_blank">
													<span class="" ><img alt="Missing" title="<?php echo JText::_('COM_SOCIALADS_ZONES_MODULE_IS_NOT_ASSIGNED'); ?>"
													src="<?php echo JUri::root(true);?>/media/com_sa/images/missing.png">
													</span>
												</a>
											<?php
											} ?>
											<?php
											else :
													echo $this->escape($item->zone_name);
											endif; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_ORIENTATION');?>">
										<?php
										if ($item->orientation=="1")
											$orientation=JText::_("COM_SOCIALADS_ZONES_HORIZONTAL");
										elseif ($item->orientation=="2")
											$orientation=JText::_("COM_SOCIALADS_ZONES_VERTICAL");
										echo $orientation;?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_AD_TYPE');?>">
										<?php $item->ad_type = str_replace('||',',',$item->ad_type);
										$item->ad_type = str_replace('|','',$item->ad_type);
										$ad_type= explode(',',$item->ad_type);?>
										<?php echo $item->ad_type; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_LAYOUT');?>">
										<?php echo str_replace('|',',',$item->layout); ?>
									</td>
									<td class = "center hidden-phone" data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_NUM_ADS');?>">
										<?php echo $adcount;
										$adnm="no_of_ads".$i; ?>
										<input type="hidden" name="<?php echo $adnm; ?>" id="<?php echo $adnm; ?>" value="<?php echo $adcount; ?>">
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_MAX_TITLE');?>">
										<?php echo $item->max_title; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_MAX_DES');?>">
										<?php echo $item->max_des; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_IMG_WIDTH');?>">
										<?php echo $item->img_width; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_IMG_HEIGHT');?>">
										<?php echo $item->img_height; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_PER_CLICK');?>">
										<?php echo $item->per_click; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_PER_IMP');?>">
										<?php echo $item->per_imp; ?>
									</td>
									<td data-title="<?php echo JText::_('COM_SOCIALADS_ZONES_PER_DAY');?>">
										<?php echo $item->per_day; ?>
									</td>
									<?php
									if (isset($this->items[0]->id)): ?>
										<td class="center hidden-phone">
											<?php echo (int) $item->id; ?>
										</td>
									<?php
									endif; ?>
								</tr>
							<?php
							endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php
			endif; ?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

<script type="text/javascript">
	saAdmin.initSaJs();
	var cblength=<?php echo count( $this->items ); ?>;
	var tjListOrderingColumn = "<?php echo $listOrder; ?>";
	Joomla.submitbutton = function(action){saAdmin.zones.submitButtonAction(action);}
</script>
