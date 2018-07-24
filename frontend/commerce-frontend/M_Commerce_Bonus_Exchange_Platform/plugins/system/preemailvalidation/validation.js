var f90pev = {};
f90pev.validation = {};

(function($){
	
	$(document).ready(function(){
		$('#f90sendvalidationcode').click(function(){
			
			if($('#jform_email1').attr('aria-invalid') == 'true' || $('#jform_email2').attr('aria-invalid') == 'true'){
				alert(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_ENTER_VALIDEMAIL'));
				return false;
			}
			
			$(this).attr('disabled', 'disabled');
			$(this).val(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_SENDING_CODE'));
			
			var email = $('#jform_email1').val();
			 $.ajax({
                 url: "index.php?plg=f90pev&task=sendValidationCode",                 
                 data: 'email='+email
                 }).done(function(data) {
                	 $('#f90sendvalidationcode').text(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_SEND_AGAIN'));
                	 	$('#f90sendvalidationcode').removeAttr('disabled');
                        data = $.parseJSON(data);
                        if(data.error == true){
                        	jQuery('.err-f90-sendvalidation-code').removeClass('hide').html('<span style="color: red;">'+data.html+'</span> <br/><br/>');
                        }
                        else{
                        	jQuery('.err-f90-sendvalidation-code').removeClass('hide').html(data.html);
                        }
                 }).fail(function() {
                	 	$('#f90sendvalidationcode').val(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_SEND_AGAIN'));
                	 	$('#f90sendvalidationcode').removeAttr('disabled');
             	 		$('.err-f90-sendvalidation-code').removeClass('hide').html('Error in coneection');
                 });
		});
	});
})(jQuery);



window.addEvent('domready', function(){
	   document.formvalidator.setHandler('checkValidationCode', function(value) {		   
			var email = jQuery('#jform_email1').val();
			var code  = jQuery('#f90validationcode').val();
			
			var verified =false;
			
			jQuery.ajax({
                url: "index.php?plg=f90pev&task=checkValidationCode",                 
                data: 'email='+email+'&code='+code,
                async: false
                }).done(function(data) {
               	 	data = jQuery.parseJSON(data);
                    if(data.error == false){
                    	verified = true;
                    }
                    
                    if(data.error == true){
                    	jQuery('.err-f90-code-validation').removeClass('hide').html('<span style="color: red;">'+data.html+'</span>');
                    }
                    else{
                    	jQuery('.err-f90-code-validation').removeClass('hide').html(data.html);
                    }
                }).fail(function() {
                	jQuery('.err-f90-code-validation').removeClass('hide').html('Error in connection');
                });
			 
			 return verified;
	   });
});