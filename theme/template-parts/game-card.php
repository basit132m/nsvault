<?php
/**
 * Template Part: Game Card (grid view)
 *
 * Expected context:
 *   $args['post']  — WP_Post object (optional, uses current post if absent)
 */
$card_post = isset( $args['post'] ) ? $args['post'] : get_post();
if ( ! $card_post ) return;

$pid      = $card_post->ID;
$title    = get_the_title( $pid );
$url      = get_permalink( $pid );
$thumb    = get_the_post_thumbnail_url( $pid, 'rom-cover-sm' );
$size     = get_post_meta( $pid, '_rom_size',    true );
$rating   = (float) get_post_meta( $pid, '_rom_rating', true );
$views    = (int)   get_post_meta( $pid, '_rom_views',  true );
$badges   = wp_get_post_terms( $pid, 'badge', [ 'fields' => 'slugs' ] );
$devs     = wp_get_post_terms( $pid, 'rom_developer', [ 'fields' => 'names' ] );
$dev_name = ! is_wp_error( $devs ) && $devs ? $devs[0] : '';
if ( is_wp_error( $badges ) ) $badges = [];
?>
<a href="<?php echo esc_url( $url ); ?>" class="game-card" title="<?php echo esc_attr( $title ); ?>">
    <div class="game-card-img">
        <?php if ( $thumb ) : ?>
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
        <?php else : ?>
            <div class="no-img"><i class="fa-solid fa-gamepad"></i></div>
        <?php endif; ?>

        <?php if ( $badges ) : ?>
            <div class="game-card-badges">
                <?php foreach ( $badges as $b_slug ) echo nsvault_badge_html( $b_slug ); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="game-card-body">
        <div class="game-card-title"><?php echo esc_html( $title ); ?></div>
        <?php if ( $dev_name ) : ?>
            <div class="game-card-developer"><?php echo esc_html( $dev_name ); ?></div>
        <?php endif; ?>
        <div class="game-card-stats">
            <span class="game-card-stat">
                <i class="fa-solid fa-star" style="color:#ffd600;font-size:0.7rem"></i>
                <?php echo $rating ? esc_html( number_format( $rating, 1 ) ) : '—'; ?>
            </span>
            <span class="game-card-stat">
                <i class="fa-solid fa-eye" style="font-size:0.7rem"></i>
                <?php echo esc_html( nsvault_format_views( $views ) ); ?>
            </span>
            <?php if ( $size ) : ?>
                <span class="game-card-size">
                    <i class="fa-solid fa-download" style="font-size:0.65rem"></i>
                    <?php echo esc_html( $size ); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</a>
