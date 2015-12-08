<?php
/***************
 * Custom Menu * 
 ***************/
 
 
/**
 * Customise navigation menu codes
 */
class FJ_Walker_Nav_Menu extends Walker_Nav_Menu {
	
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
	}
	
}


/**
 * Modify menu item classes
 */
function franz_nav_menu_css_class( $classes, $item, $args ){
	
	if ( has_nav_menu( $args->theme_location ) ) {
		if ( in_array( 'current-menu-item', $classes ) ) $classes[] = 'active';
		if ( ( $item->menu_item_parent ) && in_array( 'menu-item-has-children', $classes ) ) $classes[] = 'dropdown-submenu';
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'franz_nav_menu_css_class', 10, 3 );


/**
 * Modify menu item attributes
 */
function franz_nav_menu_link_attributes( $atts, $item, $args ){
	
	$depth = ( is_object( $args ) ) ? $args->depth : $args['depth'];
	$classes = ( is_object( $item ) ) ? $item->classes : $item; // If this is default menu, $item is actually $css_class of the link
	
	if ( in_array( 'menu-item-has-children', $classes ) && $depth != 1 ) {
		$atts['class'] = 'dropdown-toggle';
		$atts['data-toggle'] = 'dropdown';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'franz_nav_menu_link_attributes', 10, 3 );


/**
 * Modify final menu item output
 */
function franz_walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
	
	$max_depth = ( is_object( $args ) ) ? $args->depth : $args['depth'];
	$classes = ( is_object( $item ) ) ? $item->classes : $item; // If this is default menu, $item is actually $css_class of the link
	
	if ( in_array( 'menu-item-has-children', $classes ) && ( ( $depth + 1 ) < $max_depth || $max_depth == 0 ) ) {
		$item_output = preg_replace( '/(<a\s[^>]*[^>]*>)(.*)(<\/a>)/siU', '$1$2 <i class="fa fa-chevron-down"></i>$3', $item_output );
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'franz_walker_nav_menu_start_el', 10, 4 );





/****************
 * Default Menu *
 ****************/
 
 
/**
 * Custom default menu based on wp_page_menu
 */
function franz_page_menu( $args = array() ) {
	$defaults = array('sort_column' => 'menu_order, post_title', 'menu_class' => 'menu', 'echo' => true, 'link_before' => '', 'link_after' => '');
	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'wp_page_menu_args', $args );
	$menu = '';
	$list_args = $args;

	// Show Home in the menu
	if ( ! empty($args['show_home']) ) {
		if ( true === $args['show_home'] || '1' === $args['show_home'] || 1 === $args['show_home'] )
			$text = __( 'Home', 'franz-josef' );
		else
			$text = $args['show_home'];
		$class = '';
		if ( is_front_page() && !is_paged() )
			$class = 'class="current_page_item"';
		$menu .= '<li ' . $class . '><a href="' . esc_url( home_url( '/' ) ) . '">' . $args['link_before'] . $text . $args['link_after'] . '</a></li>';
		// If the front page is a page, add it to the exclude list
		if (get_option('show_on_front') == 'page') {
			if ( !empty( $list_args['exclude'] ) ) {
				$list_args['exclude'] .= ',';
			} else {
				$list_args['exclude'] = '';
			}
			$list_args['exclude'] .= get_option('page_on_front');
		}
	}

	$list_args['echo'] = false;
	$list_args['title_li'] = '';
	$menu .= str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages($list_args) );

	if ( $menu ) $menu = '<ul class="' . esc_attr($args['menu_class']) . '">' . $menu . '</ul>';

	$menu = apply_filters( 'wp_page_menu', $menu, $args );
	if ( $args['echo'] ) echo $menu;
	else return $menu;
}


/**
 * Customise default navigation menu
 *
 * Improvements over the default Walker_Page class:
 * 1. Changed all classes to match Walker_Nav_Menu
 * 2. Added link attributes filter
 * 3. Added item output filter
 */
class FJ_Walker_Page extends Walker_Page {
	
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu dropdown-menu\">\n";
	}
	
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'menu-item', 'menu-item-' . $page->ID );

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'menu-item-has-children';
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current-menu-ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current-menu-item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current-menu-parent';
			}
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = 'current-menu-parent';
		}
		
		$css_class = apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page );
		$css_classes = implode( ' ', $css_class );

		if ( '' === $page->post_title ) {
			$page->post_title = sprintf( __( '#%d (no title)', 'franz-josef' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];
		
		
		$atts = apply_filters( 'nav_menu_link_attributes', array(), $css_class, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) if ( ! empty( $value ) ) $attributes .= ' ' . $attr . '="' . $value . '"';

		/** This filter is documented in wp-includes/post-template.php */
		$item_output = $indent . sprintf(
			'<li class="%s"><a href="%s" %s>%s%s%s</a>',
			$css_classes,
			esc_url( get_permalink( $page->ID ) ),
			$attributes,
			$args['link_before'],
			apply_filters( 'the_title', $page->post_title, $page->ID ),
			$args['link_after']
		);

		if ( ! empty( $args['show_date'] ) ) {
			if ( 'modified' == $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$item_output .= " " . mysql2date( $date_format, $time );
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $css_class, $depth, $args );
	}
	
}


/**
 * Modify menu item classes
 */
function franz_page_css_class( $classes, $page, $depth, $args, $current_page ){
	
	if ( $depth > 0 && in_array( 'menu-item-has-children', $classes ) ) $classes[] = 'dropdown-submenu';
	
	return $classes;
}
add_filter( 'page_css_class', 'franz_page_css_class', 10, 5 );