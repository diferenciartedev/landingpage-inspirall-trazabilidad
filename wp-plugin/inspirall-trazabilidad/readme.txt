=== Inspirall · Trazabilidad ===
Contributors: inspirall
Tags: trazabilidad, blockchain, landing, espirulina, qr
Requires at least: 5.8
Requires PHP: 7.2
Stable tag: 1.0.0

Página de trazabilidad blockchain para Fico Crispy Blend, totalmente editable desde el panel de WordPress.

== Qué hace ==

Crea una página de trazabilidad premium (origen, cultivo, cosecha, proceso Phyco-Active
Nano-Blend, identificación, distribución, blockchain, certificaciones) donde puedes:

* Cargar el pomo (lata) y las fotos de cada etapa desde la Biblioteca de Medios.
* Cambiar todos los textos (títulos, descripciones, datos de cada zona, etc.).
* Poner los enlaces de los botones (tienda, ver registro, documentos, redes…).
* Editar el código de lote por defecto; y cambiarlo por QR con ?lote=CODIGO en la URL.

== Instalación ==

1. Ve a **Plugins → Añadir nuevo → Subir plugin** y sube `inspirall-trazabilidad.zip`.
2. Actívalo. Se crea automáticamente la página **«Trazabilidad Fico Crispy»** en pantalla completa.
3. Menú lateral **Trazabilidad** → edita el contenido y pulsa **Guardar cambios**.
4. Sube tus imágenes con el botón **Seleccionar** de cada campo de imagen (pomo, cultivo, cosecha, envase, logo).

== Formas de publicarla ==

* **Página en pantalla completa (recomendado):** en la página, elige la plantilla
  «Trazabilidad Inspirall (pantalla completa)». Se ve sin la cabecera/footer del tema.
* **Shortcode:** pega `[inspirall_trazabilidad]` en cualquier página o entrada.

== Lote dinámico por QR ==

El código del QR de cada envase apunta a la página con parámetros. Ejemplo:
`/trazabilidad-fico-crispy/?lote=FCB-2025-0731&sede=Sullana,%20Piura&hash=0x...&vegan=1`

Parámetros: lote, sede, inoc, cosecha, hash, registro, vegan, doc_registro, doc_vegano, registro_url.

== Notas ==

* La información nutricional usa los datos reales del envase (fijos en esta versión).
* Si no cargas una imagen, se muestra un respaldo (mockup del pomo / panel) para que nunca
  se vea vacío.

== Changelog ==

= 1.0.0 =
* Versión inicial: contenido editable, subida de imágenes, plantilla de pantalla completa,
  shortcode y lote dinámico por QR.
