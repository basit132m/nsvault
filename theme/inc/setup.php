<?php
/**
 * Theme setup & support
 */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {

    load_theme_textdomain( 'nsvault', NSVAULT_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'comment-list','comment-form','search-form','gallery','caption','style','script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ] );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Set thumbnail sizes
    add_image_size( 'rom-cover',      300, 400, true );   // 3:4 portrait cover
    add_image_size( 'rom-cover-sm',   150, 200, true );   // carousel thumb
    add_image_size( 'rom-card',        90, 120, true );   // sidebar / list thumb
    add_image_size( 'rom-screenshot', 800, 450, true );   // 16:9 screenshot

    // Register nav menus
    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'nsvault' ),
        'footer'  => __( 'Footer Links', 'nsvault' ),
    ] );
} );

/**
 * Custom image sizes in media library
 */
add_filter( 'image_size_names_choose', function ( $sizes ) {
    return array_merge( $sizes, [
        'rom-cover'      => __( 'ROM Cover', 'nsvault' ),
        'rom-cover-sm'   => __( 'ROM Cover Small', 'nsvault' ),
        'rom-card'       => __( 'ROM Card Thumb', 'nsvault' ),
        'rom-screenshot' => __( 'ROM Screenshot', 'nsvault' ),
    ] );
} );

/**
 * Excerpt length
 */
add_filter( 'excerpt_length', fn() => 20 );
add_filter( 'excerpt_more',   fn() => '...' );

/**
 * Body class helpers
 */
add_filter( 'body_class', function ( $classes ) {
    if ( is_singular( 'roms' ) ) $classes[] = 'single-rom-page';
    if ( is_post_type_archive( 'roms' ) ) $classes[] = 'rom-archive-page';
    return $classes;
} );

/**
 * Disable Gutenberg for ROM post type in admin
 */
add_filter( 'use_block_editor_for_post_type', function ( $use, $post_type ) {
    if ( $post_type === 'roms' ) return false;
    return $use;
}, 10, 2 );

/**
 * Custom search to include ROM post type
 */
add_filter( 'pre_get_posts', function ( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {

        // Include ROMs in search
        if ( $query->is_search() ) {
            $query->set( 'post_type', [ 'roms', 'post', 'page' ] );
        }

        // ROM archive & taxonomy: 15 per page, default newest
        if ( $query->is_post_type_archive( 'roms' ) || $query->is_tax( [ 'rom_genre', 'badge', 'rom_developer' ] ) ) {
            $query->set( 'posts_per_page', 15 );
            $query->set( 'post_type', 'roms' );
            nsvault_apply_sort( $query );
        }

        // Search: only ROMs
        if ( $query->is_search() ) {
            $query->set( 'posts_per_page', 15 );
            nsvault_apply_sort( $query );
        }
    }
    return $query;
} );

/**
 * Apply sort from ?sort= param
 */
function nsvault_apply_sort( &$query ) {
    $sort = sanitize_key( $_GET['sort'] ?? 'newest' );
    switch ( $sort ) {
        case 'popular':
            $query->set( 'orderby', 'meta_value_num' );
            $query->set( 'meta_key', '_rom_views' );
            $query->set( 'order', 'DESC' );
            break;
        case 'az':
            $query->set( 'orderby', 'title' );
            $query->set( 'order', 'ASC' );
            break;
        case 'latest':
            $query->set( 'orderby', 'modified' );
            $query->set( 'order', 'DESC' );
            break;
        default: // newest
            $query->set( 'orderby', 'date' );
            $query->set( 'order', 'DESC' );
    }
}

/**
 * Track ROM views (increment on single view)
 */
add_action( 'wp', function () {
    if ( is_singular( 'roms' ) ) {
        $post_id = get_the_ID();
        $views   = (int) get_post_meta( $post_id, '_rom_views', true );
        update_post_meta( $post_id, '_rom_views', $views + 1 );
    }
} );
