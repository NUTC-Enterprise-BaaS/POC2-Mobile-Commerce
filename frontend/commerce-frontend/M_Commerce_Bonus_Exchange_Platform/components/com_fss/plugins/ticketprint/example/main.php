<?php
/**
 * Example layout for printing a ticket
 **/
?>

<div class="well">
	<dl>
		<dt>Subject</dt>
		<dd><?php echo $this->ticket->title; ?></dd>
		<dt>Status</dt>
		<dd><?php echo $this->ticket->status; ?></dd>
		<dt>User</dt>
		<dd><?php echo $this->ticket->name; ?></dd>
		<!--<dt>Custom</dt>
		<dd><?php //echo $this->ticket->custom[5]['value']; ?></dd>-->
	</dl>
</div>

<?php foreach ($this->ticket->messages as $message): ?>
	<?php if ($message->admin > 1) continue; // this excluded the display of audit logs and other similar messages ?>
	<h4><?php echo $message->subject; ?>, Posted: <?php echo FSS_Helper::TicketTime($message->posted, FSS_DATETIME_MID); ?></h4>
	<div class="bbcode well"><?php echo FSS_Helper::ParseBBCode($message->body, $message); ?></div>
<?php endforeach; ?>

<?php 
// Show the full ticket object to aid in development:
// print_p($this->ticket);
	  			 			 	 			