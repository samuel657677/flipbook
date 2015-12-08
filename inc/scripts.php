<?php
/**
 * Enqueue required scripts
 */
function franz_enqueue_scripts(){
	global $franz_settings;
	if ( ! is_admin() ) {
		
//		wp_enqueue_script( 'bootstrap', FRANZ_ROOTURI . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'franzjosef', FRANZ_ROOTURI . '/js/jquery_002.js', array( 'jquery', 'masonry' ) );
		wp_enqueue_script( 'html5shiv', FRANZ_ROOTURI . '/js/html5shiv/html5shiv.min.js' );
		wp_enqueue_script( 'respond', FRANZ_ROOTURI . '/js/respond.js/respond.min.js' );
		wp_enqueue_script( 'gridsterjs', FRANZ_ROOTURI . '/dist/jquery.gridster.js' );
        wp_enqueue_script( 'bootstrapjs', FRANZ_ROOTURI . '/js/bootstrap.min.js' );

		
//		wp_enqueue_style( 'foundation', FRANZ_ROOTURI . '/css/foundation.css' );
		wp_enqueue_style( 'gridster', FRANZ_ROOTURI . '/dist/jquery.gridster.css' );
		wp_enqueue_style( 'demo', FRANZ_ROOTURI . '/assets/demo.css' );

		//file-uploader
		wp_enqueue_style( 'style', FRANZ_ROOTURI . '/css/style.css' );
		//CSS to style the file input field as button and adjust the Bootstrap progress bars
//		wp_enqueue_style( 'fileupload', FRANZ_ROOTURI . '/css/jquery.fileupload.css' );
//		wp_enqueue_style( 'fileupload-ui', FRANZ_ROOTURI . '/css/jquery.fileupload-ui.css' );
		// CSS adjustments for browsers with JavaScript disabled 
//		wp_enqueue_style( 'fileupload-noscript', FRANZ_ROOTURI . '/css/jquery.fileupload-noscript.css' );
//		wp_enqueue_style( 'fileupload-ui-noscript', FRANZ_ROOTURI . '/css/jquery.fileupload-ui-noscript.css' );

		wp_enqueue_style( 'bootstrap', FRANZ_ROOTURI . '/bootstrap/css/bootstrap.min.css' );
		if ( is_rtl() ) wp_enqueue_style( 'bootstrap-rtl', FRANZ_ROOTURI . '/bootstrap-rtl/bootstrap-rtl.min.css', array( 'bootstrap' ) );
		wp_enqueue_style( 'font-awesome', FRANZ_ROOTURI . '/fonts/font-awesome/css/font-awesome.min.css' );
		wp_enqueue_style( 'franzjosef', get_stylesheet_uri(), array( 'bootstrap', 'font-awesome' ) );
		wp_enqueue_style( 'franzjosef-responsive', FRANZ_ROOTURI . '/responsive.css', array( 'bootstrap', 'font-awesome', 'franzjosef' ) );
		if ( is_rtl() ) wp_enqueue_style( 'franzjosef-responsive-rtl', FRANZ_ROOTURI . '/responsive-rtl.css', array( 'franzjosef' ) );
		wp_enqueue_style( 'franzjosef-google-fonts', franz_google_fonts_uri(), array() );
		
		if ( $franz_settings['print_css'] ) wp_enqueue_style( 'franzjosef-print', FRANZ_ROOTURI . '/print.css', array( 'franzjosef-responsive' ), false, 'print' );
	}
}
add_action( 'wp_enqueue_scripts', 'franz_enqueue_scripts' );


/**
 * Generate the stylesheet link for Google Fonts
 */
function franz_google_fonts_uri(){
	$query_args = array(
		'family' => 'Open+Sans:300italic,300,400,400italic,700,700italic|Montserrat:700',
		'subset' => 'latin,latin-ext',
	);
	return add_query_arg( apply_filters( 'franz_google_fonts', $query_args ), "//fonts.googleapis.com/css" );
}


/**
 * Add conditional comments for IE-specific scripts
 */
function franz_script_loader_tag( $tag, $handle ) {
    if ( in_array( $handle, array( 'html5shiv', 'respond' ) ) ) $tag = "<!--[if lt IE 9]>$tag<![endif]-->\n";
	
	return $tag;
}
add_filter( 'script_loader_tag', 'franz_script_loader_tag', 10, 2 );


/**
 * Localize scripts and add JavaScript data
 */
function franz_localize_scripts(){
	global $franz_settings;
	
	$js_object = array(
			/* General */
			'templateUrl'			=> esc_url( FRANZ_ROOTURI ),
			'isSingular'			=> is_singular(),
			
			/* Comments */
			'shouldShowComments'	=> franz_should_show_comments(),
			
			/* Slider */
			'sliderDisable'			=> $franz_settings['slider_disable'],
			'sliderInterval'		=> $franz_settings['slider_interval'],
			
			/* Display */
			'disableResponsiveTables'	=> $franz_settings['disable_responsive_tables'],
			'isTiledPosts'			=> $franz_settings['tiled_posts'],
	);
	wp_localize_script( 'franzjosef', 'franzJS', apply_filters( 'franz_js_object', $js_object ) );
}
add_action( 'wp_enqueue_scripts', 'franz_localize_scripts' );