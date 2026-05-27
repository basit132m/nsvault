</main><!-- #main-content -->
</div><!-- #page -->

<!-- ===================== FOOTER ===================== -->
<footer id="colophon" role="contentinfo">
    <div class="container">
        <div class="footer-inner">

            <!-- Brand col -->
            <div class="footer-brand-col">
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="footer-logo">
                    <span class="ns">NS</span><span class="vault">VAULT.ME</span>
                </a>
                <p class="footer-desc">
                    <?php echo esc_html( get_bloginfo('description') ?: 'Download Nintendo Switch ROMs (NSP/XCI) for Emulator' ); ?>
                </p>
                <div class="footer-social">
                    <?php
                    $telegram = get_theme_mod( 'nsvault_telegram', '#' );
                    if ( $telegram && $telegram !== '#' ) : ?>
                        <a href="<?php echo esc_url($telegram); ?>" target="_blank" rel="noopener" aria-label="Telegram">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-links-col">
                <p class="footer-col-title"><?php esc_html_e( 'QUICK LINKS', 'nsvault' ); ?></p>
                <nav class="footer-links" aria-label="Footer Links">
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'container'      => false,
                        'fallback_cb'    => 'nsvault_footer_fallback_nav',
                        'depth'          => 1,
                        'items_wrap'     => '%3$s',
                    ] );
                    ?>
                </nav>
            </div>

        </div><!-- .footer-inner -->

        <div class="footer-bottom">
            <p>
                &copy; <?php echo date_i18n( 'Y' ); ?> <?php bloginfo( 'name' ); ?>.
                <?php esc_html_e( 'All rights reserved.', 'nsvault' ); ?>
            </p>
        </div>

    </div><!-- .container -->
</footer>

<?php wp_footer(); ?>
</body>
</html>
<?php

function nsvault_footer_fallback_nav() {
    $links = [
        __( 'About Us',      'nsvault' ) => home_url( '/about-us/' ),
        __( 'Privacy Policy','nsvault' ) => home_url( '/privacy-policy/' ),
        __( 'Contact',       'nsvault' ) => home_url( '/contact/' ),
    ];
    foreach ( $links as $label => $url ) {
        echo '<a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
    }
}
