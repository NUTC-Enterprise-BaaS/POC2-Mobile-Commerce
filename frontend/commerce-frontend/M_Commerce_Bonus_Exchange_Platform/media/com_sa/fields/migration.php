<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

?>
 <script type="text/javascript">
	function migrateads()
	{
		var migrate_sure=confirm('<?php echo JText::_('MIGRATE_AD'); ?>');
		if (migrate_sure==true)
		{
			var selected = jQuery(".camp_and_new:checked");
			var camp_or_old = selected.val();
			jQuery('#migrate_btn').hide();
			jQuery('#migrate_btn_for_old').hide();
			jQuery('#loader_image_div').show();

			jQuery.ajax({
				url: '?option=com_socialads&task=getmigration&camp_or_old='+camp_or_old,
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					if(data == 1)
					{
						jQuery('#loader_image_div').hide();
						if(camp_or_old ==1)
							jQuery('#migration_status').show();
						else
							jQuery('#migration_status_for_old').show();
						alert('<?php echo JText::_('MIGRATE_CONFIRM_SAVE_POP'); ?>');
						Joomla.submitbutton('save');
					}
					else
					{
						jQuery('#loader_image_div').hide();
						jQuery('#migrate_error_div').show();
					}
				}
			});
		}
		else
		{
			return false;
		}
	}
	</script>
<?php
class JFormFieldMigration extends JFormField
{
	var $type = 'Migration';

	public function getInput()
	{
			$params = JComponentHelper::getParams('com_socialads');
			$paymentMode = $params->get('payment_mode');
			$currency = $params->get('currency');
			$daily_budget = $params->get('camp_currency_daily');
			require_once(JPATH_SITE.DS."components".DS."com_socialads".DS."helpers".DS."payment.php");
			$SocialadsPaymentHelper = new SocialadsPaymentHelper;

			if($paymentMode==1)
			{
				$ads2_camp=$SocialadsPaymentHelper->migrateads_camp('camp_hide');
			}
			else
			{
				$ads2_old=$SocialadsPaymentHelper->migrateads_old('camp_hide');//check if migrating camp_budget to old
			}
				if(JVERSION >= '1.6.0')
				$js_key="Joomla.submitbutton = function(task){ ";
				else
				$js_key="function submitbutton( task ){";

				$js_key.="
				var pay_mode = jQuery('input:radio[name =\"config[select_campaign]\"]:checked').val();
				jQuery.when(check_migrate(pay_mode)).done(function(mode_result){
				if(mode_result == 1  ){
					alert('".JText::_('SA_MUST_MIGRATE')."');
				}
				else{
					var validateflag = document.formvalidator.isValid(document.adminForm);
					if(validateflag)";
						if(JVERSION >= '1.6.0')
						$js_key.="Joomla.submitform(task);";
						else
						$js_key.="document.adminForm.submit();";
					$js_key.="else
					return false;
				}
				});
				}";
			$html = '';
			$html .= '<div class="control-group">
					<div style="margin-top:5px" class="controls">
					<div id="migrate_div"  style="' . $paymentMode==1 && $ads2_camp ? '' :'display:none' . '">
						<div id="migrate_btn">
							<div class="alert alert-error">' . JText::_('COM_SOCIALADS_PRICING_MIGRATE_NOTICE') . '</div>
							<input type="button" class="btn btn-danger"  width="50%" onclick="migrateads()" value="' . JText::_('COM_SOCIALADS_PRICING_MIGRATE').'" />
						</div>
						<div class="alert alert-info" id="migration_status" style="display:none">
							<div class="completed_old_migrate" >
							<span class="image_span" ><img class="image"  src=" ' . JUri::root()."components/com_socialads/assets/images/confirm.png" . '" > ' . JText::sprintf('STEP1', $daily_budget, $currency) . '</span>
							</div>
							<div class="completed_old_migrate">
							<span class="image_span" ><img class="image"  src= "' . JUri::root(). "components/com_socialads/assets/images/confirm.png" . ' > '. JText::sprintf ('STEP2', $currency);'</span>
							</div>
							<div class="completed_old_migrate" >
							<span class="image_span" >
							<img class="image"  src="' . JUri::root()."components/com_socialads/images/confirm.png" . '" > ' . JText::_('STEP3') . '</span>		</div>
							<div class="completed_old_migrate">
							<span class="image_span" ><img class="image"  src="' . JUri::root()."components/com_socialads/assets/images/confirm.png" . '" > ' . JText::_('STEP4') . '</span>
							</div>
						</div>
					</div>
					<div id="loader_image_div" style="display:none">
						 <div class="alert alert-warning">' . JText::_('COM_SA_PLS_WAIT') . ' </div>
						 <img src="' . JUri::root(). "components/com_socialads/assets/images/loader_light_blue.gif" . '" width="128" height= "15" border="0" />
					</div>
					<div id="migrate_div_for_old"  style="' . $paymentMode == 0 && $ads2_old ? '' :'display:none' . '"  >
						<div id="migrate_btn_for_old">
							<div class="alert alert-error">' . JText::_('MIGRATE_NOTICE_FOR_OLD') . '</div>
							<input type="button" class="btn btn-danger"  width="50%" onclick="migrateads()" value="' . JText::_('MIGRATE_OLD') . '" />
						</div>

						<div class="alert alert-info" id="migration_status_for_old" style="display:none">

							<div class="completed_old_migrate" ><span class="image_span" ><img class="image"  src="' . JUri::root()."components/com_socialads/assets/images/confirm.png" . '" > ' . JText::_('OLD_STEP1') . '</span>	</div>
							<div class="completed_old_migrate" ><span class="image_span" ><img class="image"  src="' . JUri::root()."components/com_socialads/assets/images/confirm.png" . '" > ' . JText::_('OLD_STEP2') . '</span>	</div>
							<div class="completed_old_migrate" ><span class="image_span" ><img class="image"  src="' . JUri::root()."components/com_socialads/assets/images/confirm.png" . '" > ' . JText::_('OLD_STEP3') .'</span>		</div>
						</div>
					</div>
					<div id="migrate_error_div" class="alert alert-warning" style="display:none"  ></div>
			</div>
		</div>';

		return $html;
	}
}
