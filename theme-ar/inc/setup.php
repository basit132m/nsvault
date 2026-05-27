<?php
/**
 * Theme Setup
 */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {

    load_theme_textdomain( 'softvault-ar', SVAULT_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'comment-list','comment-form','search-form','gallery','caption','style','script' ] );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-logo', [ 'flex-width' => true, 'flex-height' => true ] );

    add_image_size( 'sv-cover',      300, 400, true );
    add_image_size( 'sv-cover-sm',   150, 200, true );
    add_image_size( 'sv-card',        90, 120, true );
    add_image_size( 'sv-screenshot', 800, 450, true );

    register_nav_menus( [
        'primary' => __( 'القائمة الرئيسية', 'softvault-ar' ),
        'footer'  => __( 'روابط الفوتر', 'softvault-ar' ),
    ] );
} );

add_filter( 'image_size_names_choose', function ( $sizes ) {
    return array_merge( $sizes, [
        'sv-cover'      => __( 'غلاف المحتوى', 'softvault-ar' ),
        'sv-cover-sm'   => __( 'غلاف صغير', 'softvault-ar' ),
        'sv-screenshot' => __( 'لقطة شاشة', 'softvault-ar' ),
    ] );
} );

/* ── Excerpts ─────────────────────────────────────────────── */
add_filter( 'excerpt_length', fn() => 20 );
add_filter( 'excerpt_more',   fn() => '...' );

/* ── Disable Gutenberg for downloads CPT ─────────────────── */
add_filter( 'use_block_editor_for_post_type', function ( $use, $post_type ) {
    if ( $post_type === 'downloads' ) return false;
    return $use;
}, 10, 2 );

/* ── pre_get_posts ──────────────────────────────────────────
 *
 * Search includes downloads post type.
 * Archive & taxonomy pages: 15 per page + sort support.
 */
add_filter( 'pre_get_posts', function ( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) return $query;

    if ( $query->is_search() ) {
        $query->set( 'post_type', [ 'downloads', 'post', 'page' ] );
        $query->set( 'posts_per_page', 15 );
        svault_apply_sort( $query );
    }

    if ( $query->is_post_type_archive( 'downloads' )
      || $query->is_tax( [ 'software_cat', 'badge', 'sw_platform', 'sw_developer' ] ) ) {
        $query->set( 'posts_per_page', 15 );
        $query->set( 'post_type', 'downloads' );
        svault_apply_sort( $query );
    }

    return $query;
} );

function svault_apply_sort( &$query ) {
    $sort = sanitize_key( $_GET['sort'] ?? 'newest' );
    switch ( $sort ) {
        case 'popular':
            $query->set( 'orderby',   'meta_value_num' );
            $query->set( 'meta_key',  '_sv_views' );
            $query->set( 'order',     'DESC' );
            break;
        case 'az':
            $query->set( 'orderby', 'title' );
            $query->set( 'order',   'ASC' );
            break;
        case 'latest':
            $query->set( 'orderby', 'modified' );
            $query->set( 'order',   'DESC' );
            break;
        default: // newest
            $query->set( 'orderby', 'date' );
            $query->set( 'order',   'DESC' );
    }
}

/* ── View counter ─────────────────────────────────────────── */
add_action( 'wp', function () {
    if ( is_singular( 'downloads' ) ) {
        $id    = get_the_ID();
        $views = (int) get_post_meta( $id, '_sv_views', true );
        update_post_meta( $id, '_sv_views', $views + 1 );
    }
} );

/* ── RTL body class ───────────────────────────────────────── */
add_filter( 'body_class', function ( $classes ) {
    $classes[] = 'rtl';
    return $classes;
} );

/* ── Force Arabic locale for the theme ────────────────────── */
add_filter( 'locale', fn() => 'ar' );
