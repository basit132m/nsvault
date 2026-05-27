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

        /* ── Archive rules (top priority so pagename catch-all loses) ──
         *
         * When rewrite=>false, WordPress generates the base archive rule
         * but SKIPS pagination rules (it guards them with `if($this->rewrite)`).
         * We add them explicitly here.
         */
        $arc = preg_quote( svault_archive_slug(), '#' );

        add_rewrite_rule(                                            // /all-downloads/
            "^{$arc}/?$",
            'index.php?post_type=downloads',
            'top'
        );
        add_rewrite_rule(                                            // /all-downloads/page/2/
            "^{$arc}/page/([0-9]+)/?$",
            'index.php?post_type=downloads&paged=$matches[1]',
            'top'
        );
        add_rewrite_rule(                                            // /all-downloads/feed/
            "^{$arc}/feed/(rss|rss2|atom)/?$",
            'index.php?post_type=downloads&feed=$matches[1]',
            'top'
        );

        /* ── Single post fallback rule (bottom) ────────────────────
         *
         * The 'request' filter below is the primary fix for single
         * posts; this rule only helps when the pagename catch-all
         * isn't registered (e.g. plain permalinks mode).
         */
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


/* ── Fix 404 in prefix-less mode ────────────────────────────
 *
 * WordPress resolves /{slug}/ differently depending on the
 * active permalink structure:
 *
 *   /%postname%/          → sets $vars['name']     (post-name rule wins)
 *   /blog/%postname%/     → sets $vars['pagename'] (pagename catch-all wins)
 *   /%year%/%postname%/   → sets $vars['pagename']
 *
 * In every case WordPress resolves to a regular post or page, finds
 * none, and returns 404. We intercept both vars and reroute to the
 * downloads CPT when a matching published post exists.
 */
add_filter( 'request', function ( $vars ) {

    // Only active in prefix-less mode
    if ( svault_dl_slug() !== '' ) return $vars;

    /*
     * Detect the slug regardless of which permalink structure is active.
     * pagename → date/category-prefix permalink structures
     * name     → /%postname%/ permalink structure (most common)
     */
    $slug = '';

    if ( ! empty( $vars['pagename'] ) && strpos( $vars['pagename'], '/' ) === false ) {
        $slug = $vars['pagename'];
    } elseif ( ! empty( $vars['name'] ) && empty( $vars['post_type'] ) && strpos( $vars['name'], '/' ) === false ) {
        $slug = $vars['name'];
    }

    if ( $slug === '' ) return $vars;

    // ── Check 1: is this the archive slug? ──────────────────────
    if ( $slug === svault_archive_slug() ) {
        return [ 'post_type' => 'downloads' ];
    }

    // ── Check 2: is this a published downloads post? ────────────
    // Direct DB query — avoids WP_Query recursion
    global $wpdb;
    $post_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_name   = %s
           AND post_type   = 'downloads'
           AND post_status = 'publish'
         LIMIT 1",
        $slug
    ) );

    if ( ! $post_id ) return $vars; // no match → real page/404, leave as-is

    // Override: serve this downloads post
    return [
        'post_type' => 'downloads',
        'name'      => $slug,
        'downloads' => $slug,
    ];

} );
