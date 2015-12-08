<?php
/**
 * Load the various theme files
*/
define( 'FRANZ_ROOTDIR', get_template_directory() );
define( 'FRANZ_ROOTURI', get_template_directory_uri() );
define( 'FJ_CHILDDIR', get_stylesheet_directory() );
define( 'FJ_CHILDURI', get_stylesheet_directory_uri() );
define( 'FLIPBOOK_WIDTH', 695/1250*1250 );
define( 'FLIPBOOK_HEIGHT', 650/660*624 );

require( FRANZ_ROOTDIR . '/inc/setup.php' );
require( FRANZ_ROOTDIR . '/admin/settings-init.php' );
require( FRANZ_ROOTDIR . '/inc/utils.php' );
require( FRANZ_ROOTDIR . '/inc/scripts.php' );
require( FRANZ_ROOTDIR . '/inc/head.php' );
require( FRANZ_ROOTDIR . '/inc/header.php' );
require( FRANZ_ROOTDIR . '/inc/menus.php' );
require( FRANZ_ROOTDIR . '/inc/slider.php' );
require( FRANZ_ROOTDIR . '/inc/stacks.php' );
require( FRANZ_ROOTDIR . '/inc/comments.php' );
require( FRANZ_ROOTDIR . '/inc/user.php' );
//require( FRANZ_ROOTDIR . '/inc/widgets.php' );
require( FRANZ_ROOTDIR . '/inc/loop.php' );

/* Natively-supported plugins */
require( FRANZ_ROOTDIR . '/inc/plugins/yarpp.php' );

require( FRANZ_ROOTDIR . '/change_cover.php' );
require( FRANZ_ROOTDIR . '/change_layout.php' );
require( FRANZ_ROOTDIR . '/image_rotate.php' );
require( FRANZ_ROOTDIR . '/image_move.php' );
require( FRANZ_ROOTDIR . '/image_delete.php' );
require( FRANZ_ROOTDIR . '/image_change.php' );
require( FRANZ_ROOTDIR . '/image_exchange.php' );
require( FRANZ_ROOTDIR . '/image_create.php' );
require( FRANZ_ROOTDIR . '/text_create.php' );
require( FRANZ_ROOTDIR . '/text_delete.php' );
require( FRANZ_ROOTDIR . '/create_page.php' );
require( FRANZ_ROOTDIR . '/delete_page.php' );
require( FRANZ_ROOTDIR . '/photo_theme.php' );

?>