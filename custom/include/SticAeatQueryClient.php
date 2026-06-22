<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use josemmo\Verifactu\Exceptions\AeatException;
use josemmo\Verifactu\Models\ComputerSystem;
use josemmo\Verifactu\Models\Records\FiscalIdentifier;
use josemmo\Verifactu\Services\AeatClient;
use UXML\UXML;

class SticAeatQueryClient
{
    const NS_CONSULTA = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/ConsultaLR.xsd';
    const NS_RESPUESTA_CONSULTA = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/RespuestaConsultaLR.xsd';
    const NS_INFO = 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroInformacion.xsd';
    const NS_SOAPENV = 'http://schemas.xmlsoap.org/soap/envelope/';

    private ComputerSystem $system;
    private FiscalIdentifier $taxpayer;
    private Client $client;
    private ?string $certificatePath = null;
    private ?string $certificatePassword = null;
    private bool $isProduction = true;
    private bool $isEntitySeal = false;

    public function __construct(
        ComputerSystem $system,
        FiscalIdentifier $taxpayer,
        ?Client $httpClient = null,
    ) {
        $this->system = $system;
        $this->taxpayer = $taxpayer;
        $this->client = $httpClient ?? new Client();
    }

    public function setCertificate(
        string $certificatePath,
        ?string $certificatePassword = null,
    ): static {
        $this->certificatePath = $certificatePath;
        $this->certificatePassword = $certificatePassword;
        return $this;
    }

    public function setProduction(bool $production): static {
        $this->isProduction = $production;
        return $this;
    }

    public function setEntitySeal(bool $entitySeal): static {
        $this->isEntitySeal = $entitySeal;
        return $this;
    }

    private function getBaseUri(): string {
        if ($this->isEntitySeal) {
            return $this->isProduction ? 'https://www10.agenciatributaria.gob.es' : 'https://prewww10.aeat.es';
        }
        return $this->isProduction ? 'https://www1.agenciatributaria.gob.es' : 'https://prewww1.aeat.es';
    }

    public function query(
        string $year,
        string $period,
        ?string $serieNumber = null,
        ?string $dateFrom = null,
        ?string $dateTo = null,
        ?string $counterpartyName = null,
        ?string $counterpartyNif = null,
        ?ComputerSystem $sifFilter = null,
    ): array {
        $xml = UXML::newInstance('soapenv:Envelope', null, [
            'xmlns:soapenv' => self::NS_SOAPENV,
            'xmlns:sum' => self::NS_CONSULTA,
            'xmlns:sum1' => self::NS_INFO,
        ]);
        $xml->add('soapenv:Header');
        $baseElement = $xml->add('soapenv:Body')->add('sum:ConsultaFactuSistemaFacturacion');

        $cabeceraElement = $baseElement->add('sum:Cabecera');
        $cabeceraElement->add('sum1:IDVersion', '1.0');
        $obligadoElement = $cabeceraElement->add('sum1:ObligadoEmision');
        $obligadoElement->add('sum1:NombreRazon', $this->taxpayer->name);
        $obligadoElement->add('sum1:NIF', $this->taxpayer->nif);

        $filtroElement = $baseElement->add('sum:FiltroConsulta');

        $periodoElement = $filtroElement->add('sum:PeriodoImputacion');
        $periodoElement->add('sum1:Ejercicio', $year);
        $periodoElement->add('sum1:Periodo', $period);

        if (!empty($serieNumber)) {
            $filtroElement->add('sum:NumSerieFactura', $serieNumber);
        }

        if (!empty($counterpartyNif)) {
            $contraElement = $filtroElement->add('sum:Contraparte');
            $contraElement->add('sum1:NombreRazon', $counterpartyName ?? '');
            $contraElement->add('sum1:NIF', $counterpartyNif);
        }

        if (!empty($dateFrom) || !empty($dateTo)) {
            $fechaElement = $filtroElement->add('sum:FechaExpedicionFactura');
            $rangoElement = $fechaElement->add('sum:RangoFechaExpedicion');
            if (!empty($dateFrom)) {
                $rangoElement->add('sum1:Desde', $dateFrom);
            }
            if (!empty($dateTo)) {
                $rangoElement->add('sum1:Hasta', $dateTo);
            }
        }

        if ($sifFilter !== null) {
            $sifElement = $filtroElement->add('sum:SistemaInformatico');
            $sifElement->add('sum1:NombreRazon', $sifFilter->vendorName);
            $sifElement->add('sum1:NIF', $sifFilter->vendorNif);
            $sifElement->add('sum1:NombreSistemaInformatico', $sifFilter->name);
            $sifElement->add('sum1:IdSistemaInformatico', $sifFilter->id);
            $sifElement->add('sum1:Version', $sifFilter->version);
            $sifElement->add('sum1:NumeroInstalacion', $sifFilter->installationNumber);
        }

        $optsElement = $baseElement->add('sum:DatosAdicionalesRespuesta');
        $optsElement->add('sum:MostrarNombreRazonEmisor', 'S');

        $options = [
            'base_uri' => $this->getBaseUri(),
            'headers' => [
                'Content-Type' => 'text/xml',
                'User-Agent' => "Mozilla/5.0 (compatible; {$this->system->name}/{$this->system->version})",
            ],
            'body' => $xml->asXML(),
        ];
        if ($this->certificatePath !== null) {
            $options['cert'] = ($this->certificatePassword === null) ?
                $this->certificatePath :
                [$this->certificatePath, $this->certificatePassword];
        }

        $GLOBALS['log']->debug('Line ' . __LINE__ . ': ' . __METHOD__ . ': Consultation XML: ' . $xml->asXML());

        $response = $this->client->post('/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP', $options);
        $responseBody = $response->getBody()->getContents();
        $responseXml = UXML::fromString($responseBody);

        return $this->parseResponse($responseXml);
    }

    private function parseResponse(UXML $xml): array {
        $nsEnv = self::NS_SOAPENV;
        $nsRes = self::NS_RESPUESTA_CONSULTA;
        $nsInfo = self::NS_INFO;

        $faultElement = $xml->get("{{$nsEnv}}Body/{{$nsEnv}}Fault/faultstring");
        if ($faultElement !== null) {
            throw new AeatException($faultElement->asText());
        }

        $rootXml = $xml->get("{{$nsEnv}}Body/{{$nsRes}}RespuestaConsultaFactuSistemaFacturacion");
        if ($rootXml === null) {
            throw new AeatException('Missing <RespuestaConsultaFactuSistemaFacturacion /> element from response');
        }

        $result = [];

        $cabeceraEl = $rootXml->get("{{$nsRes}}Cabecera/{{$nsInfo}}IDVersion");
        $result['idVersion'] = $cabeceraEl ? $cabeceraEl->asText() : null;

        $obligadoEl = $rootXml->get("{{$nsRes}}Cabecera/{{$nsInfo}}ObligadoEmision");
        if ($obligadoEl) {
            $nameEl = $obligadoEl->get("{{$nsInfo}}NombreRazon");
            $nifEl = $obligadoEl->get("{{$nsInfo}}NIF");
            $result['obligadoEmision'] = [
                'nombre' => $nameEl ? $nameEl->asText() : '',
                'nif' => $nifEl ? $nifEl->asText() : '',
            ];
        }

        $periodoEl = $rootXml->get("{{$nsRes}}PeriodoImputacion/{{$nsInfo}}Ejercicio");
        $result['ejercicio'] = $periodoEl ? $periodoEl->asText() : null;
        $periodoEl = $rootXml->get("{{$nsRes}}PeriodoImputacion/{{$nsInfo}}Periodo");
        $result['periodo'] = $periodoEl ? $periodoEl->asText() : null;

        $indicadorEl = $rootXml->get("{{$nsRes}}IndicadorPaginacion");
        $result['indicadorPaginacion'] = $indicadorEl ? $indicadorEl->asText() : null;

        $resultadoEl = $rootXml->get("{{$nsRes}}ResultadoConsulta");
        $result['resultadoConsulta'] = $resultadoEl ? $resultadoEl->asText() : null;

        $result['registros'] = [];

        foreach ($rootXml->getAll("{{$nsRes}}RegistroRespuestaConsultaFactuSistemaFacturacion") as $regEl) {
            $registro = [];

            $idFacturaEl = $regEl->get("{{$nsRes}}IDFactura");
            if ($idFacturaEl) {
                $registro['idFactura'] = [
                    'idEmisor' => $this->getChildText($idFacturaEl, $nsInfo, 'IDEmisorFactura'),
                    'numSerie' => $this->getChildText($idFacturaEl, $nsInfo, 'NumSerieFactura'),
                    'fechaExpedicion' => $this->getChildText($idFacturaEl, $nsInfo, 'FechaExpedicionFactura'),
                ];
            }

            $datosEl = $regEl->get("{{$nsRes}}DatosRegistroFacturacion");
            if ($datosEl) {
                $registro['datos'] = [
                    'nombreRazonEmisor' => $this->getChildText($datosEl, $nsRes, 'NombreRazonEmisor'),
                    'refExterna' => $this->getChildText($datosEl, $nsRes, 'RefExterna'),
                    'tipoFactura' => $this->getChildText($datosEl, $nsRes, 'TipoFactura'),
                    'tipoRectificativa' => $this->getChildText($datosEl, $nsRes, 'TipoRectificativa'),
                    'fechaOperacion' => $this->getChildText($datosEl, $nsRes, 'FechaOperacion'),
                    'descripcionOperacion' => $this->getChildText($datosEl, $nsRes, 'DescripcionOperacion'),
                    'importeTotal' => $this->getChildText($datosEl, $nsRes, 'ImporteTotal'),
                    'cuotaTotal' => $this->getChildText($datosEl, $nsRes, 'CuotaTotal'),
                ];
                $subsanacionEl = $datosEl->get("{{$nsRes}}Subsanacion");
                $registro['datos']['subsanacion'] = $subsanacionEl ? $subsanacionEl->asText() : null;
                $rechazoEl = $datosEl->get("{{$nsRes}}RechazoPrevio");
                $registro['datos']['rechazoPrevio'] = $rechazoEl ? $rechazoEl->asText() : null;

                $encadenamientoEl = $datosEl->get("{{$nsRes}}Encadenamiento");
                if ($encadenamientoEl) {
                    $anteriorEl = $encadenamientoEl->get("{{$nsRes}}RegistroAnterior");
                    if ($anteriorEl) {
                        $registro['datos']['encadenamiento'] = [
                            'idEmisor' => $this->getChildText($anteriorEl, $nsInfo, 'IDEmisorFactura'),
                            'numSerie' => $this->getChildText($anteriorEl, $nsInfo, 'NumSerieFactura'),
                            'fechaExpedicion' => $this->getChildText($anteriorEl, $nsInfo, 'FechaExpedicionFactura'),
                            'huella' => $this->getChildText($anteriorEl, $nsInfo, 'Huella'),
                        ];
                    } else {
                        $primerEl = $encadenamientoEl->get("{{$nsRes}}PrimerRegistro");
                        $registro['datos']['encadenamiento'] = [
                            'primerRegistro' => $primerEl ? $primerEl->asText() : null,
                        ];
                    }
                }

                $destinatariosEl = $datosEl->get("{{$nsRes}}Destinatarios");
                if ($destinatariosEl) {
                    $clientes = [];
                    foreach ($destinatariosEl->getAll("{{$nsRes}}IDDestinatario") as $destEl) {
                        $cNombre = $destEl->get("{{$nsInfo}}NombreRazon");
                        $cNif = $destEl->get("{{$nsInfo}}NIF");
                        $clientes[] = [
                            'nombre' => $cNombre ? $cNombre->asText() : '',
                            'nif' => $cNif ? $cNif->asText() : '',
                        ];
                    }
                    $registro['datos']['clientes'] = $clientes;
                }

                $hashEl = $datosEl->get("{{$nsRes}}Huella");
                $registro['datos']['huella'] = $hashEl ? $hashEl->asText() : null;
            }

            $presentacionEl = $regEl->get("{{$nsRes}}DatosPresentacion");
            if ($presentacionEl) {
                $registro['presentacion'] = [
                    'nifPresentador' => $this->getChildText($presentacionEl, $nsInfo, 'NIFPresentador'),
                    'timestamp' => $this->getChildText($presentacionEl, $nsInfo, 'TimestampPresentacion'),
                    'idPeticion' => $this->getChildText($presentacionEl, $nsInfo, 'IdPeticion'),
                ];
            }

            $estadoEl = $regEl->get("{{$nsRes}}EstadoRegistro");
            if ($estadoEl) {
                $registro['estado'] = [
                    'timestamp' => $this->getChildText($estadoEl, $nsRes, 'TimestampUltimaModificacion'),
                    'estado' => $this->getChildText($estadoEl, $nsRes, 'EstadoRegistro'),
                    'codigoError' => $this->getChildText($estadoEl, $nsRes, 'CodigoErrorRegistro'),
                    'descripcionError' => $this->getChildText($estadoEl, $nsRes, 'DescripcionErrorRegistro'),
                ];
            }

            $result['registros'][] = $registro;
        }

        $claveEl = $rootXml->get("{{$nsRes}}ClavePaginacion");
        if ($claveEl) {
            $result['clavePaginacion'] = [
                'idEmisor' => $this->getChildText($claveEl, $nsInfo, 'IDEmisorFactura'),
                'numSerie' => $this->getChildText($claveEl, $nsInfo, 'NumSerieFactura'),
                'fechaExpedicion' => $this->getChildText($claveEl, $nsInfo, 'FechaExpedicionFactura'),
            ];
        }

        return $result;
    }

    private function getChildText(UXML $parent, string $ns, string $tag): ?string {
        $el = $parent->get("{{$ns}}{$tag}");
        return $el ? $el->asText() : null;
    }
}
