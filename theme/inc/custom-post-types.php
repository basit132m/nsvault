<?php
/**
 * Custom Post Type: roms
 *
 * Single URL : /roms/{slug}/
 * Archive    : /nintendo-switch-roms/  (via has_archive)
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

    $labels = [
        'name'               => __( 'ROMs', 'nsvault' ),
        'singular_name'      => __( 'ROM', 'nsvault' ),
        'add_new'            => __( 'Add New ROM', 'nsvault' ),
        'add_new_item'       => __( 'Add New ROM', 'nsvault' ),
        'edit_item'          => __( 'Edit ROM', 'nsvault' ),
        'new_item'           => __( 'New ROM', 'nsvault' ),
        'view_item'          => __( 'View ROM', 'nsvault' ),
        'search_items'       => __( 'Search ROMs', 'nsvault' ),
        'not_found'          => __( 'No ROMs found.', 'nsvault' ),
        'not_found_in_trash' => __( 'No ROMs in trash.', 'nsvault' ),
        'all_items'          => __( 'All ROMs', 'nsvault' ),
        'menu_name'          => __( 'ROMs', 'nsvault' ),
    ];

    register_post_type( 'roms', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => false,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => 'nintendo-switch-roms',
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-games',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ],
        'rewrite'            => [
            'slug'       => 'roms',
            'with_front' => false,
        ],
    ] );

} );
