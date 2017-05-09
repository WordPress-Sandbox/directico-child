jQuery(function($){
	var i = 0;

	if( $.fn.mapify ) {
		$.each(additionallocations, function(k, v){
			if(v) {
				wpjmelf = {};
				$('.fieldset-job_location').append('<div class="field"><input type="text" value="'+additionallocations[k]['name']+'" name="additionallocation['+i+'][name]" id="job_location'+ i +'"><p class="remove_location">Remove Location</p></div>');
				wpjmelf.input = '#job_location' + i;
				wpjmelf.lat_input = 'additionallocation['+i+'][geo_lat]';
				wpjmelf.lng_input = 'additionallocation['+i+'][geo_lng]';
				wpjmelf.lat = additionallocations[k]['geo_lat'];
				wpjmelf.lng = additionallocations[k]['geo_lng'];
				$( '#job_location' + i ).mapify(wpjmelf);
				i++;
			}
		});
	}

	$('.fieldset-job_location').after('<p class="addLocation"> Add another location</p>');
	$('body').on('click', '.addLocation', function(){
		$('.fieldset-job_location').append('<div class="field"><input type="text" class="input-text" name="additionallocation['+i+'][name]" id="job_location'+ i +'"><p class="remove_location">Remove Location</p></div>');
		wpjmel.input = '#job_location' + i;
		wpjmel.lat_input = 'additionallocation['+i+'][geo_lat]';
		wpjmel.lng_input = 'additionallocation['+i+'][geo_lng]';
		$( '#job_location' + i ).mapify(wpjmel);
		i++;
	});

	$('body').on('click', '.remove_location', function(){
		$(this).parent().remove();
	})
	// START HIDE AND SHOW ELEMENTS DEPENDING ON WHETHER USER IS SCROLLING DOWN OR UP
	var didScroll;
	var lastScrollTop = 0;
	var delta = 5;
	var navbarHeight = $('.job_filters').outerHeight();
	$(window).scroll(function(event){
		didScroll = true;
	});
	setInterval(function() {
		if (didScroll) {
			hasScrolled();
			didScroll = false;
		}
	}, 250);
	function hasScrolled() {
		var st = $(this).scrollTop();
		// Make sure they scroll more than delta
		if(Math.abs(lastScrollTop - st) <= delta)
			return;
		// If they scrolled down and are past the job filters, add class .nav-up.
		// This is necessary so you never see what is "behind" the navbar.
		if (st > lastScrollTop && st > navbarHeight){
			// Scroll Down
			$('.job_filters').removeClass('nav-down').addClass('nav-up');
		} else {
			// Scroll Up
			if(st + $(window).height() < $(document).height()) {
				$('.job_filters').removeClass('nav-up').addClass('nav-down');
			}
		}
		lastScrollTop = st;
	}
	// START HIDE AND SHOW ELEMENTS DEPENDING ON WHETHER IT'S THE TOP OF THE PAGE OR NOT
	$(document).scroll(function() {
	   if($(window).scrollTop() === 0) {
		 // If it's the top of the page (only possible when it's been scrolled and then back up).
		 $(".page-listings .map").removeClass('scrolled-page-element').addClass('top-page-element');
		 $(".job_filters").removeClass('scrolled-page-element').addClass('top-page-element');
	   }
	   else {
		 // If user has scrolled down the page.
		 $(".page-listings .map").removeClass('top-page-element').addClass('scrolled-page-element');
		 $(".job_filters").removeClass('top-page-element').addClass('scrolled-page-element');
	   }
	});
	// IF BODY CONTAINS THESE CLASSES THEN, SHOW THE BACK TO TOP BUTTON
	if( document.body.className.match('page-listings') ) {
		$( ".scroll-back-to-top-wrapper" ).show();
	}
	if( document.body.className.match('tax-job_listing_category') ) {
		$( ".scroll-back-to-top-wrapper" ).show();
	}
});