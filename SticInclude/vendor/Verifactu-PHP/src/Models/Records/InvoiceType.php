<?php
namespace josemmo\Verifactu\Models\Records;

enum InvoiceType: string {
    /** Factura (Art. 6, 7.2 y 7.3 del R.D. 1619/2012) */
    case Factura = 'F1';

    /** Factura simplificada y facturas sin identificación del destinatario (Art. 6.1.D del R.D. 1619/2012) */
    case Simplificada = 'F2';

    /** Factura emitida en sustitución de facturas simplificadas facturadas y declaradas */
    case Sustitutiva = 'F3';

    /** Factura rectificativa (Art 80.1 y 80.2 y error fundado en derecho) */
    case R1 = 'R1';

    /** Factura rectificativa (Art. 80.3) */
    case R2 = 'R2';

    /** Factura rectificativa (Art. 80.4) */
    case R3 = 'R3';

    /** Factura rectificativa (Resto) */
    case R4 = 'R4';

    /** Factura rectificativa en facturas simplificadas */
    case R5 = 'R5';
}
