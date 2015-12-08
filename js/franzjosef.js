jQuery(document).ready(function($) {
	
	/* Front page slider */
	if ( ! franzJS.sliderDisable ) {
		$('.carousel').carousel({
			interval: franzJS.sliderInterval * 1000,
			pause	: 'hover'
		});
		$('.carousel .slide-title').each(function(){
			charLimit = 17;
			defaultSize = 5.2;
			minSize = 2.5;
			if ($(this).text().length > charLimit) {
				fontSize = (17 / $(this).text().length) * defaultSize;
				if (fontSize < minSize ) fontSize = minSize;
				$(this).css('font-size',  fontSize + 'vw');
			}
		});
		
		/* Fix Bootstrap Carousel not pausing on hover */
		$(document).on( 'mouseenter hover', '.carousel', function() {
			$(this).carousel( 'pause' );
		});
		$(document).on( 'mouseleave', '.carousel', function() {
			$(this).carousel( 'cycle' );
		});
	}
	
	/* Make the navbar smaller as visitor scrolls down */
	$(window).scroll(function() {
		var height = $(window).scrollTop();
		if ( height > 85 ) $('body').addClass('navbar-pinned');
		else $('body').removeClass('navbar-pinned');
	});
	
	/* Changes main menu and logo to full width if they are too wide */
	if ( ( $('.header .logo').outerWidth(true) + $('.header .navbar-nav').outerWidth(true) ) > $('.header').width()) {
		$('.header .navbar-nav').removeClass('navbar-right');
		$('.header').addClass('wide-nav');
		$('body').css('padding-top', $('.header').outerHeight() + 'px' );
	}
	
	/* Go to parent link of a dropdown menu if clicked when dropdown menu is open */
	$('.dropdown-toggle[href]').click(function(){
		if ($(this).parent().hasClass('open') || $(this).parent().hasClass('dropdown-submenu') ) window.location = $(this).attr('href');
	});	
	
	/* Masonry for posts stack */
	$postsStack = $('.posts-list .items-container');
	$postsStack.imagesLoaded(function(){
		$postsStack.masonry({
			itemSelector: '.item-wrap',
			columnWidth: 0.25
		});
	});
	
	/* Masonry for posts listing pages */
	if ( franzJS.isTiledPosts ) {
		$postsList = $('.tiled-posts .entries-wrapper');
		$postsList.imagesLoaded(function(){
			$postsList.masonry({
				itemSelector: '.item-wrap',
				columnWidth: 0.25
			});
		});
	}
	
	/* Smooth scroll to comment form */
	$('.comment-form-jump a').click(function(e){
		e.preventDefault();
		$('html, body').animate({scrollTop: $($(this).attr('href')).offset().top - 100}, 1000);
	});
	
	/* Add .label and .label-default classes to RSS widget dates */
	$('.widget_rss .rss-date').addClass('label label-default');
	
	/* Show gallery image captions when viewed on touch devices */
	var isTouchDevice = 'ontouchstart' in document.documentElement;
    if (isTouchDevice) $('.gallery-caption').css('opacity',1);
	
	/* Automatically make tables in .entry-content responsive */
	if ( ! franzJS.disableResponsiveTables ){
		$('.entry-content table:not(.non-responsive)').each(function(){
			$(this).addClass('table');
			$(this).wrap('<div class="table-responsive"></div>');
		});
	}
});