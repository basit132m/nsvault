<?php
/**
 * Template Part: New ROM Item (homepage grid)
 * $args['post'] — WP_Post
 */
$p = $args['post'] ?? get_post();
if ( ! $p ) return;

$pid    = $p->ID;
$url    = get_permalink( $pid );
$title  = get_the_title( $pid );
$thumb  = get_the_post_thumbnail_url( $pid, 'rom-card' );
$size   = get_post_meta( $pid, '_rom_size', true );
$date   = get_the_date( 'M d, Y', $pid );
$badges = wp_get_post_terms( $pid, 'badge', [ 'fields' => 'slugs' ] );
$genres = wp_get_post_terms( $pid, 'rom_genre', [ 'fields' => 'names' ] );
if ( is_wp_error( $badges ) ) $badges = [];
if ( is_wp_error( $genres ) ) $genres = [];
?>
<a href="<?php echo esc_url( $url ); ?>" class="new-rom-item">
    <?php if ( $thumb ) : ?>
        <img class="new-rom-thumb" src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
    <?php else : ?>
        <div class="new-rom-thumb-placeholder"><i class="fa-solid fa-gamepad"></i></div>
    <?php endif; ?>

    <div class="new-rom-body">
        <div class="new-rom-tags">
            <?php
            // genre tags
            if ( $genres ) {
                echo '<span class="rom-badge genre">' . esc_html( $genres[0] ) . '</span>';
            }
            // badge tags
            foreach ( $badges as $b ) {
                echo nsvault_badge_html( $b );
            }
            ?>
        </div>
        <div class="new-rom-title"><?php echo esc_html( $title ); ?></div>
        <div class="new-rom-meta">
            <span class="new-rom-date"><?php echo esc_html( $date ); ?></span>
            <?php if ( $size ) : ?>
                <span class="new-rom-size">
                    <i class="fa-solid fa-download fa-xs"></i>
                    <?php echo esc_html( $size ); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</a>
