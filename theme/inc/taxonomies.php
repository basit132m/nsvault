<?php
/**
 * Custom Taxonomies
 *
 * rom_genre    → /nintendo-switch-roms/{term}/
 * badge        → /badge/{term}/
 * rom_developer→ /developer/{term}/
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

    /* ── Genre ─────────────────────────────────────────── */
    register_taxonomy( 'rom_genre', 'roms', [
        'labels' => [
            'name'          => __( 'Genres', 'nsvault' ),
            'singular_name' => __( 'Genre', 'nsvault' ),
            'all_items'     => __( 'All Genres', 'nsvault' ),
            'edit_item'     => __( 'Edit Genre', 'nsvault' ),
            'add_new_item'  => __( 'Add New Genre', 'nsvault' ),
            'search_items'  => __( 'Search Genres', 'nsvault' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => false,
        'rewrite'           => [
            'slug'       => 'nintendo-switch-roms',
            'with_front' => false,
        ],
    ] );

    /* ── Badge (Exclusives, New Game, etc.) ─────────────── */
    register_taxonomy( 'badge', 'roms', [
        'labels' => [
            'name'          => __( 'Badges', 'nsvault' ),
            'singular_name' => __( 'Badge', 'nsvault' ),
            'all_items'     => __( 'All Badges', 'nsvault' ),
            'edit_item'     => __( 'Edit Badge', 'nsvault' ),
            'add_new_item'  => __( 'Add New Badge', 'nsvault' ),
            'search_items'  => __( 'Search Badges', 'nsvault' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => false,
        'rewrite'           => [
            'slug'       => 'badge',
            'with_front' => false,
        ],
    ] );

    /* ── Developer ──────────────────────────────────────── */
    register_taxonomy( 'rom_developer', 'roms', [
        'labels' => [
            'name'          => __( 'Developers', 'nsvault' ),
            'singular_name' => __( 'Developer', 'nsvault' ),
            'all_items'     => __( 'All Developers', 'nsvault' ),
            'edit_item'     => __( 'Edit Developer', 'nsvault' ),
            'add_new_item'  => __( 'Add New Developer', 'nsvault' ),
            'search_items'  => __( 'Search Developers', 'nsvault' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => false,
        'rewrite'           => [
            'slug'       => 'developer',
            'with_front' => false,
        ],
    ] );

} );
