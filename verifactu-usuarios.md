# Manual de usuario – Verifactu en SinergiaCRM

Este documento explica cómo usar la funcionalidad Verifactu para la gestión de facturas en SinergiaCRM. Está orientado a usuarios y administradores funcionales, evitando detalles técnicos innecesarios.

## 1. ¿Qué es Verifactu?
Verifactu es el sistema de la AEAT para el registro y trazabilidad de facturas. En SinergiaCRM permite:
- Enviar facturas a la AEAT.
- Generar un QR verificable en el PDF.
- Mantener una cadena de registros (hash) para trazabilidad.
- Gestionar rectificativas y anulaciones según normativa.

## 2. Conceptos clave
- **Borrador (draft)**: factura editable, todavía no enviada a AEAT.
- **Emitida (emitted)**: factura enviada a AEAT. Este estado dispara el envío.
- **Aceptada/Rechazada/Anulada**: estados de respuesta de AEAT.
- **Rectificativa**: factura que corrige otra factura previa.
- **Anulación**: elimina un registro de factura en AEAT (irreversible).

## 3. Flujo de trabajo recomendado
1. **Crear la factura** con todos los datos necesarios.
2. Verificar que el cliente tiene **NIF/CIF** informado (imprescindible para ciertos envíos).
3. Guardar la factura en **Borrador (draft)**.
4. Cuando esté lista, cambiar el estado a **Emitida (emitted)**.
5. El sistema envía automáticamente la factura a AEAT.
6. Revisar el estado AEAT en la ficha de la factura.
7. Descargar/visualizar el PDF: se incluirá el **QR** para verificación.

## 4. Envío a AEAT
- El envío se realiza automáticamente al pasar a **Emitida**.
- Si hubiera un fallo puntual, existe un botón para **reenviar a AEAT** desde la ficha.
- Tras el envío, se actualiza el **estado AEAT** y se guarda el **CSV** de respuesta.

## 5. QR en el PDF
- Todas las facturas emitidas incluyen un QR en el PDF.
- El QR permite la comprobación de la factura en la AEAT.

## 6. Facturas rectificativas
### 6.1. Cuándo usar una rectificativa
Se usa cuando es necesario corregir una factura ya emitida (importes, datos, etc.).

### 6.2. Cómo crear una rectificativa
1. Abrir la factura **emitida**.
2. Usar la acción **Crear factura rectificativa**.
3. El sistema generará una nueva factura con los datos copiados.
4. Revisar los campos de rectificación (tipo y base legal).
5. Emitir la rectificativa cambiando su estado a **Emitida**.

### 6.3. Requisitos
- La factura original debe estar **emitida** y enviada a AEAT.
- Deben completarse los campos obligatorios de rectificación.

## 7. Anulación de facturas
### 7.1. Cuándo anular
La anulación se utiliza cuando una factura **no debería existir**. Es irreversible.

### 7.2. Cómo anular
1. Abrir la factura con estado AEAT **Aceptada**.
2. Usar la acción **Anular factura**.
3. Confirmar la operación.
4. El estado pasará a **Anulada** y, en la descripción de la factura, se añadirá un bloque "Auditoría Verifactu" con el hash y el CSV originales y los datos de la anulación para futuras comprobaciones.

### 7.3. Diferencias con rectificativa
- **Rectificativa**: corrige datos creando una nueva factura.
- **Anulación**: elimina el registro en AEAT, sin crear factura nueva.

## 8. Estados AEAT y mensajes habituales
- **Pendiente**: a la espera de respuesta de AEAT.
- **Aceptada**: registrada correctamente.
- **Rechazada**: error en el envío (revisar NIF, datos o configuración).
- **Anulada**: registro eliminado en AEAT.

## 9. Certificado digital (para administradores funcionales)
Para enviar facturas a AEAT es imprescindible un **certificado digital** válido.
- Se gestiona en: **Administración → Certificado Digital**.
- Una vez cargado, el sistema lo usa automáticamente en los envíos.
- Si el certificado caduca o se elimina, los envíos fallarán.

## 10. Buenas prácticas
- Verificar siempre que el cliente tenga NIF/CIF.
- Emitir solo facturas definitivas.
- **Respetar el orden de fechas**: no es posible enviar a AEAT una factura con fecha anterior a la última ya registrada. 
- Usar **rectificativas** para correcciones, y **anulación** solo en casos excepcionales.
- Si anulas una factura que no es la última, espera a que el estado cambie a **Anulada** antes de enviar la siguiente: el sistema enlazará automáticamente con el hash de la anulación más reciente, sin pasos manuales.
- Confirmar que el PDF contiene el QR antes de enviar a cliente.

## 11. Preguntas frecuentes
**¿Puedo editar una factura emitida?**
No. Una vez enviada a AEAT, debe corregirse con una rectificativa.

**¿Qué hago si AEAT rechaza una factura?**
Corregir los datos, reenviar la factura o crear una rectificativa si ya fue emitida.

**¿Se puede anular una rectificativa?**
Sí, el sistema permite anular facturas normales y rectificativas.
