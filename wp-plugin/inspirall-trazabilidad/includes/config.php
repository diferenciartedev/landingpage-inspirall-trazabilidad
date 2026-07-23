<?php
/**
 * Inspirall Trazabilidad — content schema & defaults.
 * The whole admin UI and the front-end template are generated from this schema,
 * so adding a field here makes it editable and renderable everywhere.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Schema: tabs -> fields.
 * Field types: text, textarea, url, image, repeater (with `sub` subfields).
 */
function inspitz_schema() {
	return array(

		'general' => array( 'label' => 'General', 'fields' => array(
			'logo_header'  => array( 'type' => 'image',    'label' => 'Logo · barra superior (fondo claro)', 'help' => 'Versión oscura / a color.' ),
			'logo_footer'  => array( 'type' => 'image',    'label' => 'Logo · footer (fondo oscuro)', 'help' => 'Versión blanca.' ),
			'brand_word'   => array( 'type' => 'text',     'label' => 'Nombre de marca (texto)', 'default' => 'inspirall' ),
			'topbar_tag'   => array( 'type' => 'text',     'label' => 'Texto de la barra superior', 'default' => 'Producto con trazabilidad blockchain' ),
			'lote_default' => array( 'type' => 'text',     'label' => 'Código de lote por defecto', 'default' => 'FCB-2025-0001', 'help' => 'Se sobreescribe con ?lote= en la URL del QR.' ),
		) ),

		'secciones' => array( 'label' => 'Secciones (mostrar/ocultar)', 'fields' => array(
			'sec_nutricion'       => array( 'type' => 'toggle', 'label' => 'Información nutricional', 'default' => '1' ),
			'sec_origen'          => array( 'type' => 'toggle', 'label' => '01 · Origen', 'default' => '1' ),
			'sec_cultivo'         => array( 'type' => 'toggle', 'label' => '02 · Cultivo', 'default' => '1' ),
			'sec_cosecha'         => array( 'type' => 'toggle', 'label' => '03 · Cosecha', 'default' => '1' ),
			'sec_nanotech'        => array( 'type' => 'toggle', 'label' => '04 · Acopio y Nanotech', 'default' => '1' ),
			'sec_lote'            => array( 'type' => 'toggle', 'label' => '05 · Identificación del lote', 'default' => '1' ),
			'sec_identificacion'  => array( 'type' => 'toggle', 'label' => '06 · Sellado y rotulado', 'default' => '1' ),
			'sec_distribucion'    => array( 'type' => 'toggle', 'label' => '07 · Distribución', 'default' => '1' ),
			'sec_blockchain'      => array( 'type' => 'toggle', 'label' => '08 · Trazabilidad blockchain', 'default' => '1' ),
			'sec_certificaciones' => array( 'type' => 'toggle', 'label' => '09 · Certificaciones', 'default' => '1' ),
			'sec_proposito'       => array( 'type' => 'toggle', 'label' => '10 · Propósito', 'default' => '1' ),
			'sec_cierre'          => array( 'type' => 'toggle', 'label' => '11 · Cierre', 'default' => '1' ),
		) ),

		'imagenes' => array( 'label' => 'Imágenes (tamaños)', 'fields' => array(
			'img_hero_w'   => array( 'type' => 'text', 'label' => 'Ancho de la foto del hero (px)', 'default' => '360', 'help' => 'El pomo / lata en la portada. Ej.: 360' ),
			'img_prod_w'   => array( 'type' => 'text', 'label' => 'Ancho de la foto de producto (px)', 'default' => '460', 'help' => 'Sección «Fico Crispy Blend».' ),
			'img_prod_ar'  => array( 'type' => 'text', 'label' => 'Proporción foto de producto', 'default' => '3 / 4', 'help' => 'ancho / alto. Ej.: 3 / 4 (vertical), 4 / 3 (horizontal), 1 / 1 (cuadrada).' ),
			'img_stage_ar' => array( 'type' => 'text', 'label' => 'Proporción fotos de proceso (cultivo y cosecha)', 'default' => '4 / 3', 'help' => 'Se recortan de forma pareja a esta proporción.' ),
			'img_envase_w' => array( 'type' => 'text', 'label' => 'Ancho de la foto del envase (px)', 'default' => '210', 'help' => 'Sección «Lote sellado y rotulado».' ),
		) ),

		'hero' => array( 'label' => 'Portada (Hero)', 'fields' => array(
			'hero_eyebrow' => array( 'type' => 'text',     'label' => 'Etiqueta superior', 'default' => 'Tu nutrición tiene origen' ),
			'hero_title'   => array( 'type' => 'textarea', 'label' => 'Título principal', 'default' => "Cultivado donde\npocos llegan", 'help' => 'Usa Enter para el salto de línea.' ),
			'hero_sub'     => array( 'type' => 'text',     'label' => 'Subtítulo', 'default' => 'Hecho para quienes exigen lo mejor.' ),
			'hero_desc'    => array( 'type' => 'textarea', 'label' => 'Descripción', 'default' => 'Espirulina premium cultivada bajo la radiación solar extrema de distintas zonas del Perú, con biotecnología, trazabilidad y propósito.' ),
			'hero_cta'     => array( 'type' => 'text',     'label' => 'Texto del botón', 'default' => 'Conoce el recorrido de este lote' ),
			'hero_cta_link'=> array( 'type' => 'url',      'label' => 'Enlace del botón', 'default' => '#recorrido' ),
			'hero_image'   => array( 'type' => 'image',    'label' => 'Foto del producto (el pomo)', 'help' => 'PNG con fondo transparente recomendado.' ),
			'hero_note'    => array( 'type' => 'textarea', 'label' => 'Texto complementario', 'default' => '100% espirulina peruana, lista para sumar nutrición todos los días. Un blend funcional creado para complementar tu alimentación de forma práctica y natural.' ),
		) ),

		'producto' => array( 'label' => 'Producto', 'fields' => array(
			'prod_eyebrow' => array( 'type' => 'text',     'label' => 'Etiqueta', 'default' => 'Producto con trazabilidad blockchain' ),
			'prod_title'   => array( 'type' => 'text',     'label' => 'Nombre del producto', 'default' => 'Fico Crispy Blend' ),
			'prod_sub'     => array( 'type' => 'text',     'label' => 'Presentación', 'default' => '100 g' ),
			'prod_desc'    => array( 'type' => 'textarea', 'label' => 'Descripción oficial', 'default' => 'Nuestro blend premium combina superalimentos seleccionados para potenciar tu nutrición. Rico en proteínas, hierro, vitaminas y antioxidantes. Proceso de cultivo de circuito cerrado con monitorización y trazabilidad blockchain para garantizar la máxima calidad y frescura.' ),
			'prod_image'   => array( 'type' => 'image',    'label' => 'Foto de producto' ),
			'prod_checks'  => array( 'type' => 'repeater', 'label' => 'Características (checks)', 'sub' => array( 'texto' => 'Texto' ),
				'default' => array(
					array( 'texto' => '100% natural' ),
					array( 'texto' => '0% azúcar refinada' ),
					array( 'texto' => 'Sin pesticidas ni metales' ),
					array( 'texto' => 'Apto para diabéticos' ),
					array( 'texto' => 'Sin preservantes ni químicos artificiales' ),
					array( 'texto' => '0% gluten' ),
				) ),
		) ),

		'origen' => array( 'label' => '01 · Origen', 'fields' => array(
			'origen_eyebrow' => array( 'type' => 'text',     'label' => 'Categoría', 'default' => 'Origen' ),
			'origen_title'   => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Cultivada bajo el sol más exigente del Perú' ),
			'origen_desc'    => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'La espirulina utilizada en Fico Crispy Blend proviene de centros de cultivo ubicados en distintas regiones del Perú. Cada zona presenta condiciones solares y productivas particulares.' ),
			'origen_mapa'    => array( 'type' => 'image',    'label' => 'Mapa (imagen, opcional)', 'help' => 'Sube un mapa del Perú con las zonas. Si lo dejas vacío se muestra el mapa estilizado con puntos.' ),
			'origen_note'    => array( 'type' => 'text',     'label' => 'Nota (GHI)', 'default' => 'GHI: Irradiación Horizontal Global recibida por una superficie.' ),
			'zones' => array( 'type' => 'repeater', 'label' => 'Zonas de procedencia', 'sub' => array(
				'nombre' => 'Zona', 'lugar' => 'Lugar', 'ghi' => 'GHI (kWh/m²)', 'produccion' => 'Producción mensual', 'volumen' => 'Volumen', 'co2' => 'Captura CO₂ / año' ),
				'default' => array(
					array( 'nombre' => 'Moquegua', 'lugar' => 'Torata · Sur del Perú', 'ghi' => '2,499.8', 'produccion' => '20 kg', 'volumen' => '17 m³', 'co2' => '480 kg / año' ),
					array( 'nombre' => 'Arequipa', 'lugar' => 'Socabaya · Sur del Perú', 'ghi' => '2,468.7', 'produccion' => '36 kg', 'volumen' => '25 m³', 'co2' => '624 kg / año' ),
					array( 'nombre' => 'Piura', 'lugar' => 'Sullana · Norte del Perú', 'ghi' => '2,342.5', 'produccion' => '75 kg', 'volumen' => '65 m³', 'co2' => '1,800 kg / año' ),
					array( 'nombre' => 'Quilmaná', 'lugar' => 'Cañete · Lima', 'ghi' => '1,838.4', 'produccion' => '30 kg', 'volumen' => '23 m³', 'co2' => '720 kg / año' ),
					array( 'nombre' => 'Pucallpa', 'lugar' => 'Ucayali · Selva del Perú', 'ghi' => '1,790.4', 'produccion' => '40 kg', 'volumen' => '35 m³', 'co2' => '960 kg / año' ),
				) ),
		) ),

		'cultivo' => array( 'label' => '02 · Cultivo', 'fields' => array(
			'cultivo_eyebrow'     => array( 'type' => 'text',     'label' => 'Categoría', 'default' => 'Cultivo' ),
			'cultivo_title'       => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Cultivo de espirulina' ),
			'cultivo_desc'        => array( 'type' => 'textarea', 'label' => 'Descripción', 'default' => 'La espirulina utilizada en Fico Crispy Blend se cultiva en centros ubicados en distintas regiones del Perú. Cada ciclo debe quedar asociado al lote correspondiente mediante la información de procedencia, inoculación y responsable.' ),
			'cultivo_image'       => array( 'type' => 'image',    'label' => 'Foto del cultivo' ),
			'cultivo_procedencia' => array( 'type' => 'textarea', 'label' => 'Procedencia (una por línea)', 'default' => "Socabaya, Arequipa\nTorata, Moquegua\nSullana, Piura\nPucallpa, Ucayali\nQuilmaná, Cañete" ),
			'cultivo_inoculacion' => array( 'type' => 'text',     'label' => 'Inoculación', 'default' => 'Desde las 6:00 a. m., durante una semana de cada mes.' ),
			'cultivo_tipo'        => array( 'type' => 'text',     'label' => 'Tipo de cultivo', 'default' => 'En agua tratada, 100% reciclada al momento de cosechar.' ),
			'cultivo_responsable' => array( 'type' => 'text',     'label' => 'Responsable', 'default' => 'Familiar agricultor o miembro de la asociación.' ),
		) ),

		'cosecha' => array( 'label' => '03 · Cosecha', 'fields' => array(
			'cosecha_eyebrow'     => array( 'type' => 'text',     'label' => 'Categoría', 'default' => 'Cosecha' ),
			'cosecha_title'       => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Cosecha registrada del lote' ),
			'cosecha_desc'        => array( 'type' => 'textarea', 'label' => 'Descripción', 'default' => 'La cosecha se realiza al finalizar el ciclo correspondiente y dentro de un horario previamente establecido. La fecha, hora, procedencia y responsable deben quedar vinculados al lote consultado.' ),
			'cosecha_image'       => array( 'type' => 'image',    'label' => 'Foto de la cosecha' ),
			'cosecha_horario'     => array( 'type' => 'text',     'label' => 'Horario', 'default' => 'De 8:00 a. m. a 11:00 a. m.' ),
			'cosecha_frecuencia'  => array( 'type' => 'text',     'label' => 'Frecuencia', 'default' => 'Cada fin de mes.' ),
			'cosecha_responsable' => array( 'type' => 'text',     'label' => 'Responsable', 'default' => 'Familiar agricultor o miembro de la asociación.' ),
		) ),

		'nanotech' => array( 'label' => '04 · Nanotech', 'fields' => array(
			'nano_eyebrow'  => array( 'type' => 'text',     'label' => 'Categoría', 'default' => 'Acopio y Nanotech' ),
			'nano_title'    => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Phyco-Active Nano-Blend' ),
			'nano_subtitle' => array( 'type' => 'text',     'label' => 'Subtítulo', 'default' => 'Tecnología enzimática de precisión en frío' ),
			'nano_p1'       => array( 'type' => 'textarea', 'label' => 'Párrafo 1', 'default' => 'Nuestro proceso patentado utiliza tecnología enzimática de precisión en frío para fraccionar la membrana celular de la espirulina de manera controlada. Esta molienda en frío, realizada a aproximadamente 30 °C y a nivel nanométrico, libera la ficocianina, el característico antioxidante azul de la espirulina, de forma inmediata y bioactiva.' ),
			'nano_p2'       => array( 'type' => 'textarea', 'label' => 'Párrafo 2', 'default' => 'Una vez liberada, la ficocianina es protegida mediante compuestos naturales nano-protectores frente a la degradación producida por la luz y el oxígeno, manteniendo su potencia hasta el consumo.' ),
			'nano_flow'     => array( 'type' => 'repeater', 'label' => 'Diagrama del proceso', 'sub' => array( 'texto' => 'Paso' ),
				'default' => array(
					array( 'texto' => 'Espirulina' ),
					array( 'texto' => 'Tecnología enzimática de precisión en frío' ),
					array( 'texto' => 'Fraccionamiento controlado de la membrana celular' ),
					array( 'texto' => 'Liberación de ficocianina' ),
					array( 'texto' => 'Protección frente a luz y oxígeno' ),
				) ),
			'nano_benefits' => array( 'type' => 'repeater', 'label' => 'Beneficios indicados', 'sub' => array( 'texto' => 'Beneficio' ),
				'default' => array(
					array( 'texto' => '100% espirulina enzimática' ),
					array( 'texto' => 'Bio-liberación de ficocianina' ),
					array( 'texto' => 'Blindaje nano contra luz y O₂' ),
					array( 'texto' => 'Alto en proteínas y hierro' ),
				) ),
		) ),

		'lote' => array( 'label' => '05 · Lote', 'fields' => array(
			'lote_eyebrow' => array( 'type' => 'text',     'label' => 'Categoría', 'default' => 'Control y trazabilidad' ),
			'lote_title'   => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Información asociada a cada lote' ),
			'lote_desc'    => array( 'type' => 'textarea', 'label' => 'Descripción', 'default' => 'La trazabilidad permite asociar al lote la información disponible sobre su procedencia, cultivo, cosecha, proceso tecnológico, producto y distribución.' ),
			'lote_quote'   => array( 'type' => 'text',     'label' => 'Frase destacada', 'default' => 'Cada envase conecta al consumidor con la historia registrada de su lote.' ),
		) ),

		'identificacion' => array( 'label' => '06 · Identificación', 'fields' => array(
			'ident_eyebrow'     => array( 'type' => 'text',     'label' => 'Categoría', 'default' => 'Identificación' ),
			'ident_title'       => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Lote sellado y rotulado' ),
			'ident_desc'        => array( 'type' => 'textarea', 'label' => 'Descripción', 'default' => 'Fico Crispy Blend se presenta en un envase identificado con su código de lote. El código QR dirige al consumidor hacia esta página, donde puede consultar la información disponible del producto.' ),
			'ident_image'       => array( 'type' => 'image',    'label' => 'Foto del envase' ),
			'registro_sanitario'=> array( 'type' => 'text',     'label' => 'Registro sanitario', 'default' => 'M58014268N / NAUSDE' ),
		) ),

		'distribucion' => array( 'label' => '07 · Distribución', 'fields' => array(
			'dist_eyebrow' => array( 'type' => 'text',     'label' => 'Categoría', 'default' => 'Distribución' ),
			'dist_title'   => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Del producto hasta tu destino' ),
			'dist_desc'    => array( 'type' => 'textarea', 'label' => 'Descripción', 'default' => 'Nuestro compromiso con la calidad se extiende a la entrega. Utilizamos métodos seguros y rastreables para procurar que Fico Crispy llegue en buenas condiciones a cada destino.' ),
			'dist_cards'   => array( 'type' => 'repeater', 'label' => 'Métodos de entrega', 'sub' => array(
				'titulo' => 'Destino', 'metodo' => 'Método', 'descripcion' => 'Descripción', 'eta' => 'Tiempo estimado' ),
				'default' => array(
					array( 'titulo' => 'Lima Metropolitana', 'metodo' => 'Entrega motorizada', 'descripcion' => 'Las entregas locales se realizan mediante un delivery motorizado de confianza en distritos seleccionados de Lima Metropolitana.', 'eta' => '24 a 48 horas' ),
					array( 'titulo' => 'Provincias', 'metodo' => 'Operadores logísticos nacionales', 'descripcion' => 'Para los envíos a provincias se trabaja con operadores logísticos como Olva o Shalom.', 'eta' => '3 a 5 días' ),
				) ),
		) ),

		'blockchain' => array( 'label' => '08 · Blockchain', 'fields' => array(
			'bc_eyebrow'  => array( 'type' => 'text',     'label' => 'Etiqueta', 'default' => 'Registro digital del producto' ),
			'bc_title'    => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Trazabilidad blockchain' ),
			'bc_text'     => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Este lote cuenta con un registro digital asociado a un código único de trazabilidad. Esta información permite consultar los datos disponibles del producto y su recorrido.' ),
			'bc_hash'     => array( 'type' => 'text',     'label' => 'Hash de transacción', 'default' => '', 'help' => 'Vacío = "Registro blockchain pendiente de integración". También se puede pasar por ?hash= en la URL.' ),
			'bc_qr_note'  => array( 'type' => 'text',     'label' => 'Texto bajo el QR', 'default' => 'Escanea para consultar nuevamente este lote.' ),
			'bc_btn'      => array( 'type' => 'text',     'label' => 'Texto del botón', 'default' => 'Ver registro del lote' ),
			'bc_btn_link' => array( 'type' => 'url',      'label' => 'Enlace del botón', 'default' => '' ),
		) ),

		'certificaciones' => array( 'label' => '09 · Certificaciones', 'fields' => array(
			'cert_eyebrow' => array( 'type' => 'text',     'label' => 'Etiqueta', 'default' => 'Certificaciones y registros' ),
			'cert_title'   => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Respaldo del producto' ),
			'cert_desc'    => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Consulta los documentos disponibles relacionados con el registro y las características declaradas de Fico Crispy Blend.' ),
			'cert1_estado' => array( 'type' => 'text',     'label' => 'Registro sanitario · estado', 'default' => 'Pendiente de validación' ),
			'cert1_doc'    => array( 'type' => 'url',      'label' => 'Registro sanitario · documento (enlace)', 'default' => '' ),
			'cert2_entidad'=> array( 'type' => 'text',     'label' => 'Vegano · entidad', 'default' => 'Por confirmar' ),
			'cert2_fecha'  => array( 'type' => 'text',     'label' => 'Vegano · fecha', 'default' => 'Por confirmar' ),
			'cert2_estado' => array( 'type' => 'text',     'label' => 'Vegano · estado', 'default' => 'Por confirmar' ),
			'cert2_doc'    => array( 'type' => 'url',      'label' => 'Vegano · documento (enlace)', 'default' => '' ),
		) ),

		'proposito' => array( 'label' => '10 · Propósito', 'fields' => array(
			'prop_eyebrow' => array( 'type' => 'text',     'label' => 'Etiqueta', 'default' => 'Ciencia, territorio y nutrición' ),
			'prop_title'   => array( 'type' => 'text',     'label' => 'Título', 'default' => 'La nueva generación de espirulina peruana' ),
			'prop_quote'   => array( 'type' => 'text',     'label' => 'Frase destacada', 'default' => 'Cultivada con ciencia y propósito' ),
			'prop_text'    => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Inspirall lleva la espirulina a un formato práctico y nutritivo, pensado para complementar la alimentación diaria de forma natural.' ),
			'pillars' => array( 'type' => 'repeater', 'label' => 'Pilares', 'sub' => array( 'titulo' => 'Título', 'texto' => 'Texto' ),
				'default' => array(
					array( 'titulo' => 'Origen peruano', 'texto' => 'Espirulina procedente de diferentes zonas del Perú.' ),
					array( 'titulo' => 'Biotecnología', 'texto' => 'Proceso Phyco-Active Nano-Blend con tecnología enzimática de precisión en frío.' ),
					array( 'titulo' => 'Trazabilidad', 'texto' => 'Información del lote asociada a un registro digital.' ),
				) ),
		) ),

		'cierre' => array( 'label' => '11 · Cierre', 'fields' => array(
			'cierre_title'     => array( 'type' => 'text',     'label' => 'Título', 'default' => 'Ahora conoces el origen de tu Fico Crispy Blend' ),
			'cierre_desc'      => array( 'type' => 'textarea', 'label' => 'Texto', 'default' => 'Cada lote reúne información sobre el territorio, el cultivo, el proceso tecnológico y la distribución del producto que tienes en tus manos.' ),
			'cierre_cta1'      => array( 'type' => 'text',     'label' => 'Botón principal · texto', 'default' => 'Conocer Fico Crispy Blend' ),
			'cierre_cta1_link' => array( 'type' => 'url',      'label' => 'Botón principal · enlace', 'default' => 'https://inspirall.pe/' ),
			'cierre_cta2'      => array( 'type' => 'text',     'label' => 'Botón secundario · texto', 'default' => 'Ir a la tienda' ),
			'cierre_cta2_link' => array( 'type' => 'url',      'label' => 'Botón secundario · enlace', 'default' => 'https://inspirall.pe/' ),
		) ),

		'footer' => array( 'label' => 'Footer y enlaces', 'fields' => array(
			'footer_company'      => array( 'type' => 'text', 'label' => 'Empresa', 'default' => 'Inspirall Solutions' ),
			'footer_city'         => array( 'type' => 'text', 'label' => 'Ciudad', 'default' => 'Lima, Perú' ),
			'footer_registro'     => array( 'type' => 'text', 'label' => 'Registro sanitario (footer)', 'default' => 'M58014268N / NAUSDE' ),
			'contacto_email'      => array( 'type' => 'text', 'label' => 'Email de contacto', 'default' => 'hola@inspirall.pe' ),
			'link_sitio'          => array( 'type' => 'url',  'label' => 'Sitio oficial', 'default' => 'https://inspirall.pe/' ),
			'link_ceroanemia'     => array( 'type' => 'url',  'label' => 'Cero anemia', 'default' => 'https://inspirall.pe/ceroanemia' ),
			'social_instagram'    => array( 'type' => 'url',  'label' => 'Instagram', 'default' => '' ),
			'social_facebook'     => array( 'type' => 'url',  'label' => 'Facebook', 'default' => '' ),
			'social_tiktok'       => array( 'type' => 'url',  'label' => 'TikTok', 'default' => '' ),
			'link_privacidad'     => array( 'type' => 'url',  'label' => 'Política de privacidad', 'default' => '#' ),
			'link_terminos'       => array( 'type' => 'url',  'label' => 'Términos y condiciones', 'default' => '#' ),
			'link_reclamaciones'  => array( 'type' => 'url',  'label' => 'Libro de reclamaciones', 'default' => '#' ),
		) ),
	);
}

/** Build the default values array from the schema. */
function inspitz_defaults() {
	$d = array();
	foreach ( inspitz_schema() as $tab ) {
		foreach ( $tab['fields'] as $key => $f ) {
			$d[ $key ] = isset( $f['default'] ) ? $f['default'] : ( $f['type'] === 'repeater' ? array() : '' );
		}
	}
	return $d;
}

/** Saved values merged over defaults. */
function inspitz_get() {
	$saved = get_option( 'inspirall_traz', array() );
	if ( ! is_array( $saved ) ) $saved = array();
	return array_merge( inspitz_defaults(), $saved );
}
