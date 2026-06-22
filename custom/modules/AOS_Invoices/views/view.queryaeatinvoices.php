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

                    $rowClass = 'oddListRowS1';
                    foreach ($registros as $reg) {
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
                        $nifPresentador = htmlspecialchars($presentacion['nifPresentador'] ?? '');
                        $timestamp = htmlspecialchars($presentacion['timestamp'] ?? '');
                        $codigoError = htmlspecialchars($estado['codigoError'] ?? '');
                        $descError = htmlspecialchars($estado['descripcionError'] ?? '');

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

                        $rowClass = ($rowClass === 'oddListRowS1') ? 'evenListRowS1' : 'oddListRowS1';

                        echo '<tr class="' . $rowClass . '">';
                        echo '<td style="padding: 6px 8px;">' . $serieNum . '</td>';
                        echo '<td style="padding: 6px 8px; white-space: nowrap;">' . $fechaExp . '</td>';
                        echo '<td style="padding: 6px 8px;">' . $tipoLabel . '</td>';
                        echo '<td style="padding: 6px 8px; text-align: right;">' . $importeTotal . '</td>';
                        echo '<td style="padding: 6px 8px; max-width: 180px; overflow: hidden; text-overflow: ellipsis;">' . $nombreCliente . '</td>';
                        echo '<td style="padding: 6px 8px; white-space: nowrap;">' . $nifCliente . '</td>';
                        echo '<td style="padding: 6px 8px;">' . $estadoBadge . '</td>';
                        echo '<td style="padding: 6px 8px; white-space: nowrap; font-size: 11px;">' . $fechaCorta . '</td>';
                        $errorDisplay = '';
                        if (!empty($codigoError)) {
                            $errorDisplay = '[' . $codigoError . '] ' . $descError;
                        }
                        echo '<td style="padding: 6px 8px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; font-size: 11px;">' . htmlspecialchars($errorDisplay) . '</td>';
                        echo '</tr>';
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
