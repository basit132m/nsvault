<?php
/**
 * Homepage Template
 */
get_header();

$best_roms    = nsvault_get_best_roms( 14 );
$new_roms     = nsvault_get_new_roms( 14 );
$updated_roms = nsvault_get_updated_roms( 12 );
$roms_archive = get_post_type_archive_link( 'roms' );
?>

<!-- === Best Switch ROMs Carousel === -->
<?php
get_template_part( 'template-parts/carousel', null, [
    'posts'    => $best_roms,
    'id'       => 'best-roms-carousel',
    'title'    => __( 'Best Switch ROMs', 'nsvault' ),
    'subtitle' => __( 'List of the best Nintendo Switch games ever released. All ROM files are available for free download at maximum speed.', 'nsvault' ),
    'view_all' => add_query_arg( 'sort', 'popular', $roms_archive ),
] );
?>

<!-- === New ROMs Added === -->
<?php if ( $new_roms ) : ?>
<section class="home-section">
    <div class="container">
        <div class="section-head">
            <div class="section-title">
                <?php esc_html_e( 'New ROMs Added', 'nsvault' ); ?>
                <small><?php esc_html_e( 'New games have been released, including upcoming titles', 'nsvault' ); ?></small>
            </div>
            <a href="<?php echo esc_url( $roms_archive ); ?>" class="section-link">
                <?php esc_html_e( 'New ROMs Added', 'nsvault' ); ?> →
            </a>
        </div>

        <div class="new-roms-grid">
            <?php foreach ( $new_roms as $np ) : ?>
                <?php get_template_part( 'template-parts/new-rom-item', null, [ 'post' => $np ] ); ?>
            <?php endforeach; ?>
            <div class="new-roms-more">
                <a href="<?php echo esc_url( $roms_archive ); ?>">
                    <?php esc_html_e( 'New ROMs Added', 'nsvault' ); ?> →
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- === Latest Updated ROMs Carousel === -->
<?php
get_template_part( 'template-parts/carousel', null, [
    'posts'    => $updated_roms,
    'id'       => 'updated-roms-carousel',
    'title'    => __( 'Latest Updated ROMs', 'nsvault' ),
    'subtitle' => __( 'These games receive updates or DLCs', 'nsvault' ),
    'view_all' => add_query_arg( 'sort', 'latest', $roms_archive ),
] );
?>

<!-- === About Block === -->
<section class="about-block">
    <div class="container">
        <?php
        // Use the homepage's post content if set, otherwise show default text
        $home_page_id = get_option( 'page_on_front' );
        if ( $home_page_id && get_post_status( $home_page_id ) === 'publish' ) {
            $post = get_post( $home_page_id );
            setup_postdata( $post );
            if ( $post->post_content ) {
                the_content();
                wp_reset_postdata();
            } else {
                nsvault_default_about_text();
            }
        } else {
            nsvault_default_about_text();
        }
        ?>
    </div>
</section>

<?php
get_footer();

function nsvault_default_about_text() {
    $site = get_bloginfo('name');
    ?>
    <p>Welcome to <strong><?php echo esc_html($site); ?></strong> – the "Ultimate Vault" dedicated to the Nintendo Switch Hack and Emulator community. If you are a true fan of Nintendo Switch games, whether playing on a hacked console or emulating on PC and Android, this is the place for you. Here, you can find everything you need related to ROMs for Hacked Nintendo Switch.</p>

    <h2>Why <?php echo esc_html($site); ?>?</h2>
    <p>Even though Nintendo has released the next generation of Nintendo Switch – their most successful handheld line – the original Switch still dominates the market thanks to Switch's success comes from the incredible ecosystem of games and accessories Nintendo created. However, the biggest barrier to reaching a wider audience is the high price of games with almost no discounts. Fortunately, the Switch has been hacked, allowing us to play pirated games. Playing pirated games is certainly nothing to be proud of; it is the best way to play every game you love for free.</p>

    <h2>What do we have for you?</h2>
    <p>Finding standard, clean, and high-speed ROMs has always been a challenge. Understanding this, <?php echo esc_html($site); ?> was born. We have taken on the mission of building a massive "vault" for the Switch hack and emulator community. Here, we host and distribute thousands of released Nintendo Switch titles. You can find everything from AAA blockbusters to hidden gems (Indie games) like Nino Sole or Hades.</p>

    <h3>Diverse Formats (NSP, XCI, NSZ)</h3>
    <p>Do you prefer original XCI files for emulation or compressed NSZ files to save storage? At <?php echo esc_html($site); ?>, we provide all the most popular formats. In particular, our ROMs are always updated with the latest Updates and DLC expansion packs. In short, your gaming experience will never be interrupted.</p>

    <h3>Blazing Download Speeds</h3>
    <p>Forget about waiting hours with bandwidth-limited download links or dealing with fraudulent ads. Links at <?php echo esc_html($site); ?> are optimized so you can "squeeze" every bit of your internet speed. Click to download, get the goods, and play!</p>
    <?php
}
