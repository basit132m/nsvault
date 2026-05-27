<?php
/**
 * Custom Post Type: downloads
 *
 * Slugs are read from Settings → Permalinks (stored in wp_options).
 *
 * Single  URL : /{svault_dl_slug}/{post-slug}/
 * Archive URL : /{svault_archive_slug}/
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

    $labels = [
        'name'               => __( 'التحميلات', 'softvault-ar' ),
        'singular_name'      => __( 'تحميل', 'softvault-ar' ),
        'add_new'            => __( 'إضافة تحميل', 'softvault-ar' ),
        'add_new_item'       => __( 'إضافة تحميل جديد', 'softvault-ar' ),
        'edit_item'          => __( 'تعديل التحميل', 'softvault-ar' ),
        'new_item'           => __( 'تحميل جديد', 'softvault-ar' ),
        'view_item'          => __( 'عرض التحميل', 'softvault-ar' ),
        'search_items'       => __( 'بحث في التحميلات', 'softvault-ar' ),
        'not_found'          => __( 'لا توجد تحميلات.', 'softvault-ar' ),
        'not_found_in_trash' => __( 'لا توجد تحميلات في المهملات.', 'softvault-ar' ),
        'all_items'          => __( 'كل التحميلات', 'softvault-ar' ),
        'menu_name'          => __( 'التحميلات', 'softvault-ar' ),
    ];

    register_post_type( 'downloads', [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => false,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => svault_archive_slug(),   // ← from Permalinks settings
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-download',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ],
        'rewrite'            => [
            'slug'       => svault_dl_slug(),            // ← from Permalinks settings
            'with_front' => false,
        ],
    ] );

}, 11 ); // priority 11 — after permalink-settings.php registers the helpers
