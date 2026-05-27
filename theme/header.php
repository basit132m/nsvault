<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ===================== HEADER ===================== -->
<header id="masthead" role="banner">
    <div class="container header-inner">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-logo" aria-label="<?php bloginfo('name'); ?>">
            <span class="ns">NS</span><span class="vault">VAULT.ME</span>
        </a>

        <!-- Primary Navigation -->
        <nav id="primary-navigation" class="primary-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'nsvault' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => 'nsvault_default_nav',
            ] );
            ?>
        </nav>

        <!-- Search toggle -->
        <button class="header-search-toggle" aria-label="<?php esc_attr_e( 'Open Search', 'nsvault' ); ?>" id="search-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </button>

        <!-- Mobile toggle -->
        <button class="mobile-toggle" id="mobile-toggle" aria-label="<?php esc_attr_e( 'Toggle Menu', 'nsvault' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

    </div><!-- .header-inner -->
</header>

<!-- Search Overlay -->
<div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Search', 'nsvault'); ?>">
    <div class="search-overlay-inner">
        <form class="search-overlay-form" role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
            <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search ROMs, games…', 'nsvault' ); ?>"
                   value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off" aria-label="<?php esc_attr_e('Search','nsvault'); ?>" autofocus>
            <button type="submit">
                <?php esc_html_e( 'Search', 'nsvault' ); ?>
            </button>
        </form>
        <button class="search-overlay-close" id="search-overlay-close"><?php esc_html_e( 'Press ESC to close', 'nsvault' ); ?></button>
    </div>
</div>

<!-- ===================== MAIN ===================== -->
<div id="page">
<main id="main-content" role="main">
<?php

/**
 * Fallback nav if no menu is assigned
 */
function nsvault_default_nav() {
    $roms_url  = get_post_type_archive_link( 'roms' );
    $excl_term = get_term_by( 'slug', 'nintendo-switch-exclusives-games', 'badge' );
    $excl_url  = $excl_term ? get_term_link( $excl_term ) : home_url('/');
    echo '<ul id="primary-menu">';
    echo '<li><a href="' . esc_url( $roms_url ) . '"><span>&#9734;</span> Switch ROMs</a></li>';
    echo '<li><a href="' . esc_url( $excl_url ) . '"><span>&#9889;</span> Exclusives</a></li>';
    echo '</ul>';
}
