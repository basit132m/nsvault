<?php
/**
 * SoftVault AR — Functions
 */
defined( 'ABSPATH' ) || exit;

define( 'SVAULT_VERSION', '1.0.0' );
define( 'SVAULT_DIR',     get_template_directory() );
define( 'SVAULT_URI',     get_template_directory_uri() );

require_once SVAULT_DIR . '/inc/setup.php';
require_once SVAULT_DIR . '/inc/enqueue.php';
require_once SVAULT_DIR . '/inc/permalink-settings.php';
require_once SVAULT_DIR . '/inc/custom-post-types.php';
require_once SVAULT_DIR . '/inc/taxonomies.php';
require_once SVAULT_DIR . '/inc/meta-boxes.php';
require_once SVAULT_DIR . '/inc/helpers.php';
require_once SVAULT_DIR . '/inc/download-go.php';
