<?php
/**
 * Virtual field definition for Sinergia Data Analytics
 * 
 * This virtual field returns 1 if the relationship was active at any point 
 * four years ago, and 0 otherwise. It's designed to provide a quick way to 
 * filter or identify active relationships from four years ago in reports 
 * and analyses.
 */

// Label for the virtual field (can use an existing label in the module or any string)
$virtualFieldLabel = 'LBL_SDA_ACTIVE_CURRENT_YEAR_MINUS_4';

// Description of the virtual field (can use an existing label in the module or any string)
$virtualFieldDescription = 'LBL_SDA_ACTIVE_CURRENT_YEAR_MINUS_4_DESCRIPTION';

// Data type of the virtual field (text|numeric|date)
$virtualFieldType = 'text';

// Precision for numeric fields (0 for integer)
$virtualFieldPrecision = 0;

// Visibility flag (0 = visible to all, 1 = visible only to administrators in SDA)
$virtualFieldHidden = 0;

// SQL expression to calculate the virtual field
$virtualFieldExpression = "CASE 
    WHEN (start_date IS NULL OR start_date <= LAST_DAY(CONCAT(YEAR(CURDATE())-4,'-12-31'))) 
    AND (end_date IS NULL OR end_date >= CONCAT(YEAR(CURDATE())-4,'-01-01'))
    THEN 1
    ELSE 0
END";