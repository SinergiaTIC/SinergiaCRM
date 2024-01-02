# Funcionalidades módulos Eventos, Inscripciones, Sesiones y Asistencias

## stic_Events

Se ha modificado la acción addLPOToEventRegistrations (antes AddObjetivos) para añadir los miembros de una LPO a un determinado evento mediante el botón correspondiente.

Como novedad se ha añadido la fecha actual como `Fecha de inscripción` ya que se trata de un campo obligatorio.






## Lista de funcionalidades añadidas:
- Añadir miembros LPO como inscripciones a eventos.
- Recalcular el estado de los inscritos a un evento.
- Crear nombre de las sesiones.
- Incluir gestion de horas en logic hook de la sesion
- Calcular la duración de la sesión
- Recalcular el total de horas de un evento
- Indicador campo automático nombre sesiones
- Indicador campo automático nombre assistencias
- Indicador campo automático nombre inscripciones
- Creación de asistencias
- Crear asistente de creación de sesiones
  - Corregir la hora de las sesiones al crearlas desde el asistente.
- Cálculos de total de horas y porcentaje de asistencias por inscripción.
- Calculo de asistentes por sesión
- Incluir versión actualizada del LH de registrations
  - Limpiar lineas comentadas en LH actual
- Establecer en sesiones los siguiente campos no-inline: (complican la validación JS en la edición inline)
  - start_date
  - Final Date:
  - Total attendances:
  - Validated attendances:
  - Session Duration:
- Validaciones JS en sesiones
- Añadir calculo del día de la semana
- Validaciones JS en eventos
- Validaciones JS en inscripciones
- Validaciones JS en asistencias
- Revisar documentación de funciones
- Tarea programada creación de asistencias
- Mass Update Events
- Mass Update Registrations
- Mass Update Sessions
- Mass Update Attendances

## Lista de funcionalidades pendientes de añadir:
- Resolver problema de lentitud al crear sesiones

