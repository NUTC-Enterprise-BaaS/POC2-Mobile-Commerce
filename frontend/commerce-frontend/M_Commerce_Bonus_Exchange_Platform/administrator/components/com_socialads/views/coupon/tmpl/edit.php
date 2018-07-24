<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$input = JFactory::getApplication()->input;
$cid = $input->get( 'id','','INT' );

// Import helper for declaring language constant
JLoader::import('SocialadsHelper', JUri::root().'administrator/components/com_socialads/helpers/socialads.php');

// Call helper function
SocialadsHelper::getLanguageConstant();
?>
<div class="<?php echo SA_WRAPPER_CLASS;?> sa-coupon">
	<form action="<?php echo JRoute::_('index.php?option=com_socialads&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="coupon-form" class="form-validate">
		<div class="form-horizontal">
			<div class="row-fluid">
				<div class="span10 form-horizontal">
					<fieldset class="adminform">
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
							<div class="controls" id = "code"><?php echo $this->form->getInput('code'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('val_type'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('val_type'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('value'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('value'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('max_use'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('max_use'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('max_per_user'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('max_per_user'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('from_date'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('from_date'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('exp_date'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('exp_date'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo $this->form->getLabel('couponParams'); ?></div>
							<div class="controls"><?php echo $this->form->getInput('couponParams'); ?></div>
						</div>
					</fieldset>
				</div>
			</div>
			<?php
			if (empty($this->item->created_by))
			{ ?>
				<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
			<?php
			}
			else
			{ ?>
				<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
			<?php
			} ?>
			<input type="hidden" name="jform[published]" value="<?php echo $this->item->state; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
			<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
			<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
			<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>

<script type="text/javascript">
	saAdmin.coupon.initCouponJs();
	Joomla.submitbutton = function(task){saAdmin.coupon.submitButtonAction(task);}
	var cid=<?php if($cid) echo $cid; else echo "0"; ?>;
</script>