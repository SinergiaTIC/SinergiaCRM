# TTS Text-to-Speech

Módulo de Text-to-Speech para SinergiaCRM usando la API de Deepgram Aura-2.

## Instalación

1. Ve a **Admin → Module Loader**
2. Sube el archivo `TTS_autoinstalable.zip`
3. Haz clic en **Install**
4. Sigue las instrucciones en pantalla

## Configuración posterior

Tras la instalación:

1. Ve a **Admin → stic_Settings**
2. Configura los siguientes ajustes:

### Tipo TTS_DEEPGRAM
| Nombre | Descripción |
|--------|------------|
| `TTS_DEEPGRAM_API_KEY` | API key de Deepgram |

### Tipo TTS
| Nombre | Descripción | Ejemplo |
|--------|------------|---------|
| `TTS_ENABLED` | Activar TTS (1/0) | `1` |
| `TTS_DEFAULT_LANGUAGE` | Idioma por defecto | `es` |
| `TTS_AVAILABLE_LANGUAGES` | Idiomas disponibles | `es,en,ca` |
| `TTS_TEXTAREAS` | Módulos:campos con textarea | `Accounts:description;Notes:description` |
| `TTS_HIGHLIGHT_FIELDS` | Módulos:campos destacados | `Accounts:name,phone_office,billing_address_city` |
| `TTS_DAILY_CHAR_LIMIT` | Límite diario de caracteres | `50000` |
| `TTS_MAX_RECORDS_LIST` | Máx. registros en lista | `50` |
| `TTS_LIST_SEPARATOR` | Separador entre registros | `Registro siguiente.` |

3. Cierra sesión y vuelve a entrar

## Uso

| Escenario | Descripción |
|-----------|-------------|
| Textarea | Botón ▶ junto al campo textarea |
| Vista detalle | Botón "Escuchar info destacada" |
| Vista lista | Seleccionar registros → Bulk Action → "Escuchar seleccionados" |

## Soporte de idiomas

- Español (es)
- Catalán (ca)
- Inglés (en)

## Coexistencia con STT

TTS usa namespace `SttTTS` y prefijo `tts_`. STT usa namespace `SttSTT` y prefijo `stt_`. No hay conflictos.

## Desinstalación

Ve a **Admin → Module Loader** y desinstala el módulo.
