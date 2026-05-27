<?php
/**
 * Generic Page Template
 */
get_header();
the_post();
?>

<div class="container" style="max-width:860px; margin-bottom:40px;">
    <?php nsvault_breadcrumbs(); ?>

    <article class="page-content">
        <h1 style="margin-bottom:20px;font-size:1.6rem;"><?php the_title(); ?></h1>
        <?php the_content(); ?>
    </article>
</div>

<?php get_footer(); ?>
