<?php
/**
 * Plugin Name:       Inspirall · Trazabilidad
 * Plugin URI:        https://inspirall.pe/
 * Description:       Página de trazabilidad blockchain para Fico Crispy Blend, totalmente editable desde el panel: carga el pomo y las fotos de cada etapa, cambia textos y enlaces. Publica con el shortcode [inspirall_trazabilidad] o con la plantilla de pantalla completa. Lote dinámico por QR con ?lote=CODIGO.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Author:            Inspirall
 * Text Domain:       inspirall-traz
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'INSPITZ_VER', '1.0.0' );
define( 'INSPITZ_DIR', plugin_dir_path( __FILE__ ) );
define( 'INSPITZ_URL', plugin_dir_url( __FILE__ ) );

require_once INSPITZ_DIR . 'includes/config.php';
require_once INSPITZ_DIR . 'includes/admin.php';
require_once INSPITZ_DIR . 'includes/template.php';

/** Asset version = file mtime for cache-busting. */
function inspitz_asset_ver( $file ) {
	$path = INSPITZ_DIR . $file;
	return file_exists( $path ) ? filemtime( $path ) : INSPITZ_VER;
}

/** Register (not enqueue) front-end assets. */
function inspitz_register_assets() {
	wp_register_style( 'inspitz-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap', array(), null );
	wp_register_style( 'inspitz-styles', INSPITZ_URL . 'assets/styles.css', array( 'inspitz-fonts' ), inspitz_asset_ver( 'assets/styles.css' ) );
	wp_register_script( 'inspitz-script', INSPITZ_URL . 'assets/script.js', array(), inspitz_asset_ver( 'assets/script.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'inspitz_register_assets', 5 );

/** Enqueue assets when the shortcode is present on a singular view. */
add_action( 'wp_enqueue_scripts', function () {
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && has_shortcode( $post->post_content, 'inspirall_trazabilidad' ) ) {
			wp_enqueue_style( 'inspitz-styles' );
			wp_enqueue_script( 'inspitz-script' );
		}
	}
} );

/** Shortcode. */
add_shortcode( 'inspirall_trazabilidad', function () {
	wp_enqueue_style( 'inspitz-styles' );
	wp_enqueue_script( 'inspitz-script' );
	return inspitz_render();
} );

/* -------- Full-screen page template (standalone, sin cabecera del tema) -------- */
add_filter( 'theme_page_templates', function ( $t ) {
	$t['inspitz-fullscreen'] = 'Trazabilidad Inspirall (pantalla completa)';
	return $t;
} );
add_filter( 'template_include', function ( $template ) {
	if ( is_page() ) {
		$slug = get_page_template_slug( get_queried_object_id() );
		if ( $slug === 'inspitz-fullscreen' ) {
			$file = INSPITZ_DIR . 'templates/fullscreen.php';
			if ( file_exists( $file ) ) return $file;
		}
	}
	return $template;
} );

/* -------- Crear página automáticamente al activar -------- */
register_activation_hook( __FILE__, function () {
	if ( get_option( 'inspitz_page_id' ) ) return;
	$existing = get_page_by_path( 'trazabilidad-fico-crispy' );
	if ( $existing ) { update_option( 'inspitz_page_id', $existing->ID ); return; }
	$id = wp_insert_post( array(
		'post_title'   => 'Trazabilidad Fico Crispy',
		'post_name'    => 'trazabilidad-fico-crispy',
		'post_content' => '[inspirall_trazabilidad]',
		'post_status'  => 'publish',
		'post_type'    => 'page',
	) );
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_wp_page_template', 'inspitz-fullscreen' );
		update_option( 'inspitz_page_id', $id );
	}
} );

/** Link rápido a los ajustes desde la lista de plugins. */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	$url = admin_url( 'admin.php?page=inspirall-traz' );
	array_unshift( $links, '<a href="' . esc_url( $url ) . '">Editar contenido</a>' );
	return $links;
} );
