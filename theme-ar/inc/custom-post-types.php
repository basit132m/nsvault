<?php
/**
 * Custom Post Type: downloads
 *
 * Single URL behaviour (controlled from Settings → إعدادات SoftVault AR):
 *
 *   svault_dl_slug = ''          → /photoshop/           (no prefix)
 *   svault_dl_slug = 'download'  → /download/photoshop/
 *   svault_dl_slug = 'برامج'     → /برامج/photoshop/
 *
 * Archive URL: /{svault_archive_slug}/
 */
defined( 'ABSPATH' ) || exit;

/* ── Register post type ──────────────────────────────────── */
add_action( 'init', function () {

    $dl_slug     = svault_dl_slug();
    $has_prefix  = $dl_slug !== '';

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
        'query_var'          => 'downloads',
        'capability_type'    => 'post',
        'has_archive'        => svault_archive_slug(),
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-download',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ],

        // When prefix is set  → /prefix/slug/
        // When prefix is empty → rewrite=false, we add our own rule below
        'rewrite' => $has_prefix
            ? [ 'slug' => $dl_slug, 'with_front' => false ]
            : false,
    ] );

    /* ── Prefix-less single URL: /post-name/ ───────────────
     *
     * When svault_dl_slug is empty we:
     *  1. Add a rewrite rule at 'bottom' priority so WordPress
     *     pages, posts and categories still take precedence.
     *  2. Filter post_type_link so get_permalink() returns
     *     the correct /slug/ URL instead of /?downloads=slug.
     */
    if ( ! $has_prefix ) {

        // Rule: match /{anything}/ → query downloads post by name
        // 'bottom' = lowest priority, so WP pages/posts/terms win conflicts
        add_rewrite_rule(
            '^([^/]+)/?$',
            'index.php?post_type=downloads&name=$matches[1]',
            'bottom'
        );
    }

}, 11 ); // priority 11 ensures permalink-settings.php helpers are defined first


/* ── Filter permalink for prefix-less mode ──────────────── */
add_filter( 'post_type_link', function ( $link, $post ) {
    if ( $post->post_type !== 'downloads' ) return $link;
    if ( svault_dl_slug() !== '' ) return $link;  // prefix mode — keep default

    // Prefix-less: return /{post-slug}/
    return trailingslashit( home_url( '/' . $post->post_name ) );
}, 10, 2 );
