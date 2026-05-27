<?php
/**
 * ROM Meta Boxes
 */
defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'rom_details',
        __( '🎮 ROM Details', 'nsvault' ),
        'nsvault_render_rom_details_metabox',
        'roms',
        'normal',
        'high'
    );
    add_meta_box(
        'rom_downloads',
        __( '⬇️ Download Links', 'nsvault' ),
        'nsvault_render_rom_downloads_metabox',
        'roms',
        'normal',
        'high'
    );
    add_meta_box(
        'rom_screenshots',
        __( '🖼️ Screenshots', 'nsvault' ),
        'nsvault_render_rom_screenshots_metabox',
        'roms',
        'normal',
        'default'
    );
} );

function nsvault_render_rom_details_metabox( $post ) {
    wp_nonce_field( 'nsvault_rom_details', 'nsvault_rom_nonce' );
    $fields = [
        '_rom_version'      => [ 'label' => 'Version Number',    'placeholder' => 'e.g. v1.0.3',          'type' => 'text' ],
        '_rom_type'         => [ 'label' => 'ROM Type',          'placeholder' => 'NSP / XCI / NSZ',       'type' => 'text' ],
        '_rom_size'         => [ 'label' => 'File Size',         'placeholder' => 'e.g. 3.12 GB',          'type' => 'text' ],
        '_rom_title_id'     => [ 'label' => 'Title ID',          'placeholder' => '0100490011...',          'type' => 'text' ],
        '_rom_language'     => [ 'label' => 'Language(s)',       'placeholder' => 'English, French...',     'type' => 'text' ],
        '_rom_release_date' => [ 'label' => 'Release Date',      'placeholder' => 'YYYY-MM-DD',             'type' => 'date' ],
        '_rom_rating'       => [ 'label' => 'Rating (1–5)',      'placeholder' => '5.0',                    'type' => 'number' ],
        '_rom_views'        => [ 'label' => 'View Count',        'placeholder' => '0',                      'type' => 'number' ],
    ];
    echo '<table class="form-table" style="width:100%">';
    foreach ( $fields as $key => $field ) {
        $val = esc_attr( get_post_meta( $post->ID, $key, true ) );
        $attrs = '';
        if ( $field['type'] === 'number' ) $attrs = 'min="0" step="0.1"';
        echo '<tr>
            <th style="width:160px"><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th>
            <td><input type="' . esc_attr($field['type']) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . $val . '" placeholder="' . esc_attr($field['placeholder']) . '" style="width:100%;max-width:400px" class="regular-text" ' . $attrs . '></td>
        </tr>';
    }
    echo '</table>';
}

function nsvault_render_rom_downloads_metabox( $post ) {
    $dl1       = esc_attr( get_post_meta( $post->ID, '_rom_download_1',       true ) );
    $dl1_label = esc_attr( get_post_meta( $post->ID, '_rom_download_1_label', true ) );
    $dl2       = esc_attr( get_post_meta( $post->ID, '_rom_download_2',       true ) );
    $dl2_label = esc_attr( get_post_meta( $post->ID, '_rom_download_2_label', true ) );
    ?>
    <table class="form-table">
        <tr>
            <th style="width:160px"><label for="_rom_download_1_label">Download 1 Label</label></th>
            <td><input type="text" id="_rom_download_1_label" name="_rom_download_1_label" value="<?= $dl1_label ?>" placeholder="Download Now" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="_rom_download_1">Download 1 URL</label></th>
            <td><input type="url" id="_rom_download_1" name="_rom_download_1" value="<?= $dl1 ?>" placeholder="https://..." class="large-text"></td>
        </tr>
        <tr>
            <th><label for="_rom_download_2_label">Download 2 Label</label></th>
            <td><input type="text" id="_rom_download_2_label" name="_rom_download_2_label" value="<?= $dl2_label ?>" placeholder="DOWNLOAD ROM" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="_rom_download_2">Download 2 URL</label></th>
            <td><input type="url" id="_rom_download_2" name="_rom_download_2" value="<?= $dl2 ?>" placeholder="https://..." class="large-text"></td>
        </tr>
    </table>
    <?php
}

function nsvault_render_rom_screenshots_metabox( $post ) {
    $ids = get_post_meta( $post->ID, '_rom_screenshots', true );
    ?>
    <p style="color:#999;font-size:13px">Enter comma-separated Media Library attachment IDs for the game screenshots.</p>
    <input type="text" name="_rom_screenshots" id="_rom_screenshots"
           value="<?= esc_attr( $ids ) ?>"
           placeholder="e.g. 45, 46, 47"
           class="large-text" style="margin-bottom:10px">
    <button type="button" class="button" id="nsvault_select_screenshots">Select Images</button>
    <div id="nsvault_screenshot_preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px">
        <?php
        if ( $ids ) {
            foreach ( array_filter( array_map( 'intval', explode( ',', $ids ) ) ) as $id ) {
                $url = wp_get_attachment_image_url( $id, 'thumbnail' );
                if ( $url ) echo '<img src="' . esc_url($url) . '" style="width:80px;height:60px;object-fit:cover;border-radius:4px">';
            }
        }
        ?>
    </div>
    <script>
    jQuery(function($){
        $('#nsvault_select_screenshots').on('click', function(){
            var frame = wp.media({ title:'Select Screenshots', multiple:true });
            frame.on('select', function(){
                var ids = frame.state().get('selection').map(function(a){ return a.id; });
                $('#_rom_screenshots').val(ids.join(', '));
                var html = '';
                frame.state().get('selection').each(function(a){
                    html += '<img src="'+a.attributes.sizes.thumbnail.url+'" style="width:80px;height:60px;object-fit:cover;border-radius:4px">';
                });
                $('#nsvault_screenshot_preview').html(html);
            });
            frame.open();
        });
    });
    </script>
    <?php
}

/**
 * Save meta
 */
add_action( 'save_post_roms', function ( $post_id ) {
    if ( ! isset( $_POST['nsvault_rom_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['nsvault_rom_nonce'], 'nsvault_rom_details' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $text_fields = [
        '_rom_version', '_rom_type', '_rom_size', '_rom_title_id',
        '_rom_language', '_rom_release_date', '_rom_download_1',
        '_rom_download_1_label', '_rom_download_2', '_rom_download_2_label',
        '_rom_screenshots',
    ];
    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        }
    }
    if ( isset( $_POST['_rom_rating'] ) ) {
        update_post_meta( $post_id, '_rom_rating', (float) $_POST['_rom_rating'] );
    }
    if ( isset( $_POST['_rom_views'] ) ) {
        update_post_meta( $post_id, '_rom_views', (int) $_POST['_rom_views'] );
    }
} );

/**
 * Admin columns
 */
add_filter( 'manage_roms_posts_columns', function ( $cols ) {
    $new = [];
    foreach ( $cols as $k => $v ) {
        $new[ $k ] = $v;
        if ( $k === 'title' ) {
            $new['rom_cover']   = __( 'Cover', 'nsvault' );
            $new['rom_type']    = __( 'Type', 'nsvault' );
            $new['rom_size']    = __( 'Size', 'nsvault' );
            $new['rom_version'] = __( 'Version', 'nsvault' );
            $new['rom_views']   = __( 'Views', 'nsvault' );
        }
    }
    return $new;
} );

add_action( 'manage_roms_posts_custom_column', function ( $col, $post_id ) {
    switch ( $col ) {
        case 'rom_cover':
            $img = get_the_post_thumbnail_url( $post_id, 'rom-card' );
            if ( $img ) echo '<img src="' . esc_url($img) . '" style="width:36px;height:48px;object-fit:cover;border-radius:3px">';
            break;
        case 'rom_type':
            echo esc_html( get_post_meta( $post_id, '_rom_type', true ) ?: '—' );
            break;
        case 'rom_size':
            echo esc_html( get_post_meta( $post_id, '_rom_size', true ) ?: '—' );
            break;
        case 'rom_version':
            echo esc_html( get_post_meta( $post_id, '_rom_version', true ) ?: '—' );
            break;
        case 'rom_views':
            echo (int) get_post_meta( $post_id, '_rom_views', true );
            break;
    }
}, 10, 2 );
