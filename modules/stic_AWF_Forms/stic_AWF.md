# Formularios Web Avanzados #
## Introducción ##
Los formularios web son uno de los canales habituales a través de los que una entidad recibe información de su base social: donativos, altas de socios, recogidas de firmas, suscripciones a boletines electrónicos, inscripciones a eventos, peticiones de servicios, etc.

Que la información recibida por esta vía quede directamente incorporada al CRM de la entidad supone unos beneficios notables, puesto que evita cualquier proceso de traspaso o de registro manual de los datos recibidos y mejora la calidad de la información que entra en la aplicación, al poder aprovechar los mecanismos de detección de duplicados y otras funcionalidades del sistema.

Anteriormente, SinergiaCRM contaba con tres tipos distintos de asistentes estáticos para la creación de formularios, vinculados y limitados a operativas y módulos muy concretos. Además, los formularios web que había solo permitían descargar el código HTML de un formulario con un formato básico, que siempre requería ser retocado manualmente para poder colgarlo en un sitio web público.

El nuevo sistema de **Formularios Web Avanzados** ofrece una herramienta integral, altamente versátil, reutilizable y flexible. El objetivo de este nuevo sistema es permitir la captura de información compleja desde el exterior y automatizar flujos de trabajo sin necesidad de desarrollos a medida. Entre las principales novedades y aportaciones de este sistema destacan:

* **Persistencia y edición**: Los formularios ahora se guardan en el sistema y se pueden editar en cualquier momento, lo que favorece su reutilización sin tener que crearlos desde cero.

* **Opciones de publicación flexibles**: El formulario puede residir directamente en el CRM generando una URL accesible públicamente sin necesidad de descargarlo. También ofrece opciones para insertarlo como un iframe en la web corporativa o descargar el código.

* **Control de acceso y caducidad programada**: Se puede controlar fácilmente si el formulario es público (para aceptar respuestas) o si está cerrado. Además, permite programar de forma automática la fecha y hora de inicio y fin de su visibilidad pública.

* **Diseño responsivo y personalizable**: La maquetación visual se separa de la lógica estructural. El diseño es responsivo y se ve perfectamente tanto en dispositivos móviles como en pantallas grandes. Además, es muy personalizable (colores, estilos, inyección de CSS propio) para que se adapte por completo a la estética de la entidad.

* **Accesibilidad Web (A11y)**: El código generado de forma automática incluye de serie atributos semánticos e información de accesibilidad (etiquetas ARIA), garantizando que los formularios sean inclusivos y puedan ser utilizados por personas que emplean lectores de pantalla u otras tecnologías de asistencia.

* **Universalidad (Cualquier módulo del CRM)**: Ya no se está limitado a crear formularios para módulos predefinidos. Ahora es posible diseñar formularios basados en cualquier módulo del CRM, incluyendo aquellos módulos personalizados creados por la entidad desde el Constructor (Estudio).

* **Gestión de registros múltiples (Bloques de datos)**: Un único formulario puede generar y actualizar múltiples registros (del mismo módulo o no) a la vez en el CRM aplicando lógicas diferenciadas.

* **Validación de datos y Control anti-spam integrado**: Incorpora validación de datos para asegurar que la información (correos, DNI, IBAN, etc.) es correcta. Asimismo, cuenta con un sistema de filtros anti-spam integrados (como el honeypot y el control de tiempos de rellenado) para evitar respuestas automatizadas de bots sin depender de herramientas externas.

* **Detección de duplicados avanzada y a medida**: El sistema de detección de duplicados se configura de forma precisa para cada registro a guardar, permitiendo decidir qué acción realizar si el registro ya existe: Actualizar, Ampliar, Ignorar o generar un Error.

* **Trazabilidad total, historial y análisis de respuestas**: Se registra íntegramente cada respuesta recibida, **incluso aquellas que no llegan a procesarse** (ya sea porque el sistema las detecta como spam o porque el formulario está cerrado). A través de los **"Vínculos"**, se mantiene una trazabilidad total que permite auditar qué registros exactos del CRM han sido afectados. Además, se incorpora un módulo específico de detalles para facilitar el **análisis** estadístico de cada una de las respuestas recogidas (ideal para encuestas y métricas).

* **Lógica y automatismos fácilmente ampliables**: A través de las **"Acciones"**, se puede configurar la lógica del formulario de forma visual. Este sistema de acciones está totalmente desacoplado del "core" del CRM, lo que hace que sean fácilmente ampliables y favorece el desarrollo a medida de nuevas integraciones según crezcan las necesidades de la entidad.

### Próximamente ###
* **Procesamiento asíncrono**: El sistema está preparado para permitir el tratamiento asíncrono de respuestas. Esto permitirá guardar las respuestas temporalmente y procesarlas en segundo plano, ideal para evitar sobrecargas del servidor en formularios con picos de uso muy altos.

* **Procesos diferidos**: De forma totalmente independiente al procesamiento, el sistema permitirá gestionar acciones en espera de eventos externos, como la espera de confirmación de una pasarela de pago (TPV) o la validación de una entrada mediante código QR.

* **Archivos adjuntos**: Habilitará la opción de que los usuarios puedan subir y adjuntar archivos o documentos digitales directamente a través del formulario.

* **Grupos de bloques de datos repetibles**: Se incorpora el concepto de "Grupo", un contenedor que agrupa uno o más bloques de datos relacionados entre sí. Su característica principal es que puede definirse como "repetible", permitiendo que el conjunto de campos que contiene aparezca múltiples veces en un mismo formulario. Esta funcionalidad será ideal para simplificar operativas complejas, como recoger los datos de varios participantes a la vez en una única inscripción grupal.

* **Mayor flexibilidad en diseño y maquetación**: Se ampliarán las opciones visuales para organizar la información de forma más dinámica, permitiendo agrupar campos mediante pestañas, dividir formularios extensos en múltiples páginas y añadir otros elementos interactivos.


## Conceptos Clave ##
Antes de crear el primer formulario, es útil conocer cómo se organiza la información en el sistema de Formularios Web Avanzados:
* **Bloque de datos**: Es el corazón del formulario. Representa una unidad de información que se recogerá y se guardará como un único registro en un módulo del CRM (por ejemplo, una "Persona" o una "Inscripción"). Se pueden incluir varios bloques en un mismo formulario, aplicando lógicas distintas a cada uno (por ejemplo, un bloque para el "tutor" y otro para el "menor", ambos registros del módulo "Persona").

* **Acciones**: Son automatismos que el sistema ejecuta con los datos recogidos. Permiten validar campos, crear o actualizar los registros en el CRM, enviar correos electrónicos personalizados o redirigir al usuario a otra página web.

* **Flujo de acciones**: Es la secuencia ordenada en la que se ejecutan las acciones. Principalmente existe un "flujo principal" (si todo va bien) y un "flujo de tratamiento de errores" (si algo falla).

* **Secciones**: Son contenedores visuales que agrupan los campos del formulario para facilitar su usabilidad. Pueden tener distintos formatos, como paneles simples, tarjetas y ser desplegables. Las secciones no afectan a los datos, solo a cómo se ven en el formulario.

## ¿Cómo crear un Formulario?: El Asistente Paso a Paso ##
La creación y configuración de un formulario se realiza de forma guiada a través de un asistente visual (wizard) dividido en 5 pasos:

### Paso 1: Información general ###
En este primer paso se definirá la identidad del formulario. Se podrán indicar los datos básicos como su Nombre, la **persona asignada** (quien, además de ser responsable del formulario, será a quien se asignen por defecto los nuevos registros creados si el envío es externo) y la descripción.

### Paso 2: Estructura y campos ###
Aquí se decidirá qué información se quiere pedir a los usuarios. La selección de esta información se realiza mediante la adición de bloques de datos, que pueden ser de dos tipos principales dependiendo de su integración con el sistema:

* **Bloques de datos (enlazados)**: Son unidades de información que están conectadas directamente a un módulo específico del CRM (por ejemplo, "Personas", "Inscripciones" u "Organizaciones"). El propósito de estos bloques es que los datos recopilados sirvan para **crear o actualizar un registro real** dentro del sistema. Al estar enlazados a un módulo, permiten definir validaciones propias del CRM, configurar reglas para la detección y gestión de duplicados, y establecer campos de servidor u ocultos.

* **Bloques de datos no enlazados**: Son contenedores diseñados para recopilar información en el formulario, pero que **no están vinculados a ningún módulo del CRM**. Como consecuencia, los datos introducidos quedarán guardados de forma independiente y exclusiva dentro del registro global de la "Respuesta". A diferencia de los bloques enlazados (que permiten mezclar campos del CRM con campos virtuales), **un bloque no enlazado solo puede contener campos no enlazados**. Son ideales para realizar encuestas, recopilar información temporal, hacer valoraciones o incluir casillas de aceptación de condiciones.

#### Secciones de los Bloques de datos ####
En el asistente, los bloques de datos están definidos en distintas secciones. Estas secciones pueden ser distintas según el tipo de bloque (enlazado o no enlazado).

##### Campos #####
Esta sección aparece en ambos bloques, enlazados y no enlazados

Aquí se define qué datos componen el bloque. Al configurar los campos, se organiza la información en dos pestañas principales:

* **Formulario**: Son los campos visibles que el visitante podrá rellenar. Por cada campo se puede configurar su etiqueta (nombre visible), si es obligatorio, el tipo de entrada (texto, desplegable, fecha, etc.) y el texto de fondo (*placeholder*).

  * **Textos de ayuda y Enlaces**: Cada campo permite añadir un texto de ayuda o descripción para guiar al usuario. Además, el asistente incluye una herramienta específica para insertar fácilmente enlaces a páginas externas (ideales para acompañar a las casillas de aceptación de Políticas de Privacidad o Condiciones de Uso).
  
  * **Campos no enlazados (virtuales)**: Son campos que no alteran la base de datos principal, sino que su valor vive exclusivamente en el registro de la "Respuesta" enviada. Existen en dos contextos:
    1. **Dentro de un Bloque no enlazado**: Donde, por la naturaleza del bloque, todos los campos creados son obligatoriamente de este tipo.

    2. **Dentro de un Bloque enlazado**: Convivirán junto a los campos normales del CRM. Posibles usos: Son ideales para recabar información que solo tiene sentido en el contexto del envío (como una casilla de "Acepto las condiciones", comentarios adicionales o valoraciones temporales) evitando que estos datos "ensucien" la ficha de la Persona o Inscripción en el CRM.

  
  * **Validaciones**: Adicionalmente, se pueden vincular acciones de validación a campos específicos para garantizar la calidad de los datos introducidos. Para ofrecer la máxima fiabilidad y evitar que envíos manipulados omitan los controles, **todas las validaciones se ejecutan por partida doble**: de forma interactiva en el navegador (JavaScript) para una experiencia de usuario fluida, y de forma estricta en el servidor (PHP) antes de procesar y guardar la respuesta. El sistema incluye un **amplio catálogo de validadores predefinidos**:
    * Formato de Email.
    * Documentos de identidad y salud: DNI, NIE, CIF, NAF (Número de Afiliación a la Seguridad Social) y CIP (Código de Identificación Personal de CatSalut).
    * Datos bancarios y de contacto: IBAN y Teléfono (con validación de longitud para España o prefijos internacionales).
    * Ubicación: Código Postal (formato de España).
    * Límites y rangos: Comprobación de Edad (mínima y máxima a partir de una fecha de nacimiento) y límites numéricos (valor mínimo/máximo).
    * Otros: Direcciones web (URL), casillas de marcación obligatoria (indispensable para aceptar términos y condiciones), y validación libre mediante expresiones regulares (Regex).


  * **Condicionalidad de las validaciones**: Se pueden configurar reglas simples para que un validador solo se ejecute si otro campo del formulario contiene un valor específico (por ejemplo, validar el campo "número de identificación" como "NIE" solo si el campo "Tipo de identificación" es "nie", o si una casilla específica está marcada). También es posible personalizar el mensaje de error exacto que verá el usuario si la validación falla.

* **Servidor (solo en bloques enlazados)**: Contiene los **campos de servidor**, que son valores constantes y ocultos que no se muestran al usuario final, pero que el sistema guardará en el CRM al crear el registro. Por ejemplo, en una inscripción, puedes definir por defecto el estado de la inscripción como "Confirmado" o vincularlo a un "Evento" específico sin que esta información esté presente en el formulario que el visitante rellenará.

  * **Fechas relativas**: En el caso de los campos de tipo fecha, el sistema permite configurar valores dinámicos relativos al momento del envío: *Hoy*, *Ahora*, *Dentro de un día*, *Dentro de una semana*, *Dentro de un mes*, *Último día de este mes* o *Primer día del próximo mes*. También es posible utilizar expresiones en inglés (como '*+2 weeks*', o '*first Monday of next month*'). El sistema calculará la fecha exacta partiendo del momento en que se reciba la respuesta.

* **Selección de los campos a utilizar**: Al añadir un campo que representa un valor en el registro (ya sea un campo normal del "**Formulario**" o un campo de "**Servidor**"), el listado de campos disponibles mostrará por defecto únicamente aquellos que son visibles en la vista de edición o detalle del propio módulo en el CRM. Sin embargo, si se activa la opción *"Mostrar todos los campos"*, el listado revelará la totalidad de los campos del módulo. Esto resulta de gran utilidad si se desea operar con campos que se han ocultado expresamente desde Estudio (por ejemplo, los campos del bloque "Incorpora" en la ficha de Personas).

* **Gestión avanzada de opciones y campos booleanos**: Cuando un campo requiere que el usuario escoja entre distintos valores (al seleccionar el Tipo de entrada *"Opciones predeterminadas"*), el sistema ofrece un alto nivel de configuración:

  * **Campos con opciones (Desplegables/Selección de opción)**: El sistema cargará los valores del CRM por defecto. Si se activa la opción de *"Personalizar opciones"*, es posible modificar el comportamiento de esta lista en el formulario: permite cambiar el orden y el texto visible (nombre) de los elementos, y gestionar su visibilidad para ocultar aquellos elementos que no se deseen ofrecer en el formulario.

  * **Campos relacionados**: Si el campo es del tipo Relacionado (apunta a un registro de otro módulo del CRM), se podrá seleccionar y definir explícitamente qué registros exactos (elementos) aparecerán listados para que el usuario seleccione uno de ellos. Esto es útil para permitir seleccionar en el formulario el curso (Evento) al que se inscribirá de entre los activos, por ejemplo.

  * **Campos no enlazados**: Al no depender de la estructura del CRM, en los campos virtuales o no enlazados se puede definir desde cero el listado completo de todas las opciones predeterminadas.
  
  * **Campos booleanos (Sí/No)**: Si el campo del CRM es de tipo Booleano, la herramienta permite elegir cómo representarlo gráficamente: puede mostrarse como un *Desplegable* con las opciones de "Sí" y "No", como una *Casilla de selección (Checkbox)* única, o bien como un *Interruptor (Switch)* visual.

###### Tipos de entrada y Editores visuales (Subtipos) ######
El sistema adapta el tipo de control visual en el formulario en función del tipo de dato, permitiendo decidir cómo se presenta la información de forma precisa. Los grandes **Tipos de entrada** dictan el comportamiento general, y para cada uno existen diferentes **Tipos de editor (Subtipos)** para afinar la experiencia de usuario:

* **➖ Texto**: Entradas de una sola línea.
  * **🔤 Texto simple**: Campo de texto estándar.
  * **✉️ Correo electrónico**: Valida el formato email y habilita teclados específicos en dispositivos móviles.
  * **📞 Teléfono**: Habilita el teclado numérico telefónico en dispositivos móviles.
  * **🔗 Dirección URL**: Formato específico para enlaces web.
  * **🔒 Contraseña**: Oculta los caracteres a medida que el usuario escribe.

* **☰ Texto largo**: Áreas de texto para descripciones extensas.
  * **📃 Párrafo**: Área de texto ampliada (Textarea).
    
* **#️⃣ Numérico**: Entradas restringidas a números.
  * **🔢 Numérico**: Control estándar de entrada numérica.

* **🗓️ Selección de tiempo**: Controles nativos para escoger fechas u horas.
  * **📅 Fecha**: Selector de día, mes y año.
  * **⏱️ Hora**: Selector de hora y minutos.
  * **📅⏱️ Fecha y hora**: Selector combinado.
  
* **▼ Opciones predeterminadas**: Muestra un conjunto cerrado de opciones entre las que el visitante debe elegir.
  * **🔻 Desplegable**: Clásica lista de selección colapsada (Combo box).
  * **📑 Desplegable múltiple**: Lista de selección que permite escoger más de una opción a la vez.
  * **🔘 Selección de opción**: Muestra todas las opciones directamente en pantalla con botones de radio (*Radio buttons*), permitiendo escoger solo una.
  * **☑️ Selección múltiple**: Muestra casillas de verificación (*Checkboxes*) independientes para seleccionar varias opciones simultáneamente.
  * **◻️ Casilla de selección**: Un único *Checkbox* simple (ideal para booleanos o aceptaciones de términos).
  * **🎚️ Interruptor**: Un control visual estilo "Switch" (encendido/apagado para booleanos).

* **🏅 Valoración (Solo en campos no enlazados)**: Controles visuales pensados para encuestas y recolección de *feedback*. Exclusivos de los campos no enlazados, guardan internamente su valor como un número entero.
    *   **⭐ Estrellas**: Valoración clásica del 1 al 5.
    *   **🙂 Caras**: Valoración del 1 al 5 a través de Emojis.
    *   **🚦 Semáforo**: Valoración rápida de 3 niveles (rojo, amarillo, verde).
    *   **👍 Pulgares**: Valoración binaria de aprobación o rechazo (arriba/abajo).
    *   **🔟 Escala 0-10 (NPS)**: Barra estándar de escala *Net Promoter Score*.

* **🕵️ Oculto**: El campo no se muestra gráficamente en el formulario, pero su valor se almacena y acompaña silenciosamente a la respuesta enviada.

##### Detección de duplicados ##### 
Esta sección solo aparece en los bloques enlazados

Es posible definir las reglas para detectar si la persona, organización o registro ya existe en el CRM (por ejemplo, comprobando de forma combinada el Email y el DNI). Si el sistema detecta que el registro ya existe, se debe elegir que hacer con los duplicados entre 4 acciones concretas:

* **Actualizar (Sobrescribir)**: Reemplaza los datos existentes en el CRM por los nuevos datos introducidos en el formulario.

* **Ampliar**: Añade la información nueva del formulario solo en aquellos campos que en el CRM estuvieran vacíos, respetando los datos que ya existían.
    
* **Ignorar**: Ignora por completo los datos recibidos en ese bloque para no modificar el registro original del CRM.
    
* **Error**: Detiene el procesado y genera un error (útil cuando un formulario solo debería aceptar registros estrictamente nuevos).


##### Relaciones ##### 
Esta sección solo aparece en los bloques enlazados

Esta sección es fundamental cuando el formulario interactúa con más de un módulo del CRM. Sirve para definir cómo se conectan los registros generados entre sí dentro de la base de datos.

* Al definir una relación, se seleccionará el tipo de vínculo exacto y el bloque de datos destino de la relación. Al hacerlo, el sistema creará automáticamente las acciones "por detrás" para enlazar ambos registros una vez se guarde la respuesta.

* Ejemplo práctico: Imaginemos un formulario de inscripción de una persona a un evento. En esta sección de Relaciones se indicará que ambos bloques (Persona e Inscripción) están unidos. Así, cuando el usuario envíe el formulario, el sistema no solo creará los dos registros por separado, sino que automáticamente generará el vínculo formal entre ambos dentro del CRM.


### Paso 3: Lógica y automatismos ###
En este paso se configurará qué ocurre "por detrás" cuando alguien hace clic en "Enviar" del formulario. La lógica de negocio se articula mediante un sistema visual de flujos compuesto por **Acciones**.

* **Dos flujos de ejecución**: Cada formulario se organiza visualmente en pestañas que representan distintos flujos de ejecución. Principalmente, existen dos:

  * **Flujo Principal**: Es la lista ordenada de acciones que se ejecutan de forma secuencial cuando se procesa correctamente una respuesta.

  * **Flujo de Error**: Es un flujo de contingencia que se ejecuta de forma automática si alguna de las acciones del flujo principal falla.

* **Acciones automáticas (Persistencia garantizada)**: Al añadir bloques de datos en el paso anterior del asistente, el sistema añade **automáticamente** al flujo principal las acciones necesarias para guardar los datos en el CRM ("Guardar registro") y vincularlos entre sí ("Enlazar registros"). Gracias a esto, no es necesario preocuparse por la persistencia de la información; el sistema garantiza que los registros se crearán, actualizarán y relacionarán solos según las reglas definidas. Estas acciones base no se pueden eliminar, pero sí reordenar.

* **Acciones definidas por el usuario**: Más allá de guardar los datos, se pueden enriquecer los flujos añadiendo y encadenando nuevas acciones configurables. Cada acción introducida permite establecer parámetros específicos basándose en los propios datos introducidos en el formulario o en registros del CRM. 

  * **Condiciones de ejecución**: La ejecución de cualquier acción se puede condicionar a los datos introducidos por el usuario. Por ejemplo, se puede configurar que la acción de "Añadir a LPO" (suscripción a newsletter) solo se ejecute si el usuario ha marcado previamente la casilla de "Deseo recibir información" en el formulario.

* **Acciones finales**: Es clave destacar un tipo especial de acciones llamadas "Finales". Una acción final es aquella que, una vez ejecutada, **pierde el control del proceso y finaliza el flujo de acciones por completo**, impidiendo que se ejecute ninguna otra acción posterior. Se utilizan para operaciones de cierre, como redirigir al usuario a una página web externa o mostrar un mensaje final.

* **Un sistema escalable y ampliable**: La arquitectura de este sistema de acciones está diseñada exclusivamente por código y de forma totalmente desacoplada del "core" del CRM. Esto significa que el ecosistema de acciones es **fácilmente ampliable**: permite desarrollar a medida nuevas acciones o integraciones para entidades concretas y sumarlas al catálogo general.

#### Catálogo actual de Acciones ####
Inicialmente, el sistema incluye las siguientes acciones que se pueden añadir a los flujos:
* **Guardar registro (Automática)**: Crea o actualiza un registro a partir de un bloque de datos. Cuenta con un sistema de **asignación inteligente**: si el formulario se rellena con una sesión activa en el CRM, el registro se asignará a dicho trabajador. Si es un envío externo, se asignará a la persona responsable del formulario (o al administrador del sistema en su defecto).

* **Enlazar registros (Automática)**: Enlaza dos registros del CRM según las relaciones definidas en el paso anterior.

* **Enviar notificación por correo**: Envía un email personalizado a partir de una plantilla del CRM, permitiendo procesarla con los datos del bloque.

* **Enviar notificación al usuario asignado**: Permite enviar un email de aviso (usando una plantilla del CRM) al trabajador o usuario interno responsable del registro que se acaba de crear o actualizar o de cualquier otro referenciado (por ejemplo, el evento de la inscripción).
  
  * **Variable mágica para plantillas de correo (`{::form_summary::}`)**: En las plantillas de email utilizadas por estas acciones, se puede incluir la etiqueta especial `{::form_summary::}` dentro del cuerpo del mensaje. Al procesar el envío, el sistema detectará y sustituirá automáticamente esta variable por un resumen completo y formateado (en HTML o texto plano) que contiene todos los campos y datos introducidos por el usuario en el formulario. Resulta ideal para automatizar correos de confirmación o resguardos de inscripción.

* **Añadir a LPO**: Añade el registro resultante a una Lista de Público Objetivo destino.

* **Verificar sesión activa y permisos**: Bloquea el acceso y el procesamiento del formulario si no hay una sesión activa en el CRM o si el usuario no dispone de permisos explícitos de edición/creación para **todos y cada uno de los módulos** involucrados en los bloques de datos del formulario.

* **Mostrar página con resumen de respuestas (Final)**: Redirige al usuario a una página que contiene el resumen de todas sus respuestas al formulario.

* **Redireccionar a página (Final)**: Redirige al usuario a una página web externa una vez procesados los datos.

### Paso 4: Maquetación ###
Llega el momento de darle forma visual al formulario, separando por completo su diseño de la lógica estructural definida en los pasos anteriores. Este paso cuenta con un **editor visual con previsualización en tiempo real**, lo que permite comprobar exactamente cómo quedará el formulario final a medida que se diseña y se aplican los cambios.

Entre las opciones de diseño y maquetación disponibles destacan:

* **Encabezado y pie de página**: Mediante editores de texto enriquecido (WYSIWYG), se pueden personalizar libremente la cabecera y el pie del formulario. Esto es especialmente útil para incluir el logotipo de la entidad, un título descriptivo, instrucciones iniciales de rellenado, textos legales (como la política de privacidad) o mensajes de información y agradecimiento en la parte inferior.

* **Secciones y contenedores visuales**: El formulario se estructura organizando los campos en diferentes **"Secciones"**, que actúan como contenedores visuales. Una sección puede incluir campos de uno o más bloques de datos. A nivel visual, estas secciones ofrecen mucha versatilidad:

  * **Formato de contenedor**: Pueden mostrarse de forma limpia como un panel simple (sin bordes) o en formato tarjeta (con borde y fondo diferenciado).
  
  * **Título**: Se puede definir si la sección muestra un título visible para estructurar el contenido o si queda oculto.

  * **Comportamiento colapsable (Acordeón)**: Las secciones pueden configurarse como paneles desplegables, permitiendo elegir si al cargar la página aparecen expandidas o contraídas por defecto. Esto resulta extremadamente útil en formularios largos para no abrumar al usuario, permitiéndole navegar progresivamente por bloques o revelar información opcional solo si interactúa con ella.

* **Configuración general de estilos**: Existen multitud de opciones para adaptar la apariencia del formulario y lograr que se integre a la perfección con la identidad visual corporativa:

  * **Botón de envío**: Personalización del texto del botón de envío de datos del formulario (por ejemplo: "Hacer donativo", "Inscribirme", "Firmar petición").

  * **Estructura en columnas**: Se puede definir el número máximo de columnas de cada sección para organizar los elementos internos de forma más eficiente y compacta en la pantalla, adaptándose siempre a los distintos tamaños de pantalla.

  * **Apariencia y diseño**: Control detallado sobre el esquema de colores, la tipografía, los tamaños de fuente, los bordes y los sombreados.
  
  * **Campos y etiquetas**: Personalización del diseño visual de los campos y la disposición de las etiquetas, incluyendo el soporte para **Etiquetas Flotantes** (animación donde la etiqueta se integra dentro del propio campo).

  * **Textos de estado y mensajes**: Permite personalizar los mensajes que verán los visitantes si intentan acceder al formulario cuando este no es público o cuando se envíen los datos si no hay ninguna redirección definida.

  * **CSS Personalizado**: Para necesidades más avanzadas, el sistema permite inyectar código CSS propio para aplicar ajustes de diseño a medida y sin límites. Esta opción solo se mostrará si el usuario es un Administrador.

### Paso 5: Resumen y publicación ###
Este es el último paso del asistente, destinado a revisar el resultado final, establecer la disponibilidad del formulario y elegir el método para compartirlo.

* **Estado del formulario**: Permite definir en qué situación se encuentra el formulario, controlando así el acceso y la recepción de respuestas:

  * **Borrador**: El formulario está en fase de preparación o edición. Todavía no es accesible públicamente y no acepta respuestas.

  * **Público**: El formulario está activo y publicado; por lo tanto, permite recibir y registrar respuestas de forma normal.

  * **Cerrado**: El formulario se desactiva y ya no acepta más respuestas (ideal cuando finaliza una campaña o se llena un aforo). 
  
* **Programación de la publicación**: Si el formulario se encuentra en estado "Público", de forma opcional se puede programar una **fecha y hora de inicio y de fin** de la publicación. Esto permite automatizar la apertura y cierre del formulario sin necesidad de intervención manual.

* **Bloqueo visual y de seguridad**: Cuando un formulario no sea **"Público"** o esté fuera de las fechas programadas para su apertura, el sistema bloqueará automáticamente su uso mostrando una capa superpuesta (*overlay*) transparente que impide rellenar los campos y enviar respuestas, acompañada de un mensaje central informativo indicando que el formulario está cerrado (cuyo título y texto son totalmente configurables desde el asistente de maquetación). En el caso de que se saltase esta restricción visual y se recibiese una respuesta, esta se guardará y marcará en el sistema, pero no se procesará en ningún caso.

* **Previsualización**: Antes de compartirlo, se dispone de un botón para previsualizar el formulario generado. Esta vista previa genera una réplica interactiva en un entorno seguro que desactiva los botones de envío reales (evitando generar registros de prueba en el CRM). Además, incluye una barra de herramientas superior que permite emular el estado del formulario, pudiendo comprobar de forma interactiva cómo se verá y cómo se comportará tanto cuando está activo como cuando no acepta respuestas.

* **Opciones de publicación**: Una vez listo, el sistema ofrece diferentes vías para difundir y hacer accesible el formulario:

  * **URL pública**: El CRM genera automáticamente un enlace accesible públicamente hacia el formulario alojado en el propio sistema. Es la opción ideal para compartirlo rápidamente sin tener que publicarlo en un sitio web externo.
  
  * **Incrustar (Iframe)**: Proporciona el código necesario para insertar el formulario publicado en el CRM directamente dentro de una página web externa, como la web corporativa de la entidad.
  
  * **Descargar código**: Permite descargar el código HTML generado para realizar inserciones manuales o integraciones más específicas en plataformas externas.

## Análisis y Métricas de Rendimiento
Además de capturar datos, el formulario web es una herramienta de medición de impacto. Desde la vista de detalle o panel de control del formulario, se centraliza toda la información estadística y de rendimiento de la campaña:

* **Información general y disponibilidad**: Muestra de un vistazo el *Estado* actual (*Borrador*, *Público* o *Cerrado*) y el *Modo de procesamiento* (Síncrono o Asíncrono). Si el formulario es público, refleja también las *Fechas de inicio y fin* programadas para su apertura y cierre automáticos.

* **Tráfico y métricas SEO**: Se contabilizan las *Visitas totales* que recibe el formulario. Asimismo, se registra el número de *Visitas bloqueadas*, que corresponden a intentos de acceso mediante la URL pública cuando el formulario se encuentra cerrado.

* **Calidad y filtrado**: Permite analizar la tasa de éxito y seguridad comparando el volumen de *Respuestas válidas* reales procesadas frente al número de *Respuestas Spam* que los sistemas de protección han detectado y bloqueado.

* **Fuentes de tráfico**: El sistema consolida y elabora un resumen de los dominios y orígenes (webs) desde los cuales se ha recibido alguna respuesta a ese formulario. Esto permite a la entidad identificar qué canales, páginas propias o dominios aliados están aportando más valor y resultados a la campaña.


## Gestión de Respuestas y Trazabilidad Total ##
A diferencia de sistemas anteriores, los Formularios Web Avanzados ofrecen una auditoría completa y detallada de todo lo que ocurre con la información recibida. El sistema se organiza en tres niveles de registro para garantizar una trazabilidad total:

* **Respuestas**: Desde la vista de detalle de un formulario se pueden consultar todas las respuestas que este ha recibido a lo largo del tiempo. Cada registro de respuesta guarda la información de forma íntegra e incluye datos técnicos y de contexto:

  * **Estado**: Indica la situación del ciclo de vida en la que se encuentra el envío (por ejemplo: *Pendiente*, *Procesando*, *En espera*, *Procesada*, *Rechazada*, *No deseada*, o *Error*). Cabe destacar que, dependiendo de la configuración y de los flujos del formulario, puede que no se utilicen todos los estados.
  
  * **Origen y contexto técnico**: Registra la URL exacta de la página desde la cual se envió el formulario, el navegador y sistema operativo del usuario, y su dirección IP.
  
  * **Control de seguridad**: Incluye el *hash* único de la respuesta, un identificador diseñado para detectar envíos repetidos en ventanas de tiempo cortas y evitar dobles procesamientos accidentales.
  
  * **Datos y ejecución**: Muestra un resumen con la respuesta original recibida debidamente formateada de forma legible, así como el registro de ejecución de las acciones y los mensajes de error en caso de que el procesado haya fallado.

* **Detalles de respuestas**: Cada respuesta cuenta con un listado estructurado de "detalles". Este componente guarda las respuestas de forma desglosada y está pensado específicamente para facilitar el análisis estadístico y la extracción de métricas. Además, para facilitar los informes y la comparativa de datos cruzados, los campos de valoración para encuestas (estrellas, emojis, NPS, etc.) normalizan su puntuación de forma automática y la guardan unificada en una escala del 0 al 100 en el campo *"Valor entero de la respuesta"*.

* **Vínculos (Trazabilidad en el CRM)**: Es el módulo encargado de documentar de forma granular qué impacto exacto ha tenido una respuesta dentro de la base de datos. Cada respuesta tiene uno o varios "Vínculos" asociados. Un vínculo informa de manera precisa sobre:

  * **Registro afectado**: A qué módulo y a qué registro concreto del CRM hace referencia (incluyendo un enlace directo para acceder a la ficha afectada).
  
  * **Acción realizada**: Especifica la operación exacta que el automatismo ejecutó sobre ese registro: *Creado*, *Actualizado*, *Ampliado* (solo se llenaron los campos que estaban en blanco) o *Ignorado*.
  
  * **Datos aplicados**: Muestra un desglose del subconjunto de datos que se aplicó a dicho registro, indicando los campos afectados, el valor que se recibió desde el formulario y cómo ha cambiado dicho valor en el CRM (el salto desde el valor que tenía originalmente hasta el nuevo valor asignado).

### Protección Anti-Spam Integrada ###
Para garantizar la calidad de los datos y evitar envíos automatizados indeseados sin depender de molestos servicios de terceros (como los captchas tradicionales), el sistema incorpora tres capas de seguridad invisibles y transparentes para el usuario final:

* **Honeypot (Campo trampa)**: El sistema inyecta automáticamente un campo oculto en el diseño del formulario. Este campo es invisible para los usuarios humanos, pero los bots de spam, al analizar el código de la página, suelen rellenarlo. Si al procesar la respuesta el CRM detecta que este campo contiene datos, clasifica el envío como spam y lo descarta.

* **TimeTrap (Control de tiempo)**: Se monitoriza el tiempo exacto que transcurre desde que se carga el formulario hasta que se envía. Dado que los bots procesan y envían la información de forma casi instantánea, cualquier respuesta enviada en un intervalo inferior a 2 segundos es detectada y bloqueada como automatizada.

* **Filtro de User-Agent**: El sistema analiza la firma tecnológica del navegador (User Agent) en el momento del envío. Esto permite bloquear por defecto todas aquellas peticiones que provienen de scripts o herramientas de programación conocidas por automatizar ataques (como cURL, rutinas de Python, Postman, Wget, etc.), aceptando únicamente peticiones de navegadores reales.

## Opciones Avanzadas
El nuevo sistema ofrece un grado de flexibilidad adicional para perfiles técnicos y administradores, permitiendo adaptar los formularios a casos de uso muy específicos que van más allá de la configuración estándar del asistente:

* **Extensibilidad mediante código**: El ecosistema de formularios no está limitado a lo preestablecido. Tanto las acciones (automatismos) como las validaciones están diseñadas con una arquitectura desacoplada que permite definirlas íntegramente por código. Esto resulta vital para entidades que requieran desarrollar reglas de negocio a medida o validaciones muy específicas y sumarlas al catálogo estándar de acciones de su CRM.

* **Pre-rellenado mediante URL**: El formulario está preparado para capturar valores pasados directamente como parámetros en la dirección URL. 
  * *Casos de uso*: Es una opción extremadamente útil para campañas de email marketing, donde el usuario al hacer clic en un enlace ya encuentra sus datos personales básicos (nombre, email) pre-rellenados, reduciendo la fricción y mejorando radicalmente las tasas de conversión.

* **Campos ocultos (vs Campos de servidor)**: Se incorpora un nuevo tipo de campo visual: el campo oculto (`<input type="hidden">`). A diferencia de los "Campos de servidor" (que se configuran y viven de forma segura exclusivamente en el servidor del CRM), los campos ocultos sí se inyectan en el código HTML del formulario.
  * *Casos de uso*: Son ideales para capturar información de contexto dinámica que no requiere intervención visual del usuario, como leer parámetros pasados por la URL (ej: `?origen=newsletter_abril`), capturar identificadores de seguimiento, o permitir que un script externo de la web rellene el campo automáticamente antes de enviar la respuesta.

* **Edición HTML e inyección de nuevos campos**: El sistema genera un código HTML **limpio, indentado y optimizado para su legibilidad**, el cual puede ser descargado y editado libremente para su inserción en plataformas externas. Si se respeta la nomenclatura, el CRM entenderá perfectamente cualquier nuevo campo añadido manualmente en el código sin romper el formulario. Para añadir nuevos campos mapeados al CRM se usa el patrón `NombreBloque.NombreCampoCRM` y, para datos de uso exclusivo en la respuesta (campos no enlazados), el patrón `_detached.NombreBloque.NombreCampo`.
    * *Casos de uso*: Al generar un código limpio, resulta sencillo para un *webmaster* o desarrollador leerlo y modificarlo, aportando libertad total para crear diseños web a medida, maquetaciones altamente personalizadas, o inyectar campos interactivos por JavaScript.


* **Formularios de alta complejos para uso interno (Verificar Sesión y Permisos)**: Combinando la flexibilidad de diseño con la acción de **"Verificar sesión activa y permisos"**, los formularios avanzados pueden convertirse en una potente herramienta de uso interno. Esta acción garantiza que únicamente el personal con una sesión iniciada en el CRM y con los permisos de rol adecuados para crear/editar en todos los módulos implicados pueda acceder a ellos. 
  * *Casos de uso*: La URL generada puede añadirse como un enlace directo en el menú principal de navegación del CRM, dotando al equipo de atajos para operativas de entrada de datos de forma mucho más ágil que utilizando la interfaz estándar. Por ejemplo, permite diseñar una única pantalla de alta rápida que agrupe visualmente la creación de una Persona, de una Organización y genere la relación automática entre ambos registros. Además, gracias a la **asignación inteligente**, todos los registros creados quedarán automáticamente asignados al trabajador que ha rellenado el formulario, manteniendo intacta la autoría de los datos introducidos.


