<?php
/*------------------------------------------------------------------------
# emails.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

class HTML_OSappscheduleEmails{
	function emailListForm($option,$rows){
		global $mainframe;
		JToolBarHelper::title(JText::_('OS_MANAGE_EMAIL_TEMPLATES'),'envelope');
		JToolBarHelper::cancel('goto_index');
		JToolbarHelper::custom('cpanel_list','featured.png', 'featured_f2.png',JText::_('OS_DASHBOARD'),false);
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
		<table width="100%" class="adminlist table table-striped">
			<thead>
				<tr>
					<th width="5%" align="center">
						#
					</th>
					<th width="45%">
						<?php echo JText::_('OS_EMAIL_KEY');?>
					</th>
					<th width="50">
						<?php echo JText::_('OS_EMAIL_SUBJECT');?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$k = 0;
				for ($i=0, $n=count($rows); $i < $n; $i++) {
					$row = $rows[$i];
					$link 		= JRoute::_( 'index.php?option='.$option.'&task=emails_edit&cid[]='. $row->id );
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td align="center"><?php echo $i + 1; ?></td>
						<td align="left">
							<a href="<?php echo $link?>">
								<?php echo $row->email_key?>
							</a>
						</td>
						<td align="left">
							<a href="<?php echo $link?>">
								<?php echo $row->email_subject?>
							</a>
						</td>
					</tr>
					<?php
					$k = 1 - $k;	
				}
				?>
			</tbody>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="emails_list">
		<input type="hidden" name="boxchecked" value="0">
		</form>
		<?php
	}
	
	function editEmailTemplate($option,$row,$translatable){
		global $mainframe,$languages;
		JToolBarHelper::title(JText::_('OS_EMAIL_TEMPLATE')." <small>[Edit]</small>");
		JToolBarHelper::save('emails_save');
		JToolBarHelper::apply('emails_apply');
		JToolBarHelper::cancel('emails_gotolist');
		$editor = JFactory::getEditor();
		?>
		<form method="POST" action="index.php" name="adminForm" id="adminForm">
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
			<table cellpadding="0" cellspacing="0" width="100%" class="admintable">
				<tr>
					<td class="key">
						<?php echo JText::_('OS_EMAIL_SUBJECT')?>
					</td>
					<td>
						<input type="text" class="inputbox" size="50" value="<?php echo $row->email_subject?>" name="email_subject">
					</td>
				</tr>
				<tr>
					<td class="key" valign="top" style="padding-top:5px;">
						<?php echo JText::_('OS_EMAIL_CONTENT')?>
					</td>
					<td>
						<?php echo $editor->display('email_content',stripslashes($row->email_content), '100%', 300) ?>
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
										<td class="key"><?php echo JText::_('OS_EMAIL_SUBJECT'); ?></td>
										<td >
											<input type="text" name="email_subject_<?php echo $sef; ?>" id="email_subject_<?php echo $sef; ?>" value="<?php echo $row->{'email_subject_'.$sef}?>" class="input-large">
										</td>
									</tr>
									<tr>
										<td class="key" valign="top"><?php echo JText::_('OS_EMAIL_CONTENT'); ?></td>
										<td >
											<?php
											echo $editor->display( 'email_content_'.$sef,  stripslashes($row->{'email_content_'.$sef}) , '95%', '250', '75', '20' ) ;
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
		<input type="hidden" name="option" value="<?php echo $option?>">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="id" value="<?php echo $row->id?>">
		</form>
		<?php
	}
}
?>