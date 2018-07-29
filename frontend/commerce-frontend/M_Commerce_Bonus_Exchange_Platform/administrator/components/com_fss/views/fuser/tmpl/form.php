<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo JHTML::_( 'form.token' ); ?>

<div class="fss_main">

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }
        submitform(pressbutton);
}
//-->

function selectUser(userid, username)
{
	jQuery('#user_id').val(userid);
	jQuery('#user_name').val(username);
	jQuery('#toolbar-apply button').removeAttr('disabled');
	jQuery('#toolbar-save button').removeAttr('disabled');
	jQuery('#toolbar-save-new button').removeAttr('disabled');
	fss_modal_hide();
}

<?php if ($this->user->user_id < 1): ?>
jQuery(document).ready(function() {
	jQuery('#toolbar-apply button').attr('disabled', 'disabled');
	jQuery('#toolbar-save button').attr('disabled', 'disabled');
	jQuery('#toolbar-save-new button').attr('disabled', 'disabled');
});
<?php endif; ?>

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form form-horizontal form-condensed">

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="view" value="fuser" />
<input type="hidden" name="task" value="save" />

	<div class="control-group">
		<div class="control-label">
			<label><?php echo JText::_("USER_GROUP"); ?></label>
		</div>
		<div class="controls">
			<?php echo $this->users; ?>
		</div>
	</div>

<div class="tabbable">

	<ul class="nav nav-tabs" id="main_tabs">
		<li class="active"><a href="#t_content"><?php echo JText::_('FSS_CONTENT'); ?></a></li>
		<li><a href="#t_general"><?php echo JText::_('GENERAL'); ?></a></li>
		<li><a href="#t_admin"><?php echo JText::_('SUPPORT_ADMIN'); ?></a></li>
	</ul>
 
	<div class="tab-content">
		<div class="tab-pane active" id="t_content">
			
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs" id="content_tabs">
					<li class="active"><a href="#t_content_faqs"><?php echo JText::_('FAQS'); ?></a></li>
					<li><a href="#t_content_kb"><?php echo JText::_('KB'); ?></a></li>
					<li><a href="#t_content_glossary"><?php echo JText::_('GLOSSARY'); ?></a></li>
					<li><a href="#t_content_announce"><?php echo JText::_('ANNOUNCE'); ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="t_content_faqs">
						<?php echo $this->form->getInput("faq"); ?>
					</div>	
					<div class="tab-pane" id="t_content_kb">
						<?php echo $this->form->getInput("kb"); ?>
					</div>	
					<div class="tab-pane" id="t_content_glossary">
						<?php echo $this->form->getInput("glossary"); ?>
					</div>	
					<div class="tab-pane" id="t_content_announce">
						<?php echo $this->form->getInput("announce"); ?>
					</div>
				</div>
			</div>
		
		
		</div>
	
		<div class="tab-pane" id="t_general">
			
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs" id="general_tabs">
					<li class="active"><a href="#t_general_support"><?php echo JText::_('SUPPORT_USER'); ?></a></li>
					<li><a href="#t_general_comments"><?php echo JText::_('MODERATION'); ?></a></li>
					<li><a href="#t_general_reports"><?php echo JText::_('REPORTS'); ?></a></li>
					<li><a href="#t_general_groups"><?php echo JText::_('TICKET_GROUPS'); ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="t_general_support">
						<?php echo $this->form->getInput("support_user"); ?>
					</div>
	
					<div class="tab-pane" id="t_general_comments">
						<?php echo $this->form->getInput("moderation"); ?>
					</div>
	
					<div class="tab-pane" id="t_general_reports">
						<?php echo FSS_Admin_Permissions::prep_custom_rules($this->form, "reports", "reports", 'fss.reports.report', 'reports')->input; ?>
					</div>
	
					<div class="tab-pane" id="t_general_groups">
						<?php echo $this->form->getInput("groups"); ?>
					</div>
				</div>
			</div>
			
			
		</div>
	
		<div class="tab-pane" id="t_admin">
			
			
	
			<div class="tabbable">

				<ul class="nav nav-tabs" id="support_tabs">
					<li class="active"><a href="#support_main"><?php echo JText::_('General'); ?></a></li>
					<li><a href="#support_ticket"><?php echo JText::_('TICKET_PERMISSIONS'); ?></a></li>
					<li><a href="#support_view"><?php echo JText::_('View'); ?></a></li>
					<li><a href="#support_assign"><?php echo JText::_('ASSIGN'); ?></a></li>
				</ul>
 
				<div class="tab-content">
					<div class="tab-pane active" id="support_main">
						<div class="tabbable tabs-left">
							<ul class="nav nav-tabs" id="support_main_tabs">
								<li class="active"><a href="#t_admin_support"><?php echo JText::_('SUPPORT_ADMIN'); ?></a></li>
								<li><a href="#t_admin_support_misc"><?php echo JText::_('MISC_PERMISSIONS'); ?></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="t_admin_support">
									<div class="alert">
										<h4>General Support Admin Permissions</h4>
									</div>
									<?php echo $this->form->getInput("support_admin"); ?>
								</div>
	
								<div class="tab-pane" id="t_admin_support_misc">
									<div class="alert">
										<h4>Misc Ticket Permissions</h4>
									</div>
									<?php echo $this->form->getInput("support_admin_misc"); ?>
								</div>
							</div>
						</div>
					</div>
					
					<div class="tab-pane" id="support_ticket">
						<div class="tabbable tabs-left">
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
									<?php echo $this->form->getInput("support_admin_ticket"); ?>
								</div>
	
								<div class="tab-pane" id="t_admin_support_ticket_cc">
									<div class="alert">
										<h4>CC'd Tickets: Actions Permissions</h4>
										<p>If these are not set as restricted, then the "My Tickets" permissions apply.</p>
									</div>
									<?php echo $this->form->getInput("support_admin_ticket_cc"); ?>
								</div>
	
								<div class="tab-pane" id="t_admin_support_ticket_other">
									<div class="alert">
										<h4>Other Handlers Tickets: Actions Permissions</h4>
										<p>If these are not set as restricted, then the "My Tickets" permissions apply.</p>
									</div>
									<?php echo $this->form->getInput("support_admin_ticket_other"); ?>
								</div>
	
								<div class="tab-pane" id="t_admin_support_ticket_una">
									<div class="alert">
										<h4>Unassigned Tickets: Actions Permissions</h4>
										<p>If these are not set as restricted, then the "My Tickets" permissions apply.</p>
									</div>
									<?php echo $this->form->getInput("support_admin_ticket_una"); ?>
								</div>
				
							</div>
						</div>
					</div>
			
					<div class="tab-pane" id="support_view">
						<div class="tabbable tabs-left">
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
						<div class="tabbable tabs-left">
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

		</div>

	</div>

</div>

<div class="clr"></div>

</form>


<script>

jQuery(document).ready( function () {

	// hide some text we dont want
	jQuery('.rule-desc').hide();
	
	//jQuery('.fssTip').tooltip();
	
	setTimeout("delayTabs()", 500);
});

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