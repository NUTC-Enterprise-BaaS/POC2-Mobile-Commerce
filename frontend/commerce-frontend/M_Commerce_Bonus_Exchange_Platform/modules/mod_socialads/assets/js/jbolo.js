function countClickforchat(ad_id,chargetype)
{
	jQuery.ajax({
		url: "?option=com_socialads&task=adredirector&caltype="+chargetype+"&adid="+ad_id+"&chatoption=1",
		type: "GET",
		dataType: "json",
		success: function(msg)
		{								   
			console.log(""+msg);
		}
	});
}
