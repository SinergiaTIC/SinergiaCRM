# Plan de Implementación — Text to Speech (TTS)

**Proyecto:** Funcionalidad Text to Speech para SinergiaCRM
**Fase:** Fase 1 (MVP)
**Fecha:** 07/2026
**Estado:** Consolidado
**Requisitos asociados:** `REQUISITOS_FUNCIONALES.md`

---

## 1. Resumen ejecutivo

Se construye un paquete autoinstalable (Module Loader) que añade TTS a SinergiaCRM, siguiendo la arquitectura del STT existente (`STT_autoinstalable`) con la diferencia clave de un **proxy backend chunked** en lugar de conexión directa navegador→Deepgram. Tres escenarios: botón por textarea (a), "Escuchar info destacada" en vista detalle (b), y acción masiva en vista lista (c).

Proveedor inicial: Deepgram Aura-2. Abstracción de proveedores desde el inicio. Audio no persistente, reproductor con controles completos. 3 idiomas (ca, en, es).

---

## 2. Arquitectura técnica

### 2.1 Diagrama de componentes

```
Navegador (tts_client.js + tts_player.js + tts_buttons.js)   Backend PHP (entry points)           Deepgram API
─────────────────────────                  ──────────────────────────           ──────────────
                                          
[Botón textarea] ──┐                       ttsSynthEp.php                       POST /v1/speak
[Botón detalle]  ──┼──> fetch fragmento ──>  session_write_close()             ?model=aura-2-*
[Botón masivo]   ──┘    {module,record,      valida auth                       &encoding=mp3
                         scenario,           lee registro + campos             Authorization: Token
                         fragmentIndex}      ensambla texto                    {"text":"..."}
                                             fragmenta ≤2000 chars
                         <── audio blob ────  llama Deepgram ─────────────────>  <── audio/mpeg
                                             devuelve blob + dg-char-count
                                          
[Reproductor]     ──> ttsUsageEp.php        registra caracteres en tts_usage
                     ttsStringsEp.php      devuelve i18n strings
                                          
[after_ui_frame]  <── CSS/JS inyectados ── ttsInjectorHook.php
```

### 2.2 Flujo chunked (detalle)

1. **Cliente** determina qué texto leer (contenido de textarea para a; campos destacados para b/c).
2. **Cliente** divide el texto en fragmentos lógicos (por campo, por registro, y/o por bloques ≤2000 chars) y calcula `fragmentIndex`.
3. **Cliente** hace `fetch` al endpoint `ttsSynth` con `{module, record, scenario, fragmentIndex, fields[]}`.
4. **Backend** (`ttsSynthEp`): valida auth → `session_write_close()` → carga el bean → extrae el texto del fragmento → si >2000 chars, sub-fragmenta → llama a Deepgram por cada sub-fragmento → concatena audio → devuelve blob binario con header `X-TTS-Char-Count` (para facturación).
5. **Cliente** recibe el blob, lo añade a la cola de reproducción, y solicita el siguiente fragmento en paralelo.
6. **Reproductor** reproduce la cola secuencialmente con controles play/pausa/stop/skip/velocidad.
7. Al terminar (o al detener), **cliente** reporta caracteres consumidos a `ttsUsage`.

### 2.3 API Deepgram TTS REST (confirmada)

```
POST https://api.eu.deepgram.com/v1/speak?model=aura-2-{voice}-{lang}&encoding=mp3
Headers:
  Authorization: Token {API_KEY}
  Content-Type: application/json
Body:
  {"text": "..."}
Response:
  200 OK
  content-type: audio/mpeg
  transfer-encoding: chunked
  dg-char-count: {número de caracteres procesados}
  dg-request-id: {id}
  [audio binario]
```

- Formato del modelo: `aura-2-{voicename}-{language}` (ej: `aura-2-thalia-en`, voz española: definir voz concreta en diseño).
- Encoding mp3: sample rate fijo 22050 Hz, bitrate configurable (default 48000).
- Máximo 2000 caracteres por petición (413 si se excede).
- Concurrencia REST: 15 peticiones simultáneas (Pay As You Go).

---

## 3. Estructura del paquete

```
TTS_autoinstalable/
├── manifest.php
├── README.md
├── LICENSE.txt
├── scripts/
│   ├── post_install.php
│   └── post_uninstall.php
└── custom/
    ├── Extension/application/Ext/
    │   ├── LogicHooks/tts_Injector.php
    │   ├── EntryPointRegistry/tts_EntryPointRegistry.php
    │   ├── Sql/tts_deepgram_usage.sql
    │   └── Language/
    │       ├── ca_ES.tts_TTS.php
    │       ├── en_us.tts_TTS.php
    │       └── es_ES.tts_TTS.php
    ├── include/TextToSpeech/
    │   ├── providers/
    │   │   ├── TTSProviderInterface.php
    │   │   ├── TTSProviderBase.php
    │   │   ├── ttsDeepgramProvider.php       # provider: endpoint, auth, synthesize()
    │   │   └── ttsTextFragmenter.php         # fragmentación texto ≤2000 chars
    │   ├── TTSProviderManager.php
    │   ├── ttsCsrfUtils.php
    │   ├── LogicHooks/ttsInjectorHook.php
    │   ├── Entrypoints/
    │   │   ├── ttsSynthEp.php               # endpoint: flujo principal
    │   │   ├── ttsTextAssembler.php          # helper: ensamblar "etiqueta: valor" desde bean
    │   │   ├── ttsListviewOrder.php          # helper: reconstruir IDs ordenados de listview
    │   │   ├── ttsUsageEp.php
    │   │   └── ttsStringsEp.php
    │   └── javascript/
    │       ├── tts_client.js                 # orquestador: init, config, fetch, usage
    │       ├── tts_player.js                 # motor audio: cola blobs, controles, progreso
    │       └── tts_buttons.js               # inyección: botones a/b/c + render reproductor
    └── themes/SinergiaCRMCustom/tts_client.css
```

### 3.1 Partición de ficheros grandes

Se ha comparado el tamaño estimado de cada fichero con su equivalente en el STT (medido en la instancia). Los ficheros que superan ~500 líneas se parten para mantener mantenibilidad y permitir edición/gestión eficiente:

| Fichero | Equiv. STT (líneas) | Estimación TTS | Partición | Razón |
|---|---|---|---|---|
| `tts_client.js` | 1121 | ~2000 | **3 ficheros** (ver abajo) | Cola de blobs + reproductor + 3 escenarios de inyección + retry |
| `ttsSynthEp.php` | 229 | ~550 | **3 ficheros** (ver abajo) | Ensamblado texto + orden listview + 3 escenarios |
| `ttsDeepgramProvider.php` | 509 | ~650 | **2 ficheros** (ver abajo) | Fragmentación ≤2000 añade lógica compleja |
| `tts_client.css` | 340 | ~500 | Sin partir | CSS declarativo, sin lógica; manejable en un fichero |

**Split `tts_client.js` → 3:**
- `tts_client.js` (~600) — orquestador: IIFE base, config, strings, fetch helpers, usage reporting, MutationObserver, init. Define el namespace global que los otros dos extienden.
- `tts_player.js` (~700) — motor de audio: cola de blobs, reproducción secuencial, controles (play/pausa/stop/skip), velocidad (playbackRate), indicador de progreso, reintento por fragmento.
- `tts_buttons.js` (~700) — inyección UI: botones textarea (a), acción detalle (b), acción masiva listview (c), render del DOM del reproductor.

**Split `ttsSynthEp.php` → 1 endpoint + 2 helpers:**
- `ttsSynthEp.php` (~200) — flujo principal: validar auth, `session_write_close()`, límite diario, delegar al ensamblador y provider, devolver blob.
- `ttsTextAssembler.php` (~200) — clase `TtsTextAssembler`: construir texto "etiqueta: valor" desde el bean, leer etiquetas de vardefs/mod_strings, omitir campos vacíos, manejar los 3 escenarios.
- `ttsListviewOrder.php` (~150) — clase `TtsListviewOrder`: reconstruir IDs ordenados desde `current_query_by_page` + `lvso` + `orderBy` (reutilizar lógica de `MassUpdate` / `ListViewData`).

**Split `ttsDeepgramProvider.php` → 2:**
- `ttsDeepgramProvider.php` (~400) — provider: endpoint, auth, mapeo voz/idioma, `synthesize()` orquestador.
- `ttsTextFragmenter.php` (~250) — clase `TtsTextFragmenter`: dividir texto en bloques ≤2000 respetando límites de frase (`.` `!` `?` `\n`), luego palabra (` `), con fallback a corte duro.

> **Carga de JS en el navegador:** `ttsInjectorHook.php` inyecta los 3 ficheros JS en orden: `tts_client.js` → `tts_player.js` → `tts_buttons.js`. El orquestador (`tts_client.js`) define el namespace global; los otros dos extienden el prototype.

### 3.2 Justificación de la estructura: librería vs. módulo

Se evaluó estructurar TTS como un módulo CRM (`modules/stic_TTS/`) frente a la librería en `custom/include/TextToSpeech/` mostrada arriba. La decisión es **librería en `custom/include/`**.

**Análisis del código existente en la instancia:**
- Hay **64 módulos `stic_*`**; **63 tienen `vardefs.php` + tabla + bean** porque almacenan registros. El único sin tabla (`stic_Places`) es un redirect stub de 4 líneas, no un precedent.
- Los módulos funcionales (`stic_Custom_Views`, `stic_Web_Forms`) tienen tabla porque guardan registros. TTS no guarda registros propios.
- El STT —hermano gemelo de TTS— usa `custom/include/SpeechToText/` y funciona correctamente vía Module Loader con install/uninstall simétrico.

**Un módulo `stic_TTS` code-only implicaría (sin beneficio):**
- `vardefs.php` + bean vacío, reconstrucción de ACL, nav tab + ocultarlo, metadata.
- Uninstall más frágil (restos de bean/ACL/nav) vs. 3 operaciones limpias de la librería.
- Ruptura de simetría con el STT.

**La librería ya está compactada:** todo el TTS vive bajo `custom/include/TextToSpeech/` (providers, endpoints, hooks, JS), con solo 3 archivos de registro en `custom/Extension/application/Ext/`. Es tan compacto como un módulo, sin el ceremony del framework.

**Revisión futura:** si TTS necesitara persistir registros (clips guardados, perfiles de voz), se crearía un `modules/stic_TTS_Audio/` con tabla —un módulo de datos nuevo, no reubicar la librería.

---

## 4. Especificación por componente

### 4.1 providers/TTSProviderInterface.php

Adaptar la interfaz del STT para síntesis (no transcripción):

```php
interface TTSProviderInterface
{
    public function getId();
    public function getName();
    public function isConfigured();
    public function getSupportedLanguages();
    public function getSupportedVoices($language = null);
    public function getRestEndpoint($config = array());
    public function getAuthHeaders($apiKey, $config = array());
    public function getRequestBody($text, $config = array());
    public function getQueryParams($config = array());  // model, encoding, etc.
    public function mapLanguage($language);
    public function mapVoice($voice, $language);
    public function getMaxCharsPerRequest();
    public function validateCredentials($apiKey, $config = array());
    public function synthesize($text, $config = array());  // devuelve audio binario
}
```

### 4.2 providers/TTSProviderBase.php

Clase base abstracta con implementaciones por defecto, reutilizando los helpers del STT:
- `getApiKey()` → lee `TTS_DEEPGRAM_API_KEY` de stic_settings
- `getSetting($name)` → lee `TTS_DEEPGRAM_{name}` de stic_settings
- `getConfig($key, $default)` → lee `$sugar_config['deepgram_tts_{key}']`
- `normalizeResponse()` → formato estándar de respuesta

### 4.3 providers/ttsDeepgramProvider.php

Implementación concreta para Deepgram Aura-2:

- **Endpoint:** `https://api.eu.deepgram.com/v1/speak` (configurable vía `config_override`)
- **Auth:** `Authorization: Token {apiKey}`
- **Modelo:** `aura-2-{voice}-{language}` (construido dinámicamente)
- **Encoding:** `mp3` (default, más compatible con navegadores)
- **Método `synthesize($text, $config)`:**
  1. Si `mb_strlen($text) > 2000`: delegar la fragmentación a `TtsTextFragmenter` (§4.3b)
  2. Por cada bloque: `curl POST` a Deepgram con `{"text": "..."}`
  3. Concatenar los blobs de audio binario resultantes
  4. Devolver: `['audio' => $binaryData, 'charCount' => $totalChars, 'fragments' => $n]`
  5. El header `dg-char-count` de cada respuesta se suma para facturación
- **Manejo de errores:** curl error → log + return null; HTTP 429/413/5xx → log + return error array
- **Timeouts:** CURLOPT_TIMEOUT configurable (default 30s por sub-fragmento)

### 4.3b providers/ttsTextFragmenter.php

Clase `TtsTextFragmenter` — extraída del provider para mantenerlo manejable:

- **Método `fragment($text, $maxChars = 2000)`:** divide texto en bloques ≤ `$maxChars`
- **Estrategia de corte (prioridad descendente):**
  1. Límite de frase: cortar en `.` `!` `?` `\n` `;` (mantener el delimitador con el bloque anterior)
  2. Límite de coma: cortar en `,` si no hay frase cercana
  3. Límite de palabra: cortar en último ` ` dentro del límite
  4. Corte duro: si no hay ningún separador, cortar en `$maxChars` exacto
- **Multibyte-safe:** usa `mb_strlen`, `mb_strrpos`, `mb_substr` (la extensión `mbstring` es obligatoria según los system requirements)
- **Devuelve:** `['text', 'text', ...]` — array de fragmentos listos para enviar a Deepgram

### 4.4 TTSProviderManager.php

Singleton idéntico al STT, adaptado:
- `registerBuiltInProviders()` → registra `ttsDeepgramProvider`
- `getActiveProvider()` → seleccionado por `tts_provider` de config_override
- `getClientConfig()` → devuelve config segura al cliente (sin API key)
- `isAnyProviderConfigured()` → verifica que la API key exista

### 4.5 ttsCsrfUtils.php

Copia adaptada del `sttCsrfUtils.php` del STT (renombrar funciones a `tts*`):
- `ttsGetConfigValue($settingName, $configKey, $default)` — fallback stic_settings → config_override
- `ttsIsValidAjaxRequest()` — validación same-origin
- `ttsValidateRequest($options)` — auth + método + JSON input
- `ttsOutputJson($data, $status)` — respuesta JSON

### 4.6 Entrypoints/ttsSynthEp.php

**El componente más crítico.** Proxy backend chunked:

```
Entrada (POST JSON): {
  module: "stic_xxx",
  record: "bean-id",
  scenario: "a" | "b" | "c",
  fragmentIndex: 0,
  fields: ["field1", "field2"],   // para b/c: campos a leer
  text: "...",                     // para a: texto directo del textarea
  language: "es",
  listContext: {                   // solo para c: contexto de listview
    uids: ["id1","id2",...],
    current_query_by_page: "{json}",
    lvso: "ASC",
    orderBy: "field_name"
  }
}
```

**Flujo:**
1. `ttsValidateRequest(['json_input' => true])` → valida auth + origen + JSON
2. `session_write_close()` → libera el lock de sesión
3. Verificar `TTS_ENABLED == 1` y API key configurada
4. Verificar límite diario (`TTS_DAILY_CHAR_LIMIT`) del usuario
5. Cargar el bean (`BeanFactory::getBean($module, $record)`) y verificar acceso (`ACLController::checkAccess` / `$bean->ACLAccess('view')`)
6. Construir el texto del fragmento delegando en helpers:
   - Escenario a: usar `text` del request (contenido del textarea)
   - Escenario b: `TtsTextAssembler::buildFromBean($bean, $fields)` → "etiqueta: valor" (ver §4.6b)
   - Escenario c: `TtsListviewOrder::getOrderedIds($listContext)` (ver §4.6c) → tomar el `fragmentIndex`-ésimo → `TtsTextAssembler` + separador `TTS_LIST_SEPARATOR`
7. Llamar `$provider->synthesize($text, $config)`
8. Devolver:
   - `Content-Type: audio/mpeg`
   - `X-TTS-Char-Count: {n}`
   - Body: blob binario de audio

### 4.6b Entrypoints/ttsTextAssembler.php

Clase `TtsTextAssembler` — extraída del endpoint para mantenerlo manejable:

- **Método `buildFromBean($bean, $fields, $language)`:**
  1. Por cada field en `$fields` (en orden):
     - Obtener la **etiqueta** traducida: leer de `mod_strings` del módulo o de `$bean->field_defs[$field]['vname']`
     - Obtener el **valor**: `$bean->$field`
     - Si el valor está vacío → **omitir** el campo (no pronunciar etiqueta sin valor)
     - Formatear: `{etiqueta}: {valor}`
  2. Concatenar todos los pares con separador (`. ` o salto de línea)
  3. Devolver el texto completo
- **Método `buildFromText($text)`:** para escenario a — devuelve el texto tal cual (wrapper para API uniforme)
- **Consideraciones:** escapar caracteres que puedan romper el JSON o la síntesis (tags HTML en textareas, caracteres de control)

### 4.6c Entrypoints/ttsListviewOrder.php

Clase `TtsListviewOrder` — extraída del endpoint por complejidad del orden listview:

- **Método `getOrderedIds($listContext)`:**
  1. Decodificar `current_query_by_page` (JSON de la query de búsqueda original)
  2. Reconstruir el WHERE con `generateSearchWhere()` / `SearchForm2::populateFromArray()` (mismo mecanismo que Export y MassUpdate "entire list")
  3. Construir ORDER BY desde `lvso` (dirección) + `orderBy` (campo) — misma lógica que `ListViewData::getOrderBy()`
  4. Si `select_entire_list == 1`: ejecutar la query completa y devolver todos los IDs ordenados
  5. Si no: añadir `AND id IN ({uids})` al WHERE, ejecutar con ORDER BY → devuelve los IDs seleccionados en orden listview
  6. Devolver `['id1', 'id2', ...]` (ordenados)
- **Referencia:** `MassUpdate.php` líneas 262-287 (re-query para entire list); `ListViewData.php` líneas 90-122 (getOrderBy)

### 4.7 Entrypoints/ttsUsageEp.php

Registra caracteres consumidos en `tts_usage`:
- Entrada (POST JSON): `{charCount, language, module, provider, scenario, recordId}`
- Valida auth + origen
- Verifica que `charCount` sea razonable (1..100000)
- INSERT en `tts_usage`
- Devuelve `{success: true, used: X, limit: Y, remaining: Z}`

### 4.8 Entrypoints/ttsStringsEp.php

Idéntico al `sttStringsEp` del STT:
- Lee `app_strings['LBL_TTS_*']` del idioma del usuario
- Devuelve JSON `{success: true, strings: {...}}`

### 4.9 LogicHooks/ttsInjectorHook.php

Inyección de assets vía `after_ui_frame`:

**Detección de contexto:**
- `$_REQUEST['action']` → determinar vista:
  - `DetailView` o `EditView` → escenario a (textareas) + escenario b (acción detalle)
  - `index` → escenario c (acción masiva listview)
- Verificar `TTS_ENABLED == 1` y API key configurada
- Verificar que el módulo actual está en `TTS_TEXTAREAS` (para a) o `TTS_HIGHLIGHT_FIELDS` (para b/c)

**Inyección:**
- `echo '<link>'` CSS
- `echo '<script>var sticTtsConfig = {...}</script>'` con: módulo, vista, campos configurados, idioma, endpoint, strings endpoint
- `echo '<script src="...tts_client.js">'` (orquestador)
- `echo '<script src="...tts_player.js">'` (motor de audio)
- `echo '<script src="...tts_buttons.js">'` (inyección UI)

**Config largas:** reutilizar el patrón `sttGetModulesConfig()` (fallback a `description`).

### 4.10 javascript/tts_client.js (orquestador)

Núcleo del cliente TTS. Define el namespace global `SttTTS` y la estructura base. IIFE con MutationObserver (patrón STT). Los otros dos ficheros (`tts_player.js`, `tts_buttons.js`) extienden el prototype.

**Responsabilidades:**
- Init: cargar strings (`ttsStrings`), resolver config, arrancar MutationObserver, delegar inyección de botones a `tts_buttons.js`
- Fetch helper: `sstFetch()` con cabecera `X-Requested-With` (patrón STT)
- Request de fragmentos al backend: `requestFragment(fragmentData)` → `fetch('index.php?entryPoint=ttsSynth', ...)` → `.blob()`
- Retry por fragmento: `requestWithRetry(fragmentData)` — hasta 2 reintentos con backoff exponencial
- Reporte de uso: `reportUsage(charCount)` → POST a `ttsUsage`
- Carga ordenada: el `ttsInjectorHook` inyecta los 3 JS en orden (client → player → buttons)

**Estructura:**
```javascript
var SttTTS = (function() {
    function SttTTS() {
        this.config = window.sticTtsConfig || {};
        this.strings = {};
        this.endpoints = {
            synth: 'index.php?entryPoint=ttsSynth',
            usage: 'index.php?entryPoint=ttsUsage',
            strings: 'index.php?entryPoint=ttsStrings'
        };
        // ... estado compartido
    }
    SttTTS.prototype.init = function() { ... };
    SttTTS.prototype.log = function(msg) { ... };
    SttTTS.prototype.sstFetch = function(url, options) { ... };
    SttTTS.prototype.loadStrings = function() { ... };
    SttTTS.prototype.requestFragment = function(fragmentData) { ... };
    SttTTS.prototype.requestWithRetry = function(fragmentData) { ... };
    SttTTS.prototype.reportUsage = function(charCount) { ... };
    SttTTS.prototype.setupObserver = function() { ... };
    return SttTTS;
})();
```

### 4.10b javascript/tts_player.js (motor de audio)

Motor de reproducción con cola de blobs. Extiende `SttTTS.prototype`.

**Responsabilidades:**
- Cola de blobs (`audioQueue[]`): bufferizar fragmentos recibidos del backend
- Reproducción secuencial: al terminar un blob, reproducir el siguiente
- Controles: `play()`, `pause()`, `resume()`, `stop()`, `skipNext()`, `skipPrev()`
- Velocidad: `setSpeed(rate)` → `audioElement.playbackRate = rate` (HTML5, sin depender de Deepgram)
- Indicador de progreso: "Registro X de N" + posición dentro del fragmento actual
- `stop()`: cancela peticiones pendientes con `AbortController` y vacía la cola
- Prefetch: solicitar 1-2 fragmentos por delante del que se reproduce

**Estructura:**
```javascript
// Extiende el prototype definido en tts_client.js
SttTTS.prototype.audioQueue = [];
SttTTS.prototype.currentBlob = null;
SttTTS.prototype.isPlaying = false;
SttTTS.prototype.isPaused = false;
SttTTS.prototype.currentIndex = 0;
SttTTS.prototype.totalFragments = 0;
SttTTS.prototype.audioElement = null;
SttTTS.prototype.playbackRate = 1.0;
SttTTS.prototype.abortController = null;

SttTTS.prototype.playQueue = function() { ... };
SttTTS.prototype.pause = function() { ... };
SttTTS.prototype.resume = function() { ... };
SttTTS.prototype.stop = function() { ... };       // AbortController + vaciar cola
SttTTS.prototype.skipNext = function() { ... };
SttTTS.prototype.skipPrev = function() { ... };
SttTTS.prototype.setSpeed = function(rate) { ... };
SttTTS.prototype.updateProgress = function() { ... };
SttTTS.prototype.prefetchNext = function() { ... };
```

### 4.10c javascript/tts_buttons.js (inyección UI)

Inyección de botones según escenario + render del reproductor. Extiende `SttTTS.prototype`.

**Responsabilidades:**
- `injectButtons()`: dispatch según vista (DetailView/EditView → a+b; index → c)
- Escenario a: `injectTextareaButton(textarea)` — botón "Escuchar" junto a cada textarea configurado (excluir TinyMCE/WYSIWYG, patrón STT)
- Escenario b: `injectDetailAction()` — acción "Escuchar información destacada" en la vista de detalle
- Escenario c: `injectListviewAction()` — append `<li>` a `ul#actionLinkTop li.sugar_action_button ul.subnav` (y `#actionLinkBottom`), patrón `stic_Custom_Views`
- `getSelectedRecords()`: leer IDs seleccionados del `document.MassUpdate` form (ver abajo)
- `renderPlayer()`: construir el DOM del reproductor (controles + progreso + velocidad)
- `showError(msg)`: mostrar errores al usuario (patrón STT)

**Escenario c — recuperación de IDs seleccionados:**
```javascript
SttTTS.prototype.getSelectedRecords = function() {
    sugarListView.get_checks();  // popula document.MassUpdate.uid
    var uids = document.MassUpdate.uid.value;
    if (!uids) return null;
    return {
        uids: uids.split(','),
        current_query_by_page: document.MassUpdate.current_query_by_page.value,
        lvso: document.MassUpdate.lvso.value,
        select_entire_list: document.MassUpdate.select_entire_list.value
    };
};
```

### 4.11 themes/SinergiaCRMCustom/tts_client.css

Estilo slate monocromo coherente con `stt_client.css` del STT:
- Botones TTS (play, stop, skip) con SVG inline
- Reproductor flotante o inline (definir en diseño)
- Estados: idle, loading, playing, paused, error
- Dark theme (`.yui-skin-sam`)
- Mobile responsive
- `prefers-reduced-motion`

### 4.12 Sql/tts_deepgram_usage.sql

```sql
CREATE TABLE IF NOT EXISTS tts_usage (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    created_at DATETIME NOT NULL,
    char_count INT NOT NULL DEFAULT 0,
    language VARCHAR(10),
    module VARCHAR(100),
    provider VARCHAR(50),
    scenario VARCHAR(1),
    record_id VARCHAR(36),
    INDEX idx_user_date (user_id, created_at),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Settings tipo TTS
INSERT INTO stic_settings ... ('TTS_ENABLED', '1', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_DEFAULT_LANGUAGE', 'es', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_AVAILABLE_LANGUAGES', 'es,en,ca', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_DEFAULT_VOICE', '{voz}', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_TEXTAREAS', '', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_HIGHLIGHT_FIELDS', '', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_DAILY_CHAR_LIMIT', '50000', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_MAX_RECORDS_LIST', '50', 'TTS', ...);
INSERT INTO stic_settings ... ('TTS_LIST_SEPARATOR', 'Registro siguiente.', 'TTS', ...);

-- Settings tipo TTS_DEEPGRAM
INSERT INTO stic_settings ... ('TTS_DEEPGRAM_API_KEY', '', 'TTS_DEEPGRAM', ...);
INSERT INTO stic_settings ... ('TTS_DEEPGRAM_MODEL', 'aura-2', 'TTS_DEEPGRAM', ...);
INSERT INTO stic_settings ... ('TTS_DEEPGRAM_VOICE', '{voz}', 'TTS_DEEPGRAM', ...);
```

### 4.13 scripts/post_install.php

Adaptar del STT:
1. Ejecutar SQL (`tts_deepgram_usage.sql`) → crear `tts_usage` + insertar settings
2. Escribir `config_override.php`:
   ```php
   $sugar_config['tts_provider'] = 'deepgram';
   $sugar_config['deepgram_tts_endpoint'] = 'https://api.eu.deepgram.com/v1/speak';
   $sugar_config['tts_max_chars_per_request'] = 2000;
   $sugar_config['tts_encoding'] = 'mp3';
   $sugar_config['tts_curl_timeout'] = 30;
   ```
3. `rebuild_extensions()` + limpieza caché

### 4.14 scripts/post_uninstall.php

1. `DROP TABLE IF EXISTS tts_usage`
2. `DELETE FROM stic_settings WHERE type IN ('TTS', 'TTS_DEEPGRAM')`
3. Eliminar las líneas de config_override.php (mismo patrón que STT)

### 4.15 Language/{ca,en,es}.tts_TTS.php

Strings para el cliente JS (labels `LBL_TTS_*`):
- `LBL_TTS_LISTEN` — "Escuchar"
- `LBL_TTS_LISTEN_HIGHLIGHTED` — "Escuchar información destacada"
- `LBL_TTS_LISTEN_MASS` — "Escuchar"
- `LBL_TTS_PLAY` — "Reproducir"
- `LBL_TTS_PAUSE` — "Pausar"
- `LBL_TTS_STOP` — "Detener"
- `LBL_TTS_NEXT` — "Siguiente registro"
- `LBL_TTS_PREV` — "Registro anterior"
- `LBL_TTS_SPEED` — "Velocidad"
- `LBL_TTS_PROGRESS` — "Registro {current} de {total}"
- `LBL_TTS_ERROR_GENERIC` — "No se ha podido generar el audio, inténtelo más tarde"
- `LBL_TTS_ERROR_EMPTY` — "No hay contenido para leer"
- `LBL_TTS_ERROR_LIMIT` — "Has alcanzado el límite diario de caracteres"
- `LBL_TTS_ERROR_NO_SELECTION` — "Selecciona al menos un registro"
- `LBL_TTS_ERROR_TOO_MANY` — "Selecciona un máximo de {max} registros"
- `LBL_TTS_LOADING` — "Preparando audio..."
- `LBL_TTS_SPEED_NORMAL` — "Normal"
- `LBL_TTS_SPEED_FAST` — "Rápido"
- `LBL_TTS_SPEED_SLOW` — "Lento"

---

## 5. Fases de implementación

### Fase 0: Prerrequisitos (1 día)
- [ ] Obtener API key de Deepgram con acceso Aura-2 TTS
- [ ] Confirmar voz española disponible en Aura-2 (nombre exacto del modelo)
- [ ] Verificar conectividad saliente a `api.eu.deepgram.com` desde producción
- [ ] Crear estructura de carpetas del paquete

### Fase 1: Backend — abstracción de proveedor (2 días)
- [ ] `TTSProviderInterface.php`
- [ ] `TTSProviderBase.php`
- [ ] `ttsTextFragmenter.php` (fragmentación ≤2000 en límites de frase/palabra)
- [ ] `ttsDeepgramProvider.php` (con `synthesize()` delegando en `TtsTextFragmenter`)
- [ ] `TTSProviderManager.php`
- [ ] `ttsCsrfUtils.php`
- [ ] Test: `php -l` en todos los archivos
- [ ] Test: llamar a `synthesize()` desde CLI con texto de prueba
- [ ] Test: `TtsTextFragmenter::fragment()` con textos de 10, 2000, 5000, 10000 chars

### Fase 2: Backend — endpoints (2.5 días)
- [ ] `ttsTextAssembler.php` (helper: "etiqueta: valor" desde bean)
- [ ] `ttsListviewOrder.php` (helper: reconstruir IDs ordenados de listview)
- [ ] `ttsSynthEp.php` (proxy chunked — flujo principal delegando en helpers)
- [ ] `ttsUsageEp.php`
- [ ] `ttsStringsEp.php`
- [ ] `tts_EntryPointRegistry.php`
- [ ] Test manual: `curl` al endpoint `ttsSynth` con texto simple → verificar audio
- [ ] Test: `TtsTextAssembler` omite campos vacíos y lee etiquetas correctas
- [ ] Test: `TtsListviewOrder` respeta orden con filtros y ordenación
- [ ] Test: `session_write_close()` no bloquea peticiones concurrentes

### Fase 3: Backend — SQL e instalación (1 día)
- [ ] `tts_deepgram_usage.sql`
- [ ] `post_install.php`
- [ ] `post_uninstall.php`
- [ ] `manifest.php`
- [ ] Test: instalar paquete en instancia de pruebas → verificar tabla + settings + config_override

### Fase 4: Frontend — inyección y botones (2 días)
- [ ] `ttsInjectorHook.php` (detección de vista, inyección de config + 3 JS en orden)
- [ ] `tts_Injector.php` (registro del hook)
- [ ] `tts_client.js` (orquestador: init, config, strings, fetch, usage, observer)
- [ ] `tts_buttons.js` — inyección de botones:
  - Escenario a: botón junto a textareas (excluyendo TinyMCE)
  - Escenario b: acción en vista detalle
  - Escenario c: item en menú masivo (`ul#actionLinkTop ... ul.subnav`)
  - `getSelectedRecords()` para escenario c
- [ ] Test: botones aparecen en las vistas correctas

### Fase 5: Frontend — reproductor (3 días)
- [ ] `tts_player.js` — cola de blobs + reproducción secuencial + prefetch
- [ ] Controles: play/pausa/stop, skip next/prev, velocidad (playbackRate)
- [ ] Indicador de progreso "registro X de N"
- [ ] `stop()` con `AbortController` cancela peticiones pendientes
- [ ] Reintento de fragmento (hasta 2 con backoff) en `tts_client.js`
- [ ] `tts_client.css` (estilo, dark theme, mobile, reduced-motion)
- [ ] Test: reproducción fluida en desktop y mobile
- [ ] Test: controles funcionan correctamente
- [ ] Test: stop cancela fragmentos pendientes

### Fase 6: i18n (0.5 días)
- [ ] `es_ES.tts_TTS.php`
- [ ] `ca_ES.tts_TTS.php`
- [ ] `en_us.tts_TTS.php`
- [ ] Test: cambiar idioma del CRM → verificar strings

### Fase 7: Empaquetado y pruebas integrales (2 días)
- [ ] Crear ZIP del paquete
- [ ] Instalar vía Module Loader en instancia limpia
- [ ] Test criterios de aceptación 1-14 (ver REQUISITOS_FUNCIONALES.md §11)
- [ ] Test coexistencia con STT (namespaces separados, sin conflictos)
- [ ] Test desinstalación (limpieza completa)
- [ ] README.md

**Estimación total: ~14 días**

---

## 6. Dependencias y prerrequisitos

| Dependencia | Estado | Notas |
|---|---|---|
| API key Deepgram Aura-2 | Pendiente | Confirmar acceso TTS en la cuenta |
| Voz española Aura-2 | Pendiente | Verificar nombre exacto del modelo (`aura-2-{voice}-es`) |
| STT_autoinstalable (referencia) | Disponible | `/application/SCRM_xavier.sinergiacrm.org/STT_autoinstalable` |
| Conectividad saliente producción | Pendiente | Verificar `api.eu.deepgram.com:443` |
| HTTPS en producción | Requisito oficial | Ya garantizado por system requirements |

---

## 7. Estrategia de pruebas

### 7.1 Pruebas unitarias (backend)
- `ttsDeepgramProvider::synthesize()` con textos de diversas longitudes (10, 500, 2000, 5000, 10000 chars)
- Fragmentación: verificar que los cortes respetan límites de frase/palabra
- Manejo de errores: simular 429, 413, 500, timeout de curl

### 7.2 Pruebas de integración (endpoint)
- `curl` directo a `ttsSynth` con payloads de cada escenario (a, b, c)
- Verificar headers de respuesta (`Content-Type: audio/mpeg`, `X-TTS-Char-Count`)
- Verificar audio reproducible (guardar a fichero y reproducir)
- `session_write_close()`: lanzar 2 peticiones simultáneas y verificar que no se bloquean

### 7.3 Pruebas funcionales (por escenario)
- **a)** Botón aparece en textareas configurados, no en TinyMCE, reproduce contenido
- **b)** Acción "Escuchar info destacada" lee campos en orden, omite vacíos
- **c)** Acción masiva lee registros seleccionados en orden listview, skip funciona, progreso correcto
- **Reproductor** play/pausa/stop/skip/velocidad funcionan en desktop y mobile
- **Límite diario** bloquea al superar `TTS_DAILY_CHAR_LIMIT`
- **Master switch** `TTS_ENABLED=0` oculta todo

### 7.4 Pruebas de coexistencia
- Instalar TTS en instancia con STT ya instalado
- Verificar que ambos funcionan independientes
- Verificar namespaces separados (TTS_DEEPGRAM_* vs DEEPGRAM_*)

### 7.5 Pruebas de instalación/desinstalación
- Instalar en instancia limpia → verificar tabla, settings, config_override
- Desinstalar → verificar limpieza completa
- Reinstalar → verificar idempotencia

---

## 8. Riesgos y mitigaciones

| Riesgo | Probabilidad | Impacto | Mitigación |
|---|---|---|---|
| Voz española de Aura-2 no disponible o de baja calidad | Bajo | Alto | Verificar en Fase 0 antes de empezar; alternativa: OpenAI TTS (Fase 2) |
| Latencia alta de Deepgram EU desde el servidor | Bajo | Medio | Medir TTFB en Fase 0; arquitectura chunked mitiga (audio empieza pronto) |
| Concurrencia REST limitada (15) | Medio | Medio | El cliente serializa peticiones (una por fragmento); no hay paralelismo agresivo |
| Cola de blobs introduce gaps de audio | Medio | Medio | Bufferizar 1-2 fragmentos por delante; probar con textos largos |
| Reconstrucción de orden listview (escenario c) compleja | Medio | Medio | Reutilizar lógica existente de MassUpdate; probar con filtros y ordenaciones |
| Texto con caracteres especiales rompe la síntesis | Bajo | Bajo | Escapar/limpiar texto antes de enviar a Deepgram |
| Coexistencia con STT: conflictos de CSS/JS | Bajo | Bajo | Namespaces separados, prefijos `tts_` vs `stt_`, selectores CSS distintos |

---

## 9. Pendientes de diseño técnico

- Nombre exacto de la voz española de Aura-2 (verificar en consola Deepgram)
- Algoritmo de fragmentación de texto en `ttsTextFragmenter.php`: prioridad de corte en `.` `!` `?` `\n` `;` → `,` → ` ` → corte duro (definir umbrales exactos)
- Tamaño del buffer de cola de blobs (¿1 o 2 fragmentos por delante?)
- UI del reproductor: ¿flotante (fixed bottom) o inline?
- Formato del separador audible entre registros: el texto "Registro siguiente." se sintetiza como un fragmento más
- Verificar si `BeanFactory::getBean` + `ACLAccess('view')` es suficiente para el control de acceso
- Construcción de etiquetas de campo: ¿leer de `mod_strings` del módulo o de `vardefs['label']`?
