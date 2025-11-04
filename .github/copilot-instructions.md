# Instrucciones para agentes AI / Copilot — SinergiaCRM (subproyecto: signatures)

Propósito breve
---------------
Guía concisa para que un agente AI sea productivo rápidamente en este repositorio (rama `feature/signatures`). Incluye el "big picture", flujos de desarrollo, convenciones del proyecto y ejemplos concretos.

Arquitectura y panorama general
------------------------------
- Basado en SuiteCRM/SugarCRM (PHP). El código principal está organizado en `modules/`, `custom/` (overrides/extensions) y `include/`/`SticInclude/` para utilidades específicas.
- Módulos principales añadidos por este PR: `stic_Signatures`, `stic_Signers`, `stic_Signature_Log`. Buscarlos en `modules/` y sus extensiones en `custom/modules/`.
- Metadata y vistas: `modules/<Module>/metadata/*` (vardefs, listviewdefs, searchdefs) y `custom/modules/<Module>/Ext/*` para override dinámico.
- Extensiones y strings: `custom/application/Ext/Language/*` almacena listas y traducciones añadidas.

Patrones y convenciones del proyecto (importantes para un agente)
---------------------------------------------------------------
- Extensiones > uso de `custom/`: Nunca modificar sólo `custom/` sin activar un "Quick Repair and Rebuild" — los cambios en `custom/` se sincronizan en caché.
- Utilidades por módulo: las funciones reutilizables suelen vivir en `modules/<Module>/Utils.php` (ej.: `modules/stic_Signers/Utils.php`).
- Subpanels y llamados remotos: layoutdefs en `custom/modules/<Module>/Ext/Layoutdefs/` usan referencias tipo `get_subpanel_data => 'function:stic_SignersUtils::getSticSignersForContacts'` — el agente debe buscar la función en el archivo referenciado.
- EntryPoints y APIs: entradas personalizadas se registran en `custom/Extension/application/Ext/EntryPointRegistry/` y puntos API en `Api/`.
- Nombres de tablas y relaciones: los beans crean tablas como `stic_signers`, `stic_signatures`, `stic_signature_log` y tablas de relación `stic_signatures_stic_signers_c` — úsalos en comprobaciones SQL rápidas.

Puntos de integración criticós (ejemplos concretos)
--------------------------------------------------
- Parseo de plantillas: `SticInclude/Utils.php` contiene `SticUtils::parseEmailTemplate($templateId, $Beans)` — usado desde `modules/stic_Signers/Utils.php`.
- Portal y generación de PDF firmado: `modules/stic_Signatures/SignaturePortal/` (tpl/js/css) y `modules/stic_Signatures/sticGenerateSignedPdf.php`.
- Registro/auditoría: `modules/stic_Signature_Log/Utils.php` con acciones enumeradas en `custom/application/Ext/Language/*` (`stic_signature_log_actions`).
- Campaigns / LPO: extensiones en `custom/modules/Campaigns/` y controlador `custom/modules/ProspectLists/controller.php` implementan `action_populateLPOFilters` / `action_createAutoLPO`.

Flujos de trabajo y comandos útiles (operaciones que debes sugerir/ejecutar)
-----------------------------------------------------------------------
- Entorno local y dependencias:
  - Instalar dependencias PHP (si procede): revisar `composer.json` y ejecutar `composer install` en el workspace raíz cuando trabajes con dependencias nuevas.
  - Muchas piezas son código PHP “in-place” (SuiteCRM) — no hay bundler JS centralizado exigido por este PR.
- Caché y metadata:
  - Después de cambiar `custom/` o metadata: limpiar caché y forzar rebuild.
    - Borrar caché: `rm -rf cache/` (ya usado en este repo).
    - Desde la UI: Admin → Repair → Quick Repair and Rebuild.
- Ejecutar tests (si existen): hay `codeception.dist.yml` y `tests/` — ejecutar pruebas con Codeception si se usa: `vendor/bin/codecept run` (o `./vendor/bin/codecept run`) — confirma localmente la ruta del binario.

Cómo revisar cambios en la rama (comandos git útiles)
--------------------------------------------------
- Lista de ficheros modificados respecto a `develop`:
  - `git fetch origin develop && git diff --name-only origin/develop...HEAD`
- Revisar cambios en `custom/` y `modules/` en el PR: buscar modificaciones en `modules/stic_Signatures`, `modules/stic_Signers`, `modules/stic_Signature_Log`, `custom/modules` y `custom/application/Ext/Language`.

Errores y riesgos frecuentes (qué validar primero)
------------------------------------------------
- Metadata no desplegada: si ves que `vardefs` o `metadata` no producen efectos, recuerda borrar caché y ejecutar Quick Repair and Rebuild.
- Strings faltantes: muchas listas y labels están en `custom/application/Ext/Language/*` — comprueba que los keys nuevos existen en todos los idiomas requeridos.
- Exposición de PDFs/entrypoints: validar que `sticGenerateSignedPdf.php` y endpoints del portal validan permisos/tokens.

Checklist rápida para PRs (qué el agente debe validar o sugerir)
---------------------------------------------------------------
- Identificar ficheros modificados en `modules/` y `custom/` y mapearlos a: metadata, language, controllers, utils, templates.
- Confirmar que utilidades nuevas (ej.: `SticUtils::parseEmailTemplate`) están cubiertas por llamadas observables (buscar referencias con grep).
- Sugerir pasos reproducibles para QA: creación de una `stic_Signatures`, añadir signers, envío de OTP, firma desde portal, ver logs en `stic_signature_log`, descargar PDF firmado.

Dónde buscar ejemplos concretos en este repo
-------------------------------------------
- `modules/stic_Signatures/SignatureSignersSelect.php` — añadir firmantes programáticamente.
- `modules/stic_Signers/Utils.php` — utilidades de firmantes y llamadas a `SticUtils::parseEmailTemplate`.
- `modules/stic_Signature_Log/Utils.php` — creación de registros de auditoría.
- `custom/modules/Contacts/Ext/Layoutdefs/layoutdefs.ext.php` y `custom/modules/Users/Ext/Layoutdefs/layoutdefs.ext.php` — ejemplos de subpanels que apuntan a funciones en `Utils.php`.

Instrucciones de estilo para el agente (cómo editar y proponer cambios)
------------------------------------------------------------------------
- Prefiere cambios pequeños y localizados: editar `modules/<Module>/` y `custom/` correspondientes en el mismo PR.
- Evita modificar `vendor/` a menos que sea estrictamente necesario.
- Cuando añadas metadata new/changed en `custom/`, añade una nota en el PR indicando la necesidad de Quick Repair and Rebuild y limpieza de `cache/`.

Solicita feedback
-----------------
Si algún área está incompleta (por ejemplo, comandos de tests específicos o scripts de despliegue que no he deducido del código), dime qué partes quieres que investigue y extraigo los archivos/comandos concretos.

---
Archivo generado automáticamente y adaptado al subárbol `application/signatures` para ayudar a agentes AI a entender rápidamente dónde buscar y qué validar.
