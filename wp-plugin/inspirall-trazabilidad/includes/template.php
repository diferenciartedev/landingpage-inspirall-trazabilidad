<?php
/** Inspirall Trazabilidad — front-end renderer (built from saved content). */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ---- tiny helpers bound to the current content array ---- */
function iz( $k )  { $o = $GLOBALS['inspitz_o']; return esc_html( isset( $o[$k] ) ? $o[$k] : '' ); }
function iza( $k ) { $o = $GLOBALS['inspitz_o']; return esc_attr( isset( $o[$k] ) ? $o[$k] : '' ); }
function izu( $k ) { $o = $GLOBALS['inspitz_o']; return esc_url( isset( $o[$k] ) ? $o[$k] : '' ); }
function iznl( $k ){ $o = $GLOBALS['inspitz_o']; return nl2br( esc_html( isset( $o[$k] ) ? $o[$k] : '' ) ); }
function izraw( $k ){ $o = $GLOBALS['inspitz_o']; return isset( $o[$k] ) ? $o[$k] : ''; }

/** figure.shot with real photo (if set) + fallback markup. $style = inline CSS vars. */
function iz_shot( $url, $wrapClass, $alt, $fallback, $style = '' ) {
	$img = '';
	if ( $url ) {
		$img = '<img class="shot__img" src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" '
			. 'onload="this.closest(\'.shot\').classList.add(\'shot--hasimg\')" onerror="this.remove()" />';
	}
	$st = $style ? ' style="' . esc_attr( $style ) . '"' : '';
	return '<figure class="shot ' . esc_attr( $wrapClass ) . '"' . $st . '>' . $img . $fallback . '</figure>';
}

/** CAN (pomo) mockup markup. $variant: 'hero' | 'sm'. */
function iz_can( $variant = 'hero' ) {
	$o = $GLOBALS['inspitz_o'];
	$cls  = $variant === 'sm' ? 'pouch pouch--sm' : 'pouch pouch--hero';
	$mark = '<svg class="pouch__mark" viewBox="0 0 48 48" aria-hidden="true">'
		. '<path d="M24 5a19 19 0 1 0 13.4 32.4" fill="none" stroke="var(--turquoise)" stroke-width="3" stroke-linecap="round"/>'
		. '<path d="M24 14a10 10 0 1 0 7 17" fill="none" stroke="var(--phyco-lite)" stroke-width="3" stroke-linecap="round"/>'
		. '<circle cx="24" cy="24" r="2.4" fill="var(--turquoise)"/></svg>';
	$brand = esc_html( $o['brand_word'] );
	if ( $variant === 'sm' ) {
		$inner = '<span class="pouch__brand">' . $brand . '</span>' . $mark
			. '<span class="pouch__name">Fico Crispy</span><span class="pouch__band">Blend</span>'
			. '<span class="pouch__net">100 g</span>';
	} else {
		$inner = '<span class="pouch__brand">' . $brand . '</span>'
			. '<span class="pouch__by">by Ultragreen</span>'
			. '<span class="pouch__kicker">100% espirulina artesanal peruana</span>' . $mark
			. '<span class="pouch__name">Fico Crispy</span><span class="pouch__band">Blend</span>'
			. '<span class="pouch__net">100 g = 1 mes de felicidad</span>';
	}
	return '<div class="' . $cls . '"><span class="pouch__seal" aria-hidden="true"></span>'
		. '<div class="pouch__body">' . $inner . '</div></div>';
}

/** brand logo (image if set, else styled wordmark). */
function iz_brand( $light = false ) {
	$o = $GLOBALS['inspitz_o'];
	$logo = $light ? $o['logo_footer'] : $o['logo_header'];
	$cls  = 'brand' . ( $light ? ' brand--light' : '' );
	$href = $light ? '#hero' : '#hero';
	$img  = '';
	if ( $logo ) {
		$img = '<img class="brand__img" src="' . esc_url( $logo ) . '" alt="' . esc_attr( $o['brand_word'] ) . '" '
			. 'onload="this.closest(\'.brand\').classList.add(\'brand--haslogo\')" onerror="this.remove()" />';
	}
	$svg = '<svg class="brand__mark" viewBox="0 0 32 32" aria-hidden="true">'
		. '<path d="M16 4a12 12 0 1 0 8.5 20.5" fill="none" stroke="var(--turquoise)" stroke-width="2.6" stroke-linecap="round"/>'
		. '<path d="M16 10a6 6 0 1 0 4.2 10.2" fill="none" stroke="var(--phyco)" stroke-width="2.6" stroke-linecap="round"/>'
		. '<circle cx="16" cy="16" r="1.8" fill="var(--turquoise)"/></svg>';
	return '<a class="' . $cls . '" href="' . esc_url( $href ) . '" aria-label="' . esc_attr( $o['brand_word'] ) . '">'
		. $img . $svg . '<span class="brand__word">' . esc_html( $o['brand_word'] ) . '</span></a>';
}

/** Main render — returns the full landing HTML. */
function inspitz_render() {
	$o = inspitz_get();
	$GLOBALS['inspitz_o'] = $o;

	// Image sizing (from the "Imágenes" tab)
	$hero_style  = '--shot-w:' . max( 120, intval( $o['img_hero_w'] ) ) . 'px';
	$prod_style  = '--shot-w:' . max( 120, intval( $o['img_prod_w'] ) ) . 'px;--shot-ar:' . ( $o['img_prod_ar'] ? $o['img_prod_ar'] : '3 / 4' );
	$stage_style = '--shot-ar:' . ( $o['img_stage_ar'] ? $o['img_stage_ar'] : '4 / 3' );
	$env_style   = '--shot-w:' . max( 100, intval( $o['img_envase_w'] ) ) . 'px';

	$check_ico = '<svg class="ico ico--check" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12l4 4 10-10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	$zone_pos = array(
		array( '52%', '88%' ), array( '44%', '80%' ), array( '24%', '14%' ), array( '33%', '54%' ), array( '70%', '30%' ),
		array( '60%', '66%' ), array( '30%', '38%' ), array( '48%', '22%' ),
	);
	$benefit_icos = array(
		'<svg class="benefit__ico" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M12 4v16M4 12h16" stroke="currentColor" stroke-width="1.2" opacity=".5"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>',
		'<svg class="benefit__ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3s6 5 6 10a6 6 0 1 1-12 0c0-5 6-10 6-10z" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="13" r="2.2" fill="currentColor"/></svg>',
		'<svg class="benefit__ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 3v6c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M12 8v4m0 3h.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'<svg class="benefit__ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 20V10m6 10V4m6 16v-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
	);
	$pillar_icos = array(
		'<svg class="pillar__ico" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18M3.5 9h17M3.5 15h17" fill="none" stroke="currentColor" stroke-width="1.1" opacity=".7"/></svg>',
		'<svg class="pillar__ico" viewBox="0 0 24 24" aria-hidden="true"><circle cx="7" cy="7" r="2.4" fill="none" stroke="currentColor" stroke-width="1.4"/><circle cx="17" cy="17" r="2.4" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M9 8.5c3 1 3 5 6 7M15 6.5c-1 1-1 2 0 3M8 15c1 1 2 1 3 0" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>',
		'<svg class="pillar__ico" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="1.4" fill="none" stroke="currentColor" stroke-width="1.4"/><rect x="13" y="13" width="7" height="7" rx="1.4" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M11 7h3a3 3 0 0 1 3 3v3" fill="none" stroke="currentColor" stroke-width="1.4"/></svg>',
	);
	$dist_icos = array(
		'<svg class="dist-card__ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 16V7h10v9M14 10h4l2 3v3h-6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="7" cy="17" r="1.8" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="17" cy="17" r="1.8" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>',
		'<svg class="dist-card__ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><circle cx="7" cy="18" r="1.7" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="18" cy="18" r="1.7" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>',
	);

	ob_start();
	?>
<div class="inspitz-root">
<header class="topbar" id="topbar">
	<div class="topbar__inner">
		<?php echo iz_brand( false ); ?>
		<div class="topbar__meta">
			<span class="topbar__tag"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 3v6c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M9 12l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg> <?php echo iz( 'topbar_tag' ); ?></span>
			<span class="topbar__lote">LOTE <b data-lote><?php echo iz( 'lote_default' ); ?></b></span>
			<span class="pill pill--live"><span class="pill__dot"></span> Lote registrado</span>
		</div>
		<button class="topbar__lote-mini" data-lote-mini aria-hidden="true">LOTE <?php echo iz( 'lote_default' ); ?></button>
	</div>
</header>

<main>
	<!-- HERO -->
	<section class="hero" id="hero">
		<div class="hero__bg" aria-hidden="true"><div class="hero__sun"></div><div class="hero__grain"></div></div>
		<div class="wrap hero__grid">
			<div class="hero__copy reveal">
				<p class="eyebrow eyebrow--light"><?php echo iz( 'hero_eyebrow' ); ?></p>
				<h1 class="hero__title"><?php echo iznl( 'hero_title' ); ?></h1>
				<p class="hero__sub"><?php echo iz( 'hero_sub' ); ?></p>
				<p class="hero__desc"><?php echo iz( 'hero_desc' ); ?></p>
				<a href="<?php echo izu( 'hero_cta_link' ); ?>" class="btn btn--primary" data-scroll><?php echo iz( 'hero_cta' ); ?>
					<svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M6 13l6 6 6-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
			</div>
			<div class="hero__product reveal">
				<?php echo iz_shot( izraw( 'hero_image' ), 'shot--hero', 'Lata de Fico Crispy Blend 100 g', iz_can( 'hero' ), $hero_style ); ?>
				<p class="hero__note"><?php echo iz( 'hero_note' ); ?></p>
			</div>
		</div>
		<div class="hero__scrollcue" aria-hidden="true"><span></span></div>
	</section>

	<!-- PRODUCTO -->
	<section class="section product" id="producto">
		<div class="wrap product__grid">
			<div class="product__media reveal">
				<?php echo iz_shot( izraw( 'prod_image' ), 'shot--photo', 'Fico Crispy Blend 100 g', '<div class="photo-panel photo-panel--tall" data-photo="Fico Crispy Blend"><div class="photo-panel__ficonode" aria-hidden="true"></div></div>', $prod_style ); ?>
			</div>
			<div class="product__info reveal">
				<p class="eyebrow"><?php echo iz( 'prod_eyebrow' ); ?></p>
				<h2 class="section__title"><?php echo iz( 'prod_title' ); ?><span class="section__title-sub"><?php echo iz( 'prod_sub' ); ?></span></h2>
				<p class="lead"><?php echo iz( 'prod_desc' ); ?></p>
				<ul class="checks">
					<?php foreach ( (array) izraw( 'prod_checks' ) as $c ) : ?>
						<li><?php echo $check_ico . esc_html( isset( $c['texto'] ) ? $c['texto'] : '' ); ?></li>
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

	<?php echo inspitz_nutrition_html(); ?>

	<!-- RECORRIDO -->
	<section class="section journey" id="recorrido">
		<div class="wrap journey__head reveal">
			<p class="eyebrow">Del origen hasta tu mesa</p>
			<h2 class="section__title">Conoce la historia de este lote</h2>
			<p class="lead lead--center">Cada lote reúne información sobre el origen de la espirulina, su cultivo, cosecha, proceso tecnológico, identificación y distribución. Aquí puedes conocer los datos registrados del producto que tienes en tus manos.</p>
		</div>
		<div class="wrap">
			<ol class="stepper reveal">
				<?php
				$steps = array( array('#etapa-origen','Origen'), array('#etapa-cultivo','Cultivo'), array('#etapa-cosecha','Cosecha'), array('#etapa-nanotech','Acopio y Nanotech'), array('#etapa-lote','Identificación del lote'), array('#etapa-identificacion','Sellado y rotulado'), array('#etapa-distribucion','Distribución'), array('#blockchain','Blockchain') );
				foreach ( $steps as $i => $s ) printf('<li class="stepper__item"><a href="%s"><span class="stepper__num">%02d</span><span class="stepper__label">%s</span></a></li>', esc_attr($s[0]), $i+1, esc_html($s[1]));
				?>
			</ol>
		</div>
	</section>

	<div class="stages">
		<!-- 01 ORIGEN -->
		<section class="stage" id="etapa-origen">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail"><span class="stage__node" aria-hidden="true">01</span></div>
				<div class="stage__content">
					<header class="stage__intro"><p class="eyebrow"><?php echo iz('origen_eyebrow'); ?></p><h2 class="section__title"><?php echo iz('origen_title'); ?></h2><p class="lead"><?php echo iz('origen_desc'); ?></p></header>
					<div class="origin__grid">
						<div class="map" aria-label="Mapa de zonas de cultivo en el Perú">
							<div class="map__axis"><span>Norte</span><span>Centro</span><span>Sur</span></div>
							<div class="map__field">
								<?php foreach ( (array) izraw('zones') as $i => $z ) { $p = isset($zone_pos[$i]) ? $zone_pos[$i] : array('50%','50%'); printf('<button class="marker" style="left:%s; top:%s" data-zone="%d" aria-label="%s"><span class="marker__dot"></span><span class="marker__tip">%s</span></button>', esc_attr($p[0]), esc_attr($p[1]), $i, esc_attr($z['nombre']), esc_html($z['nombre'])); } ?>
								<span class="map__coast" aria-hidden="true"></span>
								<span class="map__caption">Perú · de norte a sur</span>
							</div>
						</div>
						<div class="zones stagger">
							<?php foreach ( (array) izraw('zones') as $i => $z ) : ?>
								<article class="zone" id="zone-<?php echo $i; ?>">
									<header class="zone__head"><h3><?php echo esc_html($z['nombre']); ?></h3><span class="zone__place"><?php echo esc_html($z['lugar']); ?></span></header>
									<dl class="zone__stats">
										<div><dt>GHI</dt><dd><?php echo esc_html($z['ghi']); ?> kWh/m²</dd></div>
										<div><dt>Producción mensual</dt><dd><?php echo esc_html($z['produccion']); ?></dd></div>
										<div><dt>Volumen</dt><dd><?php echo esc_html($z['volumen']); ?></dd></div>
										<div><dt>Captura CO₂ estimada</dt><dd><?php echo esc_html($z['co2']); ?></dd></div>
									</dl>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
					<p class="note"><?php echo iz('origen_note'); ?></p>
				</div>
			</div>
		</section>

		<!-- 02 CULTIVO -->
		<section class="stage" id="etapa-cultivo">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail"><span class="stage__node" aria-hidden="true">02</span></div>
				<div class="stage__content">
					<header class="stage__intro"><p class="eyebrow"><?php echo iz('cultivo_eyebrow'); ?></p><h2 class="section__title"><?php echo iz('cultivo_title'); ?></h2><p class="lead"><?php echo iz('cultivo_desc'); ?></p></header>
					<div class="stage__split">
						<?php echo iz_shot( izraw('cultivo_image'), 'shot--photo', 'Centro de cultivo de espirulina', '<div class="photo-panel" data-photo="Centro de cultivo · nave de espirulina"><span class="photo-panel__hint">Foto real: centro de cultivo / supervisión</span></div>', $stage_style ); ?>
						<div class="datablock">
							<div class="datablock__row"><span class="datablock__key">Procedencia</span><span class="datablock__val"><span class="chips">
								<?php foreach ( array_filter( array_map('trim', explode("\n", izraw('cultivo_procedencia') ) ) ) as $ch ) echo '<span class="chip">' . esc_html($ch) . '</span>'; ?>
							</span></span></div>
							<div class="datablock__row"><span class="datablock__key">Inoculación</span><span class="datablock__val"><?php echo iz('cultivo_inoculacion'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Tipo de cultivo</span><span class="datablock__val"><?php echo iz('cultivo_tipo'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Responsable</span><span class="datablock__val"><?php echo iz('cultivo_responsable'); ?></span></div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- 03 COSECHA -->
		<section class="stage" id="etapa-cosecha">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail"><span class="stage__node" aria-hidden="true">03</span></div>
				<div class="stage__content">
					<header class="stage__intro"><p class="eyebrow"><?php echo iz('cosecha_eyebrow'); ?></p><h2 class="section__title"><?php echo iz('cosecha_title'); ?></h2><p class="lead"><?php echo iz('cosecha_desc'); ?></p></header>
					<div class="stage__split stage__split--rev">
						<?php echo iz_shot( izraw('cosecha_image'), 'shot--photo', 'Cosecha del lote', '<div class="photo-panel" data-photo="Cosecha del lote · registro de fecha y hora"><span class="photo-panel__hint">Foto real: cosecha del lote</span></div>', $stage_style ); ?>
						<div class="datablock">
							<div class="datablock__row"><span class="datablock__key">Horario</span><span class="datablock__val"><?php echo iz('cosecha_horario'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Frecuencia</span><span class="datablock__val"><?php echo iz('cosecha_frecuencia'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Procedencia</span><span class="datablock__val"><b data-sede>Según el lote consultado</b></span></div>
							<div class="datablock__row"><span class="datablock__key">Responsable</span><span class="datablock__val"><?php echo iz('cosecha_responsable'); ?></span></div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- 04 NANOTECH -->
		<section class="stage stage--feature" id="etapa-nanotech">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail"><span class="stage__node" aria-hidden="true">04</span></div>
				<div class="stage__content">
					<div class="feature">
						<header class="stage__intro stage__intro--center"><p class="eyebrow eyebrow--light"><?php echo iz('nano_eyebrow'); ?></p><h2 class="section__title section__title--light"><?php echo iz('nano_title'); ?></h2><p class="feature__subtitle"><?php echo iz('nano_subtitle'); ?></p></header>
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
							<?php foreach ( (array) izraw('nano_benefits') as $i => $bn ) printf('<article class="benefit">%s<h3>%s</h3></article>', $benefit_icos[ $i % 4 ], esc_html($bn['texto'])); ?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- 05 LOTE -->
		<section class="stage" id="etapa-lote">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail"><span class="stage__node" aria-hidden="true">05</span></div>
				<div class="stage__content">
					<header class="stage__intro"><p class="eyebrow"><?php echo iz('lote_eyebrow'); ?></p><h2 class="section__title"><?php echo iz('lote_title'); ?></h2><p class="lead"><?php echo iz('lote_desc'); ?></p></header>
					<div class="lote-grid stagger">
						<div class="lote-cell"><span>Código del lote</span><b class="mono" data-lote><?php echo iz('lote_default'); ?></b></div>
						<div class="lote-cell"><span>Producto</span><b><?php echo iz('prod_title'); ?></b></div>
						<div class="lote-cell"><span>Presentación</span><b><?php echo iz('prod_sub'); ?></b></div>
						<div class="lote-cell"><span>Procedencia</span><b data-sede>Según el lote</b></div>
						<div class="lote-cell"><span>Fecha y hora de inoculación</span><b data-fecha-inoculacion>Registrada en el lote</b></div>
						<div class="lote-cell"><span>Fecha y hora de cosecha</span><b data-fecha-cosecha>Registrada en el lote</b></div>
						<div class="lote-cell"><span>Responsable</span><b>Asociación / familiar agricultor</b></div>
						<div class="lote-cell"><span>Tipo de cultivo</span><b>Agua tratada, 100% reciclada</b></div>
						<div class="lote-cell"><span>Proceso</span><b>Phyco-Active Nano-Blend</b></div>
						<div class="lote-cell"><span>Datos de distribución</span><b>Asociados al lote</b></div>
						<div class="lote-cell lote-cell--wide"><span>Código de transacción blockchain</span><b class="mono" data-hash>Registro blockchain pendiente de integración</b></div>
					</div>
					<p class="quote"><?php echo iz('lote_quote'); ?></p>
				</div>
			</div>
		</section>

		<!-- 06 IDENTIFICACIÓN -->
		<section class="stage" id="etapa-identificacion">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail"><span class="stage__node" aria-hidden="true">06</span></div>
				<div class="stage__content">
					<header class="stage__intro"><p class="eyebrow"><?php echo iz('ident_eyebrow'); ?></p><h2 class="section__title"><?php echo iz('ident_title'); ?></h2><p class="lead"><?php echo iz('ident_desc'); ?></p></header>
					<div class="stage__split stage__split--rev">
						<div class="stage__media-col">
							<?php echo iz_shot( izraw('ident_image'), 'shot--pack', 'Envase Fico Crispy Blend', iz_can('sm'), $env_style ); ?>
							<div class="pack__qr" style="margin-top:1rem"><div class="qr" data-qr-mini aria-label="Código QR del lote"></div><span class="mono pack__lote">LOTE <b data-lote><?php echo iz('lote_default'); ?></b></span></div>
							<span class="badge-vegan" data-vegan-badge hidden><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 14c6 1 10-3 16-10-1 9-6 15-13 15-2 0-3-1-3-3 0-1 0-1.5.5-2z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg> Vegano</span>
						</div>
						<div class="datablock">
							<div class="datablock__row"><span class="datablock__key">Producto</span><span class="datablock__val"><?php echo iz('prod_title'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Presentación</span><span class="datablock__val"><?php echo iz('prod_sub'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Marca</span><span class="datablock__val"><?php echo iz('brand_word'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Lote</span><span class="datablock__val mono" data-lote><?php echo iz('lote_default'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">País de origen</span><span class="datablock__val">Perú</span></div>
							<div class="datablock__row"><span class="datablock__key">Registro sanitario</span><span class="datablock__val mono" data-registro><?php echo iz('registro_sanitario'); ?></span></div>
							<div class="datablock__row"><span class="datablock__key">Código QR</span><span class="datablock__val mono" data-lote-url>URL del lote</span></div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- 07 DISTRIBUCIÓN -->
		<section class="stage" id="etapa-distribucion">
			<div class="wrap stage__grid reveal">
				<div class="stage__rail stage__rail--last"><span class="stage__node" aria-hidden="true">07</span></div>
				<div class="stage__content">
					<header class="stage__intro"><p class="eyebrow"><?php echo iz('dist_eyebrow'); ?></p><h2 class="section__title"><?php echo iz('dist_title'); ?></h2><p class="lead"><?php echo iz('dist_desc'); ?></p></header>
					<div class="dist__grid stagger">
						<?php foreach ( (array) izraw('dist_cards') as $i => $dc ) : ?>
							<article class="dist-card">
								<div class="dist-card__top"><?php echo $dist_icos[ $i % 2 ]; ?><div><h3><?php echo esc_html($dc['titulo']); ?></h3><span class="dist-card__method"><?php echo esc_html($dc['metodo']); ?></span></div></div>
								<p><?php echo esc_html($dc['descripcion']); ?></p>
								<div class="dist-card__eta"><span>Tiempo estimado</span><b><?php echo esc_html($dc['eta']); ?></b></div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
	</div><!-- /stages -->

	<!-- BLOCKCHAIN -->
	<section class="section blockchain" id="blockchain">
		<div class="blockchain__bg" aria-hidden="true"></div>
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
					<div class="chain-card__row"><span>Sistema</span><b>Blockchain</b></div>
				</div>
				<div class="hash"><span class="hash__label">Código de transacción blockchain</span><code class="hash__val mono" data-hash><?php echo ( izraw('bc_hash') ? iz('bc_hash') : 'Registro blockchain pendiente de integración' ); ?></code></div>
				<a href="<?php echo izu('bc_btn_link') ?: '#'; ?>" class="btn btn--ghost" data-ver-registro><?php echo iz('bc_btn'); ?></a>
			</div>
			<div class="blockchain__qr reveal">
				<div class="qr qr--lg" data-qr aria-label="Código QR del lote"></div>
				<p class="blockchain__qrnote"><?php echo iz('bc_qr_note'); ?></p>
			</div>
		</div>
	</section>

	<!-- CERTIFICACIONES -->
	<section class="section certs" id="certificaciones">
		<div class="wrap certs__head reveal">
			<p class="eyebrow"><?php echo iz('cert_eyebrow'); ?></p>
			<h2 class="section__title"><?php echo iz('cert_title'); ?></h2>
			<p class="lead lead--center"><?php echo iz('cert_desc'); ?></p>
		</div>
		<div class="wrap certs__grid stagger">
			<article class="cert">
				<div class="cert__seal"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 3v6c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M9 12l2 2 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
				<h3>Registro sanitario</h3>
				<dl class="cert__data">
					<div><dt>Código</dt><dd class="mono"><?php echo iz('registro_sanitario'); ?></dd></div>
					<div><dt>Empresa</dt><dd><?php echo iz('footer_company'); ?></dd></div>
					<div><dt>País</dt><dd>Perú</dd></div>
					<div><dt>Estado</dt><dd class="tag-pending"><?php echo iz('cert1_estado'); ?></dd></div>
				</dl>
				<a href="<?php echo izu('cert1_doc') ?: '#'; ?>" class="btn btn--line" data-doc="registro-sanitario">Ver registro</a>
			</article>
			<article class="cert">
				<div class="cert__seal"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 14c6 1 10-3 16-10-1 9-6 15-13 15-2 0-3-1-3-3 0-1 0-1.5.5-2z" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></div>
				<h3>Producto vegano</h3>
				<dl class="cert__data">
					<div><dt>Tipo de respaldo</dt><dd>Certificación o declaración</dd></div>
					<div><dt>Entidad</dt><dd class="tag-pending"><?php echo iz('cert2_entidad'); ?></dd></div>
					<div><dt>Fecha</dt><dd class="tag-pending"><?php echo iz('cert2_fecha'); ?></dd></div>
					<div><dt>Estado</dt><dd class="tag-pending"><?php echo iz('cert2_estado'); ?></dd></div>
				</dl>
				<a href="<?php echo izu('cert2_doc') ?: '#'; ?>" class="btn btn--line" data-doc="vegano">Ver documento</a>
			</article>
		</div>
	</section>

	<!-- PROPÓSITO -->
	<section class="section purpose" id="proposito">
		<div class="purpose__bg" aria-hidden="true"></div>
		<div class="wrap purpose__inner reveal">
			<p class="eyebrow eyebrow--light"><?php echo iz('prop_eyebrow'); ?></p>
			<h2 class="section__title section__title--light"><?php echo iz('prop_title'); ?></h2>
			<p class="purpose__quote"><?php echo iz('prop_quote'); ?></p>
			<p class="lead lead--light lead--center"><?php echo iz('prop_text'); ?></p>
			<div class="pillars stagger">
				<?php foreach ( (array) izraw('pillars') as $i => $pl ) printf('<article class="pillar">%s<h3>%s</h3><p>%s</p></article>', $pillar_icos[ $i % 3 ], esc_html($pl['titulo']), esc_html($pl['texto'])); ?>
			</div>
		</div>
	</section>

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
</main>

<footer class="footer">
	<div class="wrap footer__grid">
		<div class="footer__brand">
			<?php echo iz_brand( true ); ?>
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
