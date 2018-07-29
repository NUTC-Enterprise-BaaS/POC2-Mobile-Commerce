<?php
echo FSS_Helper::PageStylePopup(true);
echo FSS_Helper::PageTitlePopup("EMail Ticket", "Sent");
?>

Ticket has been sent to <?php echo $this->postData['email']['email']; ?> 

<?php echo FSS_Helper::PageStylePopupEnd(); ?>
