<?php
/**
 * Single ROM page: /roms/{slug}/
 */
get_header();

the_post();

$pid         = get_the_ID();
$title       = get_the_title();
$version     = nsvault_get_meta( '_rom_version' );
$type        = nsvault_get_meta( '_rom_type' );
$size        = nsvault_get_meta( '_rom_size' );
$title_id    = nsvault_get_meta( '_rom_title_id' );
$language    = nsvault_get_meta( '_rom_language' );
$rel_date    = nsvault_get_meta( '_rom_release_date' );
$dl1_url     = nsvault_get_meta( '_rom_download_1' );
$dl1_label   = nsvault_get_meta( '_rom_download_1_label' ) ?: __( 'Download Now', 'nsvault' );
$dl2_url     = nsvault_get_meta( '_rom_download_2' );
$dl2_label   = nsvault_get_meta( '_rom_download_2_label' ) ?: __( 'DOWNLOAD ROM', 'nsvault' );
$rating      = (float) nsvault_get_meta( '_rom_rating' );
$views       = (int)   nsvault_get_meta( '_rom_views' );
$cover       = get_the_post_thumbnail_url( $pid, 'rom-cover' );
$genres      = nsvault_get_genres();
$developers  = nsvault_get_developers();
$screenshots = nsvault_get_screenshots();
$related     = nsvault_get_related( $pid, 6 );

// Sidebar: popular in same genre
$genre_id  = $genres ? $genres[0]->term_id : null;
$genre_name = $genres ? $genres[0]->name : __( 'All', 'nsvault' );
$sidebar_popular = nsvault_get_popular_by_genre( $genre_id, 6, $pid );
?>

<div class="container">
    <?php nsvault_breadcrumbs(); ?>
</div>

<div class="container" style="margin-bottom:40px;">
<div class="game-single-wrap">

    <!-- ===================== MAIN COL ===================== -->
    <div class="game-main-col">

        <!-- Game Header Box -->
        <div class="game-header-box">
            <div class="game-header-box-inner">
                <div class="game-header-top">

                    <!-- Cover image -->
                    <div class="game-cover-wrap">
                        <?php if ( $cover ) : ?>
                            <img class="game-cover-img" src="<?php echo esc_url($cover); ?>" alt="<?php echo esc_attr($title); ?>">
                        <?php else : ?>
                            <div class="game-cover-placeholder"><i class="fa-solid fa-gamepad"></i></div>
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
                    <div class="game-header-info">
                        <?php if ( $version ) : ?>
                            <span class="game-version-badge"><?php echo esc_html($version); ?></span>
                        <?php endif; ?>
                        <?php if ( $developers ) : ?>
                            <span>by
                                <?php foreach ( $developers as $i => $dev ) :
                                    $comma = $i < count($developers) - 1 ? ', ' : '';
                                    echo '<a href="' . esc_url( get_term_link($dev) ) . '" class="game-developer-link">' . esc_html($dev->name) . '</a>' . $comma;
                                endforeach; ?>
                            </span>
                        <?php endif; ?>

                        <h1><?php the_title(); ?></h1>

                        <?php if ( $rating ) : ?>
                            <div class="game-rating">
                                <?php echo nsvault_stars_html( $rating ); ?>
                                <span class="game-rating-score"><?php echo esc_html( number_format($rating, 1) ); ?></span>
                                <span class="game-rating-count">(<?php echo number_format_i18n($views); ?> <?php esc_html_e('votes','nsvault'); ?>)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info table -->
                <table class="game-info-table">
                    <tbody>
                        <?php if ( $genres ) : ?>
                        <tr>
                            <td><?php esc_html_e( 'Genre', 'nsvault' ); ?></td>
                            <td>
                                <?php foreach ( $genres as $i => $g ) :
                                    $comma = $i < count($genres) - 1 ? ', ' : '';
                                    echo '<a href="' . esc_url(get_term_link($g)) . '">' . esc_html($g->name) . '</a>' . $comma;
                                endforeach; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ( $type ) : ?>
                        <tr>
                            <td><?php esc_html_e( 'Type', 'nsvault' ); ?></td>
                            <td style="color:var(--primary);font-weight:700"><?php echo esc_html($type); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ( $size ) : ?>
                        <tr>
                            <td><?php esc_html_e( 'Game Size', 'nsvault' ); ?></td>
                            <td><?php echo esc_html($size); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ( $version ) : ?>
                        <tr>
                            <td><?php esc_html_e( 'Version Number', 'nsvault' ); ?></td>
                            <td style="color:var(--primary)"><?php echo esc_html($version); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ( $title_id ) : ?>
                        <tr>
                            <td><?php esc_html_e( 'Title ID', 'nsvault' ); ?></td>
                            <td style="font-family:monospace;font-size:0.82rem"><?php echo esc_html($title_id); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ( $language ) : ?>
                        <tr>
                            <td><?php esc_html_e( 'Language', 'nsvault' ); ?></td>
                            <td><?php echo esc_html($language); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if ( $rel_date ) : ?>
                        <tr>
                            <td><?php esc_html_e( 'Release Date', 'nsvault' ); ?></td>
                            <td><?php echo esc_html( date_i18n( 'Y-m-d', strtotime($rel_date) ) ); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Download Buttons -->
                <div class="game-download-wrap">
                    <?php if ( $dl1_url ) : ?>
                        <a href="<?php echo esc_url($dl1_url); ?>" class="btn-download btn-download-primary" rel="nofollow" target="_blank">
                            <i class="fa-solid fa-download"></i>
                            <?php echo esc_html($dl1_label); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ( $dl2_url ) : ?>
                        <a href="<?php echo esc_url($dl2_url); ?>" class="btn-download btn-download-outline" rel="nofollow" target="_blank">
                            <i class="fa-solid fa-link"></i>
                            <?php echo esc_html($dl2_label); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div><!-- .game-header-box -->

        <!-- Description -->
        <?php if ( get_the_content() ) : ?>
        <div class="game-description-box">
            <?php the_content(); ?>
        </div>
        <?php endif; ?>

        <!-- Screenshots -->
        <?php if ( $screenshots ) : ?>
        <div class="game-screenshots-box">
            <div class="box-title"><?php esc_html_e( 'Game Screenshots', 'nsvault' ); ?></div>
            <div class="screenshots-grid">
                <?php foreach ( $screenshots as $att_id ) :
                    $img = wp_get_attachment_image_src( $att_id, 'rom-screenshot' );
                    $full = wp_get_attachment_image_src( $att_id, 'full' );
                    if ( ! $img ) continue;
                ?>
                    <a href="<?php echo esc_url($full[0]); ?>" class="screenshot-item" data-lightbox="screenshots">
                        <img src="<?php echo esc_url($img[0]); ?>" alt="<?php echo esc_attr(get_post_field('post_title', $att_id)); ?>" loading="lazy">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Problem Report Bar -->
        <div class="problem-report-bar">
            <span>
                <i class="fa-solid fa-circle-info" style="margin-right:4px"></i>
                <?php esc_html_e( 'Problems with download or installation?', 'nsvault' ); ?>
            </span>
            <a href="<?php echo esc_url( home_url('/contact/') ); ?>" class="btn-report">
                <i class="fa-solid fa-flag"></i>
                <?php esc_html_e( 'Report', 'nsvault' ); ?>
            </a>
        </div>

        <!-- FAQ -->
        <div class="faq-box">
            <?php
            $faqs = [
                __( 'How to install NSZ/XCZ ROMs?', 'nsvault' ) =>
                    __( 'To install NSZ/XCZ ROMs on a hacked Nintendo Switch, you can use Tinfoil or DBI. Open the installer, navigate to your file location, select the NSZ/XCZ file, and install. For emulators like Ryujinx or Yuzu, simply open the emulator, add the game directory, and the game will appear automatically.', 'nsvault' ),
                __( 'Having problems?', 'nsvault' ) =>
                    __( 'If you encounter issues downloading or installing, first check your internet connection. For installation problems on a hacked Switch, make sure your firmware and sigpatches are up to date. For emulator issues, ensure you have the latest emulator version and proper system files. Contact us through the report button above if the issue persists.', 'nsvault' ),
            ];
            foreach ( $faqs as $q => $a ) : ?>
                <div class="faq-item">
                    <button class="faq-question" type="button">
                        <?php echo esc_html($q); ?>
                        <span class="faq-icon"><i class="fa-solid fa-plus"></i></span>
                    </button>
                    <div class="faq-answer">
                        <p><?php echo esc_html($a); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Comments -->
        <div class="comments-box">
            <div class="box-title"><?php esc_html_e( 'COMMENTS', 'nsvault' ); ?></div>
            <p class="comments-note"><?php esc_html_e( 'Your comment will appear after admin approval.', 'nsvault' ); ?></p>

            <?php if ( comments_open() ) : ?>
            <div class="comment-form-wrap">
                <form method="post" action="<?php echo esc_url( site_url('/wp-comments-post.php') ); ?>">
                    <?php wp_nonce_field( 'comment_nonce', 'comment_nonce_field' ); ?>
                    <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($pid); ?>">
                    <input type="hidden" name="redirect_to" value="<?php echo esc_attr( get_permalink() ); ?>">

                    <textarea name="comment" placeholder="<?php esc_attr_e( 'Write your comment…', 'nsvault' ); ?>" required></textarea>

                    <div class="comment-form-fields">
                        <input type="text"  name="author" placeholder="<?php esc_attr_e( 'Name *',  'nsvault' ); ?>" required>
                        <input type="email" name="email"  placeholder="<?php esc_attr_e( 'Email *', 'nsvault' ); ?>" required>
                    </div>

                    <button type="submit" class="btn-submit-comment"><?php esc_html_e( 'Submit Comment', 'nsvault' ); ?></button>
                </form>
            </div>
            <?php endif; ?>

            <!-- Approved comments -->
            <?php
            $comments = get_comments( [ 'post_id' => $pid, 'status' => 'approve', 'order' => 'DESC' ] );
            if ( $comments ) :
            ?>
            <div class="comments-list">
                <?php foreach ( $comments as $comment ) : ?>
                    <div class="comment-item">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span class="comment-author"><?php echo esc_html( $comment->comment_author ); ?></span>
                            <span class="comment-date"><?php echo esc_html( date_i18n( 'M d, Y', strtotime($comment->comment_date) ) ); ?></span>
                        </div>
                        <div class="comment-body"><?php echo esc_html( $comment->comment_content ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Related Games -->
        <?php if ( $related ) : ?>
        <div class="related-box">
            <div class="box-title">
                <?php printf( esc_html__( 'Related with %s', 'nsvault' ), get_the_title() ); ?>
                <a href="<?php echo esc_url( $roms_archive ); ?>" style="font-size:0.75rem;font-weight:400;margin-left:auto;color:var(--text-muted)"><?php esc_html_e('View all','nsvault'); ?> ›</a>
            </div>
            <div class="related-grid">
                <?php foreach ( $related as $rp ) : ?>
                    <?php get_template_part( 'template-parts/game-card', null, [ 'post' => $rp ] ); ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif;
$roms_archive = get_post_type_archive_link( 'roms' );
?>

    </div><!-- .game-main-col -->

    <!-- ===================== SIDEBAR ===================== -->
    <aside class="game-sidebar">
        <?php if ( $sidebar_popular ) : ?>
        <div class="sidebar-widget">
            <div class="sidebar-widget-title">
                <?php printf( esc_html__( 'Most popular in %s', 'nsvault' ), esc_html($genre_name) ); ?>
            </div>
            <div class="sidebar-widget-body">
                <?php foreach ( $sidebar_popular as $sp ) :
                    $sp_thumb  = get_the_post_thumbnail_url( $sp->ID, 'rom-card' );
                    $sp_views  = (int) get_post_meta( $sp->ID, '_rom_views', true );
                    $sp_size   = get_post_meta( $sp->ID, '_rom_size', true );
                ?>
                    <a href="<?php echo esc_url( get_permalink($sp->ID) ); ?>" class="sidebar-game-item">
                        <?php if ( $sp_thumb ) : ?>
                            <img src="<?php echo esc_url($sp_thumb); ?>" alt="<?php echo esc_attr(get_the_title($sp->ID)); ?>" loading="lazy">
                        <?php else : ?>
                            <div style="width:36px;height:48px;background:var(--surface-3);border-radius:3px;display:flex;align-items:center;justify-content:center;color:var(--text-dim);flex-shrink:0;font-size:1.1rem"><i class="fa-solid fa-gamepad"></i></div>
                        <?php endif; ?>
                        <div class="sidebar-game-info">
                            <div class="sidebar-game-title"><?php echo esc_html( get_the_title($sp->ID) ); ?></div>
                            <div class="sidebar-game-meta">
                                <?php if ($sp_size) echo esc_html($sp_size) . ' · '; ?>
                                <?php echo esc_html( nsvault_format_views($sp_views) ); ?> views
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </aside>

</div><!-- .game-single-wrap -->
</div><!-- .container -->

<?php get_footer(); ?>
