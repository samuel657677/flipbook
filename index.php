<?php get_header(); 
wp_enqueue_style('thickbox'); // call to media files in wp
wp_enqueue_script('thickbox');
wp_enqueue_script( 'media-upload'); 


// load script to admin
function wpss_admin_js() {
     $siteurl = get_option('siteurl');
     $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/js/admin_script.js';
     echo "<script type='text/javascript' src='$url'></script>"; 
}
 add_action('admin_head', 'wpss_admin_js');
?>
	
	
	<div class="container main">
    	<div class="row">
        	<div class="main col-md-9">
                <?php do_action( 'franz_index_top' ); ?>
                <div class="entries-wrapper">
                <?php 
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							franz_get_template_part( 'loop' );
						}
					}
				?>
                </div>
                <?php franz_posts_nav(); ?>
                <?php do_action( 'franz_index_bottom' ); ?>
            </div>
            
            <?php get_sidebar(); ?>
            
        </div>
    </div>

<?php get_footer(); ?>