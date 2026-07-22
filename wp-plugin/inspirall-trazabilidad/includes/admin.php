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
					// keep row only if at least one field non-empty
					if ( strlen( implode( '', $r ) ) ) $clean[] = $r;
				}
				$out[ $key ] = $clean;
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

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( $hook !== 'toplevel_page_inspirall-traz' ) return;
	wp_enqueue_media();
	wp_enqueue_style( 'inspitz-admin', false );
	wp_add_inline_style( 'inspitz-admin', inspitz_admin_css() );
} );

function inspitz_admin_css() {
	return '
	.inspitz-tabs{display:flex;flex-wrap:wrap;gap:4px;margin:16px 0}
	.inspitz-tab{padding:8px 14px;background:#fff;border:1px solid #dcdcde;border-radius:6px;cursor:pointer;font-weight:600}
	.inspitz-tab.active{background:#0e8f83;border-color:#0e8f83;color:#fff}
	.inspitz-panel{display:none;background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:18px 22px;max-width:900px}
	.inspitz-panel.active{display:block}
	.inspitz-field{margin:0 0 20px}
	.inspitz-field>label{display:block;font-weight:600;margin-bottom:6px}
	.inspitz-field input[type=text],.inspitz-field input[type=url],.inspitz-field textarea{width:100%;max-width:640px}
	.inspitz-field textarea{min-height:80px}
	.inspitz-help{color:#666;font-size:12px;margin-top:4px}
	.inspitz-img{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
	.inspitz-img img{max-height:70px;border:1px solid #dcdcde;border-radius:6px;background:#f0f0f1}
	.inspitz-rep-row{border:1px solid #e0e0e0;border-radius:8px;padding:12px 14px;margin-bottom:10px;background:#fafafa;position:relative}
	.inspitz-rep-row .inspitz-sub{margin-bottom:8px}
	.inspitz-rep-row .inspitz-sub label{display:block;font-size:12px;color:#555;margin-bottom:3px}
	.inspitz-rm{position:absolute;top:8px;right:8px}
	';
}

function inspitz_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$schema = inspitz_schema();
	$o = inspitz_get();
	$first = true;
	?>
	<div class="wrap">
		<h1>Trazabilidad Inspirall</h1>
		<p>Edita el contenido de la página de trazabilidad. Publícala con el shortcode
		<code>[inspirall_trazabilidad]</code> en cualquier página, o usa la página
		<strong>«Trazabilidad Fico Crispy»</strong> creada automáticamente.
		El código de lote se puede cambiar por QR con <code>?lote=CODIGO</code> en la URL.</p>

		<div class="inspitz-tabs">
			<?php foreach ( $schema as $tabkey => $tab ) : ?>
				<div class="inspitz-tab<?php echo $first ? ' active' : ''; ?>" data-tab="<?php echo esc_attr( $tabkey ); ?>"><?php echo esc_html( $tab['label'] ); ?></div>
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
		var tabs=document.querySelectorAll('.inspitz-tab');
		tabs.forEach(function(t){t.addEventListener('click',function(){
			document.querySelectorAll('.inspitz-tab').forEach(function(x){x.classList.remove('active');});
			document.querySelectorAll('.inspitz-panel').forEach(function(x){x.classList.remove('active');});
			t.classList.add('active');
			document.getElementById('panel-'+t.dataset.tab).classList.add('active');
		});});

		// Media uploader
		document.addEventListener('click',function(e){
			if(e.target.classList.contains('inspitz-pick')){
				e.preventDefault();
				var wrap=e.target.closest('.inspitz-img');
				var input=wrap.querySelector('input');
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
			if(e.target.classList.contains('inspitz-clear')){
				e.preventDefault();
				var w=e.target.closest('.inspitz-img'); w.querySelector('input').value=''; var im=w.querySelector('img'); if(im) im.remove();
			}
			// repeater add
			if(e.target.classList.contains('inspitz-add')){
				e.preventDefault();
				var rep=e.target.closest('.inspitz-rep');
				var tpl=rep.querySelector('.inspitz-rep-tpl');
				var rows=rep.querySelector('.inspitz-rep-rows');
				var idx=rows.children.length;
				var html=tpl.innerHTML.replace(/__i__/g,idx);
				var div=document.createElement('div'); div.innerHTML=html; rows.appendChild(div.firstElementChild);
			}
			// repeater remove
			if(e.target.classList.contains('inspitz-rm')){
				e.preventDefault();
				var row=e.target.closest('.inspitz-rep-row'); row.parentNode.removeChild(row);
			}
		});
	})();
	</script>
	<?php
}

/** Render one field. */
function inspitz_field( $key, $f, $val ) {
	$name = 'inspirall_traz[' . esc_attr( $key ) . ']';
	echo '<div class="inspitz-field">';
	echo '<label>' . esc_html( $f['label'] ) . '</label>';

	if ( $f['type'] === 'image' ) {
		echo '<div class="inspitz-img">';
		if ( $val ) echo '<img src="' . esc_url( $val ) . '" alt="" />';
		echo '<input type="text" name="' . $name . '" value="' . esc_attr( $val ) . '" placeholder="URL de la imagen" />';
		echo '<button class="button inspitz-pick">Seleccionar</button> ';
		echo '<button class="button inspitz-clear">Quitar</button>';
		echo '</div>';
	} elseif ( $f['type'] === 'textarea' ) {
		echo '<textarea name="' . $name . '">' . esc_textarea( $val ) . '</textarea>';
	} elseif ( $f['type'] === 'url' ) {
		echo '<input type="url" name="' . $name . '" value="' . esc_attr( $val ) . '" placeholder="https://…" />';
	} elseif ( $f['type'] === 'repeater' ) {
		$rows = is_array( $val ) ? array_values( $val ) : array();
		if ( empty( $rows ) ) $rows = array(); // allow empty
		echo '<div class="inspitz-rep" data-key="' . esc_attr( $key ) . '">';
		echo '<div class="inspitz-rep-rows">';
		foreach ( $rows as $i => $row ) echo inspitz_rep_row( $key, $f['sub'], $i, $row );
		echo '</div>';
		// hidden template
		echo '<div class="inspitz-rep-tpl" style="display:none">' . htmlspecialchars( inspitz_rep_row( $key, $f['sub'], '__i__', array() ) ) . '</div>';
		echo '<button class="button inspitz-add">+ Añadir</button>';
		echo '</div>';
	} else {
		echo '<input type="text" name="' . $name . '" value="' . esc_attr( $val ) . '" />';
	}

	if ( ! empty( $f['help'] ) ) echo '<div class="inspitz-help">' . esc_html( $f['help'] ) . '</div>';
	echo '</div>';
}

/** One repeater row markup. */
function inspitz_rep_row( $key, $sub, $i, $row ) {
	$h = '<div class="inspitz-rep-row"><button class="button inspitz-rm">×</button>';
	foreach ( $sub as $sk => $slabel ) {
		$v = isset( $row[ $sk ] ) ? $row[ $sk ] : '';
		$n = 'inspirall_traz[' . esc_attr( $key ) . '][' . $i . '][' . esc_attr( $sk ) . ']';
		$h .= '<div class="inspitz-sub"><label>' . esc_html( $slabel ) . '</label>';
		$h .= '<input type="text" name="' . $n . '" value="' . esc_attr( $v ) . '" /></div>';
	}
	$h .= '</div>';
	return $h;
}
