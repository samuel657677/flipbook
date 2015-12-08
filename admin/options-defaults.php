<?php
/**
 * Set the default values for all the settings. If no user-defined values
 * is available for any setting, these defaults will be used.
 */
global $franz_defaults;
$franz_defaults = array(
	/* Theme's DB version */
	'db_version' 		=> '1.0',
	
	/* Theme's options page hook suffix */
	'hook_suffix'		=> '',
	'hook_suffix_faq'	=> '',
	
	/* Options page tabs */
	'options_tabs'	=> array( 
		'general' 	=> __( 'General', 'franz-josef' ),
		'display' 	=> __( 'Display', 'franz-josef' ),
		'addons' 	=> __( 'Add-ons', 'franz-josef' ),
		'about' 	=> __( 'About Franz Josef', 'franz-josef' ),
	),
	
	/* All options toggle */
	'show_all_options' 	=> false,
	
	/* Default excerpt length */
	'excerpt_length' 	=> apply_filters( 'franz_excerpt_length', 55 ),

	/* Slider options */
	'slider_type' 				=> 'latest_posts', // latest_posts | random | posts_pages | categories
	'slider_specific_posts' 	=> '',
    'slider_specific_categories'=> '',
	'slider_exclude_categories'	=> 'disabled',
	'slider_random_category_posts' => false,
	'slider_content'			=> 'excerpt', // full_content
	'slider_postcount' 			=> 5,
	'slider_height' 			=> 500,
	'slider_interval'			=> 5,
	'slider_trans_duration' 	=> 0.7,
	'slider_disable'			=> false,
	
	/* Front page options */
	'frontpage_posts_cats' 		=> array(),
	'front_page_blog_columns'	=> 2, // 2 | 3 | 4
	'disable_full_width_post'	=> false,
	'disable_front_page_blog'	=> false,
                
	/* Social profiles */
	'social_media_new_window'   => false,	
	'social_profiles'           => array ( 
										array ( 
											'type'		=> 'rss',
											'name'		=> 'RSS',
											'title'		=> sprintf( __( 'Subscribe to %s\'s RSS feed', 'franz-josef' ), get_bloginfo( 'name' ) ),
											'url'		=> '',
											'icon_fa'	=> 'rss',
											'icon_url'	=> ''
										)
									),
									
	/* Mentions bar */
	'brand_icons'				=> array(),
	
	/* Custom head tags */
	'head_tags'					=> '',
	
	/* Footer options */
	'copyright_text' 			=> '',
	'hide_copyright' 			=> false,
    
    /* Print options */
    'print_css' 				=> false,
    'print_button' 				=> false,
    	
	/* Display Options Page */
			
	/* Posts Display options */
	'tiled_posts'				=> false,
	'hide_post_cat' 			=> false,
	'hide_post_tags' 			=> false,
	'hide_post_author' 			=> false,
	'hide_author_avatar' 		=> false,
	'hide_featured_image' 		=> false,
	'hide_author_bio' 			=> false,
	'disable_responsive_tables'	=> false,
	
	/* Excerpt options */
	'archive_full_content' 		=> false,
	'excerpt_html_tags' 		=> '',
	
	/* Footer widget options */
	'footerwidget_column' 		=> 4,
		
	/* Miscellaneous options */
	'favicon_url' 				=> '',
	'custom_css' 				=> '',
	'disable_editor_style' 		=> false,
	'disable_search_widget'		=> false,
	
	/* Miscellaneous switches and vars */
	'disable_credit' 			=> false,
);
