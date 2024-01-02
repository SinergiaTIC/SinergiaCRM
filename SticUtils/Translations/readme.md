# Traducciones al gallego

El presente proceso tiene como objetivo preparar un Pull Request en el repositorio https://github.com/SinergiaTIC/SinergiaCRM-Galician-language para facilitar las traducciones de las nuevas cadenas añadidas a los ficheros de idioma gallego.

Posteriormente habrá que recoger los cambios realizados en los ficheros de gallego en un PR del repositorio de SinergiaCRM.

## 1) Preparar el PR en el repositorio de gallego para hacer las traducciones

Para ello será necesario ejecutar el script `prepareForGLtranslation.sh` comprobando que las rutas indicadas en el apartado de configuración son adecuadas para el equipo donde se ejecute:
- `TRANSLATION_WORK_FOLDER`: Indicará la carpeta de trabajo (que se creará si no existe) en la cual se clonarán los repositorios de SinergiaCRM y del idioma gallego.
- `SOURCE`: nombre de la carpeta en la que se situará la copia del repositorio de SinergiaCRM.
- `LANGUAGE`: abreviación del idioma con el que se va a trabajar (en este caso, gl). Se creará una carpeta con ese nombre donde se situará la copia del repositorio del idioma.

### Acciones que realizará el script SticUtils/Galego/prepareForGLtranslation.sh:
1) Hacer una copia local del repositorio de SinergiaCRM (rama `release`) y del repositorio de idioma gallego https://github.com/SinergiaTIC/SinergiaCRM-Galician-language (rama `main`).
2) Crear una nueva rama en el repositorio de gallego basada en `main` y llamada `gl_translation_<version_crm>` (_version_crm_ será el número de versión actual del repositorio). Cambiar a la rama recién creada.
3) Copiar los ficheros de idioma gallego desde el repositorio de SinergiaCRM al repositorio de gallego.
4) Crear un commit en el repoistorio de gallego incluyendo todos los ficheros de idioma nuevos o modificados.
5) Subir a github la rama recién creada, mediante `push`.

### Acciones a ejecutar tras lanzar el script:
6) En github, en el repositorio de gallego, crear un PR, con el mismo nombre que la rama y asignarselo a la persona traductora, de manera que le llegará un aviso por correo electrónico. No está de mas recordarle a la persona traductora, al menos en las primeras ocasiones, que, sin previo aviso, el proceso terminará **7 días antes** del siguiente despliegue de actualizaciones programado en SinergiaCRM.

## 2) Recoger los cambios hechos en los ficheros de gallego para integrarlos en el repositorio de SinergiaCRM.

### En el repositorio de gallego:
1) Revisar las traducciones realizadas para ver que no existen errores de sintaxis.
2) Cerrar el PR `gl_translation_<version_crm>` mergeándolo con la rama `main`.
3) Eliminar la rama `gl_translation_<version_crm>`.
4) En la copia local del repositorio de gallego, cambiar a la rama `main` y ejecutar `git pull`.

### En el repositorio de SinergiaCRM:
5) Crear una nueva rama llamada `enhancement/gl_translation_<version_crm>` para integrar los ficheros cambiados. 
6) Copiar mediante rsync todos los ficheros del repositorio de gallego al repositorio de SinergiaCRM.
Si se hace desde la raíz del repositorio de gallego:
```rsync -rv --include='gl_ES*.php' --exclude='.git' ./ ../../workkit_php72/app/sinergiacrm/``` 
Es importante usar los filtros de exclusión e inclusión indicados.
7) Crear un PR llamado `Enhancement - Traducciones - Traducciones de Galego <version_crm>`. Este PR seguirá el protocolo normal de validación.
