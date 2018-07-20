<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$def_sig = SupportUsers::getSetting("default_sig");
$sig_txt = "";
?>
<select name="signature" id="signature">
	<optgroup label="<?php echo JText::_('Signatures'); ?>:">
		<?php foreach (SupportCanned::GetAllSigs(null) as $sig): ?>
			<?php if ($sig_txt == "") $sig_txt = $sig->content; ?>
			<option value="<?php echo $sig->id; ?>" 
				<?php if ($sig->id == $def_sig) echo "selected"; ?>
				><?php echo $sig->description; ?></option>
		<?php endforeach; ?>
	</optgroup>
	<option value="0" <?php if ($def_sig == 0) echo "selected"; ?>><?php echo JText::_('NO_SIGNATURE'); ?></option>
</select>
		
<div class="fss_signature" style='display: none' id="signature_display">
	<?php echo str_replace("\n","<br />",$sig_txt); ?>
</div>