<?php
$p       = $args['post'] ?? get_post();
if (!$p) return;
$pid     = $p->ID;
$title   = get_the_title($pid);
$url     = get_permalink($pid);
$thumb   = get_the_post_thumbnail_url($pid,'sv-cover-sm');
$size    = svault_meta('_sv_size',   $pid);
$rating  = (float) svault_meta('_sv_rating', $pid);
$views   = (int)   svault_meta('_sv_views',  $pid);
$badges  = wp_get_post_terms($pid,'badge',['fields'=>'slugs']);
$devs    = wp_get_post_terms($pid,'sw_developer',['fields'=>'names']);
$dev     = (!is_wp_error($devs) && $devs) ? $devs[0] : '';
if (is_wp_error($badges)) $badges = [];
?>
<a href="<?php echo esc_url($url); ?>" class="game-card" title="<?php echo esc_attr($title); ?>">
    <div class="game-card-img">
        <?php if ($thumb) : ?>
            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
        <?php else : ?>
            <div class="no-img"><i class="fa-solid fa-box-open"></i></div>
        <?php endif; ?>
        <?php if ($badges) : ?>
            <div class="game-card-badges">
                <?php foreach ($badges as $b) echo svault_badge_html($b); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="game-card-body">
        <div class="game-card-title"><?php echo esc_html($title); ?></div>
        <?php if ($dev) : ?>
            <div class="game-card-developer"><?php echo esc_html($dev); ?></div>
        <?php endif; ?>
        <div class="game-card-stats">
            <span class="game-card-stat">
                <i class="fa-solid fa-star" style="color:#ffd600;font-size:0.7rem"></i>
                <?php echo $rating ? esc_html(number_format($rating,1)) : '—'; ?>
            </span>
            <span class="game-card-stat">
                <i class="fa-solid fa-eye" style="font-size:0.7rem"></i>
                <?php echo esc_html(svault_views($views)); ?>
            </span>
            <?php if ($size) : ?>
                <span class="game-card-size">
                    <i class="fa-solid fa-download" style="font-size:0.65rem"></i>
                    <?php echo esc_html($size); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</a>
