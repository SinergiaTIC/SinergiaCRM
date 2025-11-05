# Firmas ( [Pull Request #726](https://github.com/SinergiaTIC/SinergiaCRM/pull/726))
## Documentación de funcionalidades relacionadas con firmas
### Resumen funcional
Hay tres módulos principales relacionados con la gestión de firmas electrónicas en SinergiaCRM:
   - stic_Signatures: Módulo principal de gestión de firmas.
   - stic_Signers: Módulo de gestión de firmantes asociados a cada firma.
   - stic_Signature_Templates: Módulo de plantillas de firma reutilizables.

Un proceso de firma típico implica los siguientes pasos:
1. Crear una plantilla PDF en el módulo AOS_PDF_Templates con el contenido que se desea firmar.
   - Esta plantilla debe incluir un campo de firma (signature field) mediante el botón  añadido en el editor de plantillas PDF.
   - Las plantillas destinadas a ser firmadas por los representantes legales de alguna persona pueden incluir tanto datos del propio firmante como del representado.
2. Crear un registro en el módulo stic_Signatures
   - Seleccionando la plantilla PDF creada en el paso anterior.
   - Indicando la ruta a las Perosnas o Usuarios que deben firmar el documento.
   - Configurando opciones adicionales como la plantilla de email para notificaciones, el método de autenticación (OTP o sin autenticación), etc.
3. Añadir firmantes al registro de firma
   - Mediante la vista de lista del módulo principal de la Firma (el mismo que de la plantilla PDF), seleccionar los elementos que deben ser incluidos en la firma (Personas, Inscripciones, Compromisos de PAGOs, etc. Potencialmente cualquier módulo de CRM puede ser el módulo principal de la firma). Esto puede hacerse mediante el botón "Añadir al proceso de firmas" en la vista de lista del módulo stic_Signatures, o bien desde el botón similar que hay en la vista de detalle.
   El sistema creará automáticamente los registros de firmantes en el módulo stic_Signers, vinculándolos al registro de firma correspondiente.
   Para el caso de firmantes que representan a otras personas, se crearán tantos registros de firmantes como personas personas representantes del titular del registro haya.
4. Enviar solicitudes de firma a los firmantes. Hay dos opciones:
   - **Inmediato:** Enviar un correo electrónico a cada firmante seleccionado desde la vista de lista del módulo stic_Signers, utilizando el botón "Solicitar firma por correo electrónico" o desde el botón similar que hay en la vista de detalle.
   - **Como campaña de notificaciones:** Desde el subpanel de notificaciones del registro de firma, crear una campaña de notificaciones que envíe las solicitudes de firma a los firmantes. Esto permite programar el envío de las solicitudes y hacer un seguimiento más detallado.
5. El firmante recibe el correo electrónico con la solicitud de firma, que incluye un enlace al portal de firmas.
    - Según se haya configurado la firma, el firmante deberá autenticarse mediante un código OTP enviado por email o SMS, o indicando su documento de identificacion, su teléfono o su fech de nacimiento, o bien podrá acceder directamente al portal de firmas.
    - El portal de firmas mostrará el documento a firmar y el firmante podrá validarlo, según se haya configurado de una de las dos sigueintes maneras:
      - Firma manuscrita: Escribiendo su nombre con el ratón o el dedo (en dispositivos táctiles), o bien usando una firma creada a partir del texto de su nombre, o bien utilizando una imagen de su firma que tenga disponible.
      - Botón de aceptación: Pulsando un botón de "Aceptar" o "Firmar" sin necesidad de una firma manuscrita.
6. Una vez firmado el documento, el sistema genera el PDF firmado, que incluye la firma del firmante en el campo correspondiente, mostrando un botón para descargar el PDF y otro para enviarlo como adjunto al correo electrónico.
El documento firmado según se haya configurado, puede contener una página adicional con la auditoría de la firma, que incluye información sobre el firmante, la fecha y hora de la firma, etc.
El firmante podrá acceder al portal de firmas para descargar el documento firmado en cualquier momento, utilizando el mismo método de autenticación que se haya configurado.

### Módulos y desarrollos específicos incluidos
Para crear y gestionar los procesos de Firma y los firmantes, se han añadido los siguientes elementos y desarrollos especificos en SinergiaCRM:
#### Modulo Firmas (stic_Signatures)
La vista de edición está customizada para funcionar en modo asistente, con los siguientes pasos:
1. Seleccionar la plantilla PDF, puesto que de ella depende el módulo prinicpal del proceso de firma.
2. Seleccionar la ruta para a seguir para determinar qué personas o usuarios deben firmar el documento. En este proceso se muestran las relaciones disponibles para el módulo principal de la firma, dónde este está en el lado _n_ de la relación.
3. Configurar las opciones de la firma: 
     - Estado: Borrador, Abierta, Completado, Vencida.  (Cabe señalar que el estado por defecto es Borrador, y que el sistema no permite añadir firmantes ni enviar solicitudes de firma si el estado es Borrador). Para operar con normalidad el estado debe ser Abierta.
     - Método de autenticación: OTP por email, OTP por SMS, OTP por ambos sistemas, Documento de identificación, Teléfono, Fecha de nacimiento, Enlace único (sin autenticación).
     - Fecha de activación y fecha de vencimiento, que determinan el periodo en el que los firmantes pueden firmar el documento.
     - Plantillas de email para notificaciones: Permite seleccionar una plantilla de email personalizada para las notificaciones, en lugar de la plantilla por defecto del sistema.
     - Modo de firma: Firma manuscrita o botón de aceptación.
     - Incluir página de auditoría en el documento firmado.
     - En representación: Permite indicar si los titulares de la firma  (Por ejemplo, la Persona de una inscripción) van a firmar directamente el documento o si van a ser representados por otras personas (por ejemplo, los padres firmando en representación de un menor,  etc). Las opciones son:
        - No (los titulares firman directamente, es el valor por defecto).
        - Sí, solamente un representante.
        - Sí, todos los representantes.
El módulo Firmas incluye los siguientes subpaneles:
- Firmantes: Muestra los firmantes asociados a la firma. No disponible la opción de añadir nuevos firmantes manualmente, ya que estos se crean automáticamente al añadir registros al proceso de firma desde la vista de lista o detalle del módulo principal de la firma.
- Notificaciones: Permite ver y crear campañas de notificaciones para enviar las solicitudes de firma a los firmantes.
- Registro de la firma: Muestra el historial de acciones realizadas en el proceso de firma (envío de solicitudes, firmas realizadas, etc).

#### Módulo Firmantes (stic_Signers)
Este módulo gestiona los firmantes asociados a cada proceso de firma. 
Los campos relevantes de este módulo son:
     - Firmante: Campo flex relate que muestra el nombre del firmante (Persona o Usuario).
     - Estado de la firma: 
          - Pendiente. Es el valor por defecto al crear el firmante.  
          - Firmado. Indica que el firmante ha completado el proceso de firma.
          - Vencido. Indica que el plazo para firmar ha expirado.
          - Ya no se necesita la firma. Indica que la firma ya no es necesaria para el documento, porque otro firmante autorizado ya ha firmado.
     - Fecha de firma: Fecha y hora en la que el firmante ha completado la firma.
     - Registro relacionado: Campo Flex relate que muestra el registro relacionado con el proceso de firma (Inscripción, Compromiso de Pago, Persona, Usuario, etc).
     - Documento PDF. Enlace al documento PDF firmado por el firmante.
     - Código de verificación: Es un hash generado a partir del documento PDF ya firmado, que permite verificar la autenticidad del documento firmado. Generado a partir del algoritmo SHA256.
     - Autorizado a firmar en nombre de: En caso de que el firmante represente a otra persona, este campo indica a quién representa.
     - Teléfono y Dirección de correo electrónico: Datos de contacto del firmante, utilizados para enviar las solicitudes de firma y los códigos OTP si es necesario, se extraen de la persona o el usuario al generarse el registro del firmante.

El módulo Firmantes incluye los siguientes subpaneles:
- Registro de la firma: Muestra el historial de acciones realizadas por el firmante en el proceso de firma (envío de solicitudes, firmas realizadas, etc).

El módulo Firmantes incluye los siguientes botones de acción en la vista de  detalle:
- Solicitar firma por correo electrónico a: Envía un correo electrónico al firmante con la solicitud de firma y el enlace al portal de firmas.
- Ir al portal de firmas: Abre el portal de firmas en una nueva pestaña del navegador, permitiendo  acceder directamente al documento para firmar. Útil para el caso de las firmas presenciales. 
- Copiar URL del portal de firmas: Copia al portapapeles la URL del portal de firmas para enviarla al firmante por otros medios (WhatsApp, SMS, etc).

Tambien incluye en la vista de lista el botón:
- Solicitar firma por correo electrónico: Permite añadir registros al proceso de firma desde la vista de lista. Esta opción está limitada a un máximo de 20 mensajes por envío para evitar problemas de rendimiento.


#### Módulo Registro de la firma (stic_Signature_Logs)
Este módulo registra todas las acciones realizadas en el proceso de firma, tanto por los firmantes como por el sistema. 
Los campos relevantes de este módulo son:
- Acción: Descripción de la acción realizada (envío de solicitud, firma completada, etc).
- Fecha y hora: Fecha y hora en la que se realizó la acción.
- Firmante: En caso de que la acción haya sido realizada por un firmante, este campo muestra el nombre del firmante.
- Firma: En caso de que la acción pertenezca a un proceso de firma, este campo muestra el nombre de la firma.
- Agente de usuario: Información del navegador o dispositivo utilizado para realizar la acción.
- Dirección IP: Dirección IP desde la que se realizó la acción.

#### Módulo Personas
Se ha añadido un subpanel en el módulo Personas para mostrar las firmas relacionadas con la persona (registros del módulo Firmantes). Este subpanel muestra todas las firmas en las que la persona es firmante o bien es representada por otro firmante. En el subpanel se indica  quien es el tittular de la firma y quien es el firmante que representa a la persona.

#### Módulo Usuarios
Se ha añadido un subpanel en el módulo Usuarios para mostrar las firmas relacionadas con el usuario (registros del módulo Firmantes).

#### Módulo Campañas 
Se ha añadido la lógica necesaria para enviar solicitudes de firma desde campañas de notificaciones, así como la posibilidad de crear automáticamente LPOs basadas en filtros predefinidos para añadir los firmantes a la campaña de notificaciones.

#### Módulo Listas de público objetivo (LPO)
Se han habilitado las opciones para crear automáticamente LPOs basadas en filtros predefinidos y añadir los firmantes a la campaña de notificaciones.

#### Módulo Plantillas PDF (AOS_PDF_Templates)
Se ha añadido un botón en el editor de plantillas PDF para insertar campos de firma (signature fields) en la plantilla. Estos campos son los que se rellenarán con la firma del firmante en el documento PDF final.
También se ha añadido la lógica necesaria incluir en la plantilla PDF los campos de la Persona cuando esta es la representante o cuando es la persona titular y va a ser representada por otra persona. Aunque esta opción aparece siempre, el renderizado solamente lo tiene en cuenta cuando en el renderizado del la plantilla en el contexto de la Firma.

#### Eventos (stic_Events) 
Se ha añadido la posibilidad de crear automáticamente LPOs basadas en filtros predefinidos y se ha añadido un filtro que permite seleccionar los inscritos confirmados en el evento.

#### Mensajes (stic_Messages)
Se ha realizado una pequeña modificación en el envío de mensajes para permitir enviar mensajes SMS desde el portal de firmas, debido a que en este contexto no hay un usuario autenticado en el CRM

#### Entorno Personal (stic_Personal_Environment)
Se ha añadido el campo _Firmante autorizado_, para indicar que la **Persona del entorno** puede representar a la **Persona base** del entorno personal. Esta información se utiliza en el proceso de firma para determinar si la(s) persona(s) del entorno autorizadas pueden firmar en representación de la persona base.


### Desarrollos transversales incluidos
Puesto que (potencialmente) los procesos de firma pueden lanzarse desde cualquier módulo de CRM, **se ha añadido el botón "Añadir al proceso de firmas"** en la vista de lista y en la vista de detalle de todos los módulos, que permite añadir los registros seleccionados al proceso de firma. Este botón abre una ventana emergente que permite seleccionar la Firma a la que se van a añadir los registros y para la cual van a crease los registros de Firmantes. 
En esta ventana emergente se muestra un listado de las firmas abiertas (estado "Abierta") que utilizan el módulo actual como módulo principal de la firma, para que el usuario pueda seleccionar la firma a la que desea añadir los registros seleccionados.


## Detalle de los desarrollos colaterales incluidos
### Parseado de plantillas de email en campañas de notificaciones.

 Se ha modificado el parser de plantillas de email para que en las campañas de notificaciones, los campos de las plantillas se resuelvan en primer lugar con el módulo del contacto, interesado, usuario u organización notificado y en segundo lugar con el módulo que genera la notificación (firma, subvención, evento, etc). Esto permite utilizar en las plantillas de email campos de ambos módulos.

 También se ha corregido un error en el parseado de plantillas que impedía que se parsearan los campos del módulo Users, modificando el desarrollo que se hizo en el PR https://github.com/SinergiaTIC/SinergiaCRM/pull/696

En el parseado de las plantilla de email se ha incluido la sustitución de la variable $sugarurl, que se sustituye por la URL base de SugarCRM, obtenida de la configuración del sistema `$_sugar_config[‘site_url’]`. (esto debe ser documentado en campañas de todo tipo, ya que las url que se construyan mediante esta variable no podan ser incluidas en el tracking de enlaces de las campañas).

### Parseado de plantillas de email en el envío de solicitudes de firma directas
Se ha añadido en SticInclude/Utils.php una función `SticUtils::parseEmailTemplate($templateId, $Beans)` que permite parsear plantillas de email utilizando múltiples Beans para la sustitución de variables. Se ha añadido esta función debido a la dificultad de parsear plantillas de email que contienen variables de múltiples módulos (contactos, usuarios, firmas, etc) en el contexto del envío de solicitudes de firma.
Se ha colocado en SticInclude/Utils.php para que pueda ser utilizada tanto en el módulo de firmas como en otros módulos relacionados con firmas que puedan necesitar esta funcionalidad en el futuro.

### Campañas de notificaciones

Se ha añadido a las campañas de notificicaciones la posibilidad de crear de manera automática una LPO basada en criterios preestablecidos y añadir la LPO a la propia campaña.

Para ello se ha añadido en el subpanel de creación rápida de campañas de notificaciones un nuevo campo desplegable que permite seleccionar los filtros existentes para las LPOs automáticas.

Este campo tiene por defecto las siguientes opciones para cada uno de los módulos que por ahora soportan esta funcionalidad (Firmas, Subvenciones y Eventos):
- Firmas:
  - Todos los firmantes (stic_Signatures__all_signers)
  - Firmantes pendientes (stic_Signatures__pending_signers)
- Eventos:
  - Inscripciones confirmadas (stic_Events__confirmed_registrations)
- Subvenciones:

La definición de estos filtros predeterminados se hace en la función `action_populateLPOFilters` del controlador `custom/modules/ProspectLists/controller.php`, pero  pueden ampliarse facilmente por los usuarios editando la lista desplegable `notification_auto_prospect_list_name_list` a través de la interfaz de administración de listas desplegables.

La primera parte de nombre del filtro debe ser el nombre del módulo (stic_Signatures, Opportunities o stic_Events) seguido de dos guiones bajos y el nombre del filtro. El nombre del filtro puede ser cualquiera, pero se recomienda que sea descriptivo.

Cuando se establece desde la lista desplegable, el valor interno del item debe seguir el formato `Module__filterName`, donde `Module` es el nombre del módulo (stic_Signatures, Opportunities o stic_Events) y `filterName` es el nombre del filtro.

A la hora de mostrar los filtros en el desplegable de creación rápida de campañas de notificaciones, se utiliza la función `action_populateLPOFilters` del controlador `custom/modules/ProspectLists/controller.php`, que obtiene los valores definidos en el controladosr y los añade a la lista desplegable `notification_auto_prospect_list_name_list`. Esta lista muestra solamente los filtros que aplican al módulo en uso.

La creación de la LPO se realiza mediante una llamada AJAX al método `action_createAutoLPO` del controlador `custom/modules/ProspectLists/controller.php`, que recibe como parámetros el id del registro (firma, subvención o evento), el tipo de filtro seleccionado, el módulo y la etiqueta del filtro. Este método intenta cargar una clase específica para cada módulo que implemente la lógica de creación de la LPO según el filtro seleccionado. 

Se intentara cargar en primer lugar una clase personalizada ubicada en `custom/modules/{Module}/CustomLPOTypes.php`. Si no existe, se cargará la clase por defecto ubicada en `modules/{Module}/LPOTypes.php`.

Estas clases se encuentran en la ruta `custom/modules/<module>/CustomLPOTypes.php` y si no existen se usaran las clases por defecto ubicadas en `modules/<module>/LPOTypes.php`. Cada una de estas clases debe implementar un método estático `createLPO($id, $type, $label)` que se encargue de crear la LPO según el filtro seleccionado.

Estas clases por defecto ya implementan los filtros predeterminados mencionados anteriormente, pero pueden ser ampliadas o modificadas por los usuarios creando las clases personalizadas en la ruta `custom/modules/<module>/CustomLPOTypes.php`.

La  `LPOTypes.php` debe construir de manera autónoma la LPO, mediante la función `generateLPO` incluyendo la creación del registro y la vinculación de los contactos, interesados, usuarios u organizaciones a la LPO.

Finalmente la función generateLPO debe devolver un array similar a este:

```php
return [
    'status' => 'success',
    'lpo_id' => $LPOBean->id,
    'lpo_name' => $LPOBean->name,
]
```
o en caso de error:
```php
return false;
```

Hay que tener en cuenta que _en el caso de las firmas_ la LPO se crea concatenando la fecha actual al nombre del filtro, y se utiliza el nombre para verificar si ya existe una LPO con ese nombre. Por lo que si se intenta crear una LPO con el mismo filtro y sobre la misma firma varias veces en el mismo día, tendrían el mismo nombre y no se crearán varias LPOs iguales, sino que se reutilizará la misma y se añadirán los nuevos contactos.


## Pasos para añadir un filtro custom
1. Definir el filtro en la lista desplegable `notification_auto_prospect_list_name_list` a través de la interfaz de administración de listas desplegables. El valor interno debe seguir el formato `Module__filterName`. Por ejemplo para añadir a Eventos un filtro para inscripciones rechazadas:
   - Etiqueta: Inscripciones rechazadas
   - Valor: stic_Events__rejected_registrations
2. Crear la clase personalizada en `custom/modules/<module>/CustomLPOTypes.php` si no existe ya. Por ejemplo para eventos:
   ```php
   <?php
   require_once 'modules/stic_Events/LPOTypes.php';
    class CustomLPOTypes extends LPOTypes {
         public static function createLPO($id, $type, $label) {
              switch ($type) {
                case 'stic_Events__rejected_registrations':
                     return self::generateLPOForRejectedRegistrations($id, $label);
                default:
                     return parent::createLPO($id, $type, $label);
              }
         }

         private static function generateLPOForRejectedRegistrations($id, $label) {
              // Lógica para crear la LPO para inscripciones rechazadas
              // ...
              return [
                'status' => 'success',
                'lpo_id' => $LPOBean->id,
                'lpo_name' => $LPOBean->name,
              ];
         }
    }
    ```

    ## Esquema visual de la funcionalidad

    ```plaintext
    +-------------------------------+
    | Campaña de Notificación       |
    +-------------------------------+
                |
                v
    +-------------------------------+
    | Selección de Filtro LPO       |
    +-------------------------------+
                |
                v
    +-------------------------------+
    | Llamada AJAX a Controller     |
    | (action_createAutoLPO)        |
    +-------------------------------+
                |
                v
    +-------------------------------+
    | Carga de Clase Custom o       |
    | Default LPOTypes              |
    +-------------------------------+
                |
                v
    +-------------------------------+
    | Creación de LPO según Filtro  |
    | (generateLPO)                 |
    +-------------------------------+
                |
                v
    +-------------------------------+
    | Resultado (Éxito/Error)       |
    +-------------------------------+
    ```


    ## TODO: Pendiente
    - Registro relacionado, mostrar correctamente el nombre.
    - Quitar listas de LPO de ejemplos en campañas de notificaciones.
    - Incluir en la documentación la nueva función parseEmailTemplate de SticUtils.


    ### Hecho
    - Mostrar botón para mostrar firmas relacionadas con cada registro en el subpanel de firmas relacionadas.
    - Al llegar al final de documento, se redimensiona el área visible del PDF, hay que revisar si es posible evitar este comportamiento.
    - Añadir en subpanel firmas de personas que han sido representadas.
    - Revisar subpaneles de firmas en personas y usuarios.
    - búsquedas en registro de firmas.
    - Utilizar una plantilla de email para enviar la copia del documento firmado, en lugar de un email genérico.
    - Utilizar una plantilla de email para enviar el OTP, en lugar de un email genérico.
    - Al firmar la página de auditoría no incluye el registro de la acción de firma, ya que esta se registra despues de generar el PDF firmado. Hay que revisar si es posible registrar la acción antes de generar el PDF.
    - Descargar documento firmado desde el portal de firmas no funciona
    - Enviar por email el documento firmado al firmante una vez firmado desde el portal de firmas no funciona
    - Usar la plantilla de email definida en la firma para notificar a los firmantes, en lugar de la plantilla por defecto de notificaciones si está definida.
    - ON_behalf_of_id: convertir a campo relate para poder mostyrar el nombre de la persona en lugar del ID
    - Limpieza de campos innecesarios en firmantes.