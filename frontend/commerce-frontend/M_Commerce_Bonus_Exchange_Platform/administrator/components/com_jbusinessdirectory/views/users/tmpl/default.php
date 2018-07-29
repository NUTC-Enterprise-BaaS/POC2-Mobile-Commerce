<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= true;
$saveOrder	= $listOrder == 'p.ordering';
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task != 'companies.delete' || confirm('<?php echo JText::_('COM_JBUSINESS_DIRECTORY_OFFERS_CONFIRM_DELETE', true);?>')) {
			Joomla.submitform(task);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory&view=users');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left fltlft">
				<label class="filter-search-lbl element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="filter-select pull-right fltrt btn-group">
				<select name="filter_status_id" class="inputbox input-medium" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('LNG_JOPTION_SELECT_STATUS');?></option>
					<?php echo JHtml::_('select.options', $this->statuses, 'value', 'text', $this->state->get('filter.status_id'));?>
				</select>
			</div>
		</div>
	</div>
	<div class="clr clearfix"></div>
	<table class="table table-striped adminlist" id="itemList">
		<thead>
			<tr>
				<th width="1%" class="hidden-phone">#</th>
				<th width="1%" class="hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th nowrap="nowrap" width='23%' ><?php echo JHtml::_('grid.sort', '名稱', 'p.name', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('會員 QR code')?></th>
				<th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('特約 QR code')?></th>
				<th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('優惠 QR code')?></th>
				<th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('電話')?></th>
				<!-- <th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('帳號')?></th> -->
				<th class="hidden-phone" nowrap="nowrap" width='23%' ><?php echo JText::_('Email')?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', '點數', '', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', '業務 PV', '', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', '會員群組', '', $listDirn, $listOrder); ?></th>
				<th nowrap="nowrap" width='10%' ><?php echo JHtml::_('grid.sort', '推薦人', '', $listDirn, $listOrder); ?></th>
				<th class="hidden-phone" nowrap="nowrap" width='1%' ><?php echo JHtml::_('grid.sort', 'LNG_ID', 'p.id', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<?php
			   //取出使用者點數
			   $db = JFactory::getDbo();
	           $query = $db->getQuery(true);
	            $query
			    ->select(array('a.user_id', 'a.points', 'b.id'))
			    ->from($db->quoteName('#__social_points_history', 'a'))
			    ->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.user_id') . ' = ' . $db->quoteName('b.id') . ')');
	            $db->setQuery($query);
	            $pointHistorys = $db->loadObjectList();
	            $num = 0;
	            foreach ($pointHistorys as $key => $pointHistory) {
	            	$user_points = $pointHistory->points;
                    $user_id = $pointHistory->user_id ;

                    if (!isset($value[$user_id])) {
                        $value[$user_id]=0;
                    }
                    $value[$user_id] = $value[$user_id] + $user_points;
                }
               //若為業務 取出 業務PV
			   $db = JFactory::getDbo();
	           $query = $db->getQuery(true);
	            $query
			    ->select(array('a.user_id', 'a.points', 'b.id'))
			    ->from($db->quoteName('#__business_points_history', 'a'))
			    ->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.user_id') . ' = ' . $db->quoteName('b.id') . ')');
	            $db->setQuery($query);
	            $pvHistorys = $db->loadObjectList();
	            $num = 0;
	            foreach ($pvHistorys as $key => $pvHistory) {
	            	$userPv_points = $pvHistory->points;
                    $userPv_id = $pvHistory->user_id ;

                    if (!isset($valuePv[$userPv_id])) {
                        $valuePv[$userPv_id]=0;
                    }
                    $valuePv[$userPv_id] = $valuePv[$userPv_id] + $userPv_points;
                }
                //取出會員電話
                $db = JFactory::getDbo();
	            $query = $db->getQuery(true);
	            $query
			    ->select(array('a.user_id', 'a.profile_value', 'b.id'))
			    ->from($db->quoteName('#__user_profiles', 'a'))
			    ->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.user_id') . ' = ' . $db->quoteName('b.id') . ')')
			    ->where($db->quoteName('ordering') . ' = ' . 2);
	            $db->setQuery($query);
	            $userPhones = $db->loadObjectList();
	            foreach ($userPhones as $key => $userPhone) {
	            	$phone[$userPhone->user_id] = $userPhone->profile_value;
	            }
	            //取出特約店家ID
	            $db = JFactory::getDbo();
	            $query = $db->getQuery(true);
	            $query
			    ->select(array('a.userId', 'a.name', 'a.id'))
			    ->from($db->quoteName('#__jbusinessdirectory_companies', 'a'))
			    ->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.userId') . ' = ' . $db->quoteName('b.id') . ')')
			    ->where($db->quoteName('shop_class') . ' = ' . 1);
	            $db->setQuery($query);
	            $userSpes = $db->loadObjectList();
	            foreach ($userSpes as $key => $userSpe) {
	            	$speStore[$userSpe->userId] = $userSpe->id;
	            }
	            //取出優惠店家ID
	            $db = JFactory::getDbo();
	            $query = $db->getQuery(true);
	            $query
			    ->select(array('a.userId', 'a.name', 'a.id'))
			    ->from($db->quoteName('#__jbusinessdirectory_companies', 'a'))
			    ->join('INNER', $db->quoteName('#__users', 'b') . ' ON (' . $db->quoteName('a.userId') . ' = ' . $db->quoteName('b.id') . ')')
			    ->where($db->quoteName('shop_class') . ' = ' . 2);
	            $db->setQuery($query);
	            $userPres = $db->loadObjectList();
	            foreach ($userPres as $key => $userPre) {
	            	$preStore[$userPre->userId] = $userPre->id;
	            }
	            //取出會員群組
	            $db = JFactory::getDbo();
	            $query = $db->getQuery(true);
	            $query
			    ->select(array('a.id', 'a.title', 'b.user_id', 'b.group_id'))
			    ->from($db->quoteName('#__usergroups', 'a'))
			    ->join('INNER', $db->quoteName('#__user_usergroup_map', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.group_id') . ')');
	            $db->setQuery($query);
	            $userGroups = $db->loadObjectList();
	            foreach ($userGroups as $key => $userGroup) {
	            	$memberGroup[$userGroup->user_id] = $userGroup->title;
	            }
	            //取出會員推薦人
                $db = JFactory::getDbo();
	            $query = $db->getQuery(true);
	            $query
			    ->select(array('a.user_id', 'a.profile_value', 'b.phone'))
			    ->from($db->quoteName('#__user_profiles', 'a'))
			    ->join('INNER', $db->quoteName('#__jbusinessdirectory_users', 'b') . ' ON (' . $db->quoteName('a.profile_value') . ' = ' . $db->quoteName('b.id') . ')')
			    ->where($db->quoteName('ordering') . ' = ' . 4);
	            $db->setQuery($query);
	            $userRecommends = $db->loadObjectList();
	            foreach ($userRecommends as $key => $userRecommend) {
	            	$userRecommendPhone[$userRecommend->user_id] = $userRecommend->phone;
	            }
		 ?>
		<tbody>
			<?php $nrcrt = 1; $i=0;
			$count = count($this->items);
			foreach($this->items as $item) {
				$ordering  = ($listOrder == 'p.ordering');
				$canCreate  = true;
				$canEdit    = true;
				$canChange  = true;
			?>
				<TR class="row<?php echo $i % 2; ?>">
					<TD class="center hidden-phone"><?php echo $nrcrt++?></TD>
					<TD align="center" class="hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</TD>
					<TD align="left"><a
						href='<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=user.edit&id='. $item->id )?>'
						title="<?php echo JText::_('LNG_CLICK_TO_EDIT'); ?>"> <B><?php echo $item->name?>
						</B>
					</a>
					</TD>

					<td class="hidden-phone">
						<img src="<?php echo "http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=http%3A//ginkerapp.com/gobuyreg.html%3FitemId%3D" . $item->id ."&chld=H|0"; ?>"/>
					</td>
					<td class="hidden-phone">
						<?php if (isset($speStore[$item->id])) { ?>
						<img src="<?php echo "http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=" .$speStore[$item->id] ."&chld=H|0"; ?>"/>
						<?php } ?>
					</td>
					<td class="hidden-phone">
						<?php if (isset($preStore[$item->id])) { ?>
						<img src="<?php echo "http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=" .$preStore[$item->id] ."&chld=H|0"; ?>"/>
						<?php } ?>
					</td>
					<td>
						<?php if (isset($phone[$item->id])) {
							echo $phone[$item->id];
						} ?>
					</td>
					<!-- <td>
						<?php echo $item->username ?>
					</td> -->
					<td>
						<?php echo $item->email ?>
					</td>
					<td>
						<?php
							if (!isset($value[$item->id])) {
								echo "0";
							}
							echo @$value[$item->id];
						 ?>
					</td>
					<td>
						<?php
							if (!isset($valuePv[$item->id])) {
								echo "0";
							}
							echo @$valuePv[$item->id];
						 ?>
					</td>
					<td>
						<?php
							echo $memberGroup[$item->id];
						 ?>
					</td>
					<td>
						<?php
                            if (!isset($userRecommendPhone[$item->id])) {
                                echo '';
                            } else {
                                echo $userRecommendPhone[$item->id];
                            }
                        ?>
					</td>
					<!-- 改變狀態 -->
					<!-- <td valign="top" align="center">
						<img
							src="<?php echo JURI::base() ."components/".JBusinessUtil::getComponentName()."/assets/img/".($item->block==0? "unchecked.gif" : "checked.gif")?>"
							onclick="document.location.href = '<?php echo JRoute::_( 'index.php?option=com_jbusinessdirectory&task=users.chageState&id='. $item->id )?> '"
						/>
					</td> -->
					<td class="hidden-phone">
						<?php echo $item->id?>
					</td>
				</TR>
			<?php
			$i++;
			} ?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo JBusinessUtil::getComponentName()?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>