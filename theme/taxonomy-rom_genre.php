<?php
/**
 * Genre taxonomy archive: /nintendo-switch-roms/{genre}/
 */
get_header();

$term         = get_queried_object();
$current_sort = sanitize_key( $_GET['sort'] ?? 'newest' );
$view         = sanitize_key( $_GET['view']  ?? 'grid' );

$popular = nsvault_get_popular_by_genre( $term->term_id, 12 );
?>

<div class="container">
    <?php nsvault_breadcrumbs(); ?>
</div>

<?php if ( $popular ) :
    get_template_part( 'template-parts/carousel', null, [
        'posts' => $popular,
        'id'    => 'genre-popular-carousel',
        'title' => __( 'Most popular', 'nsvault' ),
    ] );
endif; ?>

<div class="container" style="margin-top:28px; margin-bottom:40px;">

    <div class="listing-header">
        <h1 class="listing-title"><?php echo esc_html( $term->name ); ?></h1>
        <div class="listing-controls">
            <a href="<?php echo esc_url( add_query_arg( 'view', 'grid' ) ); ?>"
               class="view-toggle-btn <?php echo $view !== 'list' ? 'active' : ''; ?>" aria-label="Grid view">
                <i class="fa-solid fa-grip"></i>
            </a>
            <a href="<?php echo esc_url( add_query_arg( 'view', 'list' ) ); ?>"
               class="view-toggle-btn <?php echo $view === 'list' ? 'active' : ''; ?>" aria-label="List view">
                <i class="fa-solid fa-list"></i>
            </a>
        </div>
    </div>

    <?php if ( $term->description ) : ?>
        <div class="listing-desc">
            <?php echo wp_kses_post( apply_filters( 'the_content', $term->description ) ); ?>
        </div>
    <?php endif; ?>

    <?php nsvault_filter_tabs(); ?>

    <?php global $wp_query; ?>
    <p class="results-count">
        <?php
        $total = (int) $wp_query->found_posts;
        $paged = max(1, get_query_var('paged'));
        $per   = 15;
        $from  = ( ( $paged - 1 ) * $per ) + 1;
        $to    = min( $paged * $per, $total );
        printf( esc_html__( 'Showing %1$s–%2$s of %3$s results', 'nsvault' ), number_format_i18n($from), number_format_i18n($to), number_format_i18n($total) );
        ?>
    </p>

    <?php if ( have_posts() ) : ?>
        <div class="games-grid <?php echo $view === 'list' ? 'list-view' : ''; ?>">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php
                $tpl = $view === 'list' ? 'template-parts/game-card-list' : 'template-parts/game-card';
                get_template_part( $tpl, null, [ 'post' => get_post() ] );
                ?>
            <?php endwhile; ?>
        </div>
        <?php nsvault_pagination(); ?>
    <?php else : ?>
        <div class="no-results-wrap">
            <h2><?php esc_html_e( 'No games found', 'nsvault' ); ?></h2>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
