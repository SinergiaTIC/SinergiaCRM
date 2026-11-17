# Plan de implementación: Título configurable de los registros (DetailView)

## 1. Objetivo

Permitir que la **cabecera de los registros en el DetailView** se genere a partir de una **plantilla de EmailTemplates** asignada por módulo. El administrador crea la plantilla con el editor **TinyMCE** (formato + variables) y desde **Administración** la asigna a cada módulo.

## 2. Decisiones de diseño

| Aspecto | Decisión |
|---|---|
| Dónde se aplica | **Solo en el DetailView** (cabecera del registro). |
| Otras vistas | ListView, subpaneles, búsquedas, favoritos y cabeceras siguen usando `name` sin cambios. |
| Almacenamiento de plantillas | **En EmailTemplates**, con un tipo nuevo `header_title` ("Título cabecera"). No se crea módulo nuevo. |
| Cuerpo de la plantilla | Campo `description` de EmailTemplates (el que ya usa TinyMCE). |
| Asignación por módulo | Nueva sección en **Administración** con un selector de plantillas por módulo. Se guarda en `config_override.php`. |
| Persistencia en registros | **Ninguna.** No se añaden campos, no se modifica `name`. |
| Cuándo se renderiza | **Al vuelo**, al abrir el DetailView. Sin hooks `before_save`. |
| Editor | **TinyMCE** de EmailTemplates (`SugarTinyMCE`). |
| Variables | Convención `{prefijo_campo}` de EmailTemplates. |
| Parser | Parser nativo de SuiteCRM. |
| Ubicación del renderer | `SticInclude/`. |

## 3. Arquitectura

### 3.1 Tipo de plantilla en EmailTemplates

- Añadir la opción `header_title` al desplegable `emailTemplates_type_list` mediante archivos de extensión de idioma (`custom/Extension/application/Ext/Language/...`), nunca en archivos core.
- Etiqueta: "Título cabecera" / "Header title" (según idioma).
- Las plantillas de este tipo se crean y editan en el modulo **EmailTemplates** con su EditView de siempre: nombre, tipo `header_title` y cuerpo HTML en `description` con TinyMCE e inserción de variables.

### 3.2 Asignación por módulo (Administración)

- Nueva pantalla en Administración (p. ej. `Record Title Templates`):
  - Lista de módulos susceptibles de tener título (los que tienen campo `name`).
  - Para cada módulo, un desplegable con las plantillas de EmailTemplates de tipo `header_title` + opción "Sin plantilla".
  - Al guardar, se escribe en `config_override.php`:
    ```php
    $sugar_config['detailview_header_templates'] = array(
        'Accounts' => '<id de EmailTemplate>',
        'Contacts' => '<id de EmailTemplate>',
        // ...
    );
    ```
- Solo accesible para administradores.

### 3.3 Renderer

Nueva clase `SticInclude/SticRecordTitleRenderer.php`:

- `render($moduleName, SugarBean $bean): ?string`
  - Lee `$sugar_config['detailview_header_templates'][$moduleName]`; si no hay o está vacío → `null`.
  - Recupera el bean `EmailTemplates` por id; valida que exista y que `type === 'header_title'`.
  - Toma su `description` (HTML de TinyMCE) como plantilla.
  - Parsea con el **parser nativo de SuiteCRM**:
    - `EmailTemplate::parse_template_bean()` (`modules/EmailTemplates/EmailTemplate.php`), o
    - `templateParser::parse_template()` (`modules/AOS_PDF_Templates/templateParser.php`).
  - Convención de variables `{prefijo_campo}` → valor del campo del bean, generada por `generateFieldDefsJS2()` (`modules/EmailTemplates/templateFields.php`).
  - Devuelve el HTML listo para inyectar.
  - Caché estática/APCu del bean de plantilla por módulo, invalidada al guardar la configuración o la plantilla.

### 3.4 Inyección en el DetailView

`ViewFactory.php` (~línea 256) ya permite una clase `CustomSugarView` en `custom/include/MVC/View/SugarView.php`.

- Crear `custom/include/MVC/View/SugarView.php` con `class CustomSugarView extends SugarView`.
- Sobreescribir `_getModuleTitleParams()`:
  - En el caso `DetailView`, sustituir `$this->bean->get_summary_text()` por `SticRecordTitleRenderer::render($this->module, $this->bean)` cuando haya plantilla asignada.
  - Si no hay plantilla o el parser devuelve `null`, mantener el comportamiento original exacto.
- Solo afecta a la cabecera generada por `getModuleTitle()` (`<h2 class="module-title-text">`).

## 4. Archivos

### Nuevos
- `SticInclude/SticRecordTitleRenderer.php`
- `custom/include/MVC/View/SugarView.php` (`CustomSugarView`)
- `modules/Administration/RecordTitleTemplates.php` y su plantilla `.tpl`
- Extensiones de idioma para la nueva opción `header_title` y las etiquetas de la sección de Administración.

### Modificados
- `config_override.php` — nueva clave `detailview_header_templates` (escrita desde la pantalla de Administración).
- Menú de Administración para añadir la entrada.
- Sin cambios en vardefs ni en plantillas de DetailView de los módulos.

### No se crea
- Ningún módulo nuevo.
- Ninguna tabla SQL nueva.

## 5. Fases

### Fase 0 — Investigación (1-2 días)
- Confirmar módulos a incluir en el selector (los que tienen campo `name`).
- Validar el parser nativo y el prefijo de variables por módulo.

### Fase 1 — Plantillas y asignación (3-4 días)
- Añadir tipo `header_title` a `emailTemplates_type_list` vía extensiones de idioma.
- Crear la pantalla de Administración y la escritura en `config_override.php`.

### Fase 2 — Renderer e inyección (3-4 días)
- Implementar `SticRecordTitleRenderer` con el parser nativo.
- Implementar `CustomSugarView`.
- Tests unitarios del renderer.

### Fase 3 — Integración y QA (2-3 días)
- Verificar que el título renderizado aparece solo en el DetailView.
- Regresión en ListView, subpaneles, búsquedas, favoritos y cabeceras.
- Verificar que cambiar la plantilla o su asignación se refleja sin re-guardar registros.

### Fase 4 — Opcional
- Edición manual por registro (requeriría campo en el bean; se evalúa más adelante).

## 6. Riesgos

- **Rendimiento**: se parsea en cada apertura de DetailView. Mitigar con caché de la plantilla.
- **XSS**: sanear la plantilla y escapar los valores de las variables antes de inyectar.
- **Config concurrente**: `config_override.php` es un archivo; la escritura debe ser atómica y con `sugar_config` recargado.
- **Compatibilidad**: solo ofrecer módulos con campo `name`.
- **ACL**: solo administradores asignan plantillas; los usuarios ven el resultado.
- **i18n**: las variables usan nombres internos de campo; documentar.

## 7. Criterios de aceptación

1. Se puede crear un EmailTemplates de tipo "Título cabecera" con TinyMCE y variables.
2. En Administración hay una sección que permite asignar, por módulo, una de esas plantillas (o ninguna).
3. Al abrir el DetailView de un registro de un módulo con plantilla asignada, la cabecera muestra la plantilla renderizada.
4. El resto de vistas siguen usando `name` sin cambios.
5. Si no hay plantilla asignada, el comportamiento es el original.
6. No se añaden campos a los módulos ni tablas nuevas.
7. Los tests de regresión pasan.

## 8. Estimación

~10-14 días de desarrollo + 3-5 de QA. Complejidad: media-alta. Se recomienda un módulo piloto (p. ej. Accounts) antes de extender a todos.
