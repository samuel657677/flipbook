<?php
/**
 * Settings Validator
 * 
 * This file defines the function that validates the theme's options
 * upon submission.
*/
function franz_settings_validator( $input ){
	
	global $franz_options_validated;
	if ( $franz_options_validated == true ) return $input;
	
	if ( !isset( $_POST['franz_uninstall'] ) ) {
		global $franz_defaults, $allowedposttags;
		
		// Add <script> and <ins> tags to the allowed tags in code
		$allowedposttags = array_merge( $allowedposttags, array( 'script' => array( 'type' => array(), 'src' => array() ), 'ins' => array( 'class'=>array(), 'id'=>array(), 'style'=>array(), 'title'=>array(), 'data-ad-client'=>array(), 'data-ad-slot'=>array(), 'alt'=>array() ) ) );
		
		if (isset( $_POST['franz_general'] ) ) {
		
			/* =Slider Options 
			--------------------------------------------------------------------------------------*/
                     
			// Slider category
			if ( isset( $input['slider_type'] ) && !in_array( $input['slider_type'], array( 'latest_posts', 'random', 'posts_pages', 'categories' ) ) ){
				unset( $input['slider_type'] );
				add_settings_error( 'franz_options', 2, __( 'ERROR: Invalid category to show in slider.', 'franz-josef' ) );
			} elseif ( $input['slider_type'] == 'posts_pages' && empty ( $input['slider_specific_posts'] ) ) {
				unset( $input['slider_type'] );
				add_settings_error( 'franz_options', 2, __( 'ERROR: You must specify the posts/pages to be displayed when you have "Show specific posts/pages" selected for the slider.', 'franz-josef' ) );
                        } elseif ( $input['slider_type'] == 'categories' && empty ( $input['slider_specific_categories'] ) ) {
				unset( $input['slider_type'] );
				add_settings_error( 'franz_options', 2, __( 'ERROR: You must have selected at least one category when you have "Show posts from categories" selected for the slider.', 'franz-josef' ) );
			}                        
			// Posts and/or pages to display
			if (isset( $input['slider_type'] ) && $input['slider_type'] == 'posts_pages' && isset( $input['slider_specific_posts'] ) ) {
				$input['slider_specific_posts'] = str_replace( ' ', '', $input['slider_specific_posts'] );
			}
			// Categories to display posts from
			if (isset( $input['slider_type'] ) && $input['slider_type'] == 'categories' && isset( $input['slider_specific_categories'] ) && is_array( $input['slider_specific_categories'] ) ){
				if ( in_array ( false, array_map( 'ctype_digit', (array) $input['slider_specific_categories'] ) ) ) {
					unset( $input['slider_specific_categories'] );
					add_settings_error( 'franz_options', 2, __( 'ERROR: Invalid category selected for the slider categories.', 'franz-josef' ) );
				}
			}
			// Exclude categories from posts listing
			$input = franz_validate_dropdown( $input, 'slider_exclude_categories', array( 'disabled', 'homepage', 'everywhere' ), __( 'ERROR: Invalid option for the slider categories exclusion from posts listing is specified.', 'franz-josef' ) );
			// Display posts from categories in random order
			$input['slider_random_category_posts'] = (isset( $input['slider_random_category_posts'] ) ) ? true : false;
			
			// Slider content
			if ( isset( $input['slider_content'] ) && ! in_array( $input['slider_content'], array( 'excerpt', 'full_content' ) ) ){
				unset( $input['slider_content'] );
				add_settings_error( 'franz_options', 2, __( 'ERROR: Invalid option for slider content.', 'franz-josef' ) );
			}
			// Number of posts to display
			if ( !empty( $input['slider_postcount'] ) && !ctype_digit( $input['slider_postcount'] ) ){
				unset( $input['slider_postcount'] );
				add_settings_error( 'franz_options', 2, __( 'ERROR: The number of posts to displayed in the slider must be a an integer value.', 'franz-josef' ) );
			}
			// Slider height
			$input = franz_validate_digits( $input, 'slider_height', __( 'ERROR: The value for slider height must be an integer.', 'franz-josef' ) );
			// Slider speed
			$input = franz_validate_numeric( $input, 'slider_interval', __( 'ERROR: The value for slider interval must be a number.', 'franz-josef' ) );
			// Slider transition speed
			$input = franz_validate_numeric( $input, 'slider_trans_duration', __( 'ERROR: The value for slider transition duration must be a number.', 'franz-josef' ) );
			// Slider disable switch
			$input['slider_disable'] = (isset( $input['slider_disable'] ) ) ? true : false;
			
			
			/* =Front Page Options 
			--------------------------------------------------------------------------------------*/
			if ( ! empty( $input['frontpage_posts_cats'] ) ) {
				if ( in_array ( false, array_map( 'ctype_digit', (array) $input['frontpage_posts_cats'] ) ) ) {
					unset( $input['frontpage_posts_cats'] );
					add_settings_error( 'franz_options', 2, __( 'ERROR: Invalid category selected for the front page posts categories.', 'franz-josef' ) );
				}
			} else {
				$input['frontpage_posts_cats'] = $franz_defaults['frontpage_posts_cats'];
			}
			if ( isset( $input['front_page_blog_columns'] ) && ! in_array( $input['front_page_blog_columns'], array( 2, 3, 4 ) ) ){
				unset( $input['front_page_blog_columns'] );
				add_settings_error( 'franz_options', 2, __( 'ERROR: Invalid value for front page blog columns.', 'franz-josef' ) );
			}
			$input['disable_full_width_post'] = ( isset( $input['disable_full_width_post'] ) ) ? true : false;
			$input['disable_front_page_blog'] = ( isset( $input['disable_front_page_blog'] ) ) ? true : false;
                        
                        
			/* =Social Profiles
			--------------------------------------------------------------------------------------*/
			// Open in new window
			$input['social_media_new_window'] = (isset( $input['social_media_new_window'] ) ) ? true : false;			
			/* Social profiles */
			$social_profiles = ( ! empty( $input['social_profiles'] ) ) ? $input['social_profiles'] : array();
		
			if ( ! empty( $social_profiles ) ){
				$ix = 0;
				unset( $input['social_profiles'] );
				foreach ( $social_profiles as $social_icon ){
					if ( ! empty( $social_icon['type'] ) ){
						$input['social_profiles'][$ix]['type'] = $social_icon['type'];
						$input['social_profiles'][$ix]['name'] = $social_icon['name'];
						$input['social_profiles'][$ix]['title'] = esc_attr( $social_icon['title'] );
						$social_icon['url'] = esc_url_raw( $social_icon['url'] );
						if ( empty( $social_icon['url'] ) && $social_icon['type'] != 'rss' ){
							add_settings_error( 'franz_options', 2, sprintf( __( 'ERROR: Bad URL entered for the %s URL.', 'franz-josef' ), $social_icon['name'] ) );
						} else {
							$input['social_profiles'][$ix]['url'] = $social_icon['url'];
						}
						
						if ( $social_icon['type'] == 'custom' ){
							$input['social_profiles'][$ix]['icon_url'] = esc_url_raw( $social_icon['icon_url'] );
							
							$social_icon['icon_fa'] = trim( $social_icon['icon_fa'] );
							if ( stripos( $social_icon['icon_fa'], 'fa-' ) === 0 ) $social_icon['icon_fa'] = substr( $social_icon['icon_fa'], 3 );
							$input['social_profiles'][$ix]['icon_fa'] = $social_icon['icon_fa'];
						}  
						$ix++;
					}                                
				}
			} else {
				$input['social_profiles'] = array( 0 => false );
			}
			
			
			/* =Mentions Bar
			--------------------------------------------------------------------------------------*/
			if ( array_key_exists( 'brand_icons', $input ) ) {
				foreach ( $input['brand_icons'] as $index => $brand_icon ) {
					if ( ! $brand_icon['image_id'] ) {
						unset( $input['brand_icons'][$index] );
						continue;
					}
					$input['brand_icons'][$index]['link'] = esc_url_raw( $brand_icon['link'] );
				}
			} else {
				$input['brand_icons'] = $franz_defaults['brand_icons'];
			}
			
			 
			/* =Custom Head Tags
			--------------------------------------------------------------------------------------*/
			$input['head_tags'] = trim( $input['head_tags'] );
                        
			/* =Footer Options
			--------------------------------------------------------------------------------------*/
			
			// Copyright HTML
			$input['copyright_text'] = wp_kses_post( $input['copyright_text'] );
			// Hide copyright switch
			$input['hide_copyright'] = (isset( $input['hide_copyright'] ) ) ? true : false;
                        
                        
			/* =Print Options
			--------------------------------------------------------------------------------------*/  
			
			// Enable print CSS switch
			$input['print_css'] = (isset( $input['print_css'] ) ) ? true : false;
			// Show print button switch
			$input['print_button'] = (isset( $input['print_button'] ) ) ? true : false;
	
			
			
		} // Ends the General options
		
		
		if (isset( $_POST['franz_display'] ) ) {
			
                        
			/* =Post Display Options
			--------------------------------------------------------------------------------------*/                        
			$input['tiled_posts'] = (isset( $input['tiled_posts'] ) ) ? true : false;
			$input['hide_post_cat'] = (isset( $input['hide_post_cat'] ) ) ? true : false;
			$input['hide_post_tags'] = (isset( $input['hide_post_tags'] ) ) ? true : false;
			$input['hide_post_author'] = (isset( $input['hide_post_author'] ) ) ? true : false;
			$input['hide_author_avatar'] = (isset( $input['hide_author_avatar'] ) ) ? true : false;
			$input['hide_featured_image'] = (isset( $input['hide_featured_image'] ) ) ? true : false;
			$input['hide_author_bio'] = (isset( $input['hide_author_bio'] ) ) ? true : false;
			$input['disable_responsive_tables'] = (isset( $input['disable_responsive_tables'] ) ) ? true : false;
                        
			/* =Excerpts Display Options
			--------------------------------------------------------------------------------------*/     
			$input['archive_full_content'] = (isset( $input['archive_full_content'] ) ) ? true : false;					
			$input['excerpt_html_tags'] = trim( $input['excerpt_html_tags'] );
			
			/* =Footer Widget Display Options
			--------------------------------------------------------------------------------------*/
			// Number of columns to display
			$input = franz_validate_digits( $input, 'footerwidget_column', __( 'ERROR: The number of columns to be displayed in the footer widget must be a an integer value.', 'franz-josef' ) );
			
						
			/* =Miscellaneous Display Options
			--------------------------------------------------------------------------------------*/
			$input['disable_search_widget'] = ( isset( $input['disable_search_widget'] ) ) ? true : false;
			$input = franz_validate_url( $input, 'favicon_url', __( 'ERROR: Bad URL entered for the favicon URL.', 'franz-josef' ) );
			$input['disable_editor_style'] = ( isset( $input['disable_editor_style'] ) ) ? true : false;
			
			/* =Custom CSS Options 
			--------------------------------------------------------------------------------------*/
			$input['custom_css'] = wp_filter_nohtml_kses( wp_strip_all_tags( $input['custom_css'] ) );
		
		} // Ends the Display options
		
		
		if ( isset( $_POST['franz_generic'] ) ) {
			$tab = $_POST['franz_tab'];
			$input = apply_filters( 'franz_validate_options_' . $tab, $input );
		}
		

		
		$franz_options_validated = true;
		
		// Merge the new settings with the previous one (if exists) before saving
		$input = array_merge( get_option( 'franz_settings', array() ), (array) $input );
		
		/* Only save options that have different values than the default values */
		foreach ( $input as $key => $value ){
			if ( array_key_exists( $key, $franz_defaults ) && ( $franz_defaults[$key] === $value || $value === '' ) )
				unset( $input[$key] );
		}

		if ( $input ) {
			$input = array_merge( array( 'db_version' => $franz_defaults['db_version'] ), $input );
		} else {
			delete_option( 'franz_settings' );
			return false;
		}

	} // Closes the uninstall conditional
	
	return $input;
}


/**
 * Define the data validation functions
*/
function franz_validate_digits( $input, $option_name, $error_message ){
	global $franz_defaults;
	if ( ! isset( $input[$option_name] ) ) return $input;
	if ( ! empty( $input[$option_name] ) || '0' === $input[$option_name] ){
		if ( !ctype_digit( $input[$option_name] ) ) {
			$input[$option_name] = $franz_defaults[$option_name];
			add_settings_error( 'franz_options', 2, $error_message);
		}
	} else {
		$input[$option_name] = $franz_defaults[$option_name];
	}
	return $input;
}

function franz_validate_numeric( $input, $option_name, $error_message ){
	global $franz_defaults;
	if ( ! isset( $input[$option_name] ) ) return $input;
	if ( ! empty( $input[$option_name] ) || '0' === $input[$option_name] ){
		if ( ! is_numeric( $input[$option_name] ) ) {
			$input[$option_name] = $franz_defaults[$option_name];
			add_settings_error( 'franz_options', 2, $error_message);
		}
	} else {
		$input[$option_name] = $franz_defaults[$option_name];
	}
	return $input;
}

function franz_validate_column_width( $input, $column_mode, $option_name, $error_message ){
	global $franz_defaults;
	if ( '0' === $input['column_width'][$column_mode][$option_name] || ! empty( $input['column_width'][$column_mode][$option_name] ) ){
		$width = $input['column_width'][$column_mode][$option_name];
		if ( ! ( is_numeric( $width ) && $width >= 0 ) ) {
			$input['column_width'] = $franz_defaults['column_width'];
			$input['container_width'] = $franz_defaults['container_width'];
			$input['grid_width'] = $franz_defaults['grid_width'];
			add_settings_error( 'franz_options', 2, $error_message);
		}
	} else {
		$input['column_width'] = $franz_defaults['column_width'];
		$input['container_width'] = $franz_defaults['container_width'];
		$input['grid_width'] = $franz_defaults['grid_width'];
	}
	return $input;
}

function franz_validate_dropdown( $input, $option_name, $possible_values, $error_message ){
	if ( ! isset( $input[$option_name] ) ) return $input;
	if ( ! in_array( $input[$option_name], $possible_values ) ){
		unset( $input[$option_name] );
		add_settings_error( 'franz_options', 2, $error_message );
	}
	return $input;
}

function franz_validate_url( $input, $option_name, $error_message ) {
	global $franz_defaults;
	if ( ! empty( $input[$option_name] ) ){
		$input[$option_name] = esc_url_raw( $input[$option_name] );
		if ( $input[$option_name] == '' ) {
			$input[$option_name] = $franz_defaults[$option_name];
			add_settings_error( 'franz_options', 2, $error_message);
		}	
	}	
	return $input;
}

function franz_validate_colours( $options ) {
	global $franz_defaults;
	foreach ( $options as $key => $option ){
		if ( in_array( $key, array( 'colour_preset', 'colour_presets' ) ) ) continue;
		if ( ! empty( $option ) ){
			if ( stripos( $option, '#' ) !== 0 ) {
				$options[$key] = '#' . $option;
			}
			$options[$key] = franz_convert_shortform_colour( $options[$key] );
		} else {
			$option = $franz_defaults[$key];
		}
	}
	return $options;
}

function franz_convert_shortform_colour( $colour ){
	if ( strlen( $colour ) == 4 ) {
		$colour = preg_replace( '/\#([0-9a-fA-F] )([0-9a-fA-F] )([0-9a-fA-F] )/', '#$1$1$2$2$3$3$4', $colour );
	}
	
	return $colour;
}
