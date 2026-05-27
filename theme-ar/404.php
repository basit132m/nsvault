<?php get_header(); ?>
<div class="container" style="margin-bottom:40px;">
    <div class="no-results-wrap" style="padding:100px 20px;">
        <div style="font-size:4rem;margin-bottom:16px;">📦</div>
        <h2 style="font-size:2rem;margin-bottom:12px;">404 — الصفحة غير موجودة</h2>
        <p style="color:var(--text-muted);margin-bottom:24px;">الصفحة أو الملف الذي تبحث عنه غير موجود أو تم حذفه.</p>
        <a href="<?php echo esc_url(get_post_type_archive_link('downloads')); ?>"
           style="display:inline-flex;align-items:center;gap:8px;background:var(--primary);color:#fff;padding:10px 22px;border-radius:6px;font-weight:700;font-size:0.9rem;font-family:var(--font-ar)">
            ← تصفح كل التحميلات
        </a>
    </div>
</div>
<?php get_footer(); ?>
