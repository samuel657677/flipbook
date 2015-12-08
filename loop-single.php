<?php global $franz_settings; ?>
<div <?php post_class(); ?> id="entry-<?php the_ID(); ?>">
    <h1 class="entry-title"><?php the_title(); ?></h1>
    <div class="entry-meta-wrap"><?php franz_entry_meta(); ?></div>
    
    <?php 
		$has_featured_image = true;
		if ( $franz_settings['hide_featured_image'] ) $has_featured_image = false;
		if ( ! has_post_thumbnail() ) $has_featured_image = false;
		else {
			/* Check if featured image size is at least as wide as the content area width */
			global $content_width;
			$featured_image = wp_get_attachment_metadata( get_post_thumbnail_id() );
			if ( $featured_image['width'] < $content_width ) $has_featured_image = false;
		}
	
		if ( $has_featured_image ) :
	?>
    	<div class="featured-image"><?php the_post_thumbnail(); ?></div>
        <?php do_action( 'franz_loop_thumbnail' ); ?>
    <?php endif; ?>
    
    <div class="entry-content clearfix">
    	<?php the_content(); ?>
    </div>
    
    <?php 
		franz_single_author_bio(); 
		franz_related_posts();
		comments_template();
	?>
    <?php franz_structured_data_markup(); ?>
    <?php do_action( 'franz_loop_single' ); ?>
</div>