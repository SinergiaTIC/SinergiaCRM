ver en pr origen


# Notas técnicas Verifactu

## Cambios realizados en el módulo AOS_Invoices
- Se han añadido nuevos campos
- en el desplegable status se han añadido los estados:
  - **draft**: Borrador, en es nuevo valor por defecto del campo.
  - **emitted**: Emitida, se utiliza este estado como disparador para el envio de facturas a AEAT.
- Se ha añadido la lógica para el envío de facturas a AEAT cuando se cambia el estado a "emitted".
- También se ha añadido un botón de acción para forzar el envío de la factura a AEAT, en caso de que por cualquier razón no se haya enviado automáticamente.
- Se han añadido los campos relevantes  a las vistas de lista y detalle, etc
- Se ha añadido una excepción en el parser de plantillas PDF para mostrar la imagen con el código QR en las facturas cuando se usa el campo verifactu_qr_data_c, en lugar de mostrar el contenido del campo.

### Comportamiento al duplicar facturas
Cuando se duplica una factura (mediante el botón "Duplicar" o mediante "Actualización y duplicación masiva"), el sistema automáticamente establece el estado a **"draft"** y limpia todos los campos relacionados con Verifactu. Esto garantiza que la factura duplicada no mantenga información del envío a AEAT de la factura original, evitando así errores y confusiones. Los campos que se resetean son: `verifactu_hash_c`, `verifactu_previous_hash_c`, `verifactu_check_url_c`, `verifactu_aeat_status_c` (se establece en 'pending'), `verifactu_aeat_response_c`, `verifactu_cancel_id_c`, `verifactu_csv_c` y `verifactu_submitted_at_c`. Esta lógica está implementada en el logic hook `before_save` de la clase `AOS_InvoicesHook`.

### Facturas Rectificativas (Por Sustitución)
El sistema soporta la emisión de **facturas rectificativas por sustitución** según lo establecido por la AEAT a través de Verifactu. Una factura rectificativa permite corregir errores en una factura previamente emitida y enviada.

#### Acción "Crear Factura Rectificativa"
Se ha implementado un botón de acción en la vista de detalle de facturas que permite crear automáticamente una factura rectificativa a partir de una factura original.

**Ubicación**: `custom/modules/AOS_Invoices/controller.php` - Método `action_CreateRectifiedInvoice()`

**Requisitos para usar la acción:**
- La factura original debe estar en estado "Emitida" (`status = 'emitted'`)
- La factura original debe haber sido enviada a AEAT (`verifactu_submitted_at_c` no vacío)
- La factura original no puede ser ya una factura rectificativa

**Proceso de creación:**
1. **Validación**: Verifica que la factura cumple los requisitos
2. **Duplicación de datos básicos**: Copia todos los campos relevantes de la factura original:
  - Información del cliente (billing/shipping account y contact)
  - Direcciones de facturación y envío
  - Moneda, fechas, descripción
  - Asignación de usuario
3. **Configuración de campos rectificativos**:
  - `verifactu_is_rectified_c = true`
  - `verifactu_cancel_id_c` = ID de la factura original
  - `verifactu_rectified_date_c` = Fecha de la factura original
  - `verifactu_rectified_type_c = 'S'` (Por sustitución)
  - `verifactu_rectified_base_c = 'R1'` (Por defecto, puede modificarse)
4. **Asignación de nueva serie**: Utiliza la serie configurada para facturas rectificativas
5. **Copiado de totales**: Copia directamente los totales via SQL UPDATE para evitar problemas de formateo
6. **Copiado de grupos de línea**: Duplica los grupos con `format_number()` para mantener el formato correcto
7. **Copiado de líneas de producto**:
  - Inserta directamente en `aos_products_quotes` con todos los campos
  - Inserta también en `aos_products_quotes_cstm` para copiar campos personalizados como `verifactu_aeat_operation_type_c`
8. **Redirección**: Lleva al usuario al modo de edición de la nueva factura rectificativa

**Campos implementados:**
- `verifactu_is_rectified_c`: Checkbox que indica si es una factura rectificativa
- `verifactu_rectified_type_c`: Tipo de rectificación ('S' = Por sustitución, 'I' = Por diferencias). Actualmente solo se implementa por sustitución.
- `verifactu_rectified_base_c`: Base legal de la rectificación según Art. 80 LIVA (R1, R2, R3, R4, R5)
  - **R1**: Error fundado en derecho (Art. 80.1, 80.2, 80.6 LIVA)
  - **R2**: Concurso de acreedores (Art. 80.3 LIVA)
  - **R3**: Crédito incobrable (Art. 80.4 LIVA)
  - **R4**: Otros casos
  - **R5**: Factura rectificativa simplificada
- `verifactu_rectified_invoice_id_c`: Relación con la factura original (opcional, para referencia interna)
- `verifactu_rectified_serie_c`: Serie de la factura que se rectifica
- `verifactu_rectified_number_c`: Número de la factura que se rectifica
- `verifactu_rectified_date_c`: Fecha de expedición de la factura que se rectifica

**Validaciones implementadas:**
- En el logic hook `before_save` se valida que si una factura está marcada como rectificativa, todos los campos obligatorios estén cumplimentados (tipo, base, serie, número y fecha de la factura rectificada).
- Si faltan datos, se impide el guardado y se muestra un mensaje de error descriptivo.

**Lógica de envío:**
- En el método `sendToAeat()` de `AOS_InvoicesUtils`, si la factura es rectificativa, se incluyen automáticamente:
  - El tipo de rectificación (`correctiveType`: Substitution o Differences)
  - La referencia a la factura rectificada (`correctedInvoices`)
- La AEAT registra ambas facturas (original y rectificativa) en su sistema.
- La factura rectificativa forma parte de la cadena de hash normal (no sustituye a la factura original en la cadena).

**Comportamiento al duplicar:**
- Los campos de factura rectificativa también se limpian al duplicar, para evitar que una factura duplicada mantenga referencias erróneas a facturas rectificadas.

### Anulación de Facturas en Verifactu

El sistema implementa la funcionalidad de **anulación de facturas** (RegistroAnulacion) según el estándar Verifactu. La anulación elimina completamente un registro de factura del sistema, a diferencia de las facturas rectificativas que crean un nuevo registro.

#### Diferencias entre Anulación y Rectificación

| Aspecto | Factura Rectificativa | Anulación |
|---------|----------------------|-----------|
| Clase | `RegistrationRecord` (con tipo R1-R5) | `CancellationRecord` |
| XML | `<RegistroAlta>` con TipoFactura R1-R5 | `<RegistroAnulacion>` |
| Resultado | Crea nueva factura que corrige la original | Elimina el registro del sistema |
| Datos enviados | Factura completa + datos de corrección | Solo identificador de factura + encadenamiento |
| Motivo | Requerido (R1-R5) | No requerido |
| Uso | Corregir importes, datos, errores | Eliminar completamente una factura errónea |

#### Acción "Anular Factura"

Se ha implementado un botón de acción en la vista de detalle de facturas que permite anular una factura en AEAT.

**Ubicación**: `custom/modules/AOS_Invoices/controller.php` - Método `action_CancelInvoice()`
**Utilidad**: `custom/modules/AOS_Invoices/SticUtils.php` - Método `sendCancellationToAeat()`

**Requisitos para anular:**
- La factura debe estar aceptada por AEAT (`verifactu_aeat_status_c = 'accepted'`)
- La factura debe tener hash y hash anterior de Verifactu

**Estructura del CancellationRecord:**
```php
class CancellationRecord {
  // Identificador de la factura a anular
  public InvoiceIdentifier $invoiceId;
    
  // Flags opcionales
  public bool $withoutPriorRecord = false;  // Anular registro inexistente en AEAT
  public bool $isPriorRejection = false;    // Reenvío tras rechazo
    
  // OBLIGATORIOS: Referencia a la factura anterior en la cadena
  public ?InvoiceIdentifier $previousInvoiceId;
  public ?string $previousHash;
    
  // Timestamp y hash de la anulación
  public DateTimeImmutable $hashedAt;
  public string $hash;
}
```

**Proceso de anulación:**
1. **Validación de estado**: Verifica que la factura está aceptada por AEAT
2. **Obtención de configuración**: Carga certificado, NIF emisor, entorno (pre-producción/producción)
3. **Creación del Computer System**: Configura el sistema informático de facturación (SIF)
4. **Obtención de factura anterior**: Busca la factura anterior en la cadena para el encadenamiento. Se incluyen facturas anuladas porque su `verifactu_hash_c` contiene el hash del `CancellationRecord` (el último eslabón real).
5. **Creación del CancellationRecord**:
   - Establece el identificador de la factura a anular (NIF, número, fecha)
   - Referencia la factura anterior (previousInvoiceId, previousHash)
   - Calcula el hash del registro de anulación
6. **Envío a AEAT**: Utiliza `AeatClient::send()` con el CancellationRecord
7. **Actualización de estado**: Cambia el estado a `cancelled` y guarda el CSV de respuesta

**Cálculo del Hash de Anulación:**
```
IDEmisorFacturaAnulada={NIF}
&NumSerieFacturaAnulada={Número}
&FechaExpedicionFacturaAnulada={dd-mm-yyyy}
&Huella={hash_factura_anterior}
&FechaHoraHusoGenRegistro={timestamp_ISO8601}
```

**XML de Anulación enviado a AEAT:**
```xml
<sum1:RegistroAnulacion>
  <sum1:IDVersion>1.0</sum1:IDVersion>
  <sum1:IDFactura>
    <sum1:IDEmisorFacturaAnulada>A12345678</sum1:IDEmisorFacturaAnulada>
    <sum1:NumSerieFacturaAnulada>2025/001</sum1:NumSerieFacturaAnulada>
    <sum1:FechaExpedicionFacturaAnulada>05-12-2025</sum1:FechaExpedicionFacturaAnulada>
  </sum1:IDFactura>
  <sum1:Encadenamiento>
    <sum1:RegistroAnterior>
      <sum1:IDEmisorFactura>A12345678</sum1:IDEmisorFactura>
      <sum1:NumSerieFactura>2024/099</sum1:NumSerieFactura>
      <sum1:FechaExpedicionFactura>20-12-2024</sum1:FechaExpedicionFactura>
      <sum1:Huella>ABC123...</sum1:Huella>
    </sum1:RegistroAnterior>
  </sum1:Encadenamiento>
  <sum1:SistemaInformatico>...</sum1:SistemaInformatico>
  <sum1:FechaHoraHusoGenRegistro>2026-01-08T16:30:00+01:00</sum1:FechaHoraHusoGenRegistro>
  <sum1:TipoHuella>01</sum1:TipoHuella>
  <sum1:Huella>DEF456...</sum1:Huella>
</sum1:RegistroAnulacion>
```

**Botón en la interfaz (JavaScript):**

**Ubicación**: `custom/modules/AOS_Invoices/SticUtils.js`

El botón "Anular factura" se muestra en la vista de detalle y:
- Solo se habilita cuando `verifactu_aeat_status_c === 'accepted'`
- Muestra confirmación antes de ejecutar: "¿Está seguro de que desea anular esta factura en AEAT? Esta acción no se puede deshacer..."
- Redirige a `index.php?module=AOS_Invoices&action=CancelInvoice&record={id}`

**Etiquetas de idioma:**
- `LBL_CANCEL_INVOICE`: "Anular factura"
- `LBL_CANCEL_INVOICE_CONFIRM`: Mensaje de confirmación
- `LBL_INVOICE_CANCELLED_SUCCESS`: "Factura anulada correctamente en AEAT"
- `LBL_INVOICE_NOT_ACCEPTED_BY_AEAT`: Error si no está aceptada

**Estado tras anulación:**
- `verifactu_aeat_status_c`: Se cambia a `'cancelled'` (Anulada)
- `verifactu_aeat_response_c`: "Factura anulada en AEAT. CSV: {csv}"
- `verifactu_csv_c`: Código CSV devuelto por AEAT
- `verifactu_hash_c`: Se **sobreescribe** con el hash del `CancellationRecord` (⚠️ pendiente de verificar — ver nota en la sección de validación de orden cronológico)

**Notas importantes:**
- La anulación es **irreversible**
- No requiere indicar motivo (a diferencia de las rectificativas que sí requieren base legal R1-R5)
- Se pueden anular tanto facturas normales como rectificativas
- La anulación mantiene el encadenamiento de hash (se registra en la cadena)
- **Diferencia con rectificativas**: La anulación elimina completamente el registro, mientras que una factura rectificativa crea un nuevo registro corrigiendo el anterior
- **Recomendación**: Usar anulación solo cuando la factura no debería existir (error grave). Para correcciones de datos/importes, es preferible usar factura rectificativa

- Se ha integrado la librería josemmo/verifactu-php en el proyecto, así como todas sus dependencias, definidas en su composer.json. 
- Puesto que la libreria no trabaja correctamente con certificados de sello de entidad, ya que usa la URL prewww1, que solamemnte acepta certificados de persona física, y para los Certificados de sello de entidad debe usarse prewww10, se ha modificado la clase AeatClient para poder indicarle expresamente si se trata de una sello de entidad o no.

- Para permitir la generación de números de factura personalizados, se ha añadido el campo stic_serial_format_c, que permite definir el formato de la serie de la factura. Este formato puede incluir elementos como el año (YEAR), y el número secuencial de su serie (NUM). Por ejemplo, "YYYY-000" para 2024-001, "FACT-0000" para FACT-0000, etc. Tambien se ha convertido el campo number de int a varchar(50) para permitir formatos más flexibles.
- La logica de generación del número de factura esta en la clase AOS_InvoicesUtils, en el método estático generateNextInvoiceNumber, para facilitar su reutilización desde los hooks. Consecuentemente se ha anulado  (comentado) el código que estaba en el fichero del core modules/AOS_Invoices/AOS_Invoices.php que hasta ahora genberaba el número de factura sumando 1 al más alto.
- Se ha añadido el campo verifactu_aeat_operation_type_c en el módulo AOS_Products_Quotes, para permitir definir el tipo de operación AEAT por línea de producto. Este campo es un desplegable que contiene los valores definidos por AEAT.

### Validación de orden cronológico en el envío

Antes de enviar un `RegistrationRecord` a AEAT, el sistema comprueba que la fecha de expedición de la factura a enviar **no sea anterior** a la de la última factura ya registrada en la cadena Verifactu.

**Motivación:** La especificación de la AEAT exige que cada registro de la cadena tenga una fecha de expedición igual o posterior a la del registro precedente. Un registro con fecha anterior provoca el rechazo por inconsistencia de encadenamiento.

**Implementación** (`sendToAeat()`, `SticUtils.php`):
- Se llama a `getPreviousInvoice()` para obtener la última factura registrada (excluyendo las anuladas).
- Si existe predecesora, se compara `$issueDate < $previousInvoiceDate`.
- Si la fecha es anterior, el envío se **bloquea** (no se llama a AEAT), se muestra al usuario un mensaje de error con los datos concretos (fecha de la factura, número y fecha de la última registrada) y se redirige al `DetailView`.
- Si no hay predecesora, la factura es la primera de la cadena y la validación no aplica.

**Búsqueda de la factura anterior** (`getPreviousInvoice()`):
- Filtra facturas con `verifactu_hash_c` no nulo.
- Las facturas anuladas **sí se incluyen**: tras una anulación exitosa, el campo `verifactu_hash_c` se sobreescribe con el hash del `CancellationRecord`, que es el último eslabón real de la cadena. Excluirlas rompería el encadenamiento.
- Ordena por `invoice_date DESC, number DESC` para obtener la más reciente.

> ⚠️ **Pendiente de verificar contra AEAT:** La lógica de incluir facturas anuladas en la cadena (sobreescribir `verifactu_hash_c` con el hash del `CancellationRecord` y no excluirlas en `getPreviousInvoice()`) se basa en la interpretación de la fórmula `CancellationRecord::calculateHash()` de la librería `josemmo/verifactu-php`, que incluye la `Huella` del registro anterior en su cálculo. Sin embargo, **no ha sido verificado empíricamente** contra la plataforma AEAT. Podría ser necesario ajustar este comportamiento tras pruebas reales en preproducción.

**Normalización de fechas** (`parseDateToImmutable()`):
- El campo `invoice_date` del bean puede llegar en formato BD (`Y-m-d`) o en el formato de presentación configurado en el sistema (`d/m/Y`, `d.m.Y`, `d-m-Y`, etc.).
- El helper `parseDateToImmutable()` intenta los formatos en este orden: `d-m-Y`, `Y-m-d`, `d/m/Y`, `d.m.Y`, `m/d/Y`.
- Si ninguno funciona, lanza una excepción con el valor problemático.
- Se usa en todos los puntos donde se construye un `DateTimeImmutable` a partir de un campo de fecha de bean.

## 📦 Librerías añadidas y cometido

- **josemmo/verifactu-php**: librería principal para construir y enviar los registros Verifactu (alta, rectificativas y anulación) y generar el QR.
- **guzzlehttp/guzzle**: cliente HTTP usado por la librería Verifactu para las comunicaciones con AEAT.
- **josemmo/uxml**: utilidad de XML para construir/serializar los modelos Verifactu.
- **symfony/validator**: validaciones estructurales de los modelos/inputs usados por la librería Verifactu.
- **symfony/translation** (+ contracts): mensajes/errores de validación internacionalizados.
- **psr/http-client**, **psr/http-message**, **psr/http-factory**: interfaces estándar de cliente/mensajes HTTP requeridas por Guzzle y la librería Verifactu.
- **ralouphie/getallheaders**: polyfill para cabeceras HTTP, dependencia de Guzzle/PSR.

## 🔐 Gestión de Certificado Digital para Verifactu

El sistema utiliza certificados digitales (formato .pfx o .p12) para firmar las facturas antes de enviarlas a la AEAT a través de Verifactu. Al subir el certificado por primera vez, se solicita su contraseña **únicamente para validarlo y extraer sus componentes**: el certificado X.509, la clave privada y la cadena de certificados de la autoridad certificadora (CA). Estos componentes se convierten al formato PEM estándar y se almacenan por separado en el servidor, encriptados mediante Blowfish con la clave única del sistema.

Una vez almacenado, **el certificado no requiere contraseña para su uso posterior**. Cuando se envía una factura a Verifactu, el sistema recupera automáticamente los componentes encriptados, los desencripta en memoria y los utiliza directamente para firmar el XML de la factura. Además, extrae automáticamente del certificado la información necesaria para el envío: el NIF/CIF del emisor (priorizando el de la entidad en certificados de representante), el nombre del titular y el tipo de certificado (sello de entidad o representante). Esta información se valida al momento de la subida y se muestra claramente en la interfaz de administración.

Este enfoque elimina la necesidad de almacenar o solicitar la contraseña del certificado en cada operación, mejorando tanto la seguridad (la contraseña solo existe durante la subida inicial) como el rendimiento (no hay que desencriptar archivos PKCS12 repetidamente). Los archivos se protegen mediante encriptación en reposo y permisos restrictivos del sistema de archivos, siguiendo las mejores prácticas utilizadas en servidores web de producción.

### Vista de Gestión de Certificados

- **Ubicación**: Administración → Certificado Digital (`index.php?module=Administration&action=SticManageCertificate`)
- **Controlador**: `custom/modules/Administration/controller.php` - Método `action_SticSaveCertificate()`
- **Vista**: `custom/modules/Administration/views/view.sticmanagecertificate.php`
- **Template**: `custom/modules/Administration/templates/SticManageCertificate.tpl`

**Funcionalidades de la vista:**
- Formulario de subida de certificado con validación de contraseña obligatoria
- Visualización de información del certificado actual (si existe):
  - Nombre del archivo original
  - Fecha de subida y usuario que lo subió
  - Detalles del certificado: titular, emisor, fechas de validez, número de serie
  - **Datos extraídos automáticamente** (sección destacada en verde):
    - NIF/CIF del emisor
    - Nombre del titular
    - Tipo de certificado (Sello de entidad / Representante)
- Mensajes informativos sobre el uso de estos datos en Verifactu
- Botón para eliminar el certificado actual

### Validaciones Implementadas

**Al subir el certificado:**
1. ✅ Validación de archivo obligatorio (campo `required` en HTML)
2. ✅ Validación de contraseña obligatoria (campo `required` en HTML + validación PHP)
3. ✅ Verificación de contraseña correcta usando `openssl_pkcs12_read()`
4. ✅ Validación de extracción exitosa de componentes (certificado, clave privada, CA chain)
5. ✅ Verificación de escritura correcta de archivos encriptados
6. ✅ Validación de permisos del directorio de certificados

**Durante el uso del certificado (envío de facturas):**
1. ✅ Verificación de existencia del certificado
2. ✅ Validación de desencriptación correcta de componentes
3. ✅ Extracción y validación de NIF/CIF del certificado
4. ✅ Extracción y validación del nombre del titular
5. ✅ Determinación del tipo de certificado (sello de entidad o representante)
6. ✅ Verificación de vigencia del certificado (no caducado)
7. ✅ Validación de que el certificado puede parsearse con OpenSSL

### Clase Utilitaria: SticCertificateUtils

**Ubicación**: `custom/include/SticCertificateUtils.php`

**Métodos públicos:**
- `getCertificateComponents()` - Obtiene certificado, clave privada y CA chain desencriptados
- `certificateExists()` - Verifica si existe un certificado almacenado
- `getCertificateMetadata()` - Obtiene los metadatos del certificado (sin desencriptar)
- `getCertificateNif()` - Extrae el NIF/CIF del certificado (prioriza organizationIdentifier para certificados de entidad)
- `getCertificateHolderName()` - Extrae el nombre del titular del certificado
- `isEntitySeal()` - Determina si es certificado de sello de entidad (1) o representante (0)
- `isCertificateValid()` - Verifica si el certificado está vigente (no caducado)
- `getParsedCertificateInfo()` - Obtiene información detallada del certificado parseado

**Lógica de extracción de NIF:**
1. **Prioridad 1**: `organizationIdentifier` (NIF de la entidad en certificados de representante) - Ej: `VATES-A39200019`
2. **Prioridad 2**: `serialNumber` (NIF del titular en certificados de sello de entidad) - Ej: `IDCES-99999910G`
3. **Prioridad 3**: Extracción por patrón desde el `CN` (Common Name)
4. Limpieza automática de prefijos: `IDCES-`, `VATES-`, `ES`

**Lógica de extracción de nombre:**
1. **Prioridad 1**: Campo `O` (Organization) para certificados de entidad
2. **Prioridad 2**: Campo `CN` (Common Name) limpiando el NIF del final
3. **Prioridad 3**: Combinación de `GN` (Given Name) + `SN` (Surname) para personas físicas

**Lógica de determinación de tipo:**
- Si tiene campos `GN` o `SN` → **Representante** (0)
- Si tiene campo `O` sin `GN`/`SN` → **Sello de entidad** (1)
- Verificación adicional en el `CN` buscando palabras clave como "SELLO DE ENTIDAD", "REPRESENTANTE", etc.

### Archivos de Certificado

**Estructura en disco** (`custom/certificates/`):
- `private_key_encrypted.bin` - Clave privada encriptada con Blowfish
- `certificate_encrypted.bin` - Certificado X.509 encriptado con Blowfish
- `ca_chain_encrypted.bin` - Cadena de certificados CA encriptada con Blowfish
- `cert_metadata.json` - Metadatos del certificado (JSON sin encriptar, no contiene información sensible)
- `.htaccess` - Protección para denegar acceso web directo

**Formato de cert_metadata.json:**
```json
{
    "original_filename": "nombre_certificado.p12",
    "upload_date": "2025-12-03 13:12:33",
    "uploaded_by": "2",
    "uploaded_by_name": "Nombre Usuario",
    "cert_details": {
        "subject": "/C=ES/serialNumber=IDCES-99999910G/...",
        "issuer": "AC FNMT Usuarios",
        "valid_from": "2024-10-22 09:24:54",
        "valid_to": "2028-10-22 09:24:54",
        "serial_number": "D8EA8642CA980B671753476AAB9352"
    },
    "has_ca_chain": true
}
```