$(function () {
	
	'use strict';

	// Hide placeholder on focus

	$('[placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('placeholder'));
	
		$(this).attr('placeholder', '');

		}).blur(function (){
	
			$(this).attr('placeholder', $(this).attr('data-text'));
		
	});

	$('.panel-heading-toggle').click(function (){

		'use strict';

		$(this).next('.panel-cat').fadeToggle(500);


	});

	$('#full-view').click(function(){
		
		'use strict';

		$('.panel-body').fadeIn(500);

	});

	$('#main-view').click(function(){
		
		'use strict';

		$('.panel-body').fadeOut(500);

	});

});





	var unificationHeight = 0;
	$('.fixed-height').each(function () {
	  if ($(this).height() > unificationHeight) {
	    unificationHeight = $(this).height();
	  }
	});
	$('.fixed-height').height(unificationHeight);


	var unificationHeight2 = 0;
	$('.fixed-height2').each(function () {
	  if ($(this).height() > unificationHeight) {
	    unificationHeight = $(this).height();
	  }
	});
	$('.fixed-height2').height(unificationHeight);


	var unificationHeight = 0;
	$('.fixed-height3').each(function () {
	  if ($(this).height() > unificationHeight) {
	    unificationHeight = $(this).height();
	  }
	});
	$('.fixed-height3').height(unificationHeight);



	var unificationHeight2 = 0;
	$('.fixed-height4').each(function () {
	  if ($(this).height() > unificationHeight) {
	    unificationHeight = $(this).height();
	  }
	});
	$('.fixed-height4').height(unificationHeight);


	var commentHeight = 0;
	$("span.user-profile-comment-user").each(function(){
		 if ($(this).height() > commentHeight) {
	    commentHeight = $(this).height();
	  }
	});

	$('div.user-profile-comment').css('min-height', commentHeight + 15 + 'px');


	$(document).ready(function() {

    var docHeight = $(window).height();
    var footerHeight = $('#footer').height();
    var footerTop = $('#footer').position().top + footerHeight;

    if (footerTop < docHeight)
        $('#footer').css('margin-top', 10+ (docHeight - footerTop) + 'px');
	});