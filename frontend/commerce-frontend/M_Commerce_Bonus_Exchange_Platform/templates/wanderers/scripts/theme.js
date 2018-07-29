(function($) {
	$(".toggle-l").click(function () {
		$('body').toggleClass("slide-theme-nav");
	});

	if ( $(window).width() < 780 ){
	    EasySocial.$('[data-es-provide="tooltip"]')
	    .attr('data-es-provide', 'disabled-tooltip')
	    .tooltip('destroyed');
	}

	$('*').bind('touchend', function(e){
        $(e.target).attr('rel') !== 'data-es-provide=tooltip'
        $(e.target).click();
	});

	/*$(".toggle-r").click(function () {
		$('body').toggleClass("slide-theme-helper");
	});*/

	// $( ".kmt-ratings-stars .ui-stars-cancel a" ).append( "<span>Clear ratings</span>" );
	// $( ".kmt-ratings-stars .ui-stars-cancel a" ).addClass( "Hero" );

	// $("#theme-subnav a.pull-right").parent('li').addClass('pull-right');

	// if ($(".theme-nav .nav > li.parent.active").length > 0) {
	// 	$(".theme-nav").addClass("display-child");
	// }

})(jQuery);