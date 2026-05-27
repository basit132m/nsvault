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
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        <!-- زر الهاتف -->
        <button class="mobile-toggle" id="mobile-toggle" aria-label="القائمة">
            <i class="fa-solid fa-bars"></i>
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
    echo '<li><a href="' . esc_url($archive) . '"><i class="fa-solid fa-download"></i> كل التحميلات</a></li>';
    echo '<li><a href="' . esc_url($free_url) . '"><i class="fa-solid fa-gift"></i> البرامج المجانية</a></li>';
    echo '</ul>';
}
