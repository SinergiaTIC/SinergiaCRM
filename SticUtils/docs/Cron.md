# Cron

El fichero `cron.php` original de SuiteCRM (y de SugarCRM) solo funciona con php-cli y este entorno no lo tenemos duisponibles en las instalaciones de SinergiaCRM actuales, se ha creado el archivo `SticCron.php`, donde evitamos esta protección de manera que el fichero puede ser invocado via http(s). 

Se ha optado por no modificar el fichero original para evitar que sea sobrescrito por futuras modificaciones.

Esto implica que los cron que llaman a los servidores, han de llamar a este fichero, y no a `cron.php`.
