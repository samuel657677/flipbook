		<?php do_action( 'franz_before_footer' ); ?>
        <div class="footer footer-inverse">
        	<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
                <div class="footer-lg">
                    <div class="container">
                        <div class="row">
                            <?php dynamic_sidebar( 'footer-widgets' ); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
            <div class="footer-menu-wrapper">
				<?php 
                    /* Footer menu */
                    $args = array(
                        'theme_location'=> 'footer-menu',
                        'container'     => false,
                        'menu_class'    => 'footer-menu container',
                        'echo'          => true,
                        'fallback_cb'   => false,
                        'items_wrap'    => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'         => 1
                    );
                    
                    wp_nav_menu( $args );
                ?>
            </div>
            <?php endif; ?>
            <?php do_action( 'franz_before_bottom_bar' ); ?>
            
        	<?php wp_footer(); ?>
        </div>
    </body>
</html>