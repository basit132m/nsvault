<?php get_header(); the_post();

$pid      = get_the_ID();
$version  = svault_meta('_sv_version');
$size     = svault_meta('_sv_size');
$type     = svault_meta('_sv_type');
$platform = svault_meta('_sv_platform');
$language = svault_meta('_sv_language');
$reldate  = svault_meta('_sv_release_date');
$dl1      = svault_meta('_sv_dl1');
$lb1      = svault_meta('_sv_dl1_label') ?: 'تحميل مباشر';
$dl2      = svault_meta('_sv_dl2');
$lb2      = svault_meta('_sv_dl2_label') ?: 'رابط مرآة';
$rating   = (float) svault_meta('_sv_rating');
$views    = (int)   svault_meta('_sv_views');
$cover    = get_the_post_thumbnail_url($pid,'sv-cover');
$cats     = svault_cats();
$devs     = svault_devs();
$platforms = svault_platforms();
$screenshots = svault_screenshots();
$related  = svault_get_related($pid, 6);
$cat_id   = $cats ? $cats[0]->term_id : null;
$cat_name = $cats ? $cats[0]->name : 'عام';
$sidebar  = svault_popular_by_cat($cat_id, 6, $pid);
?>

<div class="container"><?php svault_breadcrumbs(); ?></div>

<div class="container" style="margin-bottom:40px;">
<div class="game-single-wrap">

    <div class="game-main-col">

        <div class="game-header-box">
            <div class="game-header-box-inner">
                <div class="game-header-top">

                    <div class="game-cover-wrap">
                        <?php if ($cover) : ?>
                            <img class="game-cover-img" src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php else : ?>
                            <div class="game-cover-placeholder"><i class="fa-solid fa-box-open"></i></div>
                        <?php endif; ?>
                    </div>

                    <div class="game-header-info">
                        <?php if ($version) : ?>
                            <span class="game-version-badge"><?php echo esc_html($version); ?></span>
                        <?php endif; ?>
                        <?php if ($devs) : ?>
                            <span style="font-size:0.82rem;color:var(--text-muted)">بواسطة
                                <?php foreach ($devs as $i => $d) :
                                    $comma = $i < count($devs)-1 ? '، ' : '';
                                    echo '<a href="'.esc_url(get_term_link($d)).'" class="game-developer-link">'.esc_html($d->name).'</a>'.$comma;
                                endforeach; ?>
                            </span>
                        <?php endif; ?>

                        <h1><?php the_title(); ?></h1>

                        <?php if ($rating) : ?>
                            <div class="game-rating">
                                <?php echo svault_stars($rating); ?>
                                <span class="game-rating-score"><?php echo esc_html(number_format($rating,1)); ?></span>
                                <span class="game-rating-count">(<?php echo number_format_i18n($views); ?> تقييم)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- جدول المعلومات -->
                <table class="game-info-table">
                    <tbody>
                        <?php if ($cats) : ?>
                        <tr>
                            <td>التصنيف</td>
                            <td>
                                <?php foreach ($cats as $i => $c) :
                                    $comma = $i < count($cats)-1 ? '، ' : '';
                                    echo '<a href="'.esc_url(get_term_link($c)).'">'.esc_html($c->name).'</a>'.$comma;
                                endforeach; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($type) : ?>
                        <tr><td>النوع</td><td style="color:var(--primary);font-weight:700"><?php echo esc_html($type); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($size) : ?>
                        <tr><td>الحجم</td><td><?php echo esc_html($size); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($version) : ?>
                        <tr><td>الإصدار</td><td style="color:var(--primary)"><?php echo esc_html($version); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($platforms) : ?>
                        <tr>
                            <td>المنصة</td>
                            <td>
                                <?php foreach ($platforms as $i => $pl) :
                                    $comma = $i < count($platforms)-1 ? '، ' : '';
                                    echo '<a href="'.esc_url(get_term_link($pl)).'">'.esc_html($pl->name).'</a>'.$comma;
                                endforeach; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($language) : ?>
                        <tr><td>اللغة</td><td><?php echo esc_html($language); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($reldate) : ?>
                        <tr><td>تاريخ الإصدار</td><td><?php echo esc_html(date_i18n('Y-m-d', strtotime($reldate))); ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- أزرار التحميل -->
                <div class="game-download-wrap">
                    <?php if ($dl1 || $dl2) : ?>
                        <a href="<?php echo esc_url( home_url( '/go/' . get_post_field( 'post_name', $pid ) . '/' ) ); ?>" class="btn-download btn-download-primary">
                            <i class="fa-solid fa-download"></i>
                            تحميل البرنامج
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- الوصف -->
        <?php if (get_the_content()) : ?>
        <div class="game-description-box"><?php the_content(); ?></div>
        <?php endif; ?>

        <!-- لقطات الشاشة -->
        <?php if ($screenshots) : ?>
        <div class="game-screenshots-box">
            <div class="box-title">لقطات الشاشة</div>
            <div class="screenshots-grid">
                <?php foreach ($screenshots as $att) :
                    $img  = wp_get_attachment_image_src($att,'sv-screenshot');
                    $full = wp_get_attachment_image_src($att,'full');
                    if (!$img) continue;
                ?>
                    <a href="<?php echo esc_url($full[0]); ?>" class="screenshot-item" data-lightbox="screenshots">
                        <img src="<?php echo esc_url($img[0]); ?>" alt="لقطة شاشة" loading="lazy">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- الإبلاغ -->
        <div class="problem-report-bar">
            <span>
                <i class="fa-solid fa-circle-info" style="margin-left:4px"></i>
                هل لديك مشكلة في التحميل أو التثبيت؟
            </span>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-report">
                <i class="fa-solid fa-flag"></i>
                الإبلاغ عن مشكلة
            </a>
        </div>

        <!-- الأسئلة الشائعة -->
        <div class="faq-box">
            <?php
            $faqs = [
                'كيف أثبّت البرنامج؟' =>
                    'قم بتحميل الملف من الرابط المتاح، ثم انقر نقراً مزدوجاً على ملف التثبيت وأتبع خطوات المعالج. في حال وجود ملف README أو شرح مرفق، يرجى قراءته أولاً.',
                'هل الملفات آمنة من الفيروسات؟' =>
                    'نحرص على فحص جميع الملفات المتاحة على موقعنا. إذا واجهت أي مشكلة، استخدم برنامج مكافحة الفيروسات الخاص بك للتحقق. إذا استمرت المشكلة، تواصل معنا عبر زر الإبلاغ.',
            ];
            foreach ($faqs as $q => $a) : ?>
                <div class="faq-item">
                    <button class="faq-question" type="button">
                        <?php echo esc_html($q); ?>
                        <span class="faq-icon"><i class="fa-solid fa-plus"></i></span>
                    </button>
                    <div class="faq-answer"><p><?php echo esc_html($a); ?></p></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- التعليقات -->
        <div class="comments-box">
            <div class="box-title">التعليقات</div>
            <p class="comments-note">سيظهر تعليقك بعد مراجعة المشرف.</p>
            <?php if (comments_open()) : ?>
            <div class="comment-form-wrap">
                <form method="post" action="<?php echo esc_url(site_url('/wp-comments-post.php')); ?>">
                    <?php wp_nonce_field('comment_nonce','comment_nonce_field'); ?>
                    <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($pid); ?>">
                    <input type="hidden" name="redirect_to" value="<?php echo esc_attr(get_permalink()); ?>">
                    <textarea name="comment" placeholder="اكتب تعليقك هنا…" required></textarea>
                    <div class="comment-form-fields">
                        <input type="text"  name="author" placeholder="الاسم *" required>
                        <input type="email" name="email"  placeholder="البريد الإلكتروني *" required>
                    </div>
                    <button type="submit" class="btn-submit-comment">إرسال التعليق</button>
                </form>
            </div>
            <?php endif; ?>
            <?php
            $comments = get_comments(['post_id'=>$pid,'status'=>'approve','order'=>'DESC']);
            if ($comments) :
            ?>
            <div class="comments-list">
                <?php foreach ($comments as $comment) : ?>
                    <div class="comment-item">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span class="comment-author"><?php echo esc_html($comment->comment_author); ?></span>
                            <span class="comment-date"><?php echo esc_html(date_i18n('d M Y', strtotime($comment->comment_date))); ?></span>
                        </div>
                        <div class="comment-body"><?php echo esc_html($comment->comment_content); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- مشابه -->
        <?php if ($related) : ?>
        <div class="related-box">
            <div class="box-title">
                مشابه لـ <?php echo get_the_title(); ?>
                <a href="<?php echo esc_url(get_post_type_archive_link('downloads')); ?>" style="font-size:0.75rem;font-weight:400;color:var(--text-muted)">عرض الكل ›</a>
            </div>
            <div class="related-grid">
                <?php foreach ($related as $rp) :
                    get_template_part('template-parts/game-card', null, ['post'=>$rp]);
                endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- .game-main-col -->

    <!-- الشريط الجانبي -->
    <aside class="game-sidebar">
        <?php if ($sidebar) : ?>
        <div class="sidebar-widget">
            <div class="sidebar-widget-title">الأكثر شعبية في: <?php echo esc_html($cat_name); ?></div>
            <div class="sidebar-widget-body">
                <?php foreach ($sidebar as $sp) :
                    $sp_thumb = get_the_post_thumbnail_url($sp->ID,'sv-card');
                    $sp_views = (int) svault_meta('_sv_views',$sp->ID);
                    $sp_size  = svault_meta('_sv_size',$sp->ID);
                ?>
                    <a href="<?php echo esc_url(get_permalink($sp->ID)); ?>" class="sidebar-game-item">
                        <?php if ($sp_thumb) : ?>
                            <img src="<?php echo esc_url($sp_thumb); ?>" alt="<?php echo esc_attr(get_the_title($sp->ID)); ?>" loading="lazy">
                        <?php else : ?>
                            <div style="width:36px;height:48px;background:var(--surface-3);border-radius:3px;display:flex;align-items:center;justify-content:center;color:var(--text-dim);flex-shrink:0;font-size:1.1rem"><i class="fa-solid fa-box-open"></i></div>
                        <?php endif; ?>
                        <div class="sidebar-game-info">
                            <div class="sidebar-game-title"><?php echo esc_html(get_the_title($sp->ID)); ?></div>
                            <div class="sidebar-game-meta">
                                <?php if ($sp_size) echo esc_html($sp_size) . ' · '; ?>
                                <?php echo esc_html(svault_views($sp_views)); ?> مشاهدة
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </aside>

</div>
</div>
<?php get_footer(); ?>
