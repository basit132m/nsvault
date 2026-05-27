<?php
/**
 * 404 Page
 */
get_header();
?>
<div class="container" style="margin-bottom:40px;">
    <div class="no-results-wrap" style="padding:100px 20px;">
        <div style="font-size:4rem;margin-bottom:16px;">🎮</div>
        <h2 style="font-size:2rem;margin-bottom:12px;">404 — Game Not Found</h2>
        <p style="color:var(--text-muted);margin-bottom:24px;">The ROM or page you're looking for doesn't exist or has been removed.</p>
        <a href="<?php echo esc_url( get_post_type_archive_link('roms') ); ?>"
           style="display:inline-flex;align-items:center;gap:8px;background:var(--primary);color:#fff;padding:10px 22px;border-radius:6px;font-weight:700;font-size:0.875rem;text-transform:uppercase;letter-spacing:0.06em;">
            ← <?php esc_html_e( 'Browse All ROMs', 'nsvault' ); ?>
        </a>
    </div>
</div>
<?php get_footer(); ?>
