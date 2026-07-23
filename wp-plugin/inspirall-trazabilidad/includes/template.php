<?php
/** Inspirall Trazabilidad — front-end renderer (clean, ordered layout). */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ---- tiny helpers bound to the current content array ---- */
function iz( $k )  { $o = $GLOBALS['inspitz_o']; return esc_html( isset( $o[$k] ) ? $o[$k] : '' ); }
function iza( $k ) { $o = $GLOBALS['inspitz_o']; return esc_attr( isset( $o[$k] ) ? $o[$k] : '' ); }
function izu( $k ) { $o = $GLOBALS['inspitz_o']; return esc_url( isset( $o[$k] ) ? $o[$k] : '' ); }
function iznl( $k ){ $o = $GLOBALS['inspitz_o']; return nl2br( esc_html( isset( $o[$k] ) ? $o[$k] : '' ) ); }
function izraw( $k ){ $o = $GLOBALS['inspitz_o']; return isset( $o[$k] ) ? $o[$k] : ''; }
function iz_show( $k ){ $o = $GLOBALS['inspitz_o']; return ! isset( $o[$k] ) || $o[$k] === '1'; }

/** figure.shot with real photo (if set) + fallback markup. */
function iz_shot( $url, $wrapClass, $alt, $fallback, $style = '' ) {
	$img = '';
	if ( $url ) {
		$img = '<img class="shot__img" src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" '
			. 'onload="this.closest(\'.shot\').classList.add(\'shot--hasimg\')" onerror="this.remove()" />';
	}
	$st = $style ? ' style="' . esc_attr( $style ) . '"' : '';
	return '<figure class="shot ' . esc_attr( $wrapClass ) . '"' . $st . '>' . $img . $fallback . '</figure>';
}

/** Photo column: real image or labelled placeholder. */
function iz_media( $key, $alt, $label, $tall = false ) {
	$panel = '<div class="photo-panel' . ( $tall ? ' photo-panel--tall' : '' ) . '" data-photo="' . esc_attr( $label ) . '">'
		. '<span class="photo-panel__hint">Foto real (opcional)</span></div>';
	$ar = $tall ? '--shot-ar:3 / 4' : '--shot-ar:4 / 3';
	return '<div class="stage__media">' . iz_shot( izraw( $key ), $tall ? 'shot--tall' : 'shot--photo', $alt, $panel, $ar ) . '</div>';
}

/** Inline SVG icon by name (stroke = currentColor). */
function iz_ico( $name ) {
	$p = array(
		'pin'      => '<path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="10" r="2.6" fill="none" stroke="currentColor" stroke-width="1.6"/>',
		'calclock' => '<rect x="4" y="5" width="16" height="15" rx="2.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M4 9h16M8 3v3M16 3v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="15" cy="14" r="3" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M15 12.6V14l1 .8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>',
		'leaf'     => '<path d="M5 19c8 1 14-4 15-15-9 0-15 3-15 11 0 2 0 3 1 4z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M5 19c3-6 7-9 11-11" fill="none" stroke="currentColor" stroke-width="1.4"/>',
		'person'   => '<circle cx="12" cy="8" r="3.4" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
		'scale'    => '<path d="M12 4v16M7 20h10M6 8h12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M6 8l-2.5 5a2.5 2.5 0 0 0 5 0L6 8zM18 8l-2.5 5a2.5 2.5 0 0 0 5 0L18 8z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>',
		'box'      => '<path d="M12 3l8 4.2v9.6L12 21l-8-4.2V7.2L12 3z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M4 7.2l8 4.2 8-4.2M12 21v-9.6" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>',
		'tag'      => '<path d="M4 12.5V5a1 1 0 0 1 1-1h7.5L20 11.5a2 2 0 0 1 0 2.8l-5.7 5.7a2 2 0 0 1-2.8 0L4 12.5z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="8.5" cy="8.5" r="1.4" fill="currentColor"/>',
		'globe'    => '<circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M3.5 12h17M12 3.5c3 3 3 14 0 17M12 3.5c-3 3-3 14 0 17" fill="none" stroke="currentColor" stroke-width="1.3"/>',
		'truck'    => '<path d="M3 6h11v9H3zM14 9h4l3 3v3h-7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="7" cy="18" r="1.8" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="18" r="1.8" fill="none" stroke="currentColor" stroke-width="1.6"/>',
		'shield'   => '<path d="M12 3l7 3v6c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M9 12l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>',
		'sun'      => '<circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M12 2v2.5M12 19.5V22M2 12h2.5M19.5 12H22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M19.1 4.9l-1.8 1.8M6.7 17.3l-1.8 1.8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
	);
	$body = isset( $p[$name] ) ? $p[$name] : $p['pin'];
	return '<svg class="fact__ico" viewBox="0 0 24 24" aria-hidden="true">' . $body . '</svg>';
}

/** One data point (icon + label + value). $value is raw HTML. */
function iz_fact( $ico, $label, $value_html ) {
	return '<div class="fact">' . iz_ico( $ico )
		. '<span class="fact__k">' . esc_html( $label ) . '</span>'
		. '<p class="fact__v">' . $value_html . '</p></div>';
}

/** brand logo (image if set, else styled wordmark). */
function iz_brand( $light = false ) {
	$o = $GLOBALS['inspitz_o'];
	$logo = $light ? $o['logo_footer'] : $o['logo_header'];
	$cls  = 'brand' . ( $light ? ' brand--light' : '' );
	$img  = '';
	if ( $logo ) {
		$img = '<img class="brand__img" src="' . esc_url( $logo ) . '" alt="' . esc_attr( $o['brand_word'] ) . '" '
			. 'onload="this.closest(\'.brand\').classList.add(\'brand--haslogo\')" onerror="this.remove()" />';
	}
	$svg = '<svg class="brand__mark" viewBox="0 0 32 32" aria-hidden="true">'
		. '<path d="M16 4a12 12 0 1 0 8.5 20.5" fill="none" stroke="var(--brand)" stroke-width="2.6" stroke-linecap="round"/>'
		. '<path d="M16 10a6 6 0 1 0 4.2 10.2" fill="none" stroke="var(--brand-600)" stroke-width="2.6" stroke-linecap="round"/>'
		. '<circle cx="16" cy="16" r="1.8" fill="var(--brand)"/></svg>';
	return '<a class="' . $cls . '" href="#hero" aria-label="' . esc_attr( $o['brand_word'] ) . '">'
		. $img . $svg . '<span class="brand__word">' . esc_html( $o['brand_word'] ) . '</span></a>';
}

/** Critical layout CSS printed inline (theme-proof: #inspitz-app + !important). */
function inspitz_critical_css() {
	$css = <<<CSS
#inspitz-app,#inspitz-app *,#inspitz-app *::before,#inspitz-app *::after{box-sizing:border-box}
#inspitz-app{overflow-x:hidden}
#inspitz-app figure{margin:0!important}
#inspitz-app img{max-width:100%;height:auto}
#inspitz-app main{margin:0!important;padding:0!important;max-width:none!important;width:auto!important}
#inspitz-app section{margin:0!important}
#inspitz-app .wrap{width:100%;max-width:1200px;margin-inline:auto;padding-inline:clamp(1.1rem,4vw,2.4rem)}
#inspitz-app .hero__grid{display:grid!important;grid-template-columns:1.1fr .9fr!important;gap:clamp(2rem,5vw,4rem)}
#inspitz-app .product__grid{display:grid!important;grid-template-columns:.82fr 1.18fr!important;gap:clamp(2rem,5vw,4rem)}
#inspitz-app .nutrition__grid{display:grid!important;grid-template-columns:.85fr 1.15fr!important}
#inspitz-app .proc{display:grid!important;grid-template-columns:repeat(4,1fr)!important}
#inspitz-app .stage__grid{display:grid!important;grid-template-columns:64px minmax(0,1fr)!important;column-gap:clamp(1rem,2.5vw,2rem)}
#inspitz-app .stage__cols{display:grid!important;grid-template-columns:1.02fr .98fr!important;gap:clamp(1.6rem,3.6vw,3rem);align-items:start}
#inspitz-app .stage__cols--rev .stage__media{order:2}
#inspitz-app .facts{display:grid!important;grid-template-columns:1fr 1fr!important;gap:1.8rem 1.6rem}
#inspitz-app .checks{display:grid!important;grid-template-columns:1fr 1fr!important}
#inspitz-app .zones{display:grid!important;grid-template-columns:repeat(auto-fill,minmax(220px,1fr))!important}
#inspitz-app .benefits{display:grid!important;grid-template-columns:repeat(4,1fr)!important}
#inspitz-app .pillars{display:grid!important;grid-template-columns:repeat(3,1fr)!important}
#inspitz-app .blockchain__grid{display:grid!important;grid-template-columns:1.15fr .85fr!important}
#inspitz-app .feature__grid{display:grid!important;grid-template-columns:1.1fr .9fr!important}
#inspitz-app .certs__grid{display:grid!important;grid-template-columns:.8fr 1.2fr!important}
#inspitz-app .footer__grid{display:grid!important;grid-template-columns:1.4fr 1fr 1fr 1fr!important}
#inspitz-app .shot__img{width:100%!important;height:100%!important;object-fit:cover;display:block}
@media (max-width:960px){
 #inspitz-app .hero__grid,#inspitz-app .product__grid,#inspitz-app .nutrition__grid,#inspitz-app .blockchain__grid,#inspitz-app .feature__grid,#inspitz-app .certs__grid{grid-template-columns:1fr!important}
 #inspitz-app .proc,#inspitz-app .benefits{grid-template-columns:1fr 1fr!important}
 #inspitz-app .pillars,#inspitz-app .footer__grid{grid-template-columns:1fr 1fr!important}
}
@media (max-width:760px){
 #inspitz-app .stage__cols{grid-template-columns:1fr!important}
 #inspitz-app .stage__cols--rev .stage__media{order:0}
}
@media (max-width:620px){
 #inspitz-app .stage__grid{grid-template-columns:44px minmax(0,1fr)!important}
 #inspitz-app .checks,#inspitz-app .facts{grid-template-columns:1fr!important}
 #inspitz-app .proc,#inspitz-app .benefits,#inspitz-app .footer__grid,#inspitz-app .pillars{grid-template-columns:1fr!important}
}
CSS;
	return $css;
}

/** Main render — returns the full landing HTML. */
function inspitz_render() {
	$o = inspitz_get();
	$GLOBALS['inspitz_o'] = $o;

	// Visible stages → sequential numbering + which one is last (rail cap)
	$stage_keys = array(
		'sec_origen' => 'etapa-origen', 'sec_cultivo' => 'etapa-cultivo', 'sec_cosecha' => 'etapa-cosecha',
		'sec_nanotech' => 'etapa-nanotech', 'sec_lote' => 'etapa-lote', 'sec_identificacion' => 'etapa-identificacion',
		'sec_distribucion' => 'etapa-distribucion',
	);
	$snum = array(); $c = 0; $last_stage = '';
	foreach ( $stage_keys as $sk => $anchor ) { if ( iz_show( $sk ) ) { $c++; $snum[ $sk ] = sprintf( '%02d', $c ); $last_stage = $sk; } }
	$rail_last = function ( $sk ) use ( $last_stage ) { return $sk === $last_stage ? ' stage__rail--last' : ''; };

	$check_ico = '<svg class="ico ico--check" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12l4 4 10-10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	$hash_txt  = izraw('bc_hash') ? $o['bc_hash'] : 'Registro blockchain pendiente de integración';

	// process cards (recorrido)
	$proc_cards = array(
		array( 'sun',   'Origen y cultivo', 'La espirulina se cultiva en centros de distintas regiones del Perú, asociados a cada lote.' ),
		array( 'leaf',  'Cosecha',          'Se cosecha al cerrar el ciclo, registrando fecha, hora, procedencia y responsable.' ),
		array( 'shield','Proceso Nano-Blend','Tecnología enzimática de precisión en frío que libera y protege la ficocianina.' ),
		array( 'truck', 'Distribución',     'El lote se entrega mediante métodos seguros y rastreables hasta su destino.' ),
	);

	ob_start();
	?>
<style id="inspitz-critical"><?php echo inspitz_critical_css(); ?></style>
<div class="inspitz-root" id="inspitz-app">
<header class="topbar" id="topbar">
	<div class="topbar__inner">
		<?php echo iz_brand( false ); ?>
		<div class="topbar__meta">
			<span class="topbar__tag"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 3v6c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M9 12l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo iz( 'topbar_tag' ); ?></span>
			<span class="topbar__lote">LOTE <b data-lote><?php echo iz( 'lote_default' ); ?></b></span>
			<span class="pill"><span class="pill__dot"></span> Lote registrado</span>
		</div>
		<button class="topbar__lote-mini" data-lote-mini aria-hidden="true">LOTE <?php echo iz( 'lote_default' ); ?></button>
	</div>
</header>

<main>
	<!-- HERO -->
	<section class="hero" id="hero">
		<div class="hero__bg" aria-hidden="true"><div class="hero__sun"></div><div class="hero__grain"></div></div>
		<div class="wrap">
			<div class="hero__grid">
				<div class="hero__copy reveal">
					<p class="eyebrow eyebrow--light"><?php echo iz( 'hero_eyebrow' ); ?></p>
					<h1 class="hero__title"><?php echo iznl( 'hero_title' ); ?></h1>
					<p class="hero__sub"><?php echo iz( 'hero_sub' ); ?></p>
					<p class="hero__desc"><?php echo iz( 'hero_desc' ); ?></p>
					<div class="hero__actions">
						<a href="<?php echo izu( 'hero_cta_link' ); ?>" class="btn btn--primary" data-scroll><?php echo iz( 'hero_cta' ); ?>
							<svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M6 13l6 6 6-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
						<?php
						$socials = array( 'social_instagram' => 'ig', 'social_facebook' => 'fb', 'social_tiktok' => 'tt' );
						$has_social = false; foreach ( $socials as $sk => $x ) if ( izraw( $sk ) ) $has_social = true;
						if ( $has_social ) : ?>
						<div class="hero__social">
							<?php if ( izraw('social_instagram') ) : ?><a href="<?php echo izu('social_instagram'); ?>" aria-label="Instagram" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="5" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3.6" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor"/></svg></a><?php endif; ?>
							<?php if ( izraw('social_facebook') ) : ?><a href="<?php echo izu('social_facebook'); ?>" aria-label="Facebook" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M14 8h2V5h-2c-2 0-3 1-3 3v2H9v3h2v6h3v-6h2l1-3h-3V8.5c0-.3.2-.5.5-.5z" fill="currentColor"/></svg></a><?php endif; ?>
							<?php if ( izraw('social_tiktok') ) : ?><a href="<?php echo izu('social_tiktok'); ?>" aria-label="TikTok" target="_blank" rel="noopener"><svg viewBox="0 0 24 24"><path d="M14 4c.3 2.2 1.7 3.7 3.8 4v2.6c-1.4 0-2.7-.4-3.8-1.1v5.1a5 5 0 1 1-5-5c.3 0 .6 0 .9.1V12a2.4 2.4 0 1 0 1.7 2.3V4z" fill="currentColor"/></svg></a><?php endif; ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="hero__product reveal">
					<div class="hero__can">
						<?php if ( izraw('hero_image') ) : ?><img src="<?php echo izu('hero_image'); ?>" alt="Fico Crispy Blend 100 g" /><?php else : ?><svg viewBox="0 0 48 60" width="70%" aria-hidden="true"><rect x="6" y="8" width="36" height="46" rx="7" fill="none" stroke="rgba(255,255,255,.5)" stroke-width="2"/><path d="M24 20a8 8 0 1 0 5.6 13.6" fill="none" stroke="#7fe6d5" stroke-width="2.4" stroke-linecap="round"/><text x="24" y="50" text-anchor="middle" fill="rgba(255,255,255,.6)" font-size="5" font-family="monospace">100 g</text></svg><?php endif; ?>
					</div>
				</div>
			</div>
			<!-- tarjeta de producto flotante -->
			<div class="hero__card reveal">
				<div class="hero__can">
					<?php if ( izraw('prod_image') ) : ?><img src="<?php echo izu('prod_image'); ?>" alt="Fico Crispy Blend" /><?php elseif ( izraw('hero_image') ) : ?><img src="<?php echo izu('hero_image'); ?>" alt="Fico Crispy Blend" /><?php else : ?><svg viewBox="0 0 48 60" width="60%" aria-hidden="true"><rect x="6" y="8" width="36" height="46" rx="7" fill="none" stroke="rgba(255,255,255,.5)" stroke-width="2"/><path d="M24 22a7 7 0 1 0 5 12" fill="none" stroke="#7fe6d5" stroke-width="2.4" stroke-linecap="round"/></svg><?php endif; ?>
				</div>
				<div class="hero__card-body">
					<p class="hero__card-eyebrow"><?php echo iz( 'prod_eyebrow' ); ?></p>
					<p class="hero__card-title"><?php echo iz( 'prod_title' ); ?> · <?php echo iz( 'prod_sub' ); ?></p>
					<p class="hero__card-lote">LOTE <b data-lote><?php echo iz( 'lote_default' ); ?></b></p>
				</div>
			</div>
		</div>
	</section>

	<!-- PRODUCTO -->
	<section class="section product" id="producto">
		<div class="wrap product__grid">
			<div class="product__media reveal">
				<?php echo iz_shot( izraw( 'prod_image' ), 'shot--photo', 'Fico Crispy Blend 100 g', '<div class="photo-panel photo-panel--tall" data-photo="Fico Crispy Blend"><span class="photo-panel__hint">Foto del producto</span></div>', '--shot-ar:3 / 4' ); ?>
			</div>
			<div class="product__info reveal">
				<p class="eyebrow"><?php echo iz( 'prod_eyebrow' ); ?></p>
				<h2 class="section__title"><?php echo iz( 'prod_title' ); ?><span class="section__title-sub"><?php echo iz( 'prod_sub' ); ?></span></h2>
				<p class="lead"><?php echo iz( 'prod_desc' ); ?></p>
				<ul class="checks">
					<?php foreach ( (array) izraw( 'prod_checks' ) as $ck ) : ?>
						<li><?php echo $check_ico . esc_html( isset( $ck['texto'] ) ? $ck['texto'] : '' ); ?></li>
					<?php endforeach; ?>
				</ul>
				<div class="spec-card">
					<div class="spec-card__row"><span>Producto</span><b><?php echo iz( 'prod_title' ); ?></b></div>
					<div class="spec-card__row"><span>Presentación</span><b><?php echo iz( 'prod_sub' ); ?></b></div>
					<div class="spec-card__row"><span>Lote</span><b class="mono" data-lote><?php echo iz( 'lote_default' ); ?></b></div>
					<div class="spec-card__row"><span>Origen</span><b>Perú</b></div>
					<div class="spec-card__row"><span>Trazabilidad</span><b>Blockchain</b></div>
					<div class="spec-card__row"><span>Estado</span><b class="tag-ok">Registrado</b></div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( iz_show( 'sec_nutricion' ) ) echo inspitz_nutrition_html(); ?>

	<!-- RECORRIDO -->
	<section class="section journey" id="recorrido">
		<div class="wrap journey__head reveal">
			<p class="eyebrow">Del origen hasta tu mesa</p>
			<h2 class="section__title">Trazabilidad blockchain</h2>
			<p class="lead lead--center">Cada lote reúne información sobre el origen de la espirulina, su cultivo, cosecha, proceso tecnológico, identificación y distribución. Aquí puedes conocer los datos registrados del producto que tienes en tus manos.</p>
		</div>
		<div class="wrap">
			<div class="proc stagger">
				<?php foreach ( $proc_cards as $pc ) : ?>
					<article class="proc-card"><?php echo str_replace('fact__ico','proc-card__ico', iz_ico($pc[0])); ?><h3><?php echo esc_html($pc[1]); ?></h3><p><?php echo esc_html($pc[2]); ?></p></article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<div class="stages">
		<?php if ( iz_show('sec_origen') ) : ?>
		<!-- 01 ORIGEN -->
		<section class="stage" id="etapa-origen">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail<?php echo $rail_last('sec_origen'); ?>"><span class="stage__node" aria-hidden="true"><?php echo $snum['sec_origen']; ?></span></div>
				<div class="stage__content">
					<header class="stage__intro"><h2 class="stage__title"><?php echo iz('origen_eyebrow'); ?></h2><p class="stage__kicker">Datos de origen</p></header>
					<h3 class="stage__sub"><?php echo iz('origen_title'); ?></h3>
					<p class="stage__desc"><?php echo iz('origen_desc'); ?></p>
					<div class="mapblock">
						<?php if ( izraw('origen_mapa') ) : ?>
							<img src="<?php echo izu('origen_mapa'); ?>" alt="Mapa de zonas de cultivo en el Perú" />
						<?php else : ?>
							<div class="mapblock__fallback">Sube un mapa del Perú con las zonas (campo «Mapa» en el panel).</div>
						<?php endif; ?>
						<?php $zs = (array) izraw('zones'); if ( ! empty( $zs ) ) : $z0 = $zs[0]; ?>
						<div class="mapcard">
							<h3><?php echo esc_html($z0['nombre']); ?></h3>
							<div class="mapcard__rows">
								<div><?php echo esc_html($z0['lugar']); ?></div>
								<div>GHI <?php echo esc_html($z0['ghi']); ?> kWh/m²</div>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<div class="zones stagger">
						<?php foreach ( $zs as $i => $z ) : ?>
							<article class="zone" id="zone-<?php echo $i; ?>">
								<header class="zone__head"><h3><?php echo esc_html($z['nombre']); ?></h3><span class="zone__place"><?php echo esc_html($z['lugar']); ?></span></header>
								<dl class="zone__stats">
									<div><dt>GHI</dt><dd><?php echo esc_html($z['ghi']); ?> kWh/m²</dd></div>
									<div><dt>Producción</dt><dd><?php echo esc_html($z['produccion']); ?></dd></div>
									<div><dt>Volumen</dt><dd><?php echo esc_html($z['volumen']); ?></dd></div>
									<div><dt>Captura CO₂</dt><dd><?php echo esc_html($z['co2']); ?></dd></div>
								</dl>
							</article>
						<?php endforeach; ?>
					</div>
					<p class="note"><?php echo iz('origen_note'); ?></p>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( iz_show('sec_cultivo') ) : ?>
		<!-- 02 CULTIVO -->
		<section class="stage" id="etapa-cultivo">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail<?php echo $rail_last('sec_cultivo'); ?>"><span class="stage__node" aria-hidden="true"><?php echo $snum['sec_cultivo']; ?></span></div>
				<div class="stage__content">
					<header class="stage__intro"><h2 class="stage__title"><?php echo iz('cultivo_eyebrow'); ?></h2><p class="stage__kicker">Datos de cultivo</p></header>
					<div class="stage__cols">
						<?php echo iz_media( 'cultivo_image', 'Centro de cultivo de espirulina', 'Centro de cultivo' ); ?>
						<div class="stage__info">
							<h3 class="stage__sub"><?php echo iz('cultivo_title'); ?></h3>
							<p class="stage__desc"><?php echo iz('cultivo_desc'); ?></p>
							<div class="facts">
								<?php
								$chips = '';
								foreach ( array_filter( array_map('trim', explode("\n", izraw('cultivo_procedencia') ) ) ) as $ch ) $chips .= '<span class="chip">' . esc_html($ch) . '</span>';
								echo iz_fact( 'pin', 'Procedencia', $chips );
								echo iz_fact( 'calclock', 'Inoculación', esc_html($o['cultivo_inoculacion']) );
								echo iz_fact( 'leaf', 'Tipo de cultivo', esc_html($o['cultivo_tipo']) );
								echo iz_fact( 'person', 'Responsable', esc_html($o['cultivo_responsable']) );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( iz_show('sec_cosecha') ) : ?>
		<!-- 03 COSECHA -->
		<section class="stage" id="etapa-cosecha">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail<?php echo $rail_last('sec_cosecha'); ?>"><span class="stage__node" aria-hidden="true"><?php echo $snum['sec_cosecha']; ?></span></div>
				<div class="stage__content">
					<header class="stage__intro"><h2 class="stage__title"><?php echo iz('cosecha_eyebrow'); ?></h2><p class="stage__kicker">Datos de cosecha</p></header>
					<div class="stage__cols stage__cols--rev">
						<?php echo iz_media( 'cosecha_image', 'Cosecha del lote', 'Cosecha del lote' ); ?>
						<div class="stage__info">
							<h3 class="stage__sub"><?php echo iz('cosecha_title'); ?></h3>
							<p class="stage__desc"><?php echo iz('cosecha_desc'); ?></p>
							<div class="facts">
								<?php
								echo iz_fact( 'calclock', 'Horario', esc_html($o['cosecha_horario']) );
								echo iz_fact( 'calclock', 'Frecuencia', esc_html($o['cosecha_frecuencia']) );
								echo iz_fact( 'pin', 'Procedencia', '<b data-sede>Según el lote consultado</b>' );
								echo iz_fact( 'person', 'Responsable', esc_html($o['cosecha_responsable']) );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( iz_show('sec_nanotech') ) : ?>
		<!-- 04 NANOTECH -->
		<section class="stage" id="etapa-nanotech">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail<?php echo $rail_last('sec_nanotech'); ?>"><span class="stage__node" aria-hidden="true"><?php echo $snum['sec_nanotech']; ?></span></div>
				<div class="stage__content">
					<header class="stage__intro"><h2 class="stage__title"><?php echo iz('nano_eyebrow'); ?></h2><p class="stage__kicker">Proceso tecnológico</p></header>
					<div class="feature">
						<p class="eyebrow eyebrow--light"><?php echo iz('nano_title'); ?></p>
						<p class="feature__subtitle"><?php echo iz('nano_subtitle'); ?></p>
						<div class="feature__grid">
							<div class="feature__text"><p><?php echo iz('nano_p1'); ?></p><p><?php echo iz('nano_p2'); ?></p></div>
							<div class="flow" aria-label="Diagrama del proceso">
								<?php $flow = (array) izraw('nano_flow'); $n = count($flow); foreach ( $flow as $i => $st ) {
									$acc = ( stripos( $st['texto'], 'ficocianina' ) !== false ) ? ' flow__step--accent' : '';
									printf('<div class="flow__step%s"><span class="flow__i">%02d</span>%s</div>', $acc, $i+1, esc_html($st['texto']));
									if ( $i < $n - 1 ) echo '<div class="flow__arrow" aria-hidden="true"></div>';
								} ?>
							</div>
						</div>
						<div class="benefits stagger">
							<?php foreach ( (array) izraw('nano_benefits') as $bn ) printf('<article class="benefit">%s<h3>%s</h3></article>', str_replace('fact__ico','benefit__ico', iz_ico('shield')), esc_html($bn['texto'])); ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( iz_show('sec_lote') ) : ?>
		<!-- 05 LOTE -->
		<section class="stage" id="etapa-lote">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail<?php echo $rail_last('sec_lote'); ?>"><span class="stage__node" aria-hidden="true"><?php echo $snum['sec_lote']; ?></span></div>
				<div class="stage__content">
					<header class="stage__intro"><h2 class="stage__title"><?php echo iz('lote_title'); ?></h2><p class="stage__kicker"><?php echo iz('lote_eyebrow'); ?></p></header>
					<div class="stage__cols">
						<?php echo iz_media( 'lote_image', 'Fico Crispy Blend · lote', 'Producto / lote' ); ?>
						<div class="stage__info">
							<p class="stage__desc"><?php echo iz('lote_desc'); ?></p>
							<div class="facts">
								<?php
								echo iz_fact( 'tag', 'Código del lote', '<b class="mono" data-lote>' . iz('lote_default') . '</b>' );
								echo iz_fact( 'box', 'Producto', iz('prod_title') . ' · ' . iz('prod_sub') );
								echo iz_fact( 'pin', 'Procedencia', '<b data-sede>Según el lote</b>' );
								echo iz_fact( 'calclock', 'Cosecha', '<span data-fecha-cosecha>Registrada en el lote</span>' );
								echo iz_fact( 'leaf', 'Proceso', 'Phyco-Active Nano-Blend' );
								echo iz_fact( 'person', 'Responsable', 'Asociación / familiar agricultor' );
								?>
							</div>
						</div>
					</div>
					<p class="quote"><?php echo iz('lote_quote'); ?></p>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( iz_show('sec_identificacion') ) : ?>
		<!-- 06 IDENTIFICACIÓN -->
		<section class="stage" id="etapa-identificacion">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail<?php echo $rail_last('sec_identificacion'); ?>"><span class="stage__node" aria-hidden="true"><?php echo $snum['sec_identificacion']; ?></span></div>
				<div class="stage__content">
					<header class="stage__intro"><h2 class="stage__title"><?php echo iz('ident_title'); ?></h2><p class="stage__kicker"><?php echo iz('ident_eyebrow'); ?></p></header>
					<div class="stage__cols stage__cols--rev">
						<?php echo iz_media( 'ident_image', 'Envase Fico Crispy Blend', 'Envase sellado', true ); ?>
						<div class="stage__info">
							<p class="stage__desc"><?php echo iz('ident_desc'); ?></p>
							<div class="facts">
								<?php
								echo iz_fact( 'box', 'Producto', iz('prod_title') . ' · ' . iz('prod_sub') );
								echo iz_fact( 'tag', 'Lote', '<b class="mono" data-lote>' . iz('lote_default') . '</b>' );
								echo iz_fact( 'globe', 'País de origen', 'Perú' );
								echo iz_fact( 'shield', 'Registro sanitario', '<b class="mono" data-registro>' . iz('registro_sanitario') . '</b>' );
								?>
							</div>
							<div class="pack__qr"><div class="qr qr--mini" data-qr-mini aria-label="Código QR del lote"></div><span class="mono" style="font-size:.8rem;color:var(--muted)">Escanea para volver a este lote</span></div>
							<span class="badge-vegan" data-vegan-badge hidden><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 14c6 1 10-3 16-10-1 9-6 15-13 15-2 0-3-1-3-3 0-1 0-1.5.5-2z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg> Vegano</span>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( iz_show('sec_distribucion') ) : ?>
		<!-- 07 DISTRIBUCIÓN -->
		<section class="stage" id="etapa-distribucion">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail<?php echo $rail_last('sec_distribucion'); ?>"><span class="stage__node" aria-hidden="true"><?php echo $snum['sec_distribucion']; ?></span></div>
				<div class="stage__content">
					<header class="stage__intro"><h2 class="stage__title"><?php echo iz('dist_title'); ?></h2><p class="stage__kicker"><?php echo iz('dist_eyebrow'); ?></p></header>
					<div class="stage__cols stage__cols--rev">
						<?php echo iz_media( 'dist_image', 'Distribución Fico Crispy Blend', 'Distribución' ); ?>
						<div class="stage__info">
							<p class="stage__desc"><?php echo iz('dist_desc'); ?></p>
							<div class="facts">
								<?php foreach ( (array) izraw('dist_cards') as $dc ) {
									$v = esc_html($dc['metodo']) . '<br>' . esc_html($dc['descripcion']) . '<br><b style="color:var(--brand-700)">' . esc_html($dc['eta']) . '</b>';
									echo iz_fact( 'truck', $dc['titulo'], $v );
								} ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>
	</div><!-- /stages -->

	<?php if ( iz_show('sec_blockchain') ) : ?>
	<!-- BLOCKCHAIN -->
	<section class="section blockchain" id="blockchain">
		<div class="wrap blockchain__grid reveal">
			<div class="blockchain__copy">
				<p class="eyebrow eyebrow--light"><?php echo iz('bc_eyebrow'); ?></p>
				<h2 class="section__title section__title--light"><?php echo iz('bc_title'); ?></h2>
				<p class="lead lead--light"><?php echo iz('bc_text'); ?></p>
				<div class="chain-card">
					<div class="chain-card__row"><span>Producto</span><b><?php echo iz('prod_title'); ?></b></div>
					<div class="chain-card__row"><span>Presentación</span><b><?php echo iz('prod_sub'); ?></b></div>
					<div class="chain-card__row"><span>Lote</span><b class="mono" data-lote><?php echo iz('lote_default'); ?></b></div>
					<div class="chain-card__row"><span>Origen</span><b>Perú</b></div>
					<div class="chain-card__row"><span>Estado</span><b class="tag-ok">Registrado</b></div>
				</div>
				<div class="hashbar"><span class="hashbar__label">Código de transacción blockchain (Hash):</span><code class="hashbar__val mono" data-hash><?php echo esc_html( $hash_txt ); ?></code></div>
				<a href="<?php echo izu('bc_btn_link') ?: '#'; ?>" class="btn btn--ghost" data-ver-registro><?php echo iz('bc_btn'); ?></a>
			</div>
			<div class="blockchain__qr reveal">
				<div class="qr qr--lg" data-qr aria-label="Código QR del lote"></div>
				<p class="blockchain__qrnote"><?php echo iz('bc_qr_note'); ?></p>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( iz_show('sec_certificaciones') ) : ?>
	<!-- CERTIFICACIONES -->
	<section class="section certs" id="certificaciones">
		<div class="wrap certs__grid reveal">
			<div class="certs__intro">
				<p class="eyebrow"><?php echo iz('cert_eyebrow'); ?></p>
				<h2 class="section__title"><?php echo iz('cert_title'); ?></h2>
				<p class="lead"><?php echo iz('cert_desc'); ?></p>
				<ul class="certs__checks">
					<?php foreach ( (array) izraw('prod_checks') as $ck ) : ?>
						<li><?php echo $check_ico . esc_html( isset($ck['texto']) ? $ck['texto'] : '' ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="certlist stagger">
				<article class="cert">
					<div class="cert__main"><h3>Registro sanitario</h3><p class="mono"><?php echo iz('registro_sanitario'); ?></p></div>
					<div class="cert__side">
						<span class="cert__status cert__status--pending"><?php echo iz('cert1_estado'); ?></span>
						<a href="<?php echo izu('cert1_doc') ?: '#'; ?>" class="btn btn--line" data-doc="registro-sanitario">Ver</a>
					</div>
				</article>
				<article class="cert">
					<div class="cert__main"><h3>Producto vegano</h3><p>Certificación o declaración</p></div>
					<div class="cert__side">
						<span class="cert__status cert__status--pending"><?php echo iz('cert2_estado'); ?></span>
						<a href="<?php echo izu('cert2_doc') ?: '#'; ?>" class="btn btn--line" data-doc="vegano">Ver</a>
					</div>
				</article>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( iz_show('sec_proposito') ) : ?>
	<!-- PROPÓSITO -->
	<section class="section purpose" id="proposito">
		<div class="wrap purpose__inner reveal">
			<p class="eyebrow eyebrow--light"><?php echo iz('prop_eyebrow'); ?></p>
			<h2 class="section__title section__title--light"><?php echo iz('prop_title'); ?></h2>
			<p class="purpose__quote"><?php echo iz('prop_quote'); ?></p>
			<p class="lead lead--light lead--center"><?php echo iz('prop_text'); ?></p>
			<div class="pillars stagger">
				<?php $pillar_ico = array('sun','shield','tag'); foreach ( (array) izraw('pillars') as $i => $pl ) printf('<article class="pillar">%s<h3>%s</h3><p>%s</p></article>', str_replace('fact__ico','pillar__ico', iz_ico($pillar_ico[$i % 3])), esc_html($pl['titulo']), esc_html($pl['texto'])); ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( iz_show('sec_cierre') ) : ?>
	<!-- CIERRE -->
	<section class="section closing" id="cierre">
		<div class="wrap closing__inner reveal">
			<h2 class="section__title"><?php echo iz('cierre_title'); ?></h2>
			<p class="lead lead--center"><?php echo iz('cierre_desc'); ?></p>
			<div class="closing__actions">
				<a href="<?php echo izu('cierre_cta1_link'); ?>" class="btn btn--primary" target="_blank" rel="noopener"><?php echo iz('cierre_cta1'); ?></a>
				<a href="<?php echo izu('cierre_cta2_link'); ?>" class="btn btn--soft" target="_blank" rel="noopener"><?php echo iz('cierre_cta2'); ?></a>
			</div>
			<a href="#hero" class="closing__back" data-scroll>Volver al inicio del lote</a>
		</div>
	</section>
	<?php endif; ?>
</main>

<footer class="footer">
	<div class="wrap footer__grid">
		<div class="footer__brand">
			<?php echo iz_brand( false ); ?>
			<p class="footer__legal"><?php echo iz('footer_company'); ?> · <?php echo iz('footer_city'); ?></p>
			<p class="footer__legal mono">Registro sanitario: <?php echo iz('footer_registro'); ?></p>
		</div>
		<nav class="footer__col" aria-label="Producto"><h4>Producto</h4>
			<a href="#producto"><?php echo iz('prod_title'); ?></a><a href="#recorrido">Recorrido del lote</a><a href="#blockchain">Registro blockchain</a><a href="#certificaciones">Certificaciones</a>
		</nav>
		<nav class="footer__col" aria-label="Inspirall"><h4>Inspirall</h4>
			<a href="<?php echo izu('link_sitio'); ?>" target="_blank" rel="noopener">Sitio oficial</a>
			<a href="<?php echo izu('link_ceroanemia'); ?>" target="_blank" rel="noopener">Cero anemia</a>
			<a href="mailto:<?php echo iza('contacto_email'); ?>">Contacto</a>
		</nav>
		<nav class="footer__col" aria-label="Legal y redes"><h4>Legal</h4>
			<a href="<?php echo izu('link_privacidad'); ?>">Política de privacidad</a>
			<a href="<?php echo izu('link_terminos'); ?>">Términos y condiciones</a>
			<a href="<?php echo izu('link_reclamaciones'); ?>">Libro de reclamaciones</a>
			<div class="footer__social">
				<?php if ( izraw('social_instagram') ) : ?><a href="<?php echo izu('social_instagram'); ?>" aria-label="Instagram" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="5" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3.5" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="17" cy="7" r="1" fill="currentColor"/></svg></a><?php endif; ?>
				<?php if ( izraw('social_facebook') ) : ?><a href="<?php echo izu('social_facebook'); ?>" aria-label="Facebook" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h2V5h-2c-2 0-3 1-3 3v2H9v3h2v6h3v-6h2l1-3h-3V8.5c0-.3.2-.5.5-.5z" fill="currentColor"/></svg></a><?php endif; ?>
				<?php if ( izraw('social_tiktok') ) : ?><a href="<?php echo izu('social_tiktok'); ?>" aria-label="TikTok" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4c.3 2.2 1.7 3.7 3.8 4v2.6c-1.4 0-2.7-.4-3.8-1.1v5.1a5 5 0 1 1-5-5c.3 0 .6 0 .9.1V12a2.4 2.4 0 1 0 1.7 2.3V4z" fill="currentColor"/></svg></a><?php endif; ?>
			</div>
		</nav>
	</div>
	<div class="footer__base wrap">
		<span>© <?php echo iz('footer_company'); ?>. Todos los derechos reservados.</span>
		<span class="mono">LOTE <b data-lote><?php echo iz('lote_default'); ?></b> · Registrado</span>
	</div>
</footer>
</div>
	<?php
	return ob_get_clean();
}

/** Static nutrition block (real label data). */
function inspitz_nutrition_html() {
	ob_start(); ?>
	<section class="section nutrition" id="nutricion">
		<div class="wrap nutrition__head reveal">
			<p class="eyebrow">Información del producto</p>
			<h2 class="section__title">Información nutricional</h2>
			<p class="lead lead--center">Valores por porción de 100 g, tal como figuran en el envase de Fico Crispy Blend.</p>
		</div>
		<div class="wrap nutrition__grid reveal">
			<div class="nfacts">
				<p class="nfacts__title">Información nutricional</p>
				<div class="nfacts__head"><span>Tamaño de la porción</span><b>100 g</b></div>
				<div class="nfacts__cal"><span>Calorías</span><b>350</b></div>
				<p class="nfacts__vd">% Valor Diario*</p>
				<ul class="nfacts__list">
					<li><span>Grasa total 0 g</span><b>0%</b></li>
					<li><span>Grasa saturada 0 g</span><b>0%</b></li>
					<li><span>Grasa trans 0 g</span><b></b></li>
					<li><span>Sodio 580 mg</span><b>25%</b></li>
					<li><span>Carbohidratos totales 28 g</span><b>10%</b></li>
					<li><span>Azúcares totales 8 g</span><b></b></li>
					<li class="is-strong"><span>Proteína 60 g</span><b></b></li>
				</ul>
			</div>
			<div class="nutrition__side">
				<div class="nmin"><h3>Minerales y vitaminas</h3><ul>
					<li><span>Calcio 265 mg</span><b>20%</b></li><li><span>Fósforo 692 mg</span><b>55%</b></li>
					<li><span>Hierro 36.9 mg</span><b>205%</b></li><li><span>Magnesio 263 mg</span><b>63%</b></li>
					<li><span>Manganeso 9.17 mg</span><b>51%</b></li><li><span>Potasio 1452 mg</span><b>31%</b></li>
					<li><span>Zinc 3.18 mg</span><b>31%</b></li><li><span>Vitamina C 64.88 mg</span><b>72%</b></li>
				</ul></div>
				<div class="ninfo">
					<div><span class="ninfo__k">Ingredientes</span><p>Espirulina, arándano, moringa, granada. 100% peruana.</p></div>
					<div><span class="ninfo__k">Conservación</span><p>1 año y 6 meses. Mantener en un lugar fresco y seco. Una vez abierto, conservar el envase bien cerrado.</p></div>
				</div>
			</div>
		</div>
		<p class="wrap note reveal">*Los porcentajes de Valores Diarios se basan en una dieta de 2000 calorías. Fabricado por Ultragreen Sustainability S.A.C.</p>
	</section>
	<?php return ob_get_clean();
}
