<?php get_header();
$term    = get_queried_object();
$view    = sanitize_key( $_GET['view'] ?? 'grid' );
$popular = get_posts( [
    'post_type'      => 'downloads',
    'posts_per_page' => 12,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
    'tax_query'      => [ [ 'taxonomy' => 'sw_developer', 'field' => 'term_id', 'terms' => $term->term_id ] ],
] );
?>
<div class="container"><?php svault_breadcrumbs(); ?></div>

<?php if ( $popular ) :
    get_template_part( 'template-parts/carousel', null, [ 'posts' => $popular, 'id' => 'dev-pop', 'title' => 'من ' . esc_html( $term->name ) ] );
endif; ?>

<div class="container" style="margin-top:28px;margin-bottom:40px;">
    <div class="listing-header">
        <h1 class="listing-title"><?php echo esc_html( $term->name ); ?></h1>
        <div class="listing-controls">
            <a href="<?php echo esc_url( add_query_arg( 'view', 'grid' ) ); ?>" class="view-toggle-btn <?php echo $view !== 'list' ? 'active' : ''; ?>" aria-label="شبكي"><i class="fa-solid fa-grip"></i></a>
            <a href="<?php echo esc_url( add_query_arg( 'view', 'list' ) ); ?>" class="view-toggle-btn <?php echo $view === 'list' ? 'active' : ''; ?>" aria-label="قائمة"><i class="fa-solid fa-list"></i></a>
        </div>
    </div>
    <?php if ( $term->description ) : ?>
        <div class="listing-desc"><?php echo wp_kses_post( apply_filters( 'the_content', $term->description ) ); ?></div>
    <?php endif; ?>
    <?php svault_filter_tabs(); ?>
    <?php global $wp_query;
    $total = (int) $wp_query->found_posts;
    $paged = max( 1, get_query_var( 'paged' ) );
    $from  = ( ( $paged - 1 ) * 15 ) + 1;
    $to    = min( $paged * 15, $total ); ?>
    <p class="results-count">عرض <?php echo number_format_i18n( $from ); ?>–<?php echo number_format_i18n( $to ); ?> من <?php echo number_format_i18n( $total ); ?> نتيجة</p>
    <?php if ( have_posts() ) : ?>
        <div class="games-grid <?php echo $view === 'list' ? 'list-view' : ''; ?>">
            <?php while ( have_posts() ) : the_post();
                get_template_part( $view === 'list' ? 'template-parts/game-card-list' : 'template-parts/game-card', null, [ 'post' => get_post() ] );
            endwhile; ?>
        </div>
        <?php svault_pagination(); ?>
    <?php else : ?>
        <div class="no-results-wrap"><h2>لا توجد نتائج</h2></div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
