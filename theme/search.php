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
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                </svg>
            </a>
            <a href="<?php echo esc_url( add_query_arg( 'view', 'list' ) ); ?>"
               class="view-toggle-btn <?php echo $view === 'list' ? 'active' : ''; ?>" aria-label="List view">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/>
                    <line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
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
