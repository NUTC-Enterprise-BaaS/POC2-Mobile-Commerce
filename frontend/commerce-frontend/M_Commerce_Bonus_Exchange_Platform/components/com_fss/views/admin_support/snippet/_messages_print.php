<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<table class='table table-bordered table-ticketborders table-condensed'>

<?php $first = true; ?>

<?php foreach ($this->messages as $message) : ?>
	<?php if ($message['admin'] == 3) : ?>
		<tr class="fss_support_msg_audit success" style="display:none;">
			<td>
				<div class="pull-left" style="margin-right: 8px;">
					<b>
						<?php if ($message['admin'] == 0 && $this->ticket['unregname'] != "") : ?>
							<?php echo $this->ticket['unregname']; ?>
						<?php else: ?>
							<?php echo $message['name']; ?>
						<?php endif; ?>
					</b>
				</div>
				
				<div class="pull-right" style="margin-left: 8px;">
					<i>
						<?php echo FSS_Helper::Date($message['posted'], FSS_DATETIME_MID); ?>
					</i>
				</div>
				
				<?php 
					$msg = $message['body'];
					$msg = FSS_Helper::ParseBBCode($msg, $message);
					echo $msg;
				?>
			</td>
		</tr>
	<?php else: ?>		
		
		<tr class="<?php if (!$first) echo 'first'; ?>">
			<td>
				<div class="pull-right">
					<?php if ($message['admin'] == 1) : ?>
						<?php FSS_Helper::$message_labels[$message['id']] = "success"; ?>
						<a class="label label-success">
					<?php elseif ($message['admin'] == 2) : ?>
						<?php FSS_Helper::$message_labels[$message['id']] = "info"; ?>
						<a class="label label-info">
					<?php else: ?>
						<a class="label label-warning">
						<?php FSS_Helper::$message_labels[$message['id']] = "warning"; ?>
					<?php endif; ?>	
					
					<?php echo $message['name']; ?>
					
					</a>
				</div>
				
				
				
				<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/message.png'>
				<strong id='subject_<?php echo (int)$message['id']; ?>'><?php echo $message['subject']; ?></strong>
			</td>
		</tr>

		<tr>
			<td>
			
				<div class="pull-right" style="margin-bottom: 8px;margin-left: 8px;">
					<i>
						<?php echo FSS_Helper::Date($message['posted'], FSS_DATETIME_MID); ?>
					</i>
				</div>
				
				<div id="message_<?php echo (int)$message['id']; ?>">
					<?php 
						$msg = $message['body'];
						$msg = FSS_Helper::ParseBBCode($msg, $message);
						echo $msg;
					?>
				</div>
				
				<?php if (array_key_exists("attach", $message)) : ?>
					<?php foreach ($message['attach'] as &$attach): ?>
						<div class="padding-mini">
							<a href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&fileid=' . $attach['id'] ); ?>'>
								<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/download-16x16.png'>
								<?php echo $attach['filename']; ?>
							</a>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				<div id="message_raw_<?php echo (int)$message['id']; ?>" style='display:none'><?php echo htmlentities($message['body'],ENT_QUOTES,"utf-8"); ?></div>
				
			</td>
		</tr>

		<?php $first = false; ?>		
	<?php endif; ?>
<?php endforeach; ?>
</table>
