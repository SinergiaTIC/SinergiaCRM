## Gestión de estilos css

En la adaptación a SuiteCRM, SinergiaCRM ha añadido un nuevo subtema que está activado por defecto en las nuevas instancias.

Este subtema muestra los colores corporativos de SinergiaCRM y está preparado para cambiar facilmente los colores base y adaptarlo a cualquier entidad usuaria.

El subtema _Stic_ se ha creado tomando como base la información de https://docs.suitecrm.com/blog/customizing-subthemes/

La gestión de css se hace mediante Sass https://sass-lang.com/ y permite modificar mediante variables y funciones el css que aplica en todo el CRM

### Para modificar el subtema Stic

Generalmente solo será necesario modificar los siguientes ficheros:

1. _themes/SuiteP/css/Stic/SticPalette.scss_ que contiene la paleta de colores básica que aplica en el subtema (no todos) y alguna función auxiliar.

2. _themes/SuiteP/css/Stic/SticCustom.scss_ Que contiene las customizaciones generales que aplican transversalmente en el CRM.

3. _themes/SuiteP/css/Stic/SticSelectize.scss_ Contiene las customizaciones específicas incluidas en los controles del tipo Selectize para adaptarlas a la apariencia general del subtema.

4. _themes/SuiteP/css/Stic/variables.scss_ generalmente no es necesario modificar este fichero, pero para algunas operaciones avanzadas en posible que sea necesario.

Una vez hechas las modificaciones, es neecsario _compilar_ los ficheros _scss_ para que se genere el fichero _style.css_ que será el que aplique de forma efectiva en el CRM. Esto podemos hacerlo mediante la instrucción:

`./vendor/bin/pscss -f compressed themes/SuiteP/css/Stic/style.scss > themes/SuiteP/css/Stic/style.css`

NOTA IMPORTANTE: Para mantener sincronizado el subtema SticCustom, es importante sincronnizar los cambios que se vayan consolidando en el subtema Stic, lo que puede hacerse directamente copiando los ficheros de la carpeta `themes/SuiteP/css/Stic` sobre `themes/SuiteP/css/SticCustom` de manera que queden sobreescritos en la carpeta de destino.

### Modificar subtema para la propia entidad SticCustom

Siguiendo la misma técnica que en el subtema Stic, se ha creado una carpeta con un subtema llamado **SticCustom**, pensado para habilitar a las entidades que lo soliciten sus propios colores corporativos.

Generalmente solo será necesario modificar la variable _\$stic-base_ estableciendo el color apropiado en cada caso y posteriormente recompilar el fichero _style.css_ mediante

`./vendor/bin/pscss -f compressed themes/SuiteP/css/SticCustom/style.scss > themes/SuiteP/css/SticCustom/style.css`

A partir de ahí los usuarios ya podrán elegir el nuevo subtema en su perfil.
