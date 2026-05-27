<?php
/**
 * Download countdown page — /go/{post-slug}/
 *
 * Adds rewrite rule: /go/{slug}/ → countdown → reveals download links.
 * Download buttons on single-downloads.php link here instead of directly.
 */
defined( 'ABSPATH' ) || exit;

/* ── Register query var ─────────────────────────────────── */
add_filter( 'query_vars', function ( $vars ) {
    $vars[] = 'svault_go';
    return $vars;
} );

/* ── Add rewrite rule ───────────────────────────────────── */
add_action( 'init', function () {
    add_rewrite_rule(
        '^go/([^/]+)/?$',
        'index.php?svault_go=$matches[1]',
        'top'
    );
}, 11 );

/* ── Template redirect ──────────────────────────────────── */
add_action( 'template_redirect', function () {
    $slug = get_query_var( 'svault_go' );
    if ( ! $slug ) return;

    global $wpdb;
    $post_id = $wpdb->get_var( $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_name   = %s
           AND post_type   = 'downloads'
           AND post_status = 'publish'
         LIMIT 1",
        $slug
    ) );

    if ( ! $post_id ) {
        wp_redirect( home_url( '/' ) );
        exit;
    }

    $title = get_the_title( $post_id );
    $dl1   = get_post_meta( $post_id, '_sv_dl1', true );
    $lb1   = get_post_meta( $post_id, '_sv_dl1_label', true ) ?: 'تحميل مباشر';
    $dl2   = get_post_meta( $post_id, '_sv_dl2', true );
    $lb2   = get_post_meta( $post_id, '_sv_dl2_label', true ) ?: 'رابط مرآة';
    $cover = get_the_post_thumbnail_url( $post_id, 'sv-card' );
    $back  = get_permalink( $post_id );

    get_header();
    ?>

    <div class="container sv-go-wrap">
        <div class="sv-go-box">

            <div class="sv-go-header">
                <?php if ( $cover ) : ?>
                    <img src="<?php echo esc_url( $cover ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="sv-go-cover">
                <?php else : ?>
                    <div class="sv-go-cover-ph"><i class="fa-solid fa-box-open"></i></div>
                <?php endif; ?>
                <div>
                    <div class="sv-go-label">جاري تحضير رابط التحميل لـ</div>
                    <h1 class="sv-go-title"><?php echo esc_html( $title ); ?></h1>
                </div>
            </div>

            <div class="sv-go-countdown" id="sv-countdown">
                <div class="sv-go-timer" id="sv-timer">10</div>
                <div class="sv-go-track">
                    <div class="sv-go-bar" id="sv-bar"></div>
                </div>
                <p class="sv-go-hint">يُرجى الانتظار قليلاً…</p>
            </div>

            <div class="sv-go-links" id="sv-links">
                <p class="sv-go-ready"><i class="fa-solid fa-circle-check"></i> روابط التحميل جاهزة!</p>
                <?php if ( $dl1 ) : ?>
                    <a href="<?php echo esc_url( $dl1 ); ?>" class="btn-download btn-download-primary" rel="nofollow" target="_blank">
                        <i class="fa-solid fa-download"></i>
                        <?php echo esc_html( $lb1 ); ?>
                    </a>
                <?php endif; ?>
                <?php if ( $dl2 ) : ?>
                    <a href="<?php echo esc_url( $dl2 ); ?>" class="btn-download btn-download-outline" rel="nofollow" target="_blank">
                        <i class="fa-solid fa-link"></i>
                        <?php echo esc_html( $lb2 ); ?>
                    </a>
                <?php endif; ?>
                <a href="<?php echo esc_url( $back ); ?>" class="sv-go-back">
                    <i class="fa-solid fa-arrow-right"></i> العودة إلى صفحة البرنامج
                </a>
            </div>

        </div>
    </div>

    <style>
    .sv-go-wrap {
        min-height: 65vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
    }
    .sv-go-box {
        background: var(--surface-2);
        border-radius: 14px;
        padding: 36px 32px;
        max-width: 540px;
        width: 100%;
        text-align: center;
    }
    .sv-go-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
        text-align: right;
    }
    .sv-go-cover {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .sv-go-cover-ph {
        width: 64px;
        height: 64px;
        background: var(--surface-3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dim);
        font-size: 1.6rem;
        flex-shrink: 0;
    }
    .sv-go-label {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .sv-go-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        line-height: 1.3;
    }
    .sv-go-countdown { margin-bottom: 28px; }
    .sv-go-timer {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 16px;
    }
    .sv-go-track {
        height: 10px;
        background: var(--surface-3);
        border-radius: 99px;
        overflow: hidden;
        margin-bottom: 10px;
    }
    .sv-go-bar {
        height: 100%;
        width: 0%;
        background: var(--primary);
        border-radius: 99px;
    }
    .sv-go-hint {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin: 0;
    }
    .sv-go-links {
        display: none;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    .sv-go-ready {
        font-size: 1rem;
        font-weight: 600;
        color: #4caf50;
        margin: 0 0 6px;
    }
    .sv-go-ready i { margin-left: 6px; }
    .sv-go-back {
        margin-top: 6px;
        font-size: 0.82rem;
        color: var(--text-muted);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .sv-go-back:hover { color: var(--primary); }
    @media (max-width: 480px) {
        .sv-go-box { padding: 24px 18px; }
        .sv-go-timer { font-size: 2.8rem; }
    }
    </style>

    <script>
    (function () {
        var secs     = 10;
        var timer    = document.getElementById('sv-timer');
        var bar      = document.getElementById('sv-bar');
        var countdown= document.getElementById('sv-countdown');
        var links    = document.getElementById('sv-links');

        // Trigger bar animation on next frame
        requestAnimationFrame(function () {
            bar.style.transition = 'width ' + secs + 's linear';
            bar.style.width = '100%';
        });

        var iv = setInterval(function () {
            secs--;
            timer.textContent = secs > 0 ? secs : 0;
            if (secs <= 0) {
                clearInterval(iv);
                countdown.style.display = 'none';
                links.style.display = 'flex';
            }
        }, 1000);
    })();
    </script>

    <?php
    get_footer();
    exit;
} );
