# AAES Attendance Ticker - Pasador de asistencias (v1.0.0)

Paquete Module Loader para SinergiaCRM que añade el **Pasador de asistencias**: herramienta responsive (móvil/tablet) para pasar lista de sesiones, integrada dentro del módulo de **Asistencias**.

Acceso de la vista integrada:

```
index.php?module=stic_Attendances&action=AAESAttendanceTicker
```

y desde el menú del módulo Asistencias ("Pasador de asistencias").

## Qué instala

| Elemento | Destino |
|---|---|
| Clases del pasador (4 ficheros PHP) | `custom/include/AAES/AttendanceTicker/` |
| Etiquetas de la aplicación (5 idiomas: ca/es/en/gl/eu) | `custom/Extension/application/Ext/Language/*.AAES_AttendanceTicker.php` |
| Registro de la acción integrada (`ActionFileMap`) | `custom/Extension/modules/stic_Attendances/Ext/ActionFileMap/AAES_AttendanceTicker.php` |
| Enlace del menú del módulo (guard ACL) | `custom/Extension/modules/stic_Attendances/Ext/Menus/AAES_AttendanceTicker.php` |
| Etiquetas del módulo (5 idiomas) | `custom/Extension/modules/stic_Attendances/Ext/Language/*.lang.AAES_AttendanceTicker.php` |

**No añade** campos a la BD, ni estados, ni modifica ningún fichero de los módulos core (asistencias, eventos, inscripciones, proyecto).

## Requisitos previos

- SinergiaCRM 2.13.x (compatible con 2.13.\*).
- Cron activo: el generador de asistencias del core (`createAttendances`) mantiene las asistencias "regulares" al día; el pasador solo añade creación al instante y la vía "fuera del censo".
- Sesión CRM (usuario normal o usuario genérico de dispositivo compartido, con ACL mínima: lectura de Eventos, Sesiones, Inscripciones, Proyecto y Contactos + escritura de Asistencias; nunca admin).

## Instalación

1. Admin → **Module Loader** → subir `AAES-AttendanceTicker-v1.0.0.zip` → **Instalar**.
   - Al terminar verás una lista larga de líneas "Reconstruyendo ActionViewMap / ActionFileMap / Menus / Vardefs...": es el comportamiento estándar del núcleo con **cualquier** paquete (reconstruye las extensiones compiladas para activar la nueva acción y el menú); no es un error y termina al 100% en segundos. No hace falta repetirla: se puede cerrar y quedar en pausa antes de hacer la reparación posterior.
2. **Quick Repair & Rebuild** (Admin → Repair) para compilar las extensiones (`ActionFileMap`, `Menus`) y regenerar el idioma.
3. Vaciar caché si conviene: `rm -rf cache/*` (en despliegues docker: con el contenedor www-data).
4. Verificar: en Administración no aparecen pantallas nuevas; en el módulo **Asistencias** aparece el enlace del menú y la URL integrada responde (sin sesión → pantalla de login del CRM).
5. *(Opcional)* Ventana de días del buscador de sesiones pasadas (por defecto 30): ejecutar manualmente el SQL `install/AAES_Ticker_search_days_setting.sql` (crea el registro `AAES_TICKER_SEARCH_DAYS` en `stic_settings`) o editar el valor a mano (1–365) en Configuración → `stic_Settings` según necesite la entidad.

## Reglas de uso relevantes

- Solo se listan/pasan sesiones **de hoy o pasadas** (nunca futuras) dentro de la ventana configurada.
- Marcaje "Sí" al instante por bean; cambio de estado entre los 4 estados estándar (Sí / Parcial / No justificado / No injustificado); "Han venido todos" solo marca pendientes inscritos.
- **Fuera del censo**: inscripciones con estado definido distinto de confirmado/participa (cualquier estado custom de la entidad) aparecen en su sección; "Ha venido" le genera la asistencia **sin cambiar el estado de la inscripción**.
- **Deshacer** semántica dual: inscripciones del modelo core → se vacía el estado (el registro persiste); el resto → borrado lógico y recálculo automático de horas/contadores.
- Toda escritura vía POST + CSRF; las acciones quedan en `suitecrm.log` (prefijo `AAES TICKER`).

## Desinstalación

1. Admin → **Module Loader** → AAES Attendance Ticker → **Uninstall** (retira los ficheros copiados).
2. Ejecutar de nuevo **Quick Repair & Rebuild** para limpiar las extensiones compiladas; si quedara algún fichero de extensión compilado, se puede borrar a mano (sin riesgo: son ficheros generados):
   - `custom/modules/stic_Attendances/Ext/ActionFileMap/action_file_map.ext.php`
   - `custom/modules/stic_Attendances/Ext/Menus/menus.ext.php`
   - ficheros de idioma compilados si los hubiera, y vaciar la caché (`/cache`).
3. *(Opcional)* Borrar el registro del setting (`DELETE FROM stic_settings WHERE name='AAES_TICKER_SEARCH_DAYS'`).

## Soporte

Documentación técnica: `IMPL-AAES-001` (`stic/aes/docs/reference/aaes-attendance-ticker.md` en el handbook de IA del equipo AES).
