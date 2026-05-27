<?php
/**
 * ROM Archive: /nintendo-switch-roms/
 * Also used as base for genre & badge taxonomy pages
 */
get_header();

$current_sort = sanitize_key( $_GET['sort'] ?? 'newest' );
$view         = sanitize_key( $_GET['view']  ?? 'grid' );
$roms_archive = get_post_type_archive_link( 'roms' );

// Popular ROMs for carousel (different from main query)
$popular = nsvault_get_best_roms( 12 );
?>

<div class="container">
    <?php nsvault_breadcrumbs(); ?>
</div>

<!-- Most Popular Carousel -->
<?php if ( $popular ) :
    get_template_part( 'template-parts/carousel', null, [
        'posts'    => $popular,
        'id'       => 'archive-popular-carousel',
        'title'    => __( 'Most popular', 'nsvault' ),
        'view_all' => add_query_arg( 'sort', 'popular', $roms_archive ),
    ] );
endif; ?>

<!-- Main Listing -->
<div class="container" style="margin-top:28px; margin-bottom:40px;">

    <!-- Title + controls -->
    <div class="listing-header">
        <h1 class="listing-title"><?php esc_html_e( 'Nintendo Switch ROMs', 'nsvault' ); ?></h1>
        <div class="listing-controls">
            <a href="<?php echo esc_url( add_query_arg( 'view', 'grid', remove_query_arg('view') ) ); ?>"
               class="view-toggle-btn <?php echo $view === 'grid' ? 'active' : ''; ?>"
               title="Grid view" aria-label="Grid view">
                <i class="fa-solid fa-grip"></i>
            </a>
            <a href="<?php echo esc_url( add_query_arg( 'view', 'list', remove_query_arg('view') ) ); ?>"
               class="view-toggle-btn <?php echo $view === 'list' ? 'active' : ''; ?>"
               title="List view" aria-label="List view">
                <i class="fa-solid fa-list"></i>
            </a>
        </div>
    </div>

    <!-- SEO description block (editable via page content) -->
    <?php
    $desc_page = get_page_by_path( 'nintendo-switch-roms' );
    if ( $desc_page && $desc_page->post_content ) :
    ?>
        <div class="listing-desc">
            <?php echo wp_kses_post( apply_filters( 'the_content', $desc_page->post_content ) ); ?>
        </div>
    <?php endif; ?>

    <!-- Filter tabs -->
    <?php nsvault_filter_tabs(); ?>

    <!-- Results count -->
    <?php global $wp_query; ?>
    <p class="results-count">
        <?php
        $total   = (int) $wp_query->found_posts;
        $paged   = max(1, get_query_var('paged'));
        $per     = (int) get_option('posts_per_page', 15);
        $from    = ( ( $paged - 1 ) * $per ) + 1;
        $to      = min( $paged * $per, $total );
        printf(
            esc_html__( 'Showing %1$s–%2$s of %3$s results', 'nsvault' ),
            number_format_i18n($from),
            number_format_i18n($to),
            number_format_i18n($total)
        );
        ?>
    </p>

    <!-- Grid / List -->
    <?php if ( have_posts() ) : ?>
        <div class="games-grid <?php echo $view === 'list' ? 'list-view' : ''; ?>">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php if ( $view === 'list' ) : ?>
                    <?php get_template_part( 'template-parts/game-card-list', null, [ 'post' => get_post() ] ); ?>
                <?php else : ?>
                    <?php get_template_part( 'template-parts/game-card', null, [ 'post' => get_post() ] ); ?>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>

        <?php nsvault_pagination(); ?>

    <?php else : ?>
        <div class="no-results-wrap">
            <h2><?php esc_html_e( 'No ROMs found', 'nsvault' ); ?></h2>
            <p><?php esc_html_e( 'Try adjusting your filters or check back later.', 'nsvault' ); ?></p>
        </div>
    <?php endif; ?>

</div><!-- .container -->

<?php get_footer(); ?>
