# Manual de administración – Verifactu en SinergiaCRM

Este documento describe la configuración técnica necesaria para operar Verifactu en SinergiaCRM. Está dirigido a administradores del sistema.

## 1. Requisitos previos
- Certificado digital válido en formato **.pfx** o **.p12**.
- Acceso a **Administración** en SinergiaCRM.
- Datos fiscales correctos de la organización (NIF/CIF).

## 2. Certificado digital
### 2.1. Uso del certificado
El certificado se utiliza para firmar los envíos a la AEAT. Sin un certificado válido no se podrá emitir ni anular facturas en Verifactu.

### 2.2. Tipos de certificado
- **Sello de entidad**: certificado corporativo de la entidad.
- **Representante**: certificado personal de un representante legal.

El sistema detecta el tipo y extrae automáticamente:
- NIF/CIF del emisor.
- Nombre del titular.
- Vigencia del certificado.

### 2.3. Gestión del certificado en SinergiaCRM
Ruta: **Administración → SinergiaCRM → Certificado Digital**

Funciones disponibles:
- Subir un nuevo certificado.
- Ver los datos del certificado activo.
- Eliminar el certificado.

Notas:
- La contraseña solo se solicita al subir el certificado.
- Una vez almacenado, se reutiliza automáticamente.

## 3. Entorno de AEAT
Verifactu admite **preproducción** y **producción**. Asegúrate de configurar el entorno correcto según el caso:
- **Preproducción**: validaciones y pruebas.
- **Producción**: envíos reales.

## 4. Series y numeración de facturas
### 4.1. Campo de formato de serie
Se dispone del campo **stic_serial_format_c** para definir el formato de la serie. Permite personalizar la numeración.

Ejemplos:
- `YYYY-000` → 2024-001
- `FACT-0000` → FACT-0001

### 4.2. Cambio de tipo del número de factura
El número de factura se almacena como texto (varchar) para permitir formatos alfanuméricos.

### 4.3. Recomendaciones
- Definir un formato consistente por serie.
- Evitar cambios de formato una vez emitidas facturas en esa serie.

## 5. Estados Verifactu
- **draft**: borrador, no enviado.
- **emitted**: emitido, enviado automáticamente a AEAT.
- **accepted/rejected/cancelled**: respuesta de AEAT.

## 6. Rectificativas
Para habilitar rectificativas:
- Verificar que las facturas originales están **emitidas**.
- Usar la acción **Crear factura rectificativa** en la vista de detalle.
- Completar los campos obligatorios de rectificación (tipo y base legal).

## 7. Anulación
La anulación solo es posible cuando:
- La factura está **aceptada** por AEAT.
- Se dispone del encadenamiento de hash.

La anulación es irreversible. Debe usarse solo cuando la factura no debería existir.

> **Nota técnica:** Tras una anulación exitosa, el sistema sobreescribe `verifactu_hash_c` de la factura anulada con el hash del `CancellationRecord`. Esto garantiza que la siguiente factura a enviar encadene correctamente con el último registro real enviado a AEAT (el de anulación), manteniendo la integridad de la cadena Verifactu.
>
> ⚠️ **Pendiente de verificar:** Este comportamiento se deduce de la fórmula de cálculo de hash del `CancellationRecord` de la librería `josemmo/verifactu-php` (que incluye la `Huella` del registro anterior), pero **no ha sido probado contra la plataforma AEAT**. Se recomienda validarlo en el entorno de preproducción antes de asumir que es correcto.

## 8. Operaciones por línea
En las líneas de producto se puede indicar el **tipo de operación AEAT** mediante el campo correspondiente en AOS_Products_Quotes. Esto permite detallar la naturaleza fiscal de cada línea.

## 9. Troubleshooting básico
- **Rechazos AEAT**: revisar NIF/CIF, datos obligatorios del cliente o certificado.
- **Sin respuesta**: comprobar conectividad y entorno configurado.
- **Errores de firma**: renovar el certificado o verificar su validez.
- **Error «fecha anterior a la última registrada»**: el sistema bloquea el envío si la `invoice_date` de la factura es anterior a la de la última factura con hash en la cadena. Corrija la fecha o registre primero las facturas intermedias. Las facturas anuladas **sí se tienen en cuenta** al buscar la última registrada, ya que su `verifactu_hash_c` contiene el hash del `CancellationRecord` (⚠️ comportamiento pendiente de verificar contra AEAT — ver sección 7).

## 10. Buenas prácticas
- Mantener el certificado actualizado antes de su caducidad.
- Realizar pruebas en preproducción antes de cambios en producción.
- Restringir las acciones de anulación a perfiles autorizados.
- Respetar el orden cronológico de emisión: la `invoice_date` de cada factura a enviar debe ser igual o posterior a la de la última registrada en la cadena Verifactu.
