```
# Manual de usuaries para añadir iconos a SinergiaCRM

Este manual ofrece instrucciones para agregar nuevos iconos al sistema SinergiaCRM utilizando un script de Bash.

## Requisitos previos

Antes de comenzar, asegúrate de tener instaladas las siguientes dependencias y que estén disponibles en el PATH del sistema:

- git (para clonar el repositorio SuiteP-Icon-Font)
- svgo (para comprimir y copiar SticIcons a la carpeta tmp)
- icon-font-generator (para crear la fuente de iconos a partir de los archivos SVG)
- scssphp (para compilar los archivos SCSS en archivos CSS)

### Instalación de svgo e icon-font-generator

Si aún no los tienes instalados, instala svgo e icon-font-generator con los siguientes comandos. Asegúrate de tener instalados node y npm en el equipo:


sudo npm install -g svgo
sudo npm install -g icon-font-generator


### Cómo agregar un nuevo ícono

Para agregar un nuevo ícono a SinergiaCRM, sigue estos pasos:

1. Copia el archivo del ícono a la carpeta `SticUtils/Icons/SticIcons`.
2. Se recomienda comenzar con una copia de uno de los íconos existentes, renombrarlo y modificarlo con Inkscape.
3. Guarda el archivo modificado como un archivo SVG simple.

Después de agregar el nuevo ícono, ejecuta  el script usando `bash InstallNewIcons.sh`. Esto actualizará las fuentes de iconos y las hojas de estilo de SinergiaCRM con el nuevo ícono.

## Uso del repositorio SuiteP-Icon-Font

El script utiliza el repositorio [SuiteP-Icon-Font](https://github.com/salesagility/SuiteP-Icon-Font) para obtener los archivos de íconos necesarios para las fuentes de íconos y las hojas de estilo de SinergiaCRM. El repositorio se clona en la carpeta `SuiteP-Icon-Font`, que se elimina al final del script.
```