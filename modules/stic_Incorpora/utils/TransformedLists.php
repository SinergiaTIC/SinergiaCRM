<?php
// TODO: Incorpora doesn't return the key values of this list
$stic_incorpora_addr_street_type_list = array(
    'Calle' => '1',
    'Avenida' => '2',
    'Apartado' => '3',
    'Bajada' => '4',
    'Camino' => '5',
    'Carretera' => '6',
    'Casa' => '7',
    'Edificio' => '8',
    'Gran Vía' => '9',
    'Muelle' => '10',
    'Parque' => '11',
    'Pasaje' => '12',
    'Paseo' => '13',
    'Plaza' => '14',
    'Poligono' => '15',
    'Subida' => '16',
    'Rambla' => '17',
    'Ronda' => '18',
    'Travesía' => '19',
    'Urbanización' => '20',
    'Vía' => '21',
    'Otros' => 'OTROS',
);

$stic_incorpora_contract_duration_list = array(
   '1A<' => '1w',
   '1M<=3M' => '1w1m',
   '1S<=1M' => '1m3m',
   '3M<=1A' => '3m1y',
   '<=1S' => '1y',
);

$stic_incorpora_disability_degree_list = array(
    '33<=65' => 'greater_equal_33_less_65',
    '65<=' => 'greater_equal_65',
    '<33' => 'less_33',
);

$stic_incorpora_employment_status_list = array(
    'DES_1A<=2A' => 'greater_1y_less_equal_2y',
    'DES_2A<' => 'greater_2A',
    'DES_3M<=1A' => 'greater_3m_less_equal_1y',
    'DES_<=3M' => 'less_equal_3m',
    'NUNCA' => 'NUNCA',
    'OCU_AUTONO' => 'OCU_AUTONO',
    'OCU_CEN_ES' => 'OCU_CEN_ES',
    'OCU_INSERC' => 'OCU_INSERC',
    'OCU_ORDINA' => 'OCU_ORDINA',
    'OCU_OTROS' => 'OCU_OTROS',
);

$stic_contacts_identification_types_list = array(
    'DNI' => 'nif',
    'NIE' => 'nie',
    'OTROS' => 'other',
    'PASAPORTE' => 'passport',
);

$stic_genders_list = array(
    'HOMBRE' => 'male',
    'MUJER' => 'female',
);

$stic_incorpora_nationality_list = array (
    'extranjero' => 'extranjero',
    'nacional' => 'nacional',
    '**not_listed**' => 'extranjero',
);

$stic_incorpora_yesno_list = array (
    '0' => 'NO',
    '1' => 'SI',
);

$stic_incorpora_job_offers_status_list = array (
    'Abierta' => 'ABIERTA',
    'Cerrada' => 'CERRADA',
    'En preparación' => 'PREPARA',
    'En proceso de selección' => 'SELECCION',
    'Dada de baja' => 'OF_BAJA1',
);

// $stic_incorpora_working_day_list = array (
//     'MAÑANAS' => 'MANANAS',
//     'MANYTARDE' => 'MANYTARDE',
//     'TARDE' => 'TARDE',
//     'NOCHE' => 'NOCHE',
//     'FIN_SEM' => 'FIN_SEM',
//     'OTROS' => 'OTROS',
// );