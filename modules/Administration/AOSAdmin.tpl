

<form id="ConfigureSettings" name="ConfigureSettings" enctype='multipart/form-data' method="POST"
      action="index.php?module=Administration&action=AOSAdmin&do=save">

    <span class='error'>{$error.main}</span>

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
                            <th width="20%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_NAME}</th>
                            <th width="30%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_FORMAT}</th>
                            <th width="15%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_INITIAL}</th>
                            <th width="25%" style="padding: 3px; text-align: left;">{$MOD.LBL_AOS_INVOICE_SERIES_EXAMPLE}</th>
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
                <!-- END STIC CUSTOM -->
            </td>
        </tr>
    </table>
    <!-- STIC CUSTOM - JCH - 20251203 - Invoice series configuration JS -->
    <script type="text/javascript">
    var invoiceSeriesLineNumber = 0;
    var existingSeries = [];
    
    {if isset($config.aos.invoices.series) && is_array($config.aos.invoices.series)}
        {foreach from=$config.aos.invoices.series key=name item=seriesData}
    existingSeries.push({ldelim} format: "{$seriesData.format}", initialNumber: "{$seriesData.initialNumber}", name: "{$name|escape:'javascript'}" {rdelim});
        {/foreach}
    {/if}
    
    // Localized strings
    var MOD_LBL_AOS_INVOICE_SERIES_NAME_PLACEHOLDER = "{$MOD.LBL_AOS_INVOICE_SERIES_NAME_PLACEHOLDER}";
    var MOD_LBL_AOS_INVOICE_SERIES_NAME_REQUIRED = "{$MOD.LBL_AOS_INVOICE_SERIES_NAME_REQUIRED}";
    var MOD_LBL_AOS_INVOICE_SERIES_FORMAT_PLACEHOLDER = "{$MOD.LBL_AOS_INVOICE_SERIES_FORMAT_PLACEHOLDER}";
    var MOD_LBL_AOS_INVOICE_SERIES_FORMAT_VALIDATION = "{$MOD.LBL_AOS_INVOICE_SERIES_FORMAT_VALIDATION}";
    var MOD_LBL_AOS_INVOICE_SERIES_INITIAL_VALIDATION = "{$MOD.LBL_AOS_INVOICE_SERIES_INITIAL_VALIDATION}";
    var MOD_LBL_AOS_INVOICE_SERIES_REMOVE = "{$MOD.LBL_AOS_INVOICE_SERIES_REMOVE}";
    
    {literal}
    function addInvoiceSeriesLine(format, initialNumber, name) {
        format = format || '';
        initialNumber = initialNumber || '1';
        name = name || '';
        
        var lineNum = invoiceSeriesLineNumber++;
        var tbody = document.getElementById('invoice_series_lines');
        var row = tbody.insertRow(-1);
        row.id = 'invoice_series_line_' + lineNum;
        
        // Name cell
        var cell1 = row.insertCell(0);
        cell1.style.padding = '2px';
        cell1.innerHTML = '<input type="text" name="invoice_series_name[' + lineNum + ']" ' +
                         'id="invoice_series_name_' + lineNum + '" ' +
                         'value="' + name + '" ' +
                         'style="width: 95%;" ' +
                         'maxlength="50" ' +
                         'required ' +
                         'placeholder="' + MOD_LBL_AOS_INVOICE_SERIES_NAME_PLACEHOLDER + '" ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_NAME_REQUIRED + '">' +
                         '<input type="hidden" name="invoice_series_original_name[' + lineNum + ']" ' +
                         'value="' + name + '">';
        
        // Format cell
        var cell2 = row.insertCell(1);
        cell2.style.padding = '2px';
        cell2.innerHTML = '<input type="text" name="invoice_series_format[' + lineNum + ']" ' +
                         'id="invoice_series_format_' + lineNum + '" ' +
                         'value="' + format + '" ' +
                         'style="width: 95%;" ' +
                         'placeholder="' + MOD_LBL_AOS_INVOICE_SERIES_FORMAT_PLACEHOLDER + '" ' +
                         'pattern="[A-Za-z0\\-/_ ]+" ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_FORMAT_VALIDATION + '" ' +
                         'onkeyup="updateInvoiceSeriesExample(' + lineNum + ')" ' +
                         'oninput="validateSeriesFormat(this)">';
        
        // Initial number cell
        var cell3 = row.insertCell(2);
        cell3.style.padding = '2px';
        cell3.innerHTML = '<input type="number" name="invoice_series_initial[' + lineNum + ']" ' +
                         'id="invoice_series_initial_' + lineNum + '" ' +
                         'value="' + initialNumber + '" ' +
                         'style="width: 95%;" ' +
                         'min="1" ' +
                         'required ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_INITIAL_VALIDATION + '" ' +
                         'onchange="updateInvoiceSeriesExample(' + lineNum + ')">';
        
        // Example cell
        var cell4 = row.insertCell(3);
        cell4.style.padding = '2px';
        cell4.innerHTML = '<span id="invoice_series_example_' + lineNum + '" style="font-family: monospace; color: #666;"></span>';
        
        // Action cell
        var cell5 = row.insertCell(4);
        cell5.style.textAlign = 'center';
        cell5.style.padding = '2px';
        cell5.innerHTML = '<button type="button" class="button suitepicon suitepicon-action-clear" onclick="removeInvoiceSeriesLine(' + lineNum + '); return false;" ' +
                         'title="' + MOD_LBL_AOS_INVOICE_SERIES_REMOVE + '">' +
                         '</button>';
        
        updateInvoiceSeriesExample(lineNum);
        updateInvoiceSeriesCount();
    }
    
    function removeInvoiceSeriesLine(lineNum) {
        var row = document.getElementById('invoice_series_line_' + lineNum);
        if (row) {
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
    
    // Load existing series on page load
    if (existingSeries.length > 0) {
        existingSeries.forEach(function(series) {
            addInvoiceSeriesLine(series.format, series.initialNumber, series.name);
        });
    } else {
        // Add one empty line by default
        addInvoiceSeriesLine();
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

    <div style="padding-top: 2px;">
        {$BUTTONS}
    </div>
    {$JAVASCRIPT}
</form>
