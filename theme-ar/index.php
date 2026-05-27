<?php get_header(); ?>
<div class="container" style="margin-bottom:40px;">
    <?php svault_breadcrumbs(); ?>
    <?php if (have_posts()) : ?>
        <div class="games-grid">
            <?php while(have_posts()):the_post();
                if (get_post_type()==='downloads') :
                    get_template_part('template-parts/game-card',null,['post'=>get_post()]);
                endif;
            endwhile; ?>
        </div>
        <?php svault_pagination(); ?>
    <?php else : ?>
        <div class="no-results-wrap"><h2>لا يوجد محتوى</h2></div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
