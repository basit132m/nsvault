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
            <div class="no-img">🎮</div>
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
                <!-- Star -->
                <svg viewBox="0 0 24 24" fill="#ffd600"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                <?php echo $rating ? esc_html( number_format( $rating, 1 ) ) : '—'; ?>
            </span>
            <span class="game-card-stat">
                <!-- Eye -->
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <?php echo esc_html( nsvault_format_views( $views ) ); ?>
            </span>
            <?php if ( $size ) : ?>
                <span class="game-card-size">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="10" height="10"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <?php echo esc_html( $size ); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</a>
