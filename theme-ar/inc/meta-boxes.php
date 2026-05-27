<?php
/**
 * Meta Boxes — Downloads CPT
 */
defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', function () {
    add_meta_box( 'sv_details',     'تفاصيل البرنامج / اللعبة', 'svault_render_details_box',    'downloads', 'normal', 'high' );
    add_meta_box( 'sv_downloads',   'روابط التحميل',             'svault_render_downloads_box',  'downloads', 'normal', 'high' );
    add_meta_box( 'sv_screenshots', 'لقطات الشاشة',              'svault_render_screenshots_box','downloads', 'normal', 'default' );
} );

/* ── Details ────────────────────────────────────────────── */
function svault_render_details_box( $post ) {
    wp_nonce_field( 'svault_details', 'svault_nonce' );
    $fields = [
        '_sv_version'      => [ 'الإصدار',        'مثال: v2.1.0',         'text' ],
        '_sv_size'         => [ 'الحجم',           'مثال: 1.5 GB',         'text' ],
        '_sv_type'         => [ 'النوع',           'مجاني / مدفوع / كراك', 'text' ],
        '_sv_platform'     => [ 'المنصة',          'Windows / Mac / Android','text' ],
        '_sv_language'     => [ 'اللغة',           'العربية، الإنجليزية…',   'text' ],
        '_sv_release_date' => [ 'تاريخ الإصدار',   '',                      'date' ],
        '_sv_rating'       => [ 'التقييم (1–5)',   '5.0',                   'number' ],
        '_sv_views'        => [ 'عدد المشاهدات',   '0',                     'number' ],
    ];
    echo '<table class="form-table">';
    foreach ( $fields as $key => [ $label, $placeholder, $type ] ) {
        $val   = esc_attr( get_post_meta( $post->ID, $key, true ) );
        $attrs = $type === 'number' ? 'min="0" step="0.1"' : '';
        echo '<tr>
            <th style="width:150px;text-align:right"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>
            <td><input type="' . esc_attr($type) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '"
                value="' . $val . '" placeholder="' . esc_attr($placeholder) . '"
                class="regular-text" style="max-width:400px" ' . $attrs . '></td>
        </tr>';
    }
    echo '</table>';
}

/* ── Download links ─────────────────────────────────────── */
function svault_render_downloads_box( $post ) {
    $dl1  = esc_attr( get_post_meta( $post->ID, '_sv_dl1',       true ) );
    $lb1  = esc_attr( get_post_meta( $post->ID, '_sv_dl1_label', true ) );
    $dl2  = esc_attr( get_post_meta( $post->ID, '_sv_dl2',       true ) );
    $lb2  = esc_attr( get_post_meta( $post->ID, '_sv_dl2_label', true ) );
    ?>
    <table class="form-table">
        <tr><th style="width:150px;text-align:right">اسم رابط التحميل 1</th>
            <td><input type="text" name="_sv_dl1_label" value="<?= $lb1 ?>" placeholder="تحميل مباشر" class="regular-text"></td></tr>
        <tr><th style="text-align:right">رابط التحميل 1</th>
            <td><input type="url"  name="_sv_dl1"       value="<?= $dl1 ?>" placeholder="https://..." class="large-text"></td></tr>
        <tr><th style="text-align:right">اسم رابط التحميل 2 (مرآة)</th>
            <td><input type="text" name="_sv_dl2_label" value="<?= $lb2 ?>" placeholder="رابط مرآة" class="regular-text"></td></tr>
        <tr><th style="text-align:right">رابط التحميل 2</th>
            <td><input type="url"  name="_sv_dl2"       value="<?= $dl2 ?>" placeholder="https://..." class="large-text"></td></tr>
    </table>
    <?php
}

/* ── Screenshots ────────────────────────────────────────── */
function svault_render_screenshots_box( $post ) {
    $ids = esc_attr( get_post_meta( $post->ID, '_sv_screenshots', true ) );
    ?>
    <p style="color:#aaa;font-size:13px">أدخل معرّفات الصور من مكتبة الوسائط مفصولةً بفواصل.</p>
    <input type="text" name="_sv_screenshots" id="_sv_screenshots" value="<?= $ids ?>"
           placeholder="مثال: 45, 46, 47" class="large-text" style="margin-bottom:10px">
    <button type="button" class="button" id="svault_select_ss">اختيار الصور</button>
    <div id="svault_ss_preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px">
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
        $('#svault_select_ss').on('click', function(){
            var f = wp.media({ title:'اختيار لقطات الشاشة', multiple:true });
            f.on('select', function(){
                var ids = f.state().get('selection').map(function(a){ return a.id; });
                $('#_sv_screenshots').val(ids.join(', '));
                var h = '';
                f.state().get('selection').each(function(a){
                    h += '<img src="'+a.attributes.sizes.thumbnail.url+'" style="width:80px;height:60px;object-fit:cover;border-radius:4px">';
                });
                $('#svault_ss_preview').html(h);
            });
            f.open();
        });
    });
    </script>
    <?php
}

/* ── Save ────────────────────────────────────────────────── */
add_action( 'save_post_downloads', function ( $id ) {
    if ( ! isset( $_POST['svault_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['svault_nonce'], 'svault_details' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $id ) ) return;

    $text = [ '_sv_version','_sv_size','_sv_type','_sv_platform','_sv_language',
              '_sv_release_date','_sv_dl1','_sv_dl1_label','_sv_dl2','_sv_dl2_label','_sv_screenshots' ];
    foreach ( $text as $k ) {
        if ( isset( $_POST[$k] ) ) update_post_meta( $id, $k, sanitize_text_field( $_POST[$k] ) );
    }
    if ( isset($_POST['_sv_rating']) ) update_post_meta( $id, '_sv_rating', (float) $_POST['_sv_rating'] );
    if ( isset($_POST['_sv_views'])  ) update_post_meta( $id, '_sv_views',  (int)   $_POST['_sv_views']  );
} );

/* ── Admin columns ───────────────────────────────────────── */
add_filter( 'manage_downloads_posts_columns', function ( $cols ) {
    $new = [];
    foreach ( $cols as $k => $v ) {
        $new[$k] = $v;
        if ( $k === 'title' ) {
            $new['sv_cover']   = 'الغلاف';
            $new['sv_type']    = 'النوع';
            $new['sv_size']    = 'الحجم';
            $new['sv_version'] = 'الإصدار';
            $new['sv_views']   = 'المشاهدات';
        }
    }
    return $new;
} );

add_action( 'manage_downloads_posts_custom_column', function ( $col, $pid ) {
    switch ( $col ) {
        case 'sv_cover':
            $img = get_the_post_thumbnail_url( $pid, 'sv-card' );
            if ($img) echo '<img src="'.esc_url($img).'" style="width:36px;height:48px;object-fit:cover;border-radius:3px">';
            break;
        case 'sv_type':    echo esc_html( get_post_meta($pid,'_sv_type',true) ?: '—' ); break;
        case 'sv_size':    echo esc_html( get_post_meta($pid,'_sv_size',true) ?: '—' ); break;
        case 'sv_version': echo esc_html( get_post_meta($pid,'_sv_version',true) ?: '—' ); break;
        case 'sv_views':   echo (int) get_post_meta($pid,'_sv_views',true); break;
    }
}, 10, 2 );
