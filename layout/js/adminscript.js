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


	$(".signupspan").click(function(){

		$(this).addClass('active').siblings().removeClass('active');
		$(".signupdiv").fadeIn(0);
		$(".logindiv").fadeOut(0);
	});

	$(".loginspan").click(function(){
	
		$(this).addClass('active').siblings().removeClass('active');
		$(".logindiv").fadeIn(0);
		$(".signupdiv").fadeOut(0);
	});


	var commentHeight = 0;
	$("span.user-profile-comment-user").each(function(){
		 if ($(this).height() > commentHeight) {
	    commentHeight = $(this).height();
	  }
	});

	$('div.user-profile-comment').css('min-height', commentHeight + 15 + 'px');

	$('.item-name-form').keyup(function() {
		$('.item-name-div').text($('.item-name-form').val());
	});
	$('div.user-profile-comment').css('min-height', commentHeight + 15 + 'px');



	$('.item-name-form').keyup(function() {
		$('.item-name-div').text($('.item-name-form').val());
	});

	$('.item-desc-form').keyup(function() {
		$('.item-desc-div').text($('.item-desc-form').val());
	});

	$('.item-price-form').keyup(function() {
		$('.item-price-div').text('$' + $('.item-price-form').val());
	});

	$('.item-status-form').keyup(function() {
		$('.item-status-div').text($('.item-status-form').val());
	});

	$('.item-country-form').keyup(function() {
		$('.item-country-div').text($('.item-country-form').val());
	});

	$('.item-category-form').keyup(function() {
		$('.item-category-div').text($('.item-category-form').val());
	});



	$(document).ready(function() {

	    var docHeight = $(window).height();
	    var footerHeight = $('#footer').height();
	    var footerTop = $('#footer').position().top + footerHeight;

	    if (footerTop < docHeight)
	        $('#footer').css('margin-top', 10+ (docHeight - footerTop) + 'px');
	});