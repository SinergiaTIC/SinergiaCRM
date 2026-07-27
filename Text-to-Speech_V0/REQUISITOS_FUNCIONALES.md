# Requisitos Funcionales — Text to Speech (TTS)

**Proyecto:** Funcionalidad Text to Speech para SinergiaCRM
**Instancia base:** `/application/sinergiacrm`
**Fase:** Fase 1 (MVP)
**Fecha:** 07/2026
**Estado:** Consolidado

---

## 1. Contexto y objetivo

### 1.1 Objetivo
Funcionalidad que permite transformar, bajo demanda, el texto del CRM en audio reproducible por voz. **No se trata de un archivo descargable ni grabable**, sino de una función de lectura en streaming que respeta la privacidad de los datos sensibles.

### 1.2 Origen
Propuesta de SinergiaTIC (Bernat Freixes, 06/2026).

### 1.3 Arquitectura de referencia
Se toma como modelo la funcionalidad STT (Speech-to-Text) existente en `/application/SCRM_xavier.sinergiacrm.org/STT_autoinstalable`, replicando sus patrones arquitectónicos:
- Paquete autoinstalable vía Module Loader
- Capa de abstracción de proveedores (interfaz + base + manager singleton)
- Configuración en `stic_Settings` (negocio) + `config_override.php` (técnico)
- Inyección de assets vía LogicHook `after_ui_frame`
- Entry points con `auth => true` y validación CSRF
- i18n en 3 idiomas (ca, en, es)

### 1.4 Proveedor inicial
**Deepgram Aura-2** (primer proveedor implementado; la arquitectura permite añadir otros sin refactor).

### 1.5 Diferencia clave con STT
Mientras el STT conecta el navegador directamente a Deepgram vía WebSocket (con token temporal emitido por el servidor), el TTS utiliza un **proxy backend**: el navegador solicita el audio al servidor CRM, y este contacta a Deepgram. Así la API key nunca sale del servidor (ni siquiera como token) y la lógica de ensamblado de campos es uniforme para los tres escenarios.

### 1.6 Arquitectura de entrega: chunked (proxy por fragmentos)
El proxy backend NO mantiene una única petición PHP abierta durante toda la reproducción. En su lugar, funciona de forma **chunked**:

1. El navegador pide al endpoint `ttsSynth` el audio de un **fragmento** (un campo, un registro, o un bloque ≤2000 caracteres), identificado por `{module, record, scenario, fragmentIndex}`.
2. El backend valida auth, ensambla el texto del fragmento, llama a Deepgram, **devuelve un blob de audio completo** y **libera el worker PHP** en segundos.
3. El navegador reproduce ese fragmento y, en paralelo, pide el siguiente al backend.
4. El reproductor cliente mantiene una **cola de blobs** y los reproduce secuencialmente, ofreciendo al usuario una experiencia de streaming (el audio empieza a sonar antes de tenerlo todo).

Esta arquitectura se eligió tras verificar la infraestructura de producción (ver §1.7) y es superior a la alternativa de una petición PHP larga porque:
- **No requiere ajustes** de `max_execution_time`, `pm.max_children` ni timeouts del web server.
- **No retiene workers** FPM durante minutos (cada fragmento libera el worker en segundos), evitando que el CRM se quede irresponsivo con usuarios concurrentes.
- **Resiliente**: si un fragmento falla, se reintenta solo ese, no todo el registro.
- **Progreso natural**: el navegador sabe en todo momento qué fragmento reproduce, lo que alimenta el indicador "registro X de N".
- **Coste Deepgram idéntico** (mismas llamadas por la fragmentación de 2000 chars que ya impone Deepgram).

El coste es mayor complejidad en el reproductor JS (cola de blobs + Audio/MediaSource secuencial), pero era necesaria de todos modos por los controles del escenario c).

### 1.7 Compatibilidad con la infraestructura de producción
La arquitectura chunked es **totalmente compatible** con los requisitos oficiales de SinergiaCRM:

| Requisito oficial | Implicación TTS |
|---|---|
| PHP 8.4 | ✓ Compatible |
| Extensiones obligatorias: **curl**, **openssl**, **json**, **mbstring** (todas listadas) | ✓ `curl`+`openssl` para Deepgram por HTTPS; `mbstring` para contar caracteres (facturación); `json` para payloads. No falta ninguna. |
| Apache 2.4 / Nginx | ✓ Endpoint PHP estándar |
| HTTPS recomendado en producción | ✓ Aunque TTS no lo exige (no usa micrófono), viene de serie en prod |
| "Internet connectivity for integrations with external services" | ✓ Necesario para alcanzar `api.eu.deepgram.com` |
| 4 cores / 8GB (despliegue medio) | ✓ Suficiente para TTS chunked |

Los requisitos oficiales **no fijan** valores finos como `max_execution_time`, `pm.max_children` o timeouts del web server (específicos de cada despliegue). La arquitectura chunked es robusta ante cualquier valor razonable de estos parámetros, por eso se eligió.

**Única consideración de código** (no de infraestructura): el endpoint `ttsSynth` debe llamar `session_write_close()` tras validar la autenticación, para que la sesión PHP no bloquee otras peticiones AJAX del mismo usuario durante la reproducción. Es un fix estándar, no requiere tocar la configuración del servidor.

### 1.8 Decisión estructural: librería en `custom/include/` vs. módulo `modules/stic_TTS/`

Se evaluó estructurar TTS como un módulo CRM (`modules/stic_TTS/`) frente a una librería en `custom/include/TextToSpeech/`. La decisión es **librería en `custom/include/`**, por las siguientes razones fundamentadas en el análisis de la propia instancia:

**Evidencia del código existente:**
- La instancia tiene **64 módulos `stic_*`**; **63 tienen `vardefs.php` con tabla y bean** porque almacenan registros de negocio. El único sin tabla (`stic_Places`) es un stub de 4 líneas que solo hace un redirect — no es un precedent para empaquetar funcionalidad.
- Los módulos funcionales reales (`stic_Custom_Views`, `stic_Web_Forms`, `stic_Settings`) tienen tabla porque **guardan registros** (definiciones, capturas, configuraciones). TTS no guarda registros propios.
- **Cuando SinergiaCRM necesita alojar código sin tabla de negocio, no crea un módulo** — usa `custom/include/` o `SticInclude/`. Es exactamente lo que hizo el STT (`custom/include/SpeechToText/`).

**Por qué un módulo `stic_TTS` añadiría peso sin beneficio:**
- Un módulo code-only implicaría `vardefs.php` + bean vacío, reconstrucción de ACL, registro en nav tab + ocultarlo, metadata… todo sin beneficio funcional (TTS no tiene registros).
- El uninstall de un módulo es más frágil (restos de bean/ACL/nav) que el de una librería en `custom/` (3 operaciones limpias: drop table, delete settings, strip config).
- **Rompería la simetría** con el STT, que funciona perfectamente con `custom/include/` + Module Loader.

**Cuándo sí tendría sentido un módulo:** si en el futuro TTS necesitara persistir registros (clips guardados, perfiles de voz por usuario, historial auditable como entidad), entonces sí: un `modules/stic_TTS_Audio/` con tabla. Pero sería un módulo de datos nuevo, no reubicar la librería actual.

---

## 2. Alcance de Fase 1

### 2.1 Dentro de alcance
- Escenario a) Lectura de campos textarea (botón por campo)
- Escenario b) Lectura integrada de campos destacados desde la vista de detalle
- Escenario c) Lectura integrada masiva desde la vista de lista
- Proveedor Deepgram Aura-2
- Abstracción de proveedores (interfaz + Deepgram implementado)
- Configuración vía `stic_Settings`
- Reproductor con controles de play/pausa/stop, skip de registro, indicador de progreso y velocidad
- i18n en 3 idiomas (ca, en, es)

### 2.2 Fuera de alcance (Fase 2 o posterior)
- **Resumen hablado** (pasar N registros y obtener un resumen en audio). Requiere un LLM + TTS en pipeline; Deepgram no lo ofrece nativamente (su Summarization es STT + solo inglés). Se abordará en Fase 2.
- Proveedores adicionales (OpenAI TTS, ElevenLabs, etc.) — la arquitectura los permite pero no se implementan en Fase 1.
- Persistencia/descarga de audio (explícitamente excluido por la propuesta).
- Restricción de acceso por rol/Security Group específico (se respeta el control de acceso existente del CRM).

---

## 3. Escenarios funcionales

### 3.1 Escenario a) Lectura de un campo textarea

**Descripción:** El usuario puede escuchar el contenido de un campo textarea concreto mediante un botón situado junto al campo.

**Comportamiento:**
- En la vista de detalle (y edición) de un registro, junto a cada textarea configurado aparece un botón "Escuchar".
- Al pulsar, se reproduce en streaming el contenido actual del campo.
- Si el campo está vacío, el botón se muestra deshabilitado o no reproduce nada.
- El botón NO aparece en campos WYSIWYG/TinyMCE (se excluyen automáticamente, igual que en STT).
- El botón respeta el estado de pestañas ocultas (debe funcionar en textareas de cualquier pestaña, visible u oculta).

**Configuración:**
- Setting `TTS_TEXTAREAS` con formato `Module:field1,field2;Module2:ALL`.
- La palabra clave `ALL` (o `*`) habilita TTS en todos los textareas del módulo (excluyendo TinyMCE/WYSIWYG).
- Si la configuración excede los 255 caracteres del campo `value` de `stic_settings`, se lee del campo `description` (texto ilimitado) — patrón idéntico al `STT_MODULES` del STT.

### 3.2 Escenario b) Lectura integrada de campos desde la vista de detalle

**Descripción:** Desde la vista de detalle de cualquier registro, el usuario puede lanzar la acción "Escuchar información destacada" para que el sistema lea en voz alta los campos configurados del módulo, en el orden establecido, con el formato "etiqueta: valor".

**Comportamiento:**
- En la vista de detalle aparece una acción/botón "Escuchar información destacada".
- El sistema lee, en el orden configurado, la **etiqueta** del campo seguida de su **valor**, siempre que el valor exista y no esté vacío.
- Los campos sin valor se omiten (no se pronuncia la etiqueta de un campo vacío).
- La lectura se realiza en streaming; el usuario puede pausar, reanudar, detener y ajustar la velocidad.
- El formato de lectura (etiqueta + valor) permite al usuario identificar cada dato que escucha.

**Configuración:**
- Setting `TTS_HIGHLIGHT_FIELDS` con formato `Module:field1,field2;Module2:field3`.
- El **orden de los campos** en la configuración determina el **orden de lectura**.
- Fallback a `description` para configs largas (mismo patrón que STT).

### 3.3 Escenario c) Lectura integrada masiva desde la vista de lista

**Descripción:** Desde la vista de lista, el usuario puede seleccionar un conjunto de registros y lanzar una acción masiva "Escuchar" que reproduce secuencialmente los registros seleccionados, respetando el orden aplicado en la vista de lista.

**Comportamiento:**
- En la vista de lista aparece un botón de acción masiva "Escuchar".
- Solo se activa cuando hay al menos un registro seleccionado.
- Para cada registro seleccionado, el sistema lee los campos configurados en `TTS_HIGHLIGHT_FIELDS` (mismos campos que el escenario b), en el orden configurado, con formato "etiqueta: valor".
- La lectura es **secuencial**: al terminar un registro, pasa automáticamente al siguiente, **respetando el orden de los registros en la vista de lista** (no el orden de selección).
- Entre registros se puede insertar una separación audible (ej. "Registro siguiente") — configurable.
- El usuario puede navegar entre registros con controles de skip siguiente/anterior.
- Se muestra un indicador de progreso: "Registro X de N".
- El usuario puede detener la reproducción en cualquier momento.

**Configuración:**
- Reutiliza `TTS_HIGHLIGHT_FIELDS` del escenario b) (primera fase: mismos campos).
- Setting `TTS_LIST_SEPARATOR` para personalizar la separación audible entre registros (por defecto "Registro siguiente."; vacío = sin separación).

**Restricción de volumen:**
- Se define un máximo razonable de registros seleccionables para la acción masiva (ej. 50), configurable vía `TTS_MAX_RECORDS_LIST`. Por encima, se muestra un aviso al usuario invitándole a reducir la selección.

---

## 4. Reproductor de audio

### 4.1 Controles (Fase 1)
- **Play / Pausa / Stop** (imprescindible).
- **Skip registro siguiente / anterior** (escenario c, y también para textos largos en a y b).
- **Indicador de progreso** "Registro X de N" (escenario c) e indicador de campo/posición en a y b cuando proceda.
- **Velocidad de reproducción** ajustable (ej. 0.75x, 1x, 1.25x, 1.5x). Implementada vía `playbackRate` del elemento audio HTML5 (sin depender del parámetro `speed` de Deepgram, que está en Early Access).

### 4.2 Comportamiento
- La reproducción es **fluida/streaming percibido**: el audio del primer fragmento empieza a sonar en cuanto llega del backend, mientras los fragmentos siguientes se solicitan y bufferizan en paralelo (ver §1.6 arquitectura chunked). El usuario no espera a que se genere todo el audio para empezar a escuchar.
- El audio **no se persiste** ni se permite descargar/grabar desde la interfaz.
- Se permite **pausa y reanudación** desde el punto interrumpido.
- Al detener (stop), se cancela la solicitud de fragmentos pendientes y se vacía la cola de blobs.
- El reproductor se integra visualmente con el tema SinergiaCRM (estilo slate monocromo coherente con el STT, soporte dark theme, mobile y `prefers-reduced-motion`).

---

## 5. Configuración

### 5.1 Settings de negocio (stic_Settings, tipo `TTS`)

| Setting | Default | Descripción |
|---|---|---|
| `TTS_ENABLED` | `1` | Master switch. `1`=activado, `0`=desactivado (botones no aparecen, sin perder configuración). |
| `TTS_DEFAULT_LANGUAGE` | `es` | Idioma por defecto para la síntesis. |
| `TTS_AVAILABLE_LANGUAGES` | `es,en,ca` | Idiomas disponibles (coma-separados). |
| `TTS_DEFAULT_VOICE` | (voz Deepgram Aura-2 es) | Voz por defecto del proveedor. |
| `TTS_TEXTAREAS` | (vacío) | Escenario a). Formato `Module:field1,field2;Module2:ALL`. |
| `TTS_HIGHLIGHT_FIELDS` | (vacío) | Escenarios b/c). Formato `Module:field1,field2`. Orden = orden de lectura. |
| `TTS_DAILY_CHAR_LIMIT` | `50000` | Límite diario de caracteres sintetizados por usuario (`-1` = ilimitado). ~6 min de audio/día. Deepgram factura por caracteres. Ajustable por entidad. |
| `TTS_MAX_RECORDS_LIST` | `50` | Máximo de registros seleccionables en acción masiva c). |
| `TTS_LIST_SEPARATOR` | `Registro siguiente.` | Separación audible entre registros en c). Vacío = sin separación. |

> Para configs que excedan 255 caracteres (`TTS_TEXTAREAS`, `TTS_HIGHLIGHT_FIELDS`), se lee del campo `description` (patrón STT).

### 5.2 Settings de credenciales (stic_Settings, tipo `TTS_DEEPGRAM`)

Namespace **separado** del STT (permite keys/planes distintos):

| Setting | Default | Descripción |
|---|---|---|
| `TTS_DEEPGRAM_API_KEY` | (vacío) | API key de Deepgram para TTS. Vaciar para desactivar TTS. |
| `TTS_DEEPGRAM_MODEL` | `aura-2` | Modelo de Deepgram. |
| `TTS_DEEPGRAM_VOICE` | (voz es Aura-2) | Voz específica de Deepgram. |

> La API key se almacena en **texto plano** (consistente con el STT). Con la arquitectura de proxy backend, la key nunca se expone al navegador.

### 5.3 Configuración técnica (config_override.php)

Escrita automáticamente por el script `post_install` del paquete autoinstalable:

```php
$sugar_config['tts_provider'] = 'deepgram';
$sugar_config['deepgram_tts_endpoint'] = 'https://api.eu.deepgram.com/v1/speak';
$sugar_config['tts_max_chars_per_request'] = 2000;
$sugar_config['tts_chars_per_segment'] = 2000;
// (otros parámetros técnicos)
```

---

## 6. Módulos objetivo

La funcionalidad TTS es **genérica y aplicable a cualquier módulo** del CRM. Los módulos y campos concretos sobre los que se activa se definen mediante la configuración (`TTS_TEXTAREAS` para el escenario a, `TTS_HIGHLIGHT_FIELDS` para b/c), sin limitación técnica a módulos específicos.

La inspección detallada de campos (etiquetas, tipos, textareas, longitudes vs límite 2000 chars) se realiza en la **fase de diseño técnico**, previa a la implementación, para definir los valores concretos de `TTS_TEXTAREAS` y `TTS_HIGHLIGHT_FIELDS` según las necesidades de cada despliegue.

---

## 7. Permisos y seguridad

### 7.1 Acceso
- **Cualquier usuario con acceso de visualización al registro** puede usar TTS. No se añade lógica extra de permisos; se respeta el control de acceso existente del CRM (incluyendo Security Groups si los hay).
- Los entry points requieren `auth => true`.

### 7.2 Seguridad técnica
- **API key nunca expuesta al cliente**: el proxy backend la usa servidor-side.
- Validación de origen AJAX (same-origin) en entry points (patrón `sttCsrfUtils`).
- Validación de método HTTP y parseo de JSON input.
- Escapado de datos sensibles al construir el texto a sintetizar.

### 7.3 Privacidad
- El audio **no se persiste** en disco ni en base de datos.
- No se permite descarga ni grabación desde la interfaz.
- El texto enviado a Deepgram contiene datos del CRM; se asume que la entidad cliente acepta el procesamiento por Deepgram conforme a su política de privacidad (Deepgram cumple GDPR/HIPAA, con opción on-premise disponible).

---

## 8. Uso y facturación

### 8.1 Modelo de coste Deepgram Aura-2
- $0.030 / 1.000 caracteres (Pay As You Go).
- $0.027 / 1.000 caracteres (Growth tier, compromiso $4.000/año).
- $200 crédito inicial gratuito (válido 1 año).

### 8.2 Seguimiento de uso
- Tabla `tts_usage` (análoga a `stt_usage`) registra por cada síntesis: usuario, caracteres consumidos, idioma, módulo, proveedor, escenario (a/b/c), fecha.
- Entry point `ttsUsage` registra el consumo desde el cliente tras cada reproducción.
- `TTS_DAILY_CHAR_LIMIT` permite limitar el consumo por usuario/día.

### 8.3 Límite técnico de 2000 caracteres por petición
- Deepgram limita a 2000 caracteres por petición TTS.
- El `ttsDeepgramProvider` **fragmenta el texto** en bloques ≤2000 y realiza múltiples llamadas, concatenando el audio resultante.
- Esta lógica es interna del proveedor y transparente para el cliente y para los escenarios.

### 8.4 Manejo de errores de Deepgram
- Ante errores `429` (rate limit), `413` (texto excesivo) o `5xx` (servidor) en un fragmento, se muestra al usuario un mensaje simple y genérico: "No se ha podido generar el audio, inténtelo más tarde".
- El detalle técnico del error se registra en el log del CRM (`$GLOBALS['log']->error`).
- **Reintento limitado a nivel de fragmento**: con la arquitectura chunked, el cliente puede reintentar automáticamente **hasta 2 veces** el fragmento fallido con un breve backoff antes de mostrar el error al usuario. El reintento es por fragmento (no por registro completo), lo que acota el coste y la latencia añadida.

---

## 9. Idiomas

### 9.1 Idiomas soportados por Deepgram Aura-2
Inglés, español, francés, alemán, neerlandés, italiano, japonés (7 idiomas). **No soporta gallego ni euskera.**

### 9.2 Idiomas de la interfaz y síntesis TTS
- **Gallego y euskera se excluyen completamente del proyecto TTS** (tanto de la interfaz como de la síntesis), dado que Deepgram Aura-2 no los soporta y no aporta valor ofrecer una interfaz en un idioma que luego no puede sintetizarse.
- La interfaz (etiquetas, botones, mensajes) se traduce en **3 idiomas: ca, en, es**.
- Archivos `custom/Extension/application/Ext/Language/{ca,en,es}.tts_TTS.php`.
- `TTS_AVAILABLE_LANGUAGES` por defecto: `es,en,ca` (sin gl/eu).
- Las voces de síntesis dependen del idioma seleccionado para la lectura (independiente del idioma de la interfaz).
- Si una instancia opera en gl/eu como idioma de interfaz principal, el TTS simplemente mostrará sus textos en uno de los 3 idiomas disponibles (ca/en/es) según el fallback del CRM.

---

## 10. Estructura del paquete (autoinstalable)

```
TTS_autoinstalable/
├── manifest.php
├── README.md
├── LICENSE.txt
├── scripts/
│   ├── post_install.php          # crea tts_usage + inserta settings + escribe config_override
│   └── post_uninstall.php        # elimina tabla + settings + config_override
└── custom/
    ├── Extension/application/Ext/
    │   ├── LogicHooks/tts_Injector.php
    │   ├── EntryPointRegistry/tts_EntryPointRegistry.php
    │   ├── Sql/tts_deepgram_usage.sql
    │   └── Language/{ca,en,es}.tts_TTS.php
    ├── include/TextToSpeech/
    │   ├── providers/
    │   │   ├── TTSProviderInterface.php
    │   │   ├── TTSProviderBase.php
    │   │   └── ttsDeepgramProvider.php
    │   ├── TTSProviderManager.php
    │   ├── ttsCsrfUtils.php
    │   ├── LogicHooks/ttsInjectorHook.php
    │   ├── Entrypoints/
    │   │   ├── ttsSynthEp.php      # proxy chunked: devuelve blob de audio por fragmento
    │   │   ├── ttsUsageEp.php      # registro de caracteres consumidos
    │   │   └── ttsStringsEp.php    # i18n para el cliente JS
    │   └── javascript/tts_client.js
    └── themes/SinergiaCRMCustom/tts_client.css
```

Todo en `custom/` (upgrade-safe). Prefix `stic_` en tablas y settings.

---

## 11. Criterios de aceptación (resumen)

1. Un admin puede instalar/desinstalar TTS vía Module Loader sin tocar ficheros a mano.
2. Con `TTS_ENABLED=0`, ningún botón TTS aparece (sin perder configuración).
3. Escenario a: el botón "Escuchar" aparece junto a los textareas configurados y reproduce su contenido en streaming; no aparece en TinyMCE/WYSIWYG.
4. Escenario b: la acción "Escuchar información destacada" lee en orden los campos configurados con formato "etiqueta: valor", omitiendo campos vacíos.
5. Escenario c: la acción masiva lee secuencialmente los registros seleccionados respetando el orden de la vista de lista, con controles de skip y progreso "X de N".
6. El reproductor permite play/pausa/stop, skip, ajuste de velocidad (vía playbackRate HTML5).
7. El audio no se persiste ni se permite descargar.
8. La API key de Deepgram nunca se expone al navegador (proxy backend).
9. La interfaz aparece en los 3 idiomas (ca, en, es).
10. El consumo de caracteres se registra en `tts_usage` y se respeta `TTS_DAILY_CHAR_LIMIT`.
11. Textos >2000 caracteres se fragmentan y reproducen sin interrupción apreciable.
12. TTS y STT pueden coexistir en la misma instancia sin conflictos (namespaces separados).
13. La arquitectura chunked libera el worker PHP en segundos por fragmento: el CRM sigue responsivo mientras un usuario escucha TTS (no requiere ajustes de `max_execution_time` ni `pm.max_children`).
14. El endpoint `ttsSynth` llama `session_write_close()` tras validar auth, de modo que otras peticiones AJAX del mismo usuario no se bloquean durante la reproducción.

---

## 12. Pendientes / decisiones de diseño técnico (no funcionales)

- Inspección de campos de los módulos objetivo de cada despliegue para definir configs concretas.
- Estrategia de buffering para que la reproducción sea fluida entre fragmentos de 2000 chars.
- Política de timeouts del proxy backend (conexión con Deepgram por fragmento: `default_socket_timeout`, timeout de curl).
- Gestión de la cola de blobs en el cliente: tamaño máximo de buffer, descarte de fragmentos al hacer stop/skip.

### Decisiones ya cerradas (quedan registradas para diseño técnico)
- **gl/eu excluidos** del proyecto TTS (interfaz y síntesis). 3 idiomas: ca, en, es.
- **Separador entre registros (c):** frase fija por defecto "Registro siguiente.", configurable vía `TTS_LIST_SEPARATOR` (vacío = sin separación).
- **Límite diario:** `TTS_DAILY_CHAR_LIMIT` = `50000` por defecto (~6 min de audio/día/usuario), ajustable por entidad. `-1` = ilimitado.
- **Errores Deepgram (429/413/5xx):** mensaje simple al usuario ("No se ha podido generar el audio, inténtelo más tarde") + registro de detalle en log CRM. Reintento limitado (hasta 2) a nivel de fragmento con backoff.
