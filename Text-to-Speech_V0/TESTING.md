# Plan de Pruebas de Usabilidad — TTS Text-to-Speech v1.0.0

## Instrucciones

Marca cada caso con `[x]` a medida que lo verifiques.
Reporta cualquier fallo con una breve descripción junto al caso.

---

## 🔧 Configuración Previa

- [X] Verificar que `TTS_ENABLED = 1` en Admin → stic_Settings
- [X] Verificar que `TTS_DEEPGRAM_API_KEY` tiene una key válida
- [X] Configurar `TTS_TEXTAREAS` para al menos un módulo (ej: `Accounts:description;Notes:description`)
- [X] Configurar `TTS_HIGHLIGHT_FIELDS` (ej: `Accounts:name,phone_office,billing_address_city`)
- [X] Configurar `TTS_BAR_COLOR` (ej: `#1db954`)
- [ ] Configurar `TTS_DAILY_TIME_LIMIT` (ej: `60` para 60 min o `-1` para ilimitado)
- [X] Cerrar sesión y volver a entrar
- [X] `rm -rf cache/*` + Ctrl+F5

---

## 📝 Escenario A: Textarea

- [X] **A1** — Ir a una cuenta con texto en el campo configurado → ver botón ▶ al lado del textarea
- [X] **A2** — Hacer clic en ▶ → el audio comienza a reproducirse
- [X] **A3** — La barra del reproductor aparece abajo con nombre del registro
- [X] **A4** — Hacer clic en pausa → se pausa → clic en play → reanuda
- [X] **A5** — Hacer clic en stop → se detiene, barra desaparece
- [X] **A6** — Texto muy largo (>2000 chars) → se reproduce completo (fragmentación automática)
- [X] **A7** — Campo vacío → no muestra error o muestra mensaje adecuado

---

## 📋 Escenario B: Vista Detalle

- [X] **B1** — Ir a una cuenta en vista detalle → ver "▶ Escuchar info destacada" en menú de acciones
- [X] **B2** — Hacer clic → reproduce los campos configurados concatenados
- [X] **B3** — Navegar a otra cuenta (PLAY activo) → sigue sonando sin interrupción
- [ ] **B4** — Navegar a otra cuenta con PAUSA → reproductor muestra el estado pausado con currentIndex y progreso correctos
- [ ] **B5** — Recargar página (F5) con PLAY activo → no restaura sesión
- [ ] **B6** — Recargar página (F5) con PAUSA → no restaura sesión

---

## 📑 Escenario C: Vista Lista (Playlist)

- [X] **C1** — Ir a listado de cuentas → seleccionar 2-3 registros → Bulk Action → "Escuchar seleccionados"
- [ ] **C2** — Se reproduce el primer registro → nombre correcto en barra
- [ ] **C3** — Al terminar, pasa al siguiente registro automáticamente
- [ ] **C4** — Nombres de los registros aparecen en la playlist (desplegable al lado izquierdo)
- [ ] **C5** — Hacer clic en un nombre de la playlist → salta a ese registro
- [ ] **C6** — Botones prev/next → saltan entre registros correctamente
- [ ] **C7** — Al llegar al último, el playback termina limpiamente
- [ ] **C8** — Seleccionar más de `TTS_MAX_RECORDS_LIST` → muestra error o limita

---

## 🧭 Navegación y Persistencia

- [ ] **N1** — PLAY activo → navegar a distinto módulo → el audio se detiene (módulo no coincide)
- [X] **N2** — PLAY activo → navegar al mismo módulo → sigue sonando sin corte
- [ ] **N3** — PAUSA → navegar al mismo módulo → la barra reaparece pausada con "N de M" correcto
- [ ] **N4** — PAUSA → navegar a registro distinto (mismo módulo) → se descarta estado (canAutoPlay=false)
- [ ] **N5** — PLAY activo → esperar >5 min sin interactuar → navegar → sesión expirada (TTL 5 min)
- [ ] **N6** — PAUSA → recargar F5 → estado limpio

---

## 🎛️ Controles del Reproductor

- [X] **Ctl1** — Play/Pause toggle: icono cambia ▶ ↔ ⏸
- [X] **Ctl2** — Stop: barra desaparece, audio se detiene, cache se limpia
- [X] **Ctl3** — Velocidad 1.5x → el audio se acelera
- [X] **Ctl4** — Velocidad 0.5x → el audio se ralentiza
- [X] **Ctl5** — Velocidad 1x → normal
- [X] **Ctl6** — Clic en barra de progreso → seek a esa posición
- [X] **Ctl7** — Tiempo (mm:ss) se actualiza correctamente
- [X] **Ctl8** — Playlist: desplegar menú → ver nombres reales de registros

---

## 🎨 Barra de Color (TTS_BAR_COLOR)

- [ ] **Color1** — `TTS_BAR_COLOR = #ff0000` → barra roja (requiere `rm -rf cache/*` + Ctrl+F5)
- [ ] **Color2** — `TTS_BAR_COLOR = #1db954` → barra verde
- [ ] **Color3** — Dejar vacío → barra usa `#181818` por defecto
- [ ] **Color4** — Cambio requiere `rm -rf cache/*` + Ctrl+F5

---

## ⚠️ Límites y Errores

- [X] **Err1** — Superar límite diario → HTTP 429 + mensaje de error
- [ ] **Err2** — API key inválida → mensaje de error
- [ ] **Err3** — Sin conexión → mensaje de error genérico
- [X] **Err4** — `TTS_ENABLED = 0` → botones TTS no aparecen
- [X] **Err5** — `TTS_DEEPGRAM_API_KEY` vacío → botones TTS no aparecen
- [ ] **Err6** — `TTS_DAILY_TIME_LIMIT = 1` (1 min ≈ 600 chars) → superar el límite da HTTP 429 + mensaje "Límite diario de tiempo alcanzado"

---

## 🌐 Traducciones

- [ ] **L10n1** — Interfaz en español → textos en español
- [ ] **L10n2** — Interfaz en catalán → textos en catalán
- [X] **L10n3** — Interfaz en inglés → textos en inglés
- [X] **L10n4** — Síntesis respeta `TTS_DEFAULT_LANGUAGE`

---

## 📱 Responsive / UX

- [ ] **UX1** — En móvil (<768px) la barra se adapta
- [X] **UX2** — Barra no se superpone con contenido
- [X] **UX3** — Barra se oculta tras 5s de inactividad (CSS idle timeout)
- [X] **UX4** — Botón de textarea aparece correctamente al lado del campo

---

## 🔄 Cache

- [ ] **Cache1** — Reproducir texto → 2ª vez instantáneo (cache hit)
- [ ] **Cache2** — DevTools → Application → Cache Storage → ver `tts-audio-v1`
- [ ] **Cache3** — Stop limpia cache → 3ª reproducción llama a la API de nuevo

---

## 🔁 Regresión STT

- [ ] **Reg1** — Módulo STT existente (namespace `SttSTT`) sigue funcionando

---

## Resumen

| Sección | Total | Pasados | Fallos |
|---------|-------|---------|--------|
| Configuración | 8 | 7 | |
| Escenario A: Textarea | 7 | 7 | |
| Escenario B: Detalle | 6 | 3 | |
| Escenario C: Lista | 8 | 1 | |
| Navegación | 6 | 1 | |
| Controles | 8 | 8 | |
| Barra de color | 4 | 0 | |
| Límites/Errores | 6 | 3 | |
| Traducciones | 4 | 2 | |
| Responsive/UX | 4 | 3 | |
| Cache | 3 | 0 | |
| Regresión STT | 1 | 0 | |
| **Total** | **64** | **35** | |
