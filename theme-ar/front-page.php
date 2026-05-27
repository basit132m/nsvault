<?php get_header();

$popular  = svault_get_popular(14);
$newest   = svault_get_newest(14);
$updated  = svault_get_updated(12);
$archive  = get_post_type_archive_link('downloads');
?>

<!-- الأكثر شعبية -->
<?php get_template_part('template-parts/carousel', null, [
    'posts'    => $popular,
    'id'       => 'pop-carousel',
    'title'    => 'الأكثر شعبية',
    'subtitle' => 'أفضل البرامج والألعاب الأكثر تحميلاً على الموقع',
    'view_all' => add_query_arg('sort','popular',$archive),
]); ?>

<!-- أحدث الإضافات -->
<?php if ($newest) : ?>
<section class="home-section">
    <div class="container">
        <div class="section-head">
            <div class="section-title">
                أحدث الإضافات
                <small>تمت إضافة برامج وألعاب جديدة</small>
            </div>
            <a href="<?php echo esc_url($archive); ?>" class="section-link">عرض أحدث الإضافات ←</a>
        </div>
        <div class="new-items-grid">
            <?php foreach ($newest as $np) :
                get_template_part('template-parts/new-item', null, ['post' => $np]);
            endforeach; ?>
            <div class="new-items-more">
                <a href="<?php echo esc_url($archive); ?>">عرض أحدث الإضافات ←</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- آخر التحديثات -->
<?php get_template_part('template-parts/carousel', null, [
    'posts'    => $updated,
    'id'       => 'updated-carousel',
    'title'    => 'آخر التحديثات',
    'subtitle' => 'هذه البرامج والألعاب استقبلت تحديثات حديثة',
    'view_all' => add_query_arg('sort','latest',$archive),
]); ?>

<!-- نبذة عن الموقع -->
<section class="about-block">
    <div class="container">
        <?php
        $home_id = get_option('page_on_front');
        if ($home_id && get_post_status($home_id) === 'publish') {
            $p = get_post($home_id);
            setup_postdata($p);
            if ($p->post_content) { the_content(); wp_reset_postdata(); }
            else svault_default_about();
        } else {
            svault_default_about();
        }
        ?>
    </div>
</section>

<?php get_footer();

function svault_default_about() {
    $site = get_bloginfo('name');
?>
    <p>مرحباً بكم في <strong><?php echo esc_html($site); ?></strong> — وجهتكم الأولى لتحميل أفضل البرامج والألعاب مجاناً. نوفر لكم روابط مباشرة وبسرعة عالية دون إعلانات مزعجة.</p>

    <h2>لماذا <?php echo esc_html($site); ?>؟</h2>
    <p>نؤمن بأن الوصول إلى التكنولوجيا والترفيه حق للجميع. لهذا السبب نجمع أفضل البرامج والألعاب ونوفرها مجاناً بروابط موثوقة وسريعة.</p>

    <h2>ما الذي نقدمه؟</h2>
    <p>تجد في موقعنا مجموعة واسعة من البرامج والألعاب تشمل:</p>
    <ul>
        <li>برامج ويندوز وماك وأندرويد وiOS</li>
        <li>ألعاب كمبيوتر وهاتف بأحدث الإصدارات</li>
        <li>تحديثات دورية لكل المحتوى</li>
        <li>شرح طريقة التثبيت لكل برنامج</li>
    </ul>

    <h2>سرعة التحميل</h2>
    <p>جميع روابط التحميل لدينا تعمل بأقصى سرعة ممكنة. نستخدم خوادم موثوقة ونوفر روابط مرآة بديلة لضمان تحميل ناجح في جميع الأوقات.</p>
<?php
}
