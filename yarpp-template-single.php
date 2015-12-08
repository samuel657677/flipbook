<?php 
/*
YARPP Template: Franz Josef Single
Author: Graphene Themes Solutions
Description: YARPP template for displaying related posts in Franz Josef theme's single posts page
*/
global $franz_settings;
if ( have_posts() ) :
?>
<div class="related-posts" <?php if ( ! $franz_settings['hide_author_bio'] ) echo 'style="border-top: none;margin-top:0"'; ?>>
	<?php do_action( 'franz_related_posts_top' ); ?>
    <h3 class="section-title-sm"><?php _e( 'Related posts', 'franz-josef' ); ?></h3>
    <div class="row">
    	<?php while ( have_posts() ) : the_post(); if ( get_post_type() == 'quote' ) : ?>
        	<div class="related-post col-sm-4 format-quote" id="related-post-<?php the_ID(); ?>">
                <blockquote><?php the_content(); ?></blockquote>
                <?php do_action( 'franz_related_post' ); ?>
            </div>
        <?php else : ?>
            <div class="related-post col-sm-4" id="related-post-<?php the_ID(); ?>">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'franz-medium' ); ?></a>
                <h4 class="item-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
                <?php do_action( 'franz_related_post' ); ?>
            </div>
        <?php endif; endwhile; ?>
    </div>
    <?php do_action( 'franz_related_posts_bottom' ); ?>
</div>
<?php endif; ?>