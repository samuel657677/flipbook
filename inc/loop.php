<?php
/**
 * Return default placeholder thumbnail if post doesn't have post thumbnail
 */
function franz_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ){
	global $franz_no_default_thumb;
	
	if ( in_array( $size, array( 'thumbnail', 'franz-medium' ) ) ) {
		if ( ! $html && ! $franz_no_default_thumb ) {
			$html = '<span class="generic-thumb ' . esc_attr( $size ) . '"><i class="fa fa-camera"></i></span>';
		} else if ( $size == 'thumbnail' ) {
			$html = str_replace( 'class="', 'class="thumbnail ', $html );
		}
	}
	
	if ( in_array( $size, array( 'post-thumbnail', 'franz-medium' ) ) ) {
		$html = str_replace( 'class="', 'class="img-responsive ', $html );
	}
	
	return $html;
}
add_filter( 'post_thumbnail_html', 'franz_post_thumbnail_html', 10, 5 );


/**
 * Determine the correct template part to load
 */
function franz_get_template_part( $p1, $p2 = '' ){
	
	if ( $p1 == 'loop' && ! $p2 ) {
		$p2 = get_post_format();
		$filename = '/formats/loop-' . $p2 . '.php';
		if ( $p2 != 'standard' && ( file_exists( FRANZ_ROOTDIR . $filename ) || file_exists( FJ_CHILDDIR . $filename ) ) ) $p1 = 'formats/loop';
		else $p2 = '';
	}
	
	get_template_part( $p1, $p2 );
}


/**
 * Add custom classes to posts
 */
function franz_body_class( $classes ){
	global $franz_settings;
	if ( is_singular() || is_author() ) $classes[] = 'singular';
	else $classes[] = 'non-singular';
	
	if ( ! is_singular() && $franz_settings['tiled_posts'] ) $classes[] = 'tiled-posts';
	
	$classes[] = franz_column_mode();
	
	return $classes;
}
add_filter( 'body_class', 'franz_body_class' );


/**
 * Determine the main content area class for layout
 */
function franz_main_content_classes( $classes = array() ) {

	$column_mode = franz_column_mode();
	if ( stripos( $column_mode, 'left-sidebar' ) !== false ) $classes[] = 'col-md-push-3';
	if ( stripos( $column_mode, 'one-column' ) !== false ) {
		$classes[] = 'col-md-12';
		$classes = array_diff( $classes, array( 'col-md-9') );
	}
	
	echo implode( ' ', $classes );
}


/**
 * Entry meta
 */
function franz_entry_meta(){
	$post_id = get_the_ID(); global $franz_settings;
	$meta = array();
	
	/* Don't get meta for pages */
	if ( 'page' == get_post_type( $post_id ) ) return;
	
	/* Print button */
	if ( $franz_settings['print_button'] && is_singular() ) {
		$meta['print'] = array(
			'class'	=> 'print-button',
			'meta'	=> '<a href="javascript:print();" title="' . esc_attr__( 'Print this page', 'franz-josef' ) . '"><i class="fa fa-print"></i></a>',
		);
	}
	
	/* Post date */
	$meta['date'] = array(
		'class'	=> 'date',
		'meta'	=> '<a href="' . esc_url( get_permalink( $post_id ) ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</a>',
	);
	
	/* Post author and categories */
	if ( ! $franz_settings['hide_post_cat'] ) {
		$cats = get_the_category(); $categories = array();
		if ( $cats ) {
			foreach ( $cats as $cat ) $categories[] = '<a class="term term-' . esc_attr( $cat->taxonomy ) . ' term-' . esc_attr( $cat->term_id ) . '" href="' . esc_url( get_term_link( $cat->term_id, $cat->taxonomy ) ) . '">' . $cat->name . '</a>';
		}
		if ( $categories ) $categories = '<span class="terms">' . implode( ', ', $categories ) . '</span>';
	}
	if ( ! $franz_settings['hide_post_author'] ) {
		$author = '<span class="author"><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . get_the_author_meta( 'display_name' ) . '</a></span>';
	}
	
	if ( $categories && $author ) $byline = sprintf( __( 'By %1$s under %2$s', 'franz-josef' ), $author, $categories );
	elseif ( $categories ) $byline = sprintf( __( 'Filed under %2$s', 'franz-josef' ), $author, $categories );
	elseif ( $author ) $byline = sprintf( __( 'By %s', 'franz-josef' ), $author );
	else $byline = false;
	
	if ( $byline ) $meta['byline'] = array( 'class'	=> 'byline', 'meta'	=> $byline );
	
	/* Comments link */
	if ( franz_should_show_comments( $post_id ) ) {
		$comment_count = get_comment_count( $post_id );
		$comment_text = ( $comment_count['approved'] ) ? sprintf( _n( '%d comment', '%d comments', $comment_count['approved'], 'franz-josef' ), $comment_count['approved'] ) : __( 'Leave a reply', 'franz-josef' );
		$comments_link = ( $comment_count['approved'] ) ? get_comments_link() : str_replace( '#comments', '#respond', get_comments_link() );
		$meta['comments'] = array(
			'class'	=> 'comments-count',
			'meta'	=> '<a href="' . esc_url( $comments_link ) . '">' . $comment_text . '</a>',
		);
	}
	
	/* Post tags */
	$tags = get_the_tags();
	if ( $tags ) {
		$html = '';
		if ( count( $tags ) > 1 ) $html .= '<i class="fa fa-tags"></i>';
		else $html .= '<i class="fa fa-tag"></i>';
		
		$tag_links = array();
		foreach ( $tags as $tag ) $tag_links[] = '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . $tag->name . '</a>';
		$html .= implode( ', ', $tag_links );
		
		if ( $html ) $meta['tags'] = array(
			'class'	=> 'entry-tags',
			'meta'	=> $html
		);
	}
	
	$meta = apply_filters( 'franz_entry_meta', $meta, $post_id );
	if ( ! $meta ) return;
	?>
    <ul class="entry-meta">
    	<?php foreach ( $meta as $item ) : ?>
        <li class="<?php echo esc_attr( $item['class'] ); ?>"><?php echo $item['meta']; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php
}


/**
 * Entry meta for author page
 */
function franz_author_entry_meta(){
	$meta = array();
	$post_id = get_the_ID();
	
	$meta['date'] = array(
		'class'	=> 'date updated',
		'meta'	=> '<a href="' . esc_url( get_permalink( $post_id ) ) . '">' . get_the_time( get_option( 'date_format' ) ) . '</a>',
	);
	
	$comment_count = get_comment_count( $post_id );
	$comment_text = ( $comment_count['approved'] ) ? $comment_count['approved'] : __( 'Leave a reply', 'franz-josef' );
	$comments_link = ( $comment_count['approved'] ) ? get_comments_link() : str_replace( '#comments', '#respond', get_comments_link() );
	$meta['comments'] = array(
		'class'	=> 'comments-count',
		'meta'	=> '<a href="' . esc_url( $comments_link ) . '"><i class="fa fa-comment"></i> ' . $comment_text . '</a>',
	);
	
	$meta = apply_filters( 'franz_author_entry_meta', $meta );
	if ( ! $meta ) return;
	?>
    <ul class="entry-meta">
    	<?php foreach ( $meta as $item ) : ?>
        <li class="<?php echo esc_attr( $item['class'] ); ?>"><?php echo $item['meta']; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php
}


/**
 * Add structured data markup
 */
function franz_structured_data_markup(){
	global $post, $franz_settings;
	
	$markup = array();
	
	/* Date published and updated */
	$markup[] = '<span class="published"><span class="value-title" title="' . date( 'c', strtotime( $post->post_date_gmt ) ) . '" /></span>';
	$markup[] = '<span class="updated"><span class="value-title" title="' . date( 'c', strtotime( $post->post_modified_gmt ) ) . '" /></span>';
	
	/* Author */
	$markup[] = '<span class="vcard author"><span class="fn nickname"><span class="value-title" title="'. get_the_author_meta( 'display_name' ) . '" /></span></span>';
	
	$markup = apply_filters( 'franz_structured_data_markup', $markup );
	if ( ! $markup ) return;
	
	echo implode( "\n", $markup );
}


/**
 * Control the excerpt length
*/
function franz_modify_excerpt_length( $length ) {
	global $franz_excerpt_length;

	if ( $franz_excerpt_length ) return $franz_excerpt_length;
	else return $length;
}
add_filter( 'excerpt_length', 'franz_modify_excerpt_length' );


/**
 * Set the excerpt length
*/
function franz_set_excerpt_length( $length ){
	if ( ! $length ) return;
	global $franz_excerpt_length;
	$franz_excerpt_length = $length;
}


/**
 * Reset the excerpt length
*/
function franz_reset_excerpt_length(){
	global $franz_excerpt_length;
	unset( $franz_excerpt_length );
}


if ( ! function_exists( 'franz_page_navigation' ) ) :
/**
 * List subpages of current page
 */
function franz_page_navigation(){
	$current = get_the_ID();
	$ancestors = get_ancestors( $current, 'page' );
	if ( $ancestors ) $parent = $ancestors[0];
	else $parent = $current;
	
	$args = array(
		'post_type'			=> array( 'page' ),
		'posts_per_page'	=> -1,
		'post_parent'		=> $parent,
		'orderby'			=> 'title',
		'order'				=> 'ASC'
	);
	$children = new WP_Query( apply_filters( 'franz_page_navigation_args', $args ) );
	
	if ( $children->have_posts() ) :
	?>
        <div class="widget">
            <h3 class="section-title-sm"><?php _e( 'In this section', 'franz' ); ?></h3>
            <div class="list-group page-navigation">
            	<a class="list-group-item parent <?php if ( $parent == $current ) echo 'active'; ?>" href="<?php echo esc_url( get_permalink( $parent ) ); ?>"><?php echo get_the_title( $parent ); ?></a>
                <?php while ( $children->have_posts() ) : $children->the_post(); ?>
                <a class="list-group-item <?php if ( get_the_ID() == $current ) echo 'active'; ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <?php endwhile; ?>
            </div>
        </div>
    <?php 
	endif; wp_reset_postdata(); 
}
endif;


if ( ! function_exists( 'franz_posts_nav' ) ) :
/**
 * Posts navigation
 */
function franz_posts_nav( $args = array() ){
	global $wp_query, $franz_settings;
	$defaults = apply_filters( 'franz_posts_nav_defaults', array(
		'current'			=> max( 1, get_query_var( 'paged' ) ),
		'total'				=> $wp_query->max_num_pages,
		'base'				=> '',
		'format'			=> '',
		'add_fragment'		=> '',
		'type'				=> 'post',
		'prev_text'			=> '&laquo;',
		'next_text' 		=> '&raquo;'
	) );
	$args = wp_parse_args( $args, $defaults );
	
	$paginate_args = array(
		'current' 		=> $args['current'],
		'total'			=> $args['total'],
		'prev_text' 	=> $args['prev_text'],
		'next_text' 	=> $args['next_text'],
		'type'      	=> 'array',
		'echo'			=> false,
		'add_fragment'	=> $args['add_fragment'],
	);
	if ( $args['base'] ) $paginate_args['base'] = $args['base'];
	if ( $args['format'] ) $paginate_args['format'] = $args['format'];
	
	if ( $args['type'] == 'comment' ) $links = paginate_comments_links( apply_filters( 'franz_comments_nav_args', $paginate_args ) );
	else $links = paginate_links( apply_filters( 'franz_posts_nav_args', $paginate_args ) );
	
	if ( $links ) :
	?>
		<ul class="pagination">
			<?php if ( $args['current'] == 1 ) : ?><li class="disabled"><span class="page-numbers"><?php echo $args['prev_text']; ?></span></li><?php endif; ?>
			<?php 
				foreach ( $links as $link ) {
					if ( stripos( $link, 'current' ) !== false ) $link = '<li class="active">' . $link . '</li>';
					else $link = '<li>' . $link . '</li>';
					echo $link;
				}
			?>
		</ul>
	<?php
		do_action( 'franz_posts_nav', $args );
	endif;
}
endif;


if ( ! function_exists( 'franz_comments_nav' ) ) :
/**
 * Comments pagination
 */
function franz_comments_nav( $args = array() ){
	
	if ( ! get_option( 'page_comments' ) ) return;
	
	$defaults = apply_filters( 'franz_comments_nav_defaults', array(
		'current'			=> max( 1, get_query_var('cpage') ),
		'total'				=> get_comment_pages_count(),
		'base'				=> add_query_arg( 'cpage', '%#%' ),
		'format'			=> '',
		'add_fragment' 		=> '#comments',
		'prev_text'			=> __( '&laquo; Prev', 'franz-josef' ),
		'next_text' 		=> __( 'Next &raquo;', 'franz-josef' ),
		'type'				=> 'comment',
	) );
	$args = wp_parse_args( $args, $defaults );
	franz_posts_nav( $args );
}
endif;


/**
 * Add pagination links in pages
 */
function franz_link_pages(){
	$args = array(
		'before'           => '<div class="page-links"><h4 class="section-title-sm">' . __( 'Pages:', 'franz-josef' ) . '</h4><ul class="pagination"><li><span class="page-numbers">',
		'after'            => '</span></li></ul></div>',
		'link_before'      => '',
		'link_after'       => '',
		'next_or_number'   => 'number',
		'separator'        => '</span></li><li><span class="page-numbers">',
		'pagelink'         => '%',
		'echo'             => 0
	); 
	$pages_link = wp_link_pages( apply_filters( 'franz_link_pages_args', $args ) );
	
	$pages_link = explode( '</li>', $pages_link );
	foreach ( $pages_link as $i => $link ) {
		if ( stripos( $link, '<a ' ) === false ) {
			$pages_link[$i] = str_replace( '<li', '<li class="active"', $link );
			break;
		}
	}
	echo implode( '</li>', $pages_link );
}


/**
* Override the output of the submit button on forms, useful for
* adding custom classes or other attributes.
*
* @param string $button An HTML string of the default button
* @param array $form An array of form data
* @return string $button
*
* @filter gform_submit_button
*/
function franz_gform_submit_button( $button, $form ) {
	$button = sprintf(
		'<input type="submit" class="btn btn-lg btn-default" id="gform_submit_button_%d" value="%s">',
		absint( $form['id'] ),
		esc_attr( $form['button']['text'] )
	);
	 
	return $button;
}
add_filter( 'gform_submit_button', 'franz_gform_submit_button', 10, 2 );


/**
 * Allows post queries to sort the results by the order specified in the post__in parameter. 
 * Just set the orderby parameter to post__in!
 *
 * Based on the Sort Query by Post In plugin by Jake Goldman (http://www.get10up.com)
*/
function franz_sort_query_by_post_in( $sortby, $thequery ) {
	global $wpdb;
	if ( ! empty( $thequery->query['post__in'] ) && isset( $thequery->query['orderby'] ) && $thequery->query['orderby'] == 'post__in' )
		$sortby = "find_in_set(" . $wpdb->prefix . "posts.ID, '" . implode( ',', $thequery->query['post__in'] ) . "')";
	
	return $sortby;
}
add_filter( 'posts_orderby', 'franz_sort_query_by_post_in', 9999, 2 );


if ( ! function_exists( 'franz_single_author_bio' ) ) :
/**
 * Print out author's bio
 */
function franz_single_author_bio(){
	global $franz_settings;
	if ( ! is_singular() || $franz_settings['hide_author_bio'] ) return;
	?>
    <div class="entry-author">
        <div class="row">
            <div class="author-avatar col-sm-2">
            	<a href="<?php $author_id = get_the_author_meta( 'ID' ); echo esc_url( get_author_posts_url( $author_id ) ); ?>" rel="author">
					<?php echo get_avatar( $author_id, 125 ); ?>
                </a>
            </div>
            <div class="author-bio col-sm-10">
                <h3 class="section-title-sm"><?php echo get_the_author_meta( 'display_name' ); ?></h3>
                <?php echo wpautop( get_the_author_meta( 'description' ) ); franz_author_social_links( $author_id ); ?>
            </div>
        </div>
    </div>
    <?php
}
endif;


if ( ! function_exists( 'franz_author_avatar' ) ) :
/**
 * Print out post author's avatar
 */
function franz_author_avatar(){
	global $franz_settings;
	if ( $franz_settings['hide_author_avatar'] ) return;
	?>
    <p class="entry-author-avatar">
        <a href="<?php $author_id = get_the_author_meta( 'ID' ); echo esc_url( get_author_posts_url( $author_id ) ); ?>" rel="author">
            <?php echo get_avatar( $author_id, 50 ); ?>
        </a>
    </p>
    <?php
}
endif;


/**
 * Improves the WordPress default excerpt output. This function will retain HTML tags inside the excerpt.
 * Based on codes by Aaron Russell at http://www.aaronrussell.co.uk/blog/improving-wordpress-the_excerpt/
*/
function franz_improved_excerpt( $text ){
	global $franz_settings, $post;
	
	$raw_excerpt = $text;
	if ( '' == $text ) {
		$text = get_the_content( '' );
		$text = strip_shortcodes( $text );
		$text = apply_filters( 'the_content', $text);
		$text = str_replace( ']]>', ']]&gt;', $text);
		
		/* Remove unwanted JS code */
		$text = preg_replace( '@<script[^>]*?>.*?</script>@si', '', $text);
		
		/* Strip HTML tags, but allow certain tags */
		$text = strip_tags( $text, $franz_settings['excerpt_html_tags'] );

		$excerpt_length = apply_filters( 'excerpt_length', 55 );
		$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[...]' );
		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count( $words) > $excerpt_length ) {
			array_pop( $words);
			$text = implode( ' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode( ' ', $words);
		}
	}
	
	// Try to balance the HTML tags
	$text = force_balance_tags( $text );
	
	return apply_filters( 'wp_trim_excerpt', $text, $raw_excerpt);
}


/**
 * Only use the custom excerpt trimming function if user decides to retain html tags.
*/
if ( $franz_settings['excerpt_html_tags'] ) {
	remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
	add_filter( 'get_the_excerpt', 'franz_improved_excerpt' );
}


/**
 * Remove additional padding from captioned images
 */
function franz_cleaner_caption( $output, $attr, $content ) {

	/* We're not worried abut captions in feeds, so just return the output here. */
	if ( is_feed() ) return $output;

	/* Set up the default arguments. */
	$defaults = array(
		'id' 		=> '',
		'align' 	=> 'alignnone',
		'width' 	=> '',
		'caption' 	=> ''
	);

	/* Merge the defaults with user input. */
	$attr = shortcode_atts( $defaults, $attr );

	/* If the width is less than 1 or there is no caption, return the content wrapped between the [caption]< tags. */
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) ) return $content;

	/* Set up the attributes for the caption <div>. */
	$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="wp-caption ' . esc_attr( $attr['align'] ) . '"';
	$attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px"';

	/* Open the caption <div>. */
	$output = '<div' . $attributes .'>';

	/* Allow shortcodes for the content the caption was created for. */
	$output .= do_shortcode( $content );

	/* Append the caption text. */
	$output .= '<p class="wp-caption-text">' . $attr['caption'] . '</p>';

	/* Close the caption </div>. */
	$output .= '</div>';

	/* Return the formatted, clean caption. */
	return $output;
}
add_filter( 'img_caption_shortcode', 'franz_cleaner_caption', 10, 3 );