<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>

<div class="fss_main">

<a href="<?php echo JRoute::_("index.php?option=com_fss&view=perminfo&tmpl=component"); ?>" class="btn btn-success pull-right show_modal_iframe">Preview permissions</a>

<h2><?php echo JText::_('GROUP_PERMISSIONS'); ?></h2>

<div class="well well-mini pull-left margin-small">
	<h4 class="margin-mini"><?php echo JText::_("CONTENT_VIEW_AND_EDIT_PERMISSIONS"); ?></h4>
	<?php $this->Item("ALL_CONTENT","index.php?option=com_fss&view=permission","fss",""); ?>
	<?php $this->Item("FAQS","index.php?option=com_fss&view=permission&section=faq","faqs",""); ?>
	<?php $this->Item("GLOSSARY","index.php?option=com_fss&view=permission&section=glossary","glossary",""); ?>
	<?php $this->Item("ANNOUNCEMENTS","index.php?option=com_fss&view=permission&section=announce","announce",""); ?>
	<?php $this->Item("KB","index.php?option=com_fss&view=permission&section=kb","kb",""); ?>
	<div style="clear: both;"></div>
</div>
	
<div class="well well-mini pull-left margin-small">
	<h4 class="margin-mini"><?php echo JText::_("OTHER_PERMISSIONS"); ?></h4>
	<?php $this->Item("SUPPORT__USERS","index.php?option=com_fss&view=permission&section=support_user","ticketdepts",""); ?>
	<?php $this->Item("SUPPORT__ADMINS","index.php?option=com_fss&view=permission&section=support_admin","users",""); ?>
	<?php $this->Item("MODERATION","index.php?option=com_fss&view=permission&section=moderation","moderate",""); ?>
	<?php $this->Item("TICKET_GROUPS","index.php?option=com_fss&view=permission&section=groups","groups",""); ?>
	<?php $this->Item("REPORTS","index.php?option=com_fss&view=permission&section=reports","cronlog",""); ?>
	<div style="clear: both;"></div>
</div>
	
<div style="clear: both;"></div>
</div>
<h2><?php echo JText::_('PER_USER_PERMISSIONS'); ?></h2>
<form action="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=fusers' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = (strpos($this->lists['order'], "ordering") !== FALSE); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo FSS_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
			</td>
		</tr>
	</table>

    <table class="adminlist table table-striped fss_main">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Username', 'username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th>
				<?php echo JText::_('FSS_CONTENT'); ?>
			</th>
			<th>
				<?php echo JText::_('SUPPORT__USERS'); ?>
			</th>			
			<th>
				<?php echo JText::_('SUPPORT__ADMIN'); ?>
			</th>			
			<th>
				<?php echo JText::_('MODERATION'); ?>
			</th>			
			<th>
				<?php echo JText::_('TICKET_GROUPS'); ?>
			</th>			
			<th>
				<?php echo JText::_('REPORTS'); ?>
			</th>
		</tr>
    </thead>
    <?php

    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
		$checked    = JHTML::_( 'grid.id', $i, $row->user_id );
        $link = FSSRoute::_( 'index.php?option=com_fss&controller=fuser&task=edit&cid[]='. $row->user_id );

		$rules = json_decode($row->rules);
		
		
		
        ?>
        <tr class="<?php echo "row$k"; ?>">
			<td>
                <?php echo $row->user_id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
			<td>
				<?php if (isset($rules->faq) || isset($rules->kb) || isset($rules->announce) || isset($rules->glossary)): ?>
					<?php if (FSS_Permission::PermAnyContent()): ?>
						<span class="label label-success"><?php echo JText::_('ALLOWED'); ?></span>
					<?php else: ?>
						<span class="label label-important"><?php echo JText::_('DENIED'); ?></span>
					<?php endif; ?>
				<?php else: ?>
					<span class="label"><?php echo JText::_('INHERITED'); ?></span>
				<?php endif; ?>			
			</td>
			<td>
				<?php if (isset($rules->support_user)): ?>
					<?php if (FSS_Permission::auth("fss.ticket.view", "com_fss.support_user", $row->user_id) ||
					FSS_Permission::auth("fss.ticket.open", "com_fss.support_user", $row->user_id)): ?>
						<span class="label label-success"><?php echo JText::_('ALLOWED'); ?></span>
					<?php else: ?>
						<span class="label label-important"><?php echo JText::_('DENIED'); ?></span>
					<?php endif; ?>
				<?php else: ?>
					<span class="label"><?php echo JText::_('INHERITED'); ?></span>
				<?php endif; ?>						
			</td>
			<td>
				<?php if (isset($rules->support_admin)): ?>
					<?php if (FSS_Permission::auth("fss.handler", "com_fss.support_admin", $row->user_id)): ?>
						<span class="label label-success"><?php echo JText::_('ALLOWED'); ?></span>
					<?php else: ?>
						<span class="label label-important"><?php echo JText::_('DENIED'); ?></span>
					<?php endif; ?>
				<?php else: ?>
					<span class="label"><?php echo JText::_('INHERITED'); ?></span>
				<?php endif; ?>						
			</td>
			<td>
				<?php if (isset($rules->moderation)): ?>
					<?php if (FSS_Permission::auth("fss.mod.all", "com_fss.moderation", $row->user_id)): ?>
						<span class="label label-success"><?php echo JText::_('ALLOWED'); ?></span>
					<?php else: ?>
						<span class="label label-important"><?php echo JText::_('DENIED'); ?></span>
					<?php endif; ?>
				<?php else: ?>
					<span class="label"><?php echo JText::_('INHERITED'); ?></span>
				<?php endif; ?>			
			</td>
			<td>
				<?php if (isset($rules->groups)): ?>
					<?php if (FSS_Permission::auth("fss.groups", "com_fss.groups", $row->user_id)): ?>
						<span class="label label-success"><?php echo JText::_('ALLOWED'); ?></span>
					<?php else: ?>
						<span class="label label-important"><?php echo JText::_('DENIED'); ?></span>
					<?php endif; ?>
				<?php else: ?>
					<span class="label"><?php echo JText::_('INHERITED'); ?></span>
				<?php endif; ?>		
			</td>
			<td>
				<?php if (isset($rules->reports)): ?>
					<?php if (FSS_Permission::auth("fss.reports", "com_fss.reports", $row->user_id)): ?>
						<span class="label label-success"><?php echo JText::_('ALLOWED'); ?></span>
					<?php else: ?>
						<span class="label label-important"><?php echo JText::_('DENIED'); ?></span>
					<?php endif; ?>
				<?php else: ?>
					<span class="label"><?php echo JText::_('INHERITED'); ?></span>
				<?php endif; ?>
			</td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>

    </table>
</div>

<?php if (FSSJ3Helper::IsJ3()): ?>
	<div class='pull-right'>
		<?php echo $this->pagination->getLimitBox(); ?>
</div>
<?php endif; ?>
<?php echo $this->pagination->getListFooter(); ?>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="fuser" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

