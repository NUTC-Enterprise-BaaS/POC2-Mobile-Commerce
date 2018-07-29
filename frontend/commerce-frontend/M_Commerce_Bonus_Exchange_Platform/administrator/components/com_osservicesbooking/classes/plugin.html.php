<?php
/*------------------------------------------------------------------------
# plugin.php - Ossolution emailss Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class HTML_OSappschedulePlugin{
	/**
	 * List plugins
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 */
	function listPlugins($option,$rows,$pageNav,$lists){
		global $mainframe,$configClass;
		JToolBarHelper::title(JText::_('OS_MANAGE_PAYMENT_PLUGINS'),'list');
		JToolBarHelper::deleteList(JText::_('OS_ARE_YOU_SURE_TO_REMOVE_ITEMS'),'plugin_remove');
		JToolBarHelper::publish('plugin_publish');
		JToolBarHelper::unpublish('plugin_unpublish');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		$ordering = ($lists['order'] == 'ordering');
		?>
		<form action="index.php?option=com_osservicesbooking&view=plugins&type=0" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm">
		<table width="100%">
			<tr>
				<td align="left"  width="100%">
					<input type="text" placeholder="<?php echo JText::_('OS_SEARCH');?>" name="keyword" id="keyword" value="<?php echo JRequest::getVar('keyword','') ;?>" class="input-medium search-query" onchange="document.adminForm.submit();" />
                    <div class="btn-group">
                        <button onclick="this.form.submit();" class="btn btn-warning"><?php echo JText::_( 'OS_SEARCH' ); ?></button>
                        <button onclick="document.getElementById('keyword').value='';this.form.submit();" class="btn btn-info"><?php echo JText::_( 'OS_RESET' ); ?></button>
                        </div>
				</td>	
			</tr>
		</table>
		<div id="editcell">
			<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="5">
						#
					</th>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
					</th>
					<th class="title">
						<?php echo JText::_('OS_PLUGIN_NAME'); ?>
					</th>
					<th class="title" width="20%">
						<?php echo JText::_('OS_PLUGIN_TITLE'); ?>
					</th>			
					<th class="title">
						<?php echo JText::_('OS_PLUGIN_AUTHOR'); ?>
					</th>			
					<th class="title">
						<?php echo JText::_('OS_PLUGIN_EMAIL'); ?>
					</th>	
					<th style="text-align:center;">
						<?php echo JText::_('OS_PUBLISHED'); ?>
					</th>
					<th width="10%" nowrap="nowrap">
						<?php echo JHTML::_('grid.sort',  'OS_ORDER', 'ordering', $lists['order_Dir'], $lists['order'] ); ?>
						<?php echo JHTML::_('grid.order',  $rows , 'filesave.png', 'save_plugin_order' ); ?>
					</th>												
					<th style="text-align:center;">
						<?php echo JHTML::_('grid.sort', JText::_('OS_ID') , 'id', $lists['order_Dir'], $lists['order'] ); ?>
					</th>
				</tr>		
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<?php echo $pageNav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $rows ); $i < $n; $i++)
			{
				$row = &$rows[$i];
				$link 	= JRoute::_( 'index.php?option=com_osservicesbooking&task=plugin_edit&cid[]='. $row->id );
				$checked 	= JHTML::_('grid.id',   $i, $row->id );				
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'plugin_' );		
		
				//$img 	= $row->support_recurring_subscription ? 'tick.png' : 'publish_x.png';
				//$img = JHTML::_('image','admin/'.$img, '', array('border' => 0), true);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $pageNav->getRowOffset( $i ); ?>
					</td>
					<td>
						<?php echo $checked; ?>
					</td>	
					<td>
						<a href="<?php echo $link; ?>">
							<?php echo $row->name; ?>
						</a>
					</td>
					<td>
						<?php echo $row->title; ?>
					</td>												
					<td>
						<?php echo $row->author; ?>
					</td>
					<td align="center">
						<?php echo $row->author_email;?>
					</td>
					<td align="center" style="text-align:center;">
						<?php echo $published ; ?>
					</td>			
					<td class="order" style="text-align:right;">
						<span><?php echo $pageNav->orderUpIcon( $i, true,'plugin_orderup', 'Move Up', $ordering ); ?></span>
						<span><?php echo $pageNav->orderDownIcon( $i, $n, true, 'plugin_orderdown', 'Move Down', $ordering ); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?> class="input-mini" style="text-align: center;width:15px;" />
					</td>			
					<td align="center"  style="text-align:center;">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>
			<table class="adminform" style="margin-top: 50px;">
				<tr>
					<td>
						<fieldset class="adminform">
							<legend><?php echo JText::_('OS_INSTALL_NEW_PLUGIN'); ?></legend>
							<table>
								<tr>
									<td>
										<input type="file" name="plugin_package" id="plugin_package" size="50" class="inputbox" /> <input type="button" class="btn btn-info" value="<?php echo JText::_('OS_INSTALL'); ?>" onclick="installPlugin();" />
									</td>
								</tr>
							</table>					
						</fieldset>
					</td>
				</tr>		
			</table>
			</div>
			<input type="hidden" name="option" value="com_osservicesbooking" />
			<input type="hidden" name="task" value="" id="task" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo JHTML::_( 'form.token' ); ?>				 
			<script type="text/javascript">
				function installPlugin() {
					var form = document.adminForm ;
					if (form.plugin_package.value =="") {
						alert("<?php echo JText::_('OS_CHOOSE_PLUGIN'); ?>");
						return ;	
					}
					
					form.task.value = 'plugin_install' ;
					form.submit();
				}
			</script>
		</form>
		<?php
	}
	
	
	/**
	 * Edit plugin
	 *
	 * @param unknown_type $option
	 * @param unknown_type $item
	 * @param unknown_type $params
	 */
	function editPlugin($option,$item,$lists,$form){
		global $mainframe;
		JHTML::_('behavior.tooltip');
		if($item->id > 0){
			$type = "[".JText::_('OS_EDIT')."]";
		}else{
			$type = "[".JText::_('OS_ADD')."]";
		}
		JToolBarHelper::title(JText::_('OS_PLUGIN')." ".$type,'folder');
		JToolBarHelper::save('plugin_save');
		JToolBarHelper::apply('plugin_apply');
		JToolBarHelper::cancel('plugin_gotolist');
		?>
		<script language="javascript" type="text/javascript">
			<?php
				if (version_compare(JVERSION, '1.6.0', 'ge')) {
				?>
					Joomla.submitbutton = function(pressbutton)
					{
						var form = document.adminForm;
						if (pressbutton == 'plugin.cancel') {
							Joomla.submitform(pressbutton, form);
							return;				
						} else {
							//Validate the entered data before submitting													
							Joomla.submitform(pressbutton, form);
						}								
					}
				<?php	
				} else {
				?>
					function submitbutton(pressbutton) {
						var form = document.adminForm;
						if (pressbutton == 'cancel_plugin') {
							submitform( pressbutton );
							return;				
						} else {
							submitform( pressbutton );
						}
					}	
				<?php	
				}
			?>	
		</script>
		<form action="index.php" method="post" name="adminForm" id="adminForm">
		<div class="col" style="float:left; width:65%">
			<fieldset class="adminform">
				<legend><?php echo JText::_('OS_PLUGIN_DETAIL'); ?></legend>
					<table class="admintable adminform">
						<tr>
							<td width="100" align="right" class="key">
								<?php echo  JText::_('OS_NAME'); ?>
							</td>
							<td>
								<?php echo $item->name ; ?>
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
								<?php echo  JText::_('OS_TITLE'); ?>
							</td>
							<td>
								<input class="text_area" type="text" name="title" id="title" size="40" maxlength="250" value="<?php echo $item->title;?>" />
							</td>
						</tr>					
						<tr>
							<td class="key">
								<?php echo JText::_('OS_AUTHOR'); ?>
							</td>
							<td>
								<input class="text_area" type="text" name="author" id="author" size="40" maxlength="250" value="<?php echo $item->author;?>" />
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('OS_CREATION_DATE'); ?>
							</td>
							<td>
								<?php echo $item->creation_date; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('OS_COPYRIGHT') ; ?>
							</td>
							<td>
								<?php echo $item->copyright; ?>
							</td>
						</tr>	
						<tr>
							<td class="key">
								<?php echo JText::_('OS_LICENSE'); ?>
							</td>
							<td>
								<?php echo $item->license; ?>
							</td>
						</tr>							
						<tr>
							<td class="key">
								<?php echo JText::_('OS_AUTHOR_EMAIL'); ?>
							</td>
							<td>
								<?php echo $item->author_email; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('OS_AUTHOR_URL'); ?>
							</td>
							<td>
								<?php echo $item->author_url; ?>
							</td>
						</tr>				
						<tr>
							<td class="key">
								<?php echo JText::_('OS_VERSION'); ?>
							</td>
							<td>
								<?php echo $item->version; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('OS_DESCRIPTION'); ?>
							</td>
							<td>
								<?php echo $item->description; ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<?php echo JText::_('OS_PUBLISHED'); ?>
							</td>
							<td>
								<?php					
									echo $lists['published'];					
								?>						
							</td>
						</tr>
				</table>
			</fieldset>				
		</div>						
		<div class="col" style="float:left; width:35%">
			<fieldset class="adminform">
				<legend><?php echo JText::_('OS_PLUGIN_PARAMETERS'); ?></legend>
				<?php
					foreach ($form->getFieldset('basic') as $field) {
					?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label ;?>
						</div>					
						<div class="controls">
							<?php echo  $field->input ; ?>
						</div>
					</div>	
				<?php
					}					
				?>				
			</fieldset>				
		</div>
				
		<div class="clr"></div>	
			<input type="hidden" name="option" value="com_osservicesbooking" />
			<input type="hidden" name="cid[]" value="<?php echo $item->id; ?>" />
			<input type="hidden" name="id" value="<?php echo $item->id; ?>" />
			<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
}
?>