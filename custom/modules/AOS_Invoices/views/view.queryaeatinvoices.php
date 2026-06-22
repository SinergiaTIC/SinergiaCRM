<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.list.php';
require_once 'SticInclude/Views.php';

class CustomAOS_InvoicesViewQueryAeatInvoices extends SugarView
{
    public function __construct()
    {
        parent::__construct();
    }

    public function preDisplay()
    {
        SticViews::preDisplay($this);
    }

    public function display()
    {
        global $mod_strings, $app_strings, $theme;

        if (empty($mod_strings)) {
            $mod_strings = return_module_language($GLOBALS['current_language'], 'AOS_Invoices');
        }

        $currentYear = date('Y');
        $currentMonth = date('m');

        $selectedYear = $_POST['year'] ?? $currentYear;
        $selectedPeriod = $_POST['period'] ?? $currentMonth;
        $serieNumber = $_POST['serie_number'] ?? '';
        $dateFrom = $_POST['date_from'] ?? '';
        $dateTo = $_POST['date_to'] ?? '';
        $counterpartyNif = $_POST['counterparty_nif'] ?? '';
        $counterpartyName = $_POST['counterparty_name'] ?? '';

        $this->ss->assign('FORM_YEAR', $selectedYear);

        $periodOptions = [];
        for ($m = 1; $m <= 12; $m++) {
            $val = str_pad($m, 2, '0', STR_PAD_LEFT);
            $periodOptions[] = [
                'VALUE' => $val,
                'LABEL' => $val,
                'SELECTED' => ($val === $selectedPeriod),
            ];
        }
        $this->ss->assign('FORM_PERIOD_OPTIONS', $periodOptions);

        $this->ss->assign('FORM_SERIE_NUMBER', htmlspecialchars($serieNumber));
        $this->ss->assign('FORM_DATE_FROM', htmlspecialchars($dateFrom));
        $this->ss->assign('FORM_DATE_TO', htmlspecialchars($dateTo));
        $this->ss->assign('FORM_COUNTERPARTY_NIF', htmlspecialchars($counterpartyNif));
        $this->ss->assign('FORM_COUNTERPARTY_NAME', htmlspecialchars($counterpartyName));

        $hasPostback = !empty($_POST['query']);
        $nestChecked = (!$hasPostback) || (!empty($_POST['nest_rectified']));
        $this->ss->assign('FORM_NEST_CHECKED', $nestChecked);

        $result = $_SESSION['VERIFACTU_QUERY_RESULT'] ?? null;
        $this->ss->assign('HAS_RESULT', $result !== null);

        if ($result !== null) {
            if (!empty($result['success'])) {
                $this->ss->assign('RESULT_SUCCESS', true);

                $data = $result['data'];
                $resultado = $data['resultadoConsulta'] ?? 'SinDatos';
                $registros = $data['registros'] ?? [];
                $totalRegistros = count($registros);
                $indicadorPag = $data['indicadorPaginacion'] ?? 'N';

                $isConDatos = ($resultado === 'ConDatos');
                $this->ss->assign('RESULT_STATUS_CLASS', $isConDatos ? 'alert-success' : 'alert-info');
                $this->ss->assign('RESULT_STATUS_COLOR', $isConDatos ? '#3c763d' : '#31708f');
                $this->ss->assign('RESULT_OUTCOME', $resultado);
                $this->ss->assign('RESULT_COUNT', $totalRegistros);
                $this->ss->assign('RESULT_HAS_MORE', ($indicadorPag === 'S'));

                $clavePag = !empty($data['clavePaginacion']) ? htmlspecialchars($data['clavePaginacion']['numSerie'] ?? '') : '';
                $this->ss->assign('RESULT_LAST_KEY', $clavePag);

                if (!empty($registros)) {
                    $nestRectified = !empty($_POST['nest_rectified']);

                    $db = DBManagerFactory::getInstance();
                    $numSerieValues = array_map(function ($r) use ($db) {
                        return $db->quoted($r['idFactura']['numSerie'] ?? '');
                    }, $registros);
                    $numSerieValues = array_filter($numSerieValues, function ($v) {
                        return $v !== "''";
                    });
                    $invoiceMap = [];
                    if (!empty($numSerieValues)) {
                        $query = "SELECT id, number FROM aos_invoices WHERE number IN (" . implode(',', $numSerieValues) . ") AND deleted = 0";
                        $res = $db->query($query);
                        while ($row = $db->fetchByAssoc($res)) {
                            $invoiceMap[$row['number']] = $row['id'];
                        }
                    }

                    $byNumSerie = [];
                    foreach ($registros as $i => $r) {
                        $ns = $r['idFactura']['numSerie'] ?? '';
                        if ($ns !== '') {
                            $byNumSerie[$ns] = $i;
                        }
                    }

                    $children = [];
                    $childOfParentInSet = [];
                    if ($nestRectified) {
                        foreach ($registros as $i => $r) {
                            $tipo = $r['datos']['tipoFactura'] ?? '';
                            $isRectificativa = in_array($tipo, ['R1', 'R2', 'R3', 'R4', 'R5'], true);
                            if (!$isRectificativa) {
                                continue;
                            }
                            $parentNs = $r['datos']['facturaRectificada']['numSerie']
                                ?? $r['datos']['encadenamiento']['numSerie']
                                ?? null;
                            if ($parentNs !== null && isset($byNumSerie[$parentNs])) {
                                $pIdx = $byNumSerie[$parentNs];
                                $children[$pIdx][] = $i;
                                $childOfParentInSet[$i] = true;
                            }
                        }
                    }

                    $renderRegs = [];
                    foreach ($registros as $i => $r) {
                        if (isset($childOfParentInSet[$i])) {
                            continue;
                        }
                        $renderRegs[] = ['reg' => $r, 'depth' => 0, 'idx' => $i];
                        if (isset($children[$i])) {
                            foreach ($children[$i] as $cIdx) {
                                $renderRegs[] = ['reg' => $registros[$cIdx], 'depth' => 1, 'idx' => $cIdx];
                            }
                        }
                    }

                    $parentsWithChildren = [];
                    foreach ($registros as $i => $r) {
                        if (isset($children[$i])) {
                            $parentsWithChildren[$i] = true;
                        }
                    }

                    $tableRows = [];
                    $rowClass = 'oddListRowS1';

                    foreach ($renderRegs as $item) {
                        if ($nestRectified) {
                            $reg = $item['reg'];
                            $depth = $item['depth'];
                            $idx = $item['idx'];
                            $isParentRectified = $depth === 0 && isset($parentsWithChildren[$idx]);
                        } else {
                            $reg = $item;
                            $depth = 0;
                            $isParentRectified = false;
                        }

                        $idFactura = $reg['idFactura'] ?? [];
                        $datos = $reg['datos'] ?? [];
                        $estado = $reg['estado'] ?? [];
                        $presentacion = $reg['presentacion'] ?? [];

                        $serieNum = htmlspecialchars($idFactura['numSerie'] ?? '');
                        $issueDate = htmlspecialchars($idFactura['fechaExpedicion'] ?? '');
                        $invoiceTypeRaw = $datos['tipoFactura'] ?? '';
                        $invoiceType = htmlspecialchars($invoiceTypeRaw);
                        $totalAmount = htmlspecialchars($datos['importeTotal'] ?? '');
                        $statusReg = htmlspecialchars($estado['estado'] ?? '');
                        $clientList = $datos['clientes'] ?? [];
                        $clientName = htmlspecialchars($clientList[0]['nombre'] ?? '');
                        $clientNif = htmlspecialchars($clientList[0]['nif'] ?? '');
                        $timestamp = htmlspecialchars($presentacion['timestamp'] ?? '');
                        $errorCode = htmlspecialchars($estado['codigoError'] ?? '');
                        $errorDesc = htmlspecialchars($estado['descripcionError'] ?? '');

                        $isChild = $depth > 0;
                        $isCancelled = $statusReg === 'Anulado';
                        $isDimmed = !$isChild && $isParentRectified;

                        $typeLabel = match ($invoiceTypeRaw) {
                            'F1' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_F1'],
                            'F2' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_F2'],
                            'F3' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_F3'],
                            'R1', 'R2', 'R3', 'R4', 'R5' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_RECTIFYING'],
                            default => $invoiceType,
                        };

                        $statusBadge = match ($statusReg) {
                            'Correcto' => '<span class="label label-success">' . $mod_strings['LBL_VERIFACTU_QUERY_STATUS_CORRECT'] . '</span>',
                            'AceptadoConErrores' => '<span class="label label-warning">' . $mod_strings['LBL_VERIFACTU_QUERY_STATUS_ACCEPTED_WITH_ERRORS'] . '</span>',
                            'Anulado' => '<span class="label label-danger label-important" style="background-color: #d9534f; color: #fff; border-color: #d43f3a;">' . $mod_strings['LBL_VERIFACTU_QUERY_STATUS_CANCELLED'] . '</span>',
                            default => '<span class="label label-default">' . $statusReg . '</span>',
                        };

                        $presentationDate = '';
                        if (!empty($timestamp)) {
                            $dt = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:sP', $timestamp)
                                ?: DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\.\0\0\0P', $timestamp);
                            if ($dt) {
                                $presentationDate = $dt->format('d/m/Y H:i');
                            } else {
                                $presentationDate = $timestamp;
                            }
                        }

                        $errorDisplay = '';
                        if (!empty($errorCode)) {
                            $errorDisplay = '[' . $errorCode . '] ' . $errorDesc;
                        }

                        if (isset($invoiceMap[$serieNum])) {
                            $serieLink = '<a href="index.php?module=AOS_Invoices&action=DetailView&record=' . htmlspecialchars($invoiceMap[$serieNum]) . '" title="' . htmlspecialchars($mod_strings['LBL_VERIFACTU_QUERY_LINK_TOOLTIP']) . '">' . $serieNum . '</a>';
                        } else {
                            $serieLink = '<span title="' . htmlspecialchars($mod_strings['LBL_VERIFACTU_QUERY_NO_LINK_TOOLTIP']) . '" style="cursor: help;">' . $serieNum . '</span>';
                        }

                        if (!$isChild && !$isDimmed) {
                            $rowClass = ($rowClass === 'oddListRowS1') ? 'evenListRowS1' : 'oddListRowS1';
                        }

                        $seriePrefix = $isChild ? '<span style="margin-left: 20px;">↳ </span>' : '';
                        $tdPad = $isChild ? 'padding: 4px 8px;' : 'padding: 6px 8px;';
                        $rowStyle = '';
                        if ($isCancelled) {
                            $rowStyle = 'background-color: #f2dede;';
                        } elseif ($isChild) {
                            $rowStyle = 'font-size: 11px; background-color: #fafafa;';
                        } elseif ($isDimmed) {
                            $rowStyle = 'opacity: 0.55;';
                        }

                        $tableRows[] = [
                            'SERIE_PREFIX' => $seriePrefix,
                            'SERIE_LINK' => $serieLink,
                            'ISSUE_DATE' => $issueDate,
                            'TYPE_LABEL' => $typeLabel,
                            'TOTAL_AMOUNT' => $totalAmount,
                            'CLIENT_NAME' => $clientName,
                            'CLIENT_NIF' => $clientNif,
                            'STATUS_BADGE' => $statusBadge,
                            'PRESENTATION_DATE' => $presentationDate,
                            'ERROR' => htmlspecialchars($errorDisplay),
                            'ROW_CLASS' => $rowClass,
                            'TD_PAD' => $tdPad,
                            'ROW_STYLE' => $rowStyle,
                        ];
                    }

                    $this->ss->assign('HAS_ROWS', true);
                    $this->ss->assign('TABLE_ROWS', $tableRows);
                } else {
                    $this->ss->assign('HAS_ROWS', false);
                }
            } else {
                $this->ss->assign('RESULT_SUCCESS', false);
                $this->ss->assign('RESULT_ERROR_MSG', htmlspecialchars($result['message'] ?? 'Unknown error'));
            }
        }

        echo $this->ss->fetch('custom/modules/AOS_Invoices/tpls/QueryAeatInvoices.tpl');
        SticViews::display($this);
    }
}
