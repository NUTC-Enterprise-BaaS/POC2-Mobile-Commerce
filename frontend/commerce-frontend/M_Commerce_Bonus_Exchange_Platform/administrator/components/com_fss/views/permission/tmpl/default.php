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

<style>
table select {
	margin-bottom: 0 !important;
}
</style>

<div id="model-backdrop" class="modal-backdrop hide"></div>

<div class="alert alert-info"><?php echo $this->description; ?></div>

<form action="index.php?option=com_fss&view=permission&section=<?php echo $this->section; ?>&task=save" method="post" name="compForm" id="compForm">
	<input type="hidden" name="option" value="com_fss" />
	<input type="hidden" name="section" value="<?php echo $this->section; ?>" />
	<input type="hidden" name="view" value="permission" />
	<input type="hidden" name="task" value="save" id="compTask" />
	<input type="hidden" name="data" value="" id="compData" />
</form>

<form action="index.php?option=com_fss&view=permission&section=<?php echo $this->section; ?>&task=save" method="post" name="adminForm" id="adminForm">

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="section" value="<?php echo $this->section; ?>" />
<input type="hidden" name="view" value="permission" />
<input type="hidden" name="task" value="save" />

<div id='max_input_vars' style='display: none;'><?php echo ini_get('max_input_vars'); ?></div>

<div id='max_input_vars_error' class='alert alert-error' style='display: none;'>
	<h4>Error: This form is too large for your current php.ini config.</h4>
	This form is too large to submit with your current <b>max_input_vars</b> configuration. Please edit your php.ini file and add or set the following:
	<pre>max_input_vars="<span id='new_min'>???</span>"</pre>
	<h4>If you do not make this change, this form will not save correctly<h4>
</div>

<?php if ($this->section == "com_fss.support_admin"): ?>
	<div class="tabbable">

	<ul class="nav nav-tabs" id="support_tabs">
			<li class="active"><a href="#admin_support"><?php echo JText::_('SUPPORT_ADMIN'); ?></a></li>
			<li><a href="#admin_support_misc"><?php echo JText::_('MISC_PERMISSIONS'); ?></a></li>
			<li><a href="#support_ticket"><?php echo JText::_('TICKET_PERMISSIONS'); ?></a></li>
			<li><a href="#support_view"><?php echo JText::_('View'); ?></a></li>
			<li><a href="#support_assign"><?php echo JText::_('ASSIGN'); ?></a></li>
		</ul>
 
		<div class="tab-content">

			<div class="tab-pane active" id="admin_support">
				<div class="alert">
					<h4>General Support Admin Permissions</h4>
				</div>
				<?php echo $this->form->getInput("rules"); ?>
			</div>

			<div class="tab-pane" id="admin_support_misc">
				<div class="alert">
					<h4>Misc Ticket Permissions</h4>
				</div>
				<?php echo $this->form->getInput("rules_misc"); ?>
			</div>
					
			<div class="tab-pane" id="support_ticket">
				<div class="tabbable">
					<ul class="nav nav-tabs" id="support_ticket_tabs">
						<li class="active"><a href="#t_admin_support_ticket"><?php echo JText::_('MY_TICKETS'); ?></a></li>
						<li><a href="#t_admin_support_ticket_cc"><?php echo JText::_('CC__D_TICKETS'); ?></a></li>
						<li><a href="#t_admin_support_ticket_other"><?php echo JText::_('OTHER_TICKETS'); ?></a></li>
						<li><a href="#t_admin_support_ticket_una"><?php echo JText::_('UNASSIGNED_TICKETS'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="t_admin_support_ticket">
							<div class="alert">
								<h4>My Tickets: Actions Permissions</h4>
								<p>These permissions apply to tickets assigned to the handler.</p>
								<p>If the 'CCd', 'Other' or 'Unassigned' tickets permissions are not restricted, these permissions will apply to those tickets instead.</p>
							</div>
							<?php echo $this->form->getInput("rules_ticket"); ?>
						</div>
	
						<div class="tab-pane" id="t_admin_support_ticket_cc">
							<div class="alert">
								<h4>CC'd Tickets: Actions Permissions</h4>
								<p>If these are not set as restricted, then the "My Tickets" permissions apply.</p>
							</div>
							<?php echo $this->form->getInput("rules_ticket_cc"); ?>
						</div>
	
						<div class="tab-pane" id="t_admin_support_ticket_other">
							<div class="alert">
								<h4>Other Handlers Tickets: Actions Permissions</h4>
								<p>If these are not set as restricted, then the "My Tickets" permissions apply.</p>
							</div>
							<?php echo $this->form->getInput("rules_ticket_other"); ?>
						</div>
	
						<div class="tab-pane" id="t_admin_support_ticket_una">
							<div class="alert">
								<h4>Unassigned Tickets: Actions Permissions</h4>
								<p>If these are not set as restricted, then the "My Tickets" permissions apply.</p>
							</div>
							<?php echo $this->form->getInput("rules_ticket_una"); ?>
						</div>
				
					</div>
				</div>
			</div>
			
			<div class="tab-pane" id="support_view">
				<div class="tabbable">
					<ul class="nav nav-tabs" id="support_view_tabs">
						<li class="active"><a href="#t_admin_view_products"><?php echo JText::_('VIEW__PRODUCTS'); ?></a></li>
						<li><a href="#t_admin_view_departments"><?php echo JText::_('VIEW__DEPARTMENTS'); ?></a></li>
						<li><a href="#t_admin_view_categories"><?php echo JText::_('VIEW__CATEGORIES'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="t_admin_view_products">
							<div class="alert">
								<h4>Products that the user is able to view tickets for.</h4>
								<p>Only applies when:</p>
								<ul>
									<li><strong>Ticket Handler</strong> is <span class="label label-success">Allowed</span></li>
									<li><strong>Can view tickets for all products</strong> is <span class="label label-important">Not Allowed</span></li>
								</ul>
							</div>				
							<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "view_products", "products", 'fss.handler.view.product', 'perm_vp')->input; ?>
						</div>
	
						<div class="tab-pane" id="t_admin_view_departments">
							<div class="alert">
								<h4>Departments that the user is able to view tickets for.</h4>
								<p>Only applies when:</p>
								<ul>
									<li><strong>Ticket Handler</strong> is <span class="label label-success">Allowed</span></li>
									<li><strong>Can view tickets for all departments</strong> is <span class="label label-important">Not Allowed</span></li>
								</ul>
							</div>			
							<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "view_departments", "departments", 'fss.handler.view.department', 'perm_vd')->input; ?>
						</div>
	
						<div class="tab-pane" id="t_admin_view_categories">
							<div class="alert">
								<h4>Categories that the user is able to view tickets for.</h4>
								<p>Only applies when:</p>
								<ul>
									<li><strong>Ticket Handler</strong> is <span class="label label-success">Allowed</span></li>
									<li><strong>Can view tickets for all departments</strong> is <span class="label label-important">Not Allowed</span></li>
								</ul>
							</div>					
							<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "view_categories", "categories", 'fss.handler.view.category', 'perm_vc')->input; ?>
						</div>
			
					</div>
				</div>
			</div>
			
			<div class="tab-pane" id="support_assign">
				<div class="tabbable">
					<ul class="nav nav-tabs" id="support_assign_tabs">
						<li class="active"><a href="#t_admin_assign_products"><?php echo JText::_('ASSIGN__PRODUCTS'); ?></a></li>
						<li><a href="#t_admin_assign_departments"><?php echo JText::_('ASSIGN__DEPARTMENTS'); ?></a></li>
						<li><a href="#t_admin_assign_categories"><?php echo JText::_('ASSIGN__CATEGORIES'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="t_admin_assign_products">
							<div class="alert">
								<h4>Products that the user can be assigned tickets for.</h4>
								<p>Only applies when:</p>
								<ul>
									<li><strong>Ticket Handler</strong> is <span class="label label-success">Allowed</span></li>
									<li><strong>Dont assign tickets</strong> is set to <span class="label label-important">Not Allowed</span></li>
									<li><strong>Assigned tickets for all products</strong> is set to <span class="label label-important">Not Allowed</span></li>
								</ul>
							</div>					
							<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "assign_products", "products", 'fss.handler.assign.product', 'perm_ap')->input; ?>
						</div>
	
						<div class="tab-pane" id="t_admin_assign_departments">
							<div class="alert">
								<h4>Departments that the user can be assigned tickets for.</h4>
								<p>Only applies when:</p>
								<ul>
									<li><strong>Ticket Handler</strong> is <span class="label label-success">Allowed</span></li>
									<li><strong>Dont assign tickets</strong> is set to <span class="label label-important">Not Allowed</span></li>
									<li><strong>Assigned tickets for all departments</strong> is set to <span class="label label-important">Not Allowed</span></li>
								</ul>
							</div>					
							<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "assign_departments", "departments", 'fss.handler.assign.department', 'perm_ad')->input; ?>
						</div>
	
						<div class="tab-pane" id="t_admin_assign_categories">
							<div class="alert">
								<h4>Categories that the user can be assigned tickets for.</h4>
								<p>Only applies when:</p>
								<ul>
									<li><strong>Ticket Handler</strong> is <span class="label label-success">Allowed</span></li>
									<li><strong>Dont assign tickets</strong> is set to <span class="label label-important">Not Allowed</span></li>
									<li><strong>Assigned tickets for all categories</strong> is set to <span class="label label-important">Not Allowed</span></li>
								</ul>
							</div>			
							<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "assign_categories", "categories", 'fss.handler.assign.category', 'perm_ac')->input; ?>
						</div>				
					</div>
				</div>
			</div>
		
		</div>			
	</div>

<?php elseif ($this->section == "com_fss.reports"): ?>

	<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "rules", "reports", 'fss.reports.report', 'reports')->input; ?>

<?php else: ?>
	
	<?php echo $this->form->getInput("rules"); ?>

<?php endif; ?>

<script>

jQuery(document).ready( function () {

	// hide some text we dont want
	jQuery('.rule-desc').hide();
	
	setTimeout("delayTabs()", 1000);

	jQuery('a[data-toggle="tab"]').click(function (e) {
		e.preventDefault();
		var id = jQuery(this).attr('href');
		id = id.replace('#', '');
		parts = id.split('-');
		
		var current_set = parts[0];
		var group = parts[1];
		
		var group_list = {};
		
		jQuery('ul.nav-tabs > li > a').each( function () {
			var s_id = jQuery(this).attr('href');
			s_id = s_id.replace('#', '');
			s_parts = s_id.split('-');
			if (s_parts.length == 2)
				group_list[s_parts[0]] = s_parts;
		});
		
		Object.keys(group_list).forEach(function(key) {
			if (key != current_set)
			{
				var a_elem = jQuery('a[href="#' + key + '-' + group + '"]');
				a_elem.tab('show');
			}
		});
	});

	/*var max_input_vars = parseInt(jQuery('#max_input_vars').text());
	if (jQuery('select').length > max_input_vars - 20)
	{
		var count = jQuery('select').length * 2;
		count = Math.ceil(count / 10000);
		count = count * 10000;

		jQuery('#new_min').text(count);
		jQuery('#max_input_vars_error').show();
	}*/

});

Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == "save" || pressbutton == "apply")
		return compressForm("#adminForm", pressbutton);

	if (pressbutton == "nojs")
	{
		window.location = '<?php echo JRoute::_("index.php?option=com_fss&view=permission&section=support_admin&nojs=1", false); ?>';
		return;
	}

	Joomla.submitform(pressbutton);
}

function compressForm(selector, task)
{
	jQuery('#compData').val(jQuery(selector).serialize());
	jQuery('#compTask').val(task);
	jQuery('#compForm').submit();
}

function delayTabs()
{
	// top tabs
	jQuery('ul.nav-tabs a').unbind('click');

	jQuery('ul.nav-tabs a').click(function (e) {
		e.preventDefault();
		jQuery(this).tab('show');
    })
}


</script>

</div>
