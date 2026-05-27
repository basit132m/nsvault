<?php
/**
 * Fallback index template
 */
get_header();
?>
<div class="container" style="margin-bottom:40px;">
    <?php nsvault_breadcrumbs(); ?>

    <?php if ( have_posts() ) : ?>
        <div class="games-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php if ( get_post_type() === 'roms' ) : ?>
                    <?php get_template_part( 'template-parts/game-card', null, [ 'post' => get_post() ] ); ?>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
        <?php nsvault_pagination(); ?>
    <?php else : ?>
        <div class="no-results-wrap">
            <h2><?php esc_html_e( 'Nothing found', 'nsvault' ); ?></h2>
        </div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
