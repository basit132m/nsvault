<?php
/**
 * Custom Taxonomies — all slugs from Settings → Permalinks
 *
 * software_cat  → /{svault_cat_slug}/{term}/
 * badge         → /{svault_badge_slug}/{term}/
 * sw_platform   → /platform/{term}/
 * sw_developer  → /developer/{term}/
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {

    /* ── التصنيف (Category) ───────────────────────────────── */
    register_taxonomy( 'software_cat', 'downloads', [
        'labels' => [
            'name'          => __( 'التصنيفات', 'softvault-ar' ),
            'singular_name' => __( 'تصنيف', 'softvault-ar' ),
            'all_items'     => __( 'كل التصنيفات', 'softvault-ar' ),
            'edit_item'     => __( 'تعديل التصنيف', 'softvault-ar' ),
            'add_new_item'  => __( 'إضافة تصنيف', 'softvault-ar' ),
            'search_items'  => __( 'بحث في التصنيفات', 'softvault-ar' ),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => false,
        'rewrite'           => [
            'slug'       => svault_cat_slug(),    // ← from Permalinks settings
            'with_front' => false,
        ],
    ] );

    /* ── الشارة / المجموعة (Badge) ───────────────────────── */
    register_taxonomy( 'badge', 'downloads', [
        'labels' => [
            'name'          => __( 'الشارات', 'softvault-ar' ),
            'singular_name' => __( 'شارة', 'softvault-ar' ),
            'all_items'     => __( 'كل الشارات', 'softvault-ar' ),
            'edit_item'     => __( 'تعديل الشارة', 'softvault-ar' ),
            'add_new_item'  => __( 'إضافة شارة', 'softvault-ar' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => false,
        'rewrite'           => [
            'slug'       => svault_badge_slug(),  // ← from Permalinks settings
            'with_front' => false,
        ],
    ] );

    /* ── المنصة (Platform) ───────────────────────────────── */
    register_taxonomy( 'sw_platform', 'downloads', [
        'labels' => [
            'name'          => __( 'المنصات', 'softvault-ar' ),
            'singular_name' => __( 'منصة', 'softvault-ar' ),
            'all_items'     => __( 'كل المنصات', 'softvault-ar' ),
            'add_new_item'  => __( 'إضافة منصة', 'softvault-ar' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => false,
        'rewrite'           => [ 'slug' => 'platform', 'with_front' => false ],
    ] );

    /* ── المطور (Developer) ───────────────────────────────── */
    register_taxonomy( 'sw_developer', 'downloads', [
        'labels' => [
            'name'          => __( 'المطورون', 'softvault-ar' ),
            'singular_name' => __( 'مطور', 'softvault-ar' ),
            'all_items'     => __( 'كل المطورين', 'softvault-ar' ),
            'add_new_item'  => __( 'إضافة مطور', 'softvault-ar' ),
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => false,
        'rewrite'           => [ 'slug' => 'developer', 'with_front' => false ],
    ] );

}, 11 );
