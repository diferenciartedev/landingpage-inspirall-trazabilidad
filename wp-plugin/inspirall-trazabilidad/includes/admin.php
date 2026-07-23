<?php
/** Inspirall Trazabilidad — admin settings page (generated from the schema). */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu', function () {
	add_menu_page(
		'Trazabilidad Inspirall', 'Trazabilidad', 'manage_options',
		'inspirall-traz', 'inspitz_render_admin_page', 'dashicons-analytics', 58
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'inspitz_group', 'inspirall_traz', array(
		'type' => 'array', 'sanitize_callback' => 'inspitz_sanitize', 'default' => array(),
	) );
} );

/** Sanitize the whole options array according to the schema. */
function inspitz_sanitize( $input ) {
	$out = array();
	if ( ! is_array( $input ) ) return $out;
	foreach ( inspitz_schema() as $tab ) {
		foreach ( $tab['fields'] as $key => $f ) {
			if ( $f['type'] === 'repeater' ) {
				$rows = isset( $input[ $key ] ) && is_array( $input[ $key ] ) ? array_values( $input[ $key ] ) : array();
				$clean = array();
				foreach ( $rows as $row ) {
					if ( ! is_array( $row ) ) continue;
					$r = array();
					foreach ( array_keys( $f['sub'] ) as $sk ) {
						$r[ $sk ] = sanitize_text_field( isset( $row[ $sk ] ) ? $row[ $sk ] : '' );
					}
					if ( strlen( implode( '', $r ) ) ) $clean[] = $r;
				}
				$out[ $key ] = $clean;
			} elseif ( $f['type'] === 'toggle' ) {
				$out[ $key ] = ( isset( $input[ $key ] ) && $input[ $key ] === '1' ) ? '1' : '';
			} elseif ( $f['type'] === 'url' || $f['type'] === 'image' ) {
				$out[ $key ] = esc_url_raw( trim( isset( $input[ $key ] ) ? $input[ $key ] : '' ) );
			} elseif ( $f['type'] === 'textarea' ) {
				$out[ $key ] = sanitize_textarea_field( isset( $input[ $key ] ) ? $input[ $key ] : '' );
			} else {
				$out[ $key ] = sanitize_text_field( isset( $input[ $key ] ) ? $input[ $key ] : '' );
			}
		}
	}
	return $out;
}

/** Load the WP media library on our page (for the image pickers). */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( $hook === 'toplevel_page_inspirall-traz' ) wp_enqueue_media();
} );

/** Admin CSS printed inline so it always loads. */
function inspitz_admin_css() {
	return '
	.inspitz-wrap{max-width:1000px}
	.inspitz-intro{background:#fff;border:1px solid #dcdcde;border-left:4px solid #0e8f83;border-radius:6px;padding:12px 16px;margin:12px 0 0;font-size:13px;line-height:1.6}
	.inspitz-tabs{display:flex;flex-wrap:wrap;gap:6px;margin:18px 0 0;border-bottom:1px solid #dcdcde;padding-bottom:0}
	.inspitz-tab{padding:9px 15px;background:#f0f0f1;border:1px solid #dcdcde;border-bottom:none;border-radius:7px 7px 0 0;cursor:pointer;font-weight:600;font-size:13px;color:#1d2327;line-height:1.2}
	.inspitz-tab:hover{background:#fff}
	.inspitz-tab.active{background:#0e8f83;border-color:#0e8f83;color:#fff}
	.inspitz-panel{display:none;background:#fff;border:1px solid #dcdcde;border-radius:0 8px 8px 8px;padding:22px 26px;margin-top:-1px}
	.inspitz-panel.active{display:block}
	.inspitz-field{margin:0 0 20px;padding:0 0 20px;border-bottom:1px solid #f0f0f1}
	.inspitz-field:last-of-type{border-bottom:none;margin-bottom:0;padding-bottom:0}
	.inspitz-field>label{display:block;font-weight:600;font-size:13px;margin:0 0 7px;color:#1d2327}
	.inspitz-field input[type=text],.inspitz-field input[type=url],.inspitz-field textarea{width:100%;max-width:100%;padding:8px 11px;border:1px solid #8c8f94;border-radius:5px;box-sizing:border-box;font-size:14px}
	.inspitz-field textarea{min-height:88px;line-height:1.5}
	.inspitz-help{color:#646970;font-size:12px;margin-top:6px}
	.inspitz-img{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
	.inspitz-img img{width:72px;height:72px;object-fit:cover;border:1px solid #dcdcde;border-radius:8px;background:#f6f7f7}
	.inspitz-img input{flex:1 1 240px;min-width:200px}
	.inspitz-rep-rows{display:grid;gap:12px;margin-bottom:10px}
	.inspitz-rep-row{border:1px solid #e2e4e7;border-radius:10px;padding:14px 16px;background:#fbfbfc;position:relative;display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:12px}
	.inspitz-sub{margin:0}
	.inspitz-sub label{display:block;font-size:11px;text-transform:uppercase;letter-spacing:.03em;color:#646970;margin:0 0 4px}
	.inspitz-sub input{width:100%;padding:7px 10px;border:1px solid #8c8f94;border-radius:5px;box-sizing:border-box;font-size:13px}
	.inspitz-rm{position:absolute;top:-11px;right:-11px;width:26px;height:26px;padding:0;border-radius:50%;border:1px solid #dcdcde;background:#fff;cursor:pointer;line-height:22px;font-size:17px;color:#d63638;text-align:center}
	.inspitz-rm:hover{background:#d63638;color:#fff;border-color:#d63638}
	.inspitz-toggle{display:flex;align-items:center;gap:9px;font-weight:600;font-size:14px;cursor:pointer;color:#1d2327}
	.inspitz-toggle input{width:18px;height:18px;margin:0}
	';
}

function inspitz_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$schema = inspitz_schema();
	$o = inspitz_get();
	?>
	<style><?php echo inspitz_admin_css(); ?></style>
	<div class="wrap inspitz-wrap">
		<h1>Trazabilidad Inspirall</h1>
		<div class="inspitz-intro">
			Edita el contenido y pulsa <strong>Guardar cambios</strong>. Publícala con el shortcode
			<code>[inspirall_trazabilidad]</code> o, mejor, asigna a la página la plantilla
			<strong>«Trazabilidad Inspirall (pantalla completa)»</strong>. El código de lote se puede
			cambiar por QR con <code>?lote=CODIGO</code> en la URL.
		</div>

		<div class="inspitz-tabs">
			<?php $first = true; foreach ( $schema as $tabkey => $tab ) : ?>
				<button type="button" class="inspitz-tab<?php echo $first ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $tabkey ); ?>"><?php echo esc_html( $tab['label'] ); ?></button>
				<?php $first = false; endforeach; ?>
		</div>

		<form method="post" action="options.php">
			<?php settings_fields( 'inspitz_group' ); ?>
			<?php $first = true; foreach ( $schema as $tabkey => $tab ) : ?>
				<div class="inspitz-panel<?php echo $first ? ' active' : ''; ?>" id="panel-<?php echo esc_attr( $tabkey ); ?>">
					<?php foreach ( $tab['fields'] as $key => $f ) inspitz_field( $key, $f, isset( $o[ $key ] ) ? $o[ $key ] : '' ); ?>
				</div>
				<?php $first = false; endforeach; ?>
			<?php submit_button( 'Guardar cambios' ); ?>
		</form>
	</div>

	<script>
	(function(){
		document.querySelectorAll('.inspitz-tab').forEach(function(t){
			t.addEventListener('click',function(){
				document.querySelectorAll('.inspitz-tab').forEach(function(x){x.classList.remove('active');});
				document.querySelectorAll('.inspitz-panel').forEach(function(x){x.classList.remove('active');});
				t.classList.add('active');
				var p=document.getElementById('panel-'+t.dataset.tab); if(p) p.classList.add('active');
			});
		});
		document.addEventListener('click',function(e){
			var t=e.target;
			if(t.classList.contains('inspitz-pick')){
				e.preventDefault();
				var wrap=t.closest('.inspitz-img'), input=wrap.querySelector('input');
				var frame=wp.media({title:'Seleccionar imagen',multiple:false,library:{type:'image'}});
				frame.on('select',function(){
					var a=frame.state().get('selection').first().toJSON();
					input.value=a.url;
					var img=wrap.querySelector('img');
					if(!img){img=document.createElement('img');wrap.insertBefore(img,wrap.firstChild);}
					img.src=a.url;
				});
				frame.open();
			}
			if(t.classList.contains('inspitz-clear')){
				e.preventDefault();
				var w=t.closest('.inspitz-img'); w.querySelector('input').value=''; var im=w.querySelector('img'); if(im) im.remove();
			}
			if(t.classList.contains('inspitz-add')){
				e.preventDefault();
				var rep=t.closest('.inspitz-rep'), tpl=rep.querySelector('.inspitz-rep-tpl'), rows=rep.querySelector('.inspitz-rep-rows');
				var div=document.createElement('div'); div.innerHTML=tpl.innerHTML.replace(/__i__/g,rows.children.length);
				rows.appendChild(div.firstElementChild);
			}
			if(t.classList.contains('inspitz-rm')){ e.preventDefault(); var row=t.closest('.inspitz-rep-row'); if(row) row.parentNode.removeChild(row); }
		});
	})();
	</script>
	<?php
}

/** Render one field. */
function inspitz_field( $key, $f, $val ) {
	$name = 'inspirall_traz[' . esc_attr( $key ) . ']';
	echo '<div class="inspitz-field">';

	if ( $f['type'] === 'toggle' ) {
		$checked = ( $val === '1' ) ? ' checked' : '';
		echo '<label class="inspitz-toggle"><input type="checkbox" name="' . $name . '" value="1"' . $checked . ' /> ' . esc_html( $f['label'] ) . '</label>';
		if ( ! empty( $f['help'] ) ) echo '<div class="inspitz-help">' . esc_html( $f['help'] ) . '</div>';
		echo '</div>';
		return;
	}

	echo '<label>' . esc_html( $f['label'] ) . '</label>';

	if ( $f['type'] === 'image' ) {
		echo '<div class="inspitz-img">';
		if ( $val ) echo '<img src="' . esc_url( $val ) . '" alt="" />';
		echo '<input type="text" name="' . $name . '" value="' . esc_attr( $val ) . '" placeholder="URL de la imagen" />';
		echo '<button type="button" class="button inspitz-pick">Seleccionar</button> ';
		echo '<button type="button" class="button inspitz-clear">Quitar</button>';
		echo '</div>';
	} elseif ( $f['type'] === 'textarea' ) {
		echo '<textarea name="' . $name . '">' . esc_textarea( $val ) . '</textarea>';
	} elseif ( $f['type'] === 'url' ) {
		echo '<input type="url" name="' . $name . '" value="' . esc_attr( $val ) . '" placeholder="https://…" />';
	} elseif ( $f['type'] === 'repeater' ) {
		$rows = is_array( $val ) ? array_values( $val ) : array();
		echo '<div class="inspitz-rep" data-key="' . esc_attr( $key ) . '">';
		echo '<div class="inspitz-rep-rows">';
		foreach ( $rows as $i => $row ) echo inspitz_rep_row( $key, $f['sub'], $i, $row );
		echo '</div>';
		echo '<div class="inspitz-rep-tpl" style="display:none">' . htmlspecialchars( inspitz_rep_row( $key, $f['sub'], '__i__', array() ) ) . '</div>';
		echo '<button type="button" class="button inspitz-add">+ Añadir</button>';
		echo '</div>';
	} else {
		echo '<input type="text" name="' . $name . '" value="' . esc_attr( $val ) . '" />';
	}

	if ( ! empty( $f['help'] ) ) echo '<div class="inspitz-help">' . esc_html( $f['help'] ) . '</div>';
	echo '</div>';
}

/** One repeater row markup. */
function inspitz_rep_row( $key, $sub, $i, $row ) {
	$h = '<div class="inspitz-rep-row"><button type="button" class="inspitz-rm" title="Quitar">&times;</button>';
	foreach ( $sub as $sk => $slabel ) {
		$v = isset( $row[ $sk ] ) ? $row[ $sk ] : '';
		$n = 'inspirall_traz[' . esc_attr( $key ) . '][' . $i . '][' . esc_attr( $sk ) . ']';
		$h .= '<div class="inspitz-sub"><label>' . esc_html( $slabel ) . '</label>';
		$h .= '<input type="text" name="' . $n . '" value="' . esc_attr( $v ) . '" /></div>';
	}
	$h .= '</div>';
	return $h;
}
