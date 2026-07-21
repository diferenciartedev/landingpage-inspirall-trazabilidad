# Inspirall · Landing de Trazabilidad — Fico Crispy Blend 100 g

Landing page de trazabilidad blockchain para el producto **Fico Crispy Blend 100 g** de
**Inspirall**. Presenta el recorrido registrado de cada lote de espirulina peruana: origen,
cultivo, cosecha, proceso *Phyco-Active Nano-Blend*, identificación, distribución, registro
blockchain y certificaciones.

Sitio estático (HTML + CSS + JS, sin build). Listo para desplegar en Vercel u otro hosting
estático.

## Estructura

```
index.html    Estructura y contenido (16 secciones)
styles.css    Sistema de diseño (azul profundo / turquesa / verde azulado / crema / blanco)
script.js     Datos dinámicos del lote, QR, animaciones de scroll e interacciones
```

## Lote dinámico (parámetros de URL)

El QR de cada envase apunta a esta página con el código de lote y sus datos. Una sola página
sirve para todos los lotes:

```
/?lote=FCB-2025-0731
```

Parámetros reconocidos (todos opcionales; si no se envían se muestran estados neutros, sin
inventar datos):

| Parámetro       | Efecto |
|-----------------|--------|
| `lote`          | Código del lote mostrado en toda la página y codificado en el QR |
| `sede`          | Procedencia del lote (ej. `Sullana,%20Piura`) |
| `inoc`          | Fecha y hora de inoculación |
| `cosecha`       | Fecha y hora de cosecha |
| `hash`          | Código de transacción blockchain (si no se envía: *pendiente de integración*) |
| `registro`      | Registro sanitario (por defecto `M5801426N / NAUTSS`) |
| `vegan`         | `1` para mostrar la indicación vegana (solo con respaldo) |
| `doc_registro`  | URL al documento del registro sanitario |
| `doc_vegano`    | URL al documento vegano |
| `registro_url`  | URL para el botón «Ver registro del lote» |

## Fotografías

Los paneles de imagen (`.photo-panel`, `.product-shot`, `.pack__front`) son **marcadores de
posición** con estética de marca. Cada uno indica en `data-photo` qué fotografía real debe
colocarse. Para usar fotos reales, reemplaza el fondo del panel por la imagen correspondiente
(por ejemplo, `background-image: url('assets/cultivo.jpg')`) y elimina el `::after` de aviso.

Usar fotografías reales de centros de cultivo, naves/estanques de espirulina, supervisión y
personal. **No** usar imágenes de agricultura tradicional ni campos verdes convencionales.

## Código QR

El QR se genera dinámicamente contra la URL canónica del lote mediante un servicio de imagen
(`api.qrserver.com`). Para un entorno de producción sin dependencias externas, reemplaza la
función `buildQr` en `script.js` por un generador local/offline.

## Notas de contenido

- Todo el contenido proviene del documento oficial de Inspirall. No se agregan etapas,
  procesos, beneficios, certificaciones, fechas ni responsables no confirmados.
- El hash blockchain nunca se inventa: si no existe, se muestra *«Registro blockchain
  pendiente de integración»*.
- No se muestran certificaciones no documentadas (orgánico, ISO, HACCP, etc.).

## Desarrollo local

```bash
python3 -m http.server 8000
# abrir http://localhost:8000
```
