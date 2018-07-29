<h4>Enter the plain text email to test for trimming below:</h4>

<form action="<?php echo JRoute::_("index.php?option=com_fss&view=trimtest&task=test&tmpl=component"); ?>" method="post" target="test_results">

<textarea rows="15" cols="80" name="email" style="width: 80%">Dear Handler,

This has resolved my issue

Best regards

Your User

-----Original Message-----
> Dear User,
>
> Your support ticket reference number XXXX-XXXX-XXXX has been replied to by
> Handler. Please go to your admin area to view the response.
>
> Ticket Summary:-
> Subject: Some issue
> Ticket reference number: XXXX-XXXX-XXXX
> Department: Tech Support
> Product: Support Portal
> Message:
> Hello
>
> This is fixed in the latest version
>
> Ticket Handler
>
</textarea>
<p><button class="btn btn-default">Run Test</button></p>

</form>

<iframe name="test_results" src="" frameBorder="0" style="width:90%;height: 2000px"></iframe>