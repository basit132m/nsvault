<?php
/**
 * SoftVault AR — Admin Settings Page
 *
 * Adds a dedicated page at:
 *   Dashboard → الإعدادات → إعدادات SoftVault AR
 *
 * Controls 4 URL slug options:
 *   svault_dl_slug       — single post base   (empty = /photoshop/ style)
 *   svault_archive_slug  — archive page base  (default: all-downloads)
 *   svault_cat_slug      — category tax base  (default: category)
 *   svault_badge_slug    — badge tax base     (default: badge)
 *
 * Rewrite rules are flushed automatically on save.
 */
defined( 'ABSPATH' ) || exit;

/* ── 1. Register the menu page ───────────────────────────── */
add_action( 'admin_menu', function () {
    add_options_page(
        'إعدادات SoftVault AR',
        'إعدادات SoftVault AR',
        'manage_options',
        'softvault-ar-settings',
        'svault_render_settings_page'
    );
} );

/* ── 2. Register settings with WordPress Settings API ───── */
add_action( 'admin_init', function () {

    // Register each option
    register_setting( 'svault_options_group', 'svault_dl_slug', [
        'sanitize_callback' => 'svault_sanitize_slug',
        'default'           => '',
    ] );
    register_setting( 'svault_options_group', 'svault_archive_slug', [
        'sanitize_callback' => 'svault_sanitize_slug_required',
        'default'           => 'all-downloads',
    ] );
    register_setting( 'svault_options_group', 'svault_cat_slug', [
        'sanitize_callback' => 'svault_sanitize_slug_required',
        'default'           => 'software-cat',  // 'category' conflicts with WP built-in base
    ] );
    register_setting( 'svault_options_group', 'svault_badge_slug', [
        'sanitize_callback' => 'svault_sanitize_slug_required',
        'default'           => 'badge',
    ] );

} );

/* ── 3. Sanitize callbacks ───────────────────────────────── */

// Allows empty string (for prefix-less single URLs)
function svault_sanitize_slug( $value ) {
    $value = sanitize_title( trim( wp_unslash( $value ) ) );
    return $value; // can be empty ''
}

// Requires a value — returns empty string on invalid input so WP uses the registered default
function svault_sanitize_slug_required( $value ) {
    $clean = sanitize_title( trim( wp_unslash( $value ) ) );
    // Guard: never allow 'category' as cat slug (conflicts with WP built-in taxonomy base)
    if ( $clean === 'category' ) {
        $clean = 'software-cat';
        add_settings_error(
            'svault_cat_slug',
            'slug_conflict',
            'الرابط "category" محجوز لتصنيفات ووردبريس الافتراضية. تم تغييره إلى "software-cat" تلقائياً.',
            'warning'
        );
    }
    return $clean ?: 'all-downloads';
}

/* ── 4. Auto-flush rewrite rules after any option updates ── */
add_action( 'update_option_svault_dl_slug',      'svault_flush_after_save', 20 );
add_action( 'update_option_svault_archive_slug', 'svault_flush_after_save', 20 );
add_action( 'update_option_svault_cat_slug',     'svault_flush_after_save', 20 );
add_action( 'update_option_svault_badge_slug',   'svault_flush_after_save', 20 );
add_action( 'add_option_svault_dl_slug',         'svault_flush_after_save', 20 );
add_action( 'add_option_svault_archive_slug',    'svault_flush_after_save', 20 );

function svault_flush_after_save() {
    // Schedule a flush on next request so CPT is registered first
    update_option( 'svault_needs_flush', 1 );
}

add_action( 'init', function () {
    if ( is_admin() && get_option( 'svault_needs_flush' ) ) {
        flush_rewrite_rules();
        delete_option( 'svault_needs_flush' );
    }
}, 99 );

/* ── 5. Settings page HTML ───────────────────────────────── */
function svault_render_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $saved = isset( $_GET['settings-updated'] ) && $_GET['settings-updated'];

    $dl_slug      = get_option( 'svault_dl_slug',      '' );
    $archive_slug = get_option( 'svault_archive_slug', 'all-downloads' );
    $cat_slug     = get_option( 'svault_cat_slug',     'software-cat' );
    $badge_slug   = get_option( 'svault_badge_slug',   'badge' );
    $home         = trailingslashit( home_url() );
    ?>
    <style>
    /* RTL admin page styles */
    .svault-wrap { direction: rtl; text-align: right; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; }
    .svault-wrap h1 { font-size: 1.5rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
    .svault-card {
        background: #fff;
        border: 1px solid #e2e4e7;
        border-radius: 8px;
        padding: 0;
        margin-bottom: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .svault-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e2e4e7;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .svault-card-header h2 { margin: 0; font-size: 1rem; color: #1d2327; }
    .svault-card-body { padding: 20px; }
    .svault-field { margin-bottom: 22px; }
    .svault-field:last-child { margin-bottom: 0; }
    .svault-label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: #1d2327;
        margin-bottom: 6px;
    }
    .svault-input-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-direction: row;
    }
    .svault-input-wrap input[type="text"] {
        width: 240px;
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 0.9rem;
        direction: ltr;
        text-align: left;
    }
    .svault-input-wrap input[type="text"]:focus {
        outline: none;
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
    }
    .svault-preview {
        font-size: 0.78rem;
        color: #646970;
        margin-top: 6px;
        background: #f0f6fc;
        border: 1px solid #c3d9f0;
        border-radius: 4px;
        padding: 6px 10px;
        font-family: monospace;
        direction: ltr;
        text-align: left;
    }
    .svault-preview strong { color: #e53935; }
    .svault-desc {
        font-size: 0.8rem;
        color: #646970;
        margin-top: 5px;
    }
    .svault-notice-success {
        background: #edfaef;
        border: 1px solid #00a32a;
        border-right: 4px solid #00a32a;
        border-left: none;
        padding: 12px 16px;
        border-radius: 4px;
        margin-bottom: 20px;
        color: #1d2327;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .svault-notice-info {
        background: #f0f6fc;
        border: 1px solid #72aee6;
        border-right: 4px solid #2271b1;
        border-left: none;
        padding: 12px 16px;
        border-radius: 4px;
        margin-bottom: 20px;
        color: #1d2327;
        font-size: 0.875rem;
    }
    .svault-badge-empty {
        display: inline-block;
        background: #00a32a;
        color: #fff;
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 3px;
        vertical-align: middle;
        margin-right: 4px;
    }
    .svault-separator { border: none; border-top: 1px solid #e2e4e7; margin: 20px 0; }
    .svault-submit-row { display: flex; align-items: center; gap: 12px; padding-top: 4px; }
    </style>

    <div class="wrap svault-wrap">
        <h1>⚙️ إعدادات SoftVault AR</h1>

        <?php if ( $saved ) : ?>
        <div class="svault-notice-success">
            ✅ <strong>تم الحفظ بنجاح!</strong> تم تحديث الروابط وتطبيقها تلقائياً.
        </div>
        <?php endif; ?>

        <div class="svault-notice-info">
            💡 <strong>ملاحظة:</strong> بعد تغيير أي رابط، يتم تطبيق التغييرات تلقائياً.
            إذا لم تعمل الروابط، اذهب إلى
            <a href="<?php echo esc_url( admin_url('options-permalink.php') ); ?>"><strong>الإعدادات → الروابط الدائمة</strong></a>
            واضغط "حفظ التغييرات" مرة واحدة.
        </div>

        <form method="post" action="options.php">
            <?php settings_fields( 'svault_options_group' ); ?>

            <!-- ═══ بطاقة: روابط المحتوى ═══ -->
            <div class="svault-card">
                <div class="svault-card-header">
                    <span style="font-size:1.1rem">🔗</span>
                    <h2>إعدادات الروابط الدائمة</h2>
                </div>
                <div class="svault-card-body">

                    <!-- رابط المقالة الفردية -->
                    <div class="svault-field">
                        <label class="svault-label" for="svault_dl_slug">
                            رابط المقالة / التحميل الفردي
                            <span class="svault-badge-empty">يدعم الفراغ</span>
                        </label>
                        <div class="svault-input-wrap">
                            <span style="color:#646970;font-size:0.85rem"><?php echo esc_html( $home ); ?></span>
                            <input type="text"
                                   id="svault_dl_slug"
                                   name="svault_dl_slug"
                                   value="<?php echo esc_attr( $dl_slug ); ?>"
                                   placeholder="اتركه فارغاً"
                                   dir="ltr">
                            <span style="color:#646970;font-size:0.85rem">/ <em>اسم-المقالة</em> /</span>
                        </div>
                        <?php
                        $dl_preview = $dl_slug
                            ? $home . $dl_slug . '/photoshop/'
                            : $home . 'photoshop/';
                        ?>
                        <div class="svault-preview">
                            مثال: <strong><?php echo esc_html( $dl_preview ); ?></strong>
                        </div>
                        <p class="svault-desc">
                            اتركه <strong>فارغاً</strong> للحصول على روابط نظيفة مثل
                            <code><?php echo esc_html( $home ); ?>photoshop/</code>
                            — أو أدخل بادئة مثل <code>download</code> أو <code>برامج</code>
                        </p>
                    </div>

                    <hr class="svault-separator">

                    <!-- رابط صفحة الأرشيف -->
                    <div class="svault-field">
                        <label class="svault-label" for="svault_archive_slug">رابط صفحة الأرشيف (كل التحميلات)</label>
                        <div class="svault-input-wrap">
                            <span style="color:#646970;font-size:0.85rem"><?php echo esc_html( $home ); ?></span>
                            <input type="text"
                                   id="svault_archive_slug"
                                   name="svault_archive_slug"
                                   value="<?php echo esc_attr( $archive_slug ); ?>"
                                   placeholder="all-downloads"
                                   dir="ltr">
                            <span style="color:#646970;font-size:0.85rem">/</span>
                        </div>
                        <div class="svault-preview">
                            مثال: <strong><?php echo esc_html( $home . $archive_slug . '/' ); ?></strong>
                        </div>
                        <p class="svault-desc">الصفحة الرئيسية التي تعرض جميع التحميلات.</p>
                    </div>

                    <hr class="svault-separator">

                    <!-- رابط التصنيفات -->
                    <div class="svault-field">
                        <label class="svault-label" for="svault_cat_slug">رابط التصنيفات</label>
                        <div class="svault-input-wrap">
                            <span style="color:#646970;font-size:0.85rem"><?php echo esc_html( $home ); ?></span>
                            <input type="text"
                                   id="svault_cat_slug"
                                   name="svault_cat_slug"
                                   value="<?php echo esc_attr( $cat_slug ); ?>"
                                   placeholder="software-cat"
                                   dir="ltr">
                            <span style="color:#646970;font-size:0.85rem">/ <em>اسم-التصنيف</em> /</span>
                        </div>
                        <div class="svault-preview">
                            مثال: <strong><?php echo esc_html( $home . $cat_slug . '/العاب/' ); ?></strong>
                        </div>
                        <p class="svault-desc">رابط صفحات التصنيف مثل: ألعاب، برامج، أدوات… <strong style="color:#d63638">لا تستخدم "category" — هو محجوز لووردبريس</strong></p>
                    </div>

                    <hr class="svault-separator">

                    <!-- رابط الشارات -->
                    <div class="svault-field">
                        <label class="svault-label" for="svault_badge_slug">رابط الشارات والمجموعات</label>
                        <div class="svault-input-wrap">
                            <span style="color:#646970;font-size:0.85rem"><?php echo esc_html( $home ); ?></span>
                            <input type="text"
                                   id="svault_badge_slug"
                                   name="svault_badge_slug"
                                   value="<?php echo esc_attr( $badge_slug ); ?>"
                                   placeholder="badge"
                                   dir="ltr">
                            <span style="color:#646970;font-size:0.85rem">/ <em>اسم-الشارة</em> /</span>
                        </div>
                        <div class="svault-preview">
                            مثال: <strong><?php echo esc_html( $home . $badge_slug . '/مجاني/' ); ?></strong>
                        </div>
                        <p class="svault-desc">رابط صفحات الشارات مثل: مجاني، مدفوع، تحديث، حصري…</p>
                    </div>

                </div>
            </div><!-- .svault-card -->

            <!-- ═══ زر الحفظ ═══ -->
            <div class="svault-submit-row">
                <?php submit_button( 'حفظ الإعدادات', 'primary large', 'submit', false ); ?>
                <span style="font-size:0.8rem;color:#646970">سيتم تطبيق الروابط تلقائياً عند الحفظ</span>
            </div>

        </form>

        <!-- ═══ جدول الروابط الحالية ═══ -->
        <div class="svault-card" style="margin-top:24px">
            <div class="svault-card-header">
                <span style="font-size:1.1rem">📋</span>
                <h2>الروابط الحالية — ملخص</h2>
            </div>
            <div class="svault-card-body">
                <table style="width:100%;border-collapse:collapse;font-size:0.875rem;direction:rtl">
                    <thead>
                        <tr style="background:#f8f9fa;border-bottom:2px solid #e2e4e7">
                            <th style="padding:10px 14px;text-align:right;color:#1d2327;font-weight:600">النوع</th>
                            <th style="padding:10px 14px;text-align:left;color:#1d2327;font-weight:600">مثال على الرابط</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = [
                            [ 'مقالة / تحميل فردي',  $dl_slug ? $home . $dl_slug . '/photoshop/' : $home . 'photoshop/' ],
                            [ 'صفحة الأرشيف (الكل)', $home . $archive_slug . '/' ],
                            [ 'صفحة تصنيف',          $home . $cat_slug . '/العاب/' ],
                            [ 'صفحة شارة',           $home . $badge_slug . '/مجاني/' ],
                            [ 'نتائج البحث',          $home . '?s=فوتوشوب' ],
                        ];
                        foreach ( $rows as [ $label, $url ] ) :
                        ?>
                        <tr style="border-bottom:1px solid #f0f0f0">
                            <td style="padding:10px 14px;color:#1d2327;font-weight:500"><?php echo esc_html( $label ); ?></td>
                            <td style="padding:10px 14px;direction:ltr;text-align:left">
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank"
                                   style="font-family:monospace;font-size:0.82rem;color:#2271b1">
                                    <?php echo esc_html( $url ); ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- .svault-wrap -->

    <script>
    // Live URL preview as user types
    (function() {
        var home = <?php echo json_encode( trailingslashit( home_url() ) ); ?>;

        function updatePreview(inputId, previewEl, buildUrl) {
            var input = document.getElementById(inputId);
            if (!input || !previewEl) return;
            input.addEventListener('input', function() {
                previewEl.innerHTML = 'مثال: <strong>' + buildUrl(input.value.trim()) + '</strong>';
            });
        }

        updatePreview('svault_dl_slug',
            document.querySelector('#svault_dl_slug').closest('.svault-field').querySelector('.svault-preview'),
            function(v) { return v ? home + v + '/photoshop/' : home + 'photoshop/'; }
        );
        updatePreview('svault_archive_slug',
            document.querySelector('#svault_archive_slug').closest('.svault-field').querySelector('.svault-preview'),
            function(v) { return home + (v || 'all-downloads') + '/'; }
        );
        updatePreview('svault_cat_slug',
            document.querySelector('#svault_cat_slug').closest('.svault-field').querySelector('.svault-preview'),
            function(v) { return home + (v || 'software-cat') + '/العاب/'; }
        );
        updatePreview('svault_badge_slug',
            document.querySelector('#svault_badge_slug').closest('.svault-field').querySelector('.svault-preview'),
            function(v) { return home + (v || 'badge') + '/مجاني/'; }
        );
    })();
    </script>
    <?php
}

/* ── 6. Slug helper functions (used by CPT & taxonomy files) */
function svault_dl_slug()      { return get_option( 'svault_dl_slug',      '' ); }
function svault_archive_slug() { return get_option( 'svault_archive_slug', 'all-downloads' ); }
function svault_cat_slug()     { return get_option( 'svault_cat_slug',     'software-cat' ); }  // never 'category'
function svault_badge_slug()   { return get_option( 'svault_badge_slug',   'badge' ); }

/* ── 7. Flush rewrite rules on theme activation ──────────────
 *
 * Without this, all custom URLs (CPT, archive, taxonomies) return
 * 404 after first install until the admin manually visits
 * Settings → Permalinks → Save Changes.
 */
add_action( 'after_switch_theme', function () {
    // WordPress already ran init before this hook fires,
    // so CPT + taxonomies are registered. Just flush.
    flush_rewrite_rules();
} );
