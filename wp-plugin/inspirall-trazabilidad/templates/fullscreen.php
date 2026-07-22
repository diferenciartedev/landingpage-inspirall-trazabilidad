<?php
/** Standalone full-screen template — renders the landing without the theme chrome. */
if ( ! defined( 'ABSPATH' ) ) exit;

$css_ver = inspitz_asset_ver( 'assets/styles.css' );
$js_ver  = inspitz_asset_ver( 'assets/script.js' );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="theme-color" content="#ffffff" />
	<title><?php echo esc_html( get_the_title() ); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo esc_url( INSPITZ_URL . 'assets/styles.css' ); ?>?v=<?php echo esc_attr( $css_ver ); ?>" />
	<?php if ( function_exists( 'wp_site_icon' ) ) wp_site_icon(); ?>
</head>
<body <?php body_class( 'inspitz-body' ); ?>>
	<?php echo inspitz_render(); ?>
	<script src="<?php echo esc_url( INSPITZ_URL . 'assets/script.js' ); ?>?v=<?php echo esc_attr( $js_ver ); ?>"></script>
</body>
</html>
<?php
exit;
