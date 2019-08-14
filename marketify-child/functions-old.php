<?php
/**
 * Marketify child theme.
 */
function marketify_child_styles() {
    wp_enqueue_style( 'marketify-child', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'marketify_child_styles', 999 );

/** Place any new code below this line */