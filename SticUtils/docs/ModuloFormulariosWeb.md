# Funcionalidades y cambios en el  módulo de Formularios Web

- El módulo ahora se llama stic_Web_Forms.

- Se han borrado varios archivos y una carpeta que estaban en la carpeta raiz: 
    - include/
    - design.php
    - format.php
    - generate.php
    - new.php

- Se han movido los archivos relacionados con entrypoints a una carpeta llamada Entrypoints/

- Se ha integrado la funcionalidad que dependía del módulo de redk.

- Se ha implementado que la fecha de inscripción no aparezca en la vista de Seleccionar Campos del asistente ya que este campo almacenará la Fecha actual del momento cuando se rellene el formulario de Inscripciones. 

- Se ha actualizado la función de encriptación mcrypt_encrypt() por openssl_encrypt() en la comunicación con el TPV. El cambio es debido a que la función mcrypt_encrypt() no funciona correctamente a partir de PHP 7.

- Se ha modificado la forma de obtener las constantes de TPV y PAYPAL (a través de SticInclude) para coger solo los valores necesarios y no todo el objeto. Además, como el campo código ya no existe, los valores que necesita el TPV se asocian a cada constante en un array asociativo. 

