# Notas Generales
## Soporte para massupdate en todos los campos
- **2019-10-11** Se ha añadido soporte para habilitar massupdate en todos los tipos de campo, se gun se describe en https://wikidev.sinergiacrm.org/index.php?title=Hacer_actualizable_masivamente,_campos_que_por_defecto_Estudio_no_permite_establecerlos_como_tal_(texto,_moneda...). El soporte se ha añadido a todos los módulos de SinergiaCRM y a los módulos de Sugar presentes en `custom/modules`. 
Se ha modificado el fichero custom/include/SticMassUpdate.php para que los campos textarea sean actualizables masivamente en caso de ser expresamente indicado en los vardefs correspondientes, pero se ha excluido el campo 'description' de todos los módulos ([26a68963a7b9a0167d2f6b9e7a477fa751f03988](STIC#26a68963a7b9a0167d2f6b9e7a477fa751f03988#diff-069a28d6f66be6f00fadc3f5116e5ca9R22)) , ya que este por defecto aparece activado como mass update y es un campo que no aparece en el fichero vardef de cada módulo, lo que impide desactivarlo facilmente.

## Opciones merge
- **2019-10-11** Se ha establecido las opciones para control de duplicados en los módulos stic, utilizando el siguiente patrón:

  - Todos los campos de todos los módulos se han establecido con la propiedad `'duplicate_merge'='enabled'` de manera que estarán disponibles en el momento de realizar la combianción de duplicaos.
  - Todos los campos se han habilitado para que aparezcan disponibles en la búsqueda de duplicados. (`'merge_filter'='enabled'`)
  - Todos los campos `name` se han habilitado por defecto en la búsqueda de duplicados (`'merge_filter'='selected'`)

## Parche propio para resolver el problema de validación JS con etiquetas que contienen apóstrofes en campos de tipo date

Para resolver el problema reportado en https://github.com/SinergiaTIC/SinergiaCRM-SugarCRM/pull/117#issuecomment-579874797 se ha aplicado un parche en el fichero `include/javascript/javascript.php` (commit STIC#22e43756989c99fde5fbcac658679d3988f15c8d). 
Al tratarse de un fichero propio de SuiteCRM este puede quedar sobreescrito en cualquier actualización que exista, por lo que ha de ser tenido en cuenta para restaurar este parche.


## recordId disponible en el Javascript de las vistas de detalle.
Se ha incluido el código necesario en el fichero `SticInclude/Views.php` para que el valor del registro activo de la vista de detalle esté disponible en el Javascript de la página, en el objeto `STIC.record.id`


## site_url disponible en el Javascript de todas las vistas
Se ha incluido el código necesario en el fichero `SticInclude/Views.php` para que el valor del la configuración `site_url`  disponible en el Javascript de la página, en el objeto `STIC.siteUrl`

