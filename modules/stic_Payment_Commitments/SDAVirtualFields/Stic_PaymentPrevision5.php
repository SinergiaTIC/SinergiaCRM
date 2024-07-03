<?php
/**
 * Virtual field definition for Sinergia Data Analytics
 *
 * This virtual field extracts the fifth value from the expected_payments_detail string,
 * which represents the expected payment amount for the month after the next (current month + 5).
 * It's designed to provide quick access to upcoming monthly payment data in reports and analyses.
 */

// Label for the virtual field
$virtualFieldLabel = 'LBL_SDA_EXPECTED_PAYMENTS_MONTH_5';

// Description of the virtual field
$virtualFieldDescription = 'LBL_SDA_EXPECTED_PAYMENTS_DESCRIPTION_MONTH_5';

// Data type of the virtual field (text|numeric|date)
$virtualFieldType = 'numeric';

// Precision for numeric fields (2 for two decimal places)
$virtualFieldPrecision = 2;

// Visibility flag (0 = visible to all, 1 = visible only to administrators in SDA)
$virtualFieldHidden = 0;

// SQL expression to calculate the virtual field
$virtualFieldExpression = "SUBSTRING_INDEX(SUBSTRING_INDEX(expected_payments_detail, '|', 5), '|', -1)";
