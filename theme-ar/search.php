<?php get_header();
$query = get_search_query();
$view  = sanitize_key($_GET['view'] ?? 'grid');
global $wp_query;
$total = (int)$wp_query->found_posts;
$paged = max(1,get_query_var('paged'));
$per   = 15;
$from  = (($paged-1)*$per)+1;
$to    = min($paged*$per,$total);
?>
<div class="container">
    <?php svault_breadcrumbs(); ?>
    <div class="search-page-header">
        <h1 class="search-page-title">بحث: <span style="color:var(--text-muted);font-weight:500"><?php echo esc_html($query); ?></span></h1>
        <p class="search-page-count"><?php echo number_format_i18n($total); ?> نتيجة متاحة</p>
    </div>
    <div class="listing-header" style="margin-bottom:16px;">
        <div></div>
        <div class="listing-controls">
            <a href="<?php echo esc_url(add_query_arg('view','grid')); ?>" class="view-toggle-btn <?php echo $view!=='list'?'active':''; ?>" aria-label="شبكي">
                <i class="fa-solid fa-grip"></i>
            </a>
            <a href="<?php echo esc_url(add_query_arg('view','list')); ?>" class="view-toggle-btn <?php echo $view==='list'?'active':''; ?>" aria-label="قائمة">
                <i class="fa-solid fa-list"></i>
            </a>
        </div>
    </div>
    <?php svault_filter_tabs(); ?>
    <?php if ($total) : ?>
    <p class="results-count">عرض <?php echo number_format_i18n($from); ?>–<?php echo number_format_i18n($to); ?> من <?php echo number_format_i18n($total); ?> نتيجة</p>
    <?php endif; ?>
    <?php if (have_posts()) : ?>
        <div class="games-grid <?php echo $view==='list'?'list-view':''; ?>" style="margin-bottom:20px;">
            <?php while(have_posts()):the_post();
                if (get_post_type()==='downloads') :
                    get_template_part($view==='list'?'template-parts/game-card-list':'template-parts/game-card',null,['post'=>get_post()]);
                endif;
            endwhile; ?>
        </div>
        <?php svault_pagination(); ?>
    <?php else : ?>
        <div class="no-results-wrap">
            <h2>لا توجد نتائج</h2>
            <p>لم يتم العثور على نتائج لـ "<?php echo esc_html($query); ?>". جرّب كلمات بحث مختلفة.</p>
        </div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
