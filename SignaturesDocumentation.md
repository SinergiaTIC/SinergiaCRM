# Firmas ( [Pull Request #726](https://github.com/SinergiaTIC/SinergiaCRM/pull/726))
## Documentación de funcionalidades relacionadas con firmas


## Otros desarrollos colaterales
### Parseado de plantillas de email.

 Se ha modificado el parser de plantillas de email para que en las campañas de notificaciones, los campos de las plantillas se resuelvan en primer lugar con el módulo del contacto, interesado, usuario u organización notificado y en segundo lugar con el módulo que genera la notificación (firma, subvención, evento, etc). Esto permite utilizar en las plantillas de email campos de ambos módulos.

 También se ha corregido un error en el parseado de plantillas que impedía que se parsearan los campos del módulo Users, modificando el desarrollo que se hizo en el PR https://github.com/SinergiaTIC/SinergiaCRM/pull/696

En el parseado de las plantilla de email se ha incluido la sustitución de la variable $sugarurl, que se sustituye por la URL base de SugarCRM, obtenida de la configuración del sistema `$_sugar_config[‘site_url’]`. (esto debe ser documentado en campañas de todo tipo, ya que las url que se construyan mediante esta variable no podan ser incluidas en el tracking de enlaces de las campañas).
  

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
    - Usar la plantilla de email definida en la firma para notificar a los firmantes, en lugar de la plantilla por defecto de notificaciones si está definida.
    - Descargar docuimento firmado desde el portal de firmas no fucniona
    - Enviar por email el documento firmado al firmante una vez firmado desde el portal de firmas no funciona