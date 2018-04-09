<?php

return [
    'gender' => [
        'm' => 'Masculino',
        'f' => 'Femenino'
    ],
    'status_hacienda_color' => [
        'aceptado' => 'success',
        'recibido' => 'warning',
        'procesando' => 'warning',
        'rechazado' => 'danger',
        'error' => 'danger'
    ],
    'tipo_documento_color' => [
        '01' => 'success',
        '02' => 'warning',
        '03' => 'danger',
        '04' => 'info',
        '05' => 'info',
        '06' => 'info',
        '07' => 'info',
        '08' => 'info'
    ],
    'tipo_documento' => [
        '01' => 'Factura electrónica',
        '02' => 'Nota débito eléctronica',
        '03' => 'Nota crédito eléctronica',
        '04' => 'Tiquete electrónico',
        '05' => 'Nota de despacho',
        '06' => 'Contrato',
        '07' => 'Procedimiento',
        '08' => 'Comprobante emitido en contingencia',
        '99' => 'Otro'
    ],

    'medio_pago' => [
        '01' => 'Efectivo',
        '02' => 'Tarjeta',
       
    ],
    'condicion_venta' => [
        '01' => 'Contado',
        '02' => 'Crédito',

    ],
    'codigo_referencia' => [
        '01' => 'Anula Documento de Referencia',
        '02' => 'Corrige texto documento de referencia',
        '03' => 'Corrige monto',
        '04' => 'Referencia a otro documento',
        '05' => 'Sustituye comprobante provisional por contingencia',
        '99' => 'Otro',

    ],

];
