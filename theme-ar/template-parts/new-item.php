<?php
$p = $args['post'] ?? get_post();
if (!$p) return;
$pid    = $p->ID;
$url    = get_permalink($pid);
$title  = get_the_title($pid);
$thumb  = get_the_post_thumbnail_url($pid,'sv-card');
$size   = svault_meta('_sv_size',$pid);
$date   = get_the_date('d M Y',$pid);
$badges = wp_get_post_terms($pid,'badge',['fields'=>'slugs']);
$cats   = wp_get_post_terms($pid,'software_cat',['fields'=>'names']);
if (is_wp_error($badges)) $badges = [];
if (is_wp_error($cats))   $cats   = [];
?>
<a href="<?php echo esc_url($url); ?>" class="new-item">
    <?php if ($thumb) : ?>
        <img class="new-item-thumb" src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
    <?php else : ?>
        <div class="new-item-thumb-placeholder"><i class="fa-solid fa-box-open"></i></div>
    <?php endif; ?>
    <div class="new-item-body">
        <div class="new-item-tags">
            <?php if ($cats) echo '<span class="sv-badge cat">' . esc_html($cats[0]) . '</span>'; ?>
            <?php foreach ($badges as $b) echo svault_badge_html($b); ?>
        </div>
        <div class="new-item-title"><?php echo esc_html($title); ?></div>
        <div class="new-item-meta">
            <span class="new-item-date"><?php echo esc_html($date); ?></span>
            <?php if ($size) : ?>
                <span class="new-item-size">
                    <i class="fa-solid fa-download fa-xs"></i>
                    <?php echo esc_html($size); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</a>
