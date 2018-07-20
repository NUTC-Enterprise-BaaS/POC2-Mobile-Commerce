<?php if (FSS_Settings::get('ratings_ticket') && $this->ticket->isClosed() && $this->ticket->rating == 0): ?>
	<div class="alert alert-info ticket_rating">
		<h4 style="margin-bottom: 6px;"><?php echo JText::_('TICKET_RATE_HEADER'); ?></h4>
		<?php echo JText::_('TICKET_RATE_BODY'); ?>
		<div class="rating"><?php echo SupportHelper::ticketRating($this->ticket); ?></div>
	</div>
<?php endif; ?>