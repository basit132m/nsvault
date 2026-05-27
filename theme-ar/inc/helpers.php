<?php
/**
 * Helper Functions
 */
defined( 'ABSPATH' ) || exit;

/* ── Meta getters ────────────────────────────────────────── */
function svault_meta( $key, $pid = null ) {
    return get_post_meta( $pid ?: get_the_ID(), $key, true );
}
function svault_badges( $pid = null ) {
    $t = wp_get_post_terms( $pid ?: get_the_ID(), 'badge', [ 'fields' => 'slugs' ] );
    return is_wp_error($t) ? [] : $t;
}
function svault_cats( $pid = null ) {
    $t = wp_get_post_terms( $pid ?: get_the_ID(), 'software_cat', [ 'fields' => 'all' ] );
    return is_wp_error($t) ? [] : $t;
}
function svault_devs( $pid = null ) {
    $t = wp_get_post_terms( $pid ?: get_the_ID(), 'sw_developer', [ 'fields' => 'all' ] );
    return is_wp_error($t) ? [] : $t;
}
function svault_platforms( $pid = null ) {
    $t = wp_get_post_terms( $pid ?: get_the_ID(), 'sw_platform', [ 'fields' => 'all' ] );
    return is_wp_error($t) ? [] : $t;
}

/* ── Badge HTML ─────────────────────────────────────────── */
function svault_badge_html( $slug ) {
    $map = [
        'update'   => [ 'update',   'تحديث' ],
        'free'     => [ 'free',     'مجاني' ],
        'premium'  => [ 'premium',  'مدفوع' ],
        'new'      => [ 'new-item', 'جديد' ],
        'crack'    => [ 'update',   'كراك' ],
        'exclusive'=> [ 'free',     'حصري' ],
    ];
    if ( isset( $map[$slug] ) ) {
        return '<span class="sv-badge ' . $map[$slug][0] . '">' . $map[$slug][1] . '</span>';
    }
    $term  = get_term_by( 'slug', $slug, 'badge' );
    $label = $term ? esc_html($term->name) : esc_html($slug);
    return '<span class="sv-badge cat">' . $label . '</span>';
}

/* ── Stars ───────────────────────────────────────────────── */
function svault_stars( $rating ) {
    $rating = (float) $rating;
    $full   = floor($rating);
    $empty  = 5 - ceil($rating);
    $half   = ($rating - $full) >= 0.5 ? 1 : 0;
    $html   = '<span class="stars">';
    for ($i=0;$i<$full; $i++) $html .= '<i class="fa-solid fa-star star"></i>';
    if ($half)                  $html .= '<i class="fa-solid fa-star-half-stroke star"></i>';
    for ($i=0;$i<$empty;$i++) $html .= '<i class="fa-regular fa-star star empty"></i>';
    return $html . '</span>';
}

/* ── Format views ────────────────────────────────────────── */
function svault_views( $v ) {
    $v = (int)$v;
    if ($v >= 1000000) return round($v/1000000,1) . 'M';
    if ($v >= 1000)    return round($v/1000,1)    . 'K';
    return $v;
}

/* ── Breadcrumbs ─────────────────────────────────────────── */
function svault_breadcrumbs() {
    $home = '<a href="' . home_url('/') . '">' . __('الرئيسية','softvault-ar') . '</a>';
    $sep  = '<span class="sep">›</span>';
    $c    = [ $home ];

    if ( is_singular('downloads') ) {
        $c[] = '<a href="' . get_post_type_archive_link('downloads') . '">' . __('كل التحميلات','softvault-ar') . '</a>';
        $cats = svault_cats();
        if ($cats) $c[] = '<a href="' . get_term_link($cats[0]) . '">' . esc_html($cats[0]->name) . '</a>';
        $c[] = '<span class="current">' . get_the_title() . '</span>';
    } elseif ( is_post_type_archive('downloads') ) {
        $c[] = '<span class="current">' . __('كل التحميلات','softvault-ar') . '</span>';
    } elseif ( is_tax('software_cat') || is_tax('sw_platform') || is_tax('sw_developer') ) {
        $term = get_queried_object();
        $c[] = '<a href="' . get_post_type_archive_link('downloads') . '">' . __('كل التحميلات','softvault-ar') . '</a>';
        $c[] = '<span class="current">' . esc_html($term->name) . '</span>';
    } elseif ( is_tax('badge') ) {
        $c[] = '<span class="current">' . esc_html(get_queried_object()->name) . '</span>';
    } elseif ( is_search() ) {
        $c[] = '<span class="current">بحث: ' . esc_html(get_search_query()) . '</span>';
    } elseif ( is_page() ) {
        $c[] = '<span class="current">' . get_the_title() . '</span>';
    }

    echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    echo implode( ' ' . $sep . ' ', $c );
    echo '</nav>';
}

/* ── Sort URL ────────────────────────────────────────────── */
function svault_sort_url( $sort ) {
    global $wp;
    $base = home_url( add_query_arg( [], $wp->request ) );
    $base = remove_query_arg( [ 'sort', 'paged' ], $base );
    if ( $sort !== 'newest' ) $base = add_query_arg( 'sort', $sort, $base );
    return esc_url( $base );
}

/* ── Filter tabs ─────────────────────────────────────────── */
function svault_filter_tabs() {
    $current = sanitize_key( $_GET['sort'] ?? 'newest' );
    $tabs = [
        'newest'  => 'الأحدث',
        'latest'  => 'آخر تحديث',
        'popular' => 'الأكثر شعبية',
        'az'      => 'أ – ي',
    ];
    echo '<div class="filter-tabs">';
    foreach ( $tabs as $slug => $label ) {
        $active = $current === $slug ? ' active' : '';
        echo '<a href="' . svault_sort_url($slug) . '" class="filter-tab' . $active . '">' . esc_html($label) . '</a>';
    }
    echo '</div>';
}

/* ── Pagination ──────────────────────────────────────────── */
function svault_pagination() {
    global $wp_query;
    $total   = (int) $wp_query->max_num_pages;
    $current = max(1, get_query_var('paged'));
    if ($total <= 1) return;

    $links = paginate_links([
        'base'      => str_replace(999999999,'%#%', esc_url(get_pagenum_link(999999999))),
        'format'    => '?paged=%#%',
        'current'   => $current,
        'total'     => $total,
        'mid_size'  => 2,
        'end_size'  => 1,
        'prev_text' => '‹',
        'next_text' => '›',
        'type'      => 'array',
    ]);
    if (!$links) return;
    echo '<nav class="pagination">';
    foreach ($links as $link) echo $link;
    echo '</nav>';
}

/* ── Carousel queries ────────────────────────────────────── */
function svault_get_popular( $limit = 12 ) {
    return get_posts([
        'post_type'      => 'downloads',
        'posts_per_page' => $limit,
        'orderby'        => 'meta_value_num',
        'meta_key'       => '_sv_views',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ]);
}
function svault_get_newest( $limit = 14 ) {
    return get_posts([
        'post_type'      => 'downloads',
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ]);
}
function svault_get_updated( $limit = 12 ) {
    return get_posts([
        'post_type'      => 'downloads',
        'posts_per_page' => $limit,
        'orderby'        => 'modified',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ]);
}

/* ── Related ─────────────────────────────────────────────── */
function svault_get_related( $pid, $limit = 6 ) {
    $cats = wp_get_post_terms( $pid, 'software_cat', ['fields'=>'ids'] );
    $args = [
        'post_type'      => 'downloads',
        'posts_per_page' => $limit,
        'post__not_in'   => [$pid],
        'orderby'        => 'rand',
        'post_status'    => 'publish',
    ];
    if (!empty($cats) && !is_wp_error($cats)) {
        $args['tax_query'] = [['taxonomy'=>'software_cat','field'=>'term_id','terms'=>$cats]];
    }
    return get_posts($args);
}

/* ── Popular by cat for sidebar ──────────────────────────── */
function svault_popular_by_cat( $cat_id = null, $limit = 6, $exclude = null ) {
    $args = [
        'post_type'      => 'downloads',
        'posts_per_page' => $limit,
        // Don't require _sv_views meta to exist — fall back to date so
        // new posts without any recorded views still appear in sidebar
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ];
    if ($cat_id) $args['tax_query'] = [['taxonomy'=>'software_cat','field'=>'term_id','terms'=>$cat_id]];
    if ($exclude) $args['post__not_in'] = [$exclude];
    return get_posts($args);
}

/* ── Screenshots ─────────────────────────────────────────── */
function svault_screenshots( $pid = null ) {
    $raw = svault_meta('_sv_screenshots', $pid);
    if (!$raw) return [];
    return array_filter(array_map('intval', explode(',', $raw)));
}
