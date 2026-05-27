<?php
/**
 * Permalink Settings
 *
 * Adds SoftVault slug fields to Settings → Permalinks.
 * Admins can customise:
 *   • Base slug for single downloads  (default: downloads)
 *   • Base slug for archive listing   (default: all-downloads)
 *   • Base slug for categories        (default: category)
 *   • Base slug for badges            (default: badge)
 *
 * These are stored as wp_options and read by the CPT/taxonomy
 * registration functions at init time.
 *
 * IMPORTANT: After changing these values, WordPress automatically
 * flushes rewrite rules when you click "Save Changes" on the
 * Permalinks page — no extra step needed.
 */
defined( 'ABSPATH' ) || exit;

/* ── Save values before WP flushes rules ──────────────────── */
add_action( 'load-options-permalink.php', function () {
    if ( ! current_user_can( 'manage_options' ) ) return;
    if ( isset( $_POST['svault_dl_slug'] ) ) {
        update_option( 'svault_dl_slug',      sanitize_title( wp_unslash( $_POST['svault_dl_slug'] ) )      ?: 'downloads' );
        update_option( 'svault_archive_slug', sanitize_title( wp_unslash( $_POST['svault_archive_slug'] ) ) ?: 'all-downloads' );
        update_option( 'svault_cat_slug',     sanitize_title( wp_unslash( $_POST['svault_cat_slug'] ) )     ?: 'category' );
        update_option( 'svault_badge_slug',   sanitize_title( wp_unslash( $_POST['svault_badge_slug'] ) )   ?: 'badge' );
    }
} );

/* ── Register settings section on Permalinks page ────────── */
add_action( 'admin_init', function () {

    add_settings_section(
        'svault_permalink_section',
        '⚙️ ' . __( 'SoftVault — إعدادات الروابط الدائمة', 'softvault-ar' ),
        function () {
            echo '<p style="color:#aaa">' .
                esc_html__( 'تحكم في صيغة روابط المحتوى. بعد الحفظ ستُطبَّق الروابط تلقائياً.', 'softvault-ar' ) .
                '</p>';
        },
        'permalink'
    );

    $fields = [
        'svault_dl_slug'      => [ __( 'رابط المحتوى الفردي (slug)', 'softvault-ar' ),      'downloads',     'مثال: downloads أو برامج → example.com/downloads/game-name/' ],
        'svault_archive_slug' => [ __( 'رابط صفحة الأرشيف (archive)', 'softvault-ar' ),    'all-downloads', 'مثال: all-downloads → example.com/all-downloads/' ],
        'svault_cat_slug'     => [ __( 'رابط التصنيفات (category)', 'softvault-ar' ),       'category',      'مثال: category → example.com/category/العاب/' ],
        'svault_badge_slug'   => [ __( 'رابط الشارات والمجموعات (badge)', 'softvault-ar' ), 'badge',         'مثال: badge → example.com/badge/مجاني/' ],
    ];

    foreach ( $fields as $option => [ $label, $default, $desc ] ) {
        add_settings_field(
            $option,
            $label,
            function () use ( $option, $default, $desc ) {
                $val = esc_attr( get_option( $option, $default ) );
                echo '<input type="text" name="' . esc_attr($option) . '" value="' . $val . '" class="regular-text" dir="ltr">';
                echo '<p class="description">' . esc_html($desc) . '</p>';
            },
            'permalink',
            'svault_permalink_section'
        );
    }
} );

/* ── Helpers: read stored slugs with defaults ─────────────── */
function svault_dl_slug()      { return get_option( 'svault_dl_slug',      'downloads' ); }
function svault_archive_slug() { return get_option( 'svault_archive_slug', 'all-downloads' ); }
function svault_cat_slug()     { return get_option( 'svault_cat_slug',     'category' ); }
function svault_badge_slug()   { return get_option( 'svault_badge_slug',   'badge' ); }
