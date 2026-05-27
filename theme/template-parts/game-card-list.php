<?php
/**
 * Template Part: Game Card (list view)
 */
$card_post = isset( $args['post'] ) ? $args['post'] : get_post();
if ( ! $card_post ) return;

$pid      = $card_post->ID;
$title    = get_the_title( $pid );
$url      = get_permalink( $pid );
$thumb    = get_the_post_thumbnail_url( $pid, 'rom-card' );
$size     = get_post_meta( $pid, '_rom_size',    true );
$rating   = (float) get_post_meta( $pid, '_rom_rating', true );
$views    = (int)   get_post_meta( $pid, '_rom_views',  true );
$date     = get_the_date( 'M d, Y', $pid );
$badges   = wp_get_post_terms( $pid, 'badge', [ 'fields' => 'slugs' ] );
$devs     = wp_get_post_terms( $pid, 'rom_developer', [ 'fields' => 'names' ] );
$dev_name = ! is_wp_error( $devs ) && $devs ? $devs[0] : '';
if ( is_wp_error( $badges ) ) $badges = [];
?>
<a href="<?php echo esc_url( $url ); ?>" class="game-card-list" title="<?php echo esc_attr( $title ); ?>">
    <?php if ( $thumb ) : ?>
        <img class="game-card-list-thumb" src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
    <?php else : ?>
        <div class="game-card-list-thumb" style="display:flex;align-items:center;justify-content:center;background:var(--surface-3);color:var(--text-dim);font-size:1.4rem"><i class="fa-solid fa-gamepad"></i></div>
    <?php endif; ?>

    <div class="game-card-list-info">
        <div class="game-card-list-badges">
            <?php foreach ( $badges as $b_slug ) echo nsvault_badge_html( $b_slug ); ?>
        </div>
        <div class="game-card-list-title"><?php echo esc_html( $title ); ?></div>
        <div class="game-card-list-meta">
            <?php if ( $dev_name ) : ?>
                <span><?php echo esc_html( $dev_name ); ?></span>
            <?php endif; ?>
            <span>
                <i class="fa-solid fa-star" style="color:#ffd600;font-size:0.7rem"></i>
                <?php echo $rating ? esc_html( number_format($rating,1) ) : '—'; ?>
            </span>
            <span>
                <i class="fa-solid fa-eye" style="font-size:0.7rem"></i>
                <?php echo esc_html( nsvault_format_views($views) ); ?>
            </span>
        </div>
    </div>

    <div class="game-card-list-right">
        <?php if ( $size ) : ?>
            <div class="game-card-list-size">
                <i class="fa-solid fa-download fa-xs"></i> <?php echo esc_html( $size ); ?>
            </div>
        <?php endif; ?>
        <div class="game-card-list-date"><?php echo esc_html( $date ); ?></div>
    </div>
</a>
