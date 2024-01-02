# Registro de cambios y/o nuevas funcionalidades añadidas en los distintos módulos

## stic_Contacts_Relationships

El módulo **stic_Contacts_Relationships** sustituye a redk_Historico_Relacion_Contactos

### Ficheros añadidos y/o modificados:

- `modules/stic_Contacts_Relationships/LogicHooksCode/stic_lh_contact_relationships.php` contiene el código de los LH del módulo, basicamente:

  - `manage_relationships`

    - Gestiona los cambios en las relaciones, llamando si es necesario al método `CustomContact::setRelationshipType($contactBean)`
    - Establece el nombre del registro **al relacionarlo** con un registro de `Contacts` (Anteriormente se hacía mediante JS).

  - `after_save_contacts_relationships` llama al método `CustomContact::setRelationshipType($contactBean)` en caso de modificarse `start_date`, `end_date` o `relationship_type` para actualizar el valor en `Contacts` si es necesario.

- `custom/Extension/modules/stic_Contacts_Relationships/Ext/LogicHooks/stic_lh_call_contact_relationships.php`. Llamada al código del Logic Hook correspondiente.

- `modules/stic_Contacts_Relationships/stic_Contacts_Relationships.php` se ha añadido el método `is_active` para determinar si la relación es o no activa

## stic_Accounts_Relationships

El módulo **stic_Accounts_Relationships** sustituye a redk_Historico_Relacion_Accountos

### Ficheros añadidos y/o modificados:

- `modules/stic_Accounts_Relationships/LogicHooksCode/stic_lh_account_relationships.php` contiene el código de los LH del módulo, basicamente:

  - `manage_relationships`

    - Gestiona los cambios en las relaciones, llamando si es necesario al método `CustomAccount::setRelationshipType($accountBean)`
    - Establece el nombre del registro **al relacionarlo** con un registro de `Accounts` (Anteriormente se hacía mediante JS).

  - `after_save_accounts_relationships` llama al método `CustomAccount::setRelationshipType($accountBean)` en caso de modificarse `start_date`, `end_date` o `relationship_type` para actualizar el valor en `Accounts` si es necesario.

- `custom/Extension/modules/stic_Accounts_Relationships/Ext/LogicHooks/stic_lh_call_account_relationships.php`. Llamada al código del Logic Hook correspondiente.
- `modules/stic_Accounts_Relationships/stic_Accounts_Relationships.php` se ha añadido el método `is_active` para determinar si la relación es o no activa.

## Contacts

### Ficheros añadidos y/o modificados

- `custom/modules/Contacts/SticUtils.php` contiene los siguientes métodos:

  - `CustomContact::setRelationshipType`. Se encarga de mantener el tipo de relación en la persona.
  - `CustomContact::getAge`. Obtiene la edad de la persona a partir de su fecha de nacimiento.
  - `CustomContact::generateCallFromReturnMailReason`. Genera una llamada en el módulo Calls al guardarse la persona con determinados valores.

- `custom/Extension/modules/Contacts/Ext/LogicHooks/stic_lh_call_contact.php` llamada logic hooks
- `custom/Extension/modules/Contacts/Ext/LogicHooksCode/stic_lh_contacts.php` código de los logic hooks

## Accounts

- `custom/modules/Accounts/stic_utils_account.php` contiene los siguientes métodos:

  - `CustomAccount::setRelationshipType`. Se encarga de mantener el tipo de relación en la organización.
  - `CustomAccount::generateCallFromReturnMailReason`. Genera una llamada en el módulo Calls al guardarse la persona con determinados valores.

- `custom/Extension/modules/Accounts/Ext/LogicHooks/stic_lh_call_account.php` llamada logic hooks
- `custom/Extension/modules/Accounts/Ext/LogicHooksCode/stic_lh_accounts.php` código de los logic hooks

## stic_Settings

El módulo **stic_Settings** sustituye a redk_Constantes

- Desaparece el campo **codigo** y en su lugar se usa **name** para identificar cada registro.
- Se han añadido los siguientes ficheros:

  - `modules/stic_Settings/Utils.php` que contiene las funciones

    - `stic_SettingsUtils::getSetting` para obtener un registro del módulo
    - `stic_SettingsUtils::getSettingsByType` para obtener todos los registros de un mismo tipo.

  - `custom/Extension/modules/stic_Settings/Ext/LogicHooks/stic_lh_call_settings.php` y `modules/stic_Settings/LogicHooksCode.php`, logic_hook que incondicionalmente sustituye cualquier espacio que pueda contener el campo **name** por guión bajo. Además fuerza a que vaya en mayúsculas.

  - `modules/stic_Settings/Utils.js` que contiene la función `getSetting` que permite obtener el valor de un registro individual de settings mediante JS. Esta función solicita mediante AJAX la respuesta al fichero `modules/stic_Settings/controller.php` que contiene la función `action_getSetting`.

# Otros recursos

## SticInclude

Se ha creado esta carpeta para alojar cualquier contenido transversal al CRM propio de SinergiaCRM
Incluye:

### SticInclude/Utils.php

Este fichero contiene la clase `SticUtils` que incluye los siguientes métodos:

- `SticUtils::getRelatedBeanObject` Es una función que permite facilmente recuperar un Bean de un módulo relacionado, alla donde sea necesario. Especialmente útil para el caso de tener que concatenar el nombre del registro con algún valor de un campo relacionado en un LH

## Gestión de vardefs en modulos custom

Para gestionar de una manera simple las modificaciones de vardef en modulos base de suite, se crean en cada módulo base un fichero específico, en la ruta:

- `custom/Extension/modules/Accounts/Ext/Vardefs/- stic_accounts_vardefs.php`
- `custom/Extension/modules/Contacts/Ext/Vardefs/- stic_contacts_vardefs.php`
- `custom/Extension/modules/Calls/Ext/Vardefs/- stic_calls_vardefs.php`
- `...`

En estos ficheros, ademas de indicar cualquier modificaión de un vardef, es necesario indicar de manera expresa si los campos deben o no ser mostrados en actualización masiva. Esto es necesario tras haber aplicado la técnica que permite usar massupdate en cualuier tipo de campo, ya que, como efecto secundario, se muestran en actualización masiva **todos** los campos del módulo base que no tengan indicado un valor para esta propiedad.

## Validaciones Javascript

En los diferentes módulos es necesario hacer ciertas comprobaciones mediante Javascript. En SuiteCRM, estas comprobaciones han de hacerse no solamente en la vista de **edición**, sino también en las vistas de **detalle** o de **lista**, ya que, salvo que se haya indicado lo contrario, los campos se pueden modificar mediante **Inline-Edit**, y a menudo la valiación de los campos, pueden estar o no presentes en la misma vista donde se está realizando la modificación.

Para manejar esta situación se ha prescindido de la librería `CustomFormTools.js` y se aplican otras técnicas y funciones que describen a continuación.

El fichero `SticInclude/js/Utils.js` contiene las siguientes funciones de apoyo para realizar las validaciones:

- **`viewType()`**, determina el nombre de la vista desde en la cuál se está haciendo la validación (edit | detail | list).
- **`getListValueFromLabel()`**, obtiene el valor almacenado de un campo de tipo desplegable, a partir de la etiqueta seleccionada en el idioma activo.
- **`getFieldValue()`**, obtiene el valor de un campo, para ser utilizado en validaciones, (por ejemplo, es necesario conocer el metodo de pago para determinar si el número de cuenta debe ser o no obligatorio). esta funció opera con la siguiente lógica:

  1. Si el campo está presente en la vista (edit | detail | view ) devuelve el valor del campo en pantalla (si es un campo enum, el valor correspondiente a la etiqueta seleccionada).
  2. Si el campo no está presente en la vista actual, lo que es frecuente en las vistas de lista, se consulta el valor existente en base de datos para el registro activo. En este caso se hace una llamada mediante `ajax` a una acción definida en el controller del módulo `stic_Settings` (¿algún sitio mejor?) llamada `action_getFieldValue()` para devolver el valor del campo y usarlo en la validación.

- **`getFieldValueIfNotVisible()`**, se encarga de solicitar a la acción `getFieldValue()` del módulo stic_Settings el valor de un determinado campo, si no está disponibvle en la pantalla (tal como se ha descrito en el punto anterior)

## Ficheros modificados

Se ha modificado el fichero `include/javascript/sugar_3.js` aplicando la solución propuesta en https://github.com/salesagility/SuiteCRM/pull/7627 para solucionar el problema descrito en los issues correspondientes https://github.com/salesagility/SuiteCRM/issues/7626 y https://github.com/salesagility/SuiteCRM/issues/7244
