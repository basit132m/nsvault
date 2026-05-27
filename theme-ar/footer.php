</main>
</div>

<footer id="colophon" role="contentinfo">
    <div class="container">
        <div class="footer-inner">

            <div>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                    <?php
                    $parts = explode(' ', get_bloginfo('name'), 2);
                    echo '<span class="part1">' . esc_html($parts[0]) . '</span>';
                    echo '<span class="part2">' . esc_html($parts[1] ?? '') . '</span>';
                    ?>
                </a>
                <p class="footer-desc">
                    <?php echo esc_html( get_bloginfo('description') ?: 'تحميل أفضل البرامج والألعاب مجاناً بروابط مباشرة وبسرعة عالية.' ); ?>
                </p>
                <div class="footer-social">
                    <?php
                    $tg = get_theme_mod('svault_telegram','#');
                    if ($tg && $tg !== '#') :
                    ?>
                    <a href="<?php echo esc_url($tg); ?>" target="_blank" rel="noopener" aria-label="تيليجرام">
                        <i class="fa-brands fa-telegram"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <p class="footer-col-title">روابط سريعة</p>
                <nav class="footer-links" aria-label="روابط الفوتر">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => 'svault_footer_fallback',
                        'depth'          => 1,
                        'items_wrap'     => '%3$s',
                    ]);
                    ?>
                </nav>
            </div>

        </div>

        <div class="footer-bottom">
            <p>
                &copy; <?php echo date_i18n('Y'); ?> <?php bloginfo('name'); ?>. جميع الحقوق محفوظة.
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
<?php

function svault_footer_fallback() {
    $links = [
        'من نحن'        => home_url('/about/'),
        'سياسة الخصوصية' => home_url('/privacy-policy/'),
        'اتصل بنا'      => home_url('/contact/'),
    ];
    foreach ($links as $label => $url) {
        echo '<a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
    }
}
