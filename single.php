<?php
add_filter('body_class', function($c){ $c[] = 'asv-single-body'; return $c; });
add_filter('astra_page_layout', function(){ return 'no-sidebar'; });
get_header();
?>

<?php while (have_posts()) : the_post();
    $id        = get_the_ID();
    $version   = get_post_meta($id, '_asv_version',        true);
    $publisher = get_post_meta($id, '_asv_publisher',      true);
    $cat_lbl   = get_post_meta($id, '_asv_category_label', true);
    $ftype     = get_post_meta($id, '_asv_file_type',      true);
    $fsize     = get_post_meta($id, '_asv_file_size',      true);
    $lang      = get_post_meta($id, '_asv_language',       true);
    $rdate     = get_post_meta($id, '_asv_release_date',   true);
    $feat_img  = get_post_meta($id, '_asv_feature_image',  true);
    $screens   = get_post_meta($id, '_asv_screenshots',    true) ?: [];
    $req_os    = get_post_meta($id, '_asv_req_os',         true);
    $req_ram   = get_post_meta($id, '_asv_req_ram',        true);
    $req_disk  = get_post_meta($id, '_asv_req_disk',       true);
    $dl_links  = get_post_meta($id, '_asv_download_links', true) ?: [];
    $dl_page   = get_permalink(get_page_by_path('download'));
?>

<div class="asv-article-wrap">
<div class="asv-container">
<div class="asv-content-area">
<main class="asv-main">

    <!-- Header Card -->
    <div class="asv-card asv-header-card">
        <nav class="asv-breadcrumb">
            <?php if (function_exists('astra_get_breadcrumb')) astra_get_breadcrumb(); ?>
        </nav>
        <h1 class="asv-title"><?php the_title(); ?></h1>
        <div class="asv-meta-bar">
            <?php if ($version)   echo '<span class="asv-version-badge">v' . esc_html($version) . '</span>'; ?>
            <?php if ($fsize)     echo '<span class="asv-publisher">الحجم: <strong>' . esc_html($fsize) . '</strong></span>'; ?>
            <?php if ($publisher) echo '<span class="asv-publisher">بواسطة <strong>' . esc_html($publisher) . '</strong></span>'; ?>
        </div>
        <div class="asv-rating"><?php echo do_shortcode('[kkstarratings]'); ?></div>
    </div>

    <!-- Software Info Card -->
    <div class="asv-card asv-info-card">
        <h2 class="asv-card-title">معلومات البرنامج</h2>
        <div class="asv-info-layout">
            <div class="asv-info-table-wrap">
                <table class="asv-info-table"><tbody>
                    <?php if ($cat_lbl) echo "<tr><th>التصنيف</th><td>" . esc_html($cat_lbl) . "</td></tr>"; ?>
                    <?php if ($ftype)   echo "<tr><th>نوع الملف</th><td>" . esc_html($ftype) . "</td></tr>"; ?>
                    <?php if ($fsize)   echo "<tr><th>حجم الملف</th><td>" . esc_html($fsize) . "</td></tr>"; ?>
                    <?php if ($version) echo "<tr><th>الإصدار</th><td>" . esc_html($version) . "</td></tr>"; ?>
                    <?php if ($lang)    echo "<tr><th>اللغة</th><td>" . esc_html($lang) . "</td></tr>"; ?>
                    <?php if ($rdate)   echo "<tr><th>تاريخ الإصدار</th><td>" . esc_html($rdate) . "</td></tr>"; ?>
                </tbody></table>
                <a href="#asv-download-section" class="asv-btn asv-btn-download-scroll">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    تحميل البرنامج
                </a>
            </div>
            <?php if ($feat_img) : ?>
            <div class="asv-feature-image">
                <?php echo wp_get_attachment_image($feat_img, 'feature-card', false, ['class' => 'asv-feature-img', 'alt' => asv_feature_alt($id)]); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Article Content -->
    <div class="asv-card asv-content-card">
        <div class="asv-article-content"><?php the_content(); ?></div>
    </div>

    <!-- Screenshots -->
    <?php if (!empty($screens)) : ?>
    <div class="asv-card asv-screenshots-card">
        <h2 class="asv-card-title">لقطات الشاشة</h2>
        <div class="asv-screenshots-grid">
            <?php foreach ($screens as $i => $img_id) : ?>
            <div class="asv-screenshot-thumb" data-index="<?php echo $i; ?>">
                <img src="<?php echo esc_url(wp_get_attachment_image_url($img_id, 'screenshot-thumb')); ?>"
                     data-full="<?php echo esc_url(wp_get_attachment_image_url($img_id, 'full')); ?>"
                     data-alt="<?php echo esc_attr(asv_screenshot_alt($id, $i)); ?>"
                     alt="<?php echo esc_attr(asv_screenshot_alt($id, $i)); ?>" loading="lazy" />
                <div class="asv-screenshot-overlay">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Lightbox -->
    <div id="asv-lightbox" class="asv-lightbox" role="dialog" aria-modal="true">
        <div class="asv-lightbox-overlay"></div>
        <div class="asv-lightbox-content">
            <button class="asv-lightbox-close" aria-label="إغلاق">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="asv-lightbox-main">
                <button class="asv-lightbox-nav asv-lightbox-prev" aria-label="السابق">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <div class="asv-lightbox-img-wrap"><img id="asv-lightbox-img" src="" alt="" /></div>
                <button class="asv-lightbox-nav asv-lightbox-next" aria-label="التالي">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
            <div class="asv-lightbox-caption"></div>
            <div class="asv-lightbox-thumbs"></div>
            <div class="asv-lightbox-controls">
                <button class="asv-lightbox-autoplay" id="asv-autoplay-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    تشغيل تلقائي
                </button>
                <span class="asv-lightbox-counter"></span>
            </div>
            <div class="asv-autoplay-progress"><div class="asv-autoplay-bar"></div></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- System Requirements -->
    <?php if ($req_os || $req_ram || $req_disk) : ?>
    <div class="asv-card asv-requirements-card">
        <h2 class="asv-card-title">متطلبات التشغيل</h2>
        <table class="asv-info-table"><tbody>
            <?php if ($req_os)   echo "<tr><th>نظام التشغيل</th><td>" . esc_html($req_os) . "</td></tr>"; ?>
            <?php if ($req_ram)  echo "<tr><th>الذاكرة العشوائية</th><td>" . esc_html($req_ram) . "</td></tr>"; ?>
            <?php if ($req_disk) echo "<tr><th>مساحة التخزين</th><td>" . esc_html($req_disk) . "</td></tr>"; ?>
        </tbody></table>
    </div>
    <?php endif; ?>

    <!-- Download Section -->
    <?php if (!empty($dl_links)) : ?>
    <div class="asv-card asv-download-card" id="asv-download-section">
        <h2 class="asv-card-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            روابط التحميل
        </h2>
        <div class="asv-download-info">
            <?php if ($ftype) echo '<span>نوع الملف: <strong>' . esc_html($ftype) . '</strong></span>'; ?>
            <?php if ($fsize) echo '<span>الحجم: <strong>' . esc_html($fsize) . '</strong></span>'; ?>
        </div>
        <div class="asv-download-btns">
            <?php foreach ($dl_links as $i => $link) :
                $url = add_query_arg(['post_id' => $id, 'link' => $i], $dl_page);
            ?>
            <a href="<?php echo esc_url($url); ?>" class="asv-btn asv-btn-download" target="_blank" rel="nofollow">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <?php echo esc_html($link['label']); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Related Articles -->
    <?php
    $cats = get_the_category();
    if (!empty($cats)) :
        $related = new WP_Query([
            'category__in'   => wp_list_pluck($cats, 'term_id'),
            'post__not_in'   => [$id],
            'posts_per_page' => 6,
            'orderby'        => 'rand',
        ]);
        if ($related->have_posts()) :
    ?>
    <div class="asv-card asv-related-card">
        <h2 class="asv-card-title">مقالات ذات صلة</h2>
        <div class="asv-related-grid">
            <?php while ($related->have_posts()) : $related->the_post();
                $r_img = get_post_meta(get_the_ID(), '_asv_feature_image', true);
                $r_ver = get_post_meta(get_the_ID(), '_asv_version', true);
            ?>
            <a href="<?php the_permalink(); ?>" class="asv-related-card-item">
                <div class="asv-related-img">
                    <?php if ($r_img) echo wp_get_attachment_image($r_img, 'feature-card', false, ['alt' => asv_feature_alt(get_the_ID())]);
                          elseif (has_post_thumbnail()) the_post_thumbnail('feature-card'); ?>
                </div>
                <div class="asv-related-info">
                    <h3><?php the_title(); ?></h3>
                    <?php if ($r_ver) echo '<span class="asv-version-badge small">v' . esc_html($r_ver) . '</span>'; ?>
                </div>
            </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php endif; endif; ?>

</main>

<!-- Sidebar -->
<aside class="asv-sidebar">
    <?php dynamic_sidebar('article-sidebar'); ?>
</aside>

</div></div></div>

<?php endwhile; get_footer(); ?>
