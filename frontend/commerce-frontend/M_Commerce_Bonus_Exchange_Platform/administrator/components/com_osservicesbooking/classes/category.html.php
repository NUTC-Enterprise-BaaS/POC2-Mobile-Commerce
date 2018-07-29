<?php
/*------------------------------------------------------------------------
# category.php - Ossolution emailss Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class HTML_OSappscheduleCategory{
	/**
	 * List categories
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $keyword
	 */
	function listCategories($option,$rows,$pageNav,$keyword){
		global $mainframe,$_jversion,$configClass;
		JToolBarHelper::title(JText::_('OS_MANAGE_CATEGORIES'),'folder');
		JToolBarHelper::addNew('category_add');
		if(count($rows) > 0){
			JToolBarHelper::editList('category_edit');
			JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'category_remove');
			JToolBarHelper::publish('category_publish');
			JToolBarHelper::unpublish('category_unpublish');
		}
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=category_list" name="adminForm" id="adminForm">
			<table style="width: 100%;">
				<tr>
					<td align="right" width="100%">
						<input type="text" 	class="input-medium search-query" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo JText::_('OS_SEARCH');?>" />
                        <div class="btn-group">
                            <input type="submit" class="btn btn-warning"  value="<?php echo JText::_('OS_SEARCH');?>" />
                            <input type="reset"  class="btn btn-info"     value="<?php echo JText::_('OS_RESET');?>" onclick="this.form.keyword.value='';this.form.filter_state.value='';this.form.submit();" />
                        </div>
					</td>
				</tr>
			</table>
			<table class="adminlist table table-striped" width="100%">
				<thead>
					<tr>
						<th width="2%">#</th>
						<th width="3%">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="10%">
							<?php echo JText::_('OS_PHOTO');?>
						</th>
						<th width="50%">
							<?php echo JText::_('OS_CATEGORY_NAME');?>
						</th>
						<th width="10%" style="text-align:center;">
							<?php echo JText::_('OS_PUBLISHED'); ?>
						</th>
						<th width="5%" style="text-align:center;">
							ID
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td width="100%" colspan="5" style="text-align:center;">
							<?php
								echo $pageNav->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$checked = JHtml::_('grid.id', $i, $row->id);
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=category_edit&cid[]='. $row->id );
					$published 	= JHTML::_('jgrid.published', $row->published, $i, 'category_');
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $pageNav->getRowOffset( $i ); ?></td>
						<td align="center"><?php echo $checked; ?></td>
						<td align="center">
							<?php
							if($row->category_photo != ""){
								?>
								<img src="<?php echo JURI::root()?>images/osservicesbooking/category/<?php echo $row->category_photo?>" width="100" class="img-polaroid"/>
								<?php
							}else{
								?>
								<img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/no_image_available.png" width="100" class="img-polaroid"/>
								<?php
							}
							?>
						</td>
						<td align="left"><a href="<?php echo $link; ?>"><?php echo $row->category_name; ?></a></td>
						<td align="center" style="text-align:center;"><?php echo $published?></td>
						<td align="center" style="text-align:center;"><?php echo $row->id; ?></td>
					</tr>
					<?php
					$k = 1 - $k;	
				}
				?>
				</tbody>
			</table>
			<input type="hidden" name="option" value="<?php echo $option; ?>">
			<input type="hidden" name="task" value="category_list">
			<input type="hidden" name="boxchecked" value="0">
		</form>
		<?php
	}
	
	/**
	 * Edit category
	 *
	 * @param unknown_type $option
	 * @param unknown_type $row
	 */
	function editCategory($option,$row,$lists,$translatable){
		global $mainframe, $_jversion,$configClass,$languages;
		$db = JFactory::getDbo();
		JRequest::setVar( 'hidemainmenu', 1 );
		if ($row->id){
			$title = ' ['.JText::_('OS_EDIT').']';
		}else{
			$title = ' ['.JText::_('OS_NEW').']';
		}
		JToolBarHelper::title(JText::_('OS_CATEGORIES').$title,'folder');
		JToolBarHelper::save('category_save');
		JToolBarHelper::apply('category_apply');
		JToolBarHelper::cancel('category_cancel');
		$editor = &JFactory::getEditor();
		JHTML::_('behavior.tooltip');
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm" enctype="multipart/form-data">
		<?php 
		if ($translatable)
		{
		?>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('OS_GENERAL'); ?></a></li>
				<li><a href="#translation-page" data-toggle="tab"><?php echo JText::_('OS_TRANSLATION'); ?></a></li>									
			</ul>		
			<div class="tab-content">
				<div class="tab-pane active" id="general-page">			
		<?php	
		}
		?>
		<table class="admintable">
			<tr>
				<td class="key"><?php echo JText::_('OS_CATEGORY_NAME'); ?>: </td>
				<td >
					<input class="inputbox required" type="text" name="category_name" id="category_name" size="40" value="<?php echo $row->category_name?>" >
				</td>
			</tr>
			<tr>
				<td class="key" valign="top"><?php echo JText::_('Photo'); ?>: </td>
				<td >
					<?php
					if($row->category_photo != ""){
						?>
						<img src="<?php echo JURI::root()?>images/osservicesbooking/category/<?php echo $row->category_photo?>" width="150" class="img-polaroid" />
						<div style="clear:both;"></div>
						<input type="checkbox" name="remove_photo" id="remove_photo" value="0" onclick="javascript:changeValue('remove_photo')"  /> <?php echo JText::_('OS_REMOVE');?>
						<?php
					}
					?>
					<input type="file" name="image" id="image" class="input-small" />
				</td>
			</tr>
			<tr>
				<td class="key" valign="top"><?php echo JText::_('OS_CATEGORY_DESC'); ?>: </td>
				<td>
					<?php
					echo $editor->display( 'category_description',  $row->category_description , '95%', '250', '75', '20' ) ;
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><span class="hasTip" title="<?php echo JText::_('OS_SHOW_DESCRIPTION'); ?>::<?php  echo JText::_('OS_SHOW_DESCRIPTION_EXPLAIN')?>"><?php echo JText::_('OS_SHOW_DESCRIPTION'); ?>: </span></td>
				<td >
					<?php
					echo $lists['show_desc'];
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('OS_PUBLISHED'); ?>: </td>
				<td >
					<?php
					echo $lists['published'];
					?>
				</td>
			</tr>
		</table>
		<?php 
		if ($translatable)
		{
		?>
		</div>
			<div class="tab-pane" id="translation-page">
				<ul class="nav nav-tabs">
					<?php
						$i = 0;
						foreach ($languages as $language) {						
							$sef = $language->sef;
							?>
							<li <?php echo $i == 0 ? 'class="active"' : ''; ?>><a href="#translation-page-<?php echo $sef; ?>" data-toggle="tab"><?php echo $language->title; ?>
								<img src="<?php echo JURI::root(); ?>media/com_osproperty/flags/<?php echo $sef.'.png'; ?>" /></a></li>
							<?php
							$i++;	
						}
					?>			
				</ul>
				<div class="tab-content">			
					<?php	
						$i = 0;
						foreach ($languages as $language)
						{												
							$sef = $language->sef;
						?>
							<div class="tab-pane<?php echo $i == 0 ? ' active' : ''; ?>" id="translation-page-<?php echo $sef; ?>">													
								<table width="100%" class="admintable" style="background-color:white;">
									<tr>
										<td class="key"><?php echo JText::_('OS_CATEGORY_NAME'); ?>: </td>
										<td >
											<input class="inputbox required" type="text" name="category_name_<?php echo $sef; ?>" id="category_name_<?php echo $sef; ?>" size="40" value="<?php echo $row->{'category_name_'.$sef};?>" />
										</td>
									</tr>
									<tr>
										<td class="key" valign="top"><?php echo JText::_('OS_CATEGORY_DESC'); ?>: </td>
										<td>
											<?php
												echo $editor->display( 'category_description_'.$sef,  $row->{'category_description_'.$sef} , '95%', '250', '75', '20' ) ;
											?>
										</td>
									</tr>
								</table>
							</div>										
						<?php				
							$i++;		
						}
					?>
				</div>
			</div>
		<?php				
		}
		?>
		<input type="hidden" name="option" value="<?php echo $option?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="MAX_FILE_SIZE" value="9000000000" />
		</form>
		<?php
	}
}
?>