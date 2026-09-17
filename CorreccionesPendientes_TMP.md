# Correcciones Pendientes — Sesión de trabajo

Documento temporal de trabajo. Lista de problemas a corregir (excluye lo de **Certificado digital**, ya implementado).

## 🔸 Consulta AEAT — Tabla de facturas

| # | Problema | Severidad | Fix esperado | Estado |
|---|----------|-----------|--------------|--------|
| 1 | **El filtro de Mes no se puede quitar.** "Clear filter" no funciona; el control se queda en `Mes: 07 X 1 records` sin forma de limpiarlo. | Media | Que el botón **X** elimine el filtro de Mes. | CORREGIDO |
| 2 | ~~La tabla fuerza scroll horizontal de toda la página en móvil~~ | Media | *(ya resuelto)* | CORREGIDO |

## 🔸 Facturas — ListView

Sin problemas detectados.

## 🔸 Facturas — Creación

| # | Problema | Severidad | Fix esperado | Estado |
|---|----------|-----------|--------------|--------|
| 3 | **Requisito de dirección no comunicado antes del envío.** El error de "falta dirección principal/alternativa" solo aparece al intentar enviar a AEAT. | Media | Aviso visible en la sección Invoice To (icono/tooltip/resaltado) al seleccionar una organización sin dirección completa. | PENDIENTE |
| 4 | **Formulario de línea de artículo se desborda en móvil.** Al pulsar "Nueva línea" o "Nuevo servicio", el formulario excede el ancho del viewport. | Media | Apilar campos verticalmente en móvil o scroll interno en el contenedor. | PENDIENTE |
| 5 | **Duplicar factura emitida copia el estado AEAT mal.** Una factura duplicada hereda "Emitted" aunque nunca se envió a AEAT. | Media | Al duplicar: resetear estado a "Draft" y limpiar todos los campos AEAT. | PENDIENTE |
| 6 | **Campo Estado como desplegable con una sola opción** ("Borrador", resto ocultas con `display:none`) en creación. | Baja | Usar texto plano o input deshabilitado, no un `<select>`. | PENDIENTE |
| 7 | **La "dirección alternativa" se mapea como dirección de envío** de la factura. | Baja | Dejar dirección de envío vacía o usar por defecto la dirección principal. | PENDIENTE |

## 🔸 Facturas — Detalle (DetailView)

| # | Problema | Severidad | Fix esperado | Estado |
|---|----------|-----------|--------------|--------|
| 8 | **Tabla de líneas ilegible en móvil.** Las columnas se comprimen hasta ser ininteligibles. | Media | Apilar filas, scroll horizontal con primera columna fija, u ocultar columnas con patrón "tap to expand". | PENDIENTE |
| 9 | **La sección de dirección de facturación se desborda de su contenedor.** | Baja | Que el texto de la dirección haga wrap dentro del contenedor. | PENDIENTE |

---

**Resumen:** 8 hallazgos pendientes (5 severidad media, 4 de UI baja).

**Nota:** El hallazgo #2 ya está resuelto, se mantiene solo como referencia.
