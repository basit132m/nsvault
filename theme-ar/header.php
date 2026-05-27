<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="masthead" role="banner">
    <div class="container header-inner">

        <!-- الشعار -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" aria-label="<?php bloginfo('name'); ?>">
            <?php
            $parts = explode( ' ', get_bloginfo('name'), 2 );
            echo '<span class="part1">' . esc_html($parts[0]) . '</span>';
            echo '<span class="part2">' . esc_html($parts[1] ?? '') . '</span>';
            ?>
        </a>

        <!-- القائمة الرئيسية -->
        <nav id="primary-navigation" class="primary-nav" role="navigation" aria-label="القائمة الرئيسية">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => 'svault_default_nav',
            ]);
            ?>
        </nav>

        <!-- بحث -->
        <button class="header-search-toggle" id="search-toggle" aria-label="بحث">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </button>

        <!-- زر الهاتف -->
        <button class="mobile-toggle" id="mobile-toggle" aria-label="القائمة">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

    </div>
</header>

<!-- نافذة البحث -->
<div class="search-overlay" id="search-overlay" role="dialog" aria-modal="true" aria-label="بحث">
    <div class="search-overlay-inner">
        <form class="search-overlay-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" name="s"
                   placeholder="ابحث عن برامج، ألعاب…"
                   value="<?php echo esc_attr(get_search_query()); ?>"
                   autocomplete="off" autofocus>
            <button type="submit">بحث</button>
        </form>
        <button class="search-overlay-close" id="search-overlay-close">اضغط ESC للإغلاق</button>
    </div>
</div>

<div id="page">
<main id="main-content" role="main">
<?php

function svault_default_nav() {
    $archive = get_post_type_archive_link('downloads');
    $free    = get_term_by('slug','free','badge');
    $free_url = $free ? get_term_link($free) : home_url('/');
    echo '<ul id="primary-menu">';
    echo '<li><a href="' . esc_url($archive) . '">⬇️ كل التحميلات</a></li>';
    echo '<li><a href="' . esc_url($free_url) . '">🆓 البرامج المجانية</a></li>';
    echo '</ul>';
}
