-- Test script for SticRunScripts 
-- This script should be executed when calling: ?file=SticUpdates/Languages/ca/TestSQLLanguageScript.sql

SELECT COUNT(*) FROM accounts where deleted = 1;
