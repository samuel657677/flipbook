<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->                
<html <?php language_attributes(); ?>>
<!--<![endif]-->
    <head>
        <meta charset="<?php esc_attr( bloginfo( 'charset' ) ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    	<!-- Header -->
        
        <?php //do_action( 'franz_before_content' ); ?>