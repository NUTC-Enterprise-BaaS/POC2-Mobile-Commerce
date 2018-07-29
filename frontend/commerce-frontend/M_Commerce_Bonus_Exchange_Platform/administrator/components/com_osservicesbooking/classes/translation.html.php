<?php
/*------------------------------------------------------------------------
# translation.html.php - OS Calendar
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2014 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die('Restricted access');


class HTML_OsAppscheduleTranslation{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	static function translation_list($option,$trans,$lists,$pagination){
		global $mainframe,$_jversion,$configClass;
		JToolBarHelper::title(JText::_('OS_MANAGE_TRANSLATION_LIST'),'list');
		//JToolBarHelper::addNew('translation_add');
		JToolBarHelper::apply('translation_save');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<script type="text/javascript">
			Joomla.submitbutton = function(pressbutton)
			{
				var form = document.adminForm;
				Joomla.submitform(pressbutton, form);
			}
			Joomla.newLanguageItem = function() {
				table = document.getElementById('lang_table');
				row = table.insertRow(1);
				cell0  = row.insertCell(0);
				cell0.innerHTML = '<input type="text" name="extra_keys[]" class="inputbox" size="50" />';
				cell1 = row.insertCell(1);
				cell2 = row.insertCell(2);
				cell2.innerHTML = '<input type="text" name="extra_values[]" class="inputbox" size="100" />';
			}
		</script>
		<style>
			table.admintable {
			    background-color: white;
			}
			table.admintable td.key, table.admintable td.paramlist_key {
				background-color:#fff;
				border: medium none;
			}
		</style>
		<form method="POST" action="index.php?option=com_osservicesbooking" name="adminForm" id="adminForm">
			<input type="hidden" name="option" value="com_osservicesbooking" />
			<input type="hidden" name="task" value="translation_list" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="item" value="com_osservicesbooking" />
			
			<table  width="100%">
				<tr>
					<td width="40%" style="text-align: left;">
						<?php echo JText::_( 'Filter' ); ?>:
						<input type="text" name="search" id="search" value="<?php echo JRequest::getVar('search','');?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
						<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'Go' ); ?></button>
						<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'Reset' ); ?></button>
					</td>
					<td width="60%" style="text-align:right;">
						&nbsp;<?php echo JText::_("OS_SELECT_LANGUAGE")?>: &nbsp;
						<?php echo $lists['langs'];?>
							<?php echo JText::_("Select Side")?>: &nbsp;
						<?php echo $lists['site'];?>
					</td>
				</tr>
			</table>
			<table class="adminlist table table-bordered" style="width:100%" id="lang_table">
				<thead>
					<tr>
						<th class="key" style="width:5%; text-align: center;background-color:orange;"><?php echo JText::_('#'); ?></td>
						<th class="key" style="width:20%; text-align: left;background-color:orange;"><?php echo JText::_('Key'); ?></td>
						<th class="key" style="width:35%; text-align: left;background-color:orange;"><?php echo JText::_('Orginal'); ?></td>
						<th class="key" style="width:40%; text-align: left;background-color:orange;"><?php echo JText::_('Translation'); ?></td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="4" style="text-align:center;">
							<?php 
							echo $pagination->getListFooter();
							?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$item = $lists['item'];
					$lang = $lists['lang'];
					$original = $trans['en-GB'][$item] ;
					$tran = $trans[$lang][$item] ;
					$search = $lists['search'] ;
					$j = 0;
					$str = array();
					foreach ($original as  $key=>$value) {
						$j++;
						$i = $j - 1;
						$str[] = $key;
						$show = true ;
						if (isset($tran[$key])) {
							$translatedValue = $tran[$key];
							$missing = false ; 	
						} else {
							$translatedValue = $value;
							$missing = true ;
						}		
						if ($search) {					
							if (strpos(JString::strtolower($key), $search) === false && strpos(JString::strtolower($value), $search) === false) {
								$show = false ;
							}									
						}  				
						if ($show) {
							if($j % 2 == 0){
								$bgcolor = "#efefef";
							}else{
								$bgcolor = "#ffffff";
							}
						?>
							<tr>
								<td class="key" style="text-align:center;background-color:<?php echo $bgcolor;?>">
									<?php echo $j + $pagination->limitstart;?>.
								</td>
								<td class="key" style="text-align: left;background-color:<?php echo $bgcolor;?>" width="20%" title="<?php echo $key;?>"><?php echo (strlen($key)> 50)? substr($key,0,50).'...': $key;?></td>
								<td style="text-align: left;background-color:<?php echo $bgcolor;?>"><?php echo $value; ?></td>
								<td style="background-color:<?php echo $bgcolor;?>;">						
									<input type="hidden" name="keys[]" value="<?php echo $key; ?>" />
									<input type="hidden" name="items[]" value="<?php echo $i;?>" />
									<input type="text" id="item_<?php echo $i?>" name="item_<?php echo $i?>" value="<?php echo $translatedValue; ; ?>" class="input-xlarge" />
									<?php
										if ($missing) {
										?>
											<span style="color:red;">*</span>
										<?php	
										}							
									?>
								</td>					
							</tr>	
						<?php	
						}else {
						?>
							<tr style="display: none;">
								<td colspan="3"> 
									<input type="hidden" name="keys[]" value="<?php echo $key; ?>" />
									<input type="hidden" name="<?php echo $key; ?>"  value="<?php echo $translatedValue; ; ?>" />
								</td>
							</tr>
						<?php	
						}			
					}
				?>
				</tbody>
			</table>
			<input type="hidden" name="element" id="element" value="com_osservicesbooking" />
		</form>
		<?php
	}
	
}
?>