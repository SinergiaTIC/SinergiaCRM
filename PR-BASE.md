# PR-BASE: enhancement/awfSignerAction

Documento base para construir la descripción del Pull Request.  
Actualizar con cada avance antes de crear el PR final.

---

## Contenido

- [Resumen](#resumen)
- [Commits y cambios acumulados](#commits-y-cambios-acumulados)
- [Feature 1: Acción de firma en formularios web avanzados (AWF)](#feature-1-acción-de-firma-en-formularios-web-avanzados-awf)
- [Feature 2: Mejoras en el proceso de firmantes (acción popup + email)](#feature-2-mejoras-en-el-proceso-de-firmantes-acción-popup--email)
- [Feature 3: Acción "Enviar a firma" en WorkFlow](#feature-3-acción-enviar-a-firma-en-workflow)
- [Archivos modificados](#archivos-modificados)
- [Pruebas](#pruebas)
- [Notas](#notas)

---

## Resumen

Esta rama implementa la capacidad de agregar un **proceso de firma** como **acción diferida** en los Formularios Web Avanzados (AWF) y como **acción en WorkFlow**, permitiendo automatizar el envío de registros a firmar. Además, mejora la experiencia de usuario en el popup de selección de firmas (LV y DV) con checkboxes, mensajes por firmante con código de color, y un gestor de firmantes reutilizable (`SignatureSignersManager`).

---

## Commits y cambios acumulados

```
868dbaba45 Acciones diferidas y tratamiento general de pagos con pagos offline
...
eb2ceaf04b Agregar acción diferida para firmar documentos y redirigir al portal de firmas
...
a1b064615d Agregar clase SignatureSignersManager para gestionar firmantes en procesos de firma
fb977e37ba Incorporar fix de PR #1279: autenticación manual de $current_user en entrypoint sin auth
cdb9ffd3e3 Merge branch 'feature/awfDeferredActions' into enhancement/awfSignerAction
...
c1c7764fc0 Fix: Manual authentication of $current_user from session
```

> ~29 archivos modificados, ~1263 inserciones, ~309 eliminaciones.

---

## Feature 1: Acción de firma en formularios web avanzados (AWF)

### Descripción

Permite seleccionar una **acción de firma** como paso en un flujo AWF. Cuando el formulario se envía:

1. Se crea un ticket diferido (`DeferredTicket`).
2. Se añade al usuario del formulario como firmante (`stic_Signers`) mediante `SignatureSignersManager::addSignersToSignature()`.
3. El flujo AWF se detiene con resultado `WAIT` y redirige al portal de firmas (`entryPoint=sticSign`).
4. Cuando el usuario firma, el `returnToken` permite reanudar el flujo AWF vía `stic_AWF_returnHandler`.

### Archivos clave

| Archivo | Descripción |
|---------|-------------|
| `modules/stic_AWF_Forms/actions/Deferred/SignatureAction.php` | Clase de acción diferida. Extiende `DeferredBeanActionDefinition`, implementa `ITerminalAction`. Crea ticket, añade firmante, redirige al portal. |
| `modules/stic_AWF_Forms/core/actiondefs/Deferred/DeferredActionHelperTrait.php` | Trait auxiliar para acciones diferidas. |
| `modules/stic_AWF_Forms/core/actiondefs/Deferred/DeferredActionDefinition.php` | Clase base de definición de acciones diferidas. |
| `modules/stic_AWF_Forms/core/actiondefs/Deferred/DeferredBeanActionDefinition.php` | Definición de acción diferida a nivel de bean que extiende `SignatureAction`. |
| `modules/stic_Signatures/SignaturePortal/SignaturePortalEntryPoint.php` | Puente portal-firma: cuando recibe `signatureId`+`targetId` desde AWF, resuelve el firmante y redirige a `entryPoint=sticSign` preservando `returnToken`. Tras `acceptDocument`, si hay `returnToken`, devuelve `redirectUrl` a `stic_AWF_returnHandler`. |
| `modules/stic_Signatures/SignaturePortal/SignaturePortal.js` | Actualizaciones JS para soportar flujo AWF (8 líneas). |

### Strings de idioma (AWF)

- `modules/stic_AWF_Forms/language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.lang.php`: Etiquetas `LBL_SIGNATURE_ACTION_*` (título, descripción, mensajes de flujo, contexto de firmante/texto, selector de proceso de firma).

---

## Feature 2: Mejoras en el proceso de firmantes (acción popup + email)

### Descripción

Mejora la interacción del usuario al añadir registros a un proceso de firma desde List View (LV) o Detail View (DV):

1. **Popup con checkboxes** (LV y DV) en lugar de radios, permitiendo deseleccionar acciones.
   - LV: checkbox único para "Enviar email".
   - DV: dos checkboxes independientes y complementarios: "Ir al portal" y "Enviar email".
2. **Mensajes por firmante** con código de color (verde = email enviado, rojo = no enviado), en lugar de un contador consolidado.
3. **Nuevo `SignatureSignersManager`** que encapsula la lógica de negocio de añadir firmantes, reutilizable desde HTTP, AWF, workflows, etc.
4. **Fix PR #1279**: Autenticación manual de `$current_user` desde sesión cuando el entrypoint se llama sin autenticación.

### Cambios en esta sesión (Julio 2026)

- **Radio buttons → Checkboxes**: LV y DV ahora usan checkboxes. En DV, las dos acciones son complementarias (pueden marcarse ambas).
- **Mensajes por firmante**: Se reemplazó el contador consolidado por mensajes individuales con formato `(nombreFirmante) - Added correctly - Email sent/not sent`, coloreados en verde/rojo.
- **`sendToSign()` parametrizado**: Nuevo parámetro `$showMessage` (default `true`); cuando es `false` omite mensajes individuales (delegados al caller).
- **Eliminado `LBL_SIGNATURE_ACTION_DEFAULT`**: Ya no se usa al eliminar la opción "Ir al detalle".
- **Redirección por defecto**: Ahora redirige al listado del módulo origen en lugar del DetailView de la firma.

### Archivos clave

| Archivo | Descripción |
|---------|-------------|
| `custom/modules/stic_Signatures/SignatureSignersManager.php` | **Nuevo**. Lógica de negocio: `addSignersToSignature()`, `getRecordIdsFromMassUpdate()`, `getExistingSignerIds()`. Validación, evitación de duplicados, creación de beans, logging. |
| `modules/stic_Signatures/SignaturePopup.php` | Popups LV y DV con checkboxes y selección de acción. Nuevo método `DVPopupRelatedSignaturesHtml()`. |
| `modules/stic_Signatures/SignatureSignersSelect.php` | Entrypoint refactorizado. Delega en `SignatureSignersManager`. Maneja acciones: email, portal, ambas o ninguna. Incluye fix PR #1279. |
| `modules/stic_Signers/Utils.php` | `sendToSign()` con `$showMessage`, `sendOtpEmailToSigner()`, `sendOtpPhoneMessageToSigner()`, `setSignatureCompletedIfNoPendingSigners()`, `deactivateOtherSignersForSameSignature()`, `checkExpiredStatus()`, `checkActivatedStatus()`, `getDocumentName()`, `downloadDocuments()`. |

### Strings de idioma (firmas)

- `modules/stic_Signatures/language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.lang.php`: `LBL_EMAIL_STATUS_SENT`, `LBL_EMAIL_STATUS_NOT_SENT`, `LBL_ADDED_STATUS_OK`.
- `custom/Extension/application/Ext/Language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.SticLang.php`: `LBL_SIGNATURE_ACTION`, `LBL_SIGNATURE_ACTION_REDIRECT_PORTAL`, `LBL_SIGNATURE_ACTION_SEND_EMAIL`.

---

## Feature 3: Acción "Enviar a firma" en WorkFlow

### Descripción

Nueva acción en WorkFlow (AOW_WorkFlow) que permite programar el envío de un registro a un proceso de firma.  
Cuando se cumplan las condiciones del flujo, la acción:

1. Añade el registro como firmante al proceso de firma seleccionado mediante `SignatureSignersManager::addSignersToSignature()`.
2. Opcionalmente, envía el email de notificación al firmante usando la plantilla definida en el propio proceso de firma (campo `emailtemplate_id_c`), mediante `stic_SignersUtils::sendToSign()`.

### Configuración en el WorkFlow

| Campo | Descripción |
|-------|-------------|
| **Proceso de firma** | Desplegable con los procesos de firma disponibles para el módulo del flujo (`main_module`). |
| **Tipo de acción** | Dos opciones: "Añadir al proceso de firma" (`add_only`) o "Añadir al proceso de firma y enviar email" (`add_and_email`). |

La plantilla de email se toma directamente del proceso de firma seleccionado — no es necesario seleccionarla en el WorkFlow.

### Archivos clave

| Archivo | Descripción |
|---------|-------------|
| `custom/modules/AOW_Actions/Ext/Actions/actions.ext.php` | Registro de la acción `SendToSign` en `$aow_actions_list[]`. |
| `custom/modules/AOW_Actions/actions/actionSendToSign.php` | Clase `actionSendToSign extends actionBase`. Implementa `edit_display()` (selector de firma + tipo de acción) y `run_action()` (add + opcional send email). |
| `custom/modules/AOW_Actions/Ext/Language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.lang.ext.php` | Strings `LBL_SENDTOSIGN`, `LBL_SENDTOSIGN_SIGNATURE`, `LBL_SENDTOSIGN_ACTION_TYPE`, `LBL_SENDTOSIGN_ADD_ONLY`, `LBL_SENDTOSIGN_ADD_AND_EMAIL`. |

### Notas

- Reutiliza `SignatureSignersManager` (ya existente) para la lógica de negocio.
- Reutiliza `stic_SignersUtils::sendToSign()` (ya existente) para el envío de email, que usa la plantilla de notificación del proceso de firma.
- El `require_once` apunta a `custom/modules/stic_Signatures/SignatureSignersManager.php`.

---

## Archivos modificados

### Nuevos
- `custom/modules/stic_Signatures/SignatureSignersManager.php`
- `modules/stic_AWF_Forms/actions/Deferred/SignatureAction.php`
- `custom/modules/AOW_Actions/Ext/Actions/actions.ext.php`
- `custom/modules/AOW_Actions/actions/actionSendToSign.php`

### Modificados
- `custom/Extension/application/Ext/Language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.SticLang.php`
- `custom/modules/stic_Signatures/SignatureSignersManager.php`
- `modules/stic_AWF_Forms/core/actiondefs/Deferred/DeferredActionHelperTrait.php`
- `modules/stic_AWF_Forms/core/actiondefs/Deferred/DeferredActionDefinition.php`
- `modules/stic_AWF_Forms/core/actiondefs/Deferred/DeferredBeanActionDefinition.php`
- `modules/stic_AWF_Forms/core/actiondefs/includes.php`
- `modules/stic_AWF_Forms/language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.lang.php`
- `modules/stic_Signatures/SignaturePopup.php`
- `modules/stic_Signatures/SignatureSignersSelect.php`
- `modules/stic_Signatures/SignaturePortal/SignaturePortalEntryPoint.php`
- `modules/stic_Signatures/SignaturePortal/SignaturePortal.js`
- `modules/stic_Signatures/language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.lang.php`
- `modules/stic_Signers/Utils.php`
- `custom/modules/AOW_Actions/Ext/Language/{ca_ES,en_us,es_ES,eu_ES,gl_ES}.lang.ext.php`

---

## Pruebas

- [x] `php -l` sin errores de sintaxis en todos los archivos modificados.
- [x] Cache limpiada (`rm -rf cache/*`).

---

## Notas

- El `require_once` de `SignatureSignersManager` usa `custom/modules/...`; asegurar que el path es correcto en producción.
- PR pendiente de revisión: usar `/stic-review` cuando esté listo.
- Documentación: actualizar `MANUAL_TECNICO_*.md` y `MANUAL_USUARIO_*.md` antes del commit final.
