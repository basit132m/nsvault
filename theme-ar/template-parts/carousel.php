<?php
$posts    = $args['posts']    ?? [];
$id       = $args['id']       ?? 'carousel-' . uniqid();
$title    = $args['title']    ?? '';
$subtitle = $args['subtitle'] ?? '';
$view_all = $args['view_all'] ?? '';
if (empty($posts)) return;
?>
<section class="home-section">
    <div class="container">
        <div class="section-head">
            <div class="section-title">
                <?php echo esc_html($title); ?>
                <?php if ($subtitle) : ?>
                    <small><?php echo esc_html($subtitle); ?></small>
                <?php endif; ?>
            </div>
            <?php if ($view_all) : ?>
                <a href="<?php echo esc_url($view_all); ?>" class="section-link">عرض الكل ›</a>
            <?php endif; ?>
        </div>

        <div class="carousel-wrap">
            <button class="carousel-btn prev" data-target="<?php echo esc_attr($id); ?>" aria-label="السابق">‹</button>
            <div class="carousel-track" id="<?php echo esc_attr($id); ?>">
                <?php foreach ($posts as $cp) :
                    $thumb = get_the_post_thumbnail_url($cp->ID,'sv-cover-sm');
                    $size  = svault_meta('_sv_size',$cp->ID);
                ?>
                    <a href="<?php echo esc_url(get_permalink($cp->ID)); ?>"
                       class="carousel-card"
                       title="<?php echo esc_attr(get_the_title($cp->ID)); ?>">
                        <?php if ($thumb) : ?>
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($cp->ID)); ?>" loading="lazy">
                        <?php else : ?>
                            <div class="no-img">📦</div>
                        <?php endif; ?>
                        <span class="type-badge">تحميل</span>
                        <div class="carousel-card-info">
                            <div class="carousel-card-title"><?php echo esc_html(get_the_title($cp->ID)); ?></div>
                            <?php if ($size) : ?>
                                <div class="carousel-card-size">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    <?php echo esc_html($size); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <button class="carousel-btn next" data-target="<?php echo esc_attr($id); ?>" aria-label="التالي">›</button>
        </div>
    </div>
</section>
