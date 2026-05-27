<?php
/**
 * Enqueue scripts & styles
 */
defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', function () {

    // Google Fonts – Inter
    wp_enqueue_style(
        'nsvault-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap',
        [],
        null
    );

    // Font Awesome 6.5
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        [],
        '6.5.2'
    );

    // Main stylesheet
    wp_enqueue_style(
        'nsvault-style',
        get_stylesheet_uri(),
        [ 'nsvault-fonts', 'font-awesome' ],
        NSVAULT_VERSION
    );

    // Main JS
    wp_enqueue_script(
        'nsvault-main',
        NSVAULT_URI . '/js/main.js',
        [],
        NSVAULT_VERSION,
        true
    );

    // Pass data to JS
    wp_localize_script( 'nsvault-main', 'NSVault', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'nsvault_nonce' ),
        'homeUrl' => home_url( '/' ),
    ] );

    // WP comment reply
    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
} );

/**
 * Enqueue admin scripts (for media uploader in meta boxes)
 */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) {
        wp_enqueue_media();
    }
} );
