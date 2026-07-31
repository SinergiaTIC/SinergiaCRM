# Plan de Pruebas Post-Revisión — TTS v1.0.0

> Generado tras la revisión de seguridad y consistencia (Fases 2-5).
> Fecha: 2026-07-30
>
> Este plan se centra en verificar que **los fixes aplicados no han roto nada** y que **las correcciones funcionan correctamente**.

---

## Configuración previa

- [ ] `TTS_ENABLED = 1` en Admin → stic_Settings
- [ ] `TTS_DEEPGRAM_API_KEY` tiene una key válida
- [ ] `TTS_DEFAULT_LANGUAGE = es`
- [ ] `TTS_DEFAULT_VOICE = aura-2-alvaro-es` (o vacío para usar el default)
- [ ] `TTS_TEXTAREAS` configurado (ej: `Accounts:description;Notes:description`)
- [ ] `TTS_HIGHLIGHT_FIELDS` configurado (ej: `Accounts:name,phone_office,billing_address_city`)
- [ ] `TTS_DAILY_CHAR_LIMIT = 50000`
- [ ] `TTS_MAX_RECORDS_LIST = 50`
- [ ] `TTS_BAR_COLOR = #181818` (o el color que prefieras)
- [ ] Cerrar sesión y volver a entrar
- [ ] `rm -rf cache/*` + Ctrl+F5

---

## Bloque 1: Funcionalidad básica (regresión)

> Verifica que los fixes de seguridad y consistencia no han roto la funcionalidad existente.

### 1.1 Textarea (Escenario A)

- [ ] **A1** — Ir a EditView de una cuenta con texto en `description` → ver botón ▶ "Listen" al lado del campo
- [ ] **A2** — Hacer clic en ▶ → el audio comienza a reproducirse
- [ ] **A3** — La barra del reproductor aparece abajo con el nombre del registro
- [ ] **A4** — Hacer clic en pausa → se pausa → clic en play → reanuda
- [ ] **A5** — Hacer clic en stop → se detiene, barra desaparece
- [ ] **A6** — Texto largo (>2000 chars) → se reproduce completo (fragmentación)
- [ ] **A7** — Campo vacío → mensaje "No hay texto para leer" (o equivalente en el idioma)

### 1.2 Vista Detalle (Escenario B)

- [ ] **B1** — Ir a DetailView de una cuenta → ver "Listen fields" en menú Actions
- [ ] **B2** — Hacer clic → reproduce los campos configurados concatenados
- [ ] **B3** — Navegar a otra cuenta (mismo módulo) con PLAY activo → sigue sonando
- [ ] **B4** — Pausar → navegar a otra cuenta → reproductor muestra estado pausado
- [ ] **B5** — Recargar (F5) con PLAY activo → sesión se limpia (no restaura)
- [ ] **B6** — Recargar (F5) con PAUSA → sesión se limpia

### 1.3 Vista Lista (Escenario C)

- [ ] **C1** — Ir a listado de cuentas → seleccionar 2-3 registros → Bulk Action → "Listen selected"
- [ ] **C2** — Se reproduce el primer registro → nombre correcto en barra
- [ ] **C3** — Al terminar, pasa al siguiente registro automáticamente
- [ ] **C4** — Nombres de los registros aparecen en la playlist (desplegable izquierdo)
- [ ] **C5** — Clic en un nombre de la playlist → salta a ese registro
- [ ] **C6** — Botones prev/next → saltan entre registros
- [ ] **C7** — Al llegar al último, el playback termina limpiamente
- [ ] **C8** — Seleccionar >50 registros → muestra error "máximo de 50 registros"

---

## Bloque 2: Fixes de seguridad (Fase 2)

> Verifica que las correcciones de seguridad funcionan y no permiten ataques.

### 2.1 SQL Injection — ttsListviewOrder.php

> **Importante**: Este test requiere manipular el formulario con DevTools.

- [ ] **SEC1** — En la vista de lista, seleccionar "Select All" (checkbox superior) → Bulk Action → "Listen selected" → **funciona correctamente** (no error 500)
- [ ] **SEC2** — Abrir DevTools → Console → ejecutar:
  ```js
  // Simular un ataque de SQL injection en orderBy
  document.MassUpdate.orderBy.value = "name; DROP TABLE tts_usage--";
  document.MassUpdate.select_entire_list.value = "1";
  ```
  Luego pulsar "Listen selected" → **no se ejecuta el DROP TABLE** (el campo no existe en field_defs, se ignora)
- [ ] **SEC3** — En DevTools → Console → ejecutar:
  ```js
  // Simular inyección en current_query_by_page
  document.MassUpdate.current_query_by_page.value = '{"searchFields":{"name":"x\' OR 1=1--"}}';
  document.MassUpdate.select_entire_list.value = "1";
  ```
  Luego pulsar "Listen selected" → **no hay error 500 ni fuga de datos** (el valor se escapa con `$db->quote()`)

### 2.2 Doble conteo de uso eliminado

> Verifica que el uso se registra **una sola vez** (en el servidor, no en el cliente).

- [ ] **SEC4** — Abrir DevTools → Network → filtrar por `entryPoint`
- [ ] **SEC5** — Reproducir un texto (Escenario A o B)
- [ ] **SEC6** — Verificar en Network que **NO aparece petición POST a `ttsUsage`**
- [ ] **SEC7** — Verificar que solo aparece `ttsSynth` (POST) y `ttsStrings` (GET)
- [ ] **SEC8** — Reproducir varios textos y comprobar que el límite diario se consume a velocidad normal (no 2x)

### 2.3 CSRF mejorado

> Verifica que las peticiones sin origen válido se rechazan.

- [ ] **SEC9** — En DevTools → Console → ejecutar un POST sin headers de origen:
  ```js
  fetch('index.php?entryPoint=ttsRecordNames', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({module: 'Accounts', uids: ['test']})
  }).then(r => r.json()).then(console.log)
  ```
  → **Debe devolver error 403** "Origen de petición inválido" (porque fetch desde console no envía Origin/Referer correctamente en algunos navegadores)
- [ ] **SEC10** — Reproducir audio normalmente (clic en botón) → **funciona** (el navegador envía Origin/Referer automáticamente)

---

## Bloque 3: Fixes de consistencia (Fase 3)

### 3.1 Settings muertos eliminados

- [ ] **CON1** — Ir a Admin → stic_Settings → buscar `TTS_DEEPGRAM_MODEL` → **no debe existir**
- [ ] **CON2** — Buscar `TTS_DEEPGRAM_VOICE` → **no debe existir**
- [ ] **CON3** — Verificar que `TTS_DEFAULT_VOICE` sigue existiendo y funciona
- [ ] **CON4** — Verificar README del paquete (`TTS_autoinstalable/README.md`) no menciona MODEL ni VOICE

### 3.2 Bool traducido

> Requiere un módulo con campo bool configurado en TTS_HIGHLIGHT_FIELDS.

- [ ] **CON5** — Si hay un campo bool (ej: `stic_FollowUps:cancelled`) en highlight fields → reproducir → el TTS dice "Sí"/"No" (o "1"/"0") en el idioma de la interfaz, **no "Yes"/"No" en inglés**

### 3.3 Errores traducidos al español

- [ ] **CON6** — En DevTools → Console → ejecutar:
  ```js
  fetch('index.php?entryPoint=ttsUsage', {
    method: 'POST',
    headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
    body: JSON.stringify({})
  }).then(r => r.json()).then(console.log)
  ```
  → El error debe estar en español: **"Recuento de caracteres inválido."**
- [ ] **CON7** — Ejecutar:
  ```js
  fetch('index.php?entryPoint=ttsRecordNames', {
    method: 'POST',
    headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
    body: JSON.stringify({})
  }).then(r => r.json()).then(console.log)
  ```
  → Error en español: **"Parámetros inválidos."**

### 3.4 post_uninstall robusto

- [ ] **CON8** — (Opcional, solo si se desinstala) Desinstalar el módulo → verificar que `config_override.php` queda limpio de líneas `tts_` y `deepgram_tts_` **sin eliminar líneas ajenas**

---

## Bloque 4: Fixes de bugs funcionales (Fase 4)

### 4.1 Fecha localizada

> Requiere un módulo con campo datetime en TTS_HIGHLIGHT_FIELDS.

- [ ] **BUG1** — Si hay un campo de fecha (ej: `stic_FollowUps:start_date`) en highlight fields → reproducir → el TTS lee la fecha en formato legible (ej: "30/07/2026 14:30") **no "2026 guion 07 guion 30"**

### 4.2 Event listeners y Object URLs limpiados

- [ ] **BUG2** — Reproducir audio → pulsar Stop → en DevTools → Memory → tomar heap snapshot → **no hay fugas significativas de AudioElements**
- [ ] **BUG3** — Reproducir → Stop → Reproducir de nuevo → funciona correctamente (no hay audio fantasma)
- [ ] **BUG4** — Reproducir → Stop rápidamente antes de que termine → en DevTools → Application → Cache Storage → **no hay Object URLs huérfanas**

---

## Bloque 5: Controles del reproductor

- [ ] **CTL1** — Play/Pause: icono cambia ▶ ↔ ⏸
- [ ] **CTL2** — Stop: barra desaparece, audio se detiene
- [ ] **CTL3** — Velocidad 1.5x → audio acelera
- [ ] **CTL4** — Velocidad 0.5x → audio ralentiza
- [ ] **CTL5** — Velocidad 1x → normal
- [ ] **CTL6** — Clic en barra de progreso → seek
- [ ] **CTL7** — Tiempo (mm:ss) se actualiza
- [ ] **CTL8** — Playlist desplegable muestra nombres reales

---

## Bloque 6: Caché

- [ ] **CACHE1** — Reproducir texto → 2ª vez instantáneo (cache hit)
- [ ] **CACHE2** — DevTools → Application → Cache Storage → ver `tts-audio-v1`
- [ ] **CACHE3** — Stop limpia cache → 3ª reproducción llama a la API de nuevo

---

## Bloque 7: Límites y errores

- [ ] **ERR1** — Superar `TTS_DAILY_CHAR_LIMIT` → HTTP 429 + "Límite diario de caracteres alcanzado."
- [ ] **ERR2** — API key inválida (cambiar temporalmente) → mensaje de error
- [ ] **ERR3** — `TTS_ENABLED = 0` → botones TTS no aparecen
- [ ] **ERR4** — `TTS_DEEPGRAM_API_KEY` vacío → botones TTS no aparecen
- [ ] **ERR5** — `TTS_DAILY_TIME_LIMIT = 1` → superar ~600 chars → HTTP 429 + "Límite diario de tiempo alcanzado."

---

## Bloque 8: Traducciones

- [ ] **L10N1** — Cambiar idioma a español → textos en español
- [ ] **L10N2** — Cambiar idioma a catalán → textos en catalán
- [ ] **L10N3** — Cambiar idioma a inglés → textos en inglés
- [ ] **L10N4** — Síntesis respeta `TTS_DEFAULT_LANGUAGE` (no el idioma de la interfaz)

---

## Bloque 9: Regresión STT

- [ ] **REG1** — Módulo STT existente (namespace `SttSTT`, prefijo `stt_`) sigue funcionando sin conflictos

---

## Resumen

| Bloque | Descripción | Casos | Prioridad |
|--------|-------------|-------|-----------|
| 1 | Funcionalidad básica (regresión) | 21 | 🔴 Crítica |
| 2 | Fixes de seguridad | 10 | 🔴 Crítica |
| 3 | Fixes de consistencia | 8 | 🟡 Media |
| 4 | Fixes de bugs funcionales | 4 | 🟡 Media |
| 5 | Controles del reproductor | 8 | 🟡 Media |
| 6 | Caché | 3 | 🟢 Baja |
| 7 | Límites y errores | 5 | 🟡 Media |
| 8 | Traducciones | 4 | 🟡 Media |
| 9 | Regresión STT | 1 | 🔴 Crítica |
| **Total** | | **64** | |

---

## Notas

- Los tests de seguridad (Bloque 2) requieren DevTools. Lee las instrucciones con cuidado.
- Los tests de bool (CON5) y fecha (BUG1) requieren campos específicos configurados en `TTS_HIGHLIGHT_FIELDS`. Si no los tienes, configura temporalmente:
  - Bool: añade `stic_FollowUps:cancelled` (o cualquier campo bool)
  - Fecha: añade `stic_FollowUps:start_date` (o cualquier campo datetime)
- Tras cambiar settings, recuerda: cerrar sesión + entrar + `rm -rf cache/*` + Ctrl+F5
- Si algo no funciona, revisa la consola del navegador (F12) para ver errores JS
