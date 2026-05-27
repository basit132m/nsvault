<?php
/**
 * Template Part: Horizontal Carousel
 *
 * $args['posts']     — array of WP_Post
 * $args['id']        — unique carousel ID
 * $args['title']     — section title
 * $args['subtitle']  — section subtitle (optional)
 * $args['view_all']  — "view all" URL (optional)
 */
$posts    = $args['posts']    ?? [];
$id       = $args['id']       ?? 'carousel-' . uniqid();
$title    = $args['title']    ?? '';
$subtitle = $args['subtitle'] ?? '';
$view_all = $args['view_all'] ?? '';

if ( empty( $posts ) ) return;
?>
<section class="home-section">
    <div class="container">
        <div class="section-head">
            <div class="section-title">
                <?php echo esc_html( $title ); ?>
                <?php if ( $subtitle ) : ?>
                    <small><?php echo esc_html( $subtitle ); ?></small>
                <?php endif; ?>
            </div>
            <?php if ( $view_all ) : ?>
                <a href="<?php echo esc_url( $view_all ); ?>" class="section-link">
                    <?php esc_html_e( 'View All', 'nsvault' ); ?> ›
                </a>
            <?php endif; ?>
        </div>

        <div class="carousel-wrap">
            <button class="carousel-btn prev" data-target="<?php echo esc_attr($id); ?>" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="carousel-track" id="<?php echo esc_attr($id); ?>">
                <?php foreach ( $posts as $cp ) :
                    $thumb = get_the_post_thumbnail_url( $cp->ID, 'rom-cover-sm' );
                    $size  = get_post_meta( $cp->ID, '_rom_size', true );
                ?>
                    <a href="<?php echo esc_url( get_permalink($cp->ID) ); ?>"
                       class="carousel-card"
                       title="<?php echo esc_attr( get_the_title($cp->ID) ); ?>">
                        <?php if ( $thumb ) : ?>
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr( get_the_title($cp->ID) ); ?>" loading="lazy">
                        <?php else : ?>
                            <div class="no-img"><i class="fa-solid fa-gamepad"></i></div>
                        <?php endif; ?>
                        <span class="switch-badge">NSW</span>
                        <div class="carousel-card-info">
                            <div class="carousel-card-title"><?php echo esc_html( get_the_title($cp->ID) ); ?></div>
                            <?php if ( $size ) : ?>
                                <div class="carousel-card-size">
                                    <i class="fa-solid fa-download fa-xs"></i>
                                    <?php echo esc_html( $size ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <button class="carousel-btn next" data-target="<?php echo esc_attr($id); ?>" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>
