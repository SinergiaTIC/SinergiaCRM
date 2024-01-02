# Funcionalidades y cambios en el  módulo de validación de datos

- El módulo `stic_DataCheckActions` ahora se llama `stic_Validation_Actions`.
- El módulo `stic_DataAnalyzer` no se usa, puesto que contenía exclusivamente la lógica de módulo Validation Actions, y ahora esas funciones se han trasladado dentro del módulo, en la ruta `modules/stic_Validation_Actions/DataAnalyzer/functions`

- Las funciones de validación usadas en cada una de las tareas se han trasladado al fichero `SticInclude/Utils.php` desde donde son usadas por cada 

- En la busqueda de duplicados de personas se ha añadido soporte para que se rellene el NIF/NIE de ceros a la izquierda antes de hacer la comparación.

- Existe una acción programada en el controller.php del módulo que permite ejecutar las tareas de validación asociadas a un planificador, usaqndo la siguiente sintaxis desde el navegador: `location.href='index.php?module=stic_Validation_Actions&action=runValidationActions&scheduler=<NombreLiteralDelSChedulerEnElIdiomaDeLaInstancia&no-incremental'` por ejemplo `location.href='index.php?module=stic_Validation_Actions&action=runValidationActions&scheduler=SinergiaCRM - General data validation&no-incremental'`

- Se ha añadido el campo `registration_date` al array de campos a validar en  la tarea de validación de __Inscripciones Datos Principales__ por ser un campo obligatorio en el módulo.

- Se ha trasladado la lógica de la función que llama a la tarea programada que ejecuta las accciones de validación (`validationActions`) al fichero `modules/stic_Validation_Actions/Utils.php` 