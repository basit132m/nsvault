<?php
/**
 * Search Results: /?s=mario
 */
get_header();

$query  = get_search_query();
$view   = sanitize_key( $_GET['view'] ?? 'grid' );
global $wp_query;
$total  = (int) $wp_query->found_posts;
$paged  = max(1, get_query_var('paged'));
$per    = 15;
$from   = ( ( $paged - 1 ) * $per ) + 1;
$to     = min( $paged * $per, $total );
?>

<div class="container">
    <?php nsvault_breadcrumbs(); ?>

    <!-- Search page header -->
    <div class="search-page-header">
        <h1 class="search-page-title">
            <?php printf( esc_html__( 'Search: %s', 'nsvault' ), '<span style="color:var(--text-muted);font-weight:500">' . esc_html($query) . '</span>' ); ?>
        </h1>
        <p class="search-page-count">
            <?php printf( esc_html__( '%s item available', 'nsvault' ), number_format_i18n($total) ); ?>
        </p>
    </div>

    <!-- Controls row -->
    <div class="listing-header" style="margin-bottom:16px;">
        <div><!-- spacer --></div>
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

    <!-- Filter tabs -->
    <?php nsvault_filter_tabs(); ?>

    <!-- Results count -->
    <?php if ( $total ) : ?>
    <p class="results-count">
        <?php printf( esc_html__( 'Showing %1$s–%2$s of %3$s results', 'nsvault' ), number_format_i18n($from), number_format_i18n($to), number_format_i18n($total) ); ?>
    </p>
    <?php endif; ?>

    <!-- Results grid -->
    <?php if ( have_posts() ) : ?>
        <div class="games-grid <?php echo $view === 'list' ? 'list-view' : ''; ?>" style="margin-bottom:20px;">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php if ( get_post_type() === 'roms' ) : ?>
                    <?php
                    $tpl = $view === 'list' ? 'template-parts/game-card-list' : 'template-parts/game-card';
                    get_template_part( $tpl, null, [ 'post' => get_post() ] );
                    ?>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
        <?php nsvault_pagination(); ?>

    <?php else : ?>
        <div class="no-results-wrap">
            <h2><?php esc_html_e( 'No results found', 'nsvault' ); ?></h2>
            <p><?php printf( esc_html__( 'No ROMs matched "%s". Try a different search term.', 'nsvault' ), esc_html($query) ); ?></p>
        </div>
    <?php endif; ?>

</div><!-- .container -->

<?php get_footer(); ?>
