# Manual Técnico - Verifactu (AEAT) en SinergiaCRM

> **Versión**: 1.0  
> **Fecha**: Mayo 2026  
> **Rama**: `feature/verifactu`  
> **Destinatarios**: Administradores, programadores, IA de desarrollo

---

## 1. Arquitectura General

### 1.1 Descripción

La integración Verifactu conecta SinergiaCRM con la AEAT para el envío de facturas electrónicas según la normativa Veri*factu (Ley 11/2021). El envío a AEAT se realiza de forma **explícita** mediante la acción del usuario (botón "Enviar a AEAT" en la vista detalle). El sistema implementa:

- Envío de registros de alta (`RegistrationRecord`) y anulación (`CancellationRecord`)
- Encadenamiento de hashes SHA-256 entre registros
- Generación de QR de verificación
- Gestión de certificados digitales con encriptación
- Numeración flexible por series configurables
- Feature flag para modo legacy/Verifactu

### 1.2 Librería Principal

Se utiliza la librería **`josemmo/verifactu-php`** (instalada vía Composer en `SticInclude/vendor/`).

| Paquete | Propósito |
|---------|-----------|
| `josemmo/verifactu-php` | Modelos, AeatClient, QrGenerator |
| `guzzlehttp/guzzle` | Cliente HTTP para comunicación con AEAT |
| `josemmo/uxml` | Manipulación XML |
| `symfony/validator` | Validaciones (desactivada por incompatibilidad con Symfony 3.4 del CRM) |

### 1.3 Autoloader

El autoloader de Composer se registra con `prepend=true` para priorizar las dependencias de verifactu-php (Symfony 7.x) sobre las versiones legacy del CRM (Symfony 3.4):

```php
// custom/modules/AOS_Invoices/SticUtils.php:25-29
$loader = require __DIR__ . '/../../../SticInclude/vendor/autoload.php';
if ($loader instanceof \Composer\Autoload\ClassLoader) {
    $loader->unregister();
    $loader->register(true); // Prepend
}
```

> **Nota para IA**: La validación estructural de la librería está desactivada (`$record->validate()` comentado) debido a la incompatibilidad con Symfony Validator 3.4. La validación se delega al servidor de AEAT.

---

## 2. Estructura de Archivos

### 2.1 Archivos Principales

| Archivo | Función |
|---------|---------|
| `custom/modules/AOS_Invoices/SticUtils.php` | Clase principal con toda la lógica de negocio Verifactu |
| `custom/modules/AOS_Invoices/SticLogicHooksCode.php` | Logic hooks (before_save, after_save, before_delete) |
| `custom/modules/AOS_Invoices/controller.php` | Controller personalizado con acciones: sendToAEAT, CreateRectifiedInvoice, CancelInvoice |
| `custom/modules/AOS_Invoices/SticUtils.js` | JavaScript para validaciones y UI en EditView/DetailView |
| `custom/include/SticCertificateUtils.php` | Gestión de certificados digitales (almacenamiento en BD) |
| `custom/Extension/modules/AOS_Invoices/Ext/Vardefs/SticVardefs.php` | Definición de campos personalizados |
| `custom/modules/Administration/views/view.sticmanagecertificate.php` | Vista de gestión de certificados |
| `modules/Administration/AOSAdmin.php` | Gestión de series de facturación en administración |

### 2.2 Archivos de Migración e Instalación

| Archivo | Función |
|---------|---------|
| `SticUpdates/Migrations/20260518_feature_verifactu.sql` | Migración de campos en fields_meta_data + limpieza de duplicados |
| `SticUpdates/Languages/es/20260518_feature_verifactu.sql` | Strings de idioma y stic_settings |
| `SticInstall/sql/es/Settings.sql` | Configuración de settings del sistema |

### 2.3 Vistas Personalizadas

| Archivo | Función |
|---------|---------|
| `custom/modules/AOS_Invoices/views/view.detail.php` | Banner de advertencia, estado Verifactu, carga de JS |
| `custom/modules/AOS_Invoices/views/view.edit.php` | Extensiones de quicksearch para direcciones |
| `custom/modules/AOS_Invoices/views/view.list.php` | Configuración de columnas de lista |

---

## 3. Base de Datos

### 3.1 Campos en `aos_invoices_cstm`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `verifactu_hash_c` | varchar(64) | Hash SHA-256 del RegistrationRecord |
| `verifactu_previous_hash_c` | varchar(64) | Hash del registro anterior en la cadena |
| `verifactu_check_url_c` | text | URL de verificación QR |
| `verifactu_aeat_status_c` | enum | pending / accepted / rejected / cancelled |
| `verifactu_aeat_response_c` | varchar(255) | Respuesta literal de AEAT |
| `verifactu_csv_c` | varchar(50) | Código Seguro de Verificación |
| `verifactu_submitted_at_c` | datetime | Fecha/hora de envío |
| `stic_invoice_type_c` | enum | Serie de facturación (clave del config) |
| `verifactu_is_rectified_c` | bool | Flag de rectificativa |
| `verifactu_rectified_type_c` | enum | S (sustitución) / I (diferencias) |
| `verifactu_rectified_base_c` | enum | R1-R5 (base legal LIVA) |
| `verifactu_rectified_date_c` | date | Fecha de la factura original rectificada |
| `verifactu_cancel_id_c` | char(36) | ID de factura relacionada (anulación/rectificación) |
| `verifactu_cancel_name_c` | relate | Nombre para navegación UI |
| `verifactu_cancel_hash_c` | varchar(64) | Hash del CancellationRecord |
| `verifactu_audit_log_c` | text | Log técnico de operaciones |
| `verifactu_previous_status_c` | varchar(20) | Estado previo (creado, sin uso activo) |

### 3.2 Campos en `aos_products_quotes_cstm`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `verifactu_aeat_operation_type_c` | enum | Tipo de operación AEAT por línea (S/E/N/NL) |

### 3.3 Campo `number` en `aos_invoices`

El campo `number` fue alterado de `INT` a `VARCHAR(50)` para soportar formatos alfanuméricos:

```php
// custom/Extension/modules/AOS_Invoices/Ext/Vardefs/SticVardefs.php:54-56
$dictionary['AOS_Invoices']['fields']['number']['type'] = 'varchar';
$dictionary['AOS_Invoices']['fields']['number']['len'] = 50;
```

### 3.4 Índice Único

Se define un índice único para número de factura en `SticVardefs.php` (creado por `SticRefresh`):

```php
'indices' => [
    [
        'name' => 'idx_invoice_number_unique',
        'type' => 'unique',
        'fields' => ['number', 'stic_invoice_type_c', 'deleted'],
    ],
],
```

### 3.5 Certificado Digital en BD

Los certificados se almacenan en la tabla `config` con categoría `SticCertificates`:

| name | Formato | Encriptado |
|------|---------|------------|
| `private_key` | PEM | Sí (Blowfish + base64) |
| `certificate` | PEM | Sí (Blowfish + base64) |
| `ca_chain` | PEM | Sí (Blowfish + base64) |
| `metadata` | JSON | No |

La clave de encriptación es `$sugar_config['unique_key']` (Crypt_Blowfish).

### 3.6 Settings (stic_settings)

| Clave | Categoría | Valor por defecto | Descripción |
|-------|-----------|-------------------|-------------|
| `VERIFACTU_ACTIVATED` | Verifactu | `0` | 0=Legacy, 1=Verifactu activo |
| `VERIFACTU_TEST` | Verifactu | `1` | 0=Producción, 1=Preproducción |
| `VERIFACTU_TAX_TYPE` | Verifactu | `01` | 01=IVA, 02=IPSI, 03=IGIC |

---

## 4. Feature Flag: VERIFACTU_ACTIVATED

### 4.1 Implementación

```php
// custom/modules/AOS_Invoices/SticUtils.php:64-70
public static function isVerifactuActivated()
{
    require_once 'modules/stic_Settings/Utils.php';
    $setting = stic_SettingsUtils::getSetting('VERIFACTU_ACTIVATED');
    return ($setting == 1 || $setting === '1');
}
```

### 4.2 Comportamiento Condicional

| Componente | Legacy (0) | Verifactu (1) |
|------------|-----------|---------------|
| `after_save` hook | Genera número en `before_save` | Dispara `sendToAeat()` cuando el botón de envío cambia el estado a `emitted` |
| `sendToAeat()` | No se ejecuta | Se ejecuta solo vía acción explícita del usuario |
| `sendCancellationToAeat()` | No se ejecuta | Envía anulación |
| Validaciones | No se aplican | Todas activas |
| Panel AEAT en UI | Oculto | Visible |
| Numeración | En `before_save` | En `sendToAeat()` (solo si accepted) |

### 4.3 Banner de Advertencia

Si `VERIFACTU_ACTIVATED=0` pero hay certificado configurado, se muestra un banner informativo en `view.detail.php` con enlace a `stic_Settings`.

---

## 5. Lógica de Negocio

### 5.1 Envío a AEAT (`sendToAeat`)

**Ubicación**: `SticUtils.php:397-1190`

> **Nota**: Este método se invoca exclusivamente como consecuencia de la acción explícita del usuario (botón "Enviar a AEAT"). No hay ningún mecanismo de envío automático.

#### Flujo de Ejecución

1. **Guard de feature flag**: Si `!isVerifactuActivated()`, retorna sin hacer nada.
2. **Guard de re-entrada**: Usa `self::$processingInvoiceIds` para evitar recursión infinita (el `save()` interno dispara `after_save`).
3. **Validación de estado**: Solo envía si `status === 'emitted'` y `aeat_status !== 'accepted'`.
4. **Carga de certificado**: `SticCertificateUtils::getCertificateComponents()`.
5. **Extracción de NIF/nombre**: `getCertificateNif()`, `getCertificateHolderName()`.
6. **Generación de número**: `generateNextInvoiceNumber()` (en memoria, no se persiste hasta aceptación).
7. **Construcción del ComputerSystem**: `buildComputerSystem()` con valores SIF hardcodeados.
8. **Creación de AeatClient**: Con certificado temporal en archivo.
9. **Construcción de RegistrationRecord**: Con datos de factura, desglose fiscal, encadenamiento.
10. **Envío a AEAT**: `$client->send([$record])->wait()`.
11. **Procesamiento de respuesta**:
    - Si `Correcto` o `AceptadoConErrores`: `aeat_status = 'accepted'`, genera QR.
    - Si otro estado: `aeat_status = 'rejected'`.
12. **Persistencia**: Guarda hash, CSV, respuesta, fecha, número (solo si accepted).
13. **Log de auditoría**: Registra operación en `verifactu_audit_log_c`.

#### Guard de Re-entrada

```php
// SticUtils.php:408-414
$invoiceId = $invoiceBean->id;
if (isset(self::$processingInvoiceIds[$invoiceId])) {
    $GLOBALS['log']->warn('Re-entry detected for invoice ' . $invoiceId . ', skipping.');
    return;
}
self::$processingInvoiceIds[$invoiceId] = true;
```

El `finally` block limpia el registro:
```php
unset(self::$processingInvoiceIds[$invoiceId]);
```

### 5.2 Encadenamiento de Hashes

**Método**: `getPreviousInvoice()` en `SticUtils.php:1198-1260`

La cadena de hashes es **global por instancia CRM** (no por serie). La query busca la factura más reciente con `verifactu_hash_c IS NOT NULL`, ordenada por `verifactu_submitted_at_c DESC, invoice_date DESC, number DESC`.

Para facturas anuladas, usa `verifactu_cancel_hash_c` en lugar de `verifactu_hash_c`:

```php
if ($row['verifactu_aeat_status_c'] === 'cancelled' && !empty($row['verifactu_cancel_hash_c'])) {
    $invoice->verifactu_hash_c = $row['verifactu_cancel_hash_c'];
}
```

### 5.3 Anulación (`sendCancellationToAeat`)

**Ubicación**: `SticUtils.php:1868-2079`

1. Valida que la factura esté `accepted`.
2. Crea un `CancellationRecord` con el identificador de la factura a anular.
3. Envía a AEAT.
4. Actualiza la factura:
   - `verifactu_cancel_hash_c` = hash del CancellationRecord
   - `verifactu_aeat_status_c` = `'cancelled'`
   - `verifactu_csv_c` = CSV de anulación
5. Preserva el hash original (`verifactu_hash_c` no se sobrescribe).

### 5.4 Generación de Números

**Método**: `generateNextInvoiceNumber()` en `SticUtils.php:1367-1505`

#### Parámetros

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `$seriesConfigKey` | string | Clave del config (ej: `Factura normal`) |
| `$bean` | SugarBean | Bean de la factura |
| `$seriesDbValue` | string|null | Valor para query DB (si difiere del config key) |
| `$filterByAeatStatus` | bool | `true` (Verifactu): solo accepted/rejected. `false` (legacy): todas |

#### Algoritmo

1. Obtiene formato y número inicial del config.
2. Detecta si el formato contiene `YYYY` o `YY`.
3. Busca el último número con el mismo patrón (LIKE).
4. Si hay formato sin año, añade `YEAR(invoice_date) = $year` para reinicio anual.
5. Para formatos sin año, verifica unicidad con loop (máx 1000 intentos).
6. Construye el número con `buildInvoiceNumber()`.

> **Nota para IA**: El número se genera **en memoria** durante `sendToAeat()`. Solo se persiste en `$bean->number` si la respuesta de AEAT es `accepted` (línea 1162).

### 5.5 Configuración de Series

**Ubicación**: `config_override.php` como array PHP

```php
$sugar_config['aos']['invoices']['series']['Factura normal']['format'] = 'YYYY-0000';
$sugar_config['aos']['invoices']['series']['Factura normal']['initialNumber'] = 1;
$sugar_config['aos']['invoices']['series']['Factura normal']['isRectified'] = false;
```

#### Auto-creación (`ensureDefaultSeries`)

**Ubicación**: `SticUtils.php:2110-2214`

Se ejecuta en `preDisplay()` de todas las vistas de facturas. Si no existen "Factura normal" o "Factura rectificativa", las crea insertando líneas en `config_override.php` antes del segundo marcador `/***CONFIGURATOR***/` usando `substr_replace`.

#### Validación de Formato (`validateSeriesFormat`)

**Ubicación**: `SticUtils.php:1519-1550`

- Regex: `/[^A-Z0-9\-_\/. ]/` → caracteres inválidos
- No minúsculas: `/[a-z]/`
- No espacio al inicio: `/^ /`
- Máximo 4 dígitos en placeholder numérico

#### Bloqueo de Modificación (`canModifySeriesFormat`)

**Ubicación**: `SticUtils.php:1654-1678`

Consulta `COUNT(*)` de facturas con `verifactu_aeat_status_c = 'accepted'` para la serie. Si hay alguna, bloquea el cambio.

---

## 6. Logic Hooks

### 6.1 `before_save`

**Ubicación**: `SticLogicHooksCode.php:26-423`

| Validación/Acción | Líneas | Descripción |
|-------------------|--------|-------------|
| Limpieza número en duplicado | 32-40 | Reset de `number` al duplicar |
| Generación número legacy | 46-57 | Si `!isVerifactuActivated()` |
| Validación NIF cliente | 60-76 | Cuenta debe tener `stic_identification_number_c` |
| Limpieza direcciones | 78-89 | Si no hay cliente seleccionado |
| Bloqueo draft→otro | 92-107 | Solo permite draft→emitted |
| Bloqueo edición aceptada | 110-186 | Solo campos no tributarios editables |
| Validación cronología | 188-218 | Por serie, excluye anuladas |
| Validación tipo serie | 220-243 | Coherencia rectificada/normal |
| Validación longitud 60 | 245-265 | serie + número ≤ 60 |
| Regenerar número al cambiar serie | 267-301 | Reset si no enviada |
| Limpieza Verifactu al duplicar | 304-325 | Reset de todos los campos |
| Validación rectificativa | 327-371 | Campos obligatorios |
| Auto-asignar serie | 374-396 | Primera serie disponible |
| Auto-generar nombre | 398-415 | Cliente + fecha/hora |

### 6.2 `after_save`

**Ubicación**: `SticLogicHooksCode.php:426-458`

> **Importante**: Este hook se dispara como consecuencia de la acción explícita del usuario (botón "Enviar a AEAT"). El controller `action_sendToAEAT` cambia el estado a `emitted` y guarda, lo que dispara el hook. No hay envío automático al guardar una factura.

```php
public function after_save($bean, $event, $arguments)
{
    if (!AOS_InvoicesUtils::isVerifactuActivated()) return;
    if ($bean->status !== 'emitted') return;
    if ($bean->verifactu_aeat_status_c === 'accepted') return;
    // Permitir reenvío si rejected
    if (!empty($bean->fetched_row['status']) && $bean->fetched_row['status'] === 'emitted' 
        && $bean->verifactu_aeat_status_c !== 'rejected') return;
    
    AOS_InvoicesUtils::sendToAeat($bean);
}
```

> **Nota clave**: La condición `&& $bean->verifactu_aeat_status_c !== 'rejected'` (línea 449) permite reenviar facturas rechazadas tras corregir datos.

### 6.3 `before_delete`

**Ubicación**: `SticLogicHooksCode.php:461-490`

Bloquea eliminación si `verifactu_aeat_status_c` es `accepted` o `emitted`.

---

## 7. Controller Personalizado

**Ubicación**: `custom/modules/AOS_Invoices/controller.php`

### 7.1 `action_sendToAEAT`

Este es el punto de entrada principal para el envío de facturas a AEAT. Se invoca cuando el usuario pulsa el botón "Enviar a AEAT" en la vista detalle.

- Verifica `VERIFACTU_ACTIVATED`.
- Si `set=emitted`: cambia el estado a `emitted` y guarda (el `after_save` hook dispara `sendToAeat()`).
- Si no: llama directamente a `sendToAeat()`.
- Redirige a la vista detalle de la factura.

### 7.2 `action_CreateRectifiedInvoice`

- Valida que la original tenga `verifactu_submitted_at_c`.
- Valida que tenga datos de cliente (requerido para R1).
- Cueva nueva factura con flag `verifactu_is_rectified_c = true`.
- Copia líneas de producto (incluyendo custom fields de `aos_products_quotes_cstm`).
- Asigna serie rectificativa automáticamente.
- Escribe logs de auditoría en ambas facturas.
- Usa UPDATE directo en BD para description y audit_log (evita bloqueo de `before_save` en aceptadas).

### 7.3 `action_CancelInvoice`

- Valida que la factura esté `accepted`.
- Llama a `sendCancellationToAeat()`.
- Muestra resultado.

### 7.4 `action_edit`

Bloquea edición si `verifactu_submitted_at_c` no está vacío.

---

## 8. Certificado Digital

### 8.1 Clase `SticCertificateUtils`

**Ubicación**: `custom/include/SticCertificateUtils.php`

#### Métodos Principales

| Método | Función |
|--------|---------|
| `saveCertificateToConfig()` | Guarda componentes en tabla `config` con encriptación Blowfish |
| `loadCertificateFromConfig()` | Carga y desencripta componentes |
| `getCertificateComponents()` | Retorna array con `private_key`, `certificate`, `ca_chain` (PEM) |
| `getCertificateNif()` | Extrae NIF del certificado (prioriza `organizationIdentifier`) |
| `getCertificateHolderName()` | Extrae nombre (prioriza campo `O`, luego `CN`) |
| `isEntitySeal()` | Determina tipo: 1=sello entidad, 0=representante |
| `certificateExists()` | Verifica existencia (config o fallback archivos) |

### 8.2 Extracción de NIF

Prioridad de búsqueda:

1. `organizationIdentifier` (OID 2.5.4.97) — Limpia prefijos `VATES-`, `IDCES-`
2. `serialNumber` — Limpia prefijos `IDCES-`, `VATES-`, `ES`
3. `CN` — Regex `/([A-Z]?\d{7,8}[A-Z])/i`

### 8.3 Determinación de Tipo de Certificado

| Criterio | Resultado |
|----------|-----------|
| Tiene campos `GN` o `SN` | Representante (0) |
| Tiene `O` pero no `GN`/`SN` | Sello de entidad (1) |
| `CN` contiene "SELLO DE ENTIDAD", "S.A", "S.L" | Sello de entidad (1) |
| `CN` contiene "REPRESENTANTE", "PERSONA FISICA" | Representante (0) |

### 8.4 Fallback a Archivos

Si no encuentra certificado en BD, intenta cargar desde `custom/certificates/`:

- `private_key_encrypted.bin`
- `certificate_encrypted.bin`
- `ca_chain_encrypted.bin`
- `cert_metadata.json`

---

## 9. JavaScript

**Ubicación**: `custom/modules/AOS_Invoices/SticUtils.js`

### 9.1 Validaciones en EditView

| Validación | Descripción |
|------------|-------------|
| `billing_account_id` | Requiere Organización o Persona |
| `customer_id_number` | Cliente debe tener NIF |
| Fechas | Coherencia entre `invoice_date` y `due_date` |

### 9.2 Filtrado de Series

`filterSeriesDropdown()` oculta opciones incompatibles con el checkbox `verifactu_is_rectified_c`.

### 9.3 Bloqueo de Estado

Si el estado original es `draft`, deshabilita todas las opciones del dropdown excepto `draft` y `emitted`.

### 9.4 DetailView

Crea botones dinámicos:

| Botón | Condición de habilitación |
|-------|--------------------------|
| **Enviar a AEAT** | Deshabilitado si `accepted` |
| **Crear Rectificativa** | Habilitado si `emitted`, `Paid` o `Unpaid` |
| **Anular Factura** | Solo si `accepted` |

### 9.5 Modo Legacy

Oculta el panel AEAT si `verifactuActivated === false`.

---

## 10. SIF (Sistema Informático de Facturación)

### 10.1 Valores Hardcodeados

**Método**: `buildComputerSystem()` en `SticUtils.php:341-360`

| Campo | Valor |
|-------|-------|
| `systemName` | `'SinergiaCRM'` |
| `systemId` | `'SC'` |
| `systemVersion` | `$sugar_config['sinergiacrm_version']` |
| `installationNumber` | `'001'` |

> **Nota para IA**: Estos valores son fijos. Si en el futuro se necesitan configurables, añadir settings `VERIFACTU_SIF_NAME`, `VERIFACTU_SIF_ID`, `VERIFACTU_SIF_INSTALLATION`.

### 10.2 Modo Test: IDType=07

En modo test (`VERIFACTU_TEST=1`), los destinatarios con NIF personal (empieza con número) se envían como `ForeignFiscalIdentifier` con `ForeignIdType::Unregistered (07)` para evitar error 1239 de censo AEAT:

```php
// SticUtils.php:246-254
private static function createRecipientIdentifier($name, $nif)
{
    $isTestMode = stic_SettingsUtils::getSetting('VERIFACTU_TEST') == '1';
    $isPersonalNif = preg_match('/^[0-9]/', $nif);
    if ($isTestMode && $isPersonalNif) {
        return new ForeignFiscalIdentifier($name, 'ES', ForeignIdType::Unregistered, $nif);
    }
    return new FiscalIdentifier($name, $nif);
}
```

---

## 11. Plantillas PDF

El parser de plantillas PDF (`modules/AOS_PDF_Templates/templateParser.php`) detecta el campo `verifactu_check_url_c` para incluir el QR en las facturas impresas.

---

## 12. Subpanel de Facturas

El subpanel por defecto (`modules/AOS_Invoices/metadata/subpanels/default.php`) muestra el estado de Verifactu AEAT en la lista de facturas relacionadas.

---

## 13. CSS/Theming

Se han añadido estilos en los temas SuiteP para los iconos de estado Verifactu:

- `themes/SuiteP/css/Dawn/style.css`
- `themes/SuiteP/css/Day/style.css`
- `themes/SuiteP/css/Dusk/style.css`
- `themes/SuiteP/css/Night/style.css`
- `themes/SuiteP/css/Stic/style.css`
- `themes/SuiteP/css/SticCustom/style.css`

---

## 14. Flujo de Ejecución Completo

### 14.1 Creación y Envío de Factura Normal

```
1. Usuario crea factura → status = 'draft'
2. before_save:
   - Asigna serie por defecto si está vacía
   - Genera nombre automático si está vacío
   - Valida NIF del cliente
3. Usuario pulsa botón "Enviar a AEAT" en DetailView
4. Controller action_sendToAEAT:
   - Cambia status a 'emitted' y guarda
5. after_save (disparado por el save del controller):
   - Detecta status = 'emitted'
   - Llama sendToAeat()
6. sendToAeat():
   - Genera número correlativo (en memoria)
   - Construye RegistrationRecord
   - Envía a AEAT
   - Si accepted:
     * Guarda número en bean
     * Guarda hash, CSV, QR URL
     * Escribe audit log
   - Si rejected:
     * Guarda estado rejected
     * Permite reenvío posterior (botón habilitado)
7. Usuario ve resultado en DetailView
```

### 14.2 Creación de Factura Rectificativa

```
1. Usuario abre factura original → action=CreateRectifiedInvoice
2. Controller valida verifactu_submitted_at_c
3. Cueva nueva factura con:
   - verifactu_is_rectified_c = true
   - verifactu_cancel_id_c = ID original
   - Serie rectificativa automática
   - Líneas copiadas (incluyendo custom fields)
4. before_save en nueva factura:
   - Valida coherencia tipo/serie
   - Asigna serie si está vacía
5. Usuario pulsa "Enviar a AEAT" en la nueva factura
6. Audit log se escribe en ambas facturas
```

### 14.3 Anulación

```
1. Usuario abre factura aceptada → action=CancelInvoice
2. Controller valida status = 'accepted'
3. sendCancellationToAeat():
   - Crea CancellationRecord
   - Envía a AEAT
   - Guarda cancel_hash_c (no sobrescribe hash_c)
   - Cambia status a 'cancelled'
4. getPreviousInvoice() usa cancel_hash_c para encadenamiento
```

---

## 15. Puntos de Extensión para IA

### 15.1 Añadir Nuevo Campo Verifactu

1. Añadir vardef en `custom/Extension/modules/AOS_Invoices/Ext/Vardefs/SticVardefs.php`
2. Añadir entrada en `fields_meta_data` (migration SQL)
3. Añadir string de idioma en `custom/Extension/modules/AOS_Invoices/Ext/Language/es_ES.SticLang.php`
4. Añadir en metadata de vistas (detailviewdefs, editviewdefs, listviewdefs)
5. Ejecutar `SticRefresh` para crear columna en BD
6. Limpiar cache

### 15.2 Añadir Nueva Validación

1. Crear método en `AOS_InvoicesUtils` (SticUtils.php)
2. Llamar desde `before_save` en `SticLogicHooksCode.php`
3. Usar `getStyledErrorAlert()` para mensajes
4. Añadir string de idioma

### 15.3 Modificar SIF

Editar `buildComputerSystem()` en `SticUtils.php:341-360`.

### 15.4 Añadir Nuevo Tipo de Registro AEAT

1. Usar clases del namespace `josemmo\Verifactu\Models\Records\`
2. Crear método en `AOS_InvoicesUtils`
3. Seguir patrón de `sendToAeat()` o `sendCancellationToAeat()`

---

## 16. Comandos de Mantenimiento

### 16.1 Limpiar Cache

```bash
rm -rf cache/*
```

### 16.2 Verificar Sintaxis PHP

```bash
php -l custom/modules/AOS_Invoices/SticUtils.php
php -l custom/modules/AOS_Invoices/SticLogicHooksCode.php
```

### 16.3 Regenerar Vardefs

```bash
# Ejecutar SticRefresh desde la interfaz de administración
# O vía CLI si disponible
```

### 16.4 Verificar Estado de Series

```sql
SELECT * FROM config WHERE category = 'SticCertificates';
SELECT name, value FROM config WHERE name LIKE 'VERIFACTU_%';
```

---

## 17. Depuración

### 17.1 Logs

Los logs se escriben en `suitecrm.log` con niveles:

| Nivel | Uso |
|-------|-----|
| `debug` | Querys, valores intermedios, flujo de control |
| `info` | Operaciones completadas, envío a AEAT |
| `warn` | Situaciones anómalas no bloqueantes |
| `error` | Errores que bloquean la operación |

### 17.2 Modo Debug (Desactivado)

El bloque debug en `sendToAeat()` (líneas 962-1050) está desactivado con `if (false)`. Para activarlo temporalmente, cambiar a `if (true)`.

### 17.3 Response de AEAT

El método `formatAeatResponse()` y `formatAeatError()` formatean la respuesta para visualización en UI.

---

## 18. Consideraciones de Seguridad

### 18.1 Certificado

- Encriptación Blowfish con `$sugar_config['unique_key']`
- Fallback a archivos con `.htaccess` de denegación
- Archivo temporal PEM se crea en `sys_get_temp_dir()` y se elimina tras uso

### 18.2 SQL

- Todas las queries usan `$db->quoted()` para sanitización
- Security Groups se aplican implícitamente vía SuiteCRM

### 18.3 Controller

- `sugarEntry` check en todos los archivos
- Verificación de admin en vistas de administración

---

## 19. Limitaciones Conocidas

| Limitación | Descripción |
|------------|-------------|
| Validación Symfony desactivada | Incompatibilidad con Symfony 3.4 del CRM |
| SIF hardcodeado | Nombre, ID y instalación no configurables |
| Cadena global | El encadenamiento de hashes es global, no por serie |
| Sin tabla de series | Las series se almacenan en `config_override.php`, no en BD |

---

## 20. Referencias Cruzadas

| Tema | Documento |
|------|-----------|
| Requisitos | `verifactu-requirements.md` |
| Plan de implementación | `verifactu-plan.md` |
| Base de datos | `verifactu-bd.md` |
| Normativa | Ley 11/2021, RD 1619/2012 |
| Librería | https://github.com/josemmo/verifactu-php |
