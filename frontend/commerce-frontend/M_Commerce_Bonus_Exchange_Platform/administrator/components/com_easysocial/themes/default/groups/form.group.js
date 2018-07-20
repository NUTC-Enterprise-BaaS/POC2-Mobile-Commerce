EasySocial.require()
.script('admin/users/form', 'admin/grid/grid', 'validate', 'admin/groups/users')
.done(function($)
{
	$('[data-table-grid]').addController('EasySocial.Controller.Grid');

	var form = $('[data-groups-form]');

	form.implement(EasySocial.Controller.Users.Form, {
		mode: 'adminedit'
	});

	form.find('[data-tabnav]').click(function(event) {
		var name = $(this).data('for');

		form.find('[data-active-tab]').val(name);
	});

	$('[data-groups-form-members]').addController('EasySocial.Controller.Groups.Users', {
		groupid: <?php echo $group ? $group->id : 0; ?>
	});

	$.Joomla('submitbutton', function(task) {
		if (task == 'cancel') {
			window.location	= 'index.php?option=com_easysocial&view=groups';

			return false;
		}

		// Create an array of deferreds so that any one else can add in their saving process before joomla submits to the controller
		var dfd = [];

		// Validate the custom fields
		dfd.push(form.validate());

		$.when.apply(null, dfd)
			.done(function() {
				$.Joomla('submitform', [task]);
			})
			.fail(function() {
				EasySocial.dialog(
				{
					content 	: EasySocial.ajax('admin/views/users/showFormError')
				});
			});
	});
});
