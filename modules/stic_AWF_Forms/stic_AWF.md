# Formularios Web Avanzados #
## Introducción ##
Los formularios web son uno de los canales habituales a través de los que una entidad recibe información de su base social: donativos, altas de socios, recogidas de firmas, suscripciones a boletines electrónicos, inscripciones a eventos, peticiones de servicios, etc.

Que la información recibida por esta vía quede directamente incorporada al CRM de la entidad supone unos beneficios notables, puesto que evita cualquier proceso de traspaso o de registro manual de los datos recibidos y mejora la calidad de la información que entra en la aplicación, al poder aprovechar los mecanismos de detección de duplicados y otras funcionalidades del sistema.

Anteriormente, SinergiaCRM contaba con tres tipos distintos de asistentes estáticos para la creación de formularios, vinculados y limitados a operativas y módulos muy concretos (Campañas/Personas, Eventos y Captación de fondos). Con el nuevo sistema de **Formularios Web Avanzados**, se rompen estas limitaciones tecnológicas para ofrecer una herramienta integral, altamente versátil, reutilizable y flexible.

El objetivo de este nuevo sistema es permitir la captura de información compleja desde el exterior y automatizar flujos de trabajo sin necesidad de desarrollos a medida. Entre las principales novedades y aportaciones de este sistema destacan:

- **Universalidad (Cualquier módulo del CRM)**: Ya no se está limitado a crear formularios para módulos predefinidos. Ahora es posible diseñar formularios basados en cualquier módulo del CRM, incluyendo aquellos módulos personalizados creados por la entidad desde el Constructor (Estudio).

- **Gestión de registros múltiples (Bloques de datos)**: Un único formulario puede generar y actualizar múltiples registros a la vez en el CRM. Gracias al uso de **"Bloques de datos"**, un mismo formulario puede operar distintos registros aplicando lógicas diferenciadas. Por ejemplo, en una inscripción familiar, el formulario puede crear simultáneamente la ficha del tutor, la del menor, relacionarlos entre sí y generar sus inscripciones.

- **Detección de duplicados avanzada y a medida**: El sistema de detección de duplicados ya no es global, sino que se configura de forma precisa para cada bloque de datos. Además, permite decidir qué acción realizar si el registro ya existe en el CRM: **Actualizar** sus datos, **Ampliar** (completando solo lo que está vacío en el CRM), **Ignorar** la respuesta o generar un **Error**.

- **Trazabilidad total e historial de respuestas**: El sistema introduce una transparencia absoluta. Se registra íntegramente cada respuesta recibida con toda la información suministrada. Además, a través de los **"Vínculos"**, se mantiene una trazabilidad total que permite auditar qué registros exactos del CRM han sido creados, actualizados o ignorados a partir de esa respuesta.

- **Lógica y automatismos configurables**: A través de las **"Acciones"**, se puede configurar de forma visual la lógica del formulario. Esto permite desencadenar automatismos como validar información, enviar correos electrónicos personalizados, añadir personas a Listas de Público Objetivo (LPO), o establecer flujos de tratamiento de errores.

- **Maquetación visual independiente**: La estructura lógica de los datos se separa de su representación visual. Un nuevo sistema de secciones permite agrupar los campos en paneles simples, pestañas o acordeones para crear diseños complejos de forma intuitiva, mejorando drásticamente la experiencia del usuario final.

### Pendiente ###
- **Procesos diferidos y asíncronos**: El sistema está preparado para el futuro y la integración externa, permitiendo el tratamiento asíncrono de respuestas para evitar sobrecargas del servidor en picos de uso. Además, permite gestionar **"procesos diferidos"**, como la espera de confirmación de una pasarela de pago externa o la validación de una entrada mediante código QR.


## Conceptos Clave ##
Antes de crear el primer formulario, es útil conocer cómo se organiza la información en el sistema de Formularios Web Avanzados:
- **Bloque de datos**: Es el corazón del formulario. Representa una unidad de información que se recogerá y se guardará como un único registro en un módulo del CRM (por ejemplo, una "Persona" o una "Inscripción"). Se pueden incluir varios bloques en un mismo formulario, aplicando lógicas distintas a cada uno (por ejemplo, un bloque para el "tutor" y otro para el "menor", ambos registros del módulo "Persona").

- **Acciones**: Son automatismos que el sistema ejecuta con los datos recogidos. Permiten validar campos, crear o actualizar los registros en el CRM, enviar correos electrónicos personalizados o redirigir al usuario a otra página web.

- **Flujo de acciones**: Es la secuencia ordenada en la que se ejecutan las acciones. Principalmente existe un "flujo principal" (si todo va bien) y un "flujo de tratamiento de errores" (si algo falla).

- **Secciones**: Son contenedores visuales que agrupan los campos del formulario para facilitar su usabilidad. Pueden tener distintos formatos, como paneles simples, tarjetas y ser desplegables. Las secciones no afectan a los datos, solo a cómo se ven en el formulario.

## ¿Cómo crear un Formulario?: El Asistente Paso a Paso ##
La creación y configuración de un formulario se realiza de forma guiada a través de un asistente visual (wizard) dividido en 5 pasos:

### Paso 1: Información general ###
En este primer paso se definirá la identidad del formulario. Se podrán indicar los datos básicos como su Nombre, la persona asignada y la descripción del formulario.

### Paso 2: Estructura y campos ###
Aquí se decidirá qué información se quiere pedir a los usuarios. La selección de esta información se realiza mediante la adición de bloques de datos, que pueden ser de dos tipos principales dependiendo de su integración con el sistema:

- **Bloques de datos (enlazados)**: Son unidades de información que están conectadas directamente a un módulo específico del CRM (por ejemplo, "Personas", "Inscripciones" u "Organizaciones"). El propósito de estos bloques es que los datos recopilados sirvan para **crear o actualizar un registro real** dentro del sistema. Al estar enlazados a un módulo, permiten definir validaciones propias del CRM, configurar reglas para la detección y gestión de duplicados, y establecer valores fijos u ocultos.

- **Bloques de datos no enlazados**: Son contenedores de campos diseñados para recopilar información en el formulario, pero que **no están vinculados a ningún módulo del CRM**. Como consecuencia, los datos introducidos en este tipo de bloques quedarán guardados de forma independiente y exclusiva dentro del registro global de la "Respuesta". Son ideales para realizar encuestas, recopilar información temporal, hacer valoraciones o incluir casillas de aceptación de condiciones.

#### Secciones de los Bloques de datos ####

- **Sección Campos** (en ambos bloques, enlazados y no enlazados): Aquí se define qué datos componen el bloque. Al configurar los campos, se organiza la información en dos pestañas principales:
  - **En el formulario**: Son los campos visibles que el visitante podrá rellenar. Por cada campo se puede configurar su etiqueta (nombre visible), si es obligatorio, el tipo de entrada (texto, desplegable, fecha, etc.) y el texto de fondo (*placeholder*).
    
    - **Validaciones**: Adicionalmente, se pueden vincular acciones de validación a campos específicos para garantizar la calidad de los datos introducidos. El sistema incluye validadores predefinidos (comprobación de formato de Email, DNI/NIE, IBAN, longitud mínima/máxima o expresiones regulares). Además, destaca la **condicionalidad de las validaciones**: se pueden configurar reglas simples para que un validador solo se ejecute si otro campo del formulario contiene un valor específico (por ejemplo, validar el campo "número de identificación" como "NIE" solo si el campo "Tipo de identificación" es "nie", o si una casilla específica está marcada). También es posible personalizar el mensaje de error exacto que verá el usuario si la validación falla.

  - **Valores fijos (solo en bloques enlazados)**: Son valores constantes y ocultos que no se muestran al usuario final, pero que el sistema guardará en el CRM al crear el registro. Por ejemplo, en una inscripción, puedes definir por defecto el estado de la inscripción como "Confirmado" o vincularlo a un "Evento" específico sin que el usuario tenga que seleccionarlo

- **Sección Detección de duplicados** (solo en bloques enlazados): Es posible definir las reglas para detectar si la persona, organización o registro ya existe en el CRM (por ejemplo, comprobando de forma combinada el Email y el DNI). Si el sistema detecta que el registro ya existe, se debe elegir que hacer con los duplicados entre 4 acciones concretas:
  - **Actualizar (Sobrescribir)**: Reemplaza los datos existentes en el CRM por los nuevos datos introducidos en el formulario.

  - **Ampliar**: Añade la información nueva del formulario solo en aquellos campos que en el CRM estuvieran vacíos, respetando los datos que ya existían.
    
  - **Ignorar**: Ignora por completo los datos recibidos en ese bloque para no modificar el registro original del CRM.
    
  - **Error**: Detiene el procesado y genera un error (útil cuando un formulario solo debería aceptar registros estrictamente nuevos).

- **Sección Relaciones** (solo en bloques enlazados): Esta sección es fundamental cuando el formulario interactúa con más de un módulo del CRM. Sirve para definir cómo se conectan los registros generados entre sí dentro de la base de datos.
  - Al definir una relación, se indicará su tipo de relación con que enlazar el bloque de datos actual y el bloque de datos destino de la relación. Al hacerlo, el sistema creará automáticamente las acciones "por detrás" para enlazar ambos registros una vez se guarde la respuesta.

  - Ejemplo práctico: Imaginemos un formulario de inscripción de una persona a un evento. En esta sección de Relaciones se indicará que ambos bloques (Persona e Inscripción) están unidos. Así, cuando el usuario envíe el formulario, el sistema no solo creará los dos registros por separado, sino que automáticamente generará el vínculo formal entre ambos dentro del CRM.


### Paso 3: Lógica y automatismos ###
En este paso se configurará qué ocurre "por detrás" cuando alguien hace clic en "Enviar" del formulario.
- Al añadir un bloque de datos en el paso anterior, el sistema ya habrá creado automáticamente una acción para Guardar el registro en el CRM.

- Puedes añadir nuevas acciones a la lista, como Enviar una notificación por correo, Añadir a una Lista de Público Objetivo (LPO) o Redireccionar a una página de agradecimiento de la entidad.

### Paso 4: Maquetación y diseño visual ###
Llega el momento de darle forma visual al formulario, separándolo de su lógica.
• Organiza los campos creando Secciones visuales (como Pestañas o Acordeones) e indicando la disposición en columnas.
• Puedes definir un esquema de colores, añadir una cabecera con el logotipo de la entidad, un pie de página o incluso inyectar tu propio código CSS para ajustarlo a la imagen corporativa.

### Paso 5: Resumen y publicación ###
Revisión final y opciones de difusión.
• Podrás hacer una previsualización para ver el resultado exacto.
• Obtendrás la URL pública generada por el CRM para enlazar el formulario en redes sociales o correos.
• También tendrás la opción de descargar el código HTML o el código para incrustar el formulario (iframe) directamente dentro de la página web de la entidad.

## Gestión de Respuestas y Trazabilidad Total ##
A diferencia de sistemas anteriores, los Formularios Web Avanzados ofrecen una auditoría completa de lo que ocurre con las respuestas:
• Módulo de Respuestas: Cada vez que alguien envía un formulario, el envío se guarda de forma íntegra. Podrás consultar exactamente qué datos se introdujeron en ese momento y el estado en el que se encuentra la respuesta (Pendiente, Procesada, o Error).
• Módulo de Vínculos: Ofrece trazabilidad detallada. Desde la respuesta, podrás ver qué impacto ha tenido en el CRM: qué registros concretos se han creado, cuáles se han actualizado (o ampliado) y cuáles se han ignorado por ser duplicados.