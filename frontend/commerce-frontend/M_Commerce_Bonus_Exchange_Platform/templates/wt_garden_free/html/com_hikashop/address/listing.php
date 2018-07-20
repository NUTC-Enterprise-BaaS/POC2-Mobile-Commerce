<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="hikashop_address_listing">
<?php if($this->user_id){ ?>
<fieldset>
	<div class="header hikashop_header_title"><h1><?php echo JText::_('ADDRESSES');?></h1></div>
	<div class="toolbar hikashop_header_buttons" id="toolbar" >
		<table>
			<tr>
				<td><?php
					echo $this->popup->display(
						'<span class="icon-32-new" title="'. JText::_('HIKA_NEW').'"></span>'. JText::_('HIKA_NEW'),
						'HIKA_NEW',
						hikashop_completeLink('address&task=add',true),
						'hikashop_new_address_popup',
						350, 480, '', '', 'link'
					);
				?></td>
				<td>
					<a href="<?php echo hikashop_completeLink('user');?>" >
						<span class="icon-32-back" title="<?php echo JText::_('HIKA_BACK'); ?>"></span> <?php echo JText::_('HIKA_BACK'); ?>
					</a>
				</td>
			</tr>
		</table>
	</div>
</fieldset>
<?php
	if(!empty($this->addresses)) {
		$ctrl = JRequest::getCmd('ctrl');
?>
<div class="hikashop_address_listing_div">
<form action="<?php echo hikashop_completeLink($ctrl); ?>" name="hikashop_user_address" method="post">
<?php
if(false) {
	$this->setLayout('select');
	echo $this->loadTemplate();
} else {
?>
<table class="hikashop_address_listing_table">
	<tr>
	  <th style="padding-bottom: 20px;color: black">姓名</th>
	  <th style="padding-bottom: 20px;color: black">地址</th>
	  <th style="padding-bottom: 20px;color: black">修改</th>
	  <th style="padding-bottom: 20px;color: black">刪除</th>
	</tr>
<?php
		global $Itemid;
		$addressClass = hikashop_get('class.address');
		$token = hikashop_getFormToken();
		foreach($this->addresses as $address){
			$this->address =& $address;
?>
<tr>
		<td style="padding-bottom: 20px">
			<?php
				echo "$address->address_lastname";
			?>
		</td>

		<td style="padding-bottom: 20px">
			<?php
				echo "$address->address_street";
			?>
		</td>

		<td style="padding-bottom: 20px">
<?php
			echo $this->popup->display(
				'<img src="'. HIKASHOP_IMAGES.'edit.png" title="'. JText::_('HIKA_EDIT').'" alt="'. JText::_('HIKA_EDIT').'" />',
				'HIKA_EDIT',
				hikashop_completeLink('address&task=edit&address_id='.$address->address_id.'&Itemid='.$Itemid, true),
				'hikashop_edit_address_popup_'.$address->address_id,
				350, 480, '', '', 'link'
			);
?>
		</td>
		<td style="padding-bottom: 20px">
			<a onclick="if(!confirm('<?php echo JText::_('HIKASHOP_CONFIRM_DELETE_ADDRESS', true); ?>')){return false;}else{return true;}" href="<?php echo hikashop_completeLink('address&task=delete&address_id='.$address->address_id.'&'.$token.'=1&Itemid='.$Itemid);?>" title="<?php echo JText::_('HIKA_DELETE'); ?>"><img src="<?php echo HIKASHOP_IMAGES; ?>delete.png" alt="<?php echo JText::_('HIKA_DELETE'); ?>" />
			</a></td>

	</tr>

<?php
		}
?>
</table>
<?php } ?>
	<input type="hidden" name="option" value="<?php echo HIKASHOP_COMPONENT; ?>" />
	<input type="hidden" name="ctrl" value="<?php echo $ctrl ?>" />
	<input type="hidden" name="task" value="setdefault" />
	<?php echo JHTML::_('form.token'); ?>
</form>
</div>
<?php
	}
}
?>
</div>
<div class="clear_both"></div>