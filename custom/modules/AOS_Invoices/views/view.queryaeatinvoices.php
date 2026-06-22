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
        $filterBySif = !empty($_POST['filter_by_sif']);

        $result = $_SESSION['VERIFACTU_QUERY_RESULT'] ?? null;
        unset($_SESSION['VERIFACTU_QUERY_RESULT']);

        echo '<div class="moduleTitle" style="margin-bottom: 20px;">';
        echo '<h2>' . $mod_strings['LBL_VERIFACTU_QUERY_TITLE'] . '</h2>';
        echo '</div>';

        echo '<form name="VerifactuQueryForm" method="POST" action="index.php" style="background: #f8f8f8; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 20px;">';
        echo '<input type="hidden" name="module" value="AOS_Invoices">';
        echo '<input type="hidden" name="action" value="QueryAeatInvoices">';
        echo '<input type="hidden" name="query" value="1">';

        echo '<table width="100%" cellpadding="4" cellspacing="0" border="0">';

        echo '<tr>';
        echo '<td width="12%" style="font-weight: bold; white-space: nowrap;">' . $mod_strings['LBL_VERIFACTU_QUERY_YEAR'] . '</td>';
        echo '<td width="21%"><input type="number" name="year" value="' . htmlspecialchars($selectedYear) . '" min="2024" max="2099" style="width: 100px;" required></td>';
        echo '<td width="12%" style="font-weight: bold; white-space: nowrap;">' . $mod_strings['LBL_VERIFACTU_QUERY_PERIOD'] . '</td>';
        echo '<td width="21%">';
        echo '<select name="period" style="width: 100px;">';
        for ($m = 1; $m <= 12; $m++) {
            $val = str_pad($m, 2, '0', STR_PAD_LEFT);
            $selected = ($val === $selectedPeriod) ? ' selected' : '';
            echo '<option value="' . $val . '"' . $selected . '>' . $val . '</option>';
        }
        echo '</select></td>';
        echo '<td width="12%" style="font-weight: bold; white-space: nowrap;">' . $mod_strings['LBL_VERIFACTU_QUERY_SERIE_NUMBER'] . '</td>';
        echo '<td width="22%"><input type="text" name="serie_number" value="' . htmlspecialchars($serieNumber) . '" placeholder="' . $mod_strings['LBL_VERIFACTU_QUERY_SERIE_NUMBER_PLACEHOLDER'] . '" style="width: 200px;"></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td style="font-weight: bold; white-space: nowrap;">' . $mod_strings['LBL_VERIFACTU_QUERY_DATE_FROM'] . '</td>';
        echo '<td><input type="date" name="date_from" value="' . htmlspecialchars($dateFrom) . '" style="width: 180px;"></td>';
        echo '<td style="font-weight: bold; white-space: nowrap;">' . $mod_strings['LBL_VERIFACTU_QUERY_DATE_TO'] . '</td>';
        echo '<td><input type="date" name="date_to" value="' . htmlspecialchars($dateTo) . '" style="width: 180px;"></td>';
        echo '<td style="font-weight: bold; white-space: nowrap;">' . $mod_strings['LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF'] . '</td>';
        echo '<td><input type="text" name="counterparty_nif" value="' . htmlspecialchars($counterpartyNif) . '" placeholder="' . $mod_strings['LBL_VERIFACTU_QUERY_COUNTERPARTY_NIF_PLACEHOLDER'] . '" style="width: 150px;"></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td style="font-weight: bold; white-space: nowrap;">' . $mod_strings['LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME'] . '</td>';
        echo '<td colspan="5"><input type="text" name="counterparty_name" value="' . htmlspecialchars($counterpartyName) . '" placeholder="' . $mod_strings['LBL_VERIFACTU_QUERY_COUNTERPARTY_NAME_PLACEHOLDER'] . '" style="width: 300px;"></td>';
        echo '</tr>';
        echo '<input type="hidden" name="filter_by_sif" value="1">';

        echo '<tr>';
        echo '<td colspan="6" style="padding-top: 10px;">';
        $nestChecked = (!empty($_POST['query']) && !empty($_POST['nest_rectified'])) || empty($_POST['query']) ? ' checked' : '';
        echo '<label style="font-weight: normal; cursor: pointer;">';
        echo '<input type="checkbox" name="nest_rectified" value="1"' . $nestChecked . ' style="margin-right: 6px;"> ' . $mod_strings['LBL_VERIFACTU_QUERY_NEST_RECTIFIED'];
        echo '</label>';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td colspan="6" style="padding-top: 15px; text-align: center;">';
                        echo '<button type="submit" class="button primary" style="padding: 8px 30px; font-size: 14px; font-weight: bold; display: inline-flex; align-items: center; gap: 4px;">
                            <span class="suitepicon suitepicon-action-search"></span> ' . $mod_strings['LBL_VERIFACTU_QUERY_BUTTON'] . '
                        </button>';
                        echo '&nbsp;&nbsp;';
                        echo '<button type="button" class="button" onclick="window.location.href=\'index.php?module=AOS_Invoices&action=index\'" style="padding: 8px 20px; font-size: 14px; display: inline-flex; align-items: center;">
                            ' . $mod_strings['LBL_VERIFACTU_QUERY_CANCEL'] . '
                        </button>';
        echo '</td>';
        echo '</tr>';

        echo '</table>';
        echo '</form>';

        if ($result !== null) {
            echo '<div style="margin-bottom: 20px;">';
            if (!empty($result['success'])) {
                $data = $result['data'];
                $resultado = $data['resultadoConsulta'] ?? 'SinDatos';
                $registros = $data['registros'] ?? [];
                $totalRegistros = count($registros);
                $indicadorPag = $data['indicadorPaginacion'] ?? 'N';

                $statusClass = ($resultado === 'ConDatos') ? 'alert-success' : 'alert-info';
                echo '<div class="alert ' . $statusClass . '" style="margin: 10px 0; padding: 12px; border-left: 4px solid ' . (($resultado === 'ConDatos') ? '#3c763d' : '#31708f') . ';">';
                echo '<strong>' . $mod_strings['LBL_VERIFACTU_QUERY_RESULT'] . '</strong> ' . $resultado . ' (' . $totalRegistros . ' ' . $mod_strings['LBL_VERIFACTU_QUERY_REGISTROS'] . ')';
                if ($indicadorPag === 'S') {
                    echo ' &mdash; <em>' . $mod_strings['LBL_VERIFACTU_QUERY_PAGINATION'] . '</em>';
                }
                if (!empty($data['clavePaginacion'])) {
                    echo '<br><small>' . $mod_strings['LBL_VERIFACTU_QUERY_LAST_KEY'] . ' ' . htmlspecialchars($data['clavePaginacion']['numSerie'] ?? '') . '</small>';
                }
                echo '</div>';

                if (!empty($registros)) {
                    $db = DBManagerFactory::getInstance();
                    $numSeries = array_map(function ($r) use ($db) {
                        return $db->quoted($r['idFactura']['numSerie'] ?? '');
                    }, $registros);
                    $numSeries = array_filter($numSeries, function ($v) { return $v !== "''"; });
                    $invoiceMap = [];
                    if (!empty($numSeries)) {
                        $query = "SELECT id, number FROM aos_invoices WHERE number IN (" . implode(',', $numSeries) . ") AND deleted = 0";
                        $res = $db->query($query);
                        while ($row = $db->fetchByAssoc($res)) {
                            $invoiceMap[$row['number']] = $row['id'];
                        }
                    }

                    echo '<table class="list view table table-bordered" style="width: 100%;">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_SERIE'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_DATE'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_TYPE'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_AMOUNT'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_CLIENT'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_CLIENT_NIF'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_STATUS'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_PRESENTATION'] . '</th>';
                    echo '<th style="white-space: nowrap; padding: 8px;">' . $mod_strings['LBL_VERIFACTU_QUERY_HEADER_ERROR'] . '</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    $nestRectified = !empty($_POST['nest_rectified']);
                    $renderRegs = $registros;

                    if ($nestRectified) {
                        $byNumSerie = [];
                        foreach ($registros as $i => $r) {
                            $ns = $r['idFactura']['numSerie'] ?? '';
                            if ($ns !== '') {
                                $byNumSerie[$ns] = $i;
                            }
                        }

                        $children = [];
                        $childOfParentInSet = [];
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
                    }

                    $parentsWithChildren = [];
                    foreach ($registros as $i => $r) {
                        if (isset($children[$i])) {
                            $parentsWithChildren[$i] = true;
                        }
                    }

                    $rowClass = 'oddListRowS1';

                    $renderRow = function (array $reg, int $depth, bool $isParentRectified = false) use ($mod_strings, &$rowClass, $invoiceMap) {
                        $idFactura = $reg['idFactura'] ?? [];
                        $datos = $reg['datos'] ?? [];
                        $estado = $reg['estado'] ?? [];
                        $presentacion = $reg['presentacion'] ?? [];

                        $serieNum = htmlspecialchars($idFactura['numSerie'] ?? '');
                        $fechaExp = htmlspecialchars($idFactura['fechaExpedicion'] ?? '');
                        $tipoFactura = htmlspecialchars($datos['tipoFactura'] ?? '');
                        $importeTotal = htmlspecialchars($datos['importeTotal'] ?? '');
                        $estadoReg = htmlspecialchars($estado['estado'] ?? '');
                        $clientes = $datos['clientes'] ?? [];
                        $nombreCliente = htmlspecialchars($clientes[0]['nombre'] ?? '');
                        $nifCliente = htmlspecialchars($clientes[0]['nif'] ?? '');
                        $timestamp = htmlspecialchars($presentacion['timestamp'] ?? '');
                        $codigoError = htmlspecialchars($estado['codigoError'] ?? '');
                        $descError = htmlspecialchars($estado['descripcionError'] ?? '');

                        $isChild = $depth > 0;
                        $tipoLabel = match ($tipoFactura) {
                            'F1' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_F1'],
                            'F2' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_F2'],
                            'F3' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_F3'],
                            'R1', 'R2', 'R3', 'R4', 'R5' => $mod_strings['LBL_VERIFACTU_QUERY_TYPE_RECTIFYING'],
                            default => $tipoFactura,
                        };

                        $estadoBadge = match ($estadoReg) {
                            'Correcto' => '<span class="label label-success">' . $mod_strings['LBL_VERIFACTU_QUERY_STATUS_CORRECT'] . '</span>',
                            'AceptadoConErrores' => '<span class="label label-warning">' . $mod_strings['LBL_VERIFACTU_QUERY_STATUS_ACCEPTED_WITH_ERRORS'] . '</span>',
                            'Anulado' => '<span class="label label-danger">' . $mod_strings['LBL_VERIFACTU_QUERY_STATUS_CANCELLED'] . '</span>',
                            default => '<span class="label label-default">' . $estadoReg . '</span>',
                        };

                        $fechaCorta = '';
                        if (!empty($timestamp)) {
                            $dt = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:sP', $timestamp) ?: DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\.\0\0\0P', $timestamp);
                            if ($dt) {
                                $fechaCorta = $dt->format('d/m/Y H:i');
                            } else {
                                $fechaCorta = $timestamp;
                            }
                        }

                        if (!$isChild && !$isDimmed) {
                            $rowClass = ($rowClass === 'oddListRowS1') ? 'evenListRowS1' : 'oddListRowS1';
                        }

                        $isDimmed = !$isChild && $isParentRectified;
                        $seriePrefix = $isChild ? '<span style="margin-left: 20px;">↳ </span>' : '';
                        $tdPad = $isChild ? 'padding: 4px 8px;' : 'padding: 6px 8px;';
                        $rowStyle = '';
                        if ($isChild) {
                            $rowStyle = 'font-size: 11px; background-color: #fafafa;';
                        } elseif ($isDimmed) {
                            $rowStyle = 'opacity: 0.55;';
                        }

                        echo '<tr class="' . $rowClass . '" style="' . $rowStyle . '">';
                        $serieLink = $serieNum;
                        if (isset($invoiceMap[$serieNum])) {
                            $serieLink = '<a href="index.php?module=AOS_Invoices&action=DetailView&record=' . htmlspecialchars($invoiceMap[$serieNum]) . '">' . $serieNum . '</a>';
                        }
                        echo '<td style="' . $tdPad . '">' . $seriePrefix . $serieLink . '</td>';
                        echo '<td style="' . $tdPad . 'white-space: nowrap;">' . $fechaExp . '</td>';
                        echo '<td style="' . $tdPad . '">' . $tipoLabel . '</td>';
                        echo '<td style="' . $tdPad . 'text-align: right;">' . $importeTotal . '</td>';
                        echo '<td style="' . $tdPad . 'max-width: 180px; overflow: hidden; text-overflow: ellipsis;">' . $nombreCliente . '</td>';
                        echo '<td style="' . $tdPad . 'white-space: nowrap;">' . $nifCliente . '</td>';
                        echo '<td style="' . $tdPad . '">' . $estadoBadge . '</td>';
                        echo '<td style="' . $tdPad . 'white-space: nowrap; font-size: 11px;">' . $fechaCorta . '</td>';
                        $errorDisplay = '';
                        if (!empty($codigoError)) {
                            $errorDisplay = '[' . $codigoError . '] ' . $descError;
                        }
                        echo '<td style="' . $tdPad . 'max-width: 200px; overflow: hidden; text-overflow: ellipsis; font-size: 11px;">' . htmlspecialchars($errorDisplay) . '</td>';
                        echo '</tr>';
                    };

                    foreach ($renderRegs as $item) {
                        if ($nestRectified) {
                            $pRectified = $item['depth'] === 0 && isset($parentsWithChildren[$item['idx']]);
                            $renderRow($item['reg'], $item['depth'], $pRectified);
                        } else {
                            $renderRow($item, 0);
                        }
                    }

                    echo '</tbody>';
                    echo '</table>';
                }
            } else {
                echo '<div class="alert alert-danger" style="margin: 10px 0; padding: 12px; border-left: 4px solid #a94442; background-color: #f2dede;">';
                echo '<strong>' . $mod_strings['LBL_VERIFACTU_QUERY_ERROR_PREFIX'] . '</strong> ' . htmlspecialchars($result['message'] ?? 'Error desconocido');
                echo '</div>';
            }
            echo '</div>';
        }

        SticViews::display($this);
    }
}
