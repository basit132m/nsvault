<?php
/**
 * NSVault Theme Functions
 */

defined( 'ABSPATH' ) || exit;

define( 'NSVAULT_VERSION', '1.0.0' );
define( 'NSVAULT_DIR',     get_template_directory() );
define( 'NSVAULT_URI',     get_template_directory_uri() );

/* ── Includes ─────────────────────────────────────────── */
require_once NSVAULT_DIR . '/inc/setup.php';
require_once NSVAULT_DIR . '/inc/enqueue.php';
require_once NSVAULT_DIR . '/inc/custom-post-types.php';
require_once NSVAULT_DIR . '/inc/taxonomies.php';
require_once NSVAULT_DIR . '/inc/meta-boxes.php';
require_once NSVAULT_DIR . '/inc/helpers.php';
