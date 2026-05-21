# Manual de Usuario - Verifactu (AEAT) en SinergiaCRM

> **Versión**: 1.0  
> **Fecha**: Mayo 2026  
> **Módulo**: AOS_Invoices (Facturas)

---

## 1. ¿Qué es Verifactu?

Verifactu es el sistema de la Agencia Tributaria española (AEAT) para la verificación y remisión electrónica de facturas. SinergiaCRM integra esta funcionalidad de forma nativa, permitiendo enviar facturas a la AEAT desde el módulo de Facturas mediante una acción explícita del usuario.

---

## 2. Activación del Sistema

### 2.1 Modo Legacy vs Modo Verifactu

El sistema funciona en dos modos:

| Modo | Comportamiento |
|------|---------------|
| **Legacy** (por defecto) | Sin envío a AEAT. Las facturas se generan con numeración normal, sin hash ni QR. |
| **Verifactu** | Envío explícito a AEAT (botón), generación de hash encadenado, código QR y bloqueo de edición tras aceptación. |

### 2.2 Cómo activar Verifactu

1. Acceda a **Administración → Configuración del sistema** (`stic_Settings`).
2. Localice el parámetro **`VERIFACTU_ACTIVATED`**.
3. Cambie su valor de `0` a `1`.
4. Guarde los cambios.

> **Importante**: Antes de activar Verifactu, debe haber configurado un **certificado digital** válido (ver sección 3).

---

## 3. Certificado Digital

### 3.1 ¿Qué certificado necesito?

Necesita un certificado digital válido reconocido por la AEAT. Puede ser:

- **Sello de entidad**: Para empresas (sin datos personales de representante).
- **Certificado de representante**: Para personas físicas que representan a una entidad.

### 3.2 Cómo subir el certificado

1. Acceda a **Administración → Certificado Digital**.
2. Haga clic en el botón para subir el archivo.
3. Seleccione su archivo de certificado (formato `.pfx` o `.p12`).
4. Introduzca la contraseña del certificado.
5. El sistema extraerá automáticamente:
   - **NIF** del titular
   - **Nombre** del titular
   - **Tipo** de certificado (sello o representante)
   - **Fecha de expiración**
6. Guarde los cambios.

### 3.3 Información visible

Una vez cargado, podrá ver en la misma pantalla:

- NIF extraído
- Nombre del titular
- Tipo de certificado
- Fecha de expiración

> **Atención**: Si el certificado caduca, las facturas no podrán enviarse a AEAT. Renueve el certificado antes de la fecha de expiración.

---

## 4. Flujo de Trabajo con Facturas

### 4.1 Estados de una Factura

| Estado | Descripción |
|--------|-------------|
| **Borrador** (`draft`) | Factura en creación. No se ha enviado a AEAT. Estado por defecto. |
| **Emitida** (`emitted`) | Factura enviada a AEAT. Este estado se asigna automáticamente al pulsar "Enviar a AEAT". |
| **Pagada** (`Paid`) | Factura cobrada (solo accesible tras pasar por `emitted`). |
| **No pagada** (`Unpaid`) | Factura impagada (solo accesible tras pasar por `emitted`). |

### 4.2 Crear una Factura

1. Vaya al módulo **Facturas** (`AOS_Invoices`).
2. Haga clic en **Crear Factura**.
3. Complete los campos obligatorios:
   - **Organización** o **Persona** (cliente) — **obligatorio**
   - **Fecha de expedición**
   - **Líneas de producto/servicio**
   - **Serie de facturación** (se asigna automáticamente si no hay duplicados)
4. El estado por defecto será **Borrador**.

> **Nota**: El nombre de la factura se genera automáticamente a partir del nombre del cliente y la fecha/hora.

### 4.3 Enviar una Factura a AEAT

El envío a AEAT es una **acción explícita** del usuario. No se realiza automáticamente al guardar la factura.

1. Abra la factura en **Vista Detalle**.
2. Haga clic en el botón **Enviar a AEAT**.
3. Confirme la acción si la factura aún está en estado **Borrador**.
4. El sistema marcará la factura como **Emitida** y la enviará a AEAT.

> **Nota**: El estado de la factura cambia a **Emitida** como consecuencia del envío. No es necesario cambiar el estado manualmente antes de enviar.

### 4.4 Resultado del Envío

Tras el envío, la factura mostrará:

| Campo | Descripción |
|-------|-------------|
| **Estado AEAT** | `Enviado y Aceptado`, `Error de envío`, `Anulada en AEAT` |
| **CSV** | Código Seguro de Verificación asignado por AEAT |
| **Hash** | Huella digital SHA-256 del registro |
| **URL QR** | Enlace de verificación (visible si fue aceptada) |
| **Fecha de envío** | Fecha y hora del envío a AEAT |

### 4.5 Reenviar una Factura Rechazada

Si una factura fue **rechazada** por AEAT:

1. Edite la factura para corregir los datos necesarios (dentro de lo permitido).
2. Vuelva a pulsar el botón **Enviar a AEAT** en la Vista Detalle.
3. El sistema permitirá el reenvío.

> **Nota**: Las facturas **aceptadas** no pueden reenviarse. Si necesita modificar una factura aceptada, debe **anularla** y crear una rectificativa.

---

## 5. Facturas Rectificativas

### 5.1 ¿Cuándo crear una rectificativa?

Una factura rectificativa se usa para corregir una factura ya emitida y aceptada por AEAT.

### 5.2 Cómo crear una rectificativa

1. Abra la factura original en **Vista Detalle**.
2. Haga clic en el botón **Crear Factura Rectificativa**.
3. El sistema creará una nueva factura con:
   - Los mismos datos de cliente y líneas
   - El flag **"¿Es rectificativa?"** activado
   - La serie rectificativa asignada automáticamente
   - Referencia a la factura original
4. Modifique los importes o datos necesarios.
5. Pulse el botón **Enviar a AEAT** para enviar la factura.

### 5.3 Campos de Rectificación

| Campo | Valores | Descripción |
|-------|---------|-------------|
| **Tipo de rectificación** | `S` (Sustitución) / `I` (Diferencias) | Indica si anula y reemplaza o solo corrige diferencias |
| **Base legal** | `R1` a `R5` | Artículo de la LIVA que ampara la rectificación |
| **Factura rectificada** | Relación | Enlace a la factura original |
| **Fecha de expedición rectificada** | Fecha | Fecha de la factura original |

### 5.4 Tipos de Base Legal (R1-R5)

| Código | Descripción |
|--------|-------------|
| **R1** | Error fundado en derecho (Art. 80.1, 80.2, 80.6 LIVA) |
| **R2** | Concurso de acreedores (Art. 80.3 LIVA) |
| **R3** | Crédito incobrable (Art. 80.4 LIVA) |
| **R4** | Otros casos |
| **R5** | Rectificativa simplificada |

---

## 6. Anulación de Facturas

### 6.1 ¿Cuándo anular?

La anulación se usa cuando una factura aceptada por AEAT debe ser invalidada completamente (no corregida).

### 6.2 Cómo anular una factura

1. Abra la factura en **Vista Detalle**.
2. Verifique que el estado AEAT sea **Enviado y Aceptado**.
3. Haga clic en el botón **Anular Factura**.
4. Confirme la acción.
5. El sistema enviará un **Registro de Anulación** a AEAT.

### 6.3 Requisitos para anular

- La factura debe estar **aceptada** por AEAT.
- La factura **no puede ser rectificativa**.

### 6.4 Tras la anulación

- El estado AEAT cambia a **Anulada en AEAT**.
- Se genera un **hash de anulación** independiente.
- La cadena de hashes continúa correctamente para las siguientes facturas.

---

## 7. Series de Facturación

### 7.1 ¿Qué es una serie?

Una serie de facturación define el formato de numeración de las facturas. Ejemplos:

- `YYYY-0000` → `2026-0001`, `2026-0002`, ...
- `RECT-YYYY-0000` → `RECT-2026-0001`, `RECT-2026-0002`, ...

### 7.2 Series por defecto

Al acceder a facturas por primera vez, el sistema crea automáticamente:

| Nombre | Formato | Tipo |
|--------|---------|------|
| **Factura normal** | `YYYY-0000` | Normal |
| **Factura rectificativa** | `RECT-YYYY-0000` | Rectificativa |

### 7.3 Configurar series personalizadas

1. Vaya a **Administración → Configuración AOS**.
2. Localice la sección **Series de Facturas**.
3. Para cada serie, indique:
   - **Nombre**: Identificador de la serie
   - **Formato**: Patrón de numeración (ver placeholders abajo)
   - **Número inicial**: Primer número de la serie
   - **Es rectificativa**: Marque si es una serie para rectificativas
4. Guarde los cambios.

### 7.4 Placeholders válidos en el formato

| Placeholder | Significado | Ejemplo |
|-------------|-------------|---------|
| `YYYY` | Año con 4 dígitos | `2026` |
| `YY` | Año con 2 dígitos | `26` |
| `0000` | Número secuencial (4 dígitos) | `0001`, `0002` |
| `000` | Número secuencial (3 dígitos) | `001`, `002` |

### 7.5 Reglas de formato

- Solo se permiten: **A-Z**, **0-9**, guión (`-`), guión bajo (`_`), barra (`/`), punto (`.`)
- **No** se permiten minúsculas
- **No** se permite empezar con espacio
- Longitud máxima combinada (serie + número): **60 caracteres**

### 7.6 Restricciones importantes

- **No se puede modificar** el formato de una serie si ya tiene facturas aceptadas por AEAT.
- **No se puede eliminar** una serie con facturas aceptadas.
- Las series normales y rectificativas deben ser **independientes** (no mezclar).
- Al cambiar el checkbox **"¿Es rectificativa?"**, el desplegable de series se filtra automáticamente para mostrar solo las series compatibles.

---

## 8. Restricciones de Edición

### 8.1 Facturas aceptadas por AEAT

Una vez que una factura es **aceptada** por AEAT:

- **No se pueden editar** los campos tributarios (importes, cliente, fecha, serie, número).
- **Solo se pueden editar**: Descripción, usuario asignado y notas.
- **No se puede eliminar** la factura.
- Los botones de edición y eliminación se deshabilitan visualmente.
- Se muestra un **banner de advertencia** en la vista detalle.

### 8.2 Facturas enviadas (no aceptadas aún)

- **No se puede editar** la factura (se redirige a Vista Detalle).
- **No se puede eliminar**.
- El botón "Enviar a AEAT" permanece habilitado para permitir reenvío si fue rechazada.

### 8.3 Cambio de estado desde Borrador

Desde el estado **Borrador**, solo se permite cambiar a **Emitida** (que se produce al pulsar "Enviar a AEAT"). No se permite saltar directamente a Pagada, No pagada o Cancelada. El desplegable de estado muestra las opciones no permitidas deshabilitadas.

---

## 9. Código QR de Verificación

### 9.1 ¿Cuándo se genera?

El código QR se genera automáticamente cuando la factura es **aceptada** por AEAT.

### 9.2 ¿Dónde se usa?

- El campo `verifactu_check_url_c` contiene la URL de verificación.
- Las **plantillas PDF** adaptadas incluyen automáticamente el QR en la factura impresa.

### 9.3 Verificación

El QR permite a cualquier persona verificar la autenticidad de la factura en los sistemas de la AEAT.

---

## 10. Log de Auditoría

Cada factura dispone de un campo **Log de Auditoría** (`verifactu_audit_log_c`) que registra:

- Fecha y hora de envío a AEAT
- Número de factura asignado
- Hash generado
- Estado (aceptado/rechazado)
- URL del QR (si aceptado)
- Operaciones de anulación
- Creación de rectificativas (tanto en la original como en la nueva)

Este campo es **solo lectura** y no se pierde al duplicar la factura.

---

## 11. Duplicar Facturas

Al duplicar una factura:

- El **número se reinicia** (no se arrastra el número original).
- Todos los campos de Verifactu se **limpian** (hash, estado, QR, etc.).
- El estado se establece a **Borrador**.
- El campo `description` se limpia (los logs van al campo de auditoría).

---

## 12. Validaciones Automáticas

El sistema realiza las siguientes validaciones:

| Validación | Cuándo | Descripción |
|------------|--------|-------------|
| **Cliente obligatorio** | Al guardar | Debe haber una Organización o Persona seleccionada |
| **NIF del cliente** | Al guardar | El cliente debe tener NIF informado |
| **Cronología por serie** | Al enviar | La fecha no puede ser anterior a la última factura aceptada de la misma serie |
| **Correlatividad numérica** | Al enviar | El número se genera de forma correlativa |
| **Coherencia tipo/serie** | Al guardar | Una rectificativa debe usar una serie rectificativa |
| **Longitud máxima** | Al guardar | Serie + número no pueden superar 60 caracteres |
| **Rectificativa no vacía** | Al enviar | Debe tener líneas de producto e importe > 0 |
| **NIF informado** | Al enviar | El cliente debe tener NIF antes del envío a AEAT |

---

## 13. Preguntas Frecuentes

### ¿Puedo editar una factura después de enviarla a AEAT?

No, una vez enviada y aceptada, los campos tributarios quedan bloqueados por normativa. El envío se realiza pulsando el botón "Enviar a AEAT" en la vista detalle.

### ¿Qué pasa si AEAT rechaza mi factura?

Puede corregir los datos y reenviarla. El sistema permite reenvíos de facturas rechazadas.

### ¿Puedo cambiar la serie de una factura?

Solo si la factura **no ha sido enviada** a AEAT. Una vez enviada, la serie es inmutable.

### ¿Cómo sé si Verifactu está activado?

Al abrir una factura, si ve un panel con información de **Estado AEAT**, Verifactu está activado. Si no lo ve, está en modo Legacy.

### ¿Qué certificado debo usar?

Un certificado digital reconocido por la AEAT, ya sea sello de entidad o certificado de representante.

### ¿Se reinicia la numeración cada año?

Si el formato de la serie incluye `YYYY` o `YY`, la numeración se reinicia automáticamente cada año. Si no incluye año, el sistema filtra por ejercicio fiscal para garantizar la unicidad.

---

## 14. Glosario

| Término | Significado |
|---------|-------------|
| **AEAT** | Agencia Estatal de Administración Tributaria |
| **Verifactu** | Sistema de verificación de facturas de la AEAT |
| **SIF** | Sistema Informático de Facturación |
| **CSV** | Código Seguro de Verificación |
| **Hash** | Huella digital SHA-256 que encadena los registros |
| **RegistroAlta** | Envío de factura normal o rectificativa a AEAT |
| **RegistroAnulacion** | Solicitud de anulación de factura registrada |
| **Rectificativa** | Factura que corrige una factura previamente emitida |
| **Legacy** | Modo de funcionamiento sin integración Verifactu |
