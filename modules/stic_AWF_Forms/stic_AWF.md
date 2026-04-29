# Formularios Web Avanzados

## Introducción
Los formularios web son uno de los canales habituales a través de los que una entidad recibe información de su base social: donativos, altas de socios, recogidas de firmas, suscripciones a boletines electrónicos, inscripciones a eventos, peticiones de servicios, etc.

Que la información recibida por esta vía quede directamente incorporada al CRM de la entidad evita cualquier proceso de traspaso o de registro manual de los datos recibidos y mejora la calidad de la información que entra en la aplicación.

El sistema de **Formularios Web Avanzados** ofrece una herramienta integral y flexible. El objetivo de este sistema es permitir la captura de información compleja desde el exterior y automatizar flujos de trabajo de forma autónoma, ágil y sin necesidad de desarrollos a medida.

## Escenarios de Uso
El sistema permite configurar la captación de datos y automatizar su registro en el CRM. A continuación, se detallan los principales casos de uso:

### Captación y automatización de procesos en un solo paso
* **Situación:** Para aquellas entidades que necesiten publicar un formulario de captación de voluntariado o sumar personas a su base social, y requieran automatizar tareas como registrar a la persona en el CRM, indicar su tipo de vinculación, añadirla a una lista de correo y notificar al equipo.
* **Solución:** Permite configurar un formulario que encadene todas estas acciones. Al enviarse, el sistema ejecuta secuencialmente:
  1. Crea o actualiza el registro en el módulo de Personas.
  2. Crea un registro en el módulo de Relaciones con Personas indicando que el "Tipo de relación" con la entidad es de "Voluntario/a".
  3. Relaciona ambos registros entre sí.
  4. Añade a la persona a la Lista de Público Objetivo (LPO) predefinida.
  5. Dispara el envío del correo electrónico de bienvenida a la persona inscrita.
  6. Envía un correo de notificación al equipo responsable en el CRM.

### Universalidad de módulos y operativas a medida
* **Situación:** Para aquellas entidades que gestionen operativas muy diversas y utilicen módulos personalizados en el CRM (como "Peticiones de Material", "Adopciones", "Incidencias" o "Proyectos") que requieran capturar información desde el exterior.
* **Solución:** Permite construir formularios sobre cualquier módulo del CRM, incluyendo los creados a medida por la entidad desde el área de administración (Estudio). Además, facilita recoger datos de varios módulos a la vez (por ejemplo, registrar una "Incidencia" y vincularla automáticamente a un "Centro" y a la "Persona" que informa).

### Crecimiento orgánico de la base de datos
* **Situación:** Para mantener el histórico de los contactos intacto al recibir nueva información desde la web, especialmente cuando las personas comparten datos (como familiares con un mismo correo electrónico) o dejan campos clave en blanco.
* **Solución:** Incorpora un sistema de detección de duplicados en cascada que permite definir reglas de coincidencia de forma independiente para cada tipo de registro (ej. buscar a la persona por DNI, y si no lo ha introducido, buscarla por Email). La acción "Ampliar" rellena solo los campos vacíos del CRM respetando los datos existentes.

### Mejora de la participación en campañas
* **Situación:** Para facilitar la participación al enviar comunicaciones masivas por correo (ej. boletines para captar firmas, apoyo o actualización de datos), evitando que la persona encuentre un formulario completamente vacío y deba rellenar información que la entidad ya posee.
* **Solución:** Permite el pre-rellenado mediante URL. Cuando la persona hace clic en el correo, aterriza en el formulario con sus datos personales (nombre, email, etc.) ya cumplimentados.

### Comunicaciones automáticas enriquecidas
* **Situación:** Para enviar una confirmación automática a la persona inscrita con los detalles de su petición tras rellenar una solicitud o inscripción.
* **Solución:** El equipo técnico de la entidad puede diseñar plantillas de correo electrónico corporativas y asignarlas al formulario. El sistema envía automáticamente estas plantillas como respuesta inmediata, inyectando de forma dinámica la información de los registros que se acaban de crear.

### Uso como interfaz interna para operaciones complejas
* **Situación:** Para facilitar el trabajo diario del equipo a la hora de dar de alta registros relacionados navegando por diferentes pantallas del CRM (ej. crear una persona, luego crear su organización, y finalmente vincularlos).
* **Solución:** Los formularios también pueden usarse de forma interna como herramientas de entrada de datos. Se puede diseñar un formulario avanzado y restringirlo por seguridad para que solo sea accesible por el personal con una sesión iniciada en el CRM y con los permisos adecuados. La URL generada puede añadirse como un enlace directo en el menú principal.

### Escalabilidad tecnológica y arquitectura abierta
* **Situación:** Para aquellas entidades que desarrollen reglas de negocio específicas o necesiten integraciones particulares.
* **Solución:** Cuenta con una arquitectura modular y desacoplada del CRM que permite a los desarrolladores programar nuevas acciones y automatismos a medida mediante código, integrándolos en el asistente visual.

### Despliegue y autonomía técnica
* **Situación:** Para publicar un formulario con la imagen corporativa, de forma rápida, accesible y reutilizable.
* **Solución:** Los formularios se guardan en el sistema para poder editarlos y reutilizarlos. Incluye un editor visual para adaptar la estética, colores, tipografías y logotipos, generando un formulario responsivo y accesible (basado en Bootstrap 5 y etiquetas semánticas ARIA). El propio CRM puede alojar el formulario o proporcionar el código HTML/Iframe para incrustarlo en una web externa.

### Auditoría y trazabilidad total
* **Situación:** Para auditar qué registros del CRM fueron alterados por un envío concreto o analizar estadísticamente las respuestas.
* **Solución:** Registra cada respuesta en la base de datos y, mediante el módulo de "Vínculos", proporciona una trazabilidad que detalla qué información introdujo el visitante y qué acciones exactas desencadenó (qué registros se crearon, actualizaron o ignoraron).

## Conceptos Clave
Antes de crear el primer formulario, es útil conocer cómo se organiza la información en el sistema:

* **Bloque de datos** : Representa una unidad de información que se recogerá y se guardará como un único registro en un módulo del CRM (por ejemplo, una "Persona" o una "Inscripción"). Se pueden incluir varios bloques en un mismo formulario, aplicando lógicas distintas a cada uno (por ejemplo, un bloque para el "tutor" y otro para el "menor", ambos registros del módulo "Persona").
* **Acciones** : Son automatismos que el sistema ejecuta con los datos recogidos. Permiten validar campos, crear o actualizar los registros en el CRM, enviar correos electrónicos personalizados o redirigir al usuario a otra página web.
* **Flujo de acciones** : Es la secuencia ordenada en la que se ejecutan las acciones. Principalmente existe un "flujo principal" (si todo funciona correctamente) y un "flujo de tratamiento de errores" (si alguna acción falla).
* **Secciones** : Son contenedores visuales que agrupan los campos del formulario para estructurarlo. Pueden tener distintos formatos, como paneles, tarjetas o ser desplegables. Las secciones no afectan a la estructura de datos, solo a la visualización.

## Creación de un formulario: El asistente paso a paso
La creación y configuración de un formulario se realiza de forma guiada a través de un asistente visual dividido en 5 pasos:

### Paso 1: Información general
En este primer paso se definen los datos básicos como su Nombre, la **persona asignada** (quien, además de ser responsable del formulario, será a quien se asignen por defecto los nuevos registros creados si el envío es externo) y la descripción.

### Paso 2: Estructura y campos
Aquí se selecciona qué información se solicitará a los usuarios mediante la adición de bloques de datos. Existen dos tipos:

* **Bloques de datos enlazados**: Están conectados directamente a un módulo específico del CRM (por ejemplo, "Personas", "Inscripciones" u "Organizaciones"). Su propósito es **crear o actualizar un registro real** dentro del sistema. Permiten definir validaciones propias del CRM, configurar reglas para la detección de duplicados, y establecer campos de servidor u ocultos.

* **Bloques de datos no enlazados**: Son contenedores que **no están vinculados a ningún módulo del CRM**. Los datos introducidos quedarán guardados de forma independiente dentro del registro global de la "Respuesta". **Importante: Esta información no se volcará en la ficha de la Persona ni alterará ningún registro de los módulos del CRM.** Solo pueden contener campos no enlazados y se utilizan habitualmente para realizar encuestas, valoraciones o incluir casillas de aceptación de condiciones.

#### Campos
Al configurar los campos de un bloque, la información se organiza en dos pestañas:

* **Formulario**: Son los campos visibles que el visitante podrá rellenar. Se configura su etiqueta, obligatoriedad, tipo de entrada y texto de fondo (*placeholder*).
  * **Buena práctica de configuración:** Es recomendable establecer siempre **al menos un campo como obligatorio** en cada bloque de datos enlazado. Si todos los campos de un bloque se configuran como opcionales y el visitante los deja en blanco, el sistema procesará el bloque igualmente y creará un registro vacío en el CRM.
  * **Textos de ayuda y Enlaces**: Cada campo permite añadir un texto de ayuda para guiar al usuario o insertar enlaces a páginas externas (útil para Políticas de Privacidad).
  * **Campos no enlazados (virtuales)**: Su valor vive exclusivamente en el registro de la "Respuesta" enviada y no alteran la base de datos principal. Pueden incluirse tanto en bloques no enlazados como conviviendo con campos normales dentro de un bloque enlazado (ej. para recopilar un comentario adicional que no deba ensuciar la ficha del contacto).
  * **Validaciones**: Se pueden vincular acciones de validación a campos específicos. El sistema asigna validadores automáticamente al detectar ciertos tipos de campos (ej. formato email) e incluye un catálogo de validadores predefinidos (DNI, NIE, CIF, IBAN, Teléfono, Código Postal, comprobaciones numéricas y de edad, URLs y expresiones regulares).
  * **Condicionalidad de las validaciones**: Se pueden configurar reglas simples para que un validador solo se ejecute si otro campo cumple una condición, además de personalizar el mensaje de error.

* **Servidor (solo en bloques enlazados)**: Contiene valores constantes y ocultos que el sistema guardará en el CRM al crear el registro (ej. fijar el estado de una inscripción como "Confirmado" sin mostrarlo en el formulario).
  * **Fechas relativas**: En campos de tipo fecha, permite configurar valores dinámicos relativos al momento del envío (*Hoy*, *Ahora*, *Dentro de un mes*, etc.). El sistema calculará la fecha exacta en el momento en que se reciba la respuesta.

* **Selección de los campos a utilizar**: El listado muestra por defecto los campos visibles en la vista del módulo en el CRM. Activando la opción *"Mostrar todos los campos"*, se revelará la totalidad de los campos del módulo (incluyendo aquellos ocultos desde Estudio).

* **Gestión de opciones y campos booleanos**: En campos que requieren escoger valores:
  * **Desplegables/Selección de opción**: El sistema carga los valores del CRM por defecto. La opción *"Personalizar opciones"* permite cambiar el orden, texto visible o esconder elementos en el formulario.
  * **Campos relacionados**: Permite seleccionar qué registros exactos aparecerán listados para que el usuario escoja uno (ej. elegir un Evento activo).
  * **Campos no enlazados**: Se define desde cero el listado completo de opciones.
  * **Campos booleanos**: Pueden mostrarse como Desplegable (Sí/No), Casilla de selección (Checkbox) o Interruptor (Switch).

#### Tipos de entrada y Editores visuales (Subtipos)
El sistema adapta el control visual en el formulario en función del tipo de dato:

* **➖ Texto**: Texto simple, Correo electrónico, Teléfono, Dirección URL, Contraseña.
* **☰ Texto largo**: Párrafo (Textarea).
* **#️⃣ Numérico**: Control estándar de entrada numérica.
* **🗓️ Selección de tiempo**: Fecha, Hora, Fecha y hora.
* **▼ Opciones predeterminadas**: Desplegable, Desplegable múltiple, Selección de opción (Radio buttons), Selección múltiple (Checkboxes), Casilla de selección simple, Interruptor.
* **🏅 Valoración (Solo en campos no enlazados)**: Estrellas, Caras (Emojis), Semáforo, Pulgares, Escala 0-10 (NPS). Guardan su valor unificado como número entero.
* **🕵️ Oculto**: El campo no se muestra pero su valor se almacena en la respuesta.

#### Detección de duplicados *(Solo en bloques enlazados)*
Permite definir reglas para detectar si el registro ya existe en el CRM antes de crearlo.
* **Reglas múltiples y secuenciales**: Se evalúan en el orden definido. Cuando una regla detecta una coincidencia, aplica su acción y detiene la evaluación de las siguientes.
* **Salvaguarda de campos vacíos**: El sistema ignora automáticamente cualquier regla si el visitante no ha rellenado todos los campos implicados en ella durante el envío, evitando fusiones indeseadas.

Por cada regla, se debe elegir el comportamiento al detectar coincidencia:
* **Actualizar**: Sobrescribe los datos del CRM con los nuevos.
* **Ampliar**: Añade la información nueva solo en los campos que en el CRM estuvieran vacíos.
* **Ignorar**: No modifica el registro original del CRM.
* **Error**: Detiene el procesado y genera un error.

#### Relaciones *(Solo en bloques enlazados)*
Sirve para definir cómo se conectan los registros generados entre sí. Al seleccionar el tipo de vínculo y el bloque destino, el sistema crea automáticamente las acciones para enlazar ambos registros tras guardar la respuesta.

### Paso 3: Lógica y automatismos
Se configura mediante un sistema visual de flujos compuesto por **Acciones**:
* **Flujo Principal**: Lista ordenada de acciones que se ejecutan secuencialmente al procesar correctamente una respuesta.
* **Flujo de Error**: Flujo de contingencia que se ejecuta si alguna de las acciones del flujo principal falla.

* **Acciones automáticas**: Al añadir bloques enlazados, el sistema añade de forma automática las acciones para guardar los datos en el CRM y vincularlos. No se pueden eliminar, pero sí reordenar.
* **Acciones definidas por el usuario**:
  * **Condiciones de ejecución**: Cualquier acción se puede condicionar a los datos introducidos.
  * **Continuar en caso de error**: Si se activa y la acción falla (ej. error al enviar un correo), el sistema ignora el fallo y permite que el formulario termine de procesarse.
* **Acciones finales**: Una vez ejecutadas, finalizan el flujo de acciones por completo (ej. redirección a web externa).

#### Catálogo de Acciones
* **Guardar registro (Automática)**: Crea o actualiza un registro. Si el formulario se rellena con una sesión activa, se asigna a dicho usuario; si es envío externo, se asigna al responsable del formulario.
* **Enlazar registros (Automática)**: Enlaza dos registros según las relaciones definidas.
* **Enviar notificación por correo / al usuario asignado**: Envía emails utilizando las plantillas del CRM. El sistema procesa la plantilla inyectando la información de todos los registros implicados en la respuesta.
* **Añadir a LPO**: Añade el registro resultante a una Lista de Público Objetivo.
* **Verificar sesión activa y permisos**: Bloquea el formulario si no hay sesión activa en el CRM o si el usuario no dispone de permisos para los módulos involucrados.
* **Mostrar página con resumen de respuestas (Final)**: Redirige a una página con el resumen de las respuestas.
* **Redireccionar a página (Final)**: Redirige a una web externa adjuntando los datos recopilados (GET o POST).

### Paso 4: Maquetación
Este paso cuenta con un editor visual con previsualización en tiempo real para estructurar el diseño:
* **Encabezado y pie de página**: Editores de texto enriquecido (WYSIWYG) para incluir logotipos, textos legales o instrucciones.
* **Secciones y contenedores visuales**: Agrupan los campos en formatos como paneles simples o tarjetas. Pueden configurarse como **acordeones (colapsables)**, definiendo si aparecen expandidos o contraídos por defecto.
* **Configuración general de estilos**: Permite personalizar el texto del botón de envío, la estructura en columnas (adaptable a pantallas), el esquema de colores, tipografía, bordes y sombreados. Soporta **Etiquetas Flotantes**. También permite configurar los mensajes mostrados cuando el formulario está cerrado o al finalizar el envío.

### Paso 5: Resumen y publicación
Permite establecer la disponibilidad del formulario y elegir el método para compartirlo:
* **Estado del formulario**:
  * **Borrador**: En preparación. No es accesible públicamente.
  * **Público**: Activo y publicado.
  * **Cerrado**: Desactivado. No acepta más respuestas.
* **Programación de la publicación**: En estado "Público", se puede programar la fecha y hora de inicio y de fin.
* **Bloqueo visual**: Si el formulario no es público o está fuera de fechas, muestra una capa superpuesta (*overlay*) que impide rellenar campos e informa de que está cerrado.
* **Previsualización**: Vista previa interactiva en entorno seguro que desactiva los envíos reales.
* **Opciones de publicación**:
  * **URL pública**: Enlace hacia el formulario alojado en el propio CRM.
  * **Incrustar (Iframe)**: Código para insertar el formulario del CRM dentro de una página web externa.
  * **Descargar código**: Código HTML generado para integraciones manuales.

## Análisis y Métricas de Rendimiento
Desde la vista de detalle del formulario se centraliza la información estadística:
* **Información general**: Estado actual y fechas programadas.
* **Tráfico y métricas**: Contabiliza *Visitas totales* y *Visitas bloqueadas* (intentos de acceso con formulario cerrado).
* **Calidad y filtrado**: Analiza *Respuestas válidas* procesadas frente a *Respuestas Spam* bloqueadas.
* **Fuentes de tráfico**: Resumen de dominios y orígenes desde los que se han recibido respuestas.

## Gestión de Respuestas y Trazabilidad Total
El sistema audita la información recibida en tres niveles de registro:

* **Respuestas**: Accesibles desde el formulario. Cada registro guarda la información íntegra incluyendo:
  * **Estado**: Situación del envío (*Pendiente*, *Procesada*, *Error*, etc.).
  * **Origen y contexto**: URL de envío, navegador, sistema operativo e IP.
  * **Control de seguridad**: *Hash* único para detectar envíos repetidos y evitar procesamientos múltiples.
  * **Ejecución**: Resumen de los datos recibidos y registro de ejecución de las acciones o errores.
* **Detalles de respuestas**: Guarda las respuestas de forma desglosada campo a campo. Los campos de valoración normalizan su puntuación en una escala del 0 al 100 (*Valor entero de la respuesta*).
* **Vínculos**: Documenta el impacto de la respuesta en la base de datos. Cada vínculo informa sobre:
  * **Registro afectado**: Módulo y registro del CRM.
  * **Acción realizada**: *Creado*, *Actualizado*, *Ampliado* o *Ignorado*.
  * **Datos aplicados**: Desglose de los campos modificados, el valor recibido y el cambio final en el CRM.

## Seguridad e Integridad de los Datos
Funcionalidades de seguridad nativas para proteger el CRM y preservar la calidad de los datos.

### Protección Anti-Spam Integrada e Invisible
Bloquea ataques automatizados sin depender de servicios de terceros ni requerir interacción del visitante:
* **Campo trampa (Honeypot):** Se inyecta un campo oculto en el diseño. Si un bot lo rellena, el sistema descarta el envío.
* **Control de tiempo (TimeTrap):** Monitoriza el tiempo de respuesta y bloquea envíos realizados de forma antinatural (en menos de 2 segundos).
* **Filtro de firmas (User-Agent):** Bloquea peticiones de herramientas de programación analizando la firma tecnológica del navegador.

### Validación de Datos a Doble Capa
El sistema verifica los datos por partida doble:
* **En el navegador:** Avisa a la persona si hay un error de formato antes de enviar.
* **En el servidor (CRM):** Re-verifica los datos de forma estricta antes de guardarlos en base de datos.

### Salvaguardas ante Duplicados
Como medida de seguridad arquitectónica, si un formulario llega con un campo clave de búsqueda de duplicados en blanco, el sistema ignora esa regla. Esto evita falsos positivos por campos no rellenados.

## Próximamente (Evolución del sistema)
* **Procesamiento asíncrono**: El sistema permitirá guardar las respuestas temporalmente y procesarlas en segundo plano, ideal para evitar sobrecargas del servidor en picos de uso.
* **Procesos diferidos**: El sistema permitirá gestionar acciones en espera de eventos externos, como la confirmación de una pasarela de pago, la validación de una entrada mediante código QR o la aprobación manual.
* **Archivos adjuntos**: Opción para que los usuarios puedan subir y adjuntar archivos digitales directamente.
* **Grupos de bloques de datos repetibles**: Creación de contenedores "repetibles" que permiten solicitar los datos de un bloque varias veces (ej. para recoger los datos de varios participantes en una inscripción grupal).
* **Flexibilidad de maquetación**: Agrupación de campos mediante pestañas y división de formularios extensos en múltiples páginas.
* **Condiciones lógicas avanzadas**: Soporte para condiciones combinadas y nuevos operadores lógicos para la ejecución de acciones.


----

## Opciones Avanzadas de Formularios Web (Para desarrolladores)
El sistema de Formularios Web Avanzados (AWF) ofrece flexibilidad técnica y opciones de extensibilidad para adaptar los formularios a casos de uso muy específicos mediante programación y personalización de código.

### Extensibilidad mediante código (Acciones Custom)
El ecosistema de formularios no está limitado a lo preestablecido. Tanto las acciones (automatismos de flujos) como las validaciones están diseñadas con una arquitectura modular y desacoplada que permite definirlas íntegramente por código. 

Esto resulta vital para entidades que requieran desarrollar reglas de negocio a medida, integraciones con APIs externas o validaciones muy específicas, sumándolas automáticamente al catálogo estándar del asistente visual del CRM.

* **Guía e implementación:** El propio código del módulo incluye una plantilla exhaustiva de ejemplo y documentación técnica de la interfaz `ActionDefinition`. Se puede consultar directamente en el archivo del núcleo: `modules/stic_AWF_Forms/actions/Hook/ExampleAction.php`.

### Uso de Campos Ocultos (Contexto de envío)
Los formularios web avanzados soportan la inyección de campos ocultos estándar (`<input type="hidden">`). A diferencia de los "Campos de servidor" definidos en el CRM (que operan exclusivamente en el backend para asignar valores seguros como *Estados*), los campos ocultos sí se imprimen en el código HTML generado.

* **Casos de uso:** Son la herramienta adecuada para capturar información de contexto dinámica mediante JavaScript o parámetros de URL sin intervención del usuario. Resultan útiles para leer el origen de una visita (`?origen=newsletter`), capturar identificadores de seguimiento (UTMs) o permitir que un script externo propio de la web de la entidad rellene el campo automáticamente antes de realizar el POST.

### Edición HTML e Inyección Manual de Campos
El sistema genera el código HTML del formulario limpio, indentado y optimizado para su legibilidad. Esto permite descargarlo, manipularlo e insertarlo en plataformas o CMS externos (como WordPress o Drupal) con total libertad.

El motor de recepción de respuestas del CRM está preparado para procesar nuevos campos añadidos manualmente en el código fuente, siempre que se respete la nomenclatura y el patrón de estructuración de AWF:

* **Campos enlazados al CRM:** Para añadir un nuevo campo que guarde su valor en la base de datos, se debe inyectar el input utilizando el patrón `NombreBloque.NombreCampoCRM`.
* **Campos no enlazados (Variables de respuesta):** Para datos auxiliares que solo deben constar en el registro de la "Respuesta" (y no en el módulo de destino), se debe utilizar el patrón `_detached.NombreBloque.NombreCampo`.

*Nota de diseño:* Esta característica aporta libertad total a los administradores web para crear diseños y maquetaciones altamente personalizadas o inyectar controles interactivos por JavaScript sobre el HTML, garantizando que el *backend* del CRM reciba e interprete los datos correctamente.