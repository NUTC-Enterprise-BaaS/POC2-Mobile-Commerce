jQuery(document).ready(function(){
	var widthscn = jQuery(window).width();
	if(widthscn <= 480) {
		jQuery(".vbmodrooms li").css('width', widthscn);
	};
});