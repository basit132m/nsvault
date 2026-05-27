<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {

    // Cairo — Arabic Google Font
    wp_enqueue_style(
        'svault-fonts',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'svault-style',
        get_stylesheet_uri(),
        [ 'svault-fonts' ],
        SVAULT_VERSION
    );

    wp_enqueue_script(
        'svault-main',
        SVAULT_URI . '/js/main.js',
        [],
        SVAULT_VERSION,
        true
    );

    wp_localize_script( 'svault-main', 'SVault', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'svault_nonce' ),
        'homeUrl' => home_url( '/' ),
    ] );

    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) {
        wp_enqueue_media();
    }
} );
