<?php
$p      = $args['post'] ?? get_post();
if (!$p) return;
$pid    = $p->ID;
$title  = get_the_title($pid);
$url    = get_permalink($pid);
$thumb  = get_the_post_thumbnail_url($pid,'sv-card');
$size   = svault_meta('_sv_size',$pid);
$rating = (float) svault_meta('_sv_rating',$pid);
$views  = (int)   svault_meta('_sv_views', $pid);
$date   = get_the_date('d M Y',$pid);
$badges = wp_get_post_terms($pid,'badge',['fields'=>'slugs']);
$devs   = wp_get_post_terms($pid,'sw_developer',['fields'=>'names']);
$dev    = (!is_wp_error($devs) && $devs) ? $devs[0] : '';
if (is_wp_error($badges)) $badges = [];
?>
<a href="<?php echo esc_url($url); ?>" class="game-card-list" title="<?php echo esc_attr($title); ?>">
    <?php if ($thumb) : ?>
        <img class="game-card-list-thumb" src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
    <?php else : ?>
        <div class="game-card-list-thumb" style="display:flex;align-items:center;justify-content:center;background:var(--surface-3);color:var(--text-dim)">📦</div>
    <?php endif; ?>
    <div class="game-card-list-info">
        <div class="game-card-list-badges">
            <?php foreach ($badges as $b) echo svault_badge_html($b); ?>
        </div>
        <div class="game-card-list-title"><?php echo esc_html($title); ?></div>
        <div class="game-card-list-meta">
            <?php if ($dev) echo '<span>' . esc_html($dev) . '</span>'; ?>
            <span>★ <?php echo $rating ? esc_html(number_format($rating,1)) : '—'; ?></span>
            <span>👁 <?php echo esc_html(svault_views($views)); ?></span>
        </div>
    </div>
    <div class="game-card-list-right">
        <?php if ($size) : ?>
            <div class="game-card-list-size">↓ <?php echo esc_html($size); ?></div>
        <?php endif; ?>
        <div class="game-card-list-date"><?php echo esc_html($date); ?></div>
    </div>
</a>
