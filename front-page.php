<?php get_header(); 
    $login_flag = 0;
    if ( is_user_logged_in() ) {              
         $login_flag = 1;
    }
?>
    
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.navbar-toggle').click(function(){
			var form = jQuery('.nav_menu');
			form.slideToggle();
			return false;
		});
        jQuery('#make_flipbook').on('click',function(){
            jQuery('#make_flipbook').attr("href","<?php echo get_bloginfo('siteurl'); ?>/photoselect/");
        });
        jQuery('#unmake_flipbook').on('click',function(){
            $('#login_check').show();
//            alert("You have to login!");
        });
        $('.close').on('click',function(){
            $('#login_check').hide();
        });
	});
	</script>
        
    	<div class="navbar navbar-inverse navbar-fixed-top" style="background:#fff url(<?php echo get_template_directory_uri() ?>/images/header-back.png) repeat 0 0;background-size:cover;">
			<div class="navbar-header logo">
				<div id="header_first">
					<a href="<?php echo home_url(); ?>">
						<img src="<?php echo get_template_directory_uri() ?>/images/logo.png"/>
					</a>
				</div>     
				<?php if(wp_is_mobile()){ ?>
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">

				<span class="sr-only"><?php _e('Toggle navigation','zerif-lite'); ?></span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>

				<span class="icon-bar"></span>

				</button>

				<?php } ?>
				<nav id="" role="navigation" class="nav_menu"  <?php if(wp_is_mobile()){ echo 'style="display:none;"'; } ?>>
					<ul class="nav navbar-nav navbar-right responsive-nav main-nav-list" >
						<li class="tag_nav"><a href="<?php echo wp_login_url( get_permalink() ); ?>" class="tag_nava tag1" href="">Login</a>
						</li> 
						<li class="tag_nav"><a class="tag_nava tag2" href="">Basket</a>
						</li>
						<li class="tag_nav"><a class="tag_nava tag3" href="">Favorite</a> 
						</li>
						<li class="tag_nav"><a class="tag_nava tag4" href="">customer</a>
						</li>
					</ul>	
				</nav>
			</div>
			<div class="book_button">      
                <div class="alert alert-warning alert-dismissible fade in" id="login_check" role="alert" style="width:30%;margin-left:50%;display:none;">
                      <button type="button" class="close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <strong>You have to login!</strong> Best check yo self, you don't login now.
                </div>
				<div class="front_book" style="float:left;">
					<img src="<?php echo get_template_directory_uri() ?>/images/book.png"/>
				</div>
				<div class="front_button" style="float:left;">
					<div>
						<p>"You have some precious memory and beautiful love."</p>
						<p>-while a minute photobook complete</p>
					</div>
					<div style="width:120%">
						<a style="cursor:pointer;" id="<?php if($login_flag) echo "make_flipbook"; else echo "unmake_flipbook"; ?>"><img style="width:40%" src="<?php echo get_template_directory_uri() ?>/images/make_button.png"/></a>
						<a><img style="width:40%" src="<?php echo get_template_directory_uri() ?>/images/print_button.png"/></a>
					</div>
				</div>
			</div>
        </div>
    <?php //do_action( 'franz_front_page_top' ); ?>
    <?php // franz_stack( 'contra' ); ?>
    
    <?php if ( get_option( 'show_on_front' ) == 'page' ) : wp_reset_postdata(); ?>
    <div class="highlights static-front-page">
        <div class="container">
            <?php the_content(); ?>
            <?php do_action( 'franz_front_page_content' ); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php // franz_stack( 'gallery' ); ?>
    <?php // franz_stack( 'quote' ); ?>
    <?php franz_stack( 'posts' ); ?>
    <?php // franz_stack( 'cta' ); ?>
    <?php // franz_stack( 'testimonials' ); ?>
    <?php franz_stack( 'mentions-bar' ); ?>
    
	<?php do_action( 'franz_front_page_bottom' ); ?>
    
<?php get_footer(); ?>