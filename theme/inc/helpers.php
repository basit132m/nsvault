<?php
/**
 * Template helper functions
 */
defined( 'ABSPATH' ) || exit;

/* ─────────────────────────────────────────────────────────
 * ROM META GETTERS
 * ─────────────────────────────────────────────────────── */

function nsvault_get_meta( $key, $post_id = null ) {
    return get_post_meta( $post_id ?: get_the_ID(), $key, true );
}

function nsvault_get_badges( $post_id = null ) {
    $pid    = $post_id ?: get_the_ID();
    $badges = wp_get_post_terms( $pid, 'badge', [ 'fields' => 'slugs' ] );
    return is_wp_error( $badges ) ? [] : $badges;
}

function nsvault_get_genres( $post_id = null ) {
    $pid    = $post_id ?: get_the_ID();
    $genres = wp_get_post_terms( $pid, 'rom_genre', [ 'fields' => 'all' ] );
    return is_wp_error( $genres ) ? [] : $genres;
}

function nsvault_get_developers( $post_id = null ) {
    $pid  = $post_id ?: get_the_ID();
    $devs = wp_get_post_terms( $pid, 'rom_developer', [ 'fields' => 'all' ] );
    return is_wp_error( $devs ) ? [] : $devs;
}

/* ─────────────────────────────────────────────────────────
 * BADGE HTML
 * ─────────────────────────────────────────────────────── */

function nsvault_badge_html( $slug ) {
    $map = [
        'update'                       => [ 'class' => 'update',    'label' => 'UPDATE' ],
        'nintendo-switch-exclusives-games' => [ 'class' => 'exclusive', 'label' => 'EXCLUSIVES' ],
        'exclusives'                   => [ 'class' => 'exclusive', 'label' => 'EXCLUSIVES' ],
        'dlc'                          => [ 'class' => 'dlc',       'label' => 'DLC' ],
        'new-game'                     => [ 'class' => 'new-game',  'label' => 'NEW GAME' ],
    ];
    if ( isset( $map[ $slug ] ) ) {
        return '<span class="rom-badge ' . $map[$slug]['class'] . '">' . $map[$slug]['label'] . '</span>';
    }
    // fallback
    $term = get_term_by( 'slug', $slug, 'badge' );
    $label = $term ? esc_html( $term->name ) : esc_html( $slug );
    return '<span class="rom-badge genre">' . $label . '</span>';
}

function nsvault_render_badges( $post_id = null ) {
    $badges = nsvault_get_badges( $post_id );
    foreach ( $badges as $slug ) {
        echo nsvault_badge_html( $slug );
    }
}

/* ─────────────────────────────────────────────────────────
 * STAR RATING
 * ─────────────────────────────────────────────────────── */

function nsvault_stars_html( $rating ) {
    $rating = (float) $rating;
    $full   = floor( $rating );
    $empty  = 5 - ceil( $rating );
    $half   = ( $rating - $full ) >= 0.5 ? 1 : 0;

    $html = '<span class="stars">';
    for ( $i = 0; $i < $full;  $i++ ) $html .= '<span class="star">★</span>';
    if ( $half )                       $html .= '<span class="star">½</span>';
    for ( $i = 0; $i < $empty; $i++ ) $html .= '<span class="star empty">★</span>';
    $html .= '</span>';
    return $html;
}

/* ─────────────────────────────────────────────────────────
 * VIEWS FORMAT
 * ─────────────────────────────────────────────────────── */

function nsvault_format_views( $views ) {
    $views = (int) $views;
    if ( $views >= 1000000 ) return round( $views / 1000000, 1 ) . 'M';
    if ( $views >= 1000 )    return round( $views / 1000, 1 ) . 'K';
    return $views;
}

/* ─────────────────────────────────────────────────────────
 * BREADCRUMBS
 * ─────────────────────────────────────────────────────── */

function nsvault_breadcrumbs() {
    $home = '<a href="' . home_url('/') . '">' . __( 'Home', 'nsvault' ) . '</a>';
    $sep  = '<span class="sep">›</span>';
    $crumbs = [ $home ];

    if ( is_singular( 'roms' ) ) {
        $crumbs[] = '<a href="' . get_post_type_archive_link('roms') . '">' . __( 'Nintendo Switch ROMs', 'nsvault' ) . '</a>';
        // Genre
        $genres = nsvault_get_genres();
        if ( $genres ) {
            $g = $genres[0];
            $crumbs[] = '<a href="' . get_term_link($g) . '">' . esc_html($g->name) . '</a>';
        }
        $crumbs[] = '<span class="current">' . get_the_title() . '</span>';

    } elseif ( is_post_type_archive( 'roms' ) ) {
        $crumbs[] = '<span class="current">' . __( 'Nintendo Switch ROMs', 'nsvault' ) . '</span>';

    } elseif ( is_tax( 'rom_genre' ) ) {
        $term = get_queried_object();
        $crumbs[] = '<a href="' . get_post_type_archive_link('roms') . '">' . __( 'Nintendo Switch ROMs', 'nsvault' ) . '</a>';
        $crumbs[] = '<span class="current">' . esc_html( $term->name ) . '</span>';

    } elseif ( is_tax( 'badge' ) ) {
        $term = get_queried_object();
        $crumbs[] = '<span class="current">' . esc_html( $term->name ) . '</span>';

    } elseif ( is_search() ) {
        $crumbs[] = '<span class="current">Search: ' . esc_html( get_search_query() ) . '</span>';

    } elseif ( is_page() ) {
        $crumbs[] = '<span class="current">' . get_the_title() . '</span>';
    }

    echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    echo implode( ' ' . $sep . ' ', $crumbs );
    echo '</nav>';
}

/* ─────────────────────────────────────────────────────────
 * POPULAR ROMs FOR SIDEBAR (by genre)
 * ─────────────────────────────────────────────────────── */

function nsvault_get_popular_by_genre( $genre_id = null, $limit = 6, $exclude = null ) {
    $args = [
        'post_type'      => 'roms',
        'posts_per_page' => $limit,
        'orderby'        => 'meta_value_num',
        'meta_key'       => '_rom_views',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];
    if ( $genre_id ) {
        $args['tax_query'] = [[
            'taxonomy' => 'rom_genre',
            'field'    => 'term_id',
            'terms'    => $genre_id,
        ]];
    }
    if ( $exclude ) {
        $args['post__not_in'] = [ $exclude ];
    }
    return get_posts( $args );
}

/* ─────────────────────────────────────────────────────────
 * RELATED ROMs
 * ─────────────────────────────────────────────────────── */

function nsvault_get_related( $post_id, $limit = 6 ) {
    $genres = wp_get_post_terms( $post_id, 'rom_genre', [ 'fields' => 'ids' ] );
    $args = [
        'post_type'      => 'roms',
        'posts_per_page' => $limit,
        'post__not_in'   => [ $post_id ],
        'orderby'        => 'rand',
        'post_status'    => 'publish',
    ];
    if ( ! empty( $genres ) && ! is_wp_error( $genres ) ) {
        $args['tax_query'] = [[
            'taxonomy' => 'rom_genre',
            'field'    => 'term_id',
            'terms'    => $genres,
        ]];
    }
    return get_posts( $args );
}

/* ─────────────────────────────────────────────────────────
 * SCREENSHOTS
 * ─────────────────────────────────────────────────────── */

function nsvault_get_screenshots( $post_id = null ) {
    $raw = nsvault_get_meta( '_rom_screenshots', $post_id );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'intval', explode( ',', $raw ) ) );
}

/* ─────────────────────────────────────────────────────────
 * SORT URL BUILDER
 * ─────────────────────────────────────────────────────── */

function nsvault_sort_url( $sort ) {
    global $wp;
    $base = home_url( add_query_arg( [], $wp->request ) );
    // Strip existing sort/page params
    $base = remove_query_arg( [ 'sort', 'paged' ], $base );
    if ( $sort !== 'newest' ) {
        $base = add_query_arg( 'sort', $sort, $base );
    }
    return esc_url( $base );
}

/* ─────────────────────────────────────────────────────────
 * PAGINATION
 * ─────────────────────────────────────────────────────── */

function nsvault_pagination() {
    global $wp_query;

    $total   = (int) $wp_query->max_num_pages;
    $current = max( 1, get_query_var('paged') );

    if ( $total <= 1 ) return;

    $links = paginate_links( [
        'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'format'    => '?paged=%#%',
        'current'   => $current,
        'total'     => $total,
        'mid_size'  => 2,
        'end_size'  => 1,
        'prev_text' => '‹',
        'next_text' => '›',
        'type'      => 'array',
    ] );

    if ( ! $links ) return;

    echo '<nav class="pagination" aria-label="' . esc_attr__( 'Pagination', 'nsvault' ) . '">';
    foreach ( $links as $link ) {
        // Add 'current' class styling via span
        echo str_replace( 'page-numbers', 'page-numbers', $link );
    }
    echo '</nav>';
}

/* ─────────────────────────────────────────────────────────
 * HOMEPAGE CAROUSELS
 * ─────────────────────────────────────────────────────── */

function nsvault_get_best_roms( $limit = 12 ) {
    return get_posts( [
        'post_type'      => 'roms',
        'posts_per_page' => $limit,
        'orderby'        => 'meta_value_num',
        'meta_key'       => '_rom_views',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ] );
}

function nsvault_get_new_roms( $limit = 12 ) {
    return get_posts( [
        'post_type'      => 'roms',
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ] );
}

function nsvault_get_updated_roms( $limit = 12 ) {
    return get_posts( [
        'post_type'      => 'roms',
        'posts_per_page' => $limit,
        'orderby'        => 'modified',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ] );
}

/* ─────────────────────────────────────────────────────────
 * FILTER TABS RENDER
 * ─────────────────────────────────────────────────────── */

function nsvault_filter_tabs() {
    $current = sanitize_key( $_GET['sort'] ?? 'newest' );
    $tabs = [
        'newest'  => __( 'NEWEST',  'nsvault' ),
        'latest'  => __( 'LATEST',  'nsvault' ),
        'popular' => __( 'POPULAR', 'nsvault' ),
        'az'      => __( 'A – Z',   'nsvault' ),
    ];
    echo '<div class="filter-tabs">';
    foreach ( $tabs as $slug => $label ) {
        $active = ( $current === $slug ) ? ' active' : '';
        echo '<a href="' . nsvault_sort_url( $slug ) . '" class="filter-tab' . $active . '">' . esc_html( $label ) . '</a>';
    }
    echo '</div>';
}

/* ─────────────────────────────────────────────────────────
 * DOCUMENT TITLE
 * ─────────────────────────────────────────────────────── */

add_filter( 'document_title_separator', fn() => '|' );
