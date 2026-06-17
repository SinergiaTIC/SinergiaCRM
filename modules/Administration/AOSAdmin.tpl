

<form id="ConfigureSettings" name="ConfigureSettings" enctype='multipart/form-data' method="POST"
      action="index.php?module=Administration&action=AOSAdmin&do=save">

    <span class='error'>{$error.main}</span>
    {if isset($validation_errors)}
    {$validation_errors}
    {/if}

    <table width="100%" cellpadding="0" cellspacing="1" border="0" class="actionsContainer">
        <tr>
            <td>
                {$BUTTONS}
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_AOS_ADMIN_CONTRACT_SETTINGS}</h4></th>
        <tr>
            <td  scope="row" width="200">{$MOD.LBL_AOS_ADMIN_CONTRACT_RENEWAL_REMINDER}: </td>
            <td  >
                <input type='number' size='10' name='aos_contracts_renewalReminderPeriod' value='{$config.aos.contracts.renewalReminderPeriod}' > <span>{$MOD.LBL_AOS_DAYS}</span>
            </td>
        </tr>
    </table>


    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_AOS_ADMIN_INVOICE_SETTINGS}</h4></th>
        </tr>
        <tr>
            <!-- STIC CUSTOM - JCH - 20251203 - Invoice series configuration UI -->
            <td colspan="4">
                <p style="margin: 10px 0;">
                    {$MOD.LBL_AOS_INVOICE_SERIES_DESCRIPTION}
                </p>
                
                <table id="invoice_series_table" width="100%" border="0" cellspacing="1" cellpadding="1" style="margin-top: 10px;">
                    <thead>
                        <tr style="background-color: #f0f0f0;">
                            <th width="18%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_NAME}</th>
                            <th width="10%" style="padding: 3px; text-align: center;">{$MOD.LBL_AOS_INVOICE_SERIES_RECTIFIED}</th>
                            <th width="27%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_FORMAT}</th>
                            <th width="13%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_INITIAL}</th>
                            <th width="22%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_EXAMPLE}</th>
                            <th width="80px" style="padding: 3px; text-align: center;">{$MOD.LBL_AOS_INVOICE_SERIES_ACTION}</th>
                        </tr>
                    </thead>
                    <tbody id="invoice_series_lines">
                        <!-- Lines will be inserted here dynamically -->
                    </tbody>
                </table>
                
                <button type="button" class="button suitepicon suitepicon-action-add" onclick="addInvoiceSeriesLine(); return false;" style="margin-top: 10px;">
                     {$MOD.LBL_AOS_INVOICE_SERIES_ADD}
                </button>
                
                <input type="hidden" id="invoice_series_count" value="0">
                <input type="hidden" name="invoice_series_rectified" id="invoice_series_rectified_hidden" value="">
                <!-- END STIC CUSTOM -->
            </td>
        </tr>
    </table>
    <!-- STIC CUSTOM - JCH - 20251203 - Invoice series configuration JS -->
    <script type="text/javascript">
    var invoiceSeriesLineNumber = 0;
    var existingSeries = [];
    
    // Series with accepted invoices (populated from PHP to block removal)
    var seriesWithInvoices = {if isset($series_with_invoices)}{$series_with_invoices|json_encode}{else}[]{/if};
    
    // Localized strings (must be before the success message block that uses them)
    var MOD_LBL_AOS_INVOICE_SERIES_NAME_PLACEHOLDER = "{$MOD.LBL_AOS_INVOICE_SERIES_NAME_PLACEHOLDER}";
    var MOD_LBL_AOS_INVOICE_SERIES_NAME_REQUIRED = "{$MOD.LBL_AOS_INVOICE_SERIES_NAME_REQUIRED}";
    var MOD_LBL_AOS_INVOICE_SERIES_FORMAT_PLACEHOLDER = "{$MOD.LBL_AOS_INVOICE_SERIES_FORMAT_PLACEHOLDER}";
    var MOD_LBL_AOS_INVOICE_SERIES_FORMAT_VALIDATION = "{$MOD.LBL_AOS_INVOICE_SERIES_FORMAT_VALIDATION}";
    var MOD_LBL_AOS_INVOICE_SERIES_INITIAL_VALIDATION = "{$MOD.LBL_AOS_INVOICE_SERIES_INITIAL_VALIDATION}";
    var MOD_LBL_AOS_INVOICE_SERIES_REMOVE = "{$MOD.LBL_AOS_INVOICE_SERIES_REMOVE}";
    var MOD_LBL_AOS_INVOICE_SERIES_REMOVE_CONFIRM = "{$MOD.LBL_AOS_INVOICE_SERIES_REMOVE_CONFIRM}";
    var MOD_LBL_AOS_INVOICE_SERIES_BLOCKED_TOOLTIP = "{$MOD.LBL_AOS_INVOICE_SERIES_BLOCKED_TOOLTIP}";
    var MOD_LBL_AOS_INVOICE_SERIES_REMOVE_BLOCKED = "{$MOD.LBL_AOS_INVOICE_SERIES_REMOVE_BLOCKED}";
    var MOD_LBL_AOS_INVOICE_SERIES_SAVED_SUCCESS = "{$MOD.LBL_AOS_INVOICE_SERIES_SAVED_SUCCESS}";
    
    // Check for success message from save - passed as PHP variable
    var saveSuccess = {if isset($smarty.get.saved) && $smarty.get.saved == 1}true{else}false{/if};
    {literal}
    if (saveSuccess) {
        // Remove query param to avoid showing on refresh
        var url = window.location.href.replace(/[?&]saved=1/, '');
        window.history.replaceState({}, '', url);
        
        // Create auto-dismiss success message
        var msg = document.createElement('div');
        msg.style.cssText = 'position:fixed;top:20px;right:20px;background:#28a745;color:#fff;padding:15px 25px;border-radius:4px;z-index:9999;font-weight:bold;box-shadow:0 2px 10px rgba(0,0,0,0.2);';
        msg.innerHTML = MOD_LBL_AOS_INVOICE_SERIES_SAVED_SUCCESS;
        document.body.appendChild(msg);
        setTimeout(function() { msg.style.opacity = '0'; setTimeout(function() { msg.remove(); }, 500); }, 3000);
    }
    {/literal}
    
    {if isset($config.aos.invoices.series) && is_array($config.aos.invoices.series)}
        {foreach from=$config.aos.invoices.series key=name item=seriesData}
    existingSeries.push({ldelim} format: "{$seriesData.format}", initialNumber: "{$seriesData.initialNumber}", name: "{$name|escape:'javascript'}", isRectified: {if isset($seriesData.isRectified) && $seriesData.isRectified}true{else}false{/if}, isNew: false {rdelim});
        {/foreach}
    {/if}
    
    {if isset($submitted_series) && is_array($submitted_series)}
    // On validation errors, restore ALL submitted data (existing + new rows)
    existingSeries = [];
        {foreach from=$submitted_series item=series}
    existingSeries.push({ldelim} format: "{$series.format}", initialNumber: "{$series.initialNumber}", name: "{$series.name|escape:'javascript'}", isRectified: {if $series.isRectified}true{else}false{/if}, isNew: {if $series.isNew}true{else}false{/if} {rdelim});
        {/foreach}
    {/if}
    
    {literal}
    function addInvoiceSeriesLine(format, initialNumber, name, isRectified, isNew) {
        format = format || '';
        initialNumber = initialNumber || '1';
        name = name || '';
        isRectified = isRectified || false;
        isNew = isNew || false;
        
        var lineNum = invoiceSeriesLineNumber++;
        var tbody = document.getElementById('invoice_series_lines');
        var row = tbody.insertRow(-1);
        row.id = 'invoice_series_line_' + lineNum;
        
        // Check if this series has invoices in current year - if so, disable it (skip for new series)
        var hasInvoices = !isNew && name && seriesWithInvoices.indexOf(name) !== -1;
        // Attenuated styles for blocked series (0.6 opacity, not-allowed cursor)
        var blockedStyle = hasInvoices ? 'background:#f5f5f5;color:#666;opacity:0.6;cursor:not-allowed;' : '';
        var disabledAttr = hasInvoices ? 'readonly="readonly"' : '';
        
        // Add tooltip to row if blocked
        if (hasInvoices) { row.title = MOD_LBL_AOS_INVOICE_SERIES_BLOCKED_TOOLTIP; }
        
        // Name cell
        var cell1 = row.insertCell(0);
        cell1.style.padding = '2px';
        cell1.innerHTML = '<input type="text" name="invoice_series_name[' + lineNum + ']" ' +
                         'id="invoice_series_name_' + lineNum + '" ' +
                         'value="' + name + '" ' +
                         'style="width: 95%;' + blockedStyle + '" ' +
                         'maxlength="50" ' +
                         'required ' +
                         disabledAttr + ' ' +
                         'placeholder="' + MOD_LBL_AOS_INVOICE_SERIES_NAME_PLACEHOLDER + '" ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_NAME_REQUIRED + '">' +
                         '<input type="hidden" name="invoice_series_original_name[' + lineNum + ']" ' +
                         'value="' + name + '">';
        
        // Rectified series cell
        var cell2 = row.insertCell(1);
        cell2.style.textAlign = 'center';
        cell2.style.padding = '2px';
        var cb = document.createElement('input');
        cb.type = 'checkbox';
        cb.checked = !!isRectified;
        if (hasInvoices) {
            cb.disabled = true;
            cb.title = MOD_LBL_AOS_INVOICE_SERIES_BLOCKED_TOOLTIP;
        } else {
            cb.className = 'rectified_checkbox';
            cb.dataset.line = lineNum;
            cb.title = '{/literal}{$MOD.LBL_AOS_INVOICE_SERIES_RECTIFIED_HELP}{literal}';
        }
        cell2.appendChild(cb);
        // Update hidden field on page load if this is the rectified series
        if (isRectified) {
            document.getElementById('invoice_series_rectified_hidden').value = lineNum;
        }
        
        // Format cell
        var cell3 = row.insertCell(2);
        cell3.style.padding = '2px';
        cell3.innerHTML = '<input type="text" name="invoice_series_format[' + lineNum + ']" ' +
                         'id="invoice_series_format_' + lineNum + '" ' +
                         'value="' + format + '" ' +
                         'style="width: 95%;' + blockedStyle + '" ' +
                         'required ' +
                         'pattern="[A-Za-z0\\-/_ ]+" ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_FORMAT_VALIDATION + '" ' +
                         disabledAttr + ' ' +
                         'onkeyup="updateInvoiceSeriesExample(' + lineNum + ')" ' +
                         'oninput="validateSeriesFormat(this)">';
        
        // Initial number cell
        var cell4 = row.insertCell(3);
        cell4.style.padding = '2px';
        cell4.innerHTML = '<input type="number" name="invoice_series_initial[' + lineNum + ']" ' +
                         'id="invoice_series_initial_' + lineNum + '" ' +
                         'value="' + initialNumber + '" ' +
                         'style="width: 95%;' + blockedStyle + '" ' +
                         'min="1" ' +
                         'required ' +
                         disabledAttr + ' ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_INITIAL_VALIDATION + '" ' +
                         'onchange="updateInvoiceSeriesExample(' + lineNum + ')">';
        
        // Example cell
        var cell5 = row.insertCell(4);
        cell5.style.padding = '2px';
        cell5.innerHTML = '<span id="invoice_series_example_' + lineNum + '" style="font-family: monospace; color: #666;"></span>';
        
        // Action cell
        var cell6 = row.insertCell(5);
        cell6.style.textAlign = 'center';
        cell6.style.padding = '2px';
        var blockedIcon = hasInvoices ? '<i class="inline-help glyphicon glyphicon-info-sign" style="color:#888;font-size:16px;cursor:help;"></i><span class="inline-help-content" style="display:none;">' + MOD_LBL_AOS_INVOICE_SERIES_BLOCKED_TOOLTIP + '</span>' : 
                         '<button type="button" class="button suitepicon suitepicon-action-clear" onclick="removeInvoiceSeriesLine(' + lineNum + '); return false;" ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_REMOVE + '">' +
                         '</button>';
        cell6.innerHTML = blockedIcon;
        
        updateInvoiceSeriesExample(lineNum);
        updateInvoiceSeriesCount();
    }
    
    function removeInvoiceSeriesLine(lineNum) {
        var row = document.getElementById('invoice_series_line_' + lineNum);
        if (row) {
            // Get the series name from the input
            var nameInput = row.querySelector('input[name^="invoice_series_name"]');
            var seriesName = nameInput ? nameInput.value : '';
            
            // Check if this series has accepted invoices
            if (seriesName && typeof seriesWithInvoices !== 'undefined' && seriesWithInvoices.indexOf(seriesName) !== -1) {
                alert(MOD_LBL_AOS_INVOICE_SERIES_REMOVE_BLOCKED);
                return false;
            }
            
            // Confirm before removing
            if (!confirm(MOD_LBL_AOS_INVOICE_SERIES_REMOVE_CONFIRM)) {
                return false;
            }
            
            row.parentNode.removeChild(row);
            updateInvoiceSeriesCount();
        }
    }
    
    function updateInvoiceSeriesExample(lineNum) {
        var formatInput = document.getElementById('invoice_series_format_' + lineNum);
        var initialInput = document.getElementById('invoice_series_initial_' + lineNum);
        var exampleSpan = document.getElementById('invoice_series_example_' + lineNum);
        
        if (!formatInput || !initialInput || !exampleSpan) return;
        
        var format = formatInput.value;
        var initial = parseInt(initialInput.value) || 1;
        
        if (format === '') {
            exampleSpan.textContent = '';
            return;
        }
        
        // Generate example
        var currentYear = new Date().getFullYear();
        var yearTwoDigits = currentYear.toString().substr(-2);
        
        // First, find and replace the numeric placeholder (0000, 000, 00, etc) in the original format
        var match = format.match(/(0+)/);
        var example = format;
        
        if (match) {
            var numericLength = match[0].length;
            var paddedNumber = initial.toString().padStart(numericLength, '0');
            // Replace only the first occurrence of the numeric pattern
            example = example.replace(match[0], paddedNumber);
        }
        
        // Then replace year patterns
        example = example.replace(/YYYY/g, currentYear);
        example = example.replace(/YY/g, yearTwoDigits);
        
        exampleSpan.textContent = example;
    }
    
    function updateInvoiceSeriesCount() {
        var tbody = document.getElementById('invoice_series_lines');
        var count = tbody.rows.length;
        document.getElementById('invoice_series_count').value = count;
    }
    
    function validateSeriesFormat(input) {
        var value = input.value;
        // Remove any digits 1-9 (keep only letters, 0, and symbols)
        var cleaned = value.replace(/[1-9]/g, '');
        
        if (cleaned !== value) {
            input.value = cleaned;
            input.style.borderColor = 'red';
            setTimeout(function() {
                input.style.borderColor = '';
            }, 1000);
        }
    }
    
    // Handle checkbox change for rectified series
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('rectified_checkbox')) {
            var lineNum = e.target.dataset.line;
            var hidden = document.getElementById('invoice_series_rectified_hidden');
            
            if (e.target.checked) {
                // Check this one -> uncheck all other non-blocked checkboxes
                document.querySelectorAll('.rectified_checkbox').forEach(function(cb) {
                    if (cb !== e.target) {
                        cb.checked = false;
                    }
                });
                hidden.value = lineNum;
            } else {
                // Unchecked -> check if there's a disabled rectified checkbox to fall back to
                var hasBlockedRectified = false;
                document.querySelectorAll('#invoice_series_lines input[type="checkbox"][disabled]').forEach(function(cb) {
                    if (cb.checked) {
                        hasBlockedRectified = true;
                        // Find the line number from the row
                        var row = cb.closest('tr');
                        var lineMatch = row.id.match(/invoice_series_line_(\d+)/);
                        if (lineMatch) {
                            hidden.value = lineMatch[1];
                        }
                    }
                });
                if (!hasBlockedRectified) {
                    // No fallback -> re-check (must have exactly one rectified)
                    e.target.checked = true;
                }
            }
        }
    });
    
    // Validate that exactly one series is marked as rectified
    function validateRectifiedSeries() {
        var tbody = document.getElementById('invoice_series_lines');
        var rowCount = tbody.rows.length;
        
        // If there are no series, don't validate
        if (rowCount === 0) {
            return true;
        }
        
        // Check if at least one checkbox is selected (disabled or enabled)
        var checkboxes = document.querySelectorAll('#invoice_series_lines input[type="checkbox"]');
        var isOneSelected = false;
        
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                isOneSelected = true;
                break;
            }
        }
        
        if (!isOneSelected) {
            alert('{/literal}{$MOD.LBL_AOS_INVOICE_SERIES_RECTIFIED_REQUIRED}{literal}');
            return false;
        }
        
        return true;
    }
    
    // Attach validation to form submit
    document.getElementById('ConfigureSettings').addEventListener('submit', function(e) {
        if (!validateRectifiedSeries()) {
            e.preventDefault();
            return false;
        }
    });
    
    // Load existing series on page load
    if (existingSeries.length > 0) {
        existingSeries.forEach(function(series) {
            addInvoiceSeriesLine(series.format, series.initialNumber, series.name, series.isRectified, series.isNew);
        });
    } else {
        // Add one empty line by default
        addInvoiceSeriesLine();
    }
    
    // Initialize qtip for inline-help elements after loading series
    if (typeof setInlineHelpQtip === 'function') {
        setInlineHelpQtip();
    }
    {/literal}
    </script>
    <!-- END STIC CUSTOM -->

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_AOS_ADMIN_QUOTE_SETTINGS}</h4></th>
        </tr>
        <tr>
            <td  scope="row" width="200">{$MOD.LBL_AOS_ADMIN_INITIAL_QUOTE_NUMBER}: </td>
            <td  >
                <input type='number' size='10' name='aos_quotes_initialNumber' value='{$config.aos.quotes.initialNumber}' >
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr><th align="left" scope="row" colspan="4"><h4>{$MOD.LBL_AOS_ADMIN_LINE_ITEM_SETTINGS}</h4></th>
        </tr>
        <tr>
            <td  scope="row" width="200">{$MOD.LBL_AOS_ADMIN_ENABLE_LINE_ITEM_GROUPS}: </td>
            {if isset($config.aos.lineItems.enableGroups) && $config.aos.lineItems.enableGroups != "true" }
                {assign var='lineItems_enableGroups' value=''}
            {else}
                {assign var='lineItems_enableGroups' value='CHECKED'}
            {/if}
            <td>
                <input type='hidden' name='aos_lineItems_enableGroups' value='false'>
                <input name='aos_lineItems_enableGroups'  type="checkbox" value="true" {$lineItems_enableGroups}>
            </td>

            <td  scope="row" width="200">{$MOD.LBL_AOS_ADMIN_ENABLE_LINE_ITEM_TOTAL_TAX}: </td>
            {if isset($config.aos.lineItems.totalTax) && $config.aos.lineItems.totalTax != "true" }
                {assign var='lineItems_totalTax' value=''}
            {else}
                {assign var='lineItems_totalTax' value='CHECKED'}
            {/if}
            <td>
                <input type='hidden' name='aos.lineItems.totalTax' value='false'>
                <input name='aos.lineItems.totalTax'  type="checkbox" value="true" {$lineItems_totalTax}>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <th align="left" scope="row" colspan="4">
                <h4>{$MOD.LBL_AOS_ADMIN_VERIFACTU_SETTINGS}</h4>
            </th>
        </tr>
        <tr>
            <td colspan="4" style="padding: 4px 8px 8px 8px; font-size:12px; color:#666;">
                {$MOD.LBL_AOS_ADMIN_VERIFACTU_HELP}
            </td>
        </tr>
        <tr>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_VENDOR_NIF}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_VENDOR_NIF}' size='15' maxlength='9' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_VENDOR_NIF_HELP}</em>
            </td>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_VENDOR_NAME}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_VENDOR_NAME}' size='40' maxlength='120' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_VENDOR_NAME_HELP}</em>
            </td>
        </tr>
        <tr>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_SYSTEM_NAME}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_SYSTEM_NAME}' size='30' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_SYSTEM_NAME_HELP}</em>
            </td>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_SYSTEM_ID}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_SYSTEM_ID}' size='10' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_SYSTEM_ID_HELP}</em>
            </td>
        </tr>
        <tr>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_SYSTEM_VERSION}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_SYSTEM_VERSION}' size='15' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_SYSTEM_VERSION_HELP}</em>
            </td>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_INSTALLATION_NUMBER}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_INSTALLATION_NUMBER}' size='25' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_INSTALLATION_NUMBER_HELP}</em>
            </td>
        </tr>
        <tr>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_ACTIVATED}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_ACTIVATED}' size='10' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_ACTIVATED_HELP}</em>
            </td>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_TEST_MODE}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_TEST_MODE}' size='10' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_TEST_MODE_HELP}</em>
            </td>
        </tr>
        <tr>
            <td scope="row" width="200">{$MOD.LBL_AOS_ADMIN_VERIFACTU_TAX_TYPE}: </td>
            <td>
                <input type='text' value='{$VERIFACTU_TAX_TYPE}' size='10' readonly style="background:#f5f5f5;border:1px solid #ddd;">
                <br><em style="font-size:11px;color:#888;">{$MOD.LBL_AOS_ADMIN_VERIFACTU_TAX_TYPE_HELP}</em>
            </td>
            <td scope="row" width="200"></td>
            <td></td>
        </tr>
    </table>

    <div style="padding-top: 2px;">
        {$BUTTONS}
    </div>
    {$JAVASCRIPT}
</form>
