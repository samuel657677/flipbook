<?php 
	global $franz_settings; 
	
	if ( $franz_settings['tiled_posts'] ) $col = 'col-sm-6';
	else $col = 'col-md-12';
?>
<div class="item-wrap <?php echo $col; ?>">
    <div <?php post_class(); ?> id="entry-<?php the_ID(); ?>">
        
        <?php 
            $post_meta = get_post_custom();
            $has_embed = false;
            foreach ( $post_meta as $key => $meta ) {
                if ( stripos( $key, '_oembed_' ) === 0 && strlen( $key ) == 40 ) {
					if ( trim( $meta[0] ) == '{{unknown}}' ) continue;
                    $has_embed = true;
                    $embed_code = $meta[0];
                    break;
                }
            }
            
            if ( $has_embed || has_post_thumbnail() ) : ?>
            
                <div class="featured-image">
                    <?php if ( $has_embed ) : echo $embed_code; else : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                    <?php endif; ?>
                 </div>
                <?php do_action( 'franz_loop_thumbnail' ); ?>
            
        <?php endif; ?>
        
        <div class="title-wrap">
            <?php if ( is_singular() ) : ?><h1 class="entry-title"><?php else : ?><h2 class="entry-title"><?php endif; ?>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <?php if ( is_singular() ) : ?></h1><?php else : ?></h2><?php endif; ?>
            <div class="entry-meta-wrap"><?php franz_entry_meta(); ?></div>
            <?php franz_author_avatar(); ?>
        </div>
        
         <div class="entry-content">
            <?php 
                if ( $franz_settings['archive_full_content'] ) : the_content();
                else : the_excerpt(); 
            ?>
            <p class="read-more"><a class="btn btn-lg btn-default" href="<?php the_permalink();?>"><?php _e( 'Read More', 'franz-josef' ); ?></a></p>
            <?php endif; ?>
        </div>
        
        <?php do_action( 'franz_loop' ); ?>
    </div>
</div>