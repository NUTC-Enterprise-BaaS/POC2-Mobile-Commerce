var f90pev = {};
f90pev.validation = {};

(function($){
	
	var checkValidationCode = function(){
		var email = $('#jsemail').val();
		var code  = $('#f90validationcode').val();
		
		var verified =false;
		
		$.ajax({
            url: "index.php?plg=f90pev&task=checkValidationCode",                 
            data: 'email='+email+'&code='+code,
            async: false
            }).done(function(data) {
           	 	data = $.parseJSON(data);
                if(data.error == false){
                	verified = true;
                }
                
                if(data.error == true){
                	$('#f90validationcode').next().removeClass('hide').html('<span style="color: red;">'+data.html+'</span> <br/><br/>');
                }
                else{
                	$('#f90validationcode').next().removeClass('hide').html(data.html);
                }
            }).fail(function() {
            	$('#f90validationcode').next().removeClass('hide').html('Error in connection');
            });
		 
		 return verified;
   };
   
	$(document).ready(function(){
		$('#f90sendvalidationcode').click(function(){
			
			if($('#jsemail').hasClass('invalid')){
				alert(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_ENTER_VALIDEMAIL'));
				return false;
			}
			
			$(this).attr('disabled', 'disabled');
			$(this).val(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_SENDING_CODE'));
			
			var email = $('#jsemail').val();
			 $.ajax({
                 url: "index.php?plg=f90pev&task=sendValidationCode",                 
                 data: 'email='+email
                 }).done(function(data) {
                	 $('#f90sendvalidationcode').val(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_SEND_AGAIN'));
                	 	$('#f90sendvalidationcode').removeAttr('disabled');
                        data = $.parseJSON(data);
                        if(data.error == true){
                        	$('#f90sendvalidationcode').next().removeClass('hide').html('<span style="color: red;">'+data.html+'</span> <br/><br/>');
                        }
                        else{
                        	$('#f90sendvalidationcode').next().removeClass('hide').html(data.html+'<br/><br/>');
                        }
                 }).fail(function() {
                	 	$('#f90sendvalidationcode').val(Joomla.JText._('PLG_SYSTEM_PREEMAILVALIDATION_SEND_AGAIN'));
                	 	$('#f90sendvalidationcode').removeAttr('disabled');
             	 		$('#f90sendvalidationcode').next().removeClass('hide').html('Error in coneection');
                 });
		});
		
		$('#f90validationcode').blur(function() {
			//checkValidationCode();
		});
		
		$('#jomsForm').submit(function(){
			if(checkValidationCode() == false){
				$('#btnSubmit').attr('disabled', false);
				$('#btnSubmit').css('display', 'block');
				$('#jomsForm input').removeAttr('readonly');
				return false;
			}
		});
	});	
})(jomsQuery);
