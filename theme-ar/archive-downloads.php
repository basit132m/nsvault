<?php get_header();

$view    = sanitize_key($_GET['view'] ?? 'grid');
$archive = get_post_type_archive_link('downloads');
$popular = svault_get_popular(12);
?>

<div class="container"><?php svault_breadcrumbs(); ?></div>

<?php if ($popular) :
    get_template_part('template-parts/carousel', null, [
        'posts'    => $popular,
        'id'       => 'arch-pop-carousel',
        'title'    => 'الأكثر شعبية',
        'view_all' => add_query_arg('sort','popular',$archive),
    ]);
endif; ?>

<div class="container" style="margin-top:28px;margin-bottom:40px;">

    <div class="listing-header">
        <h1 class="listing-title">كل التحميلات</h1>
        <div class="listing-controls">
            <a href="<?php echo esc_url(add_query_arg('view','grid',remove_query_arg('view'))); ?>"
               class="view-toggle-btn <?php echo $view !== 'list' ? 'active' : ''; ?>" aria-label="عرض شبكي">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                </svg>
            </a>
            <a href="<?php echo esc_url(add_query_arg('view','list',remove_query_arg('view'))); ?>"
               class="view-toggle-btn <?php echo $view === 'list' ? 'active' : ''; ?>" aria-label="عرض قائمة">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/>
                    <line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
            </a>
        </div>
    </div>

    <?php
    $desc_page = get_page_by_path( svault_archive_slug() );
    if ($desc_page && $desc_page->post_content) :
    ?>
        <div class="listing-desc">
            <?php echo wp_kses_post(apply_filters('the_content',$desc_page->post_content)); ?>
        </div>
    <?php endif; ?>

    <?php svault_filter_tabs(); ?>

    <?php global $wp_query;
    $total = (int) $wp_query->found_posts;
    $paged = max(1, get_query_var('paged'));
    $per   = 15;
    $from  = (($paged-1)*$per)+1;
    $to    = min($paged*$per, $total); ?>
    <p class="results-count">عرض <?php echo number_format_i18n($from); ?>–<?php echo number_format_i18n($to); ?> من <?php echo number_format_i18n($total); ?> نتيجة</p>

    <?php if (have_posts()) : ?>
        <div class="games-grid <?php echo $view === 'list' ? 'list-view' : ''; ?>">
            <?php while (have_posts()) : the_post(); ?>
                <?php
                $tpl = $view === 'list' ? 'template-parts/game-card-list' : 'template-parts/game-card';
                get_template_part($tpl, null, ['post' => get_post()]);
                ?>
            <?php endwhile; ?>
        </div>
        <?php svault_pagination(); ?>
    <?php else : ?>
        <div class="no-results-wrap"><h2>لا توجد تحميلات</h2></div>
    <?php endif; ?>

</div>
<?php get_footer(); ?>
